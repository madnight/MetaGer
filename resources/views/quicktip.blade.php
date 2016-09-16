<html>
	<head>
		<title>{!! trans('quicktip.title') !!}</title>
		<link rel="stylesheet" type="text/css" href="/css/bootstrap.css" />
		<link rel="stylesheet" type="text/css" href="/css/quicktips.css" />
	</head>
	<body>
		<div class="mg-panel container" style="margin-bottom:20px;max-height:90px;text-align:left; max-width:100%; padding:0px;margin-top:0px">
			<a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/spendenaufruf") }}" target="_blank">
				<img src="/img/danke.png" style="max-width:100%;max-height:90px;" alt="Spendenaufruf für die unabhängige, nicht-kommerzielle Internet-Suche" >
			</a>
		</div>
		@if( $spruch !== "" )
			<blockquote id="spruch">{!! $spruch !!}</blockquote>
		@endif
		@foreach( $mqs as $mq)
			<div class="quicktip">
				<b class="qtheader"><a href="{{ $mq['URL'] }}" target="_blank">{{ $mq['title'] }}</a></b><br>
				<div>{!! $mq['descr'] !!}</div>
				@if( isset($mq['gefVon']) )
					<div class="pull-right">{!! $mq['gefVon'] !!}</div>
				@endif
			</div>
		@endforeach
	</body>
</html>
