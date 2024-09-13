@extends('adminlte::page')

@section('title', 'Dashboard')

{{-- icono de carga --}}
@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">Cargando</h4>
@stop

@section('adminlte_css')
    @vite(['resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui/1.12.1/jquery-ui.min.css">

@stop

@section('content_header')
<div class="container">
    <div class="d-flex justify-content-between">
        <span class="text text-danger">Pedido</span>
        <span class="text text-danger">B. BRAUN MEDICAL - PAQUETERÍA</span>
    </div>
</div>


@stop

@section('content')

<div class="container">
    <div class="container mb-4">
        <div class="d-flex justify-content-center">
            {!! Form::model($order,['route'=> ['admin.orders.update',$order],'method' => 'PUT']) !!}

            {!! Form::submit('Actualizar Datos Pedido', ['class' => 'btn btn-warning mx-2']) !!}
            <a href="{{ route('admin.orders.index') }}" class="btn btn-dark mx-2">Cancelar</a>


        </div>
    </div>

    <div class="card">





        <div class="card-body bg-light">
            <div class="row">
                <div class="col-1">
                    {!! Form::label(' ', 'Crear Pedido', ['class' => 'form-label']) !!}
                </div>
                <div class="col-1">
                    {!! Form::label('fechaCreacion', 'Fecha', ['class' => 'form-label']) !!}
                </div>
                <div class="col">
                    {!! Form::date('fechaCreacion', \Carbon\Carbon::now(), ['class' => 'form-control']) !!}
                </div>
                <div class="col-1">
                    {!! Form::label('fechaConfirmacion', 'Fecha Conf.', ['class' => 'form-label']) !!}
                </div>
                <div class="col">
                    {!! Form::date('fechaConfirmacion', null, ['class' => 'form-control']) !!}
                </div>
            </div>
        </div>



        <div class="card-header bg-dark" style="border-radius: 0;">
                Remitente
        </div>
        <div class="card-body bg-light">
            <div class="mb-3 input-group">
                {!! Form::label('remitente', 'Remitente', ['class' => 'form-label mr-2']) !!}
                {!! Form::text('remitente', 'B. BRAUN MEDICAL - PAQUETERÍA', ['class' => 'form-control', 'readonly']) !!}


            </div>
            <div class="mb-3 input-group">
                {!! Form::label('localidad', 'Localidad', ['class' => 'form-label mr-3']) !!}
                {!! Form::text('localidad', 'QUITO', ['class' => 'form-control', 'readonly']) !!}
            </div>

        </div>



        <div class="card-header bg-dark" style="border-radius: 0;">
            Destinatario
        </div>
        <div class="card-body bg-light">
            <div class="mb-3">
                {!! Form::label('cliente_id', 'Razon Social', ['class' => 'form-label']) !!}
                <div class="input-group">
                    {!! Form::select('cliente_id', $clientes, null, ['class'=> 'form-control', 'id' => 'cliente_id']) !!}
                    <a class="btn btn-warning" href="{{route('admin.clientes.create')}}">Nuevo</a>
                </div>
            </div>

            <div class="mb-3">
                {!! Form::label('domicilio', 'Domicilio', ['class' => 'form-label']) !!}
                {!! Form::text('', $order->cliente->direccion, ['class' => 'form-control', 'id' => 'domicilio','disabled']) !!}
            </div>
            <div class="mb-3">
                {!! Form::label('dom_ampliado', 'Dom. Ampliado', ['class' => 'form-label']) !!}
                {!! Form::text('', $order->cliente->ampliado, ['class' => 'form-control', 'id' => 'dom_ampliado','disabled']) !!}
            </div>
            <div class="mb-3">
                {!! Form::label('localidad_destino', 'Localidad', ['class' => 'form-label']) !!}
                {!! Form::text('', $order->cliente->localidad, ['class' => 'form-control', 'id' => 'localidad_destino','disabled']) !!}
            </div>

            <div class="mb-3">
                {!! Form::label('horario', 'Horario', ['class' => 'form-label']) !!}
                {!! Form::select('horario', ['TODO EL DÍA: 08:30 - 17:30' => 'TODO EL DÍA: 08:30 - 17:30'], null, ['class' => 'form-select']) !!}
            </div>
            <div class="mb-3">
                {!! Form::label('fechaEntrega', 'Fecha Entrega', ['class' => 'form-label']) !!}
                {!! Form::date('fechaEntrega', null, ['class' => 'form-control']) !!}
            </div>
            <div class="mb-3">
                {!! Form::label('observacion', 'Observaciones', ['class' => 'form-label']) !!}
                {!! Form::textarea('observacion', null, ['class' => 'form-control']) !!}
            </div>
            <div class="mb-3">
                {!! Form::label('estado', 'Estado', ['class' => 'form-label']) !!}
                {!! Form::text('estado', "Borrador", ['class' => 'form-control','disabled']) !!}
            </div>
        </div>

        <div class="card-header bg-light border" style="border-radius: 0;">
            <div class="row">
                <div class="col input-group">
                    {!! Form::label('totaBultos', 'Total Bultos', ['class' => 'form-label mr-3']) !!}
                    {!! Form::text('totaBultos', null, ['class' => 'form-control', 'disabled']) !!}
                </div>
                <div class="col input-group">
                    {!! Form::label('totalKgr', 'Total Kgr', ['class' => 'form-label mr-3']) !!}
                    {!! Form::text('totalKgr', null, ['class' => 'form-control', 'disabled']) !!}
                </div>
            </div>
        </div>







        {!! Form::close() !!}

    </div>
</div>



@stop



@section('js')


<script>
    $(document).ready(function() {
    $('#cliente_id').change(function() {
        var clienteID = $(this).val();

        if (clienteID) {
            $.ajax({
                url: '/admin/clientes/' + clienteID,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Llenar los campos del formulario con los datos recibidos
                    $('#domicilio').val(data.direccion);
                    $('#dom_ampliado').val(data.ampliado);
                    $('#localidad_destino').val(data.localidad);
                },
                error: function() {
                    alert('Error al obtener los datos del cliente.');
                }
            });
        } else {
            // Limpiar los campos si no hay cliente seleccionado
            $('#domicilio').val('');
            $('#dom_ampliado').val('');
            $('#localidad_destino').val('');
        }
    });
});
</script>



@stop






