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
<div id="container">
	@foreach($metager->getResults() as $result)
		<div class="item">
			<div class="img">
				<a href="{{ $result->link }}" target="{{ $metager->getNewtab() }}"><img src="{{ $metager->getImageProxyLink($result->image) }}" width="150px" alt="" rel="noopener"/></a>
				<span class="label label-default hostlabel">{{ strip_tags($result->gefVon) }}</span>
			</div>
		</div>
	@endforeach
</div>
<nav aria-label="...">
		<ul class="pager">
		    <li @if($metager->getPage() === 1) class="disabled" @endif><a href="@if($metager->getPage() === 1) # @else javascript:history.back() @endif">{{ trans('results.zurueck') }}</a></li>
			<li @if($metager->nextSearchLink() === "#") class="disabled" @endif><a href="{{ $metager->nextSearchLink() }}">{{ trans('results.weiter') }}</a></li>
		</ul>
	</nav>
