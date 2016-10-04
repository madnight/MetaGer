<?xmlversion = "1.0"encoding = "UTF-8"?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
	<ShortName>MetaGer</ShortName>
	<Description>{! trans('plugin.description') !}</Description>
	<Contact>office@suma-ev.de</Contact>
	<Image width="16" height="16" type="image/x-icon">{{ url('/favicon.ico') }}</Image>
	<Url type="text/html" template="{{ $link }}&amp;eingabe={searchTerms}" method="{{$request}}"></Url>
	<InputEncoding>UTF-8</InputEncoding>
</OpenSearchDescription>
