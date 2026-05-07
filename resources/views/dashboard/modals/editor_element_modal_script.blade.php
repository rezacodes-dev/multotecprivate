<!------------------------------------------------- Modal Elements ---------------------------------------------->
<script type="text/javascript">
$( function() {
  $('body').on('click', '.addEleBtn', function() {
    var getCkEdtID = $.trim( $(this).attr('data') );
    var getCkEdtTitle = $.trim( $(this).attr('title') );
    $('#elementModal').modal({
      backdrop: 'static',
      keyboard: false
    });
    $('.addOnEdtName').html( ' | ' + getCkEdtTitle );
    $('.addCkEdtBtnEle').attr( 'id', getCkEdtID );
  });
  $('body').on('click', '.arEleClose', function() {
    $('#elementModal').modal('hide');
  });
  $.ajax({
    type : 'POST',
    url : "{{ route('ajxEleScodes') }}",
    data : {
      '_token' : "{{ csrf_token() }}"
    },
    beforeSend : function() {

    },
    success : function(viewHtmlData) {
      if( viewHtmlData.status === 'ok' ) {
        $('#reusableContentDropLoad').html( viewHtmlData.html_rc );
        $('#formBuilderDropLoad').html( viewHtmlData.html_frm );
        $('#imageGalleriesDropLoad').html( viewHtmlData.html_gal );
      }
    }
  });
  $('body').on('change', '.eleModalDD', function() {
    if( $(this).val() != '0' && $(this).val() != '' ) {
      $('#eleScodeHidden').val( $(this).val() );
      $('.addCkEdtBtnEle').removeAttr('disabled');
    } else {
      $('#eleScodeHidden').val('');
      $('.addCkEdtBtnEle').attr('disabled', 'disabled');
    }
  });
  $('body').on('click', '.eleModalCkb', function() {
    if( $(this).is(':checked') ) {
      $('#eleScodeHidden').val( $(this).val() );
      $('.addCkEdtBtnEle').removeAttr('disabled');
    } else {
      $('#eleScodeHidden').val('');
      $('.addCkEdtBtnEle').attr('disabled', 'disabled'); 
    }
  });
  $('#eleMentTabs li a').on('shown.bs.tab', function() {
    $('#eleScodeHidden').val('');
    $('.addCkEdtBtnEle').attr('disabled', 'disabled');
    $('.eleModalDD').val('0');
    $('.eleModalCkb').prop('checked', false);
  });
  $('body').on('click', '.addCkEdtBtnEle', function() {
    var getCkEdtID = $(this).attr('id');
    var scode = $('#eleScodeHidden').val();
    CKEDITOR.instances[ getCkEdtID ].insertHtml('<div>' + scode +'</div>');
    $('#elementModal').modal('hide');
  });
});
</script>