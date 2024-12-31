<?php

namespace App\Http\Controllers\Admin;

use App\Models\Guia;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use App\Models\DetalleBitacora;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;

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
}
