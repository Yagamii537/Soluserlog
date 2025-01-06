@extends('adminlte::page')

@section('title', 'Crear Camión')

@section('content_header')
    <h1>Crear Camión</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        {!! Form::open(['route' => 'admin.camiones.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

        <!-- Contenedor de dos columnas -->
        <div class="row">
            <!-- Columna izquierda -->
            <div class="col-md-6">
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
            </div>

            <!-- Columna derecha -->
            <div class="col-md-6 text-center">
                <div class="form-group">
                    <label>Previsualización:</label>
                    <div>
                        <img id="fotoPreview" src="#" alt="Previsualización de la Imagen" style="display: none; max-width: 200px; max-height: 200px; border: 1px solid #ddd; padding: 5px;">
                    </div>
                </div>
                <div class="form-group">
                    {!! Form::label('foto', 'Seleccionar Foto:') !!}
                    {!! Form::file('foto', ['class' => 'form-control', 'accept' => 'image/*', 'id' => 'fotoInput']) !!}
                </div>
            </div>
        </div>

        <!-- Campos adicionales debajo de las columnas -->
        <div class="form-group">
            {!! Form::submit('Crear Camión', ['class' => 'btn btn-success']) !!}
        </div>

        {!! Form::close() !!}
    </div>
</div>
@stop

@section('js')
<script>
    document.getElementById('fotoInput').addEventListener('change', function (event) {
        const file = event.target.files[0];
        const preview = document.getElementById('fotoPreview');

        if (file) {
            const reader = new FileReader();

            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };

            reader.readAsDataURL(file);
        } else {
            preview.src = '#';
            preview.style.display = 'none';
        }
    });
</script>
@stop
