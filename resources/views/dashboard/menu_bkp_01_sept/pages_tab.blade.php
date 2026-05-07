<div class="panel-group" id="pages_accordion" style="margin-top: 10px;">
@if( isset($contentType) )
  @foreach($contentType as $v)
  <div class="panel panel-default">
    <div class="panel-heading">
      <a data-toggle="collapse" class="pgHead" data-parent="#pages_accordion" id="{{ $v->id }}" data="cms" href="#collapseCMS{{ $v->id }}">
        <h4 class="panel-title">{{ ucwords($v->name) }}</h4>
      </a>
    </div>
    <div id="collapseCMS{{ $v->id }}" class="panel-collapse collapse">
      <div class="panel-body" id="cms_{{ $v->id }}">
      </div>
    </div>
    <input type="hidden" id="ajxck_cms_{{ $v->id }}" value="0">
  </div>
  @endforeach
@endif
  <!-- PRODUCT CATEGORY -->
  <div class="panel panel-default">
    <div class="panel-heading">
      <a data-toggle="collapse" class="pgHead" data-parent="#pages_accordion" id="0" data="productCat" href="#collapsePROCAT">
        <h4 class="panel-title">Product Category</h4>
      </a>
    </div>
    <div id="collapsePROCAT" class="panel-collapse collapse">
      <div class="panel-body" id="productCat_0">
      </div>
    </div>
    <input type="hidden" id="ajxck_productCat_0" value="0">
  </div>

  <!-- PRODUCT -->
  <div class="panel panel-default">
    <div class="panel-heading">
      <a data-toggle="collapse" class="pgHead" data-parent="#pages_accordion" id="0" data="product" href="#collapsePRODUCT">
        <h4 class="panel-title">Products</h4>
      </a>
    </div>
    <div id="collapsePRODUCT" class="panel-collapse collapse">
      <div class="panel-body" id="product_0">
      </div>
    </div>
    <input type="hidden" id="ajxck_product_0" value="0">
  </div>

  <!-- ARTICLE CATEGORY -->
  <!--div class="panel panel-default">
    <div class="panel-heading">
      <a data-toggle="collapse" class="pgHead" data-parent="#pages_accordion" id="0" data="articleCat" href="#collapseARTCAT">
        <h4 class="panel-title">Article Category</h4>
      </a>
    </div>
    <div id="collapseARTCAT" class="panel-collapse collapse">
      <div class="panel-body" id="articleCat_0">
      </div>
    </div>
    <input type="hidden" id="ajxck_articleCat_0" value="0">
  </div-->

  <!-- ARTICLE -->
  <div class="panel panel-default">
    <div class="panel-heading">
      <a data-toggle="collapse" class="pgHead" data-parent="#pages_accordion" id="0" data="article" href="#collapseARTICLE">
        <h4 class="panel-title">Articles</h4>
      </a>
    </div>
    <div id="collapseARTICLE" class="panel-collapse collapse">
      <div class="panel-body" id="article_0">
      </div>
    </div>
    <input type="hidden" id="ajxck_article_0" value="0">
  </div>

  <!-- INDUSTRY -->
  <div class="panel panel-default">
    <div class="panel-heading">
      <a data-toggle="collapse" class="pgHead" data-parent="#pages_accordion" id="0" data="industry" href="#collapseINDUSTRY">
        <h4 class="panel-title">Industry</h4>
      </a>
    </div>
    <div id="collapseINDUSTRY" class="panel-collapse collapse">
      <div class="panel-body" id="industry_0">
      </div>
    </div>
    <input type="hidden" id="ajxck_industry_0" value="0">
  </div>

  <!-- INDUSTRY FLOWSHEET CATEGORY-->
  <div class="panel panel-default">
    <div class="panel-heading">
      <a data-toggle="collapse" class="pgHead" data-parent="#pages_accordion" id="0" data="industryFSC" href="#collapseINDUSTRY_FSC">
        <h4 class="panel-title">Industry Flowsheet Category</h4>
      </a>
    </div>
    <div id="collapseINDUSTRY_FSC" class="panel-collapse collapse">
      <div class="panel-body" id="industryFSC_0">
      </div>
    </div>
    <input type="hidden" id="ajxck_industryFSC_0" value="0">
  </div>

  <!-- INDUSTRY FLOWSHEET -->
  <div class="panel panel-default">
    <div class="panel-heading">
      <a data-toggle="collapse" class="pgHead" data-parent="#pages_accordion" id="0" data="industryFS" href="#collapseINDUSTRY_FS">
        <h4 class="panel-title">Industry Flowsheet</h4>
      </a>
    </div>
    <div id="collapseINDUSTRY_FS" class="panel-collapse collapse">
      <div class="panel-body" id="industryFS_0">
      </div>
    </div>
    <input type="hidden" id="ajxck_industryFS_0" value="0">
  </div>

  <!-- DISTRIBUTOR -->
  <div class="panel panel-default">
    <div class="panel-heading">
      <a data-toggle="collapse" class="pgHead" data-parent="#pages_accordion" id="0" data="distributor" href="#collapseDISTRIBUTOR">
        <h4 class="panel-title">Distributors</h4>
      </a>
    </div>
    <div id="collapseDISTRIBUTOR" class="panel-collapse collapse">
      <div class="panel-body" id="distributor_0">
      </div>
    </div>
    <input type="hidden" id="ajxck_distributor_0" value="0">
  </div>

  <!-- DISTRIBUTOR CATEGORY-->
  <div class="panel panel-default">
    <div class="panel-heading">
      <a data-toggle="collapse" class="pgHead" data-parent="#pages_accordion" id="0" data="distrbCat" href="#collapseDISTRBCAT">
        <h4 class="panel-title">Distributor Category</h4>
      </a>
    </div>
    <div id="collapseDISTRBCAT" class="panel-collapse collapse">
      <div class="panel-body" id="distrbCat_0">
      </div>
    </div>
    <input type="hidden" id="ajxck_distrbCat_0" value="0">
  </div>

  <!-- DISTRIBUTOR CONTENT-->
  <div class="panel panel-default">
    <div class="panel-heading">
      <a data-toggle="collapse" class="pgHead" data-parent="#pages_accordion" id="0" data="distrbCont" href="#collapseDISTRBCONT">
        <h4 class="panel-title">Distributor Contents</h4>
      </a>
    </div>
    <div id="collapseDISTRBCONT" class="panel-collapse collapse">
      <div class="panel-body" id="distrbCont_0">
      </div>
    </div>
    <input type="hidden" id="ajxck_distrbCont_0" value="0">
  </div>

  <!-- CAREER-->
  <!--div class="panel panel-default">
    <div class="panel-heading">
      <a data-toggle="collapse" class="pgHead" data-parent="#pages_accordion" id="0" data="career" href="#collapseCAREER">
        <h4 class="panel-title">Career Contents</h4>
      </a>
    </div>
    <div id="collapseCAREER" class="panel-collapse collapse">
      <div class="panel-body" id="career_0">
      </div>
    </div>
    <input type="hidden" id="ajxck_career_0" value="0">
  </div-->

  <!-- PROFILE-->
  <div class="panel panel-default">
    <div class="panel-heading">
      <a data-toggle="collapse" class="pgHead" data-parent="#pages_accordion" id="0" data="profile" href="#collapsePROFILE">
        <h4 class="panel-title">Profiles</h4>
      </a>
    </div>
    <div id="collapsePROFILE" class="panel-collapse collapse">
      <div class="panel-body" id="profile_0">
      </div>
    </div>
    <input type="hidden" id="ajxck_profile_0" value="0">
  </div>

  <!-- PROFILE CATEGORY-->
  <div class="panel panel-default">
    <div class="panel-heading">
      <a data-toggle="collapse" class="pgHead" data-parent="#pages_accordion" id="0" data="profileCat" href="#collapsePROFILECAT">
        <h4 class="panel-title">Profile Categories</h4>
      </a>
    </div>
    <div id="collapsePROFILECAT" class="panel-collapse collapse">
      <div class="panel-body" id="profileCat_0">
      </div>
    </div>
    <input type="hidden" id="ajxck_profileCat_0" value="0">
  </div>

  <!-- TECH RESOURCE-->
  <!--div class="panel panel-default">
    <div class="panel-heading">
      <a data-toggle="collapse" class="pgHead" data-parent="#pages_accordion" id="0" data="techres" href="#collapseTECHRES">
        <h4 class="panel-title">Technical Resources</h4>
      </a>
    </div>
    <div id="collapseTECHRES" class="panel-collapse collapse">
      <div class="panel-body" id="techres_0">
      </div>
    </div>
    <input type="hidden" id="ajxck_techres_0" value="0">
  </div-->

  <!-- EVENT -->
  <div class="panel panel-default">
    <div class="panel-heading">
      <a data-toggle="collapse" class="pgHead" data-parent="#pages_accordion" id="0" data="event" href="#collapseEVENT">
        <h4 class="panel-title">Events</h4>
      </a>
    </div>
    <div id="collapseEVENT" class="panel-collapse collapse">
      <div class="panel-body" id="event_0">
      </div>
    </div>
    <input type="hidden" id="ajxck_event_0" value="0">
  </div>

  <!-- EVENT CATEGORY-->
  <!--div class="panel panel-default">
    <div class="panel-heading">
      <a data-toggle="collapse" class="pgHead" data-parent="#pages_accordion" id="0" data="eventCat" href="#collapseEVENTCAT">
        <h4 class="panel-title">Event Categories</h4>
      </a>
    </div>
    <div id="collapseEVENTCAT" class="panel-collapse collapse">
      <div class="panel-body" id="eventCat_0">
      </div>
    </div>
    <input type="hidden" id="ajxck_eventCat_0" value="0">
  </div-->

  <!-- VIDEO CATEGORY-->
  <!--div class="panel panel-default">
    <div class="panel-heading">
      <a data-toggle="collapse" class="pgHead" data-parent="#pages_accordion" id="0" data="vidCat" href="#collapseVIDCAT">
        <h4 class="panel-title">Video Categories</h4>
      </a>
    </div>
    <div id="collapseVIDCAT" class="panel-collapse collapse">
      <div class="panel-body" id="vidCat_0">
      </div>
    </div>
    <input type="hidden" id="ajxck_vidCat_0" value="0">
  </div-->

  <!-- VIDEOS -->
  <!--div class="panel panel-default">
    <div class="panel-heading">
      <a data-toggle="collapse" class="pgHead" data-parent="#pages_accordion" id="0" data="video" href="#collapseVIDEO">
        <h4 class="panel-title">Videos</h4>
      </a>
    </div>
    <div id="collapseVIDEO" class="panel-collapse collapse">
      <div class="panel-body" id="video_0">
      </div>
    </div>
    <input type="hidden" id="ajxck_video_0" value="0">
  </div-->
</div>