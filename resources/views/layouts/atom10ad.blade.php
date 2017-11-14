@if(isset($ad))
 <ad:advertisement>
   <ad:callOut type="TEXT">{!! trans('ad.von') !!} {!! $ad->gefVon !!}</ad:callOut>
   <ad:title type="TEXT">{{ $ad->titel }}</ad:title>
   <ad:displayUrl type="TEXT">{{ $ad->anzeigeLink }}</ad:displayUrl>
   <ad:subTitle atom:type="TEXT">{{ $ad->descr }}</ad:subTitle></ad:subTitle>
   <link href="{{ $ad->link }}" />
 </ad:advertisement> 
@endif