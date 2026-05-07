@if( isset($stickyFooter) && count($stickyFooter) > 0 )
<div class="footer-stick-menu">
<a href="javascript:toggle1();"><i class="fa fa-external-link" aria-hidden="true"></i></a>
<div id="service" style="display:none;" class="footer-service"> 
<div class="container">
<div class="row">

@php $z = 1; @endphp

@foreach( $stickyFooter as $stf )
<div class="col-sm-3">
	@if( $stf->table_id == '0' )
		@if( $stf->table_type == 'MENU_CUSTOM_LINK')
			@if( $stf->is_link == '1' && $stf->custom_link != '' && $stf->custom_link != null )
				<h4><a href="{{ $stf->custom_link }}">{{ ucfirst($stf->label_txt) }}</a></h4>
			@else
				<h4><a href="#">{{ ucfirst($stf->label_txt) }}</a></h4>
			@endif
		@else
			<h4><a href="#">{{ ucfirst($stf->label_txt) }}</a></h4>
		@endif
	@else
		@php
        $linkData = getMenuLink( $stf->cms_link_id, $stf->table_type, $stf->table_id );
        @endphp
        <h4><a href="{{ $linkData }}">{{ ucfirst($stf->label_txt) }}</a></h4>
	@endif
	
	@if( isset($stf->childMenu) && count($stf->childMenu) > 0 )      
		<ul>
			@foreach($stf->childMenu as $chStf)
				@if( $chStf->table_id == '0' )
					@if( $chStf->table_type == 'MENU_CUSTOM_LINK')
						@if( $chStf->is_link == '1' && $chStf->custom_link != '' && $chStf->custom_link != null )
							<li><a href="{{ $chStf->custom_link }}">{{ ucfirst($chStf->label_txt) }}</a></li>
						@else
							<li><a href="#">{{ ucfirst($chStf->label_txt) }}</a></li>
						@endif
					@else
						<li><a href="#">{{ ucfirst($chStf->label_txt) }}</a></li>
					@endif
				@else
					@php
			        $linkData = getMenuLink( $chStf->cms_link_id, $chStf->table_type, $chStf->table_id );
			        @endphp
			        <li><a href="{{ $linkData }}">{{ ucfirst($chStf->label_txt) }}</a></li>
				@endif
			@endforeach
		</ul>
	@endif
</div>

@if( $z % 4 == 0 )
<div class="clearfix"></div>
@endif

@endforeach

</div>
</div>
</div>
</div>
@endif