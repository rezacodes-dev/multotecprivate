@if( isset($menuData) )
	<ul>
		@foreach( $menuData as $mmd )
        @if( $mmd->lng_id == $currlngid )
		<li>
            <span>
            	@if( $mmd->table_id == '0' )
                    @if( $mmd->table_type == 'MENU_CUSTOM_LINK')
                        @if( $mmd->is_link == '1' && $mmd->custom_link != '' && $mmd->custom_link != null )
                            <a href="{{ $mmd->custom_link }}">{{ ucfirst($mmd->label_txt) }}</a>
                        @else
                            <a href="javascript:void(0);">{{ ucfirst($mmd->label_txt) }}</a>
                        @endif
                    @else
                        <a href="#">{{ ucfirst($mmd->label_txt) }}</a>
                    @endif
                @else
                    @php
                    $genurl = '';
                    $linkData = getMenuLink( $mmd->cms_link_id, $mmd->table_type, $mmd->table_id );
                    if( !empty($linkData) ) {
                        $lngcode = getLngCode($linkData->language_id);
                        if( $lngcode != '' && $linkData->slug != '' ) {
                            $genurl = url($lngcode.'/'.$linkData->slug);
                        }
                    }
                    @endphp
                    <a href="{{$genurl}}">{{ ucfirst($mmd->label_txt) }}</a>
                @endif
            </span>
            @if( isset($mmd->childMenu) && count($mmd->childMenu) > 0 )
                @include('front_end.includes.render_main_menu', ['menuData' => $mmd->childMenu])
            @endif
        </li>
        @endif
		@endforeach
	</ul>
@endif