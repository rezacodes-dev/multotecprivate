@extends('front_end.layout.layout_master')

@push('page_css')
<link href="http://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
@endpush

@section('page_content')
<section class="innerpage-banner"><img src="{{ asset('public/front_end/images/cyclone_main-img.jpg') }}" alt=""></section>
<section class="container">
<div class="breadcrumb">
<ul>
<li><a href="#">Home</a></li>
<li><a href="#">Step 1</a></li>
<li class="active">Step 2</li>
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

@if( isset($otherBrochures) && !empty($otherBrochures) )
<div class="tab_info_list">
    <div class="row">
        @foreach( $otherBrochures as $br )
        <div class="col-sm-3">
          <ul><li><a href="{{ route('dwnBrochure', array('slug' => $br->slug)) }}">{{ $br->name }}</a></li></ul>
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
    <td>Print Size</td>
  </tr>
</thead>
<tbody>
  @if( isset($brochures) && !empty($brochures) )
    @foreach($brochures as $broc)
    <tr>
        <td><img src="{{ asset('public/front_end/images/pdf_icon.png') }}" alt=""></td>
        <td>
            <p><strong>{{ $broc->title }}</strong></p>
            <p>{{ $broc->details }}</p>
            <p>{{ $broc->caption }}</p>
        </td>
        <td>Emglish</td>
        <td>
            @if( isset($broc->masterFileInfo) )
                {{ sizeFilter( $broc->masterFileInfo->size ) }}
            @endif
        </td>
        <td>Letter<br>
            E_A4<br>
            TEMA
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

    