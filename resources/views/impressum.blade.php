@extends('layouts.subPages')

@section('title', $title )

@section('content')
<h1>{!! trans('impressum.title') !!}</h1>
<h2>{!! trans('impressum.headline.1') !!}</h2>
<h2>{!! trans('impressum.headline.2') !!}</h2>
<p>{!! trans('impressum.info.1') !!}</p>
<address>{!! trans('impressum.info.2') !!}</address>
<address>{!! trans('impressum.info.3') !!}</address>
<p>{!! trans('impressum.info.4') !!}</p>
<p>{!! trans('impressum.info.5') !!}<a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/team/pubkey-wsb") }}">{!! url('/team/pubkey-wsb') !!}</a></p>
<p>{!! trans('impressum.info.6') !!}</p>
<p>{!! trans('impressum.info.7') !!}</p>
<p>{!! trans('impressum.info.8') !!}</p>
<h2>{!! trans('impressum.info.11') !!}</h2>
<p>{!! trans('impressum.info.12') !!}</p>
@endsection
