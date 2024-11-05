<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::where('estado', '=', 0)->get();
        return view('admin.orders.index')->with('orders', $orders);
    }

    public function confirm(Request $request)
    {


        $orderIds = $request->input('order_ids');

        if ($orderIds) {
            Order::whereIn('id', $orderIds)->update(['estado' => 1]);
        }

        return redirect()->back()->with('success', 'Pedidos confirmados correctamente.');
    }

    public function generatePdf(Order $order)
    {
        // Buscar el pedido por su ID

        //return $order;
        // Generar la vista para el PDF

        return view('admin.orders.pdf')->with('order', $order);
        $pdf = PDF::loadView('admin.orders.pdf', compact('order'));

        // Descargar el PDF
        //return $pdf->download('pedido_' . $order->id . '.pdf');

        //? Mostrar el PDF en el navegador (en una nueva pestaña)
        return $pdf->stream('pedido_' . $order->id . '.pdf');
    }

    //? este metodo es para que en otra vista se confirme la eliminacion del pedido
    //? ya que el index tiene un formulario para confirmar varios pedidos
    public function confDelete(Order $order)
    {

        return view('admin.orders.confDelete', compact('order'));
    }

    public function confirmed()
    {
        // Obtener solo los pedidos que estén confirmados (estado == 1)
        $orders = Order::where('estado', 1)->get();

        // Retornar la vista con los pedidos confirmados
        return view('admin.orders.confirmed', compact('orders'));
    }


    public function create()
    {
        // Obtenemos todos los clientes para mostrarlos en el modal
        $clientes = Cliente::all();

        return view('admin.orders.create', compact('clientes'));
    }

    public function store(Request $request)
{
    // Validar los datos del formulario
    $validatedData = $request->validate([
        'cliente_id' => 'required|exists:clientes,id',
        'fechaCreacion' => 'required|date',
        'fechaConfirmacion' => 'nullable|date',
        'horario' => 'required|string',
        'fechaEntrega' => 'required|date',
        'observacion' => 'nullable|string',
        'remitente' => 'required|string', // Asegúrate de incluir remitente aquí
        'localidad' => 'required|string', // Asegúrate de incluir remitente aquí
    ]);

    // Agregar los valores predeterminados para los campos que no vienen del formulario
    $validatedData['estado'] = 0;
    $validatedData['totaBultos'] = 0;
    $validatedData['totalKgr'] = 0;

    // Crear el pedido en la base de datos
    $order = Order::create($validatedData);

    // Redirigir a la ruta para añadir documentos, pasando el ID del pedido creado
    return redirect()->route('admin.documents.addDocumentOrder', ['order' => $order->id])
                     ->with('success', 'Pedido creado con éxito.');
}




    public function show($id)
    {
        //
    }


    public function edit(Order $order)
    {
        $clientes = Cliente::pluck('razonSocial', 'id');


        return view('admin.orders.edit', compact('order', 'clientes'));
    }


    public function update(Request $request, Order $order)
    {



        $order->update($request->all());

        return redirect()->route('admin.orders.edit', $order)->with('info', 'Los datos se actualizaron correctamente');
    }


    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('info', 'El Pedido se elimino correctamente');
    }
}
