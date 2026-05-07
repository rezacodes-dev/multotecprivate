<script type="text/javascript">
$( function() {
	var pgbReuseLoad = 0;
	$('body').on('click', '.pgb_reusecont', function() {

		$('#REUSE_this_id').val('0');
		$('select[name="device"]').val('3');
		$("#REUSE_main_content").val('').trigger('change')

		if( pgbReuseLoad == 0 ) {
			$.ajax({
				type: "GET",
				url : "{{ route('pgbAllReuse') }}",
				beforeSend : function() {
					$('#REUSE_ajx_status').html( 'Form loading.. , Please wait...' );
				},
				success : function(allFrms) {
					var objFrm = JSON.parse( allFrms );
					var objLen = objFrm.length;
					var drpHtml = '<option></option>';
					if( objLen > 0 ) {
						for( var frm = 0; frm < objLen; frm++ ) {
							drpHtml += '<option value="' + objFrm[ frm ].short_code + '">' + objFrm[ frm ].name + '</option>'; 
						}
					}
					$('#REUSE_main_content').html( drpHtml );
					$('#REUSE_ajx_status').html( '' );
					pgbReuseLoad++;
				}
			});
		}
		
		$('#pgb_reuse_modal').modal({
			backdrop: 'static',
      		keyboard: false
		});
		
	} );
	
	var pgb_reuse_frm = $('#pgb_reuse_frm');
	
	pgb_reuse_frm.validate({
  		errorElement: 'span',
  		errorClass : 'roy-vali-error',
  		ignore: [],
		normalizer: function( value ) {
		return $.trim( value );
		},
  		rules: {
		    main_content: {
		      required: true
		    },
		    insert_id: {
		    	required: true
		    },
		    builder_type: {
		    	required: true
		    },
		    this_id: {
		    	required: true
		    }
		},
		messages: {

		    main_content: {
		      required: 'Please Select Any Reusable Content.'
		    },
		    insert_id: {
		    	required: 'SERVER ERROR :: InsertID Not Created!'
		    },
		    builder_type: {
		    	required: 'PAGE BUILDER ERROR :: Builder Type Not Created!'	
		    },
		    this_id: {
		    	required: 'PAGE BUILDER ERROR'
		    }
		},
		errorPlacement: function(error, element) {
		    //element.parent('.form-group').addClass('has-error');
		    if (element.attr("data-error-container")) { 
		      error.appendTo(element.attr("data-error-container"));
		    } else if(element.hasClass('select2')) {
		      //error.insertAfter(element.parent('div'));
		      element.parent('div').append(error);
		    } else {
		      error.insertAfter(element); 
		    }
		},
		success: function(label) {
		    //label.closest('.form-group').removeClass('has-error');
		},
		submitHandler : function(form) { 
		    $.ajax({
		      type: form.method,
		      url: form.action,
		      data: $(form).serialize(),
		      cache: false,
		      beforeSend: function() {
		        $('.pgmodal_actionBtn').attr('disabled', 'disabled');
		      },
		      success: function(rtnJson) {
		      	var obj = JSON.parse( rtnJson );
		      	var isSuccess = obj.success;
		      	var msg = obj.msg;
		      	var action_status = obj.action_status;
		      	var insert_id = obj.insert_id;
		      	var REUSE_thisID = obj.this_id;
		      	var builder_type = obj.builder_type;

		        if( isSuccess == 'success' ) {

		        	$('.pgmodal_actionBtn').removeAttr('disabled');	
		        	
		        	$('#pgb_reuse_modal').modal('hide');

		        	var html = '<div class="notice notice-info">';
		        			html += '<div class="row">';
		        				html += '<div class="col-md-8">'; 
		        					html += '<strong>' + msg + '</strong>';
		        				html += '</div>';
		        				html += '<div class="col-md-4 txtrit">';
		        					html += '<a href="javascript:void(0);" class="pgb_edt" id="' + REUSE_thisID + '" data="REUSE"><i class="fa fa-pencil base-green fa-2x" aria-hidden="true"></i></a>';
		        					html += '&nbsp;&nbsp;'
		        					html += '<a href="javascript:void(0);" class="pgb_del" id="' + REUSE_thisID + '" data="REUSE"><i class="fa fa-trash-o base-red fa-2x" aria-hidden="true"></i></a>';
		        				html += '</div>';
		        			html += '</div>';
		        		html += '</div>';

		        	if( action_status == 'insert' ) {
		        		$('#pgContentAppend').append('<div class="ar-order altTop '+ builder_type +'_holder_' + REUSE_thisID + '" id="' + REUSE_thisID +'">' + html + '</div>');
		        	}

		        	if( action_status == 'update' ) {
		        		$( '.'+ builder_type +'_holder_' + REUSE_thisID ).html( html );
		        	}
		        }
		        form.reset();
		      },
		      error: function(ajx_err) {
		        
		      }
		    });
		    //return false;
		}
	});
} );
</script>