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
            $currentDate = now(); // Obtener la fecha y hora actual

            Order::whereIn('id', $orderIds)->update([
                'estado' => 1,
                'fechaConfirmacion' => $currentDate,
            ]);
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
            'fechaCreacion' => 'required|date',
            'fechaConfirmacion' => 'nullable|date',
            'horario' => 'required|string',
            'fechaEntrega' => 'required|date',
            'observacion' => 'nullable|string',
            'remitente_direccion_id' => 'required|exists:addresses,id', // ID de la dirección del remitente
            'direccion_id' => 'required|exists:addresses,id', // ID de la dirección del destinatario
        ]);
        // Agregar valores predeterminados
        $validatedData['estado'] = 0;
        $validatedData['totaBultos'] = 0;
        $validatedData['totalKgr'] = 0;
        // Crear el pedido en la base de datos
        $order = Order::create([
            'fechaCreacion' => $validatedData['fechaCreacion'],
            'fechaConfirmacion' => $validatedData['fechaConfirmacion'],
            'horario' => $validatedData['horario'],
            'fechaEntrega' => $validatedData['fechaEntrega'],
            'observacion' => $validatedData['observacion'],
            'estado' => $validatedData['estado'],
            'totaBultos' => $validatedData['totaBultos'],
            'totalKgr' => $validatedData['totalKgr'],
            'remitente_direccion_id' => $validatedData['remitente_direccion_id'],
            'direccion_id' => $validatedData['direccion_id'],
        ]);
        // Redirigir a la ruta para añadir documentos
        return redirect()->route('admin.documents.addDocumentOrder', ['order' => $order->id])
            ->with('success', 'Pedido creado con éxito.');
    }

    public function show($id)
    {
        //
    }

    public function edit(Order $order)
    {

        // Obtener todas las direcciones del remitente, si ya se ha seleccionado
        $direccionesRemitente = $order->direccionRemitente
            ? $order->direccionRemitente->cliente->addresses->pluck('direccion', 'id')
            : collect();

        // Obtener todas las direcciones del destinatario, si ya se ha seleccionado
        $direccionesDestinatario = $order->direccionDestinatario
            ? $order->direccionDestinatario->cliente->addresses->pluck('direccion', 'id')
            : collect();

        // Obtener todos los clientes para permitir selección
        //$clientes = Cliente::pluck('razonSocial', 'id');
        $clientes = Cliente::all();

        return view('admin.orders.edit', compact('order', 'clientes', 'direccionesRemitente', 'direccionesDestinatario'));
    }

    public function update(Request $request, Order $order)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'fechaCreacion' => 'required|date',
            'fechaConfirmacion' => 'nullable|date',
            'remitente_direccion_id' => 'required|exists:addresses,id',
            'direccion_id' => 'required|exists:addresses,id',
            'horario' => 'required|string',
            'fechaEntrega' => 'required|date',
            'observacion' => 'nullable|string',
        ]);

        // Actualizar solo los campos permitidos en la tabla `orders`
        $order->update([
            'fechaCreacion' => $validatedData['fechaCreacion'],
            'fechaConfirmacion' => $validatedData['fechaConfirmacion'],
            'remitente_direccion_id' => $validatedData['remitente_direccion_id'],
            'direccion_id' => $validatedData['direccion_id'],
            'horario' => $validatedData['horario'],
            'fechaEntrega' => $validatedData['fechaEntrega'],
            'observacion' => $validatedData['observacion'],
        ]);

        // Redirigir con un mensaje de éxito
        return redirect()->route('admin.orders.index')->with('success', 'Pedido actualizado exitosamente.');
    }


    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('info', 'El Pedido se elimino correctamente');
    }
}
