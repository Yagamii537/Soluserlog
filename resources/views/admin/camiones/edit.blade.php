@extends('adminlte::page')

@section('title', 'Dashboard')

{{-- icono de carga --}}
@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">Cargando</h4>
@stop

@section('adminlte_css')
    @vite(['resources/js/app.js'])
    <style>
        /* Estilo personalizado para el modal */
        #map {
            height: 400px;
            width: 100%;
        }
    </style>

@stop

@section('content_header')
    <h1>Editar CLiente</h1>
@stop

@section('content')
@if (session('info'))
    <div class="alert alert-success">
        <strong>{{session('info')}}</strong>
    </div>

@endif
<div class="card">
    <div class="card-body">

        {!! Form::model($camion, ['route' => ['admin.camiones.update', $camion], 'method' => 'PUT']) !!}

    <div class="form-group">
        {!! Form::label('numero_placa', 'Número de Placa:') !!}
        {!! Form::text('numero_placa', null, ['class' => 'form-control', 'required']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('modelo', 'Modelo:') !!}
        {!! Form::text('modelo', null, ['class' => 'form-control', 'required']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('marca', 'Marca:') !!}
        {!! Form::text('marca', null, ['class' => 'form-control', 'required']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('capacidad_carga', 'Capacidad de Carga (kg):') !!}
        {!! Form::number('capacidad_carga', null, ['class' => 'form-control', 'required']) !!}
    </div>

    {!! Form::submit('Actualizar Camión', ['class' => 'btn btn-primary']) !!}

    {!! Form::close() !!}
    </div>
</div>


@stop



@section('js')

@stop


