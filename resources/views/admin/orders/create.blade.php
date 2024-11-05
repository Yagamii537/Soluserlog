@extends('adminlte::page')

@section('title', 'Crear Pedido')

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
                {!! Form::label('cliente_nombre', 'Cliente Seleccionado', ['class' => 'form-label']) !!}
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
                {!! Form::label('domicilio', 'Domicilio', ['class' => 'form-label']) !!}
                {!! Form::text('', null, ['class' => 'form-control', 'id' => 'domicilio', 'disabled']) !!}
            </div>
            <div class="mb-3">
                {!! Form::label('dom_ampliado', 'Dom. Ampliado', ['class' => 'form-label']) !!}
                {!! Form::text('', null, ['class' => 'form-control', 'id' => 'dom_ampliado', 'disabled']) !!}
            </div>
            <div class="mb-3">
                {!! Form::label('localidad_destino', 'Localidad', ['class' => 'form-label']) !!}
                {!! Form::text('', null, ['class' => 'form-control', 'id' => 'localidad_destino', 'disabled']) !!}
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
        // Seleccionar cliente y cerrar modal
        $('.seleccionar-cliente').on('click', function() {
            var clienteID = $(this).data('id');
            var clienteNombre = $(this).data('nombre');

            // Asignar el cliente seleccionado al formulario
            $('#cliente_id').val(clienteID);
            $('#cliente_nombre').val(clienteNombre);

            // Cerrar el modal
            $('#clientesModal').modal('hide');

            // Limpiar y cargar direcciones del cliente
            $('#direccion_id').empty().append('<option value="">Cargando direcciones...</option>');

            $.ajax({
                url: '/admin/clientes/' + clienteID,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#direccion_id').empty(); // Limpiar el select de direcciones

                    if (data.direcciones && data.direcciones.length > 0) {
                        // Si tiene direcciones, las agregamos al select
                        $('#direccion_id').append('<option value="">Seleccione una dirección</option>');
                        $.each(data.direcciones, function(index, address) {
                            $('#direccion_id').append(
                                `<option value="${address.id}">${address.nombre_sucursal} - ${address.direccion}, ${address.ciudad}</option>`
                            );
                        });
                    } else {
                        // Si no tiene direcciones, mostramos un mensaje
                        $('#direccion_id').append('<option value="">Este cliente no tiene direcciones</option>');
                    }
                },
                error: function() {
                    alert('Error al obtener las direcciones del cliente.');
                }
            });
        });

        // Actualizar campos de dirección cuando se selecciona una dirección
        $('#direccion_id').change(function() {
            var selectedOption = $(this).find(':selected').text().split(' - ');
            $('#domicilio').val(selectedOption[1]);
            $('#localidad_destino').val(selectedOption[2]);
        });
    });
</script>

@stop
