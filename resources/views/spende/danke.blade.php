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
			<button type="button" class="btn btn-primary noprint" onclick="window.print();">{{ trans('spenden.drucken') }}</button>
		</div>
		<div class="col-lg-6 col-md-12 col-sm-12 others noprint" id="right">
			<h2>{{ trans('spenden.about.1') }}</h2>
			<p>{!! trans('spenden.about.2') !!}</p>
			<p>{!! trans('spenden.about.3') !!}</p>
			<p>{!! trans('spenden.about.4') !!}</p>
			<p>{!! trans('spenden.about.5') !!}</p>
		</div>
		<div class="clearfix"></div>
	</div>
@endsection
