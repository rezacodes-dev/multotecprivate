@extends('dashboard.layouts.app')

@push('page_css')
<link rel="stylesheet" href="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.css') }}">
@endpush

@section('content_header')
<section class="content-header">
  <h1>
    Add New Location
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('allDistrib') }}">All Distributors</a></li>
    
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
      <a href="{{ route('distr.allloc') }}" class="btn btn-primary"> All Locations</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Add new location</h3>

          
        </div>
        <div class="box-body">
          <form name="frm" id="frmx" action="@if(isset($location)){{ route('distr.updloc', array('id' => $location->id)) }}@else{{ route('distr.addloc.save') }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label>Heading (H1): <em>*</em></label>
                <input type="text" name="title" class="form-control" required="required" value="@if(isset($location)){{ $location->title }}@endif">
              </div>
              <div class="form-group">
                <label>Select Distributor or Branch : <em>*</em></label>
                <select name="distrb_branch_id" class="form-control" required="required">
                  <option value="">-SELECT ANY DISTRIBUTOR OR BRANCH-</option>
                  <option value="0" @if(isset($location) && $location->distrb_branch_id == '0') selected="selected" @endif>Multotec Branch</option>
                  @if( isset($allDistrbs) )
                    @foreach($allDistrbs as $db)
                      <option value="{{ $db->id }}" @if(isset($location) && $location->distrb_branch_id == $db->id) selected="selected" @endif>{{ $db->name }}</option>
                    @endforeach
                  @endif
                </select>
              </div>
              <div class="form-group">
                <label>Distributor Website URL:</label>
                <input type="text" name="url" class="form-control" value="@if(isset($location)){{ $location->url }}@endif">
              </div>
              <div class="form-group">
                <label>Contact Number:</label>
                <input type="text" name="phno" class="form-control" value="@if(isset($location)){{ $location->phno }}@endif">
              </div>
              <div class="form-group">
                <label>Zip Code : <em>*</em></label>
                <input type="text" name="zip" class="form-control" required="required" value="@if(isset($location)){{ $location->zip }}@endif">
              </div>

              <div class="form-group">
                <label>Select Continent : <em>*</em></label>
                <select name="continent_id" id="continent_id" class="form-control" required="required">
                  <option value="">-SELECT CONTINENT-</option>
                  @if(isset($allContinents))
                    @foreach($allContinents as $cnt)
                      <option value="{{ $cnt->id }}" @if(isset($location) && $location->continent_id == $cnt->id) selected="selected" @endif>{{ $cnt->continents_name }}</option>
                    @endforeach
                  @endif
                </select>
              </div>

              <div class="form-group">
                <label>Select Country : <em>*</em></label>
                <select name="country_id" id="country_id" class="form-control" required="required">
                  <option value="">-SELECT COUNTRY-</option>
                  @if(isset($seleCountry))
                    @foreach($seleCountry as $sc)
                    <option value="{{ $sc->id }}" @if(isset($location) && $location->country_id == $sc->id) selected="selected" @endif>{{ $sc->name }}</option>
                    @endforeach
                  @endif
                </select>
              </div>

              <div class="form-group">
                <label>Select City : <em>*</em></label>
                <select name="city_id" id="city_id" class="form-control" required="required">
                  <option value="">-SELECT CITY-</option>
                  @if(isset($seleCity))
                    @foreach($seleCity as $sc)
                    <option value="{{ $sc->id }}" @if(isset($location) && $location->city_id == $sc->id) selected="selected" @endif>{{ $sc->name }}</option>
                    @endforeach
                  @endif
                </select>
              </div>

              <div class="form-group">
                <label>Address : <em>*</em></label>
                <textarea name="address" class="form-control" required="required">@if(isset($location)){{ $location->address }}@endif</textarea>
              </div>

              <div class="form-group">
                <label>Latitude : <em>*</em></label>
                <input type="text" name="lat" class="form-control" required="required" value="@if(isset($location)){{ $location->lat }}@endif">
              </div>

              <div class="form-group">
                <label>Longitude : <em>*</em></label>
                <input type="text" name="lng" class="form-control" required="required" value="@if(isset($location)){{ $location->lng }}@endif">
              </div>
              <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Add Location">
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
<script src="{{ asset('public/assets/jquery_ui/jquery-ui.js') }}"></script>
<script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.js') }}"></script>

<script type="text/javascript">


var fm = $('#frmx');

fm.validate({
  errorElement: 'span',
  errorClass : 'roy-vali-error',
  ignore: [],
  normalizer: function( value ) {
    return $.trim( value );
  },
  rules: {

    
  },
  messages: {

    
  },
  errorPlacement: function(error, element) {
    element.parent('.form-group').addClass('has-error');
    if (element.attr("data-error-container")) { 
      error.appendTo(element.attr("data-error-container"));
    } else if(element.attr('id') == 'language_id') {
      error.insertAfter(element.parent('div'));
    } else {
      error.insertAfter(element); 
    }
  },
  success: function(label) {
    label.closest('.form-group').removeClass('has-error');
  }
});



$( function() { 

  $('#continent_id').on('change', function() {
    if( $(this).val() != '' ) {
      $.ajax({
        type : "POST",
        url : "{{ route('ajx_continent_country') }}",
        data : "continent_id="+$(this).val()+"&_token={{ csrf_token() }}",
        cache : false,
        beforeSend : function() {

        },
        success : function(resp) {
          var opHTML = '<option value="">-SELECT COUNTRY-</option>';
          var jArr = JSON.parse(resp);
          var jArrLen = jArr.length;
          if( jArrLen > 0 ) {
            for( var i = 0; i < jArrLen; i++) {
              opHTML += '<option value="'+ jArr[i].id +'">'+ jArr[i].name +'</option>';
            }
          } 

          $('#country_id').html(opHTML);
        }
      });
    } 
  });


  $('#country_id').on('change', function() {
    if( $(this).val() != '' ) {
      $.ajax({
        type : "POST",
        url : "{{ route('ajx_country_city') }}",
        data : "country_id="+$(this).val()+"&_token={{ csrf_token() }}",
        cache : false,
        beforeSend : function() {

        },
        success : function(resp) {
          var opHTML = '<option value="">-SELECT CITY-</option>';
          var jArr = JSON.parse(resp);
          var jArrLen = jArr.length;
          if( jArrLen > 0 ) {
            for( var i = 0; i < jArrLen; i++) {
              opHTML += '<option value="'+ jArr[i].id +'">'+ jArr[i].name +'</option>';
            }
          } 

          $('#city_id').html(opHTML);
        }
      });
    } 
  });

});
</script>
@endpush