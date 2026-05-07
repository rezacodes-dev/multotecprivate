<script type="text/javascript">
$( function() {

	var editor_pgb_ext_cont_edt = CKEDITOR.replace( 'pgb_ext_cont_edt', {
	  customConfig: "{{ asset('public/assets/ckeditor/maxi_config.js') }}",
	} );


	$('body').on('click', '.pgb_ext_cont', function() {

		$('#EXTRA_CONT_this_id').val('0');
		$('select[name="device"]').val('3');
		CKEDITOR.instances['pgb_ext_cont_edt'].setData('');

		$('#pgb_ext_cont_modal').modal({
			backdrop: 'static',
      		keyboard: false
		});
	} );
	
	var pgb_ext_cont_frm = $('#pgb_ext_cont_frm');

	pgb_ext_cont_frm.on('submit', function() {
	  CKEDITOR.instances.pgb_ext_cont_edt.updateElement();
	});

	pgb_ext_cont_frm.validate({
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
		      required: 'Please Enter SEO Contents.'
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
		      	var EXTRA_CONT_thisID = obj.this_id;
		      	var builder_type = obj.builder_type;

		        if( isSuccess == 'success' ) {
		        	
		        	$('.pgmodal_actionBtn').removeAttr('disabled');	
		        	
		        	$('#pgb_ext_cont_modal').modal('hide');
		        	
		        	var html = '<div class="notice notice-info">';
		        			html += '<div class="row">';
		        				html += '<div class="col-md-8">';
		        					html += '<strong>' + msg + '</strong>';
		        				html += '</div>';
		        				html += '<div class="col-md-4 txtrit">';
		        					html += '<a href="javascript:void(0);" class="pgb_edt" id="' + EXTRA_CONT_thisID + '" data="EXTRA_CONT"><i class="fa fa-pencil base-green fa-2x" aria-hidden="true"></i></a>';
		        					html += '&nbsp;&nbsp;'
		        					html += '<a href="javascript:void(0);" class="pgb_del" id="' + EXTRA_CONT_thisID + '" data="EXTRA_CONT"><i class="fa fa-trash-o base-red fa-2x" aria-hidden="true"></i></a>';
		        				html += '</div>';
		        			html += '</div>';
		        		html += '</div>';

		        	if( action_status == 'insert' ) {
		        		$('#pgContentAppend').append('<div class="ar-order altTop '+ builder_type +'_holder_' + EXTRA_CONT_thisID + '" id="' + EXTRA_CONT_thisID + '">' + html + '</div>');
		        	}

		        	if( action_status == 'update' ) {
		        		$( '.'+ builder_type +'_holder_' + EXTRA_CONT_thisID ).html( html );
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