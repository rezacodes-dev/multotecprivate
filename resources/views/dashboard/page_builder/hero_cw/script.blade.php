<script type="text/javascript">
$( function() {
	$('body').on('click', '.pgb_herostat_cw', function() {

		$('#HERO_SCW_this_id').val('0');
		$('#HERO_SCW_main_content').val('');
		$('select[name="device"]').val('3');

		$('#pgb_herostat_cw_modal').modal({
			backdrop: 'static',
      		keyboard: false
		});
	} );
	
	var pgb_herostat_cw_frm = $('#pgb_herostat_cw_frm');
	
	pgb_herostat_cw_frm.validate({
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
		      required: 'Please Enter Cointer Width Hero Statement.'
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
		    } else if(element.attr('id') == 'language_id') {
		      error.insertAfter(element.parent('div'));
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
		      	var HERO_SCW_thisID = obj.this_id;
		      	var builder_type = obj.builder_type;

		        if( isSuccess == 'success' ) {
		        	
		        	$('.pgmodal_actionBtn').removeAttr('disabled');	
		        	
		        	$('#pgb_herostat_cw_modal').modal('hide');
		        	
		        	var html = '<div class="notice notice-info">';
		        			html += '<div class="row">';
		        				html += '<div class="col-md-8">'; 
		        					html += '<strong>' + msg + '</strong>';
		        				html += '</div>';
		        				html += '<div class="col-md-4 txtrit">';
		        					html += '<a href="javascript:void(0);" class="pgb_edt" id="' + HERO_SCW_thisID + '" data="HERO_SCW"><i class="fa fa-pencil base-green fa-2x" aria-hidden="true"></i></a>';
		        					html += '&nbsp;&nbsp;'
		        					html += '<a href="javascript:void(0);" class="pgb_del" id="' + HERO_SCW_thisID + '" data="HERO_SCW"><i class="fa fa-trash-o base-red fa-2x" aria-hidden="true"></i></a>';
		        				html += '</div>';
		        			html += '</div>';
		        		html += '</div>';

		        	if( action_status == 'insert' ) {
		        		$('#pgContentAppend').append('<div class="ar-order altTop '+ builder_type +'_holder_' + HERO_SCW_thisID + '" id="' + HERO_SCW_thisID + '">' + html + '</div>');
		        	}

		        	if( action_status == 'update' ) {
		        		$( '.'+ builder_type +'_holder_' + HERO_SCW_thisID ).html( html );
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