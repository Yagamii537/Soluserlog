@extends('adminlte::page')

@section('title', 'Crear Guía')

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

        <!-- Seleccionar conductor -->
        <div class="form-group">
            {!! Form::label('conductor_id', 'Conductor:') !!}
            {!! Form::select('conductor_id', $conductores->pluck('nombre', 'id'), null, ['class' => 'form-control', 'placeholder' => 'Seleccione un conductor', 'required']) !!}
        </div>

        <!-- Campo de empresa -->

        <div class="form-group">
            {!! Form::label('empresa', 'Empresa:') !!}
            {!! Form::text('empresa', $clienteOrigen, ['class' => 'form-control', 'readonly', 'required']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('origen', 'Origen:') !!}
            {!! Form::text('origen', null, ['class' => 'form-control', 'required']) !!}
        </div>


        <!-- Campo opcional de ayudante -->
        <div class="form-group">
            {!! Form::label('ayudante', 'Ayudante (opcional):') !!}
            {!! Form::text('ayudante', null, ['class' => 'form-control']) !!}
        </div>

        <!-- Guardar manifiesto_id en un campo oculto -->
        {!! Form::hidden('manifiesto_id', $manifiesto->id) !!}

        {!! Form::submit('Generar Guía', ['class' => 'btn btn-success']) !!}
        {!! Form::close() !!}
    </div>
</div>
@stop
