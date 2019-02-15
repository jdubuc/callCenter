@extends('app')
@section('content')
<?php
use App\User;
use App\PersonCampaign;
use App\Campaign;
if (Auth::check()==true) {
    // The user is logged in...
    $id = Auth::user()->id;
  $currentuser = User::find($id);
  $user= Auth::user();
}
else
{
  return Redirect::to('/auth/login');
}
  $form_data = array('route' => 'campaignFiltrar.store', 'method' => 'POST');
  $CampaignForm = new Campaign;
  //$PersonCampaign=PersonCampaign::where('idPerson', '=', $id)->get();

  Session::flash('backUrl', Request::fullUrl());
  $url = Session::get('backUrl'); 
?>

<button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo">Filtrar campaña</button>
<div id="demo" class="collapse">
    {!! Form::model($CampaignForm, $form_data, array('role' => 'form')) !!}
    @include ('campaign/errors', array('errors' => $errors))
  <div class="container">
    <div class="row">
        <div class="form-group col-md-3">
          {!! Form::label('start',  'Fecha desde') !!}
          <input type="date" name="start" class="form-control">
        </div>
        <div class="form-group col-md-3">
          {!! Form::label('end', 'Fecha hasta') !!}
          <input type="date" name="end" class="form-control">       
        </div>
        <div class="form-group col-md-2">
           {!! Form::label('mensaje', 'Palabras en el mensaje de campaña') !!}
          {!! Form::text('mensaje', null, array('placeholder' => 'Nombre', 'class' => 'form-control')) !!}        
        </div>
         <div class="form-group col-md-2">
         {!! Form::label('mensaje', 'Estado de campaña') !!}
          <select id="tipo" class="form-control campaignSelect"  name="estado">
            <option selected="selected"  label="selecciona un estado para filtrar" value="">selecciona un estado para filtrar</option>
              <option value="false">Inactiva</option>
              <option value="true">Activa</option>
          </select>      
        </div>
        <div class="form-group col-md-2">
         {!! Form::label('mensaje', 'Tipo de campaña') !!}
          <select id="tipo" class="form-control campaignSelect"  name="tipo">
            <option selected="selected"  label="selecciona un estado para filtrar" value="">selecciona tipo</option>
              <option value="CallCenter">CallCenter</option>
              <option value="Sms">Sms</option>
              <option value="Email">Email</option>
          </select>      
        </div>
      </div>
    {!! Form::button('Filtrar campaña', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}    
    {!! Form::close() !!}
  </div>
</div>


<div class="container">
  <div class="row">
    <h2>Campañas de {{ $user->firstName }} {{ $user->lastName }}</h2><br>
    <h2>Nombre - Tipo - Fecha inicio - Fecha Final - Activa</h2>
      <ol class="ordered-list">
      @if($PersonCampaign=='null')
        <li> No hay campaña</li>
      @else
       @foreach ($PersonCampaign as $PersonCampaign)
        <?php
          // $idc=$PersonCampaign->idCampaign;
          //$Campaign=Campaign::where('id', '=', $PersonCampaign->id)->first();
          $mSend=DB::table('MessageSend') ->where('idCampaign', '=', $PersonCampaign->idCampaign)->get();
        ?>
         @if($PersonCampaign->type=='Email')
        <li> <a href="{!! route('campaignEmail.show', $PersonCampaign->id) !!}" style="text-decoration:none;">{{ $PersonCampaign->name }} | {{ $PersonCampaign->type }} | {{ $PersonCampaign->dateTimeStart }} | {{ $PersonCampaign->dateTimeEnd }} |
            @if($PersonCampaign->active=='true')
              Si </a><a href="{{ url('/campaign/desactivar', $PersonCampaign->id) }}" class="btn btn-info btn-xs">Desactivar</a></li>
            @else
            <span class="helper" data-toggle="tooltip" title="No se puede editar ya que se realizaron llamadas"> No </a><a href="{!! route('campaign.edit', $PersonCampaign->id) !!}" class="btn btn-info btn-xs"
              <?php
                if($mSend!=null)
                {
                  echo'disabled';
                }
                ?> >Editar</a>
                </span>
          
              <a href="{{ url('/campaign/activar', $PersonCampaign->id) }}" class="btn btn-info btn-xs">Activar</a></li>
            @endif
        @else
        <li> <a href="{!! route('campaign.show', $PersonCampaign->id) !!}" style="text-decoration:none;">{{ $PersonCampaign->name }} | {{ $PersonCampaign->type }} | {{ $PersonCampaign->dateTimeStart }} | {{ $PersonCampaign->dateTimeEnd }} | 
            @if($PersonCampaign->active=='true')
              Si </a><a href="{{ url('/campaign/desactivar', $PersonCampaign->id) }}" class="btn btn-info btn-xs">Desactivar</a></li>
            @else
              <span class="helper" data-toggle="tooltip" title="No se puede editar ya que se realizaron llamadas"> No </a><a href="{!! route('campaign.edit', $PersonCampaign->id) !!}" class="btn btn-info btn-xs"

              <?php
                if($mSend!=null)
                {
                  echo'disabled title="No se puede editar ya que se realizaron llamadas"';
                }
                ?>
                >Editar</a>   </span><a href="{{ url('/campaign/activar', $PersonCampaign->id) }}" class="btn btn-info btn-xs">Activar</a></li>
            @endif
        @endif

       @endforeach
      @endif
      </ol>
  </div>
</div>
<script type="text/javascript">
  $('.helper').tooltip({'placement': 'bottom'})
</script>
@endsection
