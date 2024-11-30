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
{!! Form::open(['id' => 'confirmForm','route' => 'admin.orders.confirm', 'method' => 'POST']) !!}
<a href="{{route('admin.orders.create')}}" class="btn btn-primary btn-sm float-right">Nuevo Pedido</a>
{!! Form::submit('Confirmar Pedidos', ['class' => 'mr-4 btn btn-success btn-sm float-right']) !!}

    <h1 ><i class="fa-solid fa-archive mr-4 mt-2 mb-2"></i>Lista de Pedidos</h1>

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
@endif

@livewire('admin.orders-index')

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





