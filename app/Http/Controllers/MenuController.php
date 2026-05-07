<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu\MenuMaster;
use App\Models\Content\ContentType;
use App\Models\Menu\NaviMaster;
use App\Models\CmsLinks;

class MenuController extends Controller
{
    
    public function index() {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'management';
    	$DataBag['parentMenu'] = 'menuMan';
    	$DataBag['childMenu'] = 'naviMenu';
    	$DataBag['allMenus'] = MenuMaster::where('status', '=', '1')->orderBy('name', 'asc')->get();
    	$DataBag['contentType'] = ContentType::where('status', '=', '1')->orderBy('name', 'asc')->get();

        $otherLngs = \App\Models\Languages::where('status', '=', '1')->where('is_default', '=', '0')->get();

        $DataBag['otherLngs'] = $otherLngs;


    	return view('dashboard.menu.index', $DataBag);
    }

    public function allMenus() {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'management';
    	$DataBag['parentMenu'] = 'menuMan';
    	$DataBag['childMenu'] = 'allMenu';
    	$DataBag['allMenus'] = MenuMaster::where('status', '!=', '3')->orderBy('created_at', 'desc')->get();

    	return view('dashboard.menu.menus', $DataBag);
    }

    public function getPages(Request $request) {

    	$DataBag = array();
    	$getID = trim( $request->input('getID') );
    	$getData = trim( $request->input('getData') );

    	if( $getID == 0 ) {

    		if( $getData == 'productCat' ) {

    			$data = \App\Models\Product\ProductCategories::where('status', '=', '1')->where('parent_language_id', '=', '0')
                ->select('id', 'name', 'slug')->orderBy('id', 'desc')->take(75)->get();
    			$DataBag['pages'] = $data;
                $DataBag['table_type'] = 'PRODUCT_CATEGORY';
    		}

    		if( $getData == 'product' ) {

    			$data = \App\Models\Product\Products::where('status', '=', '1')->where('parent_language_id', '=', '0')
                ->select('id', 'name', 'slug')->orderBy('id', 'desc')->take(75)->get();
    			$DataBag['pages'] = $data;
                $DataBag['table_type'] = 'PRODUCT';
    		}

    		if( $getData == 'article' ) {

    			$data = \App\Models\Article\Articles::where('status', '=', '1')->where('parent_language_id', '=', '0')
                ->select('id', 'name', 'slug')->orderBy('id', 'desc')->take(75)->get();
    			$DataBag['pages'] = $data;
                $DataBag['table_type'] = 'ARTICLE';
    		}

    		if( $getData == 'industry' ) {

    			$data = \App\Models\Industry\Industries::where('status', '=', '1')->where('parent_language_id', '=', '0')
                ->select('id', 'name', 'slug')->orderBy('id', 'desc')->take(75)->get();
    			$DataBag['pages'] = $data;
                $DataBag['table_type'] = 'INDUSTRY';
    		}

    		if( $getData == 'industryFSC' ) {

    			$data = \App\Models\IndustryFlowsheet\FlowsheetCategories::where('status', '=', '1')->where('parent_language_id', '=', '0')
                ->select('id', 'name', 'slug')->orderBy('id', 'desc')->take(75)->get();
    			$DataBag['pages'] = $data;
                $DataBag['table_type'] = 'FLOWSHEET_CATEGORY';
    		}

    		if( $getData == 'industryFS' ) {

    			$data = \App\Models\IndustryFlowsheet\Flowsheet::where('status', '=', '1')->where('parent_language_id', '=', '0')
                ->select('id', 'name', 'slug')->orderBy('id', 'desc')->take(75)->get();
    			$DataBag['pages'] = $data;
                $DataBag['table_type'] = 'FLOWSHEET';
    		}

    		if( $getData == 'distributor' ) {

    			$data = \App\Models\Distributor\Distributor::where('status', '=', '1')->where('parent_language_id', '=', '0')
                ->select('id', 'name', 'slug')->orderBy('id', 'desc')->take(75)->get();
    			$DataBag['pages'] = $data;
                $DataBag['table_type'] = 'DISTRIBUTOR';
    		}

    		if( $getData == 'distrbCat' ) {

    			$data = \App\Models\Distributor\DistributorCategories::where('status', '=', '1')->where('parent_language_id', '=', '0')
                ->select('id', 'name', 'slug')->orderBy('id', 'desc')->take(75)->get();
    			$DataBag['pages'] = $data;
                $DataBag['table_type'] = 'DISTRIBUTOR_CATEGORY';
    		}

    		if( $getData == 'distrbCont' ) {

    			$data = \App\Models\Distributor\DistributorContents::where('status', '=', '1')->where('parent_language_id', '=', '0')
                ->select('id', 'name', 'slug')->orderBy('id', 'desc')->take(75)->get();
    			$DataBag['pages'] = $data;
                $DataBag['table_type'] = 'DISTRIBUTOR_CONTENT';
    		}

    		if( $getData == 'profile' ) {

    			$data = \App\Models\PeoplesProfile\PeoplesProfile::where('status', '=', '1')->where('parent_language_id', '=', '0')
                ->select('id', 'name', 'slug')->orderBy('id', 'desc')->take(75)->get();
    			$DataBag['pages'] = $data;
                $DataBag['table_type'] = 'PEOPLE_PROFILE';
    		}

    		if( $getData == 'event' ) {

    			$data = \App\Models\Events::where('status', '=', '1')->where('parent_language_id', '=', '0')
                ->select('id', 'name', 'slug')->orderBy('id', 'desc')->take(75)->get();
    			$DataBag['pages'] = $data;
                $DataBag['table_type'] = 'EVENT';
    		}
    	}

    	if( $getID > 0 ) {

    		if( $getData == 'cms' ) {

    			$data = \App\Models\Content\Contents::where('status', '=', '1')->where('content_type_id', '=', $getID)
    			->where('parent_language_id', '=', '0')->select('id', 'name', 'slug')->orderBy('id', 'desc')->take(75)->get();
    			$DataBag['pages'] = $data;
                $DataBag['table_type'] = 'DYNA_CONTENT';
    		}
    	}

        $DataBag['divID'] = md5(microtime(TRUE));
        
    	$view = view('dashboard.menu.render_pages', $DataBag)->render();

        return response()->json(['html'=>$view, 'status' => 'ok']);
    }

    public function setPages(Request $request) {

        $cms_ids = $request->input('cms_ids');
        $menu_id = trim( $request->input('menu_id') );
        
        $curr_navid = 0;
        
        $NavArray = array();
        $DataBag = array();

        if( is_array($cms_ids) && !empty($cms_ids) ) {
            foreach( $cms_ids as $cid ) {

                $CmsLinks = CmsLinks::find( $cid );
                $table_type = $CmsLinks->table_type;
                $table_id = $CmsLinks->table_id;

                $page_info = getCmsPageInfo( $cid );
                if( isset($page_info) && !empty($page_info) ) {

                    $NaviMaster = new NaviMaster;
                    $NaviMaster->menu_id = $menu_id;
                    $NaviMaster->cms_link_id = $cid;
                    $NaviMaster->table_type = $table_type;
                    $NaviMaster->table_id = $table_id;
                    $NaviMaster->label_txt = $page_info->name;
                    $NaviMaster->label_attr = $page_info->name;
                    $NaviMaster->lng_id = $page_info->language_id;
                    $NaviMaster->is_link = '1';
                    if( $NaviMaster->save() ) {
                        $curr_navid = $NaviMaster->id;
                        $arr = array();
                        $arr['nav_id'] = $NaviMaster->id;
                        $arr['page_title'] = $page_info->name;
                        array_push( $NavArray, $arr );

                        /*$lngpage_info = getChildLngPageInfo($table_type, $table_id);
                        if( isset($lngpage_info) && !empty($lngpage_info) ) {

                            foreach($lngpage_info as $lpinfo) {
                                $NaviMaster2 = new NaviMaster;
                                $NaviMaster2->menu_id = $menu_id;
                                $NaviMaster2->cms_link_id = getCmsLinkId($lpinfo->slug, $lpinfo->id);
                                $NaviMaster2->table_type = $table_type;
                                $NaviMaster2->table_id = $lpinfo->id;
                                $NaviMaster2->label_txt = $lpinfo->name;
                                $NaviMaster2->label_attr = $lpinfo->name;
                                $NaviMaster2->lng_id = $lpinfo->language_id;
                                $NaviMaster2->whos_id = $curr_navid;
                                $NaviMaster2->is_link = '1';
                                $NaviMaster2->save();
                            }
                        }*/
                    }
                }
            }
        }

        $DataBag['NavArray'] = $NavArray;

        $view = view('dashboard.menu.render_set_pages', $DataBag)->render();

        return response()->json(['html'=>$view, 'status' => 'ok']);
    }


    public function saveMenu(Request $request) {

        $rtn = 0;

        $arr = json_decode( $request->input('arraied') );
        $menu_id = trim( $request->input('menu_id') );
        if( !empty($arr) ) {
        
            array_shift($arr);
            $i = 0;
            foreach( $arr as $v ) {

                $NaviMaster = NaviMaster::find( $v->id );
                if( isset($NaviMaster) && !empty($NaviMaster) ) {
                    $pid = 0;
                    $forLng = array();

                    if( $v->parent_id != '' && $v->parent_id != null && $v->parent_id != 'null' ) {
                        $pid = $v->parent_id;
                    }
                    $NaviMaster->menu_id = $forLng['menu_id'] = $menu_id;
                    $NaviMaster->parent_page_id = $forLng['parent_page_id'] = $pid;
                    $NaviMaster->depth = $forLng['depth'] = $v->depth;
                    $NaviMaster->is_saved = $forLng['is_saved'] = '1';
                    $NaviMaster->oid = $forLng['oid'] = $i;
                    $NaviMaster->save();

                    NaviMaster::where('whos_id', '=', $v->id)->update($forLng);
                }
                $i++;
            }

            return 'ok';
        }

        return $rtn;
    }

    public function getMenu(Request $request) {
        
        $DataBag = array();

        $menu_id = trim( $request->input('menu_id') );
        
        $menuData = NaviMaster::where('menu_id', '=', $menu_id)->where('parent_page_id', '=', '0')
        ->where('lng_id', '=', '1')->orderBy('oid', 'asc')->get();

        $DataBag['menuData'] = $menuData;

        $view = view('dashboard.menu.render_display_pages', $DataBag)->render();

        return response()->json(['html'=>$view, 'status' => 'ok']);
    }

    public function deleteMenu(Request $request) {

        $menu_id = trim( $request->input('menu_id') );

        $r1 = NaviMaster::where('menu_id', '=', $menu_id)->delete();
        
        return 'ok';
    }

    public function addLink(Request $request) {

        $DataBag = array();

        $whos_id = 0;

        $link_text = trim( $request->input('link_text') );
        $link_url = trim( $request->input('link_url') );
        $menu_id = trim( $request->input('menu_id') );

        if( $link_url == '' || $link_url == '#' ) {
            $is_link = '0';
        } else {
            $is_link = '1';
        }

        $NavArray = array();

        $NaviMaster = new NaviMaster;
        $NaviMaster->menu_id = $menu_id;
        $NaviMaster->cms_link_id = '0';
        $NaviMaster->table_type = "MENU_CUSTOM_LINK";
        $NaviMaster->table_id = '0';
        $NaviMaster->label_txt = $link_text;
        $NaviMaster->label_attr = $link_text;
        $NaviMaster->is_link = $is_link;
        $NaviMaster->lng_id = '1';
        $NaviMaster->custom_link = $link_url;
        if( $NaviMaster->save() ) {

            $whos_id = $NaviMaster->id;
            $arr = array();
            $arr['nav_id'] = $NaviMaster->id;
            $arr['page_title'] = $link_text;
            array_push( $NavArray, $arr );
        }
        
        $DataBag['NavArray'] = $NavArray;

        $lnglinks = json_decode( $request->input('lnglinks') );

        if( !empty($lnglinks) ) {

            foreach($lnglinks as $k => $lk) {

                $NaviMaster2 = new NaviMaster;
                $NaviMaster2->menu_id = $menu_id;
                $NaviMaster2->cms_link_id = '0';
                $NaviMaster2->table_type = "MENU_CUSTOM_LINK";
                $NaviMaster2->table_id = '0';
                $NaviMaster2->label_txt = $lk->link_text;
                $NaviMaster2->label_attr = $lk->link_text;
                $NaviMaster2->is_link = $is_link;
                $NaviMaster2->lng_id = $k;
                $NaviMaster2->custom_link = $lk->link_url;
                $NaviMaster2->whos_id = $whos_id;
                $NaviMaster2->save();
            }
        }

        $view = view('dashboard.menu.render_set_pages', $DataBag)->render();

        return response()->json(['html'=>$view, 'status' => 'ok']);
    }


    public function addListPage(Request $request) {

        $DataBag = array();

        $whos_id = 0;
        $is_link = '1';

        $menu_id = trim( $request->input('menu_id') );
        $listpage_urls = $request->input('listpage_urls');

        $NavArray = array();
        if( is_array($listpage_urls) && !empty($listpage_urls) ) {
            foreach( $listpage_urls as $lp ) {

                $expArr = explode('#', $lp);
                if( !empty($expArr) ) {

                    $NaviMaster = new NaviMaster;
                    $NaviMaster->menu_id = $menu_id;
                    $NaviMaster->cms_link_id = '0';
                    $NaviMaster->table_type = "MENU_CUSTOM_LINK";
                    $NaviMaster->table_id = '0';
                    $NaviMaster->label_txt = trim(end($expArr));
                    $NaviMaster->label_attr = trim(end($expArr));
                    $NaviMaster->is_link = $is_link;
                    $NaviMaster->lng_id = '1';
                    $NaviMaster->custom_link = trim($expArr[0]);
                    if( $NaviMaster->save() ) {
                        $whos_id = $NaviMaster->id;
                        $arr = array();
                        $arr['nav_id'] = $NaviMaster->id;
                        $arr['page_title'] = trim(end($expArr));
                        array_push( $NavArray, $arr );
                    }
                }
            }
        }
        
        $DataBag['NavArray'] = $NavArray;

        $view = view('dashboard.menu.render_set_pages', $DataBag)->render();

        return response()->json(['html' => $view, 'status' => 'ok']);
    }

    public function searchPage(Request $request) {

        $DataBag = array();

        $src_page = trim( $request->input('src_page') );

        $tabArr = ['PRODUCT', 'PRODUCT_CATEGORY', 'ARTICLE', 'INDUSTRY', 'FLOWSHEET_CATEGORY', 'FLOWSHEET', 'DISTRIBUTOR', 'DISTRIBUTOR_CATEGORY', 'DISTRIBUTOR_CONTENT', 'PEOPLE_PROFILE', 'EVENT', 'DYNA_CONTENT'];

        $data = CmsLinks::where('slug_url', 'LIKE', '%'.$src_page.'%')->whereIn('table_type', $tabArr)->take(75)->get();

        $DataBag['cms'] = $data;
        $DataBag['divID'] = md5(microtime(TRUE));

        $view = view('dashboard.menu.render_search_pages', $DataBag)->render();

        return response()->json(['html'=>$view, 'status' => 'ok']);
    }

    public function getPageBody(Request $request) {

        $DataBag = array();

        $otherLngs = \App\Models\Languages::where('status', '=', '1')->where('is_default', '=', '0')->get();

        $DataBag['otherLngs'] = $otherLngs;

        $navid = trim( $request->input('getNavId') );

        $NavDetail = NaviMaster::find($navid);
        $DataBag['NavDetail'] = $NavDetail;

        if( isset($NavDetail) && !empty($NavDetail) ) {
            if( $NavDetail->cms_link_id != '0' ) {
                $cms_id = $NavDetail->cms_link_id;
                //$cms_page_info = getCmsPageInfo( $cms_id );
                //$DataBag['PageInfo'] = $cms_page_info;
            }
        }

        $DataBag['NavId'] = $navid;

        $view = view('dashboard.menu.render_page_body', $DataBag)->render();

        return response()->json(['html'=>$view, 'status' => 'ok']);
    }

    public function savePageBody(Request $request) {

        $menu_id = trim( $request->input('menu_id') );
        $navid = trim( $request->input('navid') );
        $page_url = trim( $request->input('page_url') );
        $label_txt = trim( $request->input('label_txt') );
        $label_attr = trim( $request->input('label_attr') );
        $is_link = trim( $request->input('is_link') );

        $NaviMaster = NaviMaster::find( $navid );
        if( isset($NaviMaster) && !empty($NaviMaster) ) {
            $NaviMaster->label_txt = $label_txt;
            $NaviMaster->label_attr = $label_attr;
            if( $is_link == 'true' ) {
                $NaviMaster->is_link = 0;
            }
            if( $is_link == 'false' ) {
                $NaviMaster->is_link = 1;
            }
            if( $NaviMaster->cms_link_id == '0' && $NaviMaster->table_id == '0' && $NaviMaster->table_type == 'MENU_CUSTOM_LINK' ) {
                $NaviMaster->custom_link = $page_url;
            }

            $NaviMaster->is_saved = 1;
            $NaviMaster->save();
        }

        $lngnav_info = json_decode($request->input('lngnav_info'));
        if( !empty( $lngnav_info ) ) {
            foreach( $lngnav_info as $k => $v ) {
                $pocketArr = explode('_', $k);
                if(!empty($pocketArr)) {
                    $navID = $pocketArr[0];
                } else {
                    $navID = 0;
                }
                $NaviMaster2 = NaviMaster::find( $navID );
                if( isset($NaviMaster2) && !empty($NaviMaster2) ) {
                    $NaviMaster2->label_txt = $v->label_txt;
                    $NaviMaster2->label_attr = $v->label_attr;
                    if( $is_link == 'true' ) {
                        $NaviMaster2->is_link = 0;
                    }
                    if( $is_link == 'false' ) {
                        $NaviMaster2->is_link = 1;
                    }
                    if( $NaviMaster2->cms_link_id == '0' && $NaviMaster->table_id == '0' && $NaviMaster->table_type == 'MENU_CUSTOM_LINK' ) {
                        $NaviMaster2->custom_link = $v->page_url;
                    }

                    $NaviMaster2->is_saved = 1;
                    $NaviMaster2->save();
                } else {
                    $NaviMaster2 = new NaviMaster;
                    $NaviMaster2->menu_id = $menu_id;
                    $NaviMaster2->cms_link_id = $v->cmslink_id;
                    $NaviMaster2->table_type = $v->table_type;
                    $NaviMaster2->table_id = $v->table_id;
                    $NaviMaster2->label_txt = $v->label_txt;
                    $NaviMaster2->label_attr = $v->label_attr;
                    $NaviMaster2->lng_id = $v->lng_id;
                    $NaviMaster2->whos_id = $v->whos_id;
                    if( $is_link == 'true' ) {
                        $NaviMaster2->is_link = 0;
                    }
                    if( $is_link == 'false' ) {
                        $NaviMaster2->is_link = 1;
                    }
                    if( $v->cmslink_id == '0' && $v->table_id == '0' && $v->table_type == 'MENU_CUSTOM_LINK' ) {
                        $NaviMaster2->custom_link = $v->page_url;
                    }

                    $NaviMaster2->is_saved = 1;
                    $NaviMaster2->save();
                }
            }
        }

        return 'ok';
    }

    public function deletePageBody(Request $request) {

        $id = trim( $request->input('id') );
        $ck = NaviMaster::find( $id )->delete();
        if( $ck ) {
            NaviMaster::where('parent_page_id', '=', $id)->delete();
            NaviMaster::where('whos_id', '=', $id)->delete();
            return 'ok';
        }

        return '';
    }
}
