@extends('adminlte::page')

@section('title', 'Editar Ayudante')

@section('content_header')
    <h1>Editar Ayudante</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        {!! Form::model($ayudante, ['route' => ['admin.ayudantes.update', $ayudante->id], 'method' => 'PUT']) !!}
        <div class="form-group">
            {!! Form::label('nombre', 'Nombre:') !!}
            {!! Form::text('nombre', $ayudante->nombre, ['class' => 'form-control', 'required']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('cedula', 'Cédula:') !!}
            {!! Form::text('cedula', $ayudante->cedula, ['class' => 'form-control', 'required']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('telefono', 'Teléfono:') !!}
            {!! Form::text('telefono', $ayudante->telefono, ['class' => 'form-control', 'required']) !!}
        </div>
        {!! Form::submit('Actualizar Ayudante', ['class' => 'btn btn-success']) !!}
        {!! Form::close() !!}
    </div>
</div>
@stop
