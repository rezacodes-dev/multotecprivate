<script type="text/javascript">
$( function() {

  $('body').on('click', '.pgb_links_custom', function() {

    var clMore = 0;
    $('#CUSTOMLINKS_more').html('');
    $('#custom_link_heading').val('');
    $('#CUSTOMLINKS_this_id').val('0');
    $('select[name="device"]').val('3');
    $('#addSeleCustomLinks').val('Add Custom Links');
    $('#fclText').val('');
    $('#fclSlug').val('');

    $('#customlinksModal').modal({
      backdrop: 'static',
      keyboard: false
    });

  } );

  var clMore = 0;
  $('body').on('click', '#CUSTOMLINKS_more_btn', function() {
    var clHtml = '<div class="row" id="clDiv_' + clMore + '">';
        clHtml += '<div class="col-md-5"><div class="form-group"><input type="text" name="custom_link_text[]" class="form-control custom_link_text" placeholder="Link Text" required="required"></div></div>';
        clHtml += '<div class="col-md-6"><div class="form-group"><input type="url" name="custom_link_slug[]" class="form-control custom_link_slug" placeholder="Link" required="required"></div></div>';
        clHtml += '<div class="col-md-1"><div class="form-group"><a href="javascript:void(0);" class="rmcl" id="'+ clMore +'">[x]</a></div></div>';
        clHtml += '</div>';

    $('#CUSTOMLINKS_more').append( clHtml );
    clMore++;
  } );

  $('body').on('click', '.rmcl', function() {
    if( $(this).attr('id') != '' ) {
      $('#clDiv_' + $(this).attr('id') ).remove();
    }
  } );

  $('#clfrmxx').validate({
      errorElement: 'span',
      errorClass : 'roy-vali-error',
      ignore: [],
    normalizer: function( value ) {
    return $.trim( value );
    },
      rules: {
        main_title: {
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
        main_title: {
          required: 'Please Enter Link Heading'
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
            $('.addSeleLinks').attr('disabled', 'disabled');
          },
          success: function(rtnJson) {
            var obj = JSON.parse( rtnJson );
            var isSuccess = obj.success;
            var msg = obj.msg;
            var action_status = obj.action_status;
            var insert_id = obj.insert_id;
            var CUSTOMLINKS_thisID = obj.this_id;
            var builder_type = obj.builder_type;

            if( isSuccess == 'success' ) {

              $('.ckblinks').removeAttr('checked'); 
              
              $('#customlinksModal').modal('hide');

              var html = '<div class="notice notice-info">';
                  html += '<div class="row">';
                    html += '<div class="col-md-8">'; 
                      html += '<strong>' + msg + '</strong>';
                    html += '</div>';
                    html += '<div class="col-md-4 txtrit">';
                      html += '<a href="javascript:void(0);" class="pgb_edt" id="' + CUSTOMLINKS_thisID + '" data="' + builder_type + '"><i class="fa fa-pencil base-green fa-2x" aria-hidden="true"></i></a>';
                      html += '&nbsp;&nbsp;'
                      html += '<a href="javascript:void(0);" class="pgb_del" id="' + CUSTOMLINKS_thisID + '" data="' + builder_type + '"><i class="fa fa-trash-o base-red fa-2x" aria-hidden="true"></i></a>';
                    html += '</div>';
                  html += '</div>';
                html += '</div>';

              if( action_status == 'insert' ) {
                $('#pgContentAppend').append('<div class="ar-order altTop ' + builder_type + '_holder_' + CUSTOMLINKS_thisID + '" id="' + CUSTOMLINKS_thisID +'">' + html + '</div>');
              }

              if( action_status == 'update' ) {
                $( '.'+ builder_type +'_holder_' + CUSTOMLINKS_thisID ).html( html );
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