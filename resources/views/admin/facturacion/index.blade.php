@extends('adminlte::page')

@section('title', 'Facturación')

@section('content')
<div class="container">
    <h1><i class="fas fa-file-invoice-dollar mr-2"></i>Facturación</h1>

    <!-- Filtro de intervalo de fechas -->
    <form method="GET" action="{{ route('admin.facturacion.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="start_date">Fecha Inicio:</label>
                <input type="date" id="start_date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-4">
                <label for="end_date">Fecha Fin:</label>
                <input type="date" id="end_date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <a href="{{ route('admin.facturacion.index') }}" class="btn btn-secondary ml-2">Limpiar</a>
            </div>
        </div>
    </form>

    <a href="{{ route('admin.facturacion.reporte.excel', request()->only(['start_date', 'end_date'])) }}" class="btn btn-sm btn-success mb-3">
        <i class="fas fa-file-excel"></i> Descargar Excel
    </a>


    <table class="table table-bordered">
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
                <th>Valor</th>
                <th>Adicional</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($facturaciones as $facturacion)
                <tr>
                    <!-- Factura -->
                    <td>{{ $facturacion->document->factura ?? 'N/A' }}</td>



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
                    <td>{{ number_format($facturacion->valor, 2) }}</td>
                    <td>{{ number_format($facturacion->adicional, 2) }}</td>
                    <td>{{ number_format($facturacion->total, 2) }}</td>
                    <td>
                        <a href="{{ route('admin.facturacion.edit', $facturacion) }}" class="btn btn-sm btn-primary">Editar</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
