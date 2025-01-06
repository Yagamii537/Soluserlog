@extends('adminlte::page')

@section('title', 'Lista de Camiones')

@section('adminlte_css')
    @vite(['resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('content_header')
<a href="{{ route('admin.camiones.create') }}" class="btn btn-primary btn-sm float-right">Nuevo Camión</a>
<h1><i class="fas fa-truck mr-4 mt-2 mb-2"></i>Lista de Camiones</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Foto</th>
                    <th>Número de Placa</th>
                    <th>Modelo</th>
                    <th>Marca</th>
                    <th>Capacidad de Carga</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($camiones as $camion)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if ($camion->foto)
                            <img src="{{ asset('storage/' . $camion->foto) }}" alt="Foto del Camión" style="width: 50px; height: auto;">
                        @else
                            Sin Foto
                        @endif
                    </td>
                    <td>{{ $camion->numero_placa }}</td>
                    <td>{{ $camion->modelo }}</td>
                    <td>{{ $camion->marca }}</td>
                    <td>{{ $camion->capacidad_carga }} kg</td>
                    <td>
                        <a href="{{ route('admin.camiones.edit', $camion->id) }}" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a>
                        <form action="{{ route('admin.camiones.destroy', $camion->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')"><i class="fa fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop
