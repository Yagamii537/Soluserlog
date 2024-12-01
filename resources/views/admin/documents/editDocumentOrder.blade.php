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
    <h1 ><i class="fa-solid fa-file mr-4 mt-2 mb-2"></i>Editar Documentos al Pedido #{{ $order->id }}</h1>
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
            <p><strong># Tracking:</strong> {{ $order->tracking_number ?? 'N/A' }}</p>
            <p><strong>Razón Social:</strong> {{ $order->direccionRemitente->cliente->razonSocial ?? 'N/A' }}</p>
            <p><strong>Fecha del pedido:</strong> {{ $order->fechaCreacion }}</p>
            <p><strong>Localidad:</strong> {{ $order->direccionDestinatario->provincia ?? 'N/A' }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <!-- Formulario para editar documentos -->
            {!! Form::open(['route' => ['admin.documents.updateDocumentOrder', $order->id], 'method' => 'PUT']) !!}
            {!! Form::hidden('order_id', $order->id) !!}

            <table class="table" id="document-table">
                <thead>
                    <tr>
                        <th>No. Fact.</th>
                        <th>Tipo Carga</th>
                        <th>Cant. Bultos</th>
                        <th>Cant. Kg</th>
                        <th>No. Doc</th>
                        <th>Observaciones</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->documents as $index => $document)
                        <tr>
                            <td>{!! Form::text("documents[$index][factura]", $document->factura, ['class' => 'form-control', 'required']) !!}</td>

                            <td>{!! Form::select("documents[$index][tipo_carga]", ['CAJAS' => 'CAJAS', 'PAQUETES' => 'PAQUETES'], $document->tipo_carga, ['class' => 'form-control', 'required']) !!}</td>
                            <td><input type="number" name="documents[{{ $index }}][cantidad_bultos]" value="{{ $document->cantidad_bultos }}" class="form-control cantidad-bultos" required></td>
                            <td><input type="number" name="documents[{{ $index }}][cantidad_kg]" value="{{ $document->cantidad_kg }}" class="form-control cantidad-kg" readonly required></td>
                            <td>{!! Form::text("documents[$index][n_documento]", $document->n_documento, ['class' => 'form-control', 'required']) !!}</td>
                            <td>{!! Form::text("documents[$index][observaciones]", $document->observaciones, ['class' => 'form-control']) !!}</td>
                            <td>
                                <button type="button" class="btn btn-danger remove-row"><i class="fa fa-trash"></i></button>
                                {!! Form::hidden("documents[$index][id]", $document->id) !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="button" id="add-document" class="btn btn-success">Agregar Documento</button>

            <!-- Botón para guardar cambios -->
            {!! Form::submit('Guardar Cambios', ['class' => 'btn btn-primary']) !!}

            {!! Form::close() !!}
        </div>
    </div>
@stop


@section('js')
<script>
    let documentIndex = {{ $order->documents->count() }};

// Función para agregar una nueva fila de documento
function addNewDocumentRow() {
    const tableBody = document.querySelector('#document-table tbody');
    const newRow = `
        <tr>
            <td><input type="text" name="documents[${documentIndex}][factura]" class="form-control" required></td>
            <td>
                <select name="documents[${documentIndex}][tipo_carga]" class="form-control" required>
                    <option value="CAJAS">CAJAS</option>
                    <option value="PAQUETES">PAQUETES</option>
                </select>
            </td>
            <td><input type="number" name="documents[${documentIndex}][cantidad_bultos]" class="form-control cantidad-bultos" required></td>
            <td><input type="number" name="documents[${documentIndex}][cantidad_kg]" class="form-control cantidad-kg" readonly required></td>
            <td><input type="text" name="documents[${documentIndex}][n_documento]" class="form-control" required></td>

            <td><input type="text" name="documents[${documentIndex}][observaciones]" class="form-control"></td>
            <td><button type="button" class="btn btn-danger remove-row"><i class="fa fa-trash"></i></button></td>
        </tr>
    `;
    tableBody.insertAdjacentHTML('beforeend', newRow);

    // Aplicar evento de cálculo al campo "cantidad_bultos" de la nueva fila
    applyCalculationEvent();
    documentIndex++;
}

// Aplicar el evento de cálculo en todos los campos "cantidad_bultos"
function applyCalculationEvent() {
    document.querySelectorAll('.cantidad-bultos').forEach(input => {
        input.addEventListener('input', function() {
            const cantidadBultos = parseFloat(this.value) || 0;
            const cantidadKgInput = this.closest('tr').querySelector('.cantidad-kg');
            if (cantidadKgInput) {
                cantidadKgInput.value = (cantidadBultos * 15).toFixed(2); // Multiplica por 15
            }
        });
    });
}

// Aplicar eventos iniciales al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    applyCalculationEvent(); // Asegurarse de aplicar a todas las filas existentes
});

// Agregar nueva fila al hacer clic en "Agregar Documento"
document.getElementById('add-document').addEventListener('click', function() {
    addNewDocumentRow();
});

// Eliminar una fila de documento
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('remove-row')) {
        event.target.closest('tr').remove();
    }
});

</script>
@stop
