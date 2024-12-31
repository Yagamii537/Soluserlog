@extends('adminlte::page')

@section('title', 'Bitácoras')

@section('content_header')
    <h1>Lista de Bitácoras</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th># Guía de Ruta</th>
                    <th># Manifiesto</th>
                    <th>Pedidos Asociados</th>
                    <th>Camión</th>
                    <th>Conductor</th>
                    <th>Ayudante</th>
                    <th scope="col" colspan="3">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bitacoras as $bitacora)
                    <tr>
                        <td>{{ $bitacora->guia->numero_guia }}</td>
                        <td>{{ $bitacora->guia->manifiesto->numero_manifiesto }}</td>
                        <td>
                            @foreach ($bitacora->guia->manifiesto->orders as $order)
                                {{ $order->id }},
                            @endforeach
                        </td>
                        <td>
                            {{ $bitacora->guia->manifiesto->camion->numero_placa ?? 'N/A' }}
                            - {{ $bitacora->guia->manifiesto->camion->marca ?? 'N/A' }}
                        </td>
                        <td>{{ $bitacora->guia->manifiesto->conductor->nombre ?? 'N/A' }}</td>
                        <td>{{ $bitacora->guia->ayudante->nombre ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('admin.bitacoras.show', $bitacora->id) }}" class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        </td>
                        <td>
                            <a href="{{ route('admin.bitacoras.seleccionarDetalles', $bitacora->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-check" aria-hidden="true"></i></a>
                        </td>
                        <td>
                            <a href="{{ route('admin.bitacoras.pdf', $bitacora->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-file-pdf"></i></a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No hay bitácoras registradas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop
