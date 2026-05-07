<script type="text/javascript">
$( function() {

  $('body').on('click', '.pgb_prdbox', function() {

    $('.xSRC').val('');
    $('#boxlinkType').removeAttr('disabled');
    $('#prdbox_link_heading').val('');
    $('#prodbox_linkboxTAB tbody').html('');
    $('#addPrdBoxSeleLinks').attr('disabled', 'disabled');
    $('select[name="device"]').val('3');

    $('#prdboxModal').modal({
      backdrop: 'static',
      keyboard: false
    });

  } );

  $('body').on('change', '#boxlinkType', function() { 
    if( $.trim($(this).val()) != '' ) {
      var lkType = $.trim($(this).val());
      $.ajax({
        type: "POST",
        url: "{{ route('pgbgetLnks') }}",
        data: {
          "link_type" : lkType,
          "_token" : "{{ csrf_token() }}"
        },
        beforeSend: function() {
          $('#prodbox_linkboxTAB tbody').html('<tr><td colspan="2">Please Wait...</td></tr>');
        },
        success: function(data) {
          $('#prodbox_linkboxTAB tbody').html(data.html);
          $('#PrdBoxlinks_builder_type').val(lkType);
        }
      });
    }
  } );

  $('body').on('change', '#column_key', function() {
    var _getCID = $(this).val();
    $.ajax({
      type : "POST",
      url : "{{ route('pgbgetPboxReu') }}",
      data : {
        "_token" : "{{ csrf_token() }}",
        "cid" : _getCID
      },
      beforeSend : function() {
        $('#column_key').attr('disabled', 'disabled');
      },
      success : function(data) {
        $('#pbox_reu_id').html(data.html);
        $('#column_key').removeAttr('disabled');
        $('#pbox_reu_id').removeAttr('disabled');
      } 
    });
  } );

  $('body').keyup('#prdbox_link_heading', function() { 
    $('#addPrdBoxSeleLinks').removeAttr('disabled');
  } );

  $('#prdbox_frm').validate({
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
        this_id: {
          required: true
        },
        link_type: {
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
        this_id: {
          required: 'PAGE BUILDER ERROR'
        },
        link_type: {
          required: 'Select Any Type of Links.'
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
            var LINKS_thisID = obj.this_id;
            var builder_type = obj.builder_type;

            if( isSuccess == 'success' ) {

              $('.ckblinks').removeAttr('checked'); 
              
              $('#prdboxModal').modal('hide');

              var html = '<div class="notice notice-info">';
                  html += '<div class="row">';
                    html += '<div class="col-md-8">'; 
                      html += '<strong>' + msg + '</strong>';
                    html += '</div>';
                    html += '<div class="col-md-4 txtrit">';
                      html += '<a href="javascript:void(0);" class="pgb_edt" id="' + LINKS_thisID + '" data="' + builder_type + '"><i class="fa fa-pencil base-green fa-2x" aria-hidden="true"></i></a>';
                      html += '&nbsp;&nbsp;'
                      html += '<a href="javascript:void(0);" class="pgb_del" id="' + LINKS_thisID + '" data="' + builder_type + '"><i class="fa fa-trash-o base-red fa-2x" aria-hidden="true"></i></a>';
                    html += '</div>';
                  html += '</div>';
                html += '</div>';

              if( action_status == 'insert' ) {
                $('#pgContentAppend').append('<div class="ar-order altTop ' + builder_type + '_holder_' + LINKS_thisID + '" id="' + LINKS_thisID +'">' + html + '</div>');
              }

              if( action_status == 'update' ) {
                $( '.'+ builder_type +'_holder_' + LINKS_thisID ).html( html );
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
