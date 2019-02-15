@extends('app')

@section ('title') Lista de Usuarios @stop



@section ('content')
<!--<p>
    <a href="{{ route('account.create') }}" class="btn btn-primary">Crear una cuenta</a>
  </p> -->

  <h1>Lista de cuentas</h1>
  
<div class="container">
	<table style="width:100%">
	  <tr>
	    <th>Nombre de cuenta</th>
	    <th>Nombre contacto</th> 
	    <th>Email</th>
	    <th>rif</th>
	    <th>Opciones</th>
	  </tr>
    @foreach ($accounts as $account)
    
	  <tr>
	    <td>{{ $account->name }}</td>
	 
	    <td>{{ $account->contactName }}</td>
	 
	    <td>{{ $account->email }}</td>
	 
	    <td>{{ $account->rif }}</td>
	 
	    <td><p>
    <a href="{{ route('account.edit', $account->id) }}" class="btn btn-primary">Editar</a>
  </p></td>
	  </tr>
	
        
    @endforeach
    </table>
</div>


{{ $accounts->render() }}
@stop