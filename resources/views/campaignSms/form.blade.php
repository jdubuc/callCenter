@extends('app')

<?php
   
    if ($campaign->exists):
        $form_data = array('route' => array('campaign.update', $campaign->id), 'method' => 'PATCH');
        $action    = 'Editar';
         $campaign->idPersonModificator = Auth::user()->id;
    else:
        $form_data = array('route' => 'campaign.store', 'method' => 'POST');
        $action    = 'Crear';     
        $campaign->idPersonModificator = Auth::user()->id;
        $campaign->idPersonCreator = Auth::user()->id; 
        $campaign->active = '0';
        $campaign->type = 'Sms';
        $campaign->tries = 1;
    endif;
?>

@section ('content')

<h1>{!! $action !!} campaña de SMS</h1>

{!! Form::model($campaign, $form_data, array('role' => 'form')) !!}

  @include ('campaign/errors', array('errors' => $errors))

  <div class="row">
    <div class="form-group col-md-4">
        {!! Form::label('name', 'Nombre de Campaña') !!}
        {!! Form::text('name', null, array('placeholder' => 'name', 'class' => 'form-control')) !!}        
      </div>
      <div class="form-group col-md-4">
        {!! Form::label('campaignMessage', 'SMS') !!}
        {!! Form::textarea('campaignMessage', null, array( 'class' => 'form-control','id' => 'the-textarea','rows' =>'4' ,'cols' =>'50','maxlength' =>'160')) !!} 
          <div id="the-count">
          <span id="current">0</span>
          <span id="maximum">/ 160</span>
        </div>       
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
 
  {!!  Form::hidden('type', $campaign->type, array('readonly' )) !!}
  {!!  Form::hidden('active', $campaign->active, array('readonly' )) !!}
  {!!  Form::hidden('idPersonCreator', $campaign->idPersonCreator, array('readonly' )) !!}
  {!!  Form::hidden('idPersonModificator', $campaign->idPersonModificator, array('readonly' )) !!}
  {!!  Form::hidden('url', Request::url(), array('readonly')) !!}
  {!! Form::button('Aceptar', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}    
  <input type="button" class="btn btn-danger" onclick="location.href = '{{ url('/') }}'" value="Cancelar">
 
{!! Form::close() !!}
 </div>
 <script class="cssdeck" src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
<script>
 $('textarea').keyup(function() {
    
  var characterCount = $(this).val().length,
      current = $('#current'),
      maximum = $('#maximum'),
      theCount = $('#the-count');
    
  current.text(characterCount);
 
  
 
  
      
});

 </script>
@stop