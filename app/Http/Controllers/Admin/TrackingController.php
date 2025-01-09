<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Document;
use Barryvdh\DomPDF\Facade\Pdf;

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
            ->with([
                'direccionRemitente.cliente',
                'direccionDestinatario.cliente',
                'documents',
                'manifiestos.guias.bitacora.detalleBitacoras.images',
            ])
            ->get();

        // Buscar pedidos por número de factura en los documentos asociados
        $documents = Document::where('factura', $searchTerm)
            ->with([
                'order.direccionRemitente.cliente',
                'order.direccionDestinatario.cliente',
                'order.documents',
                'order.manifiestos.guias.bitacora.detalles.images',
            ])
            ->get();

        // Recopilar pedidos únicos de los documentos
        $ordersByDocuments = $documents->pluck('order')->unique();

        // Combinar resultados de ambas búsquedas
        $orders = $ordersByTracking->merge($ordersByDocuments)->unique('id');

        if ($orders->isEmpty()) {
            return redirect()->route('admin.tracking.index')->with('error', 'No se encontró ningún pedido con ese número de factura o tracking.');
        }

        return view('admin.tracking.results', compact('orders', 'searchTerm'));
    }


    public function downloadPDF($orderId)
    {
        $order = Order::with([
            'direccionRemitente.cliente',
            'direccionDestinatario.cliente',
            'documents',
            'manifiestos.guias.bitacora.detalles.images',
        ])->findOrFail($orderId);

        // Filtrar bitácoras y detalles asociados únicamente a este pedido
        $filteredManifiestos = $order->manifiestos->map(function ($manifiesto) use ($orderId) {
            $manifiesto->guias = $manifiesto->guias->map(function ($guia) use ($orderId) {
                $guia->bitacora->detalles = $guia->bitacora->detalles->filter(function ($detalle) use ($orderId) {
                    return $detalle->order_id == $orderId; // Filtra detalles de la bitácora solo para este pedido
                });
                return $guia;
            });
            return $manifiesto;
        });

        // Ruta del logo
        $logoPath = public_path('vendor/adminlte/dist/img/logof.png');

        $pdf = PDF::loadView('admin.tracking.pdf', compact('order', 'logoPath', 'filteredManifiestos'));

        return $pdf->download("tracking_pedido_{$order->id}.pdf");
    }
}
