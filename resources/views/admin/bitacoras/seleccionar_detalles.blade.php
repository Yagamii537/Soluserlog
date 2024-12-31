@extends('adminlte::page')

@section('title', 'Seleccionar Pedido para Detalle')

@section('content_header')
    <h1>Seleccionar Pedido para Detalle</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <h4>Bitácora de la Guía: #{{ $bitacora->guia->numero_guia }}</h4>
        <h5>Manifiesto: #{{ $bitacora->guia->manifiesto->numero_manifiesto }}</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Pedido</th>
                    <th>Origen</th>
                    <th>Destino</th>
                    <th>Documentos Asociados</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bitacora->guia->manifiesto->orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->direccionRemitente->cliente->razonSocial }}</td>
                        <td>{{ $order->direccionDestinatario->cliente->razonSocial }}</td>
                        <td>
                            @if ($order->documents->count() > 0)

                                    @foreach ($order->documents as $document)
                                    <span class="badge badge-primary">Factura #{{ $document->factura }}</span>

                                    @endforeach

                            @else
                                <span>No hay documentos asociados</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.detalle_bitacoras.edit', ['bitacora' => $bitacora->id, 'order' => $order->id]) }}" class="btn btn-primary">Editar Detalle</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop
