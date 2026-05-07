@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
  <h1>
    @if( isset($region) )
    Update Region
    @else
    Add New Region
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('regio_page') }}">Regional Settings</a></li>
    <li class="active">Add Region</li>
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
      <a href="{{ route('regionList') }}" class="btn btn-primary"> All Region</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">@if( isset($region) ) Edit Region @else Add Region @endif</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name="frm" id="frmx" action="@if(isset($region)){{ route('regionUpdate', array('id' => $region->id)) }}@else{{ route('regionSave') }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Select Continent : <em>*</em></label>
                <select name="continent_id" class="form-control">
                @if(isset($continents))
                  <option value="">-Select Continent-</option>
                  @foreach($continents as $cn)
                  <option value="{{ $cn->id }}" @if(isset($region) && $region->continent_id == $cn->id) selected="selected" @endif>{{ $cn->continents_name }}</option>
                  @endforeach
                @endif
                </select>
              </div>
              <div class="form-group">
                <label>Region Name : <em>*</em></label>
                <input type="text" name="region_name" class="form-control" placeholder="Enter Region Name" value="@if(isset($region)){{ $region->region_name }}@endif">
              </div>
              <div class="form-group">
                <label>Status :</label>
                <input type="radio" name="status" value="1" @if(isset($region)) @if($region->status == '1') checked="checked" @endif @else checked="checked" @endif> Active
                <input type="radio" name="status" value="2" @if(isset($region) && $region->status == '2') checked="checked" @endif> Inactive
              </div>
              <div class="form-group">
                @if(isset($region))
                <input type="submit" class="btn btn-primary" value="Save Changes">
                @else
                <input type="submit" class="btn btn-primary" value="Add Region">
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
var fm = $('#frmx'); 
fm.validate({
  errorElement: 'span',
  errorClass : 'roy-vali-error',
  rules: {

    continent_id: {
      required: true
    },
    region_name: {
      required: true
    }
  },
  messages: {

    continent_id: {
      required: 'Please Select Continent Name.'
    },
    region_name: {
      required: 'Please Enter Region Name.'
    }
  }
});
</script>
@endpush