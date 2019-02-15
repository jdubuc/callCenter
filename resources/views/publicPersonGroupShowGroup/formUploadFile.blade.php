@extends('app')
<?php
use App\User;
use App\PersonCampaign;
use App\Campaign;
use App\Question;
use App\QuestionType;

?>
@section('content')
<div class="container">
  <div class="row">
    @if($errors->any())
      <ul class="alert alert-danger">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    @endif
<h1>agregar al Grupo</h1>
<a href="{{asset('/formato_crear_grupo.xlsx')}}" download class="btn btn-warning">Descargar Formato del Archivo</a>
<h3></h3>
    {!! Form::open(['url' => 'publicPersonGroupShowGroup', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
    <div class="col4" style="padding-bottom: 5px; width: 50%;">
      {!! Form::label('Select a file to upload', 'Seleciona un archivo para crear el grupo de operadores.csv .xls .xlsx', ['class' => 'control-label']) !!}
      {!! Form::file('file', ['class' => 'form-control']) !!}
    </div>
      {!! Form::submit('Crear Grupo', ['class' => 'btn btn-primary']) !!}
    
  </div>
</div>
  {!! Form::close() !!}
@endsection