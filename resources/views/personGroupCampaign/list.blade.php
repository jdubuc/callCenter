@extends ('app')

@section ('title') Lista de grupos de operadores @stop
<?php
    use App\User;
    use App\PersonCampaign;
    use App\Campaign;
    use App\PersonGroup;
    use App\PersonPersonGroup;
    use App\PersonGroupCampaign;
    $action    = 'Lista';        
    $user = Auth::user();
    $idUSer=$user->id;
    //Session::flash('backUrl', Request::fullUrl());
  $url = Session::get('backUrl');
  $personGroup = personGroup::where('idPersonCreator', '=', $idUSer)->get();
 // $pPGroup2=DB::table('personGroup') ->where('idPersonCreator', '=', $user->id);
//dd($personGroup);
?>

@section ('content')

 <p>
    <a href="{!! route('personGroup.create') !!}" class="btn btn-primary">Crear un nuevo grupo</a>
  </p>
  <h1>Lista de grupos de operadores</h1>
  <h1>Mis grupos</h1>
  <table class="table table-striped">
    <tr>
        <th>Nombre del Grupo</th>
        <th>Cantidad de Miembros</th>
        <th>Creado por</th>
        <th>Fecha de Creación</th>
        <th> </th>
    </tr>
   

    @if($personGroup=='null')
       <tr>
          <td>No hay grupos creados</td>
        </tr>

      @else
      
          @foreach ($personGroup as $personGroup)
           
            <tr>
              <td>{{$personGroup->name}}</td>
              <td><?php
                $personPersonGroup = personPersonGroup::where('idPersonGroup', '=', $personGroup->id)->count();
                ?>
              {{ $personPersonGroup }}</td>
              <?php
              $userc = user::where('id', '=', $personGroup->idPersonCreator)->first();
              ?>
              <td>{{$userc->firstName}} {{$userc->lastName}}</td>
              <td>{{$personGroup->created_at}}</td> 
               <td><a href="{!! route('personGroupShowGroup.show', $personGroup->id) !!}" class="btn btn-primary">Ver Grupo</a></td> 
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
        $personGroupAccount = personGroup::where('idAccount', '=', $idAccountUSer)->get();
        //dd($personGroupAccount);
      ?>
@if($personGroupAccount=='null')
        <tr>
          <td>No hay grupos creados</td>
        </tr>

      @else
        
          @foreach ($personGroupAccount as $personGroupAccount)
            @if($personGroupAccount->idPersonCreator!=$idUSer)
            <tr>
              <td>{{$personGroupAccount->name}}</td>
              <td><?php
                $personPersonGroup = personPersonGroup::where('idPersonGroup', '=', $personGroupAccount->id)->count();
                ?>
              {{ $personPersonGroup }}</td>
              <?php
              $userc = user::where('id', '=', $personGroupAccount->idPersonCreator)->first();
              ?>
              <td>{{$userc->firstName}} {{$userc->lastName}}</td>
              <td>{{$personGroupAccount->created_at}}</td> 
               <td><a href="{!! route('personGroupShowGroup.show', $personGroupAccount->id) !!}" class="btn btn-primary">Ver Grupo</a></td> 
            </tr>
            @endif
          @endforeach
      
             
         
        
       
    @endif
  </table>
    <input type="buttom" onclick="history.back()" name="atras" value="Atr&aacute;s" class="btn btn-primary">

 </div>
@stop
