@extends('adminlte::page')

@section('title', 'Crear Manifiesto')

{{-- icono de carga --}}
@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">Cargando</h4>
@stop





@section('content_header')
    <h1>Crear Manifesto</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        {!! Form::open(['route' => 'admin.manifiestos.store', 'method' => 'POST']) !!}
        <div class="form-group">
            {!! Form::label('fecha', 'Fecha del Manifiesto:') !!}
            {!! Form::date('fecha', null, ['class' => 'form-control', 'required']) !!}
        </div>

        {{-- Campo oculto para el ID del camión seleccionado --}}
        {!! Form::hidden('camion_id', null, ['id' => 'camion_id']) !!}

        {{-- Botón para seleccionar un camión --}}
        <div class="form-group">
            {!! Form::label('camion_seleccionado', 'Camión Seleccionado:') !!}
            <input type="text" id="camionSeleccionado" class="form-control" placeholder="Selecciona un camión" readonly>
            <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#camionesModal">Seleccionar Camión</button>
        </div>

        {{-- Campo oculto para el ID del pedido confirmado seleccionado --}}
        {!! Form::hidden('order_id', null, ['id' => 'order_id']) !!}

        {{-- Botón para seleccionar un pedido confirmado --}}
        <div class="form-group">
            {!! Form::label('pedido_seleccionado', 'Pedido Confirmado Seleccionado:') !!}
            <input type="text" id="pedidoSeleccionado" class="form-control" placeholder="Selecciona un pedido" readonly>
            <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#ordersModal">Seleccionar Pedido Confirmado</button>
        </div>

        <div class="form-group">
            {!! Form::label('descripcion', 'Descripción (opcional):') !!}
            {!! Form::textarea('descripcion', null, ['class' => 'form-control']) !!}
        </div>

        {!! Form::submit('Crear Manifiesto', ['class' => 'btn btn-primary']) !!}

        {!! Form::close() !!}

        {{-- Modal para seleccionar camiones --}}
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

        {{-- Modal para seleccionar pedidos confirmados --}}
        <div class="modal fade" id="ordersModal" tabindex="-1" role="dialog" aria-labelledby="ordersModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="ordersModalLabel">Seleccionar Pedido Confirmado</h5>
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
                      <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->remitente }}</td>
                        <td>{{ $order->fechaCreacion->format('d/m/Y') }}</td>
                        <td>
                          <button type="button" class="btn btn-success" onclick="seleccionarPedido({{ $order->id }}, '{{ $order->remitente }}')">Seleccionar</button>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>



    </div>
</div>

@stop

@section('css')
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('js')


    <script>
        // Función para seleccionar camión y actualizar el campo oculto
        function seleccionarCamion(id, numeroPlaca) {
            document.getElementById('camionSeleccionado').value = 'ID: ' + id + ' - Placa: ' + numeroPlaca;
            document.getElementById('camion_id').value = id;  // Actualizamos el campo hidden con el ID seleccionado
            $('#camionesModal').modal('hide'); // Cierra el modal automáticamente
        }

        // Función para seleccionar pedido confirmado y actualizar el campo oculto
        function seleccionarPedido(id, remitente) {
            document.getElementById('pedidoSeleccionado').value = 'ID: ' + id + ' - Remitente: ' + remitente;
            document.getElementById('order_id').value = id;  // Actualizamos el campo hidden con el ID seleccionado
            $('#ordersModal').modal('hide'); // Cierra el modal automáticamente
        }
    </script>
@stop