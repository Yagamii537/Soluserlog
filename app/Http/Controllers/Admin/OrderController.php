<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


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
        // Generar el QR como PNG usando Imagick
        $qrCodePng = QrCode::format('png')
            ->size(200) // Tamaño del QR en píxeles
            ->margin(1) // Margen del QR
            ->generate($order->tracking_number);

        // Convertir el QR en Base64 para usarlo en la vista PDF
        $qrCodeBase64 = 'data:image/png;base64,' . base64_encode($qrCodePng);

        // Obtener la ruta completa del logo
        $logoPath = public_path('vendor/adminlte/dist/img/logof.png'); // Ruta absoluta al logo
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)); // Convertir a Base64

        // Renderizar la vista del PDF y pasar el QR y el logo en formato Base64
        $pdf = PDF::loadView('admin.orders.pdf', compact('order', 'qrCodeBase64', 'logoBase64'))
            ->setPaper([0, 0, 289.134, 215.905], 'portrait'); // Tamaño 10.2 cm x 7.6 cm en puntos

        // Descargar o mostrar el PDF
        return $pdf->stream('ticket_' . $order->tracking_number . '.pdf');
    }


    public function generateBoxesPdf(Order $order)
    {
        // Generar el QR como PNG usando Imagick
        $qrCodePng = QrCode::format('png')
            ->size(200) // Tamaño del QR en píxeles
            ->margin(1) // Margen del QR
            ->generate($order->tracking_number);

        // Convertir el QR en Base64 para usarlo en la vista PDF
        $qrCodeBase64 = 'data:image/png;base64,' . base64_encode($qrCodePng);

        // Obtener la ruta completa del logo
        $logoPath = public_path('vendor/adminlte/dist/img/logof.png'); // Ruta absoluta al logo
        $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath)); // Convertir a Base64

        // Renderizar la vista del PDF y pasar el QR y el logo en formato Base64
        $pdf = PDF::loadView('admin.orders.boxes', compact('order', 'qrCodeBase64', 'logoBase64'))
            ->setPaper([0, 0, 289.134, 215.905], 'portrait'); // Tamaño 10.2 cm x 7.6 cm en puntos

        // Descargar o mostrar el PDF
        return $pdf->stream('ticket_' . $order->tracking_number . '.pdf');
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
            'horario' => 'required|string',
            'fechaEntrega' => 'required|date',
            'observacion' => 'nullable|string',
            'remitente_direccion_id' => 'required|exists:addresses,id', // ID de la dirección del remitente
            'direccion_id' => 'required|exists:addresses,id', // ID de la dirección del destinatario
        ]);

        // Obtener el último número de tracking generado
        $lastOrder = Order::orderBy('id', 'desc')->first();
        $lastTrackingNumber = $lastOrder ? (int) \Illuminate\Support\Str::after($lastOrder->tracking_number, '-') : 0;

        // Incrementar el número de tracking y generar el nuevo
        $newTrackingNumber = str_pad($lastTrackingNumber + 1, 8, '0', STR_PAD_LEFT);
        $trackingNumber = "0001-$newTrackingNumber-S";

        // Agregar valores predeterminados
        $validatedData['estado'] = 0;
        $validatedData['totaBultos'] = 0;
        $validatedData['totalKgr'] = 0;

        // Crear el pedido en la base de datos
        $order = Order::create(array_merge($validatedData, ['tracking_number' => $trackingNumber]));

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
            'remitente_direccion_id' => 'required|exists:addresses,id',
            'direccion_id' => 'required|exists:addresses,id',
            'horario' => 'required|string',
            'fechaEntrega' => 'required|date',
            'observacion' => 'nullable|string',
        ]);

        // Actualizar solo los campos permitidos en la tabla `orders`
        $order->update([
            'fechaCreacion' => $validatedData['fechaCreacion'],

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
