@extends('app')

<?php
   use App\Question;
   use App\QuestionType;
   use App\validation;
   use Illuminate\Http\Request;
   //use Session;
    if ($question->exists):
        $form_data = array('route' => array('question.update', $question->id), 'method' => 'PATCH');
        $action    = 'Editar';
        $question->order=$question->order;
        $question->idCampaign =$question->idCampaign;
        $question->idPersonModificator = Auth::user()->id;
    else:
        $form_data = array('route' => 'question.store', 'method' => 'POST');
        $action    = 'Crear';     
        $request = new Request();
         //$input = Input::all();
        //$idC=key($input);
        $validation = new validation;
          $idC=$validation->urlData(); 

        $question->idCampaign =$idC;
        $question->idPersonModificator = Auth::user()->id;
        $question->idPersonCreator = Auth::user()->id;

        $cnt = DB::table('Question')->where('idCampaign', $idC)->count();
        $cnt=$cnt+1;
        $question->order=$cnt;   
    endif;
    $question->urlData();
        //dd($question->url);
?>

@section ('content')

<h1>{!! $action !!} Pregunta</h1>

{!! Form::model($question, $form_data, array('role' => 'form')) !!}

  @include ('question/errors', array('errors' => $errors))

  <div class="row">
    <div class="form-group col-md-4">
        {!! Form::label('data', 'Ingrese la pregunta') !!}
        {!! Form::text('data', null, array('placeholder' => 'pregunta', 'class' => 'form-control')) !!}        
    </div>
    <div class="form-group col-md-4">
        <?php
          $questionType=QuestionType::all();
            //var_dump($questionType);
            //{{ $question->name }}
        ?>
         {!! Form::label('idQuestionType', 'Elija tipo de pregunta') !!}
      <select id="select" name="idQuestionType">
        @foreach ($questionType as $questionType) 
          @if($questionType->id!=10)
            <option  id="selected" value="{{ $questionType->id }}"
               <?php
               if($question->idQuestionType==$questionType->id )
               {
                echo 'selected';
               }
                ?>

             >{{ $questionType->name }}</option>
          @endif
        @endforeach
      </select>      
    </div>
  </div>
  <div class="row">
  <div id="textarea"  class="form-group col-md-4">
        {!! Form::label('option', 'Opciones - Ingresa una y presiona enter') !!}
        {!! Form::textarea('option', null, array( 'class' => 'form-control','rows' =>'6' ,'cols' =>'50')) !!}        
      </div>
  </div>
  <?php  

         

  ?>
  {!!  Form::hidden('order', $question->order, array('readonly' )) !!}
  {!!  Form::hidden('idCampaign', $question->idCampaign, array('readonly' )) !!}
  {!!  Form::hidden('url', $question->url, array('readonly' )) !!}
  
  {!! Form::button('Aceptar', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}    
  {!! Form::button('Cancelar', array('type' => 'button', 'class' => 'btn btn-danger', 'onclick' => 'history.back()')) !!}
  
{!! Form::close() !!}
 </div>


@stop