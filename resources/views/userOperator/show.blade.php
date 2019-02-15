@extends('app')

<?php
    use App\User;
    use App\PersonCampaign;
    use App\Campaign;
    use App\PersonGroup;
    use App\PersonPersonGroup;
    use App\PersonGroupCampaign;
    use App\validation;
   //$personGroupCampaign->active = '0';
   //$personGroupCampaign->type = 'CallCenter';
         //$personGroupCampaign->idPersonModificator = Auth::user()->id;
        $form_data = array('route' => 'userOperator.store', 'method' => 'POST');
        $action    = 'Crear';     
       // $personGroupCampaign->idPersonModificator = Auth::user()->id;
       // $personGroupCampaign->idPersonCreator = Auth::user()->id; 
       // $personGroupCampaign->type='CallCenter';
    //dd($idca);
    $user = Auth::user();
    $idUSer=$user->id;
    //Session::flash('backUrl', Request::fullUrl());
    Session::flash('backUrl', Request::fullUrl());
  $url = Session::get('backUrl');
  $validation = new validation;
          $idGroup=$validation->urlData(); 
          $personGroup=PersonGroup::find($idGroup);
  $person= user::where('pOperator','=',2)->where('idAccount','=',$user->idAccount)->get();
  $ppg = personPersonGroup::where('idPersonGroup', '=', $personGroup->id)->get();
  $aId= array();
  $c=0;
  foreach ($ppg as $i)
  {
    $aId[$c]=$i->idPerson;
    $c++;
  }
 // $pPGroup2=DB::table('personGroup') ->where('idPersonCreator', '=', $user->id);
  //dd($aId);
?>

@section ('content')

<!-- {!! Form::model($personGroup, $form_data, array('role' => 'form')) !!} -->

  @include ('userOperator/errors', array('errors' => $errors))
 <p>
    <a href="{!! route('personGroup.create') !!}" class="btn btn-primary">Crear un nuevo grupo</a>
  </p>
  <h1>Lista de operadores</h1>
<h1>Se asignaran al Grupo "{{ $personGroup->name }}"</h1>
  {!! Form::open(['action' => ['personGroupShowGroupController@searchUser'], 'method' => 'GET']) !!}
    {!! Form::text('q', '', ['id' =>  'q', 'placeholder' =>  'Enter name'])!!}
    {!!  Form::hidden('alter','', ['id' =>  'alter'],'readonly') !!}
    {!! Form::submit('Agregar al Grupo', array('class' => 'button expand')) !!}
{!! Form::close() !!}

  <table class="table table-striped">
    <tr>
        
          <th><strong>Nombre</strong></th>
          <th><strong>apellido</strong></th>
          <th><strong>celular</strong></th>
          <th><strong>email</strong></th>
          <th><strong>cedula</strong></th>
          <th><strong>twitter</strong></th>
          <th></th>
    </tr>
    @if($person=='null')
       <tr>
          <td>No hay operadores creados</td>
        </tr>
      @else
        
         @foreach ($person as $p)
           @if(in_array($p->id, $aId)==true)
              <tr>
                <!--<td>{!! Form::checkbox('idPerson[]', $p->id, in_array($p->id, $aId)) !!} </td>-->
                <td>{{ $p->firstName }}</td>
                <td>{{ $p->lastName }}</td>
                <td>{{ $p->phoneNumber }}</td>
                <td>{{ $p->email }}</td>
                <td>{{ $p->cedula }}</td>
                <td>{{ $p->twitter }}</td>
                <td>  
              {!!Form::model($p, array('route' => array('personGroupShowGroup.destroy', $p->id), 'method' => 'DELETE', 'role' => 'form','style'=>'width: 108px; position:relative;'))!!}
                <a >
                  {!! Form::submit('Quitar del grupo', array('class' => 'btn btn-danger ')) !!}
                </a>
              {!! Form::close() !!}
            </td>
              </tr>
            @endif
             @endforeach
      
    @endif
  </table>
  <input type="buttom" onclick="history.back()" name="atras" value="Atr&aacute;s" class="btn btn-primary">
  <!--{!! Form::button('guardar', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}    
{!! Form::close() !!} -->
 </div>
<script>
 $(function()
{
   $( "#q" ).autocomplete({
      minLength: 0,
      source: "{{ url('autocomplete') }}",
      focus: function( event, ui ) {
        $( "#q" ).val( ui.item.label );
        return false;
      },
      select: function( event, ui ) {
        $( "#q" ).val( ui.item.label );
        $( "#alter" ).val(ui.item.value );
        //$( "#q-description" ).html( ui.item.desc );
 
        return false;
      }
    })
    
});
</script>


@stop