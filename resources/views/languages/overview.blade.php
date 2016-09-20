@extends('layouts.subPages')

@section('title', $title )

@section('content')
@if(!$deComplete)
<p>Hinweis: Es sind nicht alle Texte für die Referenzsprache(deutsch) vorhanden. Das Bearbeiten der Sprachdateien ist auf die deutsche begrenzt, bis diese komplett ist.</p>
@endif
<table class="table">
	<thead>
	<tr>
		<th>Sprachkürzel</th>
		<th>Status</th>
		<th>Aktion</th>
	</tr>
	</thead>
	<tbody>
		@foreach($langTexts as $lang => $values)
			<tr @if(floor(($values['textCount'] / count($sum)) * 100) < 100) class="danger" @else class="success" @endif>
				<td>{{$lang}}</td>
				<td>{{ $values['textCount'] . "/" . count($sum)}} Texten übersetzt. ({{ floor(($values['textCount'] / count($sum)) * 100) }} %)</td>
				<td><a href="
				@if( $lang === "de" && floor(($values['textCount'] / count($sum)) * 100) < 100)
				{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), url("/languages/edit", ['from'=>'all', 'to'=>'de'])) }}
				@elseif($lang !== "de" && floor(($values['textCount'] / count($sum)) * 100) < 100)
				{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), url("/languages/edit", ['from'=>'de', 'to'=>$lang])) }}
				@else
				#
				@endif " class="btn btn-default @if((!$deComplete && $lang !== "de") || floor(($values['textCount'] / count($sum)) * 100) >= 100) disabled @endif">Texte für "{{ $lang }}" ergänzen</a></td>
			</tr>
		@endforeach
	</tbody>
</table>

@endsection
