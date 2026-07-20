<!--- FIRST BLOCK --->
<!--
    Here Title, Main Content, Buttons, Eform Fix
-->

<section class="container">
    <!-- Title -->
    <h1>
        @if (isset($allData->name))
            {{ $allData->name }}
        @endif
    </h1>
    <div class="row">
        <div class="col-sm-8">
            <div class="midblock" id="firstBlock">

                <!-- Main Page Content -->
                @if (isset($allData->page_content))
                    {!! trim(html_entity_decode($allData->page_content, ENT_QUOTES)) !!}
                @endif

                @if (isset($allData->product_link) && !empty($allData->product_link))
                    <div class="buttom-row dwn-btn">

                        <a class="squre-btn" href="{{ $allData->product_link }}" target="_blank"> <i
                                class="fa fa-angle-down" aria-hidden="true"></i> <span>Download Brochure</span></a>
                    </div>
                @else
                @endif

                <!-- Loop -->
                @if (isset($allData->pageBuilderContent) && !empty($allData->pageBuilderContent) && isset($device))

                    @foreach ($allData->pageBuilderContent as $pgd)
                        @if ($pgd->device == $device || $pgd->device == '3')
                            <!-- Device Checking -->

                            <!-- Buttons-->
                            @if ($pgd->builder_type == 'BROCHURE_BUTT' && $pgd->position == 'BODY')
                                <div class="buttom-row dwn-btn" style="display:none">
                                    {{-- <a class="squre-btn" href="{{ route('viewTechResLst', ['lng' => $lng]) }}"> <i
                                            class="fa fa-angle-down" aria-hidden="true"></i> <span>Technical
                                            Resources</span></a> --}}
                                </div>
                            @endif

                            @if ($pgd->builder_type == 'IMAGEGAL_BUTT' && $pgd->position == 'BODY')
                                <div class="buttom-row dwn-btn">
                                    <a class="squre-btn"
                                        href="{{ route('front_galSubCat', ['lng' => $lng, 'category' => $pgd->main_content, 'subcategory' => $pgd->sub_content]) }}">
                                        <i class="fa fa-angle-down" aria-hidden="true"></i> <span>View
                                            Gallery</span></a>
                                </div>
                            @endif
                            <!-- End Buttons -->
                        @endif <!-- End Device Checking -->
                    @endforeach
                @endif
            </div>
            @if( isset($allDisConts) && !empty($allDisConts))
            @foreach ($allDisConts as $dc)
                 <div class="midblock_subblock countries pgb-accr">
                <div class="outeraccor">
                    <div class="accor_heading closed_arrow" headerindex="0h"><span class="accordprefix"><img
                                src="https://www.multotec.com/public/front_end/images/arrow_down_accor.png"
                                style="width:24px; height:24px"> </span>{{ ucwords(trim(str_ireplace('multotec', '', $dc->name ?? ''))) }}<span
                            class="accordsuffix"></span></div>
                    <div class="accor_body" contentindex="0c" style="display: none;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="info">
                                    <table border="0" cellpadding="5" cellspacing="5">
                                        <tbody>
                                            <tr>
                                                <td>
                                                @if(!empty($dc->phone))
                                                    <strong>Tel:</strong>
                                                    <a href="tel:{{ $dc->phone }}">{{ $dc->phone }}</a><br>
                                                @else
                                                     <strong>Tel:</strong>
                                                    <a href=""></a><br>
                                                @endif

                                                @if(!empty($dc->email))
                                                    <strong>Email:</strong>
                                                    <a href="mailto:{{ $dc->email }}">{{ $dc->email }}</a><br>
                                                @else
                                                    <strong>Email:</strong>
                                                    <a href=""></a><br>
                                                @endif
                                                    <strong>Address:</strong>&nbsp;{{$dc->address??''}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Contact 
                                                      <a href="@if( $dc->slug != '' && isset($dc->distributorInfo) && isset($dc->distributorInfo->distrOneCategorytIds) && isset($dc->distributorInfo->distrOneCategorytIds->catInfo) ){{ route('front.distrbCont', array('lng' => 'en', 'catslug' => $dc->distributorInfo->distrOneCategorytIds->catInfo->slug, 'distrbslug' => $dc->distributorInfo->slug, 'contslug' => $dc->slug)) }}@endif">
                                                            {{ $dc->name??'' }}
                                                        </a>
                                                        
                                                        </strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            @endforeach
           
            @else
            @endif
   @if( isset($allDisConts) && !empty($allDisConts))
            @php
                $branches = $allDisConts
                    ->map(function ($branch) {
                        return [
                            'name' => $branch->name,
                            'branch_slug' => $branch->slug,
                            'latitude' => $branch->latitude,
                            'longitude' => $branch->longitude,
                            'address' => $branch->address,
                            'branch_type' => $branch->branch_type ?? '',
                            'continent_slug' => $branch->continent_slug ?? '',
                            'country_slug' => $branch->country_slug ?? '',
                        ];
                    })
                    ->values();
            @endphp

            @if ($allDisConts->count())
                <input type="hidden" id="branchURL" value="{{ url('') }}">

                <div id="mapbranch" style="height:400px;width:100%;"></div>

              
            @endif
@else
@endif
                          
                                @if (!empty($allData->below_map))
                              <div class="pgb-extra-content" style="margin-top:50px">
                                    {!! trim(html_entity_decode($allData->below_map, ENT_QUOTES)) !!}
                                </div>
                                @else
                                @endif
         
        </div>
        <div class="col-sm-4">
            <div class="rightpanel">
                <!-- Loop -->
                @if (isset($allData->pageBuilderContent) && !empty($allData->pageBuilderContent) && isset($device))
                    @foreach ($allData->pageBuilderContent as $pgd) 
                        <!-- Eform -->
                        @if ($pgd->builder_type == 'EFORM' && $pgd->position == 'RIGHT')
                            @if ($device == '1')
                                <!-- Device Checking -->
                                <div class="sidebar_block form" id="EFORM">
                                    {{-- $pgd->main_title --}}
                                    <h2 class="sph2">{!! trim(html_entity_decode($pgd->main_title, ENT_QUOTES)) !!}<span>{{ $pgd->sub_title }}</span></h2>
                                    {!! getHtmlFormBySCODE($pgd->main_content) !!}
                                </div>
                            {{-- @if (url()->current() == 'https://www.multotec.icedev.co.za/en/location/north-america/multotec-canada') --}}
                            @if(
                                !empty($allData->youtube) ||
                                !empty($allData->linkedin) ||
                                !empty($allData->facebook) ||
                                !empty($allData->twitter)
                            )
                            <p style="text-align:center;">
                                <strong style="font-size:24px;vertical-align:middle;">Follow us:</strong>

                                @if(!empty($allData->youtube))
                                    <a href="{{ $allData->youtube }}" target="_blank">
                                        <img src="{{ asset('public/uploads/files/youtube.jpg') }}" width="50" height="50">
                                    </a>
                                @endif

                                @if(!empty($allData->linkedin))
                                    <a href="{{ $allData->linkedin }}" target="_blank">
                                        <img src="{{ asset('public/uploads/files/linkedin.jpg') }}" width="50" height="50">
                                    </a>
                                @endif

                                @if(!empty($allData->facebook))
                                    <a href="{{ $allData->facebook }}" target="_blank">
                                        <img src="{{ asset('public/uploads/files/facebook.jpg') }}" width="50" height="50">
                                    </a>
                                @endif

                                @if(!empty($allData->twitter))
                                    <a href="{{ $allData->twitter }}" target="_blank">
                                        <img src="{{ asset('public/uploads/files/twitter.png') }}" width="50" height="50">
                                    </a>
                                @endif
                            </p>
                            @endif

                                @if(!empty($pgd->table_type == 'DISTRIBUTOR'))
                                  <div style="display:block;">
                                  
                                    @if($allData->brochure_link != '')

                                    <div class="buttom-row dwn-btn" style="margin:8px 0; width:100%;">
                                        <a class="squre-btn"
                                        target="_blank"
                                        href="{{ $allData->brochure_link }}"
                                        style="display:block; background-color:#92c654 !important; color:#fff !important; text-decoration:none;">
                                            <span style="width:auto; display:flex; padding:0; height:50px; justify-content:center; align-items:center; font-size:23px; color:#fff !important;">
                                                Brochures
                                            </span>
                                        </a>
                                    </div>

                                    @else
                                    @endif



                                @if($allData->kh_link != '')

                                    <div class="buttom-row dwn-btn" style="margin:8px 0; width:100%;">
                                        <a class="squre-btn"
                                         target="_blank"
                                        href="{{ $allData->kh_link }}"
                                        style="display:block; background-color:#92c654 !important; color:#fff !important; text-decoration:none;">
                                            <span style="width:auto; display:flex; padding:0; height:50px; justify-content:center; align-items:center; font-size:23px; color:#fff !important;">
                                                Knowledge Hub
                                            </span>
                                        </a>
                                    </div>

                                </div>


                                    @else
                                    @endif

                                @else
                                @endif

{{-- @endif --}}
                                <!-- For Desktop Popup -->
                                <div class="modal fade" id="desktop_eform_modal" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel2">
                                    <div class="modal-dialog modal-lg" role="document">
                                        <div class="modal-content modal-bacg">
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6 greenbg">
                                                        <div class="fbg">
                                                            @php
                                                                $frmData = getReusableByKey('modal_form_content');
                                                            @endphp
                                                            @if (isset($frmData) && !empty($frmData))
                                                                <h3>{{ $frmData->title }}</h3>
                                                            @endif
                                                            <div class="frm-data-cbox">
                                                                @if (isset($frmData) && !empty($frmData))
                                                                    {!! html_entity_decode($frmData->content) !!}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 dsk-modal-frm">
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close"><span
                                                                aria-hidden="true">&times;</span></button>
                                                        <div class="dsk-modal-frmright">
                                                            <h2 class="sph2">
                                                                {{ $pgd->main_title }}<span>{{ $pgd->sub_title }}</span>
                                                            </h2>
                                                            {!! getHtmlFormBySCODE($pgd->main_content) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($device == '2')
                                <!-- Device Checking --> <!-- for Mobile -->
                                <a href="javascript:void(0);" class="mob-frm-sbt" data-toggle="modal"
                                    data-target="#eform_modal">Submit an Enquiry</a>
                                <div class="modal fade" id="eform_modal" tabindex="-1" role="dialog"
                                    aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-body mobile_form">
                                                <button type="button" class="close" data-dismiss="modal"
                                                    aria-label="Close"><span
                                                        aria-hidden="true">&times;</span></button>
                                                <h2 class="sph2">
                                                    {{ $pgd->main_title }}<span>{{ $pgd->sub_title }}</span></h2>
                                                {!! getHtmlFormBySCODE($pgd->main_content) !!}
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

@php

    //$url = "https://www.multotec.com".$_SERVER['REQUEST_URI'];
    //echo "hi5".$allData->distrOneCategorytIds;die;
@endphp



<!-- CTA BLOCK --> <!-- CTA FULL PAGE -->
@if (isset($allData->pageBuilderContent) && !empty($allData->pageBuilderContent) && isset($device))
    @foreach ($allData->pageBuilderContent as $pgd)
        @if ($pgd->device == $device || $pgd->device == '3')
            <!-- Device Checking -->
            @if ($pgd->builder_type == 'CTA' && $pgd->position == 'BODY')
                <section class="green_strip">
                    <div class="container">
                        <div class="text-center">
                            <h6>{!! trim(html_entity_decode($pgd->main_title, ENT_QUOTES)) !!}{{-- $pgd->main_title --}}<a
                                    href="{{ $pgd->link_url }}">{{ $pgd->link_text }}</a></h6>
                        </div>
                    </div>
                </section>
            @endif
        @endif
    @endforeach
@endif
<!-- END CTA BLOCK--> <!-- END CTA FULL PAGE -->

<!-- MID BLOCK --> <!-- PRODUCT BOX FULL CONTAINER -->
<section class="container">
    @if (isset($allData->pageBuilderContent) && !empty($allData->pageBuilderContent) && isset($device))
        @foreach ($allData->pageBuilderContent as $pgd)
            @if ($pgd->device == $device || $pgd->device == '3')
                <!-- Device Checking -->

                @if ($pgd->builder_type == 'PRODUCT_BOX' && $pgd->position == 'BODY')
                    <div class="midblock_subblock">
                        <h2>{!! trim(html_entity_decode($pgd->main_title, ENT_QUOTES)) !!}{{-- $pgd->main_title --}}</h2>
                        @if (isset($pgd->links))
                            <div class="row">
                                @foreach ($pgd->links as $lnk)
                                    @php
                                        $linkData = linkSlugToContent($lnk->slug);
                                    @endphp
                                    @if (!empty($linkData))
                                        @php $proImgArr = getProductImage($linkData->id); @endphp
                                        <div class="col-sm-3 ar-pbox">
                                            <a href="{{ url($lng . '/' . $lnk->slug) }}">
                                                <h4>{{ $linkData->name }}</h4>
                                            </a>
                                            @if (!empty($proImgArr))
                                                <div class="imagecontsiner"><img
                                                        src="{{ asset('public/uploads/files/media_images/' . $proImgArr->image) }}"
                                                        alt="{{ $proImgArr->alt_tag }}"
                                                        title="{{ $proImgArr->title }}"
                                                        caption="{{ $proImgArr->caption }}" style="height: 166px;">
                                                </div>
                                            @endif
                                            <p class="ar-pbox-p">{{ str_limit($linkData->description, 140) }}</p>
                                            <div class="text-center ar-rmdiv"><a
                                                    href="{{ url($lng . '/' . $lnk->slug) }}"
                                                    class="btn1 btn2-default">Read More</a></div>
                                        </div>
                                    @endif
                                @endforeach
                                {!! genPBOXreusContent($pgd->link_text) !!}
                            </div>
                        @endif
                    </div>
                @endif


                @if ($pgd->builder_type == 'PRODUCT_CAT_BOX' && $pgd->position == 'BODY')
                    <div class="midblock_subblock">
                        <h2>{{ $pgd->main_title }}</h2>
                        @if (isset($pgd->links))
                            <div class="row">
                                @foreach ($pgd->links as $lnk)
                                    @php
                                        $linkData = linkSlugToContent($lnk->slug);
                                    @endphp
                                    @if (!empty($linkData))
                                        @php $proImgArr = getProductCatImage($linkData->id); @endphp
                                        <div class="col-sm-3 ar-pbox">
                                            <a href="{{ url($lng . '/' . $lnk->slug) }}">
                                                <h4>{{ $linkData->name }}</h4>
                                            </a>
                                            @if (!empty($proImgArr))
                                                <div class="imagecontsiner"><img
                                                        src="{{ asset('public/uploads/files/media_images/' . $proImgArr->image) }}"
                                                        alt="{{ $proImgArr->alt_tag }}"
                                                        title="{{ $proImgArr->title }}"
                                                        caption="{{ $proImgArr->caption }}" style="height: 166px;">
                                                </div>
                                            @endif
                                            <p class="ar-pbox-p">{{ str_limit($linkData->description, 140) }}</p>
                                            <div class="text-center ar-rmdiv">
                                                <a href="{{ url($lng . '/' . $lnk->slug) }}"
                                                    class="btn1 btn2-default">Read More</a>
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



<!-- LOOP BLOK -->
<section class="container loopblock">
    <div class="row">
        <div class="col-sm-8">
            <div class="midblock">
                @if (isset($allData->pageBuilderContent) && !empty($allData->pageBuilderContent) && isset($device))
                    @foreach ($allData->pageBuilderContent as $pgd)
                        @if ($pgd->device == $device || $pgd->device == '3')
                            <!-- Device Checking -->

                            <!-- Extra SEO -->
                            @if ($pgd->builder_type == 'EXTRA_SEO' && $pgd->position == 'BODY')
                                <div class="pgb-extra-seo">
                                    {!! trim(html_entity_decode($pgd->main_content, ENT_QUOTES)) !!}
                                </div>
                                <div class="clearfix"></div>
                            @endif


                            <!-- Image Carousel -->
                            @if ($pgd->builder_type == 'IMAGE_CAROUSEL' && $pgd->position == 'BODY')
                                <div class="slider_block pgb-image-slider">
                                    <div class="owl-carousel1">
                                        @if (isset($pgd->images) && !empty($pgd->images) && count($pgd->images) != 0)
                                            @foreach ($pgd->images as $caraImgs)
                                                @if (isset($caraImgs->masterImageInfo) && !empty($caraImgs->masterImageInfo))
                                                    <div class="item">
                                                        <div class="innerslide">
                                                            <img src="{{ asset('public/uploads/files/media_images/' . $caraImgs->masterImageInfo->image) }}"
                                                                alt="{{ $caraImgs->img_alt }}"
                                                                title="{{ $caraImgs->img_title }}"
                                                                caption="{{ $caraImgs->img_caption }}" />
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
                            @if ($pgd->builder_type == 'VIDEO_GALLERY' && $pgd->position == 'BODY')
                                <div class="slider_block pgb-video-slider">
                                    <div class="owl-carousel2">
                                        @if (isset($pgd->videos) && !empty($pgd->videos) && count($pgd->videos) != 0)
                                            @foreach ($pgd->videos as $vidGal)
                                                @if (isset($vidGal->masterVideoInfo) && !empty($vidGal->masterVideoInfo))
                                                    <div class="item">
                                                        <div class="innerslide">
                                                            <div class="video_block" style="width: 75%;">
                                                                <iframe width="560" height="315"
                                                                    src="https://www.youtube.com/embed/{{ $vidGal->masterVideoInfo->video_link }}?rel=0"
                                                                    frameborder="0"
                                                                    allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                                                    allowfullscreen></iframe>
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
                            @if ($pgd->builder_type == 'EXTRA_CONT' && $pgd->position == 'BODY')
                                <div class="pgb-extra-content">
                                    {!! trim(html_entity_decode($pgd->main_content, ENT_QUOTES)) !!}
                                </div>
                                <div class="clearfix"></div>
                            @endif


                            <!-- Container Width Hero Statement -->
                            @if ($pgd->builder_type == 'HERO_SCW' && $pgd->position == 'BODY')
                                <div class="pgb-hero-scw">
                                    <h6 class="midbody_subheading">{!! trim(html_entity_decode($pgd->main_content, ENT_QUOTES)) !!}{{-- $pgd->main_content --}}</h6>
                                </div>
                            @endif
                                 
                            <!-- Quick Body LINKS -->
                            @if (
                                ($pgd->builder_type == 'PRODUCT_LINKS' ||
                                    $pgd->builder_type == 'DISTRIBUTOR' ||
                                    $pgd->builder_type == 'DISTRIBUTOR_PAGE' ||
                                    $pgd->builder_type == 'PRODUCT_CAT_LINKS' ||
                                    $pgd->builder_type == 'PEOPLE_LINKS' ||
                                    $pgd->builder_type == 'NEWS_LINKS' ||
                                    $pgd->builder_type == 'CUSTOM_LINKS' ||
                                    $pgd->builder_type == 'BROCHURE_LINKS' ||
                                    strpos($pgd->builder_type, 'CONTENT_LINKS') !== false) &&
                                    $pgd->position == 'BODY')
                                <div class="midbody_newsblock pgb-links">
                                    <h3>{{ $pgd->main_title }}</h3>
                                    @if (isset($pgd->links))
                                        <div class="news_list">
                                            <ul class="greendot">
                                            




                                                {{-- @foreach ($pgd->links as $lnk)
                                              
                                                    @php
                                                        $linkData = linkSlugToContent($lnk->slug,$pgd->builder_type??'');
                                                    @endphp
                                                    

                                                    
                                                    @if (!empty($linkData) && $pgd->builder_type != 'CUSTOM_LINKS')
                                                        <li>
                                                            <a
                                                                href="{{ url($lng . '/' . $lnk->slug) }}">{{ $linkData->name }}</a>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <a  href="{{ $lnk->slug }}">{{ $lnk->link_text }}</a>
                                                        </li>
                                                    @endif
                                                @endforeach --}}
                                                @foreach ($pgd->links as $lnk)

                                                        @php
                                                            $linkData = linkSlugToContent($lnk->slug, $pgd->builder_type ?? '');
                                                        @endphp

                                                        @if (!empty($linkData) && $pgd->builder_type == 'BROCHURE_LINKS')
                                                            <li>
                                                                <a href="{{ url($lng . '/brochure/' . $lnk->slug) }}">{{ $linkData->name }}</a>
                                                            </li>
                                                        @elseif (!empty($linkData) && $pgd->builder_type != 'CUSTOM_LINKS')
                                                            <li>
                                                                <a href="{{ url($lng . '/' . $lnk->slug) }}">{{ $linkData->name }}</a>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <a href="{{ $lnk->slug }}">{{ $lnk->link_text }}</a>
                                                            </li>
                                                        @endif

                                                    @endforeach

                                              
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                                <div class="clearfix"></div>
                            @endif

                            {{-- <!-- Accordion -->
                            @if ($pgd->builder_type == 'ACCORDION' && $pgd->position == 'BODY')
                            <div class="midblock_subblock countries pgb-accr">
                                @if (isset($pgd->accordion))
                                    @foreach ($pgd->accordion as $accr)
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
                            @endif --}}
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
        <div class="col-sm-4">
            <div class="rightpanel">
                @if (isset($allData->pageBuilderContent) && !empty($allData->pageBuilderContent) && isset($device))
                    @foreach ($allData->pageBuilderContent as $pgd)
                        @if ($pgd->device == $device || $pgd->device == '3')
                            <!-- Device Checking -->

                            <!-- Quick Body LINKS -->
                            @if (
                                ($pgd->builder_type == 'PRODUCT_LINKS' ||
                                    $pgd->builder_type == 'DISTRIBUTOR' ||
                                    $pgd->builder_type == 'DISTRIBUTOR_PAGE' ||
                                    $pgd->builder_type == 'PRODUCT_CAT_LINKS' ||
                                    $pgd->builder_type == 'PEOPLE_LINKS' ||
                                    $pgd->builder_type == 'NEWS_LINKS' ||
                                    $pgd->builder_type == 'CUSTOM_LINKS' ||
                                      $pgd->builder_type == 'BROCHURE_LINKS' ||
                                    strpos($pgd->builder_type, 'CONTENT_LINKS') !== false) &&
                                    $pgd->position == 'RIGHT')
                                <div class="midbody_newsblock pgb-links-right">
                                    <h3>{!! trim(html_entity_decode($pgd->main_title, ENT_QUOTES)) !!}{{-- $pgd->main_title --}}</h3>
                                    @if (isset($pgd->links))
                                        <div class="news_list">
                                            <ul class="arrow-list">
                                                {{-- @foreach ($pgd->links as $lnk)
                                                    @php
                                                        $linkData = linkSlugToContent($lnk->slug,$pgd->builder_type??'');
                                                    @endphp
                                                    @if (!empty($linkData) && $pgd->builder_type != 'CUSTOM_LINKS')
                                                        <li>
                                                            <a
                                                                href="{{ url($lng . '/' . $lnk->slug) }}">{{ $linkData->name }}</a>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <a href="{{ $lnk->slug }}">{{ $lnk->link_text }}</a>
                                                        </li>
                                                    @endif
                                                @endforeach --}}
                                                       @foreach ($pgd->links as $lnk)

                                                        @php
                                                            $linkData = linkSlugToContent($lnk->slug, $pgd->builder_type ?? '');
                                                        @endphp

                                                        @if (!empty($linkData) && $pgd->builder_type == 'BROCHURE_LINKS')
                                                            <li>
                                                                <a href="{{ url($lng . '/brochure/' . $lnk->slug) }}">{{ $linkData->name }}</a>
                                                            </li>
                                                        @elseif (!empty($linkData) && $pgd->builder_type != 'CUSTOM_LINKS')
                                                            <li>
                                                                <a href="{{ url($lng . '/' . $lnk->slug) }}">{{ $linkData->name }}</a>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <a href="{{ $lnk->slug }}">{{ $lnk->link_text }}</a>
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

                        @if ($device == '1')
                            <!-- Device Checking -->
                            <!-- STICKY BUTTON -->
                            @if ($pgd->builder_type == 'STICKY_BUTT' && $pgd->position == 'RIGHT')
                                <div class="quote_block" id="sidebar">
                                    <h2>{{ $pgd->main_title }}<span>{{ $pgd->sub_title }}</span></h2>
                                    <div class="buttom-row">
                                        <a href="javascript:void(0);" data-toggle="modal"
                                            data-target="#desktop_eform_modal"
                                            class="submit-btn">{{ $pgd->link_text }}</a>
                                        <!-- add class "scroll-btn" -->
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
@if (isset($allData->pageBuilderContent) && !empty($allData->pageBuilderContent) && isset($device))
    @foreach ($allData->pageBuilderContent as $pgd)
        @if ($pgd->device == $device || $pgd->device == '3')
            <!-- Device Checking -->

            <!-- METRIC -->
            @if ($pgd->builder_type == 'METRIC' && $pgd->position == 'BODY')
                <section class="container">
                    <div class="row">
                        <div class="col-sm-8">
                            @if ($pgd->sub_content == 'METRIC_LEFT')
                                <div class="strip_1">
                                    <div class="bg_green"
                                        style="background-color: {{ $pgd->link_text }}; color: {{ $pgd->link_url }};">
                                        <div class="inner-dv">
                                            <span class="number">{{ $pgd->main_title }}</span> <span
                                                class="text">{{ $pgd->sub_title }}</span>
                                        </div>
                                    </div>
                                    <p>{{ $pgd->main_content }}</p>
                                </div>
                            @endif
                            @if ($pgd->sub_content == 'METRIC_RIGHT')
                                <div class="strip_2">
                                    <p>{{ $pgd->main_content }}</p>
                                    <div class="bg_blue"
                                        style="background-color: {{ $pgd->link_text }}; color: {{ $pgd->link_url }};">
                                        <div class="inner-dv">
                                            <span class="number">{{ $pgd->main_title }}</span> <span
                                                class="text">{{ $pgd->sub_title }}</span>
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
            @if ($pgd->builder_type == 'HERO_SPW' && $pgd->position == 'BODY')
                <div class="padtop">
                    <div class="container">{!! trim(html_entity_decode($pgd->main_content, ENT_QUOTES)) !!}</div>
                </div>
                <div class="clearfix"></div>
            @endif
        @endif <!-- End Device Checking -->
    @endforeach
@endif
<!-- END LAST BLOCK -->

<!-- Reusable -->
@if (isset($allData->pageBuilderContent) && !empty($allData->pageBuilderContent) && isset($device))
    @foreach ($allData->pageBuilderContent as $pgd)
        @if ($pgd->device == $device || $pgd->device == '3')
            <!-- Device Checking -->

            <!-- Eform -->
            @if ($pgd->builder_type == 'REUSE' && $pgd->position == 'BODY')
                {!! getHtmlReuseBySCODE($pgd->main_content) !!}
            @endif
            <!-- End Eform -->
        @endif
    @endforeach
@endif
<div class="clearfix"></div>
<!-- End Reusable -->
   @if( isset($allDisConts) && !empty($allDisConts))
<script>
let branches = [
@foreach($allDisConts as $key => $dc)
{
    id: "{{ $dc->id }}",
    name: "{{ addslashes($dc->name) }}",
    latitude: "{{ $dc->latitude }}",
    longitude: "{{ $dc->longitude }}",
    address: "{{ addslashes($dc->address) }}",
    url: "@if($dc->slug != '' && isset($dc->distributorInfo) && isset($dc->distributorInfo->distrOneCategorytIds) && isset($dc->distributorInfo->distrOneCategorytIds->catInfo)){{ route('front.distrbCont', ['lng' => 'en', 'catslug' => $dc->distributorInfo->distrOneCategorytIds->catInfo->slug, 'distrbslug' => $dc->distributorInfo->slug, 'contslug' => $dc->slug]) }}@endif"
},
@endforeach
];
</script>

<script>
    console.log(branches);
    function initMap() {

        let validBranches = branches.filter(branch =>
            branch.latitude &&
            branch.longitude &&
            !isNaN(branch.latitude) &&
            !isNaN(branch.longitude)
        );

        if (!validBranches.length) {
            return;
        }

        let map = new google.maps.Map(document.getElementById('mapbranch'), {
            zoom: 3,
            center: {
                lat: parseFloat(validBranches[0].latitude),
                lng: parseFloat(validBranches[0].longitude)
            },
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            
        });

        let bounds = new google.maps.LatLngBounds();
        let infoWindow = new google.maps.InfoWindow();

        validBranches.forEach(function(branch) {

            let marker = new google.maps.Marker({
                position: {
                    lat: parseFloat(branch.latitude),
                    lng: parseFloat(branch.longitude)
                },
                map: map
            });

            bounds.extend(marker.getPosition());

         let googleView =
    '<a href="https://maps.google.com/?q=' +
    branch.latitude + ',' + branch.longitude +
    '" target="_blank">Google View</a>';

let branchView =
    '<a href="' +
    branch.url +
    '">View Branch</a>';

let content =
    '<div style="min-width:220px;">' +
    '<strong>' + branch.name + '</strong><br>' +
    (branch.address || '') +
    '<br><br>' +
    googleView +
    '&nbsp;&nbsp;&nbsp;' +
    branchView +
    '</div>';

            marker.addListener('click', function() {
                infoWindow.setContent(content);
                infoWindow.open(map, marker);
            });
        });

      //  map.fitBounds(bounds);
      if (validBranches.length === 1) {
            map.setCenter({
                lat: parseFloat(validBranches[0].latitude),
                lng: parseFloat(validBranches[0].longitude)
            });

            map.setZoom(10);
        } else {
            map.fitBounds(bounds);
        }
    }

    google.maps.event.addDomListener(window, 'load', initMap);
</script>

@else
@endif


<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC1ctyLhYi1UVzqbsc1fLA6evrrdGWeoWs&callback=initMap&sensor=false">
</script>
