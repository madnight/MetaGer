@extends('layouts.indexPage')

@section('title', $title )

@section('content')
	<div class="modal fade" id="plugin-modal" tab-index="-1" role="dialog">
		<div class="modal-dialog ">
			<div class="content modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4>
						@if ($browser === 'Firefox' || $browser === 'Mozilla')
							{{ trans('index.plugin.head.1') }}
						@elseif ($browser === 'Chrome')
							{{ trans('index.plugin.head.2') }}
						@elseif ($browser === 'Opera')
							{{ trans('index.plugin.head.3') }}
						@elseif ($browser === 'IE')
							{{ trans('index.plugin.head.4') }}
						@elseif ($browser === 'Edge')
							{{ trans('index.plugin.head.5') }}
						@elseif ($browser === 'Safari')
							{{ trans('index.plugin.head.6') }}
						@elseif ($browser === 'Vivaldi')
							{{ trans('index.plugin.head.6') }}
						@else
							$(".seperator").addClass("hidden");
						@endif
					</h4>
					<p class="text-muted">{{ trans('index.plugin.head.info') }}</p>
				</div>
				<div class="modal-body">
					@if ($browser === 'Firefox' || $browser === 'Mozilla')
						<ol>
							<li>{{ trans('index.plugin.firefox.1') }}<img src="/img/Firefox.png" width="100%" /></li>
							<li>{{ trans('index.plugin.firefox.2') }}<img src="/img/Firefox_Standard.png" width="100%" /></li>
						</ol>
						<hr />
						<h4>{!! trans('index.plugin.firefox.3', ['browser' => $browser]) !!}</h4>
						<ol>
							<li>{!! trans('index.plugin.firefox.4') !!}</li>
							<li>{!! trans('index.plugin.firefox.5') !!}</li>
						</ol>
					@elseif ($browser === 'Chrome')
						<ol>
							<li>{!! trans('index.plugin.chrome.1') !!}</li>
							<li>{!! trans('index.plugin.chrome.2') !!}</li>
							<li>{{ trans('index.plugin.chrome.3') }}</li>
						</ol>
						<hr />
						<h4>{!! trans('index.plugin.chrome.4', ['browser' => $browser]) !!}</h4>
						<ol>
							<li>{!! trans('index.plugin.chrome.5') !!}</li>
							<li>{!! trans('index.plugin.chrome.6') !!}</li>
							<li>{!! trans('index.plugin.chrome.7') !!}</li>
							<li>{!! trans('index.plugin.chrome.8') !!}</li>
						</ol>
					@elseif ($browser === 'Opera')
						<ol>
							<li>{!! trans('index.plugin.opera.1') !!}</li>
							<li>{!! trans('index.plugin.opera.2') !!}</li>
							<li>{!! trans('index.plugin.opera.3') !!}</li>
							<li>{!! trans('index.plugin.opera.4') !!}</li>
							<li><small>{!! trans('index.plugin.opera.5') !!}</small>
						</ol>
						<hr />
						<h4>{!! trans('index.plugin.opera.6', ['browser' => $browser]) !!}</h4>
						<ol>
							<li>{!! trans('index.plugin.opera.7') !!}</li>
							<li>{!! trans('index.plugin.opera.8') !!}</li>
							<li>{!! trans('index.plugin.opera.9') !!}</li>
							<li>{!! trans('index.plugin.opera.10') !!}</li>
						</ol>
					@elseif ($browser === 'IE')
						<ol>
							<li>{!! trans('index.plugin.IE.1') !!}</li>
							<li>{!! trans('index.plugin.IE.4') !!} (<i class="fa fa-cog" aria-hidden="true"></i>)</li>
							<li>{!! trans('index.plugin.IE.5') !!}</li>
							<li>{!! trans('index.plugin.IE.6') !!}</li>
							<li>{!! trans('index.plugin.IE.7') !!}</li>
						</ol>
						<hr />
						<h4>{!! trans('index.plugin.IE.8', ['browser' => $browser]) !!}</h4>
						<ol>
							<li>{!! trans('index.plugin.IE.9') !!}</li>
							<li>{!! trans('index.plugin.IE.10') !!}</li>
							<li>{!! trans('index.plugin.IE.11') !!}</li>
						</ol>
					@elseif ($browser === 'Edge')
						<ol>
							<li>{!! trans('index.plugin.edge.1') !!}<i class="fa fa-ellipsis-h" aria-hidden="true"></i>{!! trans('index.plugin.edge.2') !!}</li>
							<li>{!! trans('index.plugin.edge.3') !!}</li>
							<li>{!! trans('index.plugin.edge.4') !!}</li>
							<li>{!! trans('index.plugin.edge.5') !!}</li>
						</ol>
						<hr />
						<h4>{!! trans('index.plugin.edge.6', ['browser' => $browser]) !!}</h4>
						<ol>
							<li>{!! trans('index.plugin.edge.7') !!}</li>
							<li>{!! trans('index.plugin.edge.8') !!}</li>
							<li>{!! trans('index.plugin.edge.9') !!}</li>
							<li>{!! trans('index.plugin.edge.10') !!}</li>
							<li>{!! trans('index.plugin.edge.11') !!}</li>
						</ol>
					@elseif ($browser === 'Safari')
						<ol>
							<li>{!! trans('index.plugin.safari.1') !!}</li>
							<li>{!! trans('index.plugin.safari.2') !!}</li>
							<li>{!! trans('index.plugin.safari.3') !!}</li>
							<li>{!! trans('index.plugin.safari.4') !!}</li>
						</ol>
					@elseif ($browser === 'Vivaldi')
						<ol>
							<li>{!! trans('index.plugin.vivaldi.1') !!}</li>
							<li>{!! trans('index.plugin.vivaldi.2') !!}</li>
							<li>{!! trans('index.plugin.vivaldi.3') !!}</li>
							<li>{!! trans('index.plugin.vivaldi.4') !!}</li>
							<li>{!! trans('index.plugin.vivaldi.5') !!}</li>
							<li>{!! trans('index.plugin.vivaldi.6') !!}</li>
							<li>{!! trans('index.plugin.vivaldi.7') !!}</li>
						</ol>
						<hr />
						<h4>{!! trans('index.plugin.vivaldi.8', ['browser' => $browser]) !!}</h4>
						<ol>
							<li>{!! trans('index.plugin.vivaldi.9') !!}</li>
							<li>{!! trans('index.plugin.vivaldi.10') !!}</li>
						</ol>
					@endif
					<hr>
					<p>@lang('index.plugin.faq.1')<a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/faq#mg-plugin") }}">@lang('index.plugin.faq.2')</a></p>
				</div>
			</div>
		</div>
	</div>
	<div id="create-focus-modal" class="modal fade" tab-index="-1" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="content modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4>
						@lang("index.focus-creator.head")
					</h4>
					<p class="text-muted">@lang("index.focus-creator.description")</p>
				</div>
				<div class="modal-body">
					<label for="focus-name">@lang('index.focus-creator.focusname')</label>
					<input id="focus-name" type="text" name="focus-name" placeholder="@lang('index.focus-creator.name-placeholder')">
					<input id="original-id" type="hidden" name="original-id" value="">
					{{--
					<h2>{!! trans('settings.suchmaschinen.1') !!} <small><button type="button" class="btn btn-link allUnchecker hide">{!! trans('settings.suchmaschinen.2') !!}</button></small></h2>
					--}}
					@foreach( $foki as $fokus => $sumas )
						<div class="headingGroup {{ $fokus }}">
							<h3 class="focus-category">
								@lang("settings.foki." . $fokus)
								{{--
								<small>
									<button type="button" class="checker btn btn-link hide" data-type="{{ $fokus }}">{!! trans('settings.suchmaschinen.3') !!}</button>
								</small>
								--}}
							</h3>
							<div class="row">
								@foreach( $sumas as $name => $data )
									<div class="col-sm-6 col-md-4 col-lg-3">
										<div class="checkbox settings-checkbox">
											<label>
												<input type="checkbox" name="engine_{{ $name }}" class="focusCheckbox"  @if ($fokus == 'web') checked @endif >{{ $data['displayName'] }}
												<a class="settings-icon" target="_blank" rel="noopener" href="{{ $data['url'] }}"><i class="fa fa-link" aria-hidden="true"></i></a>
											</label>
										</div>
									</div>
								@endforeach
							</div>
						</div>
					@endforeach
					<div class="clearfix">
						<div class="pull-right">
							<button id="delete-focus-btn" type="button" class="btn btn-danger">
								@lang('index.focus-creator.delete')
							</button>
							<button id="save-focus-btn" class="btn btn-primary">
								@lang('index.focus-creator.save')
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<h1 id="mglogo"><a class="hidden-xs" href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/") }}">MetaGer</a></h1>
	<!-- Create the focus selection and options -->
	<fieldset id="foki">
		<div class="focus">
			<input id="web" class="focus-radio hide" type="radio" name="focus" value="web" form="searchForm" @if ($focus === 'web') checked @endif required="">
			<label id="web-label" class="focus-label" for="web">
				<i class="fa fa-globe" aria-hidden="true"></i>
				<span class="content">{{ trans('index.foki.web') }}</span>
			</label>
		</div class="focus">
		<div class="focus">
			<input id="bilder" class="focus-radio hide" type="radio" name="focus" value="bilder" form="searchForm" @if ($focus === 'bilder') checked @endif required="">
			<label id="bilder-label" class="focus-label" for="bilder">
				<i class="fa fa-picture-o" aria-hidden="true"></i>
				<span class="content">{{ trans('index.foki.bilder') }}</span>
			</label>
		</div class="focus">
		<div class="focus">
			<input id="nachrichten" class="focus-radio hide" type="radio" name="focus" value="nachrichten" form="searchForm" @if ($focus === 'nachrichten') checked @endif required="">
			<label id="nachrichten-label" class="focus-label" for="nachrichten">
				<i class="fa fa-bullhorn" aria-hidden="true"></i>
				<span class="content">{{ trans('index.foki.nachrichten') }}</span>
			</label>
		</div>
		<div class="focus">
			<input id="wissenschaft" class="focus-radio hide" type="radio" name="focus" value="wissenschaft" form="searchForm" @if ($focus === 'wissenschaft') checked @endif required="">
			<label id="wissenschaft-label" class="focus-label" for="wissenschaft">
				<i class="fa fa-file-text" aria-hidden="true"></i>
				<span class="content">{{ trans('index.foki.wissenschaft') }}</span>
			</label>
		</div>
		<div class="focus">
			<input id="produkte" class="focus-radio hide" type="radio" name="focus" value="produktsuche" form="searchForm" @if ($focus === 'produkte') checked @endif required="">
			<label id="produkte-label" class="focus-label" for="produkte">
				<i class="fa fa-shopping-cart" aria-hidden="true"></i>
				<span class="content">{{ trans('index.foki.produkte') }}</span>
			</label>
		</div>
		{{-- Fix for older Versions --}}
		@if ($focus === 'angepasst')
			<div class="focus">
				<input id="angepasst" class="focus-radio hide" type="radio" name="focus" value="angepasst" form="searchForm" checked required="">
				<label id="anpassen-label" class="focus-label" for="angepasst">
					<i class="fa fa-cog" aria-hidden="true"></i>
					<span class="content">{{ trans('index.foki.angepasst') }}</span>
				</label>
			</div>
		@endif
		<button id="addFocusBtn" class="btn btn-default hide">
			<i class="fa fa-plus" aria-hidden="true"></i>
		</button>
		<a id="settings-btn" class="mutelink btn btn-default" href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "settings") }}">
			<i class="fa fa-cog" aria-hidden="true"></i>
		</a>
	</fieldset>
		<fieldset>
			<form id="searchForm" @if(Request::has('request') && Request::input('request') === "POST") method="POST" @elseif(Request::has('request') && Request::input('request') === "GET") method="GET" @else method="GET" @endif action="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/meta/meta.ger3") }}" accept-charset="UTF-8">
				<div class="input-group">
					<div class="input-group-addon">
						<button type="button" data-toggle="popover" data-html="true" data-container="body" title="{{ trans('index.design') }}" data-content='&lt;ul id="color-chooser" class="list-inline list-unstyled"&gt;
							&lt;li &gt;&lt;a id="standard" data-rgba="255,194,107,1" href="#"&gt;&lt;/a&gt;&lt;/li&gt;
							&lt;li &gt;&lt;a id="standardHard" data-rgba="255,128,0,1" href="#"&gt;&lt;/a&gt;&lt;/li&gt;
							&lt;li &gt;&lt;a id="blue" data-rgba="164,192,230,1" href="#"&gt;&lt;/a&gt;&lt;/li&gt;
							&lt;li &gt;&lt;a id="blueHard" data-rgba="2,93,140,1" href="#"&gt;&lt;/a&gt;&lt;/li&gt;
							&lt;li &gt;&lt;a id="green" data-rgba="177,226,163,1" href="#"&gt;&lt;/a&gt;&lt;/li&gt;
							&lt;li &gt;&lt;a id="greenHard" data-rgba="127,175,27,1" href="#"&gt;&lt;/a&gt;&lt;/li&gt;
							&lt;li &gt;&lt;a id="red" data-rgba="255,92,92,1" href="#"&gt;&lt;/a&gt;&lt;/li&gt;
							&lt;li &gt;&lt;a id="redHard" data-rgba="255,0,0,1" href="#"&gt;&lt;/a&gt;&lt;/li&gt;
							&lt;li &gt;&lt;a id="pink" data-rgba="255,196,246,1" href="#"&gt;&lt;/a&gt;&lt;/li&gt;
							&lt;li &gt;&lt;a id="pinkHard" data-rgba="254,67,101,1" href="#"&gt;&lt;/a&gt;&lt;/li&gt;
							&lt;li &gt;&lt;a id="black" data-rgba="238,238,238,1" href="#"&gt;&lt;/a&gt;&lt;/li&gt;
							&lt;li &gt;&lt;a id="blackHard" data-rgba="50,50,50,1" href="#"&gt;&lt;/a&gt;&lt;/li&gt;
						&lt;/ul&gt;'>
							<i class="fa fa-tint" aria-hidden="true"></i>
						</button>
					</div>
					<input type="text" name="eingabe" required="" autofocus="" autocomplete="{{$autocomplete}}" class="form-control" placeholder="{{ trans('index.placeholder') }}">
					<input type="hidden" name="encoding" value="utf8">
					<input type="hidden" name="lang" value={{ $lang }} >
					<input type="hidden" name="resultCount" value={{ $resultCount }} >
					<input type="hidden" name="time" value={{ $time }} >
					<input type="hidden" name="sprueche" value={{ $sprueche }} >
					<input type="hidden" name="newtab" value={{ $newtab }} >
					<input type="hidden" name="maps" value={{ $maps }} >
					@foreach ($focusPages as $fp)
						<input type="hidden" name={{ $fp }} value="on">
					@endforeach
					<input type="hidden" name="theme" value={{ $theme }}>
					<div class="input-group-addon">
						<button type="submit">
							<i class="fa fa-search" aria-hidden="true"></i>
						</button>
					</div>
				</div>
			</form>
		<div class="visible-xs">
			<a class="mutelink btn btn-default" href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "settings") }}">
				<i class="fa fa-cog" aria-hidden="true"></i>
			</a>
		</div>
		</fieldset>
		<ul class="list-inline searchform-bonus">
			<li><a href="https://www.boost-project.com/de/shops?charity_id=1129&amp;tag=bl" target="_blank" rel="noopener" id="foerdershops" class="btn btn-default mutelink" title="{{ trans('index.partnertitle') }}"><i class="fa fa-shopping-bag" aria-hidden="true"></i> {{ trans('index.conveyor') }}</a></li>
			<li class="hidden-xs seperator">|</li>
			<li id="plug"
			@unless ($browser === 'Firefox' || $browser === 'Mozilla' || $browser === 'Chrome' || $browser === 'Opera' || $browser === 'IE' || $browser === 'Edge' || $browser === 'Safari' || $browser === 'Vivaldi')
				class="hidden"
			@endunless
			>
				<a href="#" data-toggle="modal" data-target="#plugin-modal" class="btn btn-default mutelink" title="{{ trans('index.plugintitle') }}"><i class="fa fa-plug" aria-hidden="true"></i> {{ trans('index.plugin') }}</a>
			</li>
		</ul>
	<script src="{{ elixir('js/lib.js') }}"></script>
	<script src="{{ elixir('js/scriptStartPage.js') }}"></script>
@endsection

@section('optionalContent')
	<section id="moreInformation" class="hidden-xs">
		<h1 class="hidden">{{ trans('index.sponsors.head.1') }}</h1>
		<div class="row">
			<div id="sponsors" class="col-sm-6">
				<h2>{{ trans('index.sponsors.head.2') }}</h2>
				<ul class="startpage">
					<li>{!! trans('index.sponsors.woxikon') !!}</li>
					<li>{!! trans('index.sponsors.gutscheine') !!}</li>
					<li>{!! trans('index.sponsors.kredite') !!}</li>
				</ul>
			</div>
			<div id="about-us" class="col-sm-6">
				<h2>
					<a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "about") }}">{{ trans('index.about.title') }}</a>
				</h2>
				<ul class="startpage">
					<li>{!! trans('index.about.1.1') !!}</li>
					<li>{!! trans('index.about.2.1') !!}</li>
					<li>@lang('index.about.3.1')</li>
				</ul>
			</div>
		</div>
	</section>
@endsection
