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

	<!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="{{ asset('public/front_end/css/style.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('public/front_end/css/responsive.css') }}" >
	<!-- Font Awesome -->
  	<link rel="stylesheet" href="{{ asset('public/assets/bower_components/font-awesome/css/font-awesome.min.css') }}">
	<link rel="stylesheet" href="{{ asset('public/front_end/css/owl.carousel.min.css') }}">
	<link rel="stylesheet" href="{{ asset('public/front_end/css/owl.theme.default.min.css') }}">
	<link rel="Stylesheet" type="text/css" href="{{ asset('public/front_end/css/menuzord.css') }}">
	<link rel="stylesheet" href="{{ asset('public/assets/bower_components/bootstrap/dist/css/bootstrap.min.css') }}"> 
	<link rel="stylesheet" href="{{ asset('public/assets/bower_components/font-awesome/css/font-awesome.min.css') }}">
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i,900" rel="stylesheet">
	<link rel="stylesheet" href="{{ asset('public/assets/jquery_ui/jquery-ui.css') }}">
	<link href="{{ asset('public/front_end/css/jquerysctipttop.css') }}" rel="stylesheet" type="text/css">
	@stack('page_css')
	<script src='https://www.google.com/recaptcha/api.js'></script>
	@php
		$headerScript = getSEOscripts('before_head');
		if( !empty($headerScript) ) {
			foreach($headerScript as $v) {
				echo html_entity_decode( $v->script_code, ENT_QUOTES );
			}
		}
	@endphp
</head>
<body>
@php
	$bodyScript1 = getSEOscripts('after_body');
	if( !empty($bodyScript1) ) {
		foreach($bodyScript1 as $v) {
			echo html_entity_decode( $v->script_code, ENT_QUOTES );
		}
	}
@endphp
	
