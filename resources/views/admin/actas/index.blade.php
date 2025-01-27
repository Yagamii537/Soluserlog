@extends('adminlte::page')

@section('title', 'Actas')

@section('content')
<div class="container">
    <h1><i class="fas fa-folder-open mr-2"></i>Actas</h1>


    <!-- Filtro de fechas -->
    <form method="GET" action="{{ route('admin.actas.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="start_date">Fecha Inicio:</label>
                <input type="date" name="start_date" class="form-control" placeholder="Fecha inicio" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-4">
                <label for="end_date">Fecha Fin:</label>
                <input type="date" name="end_date" class="form-control" placeholder="Fecha fin" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('admin.actas.index') }}" class="btn btn-secondary ml-2">Limpiar</a>
            </div>
        </div>
    </form>

    <a href="{{ route('admin.actas.descargarExcel', request()->only(['start_date', 'end_date'])) }}" class="btn btn-sm btn-success mb-3">
        <i class="fas fa-file-excel"></i> Descargar Excel
    </a>

    <a href="{{ route('admin.actas.descargarPdf', request()->only(['start_date', 'end_date'])) }}" class="btn btn-sm btn-danger mb-3">
        <i class="fas fa-file-pdf"></i> Descargar PDF
    </a>


    <!-- Tabla de actas -->
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha del Pedido</th>
                <th>Código</th>
                <th>Razón Social</th>
                <th># Factura</th>
                <th># Documento</th>
                <th>Observación</th>
                <th>Fecha de Entrega</th>
                <th>Hora de Llegada</th>
                <th>Persona que Recibió</th>
                <th>Coordenadas</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                @foreach ($order->documents as $document)
                    <tr>
                        <td>{{ $loop->parent->iteration }}</td>
                        <td>{{ $order->fechaCreacion }}</td>
                        <td>{{ $order->direccionDestinatario->cliente->codigoCliente ?? 'No asociada' }}</td>
                        <td>{{ $order->direccionDestinatario->cliente->razonSocial ?? 'No asociada' }}</td>
                        <td>{{ $document->factura ?? 'N/A' }}</td>
                        <td>{{ $document->n_documento ?? 'N/A' }}</td>
                        <td>{{ $document->observaciones ?? 'N/A' }}</td>

                        <td>
                            @if ($order->manifiestos->isNotEmpty() && $order->manifiestos->first()->guias->isNotEmpty() && $order->manifiestos->first()->guias->first()->bitacora)
                                {{ $order->manifiestos->first()->guias->first()->bitacora->detalles->where('order_id', $order->id)->first()->fechaDestino ?? 'No asociada' }}
                            @else
                                No asociada
                            @endif
                        </td>
                        <td>
                            @if ($order->manifiestos->isNotEmpty() && $order->manifiestos->first()->guias->isNotEmpty() && $order->manifiestos->first()->guias->first()->bitacora)
                                {{ $order->manifiestos->first()->guias->first()->bitacora->detalles->where('order_id', $order->id)->first()->hora_destino_llegada ?? 'No asociada' }}
                            @else
                                No asociada
                            @endif
                        </td>
                        <td>
                            @if ($order->manifiestos->isNotEmpty() && $order->manifiestos->first()->guias->isNotEmpty() && $order->manifiestos->first()->guias->first()->bitacora)
                                {{ $order->manifiestos->first()->guias->first()->bitacora->detalles->where('order_id', $order->id)->first()->persona ?? 'No asociada' }}
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
</div>
@endsection
