@extends('adminlte::page')

@section('title', 'Facturación')

@section('content')
<div class="container">
    <h1>Facturación</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th># Factura</th>
                <th>Order ID</th>
                <th>Document ID</th>
                <th>Fecha Orden</th>
                <th>Fecha Entrega</th>
                <th>Código Cliente</th>
                <th>Nombre Cliente</th>
                <th>Chofer</th>
                <th>Destino</th>
                <th># Bultos</th>
                <th>Tipo de Flete</th>
                <th>Valor</th>
                <th>Adicional</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($facturaciones as $facturacion)
                <tr>
                    <!-- Factura -->
                    <td>{{ $facturacion->document->factura ?? 'N/A' }}</td>

                    <!-- Order ID -->
                    <td>{{ $facturacion->order_id }}</td>

                    <!-- Document ID -->
                    <td>{{ $facturacion->document_id }}</td>

                    <!-- Información de la Orden -->
                    <td>{{ $facturacion->order->fechaCreacion ? \Carbon\Carbon::parse($facturacion->order->fechaCreacion)->format('d/m/Y') : 'N/A' }}</td>
                    <td>{{ $facturacion->order->fechaEntrega ? \Carbon\Carbon::parse($facturacion->order->fechaEntrega)->format('d/m/Y') : 'N/A' }}</td>
                    <td>{{ $facturacion->order->direccionDestinatario->cliente->codigoCliente ?? 'N/A' }}</td>
                    <td>{{ $facturacion->order->direccionDestinatario->cliente->razonSocial ?? 'N/A' }}</td>

                    <!-- Información del Manifiesto -->
                    <td>{{ $facturacion->manifiesto->conductor->nombre ?? 'N/A' }}</td>
                    <td>{{ $facturacion->order->direccionDestinatario->ciudad ?? 'N/A' }}</td>
                    <td>{{ $facturacion->document->cantidad_bultos }}</td>
                    <td>{{ $facturacion->manifiesto->tipoFlete ?? 'N/A' }}</td>

                    <!-- Información de Facturación -->
                    <td>{{ $facturacion->valor }}</td>
                    <td>{{ $facturacion->adicional }}</td>
                    <td>{{ $facturacion->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
