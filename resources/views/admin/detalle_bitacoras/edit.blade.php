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
        {!! Form::model($detalle, ['route' => ['admin.detalle_bitacoras.update', $bitacora->id, $order->id], 'method' => 'PUT']) !!}

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

        <!-- HORA, TEMPERATURA Y HUMEDAD EN ORIGEN (MISMA LINEA) -->
        <div class="form-group row">
            <div class="col-md-4">
                <label><strong>Hora de Llegada al Origen:</strong></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="checkbox" id="horaOrigenCheckbox" onchange="setHora('hora_origen_llegada', this)">
                        </div>
                    </div>
                    {!! Form::time('hora_origen_llegada', null, ['class' => 'form-control', 'id' => 'hora_origen_llegada', 'readonly' => true]) !!}
                </div>
            </div>
            <div class="col-md-4">
                {!! Form::label('temperatura_origen', 'Temperatura en Origen:') !!}
                {!! Form::text('temperatura_origen', null, ['class' => 'form-control']) !!}
            </div>
            <div class="col-md-4">
                {!! Form::label('humedad_origen', 'Humedad en Origen:') !!}
                {!! Form::text('humedad_origen', null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="form-group row">
            <div class="col-md-6">
                <label><strong>Hora de Carga:</strong></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="checkbox" id="horaCargaCheckbox" onchange="setHora('hora_carga', this)">
                        </div>
                    </div>
                    {!! Form::time('hora_carga', null, ['class' => 'form-control', 'id' => 'hora_carga', 'readonly' => true]) !!}
                </div>
            </div>

            <div class="col-md-6">
                <label><strong>Hora de Salida del Origen:</strong></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="checkbox" id="horaSalidaOrigenCheckbox" onchange="setHora('hora_salida_origen', this)">
                        </div>
                    </div>
                    {!! Form::time('hora_salida_origen', null, ['class' => 'form-control', 'id' => 'hora_salida_origen', 'readonly' => true]) !!}
                </div>
            </div>
        </div>






                <!-- NOVEDADES DE CARGA -->
        <div class="form-group">
            {!! Form::label('novedades_carga', 'Novedades en la Carga:') !!}
            {!! Form::textarea('novedades_carga', null, ['class' => 'form-control']) !!}
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                {!! Form::label('temperatura_destino', 'Temperatura en Destino:') !!}
                {!! Form::text('temperatura_destino', null, ['class' => 'form-control']) !!}
            </div>
            <div class="col-md-6">
                {!! Form::label('humedad_destino', 'Humedad en Destino:') !!}
                {!! Form::text('humedad_destino', null, ['class' => 'form-control']) !!}
            </div>
        </div>


        <div class="form-group row">
            <div class="col-md-4">
                <label><strong>Hora de Llegada al Destino:</strong></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="checkbox" id="horaDestinoLlegadaCheckbox" onchange="setHora('hora_destino_llegada', this)">
                        </div>
                    </div>
                    {!! Form::time('hora_destino_llegada', null, ['class' => 'form-control', 'id' => 'hora_destino_llegada', 'readonly' => true]) !!}
                </div>
            </div>
            <div class="col-md-4">
                <label><strong>Hora de Descarga:</strong></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="checkbox" id="horaDescargaCheckbox" onchange="setHora('hora_descarga', this)">
                        </div>
                    </div>
                    {!! Form::time('hora_descarga', null, ['class' => 'form-control', 'id' => 'hora_descarga', 'readonly' => true]) !!}
                </div>
            </div>
            <div class="col-md-4">
                <label><strong>Hora de Salida del Destino:</strong></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <input type="checkbox" id="horaSalidaDestinoCheckbox" onchange="setHora('hora_salida_destino', this)">
                        </div>
                    </div>
                    {!! Form::time('hora_salida_destino', null, ['class' => 'form-control', 'id' => 'hora_salida_destino', 'readonly' => true]) !!}
                </div>
            </div>
        </div>

        <div class="form-group">
            {!! Form::label('novedades_destino', 'Novedades en el Destino:') !!}
            {!! Form::textarea('novedades_destino', null, ['class' => 'form-control']) !!}
        </div>





        <!-- FIRMA -->
        <div class="form-group">
            {!! Form::label('firma_recepcion', 'Firma de Recepción:') !!}
            {!! Form::text('firma_recepcion', null, ['class' => 'form-control']) !!}
        </div>

        {!! Form::submit('Guardar Cambios', ['class' => 'btn btn-success']) !!}
        {!! Form::close() !!}
    </div>
</div>
@stop

@section('js')
<script>
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
</script>
@stop
