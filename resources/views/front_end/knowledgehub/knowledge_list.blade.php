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
   
        <title>Knowledge Hub</title>
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
@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
body,h1,h2,h3,h4,h5,h6,p,input,a,select,textarea {
    font-family: "Poppins", sans-serif !important;
}
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
    background: #fff;
    border-radius: 4px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    text-align: center;
    transition: 0.3s ease;
    min-height: 472px;
    margin: 0 0 30px;
}
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.product-card img {
    width: 100%;
    height: auto;
    display: block;
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
 
.knowledgeicon {
border: 2px solid #008c5be8;
width: 48px;
height: 44px;
display: inline-flex;
justify-content: center;
align-items: center;
padding: 10px 10px 10px !important;
border-radius: 10px;
position: relative;
}
.knowledgeicon span {
position: absolute;
left: 50%;
bottom: -22px;
transform: translateX(-50%);
display: inline-block;
font-size: 12px;
line-height: 1.5;
font-weight: 500;
color: #008c5be8;
}
.menuzord-menu>li {
    margin: 5px 0px 0 5px;
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
        <li>Knowledge Hub</li>
    </ul>
</div>



<h1>{{ $brochureContent->heading??'' }}</h1>
<br>
{{-- <p>
{!! html_entity_decode($brochureContent->description ) !!}
</p> --}}

 <br>



<form name="frmsx" method="GET"  >
<div class="row" style="float:right;">
 

<div  style="display:flex;">

                   
<div class="filtersecrch">
        <button style="border: none;" type="submit" value=""><i class="fa fa-search" aria-hidden="true"></i></button>    
<input type="text" value="" class=" form-control filtersearch" id="search" name="search" placeholder="SEARCH">
</div>

 
<a href="#" class="filterbut" data-kmt="1" onclick="showhide();"><span id="plusminus">-</span> FILTER</a>
<div class="clearfix"></div> 
 
</div>

</div>

<br><br>



<div class="filterbox" style="padding:20px; background:#f5f5f5; border-radius:6px;">
    
    <div style="display:flex; gap:20px; justify-content:space-between; flex-wrap:wrap;">

        <!-- Item -->
        <div style="flex:1; min-width:180px;">
            <label style="font-weight:300; font-size:12px;" >Product</label>
            <select class="form-control" name="kh_product" id="kh_product" onchange="getWebinars()">
                 <option value="">Select Product</option>
            @foreach($knowledgeProduct as $row)
            <option value="{{$row->id}}">{{$row->name}}</option> 
            @endforeach 
            </select>
        </div>

        <div style="flex:1; min-width:180px;">
            <label style="font-weight:300; font-size:12px;">Commodity</label>
            <select class="form-control" name="kh_commodity" id="kh_commodity" onchange="getWebinars()">
                 <option value="">Select Commodity</option>
                    @foreach($knowledgeCommodity as $row)
                    <option value="{{$row->id}}">{{$row->name}}</option> 
                    @endforeach 
            </select>
        </div>

        <div style="flex:1; min-width:180px;">
            <label style="font-weight:300; font-size:12px;">Region</label>
            <select class="form-control" name="kh_location" id="kh_location" onchange="getWebinars()">
               <option value="">Select Region</option>
                  @foreach($knowledgeLocation as $row)
        <option value="{{$row->id}}">{{$row->name}}</option> 
        @endforeach 
            </select>
        </div>

        <div style="flex:1; min-width:180px;">
            <label style="font-weight:300; font-size:12px;">Resource Type</label>
            <select class="form-control" name="kh_type" id="kh_type" onchange="getWebinars()">
               <option value="">Select Resource Type</option>
                   <option value="1">Brochure</option> 
                   <option value="2">Podcast</option> 
                   <option value="3">Webinar</option> 
            </select>
        </div>

        <div style="flex:1; min-width:180px;">
            <label style="font-weight:300; font-size:12px;">Language</label>
            <select class="form-control" name="kh_language" id="kh_language" onchange="getWebinars()">
                <option value="">Select language</option>
                @foreach($knowledgeLanguage as $row)
              <option value="{{$row->id}}" {{ $row->name == 'English' ? 'selected' : '' }}>
                {{$row->name}}
            </option>
                @endforeach 
            </select>
        </div>

    </div>

</div>

</form>

<div class="picboxsection">
<div class="row" id="listWebinars">

@if( isset($listData) )
 @forelse( $listData as $v )

<div class="col-sm-4 col-md-4">
    <div class="product-card" style="border:1px solid #ddd; background:#f5f5f5; padding:10px; text-align:center;">
             @php
                $imageURL = isset($v->image) && $v->image != '' 
                    ? asset('public/' . $v->image) 
                    : asset('public/images/default_multotec.jpg');
            @endphp
        <!-- Image clickable -->
        <div>
            <a href="{{ route('front.knowledgehubCont', array('lng' => $lng, 'id' => $v->slug,'language_id'=>$language_id)) }}">
                <img src="{{ $imageURL }}" 
                     alt="Knowledge Hub" style="width:100%; height:200px;object-fit: cover;">
            </a>
        </div>

        <!-- Content -->
        <div style="padding:15px;">
            
            <!-- Title clickable -->
            <h5 style="font-weight:600; margin-bottom:10px;font-size: 16px !important;">
                <a href="{{ route('front.knowledgehubCont', array('lng' => $lng, 'id' => $v->slug,'language_id'=>$language_id)) }}" style="text-decoration:none; color:#000;font-weight: 500;line-height: 1.4;">
                    {{ $v->name??'' }}
                </a>
            </h5>

           <p style="font-size: 13px;line-height: 1.6;font-weight: 500;color: #646464;overflow: hidden;">
                {{ $v->short_description ?? '' }}
            </p>

            <!-- Icons -->
            <div style="display:flex; justify-content:center; gap:15px;">
                  <a href="{{ route('front.knowledgehubCont', array('lng' => $lng, 'id' => $v->slug,'language_id'=>$language_id)) }}" style="border:1px solid #1f6b3a; padding:8px;" target="_blank" title="Read" class="knowledgeicon">
                       <img src="{{ asset('public/icons/book.png') }}">
                       <span>Read</span>
                </a>
                {{-- @if(!empty($v->brochure_link))
                <a href="{{ $v->brochure_link??'' }}" style="border:1px solid #1f6b3a; padding:8px;" target="_blank" title="Read" class="knowledgeicon">
                       <img src="{{ asset('public/icons/book.png') }}">
                       <span>Read</span>
                </a>
             
                @else
                @endif --}}
                 @if(!empty($v->webinar_link))
                <a href="{{ $v->webinar_link }}" class="open-video knowledgeicon"    
                   style="border:1px solid #1f6b3a; padding:8px;"  target="_blank" title="Watch">
                  <img src="{{ asset('public/icons/play-button.png') }}">
                    <span>Watch</span>
                </a>
                   @else
                   @endif
                  @if(!empty($v->podcast_link))
                  {{-- <a href="{{ $v->podcast_link }}" class="open-audio knowledgeicon" 
                   style="border:1px solid #1f6b3a; padding:8px;" title="Listen">
                   <img src="{{ asset('public/icons/headphones.png') }}">
                   <span>Listen</span>
                </a> --}}
                {{-- <a href="javascript:void(0)"
                    class="open-audio knowledgeicon"
                    style="border:1px solid #1f6b3a; padding:8px;"
                    title="Listen"
                    data-bs-toggle="modal"
                    data-bs-target="#spotifyModal">

                        <img src="{{ asset('public/icons/headphones.png') }}">
                        <span>Listen</span>
                    </a> --}}
   <a href="javascript:void(0)"
   class="open-audio knowledgeicon"
   style="border:1px solid #1f6b3a; padding:8px;"
   title="Listen"
   data-podcast="{{ $v->podcast_link }}"
   data-title="{{ $v->name ?? '' }}">

    <img src="{{ asset('public/icons/headphones.png') }}">
    <span>Listen</span>
</a>
                   @else
                   @endif
            </div>
        </div>

    </div>
</div>
  @empty
        <h3>No Record Found</h3>
    @endforelse   
    @endif








 
  


</div>
    <div class="prev_next_btn" >
        @if( $listData->previousPageUrl() != '' ) <a style="background-color:#fff;color: #008d5c;"  href="{{ $listData->previousPageUrl() }}"> < Prev  </a> @endif
        @if( $listData->nextPageUrl() != '' ) <a  style="background-color:#fff;color: #008d5c;"  href="{{ $listData->nextPageUrl() }}"> Next > </a> @endif 
    </div>

   
</div>
 
  


</section>

<div class="modal fade" id="mediaModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Gendi accuscid qui dolore mod quae nessim vel inum I</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body text-center" id="mediaContainer">
        <!-- Video / Audio will load here -->
      </div>

    </div>
  </div>
</div>



<div class="modal fade" id="spotifyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    &times;
                </button>
                <h4 class="modal-title">
                    <i class="fa fa-spotify text-success"></i>
                    Spotify Player
                </h4>
            </div>

            <div class="modal-body text-center">

                @if(!session()->has('spotify_access_token'))

                    <p>Please login with Spotify to listen to this audio.</p>

                  <a href="{{ route('spotify.login', ['redirect' => request()->fullUrl()]) }}"
                    class="btn btn-success">
                        <i class="fa fa-spotify"></i>
                        Login with Spotify
                    </a>

                @else

                    {{-- <p class="text-success">
                        <i class="fa fa-check-circle"></i>
                        Spotify connected successfully.
                    </p> --}}

                    {{-- <iframe
                        style="border-radius:12px;"
                        src="https://open.spotify.com/embed/track/4cOdK2wGLETKBW3PvgPWqT"
                        width="100%"
                        height="352"
                        frameborder="0"
                        allowfullscreen=""
                        allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture">
                    </iframe> --}}

  {{-- <iframe
    style="border-radius:12px"
    src="https://open.spotify.com/embed/episode/3oDrffYQdM9vk095VhstdW"
    width="100%"
    height="352"
    frameborder="0"
    allowfullscreen
    allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture">
</iframe> --}}

<iframe
    id="spotifyPlayer"
    style="border-radius:12px"
    src=""
    width="100%"
    height="352"
    frameborder="0"
    allowfullscreen
    allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture">
</iframe>

                @endif

            </div>

        </div>
    </div>
</div>

<!-- Mini Spotify Player -->
<div id="spotifyMiniPlayer" style="display:none; position:fixed; bottom:0; left:0; width:100%; z-index:1050; background:#191414; color:#fff; padding:12px 20px; align-items:center; justify-content:space-between; box-shadow:0 -4px 12px rgba(0,0,0,0.3);">
    <div style="display:flex; align-items:center; gap:12px; overflow:hidden;">
        <i class="fa fa-spotify" style="color:#1db954; font-size:22px; flex-shrink:0;"></i>
        <span id="spotifyMiniTitle" style="font-weight:500; font-size:14px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Now Playing on Spotify</span>
    </div>
    <div style="display:flex; align-items:center; gap:8px; flex-shrink:0;">
        <button type="button" id="expandSpotify" class="btn btn-sm" style="background:#1db954; color:#fff; border:none; border-radius:20px; padding:5px 15px; font-weight:500;">
            <i class="fa fa-expand"></i> Expand
        </button>
        <button type="button" id="closeMiniPlayer" class="btn btn-sm" style="background:transparent; color:#fff; border:none; padding:5px 8px; font-size:16px; line-height:1;">
            <i class="fa fa-times"></i>
        </button>
    </div>
</div>

        </div>
    </div>
</div>
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
    var kh_product = $('#kh_product').val();
    var kh_commodity = $('#kh_commodity').val();
    var kh_location = $('#kh_location').val();
    var kh_type = $('#kh_type').val();
    var kh_language = $('#kh_language').val();
    var search = $('#search').val();

    $.ajax({
        url: "{{ route('knowledgeAjax') }}", // Laravel route helper
        type: "POST",
        dataType: "json",
        data: {
            kh_product: kh_product,
            kh_commodity: kh_commodity,
            kh_location: kh_location,
            kh_type: kh_type,
            kh_language: kh_language,
            search: search,
            _token: "{{ csrf_token() }}" // CSRF protection
        },
        success: function(data) {
            if (data.success) {
                $("#listWebinars").html(data.html); // use data.html from JSON
            } else {
                $("#listWebinars").html("<h3>No Record Found</h3>");
            }
        },
        error: function(jqXHR, ajaxOptions, thrownError) {
            alert('No response from server');
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


<script>
$(document).on('click', '.open-audio', function () {

    let spotifyUrl = $(this).data('podcast');

    // Extract episode ID
    let match = spotifyUrl.match(/episode\/([A-Za-z0-9]+)/);

    if (match) {
        let episodeId = match[1];
        let title = $(this).data('title') || 'Now Playing on Spotify';

        localStorage.setItem('pendingSpotifyEpisode', episodeId);
        localStorage.setItem('pendingSpotifyTitle', title);

        let currentSrc = $('#spotifyPlayer').attr('src') || '';
        if (!currentSrc.includes(episodeId)) {
            $('#spotifyPlayer').attr(
                'src',
                'https://open.spotify.com/embed/episode/' + episodeId
            );
        }

        $('#spotifyMiniPlayer').hide();
        $('#spotifyModal').modal('show');
    }
});
</script>

<script>
$(document).ready(function () {
    const params = new URLSearchParams(window.location.search);

    if (params.get('spotify_connected') == '1') {
        let pendingEpisode = localStorage.getItem('pendingSpotifyEpisode');
        if (pendingEpisode) {
            let currentSrc = $('#spotifyPlayer').attr('src') || '';
            if (!currentSrc.includes(pendingEpisode)) {
                $('#spotifyPlayer').attr(
                    'src',
                    'https://open.spotify.com/embed/episode/' + pendingEpisode
                );
            }
            localStorage.removeItem('pendingSpotifyEpisode');
        }
        $('#spotifyMiniPlayer').hide();
        $('#spotifyModal').modal('show');

        history.replaceState({}, document.title, window.location.pathname);
    }
});
</script>

<script>
$(document).ready(function () {
    // Show mini player when modal is hidden
    $('#spotifyModal').on('hide.bs.modal', function () {
        let $player = $('#spotifyPlayer');
        if ($player.length && $player.attr('src')) {
            let title = localStorage.getItem('pendingSpotifyTitle') || 'Now Playing on Spotify';
            $('#spotifyMiniTitle').text(title);
            $('#spotifyMiniPlayer').css('display', 'flex');
        }
    });

    // Expand mini player back to modal
    $(document).on('click', '#expandSpotify', function () {
        $('#spotifyMiniPlayer').hide();
        $('#spotifyModal').modal('show');
    });

    // Close mini player manually
    $(document).on('click', '#closeMiniPlayer', function () {
        $('#spotifyMiniPlayer').hide();
        localStorage.removeItem('pendingSpotifyEpisode');
        localStorage.removeItem('pendingSpotifyTitle');
    });
});
</script>



@endpush

    