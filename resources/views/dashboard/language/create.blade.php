@extends('dashboard.layouts.app')



@section('content_header')
<section class="content-header">
  <h1>
    @if(isset($language))
    Edit Language
    @else
    Add Language
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('langList') }}">All Languages</a></li>
    @if(isset($language))
    <li class="active">Edit Language</li>
    @else
    <li class="active">Add Language</li>
    @endif
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
      <a href="{{ route('langList') }}" class="btn btn-primary"> All Languages</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">@if(isset($language)) Edit Language @else Add Language @endif</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name="frm" id="frmx" action="@if(isset($language)){{ route('langUpdate', array('id' => $language->id)) }}@else{{ route('langSave') }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Language Name : <em>*</em></label>
                <input type="text" name="name" class="form-control" placeholder="Enter Language Name" value="@if(isset($language) && !empty($language)){{ $language->name }}@endif">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Code : <em>*</em></label>
                <input type="text" name="code" class="form-control" maxlength="3" placeholder="Language Code" value="@if(isset($language) && !empty($language)){{ $language->code }}@endif">
                @if($errors->has('code'))
                <span class="roy-vali-error"><small>{{$errors->first('code')}}</small></span>
                @endif
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Not Available Message : </label>
                <input type="text" name="not_msg" class="form-control" placeholder="Message..." value="@if(isset($language) && !empty($language)){{ $language->not_msg }}@endif">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Flag :</label>
                <input type="file" name="flag" id="flag">
                <span class="roy-vali-error" id="ar-flag-err"></span>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                @if(isset($language) && !empty($language) && $language->flag != '' && $language != null)
                  @php
                  $imageURL = asset('public/uploads/flags/thumb/'.$language->flag);
                  @endphp
                  <img src="{{ $imageURL }}" id="flag_preview" class="ar_img_preview" data="{{ $imageURL }}">
                @else
                 <img src="{{ asset('public/images/no-image.png') }}" id="flag_preview" class="ar_img_preview" data="{{ asset('public/images/no-image.png') }}">
                @endif
                 <i class="fa fa-times base-red libtn" id="flag_rm"></i>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Status :</label>
                <input type="radio" name="status" value="1" @if(isset($language)) @if($language->status == '1') checked="checked" @endif @else checked="checked" @endif> Active
                <input type="radio" name="status" value="2" @if(isset($language) && $language->status == '2') checked="checked" @endif> Inactive
              </div>
              <!--div class="form-group">
                <label>Is Default Language :</label>
                <input type="checkbox" name="is_default" value="1" @if(isset($language) && $language->is_default == '1') checked="checked" @endif> Yes
              </div-->
            </div>
          </div>
          <div class="row">
            <div class="col-md-12"><h3>Default 404 Information</h3></div>
          </div>
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Name (Header) : <em>*</em></label>
                <input type="text" name="header_name" class="form-control" placeholder="Enter Header Name" value="@if(isset($language) && !empty($language)){{ $language->header_name }}@endif">
              </div>
              <div class="form-group">
                <label>Content :</label>
                <textarea name="page_content" class="form-control" id="pgCont">@if(isset($language) && !empty($language)){{ html_entity_decode($language->page_content, ENT_QUOTES) }}@endif</textarea>
              </div>
              <div class="form-group">
                <label>Meta Title : <em>*</em></label>
                <input type="text" name="meta_title" class="form-control" placeholder="Enter Meta Title" value="@if(isset($language) && !empty($language)){{ $language->meta_title }}@endif">
              </div>
              <div class="form-group">
                <label>Meta Keywords : <em>*</em></label>
                <input type="text" name="meta_keywords" class="form-control" placeholder="Enter Meta Keywords" value="@if(isset($language) && !empty($language)){{ $language->meta_keywords }}@endif">
              </div>
              <div class="form-group">
                <label>Meta Description : <em>*</em></label>
                <textarea name="meta_description" class="form-control">@if(isset($language) && !empty($language)){{ $language->meta_description }}@endif</textarea>
              </div>
            </div>
          </div>
          <div class="form-group">
            @if(isset($language))
            <input type="submit" class="btn btn-primary" value="Save Changes">
            @else
            <input type="submit" class="btn btn-primary" value="Add Language">
            @endif
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
<script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
var editor = CKEDITOR.replace( 'pgCont', {
  customConfig: "{{ asset('public/assets/ckeditor/maxi_config.js') }}",
} );

/*jQuery.validator.addMethod("cke_required", function (value, element) {
    var idname = $(element).attr('id');
    var editor = CKEDITOR.instances.emBody;
    $(element).val(editor.getData());
    return $(element).val().length > 0;
}, "This field is required - tested working");*/

var fm = $('#frmx');
/*fm.on('submit', function() {
  CKEDITOR.instances.pgCont.updateElement();
});*/
$.validator.addMethod('logosize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param)
}, 'File size must be less than 2mb.');
fm.validate({
  errorElement: 'span',
  errorClass : 'roy-vali-error',
  ignore: [],
  rules: {

    name: {
      required: true,
      minlength: 3,
    },
    code: {
      required: true,
      nowhitespace: true,
    },
    header_name: {
      required: true
    },
    meta_title: {
      required: true
    },
    meta_keywords: {
      required: true
    },
    meta_description: {
      required: true
    },
    flag: {
      <?php if( !isset($language) ) { ?>
        required: true,
      <?php } ?>
        extension: "jpg|jpeg|png|gif",
        logosize: 2000000
      }
  },
  messages: {

    name: {
      required: 'Please Enter Language Name.'
    },
    code: {
      required: 'Please Enter Code.',
      nowhitespace: 'Bloank Space Not Allowed.'
    },
    header_name: {
      required: 'Please Enter Header Name.'
    },
    meta_title: {
      required: 'Please Enter Meta Title.'
    },
    meta_keywords: {
      required: 'Please Enter Meta Keywords.'
    },
    meta_description: {
      required: 'Please Enter Meta Description.'
    },
    flag: {
      required: 'Please Upload Country Flag.',
      extension: 'Please upload any image file.'
    }
  },
  success: function(label) {
    console.log(label);
  }
});

$(function() {
    
$('.libtn').hide();
$("#flag").change('click',function(){
    Ari_FLAG_Preview(this);
});
    
function Ari_FLAG_Preview(input_fileupload)
{
    if(input_fileupload.files && input_fileupload.files[0])
    {
        $('#flag_rm').show();
        var fs=input_fileupload.files[0].size;
        if(fs<=2000000)
        {
            var fileName=input_fileupload.files[0].name;
            var ext = fileName.split('.').pop().toLowerCase();
            if(ext=="jpg" || ext=="png" || ext=="jpeg" || ext=="gif")
            {
                var reader=new FileReader();
                reader.onload = function (e) 
                {
                    $('#flag_preview').attr('src', e.target.result);
                    $("#ar-flag-err").html('');
                    $("#flag").css('color', '#000000');
                    $("#flag-error").html('');
                }
                
                reader.readAsDataURL(input_fileupload.files[0]);
            }
            else
            {
                //alert('Upload .jpg,.png Image only');
                $("#ar-flag-err").html('Choose only jpg, png, gif image.');
            }
        }
        else
        {
            //alert('Upload Less Than 200KB Photo');
            $("#ar-flag-err").html('Choose less than 2mb image size.');
        }
    }
}

$('#flag_rm').on('click', function() {
  $('#flag_preview').attr('src', $('#flag_preview').attr('data'));
  $(this).hide();
  $("#ar-flag-err").html('');
  $('#flag').val('');
  $('#flag-error').hide();
});

});
</script>
@endpush