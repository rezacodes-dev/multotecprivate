@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
  <h1>
    Captcha Settings
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('frms') }}">All Forms</a></li>
    <li class="active">Captcha Settings</li>
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
      <a href="{{ route('frms') }}" class="btn btn-primary"> All Forms</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Captcha Settings</h3>

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
              <form name="frm" action="{{ route('frm_sve_sett') }}" method="post" id="frmx">
              {{ csrf_field() }}
                <div class="form-group">
                  <label>Google captcha site key : <em>*</em></label>
                  <input type="text" name="captcha_site_key" class="form-control" placeholder="Google captcha site key" value="@if( isset($data) && !empty($data) ){{ $data->captcha_site_key }}@endif">
                </div>
                <div class="form-group">
                  <label>Google captcha secret key : <em>*</em></label>
                  <input type="text" name="captcha_secret_key" class="form-control" placeholder="Google captcha secret key" value="@if( isset($data) && !empty($data) ){{ $data->captcha_secret_key }}@endif">
                </div>
                <div class="form-group">
                  <input type="submit" name="ok" class="btn btn-primary" value="SAVE">
                </div>
              </form>
            </div>
            <div class="col-md-6">
              <a href="{{ URL::asset('public/images/captcha.png') }}" target="_blank">
                <img src="{{ URL::asset('public/images/captcha.png') }}" class="img-thumbnail">
              </a>
            </div>
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
<script type="text/javascript">

$("#frmx").validate({
  errorElement: 'span',
  errorClass : 'ar-vali-error',
  rules: {

    captcha_site_key: {
      required: true,
    },
    captcha_secret_key: {
      required: true,
    }

  },
  messages: {

    captcha_site_key:{
      required: 'Please add google captcha site key.',
    },
    captcha_secret_key: {
      required: 'Please add google captcha secret key.',
    }

  }
  
});

</script>
@endpush