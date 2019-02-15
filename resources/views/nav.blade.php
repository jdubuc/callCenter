	<?php use App\Account; ?>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
				@if (Auth::guest())
					<li><a href="{{ url('/auth/login') }}">Inicio</a></li>
				@else
					<?php
						
						$user= Auth::user();	
						?>
						<?php	if( $user->isSuperSuperAdmin($user) == true  ){ ?>
					<li><a href="{{ url('/home') }}">Inicio</a></li>
					<li><a href="{!! route('user.create') !!}" >crear Usuario</a></li>
					<li><a href="{!! route('account.create') !!}" >crear Cuenta</a></li>
					<li><a href="{!! route('account.index') !!}" >ver lista de cuentas</a></li>
					<li><a href="{!! route('user.index') !!}" >ver lista de usuarios</a></li>
					<li><a href="{!! route('messageSend.create') !!}" >ver lista de llamadas Zombi</a></li>

					<?php } ?>

					<?php	if($user->isSuperAdmin($user) == true ){ ?>
					<li><a href="{{ url('/home') }}">Inicio</a></li>
					<li><a href="{!! route('user.create') !!}" >crear Usuario</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Campaña
						<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="{{ url('/campaign/type', 'callCenter') }}">Crear Campaña Call Center</a></li>
							<li><a href="{{ url('/campaignEmail/create') }}">Crear Campaña Email</a></li>
							<li><a href="{{ url('/campaignSms/create') }}">Crear Campaña SMS</a></li>
							<li><a href="{{ url('/campaignEncuesta/create') }}">Crear Campaña Encuesta</a></li>
							<li><a href="{{ url('/home') }}">Ver campañas</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Operadores
						<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="{!! route('personGroupCampaign.index') !!}" >Ver Grupos de Operadores</a></li>
	   						<li><a href="{!! route('userOperator.index') !!}" >Ver lista de Operadores activos</a></li>
	   						<li><a href="{{ url('/deactivatedOperator') }}" >Ver lista de Operadores Desactivados</a></li>
						</ul>
					</li>
	   				<li><a href="{!! route('publicPersonGroupCampaign.index') !!}" >Ver Grupos de Destinatarios</a></li>
	   				<li><a href="{!! route('user.index') !!}" >ver lista de usuarios</a></li>
	   				
					<?php } ?>

				<?php	if( $user->isUser($user) == true ){?>
					<li><a href="{{ url('/home') }}">Inicio</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Crear Campaña
						<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="{{ url('/campaign/type', 'callCenter') }}">Call Center</a></li>
							<li><a href="{{ url('/campaignEmail/create') }}">Email</a></li>
							<li><a href="{{ url('/campaignSms/create') }}">SMS</a></li>
							<li><a href="{{ url('/campaignEncuesta/create') }}">Encuesta</a></li>
						</ul>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Operadores
						<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="{!! route('personGroupCampaign.index') !!}" >Ver Grupos de Operadores</a></li>
	   						<li><a href="{!! route('userOperator.index') !!}" >Ver lista de Operadores activos</a></li>
	   						<li><a href="{{ url('/deactivatedOperator') }}" >Ver lista de Operadores Desactivados</a></li>
						</ul>

					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true">Reporte
						<span class="caret"></span></a>
						<ul class="dropdown-menu" role="menu">
							<li><a href="{!! route('reporte.create') !!}">Resultado Campaña</a></li>
							<li><a href="{!! route('reporteDestinatario.create') !!}">Resultado Campaña Por Destinatario</a></li>
							<li><a href="{!! route('reporteOperador.create') !!}">Respuestas de Operadores por Campaña</a></li>
							<li><a href="{!! route('reporteTiempoOperador.create') !!}">Tiempo operador</a></li>

							
						</ul>
					</li>
					
	   				<li><a href="{!! route('publicPersonGroupCampaign.index') !!}" >Ver Grupos de Destinatarios</a></li>
					<?php }if( $user->isOperator($user) == true ){?>
					
					<li><a href="{{ url('/homeOperator') }}">Inicio</a></li>
					<?php } ?>
					
				@endif
				</ul>

				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}">Iniciar Sesión</a></li>
						<!-- <li><a href="{{ url('/auth/register') }}">registrar</a></li> -->
					@else
						<?php
					 $cuenta= account::find(Auth::user()->idAccount) ?>
					 @if($user->confirmed == false)
					 <li><a ><span class="helper" data-toggle="tooltip" title="Debio recibir un email para confirmar"> <font color="red">Email NO confirmado</font></span></a></li>
					 @endif

					 @if($cuenta !=null)
						<li><a >Cuenta de {{ $cuenta->name }}</a></li>
						@endif
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"> <?php	$user= Auth::user(); if( $user->isSuperSuperAdmin($user) == true  ){ ?>
						<img src="{{asset('/images/ssa.jpg')}}" height="25" width="25">
						<?php } ?>Usuario {{ Auth::user()->firstName }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('/auth/logout') }}">Salir de la sesión</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>