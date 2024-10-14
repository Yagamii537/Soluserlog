<div>
    <div class="card">
        <div class="card-header">
            <input wire:model="search" type="text" class="form-control" placeholder="Ingrese Remitente">
        </div>

        @if($orders->count())
            <div class="card-body">


                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{!! Form::checkbox('select_all', null, false, ['id' => 'select-all']) !!}</th>
                                <th>#</th>
                                <th width="10%">Fecha</th>
                                <th>Remitente</th>
                                <th>Localidad Remitente</th>
                                <th>Destinatario</th>
                                <th>Localidad Entrega</th>
                                <th width="5px">Total Bultos</th>
                                <th width="5px">Total Kgr</th>
                                <th>Estado</th>
                                <th colspan="2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>{!! Form::checkbox('order_ids[]', $order->id, false) !!}</td>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->fechaCreacion }}</td>
                                <td>{{ $order->remitente }}</td>
                                <td>{{ $order->localidad }}</td>
                                <td>{{ $order->cliente->razonSocial}}</td>
                                <td>{{ $order->cliente->localidad }}</td>
                                <td>{{ $order->totaBultos }}</td>
                                <td>{{ $order->totalKgr }}</td>
                                <td>
                                    @if ($order->estado == 1)
                                        Confirmado
                                    @else
                                        Borrador
                                    @endif
                                </td>
                                <td width="5px">
                                    <a href="{{route('admin.orders.edit',$order)}}" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></a>
                                </td>
                                <td width="5px">
                                    <a href="{{route('admin.orders.confDelete',$order)}}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div><!-- Botón de Confirmar Pedidos -->

            {!! Form::close() !!}
            </div>

            <div class="card-footer">
                {{$orders->links()}}
            </div>
        @else
        <div class="card-body">
            <strong>No existe registros.</strong>
        </div>

        @endif
    </div>
</div>
