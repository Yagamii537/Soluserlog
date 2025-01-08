@extends('adminlte::page')

@section('title', 'Crear Manifiesto')

@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">Cargando</h4>
@stop

@section('content_header')
    <h1>Crear Manifiesto</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        {!! Form::open(['route' => 'admin.manifiestos.store', 'method' => 'POST']) !!}

        <div class="form-group">
            {!! Form::label('numero_manifiesto', 'Número de Manifiesto:') !!}
            {!! Form::text('numero_manifiesto', null, ['class' => 'form-control','readonly', 'required']) !!}
        </div>

        <!-- Fecha del Manifiesto -->
        <div class="form-group">

            {!! Form::label('fecha', 'Fecha', ['class' => 'form-label']) !!}

            {!! Form::date('fecha', \Carbon\Carbon::now(), ['class' => 'form-control', 'readonly']) !!}
        </div>



        <!-- Campo oculto para el ID del camión seleccionado -->
        {!! Form::hidden('camion_id', null, ['id' => 'camion_id']) !!}

        <!-- Botón para seleccionar un camión -->
        <div class="form-group">
            {!! Form::label('camion_seleccionado', 'Camión Seleccionado:') !!}
            <input type="text" id="camionSeleccionado" class="form-control" placeholder="Selecciona un camión" readonly>
            <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#camionesModal">Seleccionar Camión</button>
        </div>

        <!-- Campo oculto para el ID del conductor seleccionado -->
        {!! Form::hidden('conductor_id', null, ['id' => 'conductor_id']) !!}

        <!-- Botón para seleccionar un conductor -->
        <div class="form-group">
            {!! Form::label('conductor_seleccionado', 'Conductor Seleccionado:') !!}
            <input type="text" id="conductorSeleccionado" class="form-control" placeholder="Selecciona un conductor" readonly>
            <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#conductoresModal">Seleccionar Conductor</button>
        </div>

        <!-- Campo oculto para el ID del ayudante seleccionado -->
        {!! Form::hidden('ayudante_id', null, ['id' => 'ayudante_id']) !!}

        <!-- Botón para seleccionar un ayudante -->
        <div class="form-group">
            {!! Form::label('ayudante_seleccionado', 'Ayudante Seleccionado:') !!}
            <input type="text" id="ayudanteSeleccionado" class="form-control" placeholder="Selecciona un ayudante" readonly>
            <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#ayudantesModal">Seleccionar Ayudante</button>
        </div>

        <!-- Campo oculto para los IDs de los pedidos confirmados seleccionados -->
        {!! Form::hidden('order_ids', null, ['id' => 'order_ids']) !!}

        <!-- Botón para seleccionar pedidos confirmados -->
        <div class="form-group">
            {!! Form::label('pedidos_seleccionados', 'Pedidos Confirmados Seleccionados:') !!}
            <input type="text" id="pedidoSeleccionado" class="form-control" placeholder="Selecciona uno o más pedidos" readonly>
            <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#ordersModal">Seleccionar Pedidos Confirmados</button>
        </div>

        <!-- Contenedor para mostrar los pedidos seleccionados -->
        <ul id="pedidosSeleccionadosLista" class="list-group mb-3"></ul>

        <div class="form-group">
            {!! Form::label('tipoFlete', 'Tipo de Flete:') !!}
            {!! Form::select('tipoFlete', ['Adicional' => 'Adicional', 'Fijo' => 'Fijo'], null, ['class' => 'form-control', 'placeholder' => 'Seleccione el tipo de flete', 'required']) !!}
        </div>

        <div class="card">
            <div class="card-header bg-light border" style="border-radius: 0;">
                <div class="row">
                    <div class="col input-group">
                        {!! Form::label('fecha_inicio_traslado', 'Fecha de Inicio de Traslado:', ['class' => 'form-label mr-3']) !!}
                        {!! Form::date('fecha_inicio_traslado', null, ['class' => 'form-control']) !!}
                    </div>
                    <div class="col input-group">
                        {!! Form::label('fecha_fin_traslado', 'Fecha de Fin de Traslado:', ['class' => 'form-label mr-3']) !!}
                        {!! Form::date('fecha_fin_traslado', null, ['class' => 'form-control']) !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Descripción -->
        <div class="form-group">
            {!! Form::label('descripcion', 'Descripción (opcional):') !!}
            {!! Form::textarea('descripcion', null, ['class' => 'form-control']) !!}
        </div>


        <div class="card">
            <!-- Totales -->
            <div class="card-header bg-light border" style="border-radius: 0;">
                <div class="row">
                    <div class="col input-group">
                        {!! Form::label('bultos', 'Total de Bultos:', ['class' => 'form-label mr-3']) !!}
                        {!! Form::text('bultos', null, ['class' => 'form-control', 'disabled']) !!}
                    </div>
                    <div class="col input-group">
                        {!! Form::label('kilos', 'Total en Kilos:', ['class' => 'form-label mr-3']) !!}
                        {!! Form::text('kilos', null, ['class' => 'form-control', 'disabled']) !!}
                    </div>
                </div>
            </div>
        </div>


        {!! Form::submit('Crear Manifiesto', ['class' => 'btn btn-primary']) !!}
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
                                    <th>Licencia</th>
                                    <th>Teléfono</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($conductores as $conductor)
                                    <tr>
                                        <td>{{ $conductor->id }}</td>
                                        <td>{{ $conductor->nombre }}</td>
                                        <td>{{ $conductor->numero_licencia }}</td>
                                        <td>{{ $conductor->telefono }}</td>
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
                                    <th>Fecha de Creación</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ordersConfirmados as $order)
                                    <tr id="order-row-{{ $order->id }}">
                                        <td>{{ $order->id }}</td>
                                        <td>{{ $order->direccionRemitente->cliente->razonSocial }}</td>
                                        <td>{{ $order->fechaCreacion }}</td>
                                        <td>
                                            <button type="button" class="btn btn-success" onclick="seleccionarPedido({{ $order->id }}, '{{ $order->direccionRemitente->cliente->razonSocial }}')">Seleccionar</button>
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
        let pedidosSeleccionados = []; // Array para almacenar pedidos seleccionados

        // Función para seleccionar camión y actualizar el campo oculto
        function seleccionarCamion(id, numeroPlaca) {
            document.getElementById('camionSeleccionado').value = 'ID: ' + id + ' - Placa: ' + numeroPlaca;
            document.getElementById('camion_id').value = id;
            $('#camionesModal').modal('hide');
        }

        // Función para seleccionar conductor y actualizar el campo oculto
        function seleccionarConductor(id, nombre) {
            document.getElementById('conductorSeleccionado').value = 'ID: ' + id + ' - Nombre: ' + nombre;
            document.getElementById('conductor_id').value = id;
            $('#conductoresModal').modal('hide');
        }

        // Función para seleccionar ayudante y actualizar el campo oculto
        function seleccionarAyudante(id, nombre) {
            document.getElementById('ayudanteSeleccionado').value = 'ID: ' + id + ' - Nombre: ' + nombre;
            document.getElementById('ayudante_id').value = id;
            $('#ayudantesModal').modal('hide');
        }

        // Función para seleccionar pedido confirmado y añadirlo a la lista
        function seleccionarPedido(id, remitente) {
            if (!pedidosSeleccionados.includes(id)) {
                pedidosSeleccionados.push(id);

                // Añadir el pedido a la lista visual
                let listaPedidos = document.getElementById('pedidosSeleccionadosLista');
                let li = document.createElement('li');
                li.className = 'list-group-item';
                li.textContent = 'ID: ' + id + ' - Remitente: ' + remitente;

                // Añadir botón para eliminar el pedido de la lista
                let removeButton = document.createElement('button');
                removeButton.className = 'btn btn-danger btn-sm float-right';
                removeButton.textContent = 'Eliminar';
                removeButton.onclick = function() {
                    eliminarPedido(id, li);
                };

                li.appendChild(removeButton);
                listaPedidos.appendChild(li);

                // Actualizar el campo hidden con los IDs de pedidos seleccionados
                document.getElementById('order_ids').value = pedidosSeleccionados.join(',');

                // Ocultar el pedido del modal
                document.getElementById('order-row-' + id).style.display = 'none';
            }
        }

        // Función para eliminar un pedido de la lista
        function eliminarPedido(id, listItem) {
            pedidosSeleccionados = pedidosSeleccionados.filter(item => item !== id);
            listItem.remove();
            document.getElementById('order_ids').value = pedidosSeleccionados.join(',');
            document.getElementById('order-row-' + id).style.display = '';
        }
    </script>
@stop
