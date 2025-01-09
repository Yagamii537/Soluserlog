<?php

namespace App\Http\Controllers\Admin;



use App\Models\Order;
use App\Models\Bitacora;
use Illuminate\Http\Request;
use App\Models\DetalleBitacora;
use App\Http\Controllers\Controller;
use App\Models\DetalleBitacoraImage;
use Illuminate\Support\Facades\Storage;

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
                'fechaOrigen' => null,
                'fechaDestino' => null,
                'persona' => null,
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

        // Lógica para establecer las fechas si no están definidas
        if ($request->filled('hora_origen_llegada') && !$detalle->fechaOrigen) {
            $detalle->fechaOrigen = now()->toDateString(); // Fecha actual del sistema
        }

        if ($request->filled('hora_destino_llegada') && !$detalle->fechaDestino) {
            $detalle->fechaDestino = now()->toDateString(); // Fecha actual del sistema
        }

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
            'persona',
        ]));



        // Manejar la subida de imágenes
        if ($request->hasFile('imagenes')) {

            foreach ($request->file('imagenes') as $imagen) {
                $path = $imagen->store('detalle_bitacoras', 'public');
                $detalle->images()->create(['image_path' => $path]);
            }
        }


        // Redireccionar a la vista de seleccionar detalles
        return redirect()->route('admin.bitacoras.seleccionarDetalles', $bitacoraId)
            ->with('success', 'Detalle de bitácora actualizado exitosamente.');
    }

    public function deleteImage($imageId)
    {
        // Buscar la imagen
        $image = DetalleBitacoraImage::findOrFail($imageId);

        // Eliminar el archivo físico del almacenamiento
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        // Eliminar el registro de la base de datos
        $image->delete();

        return response()->json(['success' => 'Imagen eliminada correctamente.']);
    }
}
