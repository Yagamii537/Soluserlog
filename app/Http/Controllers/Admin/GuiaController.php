<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guia;
use App\Models\Manifiesto;
use App\Models\Conductor;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class GuiaController extends Controller
{
    // Mostrar lista de guías
    public function index()
    {
        $guias = Guia::with(['manifiesto', 'conductor', 'manifiesto.camion'])->get();
        return view('admin.guias.index', compact('guias'));
    }


    public function selectManifiesto()
    {
        $manifiestos = Manifiesto::with('camion')->get();
        return view('admin.guias.select_manifiesto', compact('manifiestos'));
    }



    // Mostrar formulario de creación
    public function create(Manifiesto $manifiesto)
    {
        $conductores = Conductor::all();
        $clienteOrigen = $manifiesto->orders->first()->direccionRemitente->cliente->razonSocial ?? 'N/A';

        return view('admin.guias.create', compact('manifiesto', 'conductores', 'clienteOrigen'));
    }



    // Guardar nueva guía
    public function store(Request $request)
    {
        $request->validate([
            'manifiesto_id' => 'required|exists:manifiestos,id',
            'conductor_id' => 'required|exists:conductores,id',
            'empresa' => 'required|string|max:255',
            'origen' => 'required|string|max:255', // Validación del campo origen
            'ayudante' => 'nullable|string|max:255',
        ]);

        // Crear la guía
        Guia::create([
            'manifiesto_id' => $request->manifiesto_id,
            'conductor_id' => $request->conductor_id,
            'empresa' => $request->empresa,
            'origen' => $request->origen,
            'ayudante' => $request->ayudante,
            'fecha_emision' => now(), // Asignar la fecha de emisión como la fecha actual
        ]);

        return redirect()->route('admin.guias.index')
            ->with('success', 'Guía creada exitosamente.');
    }





    // Mostrar formulario de edición
    public function edit(Guia $guia)
    {
        $manifiesto = $guia->manifiesto()->with('camion', 'orders.documents', 'orders.direccionDestinatario.cliente')->first();
        $conductores = Conductor::all();

        return view('admin.guias.edit', compact('guia', 'manifiesto', 'conductores'));
    }

    // Actualizar guía
    public function update(Request $request, Guia $guia)
    {
        $request->validate([
            'manifiesto_id' => 'required|exists:manifiestos,id',
            'conductor_id' => 'required|exists:conductores,id',
            'empresa' => 'required|string|max:255',
            'origen' => 'required|string|max:255',
            'fecha_emision' => 'required|date',
        ]);

        $guia->update($request->all());

        return redirect()->route('admin.guias.index')->with('success', 'Guía actualizada exitosamente.');
    }

    // Eliminar guía
    public function destroy(Guia $guia)
    {
        $guia->delete();

        return redirect()->route('admin.guias.index')->with('success', 'Guía eliminada exitosamente.');
    }

    // Generar PDF
    public function generatePdf(Guia $guia)
    {
        // Obtener los pedidos asociados al manifiesto relacionado con la guía
        $orders = $guia->manifiesto->orders()->with('direccionDestinatario.cliente', 'documents')->get();

        // Ruta del logo
        $logoPath = public_path('vendor/adminlte/dist/img/logof.png');

        // Renderizar la vista del PDF y configurarlo en formato A4 horizontal
        $pdf = PDF::loadView('admin.guias.pdf', compact('guia', 'orders', 'logoPath'))
            ->setPaper('a4', 'landscape'); // A4 horizontal

        // Descargar o mostrar el PDF
        return $pdf->stream('guia_ruta_' . $guia->id . '.pdf');
    }
}
