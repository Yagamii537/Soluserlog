<?php

namespace App\Http\Controllers\Admin;

use App\Models\Conductor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ConductorController extends Controller
{
    // Mostrar todos los conductores
    public function index()
    {
        $conductores = Conductor::all();
        return view('admin.conductores.index', compact('conductores'));
    }

    // Mostrar el formulario para crear un nuevo conductor
    public function create()
    {
        return view('admin.conductores.create');
    }

    // Guardar un nuevo conductor en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'numero_licencia' => 'required|string|max:255|unique:conductores',
            'telefono' => 'required|string|max:20',
        ]);

        Conductor::create($request->all());

        return redirect()->route('admin.conductores.index')
            ->with('success', 'Conductor creado exitosamente.');
    }

    // Mostrar el formulario para editar un conductor existente
    public function edit(Conductor $conductore)
    {
        //$conductor = Conductor::findOrFail($id);
        $conductor = $conductore;
        return view('admin.conductores.edit', compact('conductor'));
    }

    // Actualizar la información de un conductor existente
    public function update(Request $request, Conductor $conductore)
    {
        //$conductor = Conductor::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'numero_licencia' => 'required|string|max:255|unique:conductores,numero_licencia,' . $conductore->id,
            'telefono' => 'required|string|max:20',
        ]);

        $conductore->update($request->all());

        return redirect()->route('admin.conductores.index')
            ->with('success', 'Conductor actualizado exitosamente.');
    }

    // Eliminar un conductor de la base de datos
    public function destroy(Conductor $conductore)
    {

        $conductore->delete();

        return redirect()->route('admin.conductores.index')
            ->with('success', 'Conductor eliminado exitosamente.');
    }
}
