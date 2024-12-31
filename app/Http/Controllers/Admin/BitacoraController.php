<?php

namespace App\Http\Controllers\Admin;

use App\Models\Guia;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use App\Models\DetalleBitacora;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class BitacoraController extends Controller
{
    public function index()
    {
        $bitacoras = Bitacora::with('guia')->get();
        return view('admin.bitacoras.index', compact('bitacoras'));
    }

    public function seleccionarDetalles($bitacoraId)
    {
        $bitacora = Bitacora::with('guia.manifiesto.orders')->findOrFail($bitacoraId);

        return view('admin.bitacoras.seleccionar_detalles', compact('bitacora'));
    }




    public function updateDetalles(Request $request, $bitacoraId)
    {
        $bitacora = Bitacora::findOrFail($bitacoraId);

        foreach ($request->input('detalles', []) as $detalleId => $detalleData) {
            $detalle = DetalleBitacora::find($detalleId);
            if ($detalle) {
                $detalle->update([
                    'hora_origen_llegada' => $detalleData['hora_origen_llegada'] ?? null,
                    'temperatura_origen' => $detalleData['temperatura_origen'] ?? null,
                    'humedad_origen' => $detalleData['humedad_origen'] ?? null,
                    'novedades_carga' => $detalleData['novedades_carga'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.bitacoras.index')
            ->with('success', 'Detalles de bitácora actualizados exitosamente.');
    }


    public function show(Bitacora $bitacora)
    {
        $detalles = $bitacora->detalles()->with('order')->get();
        return view('admin.bitacoras.show', compact('bitacora', 'detalles'));
    }

    public function store(Request $request, Guia $guia)
    {
        $bitacora = Bitacora::create(['guia_id' => $guia->id]);

        foreach ($guia->manifiesto->orders as $order) {
            DetalleBitacora::create([
                'bitacora_id' => $bitacora->id,
                'order_id' => $order->id,
            ]);
        }

        return redirect()->route('admin.bitacoras.index')
            ->with('success', 'Bitácora creada exitosamente.');
    }

    public function generatePdf($bitacoraId)
    {
        $bitacora = Bitacora::findOrFail($bitacoraId);

        // Ruta completa del logo
        $logoPath = public_path('vendor/adminlte/dist/img/logof.png'); // Ruta absoluta al logo

        $pdf = PDF::loadView('admin.bitacoras.pdf', compact('bitacora', 'logoPath'));
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream('bitacora_' . $bitacora->id . '.pdf');
    }




    public function showMapa($bitacoraId)
    {
        $bitacora = Bitacora::findOrFail($bitacoraId);
        $puntos = [];

        foreach ($bitacora->detalles as $detalle) {
            // Origen (remitente)
            if ($detalle->order->direccionRemitente) {
                $puntos[] = [
                    'latitud' => $detalle->order->direccionRemitente->latitud,
                    'longitud' => $detalle->order->direccionRemitente->longitud,
                    'tipo' => 'origen',
                    'razon' => $detalle->order->direccionRemitente->cliente->razonSocial,
                ];
            }

            // Destino (destinatario)
            if ($detalle->order->direccionDestinatario) {
                $puntos[] = [
                    'latitud' => $detalle->order->direccionDestinatario->latitud,
                    'longitud' => $detalle->order->direccionDestinatario->longitud,
                    'tipo' => 'destino',
                    'razon' => $detalle->order->direccionDestinatario->cliente->razonSocial,
                ];
            }
        }

        // Optimizar los puntos antes de enviarlos a la vista
        $puntosOptimizados = $this->optimizeRoute($puntos);

        // Retornar la vista del mapa con los puntos optimizados
        return view('admin.bitacoras.mapa', compact('bitacora', 'puntosOptimizados'));
    }

    // Método para optimizar la ruta usando OSRM
    private function optimizeRoute($puntos)
    {
        $coordinates = array_map(function ($punto) {
            return "{$punto['longitud']},{$punto['latitud']}";
        }, $puntos);

        $serviceUrl = 'https://router.project-osrm.org/trip/v1/driving/';
        $url = $serviceUrl . implode(';', $coordinates) . '?overview=false';

        $response = Http::get($url);

        if ($response->ok()) {
            $optimizedOrder = $response->json()['waypoints'];
            $orderedPuntos = array_map(function ($waypoint) use ($puntos) {
                return $puntos[$waypoint['waypoint_index']];
            }, $optimizedOrder);

            return $orderedPuntos;
        }

        return $puntos; // En caso de error, devuelve los puntos originales
    }
}
