@if( isset($footerMenu) && count($footerMenu) > 0 )
<div class="foot_text_links">
    <div class="container">
        <div class="row">
            <div class="col-sm-9">
                <ul>
                    @foreach( $footerMenu as $fm )
                        @php
                        $genurl = '';
                        if( $fm->table_id == '0' ) {
                            if( $fm->table_type == 'MENU_CUSTOM_LINK') {
                                if( $fm->is_link == '1' && $fm->custom_link != '' && $fm->custom_link != null ) {
                                    $genurl = $fm->custom_link;
                                } else {
                                    $genurl = '#';
                                }
                            } else {
                                $genurl = '#';
                            }
                        } else {
                            $linkData = getMenuLink( $fm->cms_link_id, $fm->table_type, $fm->table_id );
                            if( !empty($linkData) ) {
                                $lngcode = getLngCode($linkData->language_id);
                                if( $lngcode != '' && $linkData->slug != '' ) {
                                    $genurl = url($lngcode.'/'.$linkData->slug);
                                }
                            }
                        }
                        @endphp
                    <li>
                        <a href="{{$genurl}}">{{ ucfirst($fm->label_txt) }}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-sm-3">
                <p class="copyright">@if( !empty(getGeneralSettings()) ){{ getGeneralSettings()->site_footer_text }}@endif</p>
            </div>
        </div>
    </div>
</div>
@endif