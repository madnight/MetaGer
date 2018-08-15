<!-- Matomo -->
<script type="text/javascript">
var _paq = _paq || [];
/* tracker methods like "setCustomDimension" should be called before "trackPageView" */
_paq.push(["setDocumentTitle", document.domain + "/" + document.title]);
_paq.push(["setCookieDomain", "*.metager.de"]);
_paq.push(["disableCookies"]);
_paq.push(['trackPageView']);
_paq.push(['enableLinkTracking']);
(function() {
	var u="//piwik.metager3.de/";
	_paq.push(['setTrackerUrl', u+'piwik.php']);
	_paq.push(['setSiteId', '1']);
	var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
	g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
})();
</script>
<noscript><p><img src="//piwik.metager3.de/piwik.php?idsite=1&amp;rec=1&amp;url={{ url()->full() }}&amp;action_name={{ $eingabe }} - MetaGer&amp;rand={{ rand(0,1000000) }}" style="border:0;" alt="" /></p></noscript>
<!-- End Matomo Code -->
@if( sizeof($errors) > 0 )
	<div class="alert alert-danger">
		<ul>
			@foreach($errors as $error)
				<li>{!! $error !!}</li>
			@endforeach
		</ul>
	</div>
@endif
@if( sizeof($warnings) > 0)
	<div class="alert alert-warning">
		<ul>
			@foreach($warnings as $warning)
				<li>{!! $warning !!}</li>
			@endforeach
		</ul>
	</div>
@endif
<div class="col-xs-12 col-md-8">
	@for($i = 0; $i <= 2; $i++)
		@include('layouts.ad', ['ad' => $metager->popAd()])
	@endfor
	@foreach($metager->getResults() as $result)
		@if($result->number % 7 === 0)
			@include('layouts.ad', ['ad' => $metager->popAd()])
		@endif
		@include('layouts.result', ['result' => $result])
	@endforeach
	<nav aria-label="...">
		<ul class="pager">
			<li @if($metager->getPage() === 1) class="disabled" @endif><a href="@if($metager->getPage() === 1) # @else javascript:history.back() @endif">{{ trans('results.zurueck') }}</a></li>
			<li @if($metager->nextSearchLink() === "#") class="disabled" @endif><a href="{{ $metager->nextSearchLink() }}">{{ trans('results.weiter') }}</a></li>
		</ul>
	</nav>
</div>
@if( $metager->showQuicktips() )
	<div class="col-md-4 hidden-xs hidden-sm" id="quicktips"></div>
@endif
</div>
