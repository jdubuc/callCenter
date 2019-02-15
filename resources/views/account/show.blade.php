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
            <p class="person"> {{ $account->name }} </p>
	            
           
          </div>

        <article id="datos">
	        <header>
	            <h4>Email:</h4>
	        </header>
	        <article id="horario">
	            <p>{{ $account->email }}</p>	                  
	        </article>
	            <!--<br>-->
	       
            <!--<a href="#" class="dr"></a>-->
          </div>
			
	        
	        
        </article>
    </div>
 		
@stop