@extends('front_end.layout.layout_master')

@section('page_content')
    
    @include('front_end.includes.home_banner')

    <section class="container">
    @if( isset($pageInfo) )
    	
    	@if( isset($device) && $device == 'desktop' )
        	<div>{!! contentHtmlGenerator( $pageInfo->page_content ) !!}</div>
        @endif

        @if( isset($device) && $device == 'mobile' )
        	<div>{!! contentHtmlGenerator( $pageInfo->mob_page_content ) !!}</div>
        @endif

    @endif
    </section>
    
@endsection

    
    
