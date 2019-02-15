@extends('app')

<?php
    if ($publicPerson->exists):
        $form_data = array('route' => array('publicPerson.update', $publicPerson->id), 'method' => 'PATCH');
        $action    = 'Editar';
    else:
        $form_data = array('route' => 'publicPerson.store', 'method' => 'POST');
        $action    = 'Crear';        
    endif;
?>

@section ('content')

<h1>{!! $action !!} Destinatario</h1>

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
      {!! Form::label('cellPhone', 'Numeo celular') !!}
      {!! Form::text('cellPhone', null, array('placeholder' => 'cellPhone', 'class' => 'form-control')) !!}        
    </div>
  </div>
  <div class="row">
    <div class="form-group col-md-4">
      {!! Form::label('twitter', 'twitter') !!}
      {!! Form::text('twitter', null, array('placeholder' => 'twitter', 'class' => 'form-control')) !!}
    </div>
    <div class="form-group col-md-4">
      {!! Form::label('identification', 'identificación') !!}
      {!! Form::text('identification', null, array('placeholder' => 'identification', 'class' => 'form-control')) !!}
    </div>
  </div>

  {!! Form::button('Aceptar', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}    
  {!! Form::button('Cancelar', array('type' => 'button', 'class' => 'btn btn-danger', 'onclick' => 'history.back()')) !!}
  
{!! Form::close() !!}

@stop