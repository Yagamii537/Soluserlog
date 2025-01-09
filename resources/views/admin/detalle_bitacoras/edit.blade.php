@extends('adminlte::page')

@section('title', 'Editar Detalle de Bitácora')
@section('adminlte_css')
    @vite(['resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('content_header')
    <h1>Editar Detalle de Bitácora para el Pedido #{{ $order->id }}</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">

        {!! Form::model($detalle, ['route' => ['admin.detalle_bitacoras.update', $bitacora->id, $order->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) !!}


        <!-- ORIGEN -->
        <div class="form-group">
            <label><strong>Origen:</strong></label>
            <input type="text" class="form-control" value="{{ $order->direccionRemitente->cliente->razonSocial }}" readonly>
        </div>

        <!-- DESTINO -->
        <div class="form-group">
            <label><strong>Destino:</strong></label>
            <input type="text" class="form-control" value="{{ $order->direccionDestinatario->cliente->razonSocial }}" readonly>
        </div>

        <!-- DOCUMENTOS ASOCIADOS -->
        <div class="form-group">
            <label><strong>Documentos Asociados:</strong></label>
            <ul>
                @foreach($order->documents as $documento)
                    <li>Factura: {{ $documento->factura }} - {{ $documento->cantidad_bultos }} bultos - {{ $documento->cantidad_kg }} kg</li>
                @endforeach
            </ul>
        </div>

        <div class="form-group">
            {!! Form::label('fechaOrigen', 'Fecha de Llegada al Origen:') !!}
            {!! Form::text('fechaOrigen', $detalle->fechaOrigen ? \Carbon\Carbon::parse($detalle->fechaOrigen)->format('d/m/Y') : 'Pendiente', ['class' => 'form-control', 'readonly' => true]) !!}
        </div>

        <!-- HORA, TEMPERATURA Y HUMEDAD EN ORIGEN (MISMA LINEA) -->
        <div class="form-group row">
            <div class="col-md-4">
                <label><strong>Hora de Llegada al Origen:</strong></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="checkbox" id="hora_origen_llegadaCheckbox" onchange="setHora('hora_origen_llegada', this)">
                        </div>
                    </div>
                    {!! Form::time('hora_origen_llegada', $detalle->hora_origen_llegada, ['class' => 'form-control', 'id' => 'hora_origen_llegada']) !!}
                </div>
            </div>
            <div class="col-md-4">
                {!! Form::label('temperatura_origen', 'Temperatura en Origen:') !!}
                {!! Form::text('temperatura_origen', $detalle->temperatura_origen, ['class' => 'form-control']) !!}
            </div>
            <div class="col-md-4">
                {!! Form::label('humedad_origen', 'Humedad en Origen:') !!}
                {!! Form::text('humedad_origen', $detalle->humedad_origen, ['class' => 'form-control']) !!}
            </div>
        </div>

        <!-- HORA DE CARGA Y SALIDA -->
        <div class="form-group row">
            <div class="col-md-6">
                <label><strong>Hora de Carga:</strong></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="checkbox" id="hora_cargaCheckbox" onchange="setHora('hora_carga', this)">
                        </div>
                    </div>
                    {!! Form::time('hora_carga', $detalle->hora_carga, ['class' => 'form-control', 'id' => 'hora_carga']) !!}
                </div>
            </div>
            <div class="col-md-6">
                <label><strong>Hora de Salida del Origen:</strong></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="checkbox" id="hora_salida_origenCheckbox" onchange="setHora('hora_salida_origen', this)">
                        </div>
                    </div>
                    {!! Form::time('hora_salida_origen', $detalle->hora_salida_origen, ['class' => 'form-control', 'id' => 'hora_salida_origen']) !!}
                </div>
            </div>
        </div>

        <!-- NOVEDADES DE CARGA -->
        <div class="form-group">
            {!! Form::label('novedades_carga', 'Novedades en la Carga:') !!}
            {!! Form::textarea('novedades_carga', $detalle->novedades_carga, ['class' => 'form-control']) !!}
        </div>

        <!-- DESTINO -->
        <div class="form-group">
            {!! Form::label('fechaDestino', 'Fecha de Llegada al Destino:') !!}
            {!! Form::text('fechaDestino', $detalle->fechaDestino ? \Carbon\Carbon::parse($detalle->fechaDestino)->format('d/m/Y') : 'Pendiente', ['class' => 'form-control', 'readonly' => true]) !!}
        </div>

        <!-- HORAS EN DESTINO -->
        <div class="form-group row">
            <div class="col-md-4">
                <label><strong>Hora de Llegada al Destino:</strong></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="checkbox" id="hora_destino_llegadaCheckbox" onchange="setHora('hora_destino_llegada', this)">
                        </div>
                    </div>
                    {!! Form::time('hora_destino_llegada', $detalle->hora_destino_llegada, ['class' => 'form-control', 'id' => 'hora_destino_llegada']) !!}
                </div>
            </div>
            <div class="col-md-4">
                <label><strong>Hora de Descarga:</strong></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="checkbox" id="hora_descargaCheckbox" onchange="setHora('hora_descarga', this)">
                        </div>
                    </div>
                    {!! Form::time('hora_descarga', $detalle->hora_descarga, ['class' => 'form-control', 'id' => 'hora_descarga']) !!}
                </div>
            </div>
            <div class="col-md-4">
                <label><strong>Hora de Salida del Destino:</strong></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="checkbox" id="hora_salida_destinoCheckbox" onchange="setHora('hora_salida_destino', this)">
                        </div>
                    </div>
                    {!! Form::time('hora_salida_destino', $detalle->hora_salida_destino, ['class' => 'form-control', 'id' => 'hora_salida_destino']) !!}
                </div>
            </div>
        </div>

        <!-- NOVEDADES DESTINO -->
        <div class="form-group">
            {!! Form::label('novedades_destino', 'Novedades en el Destino:') !!}
            {!! Form::textarea('novedades_destino', $detalle->novedades_destino, ['class' => 'form-control']) !!}
        </div>

        <!-- PERSONA -->
        <div class="form-group">
            {!! Form::label('persona', 'Persona que Recibe:') !!}
            {!! Form::text('persona', $detalle->persona, ['class' => 'form-control', 'placeholder' => 'Ingrese el nombre de la persona que recibe']) !!}
        </div>


        <!-- AGREGAR IMÁGENES -->
        <div class="form-group">
            {!! Form::label('imagenes', 'Agregar Imágenes:') !!}
            {!! Form::file('imagenes[]', ['class' => 'form-control', 'multiple' => true, 'accept' => 'image/*', 'id' => 'imagenesInput']) !!}
        </div>
        @if($detalle->images->count())
    <div class="form-group">
        <label><strong>Imágenes Actuales:</strong></label>
        <div class="row">
            @foreach ($detalle->images as $image)
                <div class="col-md-3" id="image-{{ $image->id }}">
                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="Imagen" style="max-width: 100%; margin-bottom: 10px;">
                    <button type="button" class="btn btn-danger btn-sm mt-2" onclick="deleteImage({{ $image->id }})">
                        Eliminar
                    </button>
                </div>
            @endforeach
        </div>
    </div>
@endif




        <!-- PREVISUALIZAR IMÁGENES -->
        <div class="form-group">
            <label><strong>Previsualización de Imágenes:</strong></label>
            <div id="imagenesPreview" class="d-flex flex-wrap">
                @foreach ($detalle->images as $image)
                    <div class="m-2">
                        <img src="{{ asset('storage/' . $image->path) }}" alt="Imagen" style="max-width: 100px; max-height: 100px; border: 1px solid #ddd; padding: 5px;">
                    </div>
                @endforeach
            </div>
        </div>

        {!! Form::submit('Guardar Cambios', ['class' => 'btn btn-success']) !!}
        {!! Form::close() !!}
    </div>
</div>
@stop

@section('js')
<script>
    function deleteImage(imageId) {
    if (confirm('¿Estás seguro de que deseas eliminar esta imagen?')) {
        fetch(`/admin/detalle_bitacoras/${imageId}/delete`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`image-${imageId}`).remove();
                alert('Imagen eliminada correctamente.');
            } else {
                alert('Hubo un error al eliminar la imagen.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un error al eliminar la imagen.');
        });
    }
}

    document.getElementById('imagenesInput').addEventListener('change', function(event) {
        const files = event.target.files;
        const previewContainer = document.getElementById('imagenesPreview');

        previewContainer.innerHTML = ''; // Limpiar previsualizaciones anteriores

        Array.from(files).forEach(file => {
            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100px';
                img.style.maxHeight = '100px';
                img.style.border = '1px solid #ddd';
                img.style.padding = '5px';
                img.style.margin = '5px';
                previewContainer.appendChild(img);
            };

            reader.readAsDataURL(file);
        });
    });
    function setHora(fieldId, checkbox) {
        const field = document.getElementById(fieldId);
        if (checkbox.checked) {
            const now = new Date();
            const hours = now.getHours().toString().padStart(2, '0');
            const minutes = now.getMinutes().toString().padStart(2, '0');
            field.value = `${hours}:${minutes}`;
            field.readOnly = true;
        } else {
            field.value = '';
            field.readOnly = false;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const fields = [
            'hora_origen_llegada',
            'hora_carga',
            'hora_salida_origen',
            'hora_destino_llegada',
            'hora_descarga',
            'hora_salida_destino'
        ];

        fields.forEach(function(fieldId) {
            const field = document.getElementById(fieldId);
            const checkbox = document.querySelector(`#${fieldId}Checkbox`);
            if (field.value) {
                field.readOnly = true;
                if (checkbox) {
                    checkbox.disabled = true;
                }
            }
        });
    });
</script>
@stop
