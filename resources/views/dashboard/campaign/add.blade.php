@extends('dashboard.layouts.app')

@push('page_css')
 
 
<link rel="stylesheet" href="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.css') }}">
@endpush

@section('content_header')
<section class="content-header">
  <h1>
    @if(isset($prodCat))
    Edit Campaign
    @else
    Add Campaign
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('prodCats') }}">All Campaign</a></li>
    @if(isset($prodCat))
    <li class="active">Edit Campaign</li>
    @else
    <li class="active">Add Campaign</li>
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
      <a href="{{ route('allCp') }}" class="btn btn-primary"> All Campaign</a>
      @if(isset($prodCat) && $prodCat->is_duplicate == '0')
      <a href="{{ url($prodCat->slug) }}" target="_blank" class="btn btn-primary"> View Page</a>
      @endif
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">@if(isset($prodCat)) Edit Campaign @else Add Campaign @endif</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name="jfrm" id="frmx" action="@if( isset($prodCat)){{ route('updateCp', array('id' => $content_id)) }}@else{{ route('saveCp') }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}

          
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Campaign Name : <em>*</em></label>
                <input type="text" name="name" id="scName" class="form-control" placeholder="Enter Campaign Name " value="@if( isset($prodCat) ){{ $prodCat->name }}@endif">
              </div>
             
              </div>

              <div class="col-md-10">
              <div class="form-group">
                <label>Source Type : <em>*</em></label>
               
                <select name="source_type" class="form-control">
                  <option value="">-Select Source Type-</option>
                  @if( isset($allSource) && !empty($allSource) )
                    @foreach( $allSource as $pc )
                    <option value="{{ $pc->id }}" @if( isset($prodCat) && $pc->id==$prodCat->source_type) selected="selected" @endif>{{ ucfirst( $pc->name ) }}</option>
                    @endforeach
                  @endif
                </select>
              </div>
             
              </div>

              <div class="col-md-10">
              <div class="form-group">
                <label>URL/Campaign Keyword : <em>*</em></label>
                <input type="text" name="url" id="scName" class="form-control" placeholder="Enter URL/Campaign Keyword" value="@if( isset($prodCat) ){{ $prodCat->url }}@endif">
              </div>
              <input type="submit" class="btn btn-primary" value="Save">

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
<script src="{{ asset('public/assets/shortable/Sortable.min.js') }}"></script>
<script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.js') }}"></script>
<script type="text/javascript">
$( function() {
  $('body .pgb_rightControl #pageBuilderBtn').on('click', function() {
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
    page_content: {
      required: true
    },
    canonical_url: {
      url: true
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
    page_content: {
      required: 'Please Enter Content.'
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
  <?php //if( !isset($prodCat) || ( isset($prodCat) && $prodCat->is_duplicate == '1') ) { ?>
  $('#scName').on('blur', function() {
    if( $.trim( $(this).val() ) != '' ) {
      $('#pgSlug').val( string_to_slug( $(this).val() ) );
    }
  });
  <?php //} ?>
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