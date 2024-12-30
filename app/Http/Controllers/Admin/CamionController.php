<?php

namespace App\Http\Controllers\Admin;

use App\Models\Camion;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CamionController extends Controller
{
    // Mostrar todos los camiones
    public function index()
    {
        $camiones = Camion::all();
        return view('admin.camiones.index', compact('camiones'));
    }

    // Mostrar formulario de creación de camiones
    public function create()
    {

        return view('admin.camiones.create');
    }

    // Guardar un nuevo camión
    public function store(Request $request)
    {
        $request->validate([
            'numero_placa' => 'required|unique:camiones',
            'modelo' => 'required',
            'marca' => 'required',
            'capacidad_carga' => 'required|integer',
        ]);

        // Crear el camión
        Camion::create($request->all());
        return redirect()->route('admin.camiones.index');
    }

    // Mostrar formulario de edición de camión
    public function edit(Camion $camione)
    {

        $camion = $camione;

        return view('admin.camiones.edit', compact('camion'));
    }


    // Actualizar un camión existente
    public function update(Request $request, Camion $camione)
    {;
        //$camion = Camion::findOrFail($id);
        $camione->update($request->all());

        return redirect()->route('admin.camiones.index')
            ->with('success', 'Camión actualizado exitosamente.');
    }

    // Eliminar un camión
    public function destroy(Camion $camione)
    {
        // Camion::findOrFail($camione)->delete();

        // return redirect()->route('camiones.index')
        //                  ->with('success', 'Camión eliminado exitosamente.');


        $camione->delete();
        return redirect()->route('admin.camiones.index')->with('info', 'El camion se elimino correctamente');
    }
}
