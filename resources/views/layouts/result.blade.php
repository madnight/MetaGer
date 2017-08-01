<div class="result row" data-count="{{ $result->number }}">
	<div class="number col-sm-1 hidden-xs" style="color:{{ $result->color }}">
		{{ $result->number }})
	</div>
	<div class="resultInformation col-xs-12 col-sm-11">
		<div class="col-xs-10 col-sm-11" style="padding:0; ">
			<p class="title">
				<a class="title" href="{{ $result->link }}" target="{{ $metager->getNewtab() }}" data-hoster="{{ strip_tags($result->gefVon) }}" data-count="{{ $result->number }}"  rel="noopener">
					{{ $result->titel }}
				</a>
			</p>
			<div class="link">
				<div>
					<div class="link-link">
						<a href="{{ $result->link }}" target="{{ $metager->getNewtab() }}" data-hoster="{{ strip_tags($result->gefVon) }}" data-count="{{ $result->number }}" rel="noopener">
							{{ $result->anzeigeLink }}
						</a>
					</div>
					<div class="options">
							<a class="option-opener" href="javascript:void(0);">
								<i class="fa fa-caret-down option-opener-icon" aria-hidden="true"></i>
							</a>
							<div class="option-content">
								<ul class="option-list list-unstyled small">
									<li class="option-title">
										<i class="fa fa-cog"></i> {!! trans('result.options.headline') !!}
									</li>
									<hr>
									<li class="js-only">
										<a href="javascript:resultSaver({{ $result->number }});" class="saver" data-counter="{{ $result->number }}">
											<i class="fa fa-floppy-disk"></i> {!! trans('result.options.savetab') !!}
										</a>
									</li>
									@if(strlen($metager->getSite()) === 0)
										<li>
											<a href="{{ $metager->generateSiteSearchLink($result->strippedHost) }}">
												{!! trans('result.options.1') !!}
											</a>
										</li>
									@endif
									<li>
										<a href="{{ $metager->generateRemovedHostLink($result->strippedHost) }}">
											{!! trans('result.options.2', ['host' => $result->strippedHost]) !!}
										</a>
									</li>
									@if( $result->strippedHost !== $result->strippedDomain )
										<li>
											<a href="{{ $metager->generateRemovedDomainLink($result->strippedDomain) }}">
												{!! trans('result.options.3', ['domain' => $result->strippedDomain]) !!}
											</a>
										</li>
									@endif
								</ul>
							</div>
					</div>
				</div>
				<span class="hoster">
					von {!! $result->gefVon !!}
				</span>
				@if( isset($result->partnershop) && $result->partnershop === TRUE )
					<span class="partnershop-info">
					<img src="/img/boosticon.png" height="13" alt="">
					<a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), "/partnershops") }}" target="_blank" rel="noopener">{!! trans('result.options.4') !!}</a>
					</span>
				@endif
				<a class="proxy" onmouseover="$(this).popover('show');" onmouseout="$(this).popover('hide');" data-toggle="popover" data-placement="auto right" data-container="body" data-content="@lang('result.proxytext')" href="{{ $result->proxyLink }}" target="{{ $metager->getNewtab() }}" rel="noopener">
					<img src="/img/proxyicon.png" alt="" />
					{!! trans('result.options.5') !!}
				</a>
			</div>
		</div>
		@if( isset($result->logo) )
			<div class="col-xs-2 col-sm-1" style="padding: 0;">
				<a href="{{ $result->link }}" target="{{ $metager->getNewtab() }}" data-hoster="{{ strip_tags($result->gefVon) }}" data-count="{{ $result->number }}">
					<img src="{{ $metager->getImageProxyLink($result->logo) }}" alt="" />
				</a>
			</div>
		@endif
		@if( $result->image !== "" )
			<div class="description">
				<a href="{{ $result->link }}" target="{{ $metager->getNewtab() }}" data-hoster="{{ strip_tags($result->gefVon) }}" data-count="{{ $result->number }}"  rel="noopener">
					<img src="{{ $metager->getImageProxyLink($result->image) }}" align="left" width="120px" height="60px" alt="" />
				</a>
				{!! $result->descr !!}
			</div>
		@else
			@if( $metager->getFokus() == "nachrichten" )
			<div class="description"><span class="date">{{ isset($result->additionalInformation["date"])?date("Y-m-d H:i:s", $result->additionalInformation["date"]):"" }}</span> {{ $result->descr }}</div>
			@else
			<div class="description">{{ $result->descr }}</div>
			@endif
		@endif
	</div>

</div>
