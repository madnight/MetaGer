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
    @if($result->number % 7 === 0)
      <ad:advertisement>
       <ad:callOut atom:type="TEXT">[Ad]</ad:callOut>
       <ad:title atom:type="TEXT">20% Off Coffee</ad:title>
       <ad:subTitle atom:type="TEXT">Walk in and show us this ad on your phone</ad:subTitle>
       <ad:displayUrl atom:type="TEXT">example.com/coffee</ad:displayUrl>
       <ad:image ad:id="cprp20" ad:width="170" ad:height="30">
         <ad:link href="http://example.com/ads/20_off_coffee.jpg" />
         <ad:altText atom:type="TEXT">Click for Cafes Near You</ad:altText>
       </ad:image>
       <atom:link href="http://example.com/coffee/" />
       <ad:id>1234567890</ad:id>
   </ad:advertisement> 
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
