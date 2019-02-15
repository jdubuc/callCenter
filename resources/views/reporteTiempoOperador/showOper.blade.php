@extends ('app')  
@section ('content')
<?php
use App\User;
use App\PersonCampaign;
use App\Campaign;
use App\Question;
use App\QuestionType;
use App\Account;
use App\reporteTiempoOperador;
  Session::flash('backUrl', Request::fullUrl());
  $url = Session::get('backUrl'); 
  $v=$data['v'];
  $v2=$data['v2'];
  $reporteTiempo = new reporteTiempoOperador;
  $campaign = Campaign::find($data['idCampaign']);
  $account = Account::find($campaign->idAccount);
  //dd($data);
   $idq=0;
  $idq2=0;
  $idqT=0;
  $opActual=1;
  $i=0;
  $x=0;
  $i2=0;
  $it=0;
  $cantidad=count($v);
  $TLC=0;
  $TT=0;
  $TP=0;
  $TEL=0;
  $totales =array();
  $idQuestionvsplit =array();
  $questionlist=question::where('idCampaign', '=', $campaign->id)->orderBy('order')->get();
  $questionlist2=question::where('idCampaign', '=', $campaign->id)->orderBy('order')->get();
  $q = array();
  foreach ($questionlist as $ql) {
    if($ql->idQuestionType=='8' )
    { 
      for ($t=1; $t <= 2; $t++) 
      { 
        $q[$it][0]=$ql->order;
        $q[$it][1]=$t;
        $it++;
      }
      
    }
    if($ql->idQuestionType=='9')
    { 
      $idQuestionvsplit = explode("\n", $ql->option);
      $idQuestionvsplit2 = count($idQuestionvsplit);
      for ($f=1; $f <= $idQuestionvsplit2; $f++) 
      { 
        $q[$it]=array($ql->order,$f);
        $it++;
      }
    }
     
  }
?>
 <div class="container"  >
  <h2>reporte de tiempo del operador por llamada</h2>
  <p>Cuenta: {{ $account->name }} | Campaña: {{ $campaign->name }} | Activa: 
     @if($campaign->active==true)
      si
     @else
      no
     @endif
    </p>
   <p>  Fecha Incio Campaña: {{ $campaign->dateTimeStart }} | Fecha Fin Campaña: {{ $campaign->dateTimeEnd }}  </p> 
   <p> Fecha Incio Reporte: {{ $data['inicio'] }} | Fecha Fin Reporte: {{ $data['final'] }}   </p>             
<table class="table table-bordered table-striped table-hover">
    <col>
  <col>
  <colgroup span="3"></colgroup>
     <tbody>
     <tr class="info">
        <th rowspan="2" scope="rowgroup" valign="middle" style="text-align: center;  padding-top: 2.5%;">Operador</th>
        <th colspan="3" scope="rowgroup" valign="middle" style="text-align: center;  padding-top: 2.5%;">Tiempo Total de encuesta</th>
          @foreach ($questionlist as $ql)
          
            @if($ql->idQuestionType==8) <!-- bool -->
              <th colspan="3" style="text-align: center;" >{{ $ql->data }} </th>
            @endif
            @if($ql->idQuestionType==9) <!-- options -->
              <th colspan="3" style="text-align: center;" >{{ $ql->data }} </th>      
            @endif          
        
        @endforeach
      </tr>
    
    
      <tr class="info">
        
        <th>TLC</th>
        <th>TT</th>
        <th>TP</th>

        @foreach ($questionlist as $ql)
            @if($ql->idQuestionType==8) <!-- bool -->
              <th>CN</th>
              <th>TT</th>
              <th>TP</th>
            @endif
            @if($ql->idQuestionType==9) <!-- options -->
              <th>CN</th>
              <th>TT</th>
              <th>TP</th>
            @endif          
        @endforeach
      </tr>
    
    @foreach ($v as $vg)
      <tr>
      <?php
         $TLC=$TLC+$vg->TLC;
         $TT=$TT+$vg->TT;
         $TP=$TP+$vg->TP;
        ?>
        <td>{{ $vg->firstName }} {{ $vg->lastName }}</td>

        <td>{{ $vg->TLC }}</td>
        <td>{{ $reporteTiempo->secToMin($vg->TT) }}</td>
        <td>{{ $reporteTiempo->secToMin($vg->TP) }}</td>
         <?php
          $totalesOper[$i][0]=$totalesOper[$i][0]=$vg->TLC;
          $totalesOper[$i][1]=$totalesOper[$i][1]=$vg->TT;
          $totalesOper[$i][2]=$totalesOper[$i][2]=$vg->TP;
        ?>
         <!-- preguntas -->
        @foreach ($v2 as $operTime)
        <?php
          $totales[$i][0]=$totales[$i][0]=$operTime->CN;
          $totales[$i][1]=$totales[$i][1]=$operTime->TT;
          $totales[$i][2]=$totales[$i][2]=$operTime->TP;
        ?>
          <td>{{ $operTime->CN }}</td>
          <td>{{ $reporteTiempo->secToMin($operTime->TT) }}</td>
          <td>{{ $reporteTiempo->secToMin($operTime->TP) }}</td>
          <?php
          $i++;
          ?>
        @endforeach
      </tr>
      @endforeach

      <tr>
        <td><b> Totales </b> </td>
      <?php
        for ($f=0; $f < count($totalesOper); $f++) 
        { 

          ?>

          <td><b>{{ $totalesOper[$f][0] }} </b> </td>
          <td><b> {{ $reporteTiempo->secToMin($totalesOper[$f][1]) }} </b> </td>
          <td><b> {{ $reporteTiempo->secToMin($totalesOper[$f][2]) }} </b> </td>
         <?php
        }
         for ($f=0; $f < count($totales); $f++) 
        { 
          ?>
          <td><b> {{ $totales[$f][0] }} </b> </td>
          <td><b> {{ $reporteTiempo->secToMin($totales[$f][1]) }} </b> </td>
          <td><b> {{ $reporteTiempo->secToMin($totales[$f][2]) }} </b> </td>
         <?php
        }
      ?>
      </tr>
      <tr>
        <td> <b> Promedios </b> </td>
       <?php
        for ($f=0; $f < count($totalesOper); $f++) 
        { 
          $cantidad=count($v);
          ?>

          <td><b> {{ $totalesOper[$f][0]/$cantidad }} </b> </td>
          <td><b> {{ $reporteTiempo->secToMin($totalesOper[$f][1]/$cantidad) }} </b> </td>
          <td><b> {{ $reporteTiempo->secToMin($totalesOper[$f][2]/$cantidad) }} </b> </td>
         <?php
        }
         for ($f=0; $f < count($totales); $f++) 
        { 
          ?>
          <td><b> {{ $totales[$f][0]/$cantidad }} </b> </td>
          <td><b> {{ $reporteTiempo->secToMin($totales[$f][1]/$cantidad) }} </b> </td>
          <td><b> {{ $reporteTiempo->secToMin($totales[$f][2]/$cantidad) }} </b> </td>
         <?php
        }
      ?>
       
      </tr>
    </tbody>


  </table>
   <p>TLC = Total Llamadas Concretadas | Cn = cantidad | TT = Tiempo Total | TP = Tiempo Promedio | TEL = Tiempo Entre Llamadas </p>
</div>


@stop