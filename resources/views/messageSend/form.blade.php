@extends('app')

<?php
use App\MessageSend;
use App\Campaign;
        $form_data = array('route' => 'messageSend.store', 'method' => 'POST');     
        //$messageSendZombi= messageSend::where('hang','=',null)->where('answer','=',null)->where('dateTimeEnd','=',null)->where('duration','=',null)->where('durationDialing','=',null)->get();
        $messageSendZombi= messageSend::whereNull('hang')->whereNull('answer')->whereNull('dateTimeEnd')->whereNull('duration')->whereNull('durationDialing')->paginate(15);
        //$messageSendZombi = DB::table('MessageSend')->whereNull('answer')->get();
        //$messageSendZombi=DB::select(DB::raw('select * from "zombieCall"()'));
        //$messageSendZombi= messageSend::all();
        //dd($messageSendZombi);
?>

@section ('content')

<h1>Revisar llamadas sin finalizar</h1>

{!! Form::model($messageSend, $form_data, array('role' => 'form')) !!}

  @include ('messageSend/errors', array('errors' => $errors))
  <input type="checkbox" onclick="checkAll(this)"><b>Check Todos</b>
  <table class="table table-striped">
    <tr>
        <th></th>
        <th>Fecha de Creación</th>
        <th>Campaña</th>
        <th></th>
        <th> </th>
    </tr>
    @foreach ($messageSendZombi as $mSendZombi)
            <tr>
              <td>{!! Form::checkbox('idmSendZombi[]', $mSendZombi->id, false) !!} {{$mSendZombi->name}}</td>
              <td>{{ $mSendZombi->created_at }}</td>
              <?php
                $campaign = campaign::find($mSendZombi->idCampaign);
              ?>
              <td>{{$campaign->name}}</td>
            </tr>
    @endforeach
  </table>
  <?php echo $messageSendZombi->render(); ?>

  {!! Form::button('Eliminar ', array('type' => 'submit', 'class' => 'btn btn-primary')) !!}    
  {!! Form::close() !!}
  </div>
<script type="text/javascript">
  function checkAll(bx) {
  var cbs = document.getElementsByTagName('input');
  for(var i=0; i < cbs.length; i++) {
    if(cbs[i].type == 'checkbox') {
      cbs[i].checked = bx.checked;
    }
  }
}
</script>
@stop