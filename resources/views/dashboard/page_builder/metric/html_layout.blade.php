<!-- Media Modal -->
<div class="modal fade" id="metricModal" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <form name="metricfrmxx" id="metricfrmxx" action="{{ route('pgbAddEdt') }}" method="POST">
        {{ csrf_field() }}
      <div class="modal-header">
        <button type="button" class="btn btn-xs btn-danger pull-right" data-dismiss="modal">Exit</button>
        <h4 class="modal-title">
          <strong><i class="fa fa-random" aria-hidden="true"></i> Metric Element</strong>
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
          <div class="col-md-6">
            <div class="form-group">
              <label>Text 1</label>
              <input type="text" name="main_title" id="mtext1" class="form-control" placeholder="Enter Text1">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Text 2</label>
              <input type="text" name="sub_title" id="mtext2" class="form-control" placeholder="Enter Text2">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Background Color</label>
              <input type="text" name="link_text" id="mtextbg" class="form-control" placeholder="Background Color Hexa Code, Ex:#CCF993">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Text Color</label>
              <input type="text" name="link_url" id="mtextco" class="form-control" placeholder="Text Color Hexa Code, Ex:#FFFFFF">
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label>Main Content</label>
              <textarea name="main_content" id="mtcont" class="form-control" placeholder="Content.."></textarea>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Metric Type</label>
              <select name="sub_content" class="form-control" id="mtyp">
                <option value="METRIC_LEFT">METRIC ELEMENT - LEFT POSITION</option>
                <option value="METRIC_RIGHT">METRIC ELEMENT - RIGHT POSITION</option>
              </select>
            </div>
          </div>
        </div>
        
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-primary" id="addSeleCustomLinks" value="Add Custom Links">
        @if( isset($pageBuilderData) && !empty($pageBuilderData) )
        <input type="hidden" name="insert_id" id="METRIC_insert_id" value="{{ $pageBuilderData->insert_id }}">
        @else
        <input type="hidden" name="insert_id" id="METRIC_insert_id" value="@if( isset( $insert_id ) ){{ $insert_id }}@endif">
        @endif
        <input type="hidden" class="this_id" name="this_id" id="METRIC_this_id" value="0">
        <input type="hidden" name="builder_type" value="METRIC">
      </div>
      </form>

    </div>
  </div>
</div>