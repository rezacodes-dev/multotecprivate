@php

$defLng = 'en';

@endphp

<!doctype html>
<html lang="@if( isset($page_metadata) && !empty($page_metadata) && $page_metadata->lng_tag != ''){{ $page_metadata->lng_tag }}@else{{$defLng}}@endif">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!--- META -->
	@stack('page_meta')
	<!--- END META -->
	<link rel="shortcut icon" href="{{ asset('public/front_end/images/favicon.png') }}">
<!-- ✅ Critical CSS (keep render-blocking to avoid FOUC) -->
<!-- ✅ Critical CSS (keep blocking to avoid FOUC) -->
<link rel="stylesheet" href="{{ asset('public/assets/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/front_end/css/style.css') }}">

<!-- 🚀 Non-critical CSS (async load) -->
<!-- <link rel="stylesheet" href="{{ asset('public/front_end/css/responsive.css') }}" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="{{ asset('public/front_end/css/responsive.css') }}"></noscript> -->


<!-- 🚀 Non-critical CSS (load async with onload trick + noscript fallback) -->
<link rel="stylesheet" href="{{ asset('public/front_end/css/owl.carousel.min.css') }}" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="{{ asset('public/front_end/css/owl.carousel.min.css') }}"></noscript>

<link rel="stylesheet" href="{{ asset('public/front_end/css/owl.theme.default.min.css') }}" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="{{ asset('public/front_end/css/owl.theme.default.min.css') }}"></noscript>

<link rel="stylesheet" href="{{ asset('public/front_end/css/menuzord.css') }}" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="{{ asset('public/front_end/css/menuzord.css') }}"></noscript>

<link rel="stylesheet" href="{{ asset('public/assets/jquery_ui/jquery-ui.css') }}" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="{{ asset('public/assets/jquery_ui/jquery-ui.css') }}"></noscript>

<link rel="stylesheet" href="{{ asset('public/front_end/css/jquerysctipttop.css') }}" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="{{ asset('public/front_end/css/jquerysctipttop.css') }}"></noscript>


  <!-- 🚀 Font Awesome (async load via preload + onload) -->
  <link rel="preload" href="{{ asset('public/assets/bower_components/font-awesome/css/font-awesome.min.css') }}" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript>
    <link rel="stylesheet" href="{{ asset('public/assets/bower_components/font-awesome/css/font-awesome.min.css') }}">
</noscript>


@php
    $ua = request()->header('User-Agent');
    $isMobile = preg_match('/Mobile|Android|iP(hone|od|ad)|IEMobile|BlackBerry|Opera Mini/i', $ua);
@endphp

@if(!$isMobile)
    <!-- Load Google Fonts only on Desktop -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900"
          rel="stylesheet" media="print" onload="this.media='all'">
@endif

	
	@stack('page_css')
	<style type="text/css">
		body {
			font-family: 'Roboto', sans-serif !important; font-weight:300 !important;
			color: #000;
			font-size: 18px;
			line-height:24px;
			padding: 0;
			margin: 0;
		}
		strong { font-weight: 500; }
		b { font-weight: 500; }
	</style>
		@if (!Request::is('/'))
    {{-- <script src="https://www.google.com/recaptcha/api.js"></script> --}}
@endif
{{-- @php
    $headerScript = getSEOscripts('before_head');
    if (!empty($headerScript)) {
        foreach ($headerScript as $v) {
            echo html_entity_decode($v->script_code, ENT_QUOTES);
        }
    }
@endphp --}}
</head>

<body>
@php
    $bodyScript1 = getSEOscripts('after_body');
    if (!empty($bodyScript1)) {
        foreach ($bodyScript1 as $v) {
            echo html_entity_decode($v->script_code, ENT_QUOTES);
        }
    }
@endphp

	
