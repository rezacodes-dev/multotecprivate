@extends('dashboard.layouts.app')

@push('page_css')
<link rel="stylesheet" href="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.css') }}">
@endpush


@section('content_header')
<section class="content-header">
  <h1>
    Home Page Map Content
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
  </ol>
</section>
@endsection

@section('content')
<section class="content">

  @if(Session::has('msg'))
  <div class="ar-hide @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}</div>
  @endif

  <div class="row">
    <div class="col-md-6">
      
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
        </div>
        <div class="box-body">
          <form name="jfrm" id="frmx" action="{{ route('home.mapAct') }}" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}

          <div class="row">
            <div class="col-md-6">
              <h3>Small Map Details</h3><hr/>
              <div class="form-group">
                <label>Heading :</label>
                <input type="text" name="small_heading" class="form-control" value="@if(isset($map)){{ $map->small_heading }}@endif">
              </div>
              <div class="form-group">
                <label>Link :</label>
                <input type="text" name="small_link" class="form-control" value="@if(isset($map)){{ $map->small_link }}@endif">
              </div>
              <div class="form-group">
                <label>Image :</label>
                <input type="file" name="small_image" accept="image/*" required="required">
              </div>
              @if(isset($map) && $map->small_image != '')
              <div class="form-group">
                <img src="{{ asset('public/uploads/files/media_images/'. $map->small_image) }}" class="img-thumbnail"> 
                <a href="{{ route('glbImgDel') }}?tab=home_map&id={{ $map->id }}&field=small_image" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure to delete this image ?');">Delete Image</a>
              </div>
              @endif
            </div>
            <div class="col-md-6">
              <h3>Big Map Details</h3><hr/>
              <div class="form-group">
                <label>Heading Right :</label>
                <input type="text" name="big_heading_right" class="form-control" value="@if(isset($map)){{ $map->big_heading_right }}@endif">
              </div>
              <div class="form-group">
                <label>Heading Left :</label>
                <input type="text" name="big_heading_left" class="form-control" value="@if(isset($map)){{ $map->big_heading_left }}@endif">
              </div>
              <div class="form-group">
                <label>Link :</label>
                <input type="text" name="big_link" class="form-control" value="@if(isset($map)){{ $map->big_link }}@endif">
              </div>
              <div class="form-group">
                <label>Image :</label>
                <input type="file" name="big_image" accept="image/*" required="required">
              </div>
              @if(isset($map) && $map->big_image != '')
              <div class="form-group">
                <img src="{{ asset('public/uploads/files/media_images/'. $map->big_image) }}" class="img-thumbnail"> 
                <a href="{{ route('glbImgDel') }}?tab=home_map&id={{ $map->id }}&field=big_image" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure to delete this image ?');">Delete Image</a>
              </div>
              @endif
            </div>
          </div>
          <div class="row">
            <div class="col-md-12"><hr/></div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <input type="submit" class="btn btn-primary" value="Save All">
            </div>
          </div>
          </form>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->
    </div>
  </div>

</section>

@endsection

@push('page_js')
<script src="{{ asset('public/assets/jquery_ui/jquery-ui.js') }}"></script>
<script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.js') }}"></script>


<script type="text/javascript">

var fm = $('#frmx');
fm.validate({
  errorElement: 'span',
  errorClass : 'roy-vali-error',
  ignore: [],
  normalizer: function( value ) {
    return $.trim( value );
  },
  rules: {

    big_heading_left: {
      required: true,
      minlength: 3
    },
    big_heading_right: {
      required: true,
      minlength: 3
    }
  }
});
</script>

@endpush