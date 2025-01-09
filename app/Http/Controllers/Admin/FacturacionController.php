<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facturacion;
use App\Models\Document;
use App\Models\Manifiesto;
use Illuminate\Http\Request;

class FacturacionController extends Controller
{
    /**
     * Muestra el listado de facturaciones.
     */
    public function index()
    {
        // Obtener todas las facturaciones con relaciones necesarias
        $facturaciones = Facturacion::with([
            'manifiesto.orders.documents',             // Documentos asociados
            'manifiesto.orders.direccionDestinatario.cliente', // Cliente
            'manifiesto.conductor',                   // Conductor
        ])->get();

        // Pasar las facturaciones a la vista
        return view('admin.facturacion.index', compact('facturaciones'));
    }




    /**
     * Muestra el formulario para crear una nueva facturación.
     */
    public function create()
    {
        $manifiestos = Manifiesto::with([
            'orders.documents',             // Documentos asociados a las órdenes
            'orders.direccionDestinatario.cliente', // Cliente relacionado con la dirección del destinatario
            'conductor',                   // Conductor del manifiesto
        ])->get();

        return view('admin.facturacion.create', compact('manifiestos'));
    }


    /**
     * Almacena una nueva facturación en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'manifiesto_id' => 'required|exists:manifiestos,id',
            'valor' => 'required|numeric|min:0',
            'adicional' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
        ]);

        // Crear la facturación asociada al manifiesto
        Facturacion::create([
            'manifiesto_id' => $request->manifiesto_id,
            'valor' => $request->valor,
            'adicional' => $request->adicional,
            'total' => $request->total,
        ]);

        return redirect()->route('admin.facturacion.index')->with('success', 'Facturación creada correctamente.');
    }


    /**
     * Muestra el formulario para editar una facturación.
     */
    public function edit(Facturacion $facturacion)
    {
        return view('admin.facturacion.edit', compact('facturacion'));
    }

    /**
     * Actualiza una facturación existente.
     */
    public function update(Request $request, Facturacion $facturacion)
    {
        $request->validate([
            'valor' => 'nullable|numeric',
            'adicional' => 'nullable|numeric',
            'total' => 'nullable|numeric',
        ]);

        $facturacion->update($request->only(['valor', 'adicional', 'total']));

        return redirect()->route('admin.facturacion.index')->with('success', 'Facturación actualizada correctamente.');
    }

    /**
     * Elimina una facturación de la base de datos.
     */
    public function destroy(Facturacion $facturacion)
    {
        $facturacion->delete();

        return redirect()->route('admin.facturacion.index')->with('success', 'Facturación eliminada correctamente.');
    }
}
