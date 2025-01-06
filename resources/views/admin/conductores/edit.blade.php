@extends('adminlte::page')

@section('title', 'Editar Conductor')

@section('content_header')
    <h1>Editar Conductor</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">

        {!! Form::open(['route' => ['admin.conductores.update', $conductor], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) !!}

        <div class="row">
            <!-- Columna izquierda -->
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('nombre', 'Nombre:') !!}
                    {!! Form::text('nombre', $conductor->nombre, ['class' => 'form-control', 'required']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('numero_licencia', 'Número de Licencia:') !!}
                    {!! Form::text('numero_licencia', $conductor->numero_licencia, ['class' => 'form-control', 'required']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('telefono', 'Teléfono:') !!}
                    {!! Form::text('telefono', $conductor->telefono, ['class' => 'form-control', 'required']) !!}
                </div>
            </div>

            <!-- Columna derecha -->
            <div class="col-md-6">
                <div class="form-group text-center">
                    <label>Previsualización:</label>
                    <div>
                        <img id="fotoPreview" src="#" alt="Previsualización de la Imagen" style="display: none; max-width: 200px; max-height: 200px; border: 1px solid #ddd; padding: 5px;">
                    </div>
                </div>

                <div class="form-group">
                    {!! Form::label('foto', 'Foto Nueva:') !!}
                    {!! Form::file('foto', ['class' => 'form-control', 'accept' => 'image/*', 'id' => 'fotoInput']) !!}
                </div>

                <div class="form-group text-center mt-3">
                    {!! Form::label('foto_actual', 'Foto Actual:') !!}
                    @if ($conductor->foto)
                        <img src="{{ asset('storage/' . $conductor->foto) }}" alt="Foto del Conductor" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; padding: 5px;">
                    @else
                        <p>No hay foto disponible.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Botón para actualizar -->
        <div class="form-group mt-4">
            {!! Form::submit('Actualizar Conductor', ['class' => 'btn btn-success']) !!}
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
