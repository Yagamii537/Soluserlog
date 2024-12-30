<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ayudante;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class AyudanteController extends Controller
{
    public function index()
    {
        $ayudantes = Ayudante::all();
        return view('admin.ayudantes.index', compact('ayudantes'));
    }

    public function create()
    {
        return view('admin.ayudantes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'cedula' => 'required|string|max:10|unique:ayudantes,cedula',
            'telefono' => 'required|string|max:15',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048', // Validación de la foto
        ]);

        $data = $request->all();

        // Manejar la subida de la foto
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('ayudantes', 'public'); // Guarda la foto en `storage/app/public/ayudantes`
        }

        Ayudante::create($data);

        return redirect()->route('admin.ayudantes.index')
            ->with('success', 'Ayudante creado exitosamente.');
    }


    public function edit(Ayudante $ayudante)
    {
        return view('admin.ayudantes.edit', compact('ayudante'));
    }

    public function update(Request $request, Ayudante $ayudante)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'cedula' => 'required|string|max:10|unique:ayudantes,cedula,' . $ayudante->id,
            'telefono' => 'required|string|max:15',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048', // Validación de la foto
        ]);

        // Si se proporciona una nueva foto
        if ($request->hasFile('foto')) {
            // Elimina la foto anterior si existe
            if ($ayudante->foto) {
                $oldImagePath = storage_path('app/public/' . $ayudante->foto);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Sube la nueva foto
            $newPhotoPath = $request->file('foto')->store('ayudantes', 'public');
            $ayudante->foto = $newPhotoPath;
        }

        // Actualiza los demás campos
        $ayudante->update([
            'nombre' => $request->nombre,
            'cedula' => $request->cedula,
            'telefono' => $request->telefono,
            'foto' => $ayudante->foto ?? $ayudante->getOriginal('foto'), // Mantener la foto si no fue reemplazada
        ]);

        return redirect()->route('admin.ayudantes.index')
            ->with('success', 'Ayudante actualizado exitosamente.');
    }


    public function destroy(Ayudante $ayudante)
    {
        if ($ayudante->foto) {
            $absolutePath = storage_path('app/public/' . $ayudante->foto);
            if (file_exists($absolutePath)) {
                unlink($absolutePath);
            } else {
                Log::info('La imagen no existe en la ruta absoluta: ' . $absolutePath);
            }
        }

        $ayudante->delete();

        return redirect()->route('admin.ayudantes.index')
            ->with('success', 'Ayudante eliminado exitosamente.');
    }
}
