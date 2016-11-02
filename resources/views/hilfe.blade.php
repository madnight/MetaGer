@extends('layouts.subPages')

@section('title', $title )

@section('content')
<div class="alert alert-warning" role="alert">{!! trans('hilfe.achtung') !!}</div>
<h1>{!! trans('hilfe.title') !!}</h1>
<h2>{!! trans('hilfe.einstellungen') !!}</h2>
<h3>{!! trans('hilfe.allgemein.title') !!}</h3>
<ul class="dotlist">
	<li>{!! trans('hilfe.allgemein.1') !!}</li>
	<li>{!! trans('hilfe.allgemein.2') !!}</li>
	<li>{!! trans('hilfe.allgemein.3') !!}</li>
</ul>
<h3>{!! trans('hilfe.suchfokus.title') !!}</h3>
<p>{!! trans('hilfe.suchfokus.1') !!}</p>
<h2>{!! trans('hilfe.sucheingabe') !!}</h2>
<h3>{!! trans('hilfe.stopworte.title') !!}</h3>
<p>{!! trans('hilfe.stopworte.1') !!}</p>
<ul class="dotlist">
	<li>{!! trans('hilfe.stopworte.2') !!}</li>
</ul>
<h3>{!! trans('hilfe.mehrwortsuche.title') !!}</h3>
<p>{!! trans('hilfe.mehrwortsuche.1') !!}</p>
<ul class="dotlist">
	<li>{!! trans('hilfe.mehrwortsuche.2') !!}</li>
</ul>
<h3>{!! trans('hilfe.grossklein.title') !!}</h3>
<p>{!! trans('hilfe.grossklein.1') !!}</p>
<ul class="dotlist">
	<li>{!! trans('hilfe.grossklein.2') !!}</li>
</ul>
<div class="underline-i-elements">
	<h3>Suche auf Domains beschränken / Domains ausschließen</h3>
	<p>Die im folgen verwendeten <i>Suchen und Webseiten</i> sind lediglich Beispiele. Sie müssen diese in der Praxis durch ihre eigenen ersetzen.</p>
	<p>Wenn Sie Ihre Suche nur auf Ergebnisse von einer bestimmten Domain (z.B. wikipedia.org) beschränken möchten, können Sie dies erreichen indem Sie Ihrer Suche site:<i>ihre-domain.de</i> hinzufügen.</p>
	<ul class="dotlist">
		<li>Beispiel: Sie möchten nur noch Ergebnisse von der deutschen Wikipedia (de.wikipedia.org) erhalten. Ihre Suche lautet also:
		<div class="well well-sm"><i>meine suche</i> site:de.wikipedia.org</div></li>
		<li>Beispiel: Sie möchten auch Ergebnisse von Wikipedia in anderen Sprachen (wikipedia.org) erhalten. Ihre Suche lautet also:
		<div class="well well-sm"><i>meine suche</i> site:wikipedia.org</div></li>
	</ul>
	<p>Manchmal kann es auch passieren, dass Sie Ergebnisse einer bestimmten Domain nicht mehr sehen möchten. In diesem Fall haben Sie zwei Möglichkeiten: Den Ausschluss eines Hosts und den Ausschluss einer Domain. Dies erreichen Sie, indem Sie -host:<i>unterseite.ihre-seite.de</i> beziehungsweise -domain:<i>ihre-seite.de</i> zu Ihrer Suche hinzufügen.</p>
	<ul class="dotlist">
		<li>Beispiel: Sie haben genug von den ganzen Wikipedia-Ergebnissen. Nun haben Sie zwei möglichkeiten:</li>
		<li>Sie schließen alle Ergebnisse von der deutschen Wikipedia-Domain, also de.wikipedia.org, aus
		<div class="well well-sm"><i>meine suche</i> -host:de.wikipedia.org</div>
		Sie erhalten nun weiterhin Ergebnisse von beispielsweise en.wikipedia.org, solange diese zu Ihrer Suche passen</li>
		<li>Sie schließen generell alle Ergebnisse von allen Wikipedia-Domains aus
		<div class="well well-sm"><i>meine suche</i> -domain:wikipedia.org</div></li>
	</ul>
	<div class="result">
		<div style="float: left">Zusätzlich bieten wir Ihnen die Möglichkeit Hosts beziehungsweise Domains direkt auf der Ergebnisseite auszuschließen. Bei jedem unserer Ergebnisse erscheint dieses kleine Symbol für die Optionen: </div>
		<div class="link">
			<div class="options">
				<a tabindex="0" data-toggle="popover" data-trigger="focus" data-placement="auto bottom" data-container="body" data-html="true" data-title="<span class='glyphicon glyphicon-cog'></span> Optionen" data-original-title="" title=""><span class="glyphicon glyphicon-triangle-bottom"></span></a>
				<div class="content hidden">
					<ul class="options-list list-unstyled">
						<li>
							<a href="javascript:void(0)" onclick="javascript:document.getElementById('blacklist-tutorial-search').innerHTML = 'meine suche site:wikipedia.org'">
								Suche auf dieser Domain neu starten
							</a>
						</li>
						<li>
							<a href="javascript:void(0)" onclick="javascript:document.getElementById('blacklist-tutorial-search').innerHTML = 'meine suche -host:de.wikipedia.org'">
								de.wikipedia.org ausblenden
							</a>
						</li>
						<li>
							<a href="javascript:void(0)" onclick="javascript:document.getElementById('blacklist-tutorial-search').innerHTML = 'meine suche -domain:wikipedia.org'">
								*.wikipedia.org ausblenden
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div style="clear: both">Sie können in den Optionen die Domain von der das Ergebnis stammt direkt von der Suche ausschließen. Probieren Sie es doch gleich aus.</div>
		<div id="blacklist-tutorial-search" class="well well-sm">meine suche</div>
	</div>
</div>
<h2>{!! trans('hilfe.dienste') !!}</h2>
<h3>{!! trans('hilfe.suchwortassoziator.title') !!}</h3>
<p>{!! trans('hilfe.suchwortassoziator.1') !!}</p>
<p>{!! trans('hilfe.suchwortassoziator.2') !!}</p>
<p>{!! trans('hilfe.suchwortassoziator.3') !!}</p>
<h3>{!! trans('hilfe.widget.title') !!}</h3>
<p>{!! trans('hilfe.widget.1') !!}</p>
<h3>{!! trans('hilfe.urlshort.title') !!}</h3>
<p>{!! trans('hilfe.urlshort.1') !!}</p>
<h3>=> {!! trans('hilfe.dienste.kostenlos') !!}</h3>
<h2>{!! trans('hilfe.datenschutz.title') !!}</h2>
<h3>{!! trans('hilfe.datenschutz.1') !!}</h3>
<p>{!! trans('hilfe.datenschutz.2') !!}</p>
<p>{!! trans('hilfe.datenschutz.3') !!}</p>
<h3>{!! trans('hilfe.tor.title') !!}</h3>
<p>{!! trans('hilfe.tor.1') !!}</p>
<p>{!! trans('hilfe.tor.2') !!}</p>
<h3>{!! trans('hilfe.proxy.title') !!}</h3>
<p>{!! trans('hilfe.proxy.1') !!}</p>
@endsection
