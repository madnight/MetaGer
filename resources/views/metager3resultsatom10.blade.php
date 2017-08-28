<?xmlversion = "1.0"encoding = "UTF-8"?>
 <feed xmlns="http://www.w3.org/2005/Atom" 
       xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/"
       xmlns:mg="http://metager.de/opensearch/">
     <title>{!! htmlspecialchars($eingabe, ENT_XML1, 'UTF-8'); !!} - MetaGer</title>
     <link href={{ url()->full() }} />
     <opensearch:totalResults>{{ $resultcount }}</opensearch:totalResults>
     <opensearch:Query role="request" searchTerms="{{ htmlspecialchars($eingabe, ENT_QUOTES) }}"/>
     <mg:nextSearchResults url="{{htmlspecialchars($metager->nextSearchLink() ,ENT_QUOTES)}}" />
     <id>urn:uuid:1d634a8c-2764-424f-b082-6c96494b7240</id>
  @if($apiAuthorized)
  @foreach($metager->getResults() as $result)
     <entry>
       <title>{!! htmlspecialchars($result->titel, ENT_XML1, 'UTF-8'); !!}</title>
       <link href="{!! htmlspecialchars($result->link, ENT_XML1, 'UTF-8'); !!}" />
       <mg:anzeigeLink>{!! htmlspecialchars($result->anzeigeLink, ENT_XML1, 'UTF-8'); !!}</mg:anzeigeLink>
       <content type="text">
          {!! htmlspecialchars($result->longDescr, ENT_XML1, 'UTF-8'); !!}
       </content>
     </entry>
  @endforeach
  @endif
 </feed>

 <!-- Muster von http://www.opensearch.org/Specifications/OpenSearch/1.1#Example_of_OpenSearch_response_elements_in_RSS_2.0
 <?xml version="1.0" encoding="UTF-8"?>
 <feed xmlns="http://www.w3.org/2005/Atom" 
       xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/">
   <title>Example.com Search: New York history</title> 
   <link href="http://example.com/New+York+history"/>
   <id>urn:uuid:60a76c80-d399-11d9-b93C-0003939e0af6</id>
   <opensearch:totalResults>4230000</opensearch:totalResults>
   <opensearch:startIndex>21</opensearch:startIndex>
   <opensearch:itemsPerPage>10</opensearch:itemsPerPage>
   <opensearch:Query role="request" searchTerms="New York History" startPage="1" />
   <link rel="alternate" href="http://example.com/New+York+History?pw=3" type="text/html"/>
   <link rel="self" href="http://example.com/New+York+History?pw=3&amp;format=atom" type="application/atom+xml"/>
   <link rel="first" href="http://example.com/New+York+History?pw=1&amp;format=atom" type="application/atom+xml"/>
   <link rel="previous" href="http://example.com/New+York+History?pw=2&amp;format=atom" type="application/atom+xml"/>
   <link rel="next" href="http://example.com/New+York+History?pw=4&amp;format=atom" type="application/atom+xml"/>
   <link rel="last" href="http://example.com/New+York+History?pw=42299&amp;format=atom" type="application/atom+xml"/>
   <link rel="search" type="application/opensearchdescription+xml" href="http://example.com/opensearchdescription.xml"/>
   <entry>
     <title>New York History</title>
     <link href="http://www.columbia.edu/cu/lweb/eguids/amerihist/nyc.html"/>
     <id>urn:uuid:1225c695-cfb8-4ebb-aaaa-80da344efa6a</id>
     <updated>2003-12-13T18:30:02Z</updated>
     <content type="text">
       ... Harlem.NYC - A virtual tour and information on 
       businesses ...  with historic photos of Columbia's own New York 
       neighborhood ... Internet Resources for the City's History. ...
     </content>
   </entry>
 </feed>
 --> 