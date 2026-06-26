<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Manifiesto;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class IndicadoresBBraunController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->get('end_date', now()->endOfMonth()->toDateString());

        $novedadFiltro   = $request->get('novedad_destino');
        $tipoFleteFiltro = $request->get('tipo_flete');
        $tonelajeFiltro  = $request->get('tonelaje');

        $inicio = Carbon::parse($startDate)->startOfDay();
        $fin    = Carbon::parse($endDate)->endOfDay();

        $novedadesFiltro = collect([
            'DOCUMENTACION INFORMACION CLIENTE',
            'PRODUCTO CALIDAD CLIENTE',
            'TIEMPO OPERADOR',
            'SERVICIO OPERADOR',
            'DOCUMENTACION INFORMACION OPERADOR',
            'TIEMPO CLIENTE',
            'ENTREGA SIN NOVEDAD',
        ]);

        $tonelajesKg = [
            '1'  => [1, '1', 1000, '1000'],
            '3'  => [3, '3', 3000, '3000'],
            '5'  => [5, '5', 5000, '5000'],
            '10' => [10, '10', 10000, '10000'],
        ];

        $ordersQuery = Order::with([
            'documents',
            'direccionDestinatario.cliente',
            'manifiestos.camion',
            'manifiestos.conductor',
            'manifiestos.guias.bitacora.detalles.checks',
        ])
            ->whereBetween('fechaEntrega', [$inicio, $fin])
            ->when($novedadFiltro, function ($q) use ($novedadFiltro) {
                $q->whereHas('manifiestos.guias.bitacora.detalles.checks', function ($c) use ($novedadFiltro) {
                    $c->where('tipo', 'destino')
                        ->where('opcion', $novedadFiltro);
                });
            })
            ->when($tipoFleteFiltro, function ($q) use ($tipoFleteFiltro) {
                $q->whereHas('manifiestos', function ($m) use ($tipoFleteFiltro) {
                    $m->where('tipoFlete', $tipoFleteFiltro);
                });
            })
            ->when($tonelajeFiltro, function ($q) use ($tonelajeFiltro, $tonelajesKg) {
                $valores = $tonelajesKg[$tonelajeFiltro] ?? [$tonelajeFiltro];

                $q->whereHas('manifiestos.camion', function ($c) use ($valores) {
                    $c->whereIn('capacidad_carga', $valores);
                });
            });

        $orders = (clone $ordersQuery)->get();

        $totalSolicitudes = $orders->count();

        $totalFacturas = $orders->sum(function ($order) {
            return $order->documents->whereNotNull('factura')->count();
        });

        $facturasEntregadas = $totalFacturas;

        $totalBultos = $orders->sum(function ($order) {
            return $order->documents->sum('cantidad_bultos');
        });

        $totalKg = $orders->sum(function ($order) {
            return $order->documents->sum('cantidad_kg');
        });

        $clientesUnicos = $orders
            ->map(fn($o) => optional(optional($o->direccionDestinatario)->cliente)->id)
            ->filter()
            ->unique()
            ->count();

        $ciudadesUnicas = $orders
            ->map(fn($o) => optional($o->direccionDestinatario)->ciudad)
            ->filter()
            ->unique()
            ->count();

        $novedades = $orders->flatMap(function ($order) {
            return $order->manifiestos
                ->flatMap->guias
                ->pluck('bitacora')
                ->filter()
                ->flatMap->detalles
                ->where('order_id', $order->id)
                ->flatMap(function ($detalle) {
                    return $detalle->checks
                        ->where('tipo', 'destino')
                        ->pluck('opcion');
                });
        })
            ->countBy()
            ->map(function ($total, $novedad) {
                return (object)[
                    'novedad' => $novedad === 'ENTREGA SIN NOVEDAD' ? 'S/N' : $novedad,
                    'total'   => $total,
                ];
            })
            ->sortByDesc('total')
            ->values();

        $clientes = $orders
            ->groupBy(fn($o) => optional(optional($o->direccionDestinatario)->cliente)->razonSocial ?? 'N/A')
            ->map(fn($items, $cliente) => (object)[
                'cliente' => $cliente,
                'total'   => $items->sum(fn($o) => $o->documents->whereNotNull('factura')->count()),
            ])
            ->sortByDesc('total')
            ->take(10)
            ->values();

        $destinos = $orders
            ->groupBy(fn($o) => optional($o->direccionDestinatario)->ciudad ?? 'N/A')
            ->map(fn($items, $destino) => (object)[
                'destino' => $destino,
                'total'   => $items->count(),
            ])
            ->sortByDesc('total')
            ->values();

        $conductores = $orders
            ->flatMap(fn($o) => $o->manifiestos)
            ->groupBy(fn($m) => optional($m->conductor)->nombre ?? 'N/A')
            ->map(fn($items, $conductor) => (object)[
                'conductor' => $conductor,
                'total'     => $items->count(),
            ])
            ->sortByDesc('total')
            ->values();

        $camiones = $orders
            ->flatMap(fn($o) => $o->manifiestos)
            ->groupBy(function ($m) {
                $camion = optional($m->camion);
                return trim(($camion->numero_placa ?? 'N/A') . ' - ' . ($camion->marca ?? ''));
            })
            ->map(fn($items, $camion) => (object)[
                'camion' => $camion,
                'total'  => $items->count(),
            ])
            ->sortByDesc('total')
            ->values();

        $fletesGrafico = $orders
            ->flatMap(fn($o) => $o->manifiestos)
            ->groupBy(fn($m) => $m->tipoFlete ?? 'Sin dato')
            ->map(fn($items, $flete) => (object)[
                'flete' => $flete,
                'total' => $items->count(),
            ])
            ->values();

        $tonelajesGrafico = $orders
            ->flatMap(fn($o) => $o->manifiestos)
            ->groupBy(function ($m) {
                $kg = optional($m->camion)->capacidad_carga;

                if (in_array($kg, [1, '1', 1000, '1000'])) return '1T';
                if (in_array($kg, [3, '3', 3000, '3000'])) return '3T';
                if (in_array($kg, [5, '5', 5000, '5000'])) return '5T';
                if (in_array($kg, [10, '10', 10000, '10000'])) return '10T';

                return 'Sin dato';
            })
            ->map(fn($items, $tonelaje) => (object)[
                'tonelaje' => $tonelaje,
                'total'    => $items->count(),
            ])
            ->values();

        $bultosRangos = $orders
            ->map(function ($order) {
                $b = (int) $order->documents->sum('cantidad_bultos');

                if ($b <= 10) return '0 - 10';
                if ($b <= 20) return '11 - 20';
                if ($b <= 50) return '21 - 50';
                if ($b <= 100) return '51 - 100';

                return '100+';
            })
            ->countBy()
            ->map(fn($total, $rango) => (object)[
                'rango' => $rango,
                'total' => $total,
            ])
            ->values();

        $kgRangos = $orders
            ->map(function ($order) {
                $kg = (float) $order->documents->sum('cantidad_kg');

                if ($kg <= 50) return '0 - 50 kg';
                if ($kg <= 100) return '51 - 100 kg';
                if ($kg <= 300) return '101 - 300 kg';
                if ($kg <= 500) return '301 - 500 kg';

                return '500+ kg';
            })
            ->countBy()
            ->map(fn($total, $rango) => (object)[
                'rango' => $rango,
                'total' => $total,
            ])
            ->values();

        $diasLabels = [
            0 => 'Domingo',
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
        ];

        $entregasDias = collect($diasLabels)->map(function ($dia, $num) use ($orders) {
            return (object)[
                'dia' => $dia,
                'total' => $orders->filter(function ($order) use ($num) {
                    return Carbon::parse($order->fechaEntrega)->dayOfWeek === $num;
                })->count(),
            ];
        })->values();

        $entregasPorHora = $orders->flatMap(function ($order) {
            return $order->manifiestos
                ->flatMap->guias
                ->pluck('bitacora')
                ->filter()
                ->flatMap->detalles
                ->where('order_id', $order->id)
                ->map(function ($detalle) {
                    return $detalle->hora_descarga
                        ? substr($detalle->hora_descarga, 0, 2) . ':00'
                        : null;
                })
                ->filter();
        })
            ->countBy()
            ->map(fn($total, $hora) => (object)[
                'hora'  => $hora,
                'total' => $total,
            ])
            ->sortBy('hora')
            ->values();

        $fletesFiltro = Manifiesto::whereNotNull('tipoFlete')
            ->where('tipoFlete', '<>', '')
            ->distinct()
            ->orderBy('tipoFlete')
            ->pluck('tipoFlete');

        $tabla = (clone $ordersQuery)
            ->orderBy('fechaEntrega', 'desc')
            ->paginate(15)
            ->appends($request->query());

        return view('admin.indicadores_bbraun.index', compact(
            'startDate',
            'endDate',
            'novedadFiltro',
            'tipoFleteFiltro',
            'tonelajeFiltro',
            'novedadesFiltro',
            'fletesFiltro',
            'totalSolicitudes',
            'totalFacturas',
            'facturasEntregadas',
            'totalBultos',
            'totalKg',
            'clientesUnicos',
            'ciudadesUnicas',
            'novedades',
            'clientes',
            'destinos',
            'conductores',
            'camiones',
            'fletesGrafico',
            'tonelajesGrafico',
            'bultosRangos',
            'kgRangos',
            'entregasDias',
            'entregasPorHora',
            'tabla'
        ));
    }


    public function pdf(Request $request)
    {
        $startDate = $request->start_date;
        $endDate   = $request->end_date;

        $novedadFiltro   = $request->novedad_destino;
        $tipoFleteFiltro = $request->tipo_flete;
        $tonelajeFiltro  = $request->tonelaje;

        $inicio = \Carbon\Carbon::parse($startDate)->startOfDay();
        $fin    = \Carbon\Carbon::parse($endDate)->endOfDay();

        $tonelajesKg = [
            '1'  => [1, '1', 1000, '1000'],
            '3'  => [3, '3', 3000, '3000'],
            '5'  => [5, '5', 5000, '5000'],
            '10' => [10, '10', 10000, '10000'],
        ];

        $tabla = \App\Models\Order::with([
            'documents',
            'direccionDestinatario.cliente',
            'manifiestos.camion',
            'manifiestos.conductor',
            'manifiestos.guias.bitacora.detalles.checks',
        ])
            ->whereBetween('fechaEntrega', [$inicio, $fin])
            ->when($novedadFiltro, function ($q) use ($novedadFiltro) {
                $q->whereHas('manifiestos.guias.bitacora.detalles.checks', function ($c) use ($novedadFiltro) {
                    $c->where('tipo', 'destino')
                        ->where('opcion', $novedadFiltro);
                });
            })
            ->when($tipoFleteFiltro, function ($q) use ($tipoFleteFiltro) {
                $q->whereHas('manifiestos', function ($m) use ($tipoFleteFiltro) {
                    $m->where('tipoFlete', $tipoFleteFiltro);
                });
            })
            ->when($tonelajeFiltro, function ($q) use ($tonelajeFiltro, $tonelajesKg) {
                $valores = $tonelajesKg[$tonelajeFiltro] ?? [$tonelajeFiltro];

                $q->whereHas('manifiestos.camion', function ($c) use ($valores) {
                    $c->whereIn('capacidad_carga', $valores);
                });
            })
            ->orderBy('fechaEntrega', 'desc')
            ->get();

        $data = [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'novedadFiltro' => $novedadFiltro,
            'tipoFleteFiltro' => $tipoFleteFiltro,
            'tonelajeFiltro' => $tonelajeFiltro,

            'chartNovedades' => $request->chartNovedades,
            'chartClientes' => $request->chartClientes,
            'chartDestinos' => $request->chartDestinos,
            'chartConductores' => $request->chartConductores,
            'chartCamiones' => $request->chartCamiones,
            'chartFlete' => $request->chartFlete,
            'chartTonelaje' => $request->chartTonelaje,
            'chartBultos' => $request->chartBultos,
            'chartKg' => $request->chartKg,
            'chartDias' => $request->chartDias,
            'chartHoras' => $request->chartHoras,
            'tabla' => $tabla,
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.indicadores_bbraun.pdf', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->stream('indicadores_bbraun.pdf');
    }
}
