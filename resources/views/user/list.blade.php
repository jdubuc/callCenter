@extends('app')

<?php
use App\User;

use App\Account;
//$userjesus = user::find(3);
//dd( Hash::check('12345678', $userjesus->password) );
//dd ( bcrypt('12345678') == $userjesus->password)
?>
@section ('title') Lista de Usuarios @stop


@section ('content')

  <h1>Lista de usuarios</h1>
  
  <table class="table table-striped"style="width: 100%">
    <tr>
        <th>Email</th>
        <th>nombre</th>
        <th>apellido</th>
        <th>telefono</th>
        <th>Cuenta</th>
         <!-- <th>contraseña</th> -->
        <th>tipo de cuenta</th>
        <th>Creador por</th>
        <th>Opciones</th>



    </tr>
    @foreach ($users as $user)
      
      <tr>
          <td>{{ $user->email }}</td>
          <td>{{ $user->firstName }}</td>
          <td>{{ $user->lastName }}</td>
          <td>{{ $user->phoneNumber }}</td>
          <td>
          @if($user->pOperator != '9000')
            <?php
            $currentAccount = account::find($user->idAccount);

            ?>
            {{ $currentAccount->name }} 
          @endif
          </td>
          <!-- <td> {{ bcrypt('hello word') }} 

          </td> -->
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
            <a href="{{ route('user.edit', $user->id) }}" class="btn btn-primary">
              Editar
            </a>

            <a href="{{ route('user.destroy', $user->id) }}" class="btn btn-danger">Eliminar</a>
          
          </td>
      </tr>
       <!-- Eliminar {!! Form::open(array('route' => array('user.destroy', '$userc->id'), 'method' => 'DELETE', 'role' => 'form', 'id' => 'form-delete')) !!} -->
{!! Form::close() !!}
    @endforeach
  </table>

  <br />
  <a class="btn btn-primary" href="{{ url('/home') }}">Atr&aacute;s</a>

<?php echo $users->render(); ?>
@stop