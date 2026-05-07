<div style="margin-left: 5px; margin-top: 10px;">
  <li class="nodot libb">
    <input type="checkbox" class="list_pgs" value="{{ route('front_allFileCat', array('lng' => 'en')) }}#Files Categories">
    <span>Files Category List Page</span>
  </li>
  <li class="nodot libb">
    <input type="checkbox" class="list_pgs" value="{{ route('img_gal_cats', array('lng' => 'en')) }}#Image Categories">
    <span>Images Category List Page</span>
  </li>
  <li class="nodot libb">
    <input type="checkbox" class="list_pgs" value="{{ route('vid_gal_cats', array('lng' => 'en')) }}#Video Categories">
    <span>Videos Category List Page</span>
  </li>
  <li class="nodot libb">
    <input type="checkbox" class="list_pgs" value="{{ route('viewTechResLst', array('lng' => 'en')) }}#Technical Resources">
    <span>Technical Resource List Page</span>
  </li>
  <li class="nodot libb">
    <input type="checkbox" class="list_pgs" value="{{ route('newsArticleList', array('lng' => 'en')) }}#All News & Articles">
    <span>News, Articles & Events List Page</span>
  </li>
  {{--<li class="nodot libb">
    <input type="checkbox" class="list_pgs" value="{{ route('eventLists', array('lng' => 'en')) }}#All Events">
    <span>Events List Page</span>
  </li>--}}
  <li class="nodot libb">
    <input type="checkbox" class="list_pgs" value="{{ route('profLists', array('lng' => 'en')) }}#All Profiles">
    <span>People Profile List Page</span>
  </li>
  <li class="nodot libb">
    <input type="checkbox" class="list_pgs" value="{{ route('front.distrbMap', array('lng' => 'en')) }}#Global Network">
    <span>Distributor Global Network Page</span>
  </li>
  <hr/>
  <input type="button" id="add_listpage_btn" class="btn btn-primary pull-right" value="Add To Menu" disabled="disabled">
</div>