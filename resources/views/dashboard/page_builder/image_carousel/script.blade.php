<!------------------------------------------------- Image Media ----------------------------------- -->
<script type="text/javascript">
var isLibTabOpen = 0;
var page = '';
$( function() {

  $('body').on('click', '.pgb_imgcar', function() {

    $('#img_builder_type').val( 'IMAGE_CAROUSEL' );
    $('#IMAGE_CAROUSEL_this_id').val('0');
    $('a[href="#carSeleImgs"]').hide();
    $('.nav-tabs a[href="#imgUpload"]').tab('show');
    $('#setImageInfo_Action').val( 'SET' );
    $('select[name="device"]').val('3');

    $('#imgCarModal').modal({
      backdrop: 'static',
      keyboard: false
    });

    elementInitial();
    //var getCkEdtID = $(this).attr('data'); // set html element id or editor id
    //var getCkEdtTitle = $(this).attr('title'); // set the action title
    //$('#modalEleSetEdtId').val( getCkEdtID );
    //$('.addOnEdtName').html( ' | ' + getCkEdtTitle );
  });

  $('body').on('click', '.pgb_imggal', function() {

    $('#img_builder_type').val( 'IMAGE_GALLERY' );
    $('a[href="#carSeleImgs"]').hide();
    $('.nav-tabs a[href="#imgUpload"]').tab('show');
    $('#setImageInfo_Action').val( 'SET' );

    $('#imgCarModal').modal({
      backdrop: 'static',
      keyboard: false
    });

    elementInitial();
    //var getCkEdtID = $(this).attr('data'); // set html element id or editor id
    //var getCkEdtTitle = $(this).attr('title'); // set the action title
    //$('#modalEleSetEdtId').val( getCkEdtID );
    //$('.addOnEdtName').html( ' | ' + getCkEdtTitle );
  });

  /*$('body').on('click', '.pgb_imggal_btn', function() {

    $('#img_builder_type').val( 'IMAGEGAL_BUTT' );
    $('a[href="#carSeleImgs"]').hide();
    $('.nav-tabs a[href="#imgUpload"]').tab('show');
    $('#setImageInfo_Action').val( 'SET' );

    $('#imgCarModal').modal({
      backdrop: 'static',
      keyboard: false
    });

    elementInitial();
  });*/

  $('body').on('click', '.eleModalClose', function() {
    elementInitial();
    $('#imgCarModal').modal('hide');
  });
  $('body').on('change', '#imgUpload', function() {
    Media_Images_Preview(this);
  });
});
$.validator.addMethod('imgsize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param)
}, 'Image size must be less than 2mb.');
var mediaUpload_validator = $("#mediaUpload").validate({
  errorElement : 'span',
  errorClass : 'ar-vali-error',
  rules: {
    "images[]": {
      required: true,
      extension: "jpg|jpeg|png|gif",
      accept: "image/*",
      imgsize: 2000000
    }
  },
  messages: {
    "images[]": {
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
        $('#mediaUpload_BTN').attr('disabled', 'disabled');
        $('#mediaStatus').html('<span><i class="fa fa-circle-o-notch fa-spin fa-x fa-fw"></i> Please Wait...</span>');
      },
      success: function(rtnJson) {
        //console.log($.isEmptyObject(rtnJson));
        var ck = JSON.parse( rtnJson );
        var len = ck.length;
        if( len > 0 ) {
          getLibraryImages(page = '');
          isLibTabOpen++;
          $('#mediaStatus').html('<span class="base-green"><i class="fa fa-check-square" aria-hidden="true"></i> Images Successfully Added To Media Library.</span>');
          $('#previewBox').fadeOut(1200, function() {
            $('.nav-tabs a[href="#imgLibrary"]').tab('show');
          });
        } else {
          $('#mediaStatus').html('<span class="base-red"><strong>Error:</strong> Images Not Uploaded.</span>');
        }
        form.reset();
      },
      error: function(ajx_err) {
        
      }
    });
    //return false;
  }
});
function Media_Images_Preview(inputFilesObj) { 
  if( inputFilesObj.files && inputFilesObj.files[0] ) {
    var arErr = 0;
    var previewHTML = '<div class="row">';
    var fileCount = inputFilesObj.files.length;
    if( fileCount > 0 && fileCount <= 10 ) {
      for( var i = 0; i < fileCount; i++ ) {
        var eachSize = inputFilesObj.files[i].size;
        var eachName = inputFilesObj.files[i].name;
        var eachExtn = eachName.split('.').pop().toLowerCase();
        if( eachExtn == 'jpg' || eachExtn == 'jpeg' || eachExtn == 'png' || eachExtn == 'gif' ) {
          if( eachSize <= 2000000 ) {
            previewHTML += '<div class="col-md-2 col-sm-4">';
              previewHTML += '<div class="thumbnail">';
                previewHTML += '<img src="'+ URL.createObjectURL(inputFilesObj.files[i]) +'">';
                previewHTML += '<div class="caption">';
                  previewHTML += '<span><small>'+ bytesToSize(eachSize) +'</small></span>';
                previewHTML += '</div>';
              previewHTML += '</div>';
            previewHTML += '</div>';
          } else {
            arErr++;
          }
        } else {
          arErr++;
        }
      }
    } else {
      arErr++;
      $('#imgUpload_Info').addClass('ar-vali-error').html('<br/><small>Maximum 10 Images Can Upload At Once.</small>');
    }
    previewHTML += '</div>';
    $('#previewBox').show().html( previewHTML );
    if( arErr > 0 ) {
      $('#mediaUpload_BTN').attr('disabled', 'disabled');
      $('#mediaStatus').html( '<span class="ar-vali-error"><strong>Error Found</strong><br/>Check File Extensions & File Size. Maximum 2MB Filesize Can Upload Into Server.</span>' );
    } else {
     $('#imgUpload_Info').removeClass('ar-vali-error').html('');
     $('#mediaUpload_BTN').removeAttr('disabled');
     $('#imgUpload-error').html(''); 
     $('#mediaStatus').html('');
    }
  }
}
function bytesToSize(bytes) {
  var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
  if (bytes == 0) return '0 Byte';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
  return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}
function elementInitial() {
  $('#mediaUpload_BTN').attr('disabled', 'disabled');
  $('#imgUpload_Info').html('');
  $('#mediaStatus').html('');
  $('#imgUpload').val('');
  $('#imgUpload-error').html();
  $('input[type="file"]').val('');
  $('#previewBox').html('');
  mediaUpload_validator.resetForm();
}
loadImgGals();
function loadImgGals() {
  $.get("{{ route('ajxMediaLdImgGals') }}", function(data, status) {
    if( status == 'success' ) {
      var datArr = JSON.parse(data);
      var datArrLen = datArr.length;
      if( datArrLen > 0 ) {
        var optHTML = '<option value="0">-Galleries-</option>';
        for( var i = 0; i < datArrLen; i++ ) {
          optHTML += '<option value="'+ datArr[i].id +'">'+ datArr[i].name +'</option>';
        }
        $('.lodGals').html( optHTML );
        
        /*
          For Gallery Tab
        */

        /*var opt2HTML = '<option value="0">-All Image Galleries-</option>';
        for( var i = 0; i < datArrLen; i++ ) {
          opt2HTML += '<option value="'+ datArr[i].short_code +'">'+ datArr[i].name +'</option>';
        }
        $('#onlyImgGal').html( opt2HTML );*/
      }
    }
  });
}


/******** Image Library ************/
$( function() {
  $('body').on('click', '.nav-tabs a[href="#imgLibrary"]', function() {
    if( isLibTabOpen == 0 ) {
      ckImgInfoPanelBlank();
      getLibraryImages(page);
      isLibTabOpen++;
    }
  });
  $('body').on('click', '#findImg', function() {
    getLibraryImages(page = '');
    ckImgInfoPanelBlank();
    isLibTabOpen++;
  });
  $('body').on('click', '#reloadImgs', function() {
    $('#img_src_txt').val('');
    ckImgInfoPanelBlank();
    $('.lodGals').val('0').trigger("change");
    getLibraryImages(page = '');
    isLibTabOpen++;
  });
});
/*$(window).on('hashchange', function() {    
  if (window.location.hash) {
    page = window.location.hash.replace('#', '');
    if (page == Number.NaN || page <= 0) {
      return false;
    } else {
      getLibraryImages(page);
    }
  }
});*/
$(document).ready(function() {
  $(document).on('click', '#LibraryBox .pagination a', function(event) {
    event.preventDefault();
    var myurl = $(this).attr('href');
    page = $(this).attr('href').split('page=')[1];
    getLibraryImages(page);
    $('#LibraryBox ul.pagination li').removeClass('active');
    $(this).parent('li').addClass('active');
  });
});
function getLibraryImages(page) {
  var img_src_txt = $.trim( $('#img_src_txt').val() );
  var img_gal_id = $('#img_gal_id').val();
  $.ajax({
    url: '{{ route("ajxMediaImgLibrary") }}?page=' + page + '&src_txt=' + img_src_txt + '&gallery_id=' + img_gal_id,
    type: "GET",
    datatype: "html",
    beforeSend: function() {
      $('#LibraryBox').block({ 
        message: '<h4>Please wait...</h4>', 
        css: { 
          border: 'none', 
          padding: '15px', 
          backgroundColor: '#000', 
          '-webkit-border-radius': '10px', 
          '-moz-border-radius': '10px', 
          opacity: .5, 
          color: '#fff' 
        } 
      });
    },
    success: function(data) {
      $('#LibraryBox').unblock();
      $("#LibraryBox").empty().html(data);
      location.hash = page;
      onAjaxLoadSelectedImgBox();
    },
    error: function(jqXHR, ajaxOptions, thrownError) {
      alert('No response from server');
    }
  });
}
/********* Add Image Collection *************/

/**
Single click for image select
**/

var imageCollection = new Object();
var imageInfoCollection = new Object();

$('#addSeletImgs').attr('disabled', 'disabled');
$('body').on('click', '#LibraryBox .lib-img-box', function() {
  
  $('#setImageInfo_Action').val( 'SET' );

  var get_libBoxID = $.trim( $(this).attr('id') );
  var get_libImgPath = $.trim( $(this).find('img').attr('src') );
  var get_libImgTitle = $.trim( $(this).find('img').attr('title') );
  var get_libImgAlt = $.trim( $(this).find('img').attr('alt') ); 
  var get_libImgCap = $.trim( $(this).find('img').data('caption') ); 
  var get_libImgDsc = $.trim( $(this).find('img').data('description') ); 

  var splitArr = get_libBoxID.split('_');
  var imgID = splitArr[1];

  /** Just on click select get all info & set object **/
  var innerArray = {};
  innerArray['img_id'] = imgID;
  innerArray['img_titl'] = get_libImgTitle;
  innerArray['img_alt'] = get_libImgAlt;
  innerArray['img_cap'] = get_libImgCap;
  innerArray['img_dsc'] = get_libImgDsc;
  imageInfoCollection[ imgID ] = innerArray;
  /*****************************************/

  if( imageCollection.hasOwnProperty(imgID) ) {
    
  } else {
    $(this).addClass('selet-lib-img');
    imageCollection[ imgID ] = get_libImgPath;
  }
  
  $('#IMG_ID').val( 'Media-Image-' + imgID );
  $('#sele_img_id').val( imgID );
  $('#img_title').val( get_libImgTitle );
  $('#img_alt').val( get_libImgAlt );
  $('#img_caption').val( get_libImgCap );
  $('#img_desc').val( get_libImgDsc );

  ckImgInfoPanel();
  
  if( Object.keys(imageCollection).length > 0 ) {
    $('#addSeletImgs').text('Add ('+ Object.keys(imageCollection).length +')').removeAttr('disabled');
  } else {
    $('#addSeletImgs').text('Add Images').attr('disabled', 'disabled');
  }
  //alert(Object.keys(imageCollection).length);
  //console.log(imageCollection);
});

/**
Dubble click for image remove
**/

$('body').on('dblclick', '#LibraryBox .lib-img-box', function() {
  
  var get_libBoxID = $.trim( $(this).attr('id') );
  
  var splitArr = get_libBoxID.split('_');
  var imgID = splitArr[1];
  
  if( imageCollection.hasOwnProperty(imgID) ) {
     $(this).removeClass('selet-lib-img');
     delete imageCollection[ imgID ];
     delete imageInfoCollection[ imgID ];
     $('#sele_img_id').val( '' );
     $('#img_title').val( '' );
     $('#img_alt').val( '' );
     $('#img_caption').val( '' );
     $('#img_desc').val( '' );
  } else {
    
  }

  ckImgInfoPanel();
  
  if( Object.keys(imageCollection).length > 0 ) {
    $('#addSeletImgs').text('Add ('+ Object.keys(imageCollection).length +')').removeAttr('disabled');
  } else {
    $('#addSeletImgs').text('Add Images').attr('disabled', 'disabled');
  }
  //alert(Object.keys(imageCollection).length);
  //console.log(imageCollection);
});

function onAjaxLoadSelectedImgBox() {
  $('.lib-img-box').each( function() {
    var onload_get_libBoxID = $(this).attr('id');
    var onload_splitArr = onload_get_libBoxID.split('_');
    var onload_imgID = onload_splitArr[1];
    if( imageCollection.hasOwnProperty(onload_imgID) ) {
      $(this).addClass('selet-lib-img');
    }
  });
}
$('body').on('click', '#addSeletImgs', function() {

  var dispImgHtml = '';
  var dispImgIds = '';
  if( Object.keys(imageCollection).length > 0 ) {
    var disp = '';
    for( var k in imageCollection ) {
      dispImgHtml += '<img src="'+ imageCollection[k] +'">';
      dispImgIds += k + ',';
    }
    var actionMidEleEdtId = $('#modalEleSetEdtId').val();

    if( actionMidEleEdtId != '' ) {
      
      var getInsertId = $.trim( $('#IMAGE_CAROUSEL_insert_id').val() );
      var getThisId = $.trim( $('#IMAGE_CAROUSEL_this_id').val() );
      var builderType = $.trim( $('#img_builder_type').val() );
      var deviceType = $.trim( $('#imgDevice').val() );
      $.ajax({
        type : "POST",
        url : "{{ route('pgbAddEdt') }}",
        data : {
          "insert_id" : getInsertId,
          "this_id" : getThisId,
          "builder_type" : builderType,
          "carousel_images" : JSON.stringify( imageInfoCollection ),
          "_token" : "{{ csrf_token() }}",
          "device" : deviceType
        },
        beforeSend : function() {
          $('#addSeletImgs').text('Wait..').attr('disabled', 'disabled');
        },
        success : function( rtnJson ) {
          var obj = JSON.parse( rtnJson );
          var isSuccess = obj.success;
          var msg = obj.msg;
          var action_status = obj.action_status;
          var insert_id = obj.insert_id;
          var IMAGE_CAROUSEL_thisID = obj.this_id;

          if( isSuccess == 'success' ) {
            
            //$('.pgmodal_actionBtn').removeAttr('disabled'); 
            $('#addSeletImgs').text('Add Images').attr('disabled', 'disabled');
            $('#LibraryBox .lib-img-box').removeClass('selet-lib-img');
            imageCollection = {};
            imageInfoCollection = {};
            ckImgInfoPanelBlank();
            $('#imgCarModal').modal('hide');
            
            var html = '<div class="notice notice-info">';
                html += '<div class="row">';
                  html += '<div class="col-md-8">'; 
                    html += '<strong>' + msg + '</strong>';
                  html += '</div>';
                  html += '<div class="col-md-4 txtrit">';
                    html += '<a href="javascript:void(0);" class="pgb_edt" id="' + IMAGE_CAROUSEL_thisID + '" data="' + builderType + '"><i class="fa fa-pencil base-green fa-2x" aria-hidden="true"></i></a>';
                    html += '&nbsp;&nbsp;'
                    html += '<a href="javascript:void(0);" class="pgb_del" id="' + IMAGE_CAROUSEL_thisID + '" data="' + builderType + '"><i class="fa fa-trash-o base-red fa-2x" aria-hidden="true"></i></a>';
                  html += '</div>';
                html += '</div>';
              html += '</div>';

            if( action_status == 'insert' ) {
              $('#pgContentAppend').append('<div class="ar-order altTop ' + builderType + '_holder_' + IMAGE_CAROUSEL_thisID +'" id="' + IMAGE_CAROUSEL_thisID + '">' + html + '</div>');
            }

            if( action_status == 'update' ) {
              $( '.' + builderType + '_holder_' + IMAGE_CAROUSEL_thisID ).html( html );
            }
          }
        }
      });
      /** DISPLAY **/
      //CKEDITOR.instances[ actionMidEleEdtId ].insertHtml('<div>' + dispImgHtml +'</div>'); //if u use ckeditor
      //$('#' + actionMidEleEdtId + '-idholder').val( dispImgIds );
      //$('#' + actionMidEleEdtId + '-infoholder').val( JSON.stringify( imageInfoCollection ) );
      //$('#' + actionMidEleEdtId + '-dispDiv').html( dispImgHtml );
      /** END DISPLAY **/
      
      //imageCollection = {}; // if u want to blank collection
      //imageInfoCollection = {}; // if u want to blank collection

      //$('#addSeletImgs').text('Add Images').attr('disabled', 'disabled'); // if u want to set new action - Add Image
      //$('#LibraryBox .lib-img-box').removeClass('selet-lib-img');
    }
  } 
});


/*
  For Image Gallery Short Code
*/

/**** Image Galleries Short Code ****/
$('body').on('change', '#onlyImgGal', function() {
  if( $(this).val() != '0' && $(this).val() != '' ) {
   $('#addImgGalToEdtBtn').removeAttr('disabled'); 
  } else {
    $('#addImgGalToEdtBtn').attr('disabled', 'disabled');
  }
});
$('body').on('click', '#addImgGalToEdtBtn', function() {
  var galScode = $('#onlyImgGal').val();
  var actionMidEleEdtId = $('#modalEleSetEdtId').val();
  if( actionMidEleEdtId != '' && actionMidEleEdtId != 'undefined') {
    //CKEDITOR.instances[ actionMidEleEdtId ].insertHtml('<div>' + galScode +'</div>');
    $('#imgCarModal').modal('hide');
  }
});
/* ---- End Image Gallery Short Code --- */



/*** SET IMAGE ALL INFO ***/
$('body').on('click', '#setTag', function() {
  var _imgID = $.trim( $('#sele_img_id').val() );
  if( _imgID != '' ) {
    var _imgTitl = $.trim( $('#img_title').val() );
    var _imgAlt = $.trim( $('#img_alt').val() );
    var _imgCap = $.trim( $('#img_caption').val() );
    var _imgDsc = $.trim( $('#img_desc').val() );

    if( $('#setImageInfo_Action').val() == 'SET' ) {
      $('#img-' + _imgID).attr('alt', _imgAlt);
      $('#img-' + _imgID).attr('title', _imgTitl);
      $('#img-' + _imgID).data('caption', _imgCap);
      $('#img-' + _imgID).data('description', _imgDsc);
      
      /** OVERWRITE **/
      var innerArray = {};
      innerArray['img_id'] = _imgID;
      innerArray['img_titl'] = _imgTitl;
      innerArray['img_alt'] = _imgAlt;
      innerArray['img_cap'] = _imgCap;
      innerArray['img_dsc'] = _imgDsc;
      imageInfoCollection[ _imgID ] = innerArray;
      //console.log(JSON.stringify( imageInfoCollection ) );
      alert('Image Information Saved Successfully.');
    } else {
      $.ajax({
        type : "POST",
        url : "{{ route('pgbEdtImg') }}",
        data : {
          "img_title" : _imgTitl,
          "img_alt" : _imgAlt,
          "img_caption" : _imgCap,
          "img_desc" : _imgDsc,
          "img_id" : _imgID,
          "_token"  : "{{ csrf_token() }}"
        },
        cache : false,
        beforeSend : function() {
          $('#setBrochureInfo').attr('disabled', 'disabled');
        },
        success : function(rsp) {
          if( rsp == 'ok' ) {
            $('tr#edtImgtr_' + _imgID).find( "td:eq(1)" ).text( _imgTitl );
            $('tr#edtImgtr_' + _imgID).find( "td:eq(2)" ).text( _imgAlt );
            $('tr#edtImgtr_' + _imgID).find( "td:eq(3)" ).text( _imgCap );
            $('#img_desc_' + _imgID).val( _imgDsc );
            alert('Image Information Updated Successfully');
            ckImgInfoPanelBlank();
            $('.nav-tabs a[href="#carSeleImgs"]').tab('show');
          }
        }
      });
    }
  }
} );

/** Right Panel Checking **/
function ckImgInfoPanel() {
  if( $.trim( $('#sele_img_id').val() ) != '' ) {
    $('#img_title').removeAttr( 'readonly' );
    $('#img_alt').removeAttr( 'readonly' );
    $('#img_caption').removeAttr( 'readonly' );
    $('#img_desc').removeAttr( 'readonly' );
    $('.arp_btn').removeAttr( 'disabled' );
  } else {
    $('#img_title').attr( 'readonly', 'readonly' );
    $('#img_alt').attr( 'readonly', 'readonly' );
    $('#img_caption').attr( 'readonly', 'readonly' );
    $('#img_desc').attr( 'readonly', 'readonly' );
    $('.arp_btn').attr( 'disabled', 'disabled' );
  }
}

function ckImgInfoPanelBlank() {
  $('#IMG_ID').val('Media Image Info');
  $('#img_title').val('').attr( 'readonly', 'readonly' );
  $('#img_alt').val('').attr( 'readonly', 'readonly' );
  $('#img_caption').val('').attr( 'readonly', 'readonly' );
  $('#img_desc').val('').attr( 'readonly', 'readonly' );
  $('.arp_btn').attr( 'disabled', 'disabled' );
  $('#sele_img_id').val('');
}
</script>