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
    $manifiestos = Manifiesto::with(['camion', 'orders'])->get(); // Incluimos las relaciones

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
        'order_ids' => 'required|string', // Validamos que haya pedidos seleccionados
        'descripcion' => 'nullable|string|max:255',
    ]);

    // Crear el manifiesto con los datos proporcionados
    $manifiesto = Manifiesto::create([
        'fecha' => $request->fecha,
        'camion_id' => $request->camion_id,
        'descripcion' => $request->descripcion,
        'estado' => 0, // Establecemos el estado predeterminado a 0
    ]);

    // Convertir los IDs de pedidos a un array y adjuntarlos al manifiesto
    $orderIds = explode(',', $request->order_ids); // Convertimos la cadena a un array
    $manifiesto->orders()->attach($orderIds); // Adjuntamos los pedidos seleccionados

    return redirect()->route('admin.manifiestos.index')
        ->with('success', 'Manifiesto creado exitosamente con los pedidos seleccionados.');
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
