@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
  <h1>
    @if(isset($banner) && !empty($banner))
    Edit Home Page Banner
    @else
    Add Home Page Banner
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('bannList') }}">All Banners</a></li>
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
      <a href="{{ route('bannList') }}" class="btn btn-primary"> All Banners</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">@if(isset($banner)) Edit Banner @else Add Banner @endif</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name="frm" id="frmx" action="{{ route('sveBann') }}" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-md-7">
               <div class="form-group">
                <label>Name : <em>*</em></label>
                <input type="text" name="name" class="form-control" placeholder="Enter Image Name" value="">
               </div>
               <div class="form-group">
                <label>Alt Text : <em>*</em></label>
                <input type="text" name="alt_title" class="form-control" placeholder="Enter Image Alt Text" value="">
               </div>
               <div class="form-group">
                <label>Title : <em>*</em></label>
                <input type="text" name="title" class="form-control" placeholder="Enter Image Title" value="">
               </div>
               <div class="form-group">
                <label>Caption : <em>*</em></label>
                <textarea name="caption" class="form-control" style="height: 100px;" placeholder="Enter Image Caption"></textarea>
               </div>
               <div class="form-group">
                <label>Description : </label>
                <textarea name="description" class="form-control" style="height: 100px;" placeholder="Enter Image Details"></textarea>
               </div>
               <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Add Banner">
               </div>
            </div>
            <div class="col-md-5">
              
              <!-- CALL SCRIPT -->
              <!--input type="button" class="addMedImgBtn" value="Image" title="Home Page Banner" data="imgIds_Box">
              
              <input type="text" id="imgIds_Box-idholder">

              <input type="text" id="imgIds_Box-infoholder">
              
              <div id="imgIds_Box-dispDiv"></div-->
              <!-- END -->

              <div class="form-group">
                <label>Upload Banner Image : [<small>1280 x 400</small>] <em>*</em></label>
                <input type="file" name="banner_image" id="banner_image" accept="image/*">
                @if($errors->has('banner_image'))
                <span style="color:RED;"><small>{{$errors->first('banner_image')}}</small></span>
                @endif
                <br/>
                <img id="banner_image_preview" style="width: 200px; height: 100px;" class="img-thumbnail">
                <div id="banner-err" class="roy-vali-error"></div>
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

@include('dashboard.modals.editor_imgmedia_modal')

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
    },
    alt_title: {
      required: true
    },
    title: {
      required: true
    },
    caption: {
      required: true
    },
    banner_image: {
      required: true,
      imgsize: 2000000
    }
  },
  messages: {

    name: {
      required: 'Please Enter Image Name.'
    },
    alt_title: {
      required: 'Please Enter Image Alt Title.'
    },
    title: {
      required: 'Please Enter Image Title.'
    },
    caption: {
      required: 'Please Enter Image Caption.'
    },
    banner_image: {
      required: 'Please Upload Banner Image.',
      imgsize: 'Banner Size Too Large, Should Less Than 2MB.',
      accept: 'Please Select Valid Image Format.'
    }
  }
});
$(function() {

$('#banner_image_preview').hide();

$("#banner_image").change('click',function(){
    Ari_USER_IMAGE_Preview(this);
});
    
function Ari_USER_IMAGE_Preview(input_fileupload)
{
    if(input_fileupload.files && input_fileupload.files[0])
    {
        var fs = input_fileupload.files[0].size;
        if(fs <= 2000000)
        {
            var fileName = input_fileupload.files[0].name;
            var ext = fileName.split('.').pop().toLowerCase();
            if(ext == "jpg" || ext == "png" || ext == "jpeg" || ext == "gif")
            {
                var reader = new FileReader();
                reader.onload = function (e) 
                {   
                  var image = new Image();
                  image.src = e.target.result;
                  image.onload = function () {
                    var height = this.height;
                    var width = this.width;
                    if( width >= 1280 && height >= 400 ) {
                      $('#banner_image_preview').show();
                      $('#banner_image_preview').attr('src', e.target.result);
                      $("#banner-err").html('');
                    } else {
                      $('#banner_image').val('');
                      $("#banner-err").html('Banner Image Width and Height Not Valid. <br/>Current Image Resolution: ' + width + 'x' + height);
                      $('#banner_image_preview').hide();      
                    }
                  }
                }
                
                reader.readAsDataURL(input_fileupload.files[0]);
            }
            else
            {
                //alert('Upload .jpg,.png Image only');
                $('#banner_image').val('');
                $("#banner-err").html('Choose only jpg, png, gif image.');
            }
        }
        else
        {
            //alert('Upload Less Than 200KB Photo');
            $('#banner_image').val('');
            $("#banner-err").html('Choose less than 2mb image size.');
            $('#banner_image_preview').hide();   
        }
    }
}

});
</script>

@include('dashboard.modals.editor_imgmedia_modal_script')

@endpush