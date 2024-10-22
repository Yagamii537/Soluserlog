<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Manifiesto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // Contar pedidos confirmados (estado = 1 para confirmados)
        $pedidosConfirmados = Order::where('estado', 1)->count();

        // Contar pedidos no confirmados (estado = 0 para no confirmados)
        $pedidosNoConfirmados = Order::where('estado', 0)->count();

        // Contar manifiestos en proceso (puedes definir un estado específico, por ejemplo, estado = 1)
        $manifiestosEnProceso = Manifiesto::where('estado', 1)->count();

        return view('dash.index', compact('pedidosConfirmados', 'pedidosNoConfirmados', 'manifiestosEnProceso'));
    }
}
