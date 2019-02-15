@extends ('app')
 	
@section ('content')
 
    <div class="name">
        <header>
	        <h4>Campaign Name:</h4>
	    </header>
            <p class="person"> {{ $campaign->name }} </p>
          </div>

        <article id="datos">
	        <header>
	            <h4>Campaign Message:</h4>
	        </header>
	        <article id="horario">
	            <p>{{ $campaign->campaignMessage }}</p>	                  
	        </article>
	            <!--<br>-->
	        <header>
	            <h4>Start of the campaign: </h4>
	        </header>
	        <article id="telefono">
	            <p>{{ $campaign->dateTimeStart }}</p>
	        </article>  
	        <header>
	            <h4>End of the campaign:</h4>
	        </header>
	        <div id="informacion" >
	           {{ $campaign->dateTimeEnd }} 
	        </div>      
        </article>
    </div>
 		<div class="name">
 			<a href="#" class="btn btn-info">Agregar Pregunta</a>
 			<a href="#" class="btn btn-info">Ver Grupos de Operadores</a>
 			<a href="#" class="btn btn-info">Ver Grupos de Destinatarios</a>
 		</div>
@stop