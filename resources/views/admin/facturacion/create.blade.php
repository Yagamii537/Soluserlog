@extends('adminlte::page')

@section('title', 'Crear Facturación')

@section('content')
<div class="container">
    <h1>Crear Facturación</h1>

    <form action="{{ route('admin.facturacion.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="manifiesto_id">Manifiesto</label>
            <select name="manifiesto_id" id="manifiesto_id" class="form-control" required>
                <option value="">Seleccione un manifiesto</option>
                @foreach ($manifiestos as $manifiesto)
                    <option value="{{ $manifiesto->id }}">
                        Manifiesto #{{ $manifiesto->numero_manifiesto }} - Conductor: {{ $manifiesto->conductor->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="valor">Valor</label>
            <input type="number" name="valor" id="valor" class="form-control" placeholder="Ingrese el valor" step="0.01" required>
        </div>

        <div class="form-group">
            <label for="adicional">Adicional</label>
            <input type="number" name="adicional" id="adicional" class="form-control" placeholder="Ingrese el adicional" step="0.01" required>
        </div>

        <div class="form-group">
            <label for="total">Total</label>
            <input type="number" name="total" id="total" class="form-control" placeholder="Ingrese el total" step="0.01" required>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Facturación</button>
    </form>
</div>
@endsection
