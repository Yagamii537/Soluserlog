<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Facturación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f4f4f4;
        }
        .header-table {
            width: 100%;
            font-size: 14px;
            border: none;
            margin-bottom: 5px; /* Reduce el espacio */
        }

        .header-table td {
            border: none;
            padding: 5px 0; /* Reduce padding superior e inferior */
            vertical-align: middle;
        }

        .header-logo {
            width: 150px;
            text-align: left;
        }

        .header-text {
            text-align: center;
            vertical-align: middle;
            width: 73%; /* Hace que ocupe todo el espacio disponible */
        }

        .code-text {
            text-align: right;
            font-weight: bold;
            padding-right: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
        }
        .header p {
            margin: 0;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <table class="header-table">
        <tr>
            <td class="header-logo">
                <img src="{{ $logoPath }}" alt="Logo" width="100%">
            </td>
            <td class="header-text">
                <h2>PROCEDIMIENTO OPERATIVO ESTÁNDAR</h2>
                <h3>Carga y Descarga de Productos</h3>
                <h3><b>Reporte de fletes</b></h3>
            </td>
            <td class="code-text">
                <b>DO-POE-04-F6-V00</b>
            </td>
        </tr>
    </table>
    <table>
        <thead>
            <tr>
                <th># Factura</th>
                <th>Fecha</th>
                <th>Fecha Entrega</th>
                <th>Código Cliente</th>
                <th>Nombre Cliente</th>
                <th>Chofer</th>
                <th>Destino</th>
                <th># Bultos</th>
                <th>Tipo de Flete</th>
                <th>Tonelaje</th>
                <th>Valor</th>
                <th>Adicional</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($facturaciones as $facturacion)
                <tr>
                    <td>{{ $facturacion->document->factura ?? 'N/A' }}</td>
                    <td>{{ $facturacion->order->fechaCreacion }}</td>
                    <td>{{ $facturacion->order->fechaEntrega ? \Carbon\Carbon::parse($facturacion->order->fechaEntrega)->format('d/m/Y') : 'N/A' }}</td>
                    <td>{{ $facturacion->order->direccionDestinatario->cliente->codigoCliente ?? 'N/A' }}</td>
                    <td>{{ $facturacion->order->direccionDestinatario->cliente->razonSocial ?? 'N/A' }}</td>
                    <td>{{ $facturacion->manifiesto->conductor->nombre ?? 'N/A' }}</td>
                    <td>{{ $facturacion->order->direccionDestinatario->ciudad ?? 'N/A' }}</td>
                    <td>{{ $facturacion->document->cantidad_bultos }}</td>
                    <td>{{ $facturacion->manifiesto->tipoFlete ?? 'N/A' }}</td>
                    <td>
                        {{ isset($facturacion->manifiesto->camion->capacidad_carga)
                            ? number_format($facturacion->manifiesto->camion->capacidad_carga / 1000, 2) . ' T'
                            : 'N/A' }}
                    </td>
                    <td>{{ number_format($facturacion->valor, 2) }}</td>
                    <td>{{ number_format($facturacion->adicional, 2) }}</td>
                    <td>{{ number_format($facturacion->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
