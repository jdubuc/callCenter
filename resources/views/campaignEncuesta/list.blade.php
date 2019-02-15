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
  
  $Campaign = new Campaign;
  $PersonCampaign=PersonCampaign::where('idPerson', '=', $id)->get();
  
  Session::flash('backUrl', Request::fullUrl());
  $mSend=DB::table('MessageSend') ->where('idCampaign', '=', $Campaign->id)->get();
  $url = Session::get('backUrl');
   $form_data = array('route' => 'reporte.store', 'method' => 'POST'); 
?>
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
        $idc=$PersonCampaign->idCampaign;
          $Campaign=Campaign::where('id', '=', $idc)->first();
        ?>
         @if($Campaign->type=='Email')
        <li> <a href="{!! route('campaignEmail.show', $Campaign->id) !!}" style="text-decoration:none;">{{ $Campaign->name }} | {{ $Campaign->type }} | {{ $Campaign->dateTimeStart }} | {{ $Campaign->dateTimeEnd }} |
            @if($Campaign->active=='TRUE')
              Si </a><a href="{{ url('/campaign/desactivar', $Campaign->id) }}" class="btn btn-info btn-xs">Desactivar</a></li>
            @else
            <span class="helper" data-toggle="tooltip" title="No se puede editar ya que se realizaron llamadas"> No </a><a href="{!! route('campaign.edit', $Campaign->id) !!}" class="btn btn-info btn-xs"
              <?php
                if($mSend!=null)
                {
                  echo'disabled';
                }
                ?> >Editar</a>
                </span>
          
              <a href="{{ url('/campaign/activar', $Campaign->id) }}" class="btn btn-info btn-xs">Activar</a></li>
            @endif
        @else
        <li> <a href="{!! route('campaign.show', $Campaign->id) !!}" style="text-decoration:none;">{{ $Campaign->name }} | {{ $Campaign->type }} | {{ $Campaign->dateTimeStart }} | {{ $Campaign->dateTimeEnd }} | 
            @if($Campaign->active=='TRUE')
              Si </a><a href="{{ url('/campaign/desactivar', $Campaign->id) }}" class="btn btn-info btn-xs">Desactivar</a></li>
            @else
              <span class="helper" data-toggle="tooltip" title="No se puede editar ya que se realizaron llamadas"> No </a><a href="{!! route('campaign.edit', $Campaign->id) !!}" class="btn btn-info btn-xs"

              <?php
                if($mSend!=null)
                {
                  echo'disabled title="No se puede editar ya que se realizaron llamadas"';
                }
                ?>
                >Editar</a>   </span><a href="{{ url('/campaign/activar', $Campaign->id) }}" class="btn btn-info btn-xs">Activar</a></li>
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
