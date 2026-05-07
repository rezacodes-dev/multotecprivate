<!------------------------------------------------- Image Media ----------------------------------- -->
<script type="text/javascript">
var imageCollection = new Object();
var imageInfoCollection = new Object();
var Media_isLibTabOpen = 0;
var Media_page = '';
$( function() {
  $('body').on('click', '.addMedImgBtn', function() {
    $('#mediaModal').modal({
      backdrop: 'static',
      keyboard: false
    });

    imageCollection = {};
    imageInfoCollection = {};
    $('#Media_addSeletImgs').text('Add').attr('disabled','disabled');
    $('.thumbnail').removeClass('selet-lib-img');
    elementInitial();
    var getCkEdtID = $(this).attr('data'); // set html element id or editor id
    var getCkEdtTitle = $(this).attr('title'); // set the action title
    $('#Media_modalEleSetEdtId').val( getCkEdtID );
    $('.addOnEdtName').html( ' | ' + getCkEdtTitle );
  });
  $('body').on('click', '.eleModalClose', function() {
    elementInitial();
    $('#mediaModal').modal('hide');
  });
  $('body').on('change', '#Media_imgUpload', function() {
    Media_Ele_Images_Preview(this);
  });
});
$.validator.addMethod('imgsize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param)
}, 'Image size must be less than 12mb.');
var mediaUpload_validator = $("#Media_mediaUpload").validate({
  errorElement : 'span',
  errorClass : 'ar-vali-error',
  rules: {
    "images[]": {
      required: true,
      extension: "jpg|jpeg|png|gif",
      accept: "image/*",
      imgsize: 12000000 
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
        $('#Media_mediaStatus').html('<span><i class="fa fa-circle-o-notch fa-spin fa-x fa-fw"></i> Please Wait...</span>');
      },
      success: function(rtnJson) {
        //console.log($.isEmptyObject(rtnJson));
        var ck = JSON.parse( rtnJson );
        var len = ck.length;
        if( len > 0 ) {
          Media_getLibraryImages(Media_page);
          Media_isLibTabOpen++;
          $('#Media_mediaStatus').html('<span class="base-green"><i class="fa fa-check-square" aria-hidden="true"></i> Images Successfully Added To Media Library.</span>');
          $('#Media_previewBox').fadeOut(1200, function() {
            $('.nav-tabs a[href="#Media_imgLibrary"]').tab('show');
          });
        } else {
          $('#Media_mediaStatus').html('<span class="base-red"><strong>Error:</strong> Images Not Uploaded.</span>');
        }
        form.reset();
      },
      error: function(ajx_err) {
        
      }
    });
    //return false;
  }
});
function Media_Ele_Images_Preview(inputFilesObj) { 
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
      $('#Media_imgUpload_Info').addClass('ar-vali-error').html('<br/><small>Maximum 10 Images Can Upload At Once.</small>');
    }
    previewHTML += '</div>';
    $('#Media_previewBox').show().html( previewHTML );
    if( arErr > 0 ) {
      $('#mediaUpload_BTN').attr('disabled', 'disabled');
      $('#Media_mediaStatus').html( '<span class="ar-vali-error"><strong>Error Found</strong><br/>Check File Extensions & File Size. Maximum 2MB Filesize Can Upload Into Server.</span>' );
    } else {
     $('#Media_imgUpload_Info').removeClass('ar-vali-error').html('');
     $('#mediaUpload_BTN').removeAttr('disabled');
     $('#Media_imgUpload-error').html(''); 
     $('#Media_mediaStatus').html('');
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
  $('#Media_imgUpload_Info').html('');
  $('#Media_mediaStatus').html('');
  $('#Media_imgUpload').val('');
  $('#Media_imgUpload-error').html();
  $('input[type="file"]').val('');
  $('#Media_previewBox').html('');
}
loadImgGals();
function loadImgGals() {
  $.get("{{ route('ajxMediaLdImgGals') }}", function(data, status) {
    if( status == 'success' ) {
      var datArr = JSON.parse(data);
      var datArrLen = datArr.length;
      if( datArrLen > 0 ) {
        var optHTML = '<option value="0">-Category-</option>';
        for( var i = 0; i < datArrLen; i++ ) {
          optHTML += '<option value="'+ datArr[i].id +'">'+ datArr[i].name +'</option>';
        }
        $('.lodGals').html( optHTML );
      }
    }
  });
}


/******** Image Library ************/
$( function() {
  $('body').on('click', '.nav-tabs a[href="#Media_imgLibrary"]', function() {
    if( Media_isLibTabOpen == 0 ) {
      Media_getLibraryImages(Media_page);
      Media_isLibTabOpen++;
    }
  });
  $('body').on('click', '#Media_findImg', function() {
    Media_getLibraryImages(Media_page = '');
    Media_isLibTabOpen++;
  });
  $('body').on('click', '#Media_reloadImgs', function() {
    $('#Media_img_src_txt').val('');
    $('.lodGals').val('0').trigger("change");
    Media_getLibraryImages(Media_page = '');
    Media_isLibTabOpen++;
  });
});
/*$(window).on('hashchange', function() {    
  if (window.location.hash) {
    page = window.location.hash.replace('#', '');
    if (page == Number.NaN || page <= 0) {
      return false;
    } else {
      Media_getLibraryImages(Media_page);
    }
  }
});*/
$(document).ready(function() {
  $(document).on('click', '#Media_LibraryBox .pagination a', function(event) {
    event.preventDefault();
    var myurl = $(this).attr('href');
    Media_page = $(this).attr('href').split('page=')[1];
    Media_getLibraryImages(Media_page);
    $('#Media_LibraryBox ul.pagination li').removeClass('active');
    $(this).parent('li').addClass('active');
  });
});
function Media_getLibraryImages(Media_page) {
  var img_src_txt = $.trim( $('#Media_img_src_txt').val() );
  var img_gal_id = $('#Media_img_gal_id').val();
  $.ajax({
    url: '{{ route("ajxMediaImgLibrary") }}?page=' + Media_page + '&src_txt=' + img_src_txt + '&gallery_id=' + img_gal_id,
    type: "GET",
    datatype: "html",
    beforeSend: function() {
      $('#Media_LibraryBox').block({ 
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
      $('#Media_LibraryBox').unblock();
      $("#Media_LibraryBox").empty().html(data);
      location.hash = Media_page;
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

$('#Media_addSeletImgs').attr('disabled', 'disabled');
$('body').on('click', '#Media_LibraryBox .lib-img-box', function() {
  
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
  
  $('#Media_sele_img_id').val( imgID );
  $('#Media_img_title').val( get_libImgTitle );
  $('#Media_img_alt').val( get_libImgAlt );
  $('#Media_img_caption').val( get_libImgCap );
  $('#Media_img_desc').val( get_libImgDsc );

  ckImgInfoPanel();
  
  if( Object.keys(imageCollection).length > 0 ) {
    $('#Media_addSeletImgs').text('Add ('+ Object.keys(imageCollection).length +')').removeAttr('disabled');
  } else {
    $('#Media_addSeletImgs').text('Add Images').attr('disabled', 'disabled');
  }
  //alert(Object.keys(imageCollection).length);
  //console.log(imageCollection);
});

/**
Dubble click for image remove
**/

$('body').on('dblclick', '#Media_LibraryBox .lib-img-box', function() {
  
  var get_libBoxID = $.trim( $(this).attr('id') );
  
  var splitArr = get_libBoxID.split('_');
  var imgID = splitArr[1];
  
  if( imageCollection.hasOwnProperty(imgID) ) {
     $(this).removeClass('selet-lib-img');
     delete imageCollection[ imgID ];
     delete imageInfoCollection[ imgID ];
     $('#Media_sele_img_id').val( '' );
     $('#Media_img_title').val( '' );
     $('#Media_img_alt').val( '' );
     $('#Media_img_caption').val( '' );
     $('#Media_img_desc').val( '' );
  } else {
    
  }

  ckImgInfoPanel();
  
  if( Object.keys(imageCollection).length > 0 ) {
    $('#Media_addSeletImgs').text('Add ('+ Object.keys(imageCollection).length +')').removeAttr('disabled');
  } else {
    $('#Media_addSeletImgs').text('Add Images').attr('disabled', 'disabled');
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
$('body').on('click', '#Media_addSeletImgs', function() {
  var dispImgHtml = '';
  var dispImgIds = '';
  if( Object.keys(imageCollection).length > 0 ) {
    var disp = '';
    for( var k in imageCollection ) {
      dispImgHtml += '<div class="thumbnail2" style="float: left; margin-left: 5px; margin-top: 5px;"><img src="'+ imageCollection[k] +'"></div>';
      dispImgIds += k + ',';
    }
    var actionMidEleEdtId = $('#Media_modalEleSetEdtId').val();
    if( actionMidEleEdtId != '' ) {
      
      /** DISPLAY **/
      //CKEDITOR.instances[ actionMidEleEdtId ].insertHtml('<div>' + dispImgHtml +'</div>'); //if u use ckeditor
      $('#' + actionMidEleEdtId + '-idholder').val( dispImgIds );
      $('#' + actionMidEleEdtId + '-infoholder').val( JSON.stringify( imageInfoCollection ) );
      $('#' + actionMidEleEdtId + '-dispDiv').html( dispImgHtml );
      /** END DISPLAY **/
      
      //imageCollection = {}; // if u want to blank collection
      //imageInfoCollection = {}; // if u want to blank collection

      //$('#addSeletImgs').text('Add Images').attr('disabled', 'disabled'); // if u want to set new action - Add Image
      //$('#LibraryBox .lib-img-box').removeClass('selet-lib-img');
      $('#mediaModal').modal('hide');
    }
  } 
});
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
    $('#mediaModal').modal('hide');
  }
});
/*** SET IMAGE ALL INFO ***/
$('body').on('click', '#Media_setTag', function() {
  var _imgID = $.trim( $('#Media_sele_img_id').val() );
  if( _imgID != '' ) {
    var _imgTitl = $.trim( $('#Media_img_title').val() );
    var _imgAlt = $.trim( $('#Media_img_alt').val() );
    var _imgCap = $.trim( $('#Media_img_caption').val() );
    var _imgDsc = $.trim( $('#Media_img_desc').val() );
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
    console.log(JSON.stringify( imageInfoCollection ) );
    alert('Image Information Saved.');
  }
} );

/** Right Panel Checking **/
function ckImgInfoPanel() {
  if( $.trim( $('#Media_sele_img_id').val() ) != '' ) {
    $('#Media_img_title').removeAttr( 'readonly' );
    $('#Media_img_alt').removeAttr( 'readonly' );
    $('#Media_img_caption').removeAttr( 'readonly' );
    $('#Media_img_desc').removeAttr( 'readonly' );
    $('.arp_btn').removeAttr( 'disabled' );
  } else {
    $('#Media_img_title').attr( 'readonly', 'readonly' );
    $('#Media_img_alt').attr( 'readonly', 'readonly' );
    $('#Media_img_caption').attr( 'readonly', 'readonly' );
    $('#Media_img_desc').attr( 'readonly', 'readonly' );
    $('.arp_btn').attr( 'disabled', 'disabled' );
  }
}
</script>