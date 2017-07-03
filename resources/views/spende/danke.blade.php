@extends('layouts.subPages')

@section('title', $title )

@section('navbarFocus.donate', 'class="dropdown active"')

@section('content')
	<h1>{{ trans('spende.danke.title') }}</h1>
	<div class="col">
		<div id="left" class="col-lg-6 col-md-12 col-sm-12 others">
			<p style="width:100%;" class="text-muted">{{ trans('spende.danke.nachricht') }}</p>
			<h2>{{ trans('spende.danke.kontrolle') }}</h2>
			<div>
				<table class="table table-condensed">
					<tr>
						<td>{{ trans('spende.lastschrift.3.placeholder')}}</td>
						<td>{{ $data['name'] }}</td>
					</tr>
					<tr>
						<td>{{ trans('spende.telefonnummer') }}</td>
						<td>{{ $data['telefon'] }}</td>
					</tr>
					<tr>
						<td>Email</td>
						<td>{{ $data['email'] }}</td>
					</tr>
					<tr>
						<td>{{ trans('spende.iban') }}</td>
						<td>{{ $data['kontonummer'] }}</td>
					</tr>
					<tr>
						<td>{{ trans('spende.bic') }}</td>
						<td>{{ $data['bankleitzahl'] }}</td>
					</tr>
					<tr>
						<td>{{ trans('spende.danke.message') }}</td>
						<td>{{ $data['nachricht'] }}</td>
					</tr>
				</table>
			</div>
			<button type="button" class="btn btn-primary noprint" onclick="window.print();">{{ trans('spende.drucken') }}</button>
		</div>
		<div class="col-lg-6 col-md-12 col-sm-12 others noprint" id="right">
			<h2>{{ trans('spende.about.1') }}</h2>
			<p>{!! trans('spende.about.2') !!}</p>
			<p>{!! trans('spende.about.3') !!}</p>
			<p>{!! trans('spende.about.4') !!}</p>
			<p>{!! trans('spende.about.5') !!}</p>
		</div>
		<div class="clearfix"></div>
	</div>
@endsection
