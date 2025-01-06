<?php

namespace App\Http\Controllers\Admin;

use App\Models\Camion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CamionController extends Controller
{
    public function index()
    {
        $camiones = Camion::all();
        return view('admin.camiones.index', compact('camiones'));
    }

    public function create()
    {
        return view('admin.camiones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero_placa' => 'required|string|max:255|unique:camiones,numero_placa',
            'modelo' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'capacidad_carga' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $data = $request->all();

        // Manejar la subida de la foto
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('camiones', 'public');
        }

        Camion::create($data);

        return redirect()->route('admin.camiones.index')
            ->with('success', 'Camión creado exitosamente.');
    }

    public function edit(Camion $camione)
    {
        $camion = $camione;
        return view('admin.camiones.edit', compact('camion'));
    }

    public function update(Request $request, Camion $camione)
    {
        $camion = $camione;
        $request->validate([
            'numero_placa' => 'required|string|max:255|unique:camiones,numero_placa,' . $camion->id,
            'modelo' => 'required|string|max:255',
            'marca' => 'required|string|max:255',
            'capacidad_carga' => 'required|integer|min:0',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // Si se proporciona una nueva foto
        if ($request->hasFile('foto')) {
            // Elimina la foto anterior si existe
            if ($camion->foto) {
                $oldImagePath = storage_path('app/public/' . $camion->foto);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Sube la nueva foto
            $newPhotoPath = $request->file('foto')->store('camiones', 'public');
            $camion->foto = $newPhotoPath;
        }

        // Actualiza los demás campos
        $camion->update([
            'numero_placa' => $request->numero_placa,
            'modelo' => $request->modelo,
            'marca' => $request->marca,
            'capacidad_carga' => $request->capacidad_carga,
            'foto' => $camion->foto ?? $camion->getOriginal('foto'),
        ]);

        return redirect()->route('admin.camiones.index')
            ->with('success', 'Camión actualizado exitosamente.');
    }

    public function destroy(Camion $camione)
    {
        $camion = $camione;
        if ($camion->foto) {
            $absolutePath = storage_path('app/public/' . $camion->foto);
            if (file_exists($absolutePath)) {
                unlink($absolutePath);
            } else {
                Log::info('La imagen no existe en la ruta absoluta: ' . $absolutePath);
            }
        }

        $camion->delete();

        return redirect()->route('admin.camiones.index')
            ->with('success', 'Camión eliminado exitosamente.');
    }
}
