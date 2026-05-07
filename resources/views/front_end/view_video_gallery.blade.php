@extends('front_end.layout.layout_master')
@include('front_end.structure.page_meta')


@push('page_css')
    <style>
        .close-vid {
            position: absolute;
            right: 0;
            border-radius: 50%;
            width: 30px;
            background: #333;
            color: #FFF;
            margin: -12px -15px 0 0;
            border: none;
            height: 29px;
        }
    </style>
@endpush

@section('page_content')
<section class="container">
    <div class="breadcrumb2">
        <ul>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li><a
                    href="{{ route('vid_gal_cats', array('lng' => $lng)) }}">Gallery</a>
            </li>
            @if( isset($breadcrumb_cat_name) && isset($breadcrumb_cat_slug) )
                <li>
                    <a
                        href="{{ route('front_galVSubCat', array('lng' => $lng, 'category' => $breadcrumb_cat_slug)) }}">{{ $breadcrumb_cat_name }}</a>
                </li>
            @endif
            @if( isset($breadcrumb_subcat_name) && isset($breadcrumb_subcat_slug) )
                <li class="active">
                    {{ $breadcrumb_subcat_name }}
                </li>
            @endif
        </ul>
    </div>

    <div class="midblock">
        <div class="row">
            <div class="col-sm-7">
                <h1>@if(isset($catName)){{ $catName }}@endif</h1>
            </div>
            <div class="col-sm-2"></div>
            <div class="col-sm-3">
                <div class="search"><input type="text" class="form-control" placeholder="Search"><button type="submit"
                        value=""><i class="fa fa-search" aria-hidden="true"></i></button></div>
            </div>
            <!--div class="col-sm-2"><button type="button" value="" class="slideshow_btn">View Slideshow</button></div-->
        </div>

        <p>@if( isset($page_data) ){!! html_entity_decode( $page_data->page_content, ENT_QUOTES) !!}@endif</p>


        <div class="row artab">
            <div class="col-sm-6 tab @if(isset($tab_tag) && $tab_tag == 'image_gallery') active @endif">
                <a
                    href="{{ route('img_gal_cats', array('lng' => $lng)) }}">Photo
                    Gallery</a></div>
            <div class="col-sm-6 tab @if(isset($tab_tag) && $tab_tag == 'video_gallery') active @endif">
                <a
                    href="{{ route('vid_gal_cats', array('lng' => $lng)) }}">Video
                    Gallery</a></div>
        </div>



        @if( isset($catSlug) && isset($catName) )
            <div class="tab_info_list">
                <div class="row">
                    {{--<div class="col-sm-4">
            <ul><li>
                <a href="{{ route('front_galVSubCat', array('lng' => $lng, 'catSlug' => $catSlug)) }}">{{ $catName }}</a>
                    </li>
                    </ul>
                </div>--}}
                @if( isset($vidSubCategories) && count($vidSubCategories) > 0 )
                    @foreach( $vidSubCategories as $vsc )
                        <div class="col-sm-4">
                            <ul>
                                <li><a
                                        href="{{ route('front_galVSubCat', array('lng' => $lng, 'category' => $catSlug, 'subcategory' => $vsc->slug)) }}">{{ $vsc->name }}</a>
                                </li>
                            </ul>
                        </div>
                    @endforeach
                @endif
            </div>
    </div>
    @endif

    @if( isset($viewVideos) )
        <div class="gallery">
            <div class="row">
                @foreach( $viewVideos as $vid )
                    <div class="col-sm-4">
                        <div class="video-thumb">
                            <div class="videooverlay yvid" id="{{ $vid->video_link }}"
                                data="{{ $vid->video_caption }}"></div>
                            <div class="imgxfix">
                                <a href="javascript:void(0);" class="yvid" id="{{ $vid->video_link }}"
                                    data="{{ $vid->video_caption }}">
                                    <img src="https://img.youtube.com/vi/{{ $vid->video_link }}/hqdefault.jpg"
                                        title="{{ $vid->title }}" />
                                </a>
                            </div>
                            <h5>{{ str_limit( $vid->video_caption, 60 ) }}</h5>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="prev_next_btn">
                <blade
                    if|(%20%24viewVideos-%3EpreviousPageUrl()%20!%3D%20%26%2339%3B%26%2339%3B%20)%20%3Ca%20href%3D%26%2334%3B%7B%7B%2524viewVideos-%253EpreviousPageUrl()%7D%7D%26%2334%3B%3E%20%3C%20Prev%20%20%3C%2Fa%3E%20%40endif%0D>
                    <blade
                        if|(%20%24viewVideos-%3EnextPageUrl()%20!%3D%20%26%2339%3B%26%2339%3B%20)%20%3Ca%20href%3D%26%2334%3B%7B%7B%2524viewVideos-%253EnextPageUrl()%7D%7D%26%2334%3B%3E%20Next%20%3E%20%3C%2Fa%3E%20%40endif%20%0D>
            </div>
        </div>
    @endif

    {{--<div class="btm_more_sec">
<p>@if( isset($page_data) ){!! html_entity_decode( $page_data->description, ENT_QUOTES ) !!}@endif</p>
</div>--}}

</section>

<!-- Modal -->
<div id="vidModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <button type="button" class="close-vid" data-dismiss="modal">&times;</button>

            <div class="modal-body" id="vidBox">

            </div>
            <div class="modal-footer" id="vidCap" style="text-align: left;">

            </div>
        </div>

    </div>
</div>
@endsection




@push('page_js')
    <script type="text/javascript">
        $(function () {
            $('.yvid').on('click', function () {
                var vPlayer = '<iframe width="100%" height="315" src="https://www.youtube.com/embed/' +
                    $(this).attr('id') + '?autoplay=1"></iframe>';
                $('#vidBox').html(vPlayer);
                $('#vidCap').html($(this).attr('data'));
                $('#vidModal').modal();
            });
        });
    </script>
@endpush