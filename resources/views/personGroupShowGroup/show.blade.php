@extends ('app')
 	
@section ('content')
<?php
use App\User;
use App\PersonCampaign;
use App\personGroup;
use App\personPersonGroup;
use App\personGroupCampaign;
use App\Campaign;
use App\Question;
use App\PublicPerson;
use App\validation;
	Session::flash('backUrl', Request::fullUrl());
	$url = Session::get('backUrl');
	$validation = new validation;
          $idGroup=$validation->urlData(); 
?>
<h1>Grupo "{{ $personGroup->name }}"</h1>
<a href="{{ url('/user/createOperator', $idGroup) }}" class="btn btn-primary">Agregar Operador nuevo</a>
<a href="{!! route('userOperator.show', $idGroup) !!}"class="btn btn-primary">Asignar operador a este grupo</a>
<a href="{!! route('personPersonGroup.create') !!}" class="btn btn-primary">Agregar Operador desde un archivo</a>
   @if($personPersonGroup!='null')
   		<div style="margin:50px auto;">
			<table class="table table-striped"> 
				<tr>
					<th><strong>Nombre</strong></th>
					<th><strong>apellido</strong></th>
					<th><strong>telefono</strong></th>
					<th><strong>celular</strong></th>
					<th><strong>email</strong></th>
					<th><strong>cedula</strong></th>
					<th><strong>twitter</strong></th>
					<th><strong>Opciones</strong></th>
				</tr>
	   @foreach ($personPersonGroup as $personPersonGroup)
			<?php
              $person = user::where('id', '=', $personPersonGroup->idPerson)->first();
            ?>
            @if($person!=null)
					<tr>
						<td>{{$person->firstName}}</td>	
						<td>{{$person->lastName}}</td>
						<td>{{$person->phoneNumber}}</td>
						<td>{{$person->cellPhone}}</td>
						<td>{{$person->email}}</td>
						<td>{{$person->cedula}}</td>
						<td>{{$person->twitter}}</td>  
						<td><a href="{!! route('personGroupShowGroup.edit', $person->id) !!}" class="btn btn-primary">Editar</a></td> 
						<td>  
					 		{!!Form::model($person, array('route' => array('personPersonGroup.destroy', $person->id), 'method' => 'DELETE', 'role' => 'form','style'=>'width: 108px; position:relative;'))!!}
								<a>
									{!! Form::submit('sacar del grupo', array('class' => 'btn btn-warning ')) !!}
								</a>
							{!! Form::close() !!}
						</td>
						<td>  
					 		{!!Form::model($person, array('route' => array('userOperator.destroy', $person->id), 'method' => 'DELETE', 'role' => 'form','style'=>'width: 108px; position:relative;'))!!}
								<a>
									{!! Form::submit('Desactivar este operador', array('class' => 'btn btn-danger ')) !!}
								</a>
							{!! Form::close() !!}
						</td>
					</tr>
			@endif
		@endforeach 
			</table>
		</div>
	@else
		<tr>
			<th><strong>Grupo vacio</strong></th>
		</tr>
	@endif
     <input type="buttom" onclick="history.back()" name="atras" value="Atr&aacute;s" class="btn btn-primary">

@stop