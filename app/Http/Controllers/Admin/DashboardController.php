<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Manifiesto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Contar pedidos confirmados (estado = 1 para confirmados)
        $pedidosConfirmados = Order::where('estado', 1)->count();

        // Contar pedidos no confirmados (estado = 0 para no confirmados)
        $pedidosNoConfirmados = Order::where('estado', 0)->count();

        // Contar manifiestos en proceso (estado = 1)
        $manifiestosEnProceso = Manifiesto::where('estado', 1)->count();

        // Obtener la cantidad de pedidos realizados por día en la última semana
        $pedidosPorDia = Order::selectRaw('DATE(fechaCreacion) as date, count(*) as count')
            ->where('fechaCreacion', '>=', Carbon::now()->subDays(7)) // Últimos 7 días
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Crear dos arrays para pasar a la vista: uno con las fechas y otro con las cantidades
        $fechas = [];
        $cantidades = [];

        foreach ($pedidosPorDia as $pedido) {
            $fechas[] = $pedido->date;
            $cantidades[] = $pedido->count;
        }

        // Enviar los datos a la vista
        return view('dash.index', compact('pedidosConfirmados', 'pedidosNoConfirmados', 'manifiestosEnProceso', 'fechas', 'cantidades'));
    }
}
