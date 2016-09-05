@extends('layouts.subPages')

@section('title', $title )

@section('navbarFocus.donate', 'class="dropdown active"')

@section('content')
<h1>{{ trans('spenden.danke.title') }}</h1>
<div class="col">
	<div id="left" class="col-lg-6 col-md-12 col-sm-12 others">
		<p style="width:100%;" class="text-muted">{{ trans('spenden.danke.nachricht') }}</p>
		<h2>{{ trans('spenden.danke.kontrolle') }}</h2>
		<div>
			<table class="table table-condensed">
			  <tr>
			  	<td>{{ trans('spenden.lastschrift.3.placeholder')}}</td>
			  	<td>{{ $data['name'] }}</td>
			  </tr>
			  <tr>
			  	<td>{{ trans('spenden.telefonnummer') }}</td>
			  	<td>{{ $data['telefon'] }}</td>
			  </tr>
			  <tr>
			  	<td>Email</td>
			  	<td>{{ $data['email'] }}</td>
			  </tr>
			  <tr>
			  	<td>{{ trans('spenden.iban') }}</td>
			  	<td>{{ $data['kontonummer'] }}</td>
			  </tr>
			  <tr>
			  	<td>{{ trans('spenden.bic') }}</td>
			  	<td>{{ $data['bankleitzahl'] }}</td>
			  </tr>
			  <tr>
			  	<td>{{ trans('spenden.danke.message') }}</td>
			  	<td>{{ $data['nachricht'] }}</td>
			  </tr>
			</table>
		</div>
		<button type="button" class="btn btn-primary noprint" onclick="window.print();">Drucken</button>
	</div>
	<div class="col-lg-6 col-md-12 col-sm-12 others noprint" id="right">
		<h2>{{ trans('spenden.about.0') }}</h2>
		<p>{{ trans('spenden.about.1.1') }}
			<a href="https://metager.de/klassik/bform1.htm" target="_blank">{{ trans('spenden.about.1.2') }}</a></p>
		<p>{{ trans('spenden.about.2.1') }} <a href="http://suma-ev.de/" target="_blank">SUMA-EV</a> {{ trans('spenden.about.2.2') }} <a href="http://suma-ev.de/suma-links/index.html#sponsors" target="_blank">{{ trans('spenden.about.2.3') }}</a> {{ trans('spenden.about.2.4') }} <a href="https://metager.de/klassik/spenden1.html" target="_blank">{{ trans('spenden.about.2.5') }}</a></p>
		<p><a href="http://suma-ev.de/unterstuetzung/index.html" target="_blank">{{ trans('spenden.about.3.1') }}</a> {{ trans('spenden.about.3.2') }} <a href="https://metager.de/" target="_blank">MetaGer.de!</a></p>
		<p>{{ trans('spenden.about.4.1') }} <a href="https://www.boost-project.com/de" target="_blank">www.boost-project.com</a> {{ trans('spenden.about.4.2') }} <a href="https://metager.de/" target="_blank">MetaGer.de!</a> {{ trans('spenden.about.4.3') }} <a href="https://www.boost-project.com/de/shops?charity_id=1129&amp;tag=bl" target="_blank">{{ trans('spenden.about.4.4') }}</a> {{ trans('spenden.about.4.5') }}</p>
	</div>
	<div class="clearfix"></div>
</div>
@endsection
