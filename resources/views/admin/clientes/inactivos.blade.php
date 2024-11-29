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
    <a href="{{route('admin.clientes.index') }}" class="btn btn-primary btn-sm float-right mr-2">Ver Clientes Activos</a>
    <h1 ><i class="fa-solid fa-users mr-4 mt-2 mb-2"></i>Lista Desactivados</h1>

@stop

@section('content')
@if (session('info'))
    <div class="alert alert-success">
        <strong>{{session('info')}}</strong>
    </div>

@endif
<div class="container">
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <th scope="col">ID</th>
                    <th scope="col">Codigo</th>
                    <th scope="col">Razon Social</th>
                    <th scope="col">Ruc</th>
                    <th scope="col">Tipo Institucion</th>
                    <th scope="col">Correo</th>
                    <th scope="col">Telefono</th>
                    <th scope="col" colspan="2">Acciones</th>
                </thead>
                <tbody>
                    @foreach ($clientes as $cliente)
                        <tr>
                            <td>{{$cliente->id}}</td>
                            <td>{{$cliente->codigoCliente}}</td>
                            <td>{{$cliente->razonSocial}}</td>
                            <td>{{$cliente->ruc}}</td>
                            <td>{{$cliente->tipoInstitucion}}</td>
                            <td>{{$cliente->correo}}</td>
                            <td>{{$cliente->telefono}}</td>
                            <td width="10px">
                                <!-- Formulario de reactivacion -->
                                <form action="{{ route('admin.clientes.reactivar', $cliente) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm" >
                                        <i class="fa fa-check"></i>
                                    </button>
                                </form>

                            </td>

                        </tr>

                    @endforeach

                </tbody>
            </table>

        </div>
    </div>




</div>





@stop

@section('js')
    <script>
        let formId;

        document.querySelectorAll('[data-form-id]').forEach(button => {
            button.addEventListener('click', function() {
                formId = this.getAttribute('data-form-id');
            });
        });

        document.getElementById('confirmDeleteButton').addEventListener('click', function() {
            document.getElementById(formId).submit();
        });
    </script>
@stop




