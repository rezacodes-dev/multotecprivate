@extends('front_end.layout.layout_master')


@push('page_meta')

    <title>Product Brochures</title>

@endpush

@push('page_css')
<link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
@endpush

@section('page_content')
<section class="container">
    <div class="breadcrumb">
        <ul style="margin-top: 40px;">
         <li><a href="{{ url('/') }}">Home</a></li>
         <li><a href="{{ route('prodBrochure') }}">Product Brochures</a></li>
         <li class="active">Download Brochures</li>
        </ul>
    </div>

<div class="midblock">

    <div class="row">
    <div class="col-sm-9"><h1>Product Brochures</h1></div>
    <div class="col-sm-3">
    <div class="search"><input type="text" class="form-control" placeholder="Search"><button type="submit" value=""><i class="fa fa-search" aria-hidden="true"></i></button></div>
    </div>
    </div>

    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it, text of the printing and typesetting industry. Lorem Ipsum has to make a type ply dummy text of the printing and typesetting industry. Lorem Ipsum has been the specimen book.</p>

    @if( isset($fileSubCategories) && count($fileSubCategories) > 0 && isset($catSlug) )
    <div class="tab_info_list">
        <div class="row">
            @foreach( $fileSubCategories as $fsc )
            <div class="col-sm-3">
              <ul><li><a href="{{ route('prodBrochureCat', array('catSlug' => $catSlug, 'subcatSlug' => $fsc->slug)) }}">{{ $fsc->name }}</a></li></ul>
            </div>
            @endforeach
        </div>
    </div>
    @endif


    <div class="table-responsive report">
        <table width="100%" border="0" class="table table-bordered">
        <thead>
          <tr>
            <td>File</td>
            <td>Name & Document Description</td>
            <td>Lang</td>
            <td>File Size</td>
            <td style="text-align: center;">Print Size</td>
          </tr>
        </thead>
        <tbody>
          @if( isset($downloadBrochures) && count($downloadBrochures) > 0 )
            @foreach($downloadBrochures as $broc)
            <tr>
                <td style="width: 32px;">
                    <img src="{{ asset('public/front_end/images/pdf_icon.png') }}" alt="" style="width: 30px; height: 30px;">
                </td>
                <td>
                    <p><strong>{{ $broc->title }}</strong></p>
                    <p>{{ $broc->details }}</p>
                </td>
                <td>Emglish</td>
                <td>{{ sizeFilter( $broc->size ) }}</td>
                <td style="text-align: center;">
                    @if( !empty(getFileDownloadLink($broc->a4_file_id)) )
                    <p><a href="{{ asset('public/uploads/files/media_files/'. getFileDownloadLink($broc->a4_file_id)) }}" download>
                        A4 <i class="fa fa-download" aria-hidden="true"></i></a></p>
                    @endif
                    @if( !empty(getFileDownloadLink($broc->letter_file_id)) )
                    <p><a href="{{ asset('public/uploads/files/media_files/'. getFileDownloadLink($broc->letter_file_id)) }}" download>
                        Letter <i class="fa fa-download" aria-hidden="true"></i></a></p>
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

    