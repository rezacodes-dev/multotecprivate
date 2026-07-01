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
}
.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}
.product-card img {
    width: 100%;
     min-height: 250px
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
.brochure-section {
    padding-top: 40px;
    padding-bottom: 60px; /* extra bottom space so it doesn’t touch footer */
}
.product-image-container {
 
    padding: 20px;
    display: inline-block;
}
.product-image-container img {
    max-width: 100%;
    height: auto;
}
.action-links {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
    margin-top: 20px;
}
.action-item {
    text-decoration: none;
    color: #007b3d;
    font-weight: 500;
    text-align: center;
}
.action-item:hover {
    text-decoration: underline;
}
.icon {
    width: 30px;
    height: auto;
    display: block;
    margin: 0 auto 5px;
}
.languageinner {
    position: relative;
    padding: 0 0 20px 53px;
    line-height: 26px;
}
.languageinner img {
    position: absolute;
    left: 0;
    top: 2px;
}
.languageinner a {
    display: inline-block;
    padding: 3px 8px 0 0;
    color: #000;
    /* text-decoration: underline; */
    font-size: 15px;
    font-weight: 500;
}
.languageinner strong {
    color: #3b8d65;
}

.outerdiv {
    display: flex;
    align-items: center;
}
.outerinner {
    padding: 0 35px 10px 60px;
    position: relative;
}
.outerinner img {
    position: absolute;
    left: 0;
}
.outerinner strong {
    font-size: 17px;
    color: #3b8d65;
}

.outerinner a {
    display: inline-block;
    padding: 3px 8px 0 0;
    color: #000;
    /* text-decoration: underline; */
    font-size: 15px;
    font-weight: 500;
}
.active{
    text-decoration: underline;  
}
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
p {
    padding: 0 0 15px;
    margin: 0!important;
    font-weight: 400;
    font-size: 14px;
    line-height: 1.6;
    color: #000;
}
.menuzord-menu>li {
    margin: 5px 0px 0 5px;
}
</style>


<link rel="stylesheet" href="{{ asset('public/front_end/css/jquery.tabs.css') }}">
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> --}}
@endpush

@section('page_content')

@if( isset($extraContent) && $extraContent->image_id != '' && isset($extraContent->imageInfo) )
<section class="innerpage-banner">
    <img src="{{ asset('public/uploads/files/media_images/'.$extraContent->imageInfo->image) }}" title="{{ $extraContent->image_title }}" alt="{{ $extraContent->image_alt }}" caption="{{ $extraContent->image_caption }}">
</section>
@endif

@section('page_content')

@if( isset($extraContent) && $extraContent->image_id != '' && isset($extraContent->imageInfo) )
<section class="innerpage-banner">
    <img src="{{ asset('public/uploads/files/media_images/'.$extraContent->imageInfo->image) }}" title="{{ $extraContent->image_title }}" alt="{{ $extraContent->image_alt }}" caption="{{ $extraContent->image_caption }}">
</section>
@endif

<section class="container brochure-section" style="margin-top: 40px; margin-bottom: 60px;">

    <!-- Breadcrumb -->
    <div class="breadcrumb mb-4">
        <ul>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li><a href="{{ url('/en/knowledgehub') }}">Knowledge Hub</a></li>
        </ul>
    </div>

<div style=" background:#f3f3f3; max-width:100%; margin:auto; font-family:Arial, sans-serif;">

    <!-- Top Section -->
    <div style="display:flex;">
          @php
    $imageURL = isset($listData->image) && $listData->image != '' 
        ? asset('public/' . $listData->image) 
        : asset('public/images/default_multotec.jpg');
@endphp
        <!-- Left Image -->
        <div style="width:50%;">
            <img src="{{ $imageURL??'' }}"
                 style="width:100%; height:100%; object-fit:cover; display:block;">
        </div>

        <!-- Right Content -->
        <div style="width:50%; padding: 25px 25px 25px 50px; display:flex; flex-direction:column; justify-content:center;">

            <h4 style="margin-bottom:20px;font-weight:500;color:#333;line-height:1.4;font-size: 28px !important;">
               {{ $listData->name ?? '' }}
            </h4>

            <!-- Icons -->
            <div style="display:flex; gap:12px; align-items:center;">

            {{-- @if(!empty($listData->brochure_link))
                <a href="{{ $listData->brochure_link??'' }}" style="" target="_blank" class="knowledgeicon" title="Read" ><img src="{{ asset('public/icons/book.png') }}">
                <span>Read</span>
                </a>
                @else
                @endif --}}
                 @if(!empty($listData->webinar_link))
                <a href="{{ $listData->webinar_link }}" class="open-video knowledgeicon"    
                   style=""  target="_blank" title="Watch">
                   <img src="{{ asset('public/icons/play-button.png') }}">
                    <span>Watch</span>
                </a>
                   @else
                   @endif
                  @if(!empty($listData->podcast_link))
                 <a href="javascript:void(0)"
 <a href="javascript:void(0)"
   class="open-audio knowledgeicon"
   data-podcast="{{ $listData->podcast_link }}"
   data-title="{{ $listData->name ?? '' }}"
   title="Listen">

    <img src="{{ asset('public/icons/headphones.png') }}">
    <span>Listen</span>
</a>
                   @else
                   @endif

              
         @php
  $shorturl = url()->full();


    $message = urlencode("Here is a link to the Multotec Knowledge resource, which I thought you might find interesting. " . $shorturl);
@endphp

<a href="https://wa.me/{{ env('WHATSAPP_NUMBER') }}?text={{ $message }}" 
   style="" 
   target="_blank" 
   class="knowledgeicon" title="Share On Whatsapp">
    
    <img src="{{ asset('public/icons/share.png') }}">
        <span>Share</span>
</a>

            </div>

        </div>

    </div>

    <!-- Bottom Description -->
    <div style="background:#ffffff; padding:20px 0px; font-size:13px; color:#555; line-height:1.7;">

       @if(!empty($listData->description))
            {!! html_entity_decode( $listData->description ) !!}
            @else
            @endif

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
                <h4 class="modal-title">
                    <i class="fa fa-spotify text-success"></i>
                    Spotify Player
                </h4>

                {{-- <button type="button"
                        class="btn btn-sm btn-secondary mr-2"
                        id="minimizeSpotify">
                    Minimize
                </button> --}}

                <button type="button"
                        class="close"
                        data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body text-center">

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

@endsection





@push('page_js')
<script type="text/javascript" src="{{ asset('public/front_end/js/ddaccordion.js') }}"></script>


<script>
    $(document).ready(function () {
    // Set brochure link when modal opens
    $('#emailModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        var brochure = button.data('brochure'); 
        $('#brochure_link').val(brochure);
    });

    $('#emailForm').on('submit', function (e) {
    e.preventDefault();

    let email = $('#recipient_email').val().trim();

// simple regex for email validation
let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

if (email === "") {
 
    return false;
    } else if (!emailPattern.test(email)) {
   
        return false;
    }

    $.ajax({
        url: "{{ route('send.brochure.email') }}",
        type: "POST",
        data: $(this).serialize(),
        beforeSend: function () {
            $('#emailForm button[type="submit"]').prop('disabled', true).text('Sending...');
        },
        success: function (response) {
            alert(response.message);
            $('#emailModal').modal('hide');
            $('#emailForm')[0].reset();
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                var errors = xhr.responseJSON.errors;
                var errorMessage = '';
                $.each(errors, function (key, value) {
                    errorMessage += value[0] + "\n";
                });
                alert(errorMessage);
            } else {
                alert("Something went wrong. Please try again.");
            }
        },
        complete: function () {
            $('#emailForm button[type="submit"]').prop('disabled', false).text('Send Email');
        }
    });
});



});

</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Get all language links and action rows
    const languageLinks = document.querySelectorAll('.language-link');
    const actionRows = document.querySelectorAll('.outerdiv');

    function showLanguage(languageId) {
        actionRows.forEach(row => {
            row.style.display = row.getAttribute('data-language') === languageId ? 'flex' : 'none';
        });
    }

    // Set default language (English)
    const defaultLangLink = document.querySelector('.language-link.active');
    if (defaultLangLink) {
        showLanguage(defaultLangLink.getAttribute('data-lang'));
    }

    // Handle click on language links
    languageLinks.forEach(link => {
        link.addEventListener('click', function () {
            languageLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            showLanguage(this.getAttribute('data-lang'));
        });
    });
});

</script>
<script>
    $(document).on('click', '.open-audio', function () {

    let podcastUrl = $(this).data('podcast');

    if (podcastUrl.includes('open.spotify.com')) {

        let match = podcastUrl.match(/episode\/([A-Za-z0-9]+)/);

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

    } else {

        window.open(podcastUrl, '_blank');
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

    