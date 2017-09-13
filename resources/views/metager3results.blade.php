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
	@if($metager->hasProducts())
	    @if( $metager->getFokus() !== "produktsuche" && !$apiAuthorized)
		    @include('layouts.products', ['products' => $metager->getProducts()])
		@endif
	@else
		@for($i = 0; $i <= 2; $i++)
			@include('layouts.ad', ['ad' => $metager->popAd()])
		@endfor
	@endif
	@if($metager->getMaps())
		<div class="result row" id="map">
			<div class="number col-sm-1 hidden-xs"></div>
			<div class="resultInformation col-xs-12 col-sm-11">
				<iframe class="" src="https://maps.metager.de/metager/{{ $metager->getQ() }}" style="width: 100%; height:0; border:0;"></iframe>
			</div>
		</div>
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
