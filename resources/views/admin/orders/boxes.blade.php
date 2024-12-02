<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido #{{ $order->id }}</title>
    <style>
        @page {
            size: 10.2cm 7.6cm;
            margin: 0;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
        }

        .ticket {
            width: 100%;

            padding: 3px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            margin-top: 3px;
        }

        .header-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: middle;
            padding: 0 8px;
        }

        .header-table .logo-cell {
            text-align: left;
            width: 20%;
        }

        .header-table .text-cell {
            text-align: left;
            font-size: 18px;
            font-weight: bold;

        }

        .header-table .qr-cell {
            text-align: right;
            width: 30%;
        }

        .qr-code {
            height: 50px;
        }

        .content-table {

            border-collapse: collapse;
            font-size: 12px;
            margin-top: 10px;
        }

        .content-table td {
            padding: 2px;

        }

        .content-table .label {
            font-weight: bold;
            width: 30%;
        }

        .content-table .value {
            width: 70%;
        }

        .content-table .label1 {
            font-weight: bold;
            width: 30%;
        }

        .content-table .value1 {
            width: 20%;
        }

        .content-table .label2 {
            font-weight: bold;
            width: 30%;
        }

        .content-table .value2 {
            font-weight: bold;
            width: 20%;
        }
    </style>
</head>

<body>
    @foreach ($order->documents as $document)

        @for ($i = 1; $i <= $document->cantidad_bultos; $i++)
            <div class="ticket">
                <!-- Header -->
                <table class="header-table">
                    <tr>
                        <td class="logo-cell">
                            <img src="{{ $logoBase64 }}" alt="Logo" class="logo">
                        </td>
                        <td class="text-cell" width="30%">SULOSERLOG</td>
                        <td class="text-cell" width="2%"><p>{{ $i}}/{{ $document->cantidad_bultos }}</p></td>
                        <td class="qr-cell">
                            <img src="{{ $qrCodeBase64 }}" alt="QR Code" class="qr-code">
                        </td>
                    </tr>
                </table>

                <!-- Contenido -->
                <table class="content-table">
                    <tr>
                        <td class="label">Tracking:</td>
                        <td class="value">{{ $order->tracking_number }}</td>
                    </tr>
                    <tr>
                        <td class="label">Fecha:</td>
                        <td class="value">{{ $order->fechaEntrega }}</td>
                    </tr>
                    <tr>
                        <td class="label1">Bultos:</td>
                        <td class="value1">{{ $document->cantidad_bultos }} CAJAS</td>

                        <td class="label2"># Remito:</td>
                        <td class="value2">

                            {{ $document->factura }}

                        </td>
                    </tr>
                    <tr>
                        <td class="label">Kilogramos:</td>
                        <td class="value1">{{$document->cantidad_kg}}</td>
                    </tr>
                    <tr>
                        <td class="label">Remitente:</td>
                        <td class="value">{{ $order->direccionRemitente->cliente->razonSocial }}</td>
                    </tr>
                    <tr>
                        <td class="label">Destinatario:</td>
                        <td class="value">{{ $order->direccionDestinatario->cliente->razonSocial }}</td>
                    </tr>
                    <tr>
                        <td class="label">Destino:</td>
                        <td class="value">{{ $order->direccionDestinatario->provincia }}</td>
                    </tr>
                </table>
            </div>
        @endfor
    @endforeach
</body>

</html>
