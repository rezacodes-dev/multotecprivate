@extends('dashboard.layouts.app')

@push('page_css')
<link rel="stylesheet" href="{{ asset('public/assets/jquery_ui/jquery-ui.css') }}">
<link rel="stylesheet" href="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.css') }}">
<link rel="stylesheet" href="{{ asset('public/assets/plugins/timepicker/bootstrap-timepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/assets/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
<style type="text/css">

</style>
@endpush


@section('content_header')

@if( isset($parentLngCont) )
<section class="content-header">
  <h1>
    @if(isset($event) && !empty($event))
    Edit Event
    @else
    Add New Event
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('evts_lst') }}">All Events</a></li>
     @if(isset($event) && !empty($event))
    <li class="active">Edit Event</li>
     @else
    <li class="active">Create New Event</li>
    @endif
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
      <a href="{{ route('evts_lst') }}" class="btn btn-primary"> All Events</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">@if(isset($event) && !empty($event)) Edit Event @else Create Event @endif</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name="jfrm" id="frmx" action="@if(isset($event)){{ route('evt.adedlngPst', array('pid' => $parentLngCont->id, 'cid' => $event->id)) }}@else{{ route('evt.adedlngPst', array('pid' => $parentLngCont->id)) }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          
          <div class="row">
            @if(isset($event) && isset($event->Language)) 
            <div class="col-md-3">
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">
                    <span>
                    <img src="{{ asset('public/uploads/flags/thumb/'. $event->Language->flag) }}" style="height: 20px;" id="flag">
                    </span>
                  </div>
                  <select name="language_id" id="language_id" class="form-control">
                    <option value="{{ $event->language_id }}">{{ $event->Language->name }}</option>
                  </select>
                </div>
              </div>
            </div>
            @else
            <div class="col-md-3">
              <div class="form-group">
                <label>Select Language :</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <span>
                      <img src="" style="height: 20px;" id="flag">
                    </span>
                  </div>
                  @php
                    $usedLng = array();
                    if( isset($parentLngCont->ChildLanguages) ) {
                      foreach( $parentLngCont->ChildLanguages as $x ) {
                        array_push($usedLng, $x->language_id);
                      }
                    }
                  @endphp
                  <select name="language_id" id="language_id" class="form-control">
                  @if( isset($languages) && !empty($languages) )
                    <option data-image="" value="">-Select Language-</option>
                    @foreach( $languages as $lng )
                      @if( $lng->is_default != '1' && !in_array($lng->id, $usedLng) )
                        <option data-image="{{ asset('public/uploads/flags/thumb/'. $lng->flag) }}" value="{{ $lng->id }}">{{ $lng->name }}</option>
                      @endif
                    @endforeach
                  @endif
                  </select>
                </div>
              </div>
            </div>
            @endif
            <div class="col-md-9" style="text-align: right;">
            @if(isset($event))
            <a href="{{ route('evt.adedlngDel', array('pid' => $parentLngCont->id, 'cid' => $event->id)) }}" class="btn btn-danger pull-right" onclick="return confirm('Are You Sure Want To Delete This Content ?')">Delete This Content</a>
            @endif
            </div>
          </div>


          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Event Name (H1): <em>*</em></label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Event Name" value="@if(isset($event) && !empty($event)){{ $event->name }}@endif">
                @if($errors->has('name'))
                <span class="roy-vali-error"><small>{{$errors->first('name')}}</small></span>
                @endif
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Select Categories :</label>
                <select name="category_id[]" class="form-control" id="category_id" multiple="multiple">
                @if(isset($cats))
                  @foreach( $cats as $c )
                  <option value="{{ $c->id }}" @if(isset($eventCats) && !empty($eventCats) && in_array($c->id, $eventCats)) selected="selected" @endif>{{ ucwords($c->name) }}</option>
                  @endforeach
                @endif
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Link/URL : <em>*</em></label>
                <input type="text" name="slug" id="slug" class="form-control" placeholder="Enter Event URL" value="@if(isset($event) && !empty($event)){{ $event->slug }}@endif" @if(isset($event) && !empty($event)) readonly="readonly" @endif>
                @if($errors->has('slug'))
                <span class="roy-vali-error"><small>{{$errors->first('slug')}}</small></span>
                @endif
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Short Description : <em>*</em></label>
                <textarea class="form-control" name="description">@if(isset($event) && !empty($event)){{ html_entity_decode($event->description, ENT_QUOTES) }}@endif</textarea>
              </div>
            </div>
          </div>


          <!------------------------------------------------------------------------------------------------------->
          <!-- META INFO -->
          <div class="row">
            <div class="col-md-10">
              <h3>Page Meta Information</h3>
              <hr/>
            </div>
            <div class="col-md-10">
              <div class="form-group">
                <label>Meta Title:</label>
                <input type="text" name="meta_title" class="form-control" placeholder="Meta Title" value="@if( isset($event) ){{ $event->meta_title }}@endif">
              </div>
              <div class="form-group">
                <label>Meta Keywords:</label>
                <input type="text" name="meta_keyword" class="form-control" placeholder="Meta Keywords" value="@if( isset($event) ){{ $event->meta_keyword }}@endif">
              </div>
              <div class="form-group">
                <label>Meta Description:</label>
                <textarea name="meta_desc" class="form-control" placeholder="Meta Description">@if( isset($event) ){{ $event->meta_desc }}@endif</textarea>
              </div>
              <div class="form-group">
                <label>Canonical Url:</label>
                <input type="text" name="canonical_url" class="form-control" placeholder="Any Canonical url" value="@if( isset($event) ){{ $event->canonical_url }}@endif">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Language Tag:</label>
                <input type="text" name="lng_tag" class="form-control" placeholder="Language Tag" value="@if( isset($event) ){{ $event->lng_tag }}@endif">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Add No Follow Tag :</label>
                <select name="follow" class="form-control">
                  <option value="0" @if(isset($event) && $event->follow == '0') selected="selected" @endif>NO</option>
                  <option value="1" @if(isset($event) && $event->follow == '1') selected="selected" @endif>YES</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Add No Index Tag :</label>
                <select name="index_tag" class="form-control">
                  <option value="0" @if(isset($event) && $event->index_tag == '0') selected="selected" @endif>NO</option>
                  <option value="1" @if(isset($event) && $event->index_tag == '1') selected="selected" @endif>YES</option>
                </select>
              </div>
            </div>
          </div>
          <!-- END META INFO -->
          <!------------------------------------------------------------------------------------------------------->


          
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Desktop Event Details : <em>*</em></label>
                @if( isset($event) )
                <span>
                  <a href="{{ route('previewTool') }}?url={{ route('preview', array('device' => 'desktop', 'slug' => $event->slug)) }}&device=desktop" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Desktop Preview">
                    <i class="fa fa-desktop fa-2x" aria-hidden="true"></i>
                  </a>
                </span>
                @endif
                <!--div style="text-align: right;">
                  <input type="button" class="addEleBtn" title="Add To Desktop Content" data="pgCont" value="Add Elements">
                </div-->
                <textarea name="page_content" class="form-control" id="pgCont" data-error-container="#evetDet_error">@if(isset($event) && !empty($event)){{ html_entity_decode($event->page_content, ENT_QUOTES) }}@endif</textarea>
                <div id="evetDet_error"></div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-10">
              <!-- Collapse -->
              <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#when">When : Date and Time</a>
                    </h4>
                  </div>
                  <div id="when" class="panel-collapse collapse">
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>Start Date : <em>*</em></label>
                            <input type="text" name="start_date" class="form-control datepicker" value="@if(isset($event) && !empty($event)){{ date('d-m-Y', strtotime($event->start_date)) }}@endif">
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>Start Time : </label>
                            <input type="text" name="start_time" class="form-control timepicker" value="@if(isset($event) && !empty($event)){{ date('h:i A', strtotime($event->start_time)) }}@endif">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-4">
                          <div class="form-group">
                            <label>End Date : </label>
                            <input type="text" name="end_date" class="form-control datepicker" value="@if(isset($event) && !empty($event)){{ date('d-m-Y', strtotime($event->end_date)) }}@endif">
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>End Time : </label>
                            <input type="text" name="end_time" class="form-control timepicker" value="@if(isset($event) && !empty($event)){{ date('h:i A', strtotime($event->end_time)) }}@endif">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div><!-- End When -->
                <div class="panel panel-default">
                  <div class="panel-heading">
                    <h4 class="panel-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#where">Where : Venue or Place</a>
                    </h4>
                  </div>
                  <div id="where" class="panel-collapse collapse">
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Venue Name : <em>*</em></label>
                            <input type="text" name="venue_name" class="form-control" placeholder="Enter Venue Name" value="@if(isset($event) && !empty($event)){{ $event->venue_name }}@endif">
                          </div>
                          <div class="form-group">
                            <label>Venue Address : <em>*</em></label>
                            <textarea name="venue_address" class="form-control" placeholder="Enter Venue Address">@if(isset($event) && !empty($event)){{ $event->venue_address }}@endif</textarea>
                          </div>
                          <div class="form-group">
                            <label>Pincode : </label>
                            <input type="text" name="pincode" class="form-control" placeholder="Enter Pincode" value="@if(isset($event) && !empty($event)){{ $event->pincode }}@endif">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Country : <em>*</em></label>
                            <select name="country_id" id="country_id" class="form-control select2 arS2" style="width: 100%;">
                              <option value="">-Select Country-</option>
                              @if(isset($countries) && !empty($countries))
                                @foreach($countries as $cnt)
                                <option value="{{ $cnt->id }}" @if( isset($event) && !empty($event) && $event->country_id == $cnt->id ) selected="selected" @endif>{{ $cnt->country_name }}</option>
                                @endforeach
                              @endif
                            </select>
                          </div>
                          <div class="form-group">
                            <label>Province : <em>*</em></label>
                            <select name="province_id" id="province_id" class="form-control select2 arS2" style="width: 100%;">
                              <option value="">-Select Province-</option>
                              @if( isset($SelectedProvince) && !empty($SelectedProvince) )
                                @foreach( $SelectedProvince as $p )
                                <option value="{{ $p->id }}" @if( isset($event) && !empty($event) && $event->province_id == $p->id ) selected="selected" @endif>{{ ucfirst($p->province_name) }}</option>
                                @endforeach
                              @endif
                            </select>
                          </div>
                          <div class="form-group">
                            <label>City : <em>*</em></label>
                            <select name="city_id" id="city_id" class="form-control select2 arS2" style="width: 100%;">
                              <option value="">-Select City-</option>
                              @if( isset($SelectedCity) && !empty($SelectedCity) )
                                @foreach( $SelectedCity as $c )
                                <option value="{{ $c->id }}" @if( isset($event) && !empty($event) && $event->city_id == $c->id ) selected="selected" @endif>{{ ucfirst($c->city_name) }}</option>
                                @endforeach
                              @endif
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div><!-- End Where -->
              </div> 
              <!-- End Collapse -->
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Event Color :</label> <span><small>For Calendar View</small></span>
                <input type="text" name="color" class="form-control colorpicker" style="width: 100px;" value="@if(isset($event) && !empty($event)){{ $event->color }}@endif">
              </div>
              <div class="form-group">
                <label>Status : </label>
                <input type="radio" name="status" value="1" @if(isset($event)) @if(!empty($event) && $event->status == '1') checked="checked" @endif @else checked="checked" @endif> Active
                <input type="radio" name="status" value="2" @if(isset($event) && !empty($event) && $event->status == '2') checked="checked" @endif> Inactive
              </div>
            </div>
            <div class="col-md-6">
              
            </div>
          </div>


          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                
                <!-- CALL SCRIPT -->
                <button type="button" class="btn btn-default addMedImgBtn" title="Event Image" data="eventImage"><i class="fa fa-picture-o" aria-hidden="true"></i> Add Event Image</button>
                
                <input type="hidden" id="eventImage-idholder" name="event_image_ids">

                <input type="hidden" id="eventImage-infoholder" name="event_image_infos">
                
                <div id="eventImage-dispDiv"></div>
                <!-- END -->

              </div>
            </div>
          </div>

          <div class="row">
              <div class="col-md-12">
              @if( isset($event) && isset($event->allImgIds) && count($event->allImgIds) > 0 )
                @php $i = 0; @endphp
                @foreach( $event->allImgIds as $imgs )
                @if( $imgs->image_type == 'MAIN_IMAGE' )
                  @if( isset($imgs->imageInfo) && $i == 0 )
                  <div class="col-md-3">
                  <div class="thumbnail">
                    <img src="{{ asset('public/uploads/files/media_images/thumb/'.$imgs->imageInfo->image) }}">
                    <div class="caption">{{ sizeFilter($imgs->imageInfo->size) }}</div>
                    <a href="javascript:void(0);" class="idel ifdel" data="event_images_map" id="{{ $imgs->id }}">
                      <i class="fa fa-times base-red" aria-hidden="true"></i>
                    </a>
                  </div>
                  </div>
                  @php $i++; @endphp
                  @endif
                @endif
                @endforeach
              @endif
              </div>
          </div>



          <div class="row">
            <div class="col-md-6">
              @if( isset($event) )
              <input type="submit" class="btn btn-primary" value="Save Changes">
              <input type="hidden" id="table_id" value="{{ $event->id }}">
              @else
              <input type="submit" class="btn btn-primary" value="Save Event">
              <input type="hidden" id="table_id" value="0">
              @endif
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

{{-- @include('dashboard.modals.editor_element_modal') --}}

@include('dashboard.modals.editor_imgmedia_modal')

@endif

@endsection


@push('page_js')
<script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('public/assets/jquery_ui/jquery-ui.js') }}"></script>
<script src="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.js') }}"></script>
<script src="{{ asset('public/assets/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ asset('public/assets/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
<script type="text/javascript">
$( function() {
  $( ".datepicker" ).datepicker({
      minDate:0,
      dateFormat: 'dd-mm-yy',
      changeMonth: true,
      changeYear: true
  });
  $('.timepicker').timepicker({
    showInputs: false
  });
  $('.colorpicker').colorpicker({
    <?php if( !isset($event) ) { ?>
    color: "#337ab7",
    <?php } ?>
    format: "hex"
  });
  $('#category_id').multiselect({
    buttonWidth : '100%',
    includeSelectAllOption : true,
    nonSelectedText: 'Select Categories',
    enableFiltering: true,
    filterPlaceholder: 'Search Categories..',
    enableCaseInsensitiveFiltering: true,
    //enableClickableOptGroups: true,
    //enableCollapsibleOptGroups: true,
    selectAllText: 'All Categories',
    maxHeight: 300
  });
  <?php if( !isset($event) ) { ?>
  $('#province_id, #city_id').attr('disabled', 'disabled');
  $('input[name="name"]').on('blur', function() {
    if( $.trim( $(this).val() ) != '' ) {
      $('input[name="slug"]').val( string_to_slug($(this).val()) );
    } else {
      $('input[name="slug"]').val('');
    }
  });
  <?php } ?>
  $('.arS2').on('change', function() {
    if( $.trim( $(this).val() ) != '' ) {
      $('#'+$(this).attr('id')+'-error').html('');
      $(this).parent('.form-group').removeClass('has-error');
    } else {
      $(this).parent('.form-group').addClass('has-error');
    }
  });
  $('#country_id').on('change', function() {
    if( $.trim($(this).val()) != '' ) {
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
    if( $.trim($(this).val()) != '' ) {
      $.ajax({
        type : "POST",
        url : "{{ route('ajx_cityList') }}",
        data : "province_id="+$(this).val()+"&_token={{ csrf_token() }}",
        cache : false,
        beforeSend : function() {

        },
        success : function(resp) {
          var opHTML = '<option value="">-Select Province-</option>';
          var jArr = JSON.parse(resp);
          var jArrLen = jArr.length;
          if( jArrLen > 0 ) {
            $('#city_id').removeAttr('disabled');
            for( var i = 0; i < jArrLen; i++) {
              opHTML += '<option value="'+ jArr[i].id +'">'+ jArr[i].city_name +'</option>';
            }
          } else {
            $('#city_id').attr('disabled', 'disabled');
          }
          $('#city_id').html(opHTML);
        }
      });
    } 
  });
});
//var base_url = window.location.origin;
var editor = CKEDITOR.replace( 'pgCont', {
  customConfig: "{{ asset('public/assets/ckeditor/maxi_config.js') }}",
} );


/*jQuery.validator.addMethod("cke_required", function (value, element) {
    var idname = $(element).attr('id');
    var editor = CKEDITOR.instances.emBody;
    $(element).val(editor.getData());
    return $(element).val().length > 0;
}, "This field is required - tested working");*/

var fm = $('#frmx');
fm.on('submit', function() {
  CKEDITOR.instances.pgCont.updateElement();
});
fm.validate({
  errorElement: 'span',
  errorClass : 'roy-vali-error',
  ignore: [],
  normalizer: function( value ) {
    return $.trim( value );
  },
  rules: {

    name: {
      required: true,
      minlength: 3,
    },
    slug: {
      required: true,
      nowhitespace: true,
      pattern: /^[A-Za-z\d-.]+$/,
      remote:{
        url: "{{ route('checkSlugUrl') }}",
        type: "post",
        data: {
          "slug_url": function() {
            return $( "#slug" ).val();
          },
          "_token": function() {
            return "{{ csrf_token() }}";
          },
          "id": function() {
            return $( "#table_id" ).val();
          }
        }
      }
    },
    language_id: {
      required: true
    },
    page_content: {
      required: true
    },
    mob_page_content: {
      required: true
    },
    meta_title: {
      required: true
    },
    meta_keywords: {
      required: true
    },
    meta_description: {
      required: true
    },
    start_date: {
      required: true
    },
    end_date: {
      required: true
    },
    venue_name: {
      required: true
    },
    venue_address: {
      required: true
    },
    country_id: {
      required: true
    },
    province_id: {
      required: true
    },
    city_id: {
      required: true
    },
    description: {
      required: true
    }
  },
  messages: {

    name: {
      required: 'Please Enter Event Name.',
      remote: 'Event Name Already Exist, Try Another.'
    },
    slug: {
      required: 'Please Enter Page URL or Link.',
      nowhitespace: 'White Space or Blank Space Not Allowed, Use Hyphen.',
      pattern: 'Any Special Character Not Allowed, Except Hyphen.',
      remote: 'This URL Already Exist, Try Another.'
    },
    language_id: {
      required: 'Please Select Language.'
    },
    page_content: {
      required: 'Please Enter Event Details.'
    },
    mob_page_content: {
      required: 'Please Enter Event Details.'
    },
    meta_title: {
      required: 'Please Enter Meta Title.'
    },
    meta_keywords: {
      required: 'Please Enter Meta Keywords.'
    },
    meta_description: {
      required: 'Please Enter Meta Description.'
    },
    start_date: {
      required: 'Please Select Start Date.'
    },
    end_date: {
      required: 'Please Select End Date.'
    },
    venue_name: {
      required: 'Please Enter Venue Name.'
    },
    venue_address: {
      required: 'Please Enter Venue Address.'
    },
    country_id: {
      required: 'Please Select Country.'
    },
    province_id: {
      required: 'Please Select Province.'
    },
    city_id: {
      required: 'Please Select City.'
    },
    description: {
      required: 'Please enter description.'
    }
  },
  errorPlacement: function (error, element) { 
    element.parent('.form-group').addClass('has-error');
    //element.parents('.panel-collapse.collapse').collapse('show');
    if (element.attr("data-error-container")) { 
      error.appendTo(element.attr("data-error-container"));
    } else if(element.attr('id') == 'language_id') {
      error.insertAfter(element.parent('div'));
    } else {
      error.insertAfter(element); 
    }
  },
  invalidHandler: function(e,validator) {
      for (var i=0;i<validator.errorList.length;i++){   
        $(validator.errorList[i].element).parents('.panel-collapse.collapse').collapse('show');
      }
  },
  success: function (label) {
    label.closest('.form-group').removeClass('has-error');
  }
});
function string_to_slug(str) {
  str = str.replace(/^\s+|\s+$/g, ""); // trim
  str = str.toLowerCase();

  // remove accents, swap ñ for n, etc
  var from = "åàáãäâèéëêìíïîòóöôùúüûñç·/_,:;";
  var to = "aaaaaaeeeeiiiioooouuuunc------";

  for (var i = 0, l = from.length; i < l; i++) {
    str = str.replace(new RegExp(from.charAt(i), "g"), to.charAt(i));
  }

  str = str
    .replace(/[^a-z0-9 -]/g, "") // remove invalid chars
    .replace(/\s+/g, "-") // collapse whitespace and replace by -
    .replace(/-+/g, "-") // collapse dashes
    .replace(/^-+/, "") // trim - from start of text
    .replace(/-+$/, ""); // trim - from end of text

  return str;
}
</script>

{{-- @include('dashboard.modals.editor_element_modal_script') --}}

@include('dashboard.modals.editor_imgmedia_modal_script')

@endpush