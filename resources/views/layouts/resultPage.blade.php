<!DOCTYPE html>
<html>
	<head>
		<title>{{ $eingabe }} - MetaGer</title>
		<link href="/favicon.ico" rel="icon" type="image/x-icon" />
		<link href="/favicon.ico" rel="shortcut icon" type="image/x-icon" />
		<meta content="width=device-width, initial-scale=1.0, user-scalable=no" name="viewport" />
		<meta name="p" content="{{ getmypid() }}" />
		<meta name="q" content="{{ $eingabe }}" />
		<meta name="l" content="{{ LaravelLocalization::getCurrentLocale() }}" />
		<meta name="mm" content="{{ $metager->getVerificationId() }}" />
		<meta name="mn" content="{{ $metager->getVerificationCount() }}" />
		<meta name="d" content="{!! $metager->getId() !!}" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE" />
		<meta http-equiv="language" content="{!! trans('staticPages.meta.language') !!}" />
		<meta HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE" />
		<link rel="search" type="application/opensearchdescription+xml" title="{!! trans('resultPage.opensearch') !!}" href="{{  LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), action('StartpageController@loadPlugin', ['params' => base64_encode(serialize(Request::all()))])) }}">
		<link type="text/css" rel="stylesheet" href="{{ mix('css/themes/default.css') }}" />
		<link id="theme" type="text/css" rel="stylesheet" href="/css/theme.css.php" />
		<meta name="referrer" content="origin">
		@include('layouts.utility')
		<!-- Matomo -->
		<script type="text/javascript">
		var _paq = _paq || [];
		/* tracker methods like "setCustomDimension" should be called before "trackPageView" */
		_paq.push(["setDocumentTitle", document.domain + "/" + document.title]);
		_paq.push(["setCookieDomain", "*.metager.de"]);
		_paq.push(["disableCookies"]);
		_paq.push(['trackPageView']);
		_paq.push(['enableLinkTracking']);
		(function() {
			var u="//piwik.metager3.de/";
			_paq.push(['setTrackerUrl', u+'piwik.php']);
			_paq.push(['setSiteId', '1']);
			var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
			g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
		})();
		</script>
		<noscript><p><img src="//piwik.metager3.de/piwik.php?idsite=1&amp;rec=1&amp;url={{ url()->full() }}&amp;action_name={{ $eingabe }} - MetaGer&amp;rand={{ rand(0,1000000) }}" style="border:0;" alt="" /></p></noscript>
		<!-- End Matomo Code -->
	</head>
	<body id="resultBody">
		@if( !isset($suspendheader) )
			@include('layouts.researchandtabs')
		@else
			<div class="tab-content container-fluid">
				@yield('results')
			</div>
		@endif
		<div id="feedback" style="width:50%;margin-left:25%;position: relative; top:10px;" class="alert alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  				<strong> {!! trans('metaGer.feedback') !!}<a href="{{URL::to('')}}/kontakt/{{base64_encode(Request::fullUrl())}}/" target="_blank">
  					{!! trans('kontakt.form.1') !!}</a>
  				</strong>
		</div>
		<footer>
			<div class="row">
				<div @if(LaravelLocalization::getCurrentLocale() === "de") class="col-xs-4"@else class="col-xs-6"@endif>
					<a class="btn btn-default" href="/">{!! trans('resultPage.startseite') !!}</a>
				</div>
				<div @if(LaravelLocalization::getCurrentLocale() === "de") class="col-xs-4"@else class="col-xs-6"@endif>
					<a class="btn btn-default" href="/impressum/">{!! trans('resultPage.impressum') !!}</a>
				</div>
			</div>
		</footer>
		<script type="text/javascript" src="{{ mix('js/lib.js') }}"></script>
		<script type="text/javascript" src="{{ mix('js/scriptResultPage.js') }}"></script>
	</body>
</html>
