@extends('layouts.subPages')

@section('title', $title )

@section('content')
	<h1>{{ trans('about.head.1') }}</h1>
	<h2>{{ trans('about.head.3') }}</h2>
	{!! trans('about.3.0') !!}
	<ul class="dotlist">
		<li>{!! trans('about.3.1') !!}</li>
		<li>{!! trans('about.3.2') !!}</li>
		<li>{!! trans('about.3.3') !!}</li>
		<li>{!! trans('about.3.4') !!}</li>
		<li>{!! trans('about.3.5') !!}</li>
		<li>{!! trans('about.3.6') !!}</li>
		<li>{!! trans('about.3.7') !!}</li>
	</ul>
	<h2>{{ trans('about.head.2') }}</h2>
	<ul class="dotlist">
		<li>{!! trans('about.list.1') !!}</li>
		<li>{!! trans('about.list.2') !!}</li>
		<li>{!! trans('about.list.3') !!}</li>
		<li>{!! trans('about.list.4') !!}</li>
		<li>{!! trans('about.list.5') !!}</li>
		<li>{!! trans('about.list.6') !!}</li>
		<li>{!! trans('about.list.7') !!}</li>
	</ul>
@endsection
