@extends('app')

<?php

    use App\Account;
    use App\User;
    use App\PersonCampaign;
    use App\Campaign;
    use App\PersonGroup;
    use App\PersonPersonGroup;
    use App\PersonGroupCampaign;
    use App\validation;


    if ($user->exists):
        $form_data = array('route' => array('personGroupShowGroup.update', $user->id), 'method' => 'PATCH');
        $action    = 'Editar';
    else:
        $form_data = array('route' => 'personGroupShowGroup.store', 'method' => 'POST');
        $action    = 'Crear';        
    endif;
$url = Session::get('backUrl');

        $validation = new validation;
        $idPersonGroup=$validation->urlData(); 
        $grupo = PersonGroup::where('id', '=', $idPersonGroup)->first();
        //dd($grupo);
?>




@section ('content')

<h1>{!! $action !!} Operador para el grupo "{{ $grupo->name }}"</h1>

{!! Form::model($user, $form_data, array('role' => 'form')) !!}

  @include ('user/errors', array('errors' => $errors))

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
      {!! Form::label('email', 'Correo Electronico (Email)') !!}
      {!! Form::text('email', null, array('placeholder' => 'Email', 'class' => 'form-control')) !!}
    </div>
  </div>
@if($action!='Editar')
  <div class="row">
    <div class="form-group col-md-4">
      {!! Form::label('password', 'Contraseña') !!}
      {!! Form::password('password', array('class' => 'form-control')) !!}
    </div>
    <div class="form-group col-md-4">
      {!! Form::label('password_confirmation', 'Confimaciòn de Contraseña') !!}
      {!! Form::password('password_confirmation', array('class' => 'form-control')) !!}
    </div>
  </div>
@endif
  <div class="row">
    <div class="form-group col-md-4">
      {!! Form::label('phoneNumber', 'Nùmero de telefono') !!}
      {!! Form::text('phoneNumber', null, array('placeholder' => 'PhoneNumber', 'class' => 'form-control')) !!}
    </div>
    <!--<div class="form-group col-md-4">
      {!! Form::label('idTelegram', 'Telegram') !!}
      {!! Form::text('idTelegram', null, array('placeholder' => 'Telegram', 'class' => 'form-control')) !!}        
    </div>-->
  </div>
  <div class="row">
    <div class="form-group col-md-4">
      {!! Form::label('twitter', 'twitter') !!}
      {!! Form::text('twitter', null, array('placeholder' => 'twitter', 'class' => 'form-control')) !!}
    </div>
    <div class="form-group col-md-4">
      {!! Form::label('cedula', 'cedula') !!}
      {!! Form::text('cedula', null, array('placeholder' => 'cedula', 'class' => 'form-control')) !!}        
    </div>
  </div>

  <div class=" col s6 ">
  <?php
   $currentUser= Auth::user(); 
    $account = Account::all();
   $user->pOperator = '1';
  ?>
  {!!  Form::hidden('pOperator', $user->pOperator, array('readonly' )) !!}
  {!! Form::button('Aceptar', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}    
  {!! Form::button('Cancelar', array('type' => 'button', 'class' => 'btn btn-danger', 'onclick' => 'history.back()')) !!}

{!! Form::close() !!}

@stop