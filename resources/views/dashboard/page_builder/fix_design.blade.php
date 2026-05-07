<div class="pgb_rightControl">
	<div class="txtrit">
		<button type="button" id="savePgContent" class="btn btn-success btn-sm">
  			<i class="fa fa-floppy-o" aria-hidden="true"></i> Save Content</button>
	</div>
	@if( isset($pageBuilderData) && !empty($pageBuilderData) )
	<div class="txtrit">
		@php 
			$vURL = '';
			if( $pageBuilderData->slug != '' ) {

				$vURL = url(getLngCode($pageBuilderData->language_id).'/'.$pageBuilderData->slug); 
				
				if(isset($childMenu) && $childMenu == 'profileAdd') {
					$vURL = route('front.profCont', array('lng' => getLngCode($pageBuilderData->language_id), 'slug' => $pageBuilderData->slug));
				}
			}
		@endphp
		
		@if( isset($vURL) && $vURL != '' ) 
		<a href="{{ $vURL }}" target="_blank" class="btn btn-warning btn-sm" style="margin-top: 10px; border-radius: 0px;">
  			<i class="fa fa-eye" aria-hidden="true"></i> View Content</a>
  		@endif

	</div>
	@endif
	<div class="txtrit" style="margin-top: 10px;">
  		<button type="button" id="pageBuilderBtn" class="btn btn-primary btn-sm">
  			<i class="fa fa-cogs" aria-hidden="true"></i> Page Builder</button>
	</div>
	<div class="cdiv">
		<div class="panel panel-default" style="margin-bottom: 0;">
			<div class="panel-heading" style="text-align: center;"><strong> Page Builders Controls </strong></div>
			<div class="panel-body">
				<a href="javascript:void(0)" class="btn btn-default btn-sm pgb_ext_cont" style="width: 100%;">Extra Content</a>
				<a href="javascript:void(0)" class="btn btn-default btn-sm pgbfix-contbtn pgb_ext_seo">Extra SEO Content</a>
				<a href="javascript:void(0)" class="btn btn-default btn-sm pgbfix-contbtn pgb_herostat_pw">Hero Statement Page Width</a>
				<a href="javascript:void(0)" class="btn btn-default btn-sm pgbfix-contbtn pgb_herostat_cw">Hero Statement Container Width</a>
				<a href="javascript:void(0)" class="btn btn-default btn-sm pgbfix-contbtn pgb_eform">Enquiry Forms</a>
				<a href="javascript:void(0)" class="btn btn-default btn-sm pgbfix-contbtn pgb_stkbutt">Sticky Button</a>
				<a href="javascript:void(0)" class="btn btn-default btn-sm pgbfix-contbtn pgb_cta">CTA (Call To Action)</a>
				<a href="javascript:void(0)" class="btn btn-default btn-sm pgbfix-contbtn pgb_prdbox">Product Box - Category Box</a>
				<a href="javascript:void(0)" class="btn btn-default btn-sm pgbfix-contbtn pgb_brochure_btn">Brochure Button (Extra Media)</a>
				<!--a href="javascript:void(0)" class="btn btn-default btn-sm pgbfix-contbtn pgb_techres_btn">Technical Resource Button (Extra Media)</a-->
				<a href="javascript:void(0)" class="btn btn-default btn-sm pgbfix-contbtn pgb_imggal_btn">Image Gallery Button (Extra Media)</a>
				<a href="javascript:void(0)" class="btn btn-default btn-sm pgbfix-contbtn pgb_imgcar">Image Carousel</a>
				<!--a href="javascript:void(0)" class="btn btn-default btn-sm pgbfix-contbtn pgb_imggal">Image Gallery</a-->
				<a href="javascript:void(0)" class="btn btn-default btn-sm pgbfix-contbtn pgb_vidbtn">Video Carousel</a>
				<a href="javascript:void(0)" class="btn btn-default btn-sm pgbfix-contbtn pgb_links">Any Quick Links</a>
				<a href="javascript:void(0)" class="btn btn-default btn-sm pgbfix-contbtn pgb_links_custom">Any Custom Links</a>
				<a href="javascript:void(0)" class="btn btn-default btn-sm pgbfix-contbtn pgb_metric">Metric - Left - Right</a>
				<a href="javascript:void(0)" class="btn btn-default btn-sm pgbfix-contbtn pgb_accordion">Accordion</a>
				<a href="javascript:void(0)" class="btn btn-default btn-sm pgbfix-contbtn pgb_reusecont">Reusable Content</a>
			</div>
		</div>
	</div>
</div>