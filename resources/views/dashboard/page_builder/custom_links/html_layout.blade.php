<!-- Media Modal -->
<div class="modal fade" id="customlinksModal" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <form name="clfrmxx" id="clfrmxx" action="{{ route('pgbAddEdt') }}" method="POST">
        {{ csrf_field() }}
      <div class="modal-header">
        <button type="button" class="btn btn-xs btn-danger pull-right" data-dismiss="modal">Exit</button>
        <h4 class="modal-title">
          <strong><i class="fa fa-random" aria-hidden="true"></i> Custom Quick Links</strong>
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
          <div class="col-md-12">
            <div class="form-group">
              <label>Link Heading</label>
              <input type="text" name="main_title" id="custom_link_heading" class="form-control" placeholder="Link Heading">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-5">
            <div class="form-group">
              <label>Link Text</label>
              <input type="text" name="custom_link_text[]" id="fclText" class="form-control custom_link_text" placeholder="Link Text" required="required">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Link</label>
              <input type="url" name="custom_link_slug[]" id="fclSlug" class="form-control custom_link_slug" placeholder="Link" required="required">
            </div>
          </div>
        </div>

        <div id="CUSTOMLINKS_more"></div>

        <div class="row">
          <div class="col-md-6">
            <a href="javascript:void(0);" id="CUSTOMLINKS_more_btn" class="btn btn-sm btn-default">Add More</a>
          </div>
          <div class="col-md-6"></div>
        </div>

      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-primary" id="addSeleCustomLinks" value="Add Custom Links">
        @if( isset($pageBuilderData) && !empty($pageBuilderData) )
        <input type="hidden" name="insert_id" id="CUSTOMLINKS_insert_id" value="{{ $pageBuilderData->insert_id }}">
        @else
        <input type="hidden" name="insert_id" id="CUSTOMLINKS_insert_id" value="@if( isset( $insert_id ) ){{ $insert_id }}@endif">
        @endif
        <input type="hidden" class="this_id" name="this_id" id="CUSTOMLINKS_this_id" value="0">
        <input type="hidden" name="builder_type" value="CUSTOM_LINKS">
      </div>
      </form>

    </div>
  </div>
</div>