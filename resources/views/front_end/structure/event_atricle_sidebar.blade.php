@if( isset($allData->pageBuilderContent) && !empty($allData->pageBuilderContent) && isset($device) )
    @foreach( $allData->pageBuilderContent as $pgd )
        @if( $pgd->device == $device || $pgd->device == '3' ) <!-- Device Checking -->

            <!-- Quick Body LINKS -->
            @if( ($pgd->builder_type == 'PRODUCT_LINKS' || $pgd->builder_type == 'DISTRIBUTOR' || $pgd->builder_type == 'DISTRIBUTOR_PAGE' || $pgd->builder_type == 'PRODUCT_CAT_LINKS' || $pgd->builder_type == 'PEOPLE_LINKS' || $pgd->builder_type == 'NEWS_LINKS' || $pgd->builder_type == 'CUSTOM_LINKS' || strpos($pgd->builder_type, 'CONTENT_LINKS') !== false) && $pgd->position == 'RIGHT' )

                <div class="midbody_newsblock pgb-links-right">
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
                <div class="clearfix"></div> 
            @endif

        @endif

        @if( $device == '1' ) <!-- Device Checking -->
            <!-- STICKY BUTTON -->
            @if( $pgd->builder_type == 'STICKY_BUTT' && $pgd->position == 'RIGHT' )
                <div class="quote_block" id="sidebar"> 
                    <h2>{{ $pgd->main_title }}<span>{{ $pgd->sub_title }}</span></h2>
                    <div class="buttom-row">
                        <a href="javascript:void(0);" data-toggle="modal" data-target="#desktop_eform_modal" class="submit-btn">{{ $pgd->link_text }}</a> <!-- add class "scroll-btn" -->
                    </div>
                </div>
                <div class="clearfix"></div> 
            @endif
        @endif

    @endforeach
@endif