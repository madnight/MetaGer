@if(isset($ad)  && !$apiAuthorized)
 <ad:advertisement>
   <ad:callOut atom:type="TEXT">{!! trans('ad.von') !!} {!! $ad->gefVon !!}</ad:callOut>
   <ad:title atom:type="TEXT">{{ $ad->titel }}</ad:title>
   <ad:displayUrl atom:type="TEXT">{{ $ad->anzeigeLink }}</ad:displayUrl>
   <atom:link href="{{ $ad->link }}" />
 </ad:advertisement> 
@endif