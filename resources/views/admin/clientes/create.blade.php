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
        @include('admin.clientes.partials.form')

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


