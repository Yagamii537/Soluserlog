<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function addDocumentOrder(Order $order)
    {

        return view('admin.documents.addDocumentOrder', compact('order'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
        return view('admin.orders.index')->with('orders', $orders);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
