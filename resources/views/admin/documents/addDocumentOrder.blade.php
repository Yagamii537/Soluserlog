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

    <h1 ><i class="fa-solid fa-file mr-4 mt-2 mb-2"></i>Agregar Documentos al Pedido #{{ $order->id }}</h1>

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
    <div class="card">
        <div class="card-body">
            <p><strong>Razon Social:</strong> {{ $order->cliente->razonSocial }}</p>
            <p><strong>Fecha del pedido:</strong> {{ $order->fechaCreacion }}</p>
            <p><strong>Localidad:</strong> {{ $order->localidad }}</p>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <!-- Formulario para agregar documentos -->
            {!! Form::open(['route' => 'admin.documents.store']) !!}

            {!! Form::hidden('order_id', $order->id) !!}

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
                        <td>{!! Form::text('documents[0][n_documento]', null, ['class' => 'form-control', 'required']) !!}</td>
                        <td>{!! Form::select('documents[0][tipo_carga]', ['CAJAS' => 'CAJAS', 'PAQUETES' => 'PAQUETES'], null, ['class' => 'form-control', 'required']) !!}</td>
                        <td>{!! Form::number('documents[0][cantidad_bultos]', null, ['class' => 'form-control', 'required']) !!}</td>
                        <td>{!! Form::number('documents[0][cantidad_kg]', null, ['class' => 'form-control', 'required']) !!}</td>
                        <td>{!! Form::text('documents[0][factura]', null, ['class' => 'form-control', 'required']) !!}</td>
                        <td>{!! Form::text('documents[0][observaciones]', null, ['class' => 'form-control']) !!}</td>
                        <td><button type="button" class="btn btn-danger remove-row"><i class="fa fa-trash"></i></button></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" id="add-document" class="btn btn-success">Agregar Documento</button>

            <!-- Botón para enviar el formulario -->
            {!! Form::submit('Guardar Pedido', ['class' => 'btn btn-primary']) !!}

            {!! Form::close() !!}
        </div>
    </div>


    <!-- Detalles del pedido -->





@stop

@section('js')
<script>
    // Script para agregar más filas al formulario de documentos
    let documentIndex = 1;

    document.getElementById('add-document').addEventListener('click', function() {
        const tableBody = document.querySelector('#document-table tbody');
        const newRow = `
            <tr>
                <td>{!! Form::text('documents[${documentIndex}][n_documento]', null, ['class' => 'form-control', 'required']) !!}</td>
                <td>{!! Form::select('documents[${documentIndex}][tipo_carga]', ['CAJAS' => 'CAJAS', 'PAQUETES' => 'PAQUETES'], null, ['class' => 'form-control', 'required']) !!}</td>
                <td>{!! Form::number('documents[${documentIndex}][cantidad_bultos]', null, ['class' => 'form-control', 'required']) !!}</td>
                <td>{!! Form::number('documents[${documentIndex}][cantidad_kg]', null, ['class' => 'form-control', 'required']) !!}</td>
                <td>{!! Form::text('documents[${documentIndex}][factura]', null, ['class' => 'form-control', 'required']) !!}</td>
                <td>{!! Form::text('documents[${documentIndex}][observaciones]', null, ['class' => 'form-control']) !!}</td>
                <td><button type="button" class="btn btn-danger remove-row"><i class="fa fa-trash"></i></button></td>
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




