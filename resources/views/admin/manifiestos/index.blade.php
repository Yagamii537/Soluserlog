@extends('adminlte::page')

@section('title', 'Camiones')

{{-- icono de carga --}}
@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">Cargando</h4>
@stop
@section('adminlte_css')
    @vite(['resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('content_header')
<a href="{{route('admin.manifiestos.create')}}" class="btn btn-primary btn-sm float-right">Nuevo Manifiesto</a>
<h1 ><i class="fa fa-book mr-4 mt-2 mb-2"></i>Lista de Manifiestos</h1>
@stop

@section('content')
@if (session('info'))
    <div class="alert alert-danger">
        <strong>{{session('info')}}</strong>
    </div>

@endif

<div class="container">
    <div class="card">
        <div class="card-body">

            <table class="table table-striped">
                <thead>
                    <th scope="col">Id</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Camión</th>
                    <th scope="col">Pedido</th>
                    <th scope="col">Descripción</th>
                    <th scope="col" colspan="2">Acciones</th>
                </thead>
                <tbody>

                    @foreach ($manifiestos  as $manifiesto)
                        <tr>
                            <td>{{ $manifiesto->id}}</td>
                            <td>{{ $manifiesto->fecha }}</td>
                            <td>{{ $manifiesto->camion->numero_placa}}</td>
                            <td>{{ $manifiesto->order->id}}</td>
                            <td>{{ $manifiesto->descripcion }}</td>

                            <td width="10px">
                                <a href="{{route('admin.manifiestos.edit',$manifiesto)}}" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a>
                            </td>
                            <td width="10px">


                                <!-- Formulario de eliminación -->
                                <form action="{{route('admin.manifiestos.destroy', $manifiesto) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


@stop
