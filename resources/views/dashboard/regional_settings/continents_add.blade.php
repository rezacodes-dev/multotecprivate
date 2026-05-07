@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
  <h1>
    @if( isset($continent) )
    Update Continents
    @else
    Add New Continents
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('regio_page') }}">Regional Settings</a></li>
    <li class="active">Add Continents</li>
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
      <a href="{{ route('continentsList') }}" class="btn btn-primary"> All Continents</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">@if( isset($continent) ) Edit Continents @else Add Continents @endif</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name="frm" id="frmx" action="@if(isset($continent)){{ route('continentsUpdate', array('id' => $continent->id)) }}@else{{ route('continentsSave') }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Continents Name : <em>*</em></label>
                <input type="text" name="continents_name" class="form-control" placeholder="Enter Continents Name" value="@if( isset($continent) ){{ $continent->continents_name }}@endif">
              </div>
              <div class="form-group">
                <label>Latitude : <em>*</em></label>
                <input type="text" name="lat" class="form-control" placeholder="Latitude" value="@if( isset($continent) ){{ $continent->lat }}@endif" required="required">
              </div>
              <div class="form-group">
                <label>Longitude : <em>*</em></label>
                <input type="text" name="lng" class="form-control" placeholder="Longitude" value="@if( isset($continent) ){{ $continent->lng }}@endif" required="required">
              </div>
              <div class="form-group">
                <label>Status :</label>
                <input type="radio" name="status" value="1" @if(isset($continent)) @if($continent->status == '1') checked="checked" @endif @else checked="checked" @endif> Active
                <input type="radio" name="status" value="2" @if(isset($continent) && $continent->status == '2') checked="checked" @endif> Inactive
              </div>
              <div class="form-group">
                @if(isset($continent))
                <input type="submit" class="btn btn-primary" value="Save Changes">
                @else
                <input type="submit" class="btn btn-primary" value="Add Continents">
                @endif
              </div>
            </div>
            <div class="col-md-6">
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
var fm = $('#frmx'); 
fm.validate({
  errorElement: 'span',
  errorClass : 'roy-vali-error',
  rules: {

    continents_name: {
      required: true
    }
  },
  messages: {

    continents_name: {
      required: 'Please Enter Continents Name.'
    }
  },
  errorPlacement: function (error, element) { 
    element.parent('.form-group').addClass('has-error');
    error.insertAfter(element); 
  },
  success: function (label) {
    label.closest('.form-group').removeClass('has-error');
  }
});
</script>
@endpush