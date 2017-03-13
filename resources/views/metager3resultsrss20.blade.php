<?xmlversion = "1.0"encoding = "UTF-8"?>
 <rss version="2.0"
      xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/"
      xmlns:atom="http://www.w3.org/2005/Atom">
   <channel>
     <title>{!! htmlspecialchars($eingabe, ENT_XML1, 'UTF-8'); !!} - MetaGer</title>
     <description></description>
     <opensearch:totalResults>{{ $resultcount }}</opensearch:totalResults>
     <opensearch:Query role="request" searchTerms="{{ htmlspecialchars($eingabe, ENT_QUOTES) }}"/>

  @foreach($metager->getResults() as $result)
     <item>
       <title>{!! htmlspecialchars($result->titel, ENT_XML1, 'UTF-8'); !!}</title>
       <link>{!! htmlspecialchars($result->link, ENT_XML1, 'UTF-8'); !!}</link>
       <description>
          {!! htmlspecialchars($result->descr, ENT_XML1, 'UTF-8'); !!}
       </description>
     </item>
  @endforeach
   </channel>
 </rss>
