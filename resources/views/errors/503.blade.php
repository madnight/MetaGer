@extends('layouts.subPages')

@section('title', 'Fehler 500 - Service nicht verfügbar')

@section('content')
<h1>{{ trans('503.title') }}</h1>
<p>{{ trans('503.text') }}</p>
@if( config('app.debug') )
<pre>{{ $exception }}</pre>
@endif
@endsection
