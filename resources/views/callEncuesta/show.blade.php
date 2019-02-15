@extends ('app')
 	
@section ('content')
 <?php
use App\User;
use App\PersonCampaign;
use App\Campaign;
use App\Question;
	Session::flash('backUrl', Request::fullUrl());
	$url = Session::get('backUrl');
       
	$questionlist=question::where('idCampaign', '=', $campaign->id)->orderBy('order')->get();
?>
    <div class="name">
    <div class="">
    @if($campaign->type=='CallCenter')
	    	<a href="{!! route('question.create', $campaign->id) !!}" class="btn btn-info">Agregar Pregunta</a>
	    	<a href="{!! route('personGroupCampaign.show', $campaign->id) !!}" class="btn btn-info">Ver Grupos de Operadores</a>
	    @endif
 			
 			
 			<a href="{!! route('publicPersonGroupCampaign.show', $campaign->id) !!}" class="btn btn-info">Ver Grupos de Destinatarios</a>
 		</div>
        <header>
	        <h4>Nombre de la Campaña:</h4>
	    </header>
            <p class="person"> {{ $campaign->name }} </p>
          </div>
		
        <article id="datos">
	        <header>
	            <h4>Mensaje de la campaña:</h4>
	        </header>
	        <article id="horario">
	            <p>{{ $campaign->campaignMessage }}</p>	                  
	        </article>
	            <!--<br>-->
	        <header>
	            <h4>Inicio de la campaña: </h4>
	        </header>
	        <article id="telefono">
	            <p>{{ $campaign->dateTimeStart }}</p>
	        </article>  
	        <header>
	            <h4>Fin de la campaña:</h4>
	        </header>
	        <div id="informacion" >
	           {{ $campaign->dateTimeEnd }} 
	        </div>      
        </article>
    	
     @if($campaign->type=='CallCenter')
			<header>
	            <h4>Preguntas de campaña:</h4>
	        </header>
<table>
  <tr>
    <th>Preguntas:</th>
    <th>Orden  </th>
	    @if($campaign->active=='true')
	    	<th></th>
	    @else
	    	<th>Opciones</th>
	    @endif
    
  </tr>
	    
			    @if(Empty($questionlist)=='1')
					<th> No hay preguntas</th>
				@else
					@foreach ($questionlist as $questionlist)
						<?php
						//$idc=$questionlist->idCampaign;
							//$Campaign=Campaign::find();
						?>
						<tr>
						@if($campaign->active=='true')
							<td> <a href="#" style="text-decoration:none;">{{ $questionlist->data }} | {{ $questionlist->order }} </a></td>				

						@else
							
					 		<td> 
						 		<a href="{{ url('/question/up', $questionlist->id) }}" style="text-decoration:none;">
							 		<img src="{{asset('/images/up.png')}}" height="16" width="16">
							 	</a>
							 	<a href="{{ url('/question/down', $questionlist->id) }}" style="text-decoration:none;">
							 		<img src="{{asset('/images/down.png')}} " height="16" width="16">
							 		@if($questionlist->idQuestionType==10)
							 			</a>Condición 
							 		@else
						 				</a>{{ $questionlist->data }} 
						 			@endif
					 		</td>

					 		<td> {{ $questionlist->order }} </td>
					 		<td><a href="{{ route('question.edit', $questionlist->id) }}" class="btn btn-primary">Editar</a></td>
					 		<td>	
						 		@if($questionlist->idQuestionType==8)
									<a href="{{ url('/question/createCondition', $questionlist->id) }}" class="btn btn-primary">Crear Condición</a>
								@endif		
							</td>
					 		<td>  
					 			{!!Form::model($questionlist, array('route' => array('question.destroy', $questionlist->id), 'method' => 'DELETE', 'role' => 'form','style'=>'width: 108px; position:relative;'))!!}
									<a >
										{!! Form::submit('Eliminar', array('class' => 'btn btn-danger ')) !!}
									</a>
								{!! Form::close() !!}
							</td>
					 	@endif
					@endforeach
				@endif
 		 	</tr>
 		 	</table>
 		 	 @endif
@stop