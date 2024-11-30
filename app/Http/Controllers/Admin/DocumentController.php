<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Document;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DocumentController extends Controller
{
    public function index()
    {
        //
    }

    public function addDocumentOrder(Order $order)
    {

        return view('admin.documents.addDocumentOrder', compact('order'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $order = new Order();
        $id = $request->input('order_id');
        $order = Order::find($id);
        //return $request;
        foreach ($request->input('documents', []) as $documentData) {
            $document = new Document();
            $document->order_id = $order->id;
            $document->n_documento = $documentData['n_documento'];
            $document->tipo_carga = $documentData['tipo_carga'];
            $document->cantidad_bultos = $documentData['cantidad_bultos'];
            $document->cantidad_kg = $documentData['cantidad_kg'];
            $document->factura = $documentData['factura'];
            $document->observaciones = $documentData['observaciones'];
            $document->save();
        }

        $orders = Order::where('estado', '=', 0)->get();
        return redirect()->route('admin.orders.index')->with('orders', $orders);
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function editDocumentOrder(Order $order)
    {
        return view('admin.documents.editDocumentOrder', compact('order'));
    }

    public function updateDocumentOrder(Request $request, Order $order)
    {
        // Validar los datos del formulario
        $validatedData = $request->validate([
            'documents' => 'required|array', // Validar que haya al menos un documento
            'documents.*.n_documento' => 'required|string|max:255',
            'documents.*.tipo_carga' => 'required|string|max:255',
            'documents.*.cantidad_bultos' => 'required|integer|min:1',
            'documents.*.cantidad_kg' => 'required|numeric|min:0',
            'documents.*.factura' => 'required|string|max:255',
            'documents.*.observaciones' => 'nullable|string|max:500',
        ]);

        // Eliminar todos los documentos asociados al pedido
        $order->documents()->delete();

        // Crear los nuevos documentos enviados desde la vista
        foreach ($validatedData['documents'] as $documentData) {
            $order->documents()->create($documentData);
        }

        // Redirigir de vuelta al pedido con un mensaje de éxito
        return redirect()->route('admin.orders.edit', $order->id)
            ->with('success', 'Documentos actualizados correctamente.');
    }


    public function destroy($id)
    {
        //
    }
}
