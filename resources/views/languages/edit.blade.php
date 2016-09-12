@extends('layouts.subPages')

@section('title', $title )

@section('content')
<h1>{{$filename}}</h1>
<table class="table">
	<thead>
	<tr>
		<th>#ID</th>
		<th>{{$to}}
		@foreach($langs as $lang => $value)
		<th>{{$lang}}</th>
		@endforeach
	</tr>
	</thead>
	<tbody>
		@foreach($texts as $name => $langValues)
		<tr>
			<td>{{$name}}</td>
			<td>{!! $langValues[$to] or '<input type="text" size="50"/>'!!}</td>
			@foreach($langs as $lang => $value)
			<td>{{ $langValues[$lang] or "" }}</td>
			@endforeach
		</tr>
		@endforeach
	</tbody>
</table>
@endsection
