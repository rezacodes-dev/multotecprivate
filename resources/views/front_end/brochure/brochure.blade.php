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
    padding: 0 35px 10px 50px;
    position: relative;
}
.outerinner img {
    position: absolute;
    left: 0;
    width: 44px;
    top: 5px;
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
.topbardiv {
    display: flex !important;
    justify-content: space-between !important;
    flex-wrap: wrap !important;
}
.topbardiv .outerdiv {
    display: flex !important;
    justify-content: space-between !important;
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
            <li>Product Brochure & Files</li>
        </ul>
    </div>

    <div class="row align-items-center">
        <!-- Left: Image -->
        <div class="col-md-5 text-center">
            <div class="product-image-container" style="padding:0;">
            @php
    $imageURL = isset($listData->thumbnail_image) && $listData->thumbnail_image != '' 
        ? asset('public/' . $listData->thumbnail_image) 
        : asset('public/images/default_multotec.jpg');
@endphp

<img src="{{ $imageURL }}" alt="{{ $listData->name ?? '' }}" class="img-fluid">
            </div>
        </div>

        <!-- Right: Content -->
        <div class="col-md-7">
            <h2>{{ $listData->name ?? '' }}</h2>

            @if(!empty($listData->description))
            {!! html_entity_decode( $listData->description ) !!}
            @else
            @endif
            <!-- <p>
                This combination screen panel offers the excellent wear characteristics of polyurethane coupled with the high open areas of wedgewire.
            </p>
            <p>
                The innovative screen panel consists of a standard modular 305 x 305 x 30 mm thick polyurethane frame with an ‘Optima’ stainless steel wedgewire insert - offering high abrasion and corrosion resistance. The polyurethane frame is injection moulded and the wedgewire insert is mechanically fastened to the frame.
            </p> -->

            <!-- Languages -->
           
@php
    // Group by language_id
    $brochureGrouped = collect($fetchBrochureDetails)->groupBy('language_id');
@endphp

<div class="mb-3 topbardiv">
    <div class="languageinner">
        <img src="{{ asset('public/icons/lang-icon.svg') }}" width="42" height="44">
        <strong>Languages</strong><br>

        @foreach($brochureGrouped as $langId => $brochures) 
            <a href="javascript:void(0)" 
               class="language-link {{ strtolower($brochures->first()->brochure_lang) == 'english' ? 'active' : '' }}" 
               data-lang="{{ $langId }}">
               {{ $brochures->first()->brochure_lang ?? '' }}
            </a>
        @endforeach
    </div>

    <div class="outerinner">
                <a style="text-decoration:none;" href="{{ asset('public/' . $brochures->first()->brochure_pdf) }}" target="_blank">
                    <img src="{{ asset('public/icons/view-icon.svg') }}">
                    <strong>View<br>brochure</strong>
                </a>
            </div>
</div>

<div class="action-links topbardiv" style="display: flex; flex-direction: column; gap: 30px;">
    @foreach($brochureGrouped as $langId => $brochures)
        <div class="outerdiv" data-language="{{ $langId }}" style="display: none; flex-wrap: wrap; gap: 6px;">

            {{-- Download Section --}}
            <div class="outerinner">
                <img src="{{ asset('public/icons/pdf-icon.svg') }}">
                <strong>Download size</strong><br>
                @foreach($brochures as $value)
                    @if(!empty($value->brochure_pdf))
                        <a href="{{ asset('public/' . $value->brochure_pdf) }}" 
                           download="{{ $value->download_name ?? 'brochure.pdf' }}">
                            {{ $value->brochure_size ?? '' }}
                        </a>
                    @endif
                @endforeach
            </div>

            {{-- View Brochure (Just one link) --}}
       

            {{-- Email Brochure --}}
            <div class="outerinner">
                <img src="{{ asset('public/icons/e-mail-icon.svg') }}">
                <strong>Email brochure</strong><br>
                @foreach($brochures as $value)
                    @if(!empty($value->brochure_pdf))
                        <a href="javascript:void(0)" 
                           class="open-email-modal" 
                           data-toggle="modal" 
                           data-target="#emailModal"
                           data-brochure="{{ asset('public/' . $value->brochure_pdf) }}" 
                           data-type="{{ $value->brochure_size ?? '' }}">
                            {{ $value->brochure_size ?? '' }}
                        </a>
                    @endif
                @endforeach
            </div>

            <!-- <div class="outerinner">
                <img src="{{ asset('public/icons/whatsapp-icon.png') }}">
                <strong>Whatsapp</strong><br>
                @foreach($brochures as $value)
                    @if(!empty($value->brochure_pdf))
                        <a href="javascript:void(0)" 
                           class="open-email-modal" 
                           data-toggle="modal" 
                           data-target="#emailModal"
                           data-brochure="{{ asset('public/' . $value->brochure_pdf) }}" 
                           data-type="{{ $value->brochure_type ?? '' }}">
                            {{ $value->brochure_type ?? '' }}
                        </a>
                    @endif
                @endforeach
            </div> -->
            <div class="outerinner">
  <img src="{{ asset('public/icons/whatsapp-icon.svg') }}">
<strong>Whatsapp</strong><br>

@foreach($brochures as $value) 

    @if(!empty($value->brochure_pdf))

        @php
            $brochurePdf = $value->brochure_pdf ?? '';

            // Encode spaces and special characters properly
            $encodedPdf = str_replace('%2F', '/', rawurlencode($brochurePdf));

            $brochureLink = asset('public/' . $encodedPdf);

            $type = $value->brochure_size ?? '';
            $typeSingle = str_replace(' ', '', trim($type));

            $message = urlencode(
                "Here is a link to the Multotec brochure, which I thought you might find interesting.\n\nClick Here: $brochureLink"
            );
        @endphp

        <a href="https://wa.me/{{ env('WHATSAPP_NUMBER','') }}?text={{ $message }}" 
           target="_blank">
            {{ $typeSingle }}
        </a>

    @endif

@endforeach



</div>

        </div>
    @endforeach
</div>


    </div>


    <!-- Email Modal -->
<!-- Bootstrap 3 Email Modal -->
<div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="emailForm" action="javascript:void(0);">
                @csrf
                <input type="hidden" name="brochure_link" id="brochure_link">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="emailModalLabel">Send Brochure via Email</h4>
                </div>

                <div class="modal-body">

                    <div class="form-group">
                        <label for="recipient_name">Name</label>
                        <input type="text" name="recipient_name" id="recipient_name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="recipient_email">Email</label>
                        <input type="email" name="recipient_email" id="recipient_email" class="form-control" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"  >Cancel</button>
                    <input type="submit" class="btn btn-primary" value="Send Email">
                </div>
            </form>
        </div>
    </div>
</div>

</section>


@endsection





@push('page_js')
<script type="text/javascript" src="{{ asset('public/front_end/js/ddaccordion.js') }}"></script>


<script>
$(document).ready(function () {

// when modal opens, set brochure link
$('#emailModal').on('show.bs.modal', function (event) {

    var button = $(event.relatedTarget);
    var brochure = button.data('brochure');
    $('#brochure_link').val(brochure);
});

$('#emailModal').on('hidden.bs.modal', function () {
    $('#emailForm')[0].reset();           // clear form inputs
    $('.error-text').remove();            // remove error messages
    $('#recipient_name-error').remove();            // remove error messages
    $('#recipient_email-error').remove();            // remove error messages
    $('#emailForm [type="submit"]').val('Send Email'); // reset button text if needed
});

// handle modal form submit
$('#emailForm').on('submit', function (e) {
    e.preventDefault();   // stop default form submit

    // clear previous errors
    $('.error-text').remove();

    let email = $('#recipient_email').val().trim();
    let name = $('#recipient_name').val().trim();
    let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (name === "") {
       
        return false;
    }
    if (email === "") {
    
        return false;
    }
    if (!emailPattern.test(email)) {
   
        return false;
    }

    $.ajax({
        url: "{{ route('send.brochure.email') }}", // Laravel route
        type: "POST",
        data: $(this).serialize(),
        beforeSend: function () {
            $('#emailForm [type="submit"]').prop('disabled', true).val('Sending...');
        },
        success: function (response) {
       
            $('#emailModal').modal('hide');
            $('#emailForm')[0].reset();
            // location.reload(); // uncomment if you want reload
            alert("Email Sent Successfully");
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                var errors = xhr.responseJSON.errors;
                $.each(errors, function (key, value) {
                    let input = $('#' + key);
                    if (input.length) {
                        input.after('<small class="error-text text-danger">' + value[0] + '</small>');
                    }
                });
            } else {
                alert("Something went wrong. Please try again.");
            }
        },
        complete: function () {
            $('#emailForm [type="submit"]').prop('disabled', false).val('Send Email');
        }
    });

    return false; // 👈 ensures no page reload
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

@endpush

    