@extends('app')

<?php
    if ($campaign->exists):
        $form_data = array('route' => array('campaign.update', $campaign->id), 'method' => 'PATCH');
        $action    = 'Edit';
        $campaign->idPersonModificator = Auth::user()->id;
    else:
        $form_data = array('route' => 'campaign.store', 'method' => 'POST');
        $action    = 'Create';     
        $campaign->idPersonModificator = Auth::user()->id;
        $campaign->idPersonCreator = Auth::user()->id; 
        $campaign->type='CallCenter';
        $campaign->active = '0';
        $campaign->tries = 1;

    endif;
?>

@section ('content')

<h1>{!! $action !!} campaign</h1>

{!! Form::model($campaign, $form_data, array('role' => 'form')) !!}

  @include ('campaign/errors', array('errors' => $errors))

  <div class="row">
    <div class="form-group col-md-4">
        {!! Form::label('name', 'Nombre de Campaña') !!}
        {!! Form::text('name', null, array('placeholder' => 'Nombre', 'class' => 'form-control')) !!}        
      </div>
      <div class="form-group col-md-4">
        {!! Form::label('campaignMessage', 'Mensaje de Campaña') !!}
        {!! Form::textarea('campaignMessage', null, array( 'class' => 'form-control','rows' =>'4' ,'cols' =>'50')) !!}        
      </div>
    </div>
    <div class="row">
      <div class="form-group col-md-4">
        {!! Form::label('dateTimeStart', 'Inicio de la Campaña') !!}
        <input type="datetime-local" name="dateTimeStart" class="form-control">
      </div>

      <div class="form-group col-md-4">
        {!! Form::label('dateTimeEnd', 'Final de la Campaña') !!}
        <input type="datetime-local" name="dateTimeEnd" class="form-control">       
      </div>
    </div>
    <div class="row">
     <div class="form-group col-md-4">
        {!! Form::label('tries', 'Ingrese el numero de intentos que desea por llamada (minimo 1)') !!}
        {!! Form::text('tries', null, array('placeholder' => 'Numero de intentos', 'class' => 'form-control')) !!}        
      </div>
    
    </div>
  {!!  Form::hidden('type', $campaign->type, array('readonly' )) !!}
  {!!  Form::hidden('active', $campaign->active, array('readonly' )) !!}
  {!!  Form::hidden('idPersonCreator', $campaign->idPersonCreator, array('readonly' )) !!}
  {!!  Form::hidden('idPersonModificator', $campaign->idPersonModificator, array('readonly' )) !!}
  {!! Form::button('Crear', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}    
  <input type="button" class="btn btn-danger" onclick="location.href = '{{ url('/') }}'" value="Cancelar">
{!! Form::close() !!}
 </div>
@stop