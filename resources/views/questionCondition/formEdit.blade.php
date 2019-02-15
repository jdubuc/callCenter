@extends('app')

<?php
   use App\question;
   use App\questionType;
   use Illuminate\Http\Request;
   //use Session;
    
        $form_data = array('route' => array('question.update', $question->id), 'method' => 'PATCH');
        $action    = 'Editar';
        $question->order=$question->order;
        $question->idPersonModificator = Auth::user()->id;   
        //$idC=key($input);
       /* $questionCondition = new Question;
        $questionCondition->idCampaign =$question->idCampaign;
        $questionCondition->idPersonModificator = Auth::user()->id;
        $questionCondition->idPersonCreator = Auth::user()->id;
        $questionCondition->idQuestionCondition= $question->id;
        $questionCondition->data= $question->data;*/
        $idC=$question->idCampaign;
        $questionlist=question::where('idCampaign', '=', $idC)->orderBy('order')->get();
        $questionCondition=question::find($question->idQuestionCondition);
        $idConditionList= explode(",", $question->QuestionDestinatary);
        //dd($questionCondition);
?>

@section ('content')

<h1>{!! $action !!} Condición</h1>

{!! Form::model($question, $form_data, array('role' => 'form')) !!}

  @include ('question/errors', array('errors' => $errors))

  <div class="row">
  {!! Form::checkbox('allowNotAnswer', true, false) !!} Permitir no responder
    <div class="form-group col-md-4">
        {!! Form::label('data', 'Pregunta seleccionada') !!}
         {!! Form::label('data', $question->data) !!}
          <?php
          //dd($questionCondition);
        ?>
 
@if($questionCondition->idQuestionType == 8)       
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4">
        
         {!! Form::label('idQuestionType', 'Si la respuesta es SI') !!}
      <select id="select" name="idQuestionSi">
        @foreach ($questionlist as $questionlist) 
          @if($questionlist->order>=$question->order)
            <option value="{{ $questionlist->id }}" <?php
               if($questionlist->id==$idConditionList[1] )
               {
                echo 'selected';
               }
                ?>
                >{{ $questionlist->data }}</option>
          @endif
        @endforeach
      </select>      
    </div>
    </div>
    <div class="row">
    <?php
          $questionlist=question::where('idCampaign', '=', $idC)->orderBy('order')->get();
        ?>
 
    <div class="form-group col-md-4">
        
         {!! Form::label('idquestionlist', 'Si la respuesta en NO') !!}
      <select id="select" name="idQuestionNo">
        @foreach ($questionlist as $questionlist) 
          @if($questionlist->order>=$question->order)
            <option value="{{ $questionlist->id }}" 
            <?php
               if($questionlist->id==$idConditionList[2] )
               {
                echo 'selected';
               }
                ?>
                >{{ $questionlist->data }}</option>
          @endif
        @endforeach
      </select>      
    </div>
  </div>
@endif

@if($questionCondition->idQuestionType==9)       
    </div>
</div>
<div class="row">
<?php
$questionOption = explode( PHP_EOL, $question->option );
//dd($questionOption);
?>
<?php for( $a=0; $a < count($questionOption); $a++)
              { ?>
             <div class="form-group col-md-12">
         {!! Form::label('idQuestionType', 'Si la respuesta es '. $questionOption[$a]) !!}
      <select id="select" name="idQuestion{{ $a }}">
        @foreach ($questionlist as $questionl) 
          @if($questionl->order>=$question->order)
            <option value="{{ $questionl->id }}" 
            <?php
               //if($questionl->id==$idConditionList[$a] )
              if(in_array($questionl->id,$idConditionList)==true )
               {
                echo 'selected';
               }
                ?>
                >{{ $questionl->data }}</option>
          @endif
        @endforeach
      </select>      
    </div>
    <?php   } ?>
    </div>
    
@endif
  <?php  

         

  ?>
  {!!  Form::hidden('idQuestionCondition', $question->id, array('readonly' )) !!}
  {!!  Form::hidden('data', $questionCondition->data, array('readonly' )) !!}
 {!!  Form::hidden('idQuestionType', $questionCondition->type, array('readonly' )) !!}
  {!!  Form::hidden('order', $questionCondition->order, array('readonly' )) !!}
  {!!  Form::hidden('idCampaign', $questionCondition->idCampaign, array('readonly' )) !!}
  
  {!! Form::button('Agregar', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}    
  {!! Form::button('Cancelar', array('type' => 'button', 'class' => 'btn btn-danger', 'onclick' => 'history.back()')) !!}
  
{!! Form::close() !!}
 </div>


@stop