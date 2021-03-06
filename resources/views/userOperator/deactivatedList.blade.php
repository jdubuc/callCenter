@extends('app')

<?php
use App\User;

use App\Account;

 Session::flash('backUrl', Request::fullUrl());
  $url = Session::get('backUrl');

?>
@section ('title') Lista de Usuarios @stop



@section ('content')

  <h1>Lista de Operadores Desactivados</h1>
  
  <table class="table table-striped"style="width: 100%">
    <tr>
        <th>Email</th>
        <th>nombre</th>
        <th>apellido</th>
        <th>telefono</th>
        <th>Cuenta</th>
        <th>tipo de cuenta</th>
        <th>Creador por</th>
        <th>Opciones</th>

    </tr>
    @if (count($users) > 0)
      @foreach ($users as $user)
        
        <tr>
            <td>{{ $user->email }}</td>
            <td>{{ $user->firstName }}</td>
            <td>{{ $user->lastName }}</td>
            <td>{{ $user->phoneNumber }}</td>
            <td>
              <?php
              $currentAccount = account::find($user->idAccount);

              ?>
              {{ $currentAccount->name }} 
            </td>
            <td>
              @if($user->pOperator == '4500')
            Admin user
            @endif
            @if($user->pOperator == '1')
            usuario
            @endif
            @if($user->pOperator == '2')
            operador
            @endif
            </td>
             <td>
              <?php
              $userc = user::find($user->idPersonCreator);

              ?>
              @if($userc != null)
              {{ $userc->firstName }} {{ $userc->lastName }}
              @endif
            </td>

            <td>
              <a href="{{ url('userOperator/activateOperator', $user->id ) }}" class="btn btn-primary">
                Activar
              </a>
            
            </td>
          
        
      @endforeach
    @else
      <tr>
        <td colspan="8"><h2>No hay operadores inactivos</h2></td>
      </tr>
    @endif
  </table>
  <input type="buttom" onclick="history.back()" name="atras" value="Atr&aacute;s" class="btn btn-primary">


<?php echo $users->render(); ?>
@stop