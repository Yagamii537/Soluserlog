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
            line-height: 1.2;
        }
        .header { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .header td { vertical-align: middle; text-align: center; }
        .header img { max-width: 100px; }
        .code-section { text-align: right; padding-right: 10px; font-weight: bold; }

        .header h1 {
            font-size: 20px;
            text-align: center;
            margin: 0;
        }
        .header-table {
            width: 100%;
            margin: 0 auto;
            border-collapse: collapse;
            margin-bottom: 15px;
            text-align: center;
        }
        .header-table td {
            padding: 5px;
            vertical-align: top;
        }
        .header-table .label {
            font-weight: bold;
            text-align: right;
            width: 25%;
            padding-right: 10px;
        }
        .header-table .value {
            text-align: left;
            width: 25%;
            padding-left: 10px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 5px;
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
    <table class="header">
        <tr>
            <td style="width: 20%; text-align: left;">
                <!-- Logo -->
                <img src="{{ $logoPath }}" alt="Logo">
            </td>
            <td style="width: 60%; text-align: center;">
                <!-- Encabezado centrado -->
                <div class="title-section">
                    <h3>PROCEDIMIENTO OPERATIVO ESTÁNDAR</h3>
                    <h3>CARGA Y DESCARGA DE PRODUCTOS</h3>
                    <h1>MANIFIESTO DE CARGA</h1>
                </div>
            </td>
            <td style="width: 20%; text-align: right;">
                <!-- Código a la derecha -->
                <div class="code-section">DO-POE-04-F9-V00</div>
            </td>
        </tr>
    </table>

    <table class="header-table">
        <tr>
            <td class="label">Número de Manifiesto:</td>
            <td class="value">{{ $manifiesto->numero_manifiesto }}</td>
            <td class="label">Camión:</td>
            <td class="value">{{ $manifiesto->camion->numero_placa }} - {{ $manifiesto->camion->marca }} {{ $manifiesto->camion->modelo }}</td>
        </tr>
        <tr>
            <td class="label">Chofer:</td>
            <td class="value">{{ $manifiesto->conductor->nombre }} - {{ $manifiesto->conductor->numero_licencia }}</td>
            <td class="label">Ayudante:</td>
            <td class="value">{{ $manifiesto->ayudante->nombre }} - {{ $manifiesto->ayudante->cedula }}</td>
        </tr>
        <tr>
            <td class="label">Fecha del Manifiesto:</td>
            <td class="value">{{ \Carbon\Carbon::parse($manifiesto->fecha)->format('d/m/Y') }}</td>
            <td class="label">Fecha de Inicio de Traslado:</td>
            <td class="value">{{ \Carbon\Carbon::parse($manifiesto->fecha_inicio_traslado)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="label">Fecha de Fin de Traslado:</td>
            <td class="value">{{ $manifiesto->fecha_fin_traslado ? \Carbon\Carbon::parse($manifiesto->fecha_fin_traslado)->format('d/m/Y') : 'N/A' }}</td>
            <td></td>
            <td></td>
        </tr>
    </table>

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
</body>
</html>
