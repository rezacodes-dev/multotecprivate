@extends('front_end.layout.layout_master')
@include('front_end.structure.page_meta')


@push('page_css')
<link rel="stylesheet" href="{{ asset('public/front_end/css/lightbox.min.css') }}">

<style type="text/css">
.fs-marker-outer {
  position: relative;
  width: 1000px;
  height: auto;
}
img.fsimg {
  width: 100%;
  height: auto;
}
img.fsmark:hover {
  cursor: pointer;
}
img.fsmark {
    border:none;
}
.lb-data .lb-close {
    display:block;
    position:absolute;
    right:50px;
    top:5px;
}
.lb-container {
    min-width: 520px;
    height: auto;
}
.lb-dataContainer {
    min-width: 520px;
    max-height: 500px;
    overflow-y: auto;
}
.lb-outerContainer {
    min-width: 520px;
    height: auto;
}
</style>
@endpush





@section('page_content')

@if( isset($allData) && !empty($allData) )

@php
    $banner = getFlowsheetPageBanner( $allData->id );
@endphp  
@if( isset($banner) && !empty($banner) )
<section class="innerpage-banner">
    <img src="{{ asset('public/uploads/files/media_images/'. $banner['image']) }}" alt="{{$banner['alt_tag']}}" 
    title="{{$banner['title']}}" caption="{{$banner['caption']}}">
</section>
@endif



<section class="container">
    <div class="breadcrumb"> <!-- Breadcrumb Segment -->
        <ul>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li>Industry Flowsheet</li>
            <li class="active">{{ $allData->name }}</li>
        </ul>
    </div>
</section>



<!--- FIRST BLOCK ---> 
<!--
    Here Title, Main Content, Buttons, Eform Fix
-->
<section class="container">
<div class="row">
    <div class="col-sm-8">
        <div class="midblock" id="firstBlock">

            <!-- Title -->
            <h1>@if( isset($allData->name) ){{ $allData->name }}@endif</h1>
            
            <!-- Main Page Content -->
            @if( isset($allData->page_content) )
            <p>{!! trim( html_entity_decode( $allData->page_content, ENT_QUOTES ) ) !!}</p>
            @endif

            <!-- Loop -->
            @if( isset($allData->pageBuilderContent) && !empty($allData->pageBuilderContent) && isset($device) )
                @foreach( $allData->pageBuilderContent as $pgd )
                    @if( $pgd->device == $device || $pgd->device == '3' ) <!-- Device Checking -->

                        <!-- Buttons-->
                        @if( $pgd->builder_type == 'BROCHURE_BUTT' && $pgd->position == 'BODY' )
                            <div class="buttom-row dwn-btn">
                                <a class="squre-btn" href="{{ route('front_fileSubCat', array('lng' => $lng,'cat' => $pgd->main_content, 'subcat' => $pgd->sub_content)) }}"> <i class="fa fa-angle-down" aria-hidden="true"></i> <span>Download Brochure</span></a>
                            </div>
                            <div class="buttom-row dwn-btn">
                                <a class="squre-btn" href="{{ route('viewTechResLst', array('lng' => $lng)) }}"> <i class="fa fa-angle-down" aria-hidden="true"></i> <span>Technical Resources</span></a>
                            </div>
                        @endif

                        @if( $pgd->builder_type == 'IMAGEGAL_BUTT' && $pgd->position == 'BODY' )
                            <div class="buttom-row dwn-btn">
                                <a class="squre-btn" href="{{ route('front_galSubCat', array('lng' => $lng,'cat' => $pgd->main_content, 'subcat' => $pgd->sub_content)) }}"> <i class="fa fa-angle-down" aria-hidden="true"></i> <span>View Gallery</span></a>
                            </div>
                        @endif
                        <!-- End Buttons -->

                    @endif <!-- End Device Checking -->
                @endforeach
            @endif
        </div>
    </div>
    <div class="col-sm-4">
        <div class="rightpanel">
            <!-- Loop -->
            @if( isset($allData->pageBuilderContent) && !empty($allData->pageBuilderContent) && isset($device) )
                @foreach( $allData->pageBuilderContent as $pgd )
                    @if( $pgd->device == $device || $pgd->device == '3' ) <!-- Device Checking -->

                        <!-- Eform -->
                        @if( $pgd->builder_type == 'EFORM' && $pgd->position == 'RIGHT' )
                            <div class="sidebar_block form" id="EFORM">
                                <h1>{{ $pgd->main_title }}<span>{{ $pgd->sub_title }}</span></h1>
                                {!! getHtmlFormBySCODE( $pgd->main_content ) !!}
                            </div>
                            <a href="javascript:void(0);" class="mob-frm-sbt" data-toggle="modal" data-target="#eform_modal">Submit & Enquiry</a>
                            <div class="modal fade" id="eform_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-body mobile_form">
                                   <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                   <h1>{{ $pgd->main_title }}<span>{{ $pgd->sub_title }}</span></h1>
                                   {!! getHtmlFormBySCODE( $pgd->main_content ) !!}
                                  </div>
                                </div>
                              </div>
                            </div>
                        @endif
                        <!-- End Eform -->
                    @endif
                @endforeach
            @endif
        </div>
    </div>
</div>
</section>
<!--- END FIRST BLOCK --->
<!--
-------- -------------- -------------- -------------- -------------- --------------- -------------
-->

<section class="container">
<div class="row">
    <div class="col-md-12">
        <!------- Flowsheet Image -------------->
        @if( isset($allData) && isset($allData->ImageIds) && count($allData->ImageIds) > 0 )
        <div class="fs-marker-outer1">
        <div class="fs-marker-outer">
          @if(isset($fsmarkers) && !empty($fsmarkers) && count($fsmarkers) > 0)
            @foreach($fsmarkers as $mrk)
            @php
                $fsimg = asset('public/images/multo_small_white.jpg');
                $imageObj = getImageById($mrk->image_id);
                if( !empty($imageObj) ) {
                    $fsimg = asset('public/uploads/files/media_images/'.$imageObj->image);
                }

              @endphp
                <a class="example-image-link" href="{{ $fsimg }}" data-lightbox="example-set" data-title="<span class='lightbox-head'>{{ $mrk->name }}</span><span class='lightbox-desc'>{{ html_entity_decode($mrk->page_content) }}</span>" style="border:none;">
                 
                
                @if($mrk->pin_image==1)
                
                <img src="{{ asset('public/pin.png') }}" style="position: absolute; width: 32px; height: 32px; z-index: 99; left: {{ $mrk->left_pos }}px; top: {{ $mrk->top_pos }}px;" class="fsmark" id="{{ $mrk->id }}" /> 
               
                @elseif($mrk->pin_image==2) 

                <img src="{{ asset('public/blue_pin.png') }}" style="position: absolute; width: 32px; height: 32px; z-index: 99; left: {{ $mrk->left_pos }}px; top: {{ $mrk->top_pos }}px;" class="fsmark" id="{{ $mrk->id }}" /> 
               
                @elseif($mrk->pin_image==3) 

<img src="{{ asset('public/yellow_pin.png') }}" style="position: absolute; width: 32px; height: 32px; z-index: 99; left: {{ $mrk->left_pos }}px; top: {{ $mrk->top_pos }}px;" class="fsmark" id="{{ $mrk->id }}" /> 

                @endif
            
            </a>
            @endforeach
          @endif
          
            @php $i = 0; @endphp
            @foreach( $allData->ImageIds as $imgs )
            @if( $imgs->image_type == 'FS_IMAGE' )
              @if( isset($imgs->imageInfo) && $i == 0 )
              <img src="{{ asset('public/uploads/files/media_images/'.$imgs->imageInfo->image) }}" class="fsimg">
              @php $i++; @endphp
              @endif
            @endif
            @endforeach
        </div>
        </div>       
        @endif
        <!------- End Flowsheet Image -------------->
    </div>
</div>
</section>

<!-- MID BLOCK --> <!-- PRODUCT BOX FULL CONTAINER -->
<section class="container">
@if( isset($allData->pageBuilderContent) && !empty($allData->pageBuilderContent) && isset($device) )
    @foreach( $allData->pageBuilderContent as $pgd )
        @if( $pgd->device == $device || $pgd->device == '3' ) <!-- Device Checking -->

            @if( $pgd->builder_type == 'PRODUCT_BOX' && $pgd->position == 'BODY' )
                <div class="midblock_subblock">
                    <h2>{{ $pgd->main_title }}</h2>
                    @if( isset($pgd->links) )
                     <div class="row">
                        @foreach($pgd->links as $lnk)
                            @php
                                $linkData = linkSlugToContent( $lnk->slug );
                                $proImgArr = getProductImage($linkData->id);
                            @endphp
                            @if( !empty( $linkData ) )
                            <div class="col-sm-3 ar-pbox">
                                <a href="{{ url( $lng.'/'.$lnk->slug ) }}"><h4>{{ $linkData->name }}</h4></a>
                                @if( !empty($proImgArr) )
                                <div class="imagecontsiner"><img src="{{ asset('public/uploads/files/media_images/'. $proImgArr->image) }}" alt="{{ $proImgArr->alt_tag }}" title="{{ $proImgArr->title }}" caption="{{ $proImgArr->caption }}" style="height: 166px; width: 100%;">
                                </div>
                                @endif
                                <p class="ar-pbox-p">{{ str_limit( $linkData->description, 140 ) }}</p>
                                <div class="text-center ar-rmdiv"><a href="{{ url( $lng.'/'.$lnk->slug ) }}" class="btn1 btn2-default">Read More</a></div>
                            </div>
                            @endif
                        @endforeach
                        {!! genPBOXreusContent($pgd->link_text) !!}
                     </div>
                    @endif
                </div>
            @endif


            @if( $pgd->builder_type == 'PRODUCT_CAT_BOX' && $pgd->position == 'BODY' )
                <div class="midblock_subblock">
                    <h2>{{ $pgd->main_title }}</h2>
                    @if( isset($pgd->links) )
                     <div class="row">
                        @foreach($pgd->links as $lnk)
                            @php
                                $linkData = linkSlugToContent( $lnk->slug );
                                $proImgArr = getProductCatImage($linkData->id);
                            @endphp
                            @if( !empty( $linkData ) )
                            <div class="col-sm-3 ar-pbox">
                                <a href="{{ url( $lng.'/'.$lnk->slug ) }}"><h4>{{ $linkData->name }}</h4></a>
                                @if( !empty($proImgArr) )
                                <div class="imagecontsiner"><img src="{{ asset('public/uploads/files/media_images/'. $proImgArr->image) }}" alt="{{ $proImgArr->alt_tag }}" title="{{ $proImgArr->title }}" caption="{{ $proImgArr->caption }}" style="height: 166px; width: 100%;">
                                </div>
                                @endif
                                <p class="ar-pbox-p">{{ str_limit( $linkData->description, 140 ) }}</p>
                                <div class="text-center ar-rmdiv">
                                    <a href="{{ url( $lng.'/'.$lnk->slug ) }}" class="btn1 btn2-default">Read More</a>
                                </div>
                            </div>
                            @endif
                        @endforeach
                        {!! genPBOXreusContent($pgd->link_text) !!}
                     </div>
                    @endif
                </div>
            @endif

        @endif <!-- End Device Checking -->
    @endforeach
@endif
</section>
<!-- End Mid Block --> <!-- END PRODUCT BOX FULL CONTAINER -->



<!-- CTA BLOCK --> <!-- CTA FULL PAGE -->
@if( isset($allData->pageBuilderContent) && !empty($allData->pageBuilderContent) && isset($device) )
    @foreach( $allData->pageBuilderContent as $pgd )
        @if( $pgd->device == $device || $pgd->device == '3' ) <!-- Device Checking -->
            @if( $pgd->builder_type == 'CTA' && $pgd->position == 'BODY' )
                <section class="green_strip">
                    <div class="container">
                        <div class="text-center">
                            <h6>{{ $pgd->main_title }}<a href="{{$pgd->link_url}}">{{ $pgd->link_text }}</a></h6>
                        </div>
                    </div>
                </section>
            @endif
        @endif
    @endforeach
@endif
<!-- END CTA BLOCK--> <!-- END CTA FULL PAGE -->


<!-- LOOP BLOK -->
<section class="container">
    <div class="row">
        <div class="col-sm-8">
            <div class="midblock">
                @if( isset($allData->pageBuilderContent) && !empty($allData->pageBuilderContent) && isset($device) )
                    @foreach( $allData->pageBuilderContent as $pgd )
                        @if( $pgd->device == $device || $pgd->device == '3' ) <!-- Device Checking -->

                            <!-- Extra SEO -->
                            @if( $pgd->builder_type == 'EXTRA_SEO' && $pgd->position == 'BODY' )
                                <div class="pgb-extra-seo">
                                    <p>{!! trim( html_entity_decode( $pgd->main_content, ENT_QUOTES ) ) !!}</p>
                                </div>
                                <div class="clearfix"></div> 
                            @endif


                            <!-- Image Carousel -->
                            @if( $pgd->builder_type == 'IMAGE_CAROUSEL' && $pgd->position == 'BODY' )
                            <div class="slider_block pgb-image-slider">
                                <div class="owl-carousel1">
                                    @if( isset($pgd->images) && !empty($pgd->images) && count($pgd->images) != 0 )
                                        @foreach( $pgd->images as $caraImgs )
                                            @if( isset($caraImgs->masterImageInfo) && !empty($caraImgs->masterImageInfo) )
                                                <div class="item">
                                                    <div class="innerslide">
                                                        <img src="{{ asset('public/uploads/files/media_images/'. $caraImgs->masterImageInfo->image) }}"  alt="{{ $caraImgs->img_alt }}" title="{{ $caraImgs->img_title }}" caption="{{ $caraImgs->img_caption }}"/>   
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif          
                                </div>
                            </div> 
                            <div class="clearfix"></div> 
                            @endif


                            <!-- Video Gallery -->
                            @if( $pgd->builder_type == 'VIDEO_GALLERY' && $pgd->position == 'BODY' )
                            <div class="slider_block pgb-video-slider">
                                <div class="owl-carousel2">
                                    @if( isset($pgd->videos) && !empty($pgd->videos) && count($pgd->videos) != 0 )
                                        @foreach( $pgd->videos as $vidGal )

                                            @if( isset($vidGal->masterVideoInfo) && !empty($vidGal->masterVideoInfo) )
                                                <div class="item">
                                                    <div class="innerslide">
                                                        <div class="video_block" style="width: 75%;">
                                                            <iframe width="560" height="315" src="https://www.youtube.com/embed/{{ $vidGal->masterVideoInfo->video_link }}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>   
                                                        </div>
                                                        <div class="caption" style="height: 315px; width: 25%;">
                                                            <p>{{ $vidGal->caption }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif          
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            @endif

                            <!-- Extra Content -->
                            @if( $pgd->builder_type == 'EXTRA_CONT' && $pgd->position == 'BODY' )
                                <div class="pgb-extra-content">
                                    <p>{!! trim( html_entity_decode( $pgd->main_content, ENT_QUOTES ) ) !!}</p>
                                </div>
                                <div class="clearfix"></div> 
                            @endif


                            <!-- Container Width Hero Statement -->
                            @if( $pgd->builder_type == 'HERO_SCW' && $pgd->position == 'BODY' )
                                <div class="pgb-hero-scw">
                                    <h6 class="midbody_subheading">{{ $pgd->main_content }}</h6>
                                </div>
                            @endif

                            <!-- Quick Body LINKS -->
                            @if( ($pgd->builder_type == 'PRODUCT_LINKS' || $pgd->builder_type == 'DISTRIBUTOR' || $pgd->builder_type == 'DISTRIBUTOR_PAGE' || $pgd->builder_type == 'PRODUCT_CAT_LINKS' || $pgd->builder_type == 'PEOPLE_LINKS' || $pgd->builder_type == 'NEWS_LINKS' || $pgd->builder_type == 'CUSTOM_LINKS' || strpos($pgd->builder_type, 'CONTENT_LINKS') !== false) && $pgd->position == 'BODY' )

                                <div class="midbody_newsblock pgb-links">
                                    <h3>{{ $pgd->main_title }}</h3>
                                    @if( isset($pgd->links) )
                                        <div class="news_list">
                                        <ul class="greendot">
                                            @foreach($pgd->links as $lnk)
                                                @php
                                                    $linkData = linkSlugToContent( $lnk->slug );
                                                @endphp
                                                @if( !empty( $linkData ) )
                                                <li>
                                                    <a href="{{ url( $lng.'/'.$lnk->slug ) }}">{{ $linkData->name }}</a>
                                                </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                        </div>
                                    @endif
                                </div>
                                <div class="clearfix"></div> 
                            @endif

                            <!-- Accordion -->
                            @if( $pgd->builder_type == 'ACCORDION' && $pgd->position == 'BODY' )
                            <div class="midblock_subblock countries pgb-accr">
                                @if( isset($pgd->accordion) )
                                    @foreach($pgd->accordion as $accr)
                                    <div class="outeraccor">
                                        <div class="accor_heading open_arrow">{{ $accr->heading }}</div>
                                        <div class="accor_body">
                                            <div class="row"><div class="col-md-12"><div class="info">
                                             {!! html_entity_decode( $accr->content ) !!}
                                            </div></div></div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="clearfix"></div> 
                            @endif

                        @endif
                    @endforeach
                @endif
            </div>
        </div>
        <div class="col-sm-4">
            <div class="rightpanel">
                @if( isset($allData->pageBuilderContent) && !empty($allData->pageBuilderContent) && isset($device) )
                    @foreach( $allData->pageBuilderContent as $pgd )
                        @if( $pgd->device == $device || $pgd->device == '3' ) <!-- Device Checking -->

                            <!-- Quick Body LINKS -->
                            @if( ($pgd->builder_type == 'PRODUCT_LINKS' || $pgd->builder_type == 'DISTRIBUTOR' || $pgd->builder_type == 'DISTRIBUTOR_PAGE' || $pgd->builder_type == 'PRODUCT_CAT_LINKS' || $pgd->builder_type == 'PEOPLE_LINKS' || $pgd->builder_type == 'NEWS_LINKS' || $pgd->builder_type == 'CUSTOM_LINKS' || strpos($pgd->builder_type, 'CONTENT_LINKS') !== false) && $pgd->position == 'RIGHT' )

                                <div class="midbody_newsblock pgb-links-right">
                                    <h3>{{ $pgd->main_title }}</h3>
                                    @if( isset($pgd->links) )
                                        <div class="news_list">
                                        <ul class="arrow-list">
                                            @foreach($pgd->links as $lnk)
                                                @php
                                                    $linkData = linkSlugToContent( $lnk->slug );
                                                @endphp
                                                @if( !empty( $linkData ) )
                                                <li>
                                                    <a href="{{ url( $lng.'/'.$lnk->slug ) }}">{{ $linkData->name }}</a>
                                                </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                        </div>
                                    @endif
                                </div>
                                <div class="clearfix"></div> 
                            @endif


                            <!-- STICKY BUTTON -->
                            @if( $pgd->builder_type == 'STICKY_BUTT' && $pgd->position == 'RIGHT' )
                                <div class="quote_block" id="sidebar">
                                    <h2>{{ $pgd->main_title }}<span>{{ $pgd->sub_title }}</span></h2>
                                    <div class="buttom-row">
                                        <a href="javascript:void(0);" class="submit-btn scroll-btn">{{ $pgd->link_text }}</a>
                                    </div>
                                </div>
                                <div class="clearfix"></div> 
                            @endif


                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</section>
<!-- END LOOP BLOCK -->

<!-- LAST BLOCK -->
@if( isset($allData->pageBuilderContent) && !empty($allData->pageBuilderContent) && isset($device) )
    @foreach( $allData->pageBuilderContent as $pgd )

    @if( $pgd->device == $device || $pgd->device == '3' ) <!-- Device Checking -->

        <!-- METRIC -->
        @if( $pgd->builder_type == 'METRIC' && $pgd->position == 'BODY' )
            <section class="container">
            <div class="row">
                <div class="col-sm-8"> 
                @if( $pgd->sub_content == 'METRIC_LEFT' )
                <div class="strip_1">
                    <div class="bg_green" style="background-color: {{ $pgd->link_text }}; color: {{ $pgd->link_url }};">
                         <div class="inner-dv">
                            <span class="number">{{ $pgd->main_title }}</span> <span class="text">{{ $pgd->sub_title }}</span>
                        </div>
                    </div>
                    <p>{{ $pgd->main_content }}</p>
                </div>
                @endif
                @if( $pgd->sub_content == 'METRIC_RIGHT' )
                <div class="strip_2">
                    <p>{{ $pgd->main_content }}</p>
                    <div class="bg_blue" style="background-color: {{ $pgd->link_text }}; color: {{ $pgd->link_url }};">
                        <div class="inner-dv">
                            <span class="number">{{ $pgd->main_title }}</span> <span class="text">{{ $pgd->sub_title }}</span>
                        </div>
                    </div>
                    
                </div>
                @endif
                </div>
            </div>
            <div class="clearfix"></div> 
            </section>
        @endif

        <!-- PAGE WIDTH HERO STATEMENT -->
        @if( $pgd->builder_type == 'HERO_SPW' && $pgd->position == 'BODY' )
            <p>&nbsp;</p>
            <div class="padtop"><div class="container">{{ $pgd->main_content }}</div></div>
            <div class="clearfix"></div> 
        @endif
    
    @endif <!-- End Device Checking -->

    @endforeach
@endif
<!-- END LAST BLOCK -->

<!-- Reusable -->
@if( isset($allData->pageBuilderContent) && !empty($allData->pageBuilderContent) && isset($device) )
    @foreach( $allData->pageBuilderContent as $pgd )
        @if( $pgd->device == $device || $pgd->device == '3' ) <!-- Device Checking -->

            <!-- Eform -->
            @if( $pgd->builder_type == 'REUSE' && $pgd->position == 'BODY' )
                {!! getHtmlReuseBySCODE( $pgd->main_content ) !!}
            @endif
            <!-- End Eform -->
        @endif
    @endforeach
@endif
<div class="clearfix"></div> 
<!-- End Reusable -->

@endif
@endsection




@push('page_js')
<script type="text/javascript" src="{{ asset('public/front_end/js/ddaccordion.js') }}"></script>
<script src="{{ asset('public/front_end/js/lightbox.min.js') }}"></script>
<script type="text/javascript">
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
  togglehtml: ["prefix", "<img src='{{ asset('public/front_end/images/arrow_down_accor.png') }}' style='width:24px; height:24px' /> ", "<img src='{{ asset('public/front_end/images/arrow_up_accor.png') }}' style='width:24px; height:24px' /> "],  //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
  animatespeed: "normal", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
  oninit:function(expandedindices){ //custom code to run when headers have initalized
    //do nothing
  },
  onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
    //do nothing
  }
});
</script>
<script>
lightbox.option({
  'resizeDuration': 400,
  'wrapAround': true,
  'maxHeight': 500
});
</script>
<script>
  $(document).ready(function () {
    // Fetch the country data via AJAX
    $.ajax({
        url: "{{route('getCountries')}}", // The Laravel route to fetch countries
        method: 'GET',
        success: function (response) {
            console.log("Response:", response);
            // Populate the country dropdown
            let countryDropdown = $("select[name='country_bdf6b0663c3d12b26de9d64a0331d39f']");
            countryDropdown.empty(); // Clear existing options
            countryDropdown.append('<option value="">Select Country</option>'); // Add default option

            $.each(response, function (index, country) {
                countryDropdown.append(
                    `<option value="${country.country_name}" data-dialing-code="${country.dialing_code}">${country.country_name}</option>`
                );
            });
                  // If a country is already selected, update the phone number field with the dialing code
        let selectedDialingCode = countryDropdown.val();
            if (selectedDialingCode) {
                $("input[name='contactno_07351a4ae50ef96a1c50a5cc650473f3']").val(selectedDialingCode);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error fetching countries:', error);
        }
    });

    // Update the phone number field with the selected dialing code
    $("select[name='country_bdf6b0663c3d12b26de9d64a0331d39f']").on('change', function () {
        let selectedDialingCode = $(this).find(':selected').data('dialing-code') || '';
        $("input[name='contactno_07351a4ae50ef96a1c50a5cc650473f3']").val(selectedDialingCode);
    });
});



// $(document).ready(function () {
//     // Automatically select the first option in the dropdown and toggle fields
//     $("select[name='iwanttoenquireabout_6ff314aae2564ef958f6739b4b07e185']").each(function () {
//         // $(this).attr('size', '4'); 
//         $(this).val(''); // Set the default option
//     });

//     // Trigger the visibility logic based on the default selection
//     toggleFieldsBasedOnSelection();

//     // Handle change event for the dropdown
//     $(document).on('change', "select[name='iwanttoenquireabout_6ff314aae2564ef958f6739b4b07e185']", function () {
//         toggleFieldsBasedOnSelection();
//     });

//     // Function to toggle the visibility of fields based on the selected option
//     function toggleFieldsBasedOnSelection() {
//         $("select[name='iwanttoenquireabout_6ff314aae2564ef958f6739b4b07e185']").each(function () {
//             var selectedOption = $(this).val();
//             var container = $(this).closest('.form, .modal'); // Adjust for context (main div or modal)

//             // Hide all fields initially within the specific container
//             container.find('#field_16').hide();
//             container.find('#field_23').hide();
//             container.find('#field_3').hide();
//             container.find('#field_4').hide();
//             container.find('#field_17').hide();
//             container.find('#field_5').hide();
//             container.find('#field_19').hide();
//             container.find('#field_24').hide();
//             container.find('#terms_f1b78704ea2449a379eaaf6c129751cb').hide();
//             container.find('#box2').next('div').hide();
//             container.find('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').css('display', 'none');
//             container.find('.custom-file-upload').css('display', 'none');

//             // Logic for specific selections
//             if (selectedOption === '1.-Mineral-processing-products-and-services') {
//                 container.find('#field_16').show();
//                 container.find('#field_23').show();
//                 container.find('#field_3').show();
//                 container.find('#field_4').show();
//                 container.find('#field_17').show();
//                 container.find('#field_5').show();
//                 container.find('#field_24').show();
//                 container.find('.custom-file-upload').css('display', 'block');
//                 container.find('#field_19').show();
//                 container.find('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').val('Request a quote');
//                 container.find('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').css('display', 'block');
//                 container.find('#box1').hide();
//                 container.find('#box2').hide();
//                 container.find('#box2').next('div').show();
//             } else if (selectedOption === '4.-Other') {
//                 container.find('#field_16').show();
//                 container.find('#field_23').show();
//                 container.find('#field_3').show();
//                 container.find('#field_4').show();
//                 container.find('#field_17').show();
//                 container.find('#field_5').show();
//                 container.find('#field_24').show();
//                 container.find('.custom-file-upload').css('display', 'block');
//                 container.find('#field_19').show();
//                 container.find('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').val('Submit Enquiry');
//                 container.find('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').css('display', 'block');
//                 container.find('#box1').hide();
//                 container.find('#box2').hide();
//                 container.find('#box2').next('div').show();
//             } else if (selectedOption === '2.-Job-applications') {
//                 container.find('#field_19').show();
//                 container.find('#box1').show();
//                 container.find('#box2').hide();
//                 container.find('#box2').next('div').hide();
//             } else if (selectedOption === '3.-Training-Opportunities') {
//                 container.find('#field_19').show();
//                 container.find('#box1').hide();
//                 container.find('#box2').show();
//                 container.find('#box2').next('div').hide();
//                 window.open("https://www.multotec.com/en/training", "_blank");
//             } else {
//                 container.find('#field_19').hide();
//                 container.find('#box2').next('div').hide();
//             }
//         });
//     }
// });

$(document).ready(function () {
    $("select[name='iwanttoenquireabout_6ff314aae2564ef958f6739b4b07e185'] option[value='']").attr("disabled", "disabled");

    // Hide all fields by default on page load
    $("select[name='iwanttoenquireabout_6ff314aae2564ef958f6739b4b07e185']").each(function () {
        var container = $(this).closest('.form, .modal'); // Adjust for context (main div or modal)
        
        // Hide all fields initially within the specific container
        // container.find('#field_16').hide();
        // container.find('#field_23').hide();
        // container.find('#field_3').hide();
        // container.find('#field_4').hide();
        // container.find('#field_17').hide();
        // container.find('#field_5').hide();
        // container.find('#field_19').hide();
        // container.find('#field_24').hide();
        // container.find('#terms_f1b78704ea2449a379eaaf6c129751cb').hide();
        // container.find('#box2').next('div').hide();
        // container.find('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').css('display', 'none');
        // container.find('.custom-file-upload').css('display', 'none');
    });

    // Handle change events for the select element
    $(document).on('change', "select[name='iwanttoenquireabout_6ff314aae2564ef958f6739b4b07e185']", function () {
        // Remove the option with an empty value
        // $("select[name='iwanttoenquireabout_6ff314aae2564ef958f6739b4b07e185'] option[value='']").remove();
        toggleFieldsBasedOnSelection();
    });

    // Call toggle function to handle default selection
    toggleFieldsBasedOnSelection();

    // Function to toggle the visibility of fields based on the selected option
    function toggleFieldsBasedOnSelection() {
        $("select[name='iwanttoenquireabout_6ff314aae2564ef958f6739b4b07e185']").each(function () {
            var selectedOption = $(this).val();
            var container = $(this).closest('.form, .modal'); // Adjust for context (main div or modal)
            

            // Hide all fields initially within the specific container
            // container.find('#field_16').hide();
            // container.find('#field_23').hide();
            // container.find('#field_3').hide();
            // container.find('#field_4').hide();
            // container.find('#field_17').hide();
            // container.find('#field_5').hide();
            // container.find('#field_19').hide();
            // container.find('#field_24').hide();
            // container.find('#terms_f1b78704ea2449a379eaaf6c129751cb').hide();
            // container.find('#box2').next('div').hide();
            // container.find('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').css('display', 'none');
            // container.find('.custom-file-upload').css('display', 'none');

            // Logic for specific selections
            if (selectedOption === '1.-Mineral-processing-products-and-services') {
                container.find('#field_16').show();
                container.find('#field_23').show();
                container.find('#field_3').show();
                container.find('#field_4').show();
                container.find('#field_17').show();
                container.find('#field_5').show();
                container.find('#field_24').show();
                container.find('.custom-file-upload').css('display', 'block');
                container.find('#field_19').show();
                container.find('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').val('Request a quote');
                container.find('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').css('display', 'block');
                container.find('#box1').hide();
                container.find('#box2').hide();
                container.find('#box2').next('div').show();
                $('.g-recaptcha').css('display','block');
            } 
            else if (selectedOption === '4.-Other') {
                container.find('#field_16').show();
                container.find('#field_23').show();
                container.find('#field_3').show();
                container.find('#field_4').show();
                container.find('#field_17').show();
                container.find('#field_5').show();
                container.find('#field_24').show();
                container.find('.custom-file-upload').css('display', 'block');
                container.find('#field_19').show();
                container.find('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').val('Submit Enquiry');
                container.find('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').css('display', 'block');
                container.find('#box1').hide();
                container.find('#box2').hide();
                container.find('#box2').next('div').show();
                $('.g-recaptcha').css('display','block');
            } else if (selectedOption === '2.-Job-applications') {
              //  container.find('#field_19').show();
              container.find('#field_16').hide();
            container.find('#field_23').hide();
            container.find('#field_3').hide();
            container.find('#field_4').hide();
            container.find('#field_17').hide();
            container.find('#field_5').hide();
            container.find('#field_19').hide();
            container.find('#field_24').hide();
            container.find('#terms_f1b78704ea2449a379eaaf6c129751cb').hide();
            container.find('#box2').next('div').hide();
            container.find('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').css('display', 'none');
            container.find('.custom-file-upload').css('display', 'none');
            $('.g-recaptcha').hide()
                container.find('#box2').next('div').hide();
                window.open("https://www.careers24.com/now-hiring/9744-multotec-pty-ltd/", "_blank");
            } else if (selectedOption === '3.-Training-Opportunities') {
                  container.find('#field_16').hide();
            container.find('#field_23').hide();
            container.find('#field_3').hide();
            container.find('#field_4').hide();
            container.find('#field_17').hide();
            container.find('#field_5').hide();
            container.find('#field_19').hide();
            container.find('#field_24').hide();
            container.find('#terms_f1b78704ea2449a379eaaf6c129751cb').hide();
            container.find('#box2').next('div').hide();
            container.find('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').css('display', 'none');
            container.find('.custom-file-upload').css('display', 'none');
            $('.g-recaptcha').hide()
                container.find('#box2').next('div').hide();
                window.open("https://www.multotec.com/en/training", "_blank");
            } else {
                container.find('#field_19').hide();
                container.find('#box2').next('div').hide();
            }
        });
    }
});



</script>
<!-- <script>
$('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').on('click', function(event) {
    const selectElement = $('select[name="iwanttoenquireabout_6ff314aae2564ef958f6739b4b07e185"]');
    const selectedOption = selectElement.find('option:selected');
    console.log(selectElement.val());
    // Validation
    if (!selectElement.val() || selectElement.val() === "") {
        event.preventDefault(); // Prevent the form from submitting
        alert('Please select a Service Option.');
    } else {
        // The form is valid
        console.log('Form is valid. Submitting...');
        // Allow form submission
    }
});




</script> -->
@endpush

    