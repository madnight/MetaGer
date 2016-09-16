@extends('layouts.subPages')

@section('title', $title )

@section('content')
<h1>MetaGer - Übersetzungen</h1>
<p>Vielen Dank, dass du erwägst MetaGer bei der Übersetzung seiner Texte zu unterstützen. Dir wird im unteren Bereich eine Datei und tabellarisch die dazugehörigen Texte angezeigt. Das Feld "#ID" dient dabei nur der Orientierung und ist für die Übersetzung unwichtig.
In der nächsten Spalte findest du entweder Texte der Sprache für die uns einige Übersetzungen fehlen, oder aber ein Textfeld. Wird hier für eine Reihe ein Textfeld angezeigt, so fehlen uns die Texte in der angegebenen Sprache.</p>
<p>Du kannst uns unterstützen, indem du dir die Referenztexte in den folgenden Spalten (rechts daneben) ansiehst und nach Möglichkeit eine Übersetzung in der gesuchten Sprache in das Textfeld einträgst.</p>
<div class="progress">
  <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="{{ round(100 * (($langTexts[$to]+$new) / count($sum))) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ round(100 * (($langTexts[$to]+$new) / count($sum))) }}%">
  {{ ($langTexts[$to]+$new) . "/" . count($sum) . " Texten übersetzt (" . round(100 * (($langTexts[$to]+$new) / count($sum))) . "%)"}}
  </div>
</div>
<h1>{{$filename}}</h1>
<form id="submit" method="POST">
<input type="hidden" name="filename" value="{{$filename}}" />
</form>
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
		@if($langValues === "")
		<tr>
		<td class="name">{{preg_replace("/(\s*).*#(.*)$/si", "$1$2", $name)}}</td>
		<td></td>
		<td></td>
		</tr>
		@else
		<tr>
			<td class="name">{{preg_replace("/(\s*).*#(.*)$/si", "$1$2", $name)}}</td>
			<td>@if(isset($langValues[$to])) <input type="text" size="50" form="submit" name="{{$name}}" value="{{$langValues[$to]}}" readonly /> @else <input type="text" size="50" form="submit" name="_new_{{$name}}" /> @endif</td>
			@foreach($langs as $lang => $value)
			<td>{!! $langValues[$lang] or "" !!}</td>
			@endforeach
		</tr>
		@endif

		@endforeach
	</tbody>
</table>
<p>Sobald du mit deinen Texten zufrieden bist, kannst du uns diese mit einem Klick auf folgenden Knopf automatisch zusenden. Wenn es mehr fehlende Texte in der angegebenen Sprache gibt, wird dein Browser dich danach direkt zu diesen leiten.</p>
<p><b>Hinweis</b>: Die übermittelten Texte werden von diesem Tool erst erkannt, sobald diese von uns gesichtet und eingefügt wurden. Wenn du deine Arbeit sichern möchtest um diese zu einem späteren Zeitpunkt fortzusetzen (auch wenn wir deine bisherige Arbeit noch nicht übernehmen konnten), so reicht es vollkommen, den aktuellen Link aus deiner Browserleiste zu kopieren und zu einem späteren Zeitpunkt wieder aufzurufen.</p>
<button class="btn btn-success" type="submit" form="submit">Daten übermitteln</button>
@endsection
