@extends ('app')
 	
@section ('content')
 <?php
use App\User;
use App\PersonCampaign;
use App\Campaign;
use App\Question;
	Session::flash('backUrl', Request::fullUrl());
	$url = Session::get('backUrl');
       
	$questionlist=question::where('idCampaign', '=', $campaign->id)->get();
?>
    <div class="name">
    <div class="">
 			
	    	<a href="{!! route('personGroupCampaign.show', $campaign->id) !!}" class="btn btn-info">Ver Grupos de Operadores</a>
 			<a href="{!! route('publicPersonGroupCampaign.show', $campaign->id) !!}" class="btn btn-info">Ver Grupos de Destinatarios</a>
 		</div>
        <header>
	        <h4>Nombre de la campaña:</h4>
	    </header>
            <p class="person"> {{ $campaign->name }} </p>
        
        <header>
          <h4>Asunto:</h4>
	    </header>
            <p class="person"> {{ $campaign->emailSubject }} </p>
          </div>
		

        <article id="datos">
	        <header>
	            <h4>Email:</h4>
	        </header>
	        <article id="horario">
	            <p>{!! $campaign->campaignMessage !!}</p>
	        </article>
	            <!--<br>-->
	        <header>
	            <h4>Inicio de la campaña: </h4>
	        </header>
	        <article id="telefono">
	            <p>{{ $campaign->dateTimeStart }}</p>
	        </article>  
	        <header>
	            <h4>Final de la campaña:</h4>
	        </header>
	        <div id="informacion" >
	           {{ $campaign->dateTimeEnd }} 
	        </div>      
        </article>
        <br />
  <a class="btn btn-primary" href="{{ url('/home') }}">Atr&aacute;s</a>
    </div>	
	
@stop