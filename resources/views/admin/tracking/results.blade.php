@extends('adminlte::page')

@section('title', 'Resultados de Pedidos')

@section('content')
<div class="container">
    <h1>Resultados de Tracking</h1>

    @if ($orders->isEmpty())
        <p>No se encontraron pedidos asociados.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th># Pedido</th>
                    <th>Tracking</th>
                    <th>Remitente</th>
                    <th>Destinatario</th>
                    <th>Estado</th>
                    <th># Facturas</th>

                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->tracking_number ?? 'N/A' }}</td>
                        <td>{{ $order->direccionRemitente->cliente->razonSocial }}</td>
                        <td>{{ $order->direccionDestinatario->cliente->razonSocial }}</td>
                        <td>{{ $order->estado }}</td>
                        <td>{{ $order->documents->pluck('factura')->join(', ') }}</td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
