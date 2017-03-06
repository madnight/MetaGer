@extends('layouts.subPages')

@section('title', $title )

@section('content')
	<h1>{{ trans('app.head.1') }}</h1>
	<p>{{ trans('app.disclaimer.1')}}</p>
	<h2>{{ trans('app.head.2') }}</h2>
	<p>{{ trans('app.metager.1') }}</p>
	<p>{{ trans('app.metager.2') }}</p>
	<p><a href="https://play.google.com/store/apps/details?id=de.metager.metagerapp">{{ trans('app.metager.playstore') }}</a></p>
	<p><a href="{!! url('app/metager') !!}">{{ trans('app.metager.manuell') }}</a></p>
	<h2>{{ trans('app.head.3') }}</h2>
	<p>{!! trans('app.maps.1') !!}</p>
	<p>{{ trans('app.maps.2') }}</p>
	<p>{{ trans('app.maps.3') }}</p>
	<ul>
		<li>{!! trans('app.maps.list.1') !!}</li>
		<li>{!! trans('app.maps.list.2') !!}</li>
	</ul>
	<p><a href="{!! url('app/maps') !!}">{{ trans('app.metager.manuell') }}</a></p>
	<h2>{{ trans('app.head.4') }}</h2>
	<p>{{ trans('app.installation.1') }}</p>
	<p>{{ trans('app.installation.2') }}</p>
	<ol>
		<li>{!! trans('app.list.1') !!}</li>
		<li>{!! trans('app.list.2') !!}</li>
		<li>{!! trans('app.list.3') !!}</li>
	</ol>
@endsection
