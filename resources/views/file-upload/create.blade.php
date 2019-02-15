@extends('app')

@section('content')
	@if($errors->any())
		<ul class="alert alert-danger">
			@foreach($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	@endif

	{!! Form::open(['url' => 'file-upload', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}

	<div class="form-group">
		{!! Form::label('Select a file to upload', 'Select a file to upload', ['class' => 'control-label']) !!}
		{!! Form::file('file', ['class' => 'form-control']) !!}
	</div>

	<div class="form-group">
		{!! Form::submit('Upload', ['class' => 'form-control']) !!}
	</div>

	{!! Form::close() !!}
@endsection