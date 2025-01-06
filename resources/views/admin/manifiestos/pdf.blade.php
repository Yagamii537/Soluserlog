<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Manifiesto #{{ $manifiesto->numero_manifiesto }}</title>
    <style>
        @page {
            margin: 20px;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }
        .header {
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 20px;
            text-align: center;
            margin: 0;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .header-table td {
            padding: 5px;
            vertical-align: top;
        }
        .header-table .label {
            font-weight: bold;
            text-align: right;
            width: 30%;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f4f4f4;
        }
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="header">
        <!-- Logo -->
        <div style="text-align: center;">
            <!-- Logo -->
            <img src="{{ $logoPath }}" alt="Logo" style="width:150px; height: auto; margin-bottom: 10px;">
            <!-- Título -->
            <h1 style="margin: 0;">Manifiesto de Carga</h1>
        </div>
        <table class="header-table">
            <tr>
                <td class="label">Número de Manifiesto:</td>
                <td>{{ $manifiesto->numero_manifiesto }}</td>
            </tr>
            <tr>
                <td class="label">Camión:</td>
                <td>{{ $manifiesto->camion->numero_placa }} - {{ $manifiesto->camion->marca }} {{ $manifiesto->camion->modelo }}</td>
            </tr>
            <tr>
                <td class="label">Chofer:</td>
                <td>{{ $manifiesto->conductor->nombre }} - {{ $manifiesto->conductor->numero_licencia }}</td>
            </tr>
            <tr>
                <td class="label">Ayudante:</td>
                <td>{{ $manifiesto->ayudante->nombre }} - {{ $manifiesto->ayudante->cedula }}</td>
            </tr>
            <tr>
                <td class="label">Fecha del Manifiesto:</td>
                <td>{{ \Carbon\Carbon::parse($manifiesto->fecha)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="label">Fecha de Inicio de Traslado:</td>
                <td>{{ \Carbon\Carbon::parse($manifiesto->fecha_inicio_traslado)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td class="label">Fecha de Fin de Traslado:</td>
                <td>{{ $manifiesto->fecha_fin_traslado ? \Carbon\Carbon::parse($manifiesto->fecha_fin_traslado)->format('d/m/Y') : 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Dirección de Destino</th>
                <th>Ciudad</th>
                <th>Zona</th>
                <th>Bultos</th>
                <th>Tipo de Carga</th>
                <th>Número de Factura</th>
            </tr>
        </thead>
        <tbody>
            @php
                $counter = 1;
                $totalBultos = 0;
                $totalFacturas = 0;
            @endphp
            @foreach ($orders->groupBy(function($order) {
                return $order->direccionDestinatario->cliente->razonSocial;
            }) as $cliente => $groupedOrders)
                @foreach ($groupedOrders as $order)
                    @php $firstDocument = true; @endphp
                    @foreach ($order->documents as $document)
                        <tr>
                            @if ($firstDocument)
                                <td rowspan="{{ count($order->documents) }}">{{ $counter++ }}</td>
                                <td rowspan="{{ count($order->documents) }}">{{ $order->direccionDestinatario->cliente->razonSocial }}</td>
                                <td rowspan="{{ count($order->documents) }}">{{ $order->direccionDestinatario->direccion }}</td>
                                <td rowspan="{{ count($order->documents) }}">{{ $order->direccionDestinatario->ciudad }}</td>
                                <td rowspan="{{ count($order->documents) }}">{{ $order->direccionDestinatario->zona }}</td>
                                @php $firstDocument = false; @endphp
                            @endif
                            <td>{{ $document->cantidad_bultos }}</td>
                            <td>{{ $document->tipo_carga }}</td>
                            <td>{{ $document->factura }}</td>
                        </tr>
                        @php
                            $totalBultos += $document->cantidad_bultos;
                            $totalFacturas++;
                        @endphp
                    @endforeach
                @endforeach
            @endforeach

        </tbody>
        <!-- Totales Generales -->
        <tfoot>
            <tr class="totals-row">
                <td colspan="5" style="text-align: right;">TOTAL GENERAL</td>
                <td>{{ $totalBultos }}</td>
                <td></td>
                <td>{{ $totalFacturas }}</td>
            </tr>
        </tfoot>
    </table>
    <div class="footer">
        <table style="width: 100%; margin-top: 40px; font-size: 12px; text-align: center;">
            <tr>
                <!-- Espacio para firmar -->
                <td style="border-bottom: 0px solid #000; height: 50px; width: 50%;"></td>
                <td style="border-bottom: 0px solid #000; height: 50px; width: 50%;"></td>
            </tr>
            <tr>
                <!-- Etiquetas debajo del espacio para firmar -->
                <td style="text-align: CENTER; font-weight: bold;">
                    Entregado por:<br>
                    <span>B. BRAUN MEDICAL-PAQUETERIA</span>
                </td>
                <td style="text-align: CENTER; font-weight: bold;">
                    Recibido por:<br>
                    <span></span>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
