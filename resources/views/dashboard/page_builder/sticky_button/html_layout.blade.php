<!-- Modal -->
<div id="pgb_stkbutt_modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn btn-xs btn-danger pull-right" data-dismiss="modal">Exit</button>
        <h4 class="modal-title">Sticky Button Content</h4>
      </div>
      <div class="modal-body">
        <form name="pgb_stkbutt_frm" id="pgb_stkbutt_frm" action="{{ route('pgbAddEdt') }}" method="post" enctype="multipart/form-data">
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
              <label>Heading Text</label>
              <input type="text" name="main_title" id="STICKY_BUTT_main_title" class="form-control" placeholder="Heading Text">
            </div>
            <div class="form-group">
              <label>Sub Heading Text</label>
              <input type="text" name="sub_title" id="STICKY_BUTT_sub_title" class="form-control" placeholder="Heading Text">
            </div>
            <div class="form-group">
              <label>Button Text</label>
              <input type="text" name="link_text" id="STICKY_BUTT_link_text" class="form-control" placeholder="Button Text">
            </div>
            <div class="form-group">
              <input type="submit" class="btn btn-primary pgmodal_actionBtn" value="SAVE">
              @if( isset($pageBuilderData) && !empty($pageBuilderData) )
              <input type="hidden" name="insert_id" value="{{ $pageBuilderData->insert_id }}">
              @else
              <input type="hidden" name="insert_id" value="@if( isset( $insert_id ) ){{ $insert_id }}@endif">
              @endif
              <input type="hidden" name="builder_type" value="STICKY_BUTT">
              <input type="hidden" class="this_id" name="this_id" id="STICKY_BUTT_this_id" value="0">
            </div>
          </div>
        </div>
        </form>
      </div>
    </div>
  </div>
</div>