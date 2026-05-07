@if( isset($footerMenu) && count($footerMenu) > 0 )
<div class="foot_text_links">
    <div class="container">
        <div class="row">
            <div class="col-sm-9">
                <ul>
                    @foreach( $footerMenu as $fm )
                        @php
                        $linkData = '';
                        if( $fm->table_id == '0' ) {
                            if( $fm->table_type == 'MENU_CUSTOM_LINK') {
                                if( $fm->is_link == '1' && $fm->custom_link != '' && $fm->custom_link != null ) {
                                    $linkData = $fm->custom_link;
                                } else {
                                    $linkData = '#';
                                }
                            } else {
                                $linkData = '#';
                            }
                        } else {
                            $linkData = getMenuLink( $fm->cms_link_id, $fm->table_type, $fm->table_id );
                        }
                        @endphp
                    <li>
                        <a href="{{ $linkData }}">{{ ucfirst($fm->label_txt) }}</a>
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