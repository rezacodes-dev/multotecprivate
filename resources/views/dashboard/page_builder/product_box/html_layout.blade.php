<!-- Media Modal -->
<div class="modal fade" id="prdboxModal" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <form name="prdbox_frm" id="prdbox_frm" action="{{ route('pgbAddEdt') }}" method="POST">
        {{ csrf_field() }}
      <div class="modal-header">
        <button type="button" class="btn btn-xs btn-danger pull-right" data-dismiss="modal">Exit</button>
        <h4 class="modal-title">
          <strong><i class="fa fa-random" aria-hidden="true"></i> Product & Product Category Box</strong>
        </h4>
      </div>
      <div class="modal-body modal_min_height">
        
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
          <div class="col-md-3">
            <div class="form-group">
              <label>Select Column :</label>
              <select  name="link_url" id="column_key" class="form-control">
                <option value="">-SELECT-</option>
                <option value="1">For 1 Column</option>
                <option value="2">For 2 Column</option>
                <option value="3">For 3 Column</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Select Product Box Reusable Content :</label>
              <select name="link_text" id="pbox_reu_id" class="form-control" disabled="disabled">
                <option value="">-SELECT-</option>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <label>Link Heading</label>
            <input type="text" name="main_title" id="prdbox_link_heading" class="form-control" placeholder="Enter Link Heading">
          </div>
          <div class="col-md-6">
            <label>Select Link Type:</label>
            <select name="link_type" id="boxlinkType" class="form-control">
              <option value="">-Select Type Of Links-</option>
              <option value="PRODUCT_BOX">Product Links</option>
              <option value="PRODUCT_CAT_BOX">Product Category Links</option>
            </select>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12"><hr/></div>
        </div>
        
        <div class="row">
          <div class="col-md-12" style="max-height: 300px; overflow-y: auto;">
            <input type="text" class="xSRC" placeholder="Search..." style="padding: 5px;">
            <div id="prodbox_linkbox">
              <table class="table table-bordered srcTab" id="prodbox_linkboxTAB">
                <thead>
                  <tr>
                    <th>Items</th>
                    <th>Order</th>
                    <th>Item Links</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td colspan="2">Select link type to get the links</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>  
        
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-primary" id="addPrdBoxSeleLinks" value="Add Selected Products" disabled="disabled">
        @if( isset($pageBuilderData) && !empty($pageBuilderData) )
        <input type="hidden" name="insert_id" id="PrdBoxLINKS_insert_id" value="{{ $pageBuilderData->insert_id }}">
        @else
        <input type="hidden" name="insert_id" id="PrdBoxLINKS_insert_id" value="@if( isset( $insert_id ) ){{ $insert_id }}@endif">
        @endif
        <input type="hidden" class="this_id" name="this_id" id="PrdBoxLINKS_this_id" value="0">
        <input type="hidden" name="builder_type" id="PrdBoxlinks_builder_type">
      </div>
      </form>

    </div>
  </div>
</div>