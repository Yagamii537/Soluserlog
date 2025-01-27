<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facturacion;
use App\Models\Document;
use App\Models\Manifiesto;
use Illuminate\Http\Request;
use App\Exports\FacturacionExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class FacturacionController extends Controller
{
    /**
     * Muestra el listado de facturaciones.
     */
    public function index(Request $request)
    {
        // Obtener las fechas de inicio y fin del filtro
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Query base para obtener las facturaciones con sus relaciones
        $query = Facturacion::with([
            'manifiesto.orders.documents',
            'manifiesto.orders.direccionDestinatario.cliente',
            'manifiesto.conductor',
        ]);

        // Filtrar por fecha solo si se especifica el rango
        if ($startDate && $endDate) {
            $query->whereHas('manifiesto.orders', function ($q) use ($startDate, $endDate) {
                // Asegurarse de incluir el fin del día para cubrir registros completos
                $q->whereBetween('fechaCreacion', [
                    $startDate,
                    \Carbon\Carbon::parse($endDate)->endOfDay(),
                ]);
            });
        }

        // Ordenar facturaciones por la fecha más reciente de las órdenes asociadas
        $facturaciones = $query->get()->sortByDesc(function ($facturacion) {
            return $facturacion->manifiesto->orders->max('fechaCreacion');
        });

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
        // La facturación ya se pasa como instancia gracias a Laravel
        return view('admin.facturacion.edit', compact('facturacion'));
    }

    /**
     * Actualiza una facturación existente.
     */
    public function update(Request $request, Facturacion $facturacion)
    {
        $request->validate([
            'valor' => 'required|numeric|min:0',
            'adicional' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
        ]);

        // Actualizar la facturación
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

    public function descargarExcel(Request $request)
    {

        // Capturar los filtros de fecha (si existen)
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');



        // Pasar los filtros al exportador
        return Excel::download(new FacturacionExport($startDate, $endDate), 'facturacion.xlsx');
    }


    public function descargarPdf(Request $request)
    {
        // Obtener las fechas del filtro
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Consulta las facturaciones con sus relaciones
        $facturaciones = Facturacion::with([
            'manifiesto.orders.documents',
            'manifiesto.orders.direccionDestinatario.cliente',
            'manifiesto.conductor',
        ])
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereHas('manifiesto.orders', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('fechaCreacion', [
                        $startDate,
                        \Carbon\Carbon::parse($endDate)->endOfDay(),
                    ]);
                });
            })
            ->get();

        $logoPath = public_path('vendor/adminlte/dist/img/logof.png');
        // Generar el PDF
        $pdf = Pdf::loadView('admin.facturacion.pdf', compact('facturaciones', 'startDate', 'endDate', 'logoPath'))->setPaper('a4', 'landscape');;

        // Descargar el archivo PDF
        return $pdf->download('facturacion.pdf');
    }
}
