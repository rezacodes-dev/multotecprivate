<!-- Modal -->
<div id="pgb_ext_cont_modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn btn-xs btn-danger pull-right" data-dismiss="modal">Exit</button>
        <h4 class="modal-title">Extra Content</h4>
      </div>
      <div class="modal-body">
        <form name="pgb_ext_cont_frm" id="pgb_ext_cont_frm" action="{{ route('pgbAddEdt') }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>Device :</label>
              <select name="device" class="form-control" style="width: 200px;">
                <option value="3">For Both</option>
                <option value="1">For Desktop</option>
                <option value="2">For Mobile</option>
              </select>
            </div>
            <div class="form-group">
              <textarea class="form-control" id="pgb_ext_cont_edt" name="main_content" data-error-container="#pgb_cont_edt-error"></textarea>
              <div id="pgb_cont_edt-error"></div>
            </div>
            <div class="form-group">
              <input type="submit" class="btn btn-primary pgmodal_actionBtn" value="SAVE">
              @if( isset($pageBuilderData) && !empty($pageBuilderData) )
              <input type="hidden" name="insert_id" value="{{ $pageBuilderData->insert_id }}">
              @else
              <input type="hidden" name="insert_id" value="@if( isset( $insert_id ) ){{ $insert_id }}@endif">
              @endif
              <input type="hidden" name="builder_type" value="EXTRA_CONT">
              <input type="hidden" class="this_id" name="this_id" id="EXTRA_CONT_this_id" value="0">
            </div>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>