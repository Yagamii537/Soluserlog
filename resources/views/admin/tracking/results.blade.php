@extends('adminlte::page')

@section('title', 'Resultados de Pedidos')

@section('content')
<div class="container">
    <h1>Resultados de Tracking</h1>

    @if ($orders->isEmpty())
        <p>No se encontraron pedidos asociados.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th># Pedido</th>
                    <th>Tracking</th>
                    <th>Remitente</th>
                    <th>Destinatario</th>
                    <th>Estado</th>
                    <th># Facturas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->tracking_number ?? 'N/A' }}</td>
                        <td>{{ $order->direccionRemitente->cliente->razonSocial }}</td>
                        <td>{{ $order->direccionDestinatario->cliente->razonSocial }}</td>
                        <td>
                            @if ($order->estado == 0)
                                Borrador
                            @elseif ($order->estado == 1)
                                Confirmado
                            @else
                                Adjunto en Manifiesto
                            @endif
                        </td>
                        <td>{{ $order->documents->pluck('factura')->join(', ') }}</td>
                        <td>
                            <a href="{{ route('admin.tracking.pdf', $order->id) }}" class="btn btn-sm btn-primary">
                                Descargar PDF
                            </a>
                        </td>
                    </tr>

                    <!-- Historial del Pedido -->
<tr>
    <td colspan="7">
        <h5>Historial del Pedido #{{ $order->id }}</h5>
        <ul>
            <li><strong>Creado:</strong> {{ \Carbon\Carbon::parse($order->fechaCreacion)->format('d/m/Y H:i:s') }}</li>
            @if ($order->fechaConfirmacion)
                <li><strong>Confirmado:</strong> {{ \Carbon\Carbon::parse($order->fechaConfirmacion)->format('d/m/Y H:i:s') }}</li>
            @endif
            @foreach ($order->manifiestos as $manifiesto)
                <li>
                    <strong>Asociado al Manifiesto:</strong> {{ $manifiesto->numero_manifiesto }}
                    (Fecha: {{ \Carbon\Carbon::parse($manifiesto->fecha)->format('d/m/Y H:i:s') }})
                </li>
                @foreach ($manifiesto->guias as $guia)
                    <li>
                        <strong>Asociado a la Guía:</strong> {{ $guia->numero_guia }}
                        (Fecha de Emisión: {{ \Carbon\Carbon::parse($guia->fecha_emision)->format('d/m/Y') }})
                    </li>
                    @if ($guia->bitacora)
                        <li><strong>Bitácora:</strong>
                            @php
                                // Filtrar detalles de la bitácora asociados al tracking o factura buscados
                                $detallesFiltrados = $guia->bitacora->detalles->filter(function ($detalle) use ($searchTerm) {
                                    return $detalle->order->tracking_number === $searchTerm ||
                                           $detalle->order->documents->contains('factura', $searchTerm);
                                });
                            @endphp
                            @if ($detallesFiltrados->isNotEmpty())
                                <ul>
                                    @foreach ($detallesFiltrados as $detalle)
                                        <strong>Fecha Origen:</strong> {{ $detalle->fechaOrigen ?? 'N/A' }}
                                        <ul>
                                            <li><strong>Hora Origen Llegada:</strong> {{ $detalle->hora_origen_llegada ?? 'N/A' }} </li>
                                            <li><strong>Hora Carga:</strong> {{ $detalle->hora_carga ?? 'N/A' }} </li>
                                            <li><strong>Hora Salida Origen:</strong> {{ $detalle->hora_salida_origen ?? 'N/A' }} </li>
                                            <li><strong>Novedades de Carga:</strong> {{ $detalle->novedades_carga ?? 'N/A' }} </li>
                                        </ul>
                                        <strong>Fecha Destino:</strong> {{ $detalle->fechaDestino ?? 'N/A' }}
                                        <ul>
                                            <li><strong>Hora Destino Llegada:</strong> {{ $detalle->hora_destino_llegada ?? 'N/A' }} </li>
                                            <li><strong>Hora Descarga:</strong> {{ $detalle->hora_descarga ?? 'N/A' }} </li>
                                            <li><strong>Hora Salida Destino:</strong> {{ $detalle->hora_salida_destino ?? 'N/A' }}</li>
                                            <li><strong>Novedades de Descarga:</strong> {{ $detalle->novedades_destino ?? 'N/A' }} </li>
                                        </ul>

                                        <!-- Mostrar imágenes asociadas -->
                                        @if ($detalle->images->count())
                                            <strong>Imágenes:</strong>
                                            <div class="d-flex flex-wrap">
                                                @foreach ($detalle->images as $image)
                                                    <div class="m-2">
                                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                                            alt="Imagen del detalle de bitácora"
                                                            style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; padding: 5px;">
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p>Sin imágenes asociadas.</p>
                                        @endif
                                    @endforeach
                                </ul>
                            @else
                                <p>No hay detalles de bitácora asociados al tracking o factura buscados.</p>
                            @endif
                        </li>
                    @endif
                @endforeach
            @endforeach
        </ul>
    </td>
</tr>

                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
