@extends('layouts.subPages')

@section('title', $title )

@section('content')
	<h1>{{ trans('about.head.1') }}</h1>
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
