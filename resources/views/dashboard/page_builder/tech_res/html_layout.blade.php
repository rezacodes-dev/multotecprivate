<!-- Media Modal -->
<div class="modal fade" id="tecRes_Modal" role="dialog"> <!-- brochureModal -->
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="brochureModalClose btn btn-xs btn-danger pull-right" data-dismiss="modal">Exit</button>
        <h4 class="modal-title">
          <strong><i class="fa fa-random" aria-hidden="true"></i> Extra Media - Add Technical Resource Button</strong>
        </h4>
      </div>
      <div class="modal-body modal_min_height">
        <form name="tecRes_frm" id="tecRes_frm" action="{{ route('pgbAddEdt') }}" method="post" enctype="multipart/form-data">
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
          <div class="col-md-6">
            <div class="form-group">
              <label>Select File Category :</label>
              <select name="main_content" class="form-control" id="techRes_Tab">
                <option value="">-Select Display Tab Section-</option>
                <option value="PRODUCT">PRODUCT Tab</option>
                <option value="MULTOTEC_GROUP">MULTOTEC GROUP Tab</option>
                <option value="INDUSTRY_INSIGHTS">INDUSTRY INSIGHTS Tab</option>
              </select>
            </div>
          </div>
          <div class="col-md-6"></div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Select File Subcategory :</label>
              <select name="sub_content" class="form-control" id="techRes_ProCat">
                <option value="">-SELECT SUBCATEGORY-</option>
              </select>
            </div>
          </div>
          <div class="col-md-6"></div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <input type="submit" class="btn btn-primary pgmodal_actionBtn" value="SAVE">
              @if( isset($pageBuilderData) && !empty($pageBuilderData) )
              <input type="hidden" name="insert_id" id="TECHRES_BUTT_insert_id" value="{{ $pageBuilderData->insert_id }}">
              @else
              <input type="hidden" name="insert_id" id="TECHRES_BUTT_insert_id" value="@if( isset( $insert_id ) ){{ $insert_id }}@endif">
              @endif
              <input type="hidden" class="this_id" name="this_id" id="TECHRES_BUTT_this_id" value="0">
              <input type="hidden" name="builder_type" value="TECHRES_BUTT">
            </div>
          </div>
        </div>
      </form>


      </div>
    </div>
  </div>
</div>