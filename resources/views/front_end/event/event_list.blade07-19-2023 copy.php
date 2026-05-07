@extends('front_end.layout.layout_master')
@include('front_end.structure.page_meta')


@push('page_css')
<style type="text/css">
.art-pagination {
    text-align: right;
}
.art-pagination .pagination {
    margin-top: 22px;
}
</style>
<link rel="stylesheet" href="{{ asset('public/front_end/css/jquery.tabs.css') }}">
@endpush

@section('page_content')

@if( isset($extraContent) && $extraContent->image_id != '' && isset($extraContent->imageInfo) )
<section class="innerpage-banner">
    <img src="{{ asset('public/uploads/files/media_images/'.$extraContent->imageInfo->image) }}" title="{{ $extraContent->image_title }}" alt="{{ $extraContent->image_alt }}" caption="{{ $extraContent->image_caption }}">
</section>
@endif
    

<section class="container" style="margin-top: 40px;">
    <div class="breadcrumb">
        <ul>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li class="active">Events</li>
        </ul>
    </div>
<div class="row">
    <div class="col-sm-8">
        <h1>@if( isset($page_tag) ) {{ $page_tag }} @endif</h1>
        <div class="midblock">
            <div class="row">
                <div class="col-sm-4">
                    <form name="frmsx" method="GET">
                    <div class="search"><input type="text" class="form-control" name="search" placeholder="Search" value="@if(isset($_GET['search'])){{ $_GET['search'] }}@endif"><button type="submit" value=""><i class="fa fa-search" aria-hidden="true"></i></button></div>
                    </form>
                </div>
                <div class="col-sm-8 art-pagination">
                    {{--@if( isset($listData) )
                    <nav aria-label="Page navigation" style="text-align: center;">
                      <ul class="pagination">
                        @if( $listData->previousPageUrl() != '' )
                        <li class="page-item">
                            <a class="page-link" href="{{ $listData->previousPageUrl() }}">Prev</a>
                        </li>
                        @endif
                        @if( $listData->nextPageUrl() != '' )
                        <li class="page-item">
                            <a class="page-link" href="{{ $listData->nextPageUrl() }}">Next</a>
                        </li>
                        @endif
                      </ul>
                    </nav>
                    @endif--}}
                    @if( isset($listData) ){{ $listData->links() }}@endif
                </div>
            </div>
            <div class="midblock_subblock" style="border-top: 1px solid rgb(177, 177, 177);">
                @if( isset($listData) )
                    @forelse( $listData as $v )
                    <div class="outeraccor">
                        <div class="accor_heading open_arrow">{{ $v->name }}</div>
                        <div class="accor_body">
                            <div class="row">
                            <div class="col-sm-3">
                                @php
                                    $evtImage = getEventThumbImage($v->id);
                                @endphp
                                @if(isset($evtImage) && !empty($evtImage))
                                <img src="{{ asset('public/uploads/files/media_images/'. $evtImage['image']) }}" alt="{{$evtImage['alt_tag']}}" title="{{$evtImage['title']}}" caption="{{$evtImage['caption']}}">
                                @else
                                <img src="{{ asset('public/images/default_multotec.jpg') }}" style="height: 100%;">
                                @endif
                                <div class="title">{{ date('d M Y', strtotime( $v->publish_date )) }}</div>
                            </div>
                            <div class="col-sm-9">
                            <p class="atrnw-desc">{!! html_entity_decode( $v->description ) !!}</p>
                            <a href="{{ route('front.evtCont', array('lng' => $lng, 'slug' => $v->slug)) }}" class="btn3-default">Read More</a>
                            </div>
                            </div>
                        </div>
                    </div>
                    @empty
                        <h3>No Record Found</h3>
                    @endforelse
                @endif
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
                                <a href="?year={{ $yl }}&month=01">January</a>
                            </li>
                            <li @if( isset($_GET['month']) && $_GET['month'] == '02') class="active" @endif>
                                <a href="?year={{ $yl }}&month=02">February</a>
                            </li>
                            <li @if( isset($_GET['month']) && $_GET['month'] == '03') class="active" @endif>
                                <a href="?year={{ $yl }}&month=03">March</a>
                            </li>
                            <li @if( isset($_GET['month']) && $_GET['month'] == '04') class="active" @endif>
                                <a href="?year={{ $yl }}&month=04">April</a>
                            </li>
                            <li @if( isset($_GET['month']) && $_GET['month'] == '05') class="active" @endif>
                                <a href="?year={{ $yl }}&month=05">May</a>
                            </li>
                            <li @if( isset($_GET['month']) && $_GET['month'] == '06') class="active" @endif>
                                <a href="?year={{ $yl }}&month=06">June</a>
                            </li>
                            <li @if( isset($_GET['month']) && $_GET['month'] == '07') class="active" @endif>
                                <a href="?year={{ $yl }}&month=07">July</a>
                            </li>
                            <li @if( isset($_GET['month']) && $_GET['month'] == '08') class="active" @endif>
                                <a href="?year={{ $yl }}&month=08">August</a>
                            </li>
                            <li @if( isset($_GET['month']) && $_GET['month'] == '09') class="active" @endif>
                                <a href="?year={{ $yl }}&month=09">September</a>
                            </li>
                            <li @if( isset($_GET['month']) && $_GET['month'] == '10') class="active" @endif>
                                <a href="?year={{ $yl }}&month=10">October</a>
                            </li>
                            <li @if( isset($_GET['month']) && $_GET['month'] == '11') class="active" @endif>
                                <a href="?year={{ $yl }}&month=11">November</a>
                            </li>
                            <li @if( isset($_GET['month']) && $_GET['month'] == '12') class="active" @endif>
                                <a href="?year={{ $yl }}&month=12">December</a>
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
    <ul>
        @foreach( $listCats as $v )
        <li>
            <a href="?catid={{ $v->id }}">{{ $v->name }}</a>
        </li>
        @endforeach
    </ul>
    @endif
    </div>
    <div class="sidebar_block block3">
     
    </div>
  </div>
</div>
</div>
</section>
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
<script type="text/javascript">
$( function() {
    $('ul.pagination li:first').find('span').text('Prev');
    $('ul.pagination li:last').find('span').text('Next');

    $('ul.pagination li [rel=prev]').html('Prev');
    $('ul.pagination li [rel=next]').html('Next');

} );
</script>

@endpush

    