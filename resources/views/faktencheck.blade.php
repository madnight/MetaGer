@extends('layouts.subPages')

@section('title', $title )

@section('content')
	<style>
	#checklist > li {
		margin-top: 10px;
	}
	</style>
	<h1>@lang('faktencheck.heading.1')</h1>
	<p>@lang('faktencheck.paragraph.1')</p>
	<ol id="checklist">
		<li>@lang('faktencheck.list.1')</li>
		<ol>
			<li>@lang('faktencheck.list.1.1')</li>
			<li>@lang('faktencheck.list.1.2')</li>
			<li>@lang('faktencheck.list.1.3')</li>
			<li>@lang('faktencheck.list.1.4')</li>
			<li>@lang('faktencheck.list.1.5')</li>
			<li>@lang('faktencheck.list.1.6')</li>
			<li>@lang('faktencheck.list.1.7')</li>
			<li>@lang('faktencheck.list.1.8')</li>
		</ol>
		<li>@lang('faktencheck.list.2')</li>
		<ol>
			<li>@lang('faktencheck.list.2.1')</li>
			<li>@lang('faktencheck.list.2.2')</li>
			<li>@lang('faktencheck.list.2.3')</li>
			<li>@lang('faktencheck.list.2.4')</li>
			<li>@lang('faktencheck.list.2.5')</li>
			<li>@lang('faktencheck.list.2.6')</li>
		</ol>
		<li>@lang('faktencheck.list.3')</li>
		<ol>
			<li>@lang('faktencheck.list.3.1')</li>
			<li>@lang('faktencheck.list.3.2')</li>
			<li>@lang('faktencheck.list.3.3')</li>
			<li>@lang('faktencheck.list.3.4')</li>
		</ol>
		<li>@lang('faktencheck.list.4')</li>
		<ol>
			<li>@lang('faktencheck.list.4.1')</li>
			<li>@lang('faktencheck.list.4.2')</li>
			<li>@lang('faktencheck.list.4.3')</li>
		</ol>
		<li>@lang('faktencheck.list.5')</li>
		<ol>
			<li>@lang('faktencheck.list.5.1')</li>
			<li>@lang('faktencheck.list.5.2')</li>
			<li>@lang('faktencheck.list.5.3')</li>
		</ol>
		<li>@lang('faktencheck.list.6')</li>
		<li>@lang('faktencheck.list.7')</li>
	</ol>
@endsection