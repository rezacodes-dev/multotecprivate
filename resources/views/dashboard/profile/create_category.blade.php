@extends('dashboard.layouts.app')

@push('page_css')
<link rel="stylesheet" href="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.css') }}">
@endpush

@section('content_header')
<section class="content-header">
  <h1>
    @if(isset($profileCat))
    Edit Profile Category
    @else
    Add Profile Category
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('allProfileCats') }}">All Profile Categories</a></li>
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
      <a href="{{ route('allProfileCats') }}" class="btn btn-primary"> All Categories</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">@if(isset($profileCat)) Edit Profile Category @else Add Profile Category @endif</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name="jfrm" id="frmx" action="@if( isset($profileCat) ){{ route('updProfileCat', array('id' => $profileCat->id)) }}@else{{ route('sveProfileCat') }}@endif" method="post" enctype="multipart/form-data">
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
              @if( isset($profileCat) && !empty($profileCat) )
                @if( isset($profileCat->ChildLanguages) && count($profileCat->ChildLanguages) < count($languages) - 1 )
                <a href="{{ route('adedLnProfCat', array('pid' => $profileCat->id)) }}"><i class="fa fa-plus"></i> Add Language ?</a>
                @endif
                @if( isset($profileCat->ChildLanguages) )
                  @foreach( $profileCat->ChildLanguages as $chl )
                    @if( isset($chl->Language) )
                      @php $Lng = $chl->Language; @endphp
                      <a href="{{ route('adedLnProfCat', array('pid' => $profileCat->id, 'cid' => $chl->id)) }}" class="lngLink btn btn-xs">
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
                <label>Profile Category Name (H1): <em>*</em></label>
                <input type="text" name="name" class="form-control" id="proCatName" placeholder="Enter Profile Category Name" value="@if( isset($profileCat) ){{ $profileCat->name }}@endif">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>URL or Link : <em>*</em></label>
                <input type="text" name="slug" class="form-control" id="pgSlug" placeholder="Enter Link or URL" value="@if( isset($profileCat) ){{ $profileCat->slug }}@endif" @if( isset($profileCat) ) readonly="readonly" @endif>
              </div>
              <div class="form-group">
                <label>Short Description :</label>
                <textarea name="description" class="form-control">@if( isset($profileCat) ){{ html_entity_decode($profileCat->description, ENT_QUOTES) }}@endif</textarea>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Display Order :</label>
                <input type="text" name="display_order" class="form-control onlyNumber" style="width: 100px;" @if( isset($profileCat) ) value="{{ $profileCat->display_order }}" @else value="0" @endif>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Desktop Content : </label>
                <textarea name="page_content" id="pg_cont" class="form-control">@if( isset($profileCat) ){{ html_entity_decode($profileCat->page_content, ENT_QUOTES) }}@endif</textarea>
              </div>
              <div class="form-group">
                <label>Mobile Content : </label>
                <textarea name="mob_page_content" id="mob_pgCont" class="form-control">@if( isset($profileCat) ){{ html_entity_decode($profileCat->mob_page_content, ENT_QUOTES) }}@endif</textarea>
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
              @if( isset($profileCat) && $profileCat->image_id != '' && isset($profileCat->imageInfo) )
                <div class="form-group">
                  <img src="{{ asset('public/uploads/files/media_images/'.$profileCat->imageInfo->image) }}" style="width: 260px; height: 100px;">
                  <a href="{{ route('glbImgDel') }}?tab=people_profile_categories&id={{ $profileCat->id }}" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure to delete this image ?');">Delete Image</a>
                </div>
              @endif
              <div class="form-group">
                <label>Banner Title :</label>
                <input type="text" name="image_title" class="form-control" placeholder="Banner image title" value="@if( isset($profileCat) ){{ $profileCat->image_title }}@endif">
              </div>
              <div class="form-group">
                <label>Banner Alt Tag :</label>
                <input type="text" name="image_alt" class="form-control" placeholder="Banner image alt title" value="@if( isset($profileCat) ){{ $profileCat->image_alt }}@endif">
              </div>
              <div class="form-group">
                <label>Banner Caption :</label>
                <textarea name="image_caption" class="form-control" placeholder="Banner image caption">@if( isset($profileCat) ){{ $profileCat->image_caption }}@endif</textarea>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6">
              @if(isset($profileCat))
              <input type="submit" class="btn btn-primary" value="Save Changes">
              <input type="hidden" id="table_id" value="{{ $profileCat->id }}">
              <input type="hidden" name="insert_id" id="insert_id" value="{{ $profileCat->insert_id }}">
              @else
              <input type="submit" class="btn btn-primary" value="Create Category">
              <input type="hidden" id="table_id" value="0">
              <input type="hidden" name="insert_id" id="insert_id" value="{{ $insert_id }}">
              @endif
              <input type="hidden" id="table_name" value="people_profile_categories">
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


@include('dashboard.modals.editor_imgmedia_modal')

@endsection

@push('page_js')
<script src="{{ asset('public/assets/jquery_ui/jquery-ui.js') }}"></script>
<script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.js') }}"></script>


<script type="text/javascript">
var editor_pg_cont = CKEDITOR.replace( 'pg_cont', {
  customConfig: "{{ asset('public/assets/ckeditor/maxi_config.js') }}",
} );

var editor_mobpg_cont = CKEDITOR.replace( 'mob_pgCont', {
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
      required: 'Please Enter People Category Name.'
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
    } else {
      error.insertAfter(element); 
    }
  },
  success: function(label) {
    label.closest('.form-group').removeClass('has-error');
  }
});

<?php if( !isset($profileCat) ) { ?>
$( function() {
  $('#proCatName').on('blur', function() {
    if( $.trim( $(this).val() ) != '' ) {
      $('#pgSlug').val( string_to_slug( $(this).val() ) );
    }
  });
} );
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

@include('dashboard.modals.editor_imgmedia_modal_script')

@endpush