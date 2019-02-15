@extends ('app')	
@section ('content')
 <?php
use App\User;
use App\PersonCampaign;
use App\Campaign;
use App\Question;
use App\QuestionType;
use App\Account;
use App\MessageSend;
	Session::flash('backUrl', Request::fullUrl());
	$url = Session::get('backUrl'); 
  // $v=$data['v'];
  //$v2=$data['v2'];
 $idq=0;
  $idq2=0;
  $idqT=0;
  $opActual=1;
  $i=0;
  $x=0;
  $i2=0;
  $it=0;
	
$answer=$data['answer'];
//dd($answer);
$campaign = Campaign::find($data['idCampaign']);
$account = Account::find($campaign->idAccount);
$questionlist=question::where('idCampaign', '=', $campaign->id)->orderBy('order')->get();
$messageSend=messageSend::where('idCampaign', '=', $campaign->id)->orderBy('id')->get();

 $q = array();
  foreach ($questionlist as $ql) {
  	if($ql->idQuestionType!='10' )
    {  
	        $q[$it][0]=$ql->order;
	        $q[$it][1]=$ql->idQuestionType;
	        $it++;    
    }
  }
  //dd($q);
?>
 <div class="container">
  <h2>Reporte Operador</h2>
  <p>Cuenta: {{ $account->name }} | Campaña: {{ $campaign->name }} | Activa: 
     @if($campaign->active==true)
      si
     @else
      no
     @endif
    </p>
   <p>  Fecha Incio Campaña: {{ $campaign->dateTimeStart }} | Fecha Fin Campaña: {{ $campaign->dateTimeEnd }}  </p> 
   <p> Fecha Incio Reporte: {{ $data['inicio'] }} | Fecha Fin Reporte: {{ $data['final'] }}   </p>             

<table class="table table-bordered table-striped table-hover draggable sortable">
 <h1>Preguntas</h1>
<!-- <tr class="info">
        <th colspan="{{ count($questionlist) }}" scope="rowgroup" valign="middle" style="text-align: center;  padding-top: 2.5%;">Preguntas</th>
      </tr> -->
    <col>
  <col>
  <colgroup span="3"></colgroup>
     <tbody>
     
    <tr class="info">
    @foreach ($questionlist as $ql)
    		@if($ql->idQuestionType!=10) <!-- option -->
	            <th>{{ $ql->data }}</th>
            @endif
    @endforeach
    </tr>
    

    @foreach ($messageSend as $ql)
    <tr>
    	  <?php
        $qLength = count($q); 
       $answerLength = count($answer);
        //dd($q[$i][1]);
        while ($i<$qLength){

            if(($x<$answerLength)&&($q[$i][0]==$answer[$x]->order && $q[$i][1]==$answer[$x]->idQuestionType))
            { 
               ?>

                @if($answer[$x]->idQuestionType==8) <!-- bool -->
	    			@if($answer[$x]->data==1) <!-- bool -->
	             		<th>SI</th>   
	                @elseif($answer[$x]->data==2)
	                	<th>NO</th>
	                @else
	                <td>NR</td>
	            	@endif
	            @elseif($answer[$x]->idQuestionType==9) <!-- option -->
	            <?php
	            $qOp=question::find($answer[$x]->idQuestion);
	              $idQuestionvsplit = explode("\n", $qOp->option);
                      $idQuestionvsplit2 = count($idQuestionvsplit);
	            ?>
	            	@if($idQuestionvsplit[$answer[$x]->data-1]=='-1')
	            	<td>NR </td>
	            	@else
	             	<td>{{ $idQuestionvsplit[$answer[$x]->data-1] }} </td>
	             	@endif
	            @else
	             <td>{{ $answer[$x]->data }}</td>
            	@endif
            <?php
              $x++;
            }
            else
            {
              ?>
               <td>NR</td>
               <?php
            }
            $i++;
          
        }
        $i=0;
        
      ?>
    	
  	</tr>
    @endforeach     
    
    </tbody>


  </table>



   <p>NR = no respondio	</p>
</div>


@stop

     <!-- <tr class="info">
        
        <th>¿que?</th>
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
        <td>si</td>
        <td>no</td>

        <td>si</td>
        <td>no</td>

        <td>si</td>
        <td>no</td>

       <td>si</td>
        <td>no</td>

        <td>si</td>
       
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
        
      </tr>-->
      
      <script src="{{ asset('/dragtable.js') }}"></script>