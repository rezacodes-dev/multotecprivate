@extends('front_end.layout.layout_master')
@include('front_end.structure.page_meta')


@push('page_css')
<link rel="stylesheet" href="{{ asset('public/front_end/css/jquery.tabs.css') }}">
@endpush

@section('page_content')

@if( isset($allData) && !empty($allData) )

@php
    $banner = getArticlePageBanner( $allData->id );
@endphp    
@if( isset($banner) && !empty($banner) )
<section class="innerpage-banner">
    <img src="{{ asset('public/uploads/files/media_images/'. $banner['image']) }}" alt="{{$banner['alt_tag']}}" 
    title="{{$banner['title']}}" caption="{{$banner['caption']}}">
</section>
@endif

<section class="container" style="margin-top: 10px;">

<div class="breadcrumb">
    <ul>
        <li><a href="{{url('/')}}">Home</a></li>
        <li><a href="{{ route('newsArticleList', array('lng' => 'en')) }}">Articles & News</a></li>
        <li class="active">{{$allData->name}}</li>
    </ul>
</div>

<div class="row">
    <div class="col-sm-8">
        <div class="midblock" id="firstBlock">

            @php
                $atrImage = getArticleNewsImage($allData->id);
            @endphp

            <!-- Title -->
            <h1 @if(isset($atrImage) && !empty($atrImage)) class="article_heading" @endif>
                @if( isset($allData->name) ){{ $allData->name }}@endif
            </h1>
            
            @if(isset($atrImage) && !empty($atrImage))
            <div class="atr-cont-img">
                <img src="{{ asset('public/uploads/files/media_images/'. $atrImage['image']) }}" alt="{{$atrImage['alt_tag']}}" 
                title="{{$atrImage['title']}}" caption="{{$atrImage['caption']}}" style="width: 100%;">
            </div>
            @endif


            <!-- Main Page Content -->
            @if( isset($allData->page_content) )
            <p>{!! trim( html_entity_decode( $allData->page_content, ENT_QUOTES ) ) !!}</p>
            @endif

            <div class="loopblock">
                @include('front_end.structure.event_atricle_body')
            </div>
        </div>
    </div>

    <div class="col-sm-4">
      <div class="rightpanel">
        <div class="sidebar_block block1">
        <h2><i class="fa fa-plus" aria-hidden="true"></i> Select Month</h2>
        <div class="calander_bg">
        <div class="heading">
         <div class="year">Year</div>
         <div class="month">Month</div>
        </div>
        <div class="row no-gutters">
            <div class="bhoechie-tab-container">
                <div class="col-sm-4 col-xs-4 bhoechie-tab-menu">
                    @if( isset($yearList) )
                      <div class="list-group">
                        @php $i = 0; @endphp
                        @foreach( $yearList as $yl )
                        <a href="#" class="list-group-item @if( $i == '0' ) active @endif">
                            {{ $yl }}
                        </a>
                        @php $i++; @endphp
                        @endforeach
                      </div>
                  @endif
                </div>
                <div class="col-sm-8 col-xs-8 bhoechie-tab">
                    @if( isset($yearList) )
                        @php $i = 0; @endphp
                        @foreach( $yearList as $yl )
                        <!-- flight section -->
                        <div class="bhoechie-tab-content @if( $i == '0' ) active @endif">
                            <ul>
                                <li @if( isset($_GET['month']) && $_GET['month'] == '01') class="active" @endif>
                                    <a href="{{ route('newsArticleList', array('lng' => $lng)) }}?year={{ $yl }}&month=01">January</a>
                                </li>
                                <li @if( isset($_GET['month']) && $_GET['month'] == '02') class="active" @endif>
                                    <a href="{{ route('newsArticleList', array('lng' => $lng)) }}?year={{ $yl }}&month=02">February</a>
                                </li>
                                <li @if( isset($_GET['month']) && $_GET['month'] == '03') class="active" @endif>
                                    <a href="{{ route('newsArticleList', array('lng' => $lng)) }}?year={{ $yl }}&month=03">March</a>
                                </li>
                                <li @if( isset($_GET['month']) && $_GET['month'] == '04') class="active" @endif>
                                    <a href="{{ route('newsArticleList', array('lng' => $lng)) }}?year={{ $yl }}&month=04">April</a>
                                </li>
                                <li @if( isset($_GET['month']) && $_GET['month'] == '05') class="active" @endif>
                                    <a href="{{ route('newsArticleList', array('lng' => $lng)) }}?year={{ $yl }}&month=05">May</a>
                                </li>
                                <li @if( isset($_GET['month']) && $_GET['month'] == '06') class="active" @endif>
                                    <a href="{{ route('newsArticleList', array('lng' => $lng)) }}?year={{ $yl }}&month=06">June</a>
                                </li>
                                <li @if( isset($_GET['month']) && $_GET['month'] == '07') class="active" @endif>
                                    <a href="{{ route('newsArticleList', array('lng' => $lng)) }}?year={{ $yl }}&month=07">July</a>
                                </li>
                                <li @if( isset($_GET['month']) && $_GET['month'] == '08') class="active" @endif>
                                    <a href="{{ route('newsArticleList', array('lng' => $lng)) }}?year={{ $yl }}&month=08">August</a>
                                </li>
                                <li @if( isset($_GET['month']) && $_GET['month'] == '09') class="active" @endif>
                                    <a href="{{ route('newsArticleList', array('lng' => $lng)) }}?year={{ $yl }}&month=09">September</a>
                                </li>
                                <li @if( isset($_GET['month']) && $_GET['month'] == '10') class="active" @endif>
                                    <a href="{{ route('newsArticleList', array('lng' => $lng)) }}?year={{ $yl }}&month=10">October</a>
                                </li>
                                <li @if( isset($_GET['month']) && $_GET['month'] == '11') class="active" @endif>
                                    <a href="{{ route('newsArticleList', array('lng' => $lng)) }}?year={{ $yl }}&month=11">November</a>
                                </li>
                                <li @if( isset($_GET['month']) && $_GET['month'] == '12') class="active" @endif>
                                    <a href="{{ route('newsArticleList', array('lng' => $lng)) }}?year={{ $yl }}&month=12">December</a>
                                </li>
                            </ul>
                        </div>
                        @php $i++; @endphp
                        @endforeach
                    @endif
                </div>
            </div>
      </div>
        </div>
        </div>
        <div class="sidebar_block block2">
        <h2><i class="fa fa-plus" aria-hidden="true"></i> Select Category</h2>
        @if( isset($listCats) )
        <div class="news_list">
            <ul class="arrow-list">
                @foreach( $listCats as $v )
                <li>
                    <a href="{{ route('newsArticleList', array('lng' => $lng)) }}?catid={{ $v->id }}">{{ $v->name }}</a>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
        </div>
        <div class="sidebar_block block3">
            @include('front_end.structure.event_atricle_sidebar')
        </div>
      </div>
    </div>
</div>
</section>

@include('front_end.structure.event_atricle_last')

@endif
@endsection




@push('page_js')
<script type="text/javascript" src="{{ asset('public/front_end/js/ddaccordion.js') }}"></script>
<script>
//Initialize 2nd demo:
ddaccordion.init({
    headerclass: "accor_heading", //Shared CSS class name of headers group
    contentclass: "accor_body", //Shared CSS class name of contents group
    revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
    mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
    collapseprev: false, //Collapse previous content (so only one open at any time)? true/false 
    defaultexpanded: [true], //index of content(s) open by default [index1, index2, etc]. [] denotes no content.
    onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
    animatedefault: false, //Should contents open by default be animated into view?
    scrolltoheader: false, //scroll to header each time after it's been expanded by the user?
    persiststate: false, //persist state of opened contents within browser session?
    toggleclass: ["closed_arrow", "open_arrow"], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
    togglehtml: ["prefix", "<img src='{{ asset('public/front_end/images/arrow_down_accor.png') }}' style='width:24px; height:24px' /> ", "<img src='{{ asset('public/front_end/images/arrow_up_accor.png') }}' style='width:24px; height:24px' /> "], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
    animatespeed: "normal", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
    oninit:function(expandedindices){ //custom code to run when headers have initalized
        //do nothing
    },
    onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
        //do nothing
    }
})

</script>

<script type="text/javascript">




// *********** tightpanr ********** //
$(document).ready(function() {
    $("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
        e.preventDefault();
        $(this).siblings('a.active').removeClass("active");
        $(this).addClass("active");
        var index = $(this).index();
        $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
        $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
    });
});

</script>


@endpush

    