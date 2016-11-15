@extends('layouts.subPages')

@section('title', $title )

@section('navbarFocus.donate', 'class="dropdown active"')

@section('content')
<link type="text/css" rel="stylesheet" href="/css/beitritt.css" />
<h1>{{ trans('beitritt.heading.1') }}</h1>
<form>
	<div class="form-group beitritt-form-group">
		<label for="name" class="non-bold">{{ trans('beitritt.beitritt.1') }}</label>
		<input type="text" class="form-control" name="name" placeholder="{{trans('beitritt.placeholder.1')}}" required/>
	</div>
	<div class="form-group beitritt-form-group">
		<label for="firma" class="non-bold">{{ trans('beitritt.beitritt.2') }}</label>
		<input type="text" class="form-control" name="firma" placeholder="Diese Zeile nur beim Beitritt von Firmen ausfüllen" />
	</div>
	<div class="form-group beitritt-form-group">
		<label for="funktion" class="non-bold">{{ trans('beitritt.beitritt.3') }}</label>
		<input type="text" class="form-control" name="funktion" placeholder="hier ggf. wenn Sie wollen oder beim Firmenbeitritt: Ihren Beruf/Funktion" />
	</div>
	<div class="form-group beitritt-form-group">
		<label for="adresse" class="non-bold">{{ trans('beitritt.beitritt.4') }}</label>
		<input type="text" class="form-control" name="adresse" placeholder="Straße Hausnummer, Postleitzahl, Ort" required/>
	</div>
	<div class="form-group beitritt-form-group">
		<label for="email" class="non-bold">{{ trans('beitritt.beitritt.5') }}</label>
		<input type="email" class="form-control" name="email" placeholder=""/>
	</div>
	<div class="form-group beitritt-form-group">
		<label for="homepage" class="non-bold">{{ trans('beitritt.beitritt.6') }}</label>
		<input type="text" class="form-control" name="homepage" placeholder="http://"/>
	</div>
	<div class="form-group beitritt-form-group">
		<label for="telefon" class="non-bold">{{ trans('beitritt.beitritt.7') }}</label>
		<input type="text" class="form-control" name="telefon" placeholder="Festnetz und ggf. Handy"/>
	</div>
	<div class="form-group beitritt-form-group">
		<label class="non-bold" for="betrag">{{ trans('beitritt.beitritt.8') }}</label>
		<div class="row">
			<div class="col-xs-2">
				<input type="text" class="form-control" name="betrag" />
			</div>
			<div class="col-xs-2">
				<p class="help-block"> {{ trans('beitritt.beitritt.9') }}</p>
			</div>
		</div>
	</div>
	<label class="non-bold">
		{{ trans('beitritt.beitritt.10') }}
	</label>
	<label class="non-bold">
		{{ trans('beitritt.beitritt.11') }}
	</label>
	<div class="row">
		<div class="col-xs-2">
			<div class="radio">
				<label>
					<input type="radio" name="veröffentlichung" checked> {{ trans('beitritt.beitritt.12') }}
				</label>
			</div>
		</div>
		<div class="col-xs-2">
			<div class="radio">
				<label>
					<input type="radio" name="veröffentlichung"> {{ trans('beitritt.beitritt.13') }}
				</label>
			</div>
		</div>
	</div>
	<div class="form-group beitritt-form-group">
		<label for="ort">{{ trans('beitritt.beitritt.14') }}</label>
		<input type="text" class="form-control" id="ort" placeholder=""/>
	</div>
	<br />

	<p class="sign">---------------------------------------------------------
Unterschrift nach Ausdrucken des Formulars</p>
	<h3>Abbuchungsermächtigung</h3>
	<p>Hiermit ermächtige ich den "SUMA-EV - Verein für freien Wissenszugang" den o.g. Mitgliedsbeitrag von meinem Konto abzubuchen. </p>
	<div class="form-group beitritt-form-group">
		<label for="kontoname" class="non-bold">Name des Kontoinhabers:</label>
		<input type="text" class="form-control" name="kontoname" placeholder=""/>
	</div>
	<div class="form-group beitritt-form-group">
		<label for="bankverbindung" class="non-bold">Bankverbindung, Name der Bank:</label>
		<input type="text" class="form-control" name="bankverbindung" placeholder=""/>
	</div>
	<div class="form-group beitritt-form-group">
		<label for="iban" class="non-bold">IBAN (oder Konto-Nummer)</label>
		<input type="text" class="form-control" name="iban" placeholder=""/>
	</div>
	<div class="form-group beitritt-form-group">
		<label for="bic" class="non-bold">BIC (oder Bankleitzahl)</label>
		<input type="text" class="form-control" name="bic" placeholder=""/>
	</div>
	<div class="form-group beitritt-form-group">
		<label for="ort2" class="non-bold">Ort, Datum:</label>
		<input type="text" class="form-control" id="ort2" placeholder=""/>
	</div>
	<br />
	<p class="sign">---------------------------------------------------------
Unterschrift nach Ausdrucken des Formulars</p>
</form>
<hr />
<p>Bitte drucken Sie das Formular nach Ausfüllen aus und unterschreiben an beiden ......... Linien; dann können Sie es</p>
<ul class="dotlist">
<li>faxen an 0511 34 00 10 23 (und schicken uns eine kurze EMail dazu, dass Sie das Beitrittsformular gefaxt haben, denn Faxgeräte sind manchmal inkompatibel) oder </li>
<li>per Post senden an: SUMA-EV, Röselerstr. 3, 30159 Hannover oder </li>
<li>einscannen und an office@suma-ev.de mailen. </li>
</ul>
<p>Beim Versenden per Post oder Fax informieren Sie uns bitte kurz per email an office@suma-ev.de</p>
<p>Mitgliedsbeiträge an den SUMA-EV sind steuerlich absetzbar, da der Verein vom Finanzamt Hannover Nord als gemeinnützig anerkannt ist. Eine Spendenbescheinigung wird auf Wunsch im Januar oder Februar des Folgejahres zugesandt. </p>
<button type="button" class="btn btn-lg btn-primary noprint" onclick="window.print();">Drucken</button>
@endsection
