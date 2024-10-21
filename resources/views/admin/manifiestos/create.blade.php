@extends('adminlte::page')

@section('title', 'Crear Manifiesto')

{{-- icono de carga --}}
@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">Cargando</h4>
@stop





@section('content_header')
    <h1>Crear Camion</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        {!! Form::open(['route' => 'admin.manifiestos.store', 'method' => 'POST']) !!}

    <div class="form-group">
        {!! Form::label('fecha', 'Fecha del Manifiesto:') !!}
        {!! Form::date('fecha', null, ['class' => 'form-control', 'required']) !!}
    </div>

    {{-- Botón para seleccionar un camión --}}
    <div class="form-group">
        {!! Form::label('camion_id', 'Camión Seleccionado:') !!}
        {!! Form::text('camion_seleccionado', null, ['class' => 'form-control', 'id' => 'camionSeleccionado', 'readonly' => true]) !!}
        <button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#camionesModal">Seleccionar Camión</button>
    </div>

    {{-- Botón para seleccionar un pedido confirmado --}}
    <div class="form-group">
        {!! Form::label('order_id', 'Pedido Confirmado Seleccionado:') !!}
        {!! Form::text('pedido_seleccionado', null, ['class' => 'form-control', 'id' => 'pedidoSeleccionado', 'readonly' => true]) !!}
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
                      <button type="button" class="btn btn-success btn-sm" onclick="seleccionarCamion({{ $camion->id }}, '{{ $camion->numero_placa }}')"><i class="fa fa-check" aria-hidden="true"></i></button>
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
                      <button type="button" class="btn btn-success btn-sm" onclick="seleccionarPedido({{ $order->id }}, '{{ $order->remitente }}')"><i class="fa fa-check" aria-hidden="true"></i></button>
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
        // Función para seleccionar camión y cerrar modal automáticamente
        function seleccionarCamion(id, numeroPlaca) {
            document.getElementById('camionSeleccionado').value = 'ID: ' + id + ' - Placa: ' + numeroPlaca;
            $('#camionesModal').modal('hide'); // Cierra el modal automáticamente
        }

        // Función para seleccionar pedido y cerrar modal automáticamente
        function seleccionarPedido(id, remitente) {
            document.getElementById('pedidoSeleccionado').value = 'ID: ' + id + ' - Remitente: ' + remitente;
            $('#ordersModal').modal('hide'); // Cierra el modal automáticamente
        }
    </script>
@stop
