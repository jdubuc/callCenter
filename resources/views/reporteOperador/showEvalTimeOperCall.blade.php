extends ('app')	
@section ('content')
 <?php
use App\User;
use App\PersonCampaign;
use App\Campaign;
use App\Question;
use App\QuestionType;
use App\Account;
	Session::flash('backUrl', Request::fullUrl());
	$url = Session::get('backUrl'); 
  // $v=$data['v'];
  //$v2=$data['v2'];
	//dd($data);

  /*
  $hours = floor($seconds / 3600);
$mins = floor($seconds / 60 % 60);
$secs = floor($seconds % 60);
If you want to get time format:

$timeFormat = sprintf('%02d:%02d:%02d', $hours, $mins, $secs);*/
?>
 <div class="container">
  <h2>Reporte Operador</h2>
   <p>Cuenta: Delcos | Campaña: Campaña 1 | Activa: Sí     </p>
   <p>  Fecha Incio Campaña: 12/03/2017 | Fecha Fin Campaña: // | Fecha Incio Reporte: 18/03/2017 | Fecha Fin Reporte: 18/03/2017               </p>
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
        <td>John</td>
        <td>10</td>

        <td>2</td>
        <td>0:00:50</td>
        <td>0:00:25</td>

        <td>5</td>
        <td>0:00:20</td>
        <td>0:00:04</td>

        <td>2</td>
        <td>0:00:20</td>
        <td>0:00:07</td>

        <td>2:05:00</td>
      </tr>
      <tr>
        <td>jesus</td>
        <td>100</td>

        <td>70</td>
        <td>1:00:50</td>
        <td>0:01:03</td>

        <td>10</td>
        <td>0:02:24</td>
        <td>0:00:14</td>

        <td>20</td>
        <td>0:03:37</td>
        <td>0:00:11</td>

         <td>0:12:14</td>
      </tr>
      <tr>
        <td><b> Totales </b> </td>
        <td><b>110</b></td>
        <td><b>72</b></td>
        <td><b>1:03:53</b></td>
        <td></td>
       	<td><b>15</b></td>
        <td><b>0:02:44</b></td>
        <td><b></td>
        <td><b>23</b></td>
        <td><b>0:03:57</b></td>
      </tr>
      <tr>
        <td> <b> Promedios </b> </td>
        <td><b>55</b></td>
        <td><b>36</b></td>
        <td><b>0:00:53</b></td>
        <td></td>
        <td><b>7.5</b></td>
        <td><b>0:00:11</b></td>
        <td></td>
        <td><b>11.5</b></td>
        <td><b>0:00:10</b></td>
      </tr>
    </tbody>


  </table>
   <p>TLC = Total Llamadas Concretadas | Cn = cantidad | TT = Tiempo Total | TP = Tiempo Promedio | TEL = Tiempo Entre Llamadas	</p>
</div>


@stop