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
	//dd($data);
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

//dd($q);
    $Vsum=DB::select(DB::raw('SELECT SUM(td) as td, SUM(i) as i, SUM(lc) as lc, SUM(c) as c, SUM(nc) as nc, SUM(ni) as ni 
FROM "rptResultCampEvaluacion"(' . $campaign->id .',\''.$data['inicio'].'\',\''.$data['final'] .'\')'));

    $Vsum2=DB::select(DB::raw('SELECT "idQuestion", "idQuestionType", "dataAnswer", SUM(cant) as cant, MAX("order") as "order"
FROM "rptResultCampPreguntas"(' . $campaign->id .',\''.$data['inicio'].'\',\''.$data['final'] .'\')GROUP BY "idQuestion", "idQuestionType", "dataAnswer" ORDER BY "order", "dataAnswer"'));
    //dd($Vsum2);
?>

 <div class="container">
  <h2>Resultados de Campaña por Grupos de Destinatarios </h2>
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
        <th rowspan="2" scope="rowgroup" valign="middle" style="text-align: center;  padding-top: 2.5%;">Grupo Destinatario</th>
        <th rowspan="2" scope="rowgroup" valign="middle" style="text-align: center;  padding-top: 2.5%;">TD</th>
        <th colspan="5" style="text-align: center;">Evaluación de Llamadas</th>
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
        <td>{{ $vg->name }}</td>
        <td>{{ $vg->td }}</td>

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
       
          <td><b>{{ $Vsum[0]->td }}</b></td>
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
   <p>TD = Total Destinatarios | I = Intentos | LC = Llamadas Concretadas | C = Contestó | NC = No Contestó | NI = Número Inválido | NR = No Responde | OPX = Opción X | LI = Llamada Interrumpida</p>
</div>
 <!--<table class="table table-bordered table-striped table-hover">
    <col>
  <col>
  <colgroup span="3"></colgroup>
     <tbody>
      <tr class="info">
        <th rowspan="2" scope="rowgroup" valign="middle" style="text-align: center;  padding-top: 2.5%;">Grupo Destinatario</th>
        <th rowspan="2" scope="rowgroup" valign="middle" style="text-align: center;  padding-top: 2.5%;">TD</th>
        <th colspan="5" style="text-align: center;">Evaluación de Llamadas</th>
        <th colspan="4" style="text-align: center;" >Pregunta 1     </th>
        <th colspan="5" style="text-align: center;" >Pregunta 2       </th>
      </tr>
    
      <tr class="info">
        
        <th>I</th>
        <th>LC</th>
        <th>C</th>
        <th>NC</th>
        <th>NI</th>

        <th>SI</th>
        <th>NO</th>
        <th>NR</th>
        <th>LI</th>

        <th>op1</th>
        <th>op1</th>
        <th>opn</th>
        <th>NR</th>
        <th>LI</th>
       
      </tr>

      <tr>
        <td>grupo 2</td>
        <td>15</td>

        <td>25</td>
        <td>10</td>
        <td>2</td>

        <td>5</td>
        <td>3</td>
        <td>2</td>

        <td>0</td>
        <td>0</td>
        <td>0</td>

        <td>1</td>
        <td>1</td>
        <td>0</td>
        <td>0</td>
        <td>0</td>
      </tr>
      <tr>
        <td>grupo1</td>
        <td>121</td>

        <td>110</td>
        <td>100</td>
        <td>70</td>

        <td>10</td>
        <td>20</td>
        <td>35</td>

        <td>30</td>
        <td>1</td>
        <td>4</td>
        <td>35</td>
        <td>29</td>
        <td>1</td>
        <td>0</td>
        <td>5</td>
      </tr>
      <tr>
        <td><b> Totales </b> </td>
        <td><b>136</b></td>
        <td><b>135</b></td>
        <td><b>110</b></td>
        <td>72</td>
        <td><b>15</b></td>
        <td><b>23</b></td>
        <td><b>37</b></td>
        <td><b>30</b></td>
        <td><b>1</b></td>
        <td><b>4</b></td>
        <td><b>36</b></td>
        <td><b>20</b></td>
        <td><b>1</b></td>
        <td><b>0</b></td>
        <td><b>5</b></td>
      </tr>
    </tbody>


  </table>-->

@stop