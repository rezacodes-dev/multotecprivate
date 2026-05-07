@extends('front_end.layout.layout_master')
@push('page_meta')
{{-- <meta name="robots" content="noindex, nofollow"> --}}

    @if( isset($page_metadata) && !empty($page_metadata) )
    
        @php
            $robot_txt = '';
            if( $page_metadata->follow == '1' ) {
                $robot_txt .= 'follow, ';
            } else {
                $robot_txt .= 'nofollow, ';
            }
            if( $page_metadata->index_tag == '1' ) {
                $robot_txt .= 'index, ';
            } else {
                $robot_txt .= 'noindex, ';
            }
            $robot_txt = rtrim($robot_txt , ', ');
        @endphp
   
        <title>Multotec Brochures</title>
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="robots" content="">
        {{-- <meta name="robots" content="noindex, nofollow"> --}}
      

        @if( starts_with( html_entity_decode($page_metadata->json_markup, ENT_QUOTES), '<script' ) )
            {!! html_entity_decode($page_metadata->json_markup, ENT_QUOTES) !!}
        @else
            <script type="application/ld+json">
            {!! html_entity_decode($page_metadata->json_markup, ENT_QUOTES) !!}
            </script>
        @endif
    @endif
    @if(isset($logos) && !request()->is('/'))
    <!-- Hotjar Tracking Code for https://www.multotec.com/en -->
    <script>
        (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:1682593,hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
    </script>
@endif

@endpush


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
    height: 468px;
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
    /*line-height: 23px;*/
    line-height: 18px;
	padding:8px 0;
}
	
.piccont ul {
    padding: 12px 0 0 0;
    margin: 0;
    text-align: center;
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

.product-card {
    border: 1px solid #eee;
    border-radius: 6px;
    background: #fff;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    display: flex;
    flex-direction: column;
    height: 100%;
}
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.product-card img {
    width: 100%;
    height: 200px;      /* fixed height for alignment */
    object-fit: cover;  /* keep aspect ratio without stretching */
}
.product-card .card-body {
    padding: 15px;
    flex-grow: 1;
}

.product-card h5 {
    font-size: 16px;
    margin-bottom: 12px;
    min-height: 40px;   /* makes all titles line up */
}
.product-info {
    padding: 10px;
}
.product-info h5 {
    font-size: 16px;
    margin: 10px 0;
    font-weight: 600;
}
.btn-view {
    display: inline-block;
    background-color: #007b3d; /* green */
    color: #fff;
    padding: 8px 15px;
    text-decoration: none;
    border-radius: 3px;
    font-weight: 500;
    border: 1px solid #007b3d;
    transition: background-color 0.3s ease;
}
.btn-view:hover {
    background-color: #005f2c; /* darker green */
    color: #fff; /* stays white */
}
/* .brochurepic {
    height: 197px;
    overflow: hidden;
} */
 
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
        <li>Product Brochure & Files</li>
    </ul>
</div>



<h1>{{ $brochureContent->heading??'' }}</h1>
<br>
<p>
{!! html_entity_decode($brochureContent->description ) !!}
</p>

 <br>



<form name="frmsx" method="GET"  >
<div class="row" style="float:right;">
 

<div  style="display:flex;">

                   
<div class="filtersecrch">
        <button style="border: none;" type="submit" value=""><i class="fa fa-search" aria-hidden="true"></i></button>    
<input type="text" value="{{ request('search') }}" class=" form-control filtersearch" name="search" placeholder="SEARCH">
</div>

 
<a href="#" class="filterbut" data-kmt="1" onclick="showhide();"><span id="plusminus">-</span> FILTER</a>
<div class="clearfix"></div> 
 
</div>

</div>

<br><br>

<div class="filterbox" >
<div class="row">

<div class="col-sm-3">
<label  style="font-weight: 300; font-size: 12px;">Document Type</label>
<br>    
<select class="form-control" name="brochure_type" id="brochure_type" onchange="getWebinars()">
    
    <option value="">Document Type</option> 

    @foreach($brochureType as $row)
    <option value="{{$row->id}}" {{ request('brochure_type') == $row->id ? 'selected' : '' }}>{{$row->name}}</option> 
    @endforeach 

</select>
</div>

<div class="col-sm-3">
<label  style="font-weight: 300; font-size: 12px;">Brochure Product</label>
<br>    
<select class="form-control" name="brochure_product" id="brochure_product" onchange="getWebinars()">
    
    <option value="">Brochure Product</option> 

    @foreach($brochureProduct as $row)
    <option value="{{$row->id}}" {{ request('brochure_product') == $row->id ? 'selected' : '' }}>{{$row->name}}</option> 
    @endforeach 

</select>
</div>



<div class="col-sm-3">
<label  style="font-weight: 300; font-size: 12px;">Brochure Brand</label>
<br>    
<select class="form-control" name="brochure_brand" id="brochure_brand" onchange="getWebinars()">
    
    <option value="">Select Brand</option> 

    @foreach($brochureBrand as $row)
    <option value="{{$row->id}}" {{ request('brochure_brand') == $row->id ? 'selected' : '' }}>{{$row->name}}</option> 
    @endforeach 

</select>
</div>

<div class="col-sm-3">
<label  style="font-weight: 300; font-size: 12px;">Language</label>
<br>    
<select class="form-control" name="brochure_language" id="brochure_language" onchange="getWebinars()">
    
    <option value="">Select language</option> 

    @foreach($brochureLanguage as $row)
    <option value="{{$row->id}}" {{ request('brochure_language') == $row->id ? 'selected' : '' }}>{{$row->name}}</option> 
    @endforeach 

</select>
</div>

</div> 
</div>

</form>

<!-- <div class="picboxsection" id="newListWebinars">
<div class="row" id="listWebinars">

@if( isset($listData) )
 @forelse( $listData as $v )
  
 <div class="col-sm-4 col-md-4">
    <div class="product-card">
        <div class=""><a href="{{ route('front.brochureCont', array('lng' => $lng, 'id' => $v->slug)) }}">
            @php
                $imageURL = isset($v->thumbnail_image) && $v->thumbnail_image != '' 
                    ? asset('public/' . $v->thumbnail_image) 
                    : asset('public/images/default_multotec.jpg');
            @endphp
            <img src="{{ $imageURL }}" alt="{{ $v->name }}">
        </a></div>
        <div class="">
            <h5>{{ $v->name }}</h5>
            <a href="{{ route('front.brochureCont', array('lng' => $lng, 'id' => $v->slug)) }}" class="btn-view">View Content</a>
        </div>
    </div>
</div>

 
    @empty
        <h3>No Record Found</h3>
    @endforelse   
    @endif


</div>
    <div class="prev_next_btn" >
        @if( $listData->previousPageUrl() != '' ) <a href="{{ $listData->previousPageUrl() }}"> < Prev  </a> @endif
        @if( $listData->nextPageUrl() != '' ) <a href="{{ $listData->nextPageUrl() }}"> Next > </a> @endif 
    </div>
</div> -->
 
<div class="picboxsection" id="newListWebinars">
    <div class="row" id="listWebinars">

        @if(isset($listData))
            @forelse($listData as $v)
                <div class="col-sm-6 col-md-4 mb-4">
                    <div class="product-card h-100">
                        <a href="{{ route('front.brochureCont', ['lng' => $lng, 'id' => $v->slug]) }}">
                            @php
                                $imageURL = !empty($v->thumbnail_image) 
                                    ? asset('public/' . $v->thumbnail_image) 
                                    : asset('public/images/default_multotec.jpg');
                            @endphp
                            <img src="{{ $imageURL }}" alt="{{ $v->name }}" class="card-img-top img-fluid">
                        </a>
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $v->name }}</h5>
                            <a href="{{ route('front.brochureCont', ['lng' => $lng, 'id' => $v->slug]) }}" class="btn-view">View Content</a>
                        </div>
                    </div>
                </div>
            @empty
                <h3>No Record Found</h3>
            @endforelse
        @endif

    </div>

    <div class="prev_next_btn text-center mt-3">
        @if($listData->previousPageUrl())
            <a  style="background-color:#fff;color: #008d5c;" href="{{ $listData->previousPageUrl() }}"> &lt; Prev </a>
        @endif
        @if($listData->nextPageUrl())
            <a  style="background-color:#fff;color: #008d5c;"  href="{{ $listData->nextPageUrl() }}"> Next &gt; </a>
        @endif
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

 


function getWebinars() {
    var brochure_type = $('#brochure_type').val();
    var brochure_brand = $('#brochure_brand').val();
    var brochure_language = $('#brochure_language').val();
    var brochure_product = $('#brochure_product').val();

    // Get current URL params
    let params = new URLSearchParams(window.location.search);

    // Update params
    params.set('brochure_type', brochure_type);
    params.set('brochure_brand', brochure_brand);
    params.set('brochure_language', brochure_language);
    params.set('brochure_product', brochure_product);

    // Update URL without reload
    let newUrl = window.location.pathname + '?' + params.toString();
    window.history.pushState({}, '', newUrl);

    // AJAX call
    $.ajax({
        url: "{{ route('brochureAjax') }}",
        type: "POST",
        dataType: "json",
        data: {
            brochure_type,
            brochure_brand,
            brochure_language,
            brochure_product,
            _token: "{{ csrf_token() }}"
        },
        success: function(data) {
            if (data.success) {
                $("#newListWebinars").html(data.html);
            } else {
                $("#newListWebinars").html("<h3>No Record Found</h3>");
            }
        }
    });
}

 
var i=1;

function showhide(){
 
    $('.filterbox').toggle();
    if(i%2==1){
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

    