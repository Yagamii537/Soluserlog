@extends('adminlte::page')

@section('title', 'Manifiestos')

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
<h1><i class="fa fa-book mr-4 mt-2 mb-2"></i>Lista de Manifiestos</h1>
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
                <thead style="font-size: 14px;">
                    <th scope="col">Id</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Camión</th>
                    <th scope="col">Fecha Inicio Traslado</th>
                    <th scope="col">Fecha Fin Traslado</th>
                    <th scope="col">Bultos</th>
                    <th scope="col">Kilos</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Pedidos</th>
                    <th scope="col">Reporte</th>
                    <th scope="col" colspan="2">Acciones</th>
                </thead>
                <tbody style="font-size: 14px;">
                    @foreach ($manifiestos as $manifiesto)
                        <tr>
                            <td>{{ $manifiesto->id }}</td>
                            <td>{{ $manifiesto->fecha }}</td>
                            <td>{{ $manifiesto->camion->numero_placa }}</td>
                            <td>{{ $manifiesto->fecha_inicio_traslado }}</td>
                            <td>{{ $manifiesto->fecha_fin_traslado }}</td>
                            <td>{{ $manifiesto->bultos }}</td>
                            <td>{{ $manifiesto->kilos }}</td>
                            <td>
                                @if ($manifiesto->estado==0)
                                    <p>Pendiente</p>
                                @else
                                    <p>Generado</p>
                                @endif
                            </td>
                            <td>
                                <!-- Listar los pedidos asociados al manifiesto -->
                                @foreach ($manifiesto->orders as $order)
                                    <span class="badge badge-primary">Pedido #{{ $order->id }}</span>
                                @endforeach
                            </td>

                            <td>
                                <a href="{{route('admin.manifiestos.pdf', $manifiesto)}}" class="btn btn-warning btn-sm">

                                        <i class="fa fa-file-pdf"></i>

                                </a>
                            </td>
                            <td width="10px">
                                <a href="{{route('admin.manifiestos.edit', $manifiesto)}}" class="btn btn-success btn-sm">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            </td>
                            <td width="10px">
                                <!-- Formulario de eliminación -->
                                <form action="{{ route('admin.manifiestos.destroy', $manifiesto) }}" method="POST">
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
