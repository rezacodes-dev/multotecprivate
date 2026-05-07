<script type="text/javascript">
var isVidLibTabOpen = 0;
var page = '';
$( function() {
	$('body').on('click', '.pgb_vidbtn', function() {

		$('#vid_builder_type').val( 'VIDEO_GALLERY' );
    	$('a[href="#vidSelected"]').hide();
    	$('.nav-tabs a[href="#vidUpload"]').tab('show');
    	$('#setVidInfo_Action').val( 'SET' );
      $('#VIDEO_this_id').val('0');
      $('select[name="device"]').val('3');


		$('#videoModal').modal({
			backdrop: 'static',
      keyboard: false
		});
	} );
} );

var vidAddFrm = $("#vidAddFrm").validate({
  errorElement : 'span',
  errorClass : 'ar-vali-error',
  rules: {
    "name": {
      required: true
    },
    "video_type" : {
    	required: true
    },
    "link_script" : {
    	required: true
    }
  },
  messages: {
    "name": {
      required: 'Please enter video name.'
    },
    "video_type" : {
    	required: 'Please select video type/format.'
    },
    "link_script" : {
    	required: 'Please enter video.'
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
        $('#vidAddBtn').attr('disabled', 'disabled');
        $('#addvidStatus').html('<span><i class="fa fa-circle-o-notch fa-spin fa-x fa-fw"></i> Please Wait...</span>');
      },
      success: function(rtnJson) {
        //console.log($.isEmptyObject(rtnJson));
        var ck = JSON.parse( rtnJson );
        if( ck.isSuccess == 'success' ) {
          getLibraryVideos(page = '');
          isVidLibTabOpen++;
          $('#vidAddBtn').removeAttr('disabled');
          $('#addvidStatus').html('<span class="base-green"><i class="fa fa-check-square" aria-hidden="true"></i> Video Successfully Added To Media Library.</span>');
          $('.nav-tabs a[href="#vidLibrary"]').tab('show');
        } else {
          $('#addvidStatus').html('<span class="base-red"><strong>Error:</strong> Video Not Added.</span>');
        }
        form.reset();
      },
      error: function(ajx_err) {
        
      }
    });
    //return false;
  }
});

$( function() {
	$('body').on('click', '.nav-tabs a[href="#vidLibrary"]', function() {
	    if( isVidLibTabOpen == 0 ) {
	      //ckImgInfoPanelBlank();
	      getLibraryVideos(page);
	      isVidLibTabOpen++;
	    }
	});
	$('body').on('click', '#findVids', function() {
	    getLibraryVideos(page = '');
	    //ckImgInfoPanelBlank();
	    isVidLibTabOpen++;
	});
	  $('body').on('click', '#reloadVids', function() {
	    $('#vid_src_txt').val('');
	    //ckImgInfoPanelBlank();
	    getLibraryVideos(page = '');
	    isVidLibTabOpen++;
	});
} );
/*$(window).on('hashchange', function() {    
  if (window.location.hash) {
    page = window.location.hash.replace('#', '');
    if (page == Number.NaN || page <= 0) {
      return false;
    } else {
      getLibraryVideos(page);
    }
  }
});*/
$(document).ready(function() {
  $(document).on('click', '#VidLibContainer .pagination a', function(event) {
    event.preventDefault();
    var myurl = $(this).attr('href');
    page = $(this).attr('href').split('page=')[1];
    getLibraryVideos(page);
    $('#VidLibContainer ul.pagination li').removeClass('active');
    $(this).parent('li').addClass('active');
  });
});

function getLibraryVideos(page) {
  var vid_src_txt = $.trim( $('#vid_src_txt').val() );
  $.ajax({
    url: '{{ route("ajxMediaVidLibrary") }}?page=' + page + '&src_txt=' + vid_src_txt ,
    type: "GET",
    datatype: "html",
    beforeSend: function() {
      $('#VidLibContainer').block({ 
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
      $('#VidLibContainer').unblock();
      $("#VidLibContainer").empty().html(data);
      location.hash = page;
    },
    error: function(jqXHR, ajaxOptions, thrownError) {
      alert('No response from server');
    }
  });
}

var vidInfoCollection = new Object();

$( function() {
	$('body').on('click', '.vidAddToArrayBtn', function() {
		
		var _vidID = $(this).attr('id');
		if( vidInfoCollection.hasOwnProperty(_vidID) ) {
			delete vidInfoCollection[ _vidID ];
			$('#vidBox_' + _vidID).removeClass('ar-select-tr');
			$(this).addClass('btn-primary').removeClass('btn-danger').val('Add');
			$('#VID_INFO_ID').val('MEDIA-VIDEO');
			$('#vid_name').val('').attr('readonly', 'readonly');
			$('#vid_title').val('').attr('readonly', 'readonly');
			$('#vid_caption').val('').attr('readonly', 'readonly');
			$('#sele_vid_id').val('');
			$('#setVidInfo_Action').val( 'SET' );
			$('#setVidTag').attr('disabled', 'disabled');
		} else {

			var _vidName = $('#vid_name_' + _vidID).val();
			var _vidTitle = $('#vid_title_' + _vidID).val();
			var _vidCaption = $('#vid_caption_' + _vidID).val();

			var innerArray = {};
			innerArray['vidID'] = _vidID;
			innerArray['vidName'] = _vidName;
			innerArray['vidTitle'] = _vidTitle;
			innerArray['vidCaption'] = _vidCaption;
			vidInfoCollection[ _vidID ] = innerArray;

			$('#VID_INFO_ID').val('VIDEO-' + _vidID);
			$('#vid_name').val( _vidName ).removeAttr('readonly');
			$('#vid_title').val( _vidTitle ).removeAttr('readonly');
			$('#vid_caption').val( _vidCaption ).removeAttr('readonly');
			$('#sele_vid_id').val( _vidID );
			$('#setVidInfo_Action').val( 'SET' );
			$('#setVidTag').removeAttr('disabled');

			$('#vidBox_' + _vidID).addClass('ar-select-tr');
			$(this).addClass('btn-danger').removeClass('btn-primary').val('Remove');
		}

		if( Object.keys(vidInfoCollection).length > 0 ) {
		    $('#addSeletVids').text('Add ('+ Object.keys(vidInfoCollection).length +')').removeAttr('disabled');
		} else {
		    $('#addSeletVids').text('Add Files').attr('disabled', 'disabled');
		}
	} );
	$('body').on('click', '#setVidTag', function() {

		var _getVidId = $.trim( $('#sele_vid_id').val() ); 
		if( _getVidId != '' ) {

			var _getVidName = $.trim( $('#vid_name').val() );
			var _getVidTitle = $.trim( $('#vid_title').val() );
			var _getVidCaption = $.trim( $('#vid_caption').val() );

			if( $.trim( $('#setVidInfo_Action').val() ) == 'SET' ) {

				$('#vid_name_' + _getVidId).val( _getVidName );
				$('#vid_title_' + _getVidId).val( _getVidTitle );
				$('#vid_caption_' + _getVidId).val( _getVidCaption );

				var innerArray = {};
				innerArray['vidID'] = _getVidId;
				innerArray['vidName'] = _getVidName;
				innerArray['vidTitle'] = _getVidTitle;
				innerArray['vidCaption'] = _getVidCaption;
				vidInfoCollection[ _getVidId ] = innerArray;
				alert('Video Information Saved Successfully.');
				//console.log( vidInfoCollection );
			} else {
				$.ajax({
			        type : "POST",
			        url : "{{ route('pgbEdtVid') }}",
			        data : {
			          "name" : _getVidName,
			          "title" : _getVidTitle,
			          "caption" : _getVidCaption,
			          "video_id" : _getVidId,
			          "_token"  : "{{ csrf_token() }}"
			        },
			        cache : false,
			        beforeSend : function() {
			          $('#setVidTag').attr('disabled', 'disabled');
			        },
			        success : function(rsp) {
			          if( rsp == 'ok' ) {
			            $('tr#editVidTr_' + _getVidId).find( "td:eq(1)" ).text( _getVidName );
			            $('tr#editVidTr_' + _getVidId).find( "td:eq(2)" ).text( _getVidTitle );
			            $('tr#editVidTr_' + _getVidId).find( "td:eq(3)" ).text( _getVidCaption );
			            alert('Video Information Saved Successfully.');
			            vidInfoPanelBlock();
			            $('.nav-tabs a[href="#vidSelected"]').tab('show');
			          }
			        }
			    });
			}
		}
	} );
} );


$('body').on('click', '#addSeletVids', function() {

  if( Object.keys(vidInfoCollection).length > 0 ) {
      var getInsertId = $.trim( $('#VIDEO_insert_id').val() );
      var getThisId = $.trim( $('#VIDEO_this_id').val() );
      var builderType = $.trim( $('#vid_builder_type').val() );
      var deviceType = $.trim( $('#vidDevice').val() );

      if( getInsertId != '' && getThisId != '' && builderType != '' ) {
        $.ajax({
          type : "POST",
          url : "{{ route('pgbAddEdt') }}",
          data : {
            "insert_id" : getInsertId,
            "this_id" : getThisId,
            "builder_type" : builderType,
            "videos" : JSON.stringify( vidInfoCollection ),
            "_token" : "{{ csrf_token() }}",
            "device" : deviceType
          },
          beforeSend : function() {
            $('#addSeletVids').text('Wait..').attr('disabled', 'disabled');
          },
          success : function( rtnJson ) {
            var obj = JSON.parse( rtnJson );
            var isSuccess = obj.success;
            var msg = obj.msg;
            var action_status = obj.action_status;
            var insert_id = obj.insert_id;
            var VID_thisID = obj.this_id;
            vidInfoPanelBlock();

            if( isSuccess == 'success' ) {
              
              //$('.pgmodal_actionBtn').removeAttr('disabled'); 
              $('#addSeletVids').text('Add Files').attr('disabled', 'disabled');
              $('#VidLibContainer .vbox').removeClass('ar-select-tr');
              vidInfoCollection = {};
              $('#videoModal').modal('hide');
            
              var html = '<div class="notice notice-info">';
                  html += '<div class="row">';
                    html += '<div class="col-md-8">'; 
                      html += '<strong>' + msg + '</strong>';
                    html += '</div>';
                    html += '<div class="col-md-4 txtrit">';
                      html += '<a href="javascript:void(0);" class="pgb_edt" id="' + VID_thisID + '" data="' + builderType + '"><i class="fa fa-pencil base-green fa-2x" aria-hidden="true"></i></a>';
                      html += '&nbsp;&nbsp;'
                      html += '<a href="javascript:void(0);" class="pgb_del" id="' + VID_thisID + '" data="' + builderType + '"><i class="fa fa-trash-o base-red fa-2x" aria-hidden="true"></i></a>';
                    html += '</div>';
                  html += '</div>';
                html += '</div>';

            if( action_status == 'insert' ) {
              $('#pgContentAppend').append('<div class="ar-order altTop ' + builderType + '_holder_' + VID_thisID +'" id="' + VID_thisID + '">' + html + '</div>');
            }

            if( action_status == 'update' ) {
              $( '.' + builderType + '_holder_' + VID_thisID ).html( html );
            }
          }
        }
      });
    }
  } 
});

function vidInfoPanelBlock() {
	$('#VID_INFO_ID').val('MEDIA-VIDEO');
	$('#vid_name').val('').attr('readonly', 'readonly');
	$('#vid_title').val('').attr('readonly', 'readonly');
	$('#vid_caption').val('').attr('readonly', 'readonly');
	$('#sele_vid_id').val('');
	$('#setVidInfo_Action').val( 'SET' );
	$('#setVidTag').attr('disabled', 'disabled');
}
</script>