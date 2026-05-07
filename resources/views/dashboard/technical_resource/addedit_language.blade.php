@extends('dashboard.layouts.app')

@push('page_css')
<link rel="stylesheet" href="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.css') }}">
@endpush

@section('content_header')

@if( isset($parentLngCont) )
<section class="content-header">
  <h1>
    @if(isset($resource))
    Edit Technical Resource
    @else
    Add New Technical Resource
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('allResource') }}">All Technical Resource</a></li>
  </ol>
</section>
@endsection

@section('content')
<section class="content">

  <form name="jfrm" id="frmx" action="@if( isset($resource) ){{ route('techr.adedlngPst', array('pid' => $parentLngCont->id, 'cid' => $resource->id)) }}@else{{ route('techr.adedlngPst', array('pid' => $parentLngCont->id)) }}@endif" method="post" enctype="multipart/form-data">
  {{ csrf_field() }}


  @if(Session::has('msg'))
  <div class="ar-hide @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}</div>
  @endif

  <div class="row">
    <div class="col-md-6">
      <a href="{{ route('allResource') }}" class="btn btn-primary"> All Technical Resource</a>

      <input type="submit" class="btn btn-success" value="Save Changes">
    </div>
    <div class="col-md-6">
    </div>
  </div>


  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">@if(isset($resource)) Edit Technical Resource @else Add Technical Resource @endif</h3>
        </div>
        
        <div class="box-body">

          <div class="row">
            @if(isset($resource) && isset($resource->Language)) 
            <div class="col-md-3">
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">
                    <span>
                    <img src="{{ asset('public/uploads/flags/thumb/'. $resource->Language->flag) }}" style="height: 20px;" id="flag">
                    </span>
                  </div>
                  <select name="language_id" id="language_id" class="form-control">
                    <option value="{{ $resource->language_id }}">{{ $resource->Language->name }}</option>
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
            @if(isset($resource))
            <a href="{{ route('techr.adedlngDel', array('pid' => $parentLngCont->id, 'cid' => $resource->id)) }}" class="btn btn-danger pull-right" onclick="return confirm('Are You Sure Want To Delete This Content ?')">Delete This Content</a>
            @endif
            </div>
          </div>
          
          


          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Name or Heading (H1): <em>*</em></label>
                <input type="text" name="name" id="techName" class="form-control" placeholder="Enter Technical Resource Name" value="@if( isset($resource) ){{ $resource->name }}@endif">
              </div>
              
              <div class="form-group">
                <label>Short Description :</label>
                <textarea name="description" class="form-control" id="tech_desc" rows="6">@if( isset($resource) ){{ html_entity_decode($resource->description, ENT_QUOTES) }}@endif</textarea>
              </div>
              <div class="form-group">
                <label>Select Section : <em>*</em></label>
                <select name="tab_section" class="form-control">
                  <option value="">-Select Display Tab Section-</option>
                  <option value="PRODUCT" @if( isset($resource) && $resource->tab_section == 'PRODUCT') selected="selected" @endif>PRODUCT Tab</option>
                  <option value="MULTOTEC_GROUP" @if( isset($resource) && $resource->tab_section == 'MULTOTEC_GROUP') selected="selected" @endif>MULTOTEC GROUP Tab</option>
                  <option value="INDUSTRY_INSIGHTS" @if( isset($resource) && $resource->tab_section == 'INDUSTRY_INSIGHTS') selected="selected" @endif>INDUSTRY INSIGHTS Tab</option>
                </select>
              </div>
              <div class="form-group">
                @php
                if( isset($resource) && isset($resource->procatIds) && count($resource->procatIds) ) {
                  $arr3 = array();
                  foreach($resource->procatIds as $v) {
                    array_push($arr3, $v->product_category_id);
                  }
                }
                @endphp
                <label>Select Product Categories :</label>
                <select name="product_category_id[]" class="form-control">
                  <option value="">Select Product Category</option>
                  @if( !empty($allProCats) )
                    @foreach( $allProCats as $ct )
                    <option value="{{ $ct->id }}" @if( isset($resource) && isset($arr3) && in_array($ct->id, $arr3) ) selected="selected" @endif>{{ $ct->name }}</option>
                    @endforeach
                  @endif
                </select>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Any Content :</label>
                <!--div style="text-align: right;">
                  <input type="button" class="addEleBtn" title="Add To Desktop Content" data="pgCont" value="Add Elements">
                </div-->
                <textarea name="page_content" id="pgCont" class="form-control">@if( isset($resource) ){{ html_entity_decode($resource->page_content, ENT_QUOTES) }}@endif</textarea>
              </div>
            </div>
          </div>



          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                
                <!-- CALL SCRIPT -->
                <button type="button" class="btn btn-default addMedImgBtn" title="Technical Resource Image" data="techRes_Box"><i class="fa fa-picture-o" aria-hidden="true"></i> Add Technical Resource Image</button>
                
                <input type="hidden" id="techRes_Box-idholder" name="main_image_ids">

                <input type="hidden" id="techRes_Box-infoholder" name="main_image_infos">
                
                <div id="techRes_Box-dispDiv"></div>
                <!-- END -->

              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Upload PDF file:</label>
                <input type="file" name="techpdf">
              </div>
            </div>
          </div>


          <div class="row">
              <div class="col-md-6">
              @if( isset($resource) && isset($resource->ImageIds) && count($resource->ImageIds) > 0 )
                @php $i = 0; @endphp
                @foreach( $resource->ImageIds as $imgs )
                @if( $imgs->image_type == 'MAIN_IMAGE' )
                  @if( isset($imgs->imageInfo) && $i == 0 )
                  <div class="col-md-3">
                  <div class="thumbnail">
                    <img src="{{ asset('public/uploads/files/media_images/thumb/'.$imgs->imageInfo->image) }}">
                    <div class="caption">{{ sizeFilter($imgs->imageInfo->size) }}</div>
                    <a href="javascript:void(0);" class="idel ifdel" data="tech_resource_images_map" id="{{ $imgs->id }}">
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
              <div class="col-md-6">
              @if( isset($resource) && isset($resource->FileIds) && count($resource->FileIds) > 0 )
                @php $i = 0; @endphp
                @foreach( $resource->FileIds as $fils )
                @if( $fils->file_type == 'MAIN_FILE' )
                  @if( isset($fils->fileInfo) && $i == 0 )
                  
                    <li class="nodot">
                      <a href="{{ asset('public/uploads/files/media_files/'. $fils->fileInfo->file) }}" target="_blank">
                        <i class="fa fa-paperclip" aria-hidden="true"></i> Download
                      </a>
                      <a href="javascript:void(0);" class="fdel ifdel" data="tech_resource_files_map" id="{{ $fils->id }}">
                        <i class="fa fa-times base-red" aria-hidden="true"></i>
                      </a>
                    </li>
                  
                  @php $i++; @endphp
                  @endif
                @endif
                @endforeach
              @endif
              </div>
          </div>
          
          
          
          <div class="row" style="margin-top: 30px;">
            <div class="col-md-6">
              @if( isset($resource) )
              <input type="submit" class="btn btn-primary" value="Save Changes">
              <a href="{{ route('allResource') }}" class="btn btn-danger"> Cancel</a>
              <input type="hidden" id="table_id" value="{{ $resource->id }}">
              @else
              <input type="submit" class="btn btn-primary" value="Save All">
              <input type="hidden" id="table_id" value="0">
              @endif
            </div>
            <div class="col-md-6"></div>
          </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->
    </div>
  </div>

  
  </form>

</section>




@include('dashboard.modals.editor_imgmedia_modal')

@endif

@endsection




@push('page_js')
<script src="{{ asset('public/assets/jquery_ui/jquery-ui.js') }}"></script>
<script src="{{ asset('public/assets/shortable/Sortable.min.js') }}"></script>
<script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.js') }}"></script>

<script type="text/javascript">
$( function() {
  $('#persona_id').multiselect({
    buttonWidth : '100%',
    includeSelectAllOption : true,
    nonSelectedText: 'Select Personas',
    enableFiltering: true,
    filterPlaceholder: 'Search Personas..',
    enableCaseInsensitiveFiltering: true,
    //enableClickableOptGroups: true,
    //enableCollapsibleOptGroups: true,
    selectAllText: 'All Personas',
    maxHeight: 300
  });
  $('#industry_id').multiselect({
    buttonWidth : '100%',
    includeSelectAllOption : true,
    nonSelectedText: 'Select Industries',
    enableFiltering: true,
    filterPlaceholder: 'Search Industries..',
    enableCaseInsensitiveFiltering: true,
    //enableClickableOptGroups: true,
    //enableCollapsibleOptGroups: true,
    selectAllText: 'All Industries',
    maxHeight: 300
  });
  $('#product_category_id').multiselect({
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
});



var editor_pg_cont = CKEDITOR.replace( 'pgCont', {
  customConfig: "{{ asset('public/assets/ckeditor/maxi_config.js') }}",
} );

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
      minlength: 3
    },
    language_id: {
      required: true
    },
    techpdf: {
      extension: "pdf"
    },
    tab_section: {
      required: true
    },
    "product_category_id[]": {
      required: true
    }
  },
  messages: {

    name: {
      required: 'Please Enter Name.'
    },
    language_id: {
      required: 'Please Select Language.'
    },
    techpdf: {
      extension: 'please choose PDF file.'
    },
    tab_section: {
      required: 'Please select display tab.'
    },
    "product_category_id[]": {
      required: 'Please select product category.'
    }
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
</script>



@include('dashboard.modals.editor_imgmedia_modal_script')


@endpush