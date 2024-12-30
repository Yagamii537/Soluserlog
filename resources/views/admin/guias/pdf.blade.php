<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Guía de Ruta</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 20px; }
        .header img { max-width: 150px; margin-bottom: 10px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f4f4f4; }
        .table tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body>
    <div class="header">
        <!-- Logo -->
        <img src="{{ $logoPath }}" alt="Logo">
        <!-- Título -->
        <h1>Guía de Ruta</h1>
    </div>

    <table style="width: 80%; margin: 0 auto; border-collapse: collapse; text-align: left; font-size: 14px;">
        <tr>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Empresa:</strong> {{ $guia->empresa }}</td>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Origen:</strong> {{ $guia->origen }}</td>

        </tr>
        <tr>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Conductor:</strong> {{ $guia->conductor->nombre }}</td>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Tipo Vehiculo:</strong> {{ $guia->manifiesto->tipoFlete }}</td>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Numero Guia:</strong> {{ $guia->numero_guia }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Ayudante:</strong> {{ $guia->ayudante->nombre }}</td>
            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Fecha de Emisión:</strong> {{ \Carbon\Carbon::parse($guia->fecha_emision)->format('d/m/Y') }}</td>

            <td style="padding: 10px; border-bottom: 1px solid #ddd;"><strong>Placa:</strong> {{ $guia->manifiesto->camion->numero_placa }}</td>
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
                    <td>{{ \Carbon\Carbon::parse($order->fechaCreacion)->format('d/m/Y') }}</td>
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
