@extends('app')

<?php
use App\User;
use App\Account;
use App\Session;

        $form_data = array('route' => 'resetClave.store', 'method' => 'POST');
        $action    = 'Cambiar Contraseña';        
$user = new User;

?>


@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Cambiar Contraseña</div>
				<div class="panel-body">
					@if (count($errors) > 0)
						<div class="alert alert-danger">
							<strong>Whoops!</strong> Hubo un problema con los datos que introdujo.<br><br>
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
					<!-- {{ url('/password/reset') }} -->
					<!-- <form class="form-horizontal" role="form" method="POST" action="{!! route('resetClave.store') !!}"> -->
					{!! Form::model($user, $form_data, array('role' => 'form')) !!}
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="token" value="{{ $token }}">

						<div class="form-group">
							<label class="col-md-4 control-label">E-Mail</label>
							<div class="col-md-6">
								<input type="email" class="form-control" name="email" value="{{ old('email') }}">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Contraseña</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-4 control-label">Confirmar Contraseña</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password_confirmation">
							</div>
						</div>

						<!-- <div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
									Cambiar Contraseña
								</button>
							</div>
						</div>
					</form> -->
					  {!! Form::button('Cambiar Contraseña', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}    
  
						{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
