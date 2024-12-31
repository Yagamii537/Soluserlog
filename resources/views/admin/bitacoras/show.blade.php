@extends('adminlte::page')

@section('title', 'Detalles de la Bitácora')

@section('content_header')
    <h1>Detalles de la Bitácora</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <h4>Guía #{{ $bitacora->guia->numero_guia }}</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Pedido</th>
                    <th>Hora Llegada Origen</th>
                    <th>Hora Carga</th>
                    <th>Hora Salida Origen</th>
                    <th>Hora Llegada Destino</th>
                    <th>Hora Descarga</th>
                    <th>Hora Salida Destino</th>
                </tr>
            </thead>
            <tbody>
                @foreach($detalles as $detalle)
                <tr>
                    <td>Pedido #{{ $detalle->order->id }}</td>
                    <td>{{ $detalle->hora_origen_llegada }}</td>
                    <td>{{ $detalle->hora_carga }}</td>
                    <td>{{ $detalle->hora_salida_origen }}</td>
                    <td>{{ $detalle->hora_destino_llegada }}</td>
                    <td>{{ $detalle->hora_descarga }}</td>
                    <td>{{ $detalle->hora_salida_destino }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@stop
