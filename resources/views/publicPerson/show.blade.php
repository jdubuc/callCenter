@extends ('app')
 	
@section ('content')
 
<div class=""></div>

  <div class="bg2"></div>

    <div class="profile">

      <div class="effect"></div>

          <div class="social">
            <a href="#" class="twitter"></a>
            <!-- <a href="#" class="in"></a>-->
            <!--<a href="#" class="dr"></a>-->
          </div>

          <div class="name">
          <header>
	            <h4>Name:</h4>
	        </header>
            <p class="person"> {{ $publicPerson->firstName }} {{ $publicPerson->lastName }}</p>
	            
           
          </div>

        <article id="datos">
	        <header>
	            <h4>Email:</h4>
	        </header>
	        <article id="horario">
	            <p>{{ $publicPerson->email }}</p>	                  
	        </article>
	            <!--<br>-->
	        <header>
	            <h4>PhoneNumber: </h4>
	        </header>
	        <article id="telefono">
	            <p>{{ $publicPerson->phoneNumber }}</p>
	        </article>  
	        @if ($publicPerson->twitter!=='')
    			<header>
	            	<h4>twitter</h4>
		        </header>
		        <div class="social">
            
            <a href="https://www.twitter.com/{{ $publicPerson->twitter }}" class="twitter">{{ $publicPerson->twitter }}  </a>
            <!--<a href="#" class="dr"></a>-->
          </div>
			@endif
	        
	        
        </article>
    </div>
 		
@stop