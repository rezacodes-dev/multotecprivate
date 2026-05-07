@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
  <h1>
    @if(isset($template) && !empty($template))
    Edit Email Template
    @else
    Add New Email Template
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('empTemp_lists') }}">Email Template List</a></li>
     @if(isset($template) && !empty($template))
    <li class="active">Edit Email Template</li>
     @else
    <li class="active">Create Email Template</li>
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
      <a href="{{ route('empTemp_lists') }}" class="btn btn-primary"> All Email Templates</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">@if(isset($template) && !empty($template)) Edit Email Template @else Create Email Template @endif</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name="frm" id="frmx" action="@if(isset($template)){{ route('update_empTemp', array('id' => $template->id)) }}@else{{ route('save_empTemp') }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Email Template Name : <em>*</em></label>
                <input type="text" name="name" class="form-control" placeholder="Enter Template Name" value="@if(isset($template) && !empty($template)){{ $template->name }}@endif">
              </div>
            </div>
            <div class="col-md-6">
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Email Subject : <em>*</em></label>
                <input type="text" name="subject" class="form-control" placeholder="Enter Heading" value="@if(isset($template) && !empty($template)){{ $template->subject }}@endif">
              </div>
            </div>
            <div class="col-md-6">
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Email Body : <em>*</em></label>
                <div><strong>Note</strong> : Email Body Data Replace Marker Format <code>[YOUR-MARKER]</code></div>
                <textarea name="description" class="form-control" id="emBody" placeholder="Enter Email Body..." data-error-container="#emBody_error">@if(isset($template) && !empty($template)){{ html_entity_decode($template->description , ENT_QUOTES) }}@endif</textarea>
                <div id="emBody_error"></div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label>Status :</label>
              <input type="radio" name="status" value="1" @if(isset($template)) @if(!empty($template) && $template->status == '1') checked="checked" @endif @else checked="checked" @endif> Active
              <input type="radio" name="status" value="2" @if(isset($template) && !empty($template) && $template->status == '2') checked="checked" @endif> Inactive
            </div>
          </div>
          <div class="row" style="margin-top: 10px;">
            <div class="col-md-6">
              <div class="form-group">
                <input type="submit" class="btn btn-primary" value="@if(isset($template)) Update Template @else Save Template @endif">
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
<script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
var editor = CKEDITOR.replace( 'emBody', {
  customConfig: "{{ asset('public/assets/ckeditor/email_config.js') }}",
} );

/*jQuery.validator.addMethod("cke_required", function (value, element) {
    var idname = $(element).attr('id');
    var editor = CKEDITOR.instances.emBody;
    $(element).val(editor.getData());
    return $(element).val().length > 0;
}, "This field is required - tested working");*/

var fm = $('#frmx');
fm.on('submit', function() {
  CKEDITOR.instances.emBody.updateElement();
});
fm.validate({
  errorElement: 'span',
  errorClass : 'roy-vali-error',
  ignore: [],
  debug: false,
  rules: {

    name: {
      required: true
    },
    subject: {
      required: true,
      minlength: 3
    },
    description: {
      required: true
    }
  },
  messages: {

    name: {
      required: 'Please Enter Email Template Name.'
    },
    subject: {
      required: 'Please Enter Email Subject.'
    },
    description: {
      required: 'Please Enter Email content.'
    }
  },
  errorPlacement: function (error, element) { 
    element.parent('.form-group').addClass('has-error');
    if (element.attr("data-error-container")) { 
      error.appendTo(element.attr("data-error-container"));
    } 
    else {
      error.insertAfter(element); 
    }
  },
  success: function (label) {
    label.closest('.form-group').removeClass('has-error');
  }
});
</script>
@endpush