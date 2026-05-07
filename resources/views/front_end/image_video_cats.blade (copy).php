@extends('front_end.layout.layout_master')



@push('page_meta')

    @if( isset($extraContent) && !empty($extraContent) )
        
        @php
            $robot_txt = '';
            if( $extraContent->follow == '1' ) {
                $robot_txt .= 'follow, ';
            }
            if( $extraContent->index_tag == '1' ) {
                $robot_txt .= 'index, ';
            }
            $robot_txt = rtrim($robot_txt , ', ');
        @endphp
        
        <title>{{ $extraContent->meta_title }}</title>
        <meta name="description" content="{{ $extraContent->meta_desc }}">
        <meta name="keywords" content="{{ $extraContent->meta_keyword }}">
        <meta name="robots" content="{{ $robot_txt }}">
        
        @if( $extraContent->canonical_url != '' )
        <link rel="canonical" href="{{ $extraContent->canonical_url }}" />
        @endif

    @endif

@endpush



@push('page_css')
<link href="{{ asset('public/front_end/css/jquery.tabs.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('page_content')
<section class="innerpage-banner" style="margin-top: 30px;">
    <img src="{{ asset('public/front_end/images/innerpage_banner6.jpg') }}" alt="Multotec-image-video-gallery">
</section>
<section class="container">

    <div class="breadcrumb" style="margin-top: 20px;"> <!-- Breadcrumb Segment -->
        <ul>
            <li><a href="{{ url('/') }}">Home</a></li>
            @if(isset($prods))
                @foreach($prods as $pro)
                <li><a href="@if( $pro->slug != '' ){{ url( $pro->slug ) }}@endif">{{ $pro->name }}</a></li>
                @endforeach
            @endif
            @if(isset($prodCats))
                @foreach($prodCats as $proC)
                <li><a href="@if( $proC->slug != '' ){{ url( $proC->slug ) }}@endif">{{ $proC->name }}</a></li>
                @endforeach
            @endif
        </ul>
    </div>

</section>


<section class="container">
    <div class="midblock">
        <div class="tabblock">
            <div class="jq-tab-wrapper" id="horizontalTab">
                <div class="jq-tab-menu">
                    <div class="jq-tab-title active" data-tab="1">Photo Gallery</div>
                    <div class="jq-tab-title" data-tab="2">Video Gallery</div>
                </div>
                <div class="jq-tab-content-wrapper">
                    <div class="jq-tab-content active" data-tab="1">
                    <div class="tab_info_content">
                        <p>@if(isset($extraContent)){!! html_entity_decode($extraContent->photo_gallery_content, ENT_QUOTES) !!}@endif</p>
                    </div>
                        <div class="tab_info_list">
                            <div class="row">
                                @if( isset($imgCats) )
                                    @foreach( $imgCats as $ic )
                                    <div class="col-sm-3">
                                        <ul>
                                            <li>
                                                <a href="{{ route('front_galSubCat', array('cat' => $ic->slug)) }}">{{ $ic->name }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>    
                    <div class="jq-tab-content" data-tab="2">
                        <div class="tab_info_content">
                            <p>@if(isset($extraContent)){!! html_entity_decode($extraContent->video_gallery_content, ENT_QUOTES) !!}@endif</p>
                        </div>
                        <div class="tab_info_list">
                            <div class="row">
                                @if( isset($vidCats) )
                                    @foreach( $vidCats as $vc )
                                    <div class="col-sm-3">
                                        <ul>
                                            <li>
                                                <a href="{{ route('front_galVSubCat', array('cat' => $vc->slug)) }}">{{ $vc->name }}</a>
                                            </li>
                                        </ul>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="row">
        <div class="col-sm-4">
            <nav aria-label="Page navigation">
              <ul class="pagination">
                <li class="page-item"><a class="page-link" href="#">Prev</a></li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">4</a></li>
                <li class="page-item"><a class="page-link" href="#">5</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
              </ul>
            </nav>
        </div>
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
        <div class="search">
            <input type="text" class="form-control" placeholder="Search">
            <button type="submit" value=""><i class="fa fa-search" aria-hidden="true"></i></button>
        </div>
    </div>
    </div>

    </div>
</section>

@endsection






@push('page_js')
<script src="{{ asset('public/front_end/js/jquery.tabs.min.js') }}"></script>
<script type="text/javascript">
$(function () {
   // $('#verticalTab').jqTabs();
    $('#horizontalTab').jqTabs({direction: 'horizontal', duration: 200});
});
</script>
@endpush

    