@extends('dashboard.layouts.app')

@push('page_css')
<link rel="stylesheet" href="{{ asset('public/assets/jquery_ui/jquery-ui.css') }}">
@endpush

@section('content_header')

@if( isset($parentLngCont) )
<section class="content-header">
  <h1>
    @if(isset($job))
    Edit Job
    @else
    Add New Job
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('allJobs') }}">All Jobs</a></li>
    @if(isset($job))
    <li class="active">Edit Job</li>
    @else
    <li class="active">Add Job</li>
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
      <a href="{{ route('allJobs') }}" class="btn btn-primary"> All Jobs</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">@if(isset($job)) Edit Job @else Add New Job @endif</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name="frm" id="frmx" action="@if( isset($job) ){{ route('carr.adedlngPst', array('pid' => $parentLngCont->id, 'cid' => $job->id)) }}@else{{ route('carr.adedlngPst', array('pid' => $parentLngCont->id)) }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}

          <div class="row">
            @if(isset($job) && isset($job->Language)) 
            <div class="col-md-3">
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">
                    <span>
                    <img src="{{ asset('public/uploads/flags/thumb/'. $job->Language->flag) }}" style="height: 20px;" id="flag">
                    </span>
                  </div>
                  <select name="language_id" id="language_id" class="form-control">
                    <option value="{{ $job->language_id }}">{{ $job->Language->name }}</option>
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
            @if(isset($job))
            <a href="{{ route('carr.adedlngDel', array('pid' => $parentLngCont->id, 'cid' => $job->id)) }}" class="btn btn-danger pull-right" onclick="return confirm('Are You Sure Want To Delete This Content ?')">Delete This Content</a>
            @endif
            </div>
          </div>

          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label>Job Heading or Title (H1): <em>*</em></label>
                <input type="text" name="name" id="jobTitle" class="form-control" placeholder="Enter Job Title" value="@if( isset($job) ){{ $job->name }}@endif">
              </div>
              <div class="form-group">
                <label>URL or Link : <em>*</em></label>
                <input type="text" name="slug" id="pgSlug" class="form-control" placeholder="Enter URL or Link" value="@if( isset($job) ){{ $job->slug }}@endif" @if( isset($job) ) readonly="readonly" @endif>
              </div>
              <div class="form-group">
                <label>Designation : <em>*</em></label>
                <input type="text" name="designation" class="form-control" placeholder="Enter Job Designation" value="@if( isset($job) ){{ $job->designation }}@endif">
              </div>
              <div class="form-group">
                <label>Experience : <em>*</em></label>
                <input type="text" name="experience" class="form-control" placeholder="Enter Job Required Experience" value="@if( isset($job) ){{ $job->experience }}@endif">
              </div>
              <div class="form-group">
                <label>Select Country : <em>*</em></label>
                <select name="country_id" id="country_id" class="form-control select2">
                  <option value="">-Select Country-</option>
                  @if( isset($allCountries) && !empty($allCountries) )
                    @foreach( $allCountries as $c )
                    <option value="{{ $c->id }}" @if( isset($job) && $job->country_id == $c->id ) selected="selected" @endif>{{ $c->country_name }}</option>
                    @endforeach
                  @endif
                </select>
                <span id="country_id-error"></span>
              </div>
            </div>
            <div class="col-md-4">
              <div class="panel panel-default">
                <div class="panel-heading">Publish Information</div>
                <div class="panel-body">
                  <div class="form-group">
                    <label>Status :</label>
                    <select name="status" class="form-control">
                      <option value="1" @if( isset($job) && $job->status == '1' ) selected="selected" @endif>Active</option>
                      <option value="2" @if( isset($job) && $job->status == '2' ) selected="selected" @endif>Inactive</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Publish or Draft ?</label>
                    <select name="publish_status" class="form-control">
                      <option value="1" @if( isset($job) && $job->publish_status == '1' ) selected="selected" @endif>Published</option>
                      <option value="0" @if( isset($job) && $job->publish_status == '0' ) selected="selected" @endif>Save As Draft</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Expiry Date :</label>
                    <input type="text" name="expiry_date" class="form-control datepicker" placeholder="Enter Expiry Date" value="@if( isset($job) ){{ date('d-m-Y', strtotime($job->expiry_date)) }}@endif">
                  </div>
                  <div class="form-group">
                    @if( isset($job) )
                    <input type="submit" class="btn btn-primary" value="Save Changes">
                    @else
                    <input type="submit" class="btn btn-primary" value="Save">
                    <a href="{{ route('addJob') }}" class="btn btn-danger pull-right">Cancel</a>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Job Location : <em>*</em></label>
                <textarea name="job_location" class="form-control" placeholder="Enter Job Location" style="height: 90px;">@if( isset($job) ){{ $job->job_location }}@endif</textarea>
              </div>
              <div class="form-group">
                <label>Desktop Job Description or Content : </label>
                @if( isset($job) )
                <span>
                  <a href="{{ route('previewTool') }}?url={{ route('preview', array('device' => 'desktop', 'slug' => $job->slug)) }}&device=desktop" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Desktop Preview">
                    <i class="fa fa-desktop fa-2x" aria-hidden="true"></i>
                  </a>
                </span>
                @endif
                <div style="text-align: right;">
                  <input type="button" class="addEleBtn" title="Add To Desktop Content" data="pg_cont" value="Add Elements">
                </div>
                <textarea name="page_content" id="pg_cont" class="form-control">@if( isset($job) ){{ html_entity_decode($job->page_content, ENT_QUOTES) }}@endif</textarea>
              </div>
              <div class="form-group">
                <label>Mobile Job Description or Content : </label>
                @if( isset($job) )
                <span>
                  <a href="{{ route('previewTool') }}?url={{ route('preview', array('device' => 'mobile', 'slug' => $job->slug)) }}&device=mobile" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Mobile Preview">
                    <i class="fa fa-mobile fa-2x" aria-hidden="true"></i>
                  </a>
                </span>
                @endif
                <div style="text-align: right;">
                  <input type="button" class="addEleBtn" title="Add To Mobile Content" data="mob_pg_cont" value="Add Elements">
                </div>
                <textarea name="mob_page_content" id="mob_pg_cont" class="form-control">@if( isset($job) ){{ html_entity_decode($job->mob_page_content, ENT_QUOTES) }}@endif</textarea>
              </div>
              <div class="form-group">
                <label>Meta Title :</label>
                <input type="text" name="meta_title" class="form-control" placeholder="Enter Meta Title" value="@if( isset($job) ){{ $job->meta_title }}@endif">
              </div>
              <div class="form-group">
                <label>Meta Keywords :</label>
                <input type="text" name="meta_keywords" class="form-control" placeholder="Enter Meta Keywords" value="@if( isset($job) ){{ $job->meta_keywords }}@endif">
              </div>
              <div class="form-group">
                <label>Meta Description :</label>
                <textarea name="meta_description" class="form-control" placeholder="Enter Meta Description">@if( isset($job) ){{ $job->meta_description }}@endif</textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="panel panel-default">
                <div class="panel-heading">File Uploads</div>
                <div class="panel-body">
                  <div class="form-group">
                    <label>Upload Any Image(s) :</label>
                    <input type="file" name="images[]" accept="image/*" multiple="multiple">
                  </div>
                  @if(isset($job) && isset($job->Images))
                  <div class="form-group"> 
                    @foreach($job->Images as $img)
                      @if(isset($img->imageInfo))
                      <div class="thumbnail">
                        <img src="{{ asset('public/uploads/files/media_images/thumb/'. $img->imageInfo->image) }}">
                        <div class="caption">{{ sizeFilter($img->imageInfo->size) }}</div>
                        <a href="javascript:void(0);" class="idel ifdel" data="career_images_map" id="{{ $img->id }}">
                          <i class="fa fa-times base-red" aria-hidden="true"></i>
                        </a>
                      </div>
                      @endif
                    @endforeach
                  </div>
                  @endif
                  <div class="form-group">
                    <label>Upload Any File(s) :</label>
                    <input type="file" name="files[]" accept=".pdf,application/pdf,.csv,text/csv,.doc,.docx,application/msword,.xls,.xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel, application/vnd.msexcel" multiple="multiple">
                  </div>
                  @if(isset($job) && isset($job->Files))
                  <div class="form-group">
                    @foreach($job->Files as $fls)
                      @if(isset($fls->fileInfo))
                      <li class="nodot">
                        <a href="{{ asset('public/uploads/files/media_files/'. $fls->fileInfo->file) }}" target="_blank">
                          <i class="fa fa-paperclip" aria-hidden="true"></i> Download</a>
                        <a href="javascript:void(0);" class="fdel ifdel" data="career_files_map" id="{{ $fls->id }}">
                          <i class="fa fa-times base-red" aria-hidden="true"></i>
                        </a>
                      </li>
                      @endif
                    @endforeach
                  </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-8">
              @if( isset($job) )
              <input type="submit" class="btn btn-primary" value="Save Changes">
              <input type="hidden" id="table_id" value="{{ $job->id }}">
              @else
              <input type="submit" class="btn btn-primary" value="Save All">
              <a href="{{ route('addJob') }}" class="btn btn-danger">Cancel</a>
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

@include('dashboard.modals.editor_element_modal')

@endif

@endsection

@push('page_js')
<script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('public/assets/jquery_ui/jquery-ui.js') }}"></script>
<script type="text/javascript">
var editor_pg_cont = CKEDITOR.replace( 'pg_cont', {
  customConfig: "{{ asset('public/assets/ckeditor/maxi_config.js') }}",
} );
var editor_mob_pg_cont = CKEDITOR.replace( 'mob_pg_cont', {
  customConfig: "{{ asset('public/assets/ckeditor/maxi_config.js') }}",
} );
$('#frmx').validate({
  errorElement: 'span',
  errorClass : 'roy-vali-error',
  ignore: [],
  normalizer: function( value ) {
    return $.trim( value );
  },
  rules: {

    name: {
      required: true,
      minlength: 3
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
            return $( "#pgSlug" ).val();
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
    country_id: {
      required: true
    },
    designation: {
      required: true
    },
    experience: {
      required: true
    },
    job_location: {
      required: true
    },
    expiry_date: {
      required: true
    }
  },
  messages: {

    name: {
      required: 'Please Enter Job Title.'
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
    "images[]": {
      required: 'Please Select Image File(s).',
      accept: 'Please Select Image File(s).'
    },
    "files[]": {
      required: 'Please Select Valid File(s).',
      accept: 'Please Select Valid File(s).'
    },
    country_id: {
      required: 'Please Select Country.'
    },
    designation: {
      required: 'Please Enter Description.'
    },
    experience: {
      required: 'Please Enter Experience.'
    },
    job_location: {
      required: 'Please Enter Job Location.'
    },
    expiry_date: {
      required: 'Please Select Job Expiry Date.'
    }
  },
  errorPlacement: function(error, element) {
    element.parent('.form-group').addClass('has-error');
    if (element.attr("data-error-container")) { 
      error.appendTo(element.attr("data-error-container"));
    } else if(element.hasClass('select2')) {
      $('#' + element.attr('id') + '-error').html(error);
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
  $( ".datepicker" ).datepicker({
      minDate:0,
      dateFormat: 'dd-mm-yy',
      changeMonth: true,
      changeYear: true
  });
  <?php if( !isset($job) ) { ?>
  $('#jobTitle').on('blur', function() {
    if( $.trim( $(this).val() ) != '' ) {
      $('#pgSlug').val( string_to_slug( $(this).val() ) );
    }
  });
  <?php } ?>
  $('#country_id').on('change', function() {
    $('#country_id-error').html('');
    $(this).parent('.form-group').removeClass('has-error');
  });
});
function string_to_slug(str) {
  str = str.replace(/^\s+|\s+$/g, "");
  str = str.toLowerCase();
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

@include('dashboard.modals.editor_element_modal_script')
@endpush