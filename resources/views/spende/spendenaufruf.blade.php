@extends('layouts.subPages')

@section('title', $title )

@section('navbarFocus.donate', 'class="dropdown active"')

@section('content')
<h1>MetaGer sagt: <i>Danke!</i></a></h1>
<p>
Liebe hilfsbereite Menschen und metager-ische Freunde, wir hätten uns nie
vorstellen können, dass es um MetaGer so eine wundervolle Community gibt:
Die Spendenbereitsschaft ist oberhalb dessen, was wir uns bisher vorstellen
konnten.  Sogar aus Übersee haben uns Spenden und Hilfsangebote erreicht.
<p>
Wir danken ganz herzlich allen, die für MetaGer gespendet haben! Wir haben
uns vorher nicht vorstellen können, dass die Resonanz auf unseren
Spendenaufruf so toll ist.  SIE haben es damit auch möglich gemacht, dass
wir MetaGer jetzt international als freie Software in der Open-Source
Community implementieren konnten.  MetaGer gibt es nun in Englisch und
demnächst dank der Hilfe eines engagierten Auslandsdeutschen auch in
Spanisch.  SIE alle haben damit den Betrieb und die Weiterentwicklung von
MetaGer für die nächste Zeit gesichert.
<p>
Wenn Sie darüber hinaus an MetaGer mitarbeiten und auch programmieren
möchten: Sie finden den Einstieg dazu und den Quellcode unter
<a href="https://gitlab.metager3.de/open-source/MetaGer" target="_blank">https://gitlab.metager3.de/open-source/MetaGer</a> (Hintergrundinfo dazu im
Heise-Ticker unter <a href="http://heise.de/-3295586" target="_blank">http://heise.de/-3295586</a>) - wir freuen uns auf die
Zusammenarbeit mit Ihnen!
<p>
Am 18. Juli 2016 um 15.26 Uhr hatte der SUMA-EV als Betreiber von metager.de
den Spendenaufruf auf der MetaGer-Website gestartet.  Keine zwei Minuten
später gingen die ersten Spenden ein.  Und das ging bis heute so weiter -
es ist unglaublich toll zu erleben, wie unsere Nutzer die unabhängige und
nicht-kommerzielle Internet-Suche mit MetaGer unterstützen!  Sowohl mit
Spenden als auch mit motivierenden Worten.
<p>
Wir DANKEN! Und wir beenden den Spendenaufruf heute. Die Finanzierung
unserer freien und unabhängigen Internetsuche ist nun für die nächste Zeit
gesichert.
<p>
<b>Wir wissen jetzt, dass wir uns auf unsere User verlassen können. Das
ist ein tolles Gefühl!</b>
<p>
P.S.: Wir haben auch allen, die dies zu erkennen gegeben haben, auf Wunsch
Spendenbescheinigungen gesandt.  Bei einigen Spendern war das nicht möglich,
weil keine Adresse angegeben war.  Wir können auch nicht ausschließen, dass
wir evtl. jemanden übersehen haben.  In diesem wie in jenem Fall melden Sie
sich bitte noch einmal.
<p>
Herzliche Grüße,
das MetaGer-Team im SUMA-EV
</p>
<div class="" style="margin-top:50px">
	<h1>Weiter spenden:</h1>s
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
			{{ csrf_field() }}
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
