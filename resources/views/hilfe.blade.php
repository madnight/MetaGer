@extends('layouts.subPages')

@section('title', $title )

@section('content')
<div class="alert alert-warning" role="alert">{!! trans('hilfe.achtung') !!}</div>
<h1>{!! trans('hilfe.title') !!}</h1>
<h2>{!! trans('hilfe.einstellungen') !!}</h2>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">{!! trans('hilfe.allgemein.title') !!}</h3>
	</div>
	<div class="panel-body">
		<ul class="dotlist">
			<li>{!! trans('hilfe.allgemein.1') !!}</li>
			<li>{!! trans('hilfe.allgemein.2') !!}</li>
			<li>{!! trans('hilfe.allgemein.3') !!}</li>
		</ul>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">{!! trans('hilfe.suchfokus.title') !!}</h3>
	</div>
	<div class="panel-body">
		<p>{!! trans('hilfe.suchfokus.1') !!}</p>
	</div>
</div>
<h2>{!! trans('hilfe.sucheingabe') !!}</h2>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">{!! trans('hilfe.stopworte.title') !!}</h3>
	</div>
	<div class="panel-body">
		<p>{!! trans('hilfe.stopworte.1') !!}</p>
		<ul class="dotlist">
			<li>{!! trans('hilfe.stopworte.2') !!}</li>
		</ul>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">{!! trans('hilfe.mehrwortsuche.title') !!}</h3>
	</div>
	<div class="panel-body">
		<p>{!! trans('hilfe.mehrwortsuche.1') !!}</p>
		<ul class="dotlist">
			<li>{!! trans('hilfe.mehrwortsuche.2') !!}</li>
		</ul>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">{!! trans('hilfe.grossklein.title') !!}</h3>
	</div>
	<div class="panel-body">
		<p>{!! trans('hilfe.grossklein.1') !!}</p>
		<ul class="dotlist">
			<li>{!! trans('hilfe.grossklein.2') !!}</li>
		</ul>
	</div>
</div>
<h2 id="dienste">{!! trans('hilfe.dienste') !!}</h2>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 id="mgapp" class="panel-title">{!! trans('hilfe.app.title') !!}</h3>
	</div>
	<div class="panel-body">
		<p>{!! trans('hilfe.app.1') !!}</p>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">{!! trans('hilfe.suchwortassoziator.title') !!}</h3>
	</div>
	<div class="panel-body">
		<p>{!! trans('hilfe.suchwortassoziator.1') !!}</p>
		<p>{!! trans('hilfe.suchwortassoziator.2') !!}</p>
		<p>{!! trans('hilfe.suchwortassoziator.3') !!}</p>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">{!! trans('hilfe.widget.title') !!}</h3>
	</div>
	<div class="panel-body">
		<p>{!! trans('hilfe.widget.1') !!}</p>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">{!! trans('hilfe.urlshort.title') !!}</h3>
	</div>
	<div class="panel-body">
		<p>{!! trans('hilfe.urlshort.1') !!}</p>
	</div>
</div>
<h3>=> {!! trans('hilfe.dienste.kostenlos') !!}</h3>
<h2>{!! trans('hilfe.datenschutz.title') !!}</h2>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">{!! trans('hilfe.datenschutz.1') !!}</h3>
	</div>
	<div class="panel-body">
		<p>{!! trans('hilfe.datenschutz.2') !!}</p>
		<p>{!! trans('hilfe.datenschutz.3') !!}</p>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">{!! trans('hilfe.tor.title') !!}</h3>
	</div>
	<div class="panel-body">
		<p>{!! trans('hilfe.tor.1') !!}</p>
		<p>{!! trans('hilfe.tor.2') !!}</p>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">{!! trans('hilfe.proxy.title') !!}</h3>
	</div>
	<div class="panel-body">
		<p>{!! trans('hilfe.proxy.1') !!}</p>
	</div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">{!! trans('hilfe.infobutton.title') !!}</h3>
	</div>
	<div class="panel-body">
		<p>{!! trans('hilfe.infobutton.1') !!}</p>
	</div>
</div>
@endsection
