@extends('adminlte::page')

@section('title', 'Lista de Conductores')

@section('adminlte_css')
    @vite(['resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('content_header')
<a href="{{ route('admin.conductores.create') }}" class="btn btn-primary btn-sm float-right">Nuevo Conductor</a>
<h1><i class="fas fa-user-tie mr-4 mt-2 mb-2"></i>Lista de Conductores</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Foto</th>
                    <th>Nombre</th>
                    <th>Número de Licencia</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($conductores as $conductor)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if ($conductor->foto)
                            <img src="{{ asset('storage/' . $conductor->foto) }}" alt="Foto del Conductor" style="width: 50px; height: auto;">
                        @else
                            Sin Foto
                        @endif
                    </td>
                    <td>{{ $conductor->nombre }}</td>
                    <td>{{ $conductor->numero_licencia }}</td>
                    <td>{{ $conductor->telefono }}</td>
                    <td>
                        <a href="{{ route('admin.conductores.edit', $conductor->id) }}" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a>
                        <form action="{{ route('admin.conductores.destroy', $conductor->id) }}" method="POST" style="display:inline-block;">
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
