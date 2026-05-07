@extends('dashboard.layouts.app')



@section('content_header')
<section class="content-header">
  <h1>
    Frontend Extra Content
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
    <div class="col-md-10">
      <a href="{{ route('front_allFileCat', array('lng' => 'en')) }}" class="btn btn-primary" target="_blank">View Page</a>
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Add - Edit - Frontend Extra Content For File Section</h3>

          <div class="box-tools pull-right">
            
          </div>
        </div>
        <div class="box-body">
          <form name="frm" id="frmx" action="{{ route('media.fil_extra_cont_save') }}" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Heading : <em>*</em></label>
                <input type="text" name="title" class="form-control" placeholder="Heading" value="@if( isset($extraCont) ){{ $extraCont->title }}@endif">
              </div>
              <div class="form-group">
                <label>Content : </label>
                <textarea name="page_content" id="page_content" class="form-control" data-error-container="#perror">@if(isset($extraCont)){{ html_entity_decode($extraCont->page_content, ENT_QUOTES) }}@endif</textarea>
                <div id="perror"></div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-10">
              <h3>Page Banner Information</h3>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Choose banner :</label>
                <input type="file" name="page_banner" accept="image/*">
              </div>
              @if( isset($extraCont) && $extraCont->image_id != '' && isset($extraCont->imageInfo) )
                <div class="form-group">
                  <img src="{{ asset('public/uploads/files/media_images/'.$extraCont->imageInfo->image) }}" style="width: 260px; height: 100px;">
                  <a href="{{ route('glbImgDel') }}?tab=media_extra_content&id={{ $extraCont->id }}" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure to delete this image ?');">Delete Image</a>
                </div>
              @endif
              <div class="form-group">
                <label>Banner Title :</label>
                <input type="text" name="image_title" class="form-control" placeholder="Banner image title" value="@if( isset($extraCont) ){{ $extraCont->image_title }}@endif">
              </div>
              <div class="form-group">
                <label>Banner Alt Tag :</label>
                <input type="text" name="image_alt" class="form-control" placeholder="Banner image alt title" value="@if( isset($extraCont) ){{ $extraCont->image_alt }}@endif">
              </div>
              <div class="form-group">
                <label>Banner Caption :</label>
                <textarea name="image_caption" class="form-control" placeholder="Banner image caption">@if( isset($extraCont) ){{ $extraCont->image_caption }}@endif</textarea>
              </div>
            </div>
          </div>


          <!------------------------------------------------------------------------------------------------------->
          <!-- META INFO -->
          <div class="row">
            <div class="col-md-10">
              <h3>Page Meta Information</h3>
              <hr/>
            </div>
            <div class="col-md-10">
              <div class="form-group">
                <label>Meta Title:</label>
                <input type="text" name="meta_title" class="form-control" placeholder="Meta Title" value="@if( isset($extraCont) ){{ $extraCont->meta_title }}@endif">
              </div>
              <div class="form-group">
                <label>Meta Keywords:</label>
                <input type="text" name="meta_keyword" class="form-control" placeholder="Meta Keywords" value="@if( isset($extraCont) ){{ $extraCont->meta_keyword }}@endif">
              </div>
              <div class="form-group">
                <label>Meta Description:</label>
                <textarea name="meta_desc" class="form-control" placeholder="Meta Description">@if( isset($extraCont) ){{ $extraCont->meta_desc }}@endif</textarea>
              </div>
              <div class="form-group">
                <label>Canonical Url:</label>
                <input type="text" name="canonical_url" class="form-control" placeholder="Any Canonical url" value="@if( isset($extraCont) ){{ $extraCont->canonical_url }}@endif">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Language Tag:</label>
                <input type="text" name="lng_tag" class="form-control" placeholder="Language Tag" value="@if( isset($extraCont) ){{ $extraCont->lng_tag }}@endif">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Add No Follow Tag :</label>
                <select name="follow" class="form-control">
                  <option value="1" @if(isset($extraCont) && $extraCont->follow == '1') selected="selected" @endif>FOLLOW</option>
                  <option value="0" @if(isset($extraCont) && $extraCont->follow == '0') selected="selected" @endif>NO FOLLOW</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Add No Index Tag :</label>
                <select name="index_tag" class="form-control">
                  <option value="1" @if(isset($extraCont) && $extraCont->index_tag == '1') selected="selected" @endif>INDEX</option>
                  <option value="0" @if(isset($extraCont) && $extraCont->index_tag == '0') selected="selected" @endif>NO INDEX</option>
                </select>
              </div>
            </div>
            <div class="col-md-10">
              <div class="form-group">
                <label> Add Structured data mark-up (Json-LD) :</label>
                <textarea name="json_markup" class="form-control" rows="6">@if( isset($extraCont) ){!! html_entity_decode($extraCont->json_markup, ENT_QUOTES) !!}@endif</textarea>
              </div>
            </div>
          </div>
          <!-- END META INFO -->
          <!------------------------------------------------------------------------------------------------------->

          
          
          <div class="row">
            <div class="col-md-8">
              <input type="submit" class="btn btn-primary" value="Save Content">
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
<script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
var editor_photo = CKEDITOR.replace( 'page_content', {
  customConfig: "{{ asset('public/assets/ckeditor/mini_config.js') }}",
} );

var fm = $('#frmx');
fm.on('submit', function() {
  CKEDITOR.instances.page_content.updateElement();
});
fm.validate({
  errorElement: 'span',
  errorClass : 'roy-vali-error',
  ignore: [],
  normalizer: function( value ) {
    return $.trim( value );
  },
  rules: {

    title: {
      required: true
    },
    meta_title: {
      required: true
    },
    meta_desc: {
      required: true
    },
    meta_keyword: {
      required: true
    }
  },
  messages: {

    title: {
      required: 'Please enter heading.'
    },
    meta_title: {
      required: 'Please Enter Page Meta Title.'
    },
    meta_desc: {
      required: 'Please Enter Meta Description.'
    },
    meta_keyword: {
      required: 'Please Enter Meta Keywords.'
    }
  },
  errorPlacement: function(error, element) {
    //element.parent('.form-group').addClass('has-error');
    if (element.attr("data-error-container")) { 
      error.appendTo(element.attr("data-error-container"));
    } else if(element.attr('id') == 'language_id') {
      error.insertAfter(element.parent('div'));
    } else {
      error.insertAfter(element); 
    }
  },
  success: function(label) {
    console.log(label);
  }
});
CKEDITOR.instances.photo_gallery_content.on('blur', function() {
    var data = CKEDITOR.instances.photo_gallery_content.getData();
    if(data != '') {
      $('#perror').html('');
    }
});
CKEDITOR.instances.video_gallery_content.on('blur', function() {
    var data = CKEDITOR.instances.video_gallery_content.getData();
    if(data != '') {
      $('#verror').html('');
    }
});
</script>
@endpush