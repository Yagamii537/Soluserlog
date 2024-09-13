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
<a href="{{route('admin.orders.create')}}" class=" btn btn-primary btn-sm float-right">Nuevo Pedido</a>
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
@section('content')
<div class="container">
    <h1>Crear Pedido</h1>
    {!! Form::open(['route' => 'orders.store']) !!}

    <!-- Datos del Pedido -->
    <div class="form-group">
        {!! Form::label('cliente_id', 'Cliente') !!}
        {!! Form::select('cliente_id', $clientes->pluck('nombre', 'id'), null, ['class' => 'form-control', 'placeholder' => 'Seleccione un cliente', 'required']) !!}
    </div>

    <!-- Más campos del pedido -->

    <!-- Tabla para documentos -->
    <h2>Documentos</h2>
    <table class="table" id="document-table">
        <thead>
            <tr>
                <th>No. Doc</th>
                <th>Tipo Carga</th>
                <th>Cant. Bultos</th>
                <th>Cant. Kg</th>
                <th>No. Fact.</th>
                <th>Observaciones</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{!! Form::text('documents[0][no_doc]', null, ['class' => 'form-control', 'required']) !!}</td>
                <td>{!! Form::select('documents[0][tipo_carga]', ['CAJAS' => 'CAJAS', 'PAQUETES' => 'PAQUETES'], null, ['class' => 'form-control', 'required']) !!}</td>
                <td>{!! Form::number('documents[0][cant_bultos]', null, ['class' => 'form-control', 'required']) !!}</td>
                <td>{!! Form::number('documents[0][cant_kg]', null, ['class' => 'form-control', 'required']) !!}</td>
                <td>{!! Form::text('documents[0][no_fact]', null, ['class' => 'form-control', 'required']) !!}</td>
                <td>{!! Form::text('documents[0][observaciones]', null, ['class' => 'form-control']) !!}</td>
                <td><button type="button" class="btn btn-danger remove-row">Eliminar</button></td>
            </tr>
        </tbody>
    </table>
    <button type="button" id="add-document" class="btn btn-success">Agregar Documento</button>

    <!-- Botón para enviar el formulario -->
    {!! Form::submit('Guardar Pedido', ['class' => 'btn btn-primary mt-3']) !!}

    {!! Form::close() !!}
</div>

<script>
    // Script para agregar más filas al formulario de documentos
    let documentIndex = 1;

    document.getElementById('add-document').addEventListener('click', function() {
        const tableBody = document.querySelector('#document-table tbody');
        const newRow = `
            <tr>
                <td>{!! Form::text('documents[${documentIndex}][no_doc]', null, ['class' => 'form-control', 'required']) !!}</td>
                <td>{!! Form::select('documents[${documentIndex}][tipo_carga]', ['CAJAS' => 'CAJAS', 'PAQUETES' => 'PAQUETES'], null, ['class' => 'form-control', 'required']) !!}</td>
                <td>{!! Form::number('documents[${documentIndex}][cant_bultos]', null, ['class' => 'form-control', 'required']) !!}</td>
                <td>{!! Form::number('documents[${documentIndex}][cant_kg]', null, ['class' => 'form-control', 'required']) !!}</td>
                <td>{!! Form::text('documents[${documentIndex}][no_fact]', null, ['class' => 'form-control', 'required']) !!}</td>
                <td>{!! Form::text('documents[${documentIndex}][observaciones]', null, ['class' => 'form-control']) !!}</td>
                <td><button type="button" class="btn btn-danger remove-row">Eliminar</button></td>
            </tr>
        `;
        tableBody.insertAdjacentHTML('beforeend', newRow);
        documentIndex++;
    });

    // Script para eliminar filas de documentos
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-row')) {
            event.target.closest('tr').remove();
        }
    });
</script>


@stop

@section('js')

@stop




