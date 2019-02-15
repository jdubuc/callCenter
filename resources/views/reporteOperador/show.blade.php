@extends ('app')	
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
  $v=$data['v'];
  $v2=$data['v2'];
  //dd($data);
  $idq=0;
  $idq2=0;
  $idqT=0;
  $opActual=1;
  $i=0;
  $x=0;
  $i2=0;
  $it=0;
  
  $idQuestionvsplit =array();

  $campaign = Campaign::find($data['idCampaign']);
  $account = Account::find($campaign->idAccount);
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
  $Vsum=$data['Vsum'];
  $Vsum2=$data['Vsum2'];
  //dd($Vsum2);
  
?>
 <div class="container">
  <h2>Reporte Evaluación de Respuestas de Operadores por Campaña </h2>
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
        <th colspan="5" scope="rowgroup" valign="middle" style="text-align: center;  padding-top: 2.5%;">Evaluacion de llamadas</th>
          @foreach ($questionlist as $ql)
          
            @if($ql->idQuestionType==8) <!-- bool -->
              <th colspan="2" style="text-align: center;" >{{ $ql->data }} </th>
            @endif
            @if($ql->idQuestionType==9) <!-- options -->
                    <?php
                      //$idQuestionv=Question::find($ql->idQuestion);
                      //$split= str_split($idQuestionv->option, '/\r\n|\n|\r/');
                      $idQuestionvsplit = explode("\n", $ql->option);
                      $idQuestionvsplit2 = count($idQuestionvsplit);
                      //dd($idQuestionvsplit2);
                    ?>
              <th colspan="<?php echo $idQuestionvsplit2  ?>" style="text-align: center;" >{{ $ql->data }} </th>      
            @endif          
        
        @endforeach
      </tr>
    
    
      <tr class="info">
        <th>I</th>
        <th>LC</th>
        <th>C</th>
        <th>NC</th>
        <th>NI</th>

        @foreach ($questionlist as $ql)
            @if($ql->idQuestionType==8) <!-- bool -->
             <th>SI</th>
                        <th>NO</th>
            @endif
            @if($ql->idQuestionType==9) <!-- options -->
                    <?php
                      //$idQuestionv=Question::find($ql->idQuestion);
                      //$split= str_split($idQuestionv->option, '/\r\n|\n|\r/');
                      $idQuestionvsplit = explode("\n", $ql->option);
                      $idQuestionvsplit2 = count($idQuestionvsplit);
                      //dd($idQuestionvsplit2);
                    ?>
              @foreach ($idQuestionvsplit as $split)
                <th>{{ $split }}</th>
              @endforeach
            @endif          
        @endforeach  
      </tr>
@foreach ($v as $vg)
      <tr>
        <td>{{ $vg->firstName }} {{ $vg->lastName }}</td>

        <td>{{ $vg->i }}</td>
        <td>{{ $vg->lc }}</td>
        <td>{{ $vg->c }}</td>

        <td>{{ $vg->nc }}</td>
        <td>{{ $vg->ni }}</td>
    
         <!-- preguntas -->
      <?php
        $qLength = count($q); 
       $xLength = count($v2);
        //dd($q[$i][1]);
        while ($i<$qLength){

            if(($x<$xLength)&&($q[$i][0]==$v2[$x]->order && $q[$i][1]==$v2[$x]->dataAnswer))
            { 
              //echo "<td>"."a.order:".$q[$i][0].", ds.order:".$v2[$x]->order.", a.answer:".$q[$i][1].", ds.answer:". $v2[$x]->dataAnswer."</td>";
              //echo "<td>".$v2[$x]->cant."</td>";
               ?>
               <td>{{ $v2[$x]->cant }}</td>
               <?php
              $x++;
            }
            else
            {
              ?>
               <td>0</td>
               <?php
            }
            $i++;
          
        }
        $i=0;
      ?>

      </tr>
      @endforeach
      <tr>
        <td><b> Totales </b> </td>
       
          
          <td><b>{{ $Vsum[0]->i }}</b></td>
          <td><b>{{ $Vsum[0]->lc }}</b></td>
          <td><b>{{ $Vsum[0]->c }}</b></td>
          <td><b>{{ $Vsum[0]->nc }}</b></td>
          <td><b>{{ $Vsum[0]->ni }}</b></td>
       
             
              
      <?php
      $i=0;
      $x=0;
      $qLength = count($q); 
      $xLength = count($Vsum2);
        //dd($q[$i][1]);
        while ($i<$qLength)
        {
            if(($x<$xLength)&&($q[$i][0]==$Vsum2[$x]->order && $q[$i][1]==$Vsum2[$x]->dataAnswer))
            { 
              //echo "<td>"."a.order:".$q[$i][0].", ds.order:".$Vsum2[$x]->order.", a.answer:".$q[$i][1].", ds.answer:". $Vsum2[$x]->dataAnswer."</td>";
              //echo "<td>".$Vsum2[$x]->cant."</td>";
               ?>
               <td><b>{{ $Vsum2[$x]->cant }}</b></td>
               <?php
              $x++;
            }
            else
            {
              ?>
               <td><b>0</b></td>
              <?php
            }
            $i++;  
        }
       
      ?>
      </tr>
    </tbody>
  </table>
   <p>I = Intentos | LC = Llamadas Concretadas | C = Contestó | NC = No Contestó | NI = Número Inválido | NR = No Responde | OPX = Opción X | LI = Llamada Interrumpida </p>
</div>


@stop