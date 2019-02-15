@extends ('app')	
@section ('content')
 <?php
use App\User;
use App\PersonCampaign;
use App\Campaign;
use App\Question;
use App\QuestionType;
	Session::flash('backUrl', Request::fullUrl());
	$url = Session::get('backUrl'); 
	$questionlist=question::where('idCampaign', '=', $campaign->id)->orderBy('order')->get();
	$mSend=DB::table('MessageSend') ->where('idCampaign', '=', $campaign->id)->get();
	$qid=0;			
	$idConditionList=array();	 			
	$questionListCond=question::where('idCampaign', '=', $campaign->id)->where('idQuestionType', '=', 10)->get();
	foreach ($questionListCond as $key ) {
		$idConditionList= explode(",", $key->QuestionDestinatary);
	}
	//dd($idConditionList);
?>
@if($campaign->type=='Sms')
  <link rel="stylesheet" href="{{asset('i.css')}}">
  <script src="{{asset('j.js')}}"></script>
@endif 
    <div class="name">
    <div class="">
    @include ('campaign/errors', array('errors' => $errors))
    @if($campaign->type=='CallCenter'||$campaign->type=='Encuesta')
	    	@if($campaign->active!='active')
	    		
								@if($mSend!=null)
					 				<span class="helper" data-toggle="tooltip" title="No se puede editar ya se realizó alguna llamada"> <a href="{!! route('question.create', $campaign->id) !!}" class="btn btn-info" disabled title="No se puede editar ya que se realizaron llamadas">Agregar Pregunta</a></span>
	    							<span class="helper" data-toggle="tooltip" title="No se puede editar ya se realizó alguna llamada"> <a href="{!! route('campaign.edit', $campaign->id) !!}" class="btn btn-info" disabled title="">Editar Campaña</a></span></li>
					 			@else
					 				<span class="helper" data-toggle="tooltip" > <a href="{!! route('question.create', $campaign->id) !!}" class="btn btn-info" >Agregar Pregunta</a></span>
	    							<span class="helper" data-toggle="tooltip" > <a href="{!! route('campaign.edit', $campaign->id) !!}" class="btn btn-info" >Editar Campaña</a></span></li>
					 			@endif 
	    	@else
	    		<span class="helper" data-toggle="tooltip" title="No se puede editar ya que esta activa la campaña"> <a href="{!! route('question.create', $campaign->id) !!}" class="btn btn-info" disabled title="No se puede editar ya que se realizaron llamadas">Agregar Pregunta</a></span>
	    		<span class="helper" data-toggle="tooltip" title="No se puede editar ya que esta activa la campaña"> <a href="{!! route('campaign.edit', $campaign->id) !!}" class="btn btn-info" disabled title="">Editar Campaña</a></span></li>
	    	@endif
	    	<a href="{!! route('personGroupCampaign.show', $campaign->id) !!}" class="btn btn-info">Ver Grupos de Operadores</a>
	    @endif
	    @if($campaign->type!='Encuesta')
 			<a href="{!! route('publicPersonGroupCampaign.show', $campaign->id) !!}" class="btn btn-info">Ver Grupos de Destinatarios</a>
 		@endif
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
     @if($campaign->type=='CallCenter'||$campaign->type=='Encuesta')
			<header>
	            <h4>Preguntas de campaña:</h4>
	        </header>
<table class="table table-striped">
  <tr>
  <th>Preguntas  </th>
  	<th>Ordenar  </th>
  	<th>Orden  </th>
  	<th>Tipo</th>
    <th>Preguntas:</th>
    <th>Opciones:</th>
	<th></th> 
  </tr>
			    @if(Empty($questionlist)=='1')
					<th> No hay preguntas</th>
				@else
					@foreach ($questionlist as $ql)
						<tr>
						@if($campaign->active=='true')
							<td> <a disabled href="#" 
								<?php
									if($ql->idQuestionType==10)
									{
										echo 'style="text-decoration:none; color:red;"';
									}
								?>

							>{{ $ql->data }} | {{ $ql->order }} </a>
								@if($ql->idQuestionType==10)
									<?php
									$questionC=Question::where('id', '=', $ql->idQuestionCondition)->first();
									$idQuestionSplit = explode(",", $ql->QuestionDestinatary);
                      				$idQuestionSplit2 = count($idQuestionSplit);
                      				$c=0;
									?>
									@if($questionC->idQuestionType==8)
										<?php
											$questionSi=Question::where('id', '=', $idQuestionSplit[1])->first();
										?>
										<p style="margin:0; margin-left: 50px;">si->{{ $questionSi->order }}</p>
										<?php
											$questionNo=Question::where('id', '=', $idQuestionSplit[2])->first();
										?>
										<p style="margin:0; margin-left: 50px;">no->{{ $questionNo->order }}</p>
									@elseif($questionC->idQuestionType==9)
										<?php
											$idQuestionOpSplit = explode("\n", $questionC->option);
										?>
										@foreach ($idQuestionSplit as $split)
											@if( $split!=0)
											<?php
												$questionOp=Question::where('id', '=', $split)->first();
												//$c++;
											?>
												@if($idQuestionOpSplit[$c]!=null)
								                	<p style="margin:0; margin-left: 50px;">{{ $idQuestionOpSplit[$c] }}->{{ $questionOp->order }}</p>
								                @endif
								                <?php
												$c++;
												?>
							                @endif
							            @endforeach
									@endif
									
								
								@endif
							</td>				
							<td> 
					 		<?php
								$questl=Question::where('id', '=', $qid)->first();
							?>
						 	@if($ql->idQuestionType==10)
						 		@if($qid==$ql->idQuestionCondition)
								<a href="" disabled style="text-decoration:none; border:none;">
								 		<img src="" height="16" width="16">
								</a>
								@else
								<a href="" style="text-decoration:none;" disabled >
							 		<img src="{{asset('/images/up.png')}}" height="16" width="16">
								</a>
								@endif
							@elseif($questl!=null && $questl->idQuestionType==10)
								<?php
								$questionDestinatary=explode( ',', $questl->QuestionDestinatary );
								if (in_array($ql->id, $questionDestinatary))
								{
								?>
									<a href="" disabled style="text-decoration:none; border:none;">
									 		<img src="" height="16" width="16">
									</a>
								<?php
								}
								else
								{
								?>
									<a href="" style="text-decoration:none;" disabled >
								 		<img src="{{asset('/images/up.png')}}" height="16" width="16">
									</a>
								<?php
								}	
								?>
							@else
							<a disabled href="#" style="text-decoration:none;">
							 		<img src="{{asset('/images/up.png')}}" height="16" width="16">
							</a>
							@endif
						 		
							 	<a disabled href="#" style="text-decoration:none;">
							 		<img src="{{asset('/images/down.png')}} " height="16" width="16">
							</td> 		
							<td> {{ $ql->order }} </td>
							<td> <?php
									$questionType=QuestionType::where('id', '=', $ql->idQuestionType)->first();
									?>{{ $questionType->name }} </td>
							<td> 
							 		@if($ql->idQuestionType==10)
							 			<?php
								 			$questionC=Question::where('id', '=', $ql->idQuestionCondition)->first();
								 			//dd($questionC);
											$questionTypeCond=QuestionType::where('id', '=', $questionC->idQuestionType)->first();
											$questionDestinatary=explode( ',', $ql->QuestionDestinatary );
											//dd($questionDestinatary);
										?>    
							 			</a><a disabled class="show-option" title="condición a la pregunta {{ $ql->data }}, en la posición {{ $questionC->order }}, de tipo {{ $questionTypeCond->name }}">Condición {{ $ql->data }} </a>
							 		@elseif($ql->idQuestionType==9)
							 	
							 			</a>{{ $ql->data }} <a disabled href="#"><img class="show-option" title="{{ $ql->option }}" src="{{asset('/images/playicon.jpeg')}} " height="10" width="10"></a> 
							 		@else
						 				</a>{{ $ql->data }} 
						 			@endif
					 		</td>
					 		@if($ql->idQuestionType==10) 
					 		<td><a disabled href="{{ url('/question/editCondition', $ql->id) }}" class="btn btn-primary">Editar</a></td>
					 		@else
					 		<td><a disabled href="{{ route('question.edit', $ql->id) }}" class="btn btn-primary">Editar</a></td>
					 		@endif		
					 		<td>	
						 		@if($ql->idQuestionType==8 || $ql->idQuestionType==9)
									<a disabled href="{{ url('/question/createCondition', $ql->id) }}" class="btn btn-primary">Crear Condición</a>
								@endif		
							</td>
					 		<td>  <?php

					 		if (in_array($ql->id, $idConditionList))
					 		{	?>
					 				<a>
										{!! Form::submit('Eliminar', array('class' => 'btn btn-danger ','disabled')) !!}
									</a>
					 		<?php }
					 		else{
					 		?>
					 			{!!Form::model($ql, array('route' => array('question.destroy', $ql->id), 'method' => 'DELETE', 'role' => 'form','style'=>'width: 108px; position:relative;'))!!}
									<a>
										{!! Form::submit('Eliminar', array('class' => 'btn btn-danger ')) !!}
									</a>
								{!! Form::close() !!}
							</td>
							<?php }
					 		
					 		?>

						<!--si la campaña esta inactiva -->
						@else
						<td> <a disabled href="#" <?php
									if($ql->idQuestionType==10)
									{
										echo 'style="text-decoration:none; color:red;"';
									}
								?>>{{ $ql->data }} | {{ $ql->order }} </a>
								@if($ql->idQuestionType==10)
									<?php
									$questionC=Question::where('id', '=', $ql->idQuestionCondition)->first();
									$idQuestionSplit = explode(",", $ql->QuestionDestinatary);
                      				$idQuestionSplit2 = count($idQuestionSplit);
                      				$c=0;
									?>
									@if($questionC->idQuestionType==8)
										<?php
										$questionSi=Question::where('id', '=', $idQuestionSplit[1])->first();
										?>
										<p style="margin:0; margin-left: 50px;">si->{{ $questionSi->order }}</p>
										<?php
										$questionNo=Question::where('id', '=', $idQuestionSplit[2])->first();
										?>
										<p style="margin:0; margin-left: 50px;">no->{{ $questionNo->order }}</p>
									@elseif($questionC->idQuestionType==9)
										<?php
											$idQuestionOpSplit = explode("\n", $questionC->option);
										?>
										@foreach ($idQuestionSplit as $split)
											@if( $split!=0)
											<?php
												$questionOp=Question::where('id', '=', $split)->first();
												//$c++;
											?>
												@if($idQuestionOpSplit[$c]!=null)
								                	<p style="margin:0; margin-left: 50px;">{{ $idQuestionOpSplit[$c] }}->{{ $questionOp->order }}</p>
								                @endif
								                <?php
												$c++;
												?>
							                @endif
							            @endforeach
									@endif
									
								
								@endif
							</td>	
					 		<td> 
					 @if($mSend!=null)
							<?php
								$questl=Question::where('id', '=', $qid)->first();
							?>
						 	@if($ql->idQuestionType==10)
						 		@if($qid==$ql->idQuestionCondition)
								<a href="" disabled style="text-decoration:none; border:none;">
								 		<img src="" height="16" width="16">
								</a>
								@else
								<a href="" style="text-decoration:none;" disabled >
							 		<img src="{{asset('/images/up.png')}}" height="16" width="16">
								</a>
								@endif
							@elseif($questl!=null && $questl->idQuestionType==10)
								<?php
								$questionDestinatary=explode( ',', $questl->QuestionDestinatary );
								if (in_array($ql->id, $questionDestinatary))
								{
								?>
									<a href="" disabled style="text-decoration:none; border:none;">
									 		<img src="" height="16" width="16">
									</a>
								<?php
								}
								else
								{
								?>
									<a href="" style="text-decoration:none;" disabled >
								 		<img src="{{asset('/images/up.png')}}" height="16" width="16">
									</a>
								<?php
								}	
								?>
							@else

							<a href="" style="text-decoration:none;" disabled >
							 		<img src="{{asset('/images/up.png')}}" height="16" width="16">
							</a>
							@endif
						 		
							 	<a href="" style="text-decoration:none;" disabled >
							 		<img src="{{asset('/images/down.png')}} " height="16" width="16">
							</td> 		
							<td> {{ $ql->order }} </td>
							<td> <?php
									$questionType=QuestionType::where('id', '=', $ql->idQuestionType)->first();
									?>{{ $questionType->name }} </td>
							<td> 
							 		@if($ql->idQuestionType==10)
							 			<?php
								 			$questionC=Question::where('id', '=', $ql->idQuestionCondition)->first();
								 			//dd($questionC);
											$questionTypeCond=QuestionType::where('id', '=', $questionC->idQuestionType)->first();
											$questionDestinatary=explode( ',', $ql->QuestionDestinatary );
											//dd($questionDestinatary);
										?>    
							 			</a><a class="show-option" title="condición a la pregunta {{ $ql->data }}, en la posición {{ $questionC->order }}, de tipo {{ $questionTypeCond->name }}" disabled>Condición a {{ $ql->data }} </a>
							 		@elseif($ql->idQuestionType==9)
							 	
							 			</a>{{ $ql->data }} <a href="#"><img class="show-option" title="{{ $ql->option }}" src="{{asset('/images/playicon.jpeg')}} " height="10" width="10"></a> 
							 		@else
						 				</a>{{ $ql->data }} 
						 			@endif
					 		</td>
					 		@if($ql->idQuestionType==10) 
					 		<td><span class="helper" data-toggle="tooltip" title="No se puede editar ya se realizó alguna llamada"><a href="{{ url('/question/editCondition', $ql->id) }}" class="btn btn-primary"disabled >Editar</a></span></td>
					 		@else
					 		<td><span class="helper" data-toggle="tooltip" title="No se puede editar ya se realizó alguna llamada"><a href="{{ route('question.edit', $ql->id) }}" class="btn btn-primary" disabled>Editar</a></span></td>
					 		@endif		
					 		<td>	
						 		@if($ql->idQuestionType==8 || $ql->idQuestionType==9)
									<span class="helper" data-toggle="tooltip" title="No se puede editar ya se realizó alguna llamada"><a href="{{ url('/question/createCondition', $ql->id) }}" class="btn btn-primary" disabled>Crear Condición</a></span>
								@endif		
							</td>
					 		<td>  <?php
					 			if (in_array($ql->id, $idConditionList))
					 		{	?>
					 				<a>
										{!! Form::submit('Eliminar', array('class' => 'btn btn-danger ','disabled')) !!}
									</a>
					 		<?php }
					 		else{
					 		?>
					 			{!!Form::model($ql, array('route' => array('question.destroy', $ql->id), 'method' => 'DELETE', 'role' => 'form','style'=>'width: 108px; position:relative;'))!!}
									<a>
										{!! Form::submit('Eliminar', array('class' => 'btn btn-danger ')) !!}
									</a>
								{!! Form::close() !!}
							</td>
							<?php }
					 		
					 		?>
							</td>
					 	
					@else
							<?php
								$questl=Question::where('id', '=', $qid)->first();
							?>
						 	@if($ql->idQuestionType==10)
						 		@if($qid==$ql->idQuestionCondition)
								<a href="" disabled style="text-decoration:none; border:none;">
								 		<img src="" height="16" width="16">
								</a>
								@else
								<a href="" style="text-decoration:none;" disabled >
							 		<img src="{{asset('/images/up.png')}}" height="16" width="16">
								</a>
								@endif
							@elseif($questl!=null && $questl->idQuestionType==10)
								<?php
								$questionDestinatary=explode( ',', $questl->QuestionDestinatary );
								if (in_array($ql->id, $questionDestinatary))
								{
								?>
									<a href="" disabled style="text-decoration:none; border:none;">
									 		<img src="" height="16" width="16">
									</a>
								<?php
								}
								else
								{
								?>
									<a href="" style="text-decoration:none;" disabled >
								 		<img src="{{asset('/images/up.png')}}" height="16" width="16">
									</a>
								<?php
								}	
								?>
							@else
							<a href="{{ url('/question/up', $ql->id) }}" style="text-decoration:none;"  >
							 		<img src="{{asset('/images/up.png')}}" height="16" width="16">
							</a>
							@endif
						 		
							 	<a href="{{ url('/question/down', $ql->id) }}" style="text-decoration:none;"  >
							 		<img src="{{asset('/images/down.png')}} " height="16" width="16">
							</td> 		
							<td> {{ $ql->order }} </td>
							<td> <?php
									$questionType=QuestionType::where('id', '=', $ql->idQuestionType)->first();
									?>{{ $questionType->name }} </td>
							<td> 
							 		@if($ql->idQuestionType==10)
							 			<?php
								 			$questionC=Question::where('id', '=', $ql->idQuestionCondition)->first();
								 			//dd($questionC);
											$questionTypeCond=QuestionType::where('id', '=', $questionC->idQuestionType)->first();
											$questionDestinatary=explode( ',', $ql->QuestionDestinatary );
											//dd($questionDestinatary);
										?>    
							 			</a><a class="show-option" title="condición a la pregunta {{ $ql->data }}, en la posición {{ $questionC->order }}, de tipo {{ $questionTypeCond->name }}" >Condición {{ $ql->data }} </a>
							 		@elseif($ql->idQuestionType==9)
							 	
							 			</a>{{ $ql->data }} <a href="#"><img class="show-option" title="{{ $ql->option }}" src="{{asset('/images/playicon.jpeg')}} " height="10" width="10"></a> 
							 		@else
						 				</a>{{ $ql->data }} 
						 			@endif
					 		</td>
					 		@if($ql->idQuestionType==10) 
					 		<td><a href="{{ url('/question/editCondition', $ql->id) }}" class="btn btn-primary" >Editar</a></td>
					 		@else
					 		<td><a href="{{ route('question.edit', $ql->id) }}" class="btn btn-primary" >Editar</a></td>
					 		@endif		
					 		<td>	
						 		@if($ql->idQuestionType==8 || $ql->idQuestionType==9)
									<a href="{{ url('/question/createCondition', $ql->id) }}" class="btn btn-primary" >Crear Condición</a>
								@endif		
							</td>
					 		<td>  
					 			{!!Form::model($ql, array('route' => array('question.destroy', $ql->id), 'method' => 'DELETE', 'role' => 'form','style'=>'width: 108px; position:relative;'))!!}
									<a >
										<?php
											if($mSend!=null)
								 			{?>
								 				{!! Form::submit('Eliminar', array('class' => 'btn btn-danger','disabled')) !!}
								 				
								 			<?php }  
								 			else
								 			{ ?>
								 				<?php
					 			if (in_array($ql->id, $idConditionList))
					 		{	?>
					 				<a>
										{!! Form::submit('Eliminar', array('class' => 'btn btn-danger ','disabled')) !!}
									</a>
					 		<?php }
					 		else{
					 		?>
					 			{!!Form::model($ql, array('route' => array('question.destroy', $ql->id), 'method' => 'DELETE', 'role' => 'form','style'=>'width: 108px; position:relative;'))!!}
									<a>
										{!! Form::submit('Eliminar', array('class' => 'btn btn-danger ')) !!}
									</a>
								{!! Form::close() !!}
							</td>
							<?php }
					 		
					 		?>
								 			<?php }  ?>
										
									</a>
								{!! Form::close() !!}
							</td>
					 	@endif
					@endif
						<?php 
						$qid=$ql->id;
						  ?>
					@endforeach
				@endif
 		 	</tr>
 		 	</table>
 		 	 @endif



@if($campaign->type=='Sms')
	<div class="wrapper">
  		<div class="phone-containter">
		    <div id="phone" class="phone">
		      	<div class="message left">
		      		<div class="message-text">{{ $campaign->campaignMessage }}</div>
		      	</div>
		      	<div class="message right">
			    	<div class="message-text">{{ $campaign->campaignMessage }}</div>
			    	<div class="message-text">...</div>
			    </div>
			    <div class="message left">
			    	<div class="message-text">...</div>
			    </div>
		    </div>
		    
		    <div class="send-container">
		     <!-- <form id="send"> -->
			      <input type="text" id="msgInput" class="send-input" placeholder="Message" />
			      <input type="submit" class="send-btn" value="Send">
		      <!-- </form> -->
		    </div>
  		</div>
	</div>
@endif
<script>
  $( function() {
    $( ".show-option" ).tooltip({
      show: {
        effect: "slideDown",
        delay: 250
      }
    });
  } );
</script>
<script>
$('.toolTipClick').tooltip({
     
    disabled: true,
    close: function( event, ui ) { $(this).tooltip('disable'); }
});
$('.toolTipClick').on('click', function () {
    $(this).tooltip('enable').tooltip('open');
});
</script>

<script type="text/javascript">
	$('.helper').tooltip({'placement': 'bottom'})
</script>
@stop