@extends('layouts.subPages')

@section('title', $title )

@section('content')
    <h1>Entschuldigen Sie die Störung</h1>
    <p>Wir haben Grund zur Annahme, dass von Ihrem Anschluss verstärkt automatisierte Abfragen abgeschickt wurden. 
    Deshalb bitten wir Sie, die nachfolgende Captcha Abfrage zu beantworten.</p>
    <p>Sollten Sie diese Nachricht häufiger sehen oder handelt es sich dabei um einen Irrtum, schicken Sie uns gerne eine Nachricht über unser <a href="/kontakt">Kontaktformular</a>.</p>
    <p>Nennen Sie uns in diesem Fall bitte unbedingt folgende Vorgangsnummer: {{ $id }}
    <p>Wir schauen uns den Vorgang dann gerne im Detail an.</p>
    <form method="post">
        {{ csrf_field() }}
        <input type="hidden" name="url" value="{!! $url !!}">
        <input type="hidden" name="id" value="{{ $id }}">
        <p>{!! captcha_img() !!}</p>
        @if(isset($errorMessage))
        <p><font color="red">{{$errorMessage}}</font></p>
        @endif
        <p><input type="text" name="captcha"></p>
        <p><button type="submit" name="check">OK</button></p>
    </form>
    <p>Hinweis: Zum Zwecke der Autorisierung wird auf dieser Seite ein Session Cookie gesetzt.
@endsection