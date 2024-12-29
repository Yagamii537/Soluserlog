@extends('adminlte::page')

@section('title', 'Guías Creadas')

@section('content_header')
    <a href="{{ route('admin.guias.select_manifiesto') }}" class="btn btn-primary btn-sm float-right">Seleccionar Manifiesto</a>
    <h1>Listado de Guías Creadas</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Número de Manifiesto</th>
                    <th>Empresa</th>
                    <th>Conductor</th>
                    <th>Ayudante</th>
                    <th>Camión</th>
                    <th>Fecha de Emisión</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($guias as $guia)
                <tr>
                    <td>{{ $guia->id }}</td>
                    <td>{{ $guia->manifiesto->numero_manifiesto }}</td>
                    <td>{{ $guia->empresa }}</td>
                    <td>{{ $guia->conductor->nombre }}</td>
                    <td>{{ $guia->ayudante ?? 'N/A' }}</td>
                    <td>{{ $guia->manifiesto->camion->numero_placa }}</td>
                    <td>{{ \Carbon\Carbon::parse($guia->fecha_emision)->format('d/m/Y') }}</td>
                    <td>
                        <!-- Botón para ver el PDF -->
                        <a href="{{ route('admin.guias.pdf', $guia->id) }}" class="btn btn-info btn-sm" target="_blank">
                            Ver PDF
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop
