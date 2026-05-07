@extends('dashboard.layouts.app')

@push('page_css')
<link rel="stylesheet" href="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.css') }}">
@endpush

@section('content_header')
@if( isset($typeDetails) && isset($parentLngCont) )
<section class="content-header">
  <h1>
    <strong>{{ $typeDetails->name }}</strong>
    @if(isset($dynaContent))
     | Edit Content
    @else
     | Add New Content
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('mngLists', array('type' => str_slug($typeDetails->name), 'type_id' => $typeDetails->id)) }}">All {{ $typeDetails->name }}</a></li>
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
      <a href="{{ route('mngLists', array('type' => str_slug($typeDetails->name), 'type_id' => $typeDetails->id)) }}" class="btn btn-primary"> All {{ $typeDetails->name }}</a>
      <a href="{{ route('edtDynaCont', array('type' => str_slug($typeDetails->name), 'type_id' => $typeDetails->id, 'pid' => $parentLngCont->id)) }}" class="btn btn-primary"> Back</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title"><strong>{{ $typeDetails->name }}</strong> - 
          @if(isset($dynaContent)) 
            Edit @if( isset($dynaContent->Language) )<b>{{ $dynaContent->Language->name }}</b>@endif Language Content 
          @else Add Language Content @endif</h3>

          <div class="box-tools pull-right">
            
          </div>
        </div>
        <div class="box-body">
          <form name="jfrm" id="frmx" action="@if( isset($dynaContent) ){{ route('addedtLngDynaCont_post', array('type' => str_slug($typeDetails->name), 'type_id' => $typeDetails->id, 'pid' => $dynaContent->parent_language_id, 'cid' => $dynaContent->id)) }}@else{{ route('addedtLngDynaCont_post', array('type' => str_slug($typeDetails->name), 'type_id' => $typeDetails->id, 'pid' => $parentLngCont->id)) }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          
          <div class="row">
            @if(isset($dynaContent) && isset($dynaContent->Language)) 
            <div class="col-md-3">
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">
                    <span>
                    <img src="{{ asset('public/uploads/flags/thumb/'. $dynaContent->Language->flag) }}" style="height: 20px;" id="flag">
                    </span>
                  </div>
                  <select name="language_id" id="language_id" class="form-control">
                    <option value="{{ $dynaContent->language_id }}">{{ $dynaContent->Language->name }}</option>
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
            @if(isset($dynaContent))
            <a href="{{ route('delLngDynaCont', array('type' => str_slug($typeDetails->name), 'type_id' => $typeDetails->id, 'pid' => $dynaContent->parent_language_id, 'cid' => $dynaContent->id)) }}" class="btn btn-danger pull-right" onclick="return confirm('Are You Sure Want To Delete This Content ?')">Delete This Content</a>
            @endif
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Content Name (H1) : <em>*</em></label>
                <input type="text" name="name" id="contName" class="form-control" placeholder="Enter Product Name" value="@if( isset($dynaContent) ){{ $dynaContent->name }}@endif">
              </div>
              <div class="form-group">
                <label>Link or Url : <em>*</em></label>
                <input type="text" name="slug" id="pgSlug" class="form-control" placeholder="Enter Link or Page URL" value="@if( isset($dynaContent) ){{ $dynaContent->slug }}@endif" @if( isset($dynaContent) ) readonly="readonly" @endif>
              </div>
              <div class="form-group">
                <label>Sort Description : </label>
                <textarea name="description" class="form-control" placeholder="Enter Sort Description">@if( isset($dynaContent) ){{ $dynaContent->description }}@endif</textarea>
              </div>
              <div class="form-group">
                <label>Page Display Layout :</label>
                <input type="checkbox" name="is_full_width" value="1" @if( isset($dynaContent) && $dynaContent->is_full_width == '1') checked="checked" @endif>
                <span>If checked, This page display in container width. </span><br/>
                <span><mark><b>Note:</b></mark> <small>If you checked, please don't put any content into sidebar section and don't create any div into editor.</small></span>
              </div>


              <!-- META INFO -->
              <div class="row">
                <div class="col-md-10">
                  <h3>Page Meta Information</h3>
                  <hr/>
                </div>
                <div class="col-md-10">
                  <div class="form-group">
                    <label>Meta Title:</label>
                    <input type="text" name="meta_title" class="form-control" placeholder="Meta Title" value="@if( isset($dynaContent) ){{ $dynaContent->meta_title }}@endif">
                  </div>
                  <div class="form-group">
                    <label>Meta Keywords:</label>
                    <input type="text" name="meta_keyword" class="form-control" placeholder="Meta Keywords" value="@if( isset($dynaContent) ){{ $dynaContent->meta_keyword }}@endif">
                  </div>
                  <div class="form-group">
                    <label>Meta Description:</label>
                    <textarea name="meta_desc" class="form-control" placeholder="Meta Description">@if( isset($dynaContent) ){{ $dynaContent->meta_desc }}@endif</textarea>
                  </div>
                  <div class="form-group">
                    <label>Canonical Url:</label>
                    <input type="text" name="canonical_url" class="form-control" placeholder="Any Canonical url" value="@if( isset($dynaContent) ){{ $dynaContent->canonical_url }}@endif">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Language Tag:</label>
                    <input type="text" name="lng_tag" class="form-control" placeholder="Language Tag" value="@if( isset($dynaContent) ){{ $dynaContent->lng_tag }}@endif">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Add No Follow Tag :</label>
                    <select name="follow" class="form-control">
                      <option value="0" @if(isset($dynaContent) && $dynaContent->follow == '0') selected="selected" @endif>NO</option>
                      <option value="1" @if(isset($dynaContent) && $dynaContent->follow == '1') selected="selected" @endif>YES</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label>Add No Index Tag :</label>
                    <select name="index_tag" class="form-control">
                      <option value="0" @if(isset($dynaContent) && $dynaContent->index_tag == '0') selected="selected" @endif>NO</option>
                      <option value="1" @if(isset($dynaContent) && $dynaContent->index_tag == '1') selected="selected" @endif>YES</option>
                    </select>
                  </div>
                </div>
              </div>
              <!-- END META INFO -->



              <div class="form-group">
                <label>Any Parent Page ?</label>
                <select name="parent_page_id" class="form-group select2" style="width: 100%;">
                  <option value="0">-Select Page-</option>
                  @if( isset($allPages) )
                    @foreach($allPages as $pgs)
                    <option value="{{ $pgs->id }}" @if(isset($dynaContent) && $dynaContent->parent_page_id == $pgs->id) selected="selected" @endif>{{ ucfirst($pgs->name) }}</option>
                    @endforeach
                  @endif
                </select>
              </div>
              
            </div>
          </div>

          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Desktop Content : (Above the fold content)</label>
                {{--
                @if( isset($dynaContent) )
                <span>
                  <a href="{{ route('previewTool') }}?url={{ route('preview', array('device' => 'desktop', 'slug' => $dynaContent->slug)) }}&device=desktop" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Desktop Preview">
                    <i class="fa fa-desktop fa-2x" aria-hidden="true"></i>
                  </a>
                </span>
                @endif
                <div style="text-align: right;">
                  <input type="button" class="addEleBtn" title="Add To Desktop Content" data="pg_cont" value="Add Elements">
                </div>
                --}}
                <textarea name="page_content" class="form-control" id="pg_cont">@if( isset($dynaContent) ){{ html_entity_decode($dynaContent->page_content, ENT_QUOTES) }}@endif</textarea>
              </div>
              
            </div>
          </div>

          
          <div class="row" style="margin-top: 20px;">
            <div class="col-md-6">
              @if( isset($dynaContent) )
              <!--input type="submit" class="btn btn-primary" value="Save Changes"-->
              <input type="hidden" id="table_id" value="{{ $dynaContent->id }}">
              <input type="hidden" name="insert_id" id="insert_id" value="{{ $dynaContent->insert_id }}">
              @else
              <!--input type="submit" class="btn btn-primary" value="Save All"-->
              <!--a href="{{ route('addDynaCont', array('type' => str_slug($typeDetails->name), 'type_id' => $typeDetails->id)) }}" class="btn btn-danger">Cancel</a-->
              <input type="hidden" id="table_id" value="0">
              <input type="hidden" name="insert_id" id="insert_id" value="{{ $insert_id }}">
              @endif
              <input type="hidden" name="content_type_id" value="{{ $typeDetails->id }}">
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
{{--
@include('dashboard.modals.editor_element_modal')
--}}


@endif
@endsection

@push('page_js')
<script src="{{ asset('public/assets/jquery_ui/jquery-ui.js') }}"></script>
<script src="{{ asset('public/assets/shortable/Sortable.min.js') }}"></script>
<script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
$( function() {
  $('.pgb_rightControl #pageBuilderBtn').on('click', function() {
    $('.pgb_rightControl .cdiv').toggle('slide', { direction:'right' }, 200);
  });
} );
var editor_pg_cont = CKEDITOR.replace( 'pg_cont', {
  customConfig: "{{ asset('public/assets/ckeditor/maxi_config.js') }}",
} );

var fm = $('#frmx');
fm.on('submit', function() {
  CKEDITOR.instances.pg_cont.updateElement();
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
    canonical_url: {
      url: true
    }
  },
  messages: {

    name: {
      required: 'Please Enter Content Name or Heading.'
    },
    slug: {
      required: 'Please Enter Page URL or Link.',
      nowhitespace: 'White Space or Blank Space Not Allowed, Use Hyphen.',
      pattern: 'Any Special Character Not Allowed, Except Hyphen.',
      remote: 'This URL Already Exist, Try Another.'
    },
    language_id: {
      required: 'Please Select Language'
    },
    canonical_url: {
      url: 'Please Enter Valid URL'
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
  $('#contName').on('blur', function() {
    if( $.trim( $(this).val() ) != '' ) {
      $('#pgSlug').val( string_to_slug( $(this).val() ) );
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

{{--
@include('dashboard.modals.editor_element_modal_script')
--}}

@endpush