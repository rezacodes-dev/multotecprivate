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
   
        <title>Podcasts</title>
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
@import url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap');
body,h1,h2,h3,h4,h5,h6,p,input,a,select,textarea {
    font-family: "Roboto", sans-serif !important;
}
.art-pagination {
    text-align: right;
}
.art-pagination .pagination {
    margin-top: 22px;
}
.podcast-play{
    color:#008d5c;
    font-size:28px;
    text-decoration:none;
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
    background: #1DB954;
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
    border-bottom: 3px solid #1DB954;
	box-shadow: 0px 8px 13px #ccc;
}
.filterbox select.form-control{
	height:45px !important;
	border:1px solid #ccc;
}
.midblock p{
    font-size: 17px;
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
    background: #1DB954;
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
    color: #1DB954;
    border: 1px solid #1DB954;
    border-radius: 23px;
}	
	
.piccont ul li a:hover{background:#1DB954; color:#fff;}
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
    color: #1DB954;
    font-size: 21px;
}
.filtersecrch input.filtersearch {
    padding-right: 46px;
}
/* .product-card {
    background: #fff;
    border-radius: 4px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    text-align: center;
    transition: 0.3s ease;
    min-height: 472px;
    margin: 0 0 30px;
} */
 .product-card{
    border:1px solid #ddd;
    background:#f5f5f5;
    padding:15px 20px;
}

.podcast-play {
    width: 42px;
    height: 42px;
    background: #008d5c;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    margin-left: 20px;
    flex-shrink: 0;
    font-size: 18px;
    border-radius: 50%;
}

.podcast-play:hover{
    color:#fff;
    background:#006b45;
}

.episode-item {
    border-bottom: 2px solid #e0e0e0;
}
.episode-item:last-child {
    border-bottom: none;
}
.podcast-play.knowledgeicon {
    background: transparent;
    color: #008c5be8;
}
.podcast-play.knowledgeicon:hover {
    background: transparent;
    color: #008c5be8;
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
.main-podcast-wrapper {
    position: relative;
    width: 100%;
    height: 352px;
    border-radius: 12px;
    overflow: hidden;
    background: #1DB954;
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
        <li>Mineral Processing Insights Podcast</li>
    </ul>
</div>



<h1>Mineral Processing Insights Podcast</h1>

<div class="row">
    <div class="col-sm-8">
        <div class="midblock" id="firstBlock">
            
            <!-- Main Page Content -->
                        <p>Explore Mineral Processing Insights Podcasts from Multotec — featuring expert discussions, industry trends, and practical perspectives on improving efficiency, sustainability, and performance across mineral processing operations. Listen in for valuable insights from specialists who understand the challenges and opportunities shaping the mining and minerals sector.</p>
            
                                         
            <!-- Loop -->
                                                </div>
    </div>
    
        <div class="col-sm-4">
 
                                                                                          </div> 
                                        </div>
                                    </div>
                                 
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
            <!-- Loop -->
                                                </div>
    </div>
    
</div>




@if(isset($listData) && !empty($listData))
<div class="picboxsection">
<div class="row" id="listWebinars">


@php
    $firstEpisodeId  = null;
    $firstEpisodeUri = null;
    $firstEmbedUrl   = null;

    if( isset($listData) && count($listData) > 0 ) {
        $firstPodcastLink = $listData[0]->podcast_link ?? '';
        if( !empty($firstPodcastLink) ) {
            preg_match('/episode\/([A-Za-z0-9]+)/', $firstPodcastLink, $matches);
            if( isset($matches[1]) ) {
                $firstEpisodeId  = $matches[1];
                $firstEpisodeUri = 'spotify:episode:' . $firstEpisodeId;
                $firstEmbedUrl   = 'https://open.spotify.com/embed/episode/' . $firstEpisodeId . '?theme=0';
            }
        }
    }

    $defaultEpisodeId  = '4WLyPOZmbK9xS2HgKZL0nj';
    $defaultEpisodeUri = 'spotify:episode:' . $defaultEpisodeId;
    $defaultEmbedUrl   = 'https://open.spotify.com/embed/episode/' . $defaultEpisodeId . '?theme=0';
@endphp

<div class="col-sm-12 col-md-12">

<div id="mainPodcastWrapper" class="main-podcast-wrapper">

    <iframe
        id="mainPodcastIframe"
        style="display:block; width:100%; height:352px; border:0; border-radius:12px;"
        src="{{ $firstEmbedUrl ?? $defaultEmbedUrl }}"
        allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
        loading="eager">
    </iframe>

</div>
<div class="" style="border:1px solid #ddd;background:#f5f5f5;padding:15px 20px;">

@foreach($listData as $key => $v)

<div class="episode-item" style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;">

    <a href="javascript:void(0);"
       class="podcast-play-trigger"
       data-podcast="{{ $v->podcast_link ?? '' }}"
       data-title="{{ $v->podcast_title ?? '' }}"
       style="flex:1;text-decoration:none;color:#000;font-size:15px;font-weight:500;line-height:1.4;">
        {{ $key + 1 }}. {{ $v->podcast_title ?? '' }}
    </a>
     <span>{{$v->podcast_time??''}}</span>
    <a href="javascript:void(0);"
       class="podcast-play knowledgeicon"
       data-podcast="{{ $v->podcast_link ?? '' }}"
       data-title="{{ $v->podcast_title ?? '' }}"
       style="border:1px solid #1f6b3a; padding:8px;"
       title="Listen">
        <img src="{{ asset('public/icons/headphones.png') }}">
        {{-- <span>Listen</span> --}}
    </a>

</div>

@endforeach

</div>

</div>

{{-- 
<div class="prev_next_btn" >
    @if( $listData->previousPageUrl() != '' )
        <a style="background-color:#fff;color:#008d5c;" href="{{ $listData->previousPageUrl() }}"> < Prev </a>
    @endif

    @if( $listData->nextPageUrl() != '' )
        <a style="background-color:#fff;color:#008d5c;" href="{{ $listData->nextPageUrl() }}"> Next > </a>
    @endif
</div>
--}}

</div>
</div>
 
  @else
  @endif


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



{{-- <div class="modal fade" id="spotifyModal" tabindex="-1" role="dialog">
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

                <div id="spotifyPlayerContainer" style="border-radius:12px; min-height:352px;"></div>

            </div>

        </div>
    </div>
</div> --}}

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

 


// function getWebinars() {
//     var kh_product = $('#kh_product').val();
//     var kh_commodity = $('#kh_commodity').val();
//     var kh_location = $('#kh_location').val();
//     var kh_type = $('#kh_type').val();
//     var kh_language = $('#kh_language').val();
//     var search = $('#search').val();

//     $.ajax({
//         url: "{{ route('knowledgeAjax') }}", // Laravel route helper
//         type: "POST",
//         dataType: "json",
//         data: {
//             kh_product: kh_product,
//             kh_commodity: kh_commodity,
//             kh_location: kh_location,
//             kh_type: kh_type,
//             kh_language: kh_language,
//             search: search,
//             _token: "{{ csrf_token() }}" // CSRF protection
//         },
//         success: function(data) {
//             if (data.success) {
//                 $("#listWebinars").html(data.html); // use data.html from JSON
//             } else {
//                 $("#listWebinars").html("<h3>No Record Found</h3>");
//             }
//         },
//         error: function(jqXHR, ajaxOptions, thrownError) {
//             alert('No response from server');
//         }
//     });
// }



function getWebinars(page = 1) {

    let kh_product   = $('#kh_product').val() || '';
    let kh_commodity = $('#kh_commodity').val() || '';
    let kh_location  = $('#kh_location').val() || '';
    let kh_type      = $('#kh_type').val() || '';
    let kh_language  = $('#kh_language').val() || '';
    let search       = $('#search').val() || '';

    let params = new URLSearchParams();

    params.set('kh_product', kh_product);
    params.set('kh_commodity', kh_commodity);
    params.set('kh_location', kh_location);
    params.set('kh_type', kh_type);
    params.set('kh_language', kh_language);
    params.set('search', search);
    params.set('page', page);

    history.pushState({}, '', window.location.pathname + '?' + params.toString());

    $.ajax({
        url: "{{ route('knowledgeAjax') }}",
        type: "POST",
        dataType: "json",
        data: {
            kh_product: kh_product,
            kh_commodity: kh_commodity,
            kh_location: kh_location,
            kh_type: kh_type,
            kh_language: kh_language,
            search: search,
            page: page,
            query_string: params.toString(),
            _token: "{{ csrf_token() }}"
        },
        success: function (response) {
            if (response.success) {
                $("#listWebinars").html(response.html);
            } else {
                $("#listWebinars").html("<h3>No Record Found</h3>");
            }
        },
        error: function () {
            alert('No response from server');
        }
    });
}

$(document).on('click', '.prev_next_btn a', function (e) {
    e.preventDefault();

    let href = $(this).attr('href');
    if (!href) return;

    let page = new URL(href).searchParams.get('page') || 1;

    getWebinars(page);
});

 
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
    function buildSpotifyEmbedUrl(spotifyUrl) {
        let match = spotifyUrl.match(/episode\/([A-Za-z0-9]+)/);
        if (!match) return null;
        return 'https://open.spotify.com/embed/episode/' + match[1] + '?theme=0';
    }

    function swapPodcastEmbed(spotifyUrl) {
        let embedUrl = buildSpotifyEmbedUrl(spotifyUrl);
        if (!embedUrl) return;

        let iframe = document.getElementById('mainPodcastIframe');
        if (iframe) {
            iframe.src = embedUrl;
        }
    }

    // Headphone icon: load episode immediately
    $(document).on('click', '.podcast-play', function () {
        let spotifyUrl = $(this).data('podcast');
        if (spotifyUrl) {
            swapPodcastEmbed(spotifyUrl);
        }
    });

    // Episode title: switch episode in the main player
    $(document).on('click', '.podcast-play-trigger', function () {
        let spotifyUrl = $(this).data('podcast');
        if (spotifyUrl) {
            swapPodcastEmbed(spotifyUrl);
        }
    });

    // Honor ?episode= URL parameter by swapping the iframe to that episode
    $(document).ready(function () {
        let params = new URLSearchParams(window.location.search);
        let episode = params.get('episode');
        if (episode && /^[A-Za-z0-9]+$/.test(episode)) {
            swapPodcastEmbed('https://open.spotify.com/episode/' + episode);
        }
    });
</script>



@endpush

    