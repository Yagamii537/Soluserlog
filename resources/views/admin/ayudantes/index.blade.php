@extends('adminlte::page')

@section('title', 'Lista de Ayudantes')

@section('adminlte_css')
    @vite(['resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@stop


@section('content_header')
<a href="{{route('admin.ayudantes.create')}}" class="btn btn-primary btn-sm float-right">Nuevo Ayudante</a>
<h1 ><i class="fas fa-user-friends mr-4 mt-2 mb-2"></i>Lista de Ayudante</h1>
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
                    <th>Cédula</th>
                    <th>Teléfono</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ayudantes as $ayudante)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        @if ($ayudante->foto)
                            <img src="{{ asset('storage/' . $ayudante->foto) }}" alt="Foto de {{ $ayudante->nombre }}" style="width: 50px; height: auto;">
                        @else
                            Sin Foto
                        @endif
                    </td>
                    <td>{{ $ayudante->nombre }}</td>
                    <td>{{ $ayudante->cedula }}</td>
                    <td>{{ $ayudante->telefono }}</td>
                    <td>
                        <a href="{{ route('admin.ayudantes.edit', $ayudante->id) }}" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a>
                        <form action="{{ route('admin.ayudantes.destroy', $ayudante->id) }}" method="POST" style="display:inline-block;">
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
