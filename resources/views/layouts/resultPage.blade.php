<!DOCTYPE html>
<html>
	<head>
		<title>{{ $eingabe }} - MetaGer</title>
		<link href="/favicon.ico" rel="icon" type="image/x-icon" />
		<link href="/favicon.ico" rel="shortcut icon" type="image/x-icon" />
		<meta content="width=device-width, initial-scale=1.0, user-scalable=no" name="viewport" />
		<meta content="{{ getmypid() }}" name="p" />
		<meta content="{{ $eingabe }}" name="q" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE" />
		<meta http-equiv="language" content="{!! trans('staticPages.meta.language') !!}" />
		<meta HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE" />
		<link rel="search" type="application/opensearchdescription+xml" title="{!! trans('resultPage.opensearch') !!}" href="{{  LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), action('StartpageController@loadPlugin', ['params' => base64_encode(serialize(Request::all()))])) }}">
		<link type="text/css" rel="stylesheet" href="{{ elixir('css/themes/default.css') }}" />
		<link type="text/css" rel="stylesheet" href="/public/css/lightslider.css" />
		<link type="text/css" rel="stylesheet" href="/font-awesome/css/font-awesome.min.css" />
		<link id="theme" type="text/css" rel="stylesheet" href="/public/css/theme.css.php" />
	</head>
	<body id="resultBody">
		@if( !isset($suspendheader) )
			@include('layouts.researchandtabs')
		@else
			<div class="tab-content container-fluid">
				@yield('results')
			</div>
		@endif
		<footer>
			<div class="row">
				<div @if(LaravelLocalization::getCurrentLocale() === "de") class="col-xs-4"@else class="col-xs-6"@endif>
					<a class="btn btn-default" href="/">{!! trans('resultPage.startseite') !!}</a>
				</div>
				@if(LaravelLocalization::getCurrentLocale() === "de")
				<div class="col-xs-4">
					<a class="btn btn-default" href="https://metager.de/gutscheine/">Gutscheine</a>
				</div>
				@endif
				<div @if(LaravelLocalization::getCurrentLocale() === "de") class="col-xs-4"@else class="col-xs-6"@endif>
					<a class="btn btn-default" href="/impressum/">{!! trans('resultPage.impressum') !!}</a>
				</div>
			</div>
		</footer>
		<img src="{{ action('ImageController@generateImage')}}?site={{ urlencode(url()->current()) }}" class="hidden" />
		<script type="text/javascript" src="{{ elixir('js/lib.js') }}"></script>
		<script type="text/javascript" src="{{ elixir('js/scriptResultPage.js') }}"></script>
	</body>
</html>
