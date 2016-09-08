@extends('layouts.subPages')

@section('title', $title )

@section('content')
<h1>MetaGer - FAQ</h1>
<h2>Kostenlose Dienste</h2>
<h3>Suchwortassoziator</h3>
<p>Als Hilfe f&uuml;r die Erschließung eines Begriffsumfelds haben wir den <a href="https://metager.de/klassik/asso/" target="_blank">MetaGer-Web-Assoziator</a> entwickelt. Sie finden das Tool unter dem Reiter &quot;Dienste&quot;. Gibt man in diesen ein Suchwort ein, welches dem zu untersuchenden Fachgebiet irgendwie nahe kommt, dann wird versucht, typische Fachbegriffe dieses Gebietes aus dem WWW zu extrahieren.</p>
<p>Beispiel: Sie m&ouml;chten mehr &uuml;ber Zeckenbisse und deren Gefahren wissen, aber ihnen fallen die medizinischen Fachbegriffe f&uuml;r Erkrankungen aus diesem Bereich nicht mehr ein. Die Eingabe des Wortes &quot;Zeckenbisse&quot; in den Web-Assoziator liefert dann u.a. die Begriffe &quot;Borreliose&quot; und &quot;fsme&quot;.</p>
<p>Da diese Assoziationsanalyse u.a. aus Web-Dokumenten selber gewonnen wird, ist sie sprachunabh&auml;ngig; d.h. Sie k&ouml;nnen bei Eingabe deutscher W&ouml;rter Fachbegriffe aus beliebigen Sprachen gewinnen (und umgekehrt). Wenn Ihnen andererseits Assoziationsanalysen auffallen, die mit Hilfe Ihrer Fachkenntnisse besser sein k&ouml;nnten, dann z&ouml;gern Sie bitte nicht, uns dieses samt Ihrem Verbesserungsvorschlag <a href="https://metager.de/kontakt/" target="_blank">&uuml;ber unser Kontaktformular</a> mitzuteilen.</p>
<h3>MetaGer Widget</h3>
<p>MetaGerWidget MetaGerWidget
Hierbei handelt es sich um einen Codegenerator, der es Ihnen erm&ouml;glicht, MetaGer in Ihre Webseite einzubinden. Sie k&ouml;nnen damit dann nach Belieben auf Ihrer eigenen Seite oder im Internet suchen lassen. Bei allen Fragen: <a href="https://metager.de/kontakt/" target="_blank">unser Kontaktformular</a></p>
<h3>URL-Verk&uuml;rzer</h3>
<p>Sie finden den URL-Verk&uuml;rzer unter &quot;Dienste&quot;. Wenn Sie einen extrem langen Link- oder Domainnamen haben, k&ouml;nnen Sie diesen hier in eine kurze und pr&auml;gnante Form bringen. Metager sorgt dann zusammen mit Yourls f&uuml;r die Weiterleitung.</p>
<h2>Anonymit&auml;t und Datensicherheit</h2>
<h3>Cookies, Session-IDs und IP-Adressen</h3>
<p>Nichts von alldem wird hier bei MetaGer gespeichert, aufgehoben oder sonst irgendwie verarbeitet. Weil wir diese Thematik f&uuml;r extrem wichtig halten, haben wir auch M&ouml;glichkeiten geschaffen, die Ihnen helfen k&ouml;nnen, hier ein H&ouml;chstmaß an Sicherheit zu erreichen: den MetaGer-TOR-Hidden-Service und unseren Proxyserver.</p>
<p>Genauere Informationen dazu finden Sie unter der &Uuml;berschrift &quot;Dienste&quot;.</p>
<h3>Tor-Hidden-Service</h3>
<p>Bei MetaGer werden schon seit vielen Jahren die IP-Adressen der Nutzer anonymisiert und nicht gespeichert. Nichtsdestotrotz sind diese Adressen auf dem MetaGer-Server sichtbar: wenn MetaGer also einmal kompromittiert sein sollte, dann k&ouml;nnte dieser Angreifer Ihre Adressen mitlesen und speichern. Um dem h&ouml;chsten Sicherheitsbed&uuml;rfnis entgegenzukommen, unterhalten wir eine MetaGer-Repr&auml;sentanz im Tor-Netzwerk: den MetaGer-TOR-hidden-Service - erreichbar &uuml;ber: <a href="https://metager.de/tor/" target="_blank">https://metager.de/tor/</a>. F&uuml;r die Benutzung ben&ouml;tigen Sie einen speziellen Browser, den Sie etwa auf <a href="https://www.torproject.org/" target="_blank">https://www.torproject.org/</a> herunter laden k&ouml;nnen.</p>
<p>MetaGer erreichen Sie in diesem Browser dann unter: http://b7cxf4dkdsko6ah2.onion/tor/.</p>
<h3>MetaGer Proxyserver</h3>
<p>Um ihn zu verwenden, m&uuml;ssen Sie auf der MetaGer-Ergebnisseite nur auf den Link "anonym &ouml;ffnen" rechts neben dem Ergebnislink klicken. Dann wird Ihre Anfrage an die Zielwebseite &uuml;ber unseren anonymisierenden Proxy-Server geleitet und Ihre pers&ouml;nlichen Daten bleiben weiterhin v&ouml;llig gesch&uuml;tzt. Wichtig: wenn Sie ab dieser Stelle den Links auf den Seiten folgen, bleiben Sie durch den Proxy gesch&uuml;tzt. Sie k&ouml;nnen aber oben im Adressfeld keine neue Adresse ansteuern. In diesem Fall verlieren Sie den Schutz. Ob Sie noch gesch&uuml;tzt sind, sehen Sie ebenfalls im Adressfeld. Es zeigt: https://proxy.suma-ev.de/?url=hier steht die eigentlich Adresse.</p>
<h2 id="faq">FAQ</h2>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Was ist MetaGer?</h3>
  </div>
  <div class="panel-body">
    <p>MetaGer ist eine Suchmaschine, die Suchdienste parallel nach den von Ihnen eingegebenen Suchworten absucht und alle Ergebnisse zusammenfasst. MetaGer arbeitet die Ergebnisse sinnvoll auf. Dabei werden etwa (m&ouml;glichst) alle doppelten Treffer (Doubletten) zu einem zusammengefasst. Eine vollst&auml;ndige Erkennung von Doubletten ist allerdings unm&ouml;glich. So etwas nennt man eine Meta-Suchmaschine. Wenn man also sinnvoll suchen will, dann muss man etliche Suchmaschinen nacheinander "von Hand" absuchen und alle Ergebnisse vergleichen und zusammenf&uuml;hren. Diese Arbeit kann einem ein Automat - die Metasuchmaschine – abnehmen. Dazu kommt der h&ouml;here Abdeckungsgrad, denn nicht jede Suchmaschine kennt das ganze Internet. N&auml;heres zu Metasuchmaschinen finden Sie bei <a href="https://de.wikipedia.org/wiki/Metasuchmaschine" target="_blank">Wikipedia</a>. Vielleicht gen&uuml;gen Ihnen die Ergebnisse, die Ihnen MetaGer pr&auml;sentiert, vielleicht m&ouml;chten Sie mit einer einzelnen Suchmaschine nochmals suchen. In der Ergebnisliste sehen Sie an jedem Ergebnis, woher es kam und k&ouml;nnen auch direkt klicken. Viele weitere Suchmaschinen finden Sie zum Beispiel hier: <a href="http://www.klug-suchen.de/" target="_blank">klug-suchen.de</a>.</p>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Welche Suchdienste sucht MetaGer ab?</h3>
  </div>
  <div class="panel-body">
    <p>Sie finden die Liste unter dem Men&uuml;punkt &quot;anpassen&quot; &uuml;ber dem Suchfeld. Alle, die Sie selbst nicht ausgeschaltet haben, werden abgesucht.</p>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Warum gibt es keinen Schalter "Suche &uuml;ber alle Suchdienste"?</h3>
  </div>
  <div class="panel-body">
    <p>Im Wesentlichen liegt das daran, dass sich die Anforderungen manchmal widersprechen. Es macht z.B. keinen Sinn, auf der Suche nach wissenschaftlichen Ergebnissen auch die Produktsuchmaschinen einzuschalten und umgekehrt. Diese Ergebnisse eignen sich nicht zum Vermischen.</p>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Warum fragt Ihr den Suchdienst XY [nicht ab | nicht mehr ab | doch ab]?</h3>
  </div>
  <div class="panel-body">
    <p>Wenn wir einen Suchdienst nicht mehr abfragen, den wir fr&uuml;her dabei hatten, dann hat das entweder technische, konzeptionelle oder &quot;politische Gr&uuml;nde&quot; (die Verbindung dorthin ist zu schwach, der dortige Rechner zu klein, die Ergebnisse passen nicht richtig, dieser Suchdienst bietet z.B. keine Option f&uuml;r eine deutschsprachige Suche,  dieser Suchdienstbetreiber "mag" uns nicht -was wir selbstverst&auml;ndlich respektieren m&uuml;ssen).</p>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Ein Suchdienst antwortet nicht mehr</h3>
  </div>
  <div class="panel-body">
    <p>Einer der von MetaGer abgefragten Suchdienste (der sonst geantwortet hat), findet auf einmal nichts mehr, auch nicht Begriffe, zu denen er bisher immer etwas gefunden hat - was ist da los? Vermutlich hat dieser Suchdienst sein Ausgabeformat ge&auml;ndert, und bringt damit unsere Programme durcheinander. In diesem Falle bitten wir Sie, uns dann sofort eine Mail zu senden, damit wir etwas dagegen tun k&ouml;nnen.</p>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Fragw&uuml;rdige Inhalte</h3>
  </div>
  <div class="panel-body">
    <p>Ich habe "Treffer" erhalten, die finde ich nicht nur &auml;rgerlich, sondern die enthalten meiner Meinung nach illegale Inhalte!</p>
	<p>Wenn Sie im Internet etwas finden, das Sie f&uuml;r illegal oder jugendgef&auml;hrdend halten, dann k&ouml;nnen Sie sich per Mail an hotline@jugendschutz.net wenden oder Sie gehen auf http://www.jugendschutz.net/ und f&uuml;llen das dort zu findende Beschwerdeformular aus. Sinnvoll ist ein kurzer Hinweis, was Sie konkret f&uuml;r unzul&auml;ssig halten und wie Sie auf dieses Angebot gestoßen sind. Direkt an uns k&ouml;nnen Sie fragw&uuml;rdige Inhalte auch melden. Schreiben Sie dazu eine Mail an unseren Jugendschutzbeauftragten (<a href="mailto:jugendschutz@metager.de" target="_blank">jugendschutz@metager.de</a>).</p>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Kann ich MetaGer in meine eigene Homepage einbauen?</h3>
  </div>
  <div class="panel-body">
    <p>Kein Problem! Gerne! Genauere Informationen dazu finden Sie unter der &Uuml;berschrift &quot;Dienste&quot;, unter dem Punkt &quot;Widget&quot;.</p>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Wo kann ich bei MetaGer meine Homepage/URL/etc. Anmelden ?</h3>
  </div>
  <div class="panel-body">
    <p>Gar nicht. MetaGer ist eine MetaSuchmaschine. Sie sucht nicht selber, sondern l&auml;sst andere Suchdienste suchen. Wenn Sie Ihre eigenen WWW-Seiten den Suchmaschinen bekannt geben wollen, dann m&uuml;ssen Sie die Suchmaschinen einzeln aufsuchen, bei denen Sie Ihre Seiten anmelden wollen.</p>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Wie genau funktioniert das Ranking bei MetaGer?</h3>
  </div>
  <div class="panel-body">
    <p>Dazu machen wir aus nahe liegenden Gr&uuml;nden keine Angaben</p>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Mit meinem XYZ-Browser und dem XYZ-Betriebssystem kann ich MetaGer nicht abfragen. Was tun?</h3>
  </div>
  <div class="panel-body">
    <p>Versuchen Sie bitte zuerst, das aktuelle Plugin zu installieren. Zum Installieren einfach auf den Link direkt unter dem Suchfeld klicken. Dort sollte Ihr Browser schon erkannt worden sein. Wenn Sie dann noch Probleme haben sollten, wenden Sie sich bitte an uns: office@suma-ev.de</p>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Wo habt ihr eigentlich all' die klugen Spr&uuml;che eures "Spr&uuml;cheklopfers" her?</h3>
  </div>
  <div class="panel-body">
    <p>Sie sind aus Quellen im Internet zusammengesucht. Den gr&ouml;ßten Teil hatte uns netterweise Alexander Hammer zur Verf&uuml;gung gestellt. Sch&ouml;nen Dank! Nebenbei bemerkt: Wir teilen keineswegs die inhaltliche Meinung, die in jedem Spruch zum Ausdruck kommt! Spr&uuml;che sollen und m&uuml;ssen kontroverses darstellen. Unter &quot;anpassen&quot; k&ouml;nnen Sie sie ausschalten.</p>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Wie kann ich die Anzeige meiner vorherigen Suchen l&ouml;schen?</h3>
  </div>
  <div class="panel-body">
    <p>Die Suchvorschl&auml;ge liefert Ihnen Ihr Webbrowser und dort k&ouml;nnen Sie sie auch ausschalten. Meist geht das &uuml;ber die Chronik.</p>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Wie haltet ihr es eigentlich mit dem Datenschutz, wie lange wird bei euch was gespeichert?</h3>
  </div>
  <div class="panel-body">
    <p>Der Schutz pers&ouml;nlicher Daten ist uns so wichtig, dass wir alles, was dem zuwiderlaufen k&ouml;nnte, gar nicht erst machen: es gibt bei uns keine Cookies oder Session-IDs oder irgendetwas, was so etwas erm&ouml;glichen w&uuml;rde. Siehe auch: https://metager.de/datenschutz/</p>
	<p>Was es bei jeder Suchmaschine gibt (und wogegen auch wir nichts tun k&ouml;nnen), das sind die bei den Abfragen mitgesendeten IP-Adressen. Auch dies k&ouml;nnen personenbezogene Daten sein. Darum speichern wir auch diese Adressen NICHT - und zwar &uuml;berhaupt nicht, auch nicht tageweise, und schon gar nicht f&uuml;r Jahre. Die IP-Adressen werden bereits w&auml;hrend Ihre Suche noch l&auml;uft, anonymisiert. Auch die anonymisierten IP-Adressen speichern wir NICHT und geben sie auch nicht an die von MetaGer abgefragten Suchdienste weiter. Nach unseren Erfahrungen kommt der Betrieb von Suchmaschinen sehr gut OHNE Speicherung von IP-Adressen aus. Was tun wir zus&auml;tzlich?</p>
	<p>Wenn Sie generell (unabh&auml;ngig von MetaGer) ohne Speicherung Ihrer IP-Adresse im Internet surfen wollen, dann k&ouml;nnen Sie am einfachsten einen der freien und werbefinanzierten Proxies benutzen, einen kommerziellen anmieten, oder den f&uuml;r Sie kostenlosen MetaGer-Proxy verwenden. Genauere Informationen dazu finden Sie unter der &Uuml;berschrift &quot;Dienste&quot;.</p>
	<p>Wenn Sie Ihre Anonymit&auml;t noch weiter absichern wollen, dann k&ouml;nnen Sie Teilnehmer am Tor-Netzwerk werden. Genauere Informationen dazu finden Sie unter der &Uuml;berschrift &quot;Dienste&quot;.</p>
	<p>Selbstverst&auml;ndlich erfolgt der Zugang zu MetaGer immer automatisch nur &uuml;ber das verschl&uuml;sselnde https-Protokoll. Damit sind Ihre Suchabfragen auch auf dem &Uuml;bertragungsweg von Ihrem PC zum MetaGer-Server sicher.</p>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">&Uuml;ber welche Wege kann eine Zuordnung zu Personen hergestellt werden?</h3>
  </div>
  <div class="panel-body">
    <p>Die Zuordnung kann dann hergestellt werden, wenn sich ein Nutzer bei einem Dienst eines Anbieters (z.B. Google-Mail) pers&ouml;nlich angemeldet hat. Dann wird ein Cookie f&uuml;r diesen Anmelder gesetzt. Bei einer sp&auml;teren Suche ist dann dieser Anmelder anhand des Cookies identifiziert. Es sein denn: der (schlaue) Anwender l&ouml;scht den Cookie ;-) Aber die wenigsten tun das. Eine exakte Zuordnung &uuml;ber die IP-Adresse zur Person ist nur mit Hilfe des Providers m&ouml;glich. Dies wird im Normalfall wahrscheinlich nicht geschehen. Aber es gibt weitere Indizien: auch anhand einer wechselnden IP ist ohne Mithilfe des Providers eine ungef&auml;hre geografische Zuordnung m&ouml;glich. Dar&uuml;ber hinaus sendet der Browser weitere Daten, wie z.B. den User-Agent, dessen genaue Version und Arbeitsumgebung, das Betriebssystem und dessen exakte Version und ggf. Patch-Level. Auch mit diesen Daten ist eine Zuordnung zur Person des Anmelders, wenn dessen Daten durch die Anmeldung zu einem Dienst erst einmal bekannt sind, mit hoher Wahrscheinlichkeit m&ouml;glich.</p>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Wie ist MetaGer eigentlich entstanden, wie ist die Geschichte von MetaGer?</h3>
  </div>
  <div class="panel-body">
    <p>MetaGer gibt es seit 1996 ... die Einzelheiten der Entstehungsgeschichte kann man hier in einem Interview nachlesen: http://blog.suma-ev.de/node/207.</p>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Darf ich einen Link auf euch setzen? oder Darf ich auf euch verlinken ?</h3>
  </div>
  <div class="panel-body">
    <p>Ja! Sie d&uuml;rfen 1000-sende von Links auf uns setzen!! Sie d&uuml;rfen das selbst dann, wenn Sie &uuml;ber manche Dinge des Lebens eine andere Meinung haben als wir, selbst dann, wenn Sie nicht die gleiche Partei w&auml;hlen oder eine andere Meinung &uuml;ber die einzig richtige Art der Rechtschreibung haben. Sie d&uuml;rfen Links auf alles von uns setzen, was Sie wollen. Je mehr, je besser! Noch lieber w&auml;re es uns nat&uuml;rlich, wenn Sie (vielleicht im Rahmen der Verbesserung Ihrer Webseiten) unser Widget nutzen w&uuml;rden. Bitte schauen Sie unter dem Punkt &quot;Dienste&quot; nach.</p>
  </div>
</div>
@endsection
