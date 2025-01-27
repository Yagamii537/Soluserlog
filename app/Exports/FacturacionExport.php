<?php

namespace App\Exports;

use App\Models\Facturacion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class FacturacionExport implements FromCollection, WithHeadings
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Retorna la colección de datos para exportar.
     */
    public function collection()
    {
        // Base de la consulta
        $query = Facturacion::with(['manifiesto.orders.documents', 'manifiesto.orders.direccionDestinatario.cliente', 'manifiesto.conductor']);

        // Aplicar filtros de fecha si están presentes
        if ($this->startDate && $this->endDate) {
            $query->whereHas('manifiesto.orders', function ($q) {
                $q->whereBetween('fechaCreacion', [
                    $this->startDate,
                    Carbon::parse($this->endDate)->endOfDay(), // Incluye hasta las 23:59:59 del día de `endDate`
                ]);
            });
        }

        // Obtener los datos y formatearlos
        return $query->get()->map(function ($facturacion) {
            return [
                'Factura' => $facturacion->document->factura ?? 'N/A',
                'Fecha' => $facturacion->order->fechaCreacion ?? 'N/A',
                'Fecha Entrega' => $facturacion->order->fechaEntrega ?? 'N/A',
                'Código Cliente' => $facturacion->order->direccionDestinatario->cliente->codigoCliente ?? 'N/A',
                'Nombre Cliente' => $facturacion->order->direccionDestinatario->cliente->razonSocial ?? 'N/A',
                'Chofer' => $facturacion->manifiesto->conductor->nombre ?? 'N/A',
                'Destino' => $facturacion->order->direccionDestinatario->ciudad ?? 'N/A',
                '# Bultos' => $facturacion->document->cantidad_bultos,
                'Tipo de Flete' => $facturacion->manifiesto->tipoFlete ?? 'N/A',
                'Valor' => number_format($facturacion->valor, 2),
                'Adicional' => number_format($facturacion->adicional, 2),
                'Total' => number_format($facturacion->total, 2),
            ];
        });
    }

    /**
     * Agrega los encabezados al archivo Excel.
     */
    public function headings(): array
    {
        return [
            '# Factura',
            'Fecha',
            'Fecha Entrega',
            'Código Cliente',
            'Nombre Cliente',
            'Chofer',
            'Destino',
            '# Bultos',
            'Tipo de Flete',
            'Valor',
            'Adicional',
            'Total',
        ];
    }
}
