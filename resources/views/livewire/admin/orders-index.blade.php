<div>
    <div class="card">
        <div class="card-header">
            <input wire:model="search" type="text" class="form-control" placeholder="Ingrese Remitente o Razon Social">
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
                                <th width="5%">Facturas</th>
                                <th colspan="2">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>{!! Form::checkbox('order_ids[]', $order->id, false) !!}</td>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->fechaCreacion}}</td>
                                <td>{{ $order->direccionRemitente->cliente->razonSocial ?? 'N/A' }}</td>
                                <td>{{ $order->direccionRemitente->provincia ?? 'N/A' }}</td>
                                <td>{{ $order->direccionDestinatario->cliente->razonSocial ?? 'N/A' }}</td>
                                <td>{{ $order->direccionDestinatario->provincia ?? 'N/A' }}</td>
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
                                    <a href="#" wire:click.prevent="showDocuments({{ $order->id }})" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#documentsModal">
                                        <i class="fa fa-file" aria-hidden="true"></i>
                                    </a>
                                </td>
                                <td width="5px">
                                    <a href="{{route('admin.orders.edit',$order)}}" class="btn btn-success btn-sm"><i class="fa fa-pen"></i></a>
                                </td>
                                <td width="5px">
                                    <a href="{{route('admin.orders.confDelete',$order)}}" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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

    <!-- Modal para Visualizar Documentos -->
    <div wire:ignore.self class="modal fade" id="documentsModal" tabindex="-1" role="dialog" aria-labelledby="documentsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="documentsModalLabel">Documentos Asociados</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @if ($documents && $documents->count())
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Factura</th>
                                    <th>Tipo de Carga</th>
                                    <th>Cantidad Bultos</th>
                                    <th>Cantidad Kg</th>
                                    <th># Documento</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($documents as $document)
                                    <tr>
                                        <td>{{ $document->factura }}</td>
                                        <td>{{ $document->tipo_carga }}</td>
                                        <td>{{ $document->cantidad_bultos }}</td>
                                        <td>{{ $document->cantidad_kg }}</td>
                                        <td>{{ $document->n_documento }}</td>
                                        <td>{{ $document->observaciones }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No hay documentos asociados a este pedido.</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>
