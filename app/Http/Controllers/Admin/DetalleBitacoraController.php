<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use App\Models\DetalleBitacora;
use App\Http\Controllers\Controller;

class DetalleBitacoraController extends Controller
{
    public function edit($bitacoraId, $orderId)
    {
        // Buscar la bitácora
        $bitacora = Bitacora::findOrFail($bitacoraId);

        // Buscar el pedido con sus relaciones necesarias
        $order = Order::findOrFail($orderId);

        // Buscar o crear un detalle de bitácora asociado a la bitácora y el pedido
        $detalle = DetalleBitacora::firstOrCreate(
            [
                'bitacora_id' => $bitacoraId,
                'order_id' => $orderId,
            ],
            [
                'hora_origen_llegada' => null,
                'temperatura_origen' => null,
                'humedad_origen' => null,
                'hora_carga' => null,
                'hora_salida_origen' => null,
                'novedades_carga' => null,
                'temperatura_destino' => null,
                'humedad_destino' => null,
                'hora_destino_llegada' => null,
                'hora_descarga' => null,
                'hora_salida_destino' => null,
                'novedades_destino' => null,
                'firma_recepcion' => null,
                'foto' => null,
            ]
        );

        return view('admin.detalle_bitacoras.edit', compact('detalle', 'order', 'bitacora'));
    }


    public function update(Request $request, $bitacoraId, $orderId)
    {



        // Buscar el detalle de la bitácora
        $detalle = DetalleBitacora::where('bitacora_id', $bitacoraId)
            ->where('order_id', $orderId)
            ->firstOrFail();

        // Actualizar solo los campos enviados
        $detalle->update($request->only([
            'hora_origen_llegada',
            'temperatura_origen',
            'humedad_origen',
            'hora_carga',
            'hora_salida_origen',
            'novedades_carga',
            'temperatura_destino',
            'humedad_destino',
            'hora_destino_llegada',
            'hora_descarga',
            'hora_salida_destino',
            'novedades_destino',
            'firma_recepcion',
        ]));

        // Redireccionar a la vista de seleccionar detalles
        return redirect()->route('admin.bitacoras.seleccionarDetalles', $bitacoraId)
            ->with('success', 'Detalle de bitácora actualizado exitosamente.');
    }
}
