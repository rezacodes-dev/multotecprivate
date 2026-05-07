@if( isset($cms) && isset($divID) && count($cms) > 0 )
  @forelse( $cms as $obj )
    
    @php
    $pgInfo = getCmsPageInfo( $obj->id );
    @endphp
    
    @if( isset($pgInfo) && !empty($pgInfo) )
    <li class="nodot libb">
      <input type="checkbox" class="src_ckb" value="{{ $obj->id }}">
      <span>{{ ucfirst( $pgInfo->name ) }}</span>
    </li>
    @endif
  
  @empty
  <label class="arErr-Box base-red">No Pages Found.</label>
  @endforelse
  <div class="ar-bor-top-1">
    <input type="button" class="btn btn-primary btn-sm" id="admBtn_Src" value="Add To Menu" disabled="disabled">
  </div>
@else
<label class="arErr-Box base-red">No Pages Found.</label>
@endif