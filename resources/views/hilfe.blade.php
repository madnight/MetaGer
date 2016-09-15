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
<h2>{!! trans('faq.dienste') !!}</h2>
<h3>{!! trans('faq.suchwortassoziator.title') !!}</h3>
<p>{!! trans('faq.suchwortassoziator.1') !!}</p>
<p>{!! trans('faq.suchwortassoziator.2') !!}</p>
<p>{!! trans('faq.suchwortassoziator.3') !!}</p>
<h3>{!! trans('faq.widget.title') !!}</h3>
<p>{!! trans('faq.widget.1') !!}</p>
<h3>{!! trans('faq.urlshort.title') !!}</h3>
<p>{!! trans('faq.urlshort.1') !!}</p>
<h3>=> {!! trans('faq.dienste.kostenlos') !!}</h3>
<h2>{!! trans('faq.datenschutz.title') !!}</h2>
<h3>{!! trans('faq.datenschutz.1') !!}</h3>
<p>{!! trans('faq.datenschutz.2') !!}</p>
<p>{!! trans('faq.datenschutz.3') !!}</p>
<h3>{!! trans('faq.tor.title') !!}</h3>
<p>{!! trans('faq.tor.1') !!}</p>
<p>{!! trans('faq.tor.2') !!}</p>
<h3>{!! trans('faq.proxy.title') !!}</h3>
<p>{!! trans('faq.proxy.1') !!}</p>
@endsection
