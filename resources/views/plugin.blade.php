<?xml version="1.0" encoding="UTF-8" ?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/" xmlns:moz="http://www.mozilla.org/2006/browser/search/"  xmlns:parameters="http://a9.com/-/spec/opensearch/extensions/parameters/1.0/">
	<ShortName>MetaGer</ShortName>
	<Description>{{ trans('plugin.description') }}</Description>
	<Contact>office@suma-ev.de</Contact>
	<Image width="16" height="16" type="image/x-icon">{{ url('/favicon.ico') }}</Image>
	<Url type="text/html" template="{{ $link }}" method="{{$request}}">
		<parameters:Parameter name="eingabe" value="{searchTerms}" />
		@foreach($params as $key => $value)
		<parameters:Parameter name="{{$key}}" value="{{$value}}" />
		@endforeach
	
	</Url>
	<InputEncoding>UTF-8</InputEncoding>
</OpenSearchDescription>
