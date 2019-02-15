@extends('app')

<?php
use App\User;
use App\Account;

    if ($account->exists):
        $form_data = array('route' => array('account.update', $account->id), 'method' => 'PATCH');
        $action    = 'Editar';
    else:
        $form_data = array('route' => 'account.store', 'method' => 'POST');
        $action    = 'Crear';        
    endif;
?>

@section ('content')

<h1>{!! $action !!} cuentas</h1>

{!! Form::model($account, $form_data, array('role' => 'form')) !!}

  @include ('account/errors', array('errors' => $errors))

  <div class="row">
  <div class="form-group col-md-4">
      {!! Form::label('Name', 'Nombre') !!}
      {!! Form::text('name', null, array('placeholder' => 'nombre', 'class' => 'form-control')) !!}        
    </div>
    <div class="form-group col-md-4">
      {!! Form::label('contactName', 'Nombre contacto') !!}
      {!! Form::text('contactName', null, array('placeholder' => 'nombre contacto', 'class' => 'form-control')) !!}        
    </div>
    <div class="form-group col-md-4">
      {!! Form::label('email', 'Email') !!}
      {!! Form::text('email', null, array('placeholder' => 'Email', 'class' => 'form-control')) !!}
    </div>
  </div>

  <div class="row">
    <div class="form-group col-md-4">
      {!! Form::label('rif', 'RIF') !!}
      {!! Form::text('rif', null, array('placeholder' => 'RIF', 'class' => 'form-control')) !!}
    </div>
    <div class="form-group col-md-4">
      {!! Form::label('address', 'Dirección') !!}
      {!! Form::text('address', null, array('placeholder' => 'Dirección', 'class' => 'form-control')) !!}        
    </div>
  </div>
  {!! Form::button('Aceptar', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}    
  {!! Form::button('Cancelar', array('type' => 'button', 'class' => 'btn btn-danger', 'onclick' => 'history.back()')) !!}    
  
{!! Form::close() !!}

@stop