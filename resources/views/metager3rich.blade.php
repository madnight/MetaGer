<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<title>{{ $eingabe }} - MetaGer</title>
		<link rel="stylesheet" title="Material" href="/css/material-default.css" />
		<link rel="stylesheet" title="Material-Invers" href="/css/material-inverse.css" />
		<link href="/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
		<link href="/favicon.ico" rel="icon" type="image/x-icon" />
		<link href="/favicon.ico" rel="shortcut icon" type="image/x-icon" />
		<meta content="width=device-width, initial-scale=1.0, user-scalable=no" name="viewport" />
		<meta HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE" />
		<link rel="search" type="application/opensearchdescription+xml" title="{!! trans('resultPage.opensearch') !!}" href="{{  LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), action('StartpageController@loadPlugin', ['params' => base64_encode(serialize(Request::all()))])) }}">
	</head>
	<body>
	<header class="persistent-search">
			<form class="search-card card elevation-2">
				<a href="/" class="back">
					<img src="/img/Logo-square-inverted.svg" alt="MetaGer" title="MetaGer, die sichere Suchmaschine" />
				</a>
				<input type="text" name="eingabe" placeholder="MetaGer-Suche" value="{{ $eingabe }}" class="query-input"/>
				<button type="submit" class="search-button fa"></button>
				@foreach( $metager->request->all() as $key => $value)
					@if($key !== "eingabe" && $key !== "page" && $key !== "next")
						<input type='hidden' name='{{ $key }}' value='{{ $value }}' form='submitForm' />
					@endif
				@endforeach
			</form>
			</header>
			<details class="focus-card card elevation-1">

			@if( $metager->getFokus() === "web" )
				<summary class="focus-cell"><div class="focus-cell-label"><span class="icon fa" aria-hidden="true"></span> <b>Web</b></div></summary>
			@endif
			</details>
		<main class="results-container">
		@foreach($metager->getResults() as $result)
			@if($result->number % 7 === 0)
				@include('layouts.ad', ['ad' => $metager->popAd()])
			@endif
			<article class="search-result card elevation-1">
				<div class="result-content">
                    <h1 class="result-title">{{ $result->titel }}</h1>
                    <h2 class="result-display-link">{{ $result->anzeigeLink }}</h2>
                    <p class="result-description">{{ $result->descr }}</p>
                    <p class="result-source">gefunden von {!! $result->gefVon !!}</p>
					@if( isset($result->logo) )
                    	<img class="result-thumbnail" src="{{ $metager->getImageProxyLink($result->logo) }}" alt="" />
					@endif
				</div>
				<div class="result-action-area">
                    <a class="result-action primary" href="{{ $result->link }}">Öffnen</a>
                    <a class="result-action primary" target="_blank" href="{{ $result->link }}">Neuer Tab</a>
                    <a class="result-action" target="_blank" href="{{ $result->proxyLink }}">Anonym Öffnen</a>
				</div>
			</article>
		@endforeach
		</main>
		@if($metager->getPage() === 1)
		<nav class="pagenav-first">
			<a class="pagenav-button-next card elevation-1" href="{{ $metager->nextSearchLink() }}"><span class="card-button-text">Weitersuchen</span><span class="icon-right">►</span></a>
		</nav>
		@else
		<nav class="pagenav-following">
			<div>
			<a class="pagenav-button-first card-inline elevation-1" href="javascript:history.back()">◄</a>
			</div>
			<div class="pagenav-current"><span class="pagenav-current-annotation">Seite </span>{{ $metager->getPage() }}</div>
			<a class="pagenav-button-next card-inline elevation-1" href="{{ $metager->nextSearchLink() }}"><span class="card-button-text">Weitersuchen</span><span class="icon-right">►</span></a>
		</nav>
		@endif
		<footer class="footer-text">
		<a href="https://metager.de/impressum" target="_blank">Impressum</a>
		</footer>
		<img src="{{ action('ImageController@generateImage')}}?site={{ urlencode(url()->current()) }}" class="hidden" />
	</body>
</html>
