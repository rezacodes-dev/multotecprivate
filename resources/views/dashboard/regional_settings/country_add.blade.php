@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
  <h1>
    @if( isset($country) )
    Update Country
    @else
    Add New Country
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('regio_page') }}">Regional Settings</a></li>
    <li class="active">Add Country</li>
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
      <a href="{{ route('countryList') }}" class="btn btn-primary"> All Countries</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">@if( isset($country) ) Edit Country @else Add Country @endif</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name="frm" id="frmx" action="@if(isset($country)){{ route('countryUpdate', array('id' => $country->id)) }}@else{{ route('countrySave') }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Select Continent : <em>*</em></label>
                <select name="continent_id" class="form-control" id="continent_id">
                @if(isset($continents))
                  <option value="">-Select Continent-</option>
                  @foreach($continents as $cn)
                  <option value="{{ $cn->id }}" @if(isset($country) && $country->Region->continent_id == $cn->id) selected="selected" @endif>{{ $cn->continents_name }}</option>
                  @endforeach
                @endif
                </select>
              </div>
              <div class="form-group">
                <label>Select Region : <em>*</em></label>
                <select name="region_id" class="form-control" id="region_id">
                  <option value="">-Select Region-</option>
                  @if(isset($country) && isset($regions))
                    @foreach($regions as $rg)
                    <option value="{{ $rg->id }}" @if($country->region_id == $rg->id) selected="selected" @endif>{{ $rg->region_name }}</option>
                    @endforeach
                  @endif
                </select>
              </div>
              <div class="form-group">
                <label>Country Name : <em>*</em></label>
                <input type="text" name="country_name" class="form-control" placeholder="Enter Country Name" value="@if(isset($country)){{ $country->country_name }}@endif">
              </div>
              <div class="form-group">
                <label>Latitude : <em>*</em></label>
                <input type="text" name="lat" class="form-control" placeholder="Latitude" value="@if( isset($country) ){{ $country->lat }}@endif" required="required">
              </div>
              <div class="form-group">
                <label>Longitude : <em>*</em></label>
                <input type="text" name="lng" class="form-control" placeholder="Longitude" value="@if( isset($country) ){{ $country->lng }}@endif" required="required">
              </div>
              <div class="form-group">
                <label>Status :</label>
                <input type="radio" name="status" value="1" @if(isset($country)) @if($country->status == '1') checked="checked" @endif @else checked="checked" @endif> Active
                <input type="radio" name="status" value="2" @if(isset($country) && $country->status == '2') checked="checked" @endif> Inactive
              </div>
              <div class="form-group">
                @if(isset($country))
                <input type="submit" class="btn btn-primary" value="Save Changes">
                @else
                <input type="submit" class="btn btn-primary" value="Add Country">
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
  <?php if( !isset($country) ) { ?>
  $('#region_id').attr('disabled', 'disabled');
  <?php } ?>
  $('#continent_id').on('change', function() {
    if( $(this).val() != '' ) {
      $.ajax({
        type : "POST",
        url : "{{ route('ajx_regionList') }}",
        data : "continent_id="+$(this).val()+"&_token={{ csrf_token() }}",
        cache : false,
        beforeSend : function() {

        },
        success : function(resp) {
          var opHTML = '<option value="">-Select Region-</option>';
          var jArr = JSON.parse(resp);
          var jArrLen = jArr.length;
          if( jArrLen > 0 ) {
            $('#region_id').removeAttr('disabled');
            for( var i = 0; i < jArrLen; i++) {
              opHTML += '<option value="'+ jArr[i].id +'">'+ jArr[i].region_name +'</option>';
            }
          } else {
            $('#region_id').attr('disabled', 'disabled');
          }
          $('#region_id').html(opHTML);
        }
      });
    } 
  });
});
var fm = $('#frmx'); 
fm.validate({
  errorElement: 'span',
  errorClass : 'roy-vali-error',
  rules: {

    continent_id: {
      required: true
    },
    region_id: {
      required: true
    },
    country_name: {
      required: true
    }
  },
  messages: {

    continent_id: {
      required: 'Please Select Any Continent.'
    },
    region_id: {
      required: 'Please Select Any Region.'
    },
    country_name: {
      required: 'Please Enter Country Name.'
    }
  }
});
</script>
@endpush