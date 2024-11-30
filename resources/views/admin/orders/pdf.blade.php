<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido #{{ $order->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
        }
        .ticket {
            border: 1px solid #000;
            padding: 10px;
            margin: 10px 20px; /* Ajusta los márgenes según sea necesario */
            display: inline-block; /* inline-block para que respete el ancho y el contenido */
            width: 380px; /* Ancho fijo de 300px, ajusta según sea necesario */
        }
        .header {
            display: flex;
            align-items: center; /* Alinear verticalmente al centro */
            margin-bottom: 20px;
        }
        th,td {

            text-align: left;
        }
        .logo {

            margin-right: 20px; /* Espacio entre la imagen y el texto */
            /*argin-right: 20px; /* Espacio entre la imagen y el texto */
        }
        .title {
            font-size: 24px;
            font-weight: bold;
        }
        .details {
            font-size: 14px;
        }
        .container {
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="ticket">

        <div class="header">
            <img src="{{ asset('vendor/adminlte/dist/img/logof.png') }}" class="logo" alt="Logo">

            <!-- Texto a la derecha de la imagen -->
            <div>
                <div class="title">SULOSERLOG</div>
                <div class="details">Servicios Logísticos CIA LTDA</div>
            </div>
        </div>

        <table>
            <tr>
                <th>Fecha de Entrega</th>
                <td>{{ $order->fechaEntrega }}</td>
            </tr>
            <tr>
                <th>Bultos</th>
                <td>{{ $order->totaBultos }}</td>
            </tr>
            <tr>
                <th>Remitente</th>
                <td>{{ $order->direccionRemitente->cliente->razonSocial }}</td>
            </tr>
            <tr>
                <th>Destinatario</th>
                <td>{{ $order->direccionDestinatario->cliente->razonSocial }}</td>
            </tr>




        </table>
    </div>
</body>
</html>
