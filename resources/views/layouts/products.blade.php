<div class="row lightSliderContainer hidden">
	<div class="col-sm-1 hidden-xs"></div>
	<div class="resultInformation col-xs-12 col-sm-11">
		<span id="mark">Produktanzeigen:</span>
		<ul class="lightSlider" >
			@foreach($products as $product)
			<li>
			    <div class="product">
			      	<a href="{{$product["link"]}}" title="{{$product["titel"]}}" target="_blank">
				      	<div class="price">{!!$product["price"]!!}</div>
				        <img src="{{ $metager->getImageProxyLink($product["image"]) }}" />
				        <p class="title">{{$product["titel"]}}</p>
				        <p class="shop">{{$product["gefVon"]}}</p>
				        <p class="shipping">{{$product["additionalInformation"]["shipping"]}}</p>
				    </a>
			    </div>
			</li>
			@endforeach
		</ul>
	</div>
</div>
