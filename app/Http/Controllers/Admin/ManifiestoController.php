<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manifiesto;
use App\Models\Camion;
use App\Models\Order; // Importamos el modelo Pedido
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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
            'fecha_inicio_traslado' => 'required|date',
            'fecha_fin_traslado' => 'nullable|date|after_or_equal:fecha_inicio_traslado',
        ]);
        // Calcular el número de manifiesto único
        $ultimoManifiesto = Manifiesto::latest()->first();
        $numeroManifiesto = $ultimoManifiesto ? $ultimoManifiesto->id + 1 : 1;

        // Calcular el total de bultos y kilos a partir de los pedidos seleccionados
        $orderIds = explode(',', $request->order_ids); // Convertimos la cadena a un array
        $bultosTotales = 0;
        $kilosTotales = 0;

        foreach ($orderIds as $orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $bultosTotales += $order->totaBultos ?? 0;
                $kilosTotales += $order->totalKgr ?? 0;
            }
        }

        // Crear el manifiesto con los datos proporcionados
        $manifiesto = Manifiesto::create([
            'fecha' => $request->fecha,
            'camion_id' => $request->camion_id,
            'descripcion' => $request->descripcion,
            'estado' => 0, // Establecemos el estado predeterminado a 0
            'fecha_inicio_traslado' => $request->fecha_inicio_traslado,
            'fecha_fin_traslado' => $request->fecha_fin_traslado,
            'numero_manifiesto' => str_pad($numeroManifiesto, 6, '0', STR_PAD_LEFT), // Formato de 6 dígitos
            'bultos' => $bultosTotales,
            'kilos' => $kilosTotales,
        ]);

        // Adjuntar los pedidos seleccionados al manifiesto
        $manifiesto->orders()->attach($orderIds);

        Order::whereIn('id', $orderIds)->update(['estado' => 2]);

        return redirect()->route('admin.manifiestos.index')
            ->with('success', 'Manifiesto creado exitosamente con los pedidos seleccionados.');
    }





    // Mostrar formulario de edición
    public function edit($id)
    {
        $manifiesto = Manifiesto::findOrFail($id);
        $camiones = Camion::all();
        $ordersConfirmados = Order::whereIn('estado', [1, 2])->get();

        return view('admin.manifiestos.edit', compact('manifiesto', 'camiones', 'ordersConfirmados'));
    }

    // Actualizar un manifiesto
    public function update(Request $request, Manifiesto $manifiesto)
    {
        $request->validate([
            'fecha' => 'required|date',
            'camion_id' => 'required|exists:camiones,id',
            'order_ids' => 'required|string', // Validamos que haya pedidos seleccionados
            'descripcion' => 'nullable|string|max:255',
            'fecha_inicio_traslado' => 'required|date',
            'fecha_fin_traslado' => 'nullable|date|after_or_equal:fecha_inicio_traslado',
        ]);

        // Obtener los IDs de los pedidos seleccionados en el formulario
        $orderIdsNuevos = explode(',', $request->order_ids);

        // Obtener los IDs de los pedidos actualmente asociados al manifiesto
        $orderIdsActuales = $manifiesto->orders->pluck('id')->toArray();

        // Identificar los pedidos que se eliminaron del manifiesto
        $orderIdsEliminados = array_diff($orderIdsActuales, $orderIdsNuevos);

        // Actualizar el manifiesto con los datos del formulario
        $manifiesto->update([
            'fecha' => $request->fecha,
            'camion_id' => $request->camion_id,
            'descripcion' => $request->descripcion,
            'fecha_inicio_traslado' => $request->fecha_inicio_traslado,
            'fecha_fin_traslado' => $request->fecha_fin_traslado,
            'bultos' => array_reduce($orderIdsNuevos, function ($carry, $id) {
                $order = Order::find($id);
                return $carry + ($order ? $order->totaBultos : 0);
            }, 0),
            'kilos' => array_reduce($orderIdsNuevos, function ($carry, $id) {
                $order = Order::find($id);
                return $carry + ($order ? $order->totalKgr : 0);
            }, 0),
        ]);

        // Desasociar los pedidos eliminados y cambiar su estado a 1
        if (!empty($orderIdsEliminados)) {
            $manifiesto->orders()->detach($orderIdsEliminados);
            Order::whereIn('id', $orderIdsEliminados)->update(['estado' => 1]);
        }

        // Asociar los nuevos pedidos al manifiesto y cambiar su estado a 2
        $manifiesto->orders()->sync($orderIdsNuevos);
        Order::whereIn('id', $orderIdsNuevos)->update(['estado' => 2]);

        return redirect()->route('admin.manifiestos.index')
            ->with('success', 'Manifiesto actualizado exitosamente.');
    }



    public function destroy(Manifiesto $manifiesto)
    {

        $manifiesto->delete();

        return redirect()->route('admin.manifiestos.index')
            ->with('success', 'Conductor eliminado exitosamente.');
    }

    public function generatePdf(Manifiesto $manifiesto)
    {
        // Obtener los pedidos asociados al manifiesto
        $orders = $manifiesto->orders()->with('direccionDestinatario.cliente')->get();

        // Ruta completa del logo
        $logoPath = public_path('vendor/adminlte/dist/img/logof.png'); // Ruta absoluta al logo
        // Renderizar la vista del PDF
        $pdf = PDF::loadView('admin.manifiestos.pdf', compact('manifiesto', 'orders', 'logoPath'))->setPaper('a4', 'portrait');

        // Descargar o mostrar el PDF
        return $pdf->stream('manifiesto_' . $manifiesto->numero_manifiesto . '.pdf');
    }
}
