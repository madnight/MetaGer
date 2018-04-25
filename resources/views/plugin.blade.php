<?xml version="1.0" encoding="UTF-8" ?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/" xmlns:moz="http://www.mozilla.org/2006/browser/search/">
        <ShortName>MetaGer</ShortName>
        <Description>{{ trans('plugin.description') }}</Description>
        <Contact>office@suma-ev.de</Contact>
        <Image width="16" height="16" type="image/x-icon">{{ url('/favicon.ico') }}</Image>
        @if ($request == "POST")
        <Url type="text/html" template="{{ $link }}" method="{{$request}}">
                <Param name="eingabe" value="{searchTerms}" />
                @foreach($params as $key => $value)
                <Param name="{{$key}}" value="{{$value}}" />

                @endforeach

        </Url>
        @else
        <Url type="text/html" template="{{ $link }}?eingabe={searchTerms}@foreach($params as $key => $value)&amp;{{$key}}={{$value}}@endforeach" method="{{$request}}" />
        @endif
        <InputEncoding>UTF-8</InputEncoding>
</OpenSearchDescription>
