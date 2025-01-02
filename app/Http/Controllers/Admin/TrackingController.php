<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Document;

class TrackingController extends Controller
{
    public function index()
    {
        return view('admin.tracking.index');
    }

    // Realizar búsqueda por tracking number o número de factura
    public function search(Request $request)
    {
        $request->validate([
            'search_term' => 'required|string',
        ]);

        $searchTerm = $request->input('search_term');

        // Buscar pedidos por tracking number
        $ordersByTracking = Order::where('tracking_number', $searchTerm)
            ->with(['direccionRemitente.cliente', 'direccionDestinatario.cliente', 'documents'])
            ->get();

        // Buscar pedidos por número de factura en los documentos asociados
        $documents = Document::where('factura', $searchTerm)
            ->with('order.direccionRemitente.cliente', 'order.direccionDestinatario.cliente', 'order.documents')
            ->get();

        // Recopilar los pedidos únicos de los documentos
        $ordersByDocuments = $documents->pluck('order')->unique();

        // Combinar resultados de ambas búsquedas
        $orders = $ordersByTracking->merge($ordersByDocuments)->unique('id');

        if ($orders->isEmpty()) {
            return redirect()->route('admin.tracking.index')->with('error', 'No se encontró ningún pedido con ese número de factura o tracking.');
        }

        return view('admin.tracking.results', compact('orders'));
    }
}