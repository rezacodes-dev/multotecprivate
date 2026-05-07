<!--- FIRST BLOCK ---> 
<!--
    Here Title, Main Content, Buttons, Eform Fix
-->
<section class="container">
<!-- Title -->
<h1>@if( isset($allData->name) ){{ $allData->name }}@endif</h1>
<div class="row">
    <div class="col-sm-8">
        <div class="midblock" id="firstBlock">
            
            <!-- Main Page Content -->
            @if( isset($allData->page_content) )
            {!! trim( html_entity_decode( $allData->page_content, ENT_QUOTES ) ) !!}
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
                    
                    <!-- Eform -->
                    @if( $pgd->builder_type == 'EFORM' && $pgd->position == 'RIGHT' )
                        
                        @if( $device == '1' ) <!-- Device Checking -->
                        <div class="sidebar_block form" id="EFORM">
                            <h2 class="sph2">{{ $pgd->main_title }}<span>{{ $pgd->sub_title }}</span></h2>
                            {!! getHtmlFormBySCODE( $pgd->main_content ) !!}
                        </div>
                        <!-- For Desktop Popup -->
                        <div class="modal fade" id="desktop_eform_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
                          <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content modal-bacg">
                              <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 greenbg">
                                        <div class="fbg">
                                           @php
                                           $frmData = getReusableByKey('modal_form_content');
                                           @endphp
                                           @if(isset($frmData) && !empty($frmData))
                                           <h3>{{ $frmData->title }}</h3>
                                           @endif
                                           <div class="frm-data-cbox">
                                               @if(isset($frmData) && !empty($frmData))
                                               {!! html_entity_decode($frmData->content) !!}
                                               @endif
                                           </div> 
                                        </div>
                                    </div>
                                    <div class="col-md-6 dsk-modal-frm">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <div class="dsk-modal-frmright">
                                            <h2 class="sph2">{{ $pgd->main_title }}<span>{{ $pgd->sub_title }}</span></h2>
                                            {!! getHtmlFormBySCODE( $pgd->main_content ) !!}
                                        </div>
                                    </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        @endif

                        @if( $device == '2' ) <!-- Device Checking --> <!-- for Mobile -->
                        <a href="javascript:void(0);" class="mob-frm-sbt" data-toggle="modal" data-target="#eform_modal">Submit an Enquiry</a>
                        <div class="modal fade" id="eform_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-body mobile_form">
                               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                               <h2 class="sph2">{{ $pgd->main_title }}<span>{{ $pgd->sub_title }}</span></h2>
                               {!! getHtmlFormBySCODE( $pgd->main_content ) !!}
                              </div>
                            </div>
                          </div>
                        </div>
                        @endif

                    @endif
                    <!-- End Eform -->
                    
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
                            @endphp
                            @if( !empty( $linkData ) )
                            @php $proImgArr = getProductImage($linkData->id); @endphp
                            <div class="col-sm-3 ar-pbox">
                                <a href="{{ url( $lng.'/'.$lnk->slug ) }}"><h4>{{ $linkData->name }}</h4></a>
                                @if( !empty($proImgArr) )
                                <div class="imagecontsiner"><img src="{{ asset('public/uploads/files/media_images/'. $proImgArr->image) }}" alt="{{ $proImgArr->alt_tag }}" title="{{ $proImgArr->title }}" caption="{{ $proImgArr->caption }}" style="height: 166px;">
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
                            @endphp
                            @if( !empty( $linkData ) )
                            @php $proImgArr = getProductCatImage($linkData->id); @endphp
                            <div class="col-sm-3 ar-pbox">
                                <a href="{{ url( $lng.'/'.$lnk->slug ) }}"><h4>{{ $linkData->name }}</h4></a>
                                @if( !empty($proImgArr) )
                                <div class="imagecontsiner"><img src="{{ asset('public/uploads/files/media_images/'. $proImgArr->image) }}" alt="{{ $proImgArr->alt_tag }}" title="{{ $proImgArr->title }}" caption="{{ $proImgArr->caption }}" style="height: 166px;">
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
<section class="container loopblock">
    <div class="row">
        <div class="col-sm-8">
            <div class="midblock">
                @if( isset($allData->pageBuilderContent) && !empty($allData->pageBuilderContent) && isset($device) )
                    @foreach( $allData->pageBuilderContent as $pgd )
                        @if( $pgd->device == $device || $pgd->device == '3' ) <!-- Device Checking -->

                            <!-- Extra SEO -->
                            @if( $pgd->builder_type == 'EXTRA_SEO' && $pgd->position == 'BODY' )
                                <div class="pgb-extra-seo">
                                {!! trim( html_entity_decode( $pgd->main_content, ENT_QUOTES ) ) !!}
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
                                {!! trim( html_entity_decode( $pgd->main_content, ENT_QUOTES ) ) !!}
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

                        @endif

                        @if( $device == '1' ) <!-- Device Checking -->
                            <!-- STICKY BUTTON -->
                            @if( $pgd->builder_type == 'STICKY_BUTT' && $pgd->position == 'RIGHT' )
                                <div class="quote_block" id="sidebar"> 
                                    <h2>{{ $pgd->main_title }}<span>{{ $pgd->sub_title }}</span></h2>
                                    <div class="buttom-row">
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#desktop_eform_modal" class="submit-btn">{{ $pgd->link_text }}</a> <!-- add class "scroll-btn" -->
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