<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CmsLinks;
use App\Models\Media\Images;
use App\Models\Media\FilesMaster;
use App\Models\Content\Contents;
use App\Models\Content\ContentType;
use App\Models\Content\ContentTypeImagesMap;
use App\Models\Content\ContentsFilesMap;
use App\Models\Content\ContentsImagesMap;
use App\Models\Languages;
use App\Models\HomeContent;
use App\Models\PageBuilder\PageBuilder;
use App\Models\MineralProcess;
use App\Models\Mineral;
use App\Models\HomeMap;
use File;
use Image;
use Auth;
use DB;

class ContentController extends Controller
{
    
    public function allContentTypes() {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'contManagement';
    	$DataBag['childMenu'] = 'allContType';
    	$DataBag['allTypes'] = ContentType::with('contentIds')->where('status', '!=', '3')->orderBy('created_at', 'desc')->get();
    	return view('dashboard.contents.all_types', $DataBag);
    }

    public function addContentType() {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'contManagement';
    	$DataBag['childMenu'] = 'addContType';
    	return view('dashboard.contents.add_type', $DataBag);
    }

    public function saveContentType(Request $request) {

        $request->validate([
            
            'name' => 'required|unique:content_type,name',
        ],[
        
            'name.unique' => 'Category Name Already Exist, Try Another.',
        ]);

    	$ContentType = new ContentType;
    	$ContentType->name = trim(ucfirst($request->input('name')));
    	$ContentType->description = trim($request->input('description'));
    	$ContentType->status = trim($request->input('status'));
    	$ContentType->created_by = Auth::user()->id;
        
        $bannerImageJson = json_decode( trim( $request->input('banner_image_infos') ) );

    	if( $ContentType->save() ) {

            if( !empty($bannerImageJson) ) {
                $imageMap = array();
                foreach ($bannerImageJson as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['content_type_id'] = $ContentType->id;
                        $arr['image_id'] = $v->img_id;
                        $arr['title'] = $v->img_titl;
                        $arr['caption'] = $v->img_cap;
                        $arr['alt_tag'] = $v->img_alt;
                        $arr['description'] = $v->img_dsc;
                        $arr['image_type'] = "BANNER_IMAGE";
                        array_push( $imageMap, $arr );
                    }
                }

                if( !empty($imageMap) ) {
                    ContentTypeImagesMap::insert($imageMap);
                }
            }

    		return back()->with('msg', 'Content Type Created Successfully')
    		->with('msg_class', 'alert alert-success');
    	}

    	return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function editContentType($ct_id) {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'contManagement';
    	$DataBag['childMenu'] = 'addContType';
    	$DataBag['content_type'] = ContentType::findOrFail($ct_id);
    	return view('dashboard.contents.add_type', $DataBag);
    }

    public function updateContentType(Request $request, $ct_id) {

        $request->validate([
            
            'name' => 'required|unique:content_type,name,'.$ct_id,
        ],[
        
            'name.unique' => 'Category Name Already Exist, Try Another.',
        ]);

    	$ContentType = ContentType::find($ct_id);
    	$ContentType->name = trim(ucfirst($request->input('name')));
    	$ContentType->description = trim($request->input('description'));
    	$ContentType->status = trim($request->input('status'));
    	$ContentType->updated_by = Auth::user()->id;

        $bannerImageJson = json_decode( trim( $request->input('banner_image_infos') ) ); 

    	if( $ContentType->save() ) {

            if( !empty($bannerImageJson) ) {
                $imageMap = array();
                foreach ($bannerImageJson as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['content_type_id'] = $ContentType->id;
                        $arr['image_id'] = $v->img_id;
                        $arr['title'] = $v->img_titl;
                        $arr['caption'] = $v->img_cap;
                        $arr['alt_tag'] = $v->img_alt;
                        $arr['description'] = $v->img_dsc;
                        $arr['image_type'] = "BANNER_IMAGE";
                        array_push( $imageMap, $arr );
                    }
                }

                if( !empty($imageMap) ) {
                    ContentTypeImagesMap::insert($imageMap);
                }
            }


    		return back()->with('msg', 'Content Type Updated Successfully')
    		->with('msg_class', 'alert alert-success');
    	}

    	return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function deleteContentType($ct_id) {

    	$ck = ContentType::find($ct_id);
    	if( isset($ck) && !empty($ck) ) {
    		$ck->status = '3';
    		if( $ck->save() ) {

                ContentTypeImagesMap::where('content_type_id', '=', $ct_id)->delete();
                
    			return back()->with('msg', 'Content Type Deleted Successfully.')
    			->with('msg_class', 'alert alert-success');
    		}
    	}

    	return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function allTypesList() {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'contManagement';
    	$DataBag['childMenu'] = 'mngConts';
    	$DataBag['allTypes'] = ContentType::with('contentIds')->where('status', '!=', '3')->orderBy('created_at', 'desc')->get();
    	return view('dashboard.contents.type_list', $DataBag);
    }

    public function manageList($type_name, $type_id) {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'contManagement';
    	$DataBag['childMenu'] = 'mngConts';
    	$DataBag['typeDetails'] = ContentType::findOrFail($type_id);
    	$DataBag['allContents'] = Contents::where('content_type_id', '=', $type_id)->where('status', '!=', '3')
    	->where('parent_language_id', '=', '0')->orderBy('created_at', 'desc')->get();
    	return view('dashboard.contents.management', $DataBag);
    }

    public function addContent($type_name, $type_id) {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'contManagement';
    	$DataBag['childMenu'] = 'mngConts';
    	$DataBag['typeDetails'] = ContentType::findOrFail($type_id);
    	$DataBag['allPages'] = Contents::where('content_type_id', '=', $type_id)->where('status', '=', '1')
    	->where('parent_language_id', '=', '0')->orderBy('name', 'asc')->get();
    	$DataBag['languages'] = Languages::where('status', '=', '1')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));
    	return view('dashboard.contents.add_content', $DataBag);
    }

    public function editContent($type_name, $type_id, $content_id) {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'contManagement';
    	$DataBag['childMenu'] = 'mngConts';
    	$DataBag['dynaContent'] = Contents::findOrFail($content_id);
        $DataBag['pageBuilderData'] = $DataBag['dynaContent'];
    	$DataBag['typeDetails'] = ContentType::findOrFail($type_id);
    	$DataBag['allPages'] = Contents::where('content_type_id', '=', $type_id)->where('status', '=', '1')
    	->where('parent_language_id', '=', '0')->where('id', '!=', $content_id)->where('parent_language_id', '=', '0')->orderBy('name', 'asc')->get();
    	$DataBag['languages'] = Languages::where('status', '=', '1')->get();
        
    	return view('dashboard.contents.add_content', $DataBag);
    }

    public function saveContent(Request $request, $type_name, $type_id) {

        $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

    	$Contents = new Contents;
    	$Contents->name = trim( ucfirst($request->input('name')) );
        $Contents->insert_id = $insert_id;
    	$Contents->slug = trim($request->input('slug'));
    	$Contents->description = trim( $request->input('description') );
    	$Contents->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
    	//$Contents->status = trim($request->input('status'));
    	//$Contents->publish_status = trim($request->input('publish_status'));
    	$Contents->created_by = Auth::user()->id;
    	$Contents->language_id = trim( $request->input('language_id') );

    	$Contents->parent_page_id = trim($request->input('parent_page_id'));
    	$Contents->content_type_id = trim($request->input('content_type_id'));

        $Contents->meta_title = trim($request->input('meta_title'));
        $Contents->meta_desc = trim($request->input('meta_desc'));
        $Contents->meta_keyword = trim($request->input('meta_keyword'));
        $Contents->canonical_url = trim($request->input('canonical_url'));
        $Contents->lng_tag = trim($request->input('lng_tag'));
        $Contents->follow = trim($request->input('follow'));
        $Contents->index_tag = trim($request->input('index_tag'));
        $Contents->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );

        if( $request->has('is_full_width') ) {

            $Contents->is_full_width = trim( $request->input('is_full_width') );
        }else {
            $Contents->is_full_width = '';
        }

    	$resx = $Contents->save();
    	if( isset($resx) && $resx == 1 ) {

    		$content_id = $Contents->id;

    		$CmsLinks = new CmsLinks;
    		$CmsLinks->table_id = $content_id;
    		$CmsLinks->slug_url = trim($request->input('slug'));
    		$CmsLinks->table_type = 'DYNA_CONTENT';
    		$CmsLinks->save();
            $cms_link_id = $CmsLinks->id; // Need for page builder as parameter

            /** For Page Builder -- Insert Time **/
            /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
            update_page_builder($insert_id, $cms_link_id, $content_id, 'DYNA_CONTENT');
            /** End Page Builder **/

	    return back()->with('msg', 'Content Created Successfully.')
    	->with('msg_class', 'alert alert-success');

    	}
    
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }

    public function updateContent(Request $request, $type_name, $type_id, $content_id) {

        $insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

    	$Contents = Contents::find($content_id);
    	$Contents->name = trim( ucfirst($request->input('name')) );
    	$Contents->slug = trim($request->input('slug'));
    	$Contents->description = trim( $request->input('description') );
    	$Contents->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
    	//$Contents->status = trim($request->input('status'));
    	//$Contents->publish_status = trim($request->input('publish_status'));
    	$Contents->updated_by = Auth::user()->id;
    	$Contents->language_id = trim( $request->input('language_id') );
    	
    	$Contents->parent_page_id = trim($request->input('parent_page_id'));
    	$Contents->content_type_id = trim($request->input('content_type_id'));

        $Contents->meta_title = trim($request->input('meta_title'));
        $Contents->meta_desc = trim($request->input('meta_desc'));
        $Contents->meta_keyword = trim($request->input('meta_keyword'));
        $Contents->canonical_url = trim($request->input('canonical_url'));
        $Contents->lng_tag = trim($request->input('lng_tag'));
        $Contents->follow = trim($request->input('follow'));
        $Contents->index_tag = trim($request->input('index_tag'));
        $Contents->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );

        if( $request->has('is_full_width') ) {

            $Contents->is_full_width = trim( $request->input('is_full_width') );
        } else {
            $Contents->is_full_width = '';
        }

    	$resx = $Contents->save();
    	if( isset($resx) && $resx == 1 ) {

    		CmsLinks::where('table_type', '=', 'DYNA_CONTENT')->where('table_id', '=', $content_id)
    		->update([ 'slug_url' => trim($request->input('slug')) ]);

            /** Need For Page Builder -- Update Time **/
            $cmsInfo = CmsLinks::where('table_id', '=', $content_id)->where('table_type', '=', 'DYNA_CONTENT')->first();

            if( !empty($cmsInfo) ) {
                
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                update_page_builder($insert_id, $cmsInfo->id, $content_id, 'DYNA_CONTENT');

            }
            /** End Page Builder **/

	    return back()->with('msg', 'Content Updated Successfully.')
    	->with('msg_class', 'alert alert-success');

    	}
    
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }

    public function deleteContent($type_name, $type_id, $content_id) {

    	$ck = Contents::find($content_id);
    	if( isset($ck) && !empty($ck) ) {
    		$ck->status = '3';
    		if( $ck->save() ) {
    			//ContentsImagesMap::where('content_id', '=', $content_id)->delete();
    			//ContentsFilesMap::where('content_id', '=', $content_id)->delete();

                /* delete_navigation($table_id, $table_type) */
                delete_navigation($content_id, 'DYNA_CONTENT');
    			CmsLinks::where('table_type', '=', 'DYNA_CONTENT')->where('table_id', '=', $content_id)->delete();

                PageBuilder::where('table_id', '=', $content_id)->where('table_type', '=', 'DYNA_CONTENT')->delete();

    			return back()->with('msg', 'Content Deleted Successfully.')
    			->with('msg_class', 'alert alert-success');
    		}
    	}

    	return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }



    public function deleteLngContent($type_name, $type_id, $parent_content_id, $child_content_id) {

    	Contents::find($child_content_id)->delete();

        /* delete_navigation($table_id, $table_type) */
        delete_navigation($child_content_id, 'DYNA_CONTENT');
    	CmsLinks::where('table_type', '=', 'DYNA_CONTENT')->where('table_id', '=', $child_content_id)->delete();

        PageBuilder::where('table_id', '=', $child_content_id)->where('table_type', '=', 'DYNA_CONTENT')->delete();

    	return redirect()->route('edtDynaCont', array('type' => $type_name, 'type_id' => $type_id, 'id' => $parent_content_id))
		->with('msg', 'Content Created Successfully.')->with('msg_class', 'alert alert-success');
    	
    }

    public function addeditLngContent($type_name, $type_id, $content_id, $child_id = '') {

    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'contManagement';
    	$DataBag['childMenu'] = 'mngConts';
    	$DataBag['typeDetails'] = ContentType::findOrFail($type_id);
    	$DataBag['parentLngCont'] = Contents::findOrFail($content_id);
    	if( $child_id != '' && $child_id != null ) {
    		$DataBag['dynaContent'] = Contents::findOrFail($child_id);
            $DataBag['pageBuilderData'] = $DataBag['dynaContent'];
    	}
    	$DataBag['allPages'] = Contents::where('content_type_id', '=', $type_id)->where('status', '=', '1')
    	->where('parent_language_id', '!=', '0')->orderBy('name', 'asc')->get();
    	$DataBag['languages'] = Languages::where('status', '=', '1')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));
    	return view('dashboard.contents.add_language_content', $DataBag);
    }

    public function addeditLngContentPost(Request $request, $type_name, $type_id, $parent_content_id, $child_content_id = '') {

    	if( $child_content_id != '' && $child_content_id != null ) {

            $insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

	    	$Contents = Contents::find($child_content_id);
	    	$Contents->name = trim( ucfirst($request->input('name')) );
	    	$Contents->slug = trim($request->input('slug'));
	    	$Contents->description = trim( $request->input('description') );
	    	$Contents->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
	    	//$Contents->status = trim($request->input('status'));
	    	//$Contents->publish_status = trim($request->input('publish_status'));
	    	$Contents->updated_by = Auth::user()->id;
	    	$Contents->language_id = trim( $request->input('language_id') );
	    	
	    	$Contents->parent_page_id = trim($request->input('parent_page_id'));
	    	$Contents->content_type_id = trim($request->input('content_type_id'));

            $Contents->meta_title = trim($request->input('meta_title'));
            $Contents->meta_desc = trim($request->input('meta_desc'));
            $Contents->meta_keyword = trim($request->input('meta_keyword'));
            $Contents->canonical_url = trim($request->input('canonical_url'));
            $Contents->lng_tag = trim($request->input('lng_tag'));
            $Contents->follow = trim($request->input('follow'));
            $Contents->index_tag = trim($request->input('index_tag'));

            if( $request->has('is_full_width') ) {

                $Contents->is_full_width = trim( $request->input('is_full_width') );
            }else {
                $Contents->is_full_width = '';
            }

	    	$resx = $Contents->save();
	    	if( isset($resx) && $resx == 1 ) {

	    		$content_id = $child_content_id;
	    		CmsLinks::where('table_type', '=', 'DYNA_CONTENT')->where('table_id', '=', $content_id)
	    		->update([ 'slug_url' => trim($request->input('slug')) ]);

                /** Need For Page Builder -- Update Time **/
                $cmsInfo = CmsLinks::where('table_id', '=', $content_id)->where('table_type', '=', 'DYNA_CONTENT')->first();

                if( !empty($cmsInfo) ) {
                    
                    /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                    update_page_builder($insert_id, $cmsInfo->id, $content_id, 'DYNA_CONTENT');

                }
                /** End Page Builder **/

		    return back()->with('msg', 'Content Updated Successfully.')
	    	->with('msg_class', 'alert alert-success');

	    	}
	    return back()->with('msg', 'Something Went Wrong')
	    ->with('msg_class', 'alert alert-danger');
    	} 

    	if( $child_content_id == '' ) {

            $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

	    	$Contents = new Contents;
	    	$Contents->name = trim( ucfirst($request->input('name')) );
	    	$Contents->slug = trim($request->input('slug'));
            $Contents->insert_id = $insert_id;
	    	$Contents->description = trim( $request->input('description') );
	    	$Contents->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
	    	//$Contents->status = trim($request->input('status'));
	    	//$Contents->publish_status = trim($request->input('publish_status'));
	    	$Contents->created_by = Auth::user()->id;
	    	$Contents->language_id = trim( $request->input('language_id') );
	    	$Contents->parent_language_id = $parent_content_id;

	    	$Contents->parent_page_id = trim($request->input('parent_page_id'));
	    	$Contents->content_type_id = trim($request->input('content_type_id'));

            $Contents->meta_title = trim($request->input('meta_title'));
            $Contents->meta_desc = trim($request->input('meta_desc'));
            $Contents->meta_keyword = trim($request->input('meta_keyword'));
            $Contents->canonical_url = trim($request->input('canonical_url'));
            $Contents->lng_tag = trim($request->input('lng_tag'));
            $Contents->follow = trim($request->input('follow'));
            $Contents->index_tag = trim($request->input('index_tag'));

            if( $request->has('is_full_width') ) {

                $Contents->is_full_width = trim( $request->input('is_full_width') );
            }else {
                $Contents->is_full_width = '';
            }

	    	$resx = $Contents->save();
	    	if( isset($resx) && $resx == 1 ) {

	    		$content_id = $Contents->id;

	    		$CmsLinks = new CmsLinks;
	    		$CmsLinks->table_id = $content_id;
	    		$CmsLinks->slug_url = trim($request->input('slug'));
	    		$CmsLinks->table_type = 'DYNA_CONTENT';
	    		$CmsLinks->save();

                $cms_link_id = $CmsLinks->id; // Need for page builder as parameter

                /** For Page Builder -- Insert Time **/
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                update_page_builder($insert_id, $cms_link_id, $content_id, 'DYNA_CONTENT');
                /** End Page Builder **/

		    return redirect()->route('edtDynaCont', array('type' => $type_name, 'type_id' => $type_id, 'id' => $parent_content_id))
		    ->with('msg', 'Content Created Successfully.')->with('msg_class', 'alert alert-success');
	    	}
	    
	    return back()->with('msg', 'Something Went Wrong')
	    ->with('msg_class', 'alert alert-danger');
    	}
    }


    /*********************** BULK ACTION ****************************/

    public function bulkAction(Request $request) {

        $msg = '';
        if( $request->has('action_btn') && $request->has('ids') ) {
            $actBtnValue = trim( $request->input('action_btn') );
            $idsArr = $request->input('ids');

            switch ( $actBtnValue ) {
                
                case 'activate':
                    foreach($idsArr as $id) {
                        $Contents = Contents::find($id);
                        $Contents->status = '1';
                        $Contents->save();
                    }
                    $msg = 'Contents Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $Contents = Contents::find($id);
                        $Contents->status = '2';
                        $Contents->save();
                    }
                    $msg = 'Contents Deactivated Succesfully.';
                    break;

                case 'delete':
                    foreach($idsArr as $id) {
                        $Contents = Contents::find($id);
                        $Contents->status = '3';
                        $Contents->save();
                        //ContentsImagesMap::where('content_id', '=', $id)->delete();
                        //ContentsFilesMap::where('content_id', '=', $id)->delete();
                        CmsLinks::where('table_type', '=', 'DYNA_CONTENT')->where('table_id', '=', $id)->delete();

                        delete_navigation($id, 'DYNA_CONTENT');
                        PageBuilder::where('table_id', '=', $id)->where('table_type', '=', 'DYNA_CONTENT')->delete();
                    }
                    $msg = 'Contents Deleted Succesfully.';
                    break;
            }
            return back()->with('msg', $msg)->with('msg_class', 'alert alert-success');
        }
        return back();
    }



    /*********** HOME ******************/

    public function home() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'homepage';
        $DataBag['childMenu'] = 'homeCont';
        $ck = HomeContent::find(1);
        if( !empty($ck) ) {
            $DataBag['home'] = HomeContent::find(1);  
            $DataBag['pageBuilderData'] = $DataBag['home']; /* For pagebuilder */
        }
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));
        return view('dashboard.home.create', $DataBag);
    }

    public function homeAct(Request $request) {

        $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

        $ck = HomeContent::find(1);

        if( !empty($ck) ) {
            $ck->name = trim( ucfirst($request->input('name')) );
            $ck->slug = trim($request->input('slug'));
            $ck->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $ck->readmore_content = trim( htmlentities($request->input('readmore_content'), ENT_QUOTES) );
            $ck->reuse_content1 = trim( htmlentities($request->input('reuse_content1'), ENT_QUOTES) );
            $ck->reuse_content2 = trim( htmlentities($request->input('reuse_content2'), ENT_QUOTES) );
            $ck->mineral_processing_heading = trim($request->input('mineral_processing_heading'));
            $ck->mineral_heading = trim($request->input('mineral_heading'));
            $ck->news_heading = trim($request->input('news_heading'));
            if($request->input('news_no') != '') {
                $ck->news_no = trim($request->input('news_no'));
            }

            $ck->meta_title = trim($request->input('meta_title'));
            $ck->meta_desc = trim($request->input('meta_desc'));
            $ck->meta_keyword = trim($request->input('meta_keyword'));
            $ck->canonical_url = trim($request->input('canonical_url'));
            $ck->lng_tag = trim($request->input('lng_tag'));
            $ck->follow = trim($request->input('follow'));
            $ck->index_tag = trim($request->input('index_tag'));
            $ck->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );
            
            $ck->updated_by = Auth::user()->id;

            if( $ck->save() ) {

                CmsLinks::where('table_type', '=', 'HOME_CONTENT')->where('table_id', '=', $ck->id)
                ->update( [ 'slug_url' => trim($request->input('slug')) ] );
                
                /** Need For Page Builder -- Update Time **/
                //$cmsInfo = CmsLinks::where('table_id', '=', $ck->id)->where('table_type', '=', 'HOME_CONTENT')->first();
                //if( !empty($cmsInfo) ) {
                    
                    /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                    //update_page_builder($insert_id, $cmsInfo->id, $ck->id, 'HOME_CONTENT');

                //}
                /** End Page Builder **/

                return back()->with('msg', 'Home Page Content Saved Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
        } else {
            $HomeContent = new HomeContent;
            $HomeContent->name = trim( ucfirst($request->input('name')) );
            $HomeContent->slug = trim($request->input('slug'));
            $HomeContent->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $HomeContent->readmore_content = trim( htmlentities($request->input('readmore_content'), ENT_QUOTES) );
            $HomeContent->reuse_content1 = trim( htmlentities($request->input('reuse_content1'), ENT_QUOTES) );
            $HomeContent->reuse_content2 = trim( htmlentities($request->input('reuse_content2'), ENT_QUOTES) );
            $HomeContent->language_id = trim( $request->input('language_id') );
            $HomeContent->insert_id = $insert_id;
            $HomeContent->mineral_processing_heading = trim($request->input('mineral_processing_heading'));
            $HomeContent->mineral_heading = trim($request->input('mineral_heading'));
            $HomeContent->news_heading = trim($request->input('news_heading'));
            if($request->input('news_no') != '') {
                $HomeContent->news_no = trim($request->input('news_no'));
            }

            $HomeContent->meta_title = trim($request->input('meta_title'));
            $HomeContent->meta_desc = trim($request->input('meta_desc'));
            $HomeContent->meta_keyword = trim($request->input('meta_keyword'));
            $HomeContent->canonical_url = trim($request->input('canonical_url'));
            $HomeContent->lng_tag = trim($request->input('lng_tag'));
            $HomeContent->follow = trim($request->input('follow'));
            $HomeContent->index_tag = trim($request->input('index_tag'));
            $HomeContent->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );
            
            $HomeContent->created_by = Auth::user()->id;
            
            if( $HomeContent->save() ) {

                $id = $HomeContent->id;

                $CmsLinks = new CmsLinks;
                $CmsLinks->table_id = $id;
                $CmsLinks->slug_url = trim($request->input('slug'));
                $CmsLinks->table_type = 'HOME_CONTENT';
                $CmsLinks->save();
                $cms_link_id = $CmsLinks->id; // Need for page builder as parameter

                /** For Page Builder -- Insert Time **/
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                //update_page_builder($insert_id, $cms_link_id, $id, 'HOME_CONTENT');
                /** End Page Builder **/

                return back()->with('msg', 'Home Page Content Saved Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
        }
    
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }

    public function addEditHomeLanguage($parent_language_id, $child_language_id = '') {

        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['childMenu'] = 'homeCont';
        $DataBag['parentLngCont'] = HomeContent::findOrFail($parent_language_id);
        if( $child_language_id != '' ) {
            $DataBag['home'] = HomeContent::findOrFail($child_language_id);
            $DataBag['pageBuilderData'] = $DataBag['home'];
        }
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));
        return view('dashboard.home.addedit_language', $DataBag);
    }

    public function addEditHomeLanguagePost(Request $request, $parent_language_id, $child_language_id = '') {
        
        if( $child_language_id != '' && $child_language_id != null ) {

            $insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

            $HomeContent = HomeContent::find($child_language_id);
            $HomeContent->name = trim( ucfirst($request->input('name')) );
            $HomeContent->slug = trim($request->input('slug'));
            $HomeContent->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );

            $HomeContent->meta_title = trim($request->input('meta_title'));
            $HomeContent->meta_desc = trim($request->input('meta_desc'));
            $HomeContent->meta_keyword = trim($request->input('meta_keyword'));
            $HomeContent->canonical_url = trim($request->input('canonical_url'));
            $HomeContent->lng_tag = trim($request->input('lng_tag'));
            $HomeContent->follow = trim($request->input('follow'));
            $HomeContent->index_tag = trim($request->input('index_tag'));
            $HomeContent->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );
            
            $HomeContent->updated_by = Auth::user()->id;
            
            if( $HomeContent->save() ) {

                $id = $child_language_id;

                CmsLinks::where('table_type', '=', 'HOME_CONTENT')->where('table_id', '=', $id)
                ->update( [ 'slug_url' => trim($request->input('slug')) ] );

                /** Need For Page Builder -- Update Time **/
                //$cmsInfo = CmsLinks::where('table_id', '=', $id)->where('table_type', '=', 'HOME_CONTENT')->first();
                //if( !empty($cmsInfo) ) {
                    
                    /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                    //update_page_builder($insert_id, $cmsInfo->id, $id, 'HOME_CONTENT');

               // }
                /** End Page Builder **/
                
                return back()->with('msg', 'Home Content Updated Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
        }

        if( $child_language_id == '' ) {

            $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

            $HomeContent = new HomeContent;
            $HomeContent->name = trim( ucfirst($request->input('name')) );
            $HomeContent->slug = trim($request->input('slug'));
            $HomeContent->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $HomeContent->insert_id = $insert_id;

            $HomeContent->meta_title = trim($request->input('meta_title'));
            $HomeContent->meta_desc = trim($request->input('meta_desc'));
            $HomeContent->meta_keyword = trim($request->input('meta_keyword'));
            $HomeContent->canonical_url = trim($request->input('canonical_url'));
            $HomeContent->lng_tag = trim($request->input('lng_tag'));
            $HomeContent->follow = trim($request->input('follow'));
            $HomeContent->index_tag = trim($request->input('index_tag'));
            $HomeContent->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );
            
            $HomeContent->created_by = Auth::user()->id;
            $HomeContent->language_id = trim( $request->input('language_id') );
            $HomeContent->parent_language_id = $parent_language_id;

            
            if( $HomeContent->save() ) {

                $id = $HomeContent->id;

                $CmsLinks = new CmsLinks;
                $CmsLinks->table_id = $id;
                $CmsLinks->slug_url = trim($request->input('slug'));
                $CmsLinks->table_type = 'HOME_CONTENT';
                $CmsLinks->save();

                $cms_link_id = $CmsLinks->id; // Need for page builder as parameter

                /** For Page Builder -- Insert Time **/
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                //update_page_builder($insert_id, $cms_link_id, $id, 'HOME_CONTENT');
                /** End Page Builder **/

                return redirect()->route('home.cont', array('id' => $parent_language_id))
                ->with('msg', 'Home Content Created Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
        }

        return back();
    }

    public function deleteHomeLanguage($parent_language_id, $child_language_id) {

        HomeContent::findOrFail($child_language_id)->delete();
        CmsLinks::where('table_type', '=', 'HOME_CONTENT')->where('table_id', '=', $child_language_id)->delete();

        delete_navigation($child_language_id, 'HOME_CONTENT');
        PageBuilder::where('table_id', '=', $child_language_id)->where('table_type', '=', 'HOME_CONTENT')->delete();

        return redirect()->route('home.cont', array('id' => $parent_language_id))
        ->with('msg', 'Home Content Deleted Successfully.')
        ->with('msg_class', 'alert alert-success');
        
    }


    public function mineralProcessing() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'homepage';
        $DataBag['childMenu'] = 'hMineralProcessing';

        $DataBag['allList'] = MineralProcess::orderBy('id', 'desc')->get();

        return view('dashboard.home.mps.index', $DataBag);
    }


    public function mineralProcessingAdd() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'homepage';
        $DataBag['childMenu'] = 'hMineralProcessing';

        return view('dashboard.home.mps.create', $DataBag);
    }

    public function mineralProcessingSave(Request $request) {

        $MineralProcess = new MineralProcess;
        $MineralProcess->title = trim($request->input('title'));
        $MineralProcess->description = trim($request->input('description'));
        $MineralProcess->view_link = trim($request->input('view_link'));
        
        if($request->input('display_order') != '') {
            $MineralProcess->display_order = trim($request->input('display_order'));
        }
        
        $MineralProcess->image_title = trim($request->input('image_title'));
        $MineralProcess->image_alt = trim($request->input('image_alt'));
        $MineralProcess->image_caption = trim($request->input('image_caption'));

        if( $request->hasFile('image') ) {
            
            $img = $request->file('image');
            $real_path = $img->getRealPath();
            $file_orgname = $img->getClientOriginalName();
            $file_size = $img->getClientSize();
            $file_ext = strtolower($img->getClientOriginalExtension());
            $file_newname = "mps"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
            $destinationPath = public_path('/uploads/files/media_images');
            $thumb_path = $destinationPath."/thumb";

            $imgObj = Image::make($real_path);
            $imgObj->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumb_path.'/'.$file_newname);

            $img->move($destinationPath, $file_newname);

            $Images = new Images;
            $Images->image = $file_newname;
            $Images->size = $file_size;
            $Images->extension = $file_ext;

            $Images->name = "Mineral Processing Image";
            $Images->alt_title = trim($request->input('image_alt'));
            $Images->caption = trim($request->input('image_caption'));
            $Images->title = trim($request->input('image_title'));

            $Images->created_by = Auth::user()->id;

            if($Images->save()) {

                $MineralProcess->image_id = $Images->id;  
            }
        }

        if( $MineralProcess->save() ) {

            return back()->with('msg', 'Mineral Process Added Successfully')
            ->with('msg_class', 'alert alert-success');
        }

        return back();
    }


    public function mineralProcessingEdit($id) {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'homepage';
        $DataBag['childMenu'] = 'hMineralProcessing';
        $DataBag['mps_id'] = $id;
        $DataBag['mps'] = MineralProcess::findOrFail($id);

        return view('dashboard.home.mps.create', $DataBag);   
    }

    public function mineralProcessingUpdate(Request $request, $id) {

        $MineralProcess = MineralProcess::findOrFail($id);
        $MineralProcess->title = trim($request->input('title'));
        $MineralProcess->description = trim($request->input('description'));
        $MineralProcess->view_link = trim($request->input('view_link'));

        if($request->input('display_order') != '') {
            $MineralProcess->display_order = trim($request->input('display_order'));
        }

        $MineralProcess->image_title = trim($request->input('image_title'));
        $MineralProcess->image_alt = trim($request->input('image_alt'));
        $MineralProcess->image_caption = trim($request->input('image_caption'));

        if( $request->hasFile('image') ) {
            
            $img = $request->file('image');
            $real_path = $img->getRealPath();
            $file_orgname = $img->getClientOriginalName();
            $file_size = $img->getClientSize();
            $file_ext = strtolower($img->getClientOriginalExtension());
            $file_newname = "mps"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
            $destinationPath = public_path('/uploads/files/media_images');
            $thumb_path = $destinationPath."/thumb";

            $imgObj = Image::make($real_path);
            $imgObj->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumb_path.'/'.$file_newname);

            $img->move($destinationPath, $file_newname);

            $Images = new Images;
            $Images->image = $file_newname;
            $Images->size = $file_size;
            $Images->extension = $file_ext;

            $Images->name = "Mineral Processing Image";
            $Images->alt_title = trim($request->input('image_alt'));
            $Images->caption = trim($request->input('image_caption'));
            $Images->title = trim($request->input('image_title'));

            $Images->created_by = Auth::user()->id;

            if($Images->save()) {

                $MineralProcess->image_id = $Images->id;  
            }
        }

        if( $MineralProcess->save() ) {

            return back()->with('msg', 'Mineral Process Updated Successfully')
            ->with('msg_class', 'alert alert-success');
        }

        return back(); 
    }

    public function mineralProcessingDelete($id) {

        MineralProcess::findOrFail($id)->delete();
        return back()->with('msg', 'Mineral Process Deleted Successfully')
        ->with('msg_class', 'alert alert-success');
    }

    public function mineralProcessingBulkAction(Request $request) {

        $msg = '';
        if( $request->has('action_btn') && $request->has('ids') ) {
            $actBtnValue = trim( $request->input('action_btn') );
            $idsArr = $request->input('ids');

            switch ( $actBtnValue ) {
                
                case 'activate':
                    foreach($idsArr as $id) {
                        $MineralProcess = MineralProcess::find($id);
                        $MineralProcess->status = '1';
                        $MineralProcess->save();
                    }
                    $msg = 'MineralProcess Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $MineralProcess = MineralProcess::find($id);
                        $MineralProcess->status = '2';
                        $MineralProcess->save();
                    }
                    $msg = 'MineralProcess Deactivated Succesfully.';
                    break;

                case 'delete':
                    foreach($idsArr as $id) {
                        $MineralProcess = MineralProcess::find($id);
                        $MineralProcess->delete();
                    }
                    $msg = 'MineralProcess Deleted Succesfully.';
                    break;
            }
            return back()->with('msg', $msg)->with('msg_class', 'alert alert-success');
        }
        return back();
    }


    public function mineral() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'homepage';
        $DataBag['childMenu'] = 'hMineral';

        $DataBag['allList'] = Mineral::orderBy('id', 'desc')->get();

        return view('dashboard.home.m.index', $DataBag);   
    }

    public function mineralAdd() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'homepage';
        $DataBag['childMenu'] = 'hMineral';

        return view('dashboard.home.m.create', $DataBag); 
    }


    public function mineralSave(Request $request) {

        $Mineral = new Mineral;
        $Mineral->name = trim($request->input('name'));
        $Mineral->view_link = trim($request->input('view_link'));

        if($request->input('display_order') != '') {
            $Mineral->display_order = trim($request->input('display_order'));
        }

        $Mineral->image_title = trim($request->input('image_title'));
        $Mineral->image_alt = trim($request->input('image_alt'));
        $Mineral->image_caption = trim($request->input('image_caption'));

        if( $request->hasFile('image') ) {
            
            $img = $request->file('image');
            $real_path = $img->getRealPath();
            $file_orgname = $img->getClientOriginalName();
            $file_size = $img->getClientSize();
            $file_ext = strtolower($img->getClientOriginalExtension());
            $file_newname = "mps"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
            $destinationPath = public_path('/uploads/files/media_images');
            $thumb_path = $destinationPath."/thumb";

            $imgObj = Image::make($real_path);
            $imgObj->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumb_path.'/'.$file_newname);

            $img->move($destinationPath, $file_newname);

            $Images = new Images;
            $Images->image = $file_newname;
            $Images->size = $file_size;
            $Images->extension = $file_ext;

            $Images->name = "Mineral Processing Image";
            $Images->alt_title = trim($request->input('image_alt'));
            $Images->caption = trim($request->input('image_caption'));
            $Images->title = trim($request->input('image_title'));

            $Images->created_by = Auth::user()->id;

            if($Images->save()) {

                $Mineral->image_id = $Images->id;  
            }
        }

        if( $Mineral->save() ) {

            return back()->with('msg', 'Mineral Added Successfully')
            ->with('msg_class', 'alert alert-success');
        }

        return back();   
    }

    public function mineralEdit($id) {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'homepage';
        $DataBag['childMenu'] = 'hMineral';
        
        $DataBag['mineral'] = Mineral::findOrFail($id);

        return view('dashboard.home.m.create', $DataBag);   
    }

    public function mineralUpdate(Request $request, $id) {

        $Mineral = Mineral::findOrFail($id);
        $Mineral->name = trim($request->input('name'));

        $Mineral->view_link = trim($request->input('view_link'));
        
        if($request->input('display_order') != '') {
            $Mineral->display_order = trim($request->input('display_order'));
        }

        $Mineral->image_title = trim($request->input('image_title'));
        $Mineral->image_alt = trim($request->input('image_alt'));
        $Mineral->image_caption = trim($request->input('image_caption'));

        if( $request->hasFile('image') ) {
            
            $img = $request->file('image');
            $real_path = $img->getRealPath();
            $file_orgname = $img->getClientOriginalName();
            $file_size = $img->getClientSize();
            $file_ext = strtolower($img->getClientOriginalExtension());
            $file_newname = "mps"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
            $destinationPath = public_path('/uploads/files/media_images');
            $thumb_path = $destinationPath."/thumb";

            $imgObj = Image::make($real_path);
            $imgObj->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumb_path.'/'.$file_newname);

            $img->move($destinationPath, $file_newname);

            $Images = new Images;
            $Images->image = $file_newname;
            $Images->size = $file_size;
            $Images->extension = $file_ext;

            $Images->name = "Mineral Processing Image";
            $Images->alt_title = trim($request->input('image_alt'));
            $Images->caption = trim($request->input('image_caption'));
            $Images->title = trim($request->input('image_title'));

            $Images->created_by = Auth::user()->id;

            if($Images->save()) {

                $Mineral->image_id = $Images->id;  
            }
        }

        if( $Mineral->save() ) {

            return back()->with('msg', 'Mineral Updated Successfully')
            ->with('msg_class', 'alert alert-success');
        }

        return back();   
    }

    public function mineralDelete($id) {

        Mineral::findOrFail($id)->delete();
        return back()->with('msg', 'Mineral Deleted Successfully')
        ->with('msg_class', 'alert alert-success');
    }

    public function mineralBulkAction(Request $request) {

        $msg = '';
        if( $request->has('action_btn') && $request->has('ids') ) {
            $actBtnValue = trim( $request->input('action_btn') );
            $idsArr = $request->input('ids');

            switch ( $actBtnValue ) {
                
                case 'activate':
                    foreach($idsArr as $id) {
                        $Mineral = Mineral::find($id);
                        $Mineral->status = '1';
                        $Mineral->save();
                    }
                    $msg = 'Mineral Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $Mineral = Mineral::find($id);
                        $Mineral->status = '2';
                        $Mineral->save();
                    }
                    $msg = 'Mineral Deactivated Succesfully.';
                    break;

                case 'delete':
                    foreach($idsArr as $id) {
                        $Mineral = Mineral::find($id);
                        $Mineral->delete();
                    }
                    $msg = 'Mineral Deleted Succesfully.';
                    break;
            }
            return back()->with('msg', $msg)->with('msg_class', 'alert alert-success');
        }
        return back();   
    }

    public function homeMap() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'homepage';
        $DataBag['childMenu'] = 'homeMap';
        $DataBag['map'] = HomeMap::find(1);

        return view('dashboard.home.map.create', $DataBag);  
    }

    public function homeMapAct(Request $request) {

        $HomeMap = HomeMap::find(1);

        if( !empty($HomeMap) ) {

            $HomeMap->small_heading = trim($request->input('small_heading'));
            $HomeMap->small_link = trim($request->input('small_link'));
            $HomeMap->big_heading_right = trim($request->input('big_heading_right'));
            $HomeMap->big_heading_left = trim($request->input('big_heading_left'));
            $HomeMap->big_link = trim($request->input('big_link'));

            if( $request->hasFile('small_image') ) {
            
                $img = $request->file('small_image');
                $real_path = $img->getRealPath();
                $file_orgname = $img->getClientOriginalName();
                $file_size = $img->getClientSize();
                $file_ext = strtolower($img->getClientOriginalExtension());
                $file_newname = "map"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
                $destinationPath = public_path('/uploads/files/media_images');
                $img->move($destinationPath, $file_newname);
                $HomeMap->small_image = $file_newname;
            }

            if( $request->hasFile('big_image') ) {
            
                $img = $request->file('big_image');
                $real_path = $img->getRealPath();
                $file_orgname = $img->getClientOriginalName();
                $file_size = $img->getClientSize();
                $file_ext = strtolower($img->getClientOriginalExtension());
                $file_newname = "map"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
                $destinationPath = public_path('/uploads/files/media_images');
                $img->move($destinationPath, $file_newname);
                $HomeMap->big_image = $file_newname;
            }

            $HomeMap->save();
        } else {
            $HomeMap = new HomeMap;

            $HomeMap->small_heading = trim($request->input('small_heading'));
            $HomeMap->small_link = trim($request->input('small_link'));
            $HomeMap->big_heading_right = trim($request->input('big_heading_right'));
            $HomeMap->big_heading_left = trim($request->input('big_heading_left'));
            $HomeMap->big_link = trim($request->input('big_link'));

            if( $request->hasFile('small_image') ) {
            
                $img = $request->file('small_image');
                $real_path = $img->getRealPath();
                $file_orgname = $img->getClientOriginalName();
                $file_size = $img->getClientSize();
                $file_ext = strtolower($img->getClientOriginalExtension());
                $file_newname = "map"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
                $destinationPath = public_path('/uploads/files/media_images');
                $img->move($destinationPath, $file_newname);
                $HomeMap->small_image = $file_newname;
            }

            if( $request->hasFile('big_image') ) {
            
                $img = $request->file('big_image');
                $real_path = $img->getRealPath();
                $file_orgname = $img->getClientOriginalName();
                $file_size = $img->getClientSize();
                $file_ext = strtolower($img->getClientOriginalExtension());
                $file_newname = "map"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
                $destinationPath = public_path('/uploads/files/media_images');
                $img->move($destinationPath, $file_newname);
                $HomeMap->big_image = $file_newname;
            }

            $HomeMap->save();
        }

        return back()->with('msg', 'All Content Saved Successfully')->with('msg_class', 'alert alert-success');
    }

    public function logos() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'homepage';
        $DataBag['childMenu'] = 'homeLogo';

        $DataBag['allList'] = DB::table('home_logo')->orderBy('display_order','asc')->get();

        return view('dashboard.home.logo.index', $DataBag);  
    }

    public function logoslAdd() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'homepage';
        $DataBag['childMenu'] = 'homeLogo';

        return view('dashboard.home.logo.create', $DataBag);
    }

    public function logosSave(Request $request) {

        $name = trim($request->input('name'));
        $link_file = trim($request->input('link_file'));
        if(trim($request->input('display_order')) != '') {
            $display_order = trim($request->input('display_order'));
        } else {
            $display_order = 0;
        }
        $image_title = trim($request->input('image_title'));
        $image_alt = trim($request->input('image_alt'));
        $image_caption = trim($request->input('image_caption'));

        $image = '';
        if( $request->hasFile('image') ) {
            
            $img = $request->file('image');
            $real_path = $img->getRealPath();
            $file_orgname = $img->getClientOriginalName();
            $file_size = $img->getClientSize();
            $file_ext = strtolower($img->getClientOriginalExtension());
            $file_newname = "logo"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
            $destinationPath = public_path('/uploads/files/media_images');
            $img->move($destinationPath, $file_newname);
            $image = $file_newname;
        }

        $r = DB::table('home_logo')->insert([
            'name' => $name,
            'display_order' => $display_order,
            'image' => $image,
            'image_title' => $image_title,
            'image_alt' => $image_alt,
            'image_caption' => $image_caption,
            'link_file' => $link_file,
            'status' => '1'
        ]);

        if($r) {
            return back()->with('msg', 'Logo Saved Successfully')->with('msg_class', 'alert alert-success');   
        }

        return back();
    }

    public function logosEdit($id) {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'homepage';
        $DataBag['childMenu'] = 'homeLogo';
        $DataBag['logo'] = DB::table('home_logo')->where('id', '=', $id)->first();
        return view('dashboard.home.logo.create', $DataBag);
    }

    public function logosUpdate(Request $request, $id) {

        $updateArr = array();

        $updateArr['name'] = trim($request->input('name'));
        $updateArr['link_file'] = trim($request->input('link_file'));
        if(trim($request->input('display_order')) != '') {
            $updateArr['display_order'] = trim($request->input('display_order'));
        } 
        $updateArr['image_title'] = trim($request->input('image_title'));
        $updateArr['image_alt'] = trim($request->input('image_alt'));
        $updateArr['image_caption'] = trim($request->input('image_caption'));

        if( $request->hasFile('image') ) {
            
            $img = $request->file('image');
            $real_path = $img->getRealPath();
            $file_orgname = $img->getClientOriginalName();
            $file_size = $img->getClientSize();
            $file_ext = strtolower($img->getClientOriginalExtension());
            $file_newname = "logo"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
            $destinationPath = public_path('/uploads/files/media_images');
            $img->move($destinationPath, $file_newname);
            $updateArr['image'] = $file_newname;
        }

        $r = DB::table('home_logo')->where('id', '=', $id)->update($updateArr);

        if($r) {
            return back()->with('msg', 'Logo Updated Successfully')->with('msg_class', 'alert alert-success');   
        }

        return back();
    }

    public function logosDelete($id) {

        $r = DB::table('home_logo')->where('id', '=', $id)->delete();
        if($r) {
            return back()->with('msg', 'Logo Deleted Successfully')->with('msg_class', 'alert alert-success');
        }
        return back();
    }
    
}


