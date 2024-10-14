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
    <h1>Lista de Usuarios</h1>
@stop

@section('content')
    <p class="btn btn-s">Welcome to this beautiful admin panel.s</p>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop
