@extends('adminlte::page')

@section('title', 'Actas')

@section('content')
<div class="container">
    <h1>Actas</h1>

    <!-- Filtro de fechas -->
    <form method="GET" action="{{ route('admin.actas.index') }}">
        <div class="row">
            <div class="col-md-3">
                <input type="date" name="start_date" class="form-control" placeholder="Fecha inicio" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="end_date" class="form-control" placeholder="Fecha fin" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary">Filtrar</button>
            </div>
        </div>
    </form>

    <!-- Tabla de actas -->
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha del Pedido</th>
                <th>Codigo</th>
                <th>Razón Social</th>
                <th># Factura</th>
                <th># Guia</th>
                <th>Observación</th>
                <th>Fecha de Entrega</th>
                <th>Hora de Llegada</th>
                <th>Persona que Recibio</th>
                <th>Coordenadas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                @foreach ($order->documents as $document)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $order->fechaCreacion ? \Carbon\Carbon::parse($order->fechaCreacion)->format('d/m/Y') : 'N/A' }}</td>
                    <td>{{ $order->direccionDestinatario->cliente->codigoCliente ?? 'No asociada' }}</td>
                    <td>{{ $order->direccionDestinatario->cliente->razonSocial ?? 'No asociada' }}</td>
                    <td>{{ $document->factura ?? 'N/A' }}</td>
                    <td>{{ $document->n_documento ?? 'N/A' }}</td>
                    <td>{{ $document->observaciones ?? 'N/A' }}</td>
                    <td>
                        @if ($order->manifiestos->isNotEmpty() && optional($order->manifiestos->first()->guias->first()->bitacora)->detalles->isNotEmpty())
                            {{ $order->manifiestos->first()->guias->first()->bitacora->detalles->first()->fechaDestino ?? 'No asociada' }}
                        @else
                            No asociada
                        @endif
                    </td>
                    <td>
                        @if ($order->manifiestos->isNotEmpty() && optional($order->manifiestos->first()->guias->first()->bitacora)->detalles->isNotEmpty())
                            {{ $order->manifiestos->first()->guias->first()->bitacora->detalles->first()->hora_destino_llegada ?? 'No asociada' }}
                        @else
                            No asociada
                        @endif
                    </td>
                    <td>
                        @if ($order->manifiestos->isNotEmpty() && optional($order->manifiestos->first()->guias->first()->bitacora)->detalles->isNotEmpty())
                            {{ $order->manifiestos->first()->guias->first()->bitacora->detalles->first()->persona ?? 'No asociada' }}
                        @else
                            No asociada
                        @endif
                    </td>
                    <td>
                        {{ $order->direccionDestinatario->latitud ?? 'N/A' }},
                        {{ $order->direccionDestinatario->longitud ?? 'N/A' }}
                    </td>
                </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <!-- Paginación -->
    {{ $orders->links() }}
</div>
@endsection
