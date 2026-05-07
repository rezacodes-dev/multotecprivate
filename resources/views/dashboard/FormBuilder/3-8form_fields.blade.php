@extends('dashboard.layouts.app')

@push('page_css')
<link rel="stylesheet" href="{{ asset('public/assets/jquery_ui/jquery-ui.css') }}">
<style type="text/css">
.fd_box {
  border: 1px dashed #e6e6e6;
  padding-top: 5px;
  padding-bottom: 5px;
  width: 99%;
}
.fd_box:hover {
  cursor: move;
}
.ui-state-highlight {
  border: 1px solid #1a75ff;
  -webkit-box-shadow: 0px 0px 10px #888;
  -moz-box-shadow: 0px 0px 10px #888;
  box-shadow: 0px 0px 10px #888;
}
.edBOX {
  margin-top: 5px;
}
.fld_edit {
  border: 1px solid #86b300;
}
.fld_del {
  border: 1px solid #ff3300;
  margin-left: 10px;
}
.custom-file-upload {
  background: #fff;
  border: 1px solid #dde1e4;
  display: inline-block;
  padding: 6px 12px;
  cursor: pointer;
  font-size: 16px;
  color: #8f8f8f;
  font-weight: 300;
  width: 100%;
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
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          @if( isset($form_details) && !empty($form_details) )
            <h3 class="box-title"> <i class="fa fa-file-text" aria-hidden="true"></i> <strong>{{ $form_details->frm_heading }}</strong></h3>
            <input type="hidden" id="set_frm_auto_id" value="{{ $form_details->frm_auto_id }}">
            <div class="box-tools pull-right">
              <a href="{{ route('frm_prv', array('form_id' => $form_details->frm_auto_id) ) }}"><i class="fa fa-eye"></i> Form Preview</a>
            </div>
          @endif
        </div>
        <div class="box-body">
          <div class="row" style="margin-top: 10px;">
            <div class="col-md-3 right-border">
              <h3><i class="fa fa-check-square-o" aria-hidden="true"></i> Add Form Fields</h3><hr/>
              <div class="row">
                <div class="col-md-12">
                  <label class="btn btn-default full-width tal modal_btn" id="TEXTFIELD"> 
                    <i class="fa fa-text-width" aria-hidden="true"></i> 
                    Text-Field 
                    <i class="fa fa-plus-square pull-right" aria-hidden="true"></i>
                  </label>
                </div>
              </div>
              <div class="row mtv">
                <div class="col-md-12">
                  <label class="btn btn-default full-width tal modal_btn" id="EMAILFIELD"> 
                    <i class="fa fa-envelope-o" aria-hidden="true"></i> 
                    Email-Field 
                    <i class="fa fa-plus-square pull-right" aria-hidden="true"></i>
                  </label>
                </div>
              </div>
              <div class="row mtv">
                <div class="col-md-12">
                  <label class="btn btn-default full-width tal modal_btn" id="PHONEFIELD"> 
                    <i class="fa fa-phone-square" aria-hidden="true"></i> 
                    Phone Number-Field 
                    <i class="fa fa-plus-square pull-right" aria-hidden="true"></i>
                  </label>
                </div>
              </div>
              <div class="row mtv">
                <div class="col-md-12">
                  <label class="btn btn-default full-width tal modal_btn" id="NUMBERFIELD"> 
                    <i class="fa fa-sort-numeric-asc" aria-hidden="true"></i> 
                    Number-Field 
                    <i class="fa fa-plus-square pull-right" aria-hidden="true"></i>
                  </label>
                </div>
              </div>
              <div class="row mtv">
                <div class="col-md-12">
                  <label class="btn btn-default full-width tal modal_btn" id="URLFIELD"> 
                    <i class="fa fa-globe" aria-hidden="true"></i> 
                    URL-Field 
                    <i class="fa fa-plus-square pull-right" aria-hidden="true"></i>
                  </label>
                </div>
              </div>
              <div class="row mtv">
                <div class="col-md-12">
                  <label class="btn btn-default full-width tal modal_btn" id="DATEFIELD"> 
                    <i class="fa fa-calendar" aria-hidden="true"></i> 
                    Date-Field 
                    <i class="fa fa-plus-square pull-right" aria-hidden="true"></i>
                  </label>
                </div>
              </div>
              <div class="row mtv">
                <div class="col-md-12">
                  <label class="btn btn-default full-width tal modal_btn" id="PARAFIELD"> 
                    <i class="fa fa-paragraph" aria-hidden="true"></i> 
                    Paragraph-Field 
                    <i class="fa fa-plus-square pull-right" aria-hidden="true"></i>
                  </label>
                </div>
              </div>
              <div class="row mtv">
                <div class="col-md-12">
                  <label class="btn btn-default full-width tal modal_btn" id="FILEFIELD"> 
                    <i class="fa fa-upload" aria-hidden="true"></i> 
                    Fileupload-Field 
                    <i class="fa fa-plus-square pull-right" aria-hidden="true"></i>
                  </label>
                </div>
              </div>
              <div class="row mtv">
                <div class="col-md-12">
                  <label class="btn btn-default full-width tal modal_btn" id="DROPDOWN"> 
                    <i class="fa fa-caret-square-o-down" aria-hidden="true"></i> 
                    Dropdown-Field 
                    <i class="fa fa-plus-square pull-right" aria-hidden="true"></i>
                  </label>
                </div>
              </div>
              <div class="row mtv">
                <div class="col-md-12">
                  <label class="btn btn-default full-width tal modal_btn" id="LISTBOX"> 
                    <i class="fa fa-list" aria-hidden="true"></i> 
                    Multi Select-Field 
                    <i class="fa fa-plus-square pull-right" aria-hidden="true"></i>
                  </label>
                </div>
              </div>
              <div class="row mtv">
                <div class="col-md-12">
                  <label class="btn btn-default full-width tal modal_btn" id="RADIOFIELD"> 
                    <i class="fa fa-filter" aria-hidden="true"></i> 
                    Radio Option-Field 
                    <i class="fa fa-plus-square pull-right" aria-hidden="true"></i>
                  </label>
                </div>
              </div>
              <div class="row mtv">
                <div class="col-md-12">
                  <label class="btn btn-default full-width tal modal_btn" id="CHECKFIELD"> 
                    <i class="fa fa-check-square-o" aria-hidden="true"></i> 
                    Checkbox-Field 
                    <i class="fa fa-plus-square pull-right" aria-hidden="true"></i>
                  </label>
                </div>
              </div>
            </div>
            <div class="col-md-9">
              <label id="order_status"><code>Using Drag & Drop, You Can Change The Order</code></label>
              <div id="fieldContainer">
                <!--div id="append_field"></div-->
                @if( isset($field_details) && !empty($field_details) && count($field_details) > 0 )
                  @foreach( $field_details as $fd )
                    {!! html_entity_decode( $fd->field_raw_html ) !!}
                  @endforeach
                @else
                <div class="dt-box">
                  <span>PLEASE ADD FIELDS TO FORM</span>
                </div>
                @endif
              </div>
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

<!-- Create Modal -->
<div class="modal fade" id="fdModal" role="dialog">
  <div class="modal-dialog">
  
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="modal_title"></h4>
      </div>
      <div class="modal-body">
        <form role="form" name="ajxFRM" action="{{ route('mng_flds') }}" id="ajax_frm" method="post">
        {{ csrf_field() }}
          <div id="modal_body"></div>
          <div class="row">
            <div class="col-md-6">
              <input type="submit" id="addFD" class="btn btn-primary" value="ADD FIELD">
              <input type="hidden" name="ACTION_TYPE" value="NEW_INSERT">
              <input type="hidden" name="FORM_ID" value="@if( isset($form_details) && !empty($form_details) ){{ $form_details->frm_auto_id }}@endif">
            </div>
            <div class="col-md-6">
              <label id="ajx_status"></label>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>
    
  </div>
</div>


<!-- Edit Modal -->
<div class="modal fade" id="fdEdModal" role="dialog">
  <div class="modal-dialog">
  
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="modal_title_ed"></h4>
      </div>
      <div class="modal-body">
        <form role="form" name="ajxFRM_ed" action="{{ route('mng_flds') }}" id="ajax_frm_ed" method="post">
        {{ csrf_field() }}
          <div id="modal_body_ed"></div>
          <div class="row">
            <div class="col-md-6">
              <input type="submit" id="addFD_ed" class="btn btn-primary" value="SAVE CHANGES">
              <input type="hidden" name="ACTION_TYPE" value="EDIT">
              <input type="hidden" name="FORM_ID" value="@if( isset($form_details) && !empty($form_details) ){{ $form_details->frm_auto_id }}@endif">
              <input type="hidden" name="row_id" id="edit_row_id">
            </div>
            <div class="col-md-6">
              <label id="ajx_status_ed"></label>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>
    
  </div>
</div>

</section>
@endsection

@push('page_js')
<script src="{{ asset('public/assets/jquery_ui/jquery-ui.js') }}"></script>
<script type="text/javascript">
var addM = 1;
$( function() {
  $(".modal_btn").on('click', function() {
    $('#ajx_status').html('');
    var field_type = $(this).attr('id');
    if( field_type == 'TEXTFIELD' ) {
      var fdHTML = '';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Field name : <em>*</em></label>';
                fdHTML += '<input type="text" name="field_name" class="form-control" placeholder="Enter Field name" required />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Display text : <em>*</em></label>';
                fdHTML += '<input type="text" name="display_text" class="form-control" placeholder="Enter display text" required />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Placeholder : </label>';
                fdHTML += '<input type="text" name="placeholder" class="form-control" placeholder="Enter placeholder" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Default value : </label>';
                fdHTML += '<input type="text" name="default_value" class="form-control" placeholder="Enter default value" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Css class name : </label>';
                fdHTML += '<input type="text" name="css_class" class="form-control" placeholder="For multiple use comma" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Css id : </label>';
                fdHTML += '<input type="text" name="css_id" class="form-control" placeholder="Enter css id" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-12">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Help text : </label>';
                fdHTML += '<input type="text" name="help_text" class="form-control" placeholder="Any help text" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Is required : </label>';
                fdHTML += ' <input type="radio" name="is_required" value="required"> YES';
                fdHTML += ' <input type="radio" name="is_required" value="" checked="checked" > NO';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Status : </label> ';
                fdHTML += ' <input type="radio" name="status" value="1" checked="checked"> Active';
                fdHTML += ' <input type="radio" name="status" value="0"> Inactive';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<input type="hidden" name="field_type" value="TEXTFIELD" />';

          var icon_class = $(this).children('i:first').attr('class');
          $('#modal_title').html('<i class="'+ icon_class +'"></i> TEXT FIELD');
    }

    if( field_type == 'EMAILFIELD' ) {
      var fdHTML = '';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Field name : <em>*</em></label>';
                fdHTML += '<input type="text" name="field_name" class="form-control" placeholder="Enter Field name" required />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Display text : <em>*</em></label>';
                fdHTML += '<input type="text" name="display_text" class="form-control" placeholder="Enter display text" required />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Placeholder : </label>';
                fdHTML += '<input type="text" name="placeholder" class="form-control" placeholder="Enter placeholder" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Default value : </label>';
                fdHTML += '<input type="text" name="default_value" class="form-control" placeholder="Enter default value" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Css class name : </label>';
                fdHTML += '<input type="text" name="css_class" class="form-control" placeholder="For multiple use comma" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Css id : </label>';
                fdHTML += '<input type="text" name="css_id" class="form-control" placeholder="Enter css id" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-12">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Help text : </label>';
                fdHTML += '<input type="text" name="help_text" class="form-control" placeholder="Any help text" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Is required : </label>';
                fdHTML += ' <input type="radio" name="is_required" value="required"> YES';
                fdHTML += ' <input type="radio" name="is_required" value="" checked="checked" > NO';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Status : </label> ';
                fdHTML += ' <input type="radio" name="status" value="1" checked="checked"> Active';
                fdHTML += ' <input type="radio" name="status" value="0"> Inactive';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<input type="hidden" name="field_type" value="EMAILFIELD" />';

          var icon_class = $(this).children('i:first').attr('class');
          $('#modal_title').html('<i class="'+ icon_class +'"></i> EMAIL FIELD');
    }


    if( field_type == 'NUMBERFIELD' ) {
      var fdHTML = '';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Field name : <em>*</em></label>';
                fdHTML += '<input type="text" name="field_name" class="form-control" placeholder="Enter Field name" required />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Display text : <em>*</em></label>';
                fdHTML += '<input type="text" name="display_text" class="form-control" placeholder="Enter display text" required />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Placeholder : </label>';
                fdHTML += '<input type="text" name="placeholder" class="form-control" placeholder="Enter placeholder" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Default value : </label>';
                fdHTML += '<input type="text" name="default_value" class="form-control" placeholder="Enter default value" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Css class name : </label>';
                fdHTML += '<input type="text" name="css_class" class="form-control" placeholder="For multiple use comma" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Css id : </label>';
                fdHTML += '<input type="text" name="css_id" class="form-control" placeholder="Enter css id" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Minimum value : </label>';
                fdHTML += '<input type="number" name="min_value" class="form-control onlyNumber" placeholder="Minimum value" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Maximum value : </label>';
                fdHTML += '<input type="number" name="max_value" class="form-control onlyNumber" placeholder="Maximum value" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-12">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Help text : </label>';
                fdHTML += '<input type="text" name="help_text" class="form-control" placeholder="Any help text" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Is required : </label>';
                fdHTML += ' <input type="radio" name="is_required" value="required"> YES';
                fdHTML += ' <input type="radio" name="is_required" value="" checked="checked" > NO';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Status : </label> ';
                fdHTML += ' <input type="radio" name="status" value="1" checked="checked"> Active';
                fdHTML += ' <input type="radio" name="status" value="0"> Inactive';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<input type="hidden" name="field_type" value="NUMBERFIELD" />';

          var icon_class = $(this).children('i:first').attr('class');
          $('#modal_title').html('<i class="'+ icon_class +'"></i> NUMBER FIELD');
    }


    if( field_type == 'PHONEFIELD' ) {
      var fdHTML = '';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Field name : <em>*</em></label>';
                fdHTML += '<input type="text" name="field_name" class="form-control" placeholder="Enter Field name" required />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Display text : <em>*</em></label>';
                fdHTML += '<input type="text" name="display_text" class="form-control" placeholder="Enter display text" required />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Placeholder : </label>';
                fdHTML += '<input type="text" name="placeholder" class="form-control" placeholder="Enter placeholder" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Default value : </label>';
                fdHTML += '<input type="text" name="default_value" class="form-control" placeholder="Enter default value" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Css class name : </label>';
                fdHTML += '<input type="text" name="css_class" class="form-control" placeholder="For multiple use comma" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Css id : </label>';
                fdHTML += '<input type="text" name="css_id" class="form-control" placeholder="Enter css id" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-12">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Help text : </label>';
                fdHTML += '<input type="text" name="help_text" class="form-control" placeholder="Any help text" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Is required : </label>';
                fdHTML += ' <input type="radio" name="is_required" value="required"> YES';
                fdHTML += ' <input type="radio" name="is_required" value="" checked="checked" > NO';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Status : </label> ';
                fdHTML += ' <input type="radio" name="status" value="1" checked="checked"> Active';
                fdHTML += ' <input type="radio" name="status" value="0"> Inactive';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<input type="hidden" name="field_type" value="PHONEFIELD" />';

          var icon_class = $(this).children('i:first').attr('class');
          $('#modal_title').html('<i class="'+ icon_class +'"></i> PHONE NUMBER FIELD');
    }


    if( field_type == 'URLFIELD' ) {
      var fdHTML = '';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Field name : <em>*</em></label>';
                fdHTML += '<input type="text" name="field_name" class="form-control" placeholder="Enter Field name" required />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Display text : <em>*</em></label>';
                fdHTML += '<input type="text" name="display_text" class="form-control" placeholder="Enter display text" required />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Placeholder : </label>';
                fdHTML += '<input type="text" name="placeholder" class="form-control" placeholder="Enter placeholder" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Default value : </label>';
                fdHTML += '<input type="text" name="default_value" class="form-control" placeholder="Enter default value" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Css class name : </label>';
                fdHTML += '<input type="text" name="css_class" class="form-control" placeholder="For multiple use comma" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Css id : </label>';
                fdHTML += '<input type="text" name="css_id" class="form-control" placeholder="Enter css id" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-12">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Help text : </label>';
                fdHTML += '<input type="text" name="help_text" class="form-control" placeholder="Any help text" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Is required : </label>';
                fdHTML += ' <input type="radio" name="is_required" value="required"> YES';
                fdHTML += ' <input type="radio" name="is_required" value="" checked="checked" > NO';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Status : </label> ';
                fdHTML += ' <input type="radio" name="status" value="1" checked="checked"> Active';
                fdHTML += ' <input type="radio" name="status" value="0"> Inactive';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<input type="hidden" name="field_type" value="URLFIELD" />';

          var icon_class = $(this).children('i:first').attr('class');
          $('#modal_title').html('<i class="'+ icon_class +'"></i> URL FIELD');
    }


    if( field_type == 'DATEFIELD' ) {
      var fdHTML = '';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Field name : <em>*</em></label>';
                fdHTML += '<input type="text" name="field_name" class="form-control" placeholder="Enter Field name" required />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Display text : <em>*</em></label>';
                fdHTML += '<input type="text" name="display_text" class="form-control" placeholder="Enter display text" required />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Placeholder : </label>';
                fdHTML += '<input type="text" name="placeholder" class="form-control" placeholder="Enter placeholder" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Default value : </label>';
                fdHTML += '<input type="text" name="default_value" class="form-control" placeholder="Enter default value" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Css class name : </label>';
                fdHTML += '<input type="text" name="css_class" class="form-control" placeholder="For multiple use comma" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Css id : </label>';
                fdHTML += '<input type="text" name="css_id" class="form-control" placeholder="Enter css id" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-12">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Help text : </label>';
                fdHTML += '<input type="text" name="help_text" class="form-control" placeholder="Any help text" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Is required : </label>';
                fdHTML += ' <input type="radio" name="is_required" value="required"> YES';
                fdHTML += ' <input type="radio" name="is_required" value="" checked="checked" > NO';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Status : </label> ';
                fdHTML += ' <input type="radio" name="status" value="1" checked="checked"> Active';
                fdHTML += ' <input type="radio" name="status" value="0"> Inactive';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<input type="hidden" name="field_type" value="DATEFIELD" />';

          var icon_class = $(this).children('i:first').attr('class');
          $('#modal_title').html('<i class="'+ icon_class +'"></i> DATE FIELD');
    }


    if( field_type == 'PARAFIELD' ) {
      var fdHTML = '';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Field name : <em>*</em></label>';
                fdHTML += '<input type="text" name="field_name" class="form-control" placeholder="Enter Field name" required />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Display text : <em>*</em></label>';
                fdHTML += '<input type="text" name="display_text" class="form-control" placeholder="Enter display text" required />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-12">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Placeholder : </label>';
                fdHTML += '<input type="text" name="placeholder" class="form-control" placeholder="Enter placeholder" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Css class name : </label>';
                fdHTML += '<input type="text" name="css_class" class="form-control" placeholder="For multiple use comma" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Css id : </label>';
                fdHTML += '<input type="text" name="css_id" class="form-control" placeholder="Enter css id" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-12">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Help text : </label>';
                fdHTML += '<input type="text" name="help_text" class="form-control" placeholder="Any help text" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Is required : </label>';
                fdHTML += ' <input type="radio" name="is_required" value="required"> YES';
                fdHTML += ' <input type="radio" name="is_required" value="" checked="checked" > NO';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Status : </label> ';
                fdHTML += ' <input type="radio" name="status" value="1" checked="checked"> Active';
                fdHTML += ' <input type="radio" name="status" value="0"> Inactive';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<input type="hidden" name="field_type" value="PARAFIELD" />';

          var icon_class = $(this).children('i:first').attr('class');
          $('#modal_title').html('<i class="'+ icon_class +'"></i> PARAGRAPH FIELD');
    }


    if( field_type == 'FILEFIELD' ) {
      var fdHTML = '';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Field name : <em>*</em></label>';
                fdHTML += '<input type="text" name="field_name" class="form-control" placeholder="Enter Field name" required />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Display text : <em>*</em></label>';
                fdHTML += '<input type="text" name="display_text" class="form-control" placeholder="Enter display text" required />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Css class name : </label>';
                fdHTML += '<input type="text" name="css_class" class="form-control" placeholder="For multiple use comma" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Css id : </label>';
                fdHTML += '<input type="text" name="css_id" class="form-control" placeholder="Enter css id" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-12">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Help text : </label>';
                fdHTML += '<input type="text" name="help_text" class="form-control" placeholder="Any help text" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Is required : </label>';
                fdHTML += ' <input type="radio" name="is_required" value="required"> YES';
                fdHTML += ' <input type="radio" name="is_required" value="" checked="checked" > NO';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Status : </label> ';
                fdHTML += ' <input type="radio" name="status" value="1" checked="checked"> Active';
                fdHTML += ' <input type="radio" name="status" value="0"> Inactive';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<input type="hidden" name="field_type" value="FILEFIELD" />';

          var icon_class = $(this).children('i:first').attr('class');
          $('#modal_title').html('<i class="'+ icon_class +'"></i> FILEUPLOAD FIELD');
    }


    if( field_type == 'DROPDOWN' ) {
      var fdHTML = '';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Field name : <em>*</em></label>';
                fdHTML += '<input type="text" name="field_name" class="form-control" placeholder="Enter Field name" required />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Display text : <em>*</em></label>';
                fdHTML += '<input type="text" name="display_text" class="form-control" placeholder="Enter display text" required />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Title : </label>';
                fdHTML += '<input type="text" name="title" class="form-control" placeholder="Enter title text" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Default value : </label>';
                fdHTML += '<input type="text" name="default_value" class="form-control" placeholder="Enter default value" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Add options : <em>*</em></label>';
                fdHTML += '<input type="text" name="options[0]" class="form-control" placeholder="Add option" required>';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<a href="javascript:void(0);" id="addMoreBTN" class="btn btn-primary btn-sm" style="margin-top : 25px;">Add more</a>';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="addMoreDIV"></div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Css class name : </label>';
                fdHTML += '<input type="text" name="css_class" class="form-control" placeholder="For multiple use comma" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Css id : </label>';
                fdHTML += '<input type="text" name="css_id" class="form-control" placeholder="Enter css id" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-12">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Help text : </label>';
                fdHTML += '<input type="text" name="help_text" class="form-control" placeholder="Any help text" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Is required : </label>';
                fdHTML += ' <input type="radio" name="is_required" value="required"> YES';
                fdHTML += ' <input type="radio" name="is_required" value="" checked="checked" > NO';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Status : </label> ';
                fdHTML += ' <input type="radio" name="status" value="1" checked="checked"> Active';
                fdHTML += ' <input type="radio" name="status" value="0"> Inactive';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<input type="hidden" name="field_type" value="DROPDOWN" />';

          var icon_class = $(this).children('i:first').attr('class');
          $('#modal_title').html('<i class="'+ icon_class +'"></i> DROPDOWN FIELD');
    }


    if( field_type == 'LISTBOX' ) {
      var fdHTML = '';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Field name : <em>*</em></label>';
                fdHTML += '<input type="text" name="field_name" class="form-control" placeholder="Enter Field name" required />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Display text : <em>*</em></label>';
                fdHTML += '<input type="text" name="display_text" class="form-control" placeholder="Enter display text" required />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Title : </label>';
                fdHTML += '<input type="text" name="title" class="form-control" placeholder="Enter title text" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Default value : </label>';
                fdHTML += '<input type="text" name="default_value" class="form-control" placeholder="Enter default value" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Add options : <em>*</em></label>';
                fdHTML += '<input type="text" name="options[0]" class="form-control" placeholder="Add option" required>';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<a href="javascript:void(0);" id="addMoreBTN" class="btn btn-primary btn-sm" style="margin-top : 25px;">Add more</a>';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="addMoreDIV"></div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Css class name : </label>';
                fdHTML += '<input type="text" name="css_class" class="form-control" placeholder="For multiple use comma" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Css id : </label>';
                fdHTML += '<input type="text" name="css_id" class="form-control" placeholder="Enter css id" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-12">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Help text : </label>';
                fdHTML += '<input type="text" name="help_text" class="form-control" placeholder="Any help text" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Is required : </label>';
                fdHTML += ' <input type="radio" name="is_required" value="required"> YES';
                fdHTML += ' <input type="radio" name="is_required" value="" checked="checked" > NO';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Status : </label> ';
                fdHTML += ' <input type="radio" name="status" value="1" checked="checked"> Active';
                fdHTML += ' <input type="radio" name="status" value="0"> Inactive';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<input type="hidden" name="field_type" value="LISTBOX" />';

          var icon_class = $(this).children('i:first').attr('class');
          $('#modal_title').html('<i class="'+ icon_class +'"></i> LISTBOX FIELD');
    }


    if( field_type == 'RADIOFIELD' ) {
      var fdHTML = '';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Field name : <em>*</em></label>';
                fdHTML += '<input type="text" name="field_name" class="form-control" placeholder="Enter Field name" required />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Display text : <em>*</em></label>';
                fdHTML += '<input type="text" name="display_text" class="form-control" placeholder="Enter display text" required />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Add options : <em>*</em></label>';
                fdHTML += '<input type="text" name="options[0]" class="form-control" placeholder="Add option" required>';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<a href="javascript:void(0);" id="addMoreBTN" class="btn btn-primary btn-sm" style="margin-top : 25px;">Add more</a>';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="addMoreDIV"></div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Css class name : </label>';
                fdHTML += '<input type="text" name="css_class" class="form-control" placeholder="For multiple use comma" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Css id : </label>';
                fdHTML += '<input type="text" name="css_id" class="form-control" placeholder="Enter css id" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-12">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Help text : </label>';
                fdHTML += '<input type="text" name="help_text" class="form-control" placeholder="Any help text" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Is required : </label>';
                fdHTML += ' <input type="radio" name="is_required" value="required"> YES';
                fdHTML += ' <input type="radio" name="is_required" value="" checked="checked" > NO';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Status : </label> ';
                fdHTML += ' <input type="radio" name="status" value="1" checked="checked"> Active';
                fdHTML += ' <input type="radio" name="status" value="0"> Inactive';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<input type="hidden" name="field_type" value="RADIOFIELD" />';

          var icon_class = $(this).children('i:first').attr('class');
          $('#modal_title').html('<i class="'+ icon_class +'"></i> RADIO OPTION FIELD');
    }


    if( field_type == 'CHECKFIELD' ) {
      var fdHTML = '';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Field name : <em>*</em></label>';
                fdHTML += '<input type="text" name="field_name" class="form-control" placeholder="Enter Field name" required />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Display text : <em>*</em></label>';
                fdHTML += '<input type="text" name="display_text" class="form-control" placeholder="Enter display text" required />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Add options : <em>*</em></label>';
                fdHTML += '<input type="text" name="options[0]" class="form-control" placeholder="Add option" required>';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<a href="javascript:void(0);" id="addMoreBTN" class="btn btn-primary btn-sm" style="margin-top : 25px;">Add more</a>';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="addMoreDIV"></div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Css class name : </label>';
                fdHTML += '<input type="text" name="css_class" class="form-control" placeholder="For multiple use comma" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Css id : </label>';
                fdHTML += '<input type="text" name="css_id" class="form-control" placeholder="Enter css id" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-12">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Help text : </label>';
                fdHTML += '<input type="text" name="help_text" class="form-control" placeholder="Any help text" />';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<div class="row">';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Is required : </label>';
                fdHTML += ' <input type="radio" name="is_required" value="required"> YES';
                fdHTML += ' <input type="radio" name="is_required" value="" checked="checked" > NO';
              fdHTML += '</div>';
            fdHTML += '</div>';
            fdHTML += '<div class="col-md-6">';
              fdHTML += '<div class="form-group">';
                fdHTML += '<label>Status : </label> ';
                fdHTML += ' <input type="radio" name="status" value="1" checked="checked"> Active';
                fdHTML += ' <input type="radio" name="status" value="0"> Inactive';
              fdHTML += '</div>';
            fdHTML += '</div>';
          fdHTML += '</div>';
          fdHTML += '<input type="hidden" name="field_type" value="CHECKFIELD" />';

          var icon_class = $(this).children('i:first').attr('class');
          $('#modal_title').html('<i class="'+ icon_class +'"></i> CHECKBOX FIELD');
    }

    if( fdHTML != '' ) {

      $('#modal_body').html(fdHTML);
      $("#fdModal").modal({
        backdrop: 'static',
        keyboard: false
      });  
    }
    
  });
});
</script>

<script type="text/javascript">

$("#ajax_frm").validate({
  errorElement : 'span',
  errorClass : 'ar-vali-error',
  submitHandler : function(form) {
    
    $.ajax({
      type: form.method,
      url: form.action,
      data: $(form).serialize(),
      beforeSend: function() {
        $("#addFD").attr('disabled','disabled');
        $("#ajx_status").removeClass('base-green').html('Please wait...');
      },
      success: function(data_html) {
        $("#addFD").removeAttr('disabled');
        $("#ajx_status").addClass('base-green').html('Field created & added to form.');
        $('#modal_body').find('input[type="text"]').val('');
        $('#modal_body').find('input[name="status"][value="1"]').prop('checked', true);
        $('#modal_body').find('input[name="is_required"][value="not-required"]').prop('checked', true);
        $('#addMoreDIV').html('');
        //$('#fieldContainer').append(data_html);
        $('.btnf').before( data_html ); /* Element add before the action button */
        addED_Butn();
        //var addM = 1;
        $("#fdModal").modal('hide');
      },
      error: function(ajx_err) {
        $("#ajx_status").removeClass('base-green').addClass('base-red').html(ajx_err);
      }
    });

    //return false;
  }
});

$("#ajax_frm_ed").validate({
  errorElement : 'span',
  errorClass : 'ar-vali-error',
  submitHandler : function(form) {
    
    var fdbox_id = $('#edit_row_id').val(); 
    $.ajax({
      type: form.method,
      url: form.action,
      data: $(form).serialize(),
      beforeSend: function() {
        $("#addFD_ed").attr('disabled','disabled');
        $("#ajx_status_ed").removeClass('base-green').html('Please wait...');
      },
      success: function(data_html) {
        $("#addFD_ed").removeAttr('disabled');
        $("#ajx_status_ed").addClass('base-green').html('Field updated successfully.');
        $('#field_'+fdbox_id).removeAttr('class').removeAttr('id').html(data_html);
        addED_Butn();
        //var addM = 1;
      },
      error: function(ajx_err) {
        $("#ajx_status_ed").removeClass('base-green').addClass('base-red').html(ajx_err);
      }
    });

    //return false;
  }
});

</script>

<script type="text/javascript">

addED_Butn();

function addED_Butn() {
  
  $('.fd_box').each( function() {
    var divID = $(this).attr('id');
    var rowID = '';
    var ARR = divID.split('_');
    if( ARR.length != 0 ) {
      rowID = ARR[1];
    }

    var edHTML = '';
        edHTML += '<div class="edBOX">';
          edHTML += '<a href="javascript:void(0);" class="fld_edit base-green btn btn-xs btn-default" id="'+ rowID +'"> <i class="fa fa-pencil-square-o base-green" aria-hidden="true"></i> Edit</a> ';
          if( ! $(this).hasClass('btnf') ) {  
          edHTML += '<a href="javascript:void(0);" class="fld_del base-red btn btn-xs btn-default" id="'+ rowID +'"> <i class="fa fa-trash-o base-red" aria-hidden="true"></i> Delete</a> ';
        }
          /*edHTML += '<a href="javascript:void(0);" class="pull-right"><input type="checkbox" name="mob_frm[]" value="1"> <span class="base-blue"><small>Add To Mobile</small></span></a>';*/
        edHTML += "</div>";

  $('#ed_action_box_'+rowID).html(edHTML);

  } );
}


</script>

<script type="text/javascript">
$( function() {
  $('body').on('click', '.fld_edit', function() {
    $('#ajx_status_ed').html('');
    var getRowID = $(this).attr('id');
    if( getRowID !=  '' ) {
      $.ajax({
        type : "POST",
        url : "{{ route('ajx_edt_modal') }}",
        data : "row_id="+getRowID+"&_token={{ csrf_token() }}",
        beforeSend : function() {
          $('#field_'+getRowID).block({ 
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
        success : function(modal_json_data) {

          var fdHTML = '';
          if( ! jQuery.isEmptyObject(modal_json_data) ) {
            var obj = JSON.parse(modal_json_data);
            var fldType = obj.field_type;

            var help_text = obj.help_text === null ? '' : obj.help_text;
            var display_text = obj.display_text === null ? '' : obj.display_text;
            var placeholder = obj.placeholder === null ? '' : obj.placeholder;
            var default_value = obj.default_value === null ? '' : obj.default_value;
            var css_class = obj.css_class === null ? '' : obj.css_class;
            var css_id = obj.css_id === null ? '' : obj.css_id;
            var min_value = obj.min_value === null ? '' : obj.min_value;
            var max_value = obj.max_value === null ? '' : obj.max_value;
            var title = obj.title === null ? '' : obj.title;
            var bgcolor = obj.bgcolor === null ? '' : obj.bgcolor;
            var color = obj.color === null ? '' : obj.color;
            var options = obj.options;
            var options_len = options.length;

            if( fldType == 'BUTTON' ) {
              
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-12">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Button Text : <em>*</em></label>';
                    fdHTML += '<input type="text" name="default_value" class="form-control" placeholder="Enter Button Text" required value="'+ obj.default_value +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              /*fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-4">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label> Css Class: </label>';
                    fdHTML += '<input type="text" name="css_class" class="form-control" placeholder="Any Css Class" value="'+ css_class +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-4">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label> Background: </label>';
                    fdHTML += '<input type="text" name="bgcolor" class="form-control" placeholder="Any Background color" value="'+ bgcolor +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-4">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label> Text Color: </label>';
                    fdHTML += '<input type="text" name="color" class="form-control" placeholder="Any Text color" value="'+ color +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';*/

              $('#modal_title_ed').html('Edit BUTTON FIELD');
              $('#edit_row_id').val(getRowID);
            }

            if( fldType == 'TEXTFIELD' ) {

              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Field name : <em>*</em></label>';
                    fdHTML += '<input type="text" name="field_name" class="form-control" placeholder="Enter Field name" required value="'+ obj.field_name +'" disabled="disabled" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Display text : <em>*</em></label>';
                    fdHTML += '<input type="text" name="display_text" class="form-control" placeholder="Enter display text" required value="'+ display_text +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Title : </label>';
                    fdHTML += '<input type="text" name="title" class="form-control" placeholder="Enter placeholder" value="'+ title +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Default value : </label>';
                    fdHTML += '<input type="text" name="default_value" class="form-control" placeholder="Enter default value" value="'+ default_value +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Css class name : </label>';
                    fdHTML += '<input type="text" name="css_class" class="form-control" placeholder="For multiple use comma" value="'+ css_class +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Css id : </label>';
                    fdHTML += '<input type="text" name="css_id" class="form-control" placeholder="Enter css id" value="'+ css_id +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-12">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Help text : </label>';
                    fdHTML += '<input type="text" name="help_text" class="form-control" placeholder="Any help text" value="'+ help_text +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Is required : </label>';
                    if( obj.is_required == 'required' ) {
                      fdHTML += ' <input type="radio" name="is_required" value="required" checked="checked" > YES';
                      fdHTML += ' <input type="radio" name="is_required" value="" > NO';
                    } else {
                      fdHTML += ' <input type="radio" name="is_required" value="required" > YES';
                      fdHTML += ' <input type="radio" name="is_required" value="" checked="checked" > NO';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Status : </label> ';
                    if( obj.status == 1 ) {
                      fdHTML += ' <input type="radio" name="status" value="1" checked="checked"> Active';
                      fdHTML += ' <input type="radio" name="status" value="0"> Inactive';
                    } else {
                      fdHTML += ' <input type="radio" name="status" value="1"> Active';
                      fdHTML += ' <input type="radio" name="status" value="0" checked="checked"> Inactive';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<input type="hidden" name="field_type" value="TEXTFIELD" />';

              $('#modal_title_ed').html('Edit TEXT FIELD');
              $('#edit_row_id').val(getRowID);
            }


            if( fldType == 'EMAILFIELD' ) {

              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Field name : <em>*</em></label>';
                    fdHTML += '<input type="text" name="field_name" class="form-control" placeholder="Enter Field name" required value="'+ obj.field_name +'" disabled="disabled" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Display text : <em>*</em></label>';
                    fdHTML += '<input type="text" name="display_text" class="form-control" placeholder="Enter display text" required value="'+ display_text +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Placeholder : </label>';
                    fdHTML += '<input type="text" name="placeholder" class="form-control" placeholder="Enter placeholder" value="'+ placeholder +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Default value : </label>';
                    fdHTML += '<input type="text" name="default_value" class="form-control" placeholder="Enter default value" value="'+ default_value +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Css class name : </label>';
                    fdHTML += '<input type="text" name="css_class" class="form-control" placeholder="For multiple use comma" value="'+ css_class +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Css id : </label>';
                    fdHTML += '<input type="text" name="css_id" class="form-control" placeholder="Enter css id" value="'+ css_id +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-12">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Help text : </label>';
                    fdHTML += '<input type="text" name="help_text" class="form-control" placeholder="Any help text" value="'+ help_text +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Is required : </label>';
                    if( obj.is_required == 'required' ) {
                      fdHTML += ' <input type="radio" name="is_required" value="required" checked="checked" > YES';
                      fdHTML += ' <input type="radio" name="is_required" value="" > NO';
                    } else {
                      fdHTML += ' <input type="radio" name="is_required" value="required" > YES';
                      fdHTML += ' <input type="radio" name="is_required" value="" checked="checked" > NO';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Status : </label> ';
                    if( obj.status == 1 ) {
                      fdHTML += ' <input type="radio" name="status" value="1" checked="checked"> Active';
                      fdHTML += ' <input type="radio" name="status" value="0"> Inactive';
                    } else {
                      fdHTML += ' <input type="radio" name="status" value="1"> Active';
                      fdHTML += ' <input type="radio" name="status" value="0" checked="checked"> Inactive';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<input type="hidden" name="field_type" value="EMAILFIELD" />';

              $('#modal_title_ed').html('Edit EMAIL FIELD');
              $('#edit_row_id').val(getRowID);
            }


            if( fldType == 'NUMBERFIELD' ) {

              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Field name : <em>*</em></label>';
                    fdHTML += '<input type="text" name="field_name" class="form-control" placeholder="Enter Field name" required value="'+ obj.field_name +'" disabled="disabled" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Display text : <em>*</em></label>';
                    fdHTML += '<input type="text" name="display_text" class="form-control" placeholder="Enter display text" required value="'+ display_text +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Placeholder : </label>';
                    fdHTML += '<input type="text" name="placeholder" class="form-control" placeholder="Enter placeholder" value="'+ placeholder +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Default value : </label>';
                    fdHTML += '<input type="text" name="default_value" class="form-control" placeholder="Enter default value" value="'+ default_value +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Css class name : </label>';
                    fdHTML += '<input type="text" name="css_class" class="form-control" placeholder="For multiple use comma" value="'+ css_class +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Css id : </label>';
                    fdHTML += '<input type="text" name="css_id" class="form-control" placeholder="Enter css id" value="'+ css_id +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Minimum value : </label>';
                    fdHTML += '<input type="number" name="min_value" class="form-control onlyNumber" placeholder="Minimum value" value="'+ min_value +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Maximum value : </label>';
                    fdHTML += '<input type="number" name="max_value" class="form-control onlyNumber" placeholder="Maximum value" value="'+ max_value +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-12">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Help text : </label>';
                    fdHTML += '<input type="text" name="help_text" class="form-control" placeholder="Any help text" value="'+ help_text +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Is required : </label>';
                    if( obj.is_required == 'required' ) {
                      fdHTML += ' <input type="radio" name="is_required" value="required" checked="checked" > YES';
                      fdHTML += ' <input type="radio" name="is_required" value="" > NO';
                    } else {
                      fdHTML += ' <input type="radio" name="is_required" value="required" > YES';
                      fdHTML += ' <input type="radio" name="is_required" value="" checked="checked" > NO';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Status : </label> ';
                    if( obj.status == 1 ) {
                      fdHTML += ' <input type="radio" name="status" value="1" checked="checked"> Active';
                      fdHTML += ' <input type="radio" name="status" value="0"> Inactive';
                    } else {
                      fdHTML += ' <input type="radio" name="status" value="1"> Active';
                      fdHTML += ' <input type="radio" name="status" value="0" checked="checked"> Inactive';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<input type="hidden" name="field_type" value="NUMBERFIELD" />';

              $('#modal_title_ed').html('Edit NUMBER FIELD');
              $('#edit_row_id').val(getRowID);
            }

            if( fldType == 'PHONEFIELD' ) {

              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Field name : <em>*</em></label>';
                    fdHTML += '<input type="text" name="field_name" class="form-control" placeholder="Enter Field name" required value="'+ obj.field_name +'" disabled="disabled" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Display text : <em>*</em></label>';
                    fdHTML += '<input type="text" name="display_text" class="form-control" placeholder="Enter display text" required value="'+ display_text +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Placeholder : </label>';
                    fdHTML += '<input type="text" name="placeholder" class="form-control" placeholder="Enter placeholder" value="'+ placeholder +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Default value : </label>';
                    fdHTML += '<input type="text" name="default_value" class="form-control" placeholder="Enter default value" value="'+ default_value +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Css class name : </label>';
                    fdHTML += '<input type="text" name="css_class" class="form-control" placeholder="For multiple use comma" value="'+ css_class +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Css id : </label>';
                    fdHTML += '<input type="text" name="css_id" class="form-control" placeholder="Enter css id" value="'+ css_id +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-12">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Help text : </label>';
                    fdHTML += '<input type="text" name="help_text" class="form-control" placeholder="Any help text" value="'+ help_text +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Is required : </label>';
                    if( obj.is_required == 'required' ) {
                      fdHTML += ' <input type="radio" name="is_required" value="required" checked="checked" > YES';
                      fdHTML += ' <input type="radio" name="is_required" value="" > NO';
                    } else {
                      fdHTML += ' <input type="radio" name="is_required" value="required" > YES';
                      fdHTML += ' <input type="radio" name="is_required" value="" checked="checked" > NO';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Status : </label> ';
                    if( obj.status == 1 ) {
                      fdHTML += ' <input type="radio" name="status" value="1" checked="checked"> Active';
                      fdHTML += ' <input type="radio" name="status" value="0"> Inactive';
                    } else {
                      fdHTML += ' <input type="radio" name="status" value="1"> Active';
                      fdHTML += ' <input type="radio" name="status" value="0" checked="checked"> Inactive';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<input type="hidden" name="field_type" value="PHONEFIELD" />';

              $('#modal_title_ed').html('Edit PHONE NUMBER FIELD');
              $('#edit_row_id').val(getRowID);
            }


            if( fldType == 'URLFIELD' ) {

              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Field name : <em>*</em></label>';
                    fdHTML += '<input type="text" name="field_name" class="form-control" placeholder="Enter Field name" required value="'+ obj.field_name +'" disabled="disabled" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Display text : <em>*</em></label>';
                    fdHTML += '<input type="text" name="display_text" class="form-control" placeholder="Enter display text" required value="'+ display_text +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Placeholder : </label>';
                    fdHTML += '<input type="text" name="placeholder" class="form-control" placeholder="Enter placeholder" value="'+ placeholder +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Default value : </label>';
                    fdHTML += '<input type="text" name="default_value" class="form-control" placeholder="Enter default value" value="'+ default_value +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Css class name : </label>';
                    fdHTML += '<input type="text" name="css_class" class="form-control" placeholder="For multiple use comma" value="'+ css_class +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Css id : </label>';
                    fdHTML += '<input type="text" name="css_id" class="form-control" placeholder="Enter css id" value="'+ css_id +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-12">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Help text : </label>';
                    fdHTML += '<input type="text" name="help_text" class="form-control" placeholder="Any help text" value="'+ help_text +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Is required : </label>';
                    if( obj.is_required == 'required' ) {
                      fdHTML += ' <input type="radio" name="is_required" value="required" checked="checked" > YES';
                      fdHTML += ' <input type="radio" name="is_required" value="" > NO';
                    } else {
                      fdHTML += ' <input type="radio" name="is_required" value="required" > YES';
                      fdHTML += ' <input type="radio" name="is_required" value="" checked="checked" > NO';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Status : </label> ';
                    if( obj.status == 1 ) {
                      fdHTML += ' <input type="radio" name="status" value="1" checked="checked"> Active';
                      fdHTML += ' <input type="radio" name="status" value="0"> Inactive';
                    } else {
                      fdHTML += ' <input type="radio" name="status" value="1"> Active';
                      fdHTML += ' <input type="radio" name="status" value="0" checked="checked"> Inactive';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<input type="hidden" name="field_type" value="URLFIELD" />';

              $('#modal_title_ed').html('Edit URL FIELD');
              $('#edit_row_id').val(getRowID);
            }


            if( fldType == 'DATEFIELD' ) {

              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Field name : <em>*</em></label>';
                    fdHTML += '<input type="text" name="field_name" class="form-control" placeholder="Enter Field name" required value="'+ obj.field_name +'" disabled="disabled" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Display text : <em>*</em></label>';
                    fdHTML += '<input type="text" name="display_text" class="form-control" placeholder="Enter display text" required value="'+ display_text +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Placeholder : </label>';
                    fdHTML += '<input type="text" name="placeholder" class="form-control" placeholder="Enter placeholder" value="'+ placeholder +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Default value : </label>';
                    fdHTML += '<input type="text" name="default_value" class="form-control" placeholder="Enter default value" value="'+ default_value +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Css class name : </label>';
                    fdHTML += '<input type="text" name="css_class" class="form-control" placeholder="For multiple use comma" value="'+ css_class +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Css id : </label>';
                    fdHTML += '<input type="text" name="css_id" class="form-control" placeholder="Enter css id" value="'+ css_id +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-12">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Help text : </label>';
                    fdHTML += '<input type="text" name="help_text" class="form-control" placeholder="Any help text" value="'+ help_text +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Is required : </label>';
                    if( obj.is_required == 'required' ) {
                      fdHTML += ' <input type="radio" name="is_required" value="required" checked="checked" > YES';
                      fdHTML += ' <input type="radio" name="is_required" value="" > NO';
                    } else {
                      fdHTML += ' <input type="radio" name="is_required" value="required" > YES';
                      fdHTML += ' <input type="radio" name="is_required" value="" checked="checked" > NO';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Status : </label> ';
                    if( obj.status == 1 ) {
                      fdHTML += ' <input type="radio" name="status" value="1" checked="checked"> Active';
                      fdHTML += ' <input type="radio" name="status" value="0"> Inactive';
                    } else {
                      fdHTML += ' <input type="radio" name="status" value="1"> Active';
                      fdHTML += ' <input type="radio" name="status" value="0" checked="checked"> Inactive';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<input type="hidden" name="field_type" value="DATEFIELD" />';

              $('#modal_title_ed').html('Edit DATE FIELD');
              $('#edit_row_id').val(getRowID);
            }


            if( fldType == 'PARAFIELD' ) {

              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Field name : <em>*</em></label>';
                    fdHTML += '<input type="text" name="field_name" class="form-control" placeholder="Enter Field name" required value="'+ obj.field_name +'" disabled="disabled" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Display text : <em>*</em></label>';
                    fdHTML += '<input type="text" name="display_text" class="form-control" placeholder="Enter display text" required value="'+ display_text +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-12">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Placeholder : </label>';
                    fdHTML += '<input type="text" name="placeholder" class="form-control" placeholder="Enter placeholder" value="'+ placeholder +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Css class name : </label>';
                    fdHTML += '<input type="text" name="css_class" class="form-control" placeholder="For multiple use comma" value="'+ css_class +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Css id : </label>';
                    fdHTML += '<input type="text" name="css_id" class="form-control" placeholder="Enter css id" value="'+ css_id +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-12">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Help text : </label>';
                    fdHTML += '<input type="text" name="help_text" class="form-control" placeholder="Any help text" value="'+ help_text +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Is required : </label>';
                    if( obj.is_required == 'required' ) {
                      fdHTML += ' <input type="radio" name="is_required" value="required" checked="checked" > YES';
                      fdHTML += ' <input type="radio" name="is_required" value="" > NO';
                    } else {
                      fdHTML += ' <input type="radio" name="is_required" value="required" > YES';
                      fdHTML += ' <input type="radio" name="is_required" value="" checked="checked" > NO';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Status : </label> ';
                    if( obj.status == 1 ) {
                      fdHTML += ' <input type="radio" name="status" value="1" checked="checked"> Active';
                      fdHTML += ' <input type="radio" name="status" value="0"> Inactive';
                    } else {
                      fdHTML += ' <input type="radio" name="status" value="1"> Active';
                      fdHTML += ' <input type="radio" name="status" value="0" checked="checked"> Inactive';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<input type="hidden" name="field_type" value="PARAFIELD" />';

              $('#modal_title_ed').html('Edit PARAGRAPH FIELD');
              $('#edit_row_id').val(getRowID);
            }


            if( fldType == 'FILEFIELD' ) {

              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Field name : <em>*</em></label>';
                    fdHTML += '<input type="text" name="field_name" class="form-control" placeholder="Enter Field name" required value="'+ obj.field_name +'" disabled="disabled" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Display text : <em>*</em></label>';
                    fdHTML += '<input type="text" name="display_text" class="form-control" placeholder="Enter display text" required value="'+ display_text +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Css class name : </label>';
                    fdHTML += '<input type="text" name="css_class" class="form-control" placeholder="For multiple use comma" value="'+ css_class +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Css id : </label>';
                    fdHTML += '<input type="text" name="css_id" class="form-control" placeholder="Enter css id" value="'+ css_id +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-12">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Help text : </label>';
                    fdHTML += '<input type="text" name="help_text" class="form-control" placeholder="Any help text" value="'+ help_text +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Is required : </label>';
                    if( obj.is_required == 'required' ) {
                      fdHTML += ' <input type="radio" name="is_required" value="required" checked="checked" > YES';
                      fdHTML += ' <input type="radio" name="is_required" value="" > NO';
                    } else {
                      fdHTML += ' <input type="radio" name="is_required" value="required" > YES';
                      fdHTML += ' <input type="radio" name="is_required" value="" checked="checked" > NO';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Status : </label> ';
                    if( obj.status == 1 ) {
                      fdHTML += ' <input type="radio" name="status" value="1" checked="checked"> Active';
                      fdHTML += ' <input type="radio" name="status" value="0"> Inactive';
                    } else {
                      fdHTML += ' <input type="radio" name="status" value="1"> Active';
                      fdHTML += ' <input type="radio" name="status" value="0" checked="checked"> Inactive';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<input type="hidden" name="field_type" value="FILEFIELD" />';

              $('#modal_title_ed').html('Edit FILEUPLOAD FIELD');
              $('#edit_row_id').val(getRowID);
            }

            if( fldType == 'DROPDOWN' ) {

              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Field name : <em>*</em></label>';
                    fdHTML += '<input type="text" name="field_name" class="form-control" placeholder="Enter Field name" required value="'+ obj.field_name +'" disabled="disabled" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Display text : <em>*</em></label>';
                    fdHTML += '<input type="text" name="display_text" class="form-control" placeholder="Enter display text" required value="'+ display_text +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Title : </label>';
                    fdHTML += '<input type="text" name="title" class="form-control" placeholder="Enter title text" value="'+ title +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Default value : </label>';
                    fdHTML += '<input type="text" name="default_value" class="form-control" placeholder="Enter default value" value="'+ default_value +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Add options : <em>*</em></label>';
                    if( options_len > 0 ) {
                      fdHTML += '<input type="text" name="options[0]" class="form-control" placeholder="Add option" required value="'+ options[0] +'">';
                    } else {
                      fdHTML += '<input type="text" name="options[0]" class="form-control" placeholder="Add option" required>';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<a href="javascript:void(0);" id="addMoreBTN" class="btn btn-primary btn-sm" style="margin-top : 25px;">Add more</a>';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="addMoreDIV">';
                if( options_len > 1 ) {
                  for( var j = 1; j < options_len; j++) {

                    fdHTML += '<div class="row" id="addM_'+ j +'">';
                      fdHTML += '<div class="col-md-6">';
                        fdHTML += '<div class="form-group">';
                          fdHTML += '<input type="text" name="options['+ j +']" class="form-control" value="'+ options[j] +'" placeholder="Add option" required>';
                        fdHTML += '</div>';
                      fdHTML += '</div>';
                      fdHTML += '<div class="col-md-6">';
                        fdHTML += '<div class="form-group">';
                          fdHTML += '<a href="javascript:void(0);" class="rmMore" id="rmMore_'+ j +'"><i class="fa fa-times base-red" aria-hidden="true"></i></a>';
                        fdHTML += '</div>';
                      fdHTML += '</div>';
                    fdHTML += '</div>';
                  }

                  addM = j;
                }
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Css class name : </label>';
                    fdHTML += '<input type="text" name="css_class" class="form-control" placeholder="For multiple use comma" value="'+ css_class +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Css id : </label>';
                    fdHTML += '<input type="text" name="css_id" class="form-control" placeholder="Enter css id" value="'+ css_id +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-12">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Help text : </label>';
                    fdHTML += '<input type="text" name="help_text" class="form-control" placeholder="Any help text" value="'+ help_text +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Is required : </label>';
                    if( obj.is_required == 'required' ) {
                      fdHTML += ' <input type="radio" name="is_required" value="required" checked="checked" > YES';
                      fdHTML += ' <input type="radio" name="is_required" value="" > NO';
                    } else {
                      fdHTML += ' <input type="radio" name="is_required" value="required" > YES';
                      fdHTML += ' <input type="radio" name="is_required" value="" checked="checked" > NO';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Status : </label> ';
                    if( obj.status == 1 ) {
                      fdHTML += ' <input type="radio" name="status" value="1" checked="checked"> Active';
                      fdHTML += ' <input type="radio" name="status" value="0"> Inactive';
                    } else {
                      fdHTML += ' <input type="radio" name="status" value="1"> Active';
                      fdHTML += ' <input type="radio" name="status" value="0" checked="checked"> Inactive';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<input type="hidden" name="field_type" value="DROPDOWN" />';

              $('#modal_title_ed').html('Edit DROPDOWN FIELD');
              $('#edit_row_id').val(getRowID);
            }


            if( fldType == 'LISTBOX' ) {

              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Field name : <em>*</em></label>';
                    fdHTML += '<input type="text" name="field_name" class="form-control" placeholder="Enter Field name" required value="'+ obj.field_name +'" disabled="disabled" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Display text : <em>*</em></label>';
                    fdHTML += '<input type="text" name="display_text" class="form-control" placeholder="Enter display text" required value="'+ display_text +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Title : </label>';
                    fdHTML += '<input type="text" name="title" class="form-control" placeholder="Enter title text" value="'+ title +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Default value : </label>';
                    fdHTML += '<input type="text" name="default_value" class="form-control" placeholder="Enter default value" value="'+ default_value +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Add options : <em>*</em></label>';
                    if( options_len > 0 ) {
                      fdHTML += '<input type="text" name="options[0]" class="form-control" placeholder="Add option" required value="'+ options[0] +'">';
                    } else {
                      fdHTML += '<input type="text" name="options[0]" class="form-control" placeholder="Add option" required>';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<a href="javascript:void(0);" id="addMoreBTN" class="btn btn-primary btn-sm" style="margin-top : 25px;">Add more</a>';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="addMoreDIV">';
                if( options_len > 1 ) {
                  for( var j = 1; j < options_len; j++) {

                    fdHTML += '<div class="row" id="addM_'+ j +'">';
                      fdHTML += '<div class="col-md-6">';
                        fdHTML += '<div class="form-group">';
                          fdHTML += '<input type="text" name="options['+ j +']" class="form-control" value="'+ options[j] +'" placeholder="Add option" required>';
                        fdHTML += '</div>';
                      fdHTML += '</div>';
                      fdHTML += '<div class="col-md-6">';
                        fdHTML += '<div class="form-group">';
                          fdHTML += '<a href="javascript:void(0);" class="rmMore" id="rmMore_'+ j +'"><i class="fa fa-times base-red" aria-hidden="true"></i></a>';
                        fdHTML += '</div>';
                      fdHTML += '</div>';
                    fdHTML += '</div>';
                  }

                  addM = j;
                }
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Css class name : </label>';
                    fdHTML += '<input type="text" name="css_class" class="form-control" placeholder="For multiple use comma" value="'+ css_class +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Css id : </label>';
                    fdHTML += '<input type="text" name="css_id" class="form-control" placeholder="Enter css id" value="'+ css_id +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-12">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Help text : </label>';
                    fdHTML += '<input type="text" name="help_text" class="form-control" placeholder="Any help text" value="'+ help_text +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Is required : </label>';
                    if( obj.is_required == 'required' ) {
                      fdHTML += ' <input type="radio" name="is_required" value="required" checked="checked" > YES';
                      fdHTML += ' <input type="radio" name="is_required" value="" > NO';
                    } else {
                      fdHTML += ' <input type="radio" name="is_required" value="required" > YES';
                      fdHTML += ' <input type="radio" name="is_required" value="" checked="checked" > NO';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Status : </label> ';
                    if( obj.status == 1 ) {
                      fdHTML += ' <input type="radio" name="status" value="1" checked="checked"> Active';
                      fdHTML += ' <input type="radio" name="status" value="0"> Inactive';
                    } else {
                      fdHTML += ' <input type="radio" name="status" value="1"> Active';
                      fdHTML += ' <input type="radio" name="status" value="0" checked="checked"> Inactive';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<input type="hidden" name="field_type" value="LISTBOX" />';

              $('#modal_title_ed').html('Edit LISTBOX FIELD');
              $('#edit_row_id').val(getRowID);
            }


            if( fldType == 'RADIOFIELD' ) {

              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Field name : <em>*</em></label>';
                    fdHTML += '<input type="text" name="field_name" class="form-control" placeholder="Enter Field name" required value="'+ obj.field_name +'" disabled="disabled" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Display text : <em>*</em></label>';
                    fdHTML += '<input type="text" name="display_text" class="form-control" placeholder="Enter display text" required value="'+ display_text +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Add options : <em>*</em></label>';
                    if( options_len > 0 ) {
                      fdHTML += '<input type="text" name="options[0]" class="form-control" placeholder="Add option" required value="'+ options[0] +'">';
                    } else {
                      fdHTML += '<input type="text" name="options[0]" class="form-control" placeholder="Add option" required>';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<a href="javascript:void(0);" id="addMoreBTN" class="btn btn-primary btn-sm" style="margin-top : 25px;">Add more</a>';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="addMoreDIV">';
                if( options_len > 1 ) {
                  for( var j = 1; j < options_len; j++) {

                    fdHTML += '<div class="row" id="addM_'+ j +'">';
                      fdHTML += '<div class="col-md-6">';
                        fdHTML += '<div class="form-group">';
                          fdHTML += '<input type="text" name="options['+ j +']" class="form-control" value="'+ options[j] +'" placeholder="Add option" required>';
                        fdHTML += '</div>';
                      fdHTML += '</div>';
                      fdHTML += '<div class="col-md-6">';
                        fdHTML += '<div class="form-group">';
                          fdHTML += '<a href="javascript:void(0);" class="rmMore" id="rmMore_'+ j +'"><i class="fa fa-times base-red" aria-hidden="true"></i></a>';
                        fdHTML += '</div>';
                      fdHTML += '</div>';
                    fdHTML += '</div>';
                  }

                  addM = j;
                }
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Css class name : </label>';
                    fdHTML += '<input type="text" name="css_class" class="form-control" placeholder="For multiple use comma" value="'+ css_class +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Css id : </label>';
                    fdHTML += '<input type="text" name="css_id" class="form-control" placeholder="Enter css id" value="'+ css_id +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-12">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Help text : </label>';
                    fdHTML += '<input type="text" name="help_text" class="form-control" placeholder="Any help text" value="'+ help_text +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Is required : </label>';
                    if( obj.is_required == 'required' ) {
                      fdHTML += ' <input type="radio" name="is_required" value="required" checked="checked" > YES';
                      fdHTML += ' <input type="radio" name="is_required" value="" > NO';
                    } else {
                      fdHTML += ' <input type="radio" name="is_required" value="required" > YES';
                      fdHTML += ' <input type="radio" name="is_required" value="" checked="checked" > NO';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Status : </label> ';
                    if( obj.status == 1 ) {
                      fdHTML += ' <input type="radio" name="status" value="1" checked="checked"> Active';
                      fdHTML += ' <input type="radio" name="status" value="0"> Inactive';
                    } else {
                      fdHTML += ' <input type="radio" name="status" value="1"> Active';
                      fdHTML += ' <input type="radio" name="status" value="0" checked="checked"> Inactive';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<input type="hidden" name="field_type" value="RADIOFIELD" />';

              $('#modal_title_ed').html('Edit RADIO OPTION FIELD');
              $('#edit_row_id').val(getRowID);
            }


            if( fldType == 'CHECKFIELD' ) {

              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Field name : <em>*</em></label>';
                    fdHTML += '<input type="text" name="field_name" class="form-control" placeholder="Enter Field name" required value="'+ obj.field_name +'" disabled="disabled" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Display text : <em>*</em></label>';
                    fdHTML += '<input type="text" name="display_text" class="form-control" placeholder="Enter display text" required value="'+ display_text +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Add options : <em>*</em></label>';
                    if( options_len > 0 ) {
                      fdHTML += '<input type="text" name="options[0]" class="form-control" placeholder="Add option" required value="'+ options[0] +'">';
                    } else {
                      fdHTML += '<input type="text" name="options[0]" class="form-control" placeholder="Add option" required>';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<a href="javascript:void(0);" id="addMoreBTN" class="btn btn-primary btn-sm" style="margin-top : 25px;">Add more</a>';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="addMoreDIV">';
                if( options_len > 1 ) {
                  for( var j = 1; j < options_len; j++) {

                    fdHTML += '<div class="row" id="addM_'+ j +'">';
                      fdHTML += '<div class="col-md-6">';
                        fdHTML += '<div class="form-group">';
                          fdHTML += '<input type="text" name="options['+ j +']" class="form-control" value="'+ options[j] +'" placeholder="Add option" required>';
                        fdHTML += '</div>';
                      fdHTML += '</div>';
                      fdHTML += '<div class="col-md-6">';
                        fdHTML += '<div class="form-group">';
                          fdHTML += '<a href="javascript:void(0);" class="rmMore" id="rmMore_'+ j +'"><i class="fa fa-times base-red" aria-hidden="true"></i></a>';
                        fdHTML += '</div>';
                      fdHTML += '</div>';
                    fdHTML += '</div>';
                  }

                  addM = j;
                }
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Css class name : </label>';
                    fdHTML += '<input type="text" name="css_class" class="form-control" placeholder="For multiple use comma" value="'+ css_class +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Css id : </label>';
                    fdHTML += '<input type="text" name="css_id" class="form-control" placeholder="Enter css id" value="'+ css_id +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-12">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Help text : </label>';
                    fdHTML += '<input type="text" name="help_text" class="form-control" placeholder="Any help text" value="'+ help_text +'" />';
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<div class="row">';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Is required : </label>';
                    if( obj.is_required == 'required' ) {
                      fdHTML += ' <input type="radio" name="is_required" value="required" checked="checked" > YES';
                      fdHTML += ' <input type="radio" name="is_required" value="" > NO';
                    } else {
                      fdHTML += ' <input type="radio" name="is_required" value="required" > YES';
                      fdHTML += ' <input type="radio" name="is_required" value="" checked="checked" > NO';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
                fdHTML += '<div class="col-md-6">';
                  fdHTML += '<div class="form-group">';
                    fdHTML += '<label>Status : </label> ';
                    if( obj.status == 1 ) {
                      fdHTML += ' <input type="radio" name="status" value="1" checked="checked"> Active';
                      fdHTML += ' <input type="radio" name="status" value="0"> Inactive';
                    } else {
                      fdHTML += ' <input type="radio" name="status" value="1"> Active';
                      fdHTML += ' <input type="radio" name="status" value="0" checked="checked"> Inactive';
                    }
                  fdHTML += '</div>';
                fdHTML += '</div>';
              fdHTML += '</div>';
              fdHTML += '<input type="hidden" name="field_type" value="CHECKFIELD" />';

              $('#modal_title_ed').html('Edit CHECK BOX FIELD');
              $('#edit_row_id').val(getRowID);
            }

            $('#field_'+getRowID).unblock();
            $('#modal_body_ed').html(fdHTML);
            $('#fdEdModal').modal({
              backdrop: 'static',
              keyboard: false
            });
          }
        }
      });
    }
    
  });
});
</script>

<script type="text/javascript">
$( function() {
  $("body").on('keypress', '.onlyNumber', function(evt){
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
      return false;
    return true;
  });
  $( ".datepicker" ).datepicker({
      //minDate:0,
      dateFormat: 'dd-mm-yy',
      changeMonth: true,
      changeYear: true
  });
});
</script>

<script type="text/javascript">
$( function() {
  $('body').on('click', '.fld_del', function() {
    var getRowID = $(this).attr('id');
    var frm_auto_id = $('#set_frm_auto_id').val();

    if( getRowID !=  '' && frm_auto_id != '' ) {
      swal({
        title: "Are you sure?",
        text: "Are you sure to delete this form field",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        closeOnConfirm: false
      },
      function(isConfirm) {
        if (isConfirm) {
          $.ajax({
            type : "POST",
            url : "{{ route('ajx_fld_del') }}",
            data : "id="+getRowID+"&frm_auto_id="+frm_auto_id+"&_token={{ csrf_token() }}",
            beforeSend : function() {
              $('#field_'+getRowID).block({ 
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
            success : function(resp) {
              if( resp == 'ok') {
                $('#field_'+getRowID).unblock();
                $('#field_'+getRowID).remove();
                swal("Deleted!", "Field is deleted successfully", "success");
              }
            }
          });
        }
      });
    }
  });
});
</script>

<script type="text/javascript">
$('#fieldContainer').sortable({
  cursor: 'move',
  placeholder: "ui-state-highlight",
  helper: fixWidthHelper,
  update: function(e, ui) {
    //console.log(e);
    //console.log(ui);
  },
  delay: 150,
  stop: function(e, ui) {
      if( ui.item.hasClass('btnf') ) {
        $(this).sortable('cancel');
        $(ui.sender).sortable('cancel');
      } else {
        var selectedData = new Array();
        $('#fieldContainer .fd_box').each(function() {
            selectedData.push($(this).attr("id"));
        });
        updateOrder(selectedData);
        //console.log(selectedData);
      }
  }
}).disableSelection();

function fixWidthHelper(e, ui) {
  ui.children().each(function() {
      $(this).width($(this).width());
  });
  return ui;
}

function updateOrder(data) {
  
  var frm_auto_id = $('#set_frm_auto_id').val();      
  $.ajax({
    type : "POST",
    url : "{{ route('ajx_fld_order') }}",
    data : "ids="+data+"&frm_auto_id="+frm_auto_id+"&_token={{ csrf_token() }}",
    beforeSend : function() {
      $('#fieldContainer').block({ 
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
    success : function(st){
      if(st == 'ok'){
        $('#fieldContainer').unblock();
        $("#order_status").show().html("<span class='base-green'>Fields order changed successfully.</span>").fadeOut(5000);
      }
    }
  });

}
</script>


<script type="text/javascript">
$( function() {
  
  $('body').on('click', '#addMoreBTN', function() {
    var addHTML = '';
        addHTML += '<div class="row" id="addM_'+ addM +'">';
          addHTML += '<div class="col-md-6">';
            addHTML += '<div class="form-group">';
              addHTML += '<input type="text" name="options['+ addM +']" class="form-control" placeholder="Add option" required>';
            addHTML += '</div>';
          addHTML += '</div>';
          addHTML += '<div class="col-md-6">';
            addHTML += '<div class="form-group">';
              addHTML += '<a href="javascript:void(0);" class="rmMore" id="rmMore_'+ addM +'"><i class="fa fa-times base-red" aria-hidden="true"></i></a>';
            addHTML += '</div>';
          addHTML += '</div>';
        addHTML += '</div>';
   
    $('.addMoreDIV').append(addHTML);
    addM++;
  });
  $('body').on('click', '.rmMore', function() {
    var rm = $(this).attr('id');
    var rmID = '';
    var rmARR = rm.split('_');
    if( rmARR.length != 0 ) {
      rmID = rmARR[1];
    }
    $('#addM_'+rmID).remove();
  });
});
</script>
@endpush