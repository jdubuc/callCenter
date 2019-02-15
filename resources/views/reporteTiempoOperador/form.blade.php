@extends('app')

<?php
use App\Campaign;
use App\PersonCampaign;
use App\ReporteOperador;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
        $form_data = array('route' => 'reporteTiempoOperador.store', 'method' => 'POST');
        $action    = 'Crear';     

    $currentuser= Auth::user(); 
    //$campaign = campaign::where('idPersonCreator','=',$currentuser->id);
    $reporteOperador = new reporteOperador;
    //dd($campaign);


?>

@section ('content')

<h1>{!! $action !!} reporte de tiempo del operador </h1>

{!! Form::model($reporteOperador, $form_data, array('role' => 'form', 'id'=>'reporteTiempoOperador')) !!}

  @include ('reporteDestinatario/errors', array('errors' => $errors))

  <div class="row">
    <div class="form-group col-md-4">
        <select id="campaignSelect" class="form-control campaignSelect"  name="idCampaign">
        <option selected="selected"  label="Seleciona una Campaña" value="">seleciona una Campaña</option>
        @foreach ($PersonCampaign as $PersonCampaign)
          <?php
          $idc=$PersonCampaign->idCampaign;
          $Campaign=Campaign::where('id', '=', $idc)->first();
          ?>
              <option value="{{ $Campaign->id }}">{{ $Campaign->name }}  </option>
        @endforeach
        </select>      
      </div>
       <div class="form-group col-md-4">
        <select id="tipo" class="form-control campaignSelect"  name="tipo">
        <option selected="selected"  label="seleciona una tipo de reporte, tiempo de operador" value="">seleciona un tipo de reporte, tiempo de operador</option>

              <option value="llamada">por llamada</option>
                <option value="encuesta">por Encuesta</option>
        </select>      
      </div>
      <div class="form-group col-md-4">
      </div>
    </div>
    <div class="row">
      <div class="form-group col-md-4">
        {!! Form::label('dateTimeStart', 'Comienzo de la campaña') !!}
        <div id="hello"></div>
        <span id="dateTimeStart2" class='dateTimeStart2'></span>
      </div>
      <div class="form-group col-md-4">
        {!! Form::label('dateTimeEnd', 'Final de la campaña') !!}
        <div id="hello"></div>
         <span id="dateTimeEnd2" class='dateTimeEnd2'></span>
      </div>
    </div>
    <div class="row">
      <div class="form-group col-md-4">
        {!! Form::label('dateTimeStart', 'Fecha donde inicia el Reporte') !!}
        <input type="date" name="dateTimeReporteStart" class="form-control">
      </div>
      <div class="form-group col-md-4">
        {!! Form::label('dateTimeEnd', 'Fecha donde finaliza reporte') !!}
        <input type="date" name="dateTimeReporteEnd" class="form-control">       
      </div>
    </div>
    <div class="row">
    <div class="table-responsive" >  
      <table class="table table-striped">
        <tr>
            <th>Nombre Operadores</th>
        </tr>
        <tr id="campaignOperator">
        </tr>
      </table>
    </div>
    </div>
   <input type='hidden' id='sizeG' name='sizeG'  />
   <span id="sizeG" class='sizeG'></span>
  {!! Form::button('Ver Reporte', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}    
{!! Form::close() !!}
 </div>
 <script type="text/javascript">
 var sizeG=0;
function dest() {
    $.ajax({
     url:'http://localhost/callcenter/public/campaignOperatorList',//<?php echo  url('/call/to'). "/" ?>
      type:"GET",
      data:{campaignOperator: $("#campaignSelect").val()},
      dataType: 'json',

    success:function(data){  
      //alert(data);
      var html_str ='';
       //console.log(data);
       for(var i=0; i<data.length; i++)
       {
         console.log(i);
         console.log(sizeG);
        //console.log(data);
         //console.log(data[i]);
       html_str += '<td><input type="checkbox" id="grupoOperador" name="grupoOperador[]" checked value="'+ data[i].idPg +'">'+ data[i].firstName +' '+ data[i].lastName +'</td>';
       sizeG = i+1;
       }
       //e.preventDefault();
       //html_str +='</ul>';
        //console.log(html_str);
          

       jQuery('#campaignOperator').html(html_str);

    }
  });
}
</script>

<script type="text/javascript">
  var dateCampaign = [];
  var data;
function resp() {
     $.ajax(
    { 
      url:'http://localhost/callcenter/public/campaignDate',
      type:"GET",
      data:{campaignSelect: $("#campaignSelect").val()},
      dataType: 'json',
      success: function(data)
        {
          console.log(data);
          console.log(sizeG);

          $('#dateTimeStart').val(data.fechaInicio);
          $('#dateTimeEnd').val(data.fechaFinal);
         document.getElementById('dateTimeStart2').innerHTML =data.fechaInicio;
         document.getElementById('dateTimeEnd2').innerHTML =data.fechaFinal;
        }
    });
  }

jQuery(document).ready(function(){
  $(document).on("change ",'.campaignSelect', dest);
  $(document).on("change ",'.campaignSelect', resp);
  });
$('#sizeG').submit(function () {
   console.log(i);
     document.getElementById('sizeG').value = sizeG;
     return true 
   });
</script>
@stop