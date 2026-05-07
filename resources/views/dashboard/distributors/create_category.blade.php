@extends('dashboard.layouts.app')

@push('page_css')
<link rel="stylesheet" href="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.css') }}">
@endpush

@section('content_header')
<section class="content-header">
  <h1>
    @if(isset($category))
    Edit Distributor Category
    @else
    Add Distributor Category
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('allDistribCats') }}">All Distributor Continents</a></li>
    @if(isset($category))
    <li class="active">Edit Distributor Continent</li>
    @else
    <li class="active">Add Distributor Continent</li>
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
      <a href="{{ route('allDistribCats') }}" class="btn btn-primary"> All Distributor Continents</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">@if(isset($category)) Edit Distributor Continent @else Add Distributor Continent @endif</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name="jfrm" id="frmx" action="@if( isset($category) ){{ route('updateDistribCats', array('id' => $category->id)) }}@else{{ route('sveDistribCats') }}@endif" method="post" enctype="multipart/form-data">
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
              @if( isset($category) && !empty($category) )
                @if( isset($category->ChildLanguages) && count($category->ChildLanguages) < count($languages) - 1 )
                <a href="{{ route('distrb.adedcatlng', array('pid' => $category->id)) }}"><i class="fa fa-plus"></i> Add Language ?</a>
                @endif
                @if( isset($category->ChildLanguages) )
                  @foreach( $category->ChildLanguages as $chl )
                    @if( isset($chl->Language) )
                      @php $Lng = $chl->Language; @endphp
                      <a href="{{ route('distrb.adedcatlng', array('pid' => $category->id, 'cid' => $chl->id)) }}" class="lngLink btn btn-xs">
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
                <label>Continent Name (H1): <em>*</em></label>
                <input type="text" name="name" id="catName" class="form-control" placeholder="Enter Distributor Category Name" value="@if( isset($category) ){{ $category->name }}@endif">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>URL or Link : <em>*</em></label>
                <input type="text" name="slug" id="pgSlug" class="form-control" placeholder="Enter URL or Link" value="@if( isset($category) ){{ $category->slug }}@endif" @if( isset($category) ) readonly="readonly" @endif>
              </div>
              <div class="form-group">
                <label>Short Description :</label>
                <textarea name="description" class="form-control">@if( isset($category) ){{ html_entity_decode($category->description, ENT_QUOTES) }}@endif</textarea>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-10">
              <h3>Page Banner Information</h3>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Choose banner :</label>
                <input type="file" name="page_banner" accept="image/*">
              </div>
              @if( isset($category) && $category->image_id != '' && isset($category->imageInfo) )
                <div class="form-group">
                  <img src="{{ asset('public/uploads/files/media_images/'.$category->imageInfo->image) }}" style="width: 260px; height: 100px;">
                  <a href="{{ route('glbImgDel') }}?tab=distributor_category&id={{ $category->id }}" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure to delete this image ?');">Delete Image</a>
                </div>
              @endif
              <div class="form-group">
                <label>Banner Title :</label>
                <input type="text" name="image_title" class="form-control" placeholder="Banner image title" value="@if( isset($category) ){{ $category->image_title }}@endif">
              </div>
              <div class="form-group">
                <label>Banner Alt Tag :</label>
                <input type="text" name="image_alt" class="form-control" placeholder="Banner image alt title" value="@if( isset($category) ){{ $category->image_alt }}@endif">
              </div>
              <div class="form-group">
                <label>Banner Caption :</label>
                <textarea name="image_caption" class="form-control" placeholder="Banner image caption">@if( isset($category) ){{ $category->image_caption }}@endif</textarea>
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
                <input type="text" name="meta_title" class="form-control" placeholder="Meta Title" value="@if( isset($category) ){{ $category->meta_title }}@endif" required="required">
              </div>
              <div class="form-group">
                <label>Meta Keywords:</label>
                <input type="text" name="meta_keyword" class="form-control" placeholder="Meta Keywords" value="@if( isset($category) ){{ $category->meta_keyword }}@endif" required="required">
              </div>
              <div class="form-group">
                <label>Meta Description:</label>
                <textarea name="meta_desc" class="form-control" placeholder="Meta Description" required="required">@if( isset($category) ){{ $category->meta_desc }}@endif</textarea>
              </div>
              <div class="form-group">
                <label>Canonical Url:</label>
                <input type="text" name="canonical_url" class="form-control" placeholder="Any Canonical url" value="@if( isset($category) ){{ $category->canonical_url }}@endif">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Language Tag:</label>
                <input type="text" name="lng_tag" class="form-control" placeholder="Language Tag" value="@if( isset($category) ){{ $category->lng_tag }}@endif">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Add No Follow Tag :</label>
                <select name="follow" class="form-control">
                  <option value="1" @if(isset($category) && $category->follow == '1') selected="selected" @endif>FOLLOW</option>
                  <option value="0" @if(isset($category) && $category->follow == '0') selected="selected" @endif>NO FOLLOW</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Add No Index Tag :</label>
                <select name="index_tag" class="form-control">
                  <option value="1" @if(isset($category) && $category->index_tag == '1') selected="selected" @endif>INDEX</option>
                  <option value="0" @if(isset($category) && $category->index_tag == '0') selected="selected" @endif>NO INDEX</option>
                </select>
              </div>
            </div>
            <div class="col-md-10">
              <div class="form-group">
                <label> Add Structured data mark-up (Json-LD) :</label>
                <textarea name="json_markup" class="form-control" rows="6">@if( isset($category) ){!! html_entity_decode($category->json_markup, ENT_QUOTES) !!}@endif</textarea>
              </div>
            </div>
          </div>
          <!-- END META INFO -->
          <!------------------------------------------------------------------------------------------------------->

          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Desktop Content : (Above the fold content)</label>
                <textarea name="page_content" id="pgConte" class="form-control">@if( isset($category) ){{ html_entity_decode($category->page_content, ENT_QUOTES) }}@endif</textarea>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-3">
              @if( isset($category) )
              <input type="submit" class="btn btn-primary" value="Save Changes">
              <input type="hidden" id="table_id" value="{{ $category->id }}">
              <input type="hidden" name="insert_id" id="insert_id" value="{{ $category->insert_id }}">
              @else
              <input type="submit" class="btn btn-primary" value="Add Continent">
              <input type="hidden" id="table_id" value="0">
              <input type="hidden" name="insert_id" id="insert_id" value="{{ $insert_id }}">
              @endif
              <input type="hidden" id="table_name" value="distributor_category">
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
var editor = CKEDITOR.replace( 'pgConte', {
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
      minlength: 3,
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
    }
  },
  messages: {

    name: {
      required: 'Please Enter Distributor Continent Name.'
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
  <?php if( !isset($category) ) { ?>
  $('#catName').on('blur', function() {
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

@endpush