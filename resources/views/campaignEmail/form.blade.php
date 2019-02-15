@extends('app')

<?php
   
    if ($campaign->exists):
        $form_data = array('route' => array('campaignEmail.update', $campaign->id), 'method' => 'PATCH');
        $action    = 'Editar';
         $campaign->idPersonModificator = Auth::user()->id;
    else:
        $form_data = array('route' => 'campaignEmail.store', 'method' => 'POST');
        $action    = 'Crear';     
        $campaign->idPersonModificator = Auth::user()->id;
        $campaign->idPersonCreator = Auth::user()->id; 
        $campaign->active = '0';
        $campaign->type = 'Email';
        $campaign->tries = 1;
    endif;
?>

@section ('content')

<h1>{!! $action !!} campaña</h1>

{!! Form::model($campaign, $form_data, array('role' => 'form')) !!}

  @include ('campaign/errors', array('errors' => $errors))

  <div class="row">
    <div class="form-group col-md-4">
        {!! Form::label('name', 'Nombre de la campaña') !!}
        {!! Form::text('name', null, array('placeholder' => 'Nombre de la campaña', 'class' => 'form-control')) !!}        
      </div>
      <div class="form-group col-md-4">
        {!! Form::label('tries', 'Ingrese el numero de intentos para enviar un email (minimo 1)') !!}
        {!! Form::text('tries', null, array('placeholder' => 'Numero de intentos', 'class' => 'form-control')) !!}        
      </div>
      
    </div>
    <div class="row">
      <div class="form-group col-md-4">
        {!! Form::label('dateTimeStart', 'Inicio de la Campaña') !!}
        <input type="datetime-local" name="dateTimeStart" class="form-control">
      </div>

      <div class="form-group col-md-4">
        {!! Form::label('dateTimeEnd', 'Final de la Campaña') !!}
        <input type="datetime-local" name="dateTimeEnd" class="form-control">       
      </div>

    </div>
    <div class="row">
      <div class="form-group col-md-12">
        {!! Form::label('emailSubject', 'Asunto del Email') !!}
        {!! Form::text('emailSubject', null, array('placeholder' => 'Asunto del Email', 'class' => 'form-control')) !!}        
      </div>

    </div>
 <div class="form-group col-md-14">
        {!! Form::label('campaignMessage', 'Email') !!}
        {!! Form::textarea('campaignMessage', null, array( 'class' => 'form-control')) !!}        
      </div>
 
 
 
  {!!  Form::hidden('active', $campaign->type, array('readonly' )) !!}
  {!!  Form::hidden('active', $campaign->active, array('readonly' )) !!}
  {!!  Form::hidden('idPersonCreator', $campaign->idPersonCreator, array('readonly' )) !!}
  {!!  Form::hidden('idPersonModificator', $campaign->idPersonModificator, array('readonly' )) !!}
  {!! Form::button('Crear', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}    
  <input type="button" class="btn btn-danger" onclick="location.href = '{{ url('/') }}'" value="Cancelar">
  
{!! Form::close() !!}
 </div>

 <!-- <script type="text/javascript" src="https://cdn.jsdelivr.net/tinymce/4.1.2/tinymce.min.js"></script> -->
 <script type="text/javascript" src="{{asset('/tinymce/tinymce.min.js')}}"></script>
<script>
tinymce.init({
    selector: "textarea",
    setup: function (editor) {
        editor.on('change', function () {
            editor.save();
        });
    
    },
    relative_urls: false,
    convert_urls: false,
    remove_script_host : false,
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
});
</script>
@stop