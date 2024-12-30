@extends('adminlte::page')

@section('title', 'Crear Ayudante')

@section('content_header')
    <h1>Crear Ayudante</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        {!! Form::open(['route' => 'admin.ayudantes.store', 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

        <!-- Contenedor de dos columnas -->
        <div class="row">
            <!-- Columna izquierda -->
            <div class="col-md-6">
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
            </div>

            <!-- Columna derecha -->
            <div class="col-md-6 text-center">
                <div class="form-group">
                    <label>Previsualización:</label>
                    <div>
                        <center>
                        <img id="fotoPreview" src="#" alt="Previsualización de la Imagen" style="display: none; max-width: 200px; max-height: 200px; border: 1px solid #ddd; padding: 5px;">
                    </center>
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
            {!! Form::submit('Crear Ayudante', ['class' => 'btn btn-success']) !!}
        </div>

        {!! Form::close() !!}
    </div>
</div>
@stop

@section('js')
<script>
    document.getElementById('fotoInput').addEventListener('change', function (event) {
        const file = event.target.files[0]; // Obtiene el archivo seleccionado
        const preview = document.getElementById('fotoPreview'); // Obtiene el elemento de la previsualización

        if (file) {
            const reader = new FileReader(); // Crea un FileReader para leer la imagen

            reader.onload = function (e) {
                preview.src = e.target.result; // Asigna la URL de la imagen al atributo src
                preview.style.display = 'block'; // Muestra la imagen
            };

            reader.readAsDataURL(file); // Lee el archivo como una URL
        } else {
            preview.src = '#'; // Resetea la URL
            preview.style.display = 'none'; // Oculta la imagen
        }
    });
</script>
@stop
