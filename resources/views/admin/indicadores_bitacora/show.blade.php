@extends('adminlte::page')

@section('title', 'Detalle de Checklist')

@section('content')
<div class="container">
    <h3>Detalle del checklist: <strong>{{ strtoupper($opcion) }}</strong> ({{ $tipo }})</h3>
    <p>Fecha: {{ $fecha }}</p>

    @if ($checks->isEmpty())
        <div class="alert alert-warning">
            No hay registros encontrados para este criterio.
        </div>
    @else
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Pedido</th>
                    <th>Cliente</th>
                    <th>Ciudad</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($checks as $check)
                    <tr>
                        <td>{{ $check->detalleBitacora->order->id ?? '-' }}</td>
                        <td>{{ $check->detalleBitacora->order->direccionDestinatario->cliente->razonSocial ?? 'No asociado' }}</td>
                        <td>{{ $check->detalleBitacora->order->direccionDestinatario->ciudad ?? '-' }}</td>
                        <td>
                            {{ $tipo === 'carga' ? ($check->detalleBitacora->novedades_carga ?? '-') : ($check->detalleBitacora->novedades_destino ?? '-') }}
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <a href="{{ route('admin.indicadores_bitacora.index') }}" class="btn btn-primary mb-3">
    <i class="fas fa-arrow-left"></i> Volver a indicadores
</a>
</div>
@endsection
