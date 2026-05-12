@extends('front_end.layout.layout_master')
@include('front_end.structure.page_meta')


@section('page_content')
<style>
    .open-email-modal {
    cursor: pointer;
}
.fbg * {
    color: #fff !important;
}
</style>

@if( isset($allData) && !empty($allData) )



@php $distInfo = getDistInfo($allData->distributor_id); @endphp

<section class="container">
    <div class="breadcrumb" style="margin-top: 20px;"> <!-- Breadcrumb Segment -->
        <ul>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li><a href="{{ route('front.distrbMap', array('lng' => $lng)) }}">Location</a></li>
            
            @if( isset($allData->distributorInfo) && isset($allData->distributorInfo->distrCategorytOne) && isset($allData->distributorInfo->distrCategorytOne->catInfo) )
            <li>
              <a href="{{ route('front.distrbCat', array('lng' => $lng, 'catslug' => $allData->distributorInfo->distrCategorytOne->catInfo->slug)) }}">
                {{ $allData->distributorInfo->distrCategorytOne->catInfo->name }}
              </a>
            </li>
            <li>
              <a href="{{ route('front.distrb', array('lng' => $lng, 'catslug' => $allData->distributorInfo->distrCategorytOne->catInfo->slug, 'distrbslug' => $allData->distributorInfo->slug)) }}">
                {{ $allData->distributorInfo->name }}
              </a>
            </li>
            @endif
            
            <li class="active">{{$allData->name}}</li>
        </ul>
    </div>
</section>

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
            
            @if( isset($allData) && $allData->latitude != '' && $allData->longitude != '' )
            <div class="gmap-block">
              @if( $allData->map_heading != '' )<h3>{{ $allData->map_heading }}</h3>@endif
              <div id="map" style="height: 460px; width: 100%;"></div>
              <input type="hidden" id="mLat" value="@if(isset($allData)){{ $allData->latitude }}@endif">
              <input type="hidden" id="mLng" value="@if(isset($allData)){{ $allData->longitude }}@endif">
              <input type="hidden" id="mAddr" value="@if(isset($allData)){{ $allData->address }}@endif">
              <input type="hidden" id="mType" value="@if(isset($allData)){{ $allData->branch_type }}@endif">
              <input type="hidden" id="mBname" value="@if(isset($allData)){{ $allData->name }}@endif">
            </div>
            @endif
        </div>
    </div>
    <div class="col-sm-4">
        <div class="rightpanel"> 
              <div class="modal fade" id="desktop_eform_modal_email" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2email">
                          <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content modal-bacg">
                              <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 greenbg">
                                        <div class="fbg">
                                                                                                                                 <h3>Get in touch with Multotec</h3>
                                                                                      <div class="frm-data-cbox">
                                                                                              <p style="text-align:left; font-size:19px; padding-bottom:28px;"><span>Our engineers and metallurgists will help you process minerals faster and more efficiently.</span></p>

<ul>
	<li>
	<p style="text-align:left;"><span style="font-size:21px; font-weight:500;">Full range of process equipment</span><br />
	<span style="font-size:17px;">to optimise your mineral processing plant</span></p>
	</li>
	<li>
	<p style="text-align:left"><span style="font-size:21px; font-weight:500;">Large stockholdings &amp; fast delivery</span><br />
	<span style="font-size:17px;">of equipment and spares to support your plant </span></p>
	</li>
	<li>
      <p style="text-align:left"><span style="font-size:21px; font-weight:500;">24-hour field services, </span><br />
	<span style="font-size:17px;">technical and maintenance support</span></p>
	</li>
	<li>
	<p style="text-align:left"><span style="font-size:21px; font-weight:500;">Metallurgical &amp; engineering support</span><br />
	<span style="font-size:17px;">to optimise your process and plant</span></p>
	</li>
</ul>
                                                                                          </div> 
                                        </div>
                                    </div>
                                    <div class="col-md-6 dsk-modal-frm">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <div class="dsk-modal-frmright">
                                            <h2 class="sph2">Need more info?<span>Contact Multotec</span></h2>
                                            <div class='ar_frm_container' style='background-color:#eaeaea; color:#000000;'><form name='general' action='https://www.multotec.icedev.co.za/arindam-form-submit' method='post' class='ar_vali_class '  enctype='multipart/form-data' ><input type='hidden' name='receive_email[]' value='heathl@cubicice.com' /><input type='hidden' name='receive_email[]' value='KoenaL@multotec.com' /><input type='hidden' name='receive_email[]' value='AnnahV@multotec.com' /><input type='hidden' name='receive_email[]' value='VivienneM@multotec.com' /><input type='hidden' name='receive_email[]' value='tarryn@cubicice.com' /><input type='hidden' name='receive_email[]' value='heatherr@Multotec.com' /><div class='row fd_box' id='field_22'><div class='col-md-12 col-sm-12'><div class='form-group'><label>I want to enquire about :</label> <em>*</em> <select name='iwanttoenquireabout_6ff314aae2564ef958f6739b4b07e185' required class='form-control'  ><option value=''>I want to enquire about:</option><option value='1.-Mineral-processing-products-and-services'>1. Mineral processing products and services</option><option value='2.-Job-applications'>2. Job applications</option><option value='3.-Training-Opportunities'>3. Training Opportunities</option><option value='4.-Other'>4. Other</option></select><div id='ed_action_box_22'></div></div></div></div><div class='row fd_box' id='field_24'><div class='col-md-12 col-sm-12'><div class='form-group'><label>Country :</label> <em>*</em> <select name='country_bdf6b0663c3d12b26de9d64a0331d39f' required class='form-control'  ><option value=''>Select Country</option><option value='USA'>USA</option></select><div id='ed_action_box_24'></div></div></div></div><div class='row fd_box' id='field_16'><div class='col-md-12 col-sm-12'><div class='form-group'><label>Name :</label> <em>*</em> <input type='text' name='name-full_e07b31bd420ff4b70e17fe441e78461b' placeholder='Name' required class='form-control'  /><div id='ed_action_box_16'></div></div></div></div><div class='row fd_box' id='field_3'><div class='col-md-12 col-sm-12'><div class='form-group'><label>Your Email-id :</label> <em>*</em> <input type='email' name='email_61226fd4585429e623016599e6fb44e1' placeholder='Email:' required class='form-control'  /><div id='ed_action_box_3'></div></div></div></div><div class='row fd_box' id='field_4'><div class='col-md-12 col-sm-12'><div class='form-group'><label>Phone Number :</label> <em>*</em> <input type='text' name='contactno_07351a4ae50ef96a1c50a5cc650473f3' placeholder='Phone:' required class='form-control onlyPHNO'  /><div id='ed_action_box_4'></div></div></div></div><div class='row fd_box' id='field_17'><div class='col-md-12 col-sm-12'><div class='form-group'><label>Company :</label> <em>*</em> <input type='text' name='company_8d9f1569b3d5a8fba1a5463bc280b601' placeholder='Company' required class='form-control'  /><div id='ed_action_box_17'></div></div></div></div><div class='row fd_box' id='field_5'><div class='col-md-12 col-sm-12'><div class='form-group'><label>Your Requirements :</label> <textarea name='requirements_8fa7670f330845d9f75c72ef098ad774' placeholder='What equipment or solutions does your mineral processing operation require from Multotec?' class='form-control'  ></textarea><div id='ed_action_box_5'></div></div></div></div><div class='row fd_box' id='field_19'><div class='col-md-12 col-sm-12'><div class='form-group'><label>Acceptance Info :</label> <br/><p style='font-size:13px!important; '  ><input type='checkbox'  name='terms_f1b78704ea2449a379eaaf6c129751cb[]'  class='ar-ckb'  value='I-agree-to-receive-Multotec-training-and-event-info'  > I agree to receive Multotec training and event info</p> <div id='ed_action_box_19'></div></div></div></div><div class='row fd_box' id='field_6'><div class='col-md-12 col-sm-12'><div class='form-group'><label>Upload :</label> <label class='custom-file-upload'><i class='fa fa-upload' aria-hidden='true'></i> Upload supporting documents (optional)<input type='file' name='upload_fba14c8b01e43a8e2c25745ee78746df'  style='display:none;' /></label><div id='ed_action_box_6'></div></div></div></div><div class='form-group'><div class='g-recaptcha mt5 mb5' data-sitekey='6LfRP74UAAAAAB3GY81dorfwC6HLGoNyG69DUI8n'></div></div><div class='ar-captcha-vali'></div><div class='row fd_box btnf' id='field_1'><div class='col-md-12 col-sm-12'><div class='form-group'><input type='submit' name='ok_d5d8517aae26dc072e04284ffdd0d267' value='Request a quote' class='submit-btn' style=''  /><div id='ed_action_box_1'></div></div></div></div><input type='hidden' name='referral' id='referral'><input type='hidden' name='ar_frm_id' value='d5d8517aae26dc072e04284ffdd0d267'><input type='hidden' name='thankyou_url' value='https://www.multotec.com/en/thank-you-for-contacting-multotec'></form></div>
                                        </div>
                                    </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>  
            <!-- Loop -->
            @if( isset($allData->pageBuilderContent) && !empty($allData->pageBuilderContent) && isset($device) )
                @foreach( $allData->pageBuilderContent as $pgd )
                    @if( $pgd->device == $device || $pgd->device == '3' ) <!-- Device Checking -->
                     
                        <!-- Eform -->
                        @if( $pgd->builder_type == 'EFORM' && $pgd->position == 'RIGHT' )
                            <div class="sidebar_block form" id="EFORM">
                                <h1>{!! trim( html_entity_decode( $pgd->main_title, ENT_QUOTES ) ) !!}{{-- $pgd->main_title --}}<span>{!! trim( html_entity_decode( $pgd->sub_title, ENT_QUOTES ) ) !!}{{-- $pgd->sub_title --}}</span></h1>
                                {!! getHtmlFormBySCODE( $pgd->main_content ) !!}
                            </div>
                            {{-- <div class="modal fade" id="desktop_eform_modal_email" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2email">
                          <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content modal-bacg">
                              <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 greenbg">
                                        <div class="fbg">
                                                                                                                                 <h3>Get in touch with Multotec</h3>
                                                                                      <div class="frm-data-cbox">
                                                                                              <p style="text-align:left; font-size:19px; padding-bottom:28px;"><span>Our engineers and metallurgists will help you process minerals faster and more efficiently.</span></p>

<ul>
	<li>
	<p style="text-align:left;"><span style="font-size:21px; font-weight:500;">Full range of process equipment</span><br />
	<span style="font-size:17px;">to optimise your mineral processing plant</span></p>
	</li>
	<li>
	<p style="text-align:left"><span style="font-size:21px; font-weight:500;">Large stockholdings &amp; fast delivery</span><br />
	<span style="font-size:17px;">of equipment and spares to support your plant </span></p>
	</li>
	<li>
      <p style="text-align:left"><span style="font-size:21px; font-weight:500;">24-hour field services, </span><br />
	<span style="font-size:17px;">technical and maintenance support</span></p>
	</li>
	<li>
	<p style="text-align:left"><span style="font-size:21px; font-weight:500;">Metallurgical &amp; engineering support</span><br />
	<span style="font-size:17px;">to optimise your process and plant</span></p>
	</li>
</ul>
                                                                                          </div> 
                                        </div>
                                    </div>
                                    <div class="col-md-6 dsk-modal-frm">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <div class="dsk-modal-frmright">
                                            <h2 class="sph2">Need more info?<span>Contact Multotec</span></h2>
                                            <div class='ar_frm_container' style='background-color:#eaeaea; color:#000000;'><form name='general' action='https://www.multotec.icedev.co.za/arindam-form-submit' method='post' class='ar_vali_class '  enctype='multipart/form-data' ><input type='hidden' name='receive_email[]' value='heathl@cubicice.com' /><input type='hidden' name='receive_email[]' value='KoenaL@multotec.com' /><input type='hidden' name='receive_email[]' value='AnnahV@multotec.com' /><input type='hidden' name='receive_email[]' value='VivienneM@multotec.com' /><input type='hidden' name='receive_email[]' value='tarryn@cubicice.com' /><input type='hidden' name='receive_email[]' value='heatherr@Multotec.com' /><div class='row fd_box' id='field_22'><div class='col-md-12 col-sm-12'><div class='form-group'><label>I want to enquire about :</label> <em>*</em> <select name='iwanttoenquireabout_6ff314aae2564ef958f6739b4b07e185' required class='form-control'  ><option value=''>I want to enquire about:</option><option value='1.-Mineral-processing-products-and-services'>1. Mineral processing products and services</option><option value='2.-Job-applications'>2. Job applications</option><option value='3.-Training-Opportunities'>3. Training Opportunities</option><option value='4.-Other'>4. Other</option></select><div id='ed_action_box_22'></div></div></div></div><div class='row fd_box' id='field_24'><div class='col-md-12 col-sm-12'><div class='form-group'><label>Country :</label> <em>*</em> <select name='country_bdf6b0663c3d12b26de9d64a0331d39f' required class='form-control'  ><option value=''>Select Country</option><option value='USA'>USA</option></select><div id='ed_action_box_24'></div></div></div></div><div class='row fd_box' id='field_16'><div class='col-md-12 col-sm-12'><div class='form-group'><label>Name :</label> <em>*</em> <input type='text' name='name-full_e07b31bd420ff4b70e17fe441e78461b' placeholder='Name' required class='form-control'  /><div id='ed_action_box_16'></div></div></div></div><div class='row fd_box' id='field_3'><div class='col-md-12 col-sm-12'><div class='form-group'><label>Your Email-id :</label> <em>*</em> <input type='email' name='email_61226fd4585429e623016599e6fb44e1' placeholder='Email:' required class='form-control'  /><div id='ed_action_box_3'></div></div></div></div><div class='row fd_box' id='field_4'><div class='col-md-12 col-sm-12'><div class='form-group'><label>Phone Number :</label> <em>*</em> <input type='text' name='contactno_07351a4ae50ef96a1c50a5cc650473f3' placeholder='Phone:' required class='form-control onlyPHNO'  /><div id='ed_action_box_4'></div></div></div></div><div class='row fd_box' id='field_17'><div class='col-md-12 col-sm-12'><div class='form-group'><label>Company :</label> <em>*</em> <input type='text' name='company_8d9f1569b3d5a8fba1a5463bc280b601' placeholder='Company' required class='form-control'  /><div id='ed_action_box_17'></div></div></div></div><div class='row fd_box' id='field_5'><div class='col-md-12 col-sm-12'><div class='form-group'><label>Your Requirements :</label> <textarea name='requirements_8fa7670f330845d9f75c72ef098ad774' placeholder='What equipment or solutions does your mineral processing operation require from Multotec?' class='form-control'  ></textarea><div id='ed_action_box_5'></div></div></div></div><div class='row fd_box' id='field_19'><div class='col-md-12 col-sm-12'><div class='form-group'><label>Acceptance Info :</label> <br/><p style='font-size:13px!important; '  ><input type='checkbox'  name='terms_f1b78704ea2449a379eaaf6c129751cb[]'  class='ar-ckb'  value='I-agree-to-receive-Multotec-training-and-event-info'  > I agree to receive Multotec training and event info</p> <div id='ed_action_box_19'></div></div></div></div><div class='row fd_box' id='field_6'><div class='col-md-12 col-sm-12'><div class='form-group'><label>Upload :</label> <label class='custom-file-upload'><i class='fa fa-upload' aria-hidden='true'></i> Upload supporting documents (optional)<input type='file' name='upload_fba14c8b01e43a8e2c25745ee78746df'  style='display:none;' /></label><div id='ed_action_box_6'></div></div></div></div><div class='form-group'><div class='g-recaptcha mt5 mb5' data-sitekey='6LfRP74UAAAAAB3GY81dorfwC6HLGoNyG69DUI8n'></div></div><div class='ar-captcha-vali'></div><div class='row fd_box btnf' id='field_1'><div class='col-md-12 col-sm-12'><div class='form-group'><input type='submit' name='ok_d5d8517aae26dc072e04284ffdd0d267' value='Request a quote' class='submit-btn' style=''  /><div id='ed_action_box_1'></div></div></div></div><input type='hidden' name='referral' id='referral'><input type='hidden' name='ar_frm_id' value='d5d8517aae26dc072e04284ffdd0d267'><input type='hidden' name='thankyou_url' value='https://www.multotec.com/en/thank-you-for-contacting-multotec'></form></div>
                                        </div>
                                    </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>    --}}
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

           {{-- expanded form --}}
                        
            




















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
                                $proImgArr = getProductImage($linkData->id);
                            @endphp
                            @if( !empty( $linkData ) )
                            <div class="col-sm-3">
                                <h4>{{ $linkData->name }}</h4>
                                @if( !empty($proImgArr) )
                                <div class="imagecontsiner"><img src="{{ asset('public/uploads/files/media_images/'. $proImgArr->image) }}" alt="{{ $proImgArr->alt_tag }}" title="{{ $proImgArr->title }}" caption="{{ $proImgArr->caption }}" style="height: 166px; width: 100%;">
                                </div>
                                @endif
                                <p>{{ str_limit( $linkData->description, 140 ) }}</p>
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
                            <div class="col-sm-3">
                                <h4>{{ $linkData->name }}</h4>
                                @if( !empty($proImgArr) )
                                <div class="imagecontsiner"><img src="{{ asset('public/uploads/files/media_images/'. $proImgArr->image) }}" alt="{{ $proImgArr->alt_tag }}" title="{{ $proImgArr->title }}" caption="{{ $proImgArr->caption }}" style="height: 166px; width: 100%;">
                                </div>
                                @endif
                                <p>{{ str_limit( $linkData->description, 140 ) }}</p>
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
                                <p>{!! trim( html_entity_decode( $pgd->main_content, ENT_QUOTES ) ) !!}</p>
                            @endif


                            <!-- Image Carousel -->
                            @if( $pgd->builder_type == 'IMAGE_CAROUSEL' && $pgd->position == 'BODY' )
                            <div class="slider_block">
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
                            @endif


                            <!-- Video Gallery -->
                            @if( $pgd->builder_type == 'VIDEO_GALLERY' && $pgd->position == 'BODY' )
                            <div class="slider_block">
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
                            @endif

                            <!-- Extra Content -->
                            @if( $pgd->builder_type == 'EXTRA_CONT' && $pgd->position == 'BODY' )
                                <p>{!! trim( html_entity_decode( $pgd->main_content, ENT_QUOTES ) ) !!}</p>
                            @endif


                            <!-- Container Width Hero Statement -->
                            @if( $pgd->builder_type == 'HERO_SCW' && $pgd->position == 'BODY' )
                                <h6 class="midbody_subheading">{{ $pgd->main_content }}</h6>
                            @endif

                            <!-- Quick Body LINKS -->
                            @if( ($pgd->builder_type == 'PRODUCT_LINKS' || $pgd->builder_type == 'DISTRIBUTOR' || $pgd->builder_type == 'DISTRIBUTOR_PAGE' || $pgd->builder_type == 'PRODUCT_CAT_LINKS' || $pgd->builder_type == 'PEOPLE_LINKS' || $pgd->builder_type == 'NEWS_LINKS' || $pgd->builder_type == 'CUSTOM_LINKS' || strpos($pgd->builder_type, 'CONTENT_LINKS') !== false) && $pgd->position == 'BODY' )

                                <div class="midbody_newsblock">
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
                            @endif

                            <!-- Accordion -->
                            @if( $pgd->builder_type == 'ACCORDION' && $pgd->position == 'BODY' )
                            <div class="midblock_subblock countries">
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

                                <div class="midbody_newsblock">
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
                            @endif


                            <!-- STICKY BUTTON -->
                            @if( $pgd->builder_type == 'STICKY_BUTT' && $pgd->position == 'RIGHT' )
                                <div class="quote_block" id="sidebar">
                                    <h2>{{ $pgd->main_title }}<span>{{ $pgd->sub_title }}</span></h2>
                                    <div class="buttom-row">
                                        <a href="javascript:void(0);" class="submit-btn scroll-btn">{{ $pgd->link_text }}</a>
                                    </div>
                                </div>
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
            </section>
        @endif

        <!-- PAGE WIDTH HERO STATEMENT -->
        @if( $pgd->builder_type == 'HERO_SPW' && $pgd->position == 'BODY' )
            <p>&nbsp;</p>
            <div class="padtop"><div class="container">{{ $pgd->main_content }}</div></div>
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
<!-- End Reusable -->

@endif
@endsection




@push('page_js')
<script type="text/javascript" src="{{ asset('public/front_end/js/ddaccordion.js') }}"></script>
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

@if( isset($allData) && $allData->latitude != '' && $allData->longitude != '')
<script type="text/javascript">
function initMap() {

    var getMLat = document.getElementById('mLat').value;
    var getMLng = document.getElementById('mLng').value;
    var getMAddr = document.getElementById('mAddr').value;
    var getMType = document.getElementById('mType').value;
    var getMBname = document.getElementById('mBname').value;

    var gmLink = '<br/><br/><a href="https://maps.google.com/?q='+ getMLat +','+ getMLng +'" target="_blank"><strong>Google View</strong></a>';

    var oneMarker = {
        info: '<strong>'+ getMBname + '</strong> ('+ getMType +')<br/>' + getMAddr + gmLink,
        lat: getMLat,
        long: getMLng
    };

    var locations = [
      [oneMarker.info, oneMarker.lat, oneMarker.long, 0]
    ];

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 10,
        center: new google.maps.LatLng(getMLat, getMLng),
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        minZoom: 8,
        maxZoom: 14,
    });

    var infowindow = new google.maps.InfoWindow({});

    var marker, i;
    
    //var bounds = new google.maps.LatLngBounds();

    for (i = 0; i < locations.length; i++) {
        //bounds.extend(new google.maps.LatLng(locations[i][1], locations[i][2]));
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map
        });

        google.maps.event.addListener(marker, 'click', (function (marker, i) {
            return function () {
                infowindow.setContent(locations[i][0]);
                infowindow.open(map, marker);
            }
        })(marker, i));
    }

    //map.fitBounds(bounds);
}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC1ctyLhYi1UVzqbsc1fLA6evrrdGWeoWs&callback=initMap&sensor=false"></script>
@endif
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

<script>
// $(document).on('click', '#firstBlock a[href], .open-email-modal', function (e) {

//     e.preventDefault();

//     // fetch ONLY visible anchor text
//     let email = $.trim($(this).text());

//     // fallback if text empty
//     if (email === '') {

//         email = $(this).data('email') || '';
//     }

//     // fetch country
//     let countryName = `{{ trim($country ?? '') }}`;
//     let countryAlt = `{{ trim($country_alt ?? '') }}`;

//     // modal
//     let modal = $('#desktop_eform_modal_email');

//     // form
//     let form = modal.children().find('form').first();

//     // hidden email field
//     let hiddenInput = form.find('input[name="selected_email"]');

//     if (hiddenInput.length === 0) {

//         $('<input>', {
//             type: 'hidden',
//             name: 'selected_email',
//             value: email
//         }).appendTo(form);

//     } else {

//         hiddenInput.val(email);
//     }

//     // country select
//     let countrySelect = form.find(
//         'select[name="country_bdf6b0663c3d12b26de9d64a0331d39f"]'
//     ).first();

//     // clear previous selection
//     countrySelect.val('');

//     let matchedOption = null;

//     /*
//     |--------------------------------------------------------------------------
//     | Try matching using existing blade variables
//     |--------------------------------------------------------------------------
//     */

//     // first try main country
//     if (countryName !== '') {

//         matchedOption = countrySelect.find('option').filter(function () {

//             return $.trim($(this).text()).toLowerCase() === countryName.toLowerCase();

//         });

//     }

//     // if no match then try alt country
//     if ((!matchedOption || matchedOption.length === 0) && countryAlt !== '') {

//         matchedOption = countrySelect.find('option').filter(function () {

//             return $.trim($(this).text()).toLowerCase() === countryAlt.toLowerCase();

//         });

//     }

//     /*
//     |--------------------------------------------------------------------------
//     | If still not matched -> fetch from map lat/lng
//     |--------------------------------------------------------------------------
//     */

//     if ((!matchedOption || matchedOption.length === 0)
//         && $('#mLat').length
//         && $('#mLng').length
//     ) {

//         let getMLat = $('#mLat').val();
//         let getMLng = $('#mLng').val();

//         if (getMLat !== '' && getMLng !== '') {

//             let geocoder = new google.maps.Geocoder();

//             let latlng = {
//                 lat: parseFloat(getMLat),
//                 lng: parseFloat(getMLng)
//             };

//             geocoder.geocode({ location: latlng }, function(results, status) {

//                 if (status === "OK" && results[0]) {

//                     let detectedCountry = '';

//                     results[0].address_components.forEach(function(component) {

//                         if (component.types.includes('country')) {

//                             detectedCountry = component.long_name;
//                         }

//                     });

//                     if (detectedCountry !== '') {

//                         let geoMatchedOption = countrySelect.find('option').filter(function () {

//                             return $.trim($(this).text()).toLowerCase() === detectedCountry.toLowerCase();

//                         });

//                         if (geoMatchedOption.length) {

//                             countrySelect.val(geoMatchedOption.val());
//                             countrySelect.trigger('change');
//                         }
//                     }

//                     modal.modal('show');

//                 } else {

//                     modal.modal('show');
//                 }

//             });

//             return;
//         }
//     }

//     /*
//     |--------------------------------------------------------------------------
//     | Existing match found
//     |--------------------------------------------------------------------------
//     */

//     if (matchedOption && matchedOption.length) {

//         countrySelect.val(matchedOption.val());
//     }

//     // trigger select change
//     countrySelect.trigger('change');

//     // show modal
//     modal.modal('show');

// });

$(document).on('click', '#firstBlock a[href], .open-email-modal', function (e) {

    e.preventDefault();

    // fetch ONLY visible anchor text
    let email = $.trim($(this).text());

    // fallback if text empty
    if (email === '') {

        email = $(this).data('email') || '';
    }

    // modal
    let modal = $('#desktop_eform_modal_email');

    // form
    let form = modal.children().find('form').first();

    // hidden email field
    let hiddenInput = form.find('input[name="selected_email"]');

    if (hiddenInput.length === 0) {

        $('<input>', {
            type: 'hidden',
            name: 'selected_email',
            value: email
        }).appendTo(form);

    } else {

        hiddenInput.val(email);
    }

    // country select
    let countrySelect = form.find(
        'select[name="country_bdf6b0663c3d12b26de9d64a0331d39f"]'
    ).first();

    // clear previous selection
    countrySelect.val('');

    /*
    |--------------------------------------------------------------------------
    | Fetch country using latitude / longitude
    |--------------------------------------------------------------------------
    */

    if ($('#mLat').length && $('#mLng').length) {

        let getMLat = $('#mLat').val();
        let getMLng = $('#mLng').val();

        if (getMLat !== '' && getMLng !== '') {

            let geocoder = new google.maps.Geocoder();

            let latlng = {
                lat: parseFloat(getMLat),
                lng: parseFloat(getMLng)
            };

            geocoder.geocode({ location: latlng }, function(results, status) {

                if (status === "OK" && results[0]) {

                    let detectedCountry = '';

                    results[0].address_components.forEach(function(component) {

                        if (component.types.includes('country')) {

                            detectedCountry = component.long_name;
                        }

                    });

                    if (detectedCountry !== '') {

                        let matchedOption = countrySelect.find('option').filter(function () {

                            return $.trim($(this).text()).toLowerCase() === detectedCountry.toLowerCase();

                        });

                        if (matchedOption.length) {

                            countrySelect.val(matchedOption.val());
                            countrySelect.trigger('change');
                        }
                    }
                }

                modal.modal('show');

            });

            return;
        }
    }

    // show modal fallback
    modal.modal('show');

});
</script>

<script>
$(document).ready(function () {

    $('#desktop_eform_modal_email').on('hidden.bs.modal', function () {

        let form = $(this).children().find('form').first();

        // completely remove hidden field
        form.find('input[name="selected_email"]').remove();

    });

});
</script>
@endpush

    