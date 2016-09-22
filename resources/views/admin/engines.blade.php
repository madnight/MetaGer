@extends('layouts.subPages')

@section('title', $title )

@section('content')
<h1>Suchmaschinenübersicht</h1>
<p>Diese Übersicht gibt Aufschluss darüber, welche Suchmaschinen wie oft abgefragt wurden und zusätzlich wie oft diese innerhalb unseres Timeouts geantwortet haben</p>
<table class="table table-bordered">
	<caption>Daten der letzten 10 Minuten</caption>
	<thead>
		<tr>
			<th>Name</th>
			<th>Anzahl der gesamten Abfragen</th>
			<th>Davon tatsächlich beantwortet</th>
			<th>Prozent</th>
		</tr>
	</thead>
	<tbody>
		@foreach($engineStats["recent"] as $name => $values)
		@if($values["requests"] > 0)
		<tr @if($values["requests"] === $values["answered"]) class="success" @else class="danger" @endif>
			<td>{{$name}}</td>
			<td>{{$values["requests"]}}</td>
			<td>{{$values["answered"]}}</td>
			<td>{{ floor(($values["answered"] / $values["requests"]) * 100) }}%</td>
		</tr>
		@endif
		@endforeach
	</tbody>
</table>
<table class="table table-bordered">
	<caption>Daten insgesamt</caption>
	<thead>
		<tr>
			<th>Name</th>
			<th>Anzahl der gesamten Abfragen</th>
			<th>Davon tatsächlich beantwortet</th>
			<th>Prozent</th>
		</tr>
	</thead>
	<tbody>
		@foreach($engineStats["overall"] as $name => $values)
		@if($values["requests"] > 0)
		<tr @if($values["requests"] === $values["answered"]) class="success" @else class="danger" @endif>
			<td>{{$name}}</td>
			<td>{{$values["requests"]}}</td>
			<td>{{$values["answered"]}}</td>
			<td>{{ floor(($values["answered"] / $values["requests"]) * 100) }}%</td>
		</tr>
		@endif
		@endforeach
	</tbody>
</table>
@endsection
