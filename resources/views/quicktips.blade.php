<feed xmlns="http://www.w3.org/2005/Atom" xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/">
  <title>MetaGer quicktip search: @yield{'search'}</title>
  <link href="https://www.metager.de/quicktips.xml?search=test"/>
  <updated>@yield{'time'}</updated>
  <opensearch:totalResults>{{ $quicktips->amount }}</opensearch:totalResults>
  @foreach ($quicktips as $quicktip)
    <entry>
      $quicktip
    </entry>
  @endforeach
</feed>
