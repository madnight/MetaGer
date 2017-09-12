<?xmlversion = "1.0"encoding = "UTF-8"?>
 <feed xmlns="http://www.w3.org/2005/Atom" 
       xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/"
       xmlns:mg="http://metager.de/opensearch/"
       xmlns:advertisement="http://a9.com/-/opensearch/extensions/advertisement/1.0/">
     <title>{!! htmlspecialchars($eingabe, ENT_XML1, 'UTF-8'); !!} - MetaGer</title>
     <link href="{{ url()->full() }}"/>
     <updated>{{ date('c') }}</updated>
     <opensearch:totalResults>{{ $resultcount }}</opensearch:totalResults>
     <opensearch:Query role="request" searchTerms="{{ htmlspecialchars($eingabe, ENT_QUOTES) }}"/>
     <link rel="next" href="{{ htmlspecialchars($metager->nextSearchLink() ,ENT_QUOTES) }}" type="application/atom+xml"/>
     <id>urn:uuid:1d634a8c-2764-424f-b082-6c96494b7240</id>
     @include('layouts.atom10-ad', ['ad' => $metager->popAd()])
  @foreach($metager->getResults() as $result)
    @if($result->number % 5 === 0)
      @include('layouts.atom10-ad', ['ad' => $metager->popAd()])
    @endif
     <entry>
       <title>{!! htmlspecialchars($result->titel, ENT_XML1, 'UTF-8'); !!}</title>
       <link href="{!! htmlspecialchars($result->link, ENT_XML1, 'UTF-8'); !!}" />
       <mg:anzeigeLink>{!! htmlspecialchars($result->anzeigeLink, ENT_XML1, 'UTF-8'); !!}</mg:anzeigeLink>
       <content type="text">
          {!! htmlspecialchars($result->longDescr, ENT_XML1, 'UTF-8'); !!}
       </content>
     </entry>
  @endforeach
  
 </feed>

 <!-- Muster zu finden unter http://www.opensearch.org/Specifications/OpenSearch/1.1#Example_of_OpenSearch_response_elements_in_RSS_2.0 --> 
