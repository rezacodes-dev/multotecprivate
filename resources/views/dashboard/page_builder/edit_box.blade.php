<div class="col-md-6">
    <div id="pgContentAppend" class="dragContainer">
@if( isset($pageBuilderData) && !empty($pageBuilderData) && isset($pageBuilderData->pageBuilderContent) && !empty($pageBuilderData->pageBuilderContent) )
	@foreach($pageBuilderData->pageBuilderContent as $ebox)
		
		@php
			$device = '';
			if( $ebox->device == '1' ) { $device = '<i class="fa fa-2x fa-desktop base-green" aria-hidden="true"></i>'; }
			if( $ebox->device == '2' ) { $device = '<i class="fa fa-2x fa-mobile base-red" aria-hidden="true"></i>'; }
			if( $ebox->device == '3' ) { $device = '<i class="fa fa-2x fa-desktop base-green" aria-hidden="true"></i> <i class="fa fa-2x fa-mobile base-red" aria-hidden="true"></i>'; }

			$builderTag = $ebox->builder_type;

			if( $ebox->builder_type == 'EXTRA_SEO' ) { $builderTag = 'Extra SEO'; }
	    	if( $ebox->builder_type == 'EXTRA_CONT' ) { $builderTag = 'Extra Content '; }
	    	if( $ebox->builder_type == 'CTA' ) { $builderTag = 'CTA Content '; }
	    	if( $ebox->builder_type == 'HERO_SPW' ) { $builderTag = 'Page Width Hero Statement '; }
	    	if( $ebox->builder_type == 'HERO_SCW' ) { $builderTag = 'Container Width Hero Statement '; }
	    	if( $ebox->builder_type == 'STICKY_BUTT' ) { $builderTag = 'Sticky Button Content '; }
	        if( $ebox->builder_type == 'EFORM' ) { $msg = 'Form Content '; }
	        if( $ebox->builder_type == 'IMAGE_CAROUSEL' ) { $builderTag = 'Image Carousel '; }
	        if( $ebox->builder_type == 'IMAGE_GALLERY' ) { $builderTag = 'Image Gallery '; }
	        if( $ebox->builder_type == 'BROCHURE_BUTT' ) { $builderTag = 'Brochure Download Button '; }
	        if( $ebox->builder_type == 'TECHRES_BUTT' ) { $builderTag = 'Technical Resource Button '; }
	        if( $ebox->builder_type == 'IMAGEGAL_BUTT' ) { $builderTag = 'Image Gallery Button '; }
	        if( $ebox->builder_type == 'VIDEO_GALLERY' ) { $builderTag = 'Video Gallery '; }
	        if( $ebox->builder_type == 'PRODUCT_LINKS' ) { $builderTag = 'Product Links '; }
	        if( $ebox->builder_type == 'PRODUCT_CAT_LINKS' ) { $builderTag = 'Product Category Links '; }
	        if( $ebox->builder_type == 'PRODUCT_BOX' ) { $builderTag = 'Product Box '; }
	        if( $ebox->builder_type == 'PRODUCT_CAT_BOX' ) { $builderTag = 'Product Category Box '; }
	        if( $ebox->builder_type == 'NEWS_LINKS' ) { $builderTag = 'News Links '; }
	        if( $ebox->builder_type == 'PEOPLE_LINKS' ) { $builderTag = 'Peoples Links '; }
	        if( $ebox->builder_type == 'CUSTOM_LINKS' ) { $builderTag = 'Custom Links '; }
	        if( $ebox->builder_type == 'METRIC' ) { $builderTag = 'Metric'; }
	        if( $ebox->builder_type == 'ACCORDION' ) { $builderTag = 'Accordion'; }
	        if( $ebox->builder_type == 'REUSE' ) { $builderTag = 'Resuable Content'; }
	        if (strpos($ebox->builder_type, 'CONTENT_LINKS') !== false) { $builderTag = 'Content Links'; }

		@endphp
		
		@if( $ebox->builder_type != '' && $ebox->builder_type != '0' && $ebox->position == 'BODY' )
			<div class="ar-order altTop {{ $ebox->builder_type }}_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>{{ $builderTag }}</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="{{ $ebox->builder_type }}">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="{{ $ebox->builder_type }}">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

	@endforeach
@endif
	
	</div>
	<label class="ddm"> <i class="fa fa-level-up" aria-hidden="true"></i> Drag, Drop, Move Body Content</label>
</div>

<div class="col-md-6">
    <div id="pgContentAppendRight" class="dragContainer">
@if( isset($pageBuilderData) && !empty($pageBuilderData) && isset($pageBuilderData->pageBuilderContent) && !empty($pageBuilderData->pageBuilderContent) )
	@foreach($pageBuilderData->pageBuilderContent as $ebox)
		
		@php
			$device = '';
			if( $ebox->device == '1' ) { $device = '<i class="fa fa-2x fa-desktop base-green" aria-hidden="true"></i>'; }
			if( $ebox->device == '2' ) { $device = '<i class="fa fa-2x fa-mobile base-red" aria-hidden="true"></i>'; }
			if( $ebox->device == '3' ) { $device = '<i class="fa fa-2x fa-desktop base-green" aria-hidden="true"></i> <i class="fa fa-2x fa-mobile base-red" aria-hidden="true"></i>'; }

			$builderTag = $ebox->builder_type;

			if( $ebox->builder_type == 'EXTRA_SEO' ) { $builderTag = 'Extra SEO'; }
	    	if( $ebox->builder_type == 'EXTRA_CONT' ) { $builderTag = 'Extra Content '; }
	    	if( $ebox->builder_type == 'CTA' ) { $builderTag = 'CTA Content '; }
	    	if( $ebox->builder_type == 'HERO_SPW' ) { $builderTag = 'Page Width Hero Statement '; }
	    	if( $ebox->builder_type == 'HERO_SCW' ) { $builderTag = 'Container Width Hero Statement '; }
	    	if( $ebox->builder_type == 'STICKY_BUTT' ) { $builderTag = 'Sticky Button Content '; }
	        if( $ebox->builder_type == 'EFORM' ) { $msg = 'Form Content '; }
	        if( $ebox->builder_type == 'IMAGE_CAROUSEL' ) { $builderTag = 'Image Carousel '; }
	        if( $ebox->builder_type == 'IMAGE_GALLERY' ) { $builderTag = 'Image Gallery '; }
	        if( $ebox->builder_type == 'BROCHURE_BUTT' ) { $builderTag = 'Brochure Download Button '; }
	        if( $ebox->builder_type == 'TECHRES_BUTT' ) { $builderTag = 'Technical Resource Button '; }
	        if( $ebox->builder_type == 'IMAGEGAL_BUTT' ) { $builderTag = 'Image Gallery Button '; }
	        if( $ebox->builder_type == 'VIDEO_GALLERY' ) { $builderTag = 'Video Gallery '; }
	        if( $ebox->builder_type == 'PRODUCT_LINKS' ) { $builderTag = 'Product Links '; }
	        if( $ebox->builder_type == 'PRODUCT_CAT_LINKS' ) { $builderTag = 'Product Category Links '; }
	        if( $ebox->builder_type == 'PRODUCT_BOX' ) { $builderTag = 'Product Box '; }
	        if( $ebox->builder_type == 'PRODUCT_CAT_BOX' ) { $builderTag = 'Product Category Box '; }
	        if( $ebox->builder_type == 'NEWS_LINKS' ) { $builderTag = 'News Links '; }
	        if( $ebox->builder_type == 'PEOPLE_LINKS' ) { $builderTag = 'Peoples Links '; }
	        if( $ebox->builder_type == 'CUSTOM_LINKS' ) { $builderTag = 'Custom Links '; }
	        if( $ebox->builder_type == 'METRIC' ) { $builderTag = 'Metric'; }
	        if( $ebox->builder_type == 'ACCORDION' ) { $builderTag = 'Accordion'; }
	        if( $ebox->builder_type == 'REUSE' ) { $builderTag = 'Resuable Content'; }
	        if (strpos($ebox->builder_type, 'CONTENT_LINKS') !== false) { $builderTag = 'Content Links'; }
	        
		@endphp


		@if( $ebox->builder_type != '' && $ebox->builder_type != '0' && $ebox->position == 'RIGHT' )
			<div class="ar-order altTop {{ $ebox->builder_type }}_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>{{ $builderTag }}</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="{{ $ebox->builder_type }}">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="{{ $ebox->builder_type }}">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

	@endforeach
@endif
	</div>
	<label class="ddm"> <i class="fa fa-level-up" aria-hidden="true"></i> Drag, Drop, Move Right Panel Content</label>
</div>


