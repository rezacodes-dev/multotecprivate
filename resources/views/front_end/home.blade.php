@extends('front_end.layout.layout_master')
@include('front_end.structure.page_meta')
@push('page_css')
 
 
<style>


.carousel-control.left, .carousel-control.right {
background-image: inherit!important;
}
.slider-box {border: 1px solid #696E6B; margin-top: -20px; padding: 5px 0px; margin-bottom: 20px;}
.slider-box-block {width: 65%; margin: auto;}
.slider-box-left {font-size: 14px; font-weight: bold;} 
.slider-box-right {font-size: 14px; font-weight: bold;} 
.slider-box-right span, .slider-box-right i {color:#169687;}
.carousel-indicators .active {
width:0px;
height: 0px;
margin: 0;
background-color: inherit;
}
.carousel-indicators li {
    display: none !important;
}
.carousel-control {width:5%!important;}
.carousel-control .glyphicon-chevron-left, .carousel-control .icon-prev, .carousel-control .glyphicon-chevron-right, .carousel-control .icon-next {color:#169687!important;}
.carousel-inner .item img {    width: 100%!important;
    height: 100px!important;
    margin-left: 50px;
    border-radius: 10px;}
.slider-box-block a {color:#000!important;}
</style>
@endpush

@section('page_content')
@php
    $isDesktop = !request()->header('User-Agent') || !preg_match('/(android|iphone|ipad|mobile)/i', request()->header('User-Agent'));
@endphp

@if($isDesktop)
    @include('front_end.includes.home_banner')
@endif

@if(isset($commodities) && !empty($commodities))
@include('front_end.includes.commodities_ticker')
@else
@endif

<section class="container">
	<h1 class="m_top30">@if(isset($allData)){{ $allData->name }}@endif</h1>
	<div class="row">
		<div class="col-sm-6">
			<div class="hp_block1">
			@if(isset($allData)){!! trim( html_entity_decode( $allData->page_content, ENT_QUOTES ) ) !!}@endif	
			</div>
		</div>
		<div class="col-sm-6">
			<div class="newsblock home_nblock">
				@if( isset($news) && !empty($news) )
			
					@if(isset($allData) && $allData->news_heading != '')
					<h3>{{ $allData->news_heading }}</h3>
					@endif
					<ul>
					@foreach($news as $n)
						<li>
							<a href="{{ route('front.artCont', array('lng' => $lng, 'slug' => $n->slug)) }}" style="color: #000;">
							<span>{{ date('d F', strtotime( $n->publish_date )) }}</span> {{ $n->name }}
							</a>
						</li>
					@endforeach
					</ul>
				@endif
				
				@if(isset($map) && $map->small_image != '')
				<div class="mapbox">
					<a href="@if($map->small_link != ''){{ $map->small_link }}@else #bigmap @endif"><h3>{{ $map->small_heading }}</h3>
						<img src="{{ asset('public/uploads/files/media_images/'. $map->small_image) }}"  alt="Media Image">
					</a>
				</div>
				@endif
		
				<div class="hp_block1">
					<p><a href="{{route('eventListsen')}}"><img alt="" src="{{ asset('public/uploads/files/event-home-banner.avif') }}" style="margin-top: 10px;"></a></p>
				</div>
				
				
			</div>
		</div>
	</div>
       
	@if(isset($allData)){!! trim( html_entity_decode( $allData->readmore_content, ENT_QUOTES ) ) !!}@endif	



<div class="midblock">
	<h2 class="text-center">@if(isset($allData)){{ $allData->mineral_processing_heading }}@endif</h2>
	<div class="row">
		@if( isset($mps) && !empty($mps) )
			@php $x = 1; @endphp
			@foreach($mps as $v)
			<div class="col-sm-4" style="padding-bottom: 15px;">
				<div class="imgblock">
					<div class="hoverdiv">
						<p>{{ $v->description }}</p>
					    <a href="{{ $v->view_link }}" target="_self">View more</a>      
					</div>
					@if( isset($v->imageInfo) && !empty($v->imageInfo) )
						<img 
							src="{{ asset('public/uploads/files/media_images/' . $v->imageInfo->image) }}" 
							loading="lazy" 
							alt="Media Image"
						> 
					@endif

				</div>
				<div class="imgblock_title"><a href="{{ $v->view_link }}">{{ $v->title }}</a></div>
			</div>
			@if($x % 3 == 0)
				<div class="clearfix"></div>
			@endif
			@php $x++; @endphp
			@endforeach
		@endif
	</div>
</div>


@if( isset($allData) ){!! contentHtmlGenerator( $allData->reuse_content1 ) !!}@endif

</section>

<!-- @if( isset($allData) ){!! contentHtmlGenerator( $allData->reuse_content2 ) !!}@endif -->


<section class="container minarel_secc">
	<h2 class="text-center picheading">@if(isset($allData)){{ $allData->mineral_heading }}@endif</h2>
	<div class="row">

 

		@if( isset($minerals) && !empty($minerals) )
			@php $x = 1; @endphp
			@foreach( $minerals as $v )
			<div class="col-sm-3">
				<a href="{{ $v->view_link }}">
					<div class="metalbox">
						@if( isset($v->imageInfo) && !empty($v->imageInfo) )
		              	<img loading="lazy" src="{{ asset('public/uploads/files/media_images/'. $v->imageInfo->image) }}" 
						  alt="Media Image"> 
		              	@endif
						<div class="overlay">{{ $v->name }}</div>
					</div>
				</a>
			</div>
				@if($x % 4 == 0)
				<div class="clearfix"></div>
				@endif
				@php $x++; @endphp
			@endforeach
		@endif
	</div>
</section>

@if( isset($map) )
<section class="hp_map_big" id="bigmap">
	<div class="heading">
		<div class="left">{{ $map->big_heading_left }}</div>
		<div class="right"><i class="fa fa-arrow-right"></i><a href="{{ $map->big_link }}" style="color: #fff;"> {{ $map->big_heading_right }}</a></div>
		<div class="clearfix"></div>
	</div>
	@if(isset($map) && $map->big_image != '')
	<div style="text-align:center">
		<a href="{{ $map->big_link }}">
			<img loading="lazy" src="{{ asset('public/uploads/files/media_images/'. $map->big_image) }}"  alt="Media Image">
		</a>
	</div>
	@endif
	<div class="clearfix"></div>
</section>
@endif

@if(isset($logos) && count($logos) > 0 )
<section class="container text-center home_logos">
	<ul class="ul_logos">
		@foreach($logos as $lg)
		<li>
			@if($lg->link_file != '')
				<a  href="{{ $lg->link_file }}">
				<img loading="lazy" src="{{ asset('public/uploads/files/media_images/'. $lg->image) }}" class="li_logo_img" title="{{ $lg->image_title }}" alt="{{ $lg->image_alt }}">
				</a>
			@else
				<img loading="lazy" src="{{ asset('public/uploads/files/media_images/'. $lg->image) }}" class="li_logo_img" title="{{ $lg->image_title }}" alt="{{ $lg->image_alt }}">
			@endif
		</li>
		@endforeach
	</ul>
</section>
@endif
	
@endsection