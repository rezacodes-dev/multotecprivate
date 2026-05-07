@extends('front_end.layout.layout_master')
@include('front_end.structure.page_meta')


@push('page_css')
<link rel="stylesheet" href="{{ asset('public/front_end/css/jquery.tabs.css') }}">
@endpush




@section('page_content')

<section class="innerpage-banner">
    @if( isset($extraContent) && $extraContent->image_id != '' && isset($extraContent->imageInfo) )
        <img src="{{ asset('public/uploads/files/media_images/'.$extraContent->imageInfo->image) }}" title="{{ $extraContent->image_title }}" alt="{{ $extraContent->image_alt }}" caption="{{ $extraContent->image_caption }}">
    @else
        <img src="{{ asset('public/front_end/images/banner1.jpg') }}" alt="">
    @endif
</section>


<section class="container">
    <div class="breadcrumb">
        <ul>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li class="active">Technical Resources</li>
        </ul>
    </div>

<div class="midblock">
    <h1>@if(isset($extraContent)){{ $extraContent->title }}@endif</h1>
    <p>@if(isset($extraContent)){!! html_entity_decode($extraContent->page_content, ENT_QUOTES) !!}@endif</p>
    <div class="tabblock_2">
        <div class="jq-tab-wrapper" id="horizontalTab">
            <div class="jq-tab-menu">
                <div class="jq-tab-title artab active" data-tab="1" id="PRODUCT">Products</div>
                <div class="jq-tab-title artab" data-tab="2" id="MULTOTEC_GROUP">Multotec Group</div>
                <div class="jq-tab-title artab" data-tab="3" id="INDUSTRY_INSIGHTS">Industry Insights</div>
                <input type="hidden" id="seleTab" value="PRODUCT">
            </div>
            <div class="jq-tab-content-wrapper">
                <div class="jq-tab-content active" data-tab="1">
                
                <div class="tab_info_list">
                    <div class="row">
                    @php
                    $arr1 = array();
                    @endphp

                    @if( isset($allData) && !empty($allData) )
                            @foreach( $allData as $ct )
                                @if( $ct->tab_section == 'PRODUCT' && isset($ct->procatIds) && count($ct->procatIds) > 0  && isset($ct->FileIds) && count($ct->FileIds) > 0 )
                                    @foreach($ct->procatIds as $pc) <!-- as relation hasMany -->
                                        @if( isset($pc->procatInfo) && !in_array($pc->procatInfo->id, $arr1))
                                        <div class="col-sm-4">
                                            <ul>
                                                <li class="artabli">
                                                    <a href="javascript:void(0);" class="artkpc" id="{{ $pc->procatInfo->id }}">{{ $pc->procatInfo->name }}</a>
                                                </li>
                                            </ul>
                                        </div>
                                        @php array_push($arr1, $pc->procatInfo->id); @endphp
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
                </div>    
                <div class="jq-tab-content" data-tab="2">
                    <div class="tab_info_list">
                        <div class="row">
                        @php
                        $arr2 = array();
                        @endphp

                        @if( isset($allData) && !empty($allData) )
                            @foreach( $allData as $ct )
                                @if( $ct->tab_section == 'MULTOTEC_GROUP' && isset($ct->procatIds) && count($ct->procatIds) > 0  && isset($ct->FileIds) && count($ct->FileIds) > 0 )
                                    @foreach($ct->procatIds as $pc) <!-- as relation hasMany -->
                                        @if( isset($pc->procatInfo) && !in_array($pc->procatInfo->id, $arr2))
                                        <div class="col-sm-4">
                                            <ul>
                                                <li class="artabli">
                                                    <a href="javascript:void(0);" class="artkpc" id="{{ $pc->procatInfo->id }}">{{ $pc->procatInfo->name }}</a>
                                                </li>
                                            </ul>
                                        </div>
                                        @php array_push($arr2, $pc->procatInfo->id); @endphp
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                        </div>
                    </div>
                </div>
                <div class="jq-tab-content" data-tab="3">
                    <div class="tab_info_list">
                        <div class="row">
                        @php
                        $arr3 = array();
                        @endphp

                        @if( isset($allData) && !empty($allData) )
                            @foreach( $allData as $ct )
                                @if( $ct->tab_section == 'INDUSTRY_INSIGHTS' && isset($ct->procatIds) && count($ct->procatIds) > 0  && isset($ct->FileIds) && count($ct->FileIds) > 0 )
                                    @foreach($ct->procatIds as $pc) <!-- as relation hasMany -->
                                        @if( isset($pc->procatInfo) && !in_array($pc->procatInfo->id, $arr3))
                                        <div class="col-sm-4">
                                            <ul>
                                                <li class="artabli">
                                                    <a href="javascript:void(0);" class="artkpc" id="{{ $pc->procatInfo->id }}">{{ $pc->procatInfo->name }}</a>
                                                </li>
                                            </ul>
                                        </div>
                                        @php array_push($arr3, $pc->procatInfo->id); @endphp
                                        @endif
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



<div id="showTechResFiles">

    <div class="row">
        <div class="col-sm-4"><h2 style="margin-top:20px;">Latest Additions</h2></div>
        <div class="col-sm-4">
            @if( isset($resFiles) )
            <nav aria-label="Page navigation" style="text-align: center;">
              <ul class="pagination">
                @if( $resFiles->previousPageUrl() != '' )
                <li class="page-item">
                    <a class="page-link" href="{{ $resFiles->previousPageUrl() }}">Prev</a>
                </li>
                @endif
                @if( $resFiles->nextPageUrl() != '' )
                <li class="page-item">
                    <a class="page-link" href="{{ $resFiles->nextPageUrl() }}">Next</a>
                </li>
                @endif
              </ul>
            </nav>
            @endif
        </div>
        <div class="col-sm-4">
            <div class="search">
                <input type="text" id="artksrcVal" class="form-control" placeholder="Search">
                <button type="button" id="artksrc" value=""><i class="fa fa-search" aria-hidden="true"></i></button>
            </div>    
        </div>
    </div>

    <div class="additions_block">
        @php $break = 1; @endphp
        @if( isset($resFiles) && count($resFiles) > 0 )
            @foreach( $resFiles as $v )
                @if( isset($v->FileIds) && count($v->FileIds) > 0 )
                <div class="block_thumb">
                    <div class="image" style="width: 180px;">
                        @if( isset($v) && isset($v->ImageIds) && count($v->ImageIds) > 0 )
                        @php $i = 0; @endphp
                        @foreach( $v->ImageIds as $imgs )
                        @if( $imgs->image_type == 'MAIN_IMAGE' )
                          @if( isset($imgs->imageInfo) && $i == 0 )
                          <img src="{{ asset('public/uploads/files/media_images/'.$imgs->imageInfo->image) }}" style="width: 100%;" title="{{ $imgs->title }}" alt="{{ $imgs->alt_tag }}" caption="{{ $imgs->caption }}">
                          @php $i++; @endphp
                          @endif
                        @endif
                        @endforeach
                      @endif
                      @if($v->publish_date != '')<p>{{ date('d F Y', strtotime( $v->publish_date ) ) }}</p>@endif
                    </div>
                    <div class="content_sec">
                        <h5>{{ $v->name }}</h5>
                        <p>
                            {!! str_limit(html_entity_decode( $v->description ), 120, '...') !!}

                            @if( isset($v) && isset($v->FileIds) && count($v->FileIds) > 0 )
                                @php $i = 0; @endphp
                                @foreach( $v->FileIds as $fils )
                                    @if( $fils->file_type == 'MAIN_FILE' )
                                        @if( isset($fils->fileInfo) && $i == 0 )
                                        <br/><a href="{{ asset('public/uploads/files/media_files/'. $fils->fileInfo->file) }}" target="_blank">
                                        Read More</a>
                                        @php $i++; @endphp
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                        </p>
                    </div>
                </div>
                @if( $break % 2 == 0 )
                <div class="clearfix"></div>
                @endif
                @php $break++; @endphp
                @endif
            @endforeach
        @else
            <h5 style="font-weight: normal; color: #ccc;">Sorry! No Record Founds</h5>
        @endif
        <div class="clearfix"></div>
    </div>

</div>



</div>

</section>
@endsection




@push('page_js')
<script type="text/javascript">

$(window).scroll(function() {
    if ($(this).scrollTop()) {
        $('#toTop').fadeIn();
    } else {
        $('#toTop').fadeOut();
    }
});

$("#toTop").click(function() {
    $("html, body").animate({scrollTop: 0}, 1000);
 });
 

// *********** Footer menu ********** //
function toggle1() {
    var ele = document.getElementById("service");
    var text = document.getElementById("displayservice");
    $("#service").slideToggle(400);
}
</script>



<script src="{{ asset('public/front_end/js/jquery.tabs.min.js') }}"></script>
<script>
    $(function () {
       // $('#verticalTab').jqTabs();
        $('#horizontalTab').jqTabs({direction: 'horizontal', duration: 200});
    });
</script>


<script type="text/javascript">
$( function() { 
    $('body').on('click', '.artkpc', function() { 
        
        var pcid = $(this).attr('id');
        
        var seleTab = $.trim($('#seleTab').val());

        if( pcid != '' ) {
            $.ajax({
                type : "POST",
                url : "{{ route('ajxTechResLst', array('lng' => 'en')) }}",
                data : {
                    "pcid" : pcid,
                    "seletab" : seleTab,
                    "_token" : "{{ csrf_token() }}"
                },
                cache : false,
                beforeSend : function() {

                    $('#showTechResFiles').html('<div style="text-align: center; margin-top: 40px; height: 300px;"><h3 style="font-size: 16px; font-weight: 400;"><i class="fa fa-spinner fa-spin fa-2x"></i></h3>Please wait...</div>');
                },
                success : function(ajxData) {
                    if( ajxData.status == 'ok' ) {

                        $('#showTechResFiles').html(ajxData.html_view);   
                    }
                }
            });
        }
    } );

    $('body').on('click', '#artksrc', function() {
        if( $.trim($('#artksrcVal').val()) != '' ) {
            $.ajax({
                type : "POST",
                url : "{{ route('ajxTechResSrc', array('lng' => 'en')) }}",
                data : {
                    "search" : $('#artksrcVal').val(),
                    "_token" : "{{ csrf_token() }}"
                },
                cache : false,
                beforeSend : function() {

                    $('#showTechResFiles').html('<div style="text-align: center; margin-top: 40px; height: 300px;"><h3 style="font-size: 16px; font-weight: 400;"><i class="fa fa-spinner fa-spin fa-2x"></i></h3>Please wait...</div>');
                },
                success : function(ajxData) {
                    if( ajxData.status == 'ok' ) {

                        $('#showTechResFiles').html(ajxData.html_view);   
                    }
                }
            });
        } 
    } );

    $('body').on('click', '.artab', function() {
        $('#seleTab').val( $.trim($(this).attr('id')) ); 
        var seleTab = $.trim($(this).attr('id'));
        $.ajax({
            type : "POST",
            url : "{{ route('ajxTechResTab', array('lng' => 'en')) }}",
            data : {
                "seletab" : seleTab,
                "_token" : "{{ csrf_token() }}"
            },
            cache : false,
            beforeSend : function() {

                $('#showTechResFiles').html('<div style="text-align: center; margin-top: 40px; height: 300px;"><h3 style="font-size: 16px; font-weight: 400;"><i class="fa fa-spinner fa-spin fa-2x"></i></h3>Please wait...</div>');
            },
            success : function(ajxData) {
                if( ajxData.status == 'ok' ) {

                    $('#showTechResFiles').html(ajxData.html_view);   
                }
            }
        })
    } );
} );
</script>
@endpush

    