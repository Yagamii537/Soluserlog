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
    <h1>Crear CLiente</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        {!! Form::open(['route'=> 'admin.clientes.store']) !!}


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

<h4>Direcciones</h4>
    <table class="table table-bordered" id="addressTable">
        <thead>
            <tr>
                <th>Nombre de Sucursal</th>
                <th>Dirección</th>
                <th>Ciudad</th>
                <th>Provincia</th>
                <th>Zona</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><input type="text" name="direcciones[0][nombre_sucursal]" class="form-control"></td>
                <td><input type="text" name="direcciones[0][direccion]" class="form-control" required></td>
                <td><input type="text" name="direcciones[0][ciudad]" class="form-control" required></td>
                <td><input type="text" name="direcciones[0][provincia]" class="form-control"></td>
                <td><input type="text" name="direcciones[0][zona]" class="form-control"></td>
                <td><button type="button" class="btn btn-danger remove-address">Eliminar</button></td>
            </tr>
        </tbody>
    </table>

    <button type="button" id="addAddress" class="btn btn-secondary">Agregar Dirección</button>


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



        {!! Form::submit('Crear Cliente', ['class'=>'btn btn-primary']) !!}
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
    let addressIndex = 1;

// Agregar nueva fila para dirección
document.getElementById('addAddress').addEventListener('click', function() {
    let tableBody = document.getElementById('addressTable').getElementsByTagName('tbody')[0];
    let newRow = document.createElement('tr');

    newRow.innerHTML = `
        <td><input type="text" name="direcciones[${addressIndex}][nombre_sucursal]" class="form-control"></td>
        <td><input type="text" name="direcciones[${addressIndex}][direccion]" class="form-control" required></td>
        <td><input type="text" name="direcciones[${addressIndex}][ciudad]" class="form-control" required></td>
        <td><input type="text" name="direcciones[${addressIndex}][provincia]" class="form-control"></td>
        <td><input type="text" name="direcciones[${addressIndex}][zona]" class="form-control"></td>
        <td><button type="button" class="btn btn-danger remove-address">Eliminar</button></td>
    `;

    tableBody.appendChild(newRow);
    addressIndex++;
});

// Eliminar fila de dirección
document.getElementById('addressTable').addEventListener('click', function(event) {
    if (event.target.classList.contains('remove-address')) {
        event.target.closest('tr').remove();
    }
});

    $(document).ready(function() {
        $('#provincia').on('change', function() {
            let provinciaId = $(this).val();

            // Limpiar el combo box de ciudades
            $('#ciudad').empty().append('<option value="">Seleccione una ciudad</option>');

            if (provinciaId) {
                $.ajax({
                    url: `/admin/clientes/get-ciudades/${provinciaId}`,
                    type: 'GET',
                    success: function(data) {
                        $.each(data, function(index, ciudad) {
                            $('#ciudad').append(`<option value="${ciudad}">${ciudad}</option>`);
                        });
                    },
                    error: function() {
                        alert('Hubo un problema al cargar las ciudades.');
                    }
                });
            }
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


