@extends('layouts.subPages')

@section('title', $title )

@section('content')
        <h1 class="page-title">Einen kurzen Augenblick bitte</h1>
        <div class="card-heavy">
            <p>Sie befinden sich im selben Netzwerk, aus dem wir sehr viele automatisierte Anfragen erhalten. Das bedeutet nicht, dass die Anfragen von Ihrem PC kommen. Wir müssen nun aber verifizieren, dass es sich bei Ihnen um einen realen Nutzer handelt.</p>
            <p>Der erste Schritt ist diese Vorschaltseite. Sie brauchen nichts weiter tun, als unten auf den Knopf "Weiter zur Ergebnisseite" zu klicken.</p>
            <p>Zukünftig sollten Sie diese Seite nicht mehr sehen.</p>
            <form id="goOn" method="{{ $method }}">
                @foreach(Request::all() as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}" />
                @endforeach
                <input type="hidden" name="uid" value="{{ $uid }}" />
                <p><button type="submit" class="btn btn-success">Weiter zur Ergebnisseite</button></p>
            </form>
        </div>
    <script>
        $(document).ready(function() {
            $(".mg-panel").css("display", "none");
            $("#goOn").submit();
        });
    </script>
@endsection
