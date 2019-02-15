@extends('app')

<?php
   use App\question;
   use App\questionType;
   use Illuminate\Http\Request;
   //use Session;
    /*if ($question->exists):
        $form_data = array('route' => array('question.update', $question->id), 'method' => 'PATCH');
        $action    = 'Editar';
        $question->order=$question->order;
        $question->idCampaign =$question->idCampaign;
        $question->idPersonModificator = Auth::user()->id;
    else:
        $form_data = array('route' => 'question.store', 'method' => 'POST');
        $action    = 'Create';     
        $request = new Request();
        $input = Input::all();
        dd($input);
        $idC=key($input);

        $question->idCampaign =$idC;
        $question->idPersonModificator = Auth::user()->id;
        $question->idPersonCreator = Auth::user()->id;

        $cnt = DB::table('Question')->where('idCampaign', $idC)->count();
        $cnt=$cnt+1;
        $question->order=$cnt;   
    endif;*/
    $form_data = array('route' => 'question.store', 'method' => 'POST');
        $action    = 'Crear';     
        
        //$idC=key($input);
        $questionCondition = new Question;
        $questionCondition->idCampaign =$question->idCampaign;
        $questionCondition->idPersonModificator = Auth::user()->id;
        $questionCondition->idPersonCreator = Auth::user()->id;
        $questionCondition->idQuestionCondition= $question->id;
        $questionCondition->data= $question->data;
        $idC=$question->idCampaign;
        $questionCondition->order= ($question->order)+1;
        $questionCondition->type=10;
        $questionlist=question::where('idCampaign', '=', $idC)->orderBy('order')->get();

?>

@section ('content')

<h1>{!! $action !!} Condición</h1>

{!! Form::model($questionCondition, $form_data, array('role' => 'form')) !!}

  @include ('question/errors', array('errors' => $errors))

  <div class="row">
  {!! Form::checkbox('allowNotAnswer', true, false) !!} Permitir no responder
    <div class="form-group col-md-4">
        {!! Form::label('data', 'Pregunta seleccionada') !!}
         {!! Form::label('data', $question->data) !!}
@if($question->idQuestionType==8)       
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4">
        
         {!! Form::label('idQuestionType', 'Si la respuesta es SI') !!}
      <select id="select" name="idQuestionSi">
      <option value="" selected>Seleciona una pregunta de destino si la opcion es escogida</option>
        @foreach ($questionlist as $questionlist) 
          @if($questionlist->order>=$questionCondition->order)
            <option value="{{ $questionlist->id }}">{{ $questionlist->data }}</option>
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
      <option value="" selected>Seleciona una pregunta de destino si la opcion es escogida</option>
        @foreach ($questionlist as $questionlist) 
          @if($questionlist->order>=$questionCondition->order)
            <option value="{{ $questionlist->id }}">{{ $questionlist->data }}</option>
          @endif
        @endforeach
      </select>      
    </div>
  </div>
@endif

@if($question->idQuestionType==9)       
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
        <option value="" selected>Seleciona una pregunta de destino si la opcion es escogida</option>
        @foreach ($questionlist as $questionl) 
          @if($questionl->order>=$questionCondition->order)
            <option value="{{ $questionl->id }}">{{ $questionl->data }}</option>
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