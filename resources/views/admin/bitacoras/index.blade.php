@extends('adminlte::page')

@section('title', 'Bitácoras')

@section('content_header')
    <h1>Lista de Bitácoras</h1>
@stop

@section('content')

{{-- BUSCADOR --}}
<form method="GET" action="{{ route('admin.bitacoras.index') }}" class="mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-md-8">
            <label class="form-label mb-1">Buscar</label>
            <input type="text"
                   name="q"
                   value="{{ $q ?? '' }}"
                   class="form-control"
                   placeholder="Buscar por # Guía, # Manifiesto o # Pedido (ID)">
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary w-100" type="submit">
                <i class="fas fa-search"></i> Buscar
            </button>
        </div>

        <div class="col-md-2">
            <a href="{{ route('admin.bitacoras.index') }}" class="btn btn-outline-secondary w-100">
                <i class="fas fa-redo"></i> Limpiar
            </a>
        </div>
    </div>
</form>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th># Guía de Ruta</th>
                        <th># Manifiesto</th>
                        <th>Pedidos Asociados</th>
                        <th>Camión</th>
                        <th>Conductor</th>
                        <th>Ayudante</th>
                        <th class="text-center" colspan="4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bitacoras as $bitacora)
                        @php
                            $guia = $bitacora->guia;
                            $manifiesto = optional($guia)->manifiesto;

                            $camion = optional($manifiesto)->camion;
                            $conductor = optional($manifiesto)->conductor;
                            $ayudante = optional($manifiesto)->ayudante;

                            $orders = optional($manifiesto)->orders ?? collect();
                        @endphp

                        <tr>
                            <td>{{ $guia->numero_guia ?? 'N/A' }}</td>
                            <td>{{ $manifiesto->numero_manifiesto ?? 'N/A' }}</td>

                            <td>
                                @if($orders && $orders->count())
                                    {{ $orders->pluck('id')->join(', ') }}
                                @else
                                    —
                                @endif
                            </td>

                            <td>
                                {{ $camion->numero_placa ?? 'N/A' }}
                                @if($camion && ($camion->marca ?? null))
                                    - {{ $camion->marca }}
                                @endif
                            </td>

                            <td>{{ $conductor->nombre ?? 'N/A' }}</td>
                            <td>{{ $ayudante->nombre ?? 'N/A' }}</td>

                            <td class="text-center">
                                <a href="{{ route('admin.bitacoras.show', $bitacora->id) }}"
                                   class="btn btn-info btn-sm" title="Ver">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                            </td>

                            <td class="text-center">
                                <a href="{{ route('admin.bitacoras.seleccionarDetalles', $bitacora->id) }}"
                                   class="btn btn-primary btn-sm" title="Seleccionar detalles">
                                    <i class="fa fa-check" aria-hidden="true"></i>
                                </a>
                            </td>

                            <td class="text-center">
                                <a href="{{ route('admin.bitacoras.pdf', $bitacora->id) }}"
                                   class="btn btn-warning btn-sm" title="PDF">
                                    <i class="fa fa-file-pdf"></i>
                                </a>
                            </td>

                            <td class="text-center">
                                <a href="{{ route('admin.bitacoras.mapa', $bitacora->id) }}"
                                   class="btn btn-secondary btn-sm" title="Mapa">
                                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center p-4">
                                No hay bitácoras registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINACIÓN --}}
        <div class="mt-3">
            {{ $bitacoras->links() }}
        </div>
    </div>
</div>
@stop
