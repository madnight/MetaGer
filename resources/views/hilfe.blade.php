@extends('layouts.subPages')

@section('title', $title )

@section('content')
<div class="alert alert-warning" role="alert">{!! trans('hilfe.achtung') !!}</div>
<h1>{!! trans('hilfe.title') !!}</h1>
<h2>{!! trans('hilfe.einstellungen') !!}</h2>
<h3>{!! trans('hilfe.allgemein.title') !!}</h3>
<ul>
	<li>{!! trans('hilfe.allgemein.1') !!}</li>
	<li>{!! trans('hilfe.allgemein.2') !!}</li>
	<li>{!! trans('hilfe.allgemein.3') !!}</li>
</ul>
<h3>{!! trans('hilfe.suchfokus.title') !!}</h3>
<p>{!! trans('hilfe.suchfokus.1') !!}</p>
<h2>{!! trans('hilfe.sucheingabe') !!}</h2>
<h3>{!! trans('hilfe.stopworte.title') !!}</h3>
<p>{!! trans('hilfe.stopworte.1') !!}</p>
<ul>
	<li>{!! trans('hilfe.stopworte.2') !!}</li>
</ul>
<h3>{!! trans('hilfe.mehrwortsuche.title') !!}</h3>
<p>{!! trans('hilfe.mehrwortsuche.1') !!}</p>
<ul>
	<li>{!! trans('hilfe.mehrwortsuche.2') !!}</li>
</ul>
<h3>{!! trans('hilfe.grossklein.title') !!}</h3>
<p>{!! trans('hilfe.grossklein.1') !!}</p>
<ul>
	<li>{!! trans('hilfe.grossklein.2') !!}</li>
</ul>
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
