@extends('app')
<?php
use App\MessageSend;
use App\Campaign;
use App\PublicPerson;
use App\Question;
use App\validation;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
        $form_data = array('route' => 'call.store', 'method' => 'POST','id'=>'call','name'=>'call');
        $action    = 'Preguntas de'; 
        $id = Auth::user()->id;
  $user= Auth::user(); 
  Session::flash('backUrl', Request::fullUrl());
  $url = Session::get('backUrl');
  $validation = new validation;   
  $client = new Client(); //GuzzleHttp\Client llamada al webservice
  $response = $client->get($validation->urlGetCallInfo(),['query' => ['idMessageSend' => $messageSend->id,'idUser' => $user->id,'email' => $user->email,'passw' => $user->password]])->getBody();
  $obj = json_decode($response); /* mensaje campaña, nombre apellido destinatario , numero de tlf, preguntas */
    //$campaign=Campaign::find($obj->campaign);
    $campaign = new campaign;
    //$campaignActual = new campaign::find($messageSend->idCampaign);
    $publicPersonFirstName=$obj->publicPersonFirstName;
    $publicPersonLastName=$obj->publicPersonLastName;
    $publicPersonCellPhone=$obj->publicPersonCellPhone;
    $publicPersonCedula=$obj->publicPersonCedula;
    $campaignMessage=$obj->campaignMessage;
    $question=$obj->questions;
    $result = $question;
?>
@section ('content')
<h1>{!! $action !!} Llamada</h1>
<!-- <h3>Tiempo de Llamada: <p id="demo"></p> </h3>-->
<h1>Datos Destinatario de la llamada</h1>
{!! Form::model($campaign, $form_data, array('role' => 'form', 'id'=>'call')) !!}
  @include ('call/errors', array('errors' => $errors))
  <div  id="results" class="row">
   <div class="form-group col-md-6">
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Telefono</th>
            <th>Cedula</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{ $publicPersonFirstName }}</td>
            <td>{{ $publicPersonLastName }}</td>
            <td>{{ $publicPersonCellPhone }}</td>
            <td>{{ $publicPersonCedula }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
    <div class="form-group col-md-10">
    {!! Form::label('anwsercall', 'Mensaje de campaña') !!} <br>
    {{$campaignMessage}}
          {!! Form::label('anwsercall', '¿contesto la Llamada?') !!} <br>
        <input id="anwsercall" class="question withOutAnswer question-anwsercall questionbasic enableButton" name="question-1" type="radio" value="contesto" onclick="dial()"/>
          {!! Form::label('anwsercall', 'contesto') !!}
        <input id="anwsercall" class="enableButton" name="question-1" type="radio" value="numero invalido" onclick="dial()"/>
          {!! Form::label('anwsercall', 'Número invalido') !!}
        <input id="anwsercall" class="enableButton" name="question-1" type="radio" value="no contesto" onclick="dial()"/>
          {!! Form::label('anwsercall', 'no contesto') !!}

    </div>
  </div>
   <input type='hidden' id='tiempoPregunta' name='tiempoPregunta' value='' />
   <input type='hidden' id='tiempoLlamada' name='tiempoLlamada' value='' />
    <input type='hidden' id='dialTime' name='dialTime' value='' />
  {!! Form::hidden('ms', $messageSend->id, array('readonly')) !!}
  {!! Form::button('aceptar y siguiente', array('type' => 'submit', 'class' => 'btn btn-primary', 'name'=> 'boton', 'value'=> 'siguiente', 'id'=>'botonays', 'disabled')) !!}  
  {!! Form::button('Colgar', array('type' => 'submit', 'class' => 'btn btn-primary', 'name'=> 'boton', 'value'=> 'terminar', 'id'=>'botoncolgar', 'disabled')) !!}      
  {!! Form::close() !!}
<script type="text/javascript" src="https://code.jquery.com/jquery-3.1.1.min.js"></script>

<script>
var myVar = setInterval(function(){ myTimer() }, 1000);
var timeQuestion = [];
function myTimer() {
    var d = new Date();
    sec++;
    sec2++;
    var t = d.toLocaleTimeString();
    //document.getElementById("demo").innerHTML = sec + " Segundos";
}
function enableButton() {
 $("#botoncolgar").removeAttr("disabled");
         $("#botonays").removeAttr("disabled");
}   
function dial() {
 dialTime=sec;
}         
function myQuestionTime() {
  if(timeQuestion[counter]==null)
  {
    timeQuestion[counter]=sec2;
    sec2=0;
  }
  else
  {
    timeQuestion[counter]=timeQuestion[counter]+sec2;
    sec2=0;
  }
  console.log('tiempo ='+sec);
  console.log('tiempo de pregunta ='+sec2);
  console.log('timeQuestion ='+timeQuestion);
  }

$('#call').submit(function () {
     document.getElementById('tiempoPregunta').value = timeQuestion;
     return true 
   });
$('#call').submit(function () {
     document.getElementById('tiempoLlamada').value = sec;
     return true 
   });
$('#call').submit(function () {
     document.getElementById('dialTime').value = dialTime;
     return true 
   });
</script>
<script type="text/javascript">
   var html_str ='<ul>';
   var html_push ='<ul>';
   var qanswer;
   var sec=0;
   var sec2=0;
   var dialTime=0;
   var question = [];
   var questionMap = [];
   var conditionMap = [];
   var questionDestinataryMap = [];
   var counter = 0;
   var counterLog = 0;
   var iter = 0;
   var iter2 = 0;
   var data  = <?php  echo json_encode($result); ?>;
   jQuery(document).ready(function(){

dataQuestion(data);
function dataQuestion(data){  
      console.log(data);
      var str_options;
       for(var i=0; i<data.length; i++)
       {
         if(data[i].idQuestionType !=10)
         {
        questionMap[ data[i].id ]=i;
       }
        if(data[i].idQuestionType =='1')
         {
          html_push= '<div hidden class="form-group col-md-10 question'+i+'" id="question'+i+'"> <label for="question"  >'+data[i].data+'</label>';
          html_push +=  '<input type="text" class="question withOutAnswer question-int " name="question'+i+'" >' +' </div>';
         }
        if(data[i].idQuestionType =='4')
         {
          html_push= ' <div hidden class="form-group col-md-10 question'+i+'" id="question'+i+'"> <label for="question" >'+data[i].data+'</label>';
          html_push +=  '<textarea " class="question withOutAnswer question-text" name="question'+i+'" rows="6" cols="15">'+'</textarea>'+'</div>';
         }
        if(data[i].idQuestionType =='5')
         {
          html_push= '<div hidden class="form-group col-md-10 question'+i+'" id="question'+i+'"> <label for="question" >'+data[i].data+'</label>';
          html_push +=  '<input type="text"  class="question withOutAnswer question-cedula"  name="question'+i+'" min="1" max="50">' +'cedula' +'</div>';
         }
        if(data[i].idQuestionType =='6')
         {
          html_push= '<div hidden class="form-group col-md-10 question'+i+'" id="question'+i+'"> <label for="question" >'+data[i].data+'</label>';
          html_push +=  '<input type="date" class="question withOutAnswer question-date"  name="question'+i+'">' +'</div>';
         }
        if(data[i].idQuestionType =='7')
         {
          html_push= '<div hidden class="form-group col-md-10 question'+i+'" id="question'+i+'"> <label for="question" >'+data[i].data+'</label>';
          html_push +=  +'<input type="time" class="question withOutAnswer question-date"  name="question'+i+'" >'+ 'hora' +'</div>';
         }
        if(data[i].idQuestionType =='8')
         { 
            html_push= '<div hidden class="form-group col-md-10 question'+i+'" id="question'+i+'"> <label for="question">'+data[i].data+'</label>';
            html_push+= '<br>'+'<input type="radio" name="question'+i+'" class="question withOutAnswer question-yesorno" value="1" >' + 'Si  '  + '<input type="radio" name="question'+i+'" class="question withOutAnswer question-yesorno" value="2" >' + 'No' + 
            '<input type="radio" name="question'+i+'" class="question withOutAnswer question-yesorno" value="0">' + 'No Contesta' + '</div>';
         }
        if(data[i].idQuestionType =='9')
         {
          html_push= '<div hidden class="form-group col-md-10 question'+i+'" id="question'+i+'"> <label for="question" class="question question-option">' + data[i].data  +'</label>';
          str_options = data[i].option;
          var res = str_options.split(/\r\n|\n|\r/);
            html_push += '<br>'+'<select cols="30" class="question withOutAnswer question-option" name="question'+i+'">  <option selected="selected"  label="selecciona una opcion" value="">seleciona una opción</option>'  ;
            html_push += '<option  value="0" >no respondio</option>';
              for(var a=1; a<res.length; a++)
              {
                html_push += '<option  value="'+a+'">'+res[a-1]+'</option>';
              }
            html_push += '</select>'+'</div>';
         }
        if(data[i].idQuestionType =='10')
         {
          var str_opt
          conditionMap[data[i].order]=data[i].id;
          str_opt = data[i].QuestionDestinatary;
          questionDestinataryMap[data[i].order-1] = str_opt.split(",");
         }
         if(data[i].idQuestionType !=10)
         {
         question.push(html_push);
         jQuery('#results').append(html_push ); 
         }  
       }
       html_push +='</ul>'; 
    }
  });
</script>
<script>
      //console.log(question);
     //console.log(conditionMap);
    //console.log(questionMap);
   //console.log(questionDestinataryMap);
  //console.log(data);
 //console.log(questionDestinataryMap[0]);
//console.log('tiempo ='+sec);
        //console.log('tiempo de pregunta ='+sec2);
function questionChange(){
      var borrar = false;
    counterLog=counter;
      for(i=iter;i<=counter;i++)
      {
        // console.log('entre al for valor de i='+i);
       // console.log('valor de counter='+counter);
      // console.log('valor de borrar='+borrar);
        if(borrar==true)
        {
             //borrar y vaciar();
            //$(".question"+(i)).val("");
           //$(".question"+(i)).prop("checked",false);
          //$(".question"+(i)).checked = false;
         //console.log('dentro del if borrar true--i='+i);
        //console.log("input[name=question"+(i)+"]");
       //console.log('tiempo ='+sec);
          $(".question"+(i)).attr("hidden","true");  
          $("input[name=question"+(i)+"]").prop("checked",false);
          $("[name=question"+(i)+"]").val("");
          $("input[name=question"+(i)+"]").text("");
          $("input[name=question"+(i)+"]").html("");
          $("input[name=question"+(i)+"]").children().removeAttr('selected');
        }
        else
        {
          //if($(".question"+(counter))
            if(questionDestinataryMap[i]!=undefined && $(".question"+(iter)).is(":visible"))
            {
              //console.log('if undefined 1 valor de i='+i);
              if(questionDestinataryMap[iter+1]!=undefined)
              {
                //console.log('borrar pasa a true i='+i);
                borrar=true;
                counterLog=i-1;
              }
            }
        }
      }counter=counterLog;
      return borrar;
    }
function onResponse() {
         myQuestionTime();
        
        if($(this).hasClass('questionbasic'))
        {
          $(".question"+(counter)).removeAttr( "hidden" );
          formDisableEnter();
          return;
        }
        var questionIdDestinatary;
        var questionDestinatary;
         iter=parseInt($(this).attr("name").slice(8));
         iter2=iter+1;
         formDisableEnter();
         qanswer=$(this).val();
        var qchange=questionChange();
        //console.log('click en question='+iter);
        //console.log('siguiente ='+iter2);
        //console.log('contador ='+counter);
        //console.log('respuesta ='+qanswer);
        //if(iter < counter)
         if(iter < counter && qchange==false)
        {
        //console.log('entro click en ='+iter);
          return;
        }
        //console.log('antes del if'+questionDestinataryMap[counter+1]);
         if(questionDestinataryMap[counter+1]==undefined ) //no es codicion
         {
        //console.log('en el if undefined'+questionDestinataryMap[counter+1]);
        //console.log('contador ='+counter);
          counter = (counter + 1); 
          $(".question"+(counter)).removeAttr( "hidden" );
         }
         else
         {
          //console.log('en el else iter2='+iter2);
          //console.log('counter ='+counter);
          console.log('QDM ='+questionDestinataryMap[counter+1][qanswer]);
          console.log('questionMap ='+questionMap[questionIdDestinatary]);
          questionIdDestinatary=questionDestinataryMap[counter+1][qanswer];
          questionDestinatary=questionMap[questionIdDestinatary];
          if(questionDestinatary==undefined)
            {
              //console.log('questionDestinatary= undefined');
              counter = (counter + 2); 
            $(".question"+(counter)).removeAttr( "hidden" );
            }
            //console.log('QM ='+questionMap[questionIdDestinatary]);
          else{
          $(".question"+(questionDestinatary)).removeAttr( "hidden" );
          counter = questionDestinatary; 
              }
         }
    }
function resp() {
     // increment your counter
        // the modulus (%) operator resets the counter to 0
        // when it reaches the length of the array
        if(counter < questionMap.length)
        {
          counter = (counter + 1); 
        }
    }
    $(document).on("keyup paste",'input[type=text].question.withOutAnswer,textarea.question.withOutAnswer', onResponse);
    $(document).on("change ",'.question.withOutAnswer', onResponse);
     $(document).on("change ",'.enableButton', enableButton);
     
    //$(document).on("change ",'.question.withOutAnswer',  myQuestionTime(counter)); 
    //myQuestionTime(".question"+(counter));
</script>
@stop