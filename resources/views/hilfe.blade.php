@extends('layouts.subPages')

@section('title', $title )

@section('content')
	<div class="alert alert-warning" role="alert">{!! trans('hilfe.achtung') !!}</div>
	<h1>{!! trans('hilfe.title') !!}</h1>
	<h2>{!! trans('hilfe.einstellungen') !!}</h2>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">{!! trans('hilfe.allgemein.title') !!}</h3>
		</div>
		<div class="panel-body">
			<ul class="dotlist">
				<li>{!! trans('hilfe.allgemein.1') !!}
					<a id="settings-btn" class="mutelink btn btn-default" href="#">
						<i class="fa fa-cog" aria-hidden="true"></i>
					</a>
				</li>
				<li>{!! trans('hilfe.allgemein.2') !!}</li>
				<li>{!! trans('hilfe.allgemein.3') !!}</li>
			</ul>
		</div>
	</div>
	<div id="custom-focus-help" class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">{!! trans('hilfe.suchfokus.title') !!}</h3>
		</div>
		<div class="panel-body">
			<p>{!! trans('hilfe.suchfokus.1') !!}</p>
			<p>{!! trans('hilfe.suchfokus.2') !!}
				<button id="addFocusBtn" class="btn btn-default" data-original-title="" title="">
					<i class="fa fa-plus" aria-hidden="true"></i>
				</button>
			</p>
			<p>{!! trans('hilfe.suchfokus.3') !!}</p>
			<p>{!! trans('hilfe.suchfokus.4') !!}</p>
		</div>
	</div>
	<h2>{!! trans('hilfe.sucheingabe.title') !!}</h2>
	<p>{!! trans('hilfe.sucheingabe.hint') !!}</p>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">{!! trans('hilfe.stopworte.title') !!}</h3>
		</div>
		<div class="panel-body">
			<p>{!! trans('hilfe.stopworte.1') !!}</p>
			<ul class="dotlist">
				<li>{!! trans('hilfe.stopworte.2') !!}</li>
			</ul>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">{!! trans('hilfe.mehrwortsuche.title') !!}</h3>
		</div>
		<div class="panel-body">
			<p>{!! trans('hilfe.mehrwortsuche.1') !!}</p>
			<div class = "well well-sm">der runde tisch</div>
			<p>{!! trans('hilfe.mehrwortsuche.2') !!}</p>
			<ul class="dotlist">
				<li>{!! trans('hilfe.mehrwortsuche.3') !!}</li>
				<div class = "well well-sm">"der" "runde" "tisch"</div>
				<li>{!! trans('hilfe.mehrwortsuche.4') !!}</li>
				<div class = "well well-sm">"der runde tisch"</div>
			</ul>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">{!! trans('hilfe.grossklein.title') !!}</h3>
		</div>
		<div class="panel-body">
			<p>{!! trans('hilfe.grossklein.1') !!}</p>
			<ul class="dotlist">
				<li>{!! trans('hilfe.grossklein.2') !!}</li>
			</ul>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">{!! trans('hilfe.domains.title') !!}</h3>
		</div>
		<div class="panel-body">
			<p>{!! trans('hilfe.domains.sitesearch.explanation') !!}</p>
			<ul class="dotlist">
				<li>{!! trans('hilfe.domains.sitesearch.example.1') !!}
				<div class="well well-sm">{!! trans('hilfe.domains.sitesearch.example.2') !!}</div></li>
				<li>{!! trans('hilfe.domains.sitesearch.example.3') !!}
				<div class="well well-sm">{!! trans('hilfe.domains.sitesearch.example.4') !!}</div></li>
			</ul>
			<p>{!! trans('hilfe.domains.blacklist.explanation') !!}</p>
			<ul class="dotlist">
				<li>{!! trans('hilfe.domains.blacklist.example.1') !!}</li>
				<li>{!! trans('hilfe.domains.blacklist.example.2') !!}
				<div class="well well-sm">{!! trans('hilfe.domains.blacklist.example.3') !!}</div>
				{!! trans('hilfe.domains.blacklist.example.4') !!}</li>
				<li>{!! trans('hilfe.domains.blacklist.example.5') !!}
				<div class="well well-sm">{!! trans('hilfe.domains.blacklist.example.6') !!}</div></li>
			</ul>
			<p>{!! trans('hilfe.domains.showcase.explanation.1') !!}</p>
			<img src="/img/blacklist-tutorial-searchexample.png">
			<p>{!! trans('hilfe.domains.showcase.explanation.2') !!}<p>
			<div id="result_option_showcase" style="margin-top: -300px"></div>
			<div style="margin-top: 315px; margin-bottom: 10px;">
				<div class="popover fade bottom in" role="tooltip" style="top: auto; left: auto; display: block; position: relative">
					<div class="arrow" style="left: 50%;"></div>
					<h3 class="popover-title"><i class="fa fa-cog" aria-hidden="true"></i> Optionen</h3>
					<div class="popover-content">
						<ul class="options-list list-unstyled small">
							<li>
								<a href="javascript:setDummySearch('wikipedia site:de.wikipedia.org')">
									Suche auf dieser Domain neu starten
								</a>
							</li>
							<li>
								<a href="javascript:setDummySearch('wikipedia -site:de.wikipedia.org')">
									de.wikipedia.org ausblenden
								</a>
							</li>
							<li>
								<a href="javascript:setDummySearch('wikipedia -site:*.wikipedia.org')">
									*.wikipedia.org ausblenden
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<script type="text/javascript">
				function setDummySearch(value) {
					document.getElementById("dummy_search").innerHTML = value
				}
			</script>
			<div>
				<p>{!! trans('hilfe.domains.showcase.menu.1') !!}</p>
				<ul class="dotlist">
					<li>{!! trans('hilfe.domains.showcase.menu.2') !!}</li>
					<li>{!! trans('hilfe.domains.showcase.menu.3') !!}</li>
					<li>{!! trans('hilfe.domains.showcase.menu.4') !!}</li>
				</ul>
				<p>{!! trans('hilfe.domains.showcase.menu.5') !!}</p>
			</div>
			<div>
				<div class="well well-sm"><i>meine suche</i> <span id="dummy_search"></span></div>
			</div>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">{!! trans('hilfe.bang.title') !!}</h3>
		</div>
		<div class="panel-body">
			<p>{!! trans('hilfe.bang.1') !!}</p>
		</div>
	</div>
	<h2 id="dienste">{!! trans('hilfe.dienste') !!}</h2>
	<div class="panel panel-default">
		<div id="mg-app" style="margin-top: -100px"></div>
		<div style="margin-top: 100px"></div>
		<div class="panel-heading">
			<h3 class="panel-title">{!! trans('hilfe.app.title') !!}</h3>
		</div>
		<div class="panel-body">
			<p>{!! trans('hilfe.app.1') !!}</p>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">{!! trans('hilfe.plugin.title') !!}</h3>
		</div>
		<div class="panel-body">
			<p>{!! trans('hilfe.plugin.1') !!}</p>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">{!! trans('hilfe.suchwortassoziator.title') !!}</h3>
		</div>
		<div class="panel-body">
			<p>{!! trans('hilfe.suchwortassoziator.1') !!}</p>
			<p>{!! trans('hilfe.suchwortassoziator.2') !!}</p>
			<p>{!! trans('hilfe.suchwortassoziator.3') !!}</p>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">{!! trans('hilfe.widget.title') !!}</h3>
		</div>
		<div class="panel-body">
			<p>{!! trans('hilfe.widget.1') !!}</p>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">{!! trans('hilfe.urlshort.title') !!}</h3>
		</div>
		<div class="panel-body">
			<p>{!! trans('hilfe.urlshort.1') !!}</p>
		</div>
	</div>
	<h3>=> {!! trans('hilfe.dienste.kostenlos') !!}</h3>
	<h2>{!! trans('hilfe.datenschutz.title') !!}</h2>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">{!! trans('hilfe.datenschutz.1') !!}</h3>
		</div>
		<div class="panel-body">
			<p>{!! trans('hilfe.datenschutz.2') !!}</p>
			<p>{!! trans('hilfe.datenschutz.3') !!}</p>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">{!! trans('hilfe.tor.title') !!}</h3>
		</div>
		<div class="panel-body">
			<p>{!! trans('hilfe.tor.1') !!}</p>
			<p>{!! trans('hilfe.tor.2') !!}</p>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">{!! trans('hilfe.proxy.title') !!}</h3>
		</div>
		<div class="panel-body">
			<p>{!! trans('hilfe.proxy.1') !!}</p>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">{!! trans('hilfe.infobutton.title') !!}</h3>
		</div>
		<div class="panel-body">
			<p>{!! trans('hilfe.infobutton.1') !!}</p>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title">{!! trans('hilfe.maps.title') !!}</h3>
		</div>
		<div class="panel-body">
			<p>{!! trans('hilfe.maps.1') !!}</p>
			<p>{!! trans('hilfe.maps.2') !!}</p>
			<p>{!! trans('hilfe.maps.3') !!}</p>
		</div>
	</div>
@endsection
