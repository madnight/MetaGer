@extends('layouts.subPages')

@section('title', $title )

@section('navbarFocus.datenschutz', 'class="active"')

@section('content')
	<h1>{!! trans('datenschutz.head') !!}</h1>
	<p>{!! trans('datenschutz.general.1') !!}</p>
	<p>{!! trans('datenschutz.general.3') !!}</p>
	<h2>{!! trans('datenschutz.policy.1') !!}</h2>
	<ul class="dotlist">
		<li>{!! trans('datenschutz.policy.2') !!}</li>
		<li>{!! trans('datenschutz.policy.5') !!}</li>
		<li>{!! trans('datenschutz.policy.6') !!}</li>
		<li>{!! trans('datenschutz.policy.7') !!}</li>
		<li>{!! trans('datenschutz.policy.9') !!}</li>
		<li>{!! trans('datenschutz.policy.10') !!}</li>
		<li>{!! trans('datenschutz.policy.13') !!}</li>
		<li>{!! trans('datenschutz.policy.17') !!}</li>
		<li>{!! trans('datenschutz.policy.18') !!}</li>
		<li>{!! trans('datenschutz.policy.19') !!}</li>
	</ul>
		<h2>{!! trans('datenschutz.twitter') !!}</h2>
	<pre><p>&gt; 7.4.2014 C. Schulzki-Haddouti @kooptech
	&gt; MetaGer dürfte im Moment die sicherste Suchmaschine weltweit sein</p>
	<p>&gt; 8.4.2014 Stiftung Datenschutz @DS_Stiftung
	&gt; Wenn das Suchergebnis anonym bleiben soll: @MetaGer, die gemeinnützige
	&gt; Suchmaschine aus #Hannover</p>
	<p>&gt; 8.4.2014 Markus Käkenmeister @markus2009
	&gt; Suchmaschine ohne Tracking</p>
	<p>&gt; 8.4.2014 Marko [~sHaKaL~] @mobilef0rensics 
	&gt; Nice; anonymous Search and find with MetaGer</p>
	<p>&gt; 7.4.2014 Anfahrer @anfahrer
	&gt; Websuche mit #Datenschutz dank #MetaGer : Anonyme Suche und
	&gt; Ergebnisse via Proxy</p>
	<p>&gt; 8.4.2014 stupidit&eacute; pue @dummheitstinkt
	&gt; wow, is this the MetaGer I used in the end 90s in internet cafes???
	&gt; "Anonymes Suchen und Finden mit MetaGer | heise"</p></pre>

@endsection