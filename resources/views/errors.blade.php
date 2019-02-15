@extends ('app')
  
@section ('content')
 @if ($errors->any())
    <div class="alert alert-danger">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <strong>Por favor, corrija los siguientes errores:</strong>
      <ul>
      @foreach ($errors->all() as $error)
        <li>{!! $error !!}</li>
      @endforeach
      </ul>
    </div>
    <?php
    if (Session::has('backUrl')) {
        Session::keep('backUrl');
      }
     $url = Session::get('backUrl');
               // return Redirect($url);
    ?>
      <a href="javascript:history.back(1)" class="btn btn-primary">Volver</a>
  @endif
@stop