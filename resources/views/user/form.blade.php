@extends('app')

<?php
use App\User;
use App\Account;
use App\Session;

    if ($user->exists):
        $form_data = array('route' => array('user.update', $user->id), 'method' => 'PATCH');
        $action    = 'Editar';
    else:
        $form_data = array('route' => 'user.store', 'method' => 'POST');
        $action    = 'Crear';        
    endif;

?>

@section ('content')

<h1>{!! $action !!} usuario</h1>

{!! Form::model($user, $form_data, array('role' => 'form')) !!}
  
  @include ('user/errors', array('errors' => $errors))

  <div class="row">
  <div class="form-group col-md-4">
      {!! Form::label('firstName', 'FistName') !!}
      {!! Form::text('firstName', null, array('placeholder' => 'FirstName', 'class' => 'form-control')) !!}        
    </div>
    <div class="form-group col-md-4">
      {!! Form::label('lastName', 'LastName') !!}
      {!! Form::text('lastName', null, array('placeholder' => 'lastName', 'class' => 'form-control')) !!}        
    </div>
    <div class="form-group col-md-4">
      {!! Form::label('email', 'Email') !!}
      {!! Form::text('email', null, array('placeholder' => 'Email', 'class' => 'form-control')) !!}
    </div>
  </div>

  <div class="row">
    <div class="form-group col-md-4">
      {!! Form::label('password', 'password') !!}
      {!! Form::password('password', array('class' => 'form-control')) !!}
    </div>
    <div class="form-group col-md-4">
      {!! Form::label('password_confirmation', 'Password Confirmation') !!}
      {!! Form::password('password_confirmation', array('class' => 'form-control')) !!}
    </div>
  </div>

  <div class="row">
    <div class="form-group col-md-4">
      {!! Form::label('phoneNumber', 'phone Number') !!}
      {!! Form::text('phoneNumber', null, array('placeholder' => 'PhoneNumber', 'class' => 'form-control')) !!}
    </div>
    <div class="form-group col-md-4">
      {!! Form::label('idTelegram', 'Telegram') !!}
      {!! Form::text('idTelegram', null, array('placeholder' => 'Telegram', 'class' => 'form-control')) !!}        
    </div>
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
       
        if($currentUser->pOperator=='9000')
        {
   ?>
    <select multiple="multiple" name="idAccount">
      <option value="" disabled selected>Click para seleccionar los cuenta</option>
        @foreach ($account as $account)
          <option value="{{ $account->id }}"> {{$account->name}} </option>
        @endforeach
     </select>
  </div>
    <?php
  }
   $user->pOperator = '1';
  ?>

  {!!  Form::hidden('pOperator', $user->pOperator, array('readonly' )) !!}
  {!!  Form::hidden('old_email', $user->email, array('readonly' )) !!}
  {!! Form::button('Aceptar', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}    
  {!! Form::button('Cancelar', array('type' => 'button', 'class' => 'btn btn-danger', 'onclick' => 'history.back()')) !!}
  
{!! Form::close() !!}

@stop