@extends('dashboard.layouts.app')

@push('page_css')
<link rel="stylesheet" href="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.css') }}">
@endpush

@section('content_header')
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

  @if(Session::has('msg'))
  <div class="ar-hide @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}</div>
  @endif

  <div class="row">
    <div class="col-md-6">
      <a href="{{ route('allResource') }}" class="btn btn-primary"> All Technical Resource</a>
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

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name="jfrm" id="frmx" action="@if( isset($resource) ){{ route('updResource', array('id' => $resource->id)) }}@else{{ route('sveResource') }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}

          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Default Language :</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <span>
                    @if( isset($languages) && !empty($languages) )
                      @foreach( $languages as $lng )
                        @if( $lng->is_default == '1' )
                          <img src="{{ asset('public/uploads/flags/thumb/'. $lng->flag) }}" style="height: 20px;" id="flag">
                        @endif
                      @endforeach
                    @endif
                    </span>
                  </div>
                  <select name="language_id" id="language_id" class="form-control">
                  @if( isset($languages) && !empty($languages) )
                    @foreach( $languages as $lng )
                      @if( $lng->is_default == '1' )
                        <option value="{{ $lng->id }}">{{ $lng->name }}</option>
                      @endif
                    @endforeach
                  @endif
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-9" style="text-align: right;">
              @if( isset($resource) && !empty($resource) )
                @if( isset($resource->ChildLanguages) && count($resource->ChildLanguages) < count($languages) - 1 )
                <a href="{{ route('techr.adedlng', array('pid' => $resource->id)) }}"><i class="fa fa-plus"></i> Add Language ?</a>
                @endif
                @if( isset($resource->ChildLanguages) )
                  @foreach( $resource->ChildLanguages as $chl )
                    @if( isset($chl->Language) )
                      @php $Lng = $chl->Language; @endphp
                      <a href="{{ route('techr.adedlng', array('pid' => $resource->id, 'cid' => $chl->id)) }}" class="lngLink btn btn-xs">
                        <img src="{{ asset('public/uploads/flags/thumb/'. $Lng->flag) }}"> {{ $Lng->name }}
                      </a>
                    @endif
                  @endforeach
                @endif
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
                <label>Link or Url : <em>*</em></label>
                <input type="text" name="slug" id="pgSlug" class="form-control" placeholder="Enter Link or Page URL" value="@if( isset($resource) ){{ $resource->slug }}@endif" @if( isset($resource) ) readonly="readonly" @endif>
              </div>
              <div class="form-group">
                <label>Short Description :</label>
                <textarea name="description" class="form-control" id="tech_desc">@if( isset($resource) ){{ html_entity_decode($resource->description, ENT_QUOTES) }}@endif</textarea>
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
                <input type="text" name="meta_title" class="form-control" placeholder="Meta Title" value="@if( isset($resource) ){{ $resource->meta_title }}@endif">
              </div>
              <div class="form-group">
                <label>Meta Keywords:</label>
                <input type="text" name="meta_keyword" class="form-control" placeholder="Meta Keywords" value="@if( isset($resource) ){{ $resource->meta_keyword }}@endif">
              </div>
              <div class="form-group">
                <label>Meta Description:</label>
                <textarea name="meta_desc" class="form-control" placeholder="Meta Description">@if( isset($resource) ){{ $resource->meta_desc }}@endif</textarea>
              </div>
              <div class="form-group">
                <label>Canonical Url:</label>
                <input type="text" name="canonical_url" class="form-control" placeholder="Any Canonical url" value="@if( isset($resource) ){{ $resource->canonical_url }}@endif">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Language Tag:</label>
                <input type="text" name="lng_tag" class="form-control" placeholder="Language Tag" value="@if( isset($resource) ){{ $resource->lng_tag }}@endif">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Add No Follow Tag :</label>
                <select name="follow" class="form-control">
                  <option value="0" @if(isset($resource) && $resource->follow == '0') selected="selected" @endif>NO</option>
                  <option value="1" @if(isset($resource) && $resource->follow == '1') selected="selected" @endif>YES</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Add No Index Tag :</label>
                <select name="index_tag" class="form-control">
                  <option value="0" @if(isset($resource) && $resource->index_tag == '0') selected="selected" @endif>NO</option>
                  <option value="1" @if(isset($resource) && $resource->index_tag == '1') selected="selected" @endif>YES</option>
                </select>
              </div>
            </div>
          </div>
          <!-- END META INFO -->
          <!------------------------------------------------------------------------------------------------------->



          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Desktop Content : (Above the fold content)</label>
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
              <!--input type="submit" class="btn btn-primary" value="Save Changes"-->
              <input type="hidden" id="table_id" value="{{ $resource->id }}">
              <input type="hidden" name="insert_id" id="insert_id" value="{{ $resource->insert_id }}">
              @else
              <!--input type="submit" class="btn btn-primary" value="Save All"-->
              <input type="hidden" id="table_id" value="0">
              <input type="hidden" name="insert_id" id="insert_id" value="{{ $insert_id }}">
              @endif
            </div>
            <div class="col-md-6"></div>
          </div>
          <div class="row" id="shared-lists">
           @include('dashboard.page_builder.edit_box')
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

<!-- Page Builder -->
@include('dashboard.page_builder.fix_design') <!-- Page Builder Buttons -->

@include('dashboard.page_builder.seo.html_layout') <!-- Extra Seo -->

@include('dashboard.page_builder.content.html_layout') <!-- Content -->

@include('dashboard.page_builder.cta.html_layout') <!-- CTA -->

@include('dashboard.page_builder.sticky_button.html_layout') <!-- Sticky Button -->

@include('dashboard.page_builder.hero_pw.html_layout') <!-- Hero Statement PAGE WIDTH -->

@include('dashboard.page_builder.hero_cw.html_layout') <!-- Hero Statement CONTAINER WIDTH -->

@include('dashboard.page_builder.form.html_layout') <!-- E-Form -->

@include('dashboard.page_builder.image_carousel.html_layout') <!-- Image Carousel -->

@include('dashboard.page_builder.brochure.html_layout') <!-- Brochure -->

@include('dashboard.page_builder.video.html_layout') <!-- Video -->

@include('dashboard.page_builder.links.html_layout') <!-- links -->

@include('dashboard.page_builder.product_box.html_layout') <!-- product box -->

@include('dashboard.page_builder.custom_links.html_layout') <!-- custom links -->

@include('dashboard.page_builder.metric.html_layout') <!-- metric -->

@include('dashboard.page_builder.accordion.html_layout') <!-- accordion -->

@include('dashboard.page_builder.image_gal_btn.html_layout') <!-- Image Gallery -->

<!-- End Page Builder -->


@include('dashboard.modals.editor_imgmedia_modal')

{{--

@include('dashboard.modals.editor_element_modal')

--}}

@endsection

@push('page_js')
<script src="{{ asset('public/assets/jquery_ui/jquery-ui.js') }}"></script>
<script src="{{ asset('public/assets/shortable/Sortable.min.js') }}"></script>
<script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.js') }}"></script>

<script type="text/javascript">
$( function() {
  $('.pgb_rightControl #pageBuilderBtn').on('click', function() {
    $('.pgb_rightControl .cdiv').toggle('slide', { direction:'right' }, 200);
  });
});
</script>
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
    page_content: {
      required: function(element) {
        return $("#resourceType").val() == 1;
      }
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
    slug: {
      required: 'Please Enter Page URL or Link.',
      nowhitespace: 'White Space or Blank Space Not Allowed, Use Hyphen.',
      pattern: 'Any Special Character Not Allowed, Except Hyphen.',
      remote: 'This URL Already Exist, Try Another.'
    },
    language_id: {
      required: 'Please Select Language.'
    },
    "files[]": {
      accept: 'Please Select Valid File(s).'
    },
    "images[]": {
      accept: 'Please Select Image File(s).'
    },
    page_content: {
      required: 'Please Enter Technical Resource Content.'
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
$( function() {
  $('#is_right_panel_required').on('change', function() {
    if( $(this).is(':checked') && $(this).val() == '1' ) {
      $('#right_panel_content').slideDown();
    } else {
      CKEDITOR.instances.rtp_cont.setData('');
      $('#right_panel_content').slideUp();
    }
  });
  <?php if( !isset($resource) ) { ?>
  $('#techName').on('blur', function() {
    if( $.trim( $(this).val() ) != '' ) {
      $('#pgSlug').val( string_to_slug( $(this).val() ) );
    }
  });
  <?php } ?>
  $('#resourceType').on('change', function() {
    if( $(this).val() == '1' ) {
      $('#contentDiv').slideDown();
      $('#documentDiv').hide();
    }
    if( $(this).val() == '2' ) {
      $('#contentDiv').hide();
      $('#documentDiv').slideDown();
    }
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

@include('dashboard.page_builder.seo.script')
@include('dashboard.page_builder.content.script')
@include('dashboard.page_builder.cta.script')
@include('dashboard.page_builder.sticky_button.script')
@include('dashboard.page_builder.hero_pw.script')
@include('dashboard.page_builder.hero_cw.script')
@include('dashboard.page_builder.form.script')
@include('dashboard.page_builder.image_carousel.script')
@include('dashboard.page_builder.brochure.script')
@include('dashboard.page_builder.video.script')
@include('dashboard.page_builder.links.script')
@include('dashboard.page_builder.product_box.script')
@include('dashboard.page_builder.custom_links.script')
@include('dashboard.page_builder.metric.script')
@include('dashboard.page_builder.accordion.script')
@include('dashboard.page_builder.image_gal_btn.script')

@include('dashboard.page_builder.script')

@include('dashboard.modals.editor_imgmedia_modal_script')

{{--

@include('dashboard.modals.editor_element_modal_script')

--}}

@endpush