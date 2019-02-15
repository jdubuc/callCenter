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
	$Campaign = new Campaign;
	$PersonCampaign=PersonCampaign::where('idPerson', '=', $id)->get();

	Session::flash('backUrl', Request::fullUrl());
	$mSend=DB::table('MessageSend') ->where('idCampaign', '=', $Campaign->id)->get();
	$url = Session::get('backUrl');	
?>

<button type="button" class="btn btn-info" data-toggle="collapse" data-target="#demo">Filtrar campaña</button>
<div id="demo" class="collapse">
    {!! Form::model($Campaign, $form_data, array('role' => 'form')) !!}
    @include ('campaign/errors', array('errors' => $errors))
	<div class="container">
		<div class="row">
	      <div class="form-group col-md-3">
	        {!! Form::label('start', 'Fecha desde') !!}
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
				$idc=$PersonCampaign->idCampaign;
					$Campaign=Campaign::where('id', '=', $idc)->first();
				?>
				 @if($Campaign->type=='Email')
			 	<li> {{ $Campaign->name }} | {{ $Campaign->type }} | {{ $Campaign->dateTimeStart }} | {{ $Campaign->dateTimeEnd }} |
		 			@if($Campaign->active=='TRUE')
						Si <a href="{{ url('/campaign/desactivar', $Campaign->id) }}" class="btn btn-info btn-xs">Desactivar</a>
				 	@else
					 	<span class="helper" data-toggle="tooltip" title="No se puede editar ya que se realizaron llamadas"> No <a href="{!! route('campaign.edit', $Campaign->id) !!}" class="btn btn-info btn-xs"
				 			<?php
					 			if(count($mSend) > 0) {
					 				echo ' disabled="true" title="No se puede editar ya que se realizaron llamadas"';
					 			} else {
					 				echo ' title="No se han realizado llamadas todavía"';;
					 			}
				 			?>
				 		>Editar</a>
						</span>
				 		<a href="{{ url('/campaign/activar', $Campaign->id) }}" class="btn btn-info btn-xs"
				 			<?php
					 			if(count($mSend) > 0) {
					 				echo ' disabled="true" title="No se puede editar ya que se realizaron llamadas"';
					 			}
				 			?>
				 			>Activar</a>
				 	@endif
				 	<a href="{!! route('campaignEmail.show', $Campaign->id) !!}" class="btn btn-info btn-xs"
				 			<?php
					 			if(count($mSend) > 0) {
					 				echo ' disabled="true" title="No se puede editar ya que se realizaron llamadas"';
					 			}
				 			?>
				 		>Configuraci&oacute;n</a>
				</li>
			 	@else
			 	<li> {{ $Campaign->name }} | {{ $Campaign->type }} | {{ $Campaign->dateTimeStart }} | {{ $Campaign->dateTimeEnd }} | 
		 			@if($Campaign->active=='TRUE')
						Si <a href="{{ url('/campaign/desactivar', $Campaign->id) }}" class="btn btn-info btn-xs">Desactivar</a>
				 	@else
				 		<span class="helper" data-toggle="tooltip" title="No se puede editar ya que se realizaron llamadas"> No <a href="{!! route('campaign.edit', $Campaign->id) !!}" class="btn btn-info btn-xs"

				 		<?php
				 			if(count($mSend) > 0) {
				 				echo ' disabled="true" title="No se puede editar ya que se realizaron llamadas"';
				 			} else {
				 				echo ' title="No se han realizado llamadas todavía"';;
				 			}
				 			?>
				 			>Editar</a>
				 		</span>
				 		<a href="{{ url('/campaign/activar', $Campaign->id) }}" class="btn btn-info btn-xs"
				 			<?php
					 			if(count($mSend) > 0) {
					 				echo ' disabled="true"';
					 			}
				 			?>
				 			>Activar</a>
				 	@endif
				 	<a href="{!! route('campaign.show', $Campaign->id) !!}" class="btn btn-info btn-xs"
			 			<?php
				 			if(count($mSend) > 0) {
				 				echo ' disabled="true"';
				 			}
			 			?>
				 		>Configuraci&oacute;n</a></li>
			 	@endif
			 @endforeach
			@endif
	  	</ol>
	</div>
</div>
<script type="text/javascript">
	$('.helper').tooltip({'placement': 'bottom'})
</script>
 <script>
  $( function() {
    $( "#accordion" ).accordion({
      collapsible: true
    });
  } );
  </script>
@endsection
