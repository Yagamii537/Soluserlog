@extends('adminlte::page')

@section('title', 'Editar Manifiesto')

@section('content_header')
    <h1>Editar Manifiesto</h1>
@stop


@section('content')
<div class="card">
    <div class="card-body">

        {!! Form::model($manifiesto, ['route' => ['admin.manifiestos.update', $manifiesto->id], 'method' => 'PUT']) !!}

        <div class="form-group">
            {!! Form::label('numero_manifiesto', 'Número de Manifiesto:') !!}
            {!! Form::text('numero_manifiesto', $manifiesto->numero_manifiesto, ['class' => 'form-control','readonly', 'required']) !!}
        </div>

        <!-- Fecha del Manifiesto -->
        <div class="form-group">
            {!! Form::label('fecha', 'Fecha del Manifiesto:') !!}
            {!! Form::date('fecha', $manifiesto->fecha, ['class' => 'form-control', 'required', 'readonly']) !!}
        </div>

        <!-- Campo oculto para el ID del camión seleccionado -->
        {!! Form::hidden('camion_id', $manifiesto->camion_id, ['id' => 'camion_id']) !!}

        <!-- Input para mostrar el camión seleccionado -->
        <div class="form-group">
            {!! Form::label('camion_seleccionado', 'Camión Seleccionado:') !!}
            <input type="text" id="camionSeleccionado"
                class="form-control"
                value="ID: {{ $manifiesto->camion->id }} - Placa: {{ $manifiesto->camion->numero_placa }}"
                readonly>
            <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#camionesModal">Seleccionar Camión</button>
        </div>

        <!-- Campo oculto para el ID del conductor seleccionado -->
        {!! Form::hidden('conductor_id', $manifiesto->conductor_id, ['id' => 'conductor_id']) !!}

        <!-- Input para mostrar el conductor seleccionado -->
        <div class="form-group">
            {!! Form::label('conductor_seleccionado', 'Conductor Seleccionado:') !!}
            <input type="text" id="conductorSeleccionado"
                class="form-control"
                value="ID: {{ $manifiesto->conductor->id }} - Nombre: {{ $manifiesto->conductor->nombre }}"
                readonly>
            <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#conductoresModal">Seleccionar Conductor</button>
        </div>

        <!-- Campo oculto para el ID del ayudante seleccionado -->
        {!! Form::hidden('ayudante_id', $manifiesto->ayudante_id, ['id' => 'ayudante_id']) !!}

        <!-- Input para mostrar el ayudante seleccionado -->
        <div class="form-group">
            {!! Form::label('ayudante_seleccionado', 'Ayudante Seleccionado:') !!}
            <input type="text" id="ayudanteSeleccionado"
                class="form-control"
                value="ID: {{ $manifiesto->ayudante->id ?? '' }} - Nombre: {{ $manifiesto->ayudante->nombre ?? '' }}"
                readonly>
            <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#ayudantesModal">Seleccionar Ayudante</button>
        </div>

        <!-- Tipo de Flete -->
        <div class="form-group">
            {!! Form::label('tipoFlete', 'Tipo de Flete:') !!}
            {!! Form::select('tipoFlete', ['Adicional' => 'Adicional', 'Fijo' => 'Fijo'], $manifiesto->tipoFlete, ['class' => 'form-control', 'required']) !!}
        </div>

        <!-- Campo oculto para los IDs de los pedidos confirmados seleccionados -->
        {!! Form::hidden('order_ids', $manifiesto->orders->pluck('id')->implode(','), ['id' => 'order_ids']) !!}

        <!-- Lista visual de pedidos seleccionados -->
        <div class="form-group">
            {!! Form::label('pedidos_seleccionados', 'Pedidos Confirmados Seleccionados:') !!}
            <ul id="pedidosSeleccionadosLista" class="list-group mb-3">
                @foreach ($manifiesto->orders as $order)
                    <li class="list-group-item pedido-seleccionado"
                        data-id="{{ $order->id }}"
                        data-remitente="{{ $order->direccionRemitente->cliente->razonSocial }}"
                        data-destinatario="{{ $order->direccionDestinatario->cliente->razonSocial }}"
                        data-fecha="{{ $order->fechaCreacion }}">
                        ID: {{ $order->id }} - Remitente: {{ $order->direccionRemitente->cliente->razonSocial }} - Destinatario: {{ $order->direccionDestinatario->cliente->razonSocial }}- Fecha: {{ $order->fecha_creacion }}
                        <button type="button" class="btn btn-danger btn-sm float-right" onclick="eliminarPedido({{ $order->id }}, this)">Eliminar</button>
                    </li>
                @endforeach
            </ul>
            <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#ordersModal">Seleccionar Pedidos Confirmados</button>
        </div>



        <!-- Contenedor para mostrar los pedidos seleccionados -->
        <ul id="pedidosSeleccionadosLista" class="list-group mb-3"></ul>

        <div class="card">
            <div class="card-header bg-light border" style="border-radius: 0;">
                <div class="row">
                    <div class="col input-group">
                        {!! Form::label('fecha_inicio_traslado', 'Fecha de Inicio de Traslado:', ['class' => 'form-label mr-3']) !!}
                        {!! Form::date('fecha_inicio_traslado', $manifiesto->fecha_inicio_traslado, ['class' => 'form-control']) !!}
                    </div>
                    <div class="col input-group">
                        {!! Form::label('fecha_fin_traslado', 'Fecha de Fin de Traslado:', ['class' => 'form-label mr-3']) !!}
                        {!! Form::date('fecha_fin_traslado', $manifiesto->fecha_fin_traslado, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Descripción -->
        <div class="form-group">
            {!! Form::label('descripcion', 'Descripción (opcional):') !!}
            {!! Form::textarea('descripcion', $manifiesto->descripcion, ['class' => 'form-control']) !!}
        </div>


        <div class="card">
            <!-- Totales -->
            <div class="card-header bg-light border" style="border-radius: 0;">
                <div class="row">
                    <div class="col input-group">
                        {!! Form::label('bultos', 'Total de Bultos:', ['class' => 'form-label mr-3']) !!}
                        {!! Form::text('bultos', $manifiesto->bultos, ['class' => 'form-control', 'disabled']) !!}
                    </div>
                    <div class="col input-group">
                        {!! Form::label('kilos', 'Total en Kilos:', ['class' => 'form-label mr-3']) !!}
                        {!! Form::text('kilos', $manifiesto->kilos, ['class' => 'form-control', 'disabled']) !!}
                    </div>
                </div>
            </div>
        </div>


        {!! Form::submit('Editar Manifiesto', ['class' => 'btn btn-primary']) !!}
        {!! Form::close() !!}

        <!-- Modal para seleccionar camiones -->
        <div class="modal fade" id="camionesModal" tabindex="-1" role="dialog" aria-labelledby="camionesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="camionesModalLabel">Seleccionar Camión</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Número de Placa</th>
                                    <th>Modelo</th>
                                    <th>Marca</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($camiones as $camion)
                                    <tr>
                                        <td>{{ $camion->id }}</td>
                                        <td>{{ $camion->numero_placa }}</td>
                                        <td>{{ $camion->modelo }}</td>
                                        <td>{{ $camion->marca }}</td>
                                        <td>
                                            <button type="button" class="btn btn-success" onclick="seleccionarCamion({{ $camion->id }}, '{{ $camion->numero_placa }}')">Seleccionar</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para seleccionar conductores -->
        <div class="modal fade" id="conductoresModal" tabindex="-1" role="dialog" aria-labelledby="conductoresModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="conductoresModalLabel">Seleccionar Conductor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Número de Licencia</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($conductores as $conductor)
                                    <tr>
                                        <td>{{ $conductor->id }}</td>
                                        <td>{{ $conductor->nombre }}</td>
                                        <td>{{ $conductor->numero_licencia }}</td>
                                        <td>
                                            <button type="button" class="btn btn-success" onclick="seleccionarConductor({{ $conductor->id }}, '{{ $conductor->nombre }}')">Seleccionar</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal para seleccionar ayudantes -->
        <div class="modal fade" id="ayudantesModal" tabindex="-1" role="dialog" aria-labelledby="ayudantesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ayudantesModalLabel">Seleccionar Ayudante</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Teléfono</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ayudantes as $ayudante)
                                    <tr>
                                        <td>{{ $ayudante->id }}</td>
                                        <td>{{ $ayudante->nombre }}</td>
                                        <td>{{ $ayudante->telefono }}</td>
                                        <td>
                                            <button type="button" class="btn btn-success" onclick="seleccionarAyudante({{ $ayudante->id }}, '{{ $ayudante->nombre }}')">Seleccionar</button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>



        <!-- Modal para seleccionar pedidos confirmados -->
        <div class="modal fade" id="ordersModal" tabindex="-1" role="dialog" aria-labelledby="ordersModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ordersModalLabel">Seleccionar Pedidos Confirmados</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Remitente</th>
                                    <th>Destinatario</th>
                                    <th>Fecha de Creación</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="ordersTableBody">
                                @foreach ($ordersConfirmados as $order)
                                <tr id="order-row-{{ $order->id }}" data-id="{{ $order->id }}">
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->direccionRemitente->cliente->razonSocial }}</td>
                                    <td>{{ $order->direccionDestinatario->cliente->razonSocial }}</td>
                                    <td>{{ $order->fechaCreacion }}</td>
                                    <td>
                                        <button type="button" class="btn btn-success"
                                            onclick="seleccionarPedido({{ $order->id }},
                                                                       '{{ $order->direccionRemitente->cliente->razonSocial }}',
                                                                       '{{ $order->direccionDestinatario->cliente->razonSocial }}',
                                                                       '{{ $order->fechaCreacion }}')">Seleccionar</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@stop


@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('js')
<script>
    let pedidosSeleccionados = []; // Array global para los pedidos seleccionados

    document.addEventListener('DOMContentLoaded', function () {
        const pedidosIniciales = document.getElementById('order_ids').value.split(',').map(Number).filter(id => !isNaN(id));

        // Cargar pedidos iniciales al array global
        pedidosSeleccionados = [...pedidosIniciales];

        // Ocultar en el modal los pedidos precargados
        ocultarPedidosEnModal();
    });

    function agregarPedidoVisual(id, remitente, destinatario, fecha) {
        if (!pedidosSeleccionados.includes(id)) {
            pedidosSeleccionados.push(id);

            // Crear un nuevo elemento visual para el pedido
            const li = document.createElement('li');
            li.className = 'list-group-item pedido-seleccionado';
            li.setAttribute('data-id', id);
            li.setAttribute('data-remitente', remitente);
            li.setAttribute('data-destinatario', destinatario);
            li.setAttribute('data-fecha', fecha);
            li.innerHTML = `
                ID: ${id} - Remitente: ${remitente} - Destinatario: ${destinatario} - Fecha: ${fecha}
                <button type="button" class="btn btn-danger btn-sm float-right" onclick="eliminarPedido(${id}, this)">Eliminar</button>
            `;
            document.getElementById('pedidosSeleccionadosLista').appendChild(li);

            actualizarCampoHidden();
        }
    }

    function eliminarPedido(id, listItem) {
        // Eliminar del array global
        pedidosSeleccionados = pedidosSeleccionados.filter(pedidoId => pedidoId !== id);

        // Remover de la lista visual
        listItem.closest('li').remove();

        // Obtener los valores dinámicos del pedido eliminado
        const remitente = listItem.closest('li').getAttribute('data-remitente') || 'Remitente Desconocido';
        const destinatario = listItem.closest('li').getAttribute('data-destinatario') || 'Destinatario Desconocido';
        const fecha = listItem.closest('li').getAttribute('data-fecha') || '-';

        // Verificar si el pedido ya está en el modal antes de agregarlo
        const existingRow = document.getElementById(`order-row-${id}`);
        if (!existingRow) {
            // Agregar el pedido de nuevo al modal con valores dinámicos
            const modalTableBody = document.getElementById('ordersTableBody');
            const row = document.createElement('tr');
            row.id = `order-row-${id}`;
            row.setAttribute('data-id', id);
            row.innerHTML = `
                <td>${id}</td>
                <td>${remitente}</td>
                <td>${destinatario}</td>
                <td>${fecha}</td>
                <td><button type="button" class="btn btn-success" onclick="seleccionarPedido(${id}, '${remitente}', '${destinatario}', '${fecha}')">Seleccionar</button></td>
            `;
            modalTableBody.appendChild(row);
        } else {
            existingRow.style.display = ''; // Asegurar que se muestre si estaba oculto
        }

        actualizarCampoHidden();
    }

    function seleccionarPedido(id, remitente, destinatario, fecha) {
        // Verificar si ya está en la lista para no duplicarlo
        const existingListItem = document.querySelector(`#pedidosSeleccionadosLista li[data-id="${id}"]`);
        if (!existingListItem) {
            agregarPedidoVisual(id, remitente, destinatario, fecha);
        }

        // Ocultar fila en el modal
        const row = document.getElementById(`order-row-${id}`);
        if (row) {
            row.style.display = 'none';
        }
    }

    function actualizarCampoHidden() {
        document.getElementById('order_ids').value = pedidosSeleccionados.join(',');
    }

    function ocultarPedidosEnModal() {
        document.querySelectorAll('#ordersModal tbody tr').forEach(row => {
            const pedidoId = parseInt(row.dataset.id, 10);
            row.style.display = pedidosSeleccionados.includes(pedidoId) ? 'none' : '';
        });
    }

    $('#ordersModal').on('show.bs.modal', function () {
        ocultarPedidosEnModal();
    });

    // Función para seleccionar camión y actualizar el campo oculto
    function seleccionarCamion(id, numeroPlaca) {
        document.getElementById('camionSeleccionado').value = `ID: ${id} - Placa: ${numeroPlaca}`;
        document.getElementById('camion_id').value = id;
        $('#camionesModal').modal('hide');
    }

    // Función para seleccionar conductor y actualizar el campo oculto
    function seleccionarConductor(id, nombre) {
        document.getElementById('conductorSeleccionado').value = `ID: ${id} - Nombre: ${nombre}`;
        document.getElementById('conductor_id').value = id;
        $('#conductoresModal').modal('hide');
    }

    function seleccionarAyudante(id, nombre) {
        document.getElementById('ayudanteSeleccionado').value = `ID: ${id} - Nombre: ${nombre}`;
        document.getElementById('ayudante_id').value = id;
        $('#ayudantesModal').modal('hide');
    }
</script>

@stop



