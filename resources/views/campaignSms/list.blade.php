@extends ('admin/layout')

@section ('title') Lista de Usuarios @stop

<p>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Crear un nuevo usuario</a>
  </p>


@section ('content')

  <h1>Lista de usuarios</h1>
  
  <table class="table table-striped"style="width: 100%">
    <tr>
        <th>Username</th>
        <th>Email</th>
        <th>nombre</th>
        <th>apellido</th>
        <th>telefono1</th>
        <th>telefono2</th>
        <th>informacion</th>
        <th>horario</th>
        <th>direccion</th>
        <th>Acciones</th>

    </tr>
    @foreach ($users as $user)
    <tr>
        <td>{{ $user->username }}</td>
        <td>{{ $user->email }}</td>
        <td>{{ $user->nombre }}</td>
        <td>{{ $user->apellido }}</td>
        <td>{{ $user->telefono1 }}</td>
        <td>{{ $user->telefono2 }}</td>
        <td>{{ $user->informacion }}</td>
        <td>{{ $user->horario }}</td>
        <td>{{ $user->direccion }}</td>
        <td>
          <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info">
              Ver
          </a>
          
          <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
            Editar
          </a>
          <a href="#" data-id="{{ $user->id }}" class="btn btn-danger btn-delete">
              Eliminar
          </a>
        </td>
    </tr>
    @endforeach
  </table>

{{ Form::open(array('route' => array('admin.users.destroy', 'USER_ID'), 'method' => 'DELETE', 'role' => 'form', 'id' => 'form-delete')) }}
{{ Form::close() }}

@stop