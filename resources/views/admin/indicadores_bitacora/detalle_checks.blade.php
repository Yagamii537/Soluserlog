@extends('adminlte::page')

@section('title', 'Detalle de Bitácoras por Check')

@section('content')
<div class="container">
    <h3 class="mb-4">Detalle de Bitácoras - <strong>{{ strtoupper($opcion) }}</strong></h3>

    @if ($bitacoras->isEmpty())
        <div class="alert alert-info">No hay bitácoras registradas para esta opción.</div>
    @else
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Pedido ID</th>
                    <th>Tipo</th>
                    <th>Persona que Recibe</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bitacoras as $index => $detalle)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $detalle->created_at->format('d/m/Y') }}</td>
                        <td>{{ $detalle->order_id }}</td>
                        <td>{{ ucfirst($detalle->tipo_check) }}</td>
                        <td>{{ $detalle->persona ?? 'N/A' }}</td>
                        <td>
                            @if($detalle->tipo_check === 'carga')
                                {{ $detalle->novedades_carga ?? 'Sin observaciones' }}
                            @else
                                {{ $detalle->novedades_destino ?? 'Sin observaciones' }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ route('admin.indicadores_bitacora.index') }}" class="btn btn-secondary mt-3">Volver</a>
</div>
@endsection
