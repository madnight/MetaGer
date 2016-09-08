@extends('layouts.subPages')

@section('title', $title )

@section('content')
<h1>MetaGer - Hilfe</h1>
<h2>Einstellungen</h2>
<h3>Allgemein</h3>
<ul>
	<li>Alle Einstellungen finden Sie, indem Sie auf den Suchfokus &quot;anpassen&quot; klicken</li>
	<li>Links neben dem Eingabefeld finden Sie den Farbtropfen, der Ihnen eine Anpassung der Farben gestattet.</li>
	<li>Ein Plugin f&uuml;r die allermeisten Browser finden Sie leicht &uuml;ber den Link gleich unter dem Suchfeld, bei dem Ihr Browser bereits voreingestellt sein sollte.</li>
</ul>
<h3>Auswahl des Suchfokus</h3>
<p>Die Voreinstellungen von MetaGer sind so, dass sie f&uuml;r m&ouml;glichst viele Nutzer die m&ouml;glichst besten Ergebnisse liefern. Sie k&ouml;nnen aus MetaGer jedoch wesentlich mehr und treffendere Ergebnisse herausholen, wenn Sie vor Ihrer Suche einen Augenblick &uuml;berlegen, aus welchem Bereich die von Ihnen gesuchten Ergebnisse kommen sollen.</p>
<h4>Beispiel: Eine Suche mit Fokus auf Nachrichten</h4>
<ul>
	<li>Klicken Sie auf der Startseite auf &ldquo;Nachrichten&ldquo;.</li>
	<li>F&uuml;hren Sie Ihre Suche normal &uuml;ber das Suchfeld durch.</li>
	<li>Sie sehen je nach ausgew&auml;hltem Fokus angepasste Ergebnisse.</li>
	<li>Falls Ihnen die angebotenen Einstellungen nicht zusagen, k&ouml;nnen Sie sich unter &ldquo;anpassen&ldquo; einen eigenen Fokus zusammenstellen.</li>
</ul>
<h3>Die angepassten Einstellungen als Standard setzen</h3>
<p>Falls Sie keinen unserer angebotenen Foki benutzen, sodern einen selbst zusammengestellten, m&uuml;ssen Sie, um diesen dauerhaft verwenden zu k&ouml;nnen, Ihre Einstellungen bei Ihnen im Browser speichern. Daf&uuml;r haben Sie unten auf der Seite zwei Kn&ouml;pfe zur Verf&uuml;gung: Der eine generiert eine Variante unser Startseite in der Ihre Einstellungen hinterlegt sind, solange Sie über diese Seite suchen. Sie k&ouml;nnen diese Startseite als Lezeseichen speichern. Der andere Knopf speichert die Einstellungen in Ihrem Browser im so gennanten &ldquo;Local Storage&ldquo;. Daf&uuml;r darf bei Ihnen Javscript nicht ausgeschaltet sein.</p>
<h2>Sucheingabe</h2>
<h3>Stoppworte</h3>
<ul>
	<li>Wenn Sie unter den MetaGer-Suchergebnissen solche ausschlie&szlig;en wollen, in denen bestimmte Worte (Ausschlussworte / Stopworte) vorkommen, dann erreichen Sie dies, indem Sie diese Worte mit einem &quot;-&quot; versehen.</li>
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
