@extends('adminlte::page')

@section('title', 'Crear Pedido')

{{-- icono de carga --}}
@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">Cargando</h4>
@stop

@section('adminlte_css')

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
            {!! Form::open(['route' => 'admin.orders.store']) !!}
            {!! Form::submit('Agregar', ['class' => 'btn btn-warning mx-2']) !!}
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

        <!-- Remitente Section -->
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

        <!-- Destinatario Section -->
        <div class="card-header bg-dark" style="border-radius: 0;">
            Destinatario
        </div>
        <div class="card-body bg-light">


            <div class="mb-3">
                {!! Form::label('cliente_id', 'Cliente Seleccionado', ['class' => 'form-label']) !!}
                <div class="input-group">
                    {!! Form::text('cliente_nombre', null, ['class' => 'form-control', 'id' => 'cliente_nombre', 'readonly']) !!}
                    {!! Form::hidden('cliente_id', null, ['id' => 'cliente_id']) !!}
                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#clientesModal">Seleccionar Cliente</button>
                </div>
            </div>


            <div class="mb-3">
                {!! Form::label('direccion_id', 'Seleccionar Dirección', ['class' => 'form-label']) !!}
                {!! Form::select('direccion_id', [], null, ['class' => 'form-control', 'id' => 'direccion_id']) !!}
            </div>

            <div class="mb-3">
                {!! Form::label('direccion', 'Dirección', ['class' => 'form-label']) !!}
                {!! Form::text('direccion', null, ['class' => 'form-control', 'id' => 'direccion', 'readonly']) !!}
            </div>
            <div class="mb-3">
                {!! Form::label('ciudad', 'Ciudad', ['class' => 'form-label']) !!}
                {!! Form::text('ciudad', null, ['class' => 'form-control', 'id' => 'ciudad', 'readonly']) !!}
            </div>
            <div class="mb-3">
                {!! Form::label('provincia', 'Provincia', ['class' => 'form-label']) !!}
                {!! Form::text('provincia', null, ['class' => 'form-control', 'id' => 'provincia', 'readonly']) !!}
            </div>
            <div class="mb-3">
                {!! Form::label('zona', 'Zona', ['class' => 'form-label']) !!}
                {!! Form::text('zona', null, ['class' => 'form-control', 'id' => 'zona', 'readonly']) !!}
            </div>


            <div class="mb-3">
                {!! Form::label('horario', 'Horario', ['class' => 'form-label']) !!}
                {!! Form::select('horario', ['TODO EL DÍA: 08:30 - 17:30' => 'TODO EL DÍA: 08:30 - 17:30'], null, ['class' => 'form-control']) !!}
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

    </div>
    {!! Form::close() !!}
</div>

<!-- Modal para Seleccionar Cliente -->
<div class="modal fade" id="clientesModal" tabindex="-1" role="dialog" aria-labelledby="clientesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clientesModalLabel">Seleccionar Cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered" id="clientesTable">
                    <thead>
                        <tr>
                            <th>Razón Social</th>
                            <th>RUC</th>
                            <th>Teléfono</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->razonSocial }}</td>
                            <td>{{ $cliente->ruc }}</td>
                            <td>{{ $cliente->telefono }}</td>
                            <td>
                                <button type="button" class="btn btn-primary seleccionar-cliente"
                                    data-id="{{ $cliente->id }}"
                                    data-nombre="{{ $cliente->razonSocial }}">
                                    Seleccionar
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@stop

@section('js')
<script>
    $(document).ready(function() {
        // Cuando se selecciona un cliente en el modal
        $('.seleccionar-cliente').on('click', function() {
            var clienteID = $(this).data('id');
            var clienteNombre = $(this).data('nombre');

            // Asigna el cliente seleccionado al formulario
            $('#cliente_id').val(clienteID);
            $('#cliente_nombre').val(clienteNombre);

            // Cierra el modal
            $('#clientesModal').modal('hide');

            // Limpia y carga direcciones del cliente
            $('#direccion_id').empty().append('<option value="">Cargando direcciones...</option>');

            $.ajax({
                url: `{{ url('/clientes') }}/${clienteID}`,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log(data); // Depuración: muestra la respuesta en la consola

                    $('#direccion_id').empty(); // Limpia el select de direcciones

                    if (data.direcciones && data.direcciones.length > 0) {
                        // Si hay direcciones, las agregamos al select
                        $('#direccion_id').append('<option value="">Seleccione una dirección</option>');
                        $.each(data.direcciones, function(index, address) {
                            $('#direccion_id').append(
                                `<option value="${address.id}" data-direccion="${address.direccion}" data-ciudad="${address.ciudad}" data-provincia="${address.provincia}" data-zona="${address.zona}">${address.nombre_sucursal} - ${address.direccion}, ${address.ciudad}, ${address.provincia}, ${address.zona}</option>`
                            );
                        });
                    } else {
                        // Si no tiene direcciones, muestra un mensaje
                        $('#direccion_id').append('<option value="">Este cliente no tiene direcciones</option>');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error:', textStatus, errorThrown);
                    console.error('Detalles del error:', jqXHR.responseText);
                    alert('Error al obtener las direcciones del cliente.');
                }
            });
        });

        // Actualiza los campos de dirección cuando se selecciona una dirección
        $('#direccion_id').change(function() {
            var selectedOption = $(this).find(':selected');

            // Obtiene los datos de la dirección seleccionada
            var direccion = selectedOption.data('direccion');
            var ciudad = selectedOption.data('ciudad');
            var provincia = selectedOption.data('provincia');
            var zona = selectedOption.data('zona');

            // Actualiza los campos con los datos de la dirección
            $('#direccion').val(direccion || '');
            $('#ciudad').val(ciudad || '');
            $('#provincia').val(provincia || '');
            $('#zona').val(zona || '');
        });
    });
</script>


@stop
