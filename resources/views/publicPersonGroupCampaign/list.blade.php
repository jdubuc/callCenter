@extends ('app')

@section ('title') Lista de grupos de destinatarios @stop
<?php
    use App\User;
    use App\PersonCampaign;
    use App\Campaign;
    use App\PublicPersonGroup;
    use App\PublicPersonPublicPersonGroup;
    use App\PublicPersonGroupCampaign;
    $action    = 'Lista';        
    $user = Auth::user();
    $idUSer=$user->id;
    //Session::flash('backUrl', Request::fullUrl());
  $url = Session::get('backUrl');
  $publicPersonGroup = PublicPersonGroup::where('idPersonCreator', '=', $idUSer)->get();
 // $pPGroup2=DB::table('publicPersonGroup') ->where('idPersonCreator', '=', $user->id);
//dd($publicPersonGroup);
?>

@section ('content')

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
      
          @foreach ($publicPersonGroup as $publicPersonGroup)
           
            <tr>
              <td>{{$publicPersonGroup->name}}</td>
              <td><?php
                $publicPersonPublicPersonGroupCantidad = publicPersonPublicPersonGroup::where('idPublicPersonGroup', '=', $publicPersonGroup->id)->count();
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
        
          @foreach ($publicPersonGroupAccount as $publicPersonGroupAccount)
            @if($publicPersonGroupAccount->idPersonCreator!=$idUSer)
            <tr>
              <td>{{$publicPersonGroupAccount->name}}</td>
              <td><?php
                $publicPersonPublicPersonGroupCantidad = publicPersonPublicPersonGroup::where('idPublicPersonGroup', '=', $publicPersonGroupAccount->id)->count();
                ?>
              {{ $publicPersonPublicPersonGroupCantidad }}</td>
              <?php
              $userc = user::where('id', '=', $publicPersonGroupAccount->idPersonCreator)->first();
              ?>
              <td>{{$userc->firstName}} {{$userc->lastName}}</td>
              <td>{{$publicPersonGroupAccount->created_at}}</td> 
               <td><a href="{!! route('publicPersonGroupShowGroup.show', $publicPersonGroupAccount->id) !!}" class="btn btn-primary">Ver Grupo</a></td> 
            </tr>
            @endif
          @endforeach
             
    @endif
  </table>
 </div>
@stop
