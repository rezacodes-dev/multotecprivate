@extends('dashboard.layouts.app')



@section('content_header')
<section class="content-header">
  <h1>
    Add/Edit 301 Page Redirection
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
      <a href="{{ route('r301') }}" class="btn btn-primary">All 301 Redirections</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">301 Redirection</h3>

          <div class="box-tools pull-right">
            
          </div>
        </div>
        <div class="box-body">
          <form name="frm" id="frmx" action="@if(isset($data)){{ route('r301.upd', array('id' => $data->id)) }}@else{{ route('r301.save') }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Add Source URL : <em>*</em> <span><small>(Add which url you want to redirect)</small></span></label>
                <input type="text" name="source_url" class="form-control" placeholder="Put URL" value="@if( isset($data) && !empty($data) ){{ $data->source_url }}@endif">
              </div>
              <div class="form-group">
                <label>Add 301 Redirection Page URL : <em>*</em></label>
                <input type="text" name="destination_url" class="form-control" placeholder="Put URL" value="@if( isset($data) && !empty($data) ){{ $data->destination_url }}@endif">
              </div>
              <div class="form-group" style="margin-top: 50px;">
                <input type="submit" class="btn btn-primary" value="SET 301 Redirection">
              </div>
            </div>
          </div>
          </form>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          <form name="frmx2" id="frmx2" action="{{ route('r301.upd') }}" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Upload Excel File :</label><small>(For bulk url redirection)</small><br/>
                  <input type="file" name="excel" required="required">
                </div>
              </div>
              <div class="col-md-2">
                <input type="submit" class="btn btn-primary" value="Upload" style="margin-top: 22px;">
              </div>
              <div class="col-md-4">
                <a href="{{ asset('public/multo.xls') }}" download>Download Sample Format</a><br/>
                <code>Please upload same format</code>
              </div>
            </div>
          </form>
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
    },
    destination_url: {
      required: true
    }
  },
  messages: {

    source_url: {
      required: 'Please put url.'
    },
    destination_url: {
      required: 'Please add any page for 301 Redirection.' 
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
$('#frmx2').validate({
  errorElement: 'span',
  errorClass : 'roy-vali-error',
  ignore: [],
});
</script>
@endpush