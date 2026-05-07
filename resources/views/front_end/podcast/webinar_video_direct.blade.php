@extends('front_end.layout.layout_master')
@include('front_end.structure.page_meta')


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
    line-height: 23px;
	padding:8px 0;
}
	
.piccont ul {
    padding: 12px 0 0 0;
    margin: 0;
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



.border {
    margin-top: 23px;
    background: #f6f6f6;
    padding: 45px;
    font-size: 18px;
    border-left: 3px solid #008c5be8;
}
.border h3 {
    font-size: 20px !important;
    line-height: 26px;
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

<section class="container" style="margin-top: 40px;">

<div class="breadcrumb">
    <ul>
        <li><a href="{{ url('/') }}">Home</a></li>
        <li>Webinar</li>
    </ul>
</div>

 
<div class="picboxsection">
<div class="row">

<div class="col-md-8">
 
   
<div class="loopblock catagorydetails">
<!-- <h2>{{$allData->name}}   </h2> -->

 
<div class="youtubevideo">
<iframe width="560" height="315" src="{{$allData->url}}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="" data-kmt="1" id="widget2" data-gtm-yt-inspected-1_25="true" data-gtm-yt-inspected-11482088_5="true"></iframe>
</div>

</div></div>

<div class="col-md-4  ">
<div class="  border">

 <h3>Thank you!<br><br>
You can access your recording by hitting play on the video to the left.</h3>

</div>

<div class="quote_block" id="sidebar"> 
                                    <h2 style="background: #008d5c;
    padding: 15px;
    font-size: 22px !important;
    font-weight: 500;
    color: #fff;
    text-align: center;
    margin: 0;">Need more info? <span></span></h2>
                                    <div class="buttom-row">
                                        <a href="javascript:void(0);" data-toggle="modal" data-target="#desktop_eform_modal" class="submit-btn">Contact Us</a> <!-- add class "scroll-btn" -->
                                    </div>
                                </div>
                                <div class="clearfix"></div> 



</div>
</div>
 
</section>


<div class="modal fade in" id="desktop_eform_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" style="display: none;">
                          <div class="modal-dialog modal-lg" role="document" style="margin-top: 0px;">
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
	<p style="text-align:left;"><span style="font-size:21px; font-weight:500;">Full range of process equipment</span><br>
	<span style="font-size:17px;">to optimise your mineral processing plant</span></p>
	</li>
	<li>
	<p style="text-align:left"><span style="font-size:21px; font-weight:500;">Large stockholdings &amp; fast delivery</span><br>
	<span style="font-size:17px;">of equipment and spares to support your plant </span></p>
	</li>
	<li>
      <p style="text-align:left"><span style="font-size:21px; font-weight:500;">24-hour field services, </span><br>
	<span style="font-size:17px;">technical and maintenance support</span></p>
	</li>
	<li>
	<p style="text-align:left"><span style="font-size:21px; font-weight:500;">Metallurgical &amp; engineering support</span><br>
	<span style="font-size:17px;">to optimise your process and plant</span></p>
	</li>
</ul>
                                                                                          </div> 
                                        </div>
                                    </div>
                                    <div class="col-md-6 dsk-modal-frm">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                        <div class="dsk-modal-frmright">
                                            <h2 class="sph2">Need more info?<span>Send us your requirements</span></h2>
                                            <div class="ar_frm_container" style="background-color:#eaeaea; color:#000000;"><form name="general" action="https://www.multotec.com/arindam-form-submit" method="post" class="ar_vali_class " enctype="multipart/form-data" novalidate="novalidate" data-kmt="1"><input type="hidden" name="receive_email[]" value="heathl@cubicice.com"><input type="hidden" name="receive_email[]" value="KoenaL@multotec.com"><input type="hidden" name="receive_email[]" value="AnnahV@multotec.com"><input type="hidden" name="receive_email[]" value="VivienneM@multotec.com"><input type="hidden" name="receive_email[]" value="tarryn@cubicice.com"><input type="hidden" name="receive_email[]" value="heatherr@Multotec.com"><div class="row fd_box" id="field_16"><div class="col-md-12 col-sm-12"><div class="form-group"><label>Name :</label> <em>*</em> <input type="text" name="name-full_e07b31bd420ff4b70e17fe441e78461b" placeholder="Name" required="" class="form-control"><div id="ed_action_box_16"></div></div></div></div><div class="row fd_box" id="field_3"><div class="col-md-12 col-sm-12"><div class="form-group"><label>Your Email-id :</label> <em>*</em> <input type="email" name="email_61226fd4585429e623016599e6fb44e1" placeholder="Email:" required="" class="form-control"><div id="ed_action_box_3"></div></div></div></div><div class="row fd_box" id="field_4"><div class="col-md-12 col-sm-12"><div class="form-group"><label>Phone Number :</label> <input type="text" name="contactno_07351a4ae50ef96a1c50a5cc650473f3" placeholder="Phone:" class="form-control onlyPHNO"><div id="ed_action_box_4"></div></div></div></div><div class="row fd_box" id="field_17"><div class="col-md-12 col-sm-12"><div class="form-group"><label>Company :</label> <em>*</em> <input type="text" name="company_8d9f1569b3d5a8fba1a5463bc280b601" placeholder="Company" required="" class="form-control"><div id="ed_action_box_17"></div></div></div></div><div class="row fd_box" id="field_18"><div class="col-md-12 col-sm-12"><div class="form-group"><label>Country :</label> <em>*</em> <input type="text" name="country_9c62720c1b8b66770b57067db53705ce" placeholder="Country" required="" class="form-control"><div id="ed_action_box_18"></div></div></div></div><div class="row fd_box" id="field_5"><div class="col-md-12 col-sm-12"><div class="form-group"><label>Your Requirements :</label> <textarea name="requirements_8fa7670f330845d9f75c72ef098ad774" placeholder="Requirements (include commodity to be processed)" class="form-control"></textarea><div id="ed_action_box_5"></div></div></div></div><div class="row fd_box" id="field_19"><div class="col-md-12 col-sm-12"><div class="form-group"><label>Acceptance Info :</label> <br><p style="font-size:13px!important; "><input type="checkbox" name="terms_f1b78704ea2449a379eaaf6c129751cb[]" class="ar-ckb" value="I-agree-to-receive-Multotec-training-and-event-information"> I agree to receive Multotec training and event information</p> <div id="ed_action_box_19"></div></div></div></div><div class="row fd_box" id="field_6"><div class="col-md-12 col-sm-12"><div class="form-group"><label>Upload :</label> <label class="custom-file-upload"><i class="fa fa-upload" aria-hidden="true"></i> Upload documents (optional)<input type="file" name="upload_fba14c8b01e43a8e2c25745ee78746df" style="display:none;"></label><div id="ed_action_box_6"></div></div></div></div><div class="form-group"><div class="g-recaptcha mt5 mb5" data-sitekey="6LfRP74UAAAAAB3GY81dorfwC6HLGoNyG69DUI8n"><div style="width: 304px; height: 78px;"><div><iframe title="reCAPTCHA" src="https://www.google.com/recaptcha/api2/anchor?ar=1&amp;k=6LfRP74UAAAAAB3GY81dorfwC6HLGoNyG69DUI8n&amp;co=aHR0cHM6Ly93d3cubXVsdG90ZWMuY29tOjQ0Mw..&amp;hl=en&amp;v=tftmXwdbgCvrXiHxr5HGbIaL&amp;size=normal&amp;cb=oeacxdsdox5y" width="304" height="78" role="presentation" name="a-p4lzi1n1cg52" frameborder="0" scrolling="no" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-top-navigation allow-modals allow-popups-to-escape-sandbox" data-gtm-yt-inspected-11482088_5="true" data-gtm-yt-inspected-1_25="true"></iframe></div><textarea id="g-recaptcha-response-1" name="g-recaptcha-response" class="g-recaptcha-response" style="width: 250px; height: 40px; border: 1px solid rgb(193, 193, 193); margin: 10px 25px; padding: 0px; resize: none; display: none;"></textarea></div><iframe data-gtm-yt-inspected-11482088_5="true" data-gtm-yt-inspected-1_25="true" style="display: none;"></iframe></div></div><div class="ar-captcha-vali"></div><div class="row fd_box btnf" id="field_1"><div class="col-md-12 col-sm-12"><div class="form-group"><input type="submit" name="ok_d5d8517aae26dc072e04284ffdd0d267" value="Request a quote" class="submit-btn" style=""><div id="ed_action_box_1"></div></div></div></div><input type="hidden" name="ar_frm_id" value="d5d8517aae26dc072e04284ffdd0d267"><input type="hidden" name="thankyou_url" value="https://www.multotec.com/en/thank-you-for-contacting-multotec"></form></div>
                                        </div>
                                    </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

@endsection

 

@push('page_js')
<script type="text/javascript" src="{{ asset('public/front_end/js/ddaccordion.js') }}"></script>
<script>
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
    togglehtml: ["prefix", "<img src='{{ asset('public/front_end/images/arrow_down_accor.png') }}' style='width:24px; height:24px' /> ", "<img src='{{ asset('public/front_end/images/arrow_up_accor.png') }}' style='width:24px; height:24px' /> "], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
    animatespeed: "normal", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
    oninit:function(expandedindices){ //custom code to run when headers have initalized
        //do nothing
    },
    onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
        //do nothing
    }
})

</script>

<script type="text/javascript">
// *********** tightpanr ********** //
$(document).ready(function() {
    $("div.bhoechie-tab-menu>div.list-group>a").click(function(e) {
        e.preventDefault();
        $(this).siblings('a.active').removeClass("active");
        $(this).addClass("active");
        var index = $(this).index();
        $("div.bhoechie-tab>div.bhoechie-tab-content").removeClass("active");
        $("div.bhoechie-tab>div.bhoechie-tab-content").eq(index).addClass("active");
    });
});
</script>
<script type="text/javascript">
$( function() {
    $('ul.pagination li:first').find('span').text('Prev');
    $('ul.pagination li:last').find('span').text('Next');

    $('ul.pagination li [rel=prev]').html('Prev');
    $('ul.pagination li [rel=next]').html('Next');

} );
</script>

@endpush

    