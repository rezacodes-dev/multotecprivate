@extends('front_end.layout.layout_master')

@push('page_css')
<link rel="stylesheet" href="{{ asset('public/front_end/css/lightbox.min.css') }}">
@endpush

@section('page_content')
<section class="innerpage-banner"><img src="{{ asset('public/front_end/images/cyclone_main-img.jpg') }}" alt=""></section>
<section class="container">
<div class="breadcrumb">
<ul>
<li><a href="#">Home</a></li>
<li><a href="#">Step 1</a></li>
<li class="active">Step 2</li>
</ul>
</div>

<div class="midblock">
<div class="row">
<div class="col-sm-7"><h1>Photo Gallery</h1></div>
<div class="col-sm-3">
<div class="search"><input type="text" class="form-control" placeholder="Search"><button type="submit" value=""><i class="fa fa-search" aria-hidden="true"></i></button></div>
</div>
<div class="col-sm-2"><button type="button" value="" class="slideshow_btn">View Slideshow</button></div>
</div>

<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it, text of the printing and typesetting industry. Lorem Ipsum has to make a type ply dummy text of the printing and typesetting industry. Lorem Ipsum has been the specimen book.</p>
<div class="tab_info_list">
    <div class="row">
            <div class="col-sm-4">
              <ul>
                <li><a href="#">Screening Media</a></li>
                <li><a href="#">Screening Media</a></li>
                <li><a href="#">Screening Media</a></li>
               </ul>
            </div>
            <div class="col-sm-4">
                <ul>
                <li><a href="#">Screening Media</a></li>
                <li><a href="#">Screening Media</a></li>
                <li><a href="#">Screening Media</a></li>
               </ul>
            </div>
            <div class="col-sm-4">
                <ul>
                <li><a href="#">Screening Media</a></li>
                <li><a href="#">Screening Media</a></li>
                <li><a href="#">Screening Media</a></li>
               </ul>
            </div>
        </div>
</div>
<div class="gallery">
<div class="row">
@if( isset($images) )
    @foreach( $images as $img )
        @if( isset($img->masterImageInfo) )
        <div class="col-sm-4">
        <a class="example-image-link" href="{{ asset('public/uploads/files/media_images/' . $img->masterImageInfo->image) }}" data-lightbox="example-set" data-title="{{ $img->img_title }}">
            <img class="example-image" src="{{ asset('public/uploads/files/media_images/' . $img->masterImageInfo->image) }}" alt="{{ $img->img_alt }}" style="width: 360px; height: 271px;" />
        </a>
        <h5>{{ $img->img_caption }}</h5>
        </div>
        @endif
    @endforeach
</div>
<div class="prev_next_btn" >
    @if( $images->previousPageUrl() != '' ) <a href="{{ $images->previousPageUrl() }}"> < Prev  </a> @endif
    @if( $images->nextPageUrl() != '' ) <a href="{{ $images->nextPageUrl() }}"> Next > </a> @endif 
</div>
</div>
@endif

<div class="btm_more_sec">
<p>Lorem Ipsum is simply dummy text of the printing industry. <a href="#">Lorem Ipsum</a> has been standard.</p>
</div>

</section>
@endsection




@push('page_js')
<script src="{{ asset('public/front_end/js/lightbox.min.js') }}"></script>
@endpush

    