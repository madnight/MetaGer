@extends('layouts.subPages')

@section('title', $title )

@section('content')
<div class="alert alert-warning" role="alert">{!! trans('hilfe.achtung') !!}</div>
<h1>{!! trans('hilfe.title') !!}</h1>
<h2>{!! trans('hilfe.einstellungen') !!}</h2>
<h3>{!! trans('hilfe.allgemein.title') !!}</h3>
<ul class="dotlist">
	<li>{!! trans('hilfe.allgemein.1') !!}</li>
	<li>{!! trans('hilfe.allgemein.2') !!}</li>
	<li>{!! trans('hilfe.allgemein.3') !!}</li>
</ul>
<h3>{!! trans('hilfe.suchfokus.title') !!}</h3>
<p>{!! trans('hilfe.suchfokus.1') !!}</p>
<h2>{!! trans('hilfe.sucheingabe') !!}</h2>
<h3>{!! trans('hilfe.stopworte.title') !!}</h3>
<p>{!! trans('hilfe.stopworte.1') !!}</p>
<ul class="dotlist">
	<li>{!! trans('hilfe.stopworte.2') !!}</li>
</ul>
<h3>{!! trans('hilfe.mehrwortsuche.title') !!}</h3>
<p>{!! trans('hilfe.mehrwortsuche.1') !!}</p>
<ul class="dotlist">
	<li>{!! trans('hilfe.mehrwortsuche.2') !!}</li>
</ul>
<h3>{!! trans('hilfe.grossklein.title') !!}</h3>
<p>{!! trans('hilfe.grossklein.1') !!}</p>
<ul class="dotlist">
	<li>{!! trans('hilfe.grossklein.2') !!}</li>
</ul>
<h3>{!! trans('hilfe.domains.title') !!}</h3>
<p>{!! trans('hilfe.domains.hint') !!}</p>
<p>{!! trans('hilfe.domains.sitesearch.explanation') !!}</p>
<ul class="dotlist">
	<li>{!! trans('hilfe.domains.sitesearch.example.1') !!}
	<div class="well well-sm">{!! trans('hilfe.domains.sitesearch.example.2') !!}</div></li>
	<li>{!! trans('hilfe.domains.sitesearch.example.3') !!}
	<div class="well well-sm">{!! trans('hilfe.domains.sitesearch.example.4') !!}</div></li>
</ul>
<p>{!! trans('hilfe.domains.blacklist.explanation') !!}</p>
<ul class="dotlist">
	<li>{!! trans('hilfe.domains.blacklist.example.1') !!}</li>
	<li>{!! trans('hilfe.domains.blacklist.example.2') !!}
	<div class="well well-sm">{!! trans('hilfe.domains.blacklist.example.3') !!}</div>
	{!! trans('hilfe.domains.blacklist.example.4') !!}</li>
	<li>{!! trans('hilfe.domains.blacklist.example.5') !!}
	<div class="well well-sm">{!! trans('hilfe.domains.blacklist.example.6') !!}</div></li>
</ul>
<p>Zusätzlich bieten wir Ihnen die Möglichkeit Hosts beziehungsweise Domains direkt auf der Ergebnisseite auszuschließen. Bei jedem unserer Ergebnisse erscheint dieses kleine Symbol für die Optionen: </p>
<img src="/img/blacklist-tutorial-searchexample.png">
<p>Wenn Sie auf dieses Symbol klicken, erscheint folgendes Menü: <p>
<div class="media">
	<div class="media-left">
		<img src="/img/blacklist-tutorial-options.png">
	</div>
	<div class="media-body">
		<p>Mit diesem Menü lassen sich:</p>
		<ul class="dotlist">
			<li>Eine neue Suche nur auf der Domain des Ergebnisses (de.wikipedia.de) starten</li>
			<li>Eine Suche ohne Ergebnisse der Subdomain (de.wikipedia.de) starten</li>
			<li>Eine Suche ohne Ergebnisse der Top-Level-Domain (wikipedia.de) starten</li>
		</ul>
	</div>
</div>
<h2>{!! trans('hilfe.dienste') !!}</h2>
<h3>{!! trans('hilfe.suchwortassoziator.title') !!}</h3>
<p>{!! trans('hilfe.suchwortassoziator.1') !!}</p>
<p>{!! trans('hilfe.suchwortassoziator.2') !!}</p>
<p>{!! trans('hilfe.suchwortassoziator.3') !!}</p>
<h3>{!! trans('hilfe.widget.title') !!}</h3>
<p>{!! trans('hilfe.widget.1') !!}</p>
<h3>{!! trans('hilfe.urlshort.title') !!}</h3>
<p>{!! trans('hilfe.urlshort.1') !!}</p>
<h3>=> {!! trans('hilfe.dienste.kostenlos') !!}</h3>
<h2>{!! trans('hilfe.datenschutz.title') !!}</h2>
<h3>{!! trans('hilfe.datenschutz.1') !!}</h3>
<p>{!! trans('hilfe.datenschutz.2') !!}</p>
<p>{!! trans('hilfe.datenschutz.3') !!}</p>
<h3>{!! trans('hilfe.tor.title') !!}</h3>
<p>{!! trans('hilfe.tor.1') !!}</p>
<p>{!! trans('hilfe.tor.2') !!}</p>
<h3>{!! trans('hilfe.proxy.title') !!}</h3>
<p>{!! trans('hilfe.proxy.1') !!}</p>
@endsection
