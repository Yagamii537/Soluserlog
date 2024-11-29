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

    <a href="{{route('admin.clientes.create')}}" class="btn btn-primary btn-sm float-right">Nuevo Cliente</a>
    <a href="{{route('admin.clientes.inactivos') }}" class="btn btn-secondary btn-sm float-right mr-2">Ver Clientes Desactivados</a>
    <h1 ><i class="fa-solid fa-users mr-4 mt-2 mb-2"></i>Lista de Clientes</h1>

@stop

@section('content')
@if (session('info'))
    <div class="alert alert-danger">
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
                                <a href="{{route('admin.clientes.edit',$cliente)}}" class="btn btn-success btn-sm"><i class="fa fa-pencil"></i></i></a>
                            </td>
                            <td width="10px">
                                <!-- Formulario de eliminación -->
                                <form id="deleteForm-{{ $cliente->id }}" action="{{ route('admin.clientes.destroy', $cliente) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-form-id="deleteForm-{{ $cliente->id }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                                <!-- Modal de confirmación -->
                                <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Desactivación</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                ¿Estás seguro de que deseas desactivar este cliente?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Desactivar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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




