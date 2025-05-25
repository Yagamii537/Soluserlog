<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetalleBitacoraCheck;
use Carbon\Carbon;

class IndicadoresBitacoraController extends Controller
{
    public function index(Request $request)
    {
        $opciones = [
            'INTEGRIDAD DEL EMPAQUE',
            'ETIQUETA NO DESPRENDIBLE',
            'EMBALAJE CON CINTA DE LA EMPRESA CONTRATANTE',
            'ETIQUETA DE IDENTIFICACIÓN DEL PRODUCTO CLIENTE DESTINO',
            'DOCUMENTACION INFORMACION CLIENTE',
            'PRODUCTO CALIDAD CLIENTE',
            'TIEMPO OPERADOR',
            'SERVICIO OPERADOR',
            'DOCUMENTACION INFORMACION OPERADOR',
            'TIEMPO CLIENTE',
            'ENTREGA SIN NOVEDAD',
        ];

        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Por defecto filtra el mes actual si no se escogen fechas
        if (!$startDate || !$endDate) {
            $startDate = now()->startOfMonth()->toDateString();
            $endDate = now()->endOfMonth()->toDateString();
        }

        $estadisticas = [];

        foreach ($opciones as $opcion) {
            $result = DetalleBitacoraCheck::selectRaw('DATE(created_at) as fecha, COUNT(*) as total')
                ->where('opcion', $opcion)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupByRaw('DATE(created_at)')
                ->orderBy('fecha')
                ->get();

            if ($result->isNotEmpty()) {
                $estadisticas[$opcion] = $result;
            }
        }

        $tipos = [
            'INTEGRIDAD DEL EMPAQUE' => 'carga',
            'ETIQUETA NO DESPRENDIBLE' => 'carga',
            'EMBALAJE CON CINTA DE LA EMPRESA CONTRATANTE' => 'carga',
            'ETIQUETA DE IDENTIFICACIÓN DEL PRODUCTO CLIENTE DESTINO' => 'carga',
            'DOCUMENTACION INFORMACION CLIENTE' => 'destino',
            'PRODUCTO CALIDAD CLIENTE' => 'destino',
            'TIEMPO OPERADOR' => 'destino',
            'SERVICIO OPERADOR' => 'destino',
            'DOCUMENTACION INFORMACION OPERADOR' => 'destino',
            'TIEMPO CLIENTE' => 'destino',
            'ENTREGA SIN NOVEDAD' => 'destino',
        ];

        return view('admin.indicadores_bitacora.index', compact('estadisticas', 'tipos', 'startDate', 'endDate'));
    }



    public function show($tipo, $opcion, Request $request)
    {
        $fecha = $request->query('fecha');

        // Normaliza el texto recibido (convierte + en espacio si viene por URL)
        $opcion = str_replace('+', ' ', urldecode($opcion));

        $checks = DetalleBitacoraCheck::with([
            'detalleBitacora.order.documents',
            'detalleBitacora.order.direccionDestinatario'
        ])
            ->where('tipo', $tipo)
            ->where('opcion', $opcion)
            ->whereDate('created_at', $fecha)
            ->get();

        return view('admin.indicadores_bitacora.show', compact('checks', 'tipo', 'opcion', 'fecha'));
    }
}
