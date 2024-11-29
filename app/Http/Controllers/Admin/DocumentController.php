<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Document;
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
        $orders = Order::where('estado', '=', 1)->get();
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

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
