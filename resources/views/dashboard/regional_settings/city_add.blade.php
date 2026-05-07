@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
  <h1>
    @if( isset($city) )
    Update City
    @else
    Add New City
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('regio_page') }}">Regional Settings</a></li>
    <li class="active">Add City</li>
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
      <a href="{{ route('cityList') }}" class="btn btn-primary"> All Cities</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">@if( isset($city) ) Edit City @else Add City @endif</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name="frm" id="frmx" action="@if(isset($city)){{ route('cityUpdate', array('id' => $city->id)) }}@else{{ route('citySave') }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Select Country : <em>*</em></label>
                <select name="country_id" class="form-control select2" id="country_id">
                @if(isset($countries))
                  <option value="">-Select Country-</option>
                  @foreach($countries as $cnt)
                  <option value="{{ $cnt->id }}" @if(isset($city) && isset($selectCountryID) && $selectCountryID == $cnt->id) selected="selected" @endif>{{ $cnt->country_name }}</option>
                  @endforeach
                @endif
                </select>
                <span id="country_id-error" class="roy-vali-error"></span>
              </div>
              <div class="form-group">
                <label>Select Province : <em>*</em></label>
                <select name="province_id" class="form-control select2" id="province_id">
                  <option value="">-Select Province-</option>
                  @if(isset($city) && isset($selectedProvines) && !empty($selectedProvines))
                    @foreach($selectedProvines as $pv)
                    <option value="{{ $pv->id }}" @if($city->province_id == $pv->id) selected="selected" @endif>{{ $pv->province_name }}</option>
                    @endforeach
                  @endif
                </select>
                <span id="province_id-error" class="roy-vali-error"></span>
              </div>
              <div class="form-group">
                <label>City Name : <em>*</em></label>
                <input type="text" name="city_name" class="form-control" placeholder="Enter City Name" value="@if(isset($city)){{ $city->city_name }}@endif">
              </div>
              <div class="form-group">
                <label>Status :</label>
                <input type="radio" name="status" value="1" @if(isset($city)) @if($city->status == '1') checked="checked" @endif @else checked="checked" @endif> Active
                <input type="radio" name="status" value="2" @if(isset($city) && $city->status == '2') checked="checked" @endif> Inactive
              </div>
              <div class="form-group">
                @if(isset($city))
                <input type="submit" class="btn btn-primary" value="Save Changes">
                @else
                <input type="submit" class="btn btn-primary" value="Add City">
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
  <?php if( !isset($city) ) { ?>
  $('#province_id').attr('disabled', 'disabled');
  <?php } ?>
  $('#country_id').on('change', function() {
    if( $(this).val() != '' ) {
      $('#country_id-error').html('');
      $.ajax({
        type : "POST",
        url : "{{ route('ajx_provinceList') }}",
        data : "country_id="+$(this).val()+"&_token={{ csrf_token() }}",
        cache : false,
        beforeSend : function() {

        },
        success : function(resp) {
          var opHTML = '<option value="">-Select Province-</option>';
          var jArr = JSON.parse(resp);
          var jArrLen = jArr.length;
          if( jArrLen > 0 ) {
            $('#province_id').removeAttr('disabled');
            for( var i = 0; i < jArrLen; i++) {
              opHTML += '<option value="'+ jArr[i].id +'">'+ jArr[i].province_name +'</option>';
            }
          } else {
            $('#province_id').attr('disabled', 'disabled');
          }
          $('#province_id').html(opHTML);
        }
      });
    } 
  });
  $('#province_id').on('change', function() {
    if( $(this).val() != '' ) {
      $('#province_id-error').html('');
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
    province_id: {
      required: true
    },
    city_name: {
      required: true
    }
  },
  messages: {

    country_id: {
      required: 'Please Select Country.'
    },
    province_id: {
      required: 'Please Select Province.'
    },
    city_name: {
      required: 'Please Enter City Name'
    }
  },
  errorPlacement: function(error, element) {
    if(element.hasClass('select2')) {
      $('#'+element.attr('id')+'-error').html(error);
    } else {
      error.insertAfter(element);
    }
  }
});
</script>
@endpush