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
  $reporteTiempo = new reporteTiempoOperador;
  $campaign = Campaign::find($data['idCampaign']);
  $account = Account::find($campaign->idAccount);
	//dd($data);
  $cantidad=count($v);
  $TLC=0;
  $contestoCn=0;
  $contestoTT=0;
  $contestoTP=0;
  $noContestoCn=0;
  $noContestoTT=0;
  $noContestoTP=0;
  $invalidoCn=0;
  $invalidoTT=0;
  $invalidoTP=0;
  $TEL=0;
?>
 <div class="container">
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
        <th rowspan="2" scope="rowgroup" valign="middle" style="text-align: center;  padding-top: 2.5%;">TLC</th>
        <th colspan="3" style="text-align: center;">contestó</th>
        <th colspan="3" style="text-align: center;" >No contestó</th>
        <th colspan="3" style="text-align: center;" >Número inválido</th>
        <th rowspan="2" scope="rowgroup" valign="middle" style="text-align: center;  padding-top: 2.5%;">TEL</th>
      </tr>
    
    
      <tr class="info">
        
        <th>CN</th>
        <th>TT</th>
        <th>TP</th>
        <th>CN</th>
        <th>TT</th>
        <th>TP</th>
        <th>CN</th>
        <th>TT</th>
        <th>TP</th>
       
      </tr>
      
      <tr>
        @foreach ($v as $operTime)
        <?php
         $TLC=$TLC+$operTime->TLC;
         $contestoCn=$contestoCn+$operTime->contestoCn;
         $contestoTT=$contestoTT+$operTime->contestoTT;
         $contestoTP=$contestoTP+$operTime->contestoTP;
         $noContestoCn=$noContestoCn+$operTime->noContestoCn;
         $noContestoTT=$noContestoTT+$operTime->noContestoTT;
         $noContestoTP=$noContestoTP+$operTime->noContestoTP;
         $invalidoCn=$invalidoCn+$operTime->invalidoCn;
         $invalidoTT=$invalidoTT+$operTime->invalidoTT;
         $invalidoTP=$invalidoTP+$operTime->invalidoTP;
         $TEL=$TEL+$operTime->TEL;
        ?>
          <td>{{ $operTime->firstName }} {{ $operTime->lastName }}</td>
          <td>{{ $operTime->TLC }}</td>
          <td>{{ $operTime->contestoCn }}</td>
          <td>{{ $reporteTiempo->secToMin($operTime->contestoTT) }}</td>
          <td>{{ $reporteTiempo->secToMin($operTime->contestoTP) }}</td>

          <td>{{ $operTime->noContestoCn }}</td>
          <td>{{ $reporteTiempo->secToMin($operTime->noContestoTT) }}</td>
          <td>{{ $reporteTiempo->secToMin($operTime->noContestoTP) }}</td>

          <td>{{ $operTime->invalidoCn }}</td>
          <td>{{ $reporteTiempo->secToMin($operTime->invalidoTT) }}</td>
          <td>{{ $reporteTiempo->secToMin($operTime->invalidoTP) }}</td>

          <td>{{ $reporteTiempo->secToMin($operTime->TEL) }}</td>
        @endforeach
      </tr>
      <tr>
        <td><b> Totales </b> </td>
        <td><b>{{ $TLC }}</b> </td>
        <td><b>{{ $contestoCn }} </b> </td>
        <td><b>{{ $reporteTiempo->secToMin($contestoTT) }}</b> </td>
        <td><b>{{ $reporteTiempo->secToMin($contestoTP) }}</b> </td>
        <td><b>{{ $noContestoCn }}</b> </td>
        <td><b>{{ $reporteTiempo->secToMin($noContestoTT) }}</b> </td>
        <td><b>{{ $reporteTiempo->secToMin($noContestoTP) }}</b> </td>
        <td><b>{{ $invalidoCn }}</b> </td>
        <td><b>{{ $reporteTiempo->secToMin($invalidoTT) }}</b> </td>
        <td><b>{{ $reporteTiempo->secToMin($invalidoTP) }}</b> </td>
        <td><b>{{ $reporteTiempo->secToMin($TEL) }}</b> </td>
      </tr>
      <tr>
        <td> <b> Promedios </b> </td>
        <td><b>{{ $TLC/$cantidad }}</b> </td>
        <td><b>{{ $contestoCn/$cantidad }} </b> </td>
        <td><b>{{ $reporteTiempo->secToMin($contestoTT/$cantidad) }}</b> </td>
        <td><b>{{ $reporteTiempo->secToMin($contestoTP/$cantidad) }}</b> </td>
        <td><b>{{ $noContestoCn/$cantidad }}</b> </td>
        <td><b>{{ $reporteTiempo->secToMin($noContestoTT/$cantidad) }}</b> </td>
        <td><b>{{ $reporteTiempo->secToMin($noContestoTP/$cantidad) }}</b> </td>
        <td><b>{{ $invalidoCn/$cantidad }}</b> </td>
        <td><b>{{ $reporteTiempo->secToMin($invalidoTT/$cantidad) }}</b> </td>
        <td><b>{{ $reporteTiempo->secToMin($invalidoTP/$cantidad) }}</b> </td>
        <td><b>{{ $reporteTiempo->secToMin($TEL/$cantidad) }}</b> </td>
       
      </tr>
    </tbody>


  </table>
   <p>TLC = Total Llamadas Concretadas | Cn = cantidad | TT = Tiempo Total | TP = Tiempo Promedio | TEL = Tiempo Entre Llamadas	</p>
</div>


@stop