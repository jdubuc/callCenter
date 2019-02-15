@extends('app')

<?php
    use App\User;
    use App\PersonCampaign;
    use App\Campaign;
    use App\PublicPersonPublicPersonGroup;
    use App\PublicPersonGroup;
    use App\PublicPersonGroupCampaign;
   //$publicPersonGroupCampaign->active = '0';
   //$publicPersonGroupCampaign->type = 'CallCenter';
    if ($publicPersonGroupCampaign->exists):
        $form_data = array('route' => array('publicPersonGroupCampaign.update', $publicPersonGroupCampaign->id), 'method' => 'PATCH');
        $action    = 'Editar';
         //$publicPersonGroupCampaign->idPersonModificator = Auth::user()->id;
    else:
        $form_data = array('route' => 'publicPersonGroupCampaign.store', 'method' => 'POST');
        $action    = 'Crear';     
       // $publicPersonGroupCampaign->idPersonModificator = Auth::user()->id;
       // $publicPersonGroupCampaign->idPersonCreator = Auth::user()->id; 
       // $publicPersonGroupCampaign->type='CallCenter';
    endif;
    
    
    //dd($idca);
    $user = Auth::user();
    $idUSer=$user->id;
    //Session::flash('backUrl', Request::fullUrl());
  $url = Session::get('backUrl');
  $publicPersonGroup = PublicPersonGroup::where('idPersonCreator', '=', $idUSer)->get();
  $idca=$publicPersonGroupCampaign->idCampaign;
  $ppgc = publicPersonGroupCampaign::where('idCampaign', '=', $idca)->get();
  
  $aId= array();
  $c=0;
  foreach ($ppgc as $i)
  {
    $aId[$c]=$i->idPublicPersonGroup;
    $c++;
  }
 // $pPGroup2=DB::table('publicPersonGroup') ->where('idPersonCreator', '=', $user->id);
  //dd($publicPersonGroup);
?>

@section ('content')



{!! Form::model($publicPersonGroupCampaign, $form_data, array('role' => 'form')) !!}

  @include ('publicPersonGroupCampaign/errors', array('errors' => $errors))
  
 <p>
    <a href="{!! route('publicPersonGroup.create') !!}" class="btn btn-primary">Crear un nuevo grupo</a>
  </p>
  <h1>Lista de grupos de destinatarios</h1>
  <h1>Mis grupos</h1>
  <table class="table table-striped">
    <tr>
        <th>Nombre del Grupo</th>
        <th>Cantidad de Miembros</th>
        <th>Creado por</th>
        <th>Fecha de Creación</th>
        <th> </th>
    </tr>
   
    @if($publicPersonGroup=='null')
       <tr>
          <td>No hay grupos creados</td>
        </tr>

      @else
        @if($ppgc=='null')
          @foreach ($publicPersonGroup as $publicPersonGroup)
           
            <tr>
              <td>{!! Form::checkbox('idPublicPersonGroup[]', $publicPersonGroup->id, false) !!} {{$publicPersonGroup->name}}</td>
              <td><?php
                $publicPersonPublicPersonGroupCantidad = publicPersonPublicPersonGroup::where('idPersonGroup', '=', $publicPersonGroup->id)->count();
                ?>
              {{ $publicPersonPublicPersonGroupCantidad }}</td>
              <?php
              $userc = user::where('id', '=', $publicPersonGroup->idPersonCreator)->first();
              ?>
              <td>{{$userc->firstName}} {{$userc->lastName}}</td>
              <td>{{$publicPersonGroup->created_at}}</td> 
               <td><a href="{!! route('publicPersonGroupShowGroup.show', $publicPersonGroup->id) !!}" class="btn btn-primary">Ver Grupo</a></td> 
            </tr>
          @endforeach
        @else
             
              @foreach ($publicPersonGroup as $ppg)
               
                  <tr>
                    <td>{!! Form::checkbox('idPublicPersonGroup[]', $ppg->id, in_array($ppg->id, $aId)) !!} {{$ppg->name}}</td>
                    <td><?php
                $publicPersonPublicPersonGroupCantidad = publicPersonPublicPersonGroup::where('idPublicPersonGroup', '=', $ppg->id)->count();
                ?>
              {{ $publicPersonPublicPersonGroupCantidad }}</td>
                    <?php
                    $userc = user::where('id', '=', $ppg->idPersonCreator)->first();
                    ?>
                    <td>{{$userc->firstName}} {{$userc->lastName}}</td>
                    <td>{{$ppg->created_at}}</td> 
                    <td><a href="{!! route('publicPersonGroupShowGroup.show', $ppg->id) !!}" class="btn btn-primary">Ver Grupo</a></td> 
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
        $publicPersonGroupAccount = PublicPersonGroup::where('idAccount', '=', $idAccountUSer)->get();
        //dd($publicPersonGroupAccount);
      ?>
@if($publicPersonGroupAccount=='null')
        <tr>
          <td>No hay grupos creados</td>
        </tr>

      @else
        
          @foreach ($publicPersonGroupAccount as $ppga)
            @if($ppga->idPersonCreator!=$idUSer)
              
                <tr>
                  <td>{!! Form::checkbox('idPublicPersonGroup[]', $ppga->id, in_array($ppga->id, $aId)) !!}{{$ppga->name}}</td>
                  <td><?php
                $publicPersonPublicPersonGroupCantidad = publicPersonPublicPersonGroup::where('idPublicPersonGroup', '=', $ppga->id)->count();
                ?>
              {{ $publicPersonPublicPersonGroupCantidad }}</td>
                  <?php
                  $userc = user::where('id', '=', $ppga->idPersonCreator)->first();
                  ?>
                  <td>{{$userc->firstName}} {{$userc->lastName}}</td>
                  <td>{{$ppga->created_at}}</td> 
                   <td><a href="{!! route('publicPersonGroupShowGroup.show', $ppga->id) !!}" class="btn btn-primary">Ver Grupo</a></td> 
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