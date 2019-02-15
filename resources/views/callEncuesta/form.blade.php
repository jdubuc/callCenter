@extends('app')

<?php
use App\PublicPerson;
use App\PublicPersonGroup;
use App\PublicPersonPublicPersonGroup;
use App\User;
use App\PersonCampaign;
use App\Campaign;
$publicPerson = new PublicPerson;
 
        $form_data = array('route' => 'callEncuesta.store', 'method' => 'POST');
        $action    = 'Registrar';        
Session::flash('backUrl', Request::fullUrl());
  $url = Session::get('backUrl');
?>

@section ('content')

<h1>{!! $action !!} Destinatario para Encuesta {{ $campaign->name }}</h1>

{!! Form::model($publicPerson, $form_data, array('role' => 'form')) !!}

  @include ('publicPerson/errors', array('errors' => $errors))

  <div class="row">
  <div class="form-group col-md-4">
      {!! Form::label('firstName', 'Nombre') !!}
      {!! Form::text('firstName', null, array('placeholder' => 'FirstName', 'class' => 'form-control')) !!}        
    </div>
    <div class="form-group col-md-4">
      {!! Form::label('lastName', 'Apellido') !!}
      {!! Form::text('lastName', null, array('placeholder' => 'lastName', 'class' => 'form-control')) !!}        
    </div>
  </div>

  <div class="row">
    <div class="form-group col-md-4">
      {!! Form::label('email', 'Correo electrónico') !!}
      {!! Form::text('email', null, array('placeholder' => 'Email', 'class' => 'form-control')) !!}
    </div>
    <div class="form-group col-md-4">
      {!! Form::label('cedula', 'cedula') !!}
      {!! Form::text('cedula', null, array('placeholder' => 'cedula', 'class' => 'form-control')) !!}
    </div>
  </div>

  <div class="row">
    <div class="form-group col-md-4">
      {!! Form::label('phoneNumber', 'Numero telefónico') !!}
      {!! Form::text('phoneNumber', null, array('placeholder' => 'PhoneNumber', 'class' => 'form-control')) !!}
    </div>
    <div class="form-group col-md-4">
      {!! Form::label('cellPhone', 'Numero celular') !!}
      {!! Form::text('cellPhone', null, array('placeholder' => 'cellPhone', 'class' => 'form-control')) !!}        
    </div>
  </div>
  <div class="row">
    <div class="form-group col-md-4">
      {!! Form::label('twitter', 'twitter') !!}
      {!! Form::text('twitter', null, array('placeholder' => 'twitter', 'class' => 'form-control')) !!}
    </div>
    <!--<div class="form-group col-md-4">
      {!! Form::label('identification', 'identificación') !!}
      {!! Form::text('identification', null, array('placeholder' => 'identification', 'class' => 'form-control')) !!}
    </div>-->
  </div>
{!!  Form::hidden('campaign', $campaign->id, array('readonly' )) !!}
{!! Form::button('Registrar', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}    
  
{!! Form::close() !!}

@stop