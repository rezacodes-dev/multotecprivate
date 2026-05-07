@extends('dashboard.layouts.app')

@push('page_css')
<link rel="stylesheet" href="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.css') }}">
@endpush


@section('content_header')
<section class="content-header">
  <h1>
    @if(isset($video))
    Edit Video
    @else
    Add New Video
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('allVideos') }}">All Videos</a></li>
    @if(isset($video))
    <li class="active">Edit Video</li>
    @else
    <li class="active">Add New Video</li>
    @endif
  </ol>
</section>
@endsection

@section('content')
<section class="content">

  @if(Session::has('msg'))
  <div class="ar-hide @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}</div>
  @endif

  <form name="frm" id="frmx" action="@if( isset($video) ){{ route('updVideo', array('id' => $video->id)) }}@else{{ route('saveVideo') }}@endif" method="post" enctype="multipart/form-data">
  {{ csrf_field() }}

  <div class="row">
    <div class="col-md-6">
      <a href="{{ route('allVideos') }}" class="btn btn-primary"> All Videos</a>
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
          <h3 class="box-title">@if(isset($video)) Edit Video @else Add Video @endif</h3>

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
                <label>Video Name (H1) : <em>*</em></label>
                <input type="text" name="name" id="vidName" class="form-control" placeholder="Enter Video Name" value="@if( isset($video) ){{ $video->name }}@endif">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <!--label>Link or URL : <em>*</em></label-->
                <input type="hidden" name="slug" id="pgSlug" class="form-control" placeholder="Enter Page URL or Link" value="@if( isset($video) ){{ $video->slug }}@endif" @if(isset($video)) readonly="readonly" @endif>
              </div>
            </div>
          </div>

          <!------------------------------------------------------------------------------------------------------->
          <!-- META INFO -->
          {{--<div class="row">
            <div class="col-md-10">
              <h3>Page Meta Information</h3>
              <hr/>
            </div>
            <div class="col-md-10">
              <div class="form-group">
                <label>Meta Title:</label>
                <input type="text" name="meta_title" class="form-control" placeholder="Meta Title" value="@if( isset($video) ){{ $video->meta_title }}@endif">
              </div>
              <div class="form-group">
                <label>Meta Keywords:</label>
                <input type="text" name="meta_keyword" class="form-control" placeholder="Meta Keywords" value="@if( isset($video) ){{ $video->meta_keyword }}@endif">
              </div>
              <div class="form-group">
                <label>Meta Description:</label>
                <textarea name="meta_desc" class="form-control" placeholder="Meta Description">@if( isset($video) ){{ $video->meta_desc }}@endif</textarea>
              </div>
              <div class="form-group">
                <label>Canonical Url:</label>
                <input type="text" name="canonical_url" class="form-control" placeholder="Any Canonical url" value="@if( isset($video) ){{ $video->canonical_url }}@endif">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Language Tag:</label>
                <input type="text" name="lng_tag" class="form-control" placeholder="Language Tag" value="@if( isset($video) ){{ $video->lng_tag }}@endif">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Add No Follow Tag :</label>
                <select name="follow" class="form-control">
                  <option value="0" @if(isset($video) && $video->follow == '0') selected="selected" @endif>NO</option>
                  <option value="1" @if(isset($video) && $video->follow == '1') selected="selected" @endif>YES</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Add No Index Tag :</label>
                <select name="index_tag" class="form-control">
                  <option value="0" @if(isset($video) && $video->index_tag == '0') selected="selected" @endif>NO</option>
                  <option value="1" @if(isset($video) && $video->index_tag == '1') selected="selected" @endif>YES</option>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
          	<div class="col-md-10"><hr/></div>
          </div>--}}
          <!-- END META INFO -->
          <!------------------------------------------------------------------------------------------------------->

          <div class="row">
            <div class="col-md-10">
            	<div class="form-group">
	              <label>Select Category :</label>
	              <select name="video_category_id" id="video_category_id" class="form-control select2">
	                <option value="0">-SELECT CATEGORY-</option>
	                @if(isset($Cats) && count($Cats) > 0)
	                	@foreach($Cats as $c)
	                	<option value="{{ $c->id }}" @if( isset($video) && isset($video->getCatSubcat) && $video->getCatSubcat->video_category_id == $c->id ) selected="selected" @endif>{{ $c->name }}</option>
	                	@endforeach
	                @endif
	              </select>
          		</div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-10">
            	<div class="form-group">
	              <label>Select Subcategory :</label>
	              <select name="video_subcategory_id" id="video_subcategory_id" class="form-control select2">
	                <option value="0">-SELECT SUBCATEGORY-</option>
	                @if(isset($video) && isset($video->getCatSubcat))
	                	@if(isset($seleSubCats))
	                		@foreach($seleSubCats as $sc)
	                		<option value="{{ $sc->id }}" @if($video->getCatSubcat->video_subcategory_id == $sc->id) selected="selected" @endif>{{ $sc->name }}</option>
	                		@endforeach
	                	@endif
	                @endif
	              </select>
          		</div>
            </div>
          </div>


          <div class="row">
            {{--<div class="col-md-2">
              <div class="form-group">
                <label>Video Type :</label>
                <select name="video_type" id="video_type" class="form-control">
                  <option value="1" @if(isset($video) && $video->video_type == '1') selected="selected" @endif>Youtube Link</option>
                  <option value="2" @if(isset($video) && $video->video_type == '2') selected="selected" @endif>Embedded Script</option>
                </select>
              </div>
            </div>--}}
            <input type="hidden" name="video_type" value="1"> <!-- when remove laravel comment, remove it -->
          </div>
          <div class="row" id="vLink" {{-- @if(isset($video) && $video->video_type == '2') style="display: none;" @endif --}}>
            <div class="col-md-10">
              <div class="form-group">
                <label>Youtube Video Link : <em>*</em></label>
                <input type="url" name="video_link" class="form-control" placeholder="Enter Video Link" value="@if( isset($video) ){{ $video->ytb_full_link }}@endif">
              </div>
            </div>
          </div>
          <div class="row" id="vScript" @if(isset($video) && $video->video_type != '2') style="display: none;" @endif @if( !isset($video) ) style="display: none;" @endif>
            <div class="col-md-10">
              <div class="form-group">
                <label>Video Script : <em>*</em></label>
                <textarea name="video_script" class="form-control" placeholder="Enter Video Script" style="height: 80px;">@if( isset($video) ){{ html_entity_decode($video->video_script, ENT_QUOTES) }}@endif</textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Video Caption : </label>
                <textarea name="video_caption" class="form-control" placeholder="Enter Video Caption">@if( isset($video) ){{ $video->video_caption }}@endif</textarea>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Desktop Content : </label>
                <textarea name="page_content" id="pgCont" class="form-control">@if( isset($video) ){{ html_entity_decode($video->page_content, ENT_QUOTES) }}@endif</textarea>
              </div>
              <div class="form-group">
                <label>Mobile Content : </label>
                <textarea name="mob_page_content" id="mob_pgCont" class="form-control">@if( isset($video) ){{ html_entity_decode($video->mob_page_content, ENT_QUOTES) }}@endif</textarea>
              </div>
            </div>
          </div>
          
          
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Status : </label>
                <input type="radio" name="status" value="1" @if( isset($video) ) @if($video->status == '1') checked="checked" @endif @else checked="checked" @endif> Active
                <input type="radio" name="status" value="2" @if( isset($video) && $video->status == '2' ) checked="checked" @endif> Inactive
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              @if( isset($video) )
              <input type="submit" class="btn btn-primary" value="Save Changes">
              <input type="hidden" id="table_id" value="{{ $video->id }}">
              @else
              <input type="submit" class="btn btn-primary" value="Save">
              <a href="{{ route('addVideo') }}" class="btn btn-danger">Cancel</a>
              <input type="hidden" id="table_id" value="0">
              @endif
            </div>
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

@endsection

@push('page_js')
<script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.js') }}"></script>
<script type="text/javascript">
var editor_pgCont = CKEDITOR.replace( 'pgCont', {
  customConfig: "{{ asset('public/assets/ckeditor/maxi_config.js') }}",
} );
var editor_mob_pgCont = CKEDITOR.replace( 'mob_pgCont', {
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
    /*slug: {
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
    },*/
    video_link: {
      required: function(element) {
        return $("#video_type").val() == 1;
      }
    },
    video_script: {
      required: function(element) {
        return $("#video_type").val() == 2;
      }
    }
  },
  messages: {

    name: {
      required: 'Please Enter Category or Subcategory Name.'
    },
    /*slug: {
      required: 'Please Enter Page URL or Link.',
      nowhitespace: 'White Space or Blank Space Not Allowed, Use Hyphen.',
      pattern: 'Any Special Character Not Allowed, Except Hyphen.',
      remote: 'This URL Already Exist, Try Another.'
    },*/
    video_link: {
      required: 'Please Enter Video Link.'
    },
    video_script: {
      required: 'Please Enter Video Script.'
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
$( function() {
  $('#vid_cat_id').multiselect({
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
  
  <?php if( !isset($video) ) { ?>
  $('#vidName').on('blur', function() {
    if( $.trim( $(this).val() ) != '' ) {
      $('#pgSlug').val( string_to_slug( $(this).val() ) );
    }
  });
  <?php } ?>
  
  $('#video_type').on('change', function() {
    if( $(this).val() == '1' ) {
      $('#vScript').hide();
      $('textarea[name="video_script"]').val('');
      $('#vLink').slideDown();
    }
    if( $(this).val() == '2' ) {
      $('#vScript').slideDown();
      $('#vLink').hide();
      $('input[name="video_link"]').val('');
    }
  });
  $('#is_right_panel_required').on('change', function() {
    if( $(this).is(':checked') && $(this).val() == '1' ) {
      $('#right_panel_content').slideDown();
    } else {
      CKEDITOR.instances.rtp_cont.setData('');
      $('#right_panel_content').slideUp();
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

$( function() {
	$('#video_category_id').on('change', function() {
	    $.ajax({
	      type : "POST",
	      url : "{{ route('ajxMediaLdVdSCats') }}",
	      data : {
	        "cat_id" : $(this).val(),
	        "_token" : "{{ csrf_token() }}"
	      },
	      beforeSend: function() {
	        $('#video_category_id').attr('disabled', 'disabled');
	        $('#video_subcategory_id').attr('disabled', 'disabled');
	      },
	      success: function(scatJson) {
	        $('#video_category_id').removeAttr('disabled', 'disabled');
	        $('#video_subcategory_id').removeAttr('disabled', 'disabled');
	        var datArr = JSON.parse(scatJson);
	        var datArrLen = datArr.length;
	        if( datArrLen > 0 ) {
	          var optHTML = '<option value="0">SELECT SUBCATEGORY</option>';
	          for( var i = 0; i < datArrLen; i++ ) {
	            optHTML += '<option value="'+ datArr[i].id +'">'+ datArr[i].name +'</option>';
	          }
	          $('#video_subcategory_id').html( optHTML );
	        } else {
	          $('#video_subcategory_id').html( '<option value="0">SELECT SUBCATEGORY</option>' );
	        }
	      }
	    });
	} );
} );
</script>
@endpush