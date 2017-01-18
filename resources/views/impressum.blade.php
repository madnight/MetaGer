@extends('layouts.subPages')

@section('title', $title )

@section('content')
	<h1>{!! trans('impressum.title') !!}</h1>
	<h2 class="subheading">{!! trans('impressum.headline.1') !!}
	</h2>
	<p>{!! trans('impressum.info.1') !!}</p>
	<address>{!! trans('impressum.info.2') !!}</address>
	<address>{!! trans('impressum.info.3') !!}</address>
	<p>{!! trans('impressum.info.4') !!}</p>
	<p>{!! trans('impressum.info.5') !!}</p>
	<p>{!! trans('impressum.info.6') !!}</p>
	<p>{!! trans('impressum.info.7') !!}</p>
	<p>{!! trans('impressum.info.8') !!}</p>
	<h2>{!! trans('impressum.info.9') !!}</h2>
	<p>{!! trans('impressum.info.10') !!}</p>
@endsection
