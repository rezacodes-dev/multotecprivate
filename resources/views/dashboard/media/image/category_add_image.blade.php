@extends('dashboard.layouts.app')

@push('page_css')
<style type="text/css">
li.arimg_box {
  float: left;
  padding: 8px;
  list-style: none;
  text-align: center;
  color: #a3a375;
  font-weight: 600;
}
</style>
@endpush

@section('content_header')
@if( isset($imgcat) )
<section class="content-header">
  <h1>
    Add Image(s) To <strong>{{ $imgcat->name }}</strong>
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('media_all_imgs') }}">All Images</a></li>
    <li>Add Image(s)</li>
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
      <a href="{{ route('media_all_imgs') }}" class="btn btn-primary"> All Images</a>
      <a href="{{ route('media_all_img_cats') }}" class="btn btn-primary"> All Groups</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Add Image(s)</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name="frm" id="frmx" action="{{ route('media_all_img_cats_upImg', array('id' => $imgcat->id)) }}" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Select Image(s): <em>*</em></label>
                <input type="file" name="image[]" id="imgx" multiple="multiple" accept="image/*">
              </div>
             </div>
             <div class="col-md-3">
              <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Upload" style="margin-top: 22px;">
              </div>
             </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div id="image_preview"></div>
              <div class="clearfix"></div>
              <div id="errList"></div>
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
@endif
@endsection

@push('page_js')
<script type="text/javascript">
$.validator.addMethod('imgsize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param)
}, 'Image size must be less than 2mb.');
$('#frmx').validate({
  errorElement: 'span',
  errorClass : 'roy-vali-error',
  ignore: [],
  rules: {

    "image[]": {
      required: true,
      extension: "jpg|jpeg|png|gif",
      accept: "image/*",
      imgsize: 2000000
    }
  },
  messages: {

    "image[]": {
      required: 'Please Select Image(s).',
      accept: 'Please Select Only Image Files.',
      extension: 'Image Extension Not Supported.'
    }
  }
});
$(function() {
    
$('.libtn').hide();
$("#imgx").change('click',function(){
    $('#image_preview').html('');
    $('#errList').html('');
    Ari_IMAGE_Preview(this);
});
    
function Ari_IMAGE_Preview(input_fileupload)
{
    var count = input_fileupload.files.length;
    var arErr = 0;
    if( count > 10 ) {
    	arErr++;
    }
    if( count > 0 ) {
      for(var i = 0; i < count; i++) {
        var html = "";
        var fs = input_fileupload.files[i].size;
        if(fs <= 2000000)
        {
            var fileName = input_fileupload.files[i].name;
            var ext = fileName.split('.').pop().toLowerCase();
            if(ext=="jpg" || ext=="png" || ext=="jpeg" || ext=="gif")
            {
              html += "<li class='arimg_box'>";
                html += "<img src='"+ URL.createObjectURL(input_fileupload.files[i])+"' style='width: 100px;' class='img-thumbnail'>";
                html += "<br/><span>Size : "+ bytesToSize(fs) +"</span>";
              html += "</li>";
              $('#image_preview').append(html);
              $('#imgx-error').html('');
            }
            else
            {
                $('#errList').append('<li class="error"><strong>ERROR:: '+ fileName +'</strong> - Not Uploaded, Image Extension Not Support.</li>');
            }
        }
        else
        {
            $('#errList').append('<li class="error"><strong>ERROR:: '+ fileName +'</strong> - Not Uploaded, Image Size Greater Than 2mb.</li>');
        }
      }
    }
}

function bytesToSize(bytes) {
   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
   if (bytes == 0) return '0 Byte';
   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
};

});
</script>
@endpush