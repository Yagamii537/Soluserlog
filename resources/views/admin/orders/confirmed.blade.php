@extends('adminlte::page')

@section('title', 'Dashboard')

{{-- icono de carga --}}
@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">Cargando</h4>
@stop

@section('adminlte_css')
    @vite(['resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('content_header')




    <h1 ><i class="fa fa-check-square mr-4 mt-2 mb-2"></i>Pedidos Confirmados</h1>

@stop

@section('content')
@if (session('info'))
    <div class="alert alert-danger">
        <strong>{{session('info')}}</strong>
    </div>
@else
    @if (session('success'))
    <div class="alert alert-success">
        <strong>{{session('success')}}</strong>
    </div>
    @endif
    @if (session('danger'))
    <div class="alert alert-danger">
        <strong>{{session('danger')}}</strong>
    </div>
    @endif
@endif
    <div class="card">
        <div class="card-body">


            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>

                            <th width="5%">ID</th>
                            <th width="10%">Fecha Entr</th>
                            <th width="10%">Fecha Conf</th>
                            <th>Remitente</th>
                            <th>Localidad Remitente</th>
                            <th>Destinatario</th>
                            <th>Localidad Entrega</th>
                            <th width="5px">Total Bultos</th>
                            <th width="5px">Total Kgr</th>
                            <th>Estado</th>
                            <th>Etiq. Doc</th>
                            <th>Etiq. Carga</th>
                            <th>Desconfirmar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>

                            <td>{{ $order->id }}</td>
                            <td>{{ $order->fechaEntrega }}</td>
                            <td>{{ $order->fechaConfirmacion }}</td>
                            <td>{{ $order->direccionRemitente->cliente->razonSocial ?? 'N/A' }}</td>
                            <td>{{ $order->direccionRemitente->provincia ?? 'N/A' }}</td>
                            <td>{{ $order->direccionDestinatario->cliente->razonSocial ?? 'N/A' }}</td>
                            <td>{{ $order->direccionDestinatario->provincia ?? 'N/A' }}</td>

                            <td>{{ $order->totaBultos }}</td>
                            <td>{{ $order->totalKgr }}</td>
                            <td>
                                @if ($order->estado == 1)
                                    <span class="badge bg-success">Confirmado</span>
                                @else
                                    <span class="badge bg-warning">Borrador</span>
                                @endif
                            </td>
                            <td width="5px">
                                <a href="{{ route('admin.orders.pdf', $order) }}" class="btn btn-warning btn-sm">
                                    <i class="fa fa-paperclip"></i>
                                </a>
                            </td>
                            <td width="5px">
                                <a href="{{route('admin.orders.boxes',$order)}}" class="btn btn-primary btn-sm"><i class="fa fa-archive" aria-hidden="true"></i></a>
                            </td>
                            <td width="5px">
                                @if ($order->estado == 1)
                                    <!-- Botón para desconfirmar -->
                                    <form action="{{ route('admin.orders.unconfirm', $order->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fa fa-minus-square"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>

    // Seleccionar todos los checkboxes
    document.getElementById('select-all').onclick = function() {
        var checkboxes = document.getElementsByName('order_ids[]');
        for (var checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    }
</script>
@stop




