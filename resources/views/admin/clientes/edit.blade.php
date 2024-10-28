@extends('adminlte::page')

@section('title', 'Dashboard')

{{-- icono de carga --}}
@section('preloader')
    <i class="fas fa-4x fa-spin fa-spinner text-secondary"></i>
    <h4 class="mt-4 text-dark">Cargando</h4>
@stop

@section('adminlte_css')
    @vite(['resources/js/app.js'])
    <style>
        /* Estilo personalizado para el modal */
        #map {
            height: 400px;
            width: 100%;
        }
    </style>

@stop

@section('content_header')
    <h1>Editar CLiente</h1>
@stop


@section('content')
@if (session('info'))
    <div class="alert alert-success">
        <strong>{{session('info')}}</strong>
    </div>

@endif
<div class="card">
    <div class="card-body">
        {!! Form::model($cliente, ['route' => ['admin.clientes.update', $cliente->id], 'method' => 'PUT']) !!}

    <div class="form-group">
        {!! Form::label('codigoCliente', 'Código Cliente') !!}
        {!! Form::text('codigoCliente', null, ['class' => 'form-control', 'maxlength' => 5, 'required' => true]) !!}
        @error('codigoCliente')
            <small class="text text-danger">{{$message}}</small>
        @enderror
    </div>

    <div class="form-group">
        {{-- primer parametro nombre del campo, segundo como quiero q aparezca --}}
        {!! Form::label('razonSocial', 'Razon Social') !!}
        {{-- primer parametro nombre del campo, segundo como null, tercero las clases --}}
        {!! Form::text('razonSocial', null, ['class'=>'form-control','placeholder'=>'Ingrese la Razon Social']) !!}

        @error('razonSocial')
            <small class="text text-danger">{{$message}}</small>
        @enderror
    </div>

    <div class="form-group">
        {!! Form::label('ruc', 'Ruc') !!}
        {!! Form::text('ruc', null, ['class'=>'form-control','placeholder'=>'Ingrese el Ruc']) !!}

        @error('ruc')
            <small class="text text-danger">{{$message}}</small>
        @enderror

    </div>
    <div class="form-group">
        {!! Form::label('direccion', 'Domicilio (calle y número)') !!}
        {!! Form::text('direccion', null, ['class'=>'form-control','placeholder'=>'Ingrese domicilio/calle/numero de casa']) !!}

        @error('direccion')
            <small class="text text-danger">{{$message}}</small>
        @enderror
    </div>

    <div class="form-group">
        {!! Form::label('tipoInstitucion', 'Tipo de Institución') !!}
        {!! Form::text('tipoInstitucion', null, ['class' => 'form-control', 'maxlength' => 150]) !!}

        @error('tipoInstitucion')
            <small class="text text-danger">{{$message}}</small>
        @enderror
    </div>

    <div class="form-group">
        {!! Form::label('tipoCliente', 'Tipo de Cliente') !!}
        {!! Form::text('tipoCliente', null, ['class' => 'form-control', 'maxlength' => 50]) !!}
        @error('tipoCliente')
            <small class="text text-danger">{{$message}}</small>
        @enderror
    </div>

    <div class="form-group">
        {!! Form::label('publicoPrivado', 'Público o Privado') !!}
        {!! Form::text('publicoPrivado', null, ['class' => 'form-control', 'maxlength' => 50]) !!}
        @error('publicoPrivado')
            <small class="text text-danger">{{$message}}</small>
        @enderror
    </div>


    <div class="form-group">
        {!! Form::label('telefono', 'Teléfono Fijo') !!}
        {!! Form::text('telefono', null, ['class'=>'form-control','placeholder'=>'Ingrese el numero de telefono fijo']) !!}

        @error('telefono')
            <small class="text text-danger">{{$message}}</small>
        @enderror
    </div>

    <div class="form-group">
        {!! Form::label('provincia', 'Provincia') !!}
        {!! Form::select('provincia', $provincias, $cliente->provincia, ['class' => 'form-control', 'placeholder' => 'Seleccione una provincia', 'id' => 'provincia']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('ciudad', 'Ciudad') !!}
        {!! Form::select('ciudad', [], $cliente->ciudad, ['class' => 'form-control', 'placeholder' => 'Seleccione una ciudad', 'id' => 'ciudad']) !!}
    </div>

    <div class="form-group">
        {!! Form::label('zona', 'Zona') !!}
        {!! Form::text('zona', null, ['class' => 'form-control', 'maxlength' => 100]) !!}
        @error('zona')
            <small class="text text-danger">{{$message}}</small>
        @enderror
    </div>

    <div class="form-group">
        {!! Form::label('correo', 'Email') !!}
        {!! Form::email('correo', null, ['class'=>'form-control','placeholder'=>'Ingrese el email']) !!}

        @error('correo')
            <small class="text text-danger">{{$message}}</small>
        @enderror
    </div>

    <div class="form-group">
        {!! Form::label('latitud', 'Latitud') !!}
        {!! Form::text('latitud', null, ['class'=>'form-control']) !!}

        @error('latitud')
            <small class="text text-danger">{{$message}}</small>
        @enderror
    </div>


    <div class="form-group">
        {!! Form::label('longitud', 'Longitud') !!}
        {!! Form::text('longitud', null, ['class'=>'form-control']) !!}

        @error('longitud')
            <small class="text text-danger">{{$message}}</small>
        @enderror
    </div>

    <button type="button" id="btn-google-maps" class="btn btn-warning">Google Maps</button>


    <br><br>


        {!! Form::submit('Actualizar Datos Cliente', ['class'=>'btn btn-success']) !!}
        <a href="{{route('admin.clientes.index')}}" class="btn btn-secondary" >Regresar</a>
        {!! Form::close() !!}
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="mapModalLabel">Seleccione una ubicación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="map" style="height: 400px; width: 100%;"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

@stop



@section('js')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAy2Hwfj8cc6JXoVd96At_TEzI1mi-8N7g&callback=initMap" async defer></script>

<script>

$(document).ready(function() {
        // Función para cargar ciudades cuando la provincia cambia
        function cargarCiudades(provinciaSeleccionada, ciudadSeleccionada = null) {
            $('#ciudad').empty().append('<option value="">Seleccione una ciudad</option>');

            if (provinciaSeleccionada) {
                $.ajax({
                    url: `/admin/clientes/get-ciudades/${provinciaSeleccionada}`,
                    type: 'GET',
                    success: function(data) {
                        $.each(data, function(index, ciudad) {
                            let selected = ciudad === ciudadSeleccionada ? 'selected' : '';
                            $('#ciudad').append(`<option value="${ciudad}" ${selected}>${ciudad}</option>`);
                        });
                    },
                    error: function() {
                        alert('Hubo un problema al cargar las ciudades.');
                    }
                });
            }
        }

        // Cargar ciudades al cargar la página en base a la provincia del cliente
        cargarCiudades($('#provincia').val(), "{{ $cliente->ciudad }}");

        // Cargar ciudades cuando se cambie la provincia
        $('#provincia').on('change', function() {
            cargarCiudades($(this).val());
        });
    });

    $('#mapModal').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
});

    document.getElementById('btn-google-maps').addEventListener('click', function() {
    var modal = new bootstrap.Modal(document.getElementById('mapModal'));
    modal.show();
    initMap();
});

document.querySelector('.btn-close').addEventListener('click', function() {
    var modal = bootstrap.Modal.getInstance(document.getElementById('mapModal'));
    modal.hide();
});

document.querySelector('.btn-secondary').addEventListener('click', function() {
    var modal = bootstrap.Modal.getInstance(document.getElementById('mapModal'));
    modal.hide();
});

function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: -0.180653, lng: -78.467834},
        zoom: 8
    });

    var marker = new google.maps.Marker({
        position: {lat: -0.180653, lng: -78.467834},
        map: map,
        draggable: true
    });

    google.maps.event.addListener(marker, 'dragend', function() {
        var lat = marker.getPosition().lat();
        var lng = marker.getPosition().lng();
        document.getElementById('latitud').value = lat;
        document.getElementById('longitud').value = lng;
    });
}


</script>


@stop


