@extends('layouts.subPages')

@section('title', $title )

@section('navbarFocus.donate', 'class="dropdown active"')

@section('content')
<h1>MetaGer sagt: <i>Danke!</i></h1>
<p>
Liebe hilfsbereite Menschen und metager-ische Freunde, wir sind überwältigt von der
wundervollen Community um MetaGer: 
Eure großzügige Spendenbereitschaft hilft dabei, MetaGer besser schneller und bekannter
zu machen. 
</p>
<p><b>Wie bereits im Spendenaufruf angekündigt, haben wir für das Jahr 2018 große
Pläne, die wir dank Eurer Hilfe auch in die Tat umsetzen werden.
</b></p>
<p>
Am 12. Dezember 2017 hatte der SUMA-EV als Betreiber von metager.de
den Spendenaufruf auf der MetaGer-Website gestartet.  Keine zwei Minuten
später gingen die ersten Spenden ein.  Und das ging so weiter -
es ist unglaublich toll zu erleben, wie unsere Nutzer die unabhängige und
nicht-kommerzielle Internet-Suche mit MetaGer unterstützen!  Sowohl mit
Spenden als auch mit motivierenden Worten.
</p>
<p>
Wir Danken! Und haben den Spendenaufruf am 23.01.2018 beendet. Die Finanzierung
unserer freien und unabhängigen Internetsuche ist nun für die nächste Zeit
gesichert.
</p>
<p>
P.S.: Wir haben auch allen, die dies zu erkennen gegeben haben, auf Wunsch
Spendenbescheinigungen gesandt.  Bei einigen Spendern war das nicht möglich,
weil keine Adresse angegeben war.  Wir können auch nicht ausschließen, dass
wir evtl. jemanden übersehen haben.  In diesem wie in jenem Fall melden Sie
sich bitte noch einmal.
</p>
<p>
Herzliche Grüße,
das MetaGer-Team im SUMA-EV
</p>
<div class="" style="margin-top:50px">
		<h1 id="formular">Weiter Spenden</h1>
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
					<h3>{!! trans('spende.bitcoins.1') !!}</h3>
                	{!! trans('spende.bitcoins.2') !!}<br/>
                		<a href="bitcoin:174SDRNZqM2WNobHhCDqD1VXbnZYFXNf8V">174SDRNZqM2WNobHhCDqD1VXbnZYFXNf8V</a>
                	</div>
                	<div class="clearfix"></div>
                	<hr>

				</div>
			</div>
		</div>
		<div class="clearfix"></div>
		<hr>
		<div class="col-md-6">
			<h3 id="lastschrift">{!! trans('spende.lastschrift.1') !!}</h3>
		<p>{!! trans('spende.lastschrift.2') !!}</p>
		<form id="donate" role="form" method="POST" action="/spende">
			<input type="hidden" name="dt" value="{{ md5(date('Y') . date('m') . date('d')) }}">
			<div class="form-group donation-form-group">
			<label for="Name">{!! trans('spende.lastschrift.3') !!}</label>
			<input type="text" class="form-control" id="Name" required="" name="Name" placeholder="{!! trans('spende.lastschrift.3.placeholder') !!}" value="{{ old('Name') }}" />
			</div>
			<div class="form-group donation-form-group">
			<label for="email">{!! trans('spende.lastschrift.4') !!}</label>
				<input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ old('email') }}">
			</div>
			<div class="form-group donation-form-group">
			<label for="iban">{!! trans('spende.lastschrift.6') !!}</label>
				<input type="text" class="form-control" id="iban" required="" name="Kontonummer" placeholder="IBAN" value="{{ old('Kontonummer') }}">
			</div>
			<div class="form-group donation-form-group">
			<label for="bic">{!! trans('spende.lastschrift.7') !!}</label>
				<input type="text" class="form-control" id="bic" required="" name="Bankleitzahl" placeholder="BIC" value="{{ old('Bankleitzahl') }}">
			</div>
			<div class="form-group donation-form-group">
			<label for="value">{!! trans('spende.lastschrift.8.value')!!} </label>
				<input type="number" class="form-control" id="value" required="" name="Betrag" placeholder="{!! trans('spende.lastschrift.8.value.placeholder') !!}" value="{{ old('Bankleitzahl') }}">
			</div>
			<div class="form-group donation-form-group">
			<label for="msg">{!! trans('spende.lastschrift.8.message')!!}</label>
			<label for="msg"><u>{!! trans('spende.bankinfo.3')!!}</u></label>
			<textarea class="form-control" id="msg" name="Nachricht" placeholder="{!! trans('spende.lastschrift.8.message.placeholder') !!}">{{ old('Nachricht') }}</textarea>
			</div>
			<button type="submit" form="donate" class="btn btn-default">{!! trans('spende.lastschrift.9') !!}</button>
		</form>
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
