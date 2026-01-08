<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\DetalleBitacoraCheck;
use Illuminate\Database\Eloquent\Builder;

class IndicadoresBitacoraController extends Controller
{
    public function index(Request $request)
    {
        // Opciones que cuentan como "con novedad"
        $opcionesCarga = [
            'INTEGRIDAD DEL EMPAQUE',
            'ETIQUETA NO DESPRENDIBLE',
            'EMBALAJE CON CINTA DE LA EMPRESA CONTRATANTE',
            'ETIQUETA DE IDENTIFICACION DEL PRODUCTO CLIENTE DESTINO',
        ];

        $opcionesDestino = [
            'DOCUMENTACION INFORMACION CLIENTE',
            'PRODUCTO CALIDAD CLIENTE',
            'TIEMPO OPERADOR',
            'SERVICIO OPERADOR',
            'DOCUMENTACION INFORMACION OPERADOR',
            'TIEMPO CLIENTE',
        ];

        // =========================
        // NUEVO: filtro por fechas
        // =========================
        $startInput = $request->get('start_date');
        $endInput   = $request->get('end_date');

        if ($startInput && $endInput) {
            // Usamos el rango enviado
            $startDate = Carbon::parse($startInput)->startOfDay();
            $endDate   = Carbon::parse($endInput)->endOfDay();

            // Mantener visual semanal: tomamos la semana donde cae start_date (L–D)
            $monday = (clone $startDate)->startOfWeek(Carbon::MONDAY)->startOfDay();
            $sunday = (clone $monday)->endOfWeek(Carbon::SUNDAY)->endOfDay();

            // Pero para la consulta usamos el rango exacto del filtro
            $queryStart = $startDate;
            $queryEnd   = $endDate;

            $rangeLabel = Carbon::parse($startInput)->format('d/m') . ' – ' . Carbon::parse($endInput)->format('d/m');
        } else {
            // Comportamiento actual (sin filtro): semana actual
            $monday = Carbon::now()->startOfWeek(Carbon::MONDAY)->startOfDay();
            $sunday = Carbon::now()->endOfWeek(Carbon::SUNDAY)->endOfDay();

            $queryStart = $monday;
            $queryEnd   = $sunday;

            $rangeLabel = $monday->format('d/m') . ' – ' . $sunday->format('d/m');
            $startInput = $monday->toDateString(); // para que el input se muestre con valor
            $endInput   = $sunday->toDateString();
        }

        // Trae conteo por día: bitácoras únicas con al menos un check de novedad en el rango
        $rows = DetalleBitacoraCheck::query()
            ->selectRaw('DATE(created_at) as fecha, COUNT(DISTINCT detalle_bitacora_id) as total')
            ->whereBetween('created_at', [$queryStart, $queryEnd])
            ->where(function (Builder $q) use ($opcionesCarga, $opcionesDestino) {
                $q->where(function ($w) use ($opcionesCarga) {
                    $w->where('tipo', 'carga')->whereIn('opcion', $opcionesCarga);
                })->orWhere(function ($w) use ($opcionesDestino) {
                    $w->where('tipo', 'destino')->whereIn('opcion', $opcionesDestino);
                });
            })
            ->groupByRaw('DATE(created_at)')
            ->orderBy('fecha')
            ->get()
            ->keyBy('fecha');

        // Construcción L a D (misma estructura que ya tienes)
        $labels = ['L', 'M', 'X', 'J', 'V', 'S', 'D'];
        $dates  = [];
        $data   = [];

        for ($i = 0; $i < 7; $i++) {
            $day = (clone $monday)->addDays($i);
            $key = $day->toDateString();
            $dates[] = $key;

            // Si hay filtro, solo se contabiliza si el día cae dentro del rango exacto
            if ($startInput && $endInput) {
                $inRange = $day->betweenIncluded(Carbon::parse($startInput)->startOfDay(), Carbon::parse($endInput)->endOfDay());
                $data[] = ($inRange && isset($rows[$key])) ? (int)$rows[$key]->total : 0;
            } else {
                $data[] = isset($rows[$key]) ? (int)$rows[$key]->total : 0;
            }
        }

        return view('admin.indicadores_bitacora.index', [
            'labels'     => $labels,
            'dates'      => $dates,
            'data'       => $data,
            'rangeLabel' => $rangeLabel,
            // NUEVO: valores para el formulario de filtro
            'startDate'  => $startInput,
            'endDate'    => $endInput,
        ]);
    }

    /**
     * Devuelve (HTML) la tabla de pedidos con novedad para una fecha dada (YYYY-MM-DD).
     * Se usa vía AJAX desde la gráfica.
     */
    public function dia(Request $request)
    {
        $request->validate([
            'fecha' => ['required', 'date']
        ]);
        $fecha = $request->get('fecha');

        $opcionesCarga = [
            'INTEGRIDAD DEL EMPAQUE',
            'ETIQUETA NO DESPRENDIBLE',
            'EMBALAJE CON CINTA DE LA EMPRESA CONTRATANTE',
            'ETIQUETA DE IDENTIFICACION DEL PRODUCTO CLIENTE DESTINO',
        ];
        $opcionesDestino = [
            'DOCUMENTACION INFORMACION CLIENTE',
            'PRODUCTO CALIDAD CLIENTE',
            'TIEMPO OPERADOR',
            'SERVICIO OPERADOR',
            'DOCUMENTACION INFORMACION OPERADOR',
            'TIEMPO CLIENTE',
        ];

        // Trae TODOS los checks de ese día que sean "novedad"
        $checks = DetalleBitacoraCheck::with([
            'detalleBitacora.order.documents',
            'detalleBitacora.order.direccionDestinatario'
        ])
            ->whereDate('created_at', $fecha)
            ->where(function (Builder $q) use ($opcionesCarga, $opcionesDestino) {
                $q->where(function ($w) use ($opcionesCarga) {
                    $w->where('tipo', 'carga')->whereIn('opcion', $opcionesCarga);
                })->orWhere(function ($w) use ($opcionesDestino) {
                    $w->where('tipo', 'destino')->whereIn('opcion', $opcionesDestino);
                });
            })
            ->get();


        $porPedido = $checks->groupBy(function ($c) {
            return optional(optional($c->detalleBitacora)->order)->id;
        })->filter(function ($grupo, $orderId) {
            return !is_null($orderId);
        })->map(function ($grupo) {
            $order = $grupo->first()->detalleBitacora->order;

            // Bitácoras únicas encontradas para este pedido en la fecha
            $bitacoraIds = $grupo->map(function ($c) {
                // puede venir como bitacora_id en la tabla detalle_bitacoras
                return optional($c->detalleBitacora)->bitacora_id
                    ?? optional(optional($c->detalleBitacora)->bitacora)->id;
            })->filter()->unique()->values()->all();

            // Lista de novedades únicas "TIPO: OPCION"
            $novedades = $grupo->map(function ($c) {
                return strtoupper($c->tipo) . ': ' . $c->opcion;
            })->unique()->values()->all();

            return [
                'order'        => $order,
                'bitacora_ids' => $bitacoraIds, // ← pasamos todos los ids de bitácora
                'novedades'    => $novedades,
            ];
        })->values();


        // Renderizamos una vista parcial con la tabla y la devolvemos (para inyectar en la página)
        return response()->view('admin.indicadores_bitacora._tabla_dia', [
            'fecha'    => $fecha,
            'porPedido' => $porPedido,
        ]);
    }
}
