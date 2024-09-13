@extends('adminlte::page')

@section('title', 'Dashboard')

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

<a href="{{route('admin.orders.create')}}" class="btn btn-primary btn-sm float-right">Nuevo Pedido</a>


    <h1 ><i class="fa-solid fa-archive mr-4 mt-2 mb-2"></i>Lista de Pedidos</h1>

@stop

@section('content')
@if (session('info'))
    <div class="alert alert-danger">
        <strong>{{session('info')}}</strong>
    </div>
@endif
    <div class="card">
        <div class="card-body">

            <!-- Formulario DELETE separado dentro del modal -->
            {!! Form::open(['route' => ['admin.orders.destroy', $order->id], 'method' => 'DELETE', 'style' => 'display:inline;']) !!}
            ¿Estás seguro de que deseas eliminar el pedido #{{ $order->id }}?
            {!! Form::submit('Eliminar', ['class' => 'btn btn-danger ml-5 mr-3']) !!}
            <a href="{{route('admin.orders.index')}}" class="btn btn-secondary" >Cancelar</a>
        {!! Form::close() !!}
        </div>
    </div>


@stop


