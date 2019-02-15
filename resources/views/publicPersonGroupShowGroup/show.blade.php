@extends ('app')
 	
@section ('content')
<?php
use App\User;
use App\PersonCampaign;
use App\Campaign;
use App\Question;
use App\PublicPerson;
	Session::flash('backUrl', Request::fullUrl());
	$url = Session::get('backUrl');

?>
<h1>Grupo {{ $publicPersonGroup->name }} </h1>

   @if($publicPersonPublicPersonGroup!='null')
   <a href="{{ url('/publicPersonGroupToCsv', $publicPersonGroup->id) }}" class="btn btn-primary">Exportar a CSV</a>
   <a href="{!! route('publicPerson.create') !!}" title="Se asignara a este grupo" class="btn btn-primary">crear Destinatario</a>
   <a href="{!! route('personPersonGroup.create') !!}" class="btn btn-primary">Agregar Destinatario desde un archivo</a>
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
	   @foreach ($publicPersonPublicPersonGroup as $publicPersonPublicPersonGroup)
			<?php
              $PublicPerson = PublicPerson::where('id', '=', $publicPersonPublicPersonGroup->idPublicPerson)->first();
            ?>
					<tr>
						<td>{{$PublicPerson->firstName}}</td>	
						<td>{{$PublicPerson->lastName}}</td>
						<td>{{$PublicPerson->phoneNumber}}</td>
						<td>{{$PublicPerson->cellPhone}}</td>
						<td>{{$PublicPerson->email}}</td>
						<td>{{$PublicPerson->cedula}}</td>
						<td>{{$PublicPerson->twitter}}</td> 
						<td><a href="{!! route('publicPerson.edit',$PublicPerson->id) !!}" class="btn btn-primary">Editar</a></td>   
						<td>  
					 		{!!Form::model($PublicPerson, array('route' => array('publicPerson.destroy', $PublicPerson->id), 'method' => 'DELETE', 'role' => 'form','style'=>'width: 108px; position:relative;'))!!}
								<a>
									{!! Form::submit('sacar del grupo', array('class' => 'btn btn-warning ')) !!}
								</a>
							{!! Form::close() !!}
						</td>
					</tr>
		@endforeach 
			</table>
		  {!! Form::button('Cancelar', array('type' => 'button', 'class' => 'btn btn-danger', 'onclick' => 'history.back()')) !!}
		</div>
	@else
		<tr>
					<th><strong>Grupo vacio</strong></th>
		</tr>
	@endif
   
@stop