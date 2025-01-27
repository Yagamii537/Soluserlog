<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Guía de Ruta</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.5; margin: 0; padding: 0; }
        .header { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .header td { vertical-align: middle; text-align: center; }
        .header img { max-width: 100px; }
        .title-section { text-align: center; }
        .title-section h3, .title-section h1 { margin: 5px 0; }
        .title-section h1 { font-size: 18px; }
        .code-section { text-align: right; padding-right: 10px; font-weight: bold; }
        .details-table { width: 80%; margin: 0 auto; border-collapse: collapse; text-align: left; font-size: 14px; }
        .details-table td { padding: 10px; border-bottom: 1px solid #ddd; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f4f4f4; }
        .table tr:nth-child(even) { background-color: #f9f9f9; }
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
                    <h1>Guía de Ruta</h1>
                </div>
            </td>
            <td style="width: 20%; text-align: right;">
                <!-- Código a la derecha -->
                <div class="code-section">DO-POE-04-F0-V01</div>
            </td>
        </tr>
    </table>

    <table class="details-table">
        <tr>
            <td><strong>Empresa:</strong> {{ $guia->empresa }}</td>
            <td><strong>Origen:</strong> {{ $guia->origen }}</td>
        </tr>
        <tr>
            <td><strong>Conductor:</strong> {{ $guia->manifiesto->conductor->nombre }}</td>
            <td><strong>Tipo Vehículo:</strong> {{ $guia->manifiesto->tipoFlete }}</td>
            <td><strong>Número Guía:</strong> {{ $guia->numero_guia }}</td>
        </tr>
        <tr>
            <td><strong>Ayudante:</strong> {{ $guia->manifiesto->ayudante->nombre }}</td>
            <td><strong>Fecha de Emisión:</strong> {{ \Carbon\Carbon::parse($guia->fecha_emision)->format('d/m/Y') }}</td>
            <td><strong>Placa:</strong> {{ $guia->manifiesto->camion->numero_placa }}</td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>Ítems</th>
                <th># Facturas</th>
                <th>Fecha de Order</th>
                <th>Código Cliente</th>
                <th>Nombre del Cliente</th>
                <th>Dirección de la Entrega</th>
                <th>Ciudad</th>
                <th>Bultos</th>
                <th>Observación</th>
                <th>Fecha de Entrega</th>
                <th>Coordenadas</th>
            </tr>
        </thead>
        <tbody>
            @php $counter = 1; @endphp
            @foreach ($orders as $order)
                @php
                    // Agrupamos los datos del order
                    $totalBultos = $order->documents->sum('cantidad_bultos');
                    $facturas = $order->documents->pluck('factura')->implode(', ');
                    $cantidadDocumentos = $order->documents->count();
                @endphp
                <tr>
                    <td>{{ $cantidadDocumentos }}</td>
                    <td>{{ $facturas }}</td>
                    <td>{{ $order->fechaCreacion }}</td>
                    <td>{{ $order->direccionDestinatario->cliente->codigoCliente }}</td>
                    <td>{{ $order->direccionDestinatario->cliente->razonSocial }}</td>
                    <td>{{ $order->direccionDestinatario->direccion }}</td>
                    <td>{{ $order->direccionDestinatario->ciudad }}</td>
                    <td>{{ $totalBultos }}</td>
                    <td>{{ $order->observacion ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($order->fechaEntrega)->format('d/m/Y') }}</td>
                    <td>{{ $order->direccionDestinatario->latitud }}, {{ $order->direccionDestinatario->longitud }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
