@if( isset($menuData) )
  @foreach( $menuData as $nv )
      
      <!-- Only Display Default (ENG) Language links -->
      @if($nv->lng_id == '1') 
      <li id="menuItem_{{ $nv->id }}">
        <div class="panel panel-default">
          <div class="panel-heading">
            <a data-toggle="collapse" class="page_details" href="#srtnav{{ $nv->id }}" id="{{ $nv->id }}">
              <h4 class="panel-title"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i> 
                <span id="itemTitle_{{ $nv->id }}">{{ ucfirst( $nv->label_txt ) }}</span></h4>
            </a>
          </div>
          <div id="srtnav{{ $nv->id }}" class="panel-collapse collapse">
            <div class="panel-body" id="pageBody_{{ $nv->id }}">
              
            </div>
            <div class="panel-footer" style="text-align: right;">
              <input type="button" id="savePageDetails_{{ $nv->id }}" class="btn btn-primary btn-sm savePageDetails" value="Save">
              <input type="button" id="delePageDetails_{{ $nv->id }}" class="btn btn-danger btn-sm delePageDetails" value="Delete">
              <input type="hidden" id="ajxFirSts_{{ $nv->id }}" value="0">
            </div>
          </div>
        </div>
        @if( isset($nv->childMenu) && count($nv->childMenu) > 0 )
          <ol>
            @include('dashboard.menu.render_display_pages', ['menuData' => $nv->childMenu])
          </ol>
        @endif
      </li>
      @endif

  @endforeach
@endif