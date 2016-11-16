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
		<input type="text" class="form-control" name="firma" placeholder="{{trans('beitritt.placeholder.2')}}" />
	</div>
	<div class="form-group beitritt-form-group">
		<label for="funktion" class="non-bold">{{ trans('beitritt.beitritt.3') }}</label>
		<input type="text" class="form-control" name="funktion" placeholder="{{trans('beitritt.placeholder.3')}}" />
	</div>
	<div class="form-group beitritt-form-group">
		<label for="adresse" class="non-bold">{{ trans('beitritt.beitritt.4') }}</label>
		<input type="text" class="form-control" name="adresse" placeholder="{{trans('beitritt.placeholder.4')}}" required/>
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
		<input type="text" class="form-control" name="telefon" placeholder="{{trans('beitritt.placeholder.7')}}"/>
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
{{ trans('beitritt.abbuchung.1') }}</p>
	<h3>{{ trans('beitritt.abbuchung.2') }}</h3>
	<p>{{ trans('beitritt.abbuchung.3') }} </p>
	<div class="form-group beitritt-form-group">
		<label for="kontoname" class="non-bold">{{ trans('beitritt.abbuchung.4') }}</label>
		<input type="text" class="form-control" name="kontoname" placeholder=""/>
	</div>
	<div class="form-group beitritt-form-group">
		<label for="bankverbindung" class="non-bold">{{ trans('beitritt.abbuchung.5') }}</label>
		<input type="text" class="form-control" name="bankverbindung" placeholder=""/>
	</div>
	<div class="form-group beitritt-form-group">
		<label for="iban" class="non-bold">{{ trans('beitritt.abbuchung.6') }}</label>
		<input type="text" class="form-control" name="iban" placeholder=""/>
	</div>
	<div class="form-group beitritt-form-group">
		<label for="bic" class="non-bold">{{ trans('beitritt.abbuchung.7') }}</label>
		<input type="text" class="form-control" name="bic" placeholder=""/>
	</div>
	<div class="form-group beitritt-form-group">
		<label for="ort2" class="non-bold">{{ trans('beitritt.abbuchung.8') }}</label>
		<input type="text" class="form-control" id="ort2" placeholder=""/>
	</div>
	<br />
	<p class="sign">---------------------------------------------------------
{{ trans('beitritt.abbuchung.9') }}</p>
</form>
<hr />
<p>{{ trans('beitritt.anweisung.1') }}</p>
<ul class="dotlist">
<li>{{ trans('beitritt.anweisung.2') }} </li>
<li>{{ trans('beitritt.anweisung.3') }} </li>
<li>{{ trans('beitritt.anweisung.4') }} </li>
</ul>
<p>{{ trans('beitritt.anweisung.5') }}</p>
<p>{{ trans('beitritt.anweisung.6') }} </p>
<button type="button" class="btn btn-lg btn-primary noprint" onclick="window.print();">{{ trans('beitritt.anweisung.7') }}</button>
@endsection
