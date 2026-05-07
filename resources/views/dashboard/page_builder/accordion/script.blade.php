<script type="text/javascript">
$( function() {

  $('.accordion').each( function() { 
    CKEDITOR.replace( $(this).attr('id'), {
      customConfig: "{{ asset('public/assets/ckeditor/accr_config.js') }}",
    } );
  } );
  $('body').on('click', '.pgb_accordion', function() {

    var clMore = 1;

    $('select[name="device"]').val('3');
    $('#addAccordion').val('Add Accordion');
    $('#ACCORDION_this_id').val('0');
    $('#accr0_heading').val('');
    CKEDITOR.instances[ 'accr0' ].setData( '' );
    $('#ACCORDION_more').html('');

    $('#accordionModal').modal({
      backdrop: 'static',
      keyboard: false
    });

  } );

  var clMore = 1;
  $('body').on('click', '#ACCORDION_more_btn', function() {
    var clHtml = '<div class="row" id="clDiv_' + clMore + '">';
          clHtml += '<div class="col-md-10">';
            clHtml += '<div class="form-group">';
              clHtml += '<label>Accordion Heading</label><input type="text" name="accordion_heading[]" id="accordion_heading" class="form-control" placeholder="accordion heading" required="required">';
            clHtml += '</div>';
            clHtml += '<div class="form-group">';
              clHtml += '<label>Accordion Body Content</label><textarea name="accordion_body_content[]" id="accr' + clMore + '" class="form-control accordion" placeholder="accordion body content" required="required"></textarea>';
            clHtml += '</div>';
          clHtml += '</div>';
          clHtml += '<div class="col-md-2">';
            clHtml += '<div class="form-group">';
              clHtml += '<a href="javascript:void(0)" class="rmcl" id="' + clMore + '">[x]</a>';
            clHtml += '</div>';
          clHtml += '</div>';
        clHtml += '</div>';

    $('#ACCORDION_more').append( clHtml );
    
    CKEDITOR.replace( 'accr' + clMore, {
      customConfig: "{{ asset('public/assets/ckeditor/accr_config.js') }}",
    } );

    clMore++;

  } );

  $('body').on('click', '.rmcl', function() {
    if( $(this).attr('id') != '' ) {
      $('#clDiv_' + $(this).attr('id') ).remove();
    }
  } );

  var accrFrm = $('#accordionfrmxx');

  accrFrm.on('submit', function() {
    $('.accordion').each( function() { 
      var accID = $(this).attr('id');
      CKEDITOR.instances[ accID ].updateElement();
    } );
  });

  accrFrm.validate({
      errorElement: 'span',
      errorClass : 'roy-vali-error',
      ignore: [],
    normalizer: function( value ) {
    return $.trim( value );
    },
      rules: {
        "accordion_heading[]": {
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
            var ACCORDION_thisID = obj.this_id;
            var builder_type = obj.builder_type;

            if( isSuccess == 'success' ) {

              $('.ckblinks').removeAttr('checked'); 
              
              $('#accordionModal').modal('hide');

              var html = '<div class="notice notice-info">';
                  html += '<div class="row">';
                    html += '<div class="col-md-8">'; 
                      html += '<strong>' + msg + '</strong>';
                    html += '</div>';
                    html += '<div class="col-md-4 txtrit">';
                      html += '<a href="javascript:void(0);" class="pgb_edt" id="' + ACCORDION_thisID + '" data="' + builder_type + '"><i class="fa fa-pencil base-green fa-2x" aria-hidden="true"></i></a>';
                      html += '&nbsp;&nbsp;'
                      html += '<a href="javascript:void(0);" class="pgb_del" id="' + ACCORDION_thisID + '" data="' + builder_type + '"><i class="fa fa-trash-o base-red fa-2x" aria-hidden="true"></i></a>';
                    html += '</div>';
                  html += '</div>';
                html += '</div>';

              if( action_status == 'insert' ) {
                $('#pgContentAppend').append('<div class="ar-order altTop ' + builder_type + '_holder_' + ACCORDION_thisID + '" id="' + ACCORDION_thisID +'">' + html + '</div>');
              }

              if( action_status == 'update' ) {
                $( '.'+ builder_type +'_holder_' + ACCORDION_thisID ).html( html );
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