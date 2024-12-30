@extends('adminlte::page')

@section('title', 'Crear Ayudante')

@section('content_header')
    <h1>Crear Ayudante</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        {!! Form::open(['route' => 'admin.ayudantes.store', 'method' => 'POST']) !!}
        <div class="form-group">
            {!! Form::label('nombre', 'Nombre:') !!}
            {!! Form::text('nombre', null, ['class' => 'form-control', 'required']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('cedula', 'Cédula:') !!}
            {!! Form::text('cedula', null, ['class' => 'form-control', 'required']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('telefono', 'Teléfono:') !!}
            {!! Form::text('telefono', null, ['class' => 'form-control', 'required']) !!}
        </div>
        {!! Form::submit('Crear Ayudante', ['class' => 'btn btn-success']) !!}
        {!! Form::close() !!}
    </div>
</div>
@stop
