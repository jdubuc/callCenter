@extends ('user/layout4')
@section ('title') calendario @stop
	{{ HTML::style('assets/images/cupertino/jquery-ui.min.css', array('media' => 'screen')) }}
	{{ HTML::style('assets/js/fullcalendar/fullcalendar.css', array('media' => 'screen')) }}
	{{ HTML::style('assets/js/fullcalendar/fullcalendar.print.css', array('media' => 'screen')) }}
	{{ HTML::script('assets/images/cupertino/jquery.min.js') }}
	{{ HTML::script('assets/images/cupertino/jquery-ui.custom.min.js') }}
	{{ HTML::script('assets/js/fullcalendar/fullcalendar.js') }}
	<?php
	$id = Auth::user()->id;
	$currentuser = User::find($id);
	$user= Auth::user();
	$cita= Cita::where('User', '=', $id)->orderBy('fecha_cita')->get();
	?>
<script>
	$(document).ready(function() {
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
		$('#calendar').fullCalendar({
			theme: true,
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			editable: true,
			events: [
			//comienza
				@foreach( $cita  as $cita )
					<?php
					$pacientecita = paciente::find($cita->paciente);
					$orderdate = explode('-', $cita->fecha_cita);
					$year = $orderdate[0];
					$month   = $orderdate[1];
					$day  = $orderdate[2];
					//$hora=date("h:i:s:a");
					$horarioemp=explode(" ", $cita->hora);
					$horaex=explode(":", $cita->hora);
					$timee=$horaex['0'];
					$timeh=$horarioemp['1'];
					if($timeh=='pm'){$timee=$timee+12;}
					?>
				{
					title: '<?php echo "$pacientecita->nombre";  echo "$pacientecita->apellido "; echo "$cita->hora ";?>',
					start: new Date( <?php echo "$year"; ?>, <?php echo "$month-1"; ?>, <?php echo "$day"; ?>,<?php echo "$timee"; ?>),
					end: new Date(<?php echo "$year"; ?>, <?php echo "$month-1"; ?>, <?php echo "$day"; ?>),
					allDay: false,
					url: '<?php echo "/directoriomedico/public/user/citas/"; echo "$cita->id";?>'
				},									     
				@endforeach
			//termina 
			{
					title: 'Click for Google',
					url: 'http://google.com/'
				}
			]
		});
	});
</script>
<style>
	body {
		margin-top: 40px;
		text-align: center;
		font-size: 13px;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		}
	#calendar {
		width: 900px;
		margin: 0 auto;
		}
</style>
@section ('content')
<a href="/directoriomedico/public/" class="btn btn-primary">Volver</a>
<div id='calendar'></div>
@stop