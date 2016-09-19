<div class="row">
	<div class="col-sm-1 hidden-xs"></div>
	<div class="resultInformation col-xs-12 col-sm-11">
		<span id="mark">Anzeige</span>
		<ul id="lightSlider" >
			@foreach($products as $product)
			<li>
			    <div class="product">
			      	<a href="{{$product["link"]}}" title="{{$product["titel"]}}" target="_blank">
				      	<div class="price">{!!$product["price"]!!}</div>
				        <img src="{{ $product["image"] }}" />
				        <p class="title">{{$product["titel"]}}</p>
				        <p class="shop">{{$product["gefVon"]}}</p>
				        <p class="shipping">Versand gratis</p>
				    </a>
			    </div>
			</li>
			@endforeach
		</ul>
	</div>
</div>
