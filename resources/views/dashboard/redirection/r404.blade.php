@extends('dashboard.layouts.app')



@section('content_header')
<section class="content-header">
  <h1>
    Manage 404 Page Redirection
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
    <div class="col-md-6">
      
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">404 Redirection</h3>

          <div class="box-tools pull-right">
            
          </div>
        </div>
        <div class="box-body">
          <form name="frm" id="frmx" action="{{ route('r404.save') }}" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Enter 404 Page URL : <em>*</em></label>
                <input type="text" name="source_url" class="form-control" placeholder="Enter 404 Page URL">
              </div>
              @if( isset($data) && !empty($data) )
              <div class="form-group">
                <label>Current 404 Page URL : </label>
                <div class="input-group">
                  <input type="text" id="seleURL" class="form-control" value="{{ $data->source_url }}" readonly="readonly" style="height: 38px;">
                  <div class="input-group-addon">
                    <a href="{{ $data->source_url }}" target="_blank">
                      <i class="fa fa-eye" aria-hidden="true"></i>
                    </a>
                  </div>
                </div>
              </div>
              @endif
              <div class="form-group" style="margin-top: 50px;">
                <input type="submit" class="btn btn-primary" value="SET 404 Page">
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
<script type="text/javascript">
$('#frmx').validate({
  errorElement: 'span',
  errorClass : 'roy-vali-error',
  ignore: [],
  rules: {

    source_url: {
      required: true
    }
  },
  messages: {

    source_url: {
      required: 'Please enter any page for 404.'
    }
  },
  errorPlacement: function(error, element) {
    if(element.hasClass('select2')) {
      error.insertAfter(element.parent('div'));
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