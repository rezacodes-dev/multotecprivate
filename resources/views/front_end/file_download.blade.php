@extends('front_end.layout.layout_master')
@include('front_end.structure.page_meta')


@push('page_css')
<link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
@endpush

@section('page_content')
<section class="container">
    <div class="breadcrumb">
        <ul style="margin-top: 40px;">
         
         <li><a href="{{ url('/') }}">Home</a></li>
         
         <li><a href="{{ route('front_allFileCat', array('lng' => $lng)) }}">Product Brochures & Files</a></li>
         
         @if( isset($breadcrumb_cat_name) && isset($breadcrumb_cat_slug) )
         <li>
         <a href="{{ route('front_fileSubCat', array('lng' => $lng, 'category' => $breadcrumb_cat_slug)) }}">{{ $breadcrumb_cat_name }}</a>
         </li>
         @endif

         @if( isset($breadcrumb_subcat_name) && isset($breadcrumb_subcat_slug) )
         <li class="active">{{ $breadcrumb_subcat_name }}</li>
         @endif
        </ul>
    </div>

<div class="midblock">

    <div class="row">
    <div class="col-sm-9"><h1>@if(isset($catName)){{ $catName }}@endif</h1></div>
    <div class="col-sm-3">
    <div class="search">
        <form method="GET">
            <input type="text" class="form-control" placeholder="Search" name="search" value="@if(isset($_GET['search'])){{ $_GET['search'] }}@endif">
            <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
        </form>
    </div>
    </div>
    </div>

    <p>@if( isset($page_data) ){!! html_entity_decode( $page_data->page_content, ENT_QUOTES) !!}@endif</p>

    @if( isset($catSlug) && isset($catName) )
    <div class="tab_info_list">
        <div class="row">
            {{--<div class="col-sm-4">
                <ul><li>
                    <a href="{{ route('front_fileSubCat', array('lng' => $lng, 'catSlug' => $catSlug)) }}">{{ $catName }}</a>
                </li></ul>
            </div>--}}
            @if( isset($fileSubCategories) && count($fileSubCategories) > 0 )
            @foreach( $fileSubCategories as $fsc )
            <div class="col-sm-4">
              <ul><li><a href="{{ route('front_fileSubCat', array('lng' => $lng, 'category' => $catSlug, 'subcategory' => $fsc->slug)) }}">{{ $fsc->name }}</a></li></ul>
            </div>
            @endforeach
            @endif
        </div>
    </div>
    @endif


    <div class="table-responsive report">
        <table width="100%" border="0" class="table table-bordered">
        <thead>
          <tr>
            <td style="width: 120px;">Image</td>
            <td>Name & Document Description</td>
            <td style="width: 120px;">Language</td>
            <td style="width: 100px;">File Size</td>
            <td style="width: 100px; text-align: right;">Print Format</td>
          </tr>
        </thead>
        <tbody>
          @if( isset($downloadBrochures) && count($downloadBrochures) > 0 )
            @foreach($downloadBrochures as $broc)
            <tr>
                <td style="text-align: center; vertical-align: middle;">
                    @if( $broc->img_thumb_name != '' )
                    <img src="{{ asset('public/uploads/files/media_images/'. $broc->img_thumb_name) }}">
                    @else
                    <img src="{{ asset('public/front_end/images/pdf_icon.png') }}">
                    @endif
                </td>
                <td>
                    <p><strong>{{ $broc->title }}</strong></p>
                    <p class="fdetails">{{ $broc->details }}</p>
                </td>
                <td>
                    {{ getLanguage( $broc->language_id ) }}
                </td>
                <td>{{ sizeFilter( $broc->size ) }}</td>
                <td style="text-align: right;">
                    @if( !empty(getFileDownloadLink($broc->id)) )
                    <p class="fdetails">
                        <a href="{{ asset('public/uploads/files/media_files/'. getFileDownloadLink($broc->id)) }}" target="_blank">
                        Letter</a>&nbsp;&nbsp;
                        <a href="{{ asset('public/uploads/files/media_files/'. getFileDownloadLink($broc->id)) }}" download>
                        <i class="fa fa-download" aria-hidden="true"></i></a>
                    </p>
                    @endif
                    @if( !empty(getFileDownloadLink($broc->a4_file_id)) )
                    <p class="fdetails">
                        <a href="{{ asset('public/uploads/files/media_files/'. getFileDownloadLink($broc->a4_file_id)) }}" target="_blank">
                        A4</a>&nbsp;&nbsp;
                        <a href="{{ asset('public/uploads/files/media_files/'. getFileDownloadLink($broc->a4_file_id)) }}" download>
                        <i class="fa fa-download" aria-hidden="true"></i></a>
                    </p>
                    @endif
                    @if( !empty(getFileDownloadLink($broc->tema_file_id)) )
                    <p class="fdetails">
                        <a href="{{ asset('public/uploads/files/media_files/'. getFileDownloadLink($broc->tema_file_id)) }}" target="_blank">
                        TEMA</a>&nbsp;&nbsp;
                        <a href="{{ asset('public/uploads/files/media_files/'. getFileDownloadLink($broc->tema_file_id)) }}" download>
                        <i class="fa fa-download" aria-hidden="true"></i></a>
                    </p>
                    @endif
                </td>
            </tr>
            @endforeach
          @endif
         </tbody>
        </table>
    </div>

</div>

</section>
@endsection




@push('page_js')

@endpush

    