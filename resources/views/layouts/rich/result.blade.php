<article class="search-result card elevation-1">
	<div class="result-content">
        <h1 class="result-title">{{ $result->titel }}</h1>
        <h2 class="result-display-link"><a href="{{ $result->link }}">{{ $result->anzeigeLink }}</a></h2>
        <p class="result-description">{{ $result->descr }}</p>
        <p class="result-source">gefunden von {!! $result->gefVon !!}</p>
		@if( isset($result->logo) )
        	<img class="result-thumbnail" src="{{ $metager->getImageProxyLink($result->logo) }}" alt="" />
		@endif
	</div>
	<div class="result-action-area">
        <a class="result-action primary" href="{{ $result->link }}">Öffnen</a>
        <a class="result-action primary" target="_blank" href="{{ $result->link }}">Neuer Tab</a>
        <a class="result-action" target="_blank" href="{{ $result->proxyLink }}">Anonym Öffnen</a>
	</div>
</article>
