<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ActasExport implements FromCollection, WithHeadings
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Colección de datos para la exportación.
     */
    public function collection()
    {
        // Query con relaciones necesarias
        $query = Order::with([
            'direccionDestinatario.cliente',
            'documents',
            'manifiestos.guias.bitacora.detalles',
        ]);

        // Filtrar por fechas si se especifican
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('fechaCreacion', [
                $this->startDate,
                \Carbon\Carbon::parse($this->endDate)->endOfDay(), // Fin del día para incluir registros del día completo
            ]);
        }

        // Obtener y mapear los datos
        return $query->get()->flatMap(function ($order) {
            return $order->documents->map(function ($document) use ($order) {
                $manifiesto = $order->manifiestos->first();
                $guia = $manifiesto ? $manifiesto->guias->first() : null;
                $bitacora = $guia ? $guia->bitacora : null;
                $detalle = $bitacora ? $bitacora->detalles->where('order_id', $order->id)->first() : null;

                return [
                    'Fecha del Pedido' => $order->fechaCreacion,
                    'Código' => $order->direccionDestinatario->cliente->codigoCliente ?? 'No asociada',
                    'Razón Social' => $order->direccionDestinatario->cliente->razonSocial ?? 'No asociada',
                    '# Factura' => $document->factura ?? 'N/A',
                    '# Documento' => $document->n_documento ?? 'N/A',
                    'Observación' => $document->observaciones ?? 'N/A',
                    'Fecha de Entrega' => $detalle->fechaDestino ?? 'No asociada',
                    'Hora de Llegada' => $detalle->hora_destino_llegada ?? 'No asociada',
                    'Persona que Recibió' => $detalle->persona ?? 'No asociada',
                    'Coordenadas' => $order->direccionDestinatario->latitud . ', ' . $order->direccionDestinatario->longitud ?? 'N/A',
                ];
            });
        });
    }


    /**
     * Encabezados para el archivo Excel.
     */
    public function headings(): array
    {
        return [
            'Fecha del Pedido',
            'Código',
            'Razón Social',
            '# Factura',
            '# Documento',
            'Observación',
            'Fecha de Entrega',
            'Hora de Llegada',
            'Persona que Recibió',
            'Coordenadas',
        ];
    }
}
