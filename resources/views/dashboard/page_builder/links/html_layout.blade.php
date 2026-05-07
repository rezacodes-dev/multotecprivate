<!-- Media Modal -->
<div class="modal fade" id="linksModal" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      
      <form name="frmxx" id="frmxx" action="{{ route('pgbAddEdt') }}" method="POST">
        {{ csrf_field() }}
      <div class="modal-header">
        <button type="button" class="btn btn-xs btn-danger pull-right" data-dismiss="modal">Exit</button>
        <h4 class="modal-title">
          <strong><i class="fa fa-random" aria-hidden="true"></i> Quick Links</strong>
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

        @php
        $contentTypes = getContentTypes();
        @endphp
        <div class="row">
          <div class="col-md-6">
            <label>Select Links Type</label>
            <select id="linkType" class="form-control">
              <option value="0">-SELECT LINK TYPE-</option>
              <option value="PRODUCT_LINKS">Product Links</option>
              <option value="PRODUCT_CAT_LINKS">Product Category Links</option>
              <option value="NEWS_LINKS">News Links</option>
              <option value="PEOPLE_LINKS">People Links</option>
              @if( isset($contentTypes) && !empty($contentTypes) && count($contentTypes) > 0 )
                @foreach($contentTypes as $ty)
                <option value="CONTENT_LINKS-{{ $ty->id }}">{{ $ty->name }}</option>
                @endforeach
              @endif
              <option value="DISTRIBUTOR">Distributor Links</option>
              <option value="DISTRIBUTOR_PAGE">Distributor Content Links</option>
            </select>
          </div>
          <div class="col-md-6">
            <label>Link Heading</label>
            <input type="text" name="main_title" id="link_heading" class="form-control" placeholder="Enter Link Heading">
          </div>
        </div>

        <div class="row">
          <div class="col-md-12"><hr/></div>
        </div>
        
        <div class="row">
          <div class="col-md-12" style="max-height: 300px; overflow-y: auto;">
            <input type="text" class="xSRC" placeholder="Search..." style="padding: 5px;">
            <div id="linkbox">
              <table class="table table-bordered srcTab" id="linkboxTAB">
                <thead>
                  <tr>
                    <th>Items</th>
                    <th>Order</th>
                    <th>Links</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>  
        
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-primary" id="addSeleLinks" value="Add Selected Links" >
        @if( isset($pageBuilderData) && !empty($pageBuilderData) )
        <input type="hidden" name="insert_id" id="LINKS_insert_id" value="{{ $pageBuilderData->insert_id }}">
        @else
        <input type="hidden" name="insert_id" id="LINKS_insert_id" value="@if( isset( $insert_id ) ){{ $insert_id }}@endif">
        @endif
        <input type="hidden" class="this_id" name="this_id" id="LINKS_this_id" value="0">
        <input type="hidden" name="builder_type" id="links_builder_type">
      </div>
      </form>

    </div>
  </div>
</div>