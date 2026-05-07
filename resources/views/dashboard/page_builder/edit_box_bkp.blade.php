<div class="col-md-6">
    <div id="pgContentAppend" class="dragContainer">
@if( isset($pageBuilderData) && !empty($pageBuilderData) && isset($pageBuilderData->pageBuilderContent) && !empty($pageBuilderData->pageBuilderContent) )
	@foreach($pageBuilderData->pageBuilderContent as $ebox)
		
		@php
			$device = '';
			if( $ebox->device == '1' ) { $device = '<i class="fa fa-2x fa-desktop base-green" aria-hidden="true"></i>'; }
			if( $ebox->device == '2' ) { $device = '<i class="fa fa-2x fa-mobile base-red" aria-hidden="true"></i>'; }
			if( $ebox->device == '3' ) { $device = '<i class="fa fa-2x fa-desktop base-green" aria-hidden="true"></i> <i class="fa fa-2x fa-mobile base-red" aria-hidden="true"></i>'; }
		@endphp
		
		@if( $ebox->builder_type == 'EXTRA_SEO' && $ebox->position == 'BODY' )
			<div class="ar-order altTop extraSEO_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Extra SEO Content</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="EXTRA_SEO">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="EXTRA_SEO">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'EXTRA_CONT' && $ebox->position == 'BODY' )
			<div class="ar-order altTop extraCONT_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Extra Content</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="EXTRA_CONT">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="EXTRA_CONT">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'CTA' && $ebox->position == 'BODY' )
			<div class="ar-order altTop CTA_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>CTA Content</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="CTA">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="CTA">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'STICKY_BUTT' && $ebox->position == 'BODY' )
			<div class="ar-order altTop STICKY_BUTT_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Sticky Button Content</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="STICKY_BUTT">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="STICKY_BUTT">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'HERO_SPW' && $ebox->position == 'BODY' )
			<div class="ar-order altTop HERO_PW_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Page Width Hero Statement</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="HERO_SPW">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="HERO_SPW">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'HERO_SCW' && $ebox->position == 'BODY' )
			<div class="ar-order altTop HERO_CW_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Container Width Hero Statement</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="HERO_SCW">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="HERO_SCW">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'EFORM' && $ebox->position == 'BODY' )
			<div class="ar-order altTop EFORM_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Enquiry Form</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="EFORM">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="EFORM">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'IMAGE_CAROUSEL' && $ebox->position == 'BODY' )
			<div class="ar-order altTop IMAGE_CAROUSEL_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Image Carousel</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="IMAGE_CAROUSEL">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="IMAGE_CAROUSEL">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'BROCHURE_BUTT' && $ebox->position == 'BODY' )
			<div class="ar-order altTop BROCHURE_BUTT_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Brochure Download Button</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="BROCHURE_BUTT">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="BROCHURE_BUTT">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'TECHRES_BUTT' && $ebox->position == 'BODY' )
			<!--div class="ar-order altTop TECHRES_BUTT_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							<strong>Technical Resource Download Button</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="TECHRES_BUTT">
								<i class="fa fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="TECHRES_BUTT">
								<i class="fa fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div-->
		@endif

		@if( $ebox->builder_type == 'IMAGE_GALLERY' && $ebox->position == 'BODY' )
			<div class="ar-order altTop IMAGE_GALLERY_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Image Gallery</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="IMAGE_GALLERY">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="IMAGE_GALLERY">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'IMAGEGAL_BUTT' && $ebox->position == 'BODY' )
			<div class="ar-order altTop IMAGEGAL_BUTT_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Image Gallery Button</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="IMAGEGAL_BUTT">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="IMAGEGAL_BUTT">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'VIDEO_GALLERY' && $ebox->position == 'BODY' )
			<div class="ar-order altTop VIDEO_GALLERY_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Video Gallery</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="VIDEO_GALLERY">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="VIDEO_GALLERY">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'PRODUCT_LINKS' && $ebox->position == 'BODY' )
			<div class="ar-order altTop PRODUCT_LINKS_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Product Links</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="PRODUCT_LINKS">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="PRODUCT_LINKS">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'PRODUCT_BOX' && $ebox->position == 'BODY' )
			<div class="ar-order altTop PRODUCT_BOX_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Product Box</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="PRODUCT_BOX">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="PRODUCT_BOX">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'NEWS_LINKS' && $ebox->position == 'BODY' )
			<div class="ar-order altTop NEWS_LINKS_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>News Links</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="NEWS_LINKS">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="NEWS_LINKS">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif


		@if( $ebox->builder_type == 'PEOPLE_LINKS' && $ebox->position == 'BODY' )
			<div class="ar-order altTop PEOPLE_LINKS_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Peoples Profile Links</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="PEOPLE_LINKS">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="PEOPLE_LINKS">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'PRODUCT_CAT_LINKS' && $ebox->position == 'BODY' )
			<div class="ar-order altTop PRODUCT_CAT_LINKS_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Product Category Links</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="PRODUCT_CAT_LINKS">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="PRODUCT_CAT_LINKS">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'DISTRIBUTOR' && $ebox->position == 'BODY' )
			<div class="ar-order altTop DISTRIBUTOR_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Distributor Links</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="DISTRIBUTOR">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="DISTRIBUTOR">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'DISTRIBUTOR_PAGE' && $ebox->position == 'BODY' )
			<div class="ar-order altTop DISTRIBUTOR_PAGE_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Distributor Content Links</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="DISTRIBUTOR_PAGE">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="DISTRIBUTOR_PAGE">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( strpos($ebox->builder_type, 'CONTENT_LINKS') !== false && $ebox->position == 'BODY' )
			<div class="ar-order altTop {{ $ebox->builder_type }}_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Content Links</strong>
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

		@if( $ebox->builder_type == 'CUSTOM_LINKS' && $ebox->position == 'BODY' )
			<div class="ar-order altTop CUSTOM_LINKS_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Custom Links</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="CUSTOM_LINKS">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="CUSTOM_LINKS">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'METRIC' && $ebox->position == 'BODY' )
			<div class="ar-order altTop METRIC_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Metric - {{ $ebox->sub_content }}</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="METRIC">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="METRIC">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'ACCORDION' && $ebox->position == 'BODY' )
			<div class="ar-order altTop ACCORDION_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Accordion</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="ACCORDION">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="ACCORDION">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'REUSE' && $ebox->position == 'BODY' )
			<div class="ar-order altTop REUSE_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Reusable Content</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="REUSE">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="REUSE">
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
		
		@if( $ebox->builder_type == 'EXTRA_SEO' && $ebox->position == 'RIGHT' )
			<div class="ar-order altTop extraSEO_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Extra SEO Content</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="EXTRA_SEO">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="EXTRA_SEO">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'EXTRA_CONT' && $ebox->position == 'RIGHT' )
			<div class="ar-order altTop extraCONT_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Extra Content</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="EXTRA_CONT">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="EXTRA_CONT">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'CTA' && $ebox->position == 'RIGHT' )
			<div class="ar-order altTop CTA_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>CTA Content</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="CTA">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="CTA">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'STICKY_BUTT' && $ebox->position == 'RIGHT' )
			<div class="ar-order altTop STICKY_BUTT_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Sticky Button Content</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="STICKY_BUTT">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="STICKY_BUTT">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'HERO_SPW' && $ebox->position == 'RIGHT' )
			<div class="ar-order altTop HERO_PW_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Page Width Hero Statement</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="HERO_SPW">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="HERO_SPW">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'HERO_SCW' && $ebox->position == 'RIGHT' )
			<div class="ar-order altTop HERO_CW_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Container Width Hero Statement</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="HERO_SCW">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="HERO_SCW">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'EFORM' && $ebox->position == 'RIGHT' )
			<div class="ar-order altTop EFORM_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Enquiry Form</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="EFORM">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="EFORM">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'IMAGE_CAROUSEL' && $ebox->position == 'RIGHT' )
			<div class="ar-order altTop IMAGE_CAROUSEL_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Image Carousel</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="IMAGE_CAROUSEL">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="IMAGE_CAROUSEL">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'BROCHURE_BUTT' && $ebox->position == 'RIGHT' )
			<div class="ar-order altTop BROCHURE_BUTT_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							{!! html_entity_decode($device) !!} <strong>Brochure Download Button</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="BROCHURE_BUTT">
								<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="BROCHURE_BUTT">
								<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div>
		@endif

		@if( $ebox->builder_type == 'TECHRES_BUTT' && $ebox->position == 'RIGHT' )
			<!--div class="ar-order altTop TECHRES_BUTT_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
				<div class="notice notice-info">
					<div class="row">
						<div class="col-md-8">
							<strong>Technical Resource Download Button</strong>
						</div>
						<div class="col-md-4 txtrit">
							<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="TECHRES_BUTT">
								<i class="fa fa-pencil base-green" aria-hidden="true"></i></a>
							&nbsp;&nbsp;
							<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="TECHRES_BUTT">
								<i class="fa fa-trash-o base-red" aria-hidden="true"></i></a>
						</div>
					</div>
				</div>
			</div-->
		@endif

		@if( $ebox->builder_type == 'IMAGE_GALLERY' && $ebox->position == 'RIGHT' )
		<div class="ar-order altTop IMAGE_GALLERY_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
			<div class="notice notice-info">
				<div class="row">
					<div class="col-md-8">
						{!! html_entity_decode($device) !!} <strong>Image Gallery</strong>
					</div>
					<div class="col-md-4 txtrit">
						<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="IMAGE_GALLERY">
							<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
						&nbsp;&nbsp;
						<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="IMAGE_GALLERY">
							<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
					</div>
				</div>
			</div>
		</div>
		@endif

		@if( $ebox->builder_type == 'IMAGEGAL_BUTT' && $ebox->position == 'RIGHT' )
		<div class="ar-order altTop IMAGEGAL_BUTT_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
			<div class="notice notice-info">
				<div class="row">
					<div class="col-md-8">
						{!! html_entity_decode($device) !!} <strong>Image Gallery Button</strong>
					</div>
					<div class="col-md-4 txtrit">
						<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="IMAGEGAL_BUTT">
							<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
						&nbsp;&nbsp;
						<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="IMAGEGAL_BUTT">
							<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
					</div>
				</div>
			</div>
		</div>
		@endif

		@if( $ebox->builder_type == 'VIDEO_GALLERY' && $ebox->position == 'RIGHT' )
		<div class="ar-order altTop VIDEO_GALLERY_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
			<div class="notice notice-info">
				<div class="row">
					<div class="col-md-8">
						{!! html_entity_decode($device) !!} <strong>Video Gallery</strong>
					</div>
					<div class="col-md-4 txtrit">
						<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="VIDEO_GALLERY">
							<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
						&nbsp;&nbsp;
						<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="VIDEO_GALLERY">
							<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
					</div>
				</div>
			</div>
		</div>
		@endif

		@if( $ebox->builder_type == 'PRODUCT_LINKS' && $ebox->position == 'RIGHT' )
		<div class="ar-order altTop PRODUCT_LINKS_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
			<div class="notice notice-info">
				<div class="row">
					<div class="col-md-8">
						{!! html_entity_decode($device) !!} <strong>Product Links</strong>
					</div>
					<div class="col-md-4 txtrit">
						<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="PRODUCT_LINKS">
							<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
						&nbsp;&nbsp;
						<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="PRODUCT_LINKS">
							<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
					</div>
				</div>
			</div>
		</div>
		@endif


		@if( $ebox->builder_type == 'PRODUCT_CAT_LINKS' && $ebox->position == 'RIGHT' )
		<div class="ar-order altTop PRODUCT_CAT_LINKS_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
			<div class="notice notice-info">
				<div class="row">
					<div class="col-md-8">
						{!! html_entity_decode($device) !!} <strong>Product Category Links</strong>
					</div>
					<div class="col-md-4 txtrit">
						<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="PRODUCT_CAT_LINKS">
							<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
						&nbsp;&nbsp;
						<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="PRODUCT_CAT_LINKS">
							<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
					</div>
				</div>
			</div>
		</div>
		@endif


		@if( $ebox->builder_type == 'PRODUCT_BOX' && $ebox->position == 'RIGHT' )
		<div class="ar-order altTop PRODUCT_BOX_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
			<div class="notice notice-info">
				<div class="row">
					<div class="col-md-8">
						{!! html_entity_decode($device) !!} <strong>Product Box</strong>
					</div>
					<div class="col-md-4 txtrit">
						<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="PRODUCT_BOX">
							<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
						&nbsp;&nbsp;
						<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="PRODUCT_BOX">
							<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
					</div>
				</div>
			</div>
		</div>
		@endif

		@if( $ebox->builder_type == 'NEWS_LINKS' && $ebox->position == 'RIGHT' )
		<div class="ar-order altTop NEWS_LINKS_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
			<div class="notice notice-info">
				<div class="row">
					<div class="col-md-8">
						{!! html_entity_decode($device) !!} <strong>News Links</strong>
					</div>
					<div class="col-md-4 txtrit">
						<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="NEWS_LINKS">
							<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
						&nbsp;&nbsp;
						<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="NEWS_LINKS">
							<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
					</div>
				</div>
			</div>
		</div>
		@endif


		@if( $ebox->builder_type == 'PEOPLE_LINKS' && $ebox->position == 'RIGHT' )
		<div class="ar-order altTop PEOPLE_LINKS_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
			<div class="notice notice-info">
				<div class="row">
					<div class="col-md-8">
						{!! html_entity_decode($device) !!} <strong>Peoples Profile Links</strong>
					</div>
					<div class="col-md-4 txtrit">
						<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="PEOPLE_LINKS">
							<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
						&nbsp;&nbsp;
						<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="PEOPLE_LINKS">
							<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
					</div>
				</div>
			</div>
		</div>
		@endif

		@if( $ebox->builder_type == 'CUSTOM_LINKS' && $ebox->position == 'RIGHT' )
		<div class="ar-order altTop CUSTOM_LINKS_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
			<div class="notice notice-info">
				<div class="row">
					<div class="col-md-8">
						{!! html_entity_decode($device) !!} <strong>Custom Links</strong>
					</div>
					<div class="col-md-4 txtrit">
						<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="CUSTOM_LINKS">
							<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
						&nbsp;&nbsp;
						<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="CUSTOM_LINKS">
							<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
					</div>
				</div>
			</div>
		</div>
		@endif

		@if( $ebox->builder_type == 'DISTRIBUTOR' && $ebox->position == 'RIGHT' )
		<div class="ar-order altTop DISTRIBUTOR_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
			<div class="notice notice-info">
				<div class="row">
					<div class="col-md-8">
						{!! html_entity_decode($device) !!} <strong>Distributor Links</strong>
					</div>
					<div class="col-md-4 txtrit">
						<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="DISTRIBUTOR">
							<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
						&nbsp;&nbsp;
						<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="DISTRIBUTOR">
							<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
					</div>
				</div>
			</div>
		</div>
		@endif

		@if( $ebox->builder_type == 'DISTRIBUTOR_PAGE' && $ebox->position == 'RIGHT' )
		<div class="ar-order altTop DISTRIBUTOR_PAGE_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
			<div class="notice notice-info">
				<div class="row">
					<div class="col-md-8">
						{!! html_entity_decode($device) !!} <strong>Distributor Content Links</strong>
					</div>
					<div class="col-md-4 txtrit">
						<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="DISTRIBUTOR_PAGE">
							<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
						&nbsp;&nbsp;
						<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="DISTRIBUTOR_PAGE">
							<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
					</div>
				</div>
			</div>
		</div>
		@endif

		@if( strpos($ebox->builder_type, 'CONTENT_LINKS') !== false && $ebox->position == 'RIGHT' )
		<div class="ar-order altTop {{ $ebox->builder_type }}_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
			<div class="notice notice-info">
				<div class="row">
					<div class="col-md-8">
						{!! html_entity_decode($device) !!} <strong>Content Links</strong>
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

		@if( $ebox->builder_type == 'METRIC' && $ebox->position == 'RIGHT' )
		<div class="ar-order altTop METRIC_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
			<div class="notice notice-info">
				<div class="row">
					<div class="col-md-8">
						{!! html_entity_decode($device) !!} <strong>Metric - {{ $ebox->sub_content }}</strong>
					</div>
					<div class="col-md-4 txtrit">
						<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="METRIC">
							<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
						&nbsp;&nbsp;
						<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="METRIC">
							<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
					</div>
				</div>
			</div>
		</div>
		@endif

		@if( $ebox->builder_type == 'ACCORDION' && $ebox->position == 'RIGHT' )
		<div class="ar-order altTop ACCORDION_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
			<div class="notice notice-info">
				<div class="row">
					<div class="col-md-8">
						{!! html_entity_decode($device) !!} <strong>Accordion</strong>
					</div>
					<div class="col-md-4 txtrit">
						<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="ACCORDION">
							<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
						&nbsp;&nbsp;
						<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="ACCORDION">
							<i class="fa fa-2x fa-trash-o base-red" aria-hidden="true"></i></a>
					</div>
				</div>
			</div>
		</div>
		@endif

		@if( $ebox->builder_type == 'REUSE' && $ebox->position == 'RIGHT' )
		<div class="ar-order altTop REUSE_holder_{{ $ebox->id }}" id="{{ $ebox->id }}">
			<div class="notice notice-info">
				<div class="row">
					<div class="col-md-8">
						{!! html_entity_decode($device) !!} <strong>Reusable Content</strong>
					</div>
					<div class="col-md-4 txtrit">
						<a href="javascript:void(0);" class="pgb_edt" id="{{ $ebox->id }}" data="REUSE">
							<i class="fa fa-2x fa-pencil base-green" aria-hidden="true"></i></a>
						&nbsp;&nbsp;
						<a href="javascript:void(0);" class="pgb_del" id="{{ $ebox->id }}" data="REUSE">
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


