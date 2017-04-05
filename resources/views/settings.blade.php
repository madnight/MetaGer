@extends('layouts.subPages')

@section('title', $title )

@section('content')
	<form id="settings-form" action="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/") }}" method="get">
		<h1>{!! trans('settings.head.1') !!}</h1>
		<p id="lead">{!! trans('settings.head.2') !!}</p>
		<h2>{!! trans('settings.allgemein.1') !!}</h2>
		<input id="settings-focus" type="hidden" name="focus" value="eigene">
		<container>
			<div class="row">
				<div class="col-sm-6 col-md-4 col-lg-3">
					<label class="select-label">@lang("settings.quotes.label")</label>
					<select class="form-control settings-form-control" name="param_sprueche">
						<option value="on" selected>@lang("settings.quotes.on")</option>
						<option value="off">@lang("settings.quotes.off")</option>
					</select>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<label class="select-label">@lang("settings.maps.label")</label>
					<select class="form-control settings-form-control" name="param_maps">
						<option value="on" selected>@lang("settings.maps.on")</option>
						<option value="off">@lang("settings.maps.off")</option>
					</select>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<label class="select-label">@lang("settings.tab.label")</label>
					<select class="form-control settings-form-control" name="param_newtab">
						<option value="on" selected>@lang("settings.tab.new")</option>
						<option value="off">@lang("settings.tab.same")</option>
					</select>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<label class="select-label">{!! trans('settings.language.label') !!}</label>
					<select class="form-control settings-form-control" name="param_lang">
						<option value="all" @if(App::isLocale('de')) selected @endif >{!! trans('settings.language.all') !!}</option>
						<option value="de">{!! trans('settings.language.de') !!}</option>
						<option value="en" @if(App::isLocale('en')) selected @endif>{!! trans('settings.language.en') !!}</option>
					</select>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<label class="select-label">{{ trans('settings.request') }}:</label>
					<select class="form-control settings-form-control" name="request">
						<option value="GET" selected>GET</option>
						<option value="POST">POST</option>
					</select>
				</div>
				<div class="col-sm-6 col-md-4 col-lg-3">
					<label class="select-label">@lang('settings.autocomplete'):</label>
					<select class="form-control settings-form-control" name="param_autocomplete">
						<option value="on" selected>@lang('settings.autocomplete.on')</option>
						<option value="off">@lang('settings.autocomplete.off')</option>
					</select>
				</div>
			</div>
		</container>
		<div id="settingsButtons">
			<a id="settings-abort-btn" class="btn btn-danger mutelink" href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/") }}">@lang('settings.abort')</a>
			<input id="unten" class="btn btn-primary settings-btn" type="submit" value="{!! trans('settings.speichern.1') !!}">
			<input id="save" class="btn btn-primary settings-btn hidden" type="button" data-href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/") }}" value="{!! trans('settings.speichern.2') !!}">
			<input id="plugin" class="btn btn-primary settings-btn" type="submit" value="{!! trans('settings.speichern.3') !!}">
		</div>
	</form>
	<script src="{{ elixir('js/lib.js') }}"></script>
	<script src="{{ elixir('js/settings.js') }}"></script>
@endsection
