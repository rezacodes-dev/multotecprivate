@extends('front_end.layout.layout_master')
@include('front_end.structure.page_meta')


@push('page_css')
<link rel="stylesheet" href="{{ asset('public/front_end/css/lightbox.min.css') }}">
<style type="text/css">
.lb-data .lb-close {
    display:block;
    position:absolute;
    right:50px;
    top:5px;
}
</style>
@endpush



@section('page_content')
<section class="container">
    <div class="breadcrumb2">
        <ul>
         <li><a href="{{ url('/') }}">Home</a></li>
         <li><a href="{{ route('img_gal_cats', array('lng' => $lng)) }}">Gallery</a></li>
         @if( isset($breadcrumb_cat_name) && isset($breadcrumb_cat_slug) )
         <li>
            <a href="{{ route('front_galSubCat', array('lng' => $lng, 'category' => $breadcrumb_cat_slug)) }}">{{ $breadcrumb_cat_name }}</a>
         </li>
         @endif
         @if( isset($breadcrumb_subcat_name) && isset($breadcrumb_subcat_slug) )
         <li class="active">
            {{ $breadcrumb_subcat_name }}
         </li>
         @endif
        </ul>
    </div>

<div class="midblock">
<div class="row">
<div class="col-sm-7"><h1>@if(isset($catName)){{ $catName }}@endif</h1></div>
<div class="col-sm-2"></div>
<div class="col-sm-3">
<div class="search">
    <form name="frm" method="GET">
        <input type="text" class="form-control" placeholder="Search" name="search" value="@if(isset($_GET['search'])){{ $_GET['search'] }}@endif">
        <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
    </form>
</div>
</div>
<!--div class="col-sm-2"><button type="button" value="" class="slideshow_btn">View Slideshow</button></div-->
</div>

<p>@if( isset($page_data) ){!! html_entity_decode( $page_data->page_content, ENT_QUOTES) !!}@endif</p>

<div class="row artab">
    <div class="col-sm-6 tab @if(isset($tab_tag) && $tab_tag == 'image_gallery') active @endif">
        <a href="{{ route('img_gal_cats', array('lng' => $lng)) }}">Photo Gallery</a></div>
    <div class="col-sm-6 tab @if(isset($tab_tag) && $tab_tag == 'video_gallery') active @endif">
        <a href="{{ route('vid_gal_cats', array('lng' => $lng)) }}">Video Gallery</a></div>
</div>


@if( isset($catSlug) && isset($catName) )
<div class="tab_info_list">

    <div class="row">
        {{--<div class="col-sm-4">
            <ul><li>
                <a href="{{ route('front_galSubCat', array('lng' => $lng, 'catSlug' => $catSlug)) }}">{{ $catName }}</a>
            </li></ul>
        </div>--}}
        @if( isset($imgSubCategories) && count($imgSubCategories) > 0 )
        @foreach( $imgSubCategories as $isc )
        <div class="col-sm-4">
          <ul><li><a href="{{ route('front_galSubCat', array('lng' => $lng, 'category' => $catSlug, 'subcategory' => $isc->slug)) }}">{{ $isc->name }}</a></li></ul>
        </div>
        @endforeach
        @endif
    </div>
</div>
@endif

@if( isset($viewImages) )
<div class="gallery">
    <div class="row">
        @foreach( $viewImages as $img )
        <div class="col-sm-4">
          <a class="example-image-link" href="{{ asset('public/uploads/files/media_images/' . $img->image) }}" data-lightbox="example-set" data-title="<span class='lightbox-head'>{{ $img->caption }}</span><span class='lightbox-desc'>{{ $img->description }}</span>">
          <div class="video-thumb">
            <div class="videooverlay"></div>
            <div class="imgxfix">
              <img class="example-image" src="{{ asset('public/uploads/files/media_images/' . $img->image) }}" alt="{{ $img->alt_title }}" title="{{ $img->title }}"/>
            </div>
            <h5>{{ $img->name }}</h5>
          </div>
          </a>
        </div>
        @endforeach
    </div>
    <div class="prev_next_btn" >
        @if( $viewImages->previousPageUrl() != '' ) <a href="{{ $viewImages->previousPageUrl() }}"> < Prev  </a> @endif
        @if( $viewImages->nextPageUrl() != '' ) <a href="{{ $viewImages->nextPageUrl() }}"> Next > </a> @endif 
    </div>
</div>
@endif

{{--<div class="btm_more_sec">
<p>@if( isset($page_data) ){{ $page_data->description }}@endif</p>
</div>--}}

</section>
@endsection




@push('page_js')
<script src="{{ asset('public/front_end/js/lightbox.min.js') }}"></script>
<script>
lightbox.option({
  'resizeDuration': 400,
  'wrapAround': true,
  'maxHeight': 500
});
</script>
@endpush

    