<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tracking Pedido</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }
        .header-table {
            width: 100%;
            border: 1px solid black;
            margin-bottom: 20px;
        }
        .header-table td {
            text-align: center;
            padding: 5px;
        }
        .header-table .logo {
            width: 20%;
            text-align: left;
        }
        .header-table .title {
            width: 60%;
            font-weight: bold;
            font-size: 18px;
            text-align: center;
        }
        .header-table .code {
            width: 20%;
            font-size: 12px;
            text-align: right;
            vertical-align: top;
        }
        .subheader {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin-top: 10px;
            text-transform: uppercase;
        }
        .images-table {
            width: 100%;
            border: none;
            margin-top: 20px;
        }
        .images-table td {
            border: none;
            text-align: center;
            padding: 5px;
        }
        .images-table img {
            max-width: 150px;
            max-height: 150px;
            border: 1px solid black;
            padding: 5px;
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <table class="header-table">
        <tr>
            <td class="logo">
                <img src="{{ $logoPath }}" alt="Logo">

            </td>
            <td class="title">
                PROCEDIMIENTO OPERATIVO ESTÁNDAR<br>
                MONITOREO DE RUTAS Y REPORTE DE NOVEDADES
            </td>
            <td class="code">
                DO-POE-03-F4-V00
            </td>
        </tr>
    </table>

    <!-- Subtítulo -->
    <div class="subheader">
        TRACKING DE TRANSPORTE SOLUSERLOG
    </div>

    <!-- Información principal -->
    <table>
        <tr>
            <th># FACTURA</th>
            <th>FECHA FACTURA</th>
            <th>FECHA ENTREGA</th>
        </tr>
        <tr>
            <td>{{ $order->documents->pluck('factura')->join(', ') }}</td>
            <td>{{ $order->fechaCreacion }}</td>
            <td>{{ $order->fechaEntrega  }}</td>
        </tr>
    </table>

    <!-- Información adicional -->
    <table>
        <tr>
            <th>CÓDIGO DEL CLIENTE</th>
            <th>NOMBRE DEL CLIENTE</th>
            <th>DIRECCIÓN</th>
            <th>CIUDAD</th>
        </tr>
        <tr>
            <td>{{ $order->direccionDestinatario->cliente->codigoCliente ?? 'N/A' }}</td>
            <td>{{ $order->direccionDestinatario->cliente->razonSocial ?? 'N/A' }}</td>
            <td>{{ $order->direccionDestinatario->direccion ?? 'N/A' }}</td>
            <td>{{ $order->direccionDestinatario->ciudad ?? 'N/A' }}</td>
        </tr>
    </table>

    <!-- Información de descarga -->
    <table>
        <tr>
            <th>HORA DESCARGA</th>
            <th>TEMPERATURA DESCARGA</th>
            <th>HUMEDAD DESCARGA</th>
        </tr>
        @if ($order->manifiestos->count())
            @foreach ($order->manifiestos as $manifiesto)
                @foreach ($manifiesto->guias as $guia)
                    @foreach ($guia->bitacora->detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->hora_descarga ?? 'N/A' }}</td>
                        <td>{{ $detalle->temperatura_destino ?? 'N/A' }}</td>
                        <td>{{ $detalle->humedad_destino ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                @endforeach
            @endforeach
        @else
        <tr>
            <td colspan="3">Sin datos</td>
        </tr>
        @endif
    </table>

    <!-- Evidencia fotográfica -->
    <h3>EVIDENCIA FOTOGRÁFICA</h3>
    <table class="images-table">
        <tr>
            @if ($order->manifiestos->count())
                @foreach ($order->manifiestos as $manifiesto)
                    @foreach ($manifiesto->guias as $guia)
                        @foreach ($guia->bitacora->detalles as $detalle)
                            @foreach ($detalle->images as $image)
                                <td>
                                    <img src="{{ public_path('storage/' . $image->image_path) }}" alt="Evidencia">
                                </td>
                            @endforeach
                        @endforeach
                    @endforeach
                @endforeach
            @else
                <td>Sin imágenes asociadas.</td>
            @endif
        </tr>
    </table>
</body>
</html>
