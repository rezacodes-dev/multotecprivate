@extends('dashboard.layouts.app')

@push('page_css')
<link rel="stylesheet" href="{{ asset('public/assets/jquery_ui/jquery-ui.css') }}">
<script src='https://www.google.com/recaptcha/api.js'></script>
<style type="text/css">
.ar_frm_container {
  padding: 10px;
}
.submit-btn {
  background: #92c654;
  color: #fff;
  font-size: 24px;
  font-weight: 400;
  border: 1px solid #fff;
  padding: 10px 20px;
  width: 100%;
  transition: all .5s ease-out;
}
.custom-file-upload {
  background: #fff;
  border: 1px solid #dde1e4;
  display: inline-block;
  padding: 6px 12px;
  cursor: pointer;
  font-size: 16px;
  color: #8f8f8f;
  font-weight: 300;
  width: 100%;
}
</style>
@endpush


@section('content_header')
<section class="content-header">
  <h1>
    Form Preview
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('frms') }}">All Forms</a></li>
    <li class="active">Form Preview</li>
  </ol>
</section>
@endsection

@section('content')
<section class="content">

  @if(Session::has('msg'))
  <div class="@if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}</div>
  @endif

  <div class="row">
    <div class="col-md-6">
      <a href="{{ route('frms') }}" class="btn btn-primary"> All Forms</a>
      <!-- {{dd($form_details->frm_auto_id)}} -->
      <!-- <a href="@if(isset($form_details)){{ route('edt_frm_flds', array('fid' => $form_details->frm_auto_id)) }}@endif" class="btn btn-primary"> -->
      <a href="@if(isset($form_details)){{ route('edt_frm_flds', ['fid' => $form_details->frm_auto_id] )}}@endif" class="btn btn-primary">
      BACK</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Form Preview</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="row" style="margin-top: 10px;">
            <div class="col-md-6">
              @if( !empty($form_details) )
              {!! html_entity_decode( $form_details->frm_raw_html ) !!}
              @endif
            </div>
            <div class="col-md-6"></div>
          </div>
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
<script type="text/javascript">
$( function() {
  $("body").on('keypress', '.onlyNumber', function(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
      return false;
    return true;
  });
  $( ".datepicker" ).datepicker({
      //minDate:0,
      dateFormat: 'dd-mm-yy',
      changeMonth: true,
      changeYear: true
  });
});
</script>
<script type="text/javascript">
$(".ar_vali_class").validate({
  errorElement: 'span',
  errorClass : 'ar-vali-error',
  errorPlacement: function(error, element) {
    if( element.attr('type') == 'radio') {
      error.insertAfter(element.parent('.form-group'));
    } else if( element.attr('type') == 'checkbox' ) {
      error.insertAfter(element.parent('.form-group'));
    } 
    else {
      error.insertAfter(element);
    }
  },
  submitHandler : function(form) {

    if( grecaptcha.getResponse() != "" && grecaptcha.getResponse().length != 0 ) {

      form.submit();   
    } else {
      $('.ar-captcha-vali').html('<span class="ar-vali-error">Please check the captcha</span>');
    }
    
  }
});
</script>
@endpush