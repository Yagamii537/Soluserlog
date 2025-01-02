@extends('adminlte::page')

@section('title', 'Tracking de Pedidos')

@section('content')
<div class="container">
    <h1>Tracking de Pedidos</h1>
    <form action="{{ route('admin.tracking.search') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="search_term">Número de Factura o Numero de Trancking:</label>
            <input type="text" id="search_term" name="search_term" class="form-control" placeholder="Ingrese el número de factura o tracking" required>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Buscar</button>
    </form>
</div>
@endsection
