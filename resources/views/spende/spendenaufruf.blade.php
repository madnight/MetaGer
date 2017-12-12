@extends('layouts.subPages')

@section('title', $title )

@section('navbarFocus.donate', 'class="dropdown active"')

@section('content')
	<h1>MetaGer: die unabhängige, nicht-kommerzielle Internet-Suche</h1>
	<p><a href="#formular">Direkt zum Spendenformular</a></p>
	<p>In den vergangenen Monaten hat sich MetaGer prächtig weiter entwickelt.
	Daran haben <em>Sie</em>, die MetaGer-Nutzer, einen maßgeblichen Anteil: Ohne
	<em>Ihre</em> Hilfe gäbe es die unabhängige, nicht-kommerzielle MetaGer-Suche
	schon lange nicht mehr.</p>
	<p>Für das kommende Jahr haben wir uns nun allerhand vorgenommen:</p>
	<ul>
		<li>
			<p>Unsere englische Version <a href="https://metager.net" target="_blank">https://metager.net</a> muss im englischen Sprachraum expandieren. In diesem Jahr konnten wir mit unserem mehrsprachigen Interface eine sehr gute Grundlage schaffen. Für die englische Version müssen wir nun auch noch mit einer gewohnt ausgezeichneten Ergebnisqualität überzeugen.</p>
		</li>
		<li>
			<p>Gleichzeitig planen wir die „Wiederbelebung“ unserer Bildersuche. Die bisherige MetaGer Bildersuche wurde dieses Jahr von uns deaktiviert, um diese grundlegend zu überarbeiten. Die Ergebnisse und Optionen waren für viele Nutzer nicht ausreichend. Genau das soll sich nun ändern. 2018 soll MetaGer wieder um eine vollumfängliche Bildersuche erweitert werden.</p>
		</li>
		<li>
			<p>Frischer, schneller, produktiver: MetaGer bekommt ein neues Gewand, um euch noch besser in jeder Situation unterstützen zu können.</p>
		</li>
		<li>
			<p>Neben der Suche nach Texten und Bildern ist die Suche nach räumlicher, geographischer Information einer der am häufigsten genutzten Internetdienste. Gerade hierbei sind die Standortaufzeichnungen der globalen Suchmaschinenanbieter ein Überwachungsinstrument erster Güte. Um dafür eine Alternative zu bieten, entwickeln wir <a href="https://maps.metager.de" target="_blank">maps.metager.de</a>. Diesen Dienst haben wir vor kurzem um eine generelle Offline-Funktionalität erweitert. Nun gilt es, diese zu verbessern und den Dienst auch auf die ganze Welt auszuweiten.</p>
		</li>
		<li>
			<p>Daneben steht wie immer das "<i>Tagesgeschäft</i>" mit vielen Nutzeranfragen. Unser Ziel ist es, dass <em>jede/r</em> die/der uns etwas fragt, eine vernünftige Antwort bekommt.  Auch das unterscheidet uns von den globalen Suchmaschinenanbietern: Bei MetaGer findet jeder einzelne Mensch Beachtung.</p>
		</li>
	</ul>
	<p class="lead">Damit wir dieses alles "<i>stemmen</i>" können, sind wir auch weiterhin auf
	Ihre Hilfe angewiesen; wir bitten um Spenden für unsere Arbeit:</p>
	<div class="" style="margin-top:50px">
		<h1 id="formular">Jetzt Spenden</h1>
		<div class="col-sm-6">
			<h2>{{ trans('spende.bankinfo.1') }}</h2>
			<p style="white-space:pre;">{{ trans('spende.bankinfo.2') }}</p>
			<p class="text-muted">{{ trans('spende.bankinfo.3') }}</p>
		</div>
		<div class="col-sm-6">
			<div class="">
				<div class="col-md-6">
					<h2>{!! trans('spende.paypal.1') !!}</h2>
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
					<h2>{!! trans('spende.bitcoins.1') !!}</h2>
					<a href="bitcoin:174SDRNZqM2WNobHhCDqD1VXbnZYFXNf8V"><img src="/img/WeAcceptBitcoin.png" style="width:120px" alt="Bitcoin"></a>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<hr>
		<div class="col-md-6">
			<h2 id="lastschrift">{{ trans('spende.lastschrift.1') }}</h2>
			<p>{{ trans('spende.lastschrift.2') }}</p>
			<form role="form" method="POST" action="{{ action('MailController@donation') }}">
				<input type="hidden" name="dt" value="{{ md5(date('Y') . date('m') . date('d')) }}" />
				<div class="form-group" style="text-align:left;">
					<label for="Name">{{ trans('spende.lastschrift.3') }}</label>
					<input type="text" class="form-control" id="Name" required="" name="Name" placeholder="{{ trans('spende.lastschrift.3.placeholder') }}">
				</div>
				<div class="form-group" style="text-align:left;">
					<label for="email">{{ trans('spende.lastschrift.4') }}</label>
					<input type="email" class="form-control" id="email" name="email" placeholder="Email">
				</div>
				<div class="form-group" style="text-align:left;">
					<label for="tel">{{ trans('spende.lastschrift.5') }}</label>
					<input type="tel" class="form-control" id="tel" name="Telefon" placeholder="xxxx-xxxxx">
				</div>
				<div class="form-group" style="text-align:left;">
					<label for="iban">{{ trans('spende.lastschrift.6') }}</label>
					<input type="text" class="form-control" id="iban" required="" name="Kontonummer" placeholder="IBAN">
				</div>
				<div class="form-group" style="text-align:left;">
					<label for="bic">{{ trans('spende.lastschrift.7') }}</label>
					<input type="text" class="form-control" id="bic" required="" name="Bankleitzahl" placeholder="BIC">
				</div>
				<div class="form-group" style="text-align:left;">
					<label for="msg">{{ trans('spende.lastschrift.8') }}</label>
					<textarea class="form-control" id="msg" required="" name="Nachricht" placeholder="{{ trans('spende.lastschrift.8.placeholder') }}"></textarea>
				</div>
				<button type="submit" class="btn btn-default">{{ trans('spende.lastschrift.9') }}</button>
			</form>
			<p>{{ trans('spende.lastschrift.10') }}</p>
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
	<div id="left" class="col-lg-6 col-md-12 col-sm-12 others"></div>
@endsection
