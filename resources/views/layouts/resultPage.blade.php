<!DOCTYPE html>
<html>
<head>
	<title>{{ $metager->getQ() }} - MetaGer</title>
	<!--<link href="/css/bootstrap.css" rel="stylesheet" />-->
	@if( isset($mobile) && $mobile )
		<link href="/css/styleResultPageMobile.css" rel="stylesheet" />
	@endif
	<link href="/favicon.ico" rel="icon" type="image/x-icon" />
	<link href="/favicon.ico" rel="shortcut icon" type="image/x-icon" />
	<meta content="width=device-width, initial-scale=1.0, user-scalable=no" name="viewport" />
	<meta content="{{ getmypid() }}" name="p" />
	<meta content="{{ $eingabe }}" name="q" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="search" type="application/opensearchdescription+xml" title="{!! trans('resultPage.opensearch') !!}" href="{{  LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), action('StartpageController@loadPlugin', ['params' => base64_encode(serialize(Request::all()))])) }}">
	<link type="text/css" rel="stylesheet" href="/css/themes/{{ app('request')->input('theme', 'default') }}.css" />
	<link type="text/css" rel="stylesheet" href="/css/lightslider.css" />
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
			<div class="col-xs-6">
				<a class="btn btn-default" href="/">{!! trans('resultPage.startseite') !!}</a>
			</div>
			<div class="col-xs-6">
				<a class="btn btn-default" href="/impressum/">{!! trans('resultPage.impressum') !!}</a>
			</div>
		</div>
	</footer>
	<img src="{{ action('ImageController@generateImage')}}?site={{ urlencode(url()->current()) }}" class="hidden" />
	<script src="/js/jquery.js" type="text/javascript"></script>
	<script src="/js/bootstrap.js" type="text/javascript"></script>
	<script src="/js/masonry.js" type="text/javascript"></script>
	<script src="/js/imagesloaded.js" type="text/javascript"></script>
	<script src="/js/scriptResultPage.js" type="text/javascript"></script>
	<script src="/js/lightslider.js"></script>
	<!--[if lte IE 8]><script type="text/javascript" src="/js/html5shiv.min.js"></script><![endif]-->
</body>
</html>
