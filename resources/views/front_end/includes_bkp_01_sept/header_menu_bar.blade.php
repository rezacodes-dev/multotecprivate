@if( isset($mainMenu) )
<div class="row">
    <div id="menuzord" class="menuzord">           
        <ul class="menuzord-menu menuzord-left">
            @foreach( $mainMenu as $mnu )
            <li class="memu-margin">
                @if( $mnu->table_id == '0' )
                    @if( $mnu->table_type == 'MENU_CUSTOM_LINK')
                        @if( $mnu->is_link == '1' && $mnu->custom_link != '' && $mnu->custom_link != null )
                            <a href="{{ $mnu->custom_link }}">{{ ucfirst($mnu->label_txt) }}</a>
                        @else
                            <a href="#">{{ ucfirst($mnu->label_txt) }}</a>
                        @endif
                    @else
                        <a href="#">{{ ucfirst($mnu->label_txt) }}</a>
                    @endif
                @else
                    @php
                    $genurl = '';
                    $linkData = getMenuLink( $mnu->cms_link_id, $mnu->table_type, $mnu->table_id );
                    if( !empty($linkData) ) {
                        $lngcode = getLngCode($linkData->language_id);
                        if( $lngcode != '' && $linkData->slug != '' ) {
                            $genurl = url($lngcode.'/'.$linkData->slug);
                        }
                    }
                    @endphp
                    <a href="{{$genurl}}">{{ ucfirst($mnu->label_txt) }}</a>
                @endif
                @if( isset($mnu->childMenu) && count($mnu->childMenu) > 0 )
                @php $x = 1; @endphp
                <div class="megamenu megamenu-full-width">
                    <div class="container">
                        <div class="megamenu-row">
                            @foreach($mnu->childMenu as $chMnu)
                                @if( $chMnu->lng_id == $currlngid )
                                <div class="col-md-3">
                                    <ul>            
                                        <li>
                                            <span class="mnu-lvf">
                                                @if( $chMnu->table_id == '0' )
                                                    @if( $chMnu->table_type == 'MENU_CUSTOM_LINK')
                                                        @if( $chMnu->is_link == '1' && $chMnu->custom_link != '' && $chMnu->custom_link != null )
                                                            <a href="{{ $chMnu->custom_link }}">{{ ucfirst($chMnu->label_txt) }}</a>
                                                        @else
                                                            <a href="#">{{ ucfirst($chMnu->label_txt) }}</a>
                                                        @endif
                                                    @else
                                                        <a href="#">{{ ucfirst($chMnu->label_txt) }}</a>
                                                    @endif
                                                @else
                                                    @php
                                                    $genurl = '';
                                                    $linkData = getMenuLink( $chMnu->cms_link_id, $chMnu->table_type, $chMnu->table_id );
                                                    if( !empty($linkData) ) {
                                                        $lngcode = getLngCode($linkData->language_id);
                                                        if( $lngcode != '' && $linkData->slug != '' ) {
                                                            $genurl = url($lngcode.'/'.$linkData->slug);
                                                        }
                                                    }
                                                    @endphp
                                                    <a href="{{$genurl}}">{{ ucfirst($chMnu->label_txt) }}</a>
                                                @endif
                                            </span>
                                            @if( isset($chMnu->childMenu) && count($chMnu->childMenu) > 0 )
                                                @include('front_end.includes.render_main_menu', ['menuData' => $chMnu->childMenu])
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                                    @if( $x % 4 == 0 )
                                    <div class="clearfix"></div>
                                    @endif
                                @php $x++; @endphp
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endif