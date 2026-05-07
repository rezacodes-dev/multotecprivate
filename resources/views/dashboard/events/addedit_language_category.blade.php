@extends('dashboard.layouts.app')

@section('content_header')

@if( isset($parentLngCont) )
<section class="content-header">
  <h1>
    @if(isset($category) && !empty($category))
    Edit Email Category
    @else
    Add New Category
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('evt_cats') }}">All Event Categories</a></li>
     @if(isset($category) && !empty($category))
    <li class="active">Edit Category</li>
     @else
    <li class="active">Create New Category</li>
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
      <a href="{{ route('evt_cats') }}" class="btn btn-primary"> All Categories</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">@if(isset($category) && !empty($category)) Edit Category @else Create Category @endif</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name="frm" id="frmx" action="@if(isset($category)){{ route('evt.adedcatlngPst', array('pid' => $parentLngCont->id, 'cid' => $category->id)) }}@else{{ route('evt.adedcatlngPst', array('pid' => $parentLngCont->id)) }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}

          <div class="row">
            @if(isset($category) && isset($category->Language)) 
            <div class="col-md-3">
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">
                    <span>
                    <img src="{{ asset('public/uploads/flags/thumb/'. $category->Language->flag) }}" style="height: 20px;" id="flag">
                    </span>
                  </div>
                  <select name="language_id" id="language_id" class="form-control">
                    <option value="{{ $category->language_id }}">{{ $category->Language->name }}</option>
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
            @if(isset($category))
            <a href="{{ route('evt.adedcatlngDel', array('pid' => $parentLngCont->id, 'cid' => $category->id)) }}" class="btn btn-danger pull-right" onclick="return confirm('Are You Sure Want To Delete This Content ?')">Delete This Content</a>
            @endif
            </div>
          </div>

          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Category Name (H1): <em>*</em></label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter Category Name" value="@if(isset($category) && !empty($category)){{ $category->name }}@endif">
                @if($errors->has('name'))
                <span class="roy-vali-error"><small>{{$errors->first('name')}}</small></span>
                @endif
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Link/URL : <em>*</em></label>
                <input type="text" name="slug" id="slug" class="form-control" placeholder="Enter Category Link or URL" value="@if(isset($category) && !empty($category)){{ $category->slug }}@endif" @if(isset($category) && !empty($category)) readonly="readonly" @endif>
                @if($errors->has('slug'))
                <span class="roy-vali-error"><small>{{$errors->first('slug')}}</small></span>
                @endif
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Desktop Content : </label>
                @if( isset($category) )
                <span>
                  <a href="{{ route('previewTool') }}?url={{ route('preview', array('device' => 'desktop', 'slug' => $category->slug)) }}&device=desktop" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Desktop Preview">
                    <i class="fa fa-desktop fa-2x" aria-hidden="true"></i>
                  </a>
                </span>
                @endif
                <!--div style="text-align: right;">
                  <input type="button" class="addEleBtn" title="Add To Desktop Content" data="pgCont" value="Add Elements">
                </div-->
                <textarea name="page_content" id="pgCont" class="form-control" data-error-container="#catDESC_error">@if(isset($category) && !empty($category)){{ html_entity_decode($category->page_content, ENT_QUOTES) }}@endif</textarea>
                <div id="catDESC_error"></div>
              </div>
              <div class="form-group">
                <label>Mobile Content : </label>
                @if( isset($category) )
                <span>
                  <a href="{{ route('previewTool') }}?url={{ route('preview', array('device' => 'mobile', 'slug' => $category->slug)) }}&device=mobile" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Mobile Preview">
                    <i class="fa fa-mobile fa-2x" aria-hidden="true"></i>
                  </a>
                </span>
                @endif
                <!--div style="text-align: right;">
                  <input type="button" class="addEleBtn" title="Add To Mobile Content" data="mob_pgCont" value="Add Elements">
                </div-->
                <textarea name="mob_page_content" id="mob_pgCont" class="form-control" data-error-container="#catDESC_error">@if(isset($category) && !empty($category)){{ html_entity_decode($category->mob_page_content, ENT_QUOTES) }}@endif</textarea>
                <div id="catDESC_error"></div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Status : </label>
                <input type="radio" name="status" value="1" @if(isset($category)) @if($category->status == '1') checked="checked" @endif @else checked="checked" @endif> Active
                <input type="radio" name="status" value="2" @if(isset($category) && $category->status == '2') checked="checked" @endif> Inactive
              </div>
            </div>
          </div>



          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                
                <!-- CALL SCRIPT -->
                <button type="button" class="btn btn-default addMedImgBtn" title="Page Banner Image" data="pgBannerImage"><i class="fa fa-picture-o" aria-hidden="true"></i> Add Page Banner Image</button>
                
                <input type="hidden" id="pgBannerImage-idholder" name="banner_image_ids">

                <input type="hidden" id="pgBannerImage-infoholder" name="banner_image_infos">
                
                <div id="pgBannerImage-dispDiv"></div>
                <!-- END -->

              </div>
            </div>
          </div>

          <div class="row">
              <div class="col-md-12">
              @if( isset($category) && isset($category->allImgIds) && count($category->allImgIds) > 0 )
                @php $i = 0; @endphp
                @foreach( $category->allImgIds as $imgs )
                @if( $imgs->image_type == 'BANNER_IMAGE' )
                  @if( isset($imgs->imageInfo) && $i == 0 )
                  <div class="col-md-3">
                  <div class="thumbnail">
                    <img src="{{ asset('public/uploads/files/media_images/thumb/'.$imgs->imageInfo->image) }}">
                    <div class="caption">{{ sizeFilter($imgs->imageInfo->size) }}</div>
                    <a href="javascript:void(0);" class="idel ifdel" data="event_categories_image_map" id="{{ $imgs->id }}">
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
            <div class="col-md-10">
              @if(isset($category))
              <input type="submit" class="btn btn-primary" value="Save Changes">
              <input type="hidden" id="table_id" value="{{ $category->id }}">
              @else
              <input type="submit" class="btn btn-primary" value="Create Category">
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
<script type="text/javascript">
<?php if( !isset($category)) { ?>
$( function() {
  <?php if( !isset($category) ) { ?>
  $('input[name="name"]').on('blur', function() {
    if( $.trim($(this).val()) != '' ) {
      var slug = string_to_slug($.trim($(this).val()));
      $('input[name="slug"]').val(slug);
    } else {
      $('input[name="slug"]').val('');
    }
  });
  <?php } ?>
});
<?php } ?>
var editor = CKEDITOR.replace( 'pgCont', {
  customConfig: "{{ asset('public/assets/ckeditor/maxi_config.js') }}",
} );
var mob_editor = CKEDITOR.replace( 'mob_pgCont', {
  customConfig: "{{ asset('public/assets/ckeditor/maxi_config.js') }}",
} );

/*jQuery.validator.addMethod("cke_required", function (value, element) {
    var idname = $(element).attr('id');
    var editor = CKEDITOR.instances.emBody;
    $(element).val(editor.getData());
    return $(element).val().length > 0;
}, "This field is required - tested working");*/

var fm = $('#frmx');
/*fm.on('submit', function() {
  CKEDITOR.instances.catDESC.updateElement();
});*/
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
    }
  },
  messages: {

    name: {
      required: 'Please Enter Category Name.',
      remote: 'Category Name Already Exist, Try Another.'
    },
    slug: {
      required: 'Please Enter Page URL or Link.',
      nowhitespace: 'White Space or Blank Space Not Allowed, Use Hyphen.',
      pattern: 'Any Special Character Not Allowed, Except Hyphen.',
      remote: 'This URL Already Exist, Try Another.'
    },
    language_id: {
      required: 'Please Select Language.'
    }
  },
  errorPlacement: function (error, element) { 
    element.parent('.form-group').addClass('has-error');
    if (element.attr("data-error-container")) { 
      error.appendTo(element.attr("data-error-container"));
    } else if(element.attr('id') == 'language_id') {
      error.insertAfter(element.parent('div'));
    } else {
      error.insertAfter(element); 
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