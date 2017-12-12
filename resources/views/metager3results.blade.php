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
	@if(!$apiAuthorized && LaravelLocalization::getCurrentLocale() == "de" && strpos(url()->current(), '/beitritt') === false && strpos(url()->current(), '/spendenaufruf') === false)
			<div class="row" style="margin-bottom: 10px">
				<div class="col-sm-1">
				</div>
				<div class="col-sm-10">
				<a href="/spendenaufruf" target="_blank" style="
			    background-color: white;
			    display: inline-block;
			    width: 100%;
			    ">
			    	<img src="/img/aufruf.png" alt="Spendenaufruf SuMa eV" width="100%">
			    </a>
			</div>
			</div>
		@endif
	@if($metager->hasProducts())
	    @if( $metager->getFokus() !== "produktsuche" && !$apiAuthorized)
		    @include('layouts.products', ['products' => $metager->getProducts()])
		@endif
	@else
		@for($i = 0; $i <= 1; $i++)
			@include('layouts.ad', ['ad' => $metager->popAd()])
		@endfor
	@endif
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
