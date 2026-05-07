@if( isset($NavArray) )
  @foreach( $NavArray as $nv )
    <li id="menuItem_{{ $nv['nav_id'] }}">
      <div class="panel panel-default">
        <div class="panel-heading">
          <a data-toggle="collapse" class="page_details" href="#srtnav{{ $nv['nav_id'] }}" id="{{ $nv['nav_id'] }}">
            <h4 class="panel-title"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i> 
              <span id="itemTitle_{{ $nv['nav_id'] }}">{{ ucfirst( $nv['page_title'] ) }}</span></h4>
          </a>
        </div>
        <div id="srtnav{{ $nv['nav_id'] }}" class="panel-collapse collapse">
          <div class="panel-body" id="pageBody_{{ $nv['nav_id'] }}">

          </div>
          <div class="panel-footer" style="text-align: right;">
            <input type="button" id="savePageDetails_{{ $nv['nav_id'] }}" class="btn btn-primary savePageDetails" value="Save">
            <input type="button" id="delePageDetails_{{ $nv['nav_id'] }}" class="btn btn-danger delePageDetails" value="Delete">
            <input type="hidden" id="ajxFirSts_{{ $nv['nav_id'] }}" value="0">
          </div>
        </div>
      </div>
    </li>
  @endforeach
@endif

<!-- SAME WITH render_display_pages layout -->