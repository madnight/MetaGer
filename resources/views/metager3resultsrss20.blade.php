<?xmlversion = "1.0"encoding = "UTF-8"?>
 <rss version="2.0"
      xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/"
      xmlns:atom="http://www.w3.org/2005/Atom">
   <channel>
     <title>{{ $eingabe }} - MetaGer</title>
     <description></description>
     <opensearch:totalResults>{{ $resultcount }}</opensearch:totalResults>
     <opensearch:Query role="request" searchTerms="{{ htmlspecialchars($eingabe, ENT_QUOTES) }}"/>

  @foreach($metager->getResults() as $result)
     <item>
       <title>{{ $result->titel }}</title>
       <link>{{ $result->link }}</link>
       <description>
          {{ $result->descr }}
       </description>
     </item>
  @endforeach
   </channel>
 </rss>
