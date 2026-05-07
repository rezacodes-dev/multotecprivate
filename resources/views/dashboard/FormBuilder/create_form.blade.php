@extends('dashboard.layouts.app')

@push('page_css')
<link rel="stylesheet" href="{{ asset('public/assets/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
<style type="text/css">
.cpv1, .cpv2 {
  border: 1px solid #d2d6de;
}
</style>
@endpush


@section('content_header')
<section class="content-header">
  <h1>
    @if(isset($form_details))
    Edit Form
    @else
    Add Form
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('frms') }}">All Forms</a></li>
    @if(isset($form_details))
    <li class="active">Edit Form</li>
    @else
    <li class="active">Add Form</li>
    @endif
  </ol>
</section>
@endsection

@section('content')
<section class="content">

  @if(Session::has('msg'))
  <div class="ar-hide @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}</div>
  @endif

  <div class="row">
    <div class="col-md-6">
      <a href="{{ route('frms') }}" class="btn btn-primary"> All Forms</a>
      @if( isset($form_details) && !empty($form_details) )
      <a href="{{ route('edt_frm_flds', array('form_id' => $form_details->frm_auto_id) ) }}" class="btn btn-success">
      Show Form Fields</a>
      @endif
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">@if(isset($form_details)) Edit Form @else Add Form @endif</h3>

          <div class="box-tools pull-right">
            @if( isset($form_details) && !empty($form_details) )
            <a href="{{ route('edt_frm_flds', array('form_id' => $form_details->frm_auto_id) ) }}" class="btn btn-success">
            Show Form Fields</a>
            @endif
          </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <form name="frm" action="@if( isset($form_details) && !empty($form_details) ){{ route( 'edt_frm_sve', array('form_id' => $form_details->frm_auto_id) ) }}@else{{ route('sv_frm') }}@endif" id="frm_frmx" method="post">
              {{ csrf_field() }}
                <div class="form-group">
                  <label>Form Category : <em>*</em></label>
                  <select name="category_id" class="form-control">
                    <option value="">-Select Form Category-</option>
                    @if( isset($cats) )
                      @foreach( $cats as $c )
                      <option value="{{ $c->id }}" @if( isset($form_details) && !empty($form_details) && $form_details->category_id == $c->id) selected="selected" @endif>{{ ucfirst($c->category_name) }}</option>
                      @endforeach
                    @endif
                  </select>
                </div>
                <div class="form-group">
                  <label>Form heading : <em>*</em></label>
                  <input type="text" name="frm_heading" class="form-control" placeholder="Form heading" value="@if( isset($form_details) && !empty($form_details) ){{ $form_details->frm_heading }}@endif">
                </div>
                <div class="form-group">
                  <label>Form name : <em>*</em></label>
                  <a href="javascript:void(0);" data-toggle="tooltip" data-placement="right" title="Don't use comma and space"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                  <input type="text" name="frm_name" class="form-control" placeholder="Form name" value="@if( isset($form_details) && !empty($form_details) ){{ $form_details->frm_name }}@endif">
                </div>
                <div class="form-group">
                  <label>Form css class : </label> 
                  <a href="javascript:void(0);" data-toggle="tooltip" data-placement="right" title="For multiple css class use comma. ex: class-1, class-2, class-3"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                  <input type="text" name="frm_css_class" class="form-control" placeholder="Form css class" value="@if( isset($form_details) && !empty($form_details) ){{ $form_details->frm_css_class }}@endif">
                </div>
                <div class="form-group">
                  <label>Form css id : </label>
                  <a href="javascript:void(0);" data-toggle="tooltip" data-placement="right" title="Don't use comma and space"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                  <input type="text" name="frm_css_id" class="form-control" placeholder="Form css id" value="@if( isset($form_details) && !empty($form_details) ){{ $form_details->frm_css_id }}@endif">
                </div>
                <div class="form-group">
                  <label>Form details : </label>
                  <a href="javascript:void(0);" data-toggle="tooltip" data-placement="right" title="Any note"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                  <textarea name="frm_details" class="form-control" rows="4">@if( isset($form_details) && !empty($form_details) ){{ $form_details->frm_details }}@endif</textarea>
                </div>
                <div class="form-group">
                  <label>Thankyou page url : <em>*</em></label>
                  <input type="url" name="thankyou_url" class="form-control" placeholder="Thankyou page url" value="@if( isset($form_details) && !empty($form_details) ){{ $form_details->thankyou_url }}@endif">
                </div>
                <div class="form-group">
                  <label>Admin received email ?</label>
                  <a href="javascript:void(0);" data-toggle="tooltip" data-placement="right" title="Form data receive by email"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                  <select name="is_email_receive" class="form-control isEmRev">
                    <option value="0" @if( isset($form_details) && !empty($form_details) && $form_details->is_email_receive == '0') selected="selected" @endif>No, don't want to receive any mail</option>
                    <option value="1" @if( isset($form_details) && !empty($form_details) && $form_details->is_email_receive == '1') selected="selected" @endif>Yes, want to receive mail with form data</option>
                  </select>
                </div>
                @php
                if( isset($form_details) && !empty($form_details) && $form_details->is_email_receive == '1' && $form_details->receive_emails != '' ) {

                  $emARR = unserialize($form_details->receive_emails);
                  //print_r($emARR);
                  $emCount = count($emARR);
                  //echo $emCount;
                }
                @endphp
                <div class="form-group opt-box">
                  <label>Receive mail-id : <em>*</em></label>
                  <input type="email" name="receive_emails[]" id="emx_1" class="form-control" placeholder="Enter email-id" value="@if( isset($emARR) && !empty($emARR) && $emCount > 0 ){{ $emARR[0] }}@endif">
                </div>
                <div id="add_more_mail_id_div" class="opt-box">
                  @php
                  if( isset($emARR) && !empty($emARR) && $emCount > 1 ) {
                    $loopID = 2;
                    for( $i = 1; $i < $emCount; $i++ ) {
                    @endphp
                    <div class="row" id="emBox_{{ $loopID }}">
                      <div class="col-md-11">
                        <div class="form-group">
                          <label>Another email-id : <em>*</em></label>
                          <input type="email" id="emx_{{ $loopID }}" name="receive_emails[{{ $loopID }}]" class="form-control required" placeholder="Enter email-id" value="@php echo $emARR[$i]; @endphp" />
                        </div>
                      </div>
                      <div class="col-md-1">
                        <div class="emrm_box">
                          <a href="javascript:void(0);" class="emrm_btn" id="{{ $loopID }}"><i class="fa fa-times fared" aria-hidden="true"></i></a>
                        </div>
                      </div>
                    </div>
                    @php
                    $loopID++;
                    }
                  }
                  @endphp
                </div>
                <div class="form-group opt-box">
                  <button type="button" class="btn btn-primary btn-sm" id="add_more_mail_id_btn">
                    <i class="fa fa-plus" aria-hidden="true"></i> Add more</button>
                </div>
                @php $default_value = "Submit"; $default_color = "#eaeaea"; $default_txt_color = "#000000"; @endphp
                <div class="form-group">
                  <label>Form submit button text : <em>*</em></label>
                  <input type="text" name="frm_btn_name" class="form-control" placeholder="Form Button Text" value="@if( isset($btn_details) && !empty($btn_details) ){{ $btn_details->default_value }}@else{{ $default_value }}@endif">
                </div>
        
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Form Background Color : <em>*</em></label>
                      <div class="input-group">
                        <div class="input-group-addon cpv1" @if( isset($form_details) ) style="background-color: {{ $form_details->frm_bg_color }};" @else style="background-color: {{ $default_color }};" @endif>
                        </div>
                      <input type="text" name="frm_bg_color" class="form-control colorpicker" placeholder="Form Background Color" value="@if( isset($form_details) && !empty($form_details) ){{ $form_details->frm_bg_color }}@endif">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Form Text Color : <em>*</em></label>
                      <div class="input-group">
                        <div class="input-group-addon cpv2" @if( isset($form_details) ) style="background-color: {{ $form_details->frm_txt_color }};" @else style="background-color: {{ $default_txt_color }};" @endif>
                          
                        </div>
                      <input type="text" name="frm_txt_color" class="form-control colorpicker2" placeholder="Form Text Color" value="@if( isset($form_details) && !empty($form_details) ){{ $form_details->frm_txt_color }}@endif">
                      </div>
                    </div>
                  </div>
                </div>


                <div class="form-group">
                  <label>Is captcha required ?</label> <input type="checkbox" name="is_captcha" value="1" @if( isset($form_details) && !empty($form_details) && $form_details->is_captcha == '1') checked="checked" @endif> Yes 
                </div>
                <div class="form-group">
                  <label>Status :</label>
                  <select name="status" class="form-control">
                    <option value="1" @if( isset($form_details) && !empty($form_details) && $form_details->status == '1') selected="selected" @endif>Active</option>
                    <option value="0" @if( isset($form_details) && !empty($form_details) && $form_details->status == '0') selected="selected" @endif>Inactive</option>
                  </select>
                </div>
                <div class="form-group">
                  @if( isset($form_details) && !empty($form_details) )
                  <input type="submit" name="edit" class="btn btn-primary" value="SAVE CHANGES">
                  <a href="{{ route( 'edt_frm', array('form_id' => $form_details->frm_auto_id) ) }}" class="btn btn-danger">CANCEL CHANGES</a>
                  @endif
                  @if( !isset($form_details) )
                  <input type="submit" name="ok" class="btn btn-primary" value="SAVE FORM">
                  <input type="button" id="clrForm" class="btn btn-danger" value="CLEAR FORM">
                  @endif
                </div>
              </form>
            </div>
            <div class="col-md-6">
             
            </div>
          </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->
    </div>
  </div>

</section>
@endsection

@push('page_js')
<script src="{{ asset('public/assets/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
<script type="text/javascript">

$( function() {

  $('.colorpicker').colorpicker({
    <?php if( ! isset($form_details) ) { ?>
    color: "#eaeaea",
    <?php } ?>
    format: "hex"
  }).on('changeColor', function (e) {
    $('.cpv1').css('background-color', e.color.toHex());
  });

  $('.colorpicker2').colorpicker({
    <?php if( ! isset($form_details) ) { ?>
    color: "#000000",
    <?php } ?>
    format: "hex"
  }).on('changeColor', function (e) {
    $('.cpv2').css('background-color', e.color.toHex());
  });
  
  <?php if( isset($form_details) && !empty($form_details) && $form_details->is_email_receive == '1' ) { ?>
  
  $('.opt-box').show();
  
  <?php } else { ?>
  
  $('.opt-box').hide();
  
  <?php } ?>
  
  $('.isEmRev').on('change', function(){
    if($(this).val() == '1') {
      $('.opt-box').slideDown();
    } else {
      $('#add_more_mail_id_div').html('');
      $('.opt-box').hide();
    }
  });
  
  <?php if( isset($loopID) ) { ?>
  
  var add_more_email = "{{ $loopID }}" ;
  
  <?php } else { ?> 
  
  var add_more_email = 2;
  
  <?php } ?>
  
  var add_more_email = parseInt(add_more_email);
  
  $('#add_more_mail_id_btn').on('click', function(e){
    var add_more_email_html = '';
      add_more_email_html += '<div class="row" id="emBox_'+ add_more_email +'"><div class="col-md-11"><div class="form-group">';
        add_more_email_html += '<label>Another email-id : <em>*</em></label>';
        add_more_email_html += '<input type="email" id="emx_'+ add_more_email +'" name="receive_emails['+ add_more_email +']" class="form-control required" placeholder="Enter email-id" />'; 
      add_more_email_html += '</div></div>';
      add_more_email_html += '<div class="col-md-1"><div class="emrm_box">';
      add_more_email_html += '<a href="javascript:void(0);" class="emrm_btn" id="'+ add_more_email +'"><i class="fa fa-times fared" aria-hidden="true"></i></a>';
      add_more_email_html += '</div></div></div>';
    $('#add_more_mail_id_div').append(add_more_email_html);
    add_more_email++;
  }); 
  $('body').on('click', '.emrm_btn', function() {
    $('#emBox_'+$(this).attr('id')).remove();
  });
  $('#clrForm').on('click', function() {
    $('#frm_frmx').find('input[type="text"]').val('');
    $('#frm_frmx').find('input[type="email"]').val('');
    $('#frm_frmx').find('textarea').val('');
    $('#frm_frmx').find('select').prop("selectedIndex", 0);
    $('#add_more_mail_id_div').html('');
    $('.opt-box').hide();
    $('#frm_frmx').validate().resetForm();
  });

});


$("#frm_frmx").validate({
  errorElement: 'span',
  errorClass : 'ar-vali-error',
  rules: {

    frm_heading: {
      required: true
    },
    category_id: {
      required: true
    },
    frm_name: {
      required: true
    },
    "receive_emails[]": {
      required: true,
      email: true
    },
    frm_btn_name: {
      required: true
    },
    frm_btn_color: {
      required: true
    },
    frm_bg_color: {
      required: true
    },
    frm_btntxt_color: {
      required: true
    },
    frm_txt_color: {
      required: true
    },
    thankyou_url: {
      required: true,
      url: true
    }

  },
  messages: {

    frm_heading:{
      required: 'Please enter form heading.',
    },
    category_id: {
      required: 'Please Select Any Category'
    },
    frm_name: {
      required: 'Please enter form name.',
    },
    "receive_emails[]": {
      required: 'Please enter email-id.',
      email : 'Please enter valid email-id.'
    },
    frm_btn_name: {
      required: 'Please enter submit button text.',
    },
    frm_btn_color: {
      required: 'Please enter button color code.'
    },
    frm_bg_color: {
      required: 'Please enter form color code.'
    },
    frm_btntxt_color: {
      required: 'Please enter button text color code.'
    },
    frm_txt_color: {
      required: 'Please enter form text color code.'
    },
    thankyou_url: {
      required: 'Please enter thankyou page url.'
    }

  }
  
});

</script>
@endpush