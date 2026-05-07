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


input.filtersearch{width: 100%;
height: 50px !important;
padding: 10px;
font-size: 14px;
border: none;
background: #ebebeb;
}
a.filterbut {
    float: right;
    padding: 13px 33px;
    background: #3b8d65;
    font-size: 15px;
    font-weight: 600;
    letter-spacing: 1px;
    border-radius: 4px;
    color: #fff;
}
.filterbox {
    padding: 26px;
    margin: 15px 0 ;
    background: #f5f5f5;
    border-radius: 0 0 5px 5px;
    border-bottom: 3px solid #3b8d65;
	box-shadow: 0px 8px 13px #ccc;
}
.filterbox select.form-control{
	height:45px !important;
	border:1px solid #ccc;
}

.picboxsection{padding:35px 0;}
.picinner {
    padding: 15px;
    background: #f1f1f1;
	margin:0 0 30px;
    min-height: 550px;
}
.picinner h4 {
    margin: 0;
    padding: 13px 10px;
    background: #3b8d65;
    color: #fff;
}
.piccont{
	font-size:14px;
	
	}
.piccont p {
    font-size: 16px;
    line-height: 23px;
	padding:8px 0;
}
	
.piccont ul {
    padding: 12px 0 0 0;
    margin: 0;
}
.piccont ul li {
    display: inline-block;
}
.piccont ul li a {
    padding: 2px 15px;
    display: inline-block;
    background: #fff;
    font-size: 13px;
    font-weight: 600;
    margin: 0 5px 5px 0;
    color: #3b8d65;
    border: 1px solid #3b8d65;
    border-radius: 23px;
}	
	
.piccont ul li a:hover{background:#3b8d65; color:#fff;}
.catagorydetails{padding:0 0 25px;}
.youtubevideo{padding:15px; background:#f6f6f6; margin:20px 5%;}
.youtubevideo iframe {
    width: 100%;
    height: 450px;
}
@media only screen and (max-width:767px){
	input.filtersearch{margin:35px 0 8px;}
	a.filterbut{display:block; text-align:center; float:none;}
	.filterbox select.form-control {margin: 9px 0;}
	.picinner img {
    width: 100%;
}
}



.filtersecrch {
    position: relative;
}
.filtersecrch button {
    position: absolute;
    right: 9px;
    top: 11px;
    background: none;
    border: none;
    color: #3b8d65;
    font-size: 21px;
}
.filtersecrch input.filtersearch {
    padding-right: 46px;
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
        <li>Webinar</li>
    </ul>
</div>


<h1>{{$webinarContent->heading}}</h1>

<br>
<p>
{!! html_entity_decode($webinarContent->description ) !!}
</p>

 <br>



<form name="frmsx" method="GET"  >
<div class="row" style="float:right;">
<!-- <div class="col-sm-8">

                   
<div class="filtersecrch">
        <button style="border: none;" type="submit" value=""><i class="fa fa-search" aria-hidden="true"></i></button>    
<input type="text" value="@if(isset($_GET['search'])){{ $_GET['search'] }}@endif" class=" form-control filtersearch" name="search"  placeholder="SEARCH">
</div>


</div>
<div class="col-sm-4">
<a href="#" class="filterbut" data-kmt="1" onclick="showhide();"><span id="plusminus">+</span> FILTER</a>
<div class="clearfix"></div> 
</div>
</div> -->

<div  style="display:flex;">

                   
<div class="filtersecrch">
        <button style="border: none;" type="submit" value=""><i class="fa fa-search" aria-hidden="true"></i></button>    
<input type="text" value="" class=" form-control filtersearch" name="search" placeholder="SEARCH">
</div>

 
<a href="#" class="filterbut" data-kmt="1" onclick="showhide();"><span id="plusminus">+</span> FILTER</a>
<div class="clearfix"></div> 
 
</div>

</div>

<br><br>

<div class="filterbox" style="display:none;">
<div class="row">
<div class="col-sm-4">
<label  style="font-weight: 300; font-size: 12px;">Product</label>
        <br>    
<select class="form-control" name="webinar_category">
    
    <option value="">Select Product</option>

    @foreach($listCats as $row)

    <option value="{{$row->id}}">{{$row->name}}</option>

    @endforeach


</select></div>

<div class="col-sm-4">
<label  style="font-weight: 300; font-size: 12px;">Topic</label>
        <br>    
<select class="form-control" name="webinar_topic">
    
    <option value="">Select Topic</option>

    @foreach($listTopic as $row)

    <option value="{{$row->id}}">{{$row->name}}</option>

    @endforeach


</select></div>

<!-- <div class="col-sm-4">
    <label  style="font-weight: 300; font-size: 12px;">Start Date</label>
        <br>
    
    <input type="date" name="start_date" class="form-control" value="@if(isset($_GET['start_date'])){{ $_GET['start_date'] }}@endif"></div>
<div class="col-sm-4">
<label  style="font-weight: 300; font-size: 12px;">End Date</label>
        <br>    
<input type="date" name="end_date" class="form-control" value="@if(isset($_GET['end_date'])){{ $_GET['end_date'] }}@endif"></div>
--></div> 
</div>

</form>



<div class="picboxsection">
<div class="row">
 
  
@if( isset($listData) )
                    @forelse( $listData as $v )
<div class="col-sm-4 col-md-4">
<div class="picinner">
<h4>{{ $v->name }}</h4>


@php
$imageURL='';
                                  
                                    if(isset($v->image) && $v->image!=''){
                                     $imageURL = asset('public/uploads/user_images/original/'.$v->image); 
                                    }
                                @endphp
                                @if(isset($imageURL) && !empty($imageURL))
                                <img src="{{$imageURL}}" >
                                @else
                                <img src="{{ asset('public/images/default_multotec.jpg') }}" style="height: 100%;">
                                @endif

 
<div class="piccont">
<ul>
<li><a >Product: {{$v->webinarcat}}</a></li>
<!-- <li><a h onclick="setURL('{{ route('front.webinarCont', array('lng' => $lng, 'id' => $v->id)) }}', {{$v->id}})" data-kmt="1"  data-toggle="modal" data-target="#myModal">Watch</a></li> -->
<li><a href="{{ route('front.webinarCont', array('lng' => $lng, 'id' => $v->slug)) }}" >Watch Webinar</a></li> 

 
</ul>
<p>{!! html_entity_decode( $v->short_description ) !!}</p>
</div>
</div>
</div>

 
                    @empty
                        <h3>No Record Found</h3>
                    @endforelse
                    @endif


</div>
</div>

 
 
<!-- <div class="loopblock catagorydetails">
<h2>Courier® 6G SL on-stream analyzer launch presentation</h2>
<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum</p> 

<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </p>

<p>In this webinar, you will learn: </p>
<ul>
    <li>Courier® 6G SL analyzer benefits and how it works</li>
    <li>The components that make up the Courier® 6G SL analyzer</li>
    <li>The role of the Courier® 6G SL analyzer in helping to optimize your entire flotation circuit</li>
</ul>
<p>Webinar will be 1 hour including presentation and question and answer session.</p> 


<div class="youtubevideo">
<iframe width="560" height="315" src="https://www.youtube.com/embed/wWXBGr5BZcM?enablejsapi=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" data-kmt="1" id="widget2" data-gtm-yt-inspected-1_25="true" data-gtm-yt-inspected-11482088_5="true"></iframe>
</div>





</div> -->






























<!-- <div class="row">
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
                                    $atrImage = getArticleThumbImage($v->id);
                                @endphp
                                @if(isset($atrImage) && !empty($atrImage))
                                <img src="{{ asset('public/uploads/files/media_images/'. $atrImage['image']) }}" alt="{{$atrImage['alt_tag']}}" title="{{$atrImage['title']}}" caption="{{$atrImage['caption']}}">
                                @else
                                <img src="{{ asset('public/images/default_multotec.jpg') }}" style="height: 100%;">
                                @endif
                                <div class="title">{{ date('d M Y', strtotime( $v->created_at )) }}</div>
                            </div>
                            <div class="col-sm-9">
                            <p class="atrnw-desc">{!! html_entity_decode( $v->description ) !!}</p>
                            <a href="{{ route('front.artCont', array('lng' => $lng, 'slug' => $v->slug)) }}" class="btn3-default">Read More</a>
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
</div> -->

  
<!-- The Modal -->
<div class="modal" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Please fill this form to continue</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <form action="https://www.multotec.com/en/saveWbUser" method="post">

        {{ csrf_field() }}
        <!-- Modal body -->
        <div class="modal-body">
         
          <div class="form-group">
          <input type="text" name="name" placeholder="Enter Name" class="form-control" required>
          <input type="hidden" name="webinar_url"  id="webinar_url"   class="form-control">
          <input type="hidden" name="webinar_id"  id="webinar_id"   class="form-control">
          
          </div>

          <div class="form-group">
          <input type="text" name="contact_no" placeholder="Enter Contact Number" class="form-control" required>
          </div>

          <div class="form-group">
          <input type="text" name="email_id" placeholder="Enter Email" class="form-control" required>
          </div>

        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="submit" class="btn btn-submit" >Proceed</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

        </form >
        
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

var i=1;

function showhide(){

 
    $('.filterbox').toggle();
    if(i%2==0){
        $('#plusminus').html('+'); 
    }
    else{
        $('#plusminus').html('-'); 
    }
    

    i++;
}


function setURL(url,webinar_id){
    $('#webinar_url').val(url);
    $('#webinar_id').val(webinar_id);
 
}

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

    