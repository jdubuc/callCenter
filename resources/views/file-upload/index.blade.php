@extends('app')

@section('content')
	@if( count($files) > 0)
		<h3>Your files:</h3>
		<ul>
		@foreach($files as $file)
			<li>
				<span>{!! link_to('file-view/'.$file['id'], $file['name']) !!}</span>
				<span> on {{ $file['created_at'] }}</span>
			</li>
		@endforeach
		</ul>
	@else
		<h3>You haven't uploaded any files!</h3>
	@endif
@endsection