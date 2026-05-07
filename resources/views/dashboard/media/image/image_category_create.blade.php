@extends('dashboard.layouts.app')

@push('page_css')
<style type="text/css">
li.arimg_box {
  float: left;
  padding: 8px;
  list-style: none;
  text-align: center;
  color: #a3a375;
  font-weight: 600;
}
.thumbnail {
  margin-bottom:2px;
}
.ibox {
  text-align: left;
  color: #a3a375;
  font-weight: 600;
  margin-bottom:10px;
}
.ibox span {
  margin-left: 6px;
}
</style>
@endpush

@section('content_header')
<section class="content-header">
  <h1>
    @if(isset($imgcat))
    Edit Image Category
    @else
    Add Image Category
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('media_all_img_cats') }}">All Image Categories</a></li>
  </ol>
</section>
@endsection

@section('content')

<form name="frm" id="frmx" action="@if(isset($imgcat)){{ route('media_img_cats_upd', array('id' => $imgcat->id)) }}@else{{ route('media_img_cats_save') }}@endif" method="post" enctype="multipart/form-data">
{{ csrf_field() }}

<section class="content">

  @if(Session::has('msg'))
  <div class="ar-hide @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}</div>
  @endif

  <div class="row">
    <div class="col-md-6">
      <a href="{{ route('media_all_img_cats') }}" class="btn btn-primary"> All Image Categories</a>
      <input type="submit" class="btn btn-success" value="Save Category">
      <a href="javascript:void(0);" class="btn btn-danger" onClick="window.location.reload();">Cancel</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">@if(isset($imgcat)) Edit Image Category @else Add Image Category @endif</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Image Category/Subcategory Name (H1) : <em>*</em></label>
                <input type="text" name="name" id="catName" class="form-control" placeholder="Enter Image Category Name" value="@if( isset($imgcat) ){{ $imgcat->name }}@endif">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Link or URL : <em>*</em></label>
                <input type="text" name="slug" id="pgSlug" class="form-control" placeholder="Enter Page URL or Link" value="@if( isset($imgcat) ){{ $imgcat->slug }}@endif" @if( isset($imgcat) ) readonly="readonly" @endif>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Short Description : </label>
                <textarea name="description" class="form-control">@if( isset($imgcat) ){{ $imgcat->description }}@endif</textarea>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Select Parent Category :</label>
                <select name="parent_category_id" class="form-control select2">
                  <option value="0">-SELECT CATEGORY-</option>
                  @if( isset($allFCats) )
                    @foreach( $allFCats as $ict )
                    <option value="{{ $ict->id }}" @if( isset($imgcat) && $imgcat->parent_category_id == $ict->id ) selected="selected" @endif>{{ $ict->name }}</option>
                    @endforeach
                  @endif
                </select>
              </div>    
            </div>
          </div>

          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Display Order : </label>
                <input type="text" name="display_order" class="form-control onlyNumber" style="width: 100px;" @if( isset($imgcat) ) value="{{ $imgcat->display_order }}" @else value="0" @endif>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Is Show in Gallery ? </label><br/>
                <input type="checkbox" name="show_in_gallery" value="1" @if( isset($imgcat) && $imgcat->show_in_gallery == '1') checked="checked" @endif>
                <span>If check, this category will show into gallery section.</span>
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
                <input type="text" name="meta_title" class="form-control" placeholder="Meta Title" value="@if( isset($imgcat) ){{ $imgcat->meta_title }}@endif">
              </div>
              <div class="form-group">
                <label>Meta Keywords:</label>
                <input type="text" name="meta_keyword" class="form-control" placeholder="Meta Keywords" value="@if( isset($imgcat) ){{ $imgcat->meta_keyword }}@endif">
              </div>
              <div class="form-group">
                <label>Meta Description:</label>
                <textarea name="meta_desc" class="form-control" placeholder="Meta Description">@if( isset($imgcat) ){{ $imgcat->meta_desc }}@endif</textarea>
              </div>
              <div class="form-group">
                <label>Canonical Url:</label>
                <input type="text" name="canonical_url" class="form-control" placeholder="Any Canonical url" value="@if( isset($imgcat) ){{ $imgcat->canonical_url }}@endif">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Language Tag:</label>
                <input type="text" name="lng_tag" class="form-control" placeholder="Language Tag" value="@if( isset($imgcat) ){{ $imgcat->lng_tag }}@endif">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Add No Follow Tag :</label>
                <select name="follow" class="form-control">
                  <option value="1" @if(isset($imgcat) && $imgcat->follow == '1') selected="selected" @endif>FOLLOW</option>
                  <option value="0" @if(isset($imgcat) && $imgcat->follow == '0') selected="selected" @endif>NO FOLLOW</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Add No Index Tag :</label>
                <select name="index_tag" class="form-control">
                  <option value="1" @if(isset($imgcat) && $imgcat->index_tag == '1') selected="selected" @endif>INDEX</option>
                  <option value="0" @if(isset($imgcat) && $imgcat->index_tag == '0') selected="selected" @endif>NO INDEX</option>
                </select>
              </div>
            </div>
          </div>
          <!-- END META INFO -->
          <!------------------------------------------------------------------------------------------------------->

          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Desktop Content : </label>
                <textarea name="page_content" id="pgCont" class="form-control">@if( isset($imgcat) ){{ html_entity_decode($imgcat->page_content, ENT_QUOTES) }}@endif</textarea>
              </div>
              <div class="form-group">
                <label>Mobile Content : </label>
                <textarea name="mob_page_content" id="mob_pgCont" class="form-control">@if( isset($imgcat) ){{ html_entity_decode($imgcat->mob_page_content, ENT_QUOTES) }}@endif</textarea>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label>Status :</label>
                <input type="radio" name="status" value="1" @if( isset($imgcat) ) @if( $imgcat->status == '1' ) checked="checked" @endif @else checked="checked" @endif> Active
                <input type="radio" name="status" value="2" @if( isset($imgcat) && $imgcat->status == '2' ) checked="checked" @endif> Inactive
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-8">
              @if( isset($imgcat) )
              <input type="submit" class="btn btn-primary" value="Save Changes">
              <input type="hidden" id="table_id" value="{{ $imgcat->id }}">
              @else
              <input type="submit" class="btn btn-primary" value="Create Category">
              <input type="hidden" id="table_id" value="0">
              @endif
              <input type="hidden" id="table_name" value="image_category">
            </div>
          </div>
          
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->
    </div>
  </div>

</section>

</form>

@endsection

@push('page_js')
<script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
var editor_pgCont = CKEDITOR.replace( 'pgCont', {
  customConfig: "{{ asset('public/assets/ckeditor/maxi_config.js') }}",
} );
var editor_mob_pgCont = CKEDITOR.replace( 'mob_pgCont', {
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
    canonical_url: {
        url: true
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
    meta_title: {
      required: true
    },
    meta_keyword: {
      required: true
    },
    meta_desc: {
      required: true
    },
    lng_tag: {
      required: true
    }
  },
  messages: {

    name: {
      required: 'Please Enter Category or Subcategory Name.'
    },
    slug: {
      required: 'Please Enter Page URL or Link.',
      nowhitespace: 'White Space or Blank Space Not Allowed, Use Hyphen.',
      pattern: 'Any Special Character Not Allowed, Except Hyphen.',
      remote: 'This URL Already Exist, Try Another.'
    },
    canonical_url: {
      url: 'Please Enter Valid URL.'
    }
  },
  errorPlacement: function(error, element) {
    element.parent('.form-group').addClass('has-error');
    if (element.attr("data-error-container")) { 
      error.appendTo(element.attr("data-error-container"));
    } else {
      error.insertAfter(element); 
    }
  },
  success: function(label) {
    label.closest('.form-group').removeClass('has-error');
  }
});

<?php if( !isset($imgcat) ) { ?>
$( function() {
  $('#catName').on('blur', function() {
    if( $.trim( $(this).val() ) != '' ) {
      $('#pgSlug').val( string_to_slug( $(this).val() ) );
    }
  });
});
<?php } ?>

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
@endpush