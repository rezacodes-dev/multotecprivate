@extends('dashboard.layouts.app')

@push('page_css')
<link rel="stylesheet" href="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.css') }}">
@endpush


@section('content_header')
<section class="content-header">
  <h1>
    @if(isset($content))
    Edit Branch
    @else
    Add Branch
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('allDistribConts') }}">All Branches</a></li>
    @if(isset($content))
    <li class="active">Edit Branch</li>
    @else
    <li class="active">Add Branch</li>
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
      <a href="{{ route('allDistribConts') }}" class="btn btn-primary"> All Branches</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">@if(isset($content)) Edit Branch @else Add Branch @endif</h3>

          <div class="box-tools pull-right">
            
          </div>
        </div>
        <div class="box-body">
          <form name="jfrm" id="frmx" action="@if( isset($content) ){{ route('updDistribCont', array('id' => $content->id)) }}@else{{ route('sveDistribCont') }}@endif" method="post" enctype="multipart/form-data">
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
              @if( isset($content) && !empty($content) )
                @if( isset($content->ChildLanguages) && count($content->ChildLanguages) < count($languages) - 1 )
                <a href="{{ route('distrb.adedcontlng', array('pid' => $content->id)) }}"><i class="fa fa-plus"></i> Add Language ?</a>
                @endif
                @if( isset($content->ChildLanguages) )
                  @foreach( $content->ChildLanguages as $chl )
                    @if( isset($chl->Language) )
                      @php $Lng = $chl->Language; @endphp
                      <a href="{{ route('distrb.adedcontlng', array('pid' => $content->id, 'cid' => $chl->id)) }}" class="lngLink btn btn-xs">
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
                <label>Select Country : <em>*</em></label>
                <select name="distributor_id" class="form-control select2">
                  <option value="">-Select Country-</option>
                  @if( isset($allDistrbs) )
                    @foreach( $allDistrbs as $ds )
                    <option value="{{ $ds->id }}" @if( isset($content) && $content->distributor_id == $ds->id ) selected="selected" @endif>{{ $ds->name }}</option>
                    @endforeach
                  @endif
                </select>
                <span class="roy-vali-error" id="distributor_id-error"></span>
              </div>
              <div class="form-group">
                <label>Branch Name  (H1): <em>*</em></label>
                <input type="text" name="name" id="contName" class="form-control" placeholder="Enter Content Name" value="@if( isset($content) ){{ $content->name }}@endif">
              </div>
              <div class="form-group">
                <label>Link or Url : <em>*</em></label>
                <input type="text" name="slug" id="pgSlug" class="form-control" placeholder="Enter Link or Page URL" value="@if( isset($content) ){{ $content->slug }}@endif" @if( isset($content) ) readonly="readonly" @endif>
              </div>
              <div class="form-group">
                <label>Short Description :</label>
                <textarea name="description" class="form-control">@if( isset($content) ){{ html_entity_decode($content->description, ENT_QUOTES) }}@endif</textarea>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-10">
              <h3>Branch Map Informations</h3>
              <hr/>
            </div>
            <div class="col-md-10">
              <div class="form-group">
                <label>Branch Type : <em>*</em></label>
                <select name="branch_type" class="form-control">
                  <option value="">-Select Branch Type-</option>
                  <option value="Multotec_Branch" @if( isset($content) && $content->branch_type == 'Multotec_Branch') selected="selected" @endif>Multotec Branch</option>
                  <option value="Regional_Representatives" @if( isset($content) && $content->branch_type == 'Regional_Representatives') selected="selected" @endif>Regional Representatives</option>
                </select>
              </div>
              <div class="form-group">
                <label>Map Heading :</label>
                <input type="text" name="map_heading" class="form-control" placeholder="Map Heading" value="@if( isset($content)){{ $content->map_heading }}@endif">
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Latitude : <em>*</em></label>
                    <input type="text" name="latitude" class="form-control" placeholder="Map Latitude" value="@if( isset($content)){{ $content->latitude }}@endif">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Longitude : <em>*</em></label>
                    <input type="text" name="longitude" class="form-control" placeholder="Map Longitude" value="@if( isset($content)){{ $content->longitude }}@endif">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Address : <em>*</em></label>
                <textarea name="address" class="form-control" placeholder="Address">@if( isset($content)){{ $content->address }}@endif</textarea>
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
                <input type="text" name="meta_title" class="form-control" placeholder="Meta Title" value="@if( isset($content) ){{ $content->meta_title }}@endif" required="required">
              </div>
              <div class="form-group">
                <label>Meta Keywords:</label>
                <input type="text" name="meta_keyword" class="form-control" placeholder="Meta Keywords" value="@if( isset($content) ){{ $content->meta_keyword }}@endif" required="required">
              </div>
              <div class="form-group">
                <label>Meta Description:</label>
                <textarea name="meta_desc" class="form-control" placeholder="Meta Description" required="required">@if( isset($content) ){{ $content->meta_desc }}@endif</textarea>
              </div>
              <div class="form-group">
                <label>Canonical Url:</label>
                <input type="text" name="canonical_url" class="form-control" placeholder="Any Canonical url" value="@if( isset($content) ){{ $content->canonical_url }}@endif">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Language Tag:</label>
                <input type="text" name="lng_tag" class="form-control" placeholder="Language Tag" value="@if( isset($content) ){{ $content->lng_tag }}@endif">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Add No Follow Tag :</label>
                <select name="follow" class="form-control">
                  <option value="1" @if(isset($content) && $content->follow == '1') selected="selected" @endif>FOLLOW</option>
                  <option value="0" @if(isset($content) && $content->follow == '0') selected="selected" @endif>NO FOLLOW</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Add No Index Tag :</label>
                <select name="index_tag" class="form-control">
                  <option value="1" @if(isset($content) && $content->index_tag == '1') selected="selected" @endif>INDEX</option>
                  <option value="0" @if(isset($content) && $content->index_tag == '0') selected="selected" @endif>NO INDEX</option>
                </select>
              </div>
            </div>
            <div class="col-md-10">
              <div class="form-group">
                <label> Add Structured data mark-up (Json-LD) :</label>
                <textarea name="json_markup" class="form-control" rows="6">@if( isset($content) ){!! html_entity_decode($content->json_markup, ENT_QUOTES) !!}@endif</textarea>
              </div>
            </div>
          </div>
          <!-- END META INFO -->
          <!------------------------------------------------------------------------------------------------------->


          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Desktop Content :</label>
                <textarea name="page_content" id="pgConte" class="form-control">@if( isset($content) ){{ html_entity_decode($content->page_content, ENT_QUOTES) }}@endif</textarea>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              @if( isset($content) )
              <!--input type="submit" class="btn btn-primary" value="Save Changes"-->
              <input type="hidden" id="table_id" value="{{ $content->id }}">
              <input type="hidden" name="insert_id" id="insert_id" value="{{ $content->insert_id }}">
              @else
              <!--input type="submit" class="btn btn-primary" value="Add Content"-->
              <input type="hidden" id="table_id" value="0">
              <input type="hidden" name="insert_id" id="insert_id" value="{{ $insert_id }}">
              @endif
              <input type="hidden" id="table_name" value="distributor_contents">
            </div>
            <div class="col-md-8"></div>
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


{{--

@include('dashboard.modals.editor_element_modal')
@include('dashboard.modals.editor_imgmedia_modal')

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
var editor = CKEDITOR.replace( 'pgConte', {
  customConfig: "{{ asset('public/assets/ckeditor/maxi_config.js') }}",
} );

var fm = $('#frmx');

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
        url: "{{ route('checkSlugUrlSelf') }}",
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
          },
          "tab": function() {
            return $( "#table_name" ).val();
          }
        }
      }
    },
    language_id: {
      required: true
    },
    distributor_id: {
      required: true
    },
    branch_type: {
      required: true
    },
    latitude: {
      required: true
    },
    longitude: {
      required: true
    },
    address: {
      required: true
    }
  },
  messages: {

    name: {
      required: 'Please Enter Branch Name.'
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
    distributor_id: {
      required: 'Please Select Any Country.'
    },
    branch_type: {
      required: 'Please select branch type.'
    },
    latitude: {
      required: 'Please enter latitude.'
    },
    longitude: {
      required: 'Please enter longitude.'
    },
    address: {
      required: 'Please enter address.'
    }
  },
  errorPlacement: function(error, element) {
    element.parent('.form-group').addClass('has-error');
    if (element.attr("data-error-container")) { 
      error.appendTo(element.attr("data-error-container"));
    } else if(element.attr('id') == 'language_id') {
      error.insertAfter(element.parent('div'));
    } else if (element.hasClass('select2')) {
      $('#'+element.attr('name')+'-error').html(error);
    } else {
      error.insertAfter(element); 
    }
  },
  success: function(label) {
    label.closest('.form-group').removeClass('has-error');
  }
});
$( function() {
  <?php if( !isset($content) ) { ?>
  $('#contName').on('blur', function() {
    if( $.trim( $(this).val() ) != '' ) {
      $('#pgSlug').val( string_to_slug( $(this).val() ) );
    }
  });
  <?php } ?>
  
  $('.select2').on('change', function() {
    if( $(this).val() != '' ) {
      $(this).parent('.form-group').removeClass('has-error');
      $('#' + $(this).attr('name') + '-error').html('');
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
function isNumberKey(evt, obj) {
  var charCode = (evt.which) ? evt.which : event.keyCode
  var value = obj.value;
  var dotcontains = value.indexOf(".") != -1;
  if (dotcontains)
    if (charCode == 46) return false;
  if (charCode == 46) return true;
  if (charCode > 31 && (charCode < 48 || charCode > 57))
    return false;
  return true;
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

{{--

@include('dashboard.modals.editor_element_modal_script')
@include('dashboard.modals.editor_imgmedia_modal_script')

--}}

@endpush