@if( isset($NavDetail) && isset($NavId) )

@if( $NavDetail->cms_link_id == '0' && $NavDetail->table_id == '0' && $NavDetail->table_type == 'MENU_CUSTOM_LINK' )
<div class="form-group">
  <label>Page Link or URL :(English)</label>
  <input type="text" name="page_url" class="form-control" placeholder="Enter Page URL" value="{{ $NavDetail->custom_link }}">
</div>
@else
  @php
    $url = getMenuLink( $NavDetail->cms_link_id, $NavDetail->table_type, $NavDetail->table_id );
  @endphp
<div class="form-group">
  <label>Page Link or URL : (English)</label>
  <input type="text" name="page_url" class="form-control" placeholder="Enter Page URL" value="{{ $url }}" readonly="readonly">
</div>
@endif

<div class="form-group" style="margin-top: 30px;">
  <input type="checkbox" name="is_link" value="0" @if( $NavDetail->is_link == '0' ) checked="checked" @endif> 
  <span>Link Not Required <small>(If checked, then link not working only for custom links)</small></span>
</div>
<div class="form-group" style="margin-top: 10px;">
  <label>Link Label Text : (English)<em>*</em></label>
  <input type="text" name="label_txt" class="form-control" placeholder="Enter Link Label Text" value="{{ $NavDetail->label_txt }}">
</div>
<div class="form-group" style="margin-top: 10px;">
  <label>Link Label Attribute : (English)<em>*</em></label>
  <input type="text" name="label_attr" class="form-control" placeholder="Enter Link Label Attribute" value="{{ $NavDetail->label_attr }}">
</div>

{{-- Language Module Start --}}

@if( isset($otherLngs) && !empty($otherLngs) )
  <div><hr/></div>
  @foreach($otherLngs as $olng)
    
    @php
      $language_id = $olng->id;
      $lngPageInfo = array();
      $lngNavRow = array();
      $lng_nav_id = 0;
      $lngurl = '';
      $lbtext = '';
      $lbattr = '';

      $tableType = '';
      $tableID = 0;
      $getCmsLinkId = 0;
      $whosId = 0;

      if( !empty($NavDetail) ) {
        
        $tableType = $NavDetail->table_type;
        $whosId = $NavDetail->id;
        
        if($NavDetail->table_type != 'MENU_CUSTOM_LINK') {
          $lngPageInfo = getChildLngPageInfo( $NavDetail->table_type, $NavDetail->table_id, $language_id ); // get child language page
          if( !empty($lngPageInfo) ) {
            $getCmsLinkId = getCmsLinkId($lngPageInfo->slug, $lngPageInfo->id);
            $lngurl = getMenuLink( $getCmsLinkId, $NavDetail->table_type, $lngPageInfo->id );
            $lbtext = $lngPageInfo->name;
            $lbattr = $lngPageInfo->name;
            $tableID = $lngPageInfo->id;
          }
        }

        $lngNavRow = getLngNaviInfo($NavDetail->id, $olng->id); //language navi row details
        if( !empty($lngNavRow) ) {
          $lng_nav_id = $lngNavRow->id;
          $lbtext = $lngNavRow->label_txt;
          $lbattr = $lngNavRow->label_attr;
          $tableID = $lngNavRow->table_id;
          $getCmsLinkId = $lngNavRow->cms_link_id;
        }
      }
    @endphp
    
    <input type="hidden" id="lngnavid_{{ $language_id }}" class="lng_nav_id" value="{{ $lng_nav_id }}"> 
    
    <input type="hidden" id="lngTableType_{{ $language_id }}" value="{{ $tableType }}">
    <input type="hidden" id="lngTableId_{{ $language_id }}" value="{{ $tableID }}">
    <input type="hidden" id="whosId_{{ $language_id }}" value="{{ $whosId }}">
    <input type="hidden" id="cmsLinkId_{{ $language_id }}" value="{{ $getCmsLinkId }}">

    @if( $NavDetail->cms_link_id == '0' && $NavDetail->table_id == '0' && $NavDetail->table_type == 'MENU_CUSTOM_LINK' )
    <div class="form-group">
      <label>Page Link or URL :({{$olng->name}})</label>
      <input type="text" id="lngpgurl_{{ $language_id }}" class="form-control lng_page_url" placeholder="Enter Page URL" value="@if(isset($lngNavRow) && !empty($lngNavRow)){{ $lngNavRow->custom_link }}@endif">
    </div>
    @else
    <div class="form-group">
      <label>Page Link or URL :({{$olng->name}})</label>
      <input type="text" id="lngpgurl_{{ $language_id }}" class="form-control lng_page_url" placeholder="Enter Page URL" value="{{ $lngurl }}" readonly="readonly">
    </div>
    @endif
    
    <div class="form-group" style="margin-top: 10px;">
      <label>Link Label Text : <em>*</em>({{$olng->name}})</label>
      <input type="text" id="lnglabel_{{ $language_id }}" class="form-control lng_label_txt" placeholder="Enter Link Label Text" value="{{ $lbtext }}" @if(empty($lngPageInfo) && $NavDetail->table_type != 'MENU_CUSTOM_LINK') readonly="readonly" @endif>
    </div>
    <div class="form-group" style="margin-top: 10px;">
      <label>Link Label Attribute : <em>*</em>({{$olng->name}})</label>
      <input type="text" id="lngattr_{{ $language_id }}" class="form-control lng_label_attr" placeholder="Enter Link Label Attribute" value="{{ $lbattr }}" @if(empty($lngPageInfo) && $NavDetail->table_type != 'MENU_CUSTOM_LINK') readonly="readonly" @endif>
    </div>
    
    @if(empty($lngPageInfo) && $NavDetail->table_type != 'MENU_CUSTOM_LINK')
      <span class="base-red">{{$olng->name}} - Link/Content Not Created Yet!<br/>
        **<small><i>First create content to active this panel</i></small></span>
    @endif
    <div><hr/></div>
  @endforeach
@endif

{{-- Language Module End --}}


@endif