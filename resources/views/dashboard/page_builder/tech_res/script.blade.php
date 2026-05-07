<script type="text/javascript">
$( function() {
  $('body').on('click', '.pgb_techres_btn', function() {

    $('#TECHRES_BUTT_this_id').val('0');
    $('select[name="device"]').val('3');

    $('#tecRes_Modal').modal({
      backdrop: 'static',
      keyboard: false
    });
  } );
  
  var pgb_TkRES_frm = $('#tecRes_frm');
  
  pgb_TkRES_frm.validate({
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
          required: 'Please Select Category.'
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
            var TECHRES_BUTT_thisID = obj.this_id;
            var builder_type = obj.builder_type;

            if( isSuccess == 'success' ) {
              
              $('.pgmodal_actionBtn').removeAttr('disabled'); 
              
              $('#brochureModal').modal('hide');
              
              var html = '<div class="notice notice-info">';
                  html += '<div class="row">';
                    html += '<div class="col-md-8">'; 
                      html += '<strong>' + msg + '</strong>';
                    html += '</div>';
                    html += '<div class="col-md-4 txtrit">';
                      html += '<a href="javascript:void(0);" class="pgb_edt" id="' + TECHRES_BUTT_thisID + '" data="TECHRES_BUTT"><i class="fa fa-pencil base-green fa-2x" aria-hidden="true"></i></a>';
                      html += '&nbsp;&nbsp;'
                      html += '<a href="javascript:void(0);" class="pgb_del" id="' + TECHRES_BUTT_thisID + '" data="TECHRES_BUTT"><i class="fa fa-trash-o base-red fa-2x" aria-hidden="true"></i></a>';
                    html += '</div>';
                  html += '</div>';
                html += '</div>';

              if( action_status == 'insert' ) {
                $('#pgContentAppend').append('<div class="ar-order altTop '+ builder_type +'_holder_' + TECHRES_BUTT_thisID +'" id="' + TECHRES_BUTT_thisID + '">' + html + '</div>');
              }

              if( action_status == 'update' ) {
                $('.'+ builder_type +'_holder_' + TECHRES_BUTT_thisID).html( html );
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

$( function() {
  $.get("{{ route('ajxMediaLdFlCats') }}", function(data, status) {
    if( status == 'success' ) {
      var datArr = JSON.parse(data);
      var datArrLen = datArr.length;
      if( datArrLen > 0 ) {
        var optHTML = '<option value="">SELECT CATEGORY</option>';
        for( var i = 0; i < datArrLen; i++ ) {
          optHTML += '<option value="'+ datArr[i].slug +'">'+ datArr[i].name +'</option>';
        }
        $('#file_category').html( optHTML );
      }
    }
  });
  $('body').on('change', '#file_category', function() {
    $.ajax({
      type : "POST",
      url : "{{ route('ajxMediaLdFlSCatsSlug') }}",
      data : {
        "slug" : $(this).val(),
        "_token" : "{{ csrf_token() }}"
      },
      beforeSend: function() {
        $('#file_category').attr('disabled', 'disabled');
        $('#file_subcategory').attr('disabled', 'disabled');
      },
      success: function(scatJson) {
        $('#file_category').removeAttr('disabled', 'disabled');
        $('#file_subcategory').removeAttr('disabled', 'disabled');
        var datArr = JSON.parse(scatJson);
        var datArrLen = datArr.length;
        if( datArrLen > 0 ) {
          var optHTML = '<option value="">SELECT SUBCATEGORY</option>';
          for( var i = 0; i < datArrLen; i++ ) {
            optHTML += '<option value="'+ datArr[i].slug +'">'+ datArr[i].name +'</option>';
          }
          $('#file_subcategory').html( optHTML );
        } else {
          $('#file_subcategory').html( '<option value="">SELECT SUBCATEGORY</option>' );
        }
      }
    });
  } );
} );
</script>