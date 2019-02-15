@extends('app')
@section('content')
<?php
use App\User;
use App\PersonCampaign;
use App\Campaign;
use App\validation;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
if (Auth::check()==true) {
    // The user is logged in...
    $id = Auth::user()->id;
  $currentuser = User::find($id);
  $user= Auth::user();
}
else
{
  return Redirect::to('/auth/login');
}
	//$Campaign = new Campaign;
	//$PersonCampaign=PersonCampaign::where('idPerson', '=', $id)->get();
	Session::flash('backUrl', Request::fullUrl());
	$url = Session::get('backUrl');
    $validation = new validation; 
    $client = new Client(); //GuzzleHttp\Client llamada al webservice
        //$response = $client->get($validation->urlGetCampaign(),['query' => ['idUser' => $user->id,'email' => $user->email,'passw' => $user->password]])->getBody();
        //$obj = json_decode($response);
?>
<h2>Bienvenido Operador</h2>
  @include ('call/errors', array('errors' => $errors))
<!-- <p id="demo"></p> -->
<div class="container">
	<div class="row">
    <h2>Campañas de {{ $user->firstName }} {{ $user->lastName }}</h2><br>
    <h2>Nombre - Tipo - Fecha inicio - Fecha Final - Activa</h2>
      <ol class="ordered-list" id="results">
       
      </ol>
	</div>
</div>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
  /*var data  = <?php  /*echo json_encode($obj);*/ ?>;
operatorCampaign(data);
function operatorCampaign(data){  
      console.log(data);
      var html_str =' ';
       for(var i=0; i<data.length; i++)
       {
        if( data[i].type=='Encuesta')
        {
          html_str += '<li > <a href="<?php /* echo  url('/callEncuestaForm/to'). "/" */ ?>'+ data[i].id + '" style="text-decoration:none;">' + data[i].name +' | '+ data[i].type +' | '+ data[i].dateTimeStart +' | '+ data[i].dateTimeEnd +'</a>  </li>';
        }
        else
        {
        html_str += '<li > <a href="<?php /* echo  url('/call/to'). "/" */ ?>'+ data[i].id + '" style="text-decoration:none;">' + data[i].name +' | '+ data[i].type +' | '+ data[i].dateTimeStart +' | '+ data[i].dateTimeEnd +'</a>  </li>';
        }
       }
       jQuery('#results').html(html_str);
    }*/
  });
</script>
<!-- <script>
var sec=0;
var myVar = setInterval(function(){ myTimer() }, 1000);

function myTimer() {
    var d = new Date();
    
    sec++;
    var t = d.toLocaleTimeString();
    
    document.getElementById("demo").innerHTML = sec;
}
</script> -->
@endsection
