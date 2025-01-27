<?php

namespace App\Http\Controllers\Admin;

use App\Models\Guia;
use App\Models\Ayudante;
use App\Models\Bitacora;
use App\Models\Conductor;
use App\Models\Manifiesto;
use App\Models\Facturacion;
use Illuminate\Http\Request;
use App\Models\DetalleBitacora;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;

class GuiaController extends Controller
{
    // Mostrar lista de guías
    public function index()
    {
        $guias = Guia::with(['manifiesto', 'manifiesto.conductor', 'manifiesto.ayudante', 'manifiesto.camion'])->orderBy('id', 'desc')->get();
        return view('admin.guias.index', compact('guias'));
    }


    public function selectManifiesto()
    {
        $manifiestos = Manifiesto::with('camion')->where('estado', 0)->orderBy('id', 'desc')->get();
        return view('admin.guias.select_manifiesto', compact('manifiestos'));
    }



    // Mostrar formulario de creación
    public function create(Manifiesto $manifiesto)
    {
        $ayudantes = Ayudante::all();
        $clienteOrigen = $manifiesto->orders->first()->direccionRemitente->cliente->razonSocial ?? 'N/A';

        return view('admin.guias.create', compact('manifiesto', 'ayudantes', 'clienteOrigen'));
    }



    // Guardar nueva guía
    public function store(Request $request)
    {
        $request->validate([
            'manifiesto_id' => 'required|exists:manifiestos,id',
            'empresa' => 'required|string|max:255',
            'origen' => 'required|string|max:255',
        ]);

        // Crear la guía
        $guia = Guia::create([
            'manifiesto_id' => $request->manifiesto_id,
            'empresa' => $request->empresa,
            'origen' => $request->origen,
            'fecha_emision' => now(),
            'numero_guia' => Guia::getNextNumeroGuia(),
        ]);

        // Cambiar el estado del manifiesto a 1
        $manifiesto = Manifiesto::find($request->manifiesto_id);
        $manifiesto->update(['estado' => 1]);

        // Crear la bitácora principal
        $bitacora = Bitacora::create([
            'guia_id' => $guia->id,
        ]);

        // Crear los detalles de la bitácora basados en los pedidos del manifiesto
        $orders = $guia->manifiesto->orders;
        foreach ($orders as $order) {
            DetalleBitacora::create([
                'bitacora_id' => $bitacora->id,
                'order_id' => $order->id,
            ]);
            // Crear las facturaciones para cada documento asociado a la orden
            foreach ($order->documents as $document) {
                Facturacion::create([
                    'manifiesto_id' => $manifiesto->id,
                    'order_id' => $order->id,
                    'document_id' => $document->id,
                    'valor' => 0, // Inicialmente en 0, puede ser actualizado posteriormente
                    'adicional' => 0, // Inicialmente en 0
                    'total' => 0, // Inicialmente en 0
                ]);
            }
        }

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
