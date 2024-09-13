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
    {!! Form::label('localidad', 'Localidad/Ciudad/Provincia') !!}
    {!! Form::text('localidad', null, ['class'=>'form-control','placeholder'=>'Ingrese localidad/ciudad/provincia']) !!}

    @error('localidad')
        <small class="text text-danger">{{$message}}</small>
    @enderror
</div>

<div class="form-group">
    {!! Form::label('pisos', 'Piso/Dpto/Otros datos') !!}
    {!! Form::text('pisos', null, ['class'=>'form-control','placeholder'=>'Ingrese # de pisos,dpto']) !!}

    @error('pisos')
        <small class="text text-danger">{{$message}}</small>
    @enderror
</div>

<div class="form-group">
    {!! Form::label('CodigoPostal', 'Código Postal') !!}
    {!! Form::text('CodigoPostal', null, ['class'=>'form-control','placeholder'=>'Ingrese el codigo postal']) !!}

    @error('CodigoPostal')
        <small class="text text-danger">{{$message}}</small>
    @enderror
</div>
<div class="form-group">
    {!! Form::label('ampliado', 'Domicilio Ampliado') !!}
    {!! Form::text('ampliado', null, ['class'=>'form-control','placeholder'=>'Ingrese mas detalles del domicilio']) !!}

    @error('ampliado')
        <small class="text text-danger">{{$message}}</small>
    @enderror
</div>

<div class="form-group">
    {!! Form::label('celular', 'Teléfono Móvil') !!}
    {!! Form::text('celular', null, ['class'=>'form-control','placeholder'=>'Ingrese el numero de telefono móvil']) !!}

    @error('celular')
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
    {!! Form::label('correo', 'Email') !!}
    {!! Form::email('correo', null, ['class'=>'form-control','placeholder'=>'Ingrese el email']) !!}

    @error('correo')
        <small class="text text-danger">{{$message}}</small>
    @enderror
</div>

<div class="form-group">
    {!! Form::label('contribuyente', 'Contribuyente') !!}
    {!! Form::text('contribuyente', null, ['class'=>'form-control','placeholder'=>'Ingrese tipo de contribuyente']) !!}

    @error('contribuyente')
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
