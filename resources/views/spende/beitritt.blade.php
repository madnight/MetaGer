@extends('layouts.subPages')

@section('title', $title )

@section('navbarFocus.donate', 'class="dropdown active"')

@section('content')
	<link type="text/css" rel="stylesheet" href="{{ mix('/css/beitritt.css') }}" />
	<h1>{{ trans('beitritt.heading.1') }}</h1>
Momentan überarbeiten wir unseren Antrag auf Mitgliedschaft im Hinblick	auf die Erfordernisse der DSGVO. Dies kann noch ein oder zwei Wochen dauern, da wir ein kleines Team sind. Sie können uns aber gerne eine E-Mail an office@suma-ev.de schicken. Wir werden Ihnen Bescheid geben, sobald der Antrag wieder online verfügbar ist.
@endsection
