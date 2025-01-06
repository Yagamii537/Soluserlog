<?php

namespace App\Http\Controllers\Admin;

use App\Models\Conductor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ConductorController extends Controller
{
    public function index()
    {
        $conductores = Conductor::all();
        return view('admin.conductores.index', compact('conductores'));
    }

    public function create()
    {
        return view('admin.conductores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'numero_licencia' => 'required|string|max:255|unique:conductores,numero_licencia',
            'telefono' => 'required|string|max:15',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $data = $request->all();

        // Manejar la subida de la foto
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('conductores', 'public');
        }

        Conductor::create($data);

        return redirect()->route('admin.conductores.index')
            ->with('success', 'Conductor creado exitosamente.');
    }

    public function edit(Conductor $conductore)
    {
        $conductor = $conductore;
        return view('admin.conductores.edit', compact('conductor'));
    }

    public function update(Request $request, Conductor $conductore)
    {
        $conductor = $conductore;
        $request->validate([
            'nombre' => 'required|string|max:255',
            'numero_licencia' => 'required|string|max:255|unique:conductores,numero_licencia,' . $conductor->id,
            'telefono' => 'required|string|max:15',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        // Si se proporciona una nueva foto
        if ($request->hasFile('foto')) {
            // Elimina la foto anterior si existe
            if ($conductor->foto) {
                $oldImagePath = storage_path('app/public/' . $conductor->foto);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Sube la nueva foto
            $newPhotoPath = $request->file('foto')->store('conductores', 'public');
            $conductor->foto = $newPhotoPath;
        }

        // Actualiza los demás campos
        $conductor->update([
            'nombre' => $request->nombre,
            'numero_licencia' => $request->numero_licencia,
            'telefono' => $request->telefono,
            'foto' => $conductor->foto ?? $conductor->getOriginal('foto'),
        ]);

        return redirect()->route('admin.conductores.index')
            ->with('success', 'Conductor actualizado exitosamente.');
    }

    public function destroy(Conductor $conductore)
    {
        $conductor = $conductore;
        if ($conductor->foto) {
            $absolutePath = storage_path('app/public/' . $conductor->foto);
            if (file_exists($absolutePath)) {
                unlink($absolutePath);
            } else {
                Log::info('La imagen no existe en la ruta absoluta: ' . $absolutePath);
            }
        }

        $conductor->delete();

        return redirect()->route('admin.conductores.index')
            ->with('success', 'Conductor eliminado exitosamente.');
    }
}
