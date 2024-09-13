


<div class="card-body bg-light">
    <div class="row">
        <div class="col-1">
            {!! Form::label(' ', 'Crear Pedido', ['class' => 'form-label']) !!}
        </div>
        <div class="col-1">
            {!! Form::label('fechaCreacion', 'Fecha', ['class' => 'form-label']) !!}
        </div>
        <div class="col">
            {!! Form::date('fechaCreacion', \Carbon\Carbon::now(), ['class' => 'form-control']) !!}
        </div>
        <div class="col-1">
            {!! Form::label('fechaConfirmacion', 'Fecha Conf.', ['class' => 'form-label']) !!}
        </div>
        <div class="col">
            {!! Form::date('fechaConfirmacion', null, ['class' => 'form-control']) !!}
        </div>
    </div>
</div>



<div class="card-header bg-dark" style="border-radius: 0;">
        Remitente
</div>
<div class="card-body bg-light">
    <div class="mb-3 input-group">
        {!! Form::label('remitente', 'Remitente', ['class' => 'form-label mr-2']) !!}
        {!! Form::text('remitente', 'B. BRAUN MEDICAL - PAQUETERÍA', ['class' => 'form-control', 'readonly']) !!}


    </div>
    <div class="mb-3 input-group">
        {!! Form::label('localidad', 'Localidad', ['class' => 'form-label mr-3']) !!}
        {!! Form::text('localidad', 'QUITO', ['class' => 'form-control', 'readonly']) !!}
    </div>

</div>



<div class="card-header bg-dark" style="border-radius: 0;">
    Destinatario
</div>
<div class="card-body bg-light">
    <div class="mb-3">
        {!! Form::label('cliente_id', 'Razon Social', ['class' => 'form-label']) !!}
        <div class="input-group">
            {!! Form::select('cliente_id', $clientes, null, ['class'=> 'form-control', 'id' => 'cliente_id']) !!}
            <a class="btn btn-warning" href="{{route('admin.clientes.create')}}">Nuevo</a>
        </div>
    </div>

    <div class="mb-3">
        {!! Form::label('domicilio', 'Domicilio', ['class' => 'form-label']) !!}
        {!! Form::text('', null, ['class' => 'form-control', 'id' => 'domicilio','disabled']) !!}
    </div>
    <div class="mb-3">
        {!! Form::label('dom_ampliado', 'Dom. Ampliado', ['class' => 'form-label']) !!}
        {!! Form::text('', null, ['class' => 'form-control', 'id' => 'dom_ampliado','disabled']) !!}
    </div>
    <div class="mb-3">
        {!! Form::label('localidad_destino', 'Localidad', ['class' => 'form-label']) !!}
        {!! Form::text('', null, ['class' => 'form-control', 'id' => 'localidad_destino','disabled']) !!}
    </div>

    <div class="mb-3">
        {!! Form::label('horario', 'Horario', ['class' => 'form-label']) !!}
        {!! Form::select('horario', ['TODO EL DÍA: 08:30 - 17:30' => 'TODO EL DÍA: 08:30 - 17:30'], null, ['class' => 'form-select']) !!}
    </div>
    <div class="mb-3">
        {!! Form::label('fechaEntrega', 'Fecha Entrega', ['class' => 'form-label']) !!}
        {!! Form::date('fechaEntrega', null, ['class' => 'form-control']) !!}
    </div>
    <div class="mb-3">
        {!! Form::label('observacion', 'Observaciones', ['class' => 'form-label']) !!}
        {!! Form::textarea('observacion', null, ['class' => 'form-control']) !!}
    </div>
    <div class="mb-3">
        {!! Form::label('estado', 'Estado', ['class' => 'form-label']) !!}
        {!! Form::text('estado', "Borrador", ['class' => 'form-control','disabled']) !!}
    </div>
</div>

<div class="card-header bg-light border" style="border-radius: 0;">
    <div class="row">
        <div class="col input-group">
            {!! Form::label('totaBultos', 'Total Bultos', ['class' => 'form-label mr-3']) !!}
            {!! Form::text('totaBultos', null, ['class' => 'form-control', 'disabled']) !!}
        </div>
        <div class="col input-group">
            {!! Form::label('totalKgr', 'Total Kgr', ['class' => 'form-label mr-3']) !!}
            {!! Form::text('totalKgr', null, ['class' => 'form-control', 'disabled']) !!}
        </div>
    </div>
</div>



