@extends('adminlte::page')

@section('title', 'Editar Camión')

@section('content_header')
    <h1>Editar Camión</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">

        {!! Form::open(['route' => ['admin.camiones.update', $camion], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) !!}

        <div class="row">
            <!-- Columna izquierda -->
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('numero_placa', 'Número de Placa:') !!}
                    {!! Form::text('numero_placa', $camion->numero_placa, ['class' => 'form-control', 'required']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('modelo', 'Modelo:') !!}
                    {!! Form::text('modelo', $camion->modelo, ['class' => 'form-control', 'required']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('marca', 'Marca:') !!}
                    {!! Form::text('marca', $camion->marca, ['class' => 'form-control', 'required']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('capacidad_carga', 'Capacidad de Carga (kg):') !!}
                    {!! Form::number('capacidad_carga', $camion->capacidad_carga, ['class' => 'form-control', 'required']) !!}
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
                    @if ($camion->foto)
                        <img src="{{ asset('storage/' . $camion->foto) }}" alt="Foto del Camión" style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; padding: 5px;">
                    @else
                        <p>No hay foto disponible.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Botón para actualizar -->
        <div class="form-group mt-4">
            {!! Form::submit('Actualizar Camión', ['class' => 'btn btn-success']) !!}
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
