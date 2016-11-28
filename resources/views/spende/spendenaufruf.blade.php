@extends('layouts.subPages')

@section('title', $title )

@section('navbarFocus.donate', 'class="dropdown active"')

@section('content')
<h1>MetaGer: die unabhängige, nicht-kommerzielle Internet-Suche</h1>
<p><a href="#formular">direkt zum Spendenformular</a></p>
<p>
In den vergangenen Monaten hat sich MetaGer prächtig weiter entwickelt.
Daran haben <em>Sie</em>, die MetaGer-Nutzer, einen maßgeblichen Anteil: Ohne
<em>Ihre</em> Hilfe gäbe es die unabhängige, nicht-kommerzielle MetaGer-Suche
schon lange nicht mehr.
<p>
Für das kommende Jahr haben wir uns nun allerhand vorgenommen:
<ul>
	<li><p>Unsere englische Version <a href="https://metager.net" target="_blank">https://metager.net</a> muss im englischen Sprachraum
  expandieren: In Großbritannien ist ein Gesetz (das "<i lang="en-UK">Investigatory Powers
  Bill</i>") beschlossen, welches <q cite="https://netzpolitik.org//2016/analyse-london-segnet-haertestes-ueberwachungsgesetz-einer-demokratie-ab/">alle bisherigen Eingriffe in Grundrechte in
  den Schatten</q> stellt - der Orwellsche Überwachungsstaat mit extremer
  Vorratsdatenspeicherung ist Realität geworden
  (<a href="https://netzpolitik.org//2016/analyse-london-segnet-haertestes-ueberwachungsgesetz-einer-demokratie-ab/">https://netzpolitik.org//2016/analyse-london-segnet-haertestes-ueberwachungsgesetz-einer-demokratie-ab/</a>).
  Dagegen bietet MetaGer optimale Schutzmöglichkeiten mit unserem
  anonymisierenden Proxy, der durch den Klick auf "anonym öffnen" genutzt
  wird und durch unseren Zugang über das TOR-Netzwerk.  Dieses Wissen müssen
  wir jetzt im englischen Sprachraum propagieren. Und auch hierzulande
  steht eine <a href="https://digitalcourage.de/themen/vorratsdatenspeicherung" target="_blank">Neuauflage der Vorratsdatenspeicherung</a> vor der Tür.</p></li>
  <li><p>Neben der Suche nach Texten und Bildern ist die Suche nach räumlicher,
  geographischer Information eine der häufigst genutzen Internetdienste.
  Gerade hierbei sind die Standortaufzeichnungen der globalen
  Suchmaschinenanbieter ein Überwachungsinstrument erster Güte.  Um dagegen
  eine Alternative zu bieten, entwickeln wir <a href="https://maps.metager.de" target="_blank">maps.metager.de</a>. Sie
  können es jetzt bereits nutzen: eine erste Version mit Deutschland-Karten
  ist online.  Aber das ist bei weitem noch nicht alles, was wir damit
  vorhaben: Routenplaner, Karten außerhalb Deutschlands usw.  Hiermit wollen
  wir gegen diese Überwachung der geographischem Nutzerdaten eine
  Alternative schaffen.</p></li>
  <li><p>Im August 2016 haben wir den MetaGer-Quellcode öffentlich gemacht (Sie
  finden diesen unter <a href="https://gitlab.metager3.de/open-source/MetaGer" target="_blank">https://gitlab.metager3.de/open-source/MetaGer</a>).  Damit
  ist zum einen öffentlich kontrollierbar, wie wir Datenschutz und
  Privatsphäre im Detail in die Realität umsetzen.  Zum anderen kann jede/r
  MetaGer mit weiterentwickeln und programmieren.  Denn gegen die gewaltige
  Macht der globalen IT-Konzerne haben nur offene Systeme eine Chance, an
  denen <em>viele</em> mitmachen!  Hier erwarten wir im kommenden Jahr neue Ideen und
  Features für MetaGer.  Die von uns ausgelobten <a href="https://suma-awards.de" target="_blank">SUMA Awards</a> belohnen solche
  Programmierungen mit insgesamt 2.500,-EUR.</p></li>
  <li><p>Daneben steht wie immer das "<i>Tagesgeschäft</i>" mit vielen Nutzeranfragen.
  Unser Ziel ist es, dass <em>jede/r</em> die/der uns etwas fragt, eine vernünftige
  Antwort bekommt.  Auch das unterscheidet uns von den globalen
  Suchmaschinenanbietern: Bei MetaGer findet jeder einzelne Mensch
  Beachtung.</p></li>
</ul>
<p class="lead">
Damit wir dieses alles "<i>stemmen</i>" können, sind wir auch weiterhin auf
Ihre Hilfe angewiesen; wir bitten um Spenden für unsere Arbeit:
</p>
<div class="" style="margin-top:50px">
	<h1 id="formular">Jetzt Spenden</h1>
	<div class="col-sm-6">
		<h2>{{ trans('spenden.bankinfo.1') }}</h2>
		<p style="white-space:pre;">{{ trans('spenden.bankinfo.2') }}</p>
		<p class="text-muted">{{ trans('spenden.bankinfo.3') }}</p>
	</div>
	<div class="col-sm-6">
		<div class="">
		<div class="col-md-6">
			<h2>{!! trans('spenden.logos.1') !!}</h2>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input name="cmd" value="_xclick" type="hidden">
				<input name="business" value="wsb@suma-ev.de" type="hidden">
				<input name="item_name" value="SuMa-eV Spende" type="hidden">
				<input name="buyer_credit_promo_code" value="" type="hidden">
				<input name="buyer_credit_product_category" value="" type="hidden">
				<input name="buyer_credit_shipping_method" value="" type="hidden">
				<input name="buyer_credit_user_address_change" value="" type="hidden">
				<input name="no_shipping" value="0" type="hidden">
				<input name="no_note" value="1" type="hidden">
				<input name="currency_code" value="EUR" type="hidden">
				<input name="tax" value="0" type="hidden">
				<input name="lc" value="DE" type="hidden">
				<input name="bn" value="PP-DonationsBF" type="hidden">
				<input src="/img/paypalspenden.gif" name="submit" width="120px" alt="Spenden Sie mit PayPal - schnell, kostenlos und sicher!" type="image">
			</form>
		</div>
		<div class="col-md-6">
			<h2>{!! trans('spenden.logos.2') !!}</h2>
			<a href="bitcoin:174SDRNZqM2WNobHhCDqD1VXbnZYFXNf8V"><img src="/img/WeAcceptBitcoin.png" style="width:120px" alt="Bitcoin"></a>
		</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<hr />
	<div class="col-md-6">
		<h2 id="lastschrift">{{ trans('spenden.lastschrift.1') }}</h2>
		<p>{{ trans('spenden.lastschrift.2') }}</p>
		<form role="form" method="POST" action="{{ action('MailController@donation') }}">
			<input type="hidden" name="dt" value="{{ md5(date('Y') . date('m') . date('d')) }}" />
			<div class="form-group" style="text-align:left;">
				<label for="Name">{{ trans('spenden.lastschrift.3') }}</label>
				<input type="text" class="form-control" id="Name" required="" name="Name" placeholder="{{ trans('spenden.lastschrift.3.placeholder') }}">
			</div>
			<div class="form-group" style="text-align:left;">
				<label for="email">{{ trans('spenden.lastschrift.4') }}</label>
				<input type="email" class="form-control" id="email" name="email" placeholder="Email">
			</div>
			<div class="form-group" style="text-align:left;">
				<label for="tel">{{ trans('spenden.lastschrift.5') }}</label>
				<input type="tel" class="form-control" id="tel" name="Telefon" placeholder="xxxx-xxxxx">
			</div>
			<div class="form-group" style="text-align:left;">
				<label for="iban">{{ trans('spenden.lastschrift.6') }}</label>
				<input type="text" class="form-control" id="iban" required="" name="Kontonummer" placeholder="IBAN">
			</div>
			<div class="form-group" style="text-align:left;">
				<label for="bic">{{ trans('spenden.lastschrift.7') }}</label>
				<input type="text" class="form-control" id="bic" required="" name="Bankleitzahl" placeholder="BIC">
			</div>
			<div class="form-group" style="text-align:left;">
				<label for="msg">{{ trans('spenden.lastschrift.8') }}</label>
				<textarea class="form-control" id="msg" required="" name="Nachricht" placeholder="{{ trans('spenden.lastschrift.8.placeholder') }}"></textarea>
			</div>
			<button type="submit" class="btn btn-default">{{ trans('spenden.lastschrift.9') }}</button>
		</form>
		<p>{{ trans('spenden.lastschrift.10') }}</p>
	</div>
	<div class="col-md-6">
		<h2 id="mails">Aus den EMails vorheriger Spender:</h2>
		<ul style="text-align:left; list-style-type: initial;">
			<li>"Danke, dass es metager gibt."</li>
			<li>"Ich (85J.) möchte für Ihre aufwändige Arbeit 200 Euro spenden. Bleibt stark gegen die Kraken."</li>
  			<li>"Ihre Arbeit halte ich für sehr wertvoll"</li>
  			<li>"Danke für Ihre gute Arbeit!"</li>
  			<li>"Super das neue MetaGer!"</li>
  			<li>"Suchmaschine wie von Ihnen entwickelt und betrieben ist sehr begrüßenswert.  Meine Spende dazu"</li>
  			<li>"Als kleinen Beitrag für Ihre große und großartige Arbeit spende ich"</li>
  			<li>"Bitte buchen Sie 100,-EUR für Ihre gute Arbeit ab."</li>
  			<li>"Gerade in der heutigen Zeit braucht es eine Suchmaschine aus sicherer Hand und guten Absichten."</li>
  			<li>"Ihre Arbeit ist Spitze. Deshalb möchte Ihr Projekt fördern."</li>
  			<li>"Ich verwende schon seit Jahren Metager und danke mit einer Spende"</li>
  			<li>"MetaGer ist Spitze! Ich spende"</li>
  			<li>"Armer Rentner spendet gerne 5,00 Euro"</li>
  			<li>"Ich verwende fast nur noch die MetaGer-Suche und bin damit sehr zufrieden"</li>
  			<li>"Danke für euer Werk!"</li>
		</ul>
	</div>
</div>
<div id="left" class="col-lg-6 col-md-12 col-sm-12 others">


	</div>
@endsection
