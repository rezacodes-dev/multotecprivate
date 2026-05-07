@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
  <h1>
    Default Email Settings
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('empTemp_lists') }}">Email Template List</a></li>
    <li class="active">Email Settings</li>
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
          <h3 class="box-title">Setup Email Settings</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name="frm" id="frmx" action="{{ route('emp_sett_save') }}" method="post">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Default Sender Email-id : <em>*</em></label>
                <input type="text" name="sender_email_id" class="form-control" placeholder="Default Sender Email-id" value="@if(isset($settings)){{ $settings->sender_email_id }}@endif">
              </div>
              <div class="form-group">
                <label>Default Sender Email Name : <em>*</em></label>
                <input type="text" name="sender_name" class="form-control" placeholder="Default Sender Email Name" value="@if(isset($settings)){{ $settings->sender_name }}@endif">
              </div>
              <div class="form-group">
                <label>Default Email Signature : <em>*</em></label>
                <textarea name="email_signature" class="form-control" placeholder="Default Email Signature" style="height: 90px;">@if(isset($settings)){{ $settings->email_signature }}@endif</textarea>
              </div>
              <div class="form-group">
                <label>Status :</label>
                <input type="radio" name="status" value="1" @if(isset($settings)) @if($settings->status == '1') checked="checked" @endif @else checked="checked" @endif> Active
                <input type="radio" name="status" value="2" @if(isset($settings) && $settings->status == '2') checked="checked" @endif> Inactive
              </div>
              <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Save Settings">
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

$('#frmx').validate({
  errorElement: 'span',
  errorClass : 'roy-vali-error',
  rules: {

    sender_email_id: {
      required: true,
      email: true
    },
    sender_name: {
      required: true,
      minlength: 3
    },
    email_signature: {
      required: true
    }
  },
  messages: {

    sender_email_id: {
      required: 'Please Enter Default Sender Email-id.',
      email: 'Please Enter Valid Email-id.'
    },
    sender_name: {
      required: 'Please Enter Default Sender Email Name.',
    },
    email_signature: {
      required: 'Please Enter Email Signature.'
    }
  }
});
</script>
@endpush