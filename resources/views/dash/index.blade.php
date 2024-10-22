@extends('adminlte::page')

@section('title', 'Dashboard')

{{-- icono de carga --}}
@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">Loading</h4>
@stop

@section('adminlte_css')
    @vite(['resources/js/app.js'])
@stop

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<div class="row">
    {{-- Tarjeta para pedidos confirmados --}}
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $pedidosConfirmados }}</h3>
                <p>Pedidos Confirmados</p>
            </div>
            <div class="icon">
                <i class="fas fa-check"></i>
            </div>
        </div>
    </div>

    {{-- Tarjeta para pedidos no confirmados --}}
    <div class="col-lg-4 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $pedidosNoConfirmados }}</h3>
                <p>Pedidos No Confirmados</p>
            </div>
            <div class="icon">
                <i class="fas fa-times"></i>
            </div>
        </div>
    </div>

    {{-- Tarjeta para manifiestos en proceso --}}
    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $manifiestosEnProceso }}</h3>
                <p>Manifiestos en Proceso</p>
            </div>
            <div class="icon">
                <i class="fas fa-truck"></i>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop
