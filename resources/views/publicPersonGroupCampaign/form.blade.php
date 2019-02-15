@extends('app')

<?php
   $campaign->active = '0';
   $campaign->type = 'CallCenter';
    if ($campaign->exists):
        $form_data = array('route' => array('campaign.update', $campaign->id), 'method' => 'PATCH');
        $action    = 'Edit';
         $campaign->idPersonModificator = Auth::user()->id;
    else:
        $form_data = array('route' => 'campaign.store', 'method' => 'POST');
        $action    = 'Create';     
        $campaign->idPersonModificator = Auth::user()->id;
        $campaign->idPersonCreator = Auth::user()->id; 
        $campaign->type='CallCenter';
    endif;
?>

@section ('content')

<h1>{!! $action !!} Lista de grupos de destinatarios</h1>

{!! Form::model($campaign, $form_data, array('role' => 'form')) !!}

  @include ('campaign/errors', array('errors' => $errors))

 @extends ('app')

@section ('title') Lista de grupos de destinatarios @stop




@section ('content')
<p>
    <a href="{!! route('publicPersonGroup.create') !!}" class="btn btn-primary">Crear un nuevo grupo</a>
  </p>
  
  <h1>Mis grupos</h1>
  <table class="table table-striped">
    <tr>
        <th>Nombre del Grupo</th>
        <th>Cantidad de Miembros</th>
        <th>Creado por</th>
        <th>Fecha de Creación</th>
    </tr>
   
    <tr>
        <td><input type="checkbox" name="2" value="Bike">Grupo 1<br></td>
        <td>1000</td>
  
   <td>Jesus Dubuc</td>
   
   <td>16-12-2016</td>
      
    </tr>
   
   
   
  </table>
<h1>Otros Grupos</h1>
  
  <table class="table table-striped">
    <tr>
        <th>Nombre del Grupo</th>
        <th>Cantidad de Miembros</th>
        <th>Creado por</th>
        <th>Fecha de Creación</th>
        
    </tr>
   
    <tr>
        <td><input type="checkbox" name="3" value="Bike">Grupo 2<br></td>
       <td>1000</td>
  
   <td>Jesus Dubuc</td>
   
   <td>16-12-2016</td>
      
    </tr>
   
  </table>
@stop
  {!! Form::button('salvar', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}    
  
{!! Form::close() !!}
 </div>
@stop