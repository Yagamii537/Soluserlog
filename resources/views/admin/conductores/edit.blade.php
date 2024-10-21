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

        {!! Form::model($conductor, ['route' => ['admin.conductores.update', $conductor], 'method' => 'PUT']) !!}

    <div class="form-group">
        {!! Form::label('nombre', 'Nombre:') !!}
        {!! Form::text('nombre', null, ['class' => 'form-control', 'required']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('numero_licencia', 'Número de Licencia:') !!}
        {!! Form::text('numero_licencia', null, ['class' => 'form-control', 'required']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('telefono', 'Teléfono:') !!}
        {!! Form::text('telefono', null, ['class' => 'form-control', 'required']) !!}
    </div>

    {!! Form::submit('Actualizar Conductor', ['class' => 'btn btn-primary']) !!}

    {!! Form::close() !!}
    </div>
</div>


@stop



@section('js')

@stop


