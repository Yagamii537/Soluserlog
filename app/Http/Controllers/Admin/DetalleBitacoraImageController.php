<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DetalleBitacoraImage;
use Illuminate\Support\Facades\Storage;

class DetalleBitacoraImageController extends Controller
{
    public function destroy($detalleBitacoraId, $imageId)
    {
        // Buscar la imagen
        $image = DetalleBitacoraImage::where('detalle_bitacora_id', $detalleBitacoraId)->findOrFail($imageId);

        // Eliminar el archivo de almacenamiento
        if (Storage::exists('public/' . $image->path)) {
            Storage::delete('public/' . $image->path);
        }

        // Eliminar el registro de la base de datos
        $image->delete();

        return redirect()->back()->with('success', 'Imagen eliminada correctamente.');
    }
}
