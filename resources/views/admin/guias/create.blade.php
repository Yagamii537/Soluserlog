@extends('adminlte::page')

@section('title', 'Crear Guía')

@section('adminlte_css')
    @vite(['resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('content_header')
    <h1>Crear Guía para el Manifiesto #{{ $manifiesto->numero_manifiesto }}</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        {!! Form::open(['route' => 'admin.guias.store', 'method' => 'POST']) !!}

        <!-- Mostrar información del manifiesto -->
        <div class="form-group">
            <label>Número de Manifiesto:</label>
            <input type="text" class="form-control" value="{{ $manifiesto->numero_manifiesto }}" readonly>
        </div>
        <div class="form-group">
            <label>Camión:</label>
            <input type="text" class="form-control" value="{{ $manifiesto->camion->numero_placa }} - {{ $manifiesto->camion->marca }}" readonly>
        </div>
        <div class="form-group">
            <label>Fecha del Manifiesto:</label>
            <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($manifiesto->fecha)->format('d/m/Y') }}" readonly>
        </div>

        <!-- Mostrar conductor asignado -->
        <div class="form-group">
            {!! Form::label('conductor', 'Conductor Asignado:') !!}
            <input type="text" class="form-control" value="{{ $manifiesto->conductor->nombre }}" readonly>
            {!! Form::hidden('conductor_id', $manifiesto->conductor->id) !!}
        </div>

        <!-- Campo de empresa -->
        <div class="form-group">
            {!! Form::label('empresa', 'Empresa:') !!}
            {!! Form::text('empresa', $clienteOrigen, ['class' => 'form-control', 'readonly', 'required']) !!}
        </div>

        <!-- Campo de origen -->
        <div class="form-group">
            {!! Form::label('origen', 'Origen:') !!}
            {!! Form::text('origen', null, ['class' => 'form-control', 'required']) !!}
        </div>

        <!-- Seleccionar ayudante -->
        <div class="form-group">
            {!! Form::label('ayudante_id', 'Ayudante (opcional):') !!}
            {!! Form::select('ayudante_id', $ayudantes->pluck('nombre', 'id'), null, ['class' => 'form-control', 'placeholder' => 'Seleccione un ayudante']) !!}
        </div>

        <!-- Guardar manifiesto_id en un campo oculto -->
        {!! Form::hidden('manifiesto_id', $manifiesto->id) !!}

        {!! Form::submit('Generar Guía', ['class' => 'btn btn-success']) !!}
        {!! Form::close() !!}
    </div>
</div>
@stop
