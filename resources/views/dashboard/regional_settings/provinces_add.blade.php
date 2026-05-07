@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
  <h1>
    @if( isset($province) )
    Update Province
    @else
    Add New Province
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('regio_page') }}">Regional Settings</a></li>
    <li class="active">Add Province</li>
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
      <a href="{{ route('provincesList') }}" class="btn btn-primary"> All Provinces</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">@if( isset($province) ) Edit Province @else Add Province @endif</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name="frm" id="frmx" action="@if(isset($province)){{ route('provincesUpdate', array('id' => $province->id)) }}@else{{ route('provincesSave') }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Select Country : <em>*</em></label>
                <select name="country_id" class="form-control select2" id="country_id">
                  <option value="">-Select Country-</option>
                  @if(isset($countries))
                    @foreach($countries as $cnt)
                    <option value="{{ $cnt->id }}" @if(isset($province) && !empty($province) && $province->country_id == $cnt->id) selected="selected" @endif>{{ $cnt->country_name }}</option>
                    @endforeach
                  @endif
                </select>
                <span id="country_id-error" class="roy-vali-error"></span>
              </div>
              <div class="form-group">
                <label>Province Name : <em>*</em></label>
                <input type="text" name="province_name" class="form-control" placeholder="Enter Province Name" value=" @if(isset($province) && !empty($province)){{ $province->province_name }}@endif">
              </div>
              <div class="form-group">
                <label>Status :</label>
                <input type="radio" name="status" value="1" @if(isset($province)) @if($province->status == '1') checked="checked" @endif @else checked="checked" @endif> Active
                <input type="radio" name="status" value="2" @if(isset($province) && $province->status == '2') checked="checked" @endif> Inactive
              </div>
              <div class="form-group">
                @if(isset($province))
                <input type="submit" class="btn btn-primary" value="Save Changes">
                @else
                <input type="submit" class="btn btn-primary" value="Add Province">
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
<script type="text/javascript">
$( function() {
  $('#country_id').on('change', function() {
    if( $(this).val() != '' ) {
      $('#country_id-error').html('');
    }
  });
});
var fm = $('#frmx'); 
fm.validate({
  errorElement: 'span',
  errorClass : 'roy-vali-error',
  rules: {

    country_id: {
      required: true
    },
    province_name: {
      required: true
    }
  },
  messages: {

    country_id: {
      required: 'Please Select Any Country.'
    },
    province_name: {
      required: 'Please Enter Province Name.'
    }
  },
  errorPlacement: function(error, element) {
    if(element.hasClass('select2')) {
      $('#country_id-error').html(error);
    } else {
      error.insertAfter(element);
    }
  }
});
</script>
@endpush