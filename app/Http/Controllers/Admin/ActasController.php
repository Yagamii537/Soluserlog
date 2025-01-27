<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\ActasExport;
use Maatwebsite\Excel\Facades\Excel;

class ActasController extends Controller
{
    public function index(Request $request)
    {
        // Filtros por fecha
        $query = Order::with([
            'direccionDestinatario.cliente',
            'documents',
            'manifiestos.guias.bitacora.detalles',
        ]);

        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('fechaCreacion', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('fechaCreacion', '<=', $request->end_date);
        }

        // Ordenar por fechaCreacion de manera descendente
        $orders = $query->orderBy('fechaCreacion', 'desc')->get();

        return view('admin.actas.index', compact('orders'));
    }

    public function descargarExcel(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        return Excel::download(new ActasExport($startDate, $endDate), 'actas.xlsx');
    }
}
