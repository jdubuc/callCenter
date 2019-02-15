@extends('app')

@section('content')
<?php
use App\User;
use App\PersonCampaign;
use App\Campaign;
	$id = Auth::user()->id;
	$currentuser = User::find($id);
	$user= Auth::user();
	$Campaign = new Campaign;
	$PersonCampaign=PersonCampaign::where('idPerson', '=', $id)->get();

	Session::flash('backUrl', Request::fullUrl());
	$url = Session::get('backUrl');

	
?>
<h2>Bienvenido Operador</h2>
<div class="container">
	<div class="row">
		<!--<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-default">
				<div class="panel-heading">Home</div>
				<div class="panel-body">
					You are logged in!
				</div>
			</div><a href="#" class="btn btn-info">Ver</a> <a href="#" class="btn btn-info">
		</div>

  <div class="container">-->
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
			//dd($Campaign);
			//{{ $Campaign->name }}
		?>
		 @if($Campaign->type=='Email')
	 	<li> <a href="{!! route('campaignEmail.show', $Campaign->id) !!}" style="text-decoration:none;">{{ $Campaign->name }} | {{ $Campaign->type }} | {{ $Campaign->dateTimeStart }} | {{ $Campaign->dateTimeEnd }} |
	 			@if($Campaign->active=='TRUE')
				Si </a></li>
			 	@else
			 	No </a></li>
			 	@endif
	 	@else
	 	<li> <a href="{!! route('campaign.show', $Campaign->id) !!}" style="text-decoration:none;">{{ $Campaign->name }} | {{ $Campaign->type }} | {{ $Campaign->dateTimeStart }} | {{ $Campaign->dateTimeEnd }} |
	 			@if($Campaign->active=='TRUE')
				Si </a></li>
			 	@else
			 	No </a></li>
			 	@endif
	 	@endif
	 @endforeach
	@endif
   
  </ol>
<!--</div>-->
 

	</div>
</div>



@endsection
