@extends('adminlte::page')

@section('title', 'Seleccionar Manifiesto')

@section('content_header')
    <a href="{{ route('admin.guias.index') }}" class="btn btn-secondary btn-sm float-right mr-2">Ver Guías Creadas</a>
    <h1>Seleccionar Manifiesto para Generar Guía</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Número de Manifiesto</th>
                    <th>Camión</th>
                    <th>Fecha del Manifiesto</th>
                    <th>Bultos</th>
                    <th>Kilos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($manifiestos as $manifiesto)
                <tr>
                    <td>{{ $manifiesto->id }}</td>
                    <td>{{ $manifiesto->numero_manifiesto }}</td>
                    <td>{{ $manifiesto->camion->numero_placa }} - {{ $manifiesto->camion->marca }}</td>
                    <td>{{ \Carbon\Carbon::parse($manifiesto->fecha)->format('d/m/Y') }}</td>
                    <td>{{ $manifiesto->bultos }}</td>
                    <td>{{ $manifiesto->kilos }}</td>
                    <td>
                        <a href="{{ route('admin.guias.create', $manifiesto->id) }}" class="btn btn-primary btn-sm">
                            Generar Guía
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop
