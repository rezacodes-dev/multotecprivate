@extends('dashboard.layouts.app')

@push('page_css')
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/light_box/ekko-lightbox.css') }}">
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
    @if(isset($galDetails))
      Gallery : <strong>{{ $galDetails->name }}</strong>
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('media_all_img_gals') }}">All Galleries</a></li>
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
      <a href="{{ route('media_all_img_gals') }}" class="btn btn-primary"> All Galleries</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Manage Image(s)</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name="frm" id="frmx" action="@if(isset($galDetails)){{ route('media_img_gals_sveImg', array('id' => $galDetails->id)) }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Select Gallery Source : <em>*</em></label>
                <select name="gallery_source" class="form-control" id="gs">
                  <option value="">-Select Gallery Source-</option>
                  <option value="1" @if(isset($galDetails) && $galDetails->gallery_source == '1') selected="selected" @endif>Image Groups</option>
                  <option value="2" @if(isset($galDetails) && $galDetails->gallery_source == '2') selected="selected" @endif>Image Library</option>
                </select>
              </div>
            </div>
            <div class="col-md-4"></div>
            <div class="col-md-4"></div>
          </div>
          
          <div class="row" id="groupDiv" @if(isset($galDetails) && $galDetails->gallery_source != '1') style="display: none;" @endif>
            <div class="col-md-4">
              <div class="form-group">
                <label>Select Image Group : <em>*</em></label>
                <select name="image_category_id" class="form-control">
                  <option value="">-Select Any Image Group-</option>
                  @if(isset($allGroups))
                    @foreach($allGroups as $g)
                    <option value="{{ $g->id }}" @if( isset($galDetails) && $galDetails->image_category_id == $g->id ) selected="selected" @endif>{{ $g->name }}</option>
                    @endforeach
                  @endif
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <input type="submit" class="btn btn-primary" value="Set With Gallery" style="margin-top: 25px;">
            </div>
            <div class="col-md-4"></div>
          </div>

          <div class="row" id="imgLibDiv" @if(isset($galDetails) && $galDetails->gallery_source != '2') style="display: none;" @endif>
            <div class="col-md-8">
              <div class="form-group">
                <label>Choose Images From Image Library Or You Can Upload New Image</label><br/>
                <a href="javascript:void(0);" id="imgLibBTN" class="btn btn-primary"><i class="fa fa-picture-o" aria-hidden="true"></i> Open Library</a>
              </div>
            </div>
          </div>
          </form>
          @if( isset($galImages) && !empty($galImages) && isset($galDetails) && $galDetails->gallery_source == '2')
          	<div class="row">
          		<div class="col-md-12"><h3>All Gallery Images</h3></div>
          	</div>
            <div class="row" id="galleryBox">
              @foreach( $galImages as $img )
                <div class="col-lg-2 col-md-2 ibox">
                  <a href="{{ asset('public/uploads/files/media_images/'. $img->image) }}" data-toggle="lightbox" data-gallery="roy-gallery" data-type="image" data-title="{{ $galDetails->name }}">
                  <img src="{{ asset('public/uploads/files/media_images/thumb/'. $img->image) }}" class="thumbnail">
                  </a>
                  <a href="{{ route('media_img_gals_delImg', array('gid' => $galDetails->id, 'gmid' => $img->id)) }}" onclick="return confirm('Sure To Delete This Image From Gallery ?');">
                  	<i class="fa fa-trash-o base-red" aria-hidden="true"></i>
                  </a>
                  <span>{{ sizeFilter($img->size) }}</span>
                </div>
              @endforeach
            </div>
            <div class="row">
            	<div class="col-md-12">
            	{{ $galImages->fragment('galleryBox')->appends(request()->query())->links() }}
            	</div>
            </div>
          @endif
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->
    </div>
  </div>


<!-- Modal -->
  <div class="modal fade" id="imgLibModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="btn btn-danger modal_close pull-right">Close</button>
          <h4 class="modal-title">Image Library</h4>
        </div>
        <div class="modal-body">
          <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#newUpload">Upload Image(s)</a></li>
            <li><a data-toggle="tab" href="#collLib">Image Library</a></li>
          </ul>
          <div class="tab-content">
            <div id="newUpload" class="tab-pane fade in active">
             <form name="frmx2" id="frmx2" action="{{ route('ajaxGalImgUpload') }}" method="post" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="row" style="margin-top: 30px;">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Upload Images : <small>(Max 10 Images)</small><em>*</em></label>
                    <input type="file" name="image[]" id="imgx" accept="image/*" required="required" multiple="multiple">
                  </div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-2">
                  <div class="form-group">
                    <input type="submit" class="btn btn-primary" id="upBTN" value="Upload To Gallery" style="margin-top: 21px;">
                  </div>
                </div>
                <div class="col-md-5">
                  <label id="ajxStatus" style="margin-top: 28px;"></label>
                </div>
              </div>
              <input type="hidden" name="gallery_id" value="@if(isset($galDetails)){{ $galDetails->id }}@endif">
             </form>
             <div class="row">
               <div class="col-md-12"><div id="image_preview"></div></div>
             </div>
             <div class="row">
               <div class="col-md-12"><div id="errList"></div></div>
             </div>
            </div>
            <div id="collLib" class="tab-pane fade">
            	<div class="row">
            		<div class="col-md-6"><h3>All Images</h3></div>
            		<div class="col-md-6">
            			<a href="" id="addSelectedImgs" class="btn btn-primary pull-right" style="margin-top: 10px;">Add Image</a>
            		</div>
            	</div>
              	<div id="renderBox">
              	@include('dashboard.media.image.render_image')
          	  	</div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger modal_close">Close</button>
        </div>
      </div>
    </div>
  </div>

</section>
@endsection

@push('page_js')
<script type="text/javascript" src="{{ asset('public/assets/light_box/ekko-lightbox.min.js') }}"></script>
<script type="text/javascript">
$('#frmx').validate({
  errorElement: 'span',
  errorClass : 'roy-vali-error',
  ignore: [],
  rules: {

    gallery_source: {
      required: true
    },
    image_category_id: {
      required: true
    }
  },
  messages: {

    gallery_source: {
      required: 'Please Select Gallery Source.'
    },
    image_category_id: {
      required: 'Please Select Any Image Group.'
    }
  }
});
$.validator.addMethod('imgsize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param)
}, 'Image size must be less than 2mb.');
$("#frmx2").validate({
  errorElement : 'span',
  errorClass : 'ar-vali-error',
  rules: {
    "image[]": {
      required: true,
      extension: "jpg|jpeg|png|gif",
      accept: "image/*",
      imgsize: 2000000
    }
  },
  messages: {
    "image[]": {
      required: 'Please Select Image(s).',
      accept: 'Please Select Only Image Files.',
      extension: 'Image Extension Not Supported.'
    }
  },
  submitHandler : function(form) {
    
    $.ajax({
      type: form.method,
      url: form.action,
      data: new FormData(form),
      contentType: false,
      cache: false,
      processData: false,
      beforeSend: function() {
        $('#upBTN').attr('disabled', 'disabled');
        $('#ajxStatus').html('<span><i class="fa fa-circle-o-notch fa-spin fa-x fa-fw"></i> Please Wait...</span>');
      },
      success: function(rtn) {
        if( rtn == 'OK' ) {
          $('#upBTN').removeAttr('disabled', 'disabled');
          $('#ajxStatus').html('<span class="base-green"><i class="fa fa-check-square" aria-hidden="true"></i> Images Uploaded To Gallery.</span>');
          $('#image_preview').html('');
          $('#errList').html('');
        } else {
          $('#ajxStatus').html('<span class="base-red">Error: Images Not Added To Gallery.</span>');
        }
        form.reset();
      },
      error: function(ajx_err) {
        
      }
    });

    //return false;
  }
});
$(function() {
  $('#upBTN').attr('disabled', 'disabled');
  $('#gs').on('change', function() {
    if( $.trim($(this).val()) != '' ) {
      if( $(this).val() == '1' ) {
        $('#groupDiv').slideDown();
        $('#imgLibDiv').hide();
      }
      if( $(this).val() == '2' ) {
       $('#groupDiv').hide();
       $('#imgLibDiv').slideDown(); 
      }
    } else {
      $('#imgLibDiv').hide();
      $('#groupDiv').hide();
    }
  });
  $('#imgLibBTN').on('click', function() {
  	$('#imgLibModal').modal({
      backdrop: 'static',
      keyboard: false
    });
    ArInitial();
  });

$("#imgx").change('click',function(){
  $('#image_preview').html('');
  $('#errList').html('');
  $('#ajxStatus').html('');
  Ari_IMAGE_Preview(this);
});
    
function Ari_IMAGE_Preview(input_fileupload)
{ 
  var arErr = 0;
  var count = input_fileupload.files.length;
  if( count > 10 ) {
    arErr++;
    $('#ajxStatus').html('<span class="base-red"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Maximum 10 Images Uploaded at Once</span>');
  }
  if( count > 0 ) {
    for(var i = 0; i < count; i++) {
      var html = "";
      var fs = input_fileupload.files[i].size;
      if(fs <= 2000000) {
        var fileName = input_fileupload.files[i].name;
        var ext = fileName.split('.').pop().toLowerCase();
        if(ext=="jpg" || ext=="png" || ext=="jpeg" || ext=="gif")
        {
          html += "<li class='arimg_box'>";
            html += "<img src='"+ URL.createObjectURL(input_fileupload.files[i])+"' style='width: 100px;' class='img-thumbnail'>";
            html += "<br/><span>Size : "+ bytesToSize(fs) +"</span>";
          html += "</li>";
          $('#image_preview').append(html);
        }
        else
        {
          $('#errList').append('<li class="error"><strong>ERROR:: '+ fileName +'</strong> - Not Uploaded, Image Extension Not Support.</li>');
          arErr++;
        }
      } else {
        $('#errList').append('<li class="error"><strong>ERROR:: '+ fileName +'</strong> - Not Uploaded, Image Size Greater Than 2mb.</li>');
        arErr++;
      }
    }
  }
  if( arErr == 0 ) {
    $('#imgx-error').html('');
    $('#upBTN').removeAttr('disabled', 'disabled');
  } else {
    $('#upBTN').attr('disabled', 'disabled');
  }
}
function bytesToSize(bytes) {
   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
   if (bytes == 0) return '0 Byte';
   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
};


$('.modal_close').on('click', function() {
	$('#imgLibModal').modal('hide');
	window.location.reload();
})

function ArInitial() {
	$('#image_preview').html('');
  	$('#errList').html('');
  	$('#ajxStatus').html('');
  	$('input[type="file"]').val('');
  	$('#imgx-error').html('');
  	$('#upBTN').attr('disabled', 'disabled');
  	$('#frmx2')[0].reset();
}

});
$(document).on('click', '[data-toggle="lightbox"]', function(event) {
    event.preventDefault();
    $(this).ekkoLightbox();
});
</script>
<!-- Ajax Pagination-->
<script type="text/javascript">
$(window).on('hashchange', function() {
    if (window.location.hash) {
        var page = window.location.hash.replace('#', '');
        if (page == Number.NaN || page <= 0) {
            return false;
        }else{
            getData(page);
        }
    }
});
    
$(document).ready(function() {
    $(document).on('click', '#renderBox .pagination a',function(event) {
        event.preventDefault();
       // $('li').removeClass('active');
        //$(this).parent('li').addClass('active');
        var myurl = $(this).attr('href');
        var page = $(this).attr('href').split('page=')[1];
        getData(page);
    });
});
  
function getData(page) {
    $.ajax({
        url: '?page=' + page,
        type: "get",
        datatype: "html"
    }).done(function(data){
        $("#renderBox").empty().html(data);
        location.hash = page;
    }).fail(function(jqXHR, ajaxOptions, thrownError){
          alert('No response from server');
    });
}
</script>
<script type="text/javascript">
$( function() {
	var SelectedIDsArr = [];
	$('body').on('click', '.add_imgBtn', function() {
		SelectedIDsArr.push($(this).attr('id'));
		alert(SelectedIDsArr);
	});
});
</script>
@endpush