@extends('dashboard.layouts.app')


@section('content_header')

@if( isset($parentLngCont) )
<section class="content-header">
  <h1>
    @if(isset($artCat))
     Edit Category
    @else
     Add New Category
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('allArtCats') }}">All Categories</a></li>
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
      <a href="{{ route('allArtCats') }}" class="btn btn-primary"> All Categories</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">@if(isset($artCat)) Edit Category @else Add Category @endif</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name="jfrm" id="frmx" action="@if( isset($artCat) ){{ route('arti.adedcatlngPst', array('pid' => $parentLngCont->id, 'cid' => $artCat->id)) }}@else{{ route('arti.adedcatlngPst', array('pid' => $parentLngCont->id)) }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}

          <div class="row">
            @if(isset($artCat) && isset($artCat->Language)) 
            <div class="col-md-3">
              <div class="form-group">
                <div class="input-group">
                  <div class="input-group-addon">
                    <span>
                    <img src="{{ asset('public/uploads/flags/thumb/'. $artCat->Language->flag) }}" style="height: 20px;" id="flag">
                    </span>
                  </div>
                  <select name="language_id" id="language_id" class="form-control">
                    <option value="{{ $artCat->language_id }}">{{ $artCat->Language->name }}</option>
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
            @if(isset($artCat))
            <a href="{{ route('arti.adedcatlngDel', array('pid' => $parentLngCont->id, 'cid' => $artCat->id)) }}" class="btn btn-danger pull-right" onclick="return confirm('Are You Sure Want To Delete This Content ?')">Delete This Content</a>
            @endif
            </div>
          </div>

          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Article Category Name (H1) : <em>*</em></label>
                <input type="text" name="name" id="artCatName" class="form-control" placeholder="Enter Article Category Name" value="@if( isset($artCat) ){{ $artCat->name }}@endif">
              </div>
              <div class="form-group">
                <label>Link or Url : <em>*</em></label>
                <input type="text" name="slug" id="pgSlug" class="form-control" placeholder="Enter Link or Page URL" value="@if( isset($artCat) ){{ $artCat->slug }}@endif" @if( isset($artCat) ) readonly="readonly" @endif>
              </div>
              <div class="form-group">
                <label>Any Parent Category ?</label>
                <select name="parent_category_id" class="form-group select2" style="width: 100%;">
                  <option value="0">-Select Category-</option>
                  @if( isset($allCats) )
                    @foreach($allCats as $cts)
                      @if( isset($artCat) )
                        @if( $artCat->id != $cts->id )
                        <option value="{{ $cts->id }}" @if(isset($artCat) && $artCat->parent_category_id == $cts->id) selected="selected" @endif>{{ ucfirst($cts->name) }}</option>
                        @endif
                      @else
                      <option value="{{ $cts->id }}">{{ ucfirst($cts->name) }}</option>
                      @endif
                    @endforeach
                  @endif
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Desktop Content :</label>
                @if( isset($artCat) )
                <span>
                  <a href="{{ route('previewTool') }}?url={{ route('preview', array('device' => 'desktop', 'slug' => $artCat->slug)) }}&device=desktop" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Desktop Preview">
                    <i class="fa fa-desktop fa-2x" aria-hidden="true"></i>
                  </a>
                </span>
                @endif
                <!--div style="text-align: right;">
                  <input type="button" class="addEleBtn" title="Add To Desktop Content" data="pg_cont" value="Add Elements">
                </div-->
                <textarea name="page_content" class="form-control" id="pg_cont">@if( isset($artCat) ){{ html_entity_decode($artCat->page_content, ENT_QUOTES) }}@endif</textarea>
              </div>
              <div class="form-group">
                <label>Mobile Content :</label>
                @if( isset($artCat) )
                <span>
                  <a href="{{ route('previewTool') }}?url={{ route('preview', array('device' => 'mobile', 'slug' => $artCat->slug)) }}&device=mobile" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Mobile Preview">
                    <i class="fa fa-mobile fa-2x" aria-hidden="true"></i>
                  </a>
                </span>
                @endif
                <!--div style="text-align: right;">
                  <input type="button" class="addEleBtn" title="Add To Mobile Content" data="mob_pg_cont" value="Add Elements">
                </div-->
                <textarea name="mob_page_content" class="form-control" id="mob_pg_cont">@if( isset($artCat) ){{ html_entity_decode($artCat->mob_page_content, ENT_QUOTES) }}@endif</textarea>
              </div>
              <div class="form-group">
                <label>Status :</label>
                <input type="radio" name="status" value="1" @if( isset($artCat) ) @if($artCat->status == '1') checked="checked" @endif @else checked="checked" @endif> Active
                <input type="radio" name="status" value="2" @if( isset($artCat) && $artCat->status == '2' ) checked="checked" @endif> Inactive
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
              @if( isset($artCat) && isset($artCat->allImgIds) && count($artCat->allImgIds) > 0 )
                @php $i = 0; @endphp
                @foreach( $artCat->allImgIds as $imgs )
                @if( $imgs->image_type == 'BANNER_IMAGE' )
                  @if( isset($imgs->imageInfo) && $i == 0 )
                  <div class="col-md-3">
                  <div class="thumbnail">
                    <img src="{{ asset('public/uploads/files/media_images/thumb/'.$imgs->imageInfo->image) }}">
                    <div class="caption">{{ sizeFilter($imgs->imageInfo->size) }}</div>
                    <a href="javascript:void(0);" class="idel ifdel" data="article_categories_image_map" id="{{ $imgs->id }}">
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


          <div class="row" style="margin-top: 20px;">
            <div class="col-md-6">
              @if( isset($artCat) )
              <input type="submit" class="btn btn-primary" value="Save Changes">
              <input type="hidden" id="table_id" value="{{ $artCat->id }}">
              @else
              <input type="submit" class="btn btn-primary" value="Create Category">
              <input type="hidden" id="table_id" value="0">
              @endif 
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

{{-- @include('dashboard.modals.editor_element_modal') --}}

@include('dashboard.modals.editor_imgmedia_modal')


@endif

@endsection

@push('page_js')
<script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}"></script>
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
    }
  },
  messages: {

    name: {
      required: 'Please Enter Article Category Name or Heading.'
    },
    slug: {
      required: 'Please Enter Page URL or Link.',
      nowhitespace: 'White Space or Blank Space Not Allowed, Use Hyphen.',
      pattern: 'Any Special Character Not Allowed, Except Hyphen.',
      remote: 'This URL Already Exist, Try Another.'
    },
    language_id: {
      required: 'Please Select Language'
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
  <?php if( !isset($artCat) ) { ?>
  $('#artCatName').on('blur', function() {
    if( $.trim( $(this).val() ) != '' ) {
      $('#pgSlug').val( string_to_slug( $(this).val() ) );
    }
  });
  <?php } ?>
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

@include('dashboard.modals.editor_imgmedia_modal_script')

{{-- @include('dashboard.modals.editor_element_modal_script') --}}


@endpush