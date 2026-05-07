<!------------------------------------------------- Image Media ----------------------------------- -->
<script type="text/javascript">
var brochure_isLibTabOpen = 0;
var brochure_page = '';
$( function() {
  $('body').on('click', '.pgb_brochure_btn', function() {

    $('#file_builder_type').val( 'BROCHURE_BUTT' );
    $('#BROCHURE_BUTT_this_id').val('0');
    $('a[href="#brochureSeletFiles"]').hide();
    $('.nav-tabs a[href="#brochureUpload"]').tab('show');
    $('#setBrochureInfo_Action').val( 'SET' );
    $('select[name="device"]').val('3');
    
    $('#brochureModal').modal({
      backdrop: 'static',
      keyboard: false
    });

    brochure_elementInitial();
    //var getCkEdtID = $(this).attr('data'); // set html element id or editor id
    //var getCkEdtTitle = $(this).attr('title'); // set the action title
    //$('#modalEleSetEdtId').val( getCkEdtID );
    //$('.addOnEdtName').html( ' | ' + getCkEdtTitle );
  });


  /*$('body').on('click', '.pgb_techres_btn', function() {

    $('#file_builder_type').val( 'TECHRES_BUTT' );
    $('a[href="#brochureSeletFiles"]').hide();
    $('.nav-tabs a[href="#brochureUpload"]').tab('show');
    $('#setBrochureInfo_Action').val( 'SET' );

    $('#brochureModal').modal({
      backdrop: 'static',
      keyboard: false
    });

    brochure_elementInitial();
    
  });*/


  $('body').on('click', '.brochureModalClose', function() {
    brochure_elementInitial();
    $('#brochureModal').modal('hide');
  });
  $('body').on('change', '#brochureFiles', function() {
    Media_Files_Preview(this);
  });
});
$.validator.addMethod('filesize', function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param)
}, 'File size must be less than 2mb.');
var brochureFileUploadFrm_validator = $("#brochureFileUploadFrm").validate({
  errorElement : 'span',
  errorClass : 'ar-vali-error',
  rules: {
    "brochure[]": {
      required: true,
      filesize: 2000000
    }
  },
  messages: {
    "brochure[]": {
      required: 'Please Select File(s).',
      accept: 'Please Select Only PDF/Word/Excel/PPT Files.',
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
        $('#brochureUpload_BTN').attr('disabled', 'disabled');
        $('#brochureUploadStatus').html('<span><i class="fa fa-circle-o-notch fa-spin fa-x fa-fw"></i> Please Wait...</span>');
      },
      success: function(rtnJson) {
        //console.log($.isEmptyObject(rtnJson));
        var ck = JSON.parse( rtnJson );
        var len = ck.length;
        if( len > 0 ) {
          getBrochures(brochure_page = '');
          brochure_isLibTabOpen++;
          $('#brochureUploadStatus').html('<span class="base-green"><i class="fa fa-check-square" aria-hidden="true"></i> Brochure Successfully Added To Media Library.</span>');
          $('#brochureUploadpreviewBox').fadeOut(1200, function() {
            $('.nav-tabs a[href="#brochureFileLibrary"]').tab('show');
          });
        } else {
          $('#brochureUploadStatus').html('<span class="base-red"><strong>Error:</strong> File Not Uploaded.</span>');
        }
        form.reset();
      },
      error: function(ajx_err) {
        
      }
    });
    //return false;
  }
});
function Media_Files_Preview(inputFilesObj) { 
  if( inputFilesObj.files && inputFilesObj.files[0] ) {
    var arErr = 0;
    var previewHTML = '<div class="row">';
    var fileCount = inputFilesObj.files.length;
    if( fileCount > 0 && fileCount <= 10 ) {
      for( var i = 0; i < fileCount; i++ ) {
        var eachSize = inputFilesObj.files[i].size;
        var eachName = inputFilesObj.files[i].name;
        var eachExtn = eachName.split('.').pop().toLowerCase();
        if( eachExtn == 'pdf' || eachExtn == 'doc' || eachExtn == 'docx' || eachExtn == 'xls' || eachExtn == 'csv' ) {
          if( eachSize <= 2000000 ) {
            
          } else {
            arErr++;
          }
        } else {
          arErr++;
        }
      }
    } else {
      arErr++;
      $('#brochureUpload_Info').addClass('ar-vali-error').html('<br/><small>Maximum 10 Files Can Upload At Once.</small>');
    }
    previewHTML += '</div>';
    $('#brochureUploadpreviewBox').show().html( previewHTML );
    if( arErr > 0 ) {
      $('#brochureUpload_BTN').attr('disabled', 'disabled');
      $('#brochureUploadStatus').html( '<span class="ar-vali-error"><strong>Error Found</strong><br/>Check File Extensions & File Size. Maximum 2MB Filesize Can Upload Into Server.</span>' );
    } else {
     $('#brochureUpload_Info').removeClass('ar-vali-error').html('');
     $('#brochureUpload_BTN').removeAttr('disabled');
     $('#brochureFiles-error').html(''); 
     $('#brochureUploadStatus').html('');
    }
  }
}
function bytesToSize(bytes) {
  var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
  if (bytes == 0) return '0 Byte';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
  return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}
function brochure_elementInitial() {
  $('#brochureUpload_BTN').attr('disabled', 'disabled');
  $('#brochureUpload_Info').html('');
  $('#brochureUploadStatus').html('');
  $('#brochureFiles').val('');
  $('#brochureFiles-error').html('');
  $('input[type="file"]').val('');
  $('#brochureUploadpreviewBox').html('');
  brochureFileUploadFrm_validator.resetForm();
}
loadFileCats();
function loadFileCats() {
  $.get("{{ route('ajxMediaLdFlCats') }}", function(data, status) {
    if( status == 'success' ) {
      var datArr = JSON.parse(data);
      var datArrLen = datArr.length;
      if( datArrLen > 0 ) {
        var optHTML = '<option value="0">File Categories</option>';
        for( var i = 0; i < datArrLen; i++ ) {
          optHTML += '<option value="'+ datArr[i].id +'">'+ datArr[i].name +'</option>';
        }
        $('.lodFilCats').html( optHTML );
      }
    }
  });
}


/******** Image Library ************/
$( function() {
  $('body').on('click', '.nav-tabs a[href="#brochureFileLibrary"]', function() {    
    if( brochure_isLibTabOpen == 0 ) {
      getBrochures(brochure_page);
      ckBrochureInfoPanelBlank();
      brochure_isLibTabOpen++;
    }
  });

  $('body').on('click', '#brochure_find', function() {
    getBrochures(brochure_page = '');
    ckBrochureInfoPanelBlank();
    brochure_isLibTabOpen++;
  });

  $('body').on('click', '#reloadBrochure', function() {
    $('#img_src_txt').val('');
    $('.lodGals').val('0').trigger("change");
    getBrochures(brochure_page = '');
    ckBrochureInfoPanelBlank();
    brochure_isLibTabOpen++;
  });
});
$(window).on('hashchange', function() {    
  if (window.location.hash) {
    brochure_page = window.location.hash.replace('#', '');
    if (brochure_page == Number.NaN || brochure_page <= 0) {
      return false;
    } else {
      getBrochures(brochure_page);
    }
  }
});
$(document).ready(function() {
  $(document).on('click', '#brochureLibraryBox .pagination a', function(event) {
    event.preventDefault();
    var myurl = $(this).attr('href');
    brochure_page = $(this).attr('href').split('page=')[1];
    getBrochures(brochure_page);
    $('#brochureLibraryBox ul.pagination li').removeClass('active');
    $(this).parent('li').addClass('active');
  });
});
function getBrochures(brochure_page) {
  var brochure_category_id = $.trim( $('#brochure_category_id').val() );
  var brochure_src_txt = $.trim( $('#brochure_src_txt').val() );
  $.ajax({
    url: '{{ route("ajxMediaFileLibrary") }}?page=' + brochure_page + '&category_id=' + brochure_category_id + '&src_txt=' + brochure_src_txt,
    type: "GET",
    datatype: "html",
    beforeSend: function() {
      $('#brochureLibraryBox').block({ 
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
      $('#brochureLibraryBox').unblock();
      $("#brochureLibraryBox").empty().html(data);
      location.hash = brochure_page;
      //onAjaxLoadSelectedImgBox();
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

var brochureFileInfoCollection = new Object();

$('#addSeletBrochure').attr('disabled', 'disabled');
$('body').on('click', '#brochureLibraryBox .fileTr', function() {
  
  $('#setBrochureInfo_Action').val( 'SET' );

  var get_brochureTrID = $.trim( $(this).attr('id') );

  var splitArr = get_brochureTrID.split('_');
  var brochureFileID = splitArr[1];

  var brochure_fileName = $( '#fileName_' + brochureFileID ).val();
  var brochure_fileTitle = $( '#fileTitle_' + brochureFileID ).val();
  var brochure_fileCaption = $( '#fileCaption_' + brochureFileID ).val();
  var brochure_fileDesc = $( '#fileDesc_' + brochureFileID ).val();

  /** Just on click select get all info & set object **/
  var innerArray = {};
  innerArray['brochure_file_id'] = brochureFileID;
  innerArray['brochure_name'] = brochure_fileName;
  innerArray['brochure_title'] = brochure_fileTitle;
  innerArray['brochure_caption'] = brochure_fileCaption;
  innerArray['brochure_desc'] = brochure_fileDesc;
  brochureFileInfoCollection[ brochureFileID ] = innerArray;
  /*****************************************/
  $(this).addClass('ar-select-tr');
  
  $('#BROCHURE_FILE').val( 'Media-File-' + brochureFileID );
  $('#sele_brochure_id').val( brochureFileID );
  $('#brochure_name').val( brochure_fileName );
  $('#brochure_title').val( brochure_fileTitle );
  $('#brochure_caption').val( brochure_fileCaption );
  $('#brochure_desc').val( brochure_fileDesc );

  ckBrochureInfoPanel();
  
  if( Object.keys(brochureFileInfoCollection).length > 0 ) {
    $('#addSeletBrochure').text('Add ('+ Object.keys(brochureFileInfoCollection).length +')').removeAttr('disabled');
  } else {
    $('#addSeletBrochure').text('Add Files').attr('disabled', 'disabled');
  }
  //alert(Object.keys(imageCollection).length);
  //console.log(imageCollection);
});

/**
Dubble click for image remove
**/

$('body').on('dblclick', '#brochureLibraryBox .fileTr', function() {
  
  var get_brochureTrID = $.trim( $(this).attr('id') );

  var splitArr = get_brochureTrID.split('_');
  var brochureFileID = splitArr[1];
  
  if( brochureFileInfoCollection.hasOwnProperty(brochureFileID) ) {
     $(this).removeClass('ar-select-tr');
     delete brochureFileInfoCollection[ brochureFileID ];
      $('#BROCHURE_FILE').val( '' );
      $('#sele_brochure_id').val( '' );
      $('#brochure_name').val( '' );
      $('#brochure_title').val( '' );
      $('#brochure_caption').val( '' );
      $('#brochure_desc').val( '' );
  }

  ckBrochureInfoPanel();
  
  if( Object.keys(brochureFileInfoCollection).length > 0 ) {
    $('#addSeletBrochure').text('Add ('+ Object.keys(brochureFileInfoCollection).length +')').removeAttr('disabled');
  } else {
    $('#addSeletBrochure').text('Add Files').attr('disabled', 'disabled');
  }
  //alert(Object.keys(imageCollection).length);
  //console.log(imageCollection);
});

/*function onAjaxLoadSelectedImgBox() {
  $('.lib-img-box').each( function() {
    var onload_get_libBoxID = $(this).attr('id');
    var onload_splitArr = onload_get_libBoxID.split('_');
    var onload_imgID = onload_splitArr[1];
    if( imageCollection.hasOwnProperty(onload_imgID) ) {
      $(this).addClass('selet-lib-img');
    }
  });
}*/
$('body').on('click', '#addSeletBrochure', function() {

  if( Object.keys(brochureFileInfoCollection).length > 0 ) {
      var getInsertId = $.trim( $('#BROCHURE_BUTT_insert_id').val() );
      var getThisId = $.trim( $('#BROCHURE_BUTT_this_id').val() );
      var builderType = $.trim( $('#file_builder_type').val() );
      var deviceType = $.trim( $('#filDevice').val() );
      if( getInsertId != '' && getThisId != '' && builderType != '' ) {
        $.ajax({
          type : "POST",
          url : "{{ route('pgbAddEdt') }}",
          data : {
            "insert_id" : getInsertId,
            "this_id" : getThisId,
            "builder_type" : builderType,
            "brochures" : JSON.stringify( brochureFileInfoCollection ),
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
            var BROCHURE_BUTT_thisID = obj.this_id;

            if( isSuccess == 'success' ) {
              
              //$('.pgmodal_actionBtn').removeAttr('disabled'); 
              $('#addSeletBrochure').text('Add Files').attr('disabled', 'disabled');
              $('#brochureLibraryBox .fileTr').removeClass('ar-select-tr');
              brochureFileInfoCollection = {};
              ckBrochureInfoPanelBlank();
              $('#brochureModal').modal('hide');
            
              var html = '<div class="notice notice-info">';
                  html += '<div class="row">';
                    html += '<div class="col-md-8">'; 
                      html += '<strong>' + msg + '</strong>';
                    html += '</div>';
                    html += '<div class="col-md-4 txtrit">';
                      html += '<a href="javascript:void(0);" class="pgb_edt" id="' + BROCHURE_BUTT_thisID + '" data="' + builderType + '"><i class="fa fa-pencil base-green fa-2x" aria-hidden="true"></i></a>';
                      html += '&nbsp;&nbsp;'
                      html += '<a href="javascript:void(0);" class="pgb_del" id="' + BROCHURE_BUTT_thisID + '" data="' + builderType + '"><i class="fa fa-trash-o base-red fa-2x" aria-hidden="true"></i></a>';
                    html += '</div>';
                  html += '</div>';
                html += '</div>';

            if( action_status == 'insert' ) {
              $('#pgContentAppend').append('<div class="ar-order altTop ' + builderType + '_holder_' + BROCHURE_BUTT_thisID +'" id="' + BROCHURE_BUTT_thisID + '">' + html + '</div>');
            }

            if( action_status == 'update' ) {
              $( '.' + builderType + '_holder_' + BROCHURE_BUTT_thisID ).html( html );
            }
          }
        }
      });
    }
  } 
});

/*** SET IMAGE ALL INFO ***/
$('body').on('click', '#setBrochureInfo', function() {
  var _brochureFileID = $.trim( $('#sele_brochure_id').val() );
  if( _brochureFileID != '' ) {
    var _brochure_fileName = $.trim( $('#brochure_name').val() );
    var _brochure_fileTitle = $.trim( $('#brochure_title').val() );
    var _brochure_fileCaption = $.trim( $('#brochure_caption').val() );
    var _brochure_fileDesc = $.trim( $('#brochure_desc').val() );
    
    if( $('#setBrochureInfo_Action').val() == 'SET' ) {
      $( '#fileName_' + _brochureFileID ).val( _brochure_fileName );
      $( '#fileTitle_' + _brochureFileID ).val( _brochure_fileTitle );
      $( '#fileCaption_' + _brochureFileID ).val( _brochure_fileCaption );
      $( '#fileDesc_' + _brochureFileID ).val( _brochure_fileDesc );

      /** OVERWRITE **/
      var innerArray = {};
      innerArray['brochure_name'] = _brochure_fileName;
      innerArray['brochure_title'] = _brochure_fileTitle;
      innerArray['brochure_caption'] = _brochure_fileCaption;
      innerArray['brochure_desc'] = _brochure_fileDesc;
      brochureFileInfoCollection[ _brochureFileID ] = innerArray;
      alert('File Information Saved Successfully.');
    } else {
      $.ajax({
        type : "POST",
        url : "{{ route('pgbEdtFil') }}",
        data : {
          "name" : _brochure_fileName,
          "title" : _brochure_fileTitle,
          "caption" : _brochure_fileCaption,
          "details" : _brochure_fileDesc,
          "file_id" : _brochureFileID,
          "_token"  : "{{ csrf_token() }}"
        },
        cache : false,
        beforeSend : function() {
          $('#setBrochureInfo').attr('disabled', 'disabled');
        },
        success : function(rsp) {
          if( rsp == 'ok' ) {
            $('tr#editFilTr_' + _brochureFileID).find( "td:eq(1)" ).text( _brochure_fileName );
            $('tr#editFilTr_' + _brochureFileID).find( "td:eq(2)" ).text( _brochure_fileTitle );
            $('tr#editFilTr_' + _brochureFileID).find( "td:eq(3)" ).text( _brochure_fileCaption );
            $('#file_desc_' + _brochureFileID).val( _brochure_fileDesc );
            alert('File Information Updated Successfully');
            ckBrochureInfoPanelBlank();
            $('.nav-tabs a[href="#brochureSeletFiles"]').tab('show');
          }
        }
      });
    }
  }
} );

/** Right Panel Checking **/
function ckBrochureInfoPanel() {
  if( $.trim( $('#sele_brochure_id').val() ) != '' ) {
    $('#brochure_name').removeAttr( 'readonly' );
    $('#brochure_title').removeAttr( 'readonly' );
    $('#brochure_caption').removeAttr( 'readonly' );
    $('#brochure_desc').removeAttr( 'readonly' );
    $('.arp_btn').removeAttr( 'disabled' );
  } else {
    $('#brochure_name').attr( 'readonly', 'readonly' );
    $('#brochure_title').attr( 'readonly', 'readonly' );
    $('#brochure_caption').attr( 'readonly', 'readonly' );
    $('#brochure_desc').attr( 'readonly', 'readonly' );
    $('.arp_btn').attr( 'disabled', 'disabled' );
  }
}

function ckBrochureInfoPanelBlank() {
  $('#BROCHURE_FILE').val('Media File Info');
  $('#brochure_name').val('').attr( 'readonly', 'readonly' );
  $('#brochure_title').val('').attr( 'readonly', 'readonly' );
  $('#brochure_caption').val('').attr( 'readonly', 'readonly' );
  $('#brochure_desc').val('').attr( 'readonly', 'readonly' );
  $('.arp_btn').attr( 'disabled', 'disabled' );
  $('#sele_brochure_id').val('');
}

$('body').on('change', '.lodFilCats', function() {
  $.ajax({
    type : "POST",
    url : "{{ route('ajxMediaLdFlSCats') }}",
    data : {
      "cat_id" : $(this).val(),
      "_token" : "{{ csrf_token() }}"
    },
    beforeSend: function() {
      $('.lodFilCats').attr('disabled', 'disabled');
      $('.lodFilSCats').attr('disabled', 'disabled');
    },
    success: function(scatJson) {
      $('.lodFilCats').removeAttr('disabled', 'disabled');
      $('.lodFilSCats').removeAttr('disabled', 'disabled');
      var datArr = JSON.parse(scatJson);
      var datArrLen = datArr.length;
      if( datArrLen > 0 ) {
        var optHTML = '<option value="0">Select Subcategories</option>';
        for( var i = 0; i < datArrLen; i++ ) {
          optHTML += '<option value="'+ datArr[i].id +'">'+ datArr[i].name +'</option>';
        }
        $('.lodFilSCats').html( optHTML );
      } else {
        $('.lodFilSCats').html( '<option value="0">Select Subcategories</option>' );
      }
    }
  });
} );
</script>