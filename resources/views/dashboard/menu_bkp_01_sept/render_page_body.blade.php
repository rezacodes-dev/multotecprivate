@if( isset($NavDetail) && isset($NavId) )

@if( $NavDetail->cms_link_id == '0' && $NavDetail->table_id == '0' && $NavDetail->table_type == 'MENU_CUSTOM_LINK' )
<div class="form-group">
  <label>Page Link or URL :(English)</label>
  <input type="text" name="page_url" class="form-control" placeholder="Enter Page URL" value="{{ $NavDetail->custom_link }}">
</div>
@else
  @php
    $url = '';
    if( isset($PageInfo) && !empty($PageInfo) ) {
      $lngcode = getLngCode($PageInfo->language_id);
      if( $lngcode != '' && $PageInfo->slug != '' ) {
        $url = url($lngcode.'/'.$PageInfo->slug);
      }
    }
  @endphp
<div class="form-group">
  <label>Page Link or URL : (English)</label>
  <input type="text" name="page_url" class="form-control" placeholder="Enter Page URL" value="{{ $url }}" readonly="readonly">
</div>
@endif

<div class="form-group" style="margin-top: 30px;">
  <input type="checkbox" name="is_link" value="0" @if( $NavDetail->is_link == '0' ) checked="checked" @endif> 
  <span>Link Not Required</span>
</div>
<div class="form-group" style="margin-top: 10px;">
  <label>Link Label Text : (English)<em>*</em></label>
  <input type="text" name="label_txt" class="form-control" placeholder="Enter Link Label Text" value="{{ $NavDetail->label_txt }}">
</div>
<div class="form-group" style="margin-top: 10px;">
  <label>Link Label Attribute : (English)<em>*</em></label>
  <input type="text" name="label_attr" class="form-control" placeholder="Enter Link Label Attribute" value="{{ $NavDetail->label_attr }}">
</div>

@if( isset($otherLngs) && !empty($otherLngs) )
  <div><hr/></div>
  @foreach($otherLngs as $olng)
    
    @php
      $language_id = $olng->id;
      $lngNavPageInfo = array();

      $lngNavDetail = getLngNaviInfo($NavDetail->id, $olng->id); //language navi row details
      if( isset($lngNavDetail) && !empty($lngNavDetail) && $NavDetail->cms_link_id != '0' && $NavDetail->table_id != '0' ) {
        $lngNavPageInfo = getCmsPageInfo($lngNavDetail->cms_link_id); //language page details
      }

      $lng_nav_id = 0;
      if(isset($lngNavDetail) && !empty($lngNavDetail)) {
        $lng_nav_id = $lngNavDetail->id;
      }

      $lngurl = '';
      if( isset($lngNavPageInfo) && !empty($lngNavPageInfo) ) {
        $lngcode = getLngCode($lngNavPageInfo->language_id);
        if( $lngcode != '' && $lngNavPageInfo->slug != '' ) {
          $lngurl = url($lngcode.'/'.$lngNavPageInfo->slug);
        }
      }
    @endphp

    
    <input type="hidden" id="lngnavid_{{ $language_id }}" class="lng_nav_id" value="{{ $lng_nav_id }}"> 
    @if( $NavDetail->cms_link_id == '0' && $NavDetail->table_id == '0' && $NavDetail->table_type == 'MENU_CUSTOM_LINK' )
    <div class="form-group">
      <label>Page Link or URL :({{$olng->name}})</label>
      <input type="text" id="lngpgurl_{{ $language_id }}" class="form-control lng_page_url" placeholder="Enter Page URL" value="@if(isset($lngNavDetail) && !empty($lngNavDetail)){{ $lngNavDetail->custom_link }}@endif">
    </div>
    @else
    <div class="form-group">
      <label>Page Link or URL :({{$olng->name}})</label>
      <input type="text" id="lngpgurl_{{ $language_id }}" class="form-control lng_page_url" placeholder="Enter Page URL" value="{{ $lngurl }}" readonly="readonly">
    </div>
    @endif
    <div class="form-group" style="margin-top: 10px;">
      <label>Link Label Text : <em>*</em>({{$olng->name}})</label>
      <input type="text" id="lnglabel_{{ $language_id }}" class="form-control lng_label_txt" placeholder="Enter Link Label Text" value="@if(isset($lngNavDetail) && !empty($lngNavDetail)){{ $lngNavDetail->label_txt }}@endif" @if($lngurl == '' && $NavDetail->cms_link_id != '0' && $NavDetail->table_id != '0') readonly="readonly" @endif>
    </div>
    <div class="form-group" style="margin-top: 10px;">
      <label>Link Label Attribute : <em>*</em>({{$olng->name}})</label>
      <input type="text" id="lngattr_{{ $language_id }}" class="form-control lng_label_attr" placeholder="Enter Link Label Attribute" value="@if(isset($lngNavDetail) && !empty($lngNavDetail)){{ $lngNavDetail->label_attr }}@endif" @if($lngurl == '' && $NavDetail->cms_link_id != '0' && $NavDetail->table_id != '0') readonly="readonly" @endif>
    </div>
    @if($lngurl == '' && $NavDetail->cms_link_id != '0' && $NavDetail->table_id != '0')
      <span class="base-red">{{$olng->name}} - Link/Content Not Created Yet!<br/>
        **<small><i>First create content to active this panel</i></small></span>
    @endif
    <div><hr/></div>
    

  @endforeach
@endif


@endif