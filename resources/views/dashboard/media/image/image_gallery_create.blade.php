@extends('dashboard.layouts.app')



@section('content_header')
<section class="content-header">
  <h1>
    @if(isset($imgGal))
    Edit Image Gallery
    @else
    Add Image Gallery
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('media_all_img_gals') }}">All Image Galleries</a></li>
    @if(isset($imgGal))
    <li class="active">Edit Image Gallery</li>
    @else
    <li class="active">Add Image Gallery</li>
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
      <a href="{{ route('media_all_img_gals') }}" class="btn btn-primary"> All Image Galleries</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">@if(isset($imgGal)) Edit Image Gallery @else Add Image Gallery @endif</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name="frm" id="frmx" action="@if(isset($imgGal)){{ route('media_img_gals_upd', array('id' => $imgGal->id)) }}@else{{ route('media_img_gals_sve') }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Gallery or Album Name : <em>*</em></label>
                <input type="text" name="name" class="form-control" placeholder="Enter Gallery Name" value="@if(isset($imgGal)){{ $imgGal->name }}@endif">
              </div>
            </div>
            <div class="col-md-6"></div>
          </div>
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Gallery Description : <em>*</em></label>
                <textarea name="description" id="galDesc" class="form-control" data-error-container="#galDesc_error">@if(isset($imgGal)){{ html_entity_decode($imgGal->description, ENT_QUOTES) }}@endif</textarea>
                <div id="galDesc_error"></div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Meta Title : <em>*</em></label>
                <input type="text" name="meta_title" class="form-control" placeholder="Enter Meta Title" value="@if(isset($imgGal)){{ $imgGal->meta_title }}@endif">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Meta Keywords : <em>*</em></label>
                <input type="text" name="meta_keywords" class="form-control" placeholder="Enter Meta Keywords" value="@if(isset($imgGal)){{ $imgGal->meta_keywords }}@endif">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Meta Description : <em>*</em></label>
                <textarea name="meta_description" class="form-control" placeholder="Enter Meta Description" style="height: 120px;">@if(isset($imgGal)){{ $imgGal->meta_description }}@endif</textarea>
              </div>
            </div>
          </div>
          @if( isset($imgGal) )
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Status :</label>
                <input type="radio" name="status" value="1" @if($imgGal->status == '1') checked="checked" @endif> Active
                <input type="radio" name="status" value="2" @if($imgGal->status == '2') checked="checked" @endif> Inactive
              </div>
            </div>
          </div>
          @endif
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                @if( isset($imgGal) )
                <input type="submit" class="btn btn-primary" value="Save Changes">
                @else
                <input type="submit" class="btn btn-primary" value="Create Gallery">
                @endif
              </div>
            </div>
            <div class="col-md-6"></div>
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
var editor = CKEDITOR.replace( 'galDesc', {
  height : 260,
  resize_enabled : false,
  extraPlugins : 'wordcount',
  wordcount : {
    showCharCount : true,
  },
  filebrowserUploadMethod : 'form',
  filebrowserBrowseUrl : '{{ asset("public/assets/ckeditor/kcfinder/browse.php?opener=ckeditor&type=files") }}',
  filebrowserUploadUrl : '{{ asset("public/assets/ckeditor/kcfinder/upload.php?opener=ckeditor&type=files") }}',
  filebrowserImageBrowseUrl : '{{ asset("public/assets/ckeditor/kcfinder/browse.php?opener=ckeditor&type=images") }}',
  filebrowserImageUploadUrl : '{{ asset("public/assets/ckeditor/kcfinder/upload.php?opener=ckeditor&type=images") }}',
   
  extraPlugins : 'youtube',
  toolbarGroups : [
    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
    { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
    { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
    { name: 'forms', groups: [ 'forms' ] },
    { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
    { name: 'links', groups: [ 'links' ] },
    { name: 'insert', groups: [ 'insert' ] },
    '/',
    { name: 'styles', groups: [ 'styles' ] },
    { name: 'colors', groups: [ 'colors' ] },
    { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
    { name: 'tools', groups: [ 'tools' ] },
    { name: 'others', groups: [ 'others' ] },
    { name: 'about', groups: [ 'about' ] }
  ],

  removeButtons : 'Save,NewPage,Templates,Print,Cut,Copy,Paste,PasteText,PasteFromWord,SelectAll,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Subscript,Superscript,Language,Anchor,Flash,Iframe,About,Find,Replace,Scayt,Blockquote,CreateDiv,Outdent,Indent,BidiLtr,BidiRtl,Smiley,SpecialChar,PageBreak,CopyFormatting,RemoveFormat,ShowBlocks',
} );

var fm = $('#frmx');
fm.on('submit', function() {
  CKEDITOR.instances.galDesc.updateElement();
});
fm.validate({
  errorElement: 'span',
  errorClass : 'roy-vali-error',
  ignore: [],
  rules: {

    name: {
      required: true,
      minlength: 3
    },
    description: {
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
    }
  },
  messages: {

    name: {
      required: 'Please Enter Image Gallery Name.'
    },
    description: {
      required: 'Please Enter Gallery Content.'
    },
    meta_title: {
      required: 'Please Enter Meta Title.'
    },
    meta_keywords: {
      required: 'Please Enter Meta Keywords.'
    },
    meta_description: {
      required: 'Please Enter Meta Description.'
    }
  },
  errorPlacement: function (error, element) { 
    if (element.attr("data-error-container")) { 
      error.appendTo(element.attr("data-error-container"));
    } else {
      error.insertAfter(element); 
    }
  }
});

</script>
@endpush