@extends('dashboard.layouts.app')



@section('content_header')
<section class="content-header">
  <h1>
    @if(isset($content))
    Edit Product Box Resuable Content
    @else
    Add Product Box Reusable Content
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('pbox_rlist') }}">All Product Box Contents</a></li>
    @if(isset($content))
    <li class="active">Edit Resuable Content</li>
    @else
    <li class="active">Add Reusable Content</li>
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
      <a href="{{ route('pbox_rlist') }}" class="btn btn-primary"> All Reusable Contents</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">@if(isset($content)) Edit Product Box Resuable Content @else Add Product Box Resuable Content @endif</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name="frm" id="frmx" action="@if(isset($content)){{ route('pbox_edit.post', array('id' => $content->id)) }}@else{{ route('pbox_crte.post') }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Select Column : <em>*</em></label>
                <select name="column_key" class="form-control">
                  <option value="">-SELECT COLUMN FOR RESUABLE CONTENT-</option>
                  <option value="1" @if(isset($content) && $content->column_key == '1') selected="selected" @endif>For 1 Column</option>
                  <option value="2" @if(isset($content) && $content->column_key == '2') selected="selected" @endif>For 2 Column</option>
                  <option value="3" @if(isset($content) && $content->column_key == '3') selected="selected" @endif>For 3 Column</option>
                </select>
              </div>
              <div class="form-group">
                <label>Product Box Reusable Content Name : <em>*</em></label>
                <input type="text" name="name" class="form-control" placeholder="Enter Content Name" value="@if(isset($content)){{ $content->name }}@endif">
              </div>
              <div class="form-group">
                <label>Background Image :</label>
                <input type="file" name="backimg" accept="image/*">
                @if( isset($content) && $content->backimg != '' )
                  <br/>
                  <img src="{{ asset('public/uploads/files/media_images/'.$content->backimg) }}" style="width: 160px; height: 100px;">
                @endif
              </div>
              <div class="form-group">
                <label>Product Box Reusable Content : <em>*</em></label>
                <textarea name="content" id="ck_content" class="form-control" data-error-container="#reSBcontError">@if(isset($content)){{ html_entity_decode($content->content, ENT_QUOTES) }}@endif</textarea>
                <div id="reSBcontError"></div>
              </div>
              <div class="form-group">
                <label>Status :</label>
                <input type="radio" name="status" value="1" @if(isset($content)) @if($content->status == '1') checked="checked" @endif @else checked="checked" @endif> Active
                <input type="radio" name="status" value="2" @if(isset($content) && $content->status == '2') checked="checked" @endif> Inactive
              </div>
              <div class="form-group">
                @if(isset($content))
                <input type="submit" class="btn btn-primary" value="Save Content">
                @else
                <input type="submit" class="btn btn-primary" value="Add Content">
                @endif
              </div>
            </div>
            <div class="col-md-4">
              
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
var editor = CKEDITOR.replace( 'ck_content', {
  customConfig: "{{ asset('public/assets/ckeditor/maxi_config.js') }}",
} );

var fm = $('#frmx');
fm.on('submit', function() {
  CKEDITOR.instances.ck_content.updateElement();
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
    content: {
      required: true
    },
    column_key: {
      required: true
    }
  },
  messages: {

    name: {
      required: 'Please Enter Content Name.'
    },
    content: {
      required: 'Please Enter Product Box Reusable Content.'
    },
    column_key: {
      required: 'Please Select Column First.'
    }
  },
  errorPlacement: function(error, element) {
    if (element.attr("data-error-container")) { 
      error.appendTo(element.attr("data-error-container"));
    } else {
      error.insertAfter(element); 
    }
  },
  success: function(label) {
    console.log(label);
  }
});

</script>
@endpush