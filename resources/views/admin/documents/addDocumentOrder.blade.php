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
            <p><strong>Razón Social:</strong> {{ $order->direccionRemitente->cliente->razonSocial ?? 'N/A' }}</p>
            <p><strong>Fecha del pedido:</strong> {{ $order->fechaCreacion }}</p>
            <p><strong>Localidad:</strong> {{ $order->direccionDestinatario->provincia ?? 'N/A' }}</p>
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
                    <!-- Primera fila de documentos -->
                    <tr>
                        <td>{!! Form::text('documents[0][n_documento]', null, ['class' => 'form-control', 'required']) !!}</td>
                        <td>{!! Form::select('documents[0][tipo_carga]', ['CAJAS' => 'CAJAS', 'PAQUETES' => 'PAQUETES'], null, ['class' => 'form-control', 'required']) !!}</td>
                        <td><input type="number" name="documents[0][cantidad_bultos]" class="form-control cantidad-bultos" required></td>
                        <td><input type="number" name="documents[0][cantidad_kg]" class="form-control cantidad-kg" readonly required></td>
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
@stop

@section('js')
<script>
    let documentIndex = 1;

    // Función para agregar una nueva fila de documento
    function addNewDocumentRow() {
        const tableBody = document.querySelector('#document-table tbody');
        const newRow = `
            <tr>
                <td><input type="text" name="documents[${documentIndex}][n_documento]" class="form-control" required></td>
                <td>
                    <select name="documents[${documentIndex}][tipo_carga]" class="form-control" required>
                        <option value="CAJAS">CAJAS</option>
                        <option value="PAQUETES">PAQUETES</option>
                    </select>
                </td>
                <td><input type="number" name="documents[${documentIndex}][cantidad_bultos]" class="form-control cantidad-bultos" required></td>
                <td><input type="number" name="documents[${documentIndex}][cantidad_kg]" class="form-control cantidad-kg" readonly required></td>
                <td><input type="text" name="documents[${documentIndex}][factura]" class="form-control" required></td>
                <td><input type="text" name="documents[${documentIndex}][observaciones]" class="form-control"></td>
                <td><button type="button" class="btn btn-danger remove-row"><i class="fa fa-trash"></i></button></td>
            </tr>
        `;
        tableBody.insertAdjacentHTML('beforeend', newRow);

        // Reaplicar eventos a todas las filas
        applyCalculationEvents();
        documentIndex++;
    }

    // Función para aplicar eventos a todas las filas
    function applyCalculationEvents() {
        const allRows = document.querySelectorAll('#document-table tbody tr');

        allRows.forEach(row => {
            const cantidadBultosInput = row.querySelector('.cantidad-bultos');
            const cantidadKgInput = row.querySelector('.cantidad-kg');

            if (cantidadBultosInput && cantidadKgInput) {
                cantidadBultosInput.addEventListener('input', function () {
                    const cantidadBultos = parseFloat(cantidadBultosInput.value) || 0;
                    cantidadKgInput.value = (cantidadBultos * 15).toFixed(2); // Multiplicar por 15
                });
            }
        });
    }

    // Aplicar eventos iniciales al cargar la página
    document.addEventListener('DOMContentLoaded', function () {
        applyCalculationEvents();
    });

    // Agregar nueva fila al hacer clic en "Agregar Documento"
    document.getElementById('add-document').addEventListener('click', function () {
        addNewDocumentRow();
    });

    // Eliminar una fila de documento
    document.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-row')) {
            event.target.closest('tr').remove();
        }
    });
</script>

@stop
