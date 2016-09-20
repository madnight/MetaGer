@extends('layouts.subPages')

@section('title', 'Fehler 500 - Interner Serverfehler')

@section('content')
<h1>{{ trans('500.title') }}</h1>
<p>{{ trans('500.text') }}</p>
@if( config('app.debug') )
<pre>{{ $exception }}</pre>
@endif
@endsection
