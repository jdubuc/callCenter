@extends('app')

<?php
    use App\User;
    use App\PersonCampaign;
    use App\Campaign;
    use App\PersonGroup;
    use App\PersonPersonGroup;
    use App\PersonGroupCampaign;
   //$personGroupCampaign->active = '0';
   //$personGroupCampaign->type = 'CallCenter';
    if ($personGroupCampaign->exists):
        $form_data = array('route' => array('personGroupCampaign.update', $personGroupCampaign->id), 'method' => 'PATCH');
        $action    = 'Editar';
         //$personGroupCampaign->idPersonModificator = Auth::user()->id;
    else:
        $form_data = array('route' => 'personGroupCampaign.store', 'method' => 'POST');
        $action    = 'Crear';     
       // $personGroupCampaign->idPersonModificator = Auth::user()->id;
       // $personGroupCampaign->idPersonCreator = Auth::user()->id; 
       // $personGroupCampaign->type='CallCenter';
    endif;
    
    
    //dd($idca);
    $user = Auth::user();
    $idUSer=$user->id;
    //Session::flash('backUrl', Request::fullUrl());
  $url = Session::get('backUrl');
  $personGroup = personGroup::where('idPersonCreator', '=', $idUSer)->get();
  $idca=$personGroupCampaign->idCampaign;
  $pgc = personGroupCampaign::where('idCampaign', '=', $idca)->get();
  $aId= array();
  $c=0;
  foreach ($pgc as $i)
  {
    $aId[$c]=$i->idPersonGroup;
    $c++;
  }

 // $pPGroup2=DB::table('personGroup') ->where('idPersonCreator', '=', $user->id);
  //dd($aId);
?>

@section ('content')



{!! Form::model($personGroupCampaign, $form_data, array('role' => 'form')) !!}

  @include ('personGroupCampaign/errors', array('errors' => $errors))

  
 <p>
    <a href="{!! route('personGroup.create') !!}" class="btn btn-primary">Crear un nuevo grupo</a>
  </p>
  <h1>Lista grupos de operadores</h1>
  <h1>Mis grupos</h1>
  <table class="table table-striped">
    <!-- <tr>
        <th>Nombre del Grupo</th>
        <th>Cantidad de Miembros</th>
        <th>Creado por</th>
        <th>Fecha de Creación</th>
        <th> </th>
    </tr> -->
   

    @if($personGroup=='null')
       <tr>
          <td>No hay grupos creados</td>
        </tr>

      @else
        @if($pgc=='null')
          @foreach ($personGroup as $personGroup)
           
            <tr>
              <td>{!! Form::checkbox('idPersonGroup[]', $personGroup->id, false) !!} {{$personGroup->name}}</td>
              <td><?php
                $personPersonGroup = personPersonGroup::where('idPersonGroup', '=', $personGroup->id)->whereNull('deleted_at')->count();
                //error se trae los q tienen softdelete porq cuento la tabla intermedia ?>
              {{ $personPersonGroup }}</td>
              <?php
              $userc = user::where('id', '=', $personGroup->idPersonCreator)->first();
              ?>
              <td>{{$userc->firstName}} {{$userc->lastName}}</td>
              <td>{{$personGroup->created_at}}</td> 
               <td><a href="{!! route('personGroupShowGroup.show', $personGroup->id) !!}" class="btn btn-primary">Ver Grupo</a></td> 
            </tr>
          @endforeach
        @else
         @foreach ($personGroup as $pg)
             
             
                  <tr>
                    <td>{!! Form::checkbox('idPersonGroup[]', $pg->id, in_array($pg->id, $aId)) !!} {{$pg->name}}</td>
                    <td><?php
                $personPersonGroup = personPersonGroup::where('idPersonGroup', '=', $pg->id)->count();
                ?>
              {{ $personPersonGroup }}</td>
                    <?php
                    $userc = user::where('id', '=', $pg->idPersonCreator)->first();
                    ?>
                    <td>{{$userc->firstName}} {{$userc->lastName}}</td>
                    <td>{{$pg->created_at}}</td> 
                    <td><a href="{!! route('personGroupShowGroup.show', $pg->id) !!}" class="btn btn-primary">Ver Grupo</a></td> 
                  </tr>
             
             @endforeach
        @endif
        
       
    @endif

      <tr>
        <th>Nombre del Grupo de cuentas</th>
        <th>Cantidad de Miembros</th>
        <th>Creado por</th>
        <th>Fecha de Creación</th>
        <th> </th>
    </tr>
      <?php
        $user = Auth::user();
        $idAccountUSer=$user->idAccount;
        $idUser=$user->id;
        $url = Session::get('backUrl');
        $personGroupAccount = personGroup::where('idAccount', '=', $idAccountUSer)->get();
        //dd($personGroupAccount);
      ?>
@if($personGroupAccount=='null')
        <tr>
          <td>No hay grupos creados</td>
        </tr>

      @else
        
          @foreach ($personGroupAccount as $ppga)
            @if($ppga->idPersonCreator!=$idUSer)
              
                <tr>
                  <td>{!! Form::checkbox('idPersonGroup[]', $ppga->id, in_array($ppga->id, $aId)) !!}{{$ppga->name}}</td>
                  <td><?php
                $personpersonGroupCantidad = personpersonGroup::where('idPersonGroup', '=', $ppga->id)->count();
                ?>
              {{ $personpersonGroupCantidad }}</td>
                  <?php
                  $userc = user::where('id', '=', $ppga->idPersonCreator)->first();
                  ?>
                  <td>{{$userc->firstName}} {{$userc->lastName}}</td>
                  <td>{{$ppga->created_at}}</td> 
                   <td><a href="{!! route('personGroupShowGroup.show', $ppga->id) !!}" class="btn btn-primary">Ver Grupo</a></td> 
                </tr>
               
            @endif
          @endforeach
      
             
         
        
       
    @endif
  </table>
  {!! Form::button('Guardar', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}    
  {!! Form::button('Cancelar', array('type' => 'button', 'class' => 'btn btn-danger', 'onclick' => 'history.back()')) !!}
  
{!! Form::close() !!}
 </div>
@stop