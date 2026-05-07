<!-- Media Modal -->
<div class="modal fade" id="accordionModal" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <form name="accordionfrmxx" id="accordionfrmxx" action="{{ route('pgbAddEdt') }}" method="POST">
        {{ csrf_field() }}
      <div class="modal-header">
        <button type="button" class="btn btn-xs btn-danger pull-right" data-dismiss="modal">Exit</button>
        <h4 class="modal-title">
          <strong><i class="fa fa-random" aria-hidden="true"></i> Accordion Element</strong>
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
        </div>
        
        <div class="row">
          <div class="col-md-10">
            <div class="form-group">
              <label>Accordion Heading</label>
              <input type="text" name="accordion_heading[]" id="accr0_heading" class="form-control" placeholder="accordion heading" required="required">
            </div>
            <div class="form-group">
              <label>Accordion Body Content</label>
              <textarea name="accordion_body_content[]" id="accr0" class="form-control accordion" placeholder="accordion body content" required="required"></textarea>
            </div>
          </div>
        </div>
        

        <div id="ACCORDION_more"></div>

        <div class="row">
          <div class="col-md-6">
            <a href="javascript:void(0);" id="ACCORDION_more_btn" class="btn btn-sm btn-default">Add More</a>
          </div>
          <div class="col-md-6"></div>
        </div>

      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-primary" id="addAccordion" value="Add Accordion">
        @if( isset($pageBuilderData) && !empty($pageBuilderData) )
        <input type="hidden" name="insert_id" id="ACCORDION_insert_id" value="{{ $pageBuilderData->insert_id }}">
        @else
        <input type="hidden" name="insert_id" id="ACCORDION_insert_id" value="@if( isset( $insert_id ) ){{ $insert_id }}@endif">
        @endif
        <input type="hidden" class="this_id" name="this_id" id="ACCORDION_this_id" value="0">
        <input type="hidden" name="builder_type" value="ACCORDION">
      </div>
      </form>

    </div>
  </div>
</div>