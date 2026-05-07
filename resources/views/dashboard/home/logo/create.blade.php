@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
  <h1>
    Logo
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
      <a href="{{ route('home.logo') }}" class="btn btn-primary"> All Logos</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Logo</h3>

          
        </div>
        <div class="box-body">
          <form name="frm" id="frmx" action="@if(isset($logo)){{ route('logo_upd', array('id' => $logo->id)) }}@else{{ route('logo_sve') }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-md-10">
            	<div class="form-group">
            		<label>Name : <em>*</em></label>
            		<input type="text" name="name" class="form-control" placeholder="Name" value="@if(isset($logo)){{ $logo->name }}@endif">
            	</div>
              <div class="form-group">
                <label>URL To Link  :</label>
                <input type="text" name="link_file" class="form-control" value="@if(isset($logo)){{ $logo->link_file }}@endif">
              </div>
              <div class="form-group">
                <label>Display Order :</label>
                <input type="text" name="display_order" class="form-control onlyNumber" style="width: 100px;" @if( isset($logo) ) value="{{ $logo->display_order }}" @else value="0" @endif>
              </div>
            </div>
          </div>
          <div class="row">
          	<div class="col-md-5"><h3>Image Information</h3></div>
            <div class="col-md-5">

            </div>
          </div>
          <div class="row">
          	<div class="col-md-10">
          		<div class="form-group">
          			<label>Upload Image : <em>*</em></label>
          			<input type="file" name="image" accept="image/*" @if( isset($logo) && $logo->image == '') required="required" @endif @if(!isset($logo)) required="required" @endif>
          		</div>
          		@if(isset($logo) && $logo->image != '')
              <div class="form-group">
                <img src="{{ asset('public/uploads/files/media_images/'. $logo->image) }}" class="img-thumbnail"> 
                <a href="{{ route('glbImgDel') }}?tab=home_logo&id={{ $logo->id }}&field=image" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure to delete this image ?');">Delete Image</a>
              </div>
              @endif
          	</div>
          </div>
          <div class="row">
          	<div class="col-md-5">
          		<div class="form-group">
          			<label>Image Title Tag :</label>
          			<input type="text" name="image_title" class="form-control" placeholder="Title Tag" value="@if(isset($logo)){{ $logo->image_title }}@endif">
          		</div>
          		<div class="form-group">
          			<label>Image Alt Tag :</label>
          			<input type="text" name="image_alt" class="form-control" placeholder="Alt Tag" value="@if(isset($logo)){{ $logo->image_alt }}@endif">
          		</div>
          		<div class="form-group">
          			<label>Image Caption :</label>
          			<textarea name="image_caption" class="form-control" placeholder="Caption">@if(isset($logo)){{ $logo->image_caption }}@endif</textarea>
          		</div>
          	</div>
          </div>
          <div class="row">
          	<div class="col-md-10">
          		<div class="form-group">
          			<input type="submit" class="btn btn-primary" value="Save All">
          		</div>
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
<script type="text/javascript">
$.validator.addMethod('imgsize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param)
}, 'File size must be less than 2mb.');
$('#frmx').validate({
  errorElement: 'span',
  errorClass : 'roy-vali-error',
  ignore: [],
  rules: {

    name: {
      required: true
    }
  },
  messages: {

    name: {
      required: 'Please Enter Name.'
    },
    image: {
      required: 'Please Upload Image.'
    }
  }
});
</script>

@endpush