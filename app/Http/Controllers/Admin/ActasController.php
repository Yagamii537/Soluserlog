<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

        // Paginación
        $orders = $query->paginate(10);

        return view('admin.actas.index', compact('orders'));
    }
}
