@extends('layouts.subPages')

@section('title', $title )

@section('content')
	<h1>Team</h1>
	<ul class="dotlist">
		<li>
			<p><a href="https://de.wikipedia.org/wiki/Wolfgang_Sander-Beuermann" target="_blank" rel="noopener">Sander-Beuermann, Wolfgang</a>, Dr.-Ing. - {!! trans('team.role.1') !!} -
			<a href="mailto:wsb@suma-ev.de">wsb@suma-ev.de</a> -
			<a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/team/pubkey-wsb") }}">Public Key</a></p>
		</li>
		<li>
			<p>Becker, Georg - {!! trans('team.role.2') !!} -
			<a href="mailto:georg.becker@suma-ev.de">georg.becker@suma-ev.de</a></p>
		</li>
		<li>
			<p>Branz, Manuela - {!! trans('team.role.3') !!} -
			<a href="mailto:manuela.branz@suma-ev.de">manuela.branz@suma-ev.de</a></p>
		</li>
		<li>
			<p>Pfennig, Dominik - {!! trans('team.role.4') !!} -
			<a href="mailto:dominik@suma-ev.de">dominik@suma-ev.de</a></p>
		</li>
		<li>
			<p>Höfer, Phil - {!! trans('team.role.5') !!} -
			<a href="mailto:phil@suma-ev.de">phil@suma-ev.de</a></p>
		</li>
		<li>
			<p>Hasselbring, Karl - {!! trans('team.role.6') !!} -
			<a href="mailto:karl@suma-ev.de">karl@suma-ev.de</a></p>
		</li>
		<li>
			<p>Riel, Carsten - {!! trans('team.role.7') !!} -
			<a href="carsten@suma-ev.de">carsten@suma-ev.de</a></p>
		</li>
	</ul>
	<p>{!! trans('team.contact.1') !!}</p>
	<p>{!! trans('team.contact.2') !!}</p>
@endsection
