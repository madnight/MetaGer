@extends('layouts.subPages')

@section('title', $title )

@section('content')
    <h1>Entschuldigen Sie die Störung</h1>
    <p>Sie befinden sich in einem Netzwerk aus dem wir verstärkt automatisierte Anfragen erhalten. Keine Sorge: Das bedeutet nicht unbedingt, dass diese Anfragen von Ihrem PC kommen.</p>
    <p>Allerdings können wir Ihre Anfragen nicht von denen des "Robots" unterscheiden. Zum Schutz der von uns abgefragten Suchmaschinen müssen wir aber sicherstellen, dass diese nicht mit (automatisierten) Abfragen überflutet werden.</p>

    <p>Bitte geben Sie deshalb die Zeichen aus dem Bild in die Eingabebox ein und bestätigen Sie mit "OK" um zur Ergebnisseite zu gelangen.</p>
    <form method="post" action="{{ route('verification', ['id' => $id]) }}">
        <input type="hidden" name="url" value="{!! $url !!}">
        <input type="hidden" name="id" value="{{ $id }}">
        <p><img src="{{ $image }}" /></p>
        @if(isset($errorMessage))
        <p><font color="red">{{$errorMessage}}</font></p>
        @endif
        <p><input type="text" class="form-control" name="captcha" placeholder="Captcha eingeben"  autofocus></p>
        <p><button type="submit" class="btn btn-success" name="check">OK</button></p>
    </form>
    <p>Sollten Sie diese Nachricht häufiger sehen oder handelt es sich dabei um einen Irrtum, schicken Sie uns gerne eine Nachricht über unser <a href="/kontakt">Kontaktformular</a>.</p>
    <p>Nennen Sie uns in diesem Fall bitte unbedingt folgende Vorgangsnummer: {{ $id }}
@endsection
