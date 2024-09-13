@extends('adminlte::page')

@section('title', 'Dashboard')

{{-- icono de carga --}}
@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">Cargando</h4>
@stop

@section('adminlte_css')
    @vite(['resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui/1.12.1/jquery-ui.min.css">

@stop

@section('content_header')
<div class="container">
    <div class="d-flex justify-content-between">
        <span class="text text-danger">Pedido</span>
        <span class="text text-danger">B. BRAUN MEDICAL - PAQUETERÍA</span>
    </div>
</div>


@stop

@section('content')

<div class="container">
    <div class="container mb-4">
        <div class="d-flex justify-content-center">
            {!! Form::open(['route' => 'admin.orders.store']) !!}
            {!! Form::submit('Agregar', ['class' => 'btn btn-warning mx-2']) !!}
            <a href="{{ route('admin.orders.index') }}" class="btn btn-dark mx-2">Cancelar</a>


        </div>
    </div>

    <div class="card">


        @include('admin.orders.partials.form')



        {!! Form::close() !!}

    </div>
</div>



@stop



@section('js')


<script>
    $(document).ready(function() {
    $('#cliente_id').change(function() {
        var clienteID = $(this).val();

        if (clienteID) {
            $.ajax({
                url: '/admin/clientes/' + clienteID,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Llenar los campos del formulario con los datos recibidos
                    $('#domicilio').val(data.direccion);
                    $('#dom_ampliado').val(data.ampliado);
                    $('#localidad_destino').val(data.localidad);
                },
                error: function() {
                    alert('Error al obtener los datos del cliente.');
                }
            });
        } else {
            // Limpiar los campos si no hay cliente seleccionado
            $('#domicilio').val('');
            $('#dom_ampliado').val('');
            $('#localidad_destino').val('');
        }
    });
});
</script>



@stop


