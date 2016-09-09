@extends('layouts.subPages')

@section('title', $title )

@section('content')
<h1>MetaGer - Hilfe</h1>
<h2>Einstellungen</h2>
<h3>Allgemein</h3>
<ul>
	<li>Alle Einstellungen finden Sie unter dem Suchfokus &quot;anpassen&quot;.</li>
	<li>Der Farbtropfen links neben dem Suchfeld erm&ouml;glicht Ihnen eine individuelle Farbeinstellung für die Startseite.</li>
	<li>Ein Plugin f&uuml;r die allermeisten Browser finden Sie leicht &uuml;ber den Link gleich unter dem Suchfeld, wo Ihr Browser bereits voreingestellt sein sollte.</li>
</ul>
<h3>Auswahl des Suchfokus</h3>
<p>&Uuml;ber dem Suchfeld finden Sie f&uuml;nf Sucheinstellungen, die den meisten Anforderungen gen&uuml;gen ( &quot;Web&quot;, &quot;Bilder&quot;, &quot;Nachrichten&quot;, &quot;Wissenschaft&quot; sowie &quot;Produkte&quot; ). &Uuml;ber den Button &quot;anpassen&quot; rechts daneben k&ouml;nnen Sie pers&ouml;nliche Feineinstellungen dazu vornehmen. Zuletzt entscheiden Sie &uuml;ber die Verwendung Ihrer Einstellungen. Sie finden ganz unten unter den Einstellungen zwei Buttons: entweder benutzen Sie die Einstellung nur f&uuml;r eine Suche (hierf&uuml;r k&ouml;nnen Sie auch ein Lesezeichen setzen), oder f&uuml;r eine dauerhafte Verwendung. MetaGer speichert Ihre Einstellungen dann im sogenannten &quot;Local Storage&quot; (des Browsers), hierf&uuml;r ben&ouml;tigen Sie Javascript.</p>
<h2>Sucheingabe</h2>
<h3>Stoppworte</h3>
<ul>
	<li>Wenn Sie unter den MetaGer-Suchergebnissen solche ausschlie&szlig;en wollen, in denen bestimmte Worte (Ausschlussworte / Stopworte) vorkommen, dann erreichen Sie das, indem Sie diese Worte mit einem Minus versehen.</li>
	<li>Beispiel: Sie suchen ein neues Auto, aber auf keinen Fall einen BMW. Ihre Eingabe lautet also: <div class="well well-sm">auto neu -bmw</div></li>
</ul>
<h3>Mehrwortsuche</h3>
<p>Bei einer Mehrwortsuche werden als Voreinstellung diejenigen Dokumente gesucht, in denen alle Worte vorkommen. Die Suche nach mehreren Begriffen zeigt ann&auml;hernd gleiche Ergebnisse mit oder ohne Verwendung von Anf&uuml;hrungszeichen. Wenn Sie jedoch zum Beispiel ein l&auml;ngeres Zitat oder so etwas suchen, sollten Sie Anf&uuml;hrungszeichen verwenden.</p>
<ul>
	<li>Beispiel: die Suche nach <div class="well well-sm">&quot;in den &ouml;den Fensterh&ouml;hlen&quot;</div> liefert viele Ergebnisse, aber spannend (und genauer) wird es bei der Suche <div class="well well-sm">Schiller &quot;in den &ouml;den Fensterh&ouml;hlen&quot;</div></li>
</ul>
<h3>Gro&szlig;-/ Kleinschreibung</h3>
<p>Gro&szlig;- und Kleinschreibung wird bei der Suche nicht unterschieden, es handelt sich um eine rein inhaltliche Suche.</p>
<ul>
	<li>Beispiel: die Suche nach <div class="well well-sm">gro&szlig;schreibung</div> liefert also genau die gleichen Ergebnisse wie <div class="well well-sm">GRO&szlig;SCHREIBUNG</div></li>
</ul>
@endsection
