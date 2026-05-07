<!-- Modal -->
<div id="pgb_eform_modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn btn-xs btn-danger pull-right" data-dismiss="modal">Exit</button>
        <h4 class="modal-title">Enquiry Forms</h4>
      </div>
      <div class="modal-body">
        <form name="pgb_eform_frm" id="pgb_eform_frm" action="{{ route('pgbAddEdt') }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label>Device :</label>
              <select name="device" class="form-control">
                <option value="3">For Both</option>
                <option value="1">For Desktop</option>
                <option value="2">For Mobile</option>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>Form Title</label>
              <input type="text" name="main_title" id="EFORM_main_title" class="form-control" placeholder="Form Heading">
            </div>
            <div class="form-group">
              <label>Form Subtitle</label>
              <input type="text" name="sub_title" id="EFORM_sub_title" class="form-control" placeholder="Form Sub Heading">
            </div>
            <div class="form-group">
              <label>Choose Form Short Code</label> <span id="EFORM_ajx_status"></span>
              <select name="main_content" id="EFORM_main_content" class="form-control select2" style="width: 100%;"></select>
            </div>
            <div class="form-group">
              <input type="submit" class="btn btn-primary pgmodal_actionBtn" value="SAVE">
              @if( isset($pageBuilderData) && !empty($pageBuilderData) )
              <input type="hidden" name="insert_id" value="{{ $pageBuilderData->insert_id }}">
              @else
              <input type="hidden" name="insert_id" value="@if( isset( $insert_id ) ){{ $insert_id }}@endif">
              @endif
              <input type="hidden" name="builder_type" value="EFORM">
              <input type="hidden" class="this_id" name="this_id" id="EFORM_this_id" value="0">
            </div>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>