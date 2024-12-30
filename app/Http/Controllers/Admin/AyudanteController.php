<?php

namespace App\Http\Controllers\Admin;

use App\Models\Ayudante;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        ]);

        Ayudante::create($request->all());

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
            'cedula' => "required|string|max:10|unique:ayudantes,cedula,{$ayudante->id}",
            'telefono' => 'required|string|max:15',
        ]);

        $ayudante->update($request->all());

        return redirect()->route('admin.ayudantes.index')
            ->with('success', 'Ayudante actualizado exitosamente.');
    }

    public function destroy(Ayudante $ayudante)
    {
        $ayudante->delete();

        return redirect()->route('admin.ayudantes.index')
            ->with('success', 'Ayudante eliminado exitosamente.');
    }
}
