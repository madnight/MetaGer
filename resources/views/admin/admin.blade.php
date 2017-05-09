@extends('layouts.subPages')

@section('title', $title )

@section('content')
<table class="table table-bordered">
	<thead>
		<th>Name</th>
		<th>Status</th>
		<th>Fetcher Anzahl</th>
		<th>Abfragedauer</th>
		<th>Abfragelast</th>
	</thead>
	<tbody>
		@foreach($stati as $engineName => $engineStats)
		<tr>
			<td>{{$engineName}}</td>
			<td>{{$engineStats["status"]}}</td>
			<td>{{sizeof($engineStats["fetcher"])}}</td>
			<td>{{$engineStats["median-connection"]["total_time"]}}s</td>
			<td>{{$engineStats["median-poptime"]}}</td>
		</tr>
		@endforeach
	</tbody>
</table>
@endsection