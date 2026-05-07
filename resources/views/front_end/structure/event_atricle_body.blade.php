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