@extends('adminlte::page')

@section('title', 'Editar Facturación')

@section('content')
<div class="container">
    <h1>Editar Facturación</h1>

    <form action="{{ route('admin.facturacion.update', $facturacion) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Información del Documento -->
        <div class="form-group">
            <label for="factura"># Factura</label>
            <input type="text" id="factura" class="form-control" value="{{ $facturacion->document->factura ?? 'N/A' }}" readonly>
        </div>

        <!-- Información del Cliente -->
        <div class="form-group">
            <label for="cliente">Cliente</label>
            <input type="text" id="cliente" class="form-control" value="{{ $facturacion->order->direccionDestinatario->cliente->razonSocial ?? 'N/A' }}" readonly>
        </div>

        <!-- Campos Editables -->
        <div class="form-group">
            <label for="valor">Valor</label>
            <input type="number" name="valor" id="valor" class="form-control" value="{{ $facturacion->valor }}" step="0.01" required>
        </div>

        <div class="form-group">
            <label for="adicional">Adicional</label>
            <input type="number" name="adicional" id="adicional" class="form-control" value="{{ $facturacion->adicional }}" step="0.01" required>
        </div>

        <div class="form-group">
            <label for="total">Total</label>
            <input type="number" name="total" id="total" class="form-control" value="{{ $facturacion->total }}" step="0.01" required>
        </div>

        <button type="submit" class="btn btn-success">Guardar Cambios</button>
    </form>
</div>
@endsection
