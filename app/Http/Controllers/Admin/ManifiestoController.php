<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manifiesto;
use App\Models\Camion;
use App\Models\Order; // Importamos el modelo Pedido
use Illuminate\Http\Request;

class ManifiestoController extends Controller
{
    // Mostrar la lista de manifiestos
    public function index()
    {
        $manifiestos = Manifiesto::with(['camion', 'order'])->get(); // Incluimos las relaciones
        return view('admin.manifiestos.index', compact('manifiestos'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        $camiones = Camion::all();
        // Solo mostramos pedidos confirmados (estado = 1)
        $ordersConfirmados = Order::where('estado', 1)->get();

        return view('admin.manifiestos.create', compact('camiones', 'ordersConfirmados'));
    }

    // Guardar un nuevo manifiesto
    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'camion_id' => 'required|exists:camiones,id',
            'order_id' => 'required|exists:orders,id', // Validamos que el pedido confirmado exista
            'descripcion' => 'nullable|string|max:255',
        ]);

        Manifiesto::create($request->all());

        return redirect()->route('admin.manifiestos.index')
            ->with('success', 'Manifiesto creado exitosamente.');
    }

    // Mostrar formulario de edición
    public function edit($id)
    {
        $manifiesto = Manifiesto::findOrFail($id);
        $camiones = Camion::all();
        $ordersConfirmados = Order::where('estado', 1)->get();

        return view('admin.manifiestos.edit', compact('manifiesto', 'camiones', 'ordersConfirmados'));
    }

    // Actualizar un manifiesto
    public function update(Request $request, Manifiesto $manifiesto)
    {

        $request->validate([
            'fecha' => 'required|date',
            'camion_id' => 'required|exists:camiones,id',
            'order_id' => 'required|exists:orders,id',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $manifiesto->update($request->all());

        return redirect()->route('admin.manifiestos.index')
            ->with('success', 'Manifiesto actualizado exitosamente.');
    }

    public function destroy(Manifiesto $manifiesto)
    {

        $manifiesto->delete();

        return redirect()->route('admin.manifiestos.index')
            ->with('success', 'Conductor eliminado exitosamente.');
    }
}
