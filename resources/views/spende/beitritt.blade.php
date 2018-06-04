@extends('layouts.subPages')

@section('title', $title )

@section('navbarFocus.donate', 'class="dropdown active"')

@section('content')
	<link type="text/css" rel="stylesheet" href="{{ mix('/css/beitritt.css') }}" />
	<h1>{{ trans('beitritt.heading.1') }}</h1>
	<div class="beitritt-info">
		<h3>{{ trans('beitritt.ansprache.1') }}</h3>
		<h3>{{ trans('beitritt.ansprache.2') }}</h3>
		<h3>{{ trans('beitritt.ansprache.3') }}</h3>
		<h3>{{ trans('beitritt.ansprache.4') }}</h3>
		<h3>{!! trans('beitritt.ansprache.5') !!}</h3>
	</div>
	<form>
		<div>
			<label>{{ trans('beitritt.beitritt.1') }}</label>
			<br>
			<input type="radio" name="membershipType" required> {{ trans('beitritt.radioperson') }}
			<input type="radio" name="membershipType"required> {{ trans('beitritt.radiofirma') }}
			{{ trans('beitritt.beitritt.2') }}
		</div>
		<div class="col-sm-6">
			<div class="form-group beitritt-form-group">
				<label for="name" class="non-bold beitritt-required-fields">{{ trans('beitritt.beitritt.3') }}</label>
				<input type="text" class="form-control beitritt-input" id="name" placeholder="{{trans('beitritt.placeholder.3')}}" required>

			</div>
		</div>	
		<div class="col-sm-6">
			<div class="form-group beitritt-form-group">
				<label for="firma" class="non-bold beitritt-required-fields">{{ trans('beitritt.beitritt.4') }}</label>
				<input type="text" class="form-control beitritt-input" id="firma" placeholder="">
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group beitritt-form-group">
				<label for="funktion" class="non-bold">{{ trans('beitritt.beitritt.5') }}</label>
				<input type="text" class="form-control beitritt-input" id="funktion" placeholder="">
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group beitritt-form-group">
				<label for="webpage" class="non-bold">{{ trans('beitritt.beitritt.11') }}</label>
				<input type="text" class="form-control beitritt-input" id="webpage" placeholder="http://">
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group beitritt-form-group">
				<label for="adresse" class="non-bold beitritt-required-fields">{{ trans('beitritt.beitritt.6') }}</label>
				<input type="text" class="form-control beitritt-input" id="adresse" placeholder="{{trans('beitritt.placeholder.6')}}" required>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group beitritt-form-group">
				<label for="email" class="non-bold beitritt-required-fields">{{ trans('beitritt.beitritt.10') }}</label>
				<input type="email" class="form-control beitritt-input" id="email" required>
			</div>
		</div>
		<div class="form-group beitritt-form-group">
			<p>{!! trans('beitritt.ansprache.6') !!}</p>
			<p>{!! trans('beitritt.ansprache.7') !!}</p>
			<input type="radio" name="veröffentlichung"> {{ trans('beitritt.radiozustimmung') }}
			<input type="radio" name="veröffentlichung"> {{ trans('beitritt.radioablehnung') }}
		</div>
		<div class="row">
			<p>{{ trans('beitritt.ansprache.8') }}
			<div>
				<label for="beitrag">{{ trans('beitritt.beitritt.12') }}</label>
				<input type="text" class="form-control beitritt-input" id="beitrag" placeholder="">
				<label for="beitrag">{{ trans('beitritt.beitritt.13') }}</label>
			</div>
			<br>
			<p class="signature">{{ trans('beitritt.unterschrift') }}</p>
		</div>
		<hr>
		<h1>{{ trans('beitritt.abbuchung.2') }}</h1>
		<p>{{ trans('beitritt.abbuchung.3') }}</p>
		<div class="form-group beitritt-form-group">
			<label for="kontoname" class="non-bold beitritt-required-fields">{{ trans('beitritt.abbuchung.4') }}</label>
			<input type="text" class="form-control" id="kontoname" placeholder="">
		</div>
		<div class="row">
			<div class="col-sm-4 form-group beitritt-form-group">
				<label for="bankverbindung" class="non-bold beitritt-required-fields">{{ trans('beitritt.abbuchung.5') }}</label>
				<input type="text" class="form-control" id="bankverbindung" placeholder="">
			</div>
			<div class="col-sm-5 form-group beitritt-form-group">
				<label for="iban" class="non-bold beitritt-required-fields">{{ trans('beitritt.abbuchung.6') }}</label>
				<input type="text" class="form-control" id="iban" maxlength="22" placeholder="">
			</div>
			<div class="col-sm-3 form-group beitritt-form-group">
				<label for="bic" class="non-bold beitritt-required-fields">{{ trans('beitritt.abbuchung.7') }}</label>
				<input type="text" class="form-control" id="bic" placeholder="">
			</div>
		</div>
		<br>
		<p class="signature">{{ trans('beitritt.unterschrift') }}</p>
	</form>
	<hr>
	<div class="beitritt-formular-info">
		<p>{{ trans('beitritt.anweisung.1') }}</p>
		<ul class="dotlist">
			<li>{{ trans('beitritt.anweisung.2') }}</li>
			<li>{{ trans('beitritt.anweisung.3') }}</li>
			<li>{{ trans('beitritt.anweisung.4') }}</li>
		</ul>
		<p>{{ trans('beitritt.anweisung.5') }}</p>
		<p>{{ trans('beitritt.anweisung.6') }}</p>
	</div>
	<button type="button" class="btn btn-lg btn-primary noprint" onclick="window.print();">{{ trans('beitritt.anweisung.7') }}</button>
	<!-- <script src="{{ mix('js/scriptJoinPage.js') }}"></script> -->
@endsection
