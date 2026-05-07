<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CmsLinks;
use App\Models\Media\Images;
use App\Models\Media\FilesMaster;
use App\Models\Industry\Industries;
use App\Models\Industry\IndustryFilesMap;
use App\Models\Industry\IndustryImagesMap;
use App\Models\Languages;
use App\Models\PageBuilder\PageBuilder;
use File;
use Image;
use Auth;
use DB;

class IndustryController extends Controller
{
    
    public function index() {
    	$DataBag = array();
    	$DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'IndustryManagement';
    	$DataBag['childMenu'] = 'indusList';
    	$DataBag['allIndustries'] = Industries::where('status', '!=', '3')->where('parent_language_id', '=', '0')
    	->orderBy('created_at', 'desc')->get();
    	return view('dashboard.Industry.index', $DataBag);
    }

    public function addIndustry() {
    	$DataBag = array();
    	$DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'IndustryManagement';
    	$DataBag['childMenu'] = 'addIndus';
    	$DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
    	$DataBag['insert_id'] = md5(microtime(TRUE));
    	return view('dashboard.Industry.create', $DataBag);
    }

    public function saveIndustry(Request $request) {

    	$insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time


    	$Industries = new Industries;
    	$Industries->name = trim( ucfirst($request->input('name')) );
    	$Industries->slug = trim($request->input('slug'));
    	$Industries->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
    	$Industries->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
    	$Industries->language_id = trim( $request->input('language_id') );
    	$Industries->insert_id = $insert_id;

    	$Industries->meta_title = trim($request->input('meta_title'));
        $Industries->meta_desc = trim($request->input('meta_desc'));
        $Industries->meta_keyword = trim($request->input('meta_keyword'));
        $Industries->canonical_url = trim($request->input('canonical_url'));
        $Industries->lng_tag = trim($request->input('lng_tag'));
        $Industries->follow = trim($request->input('follow'));
        $Industries->index_tag = trim($request->input('index_tag'));
        $Industries->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );
    	
    	$Industries->created_by = Auth::user()->id;

        $bannerImageJson = json_decode( trim( $request->input('banner_image_infos') ) );
    	
    	if( $Industries->save() ) {

    		$industry_id = $Industries->id;

    		$CmsLinks = new CmsLinks;
    		$CmsLinks->table_id = $industry_id;
    		$CmsLinks->slug_url = trim($request->input('slug'));
    		$CmsLinks->table_type = 'INDUSTRY';
    		$CmsLinks->save();
    		$cms_link_id = $CmsLinks->id; // Need for page builder as parameter

            /** For Page Builder -- Insert Time **/
            /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
            update_page_builder($insert_id, $cms_link_id, $industry_id, 'INDUSTRY');
            /** End Page Builder **/

            if( !empty($bannerImageJson) ) {
                $imageMap = array();
                foreach ($bannerImageJson as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['industry_id'] = $industry_id;
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
                    IndustryImagesMap::insert($imageMap);
                }
            }
	    	
	    return back()->with('msg', 'Industry Created Successfully.')
    	->with('msg_class', 'alert alert-success');

    	}
    
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    
    }

    public function deleteIndustry($industry_id) {

    	$ck = Industries::find($industry_id);
    	if( isset($ck) && !empty($ck) ) {
    		$ck->status = '3';
    		$res = $ck->save();
    		if( isset($res) && $res == 1 ) {

    			delete_navigation($industry_id, 'INDUSTRY');
    			IndustryImagesMap::where('industry_id', '=', $industry_id)->delete();
    			IndustryFilesMap::where('industry_id', '=', $industry_id)->delete();
    			CmsLinks::where('table_type', '=', 'INDUSTRY')->where('table_id', '=', $industry_id)->delete();

    			PageBuilder::where('table_id', '=', $industry_id)->where('table_type', '=', 'INDUSTRY')->delete();
    			
    			return back()->with('msg', 'Industry Deleted Successfully.')
    			->with('msg_class', 'alert alert-success');
    		}
    	}

    	return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }



    public function editIndustry($industry_id) {
    	$DataBag = array();
    	$DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'IndustryManagement';
    	$DataBag['childMenu'] = 'addIndus';
    	$DataBag['industry'] = Industries::findOrFail($industry_id);
    	$DataBag['pageBuilderData'] = $DataBag['industry']; /* For pagebuilder */
    	$DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
    	return view('dashboard.Industry.create', $DataBag);
    }

    public function updateIndustry(Request $request, $industry_id) {

    	$insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

    	$Industries = Industries::find($industry_id);
    	$Industries->name = trim( ucfirst($request->input('name')) );
    	$Industries->slug = trim($request->input('slug'));
    	$Industries->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
    	$Industries->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );

    	$Industries->meta_title = trim($request->input('meta_title'));
        $Industries->meta_desc = trim($request->input('meta_desc'));
        $Industries->meta_keyword = trim($request->input('meta_keyword'));
        $Industries->canonical_url = trim($request->input('canonical_url'));
        $Industries->lng_tag = trim($request->input('lng_tag'));
        $Industries->follow = trim($request->input('follow'));
        $Industries->index_tag = trim($request->input('index_tag'));
        $Industries->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );
    	
    	$Industries->updated_by = Auth::user()->id;

        $bannerImageJson = json_decode( trim( $request->input('banner_image_infos') ) );
    	
    	if( $Industries->save() ) {

    		CmsLinks::where('table_type', '=', 'INDUSTRY')->where('table_id', '=', $industry_id)
    		->update( [ 'slug_url' => trim($request->input('slug')) ] );
    		
    		/** Need For Page Builder -- Update Time **/
            $cmsInfo = CmsLinks::where('table_id', '=', $industry_id)->where('table_type', '=', 'INDUSTRY')->first();
            if( !empty($cmsInfo) ) {
                
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                update_page_builder($insert_id, $cmsInfo->id, $industry_id, 'INDUSTRY');

            }
            /** End Page Builder **/

            if( !empty($bannerImageJson) ) {
                $imageMap = array();
                foreach ($bannerImageJson as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['industry_id'] = $industry_id;
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
                    IndustryImagesMap::insert($imageMap);
                }
            }

	    return back()->with('msg', 'Industry Updated Successfully.')
    	->with('msg_class', 'alert alert-success');

    	}
    
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');

    }















    public function addEditLanguage($parent_language_id, $child_language_id = '') {

    	$DataBag = array();
    	$DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'IndustryManagement';
    	$DataBag['childMenu'] = 'addIndus';
    	$DataBag['parentLngCont'] = Industries::findOrFail($parent_language_id);
    	if( $child_language_id != '' ) {
    		$DataBag['industry'] = Industries::findOrFail($child_language_id);
    		$DataBag['pageBuilderData'] = $DataBag['industry'];
    	}
    	$DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
    	$DataBag['insert_id'] = md5(microtime(TRUE));
    	return view('dashboard.Industry.addedit_language', $DataBag);
    }

    public function addEditLanguagePost(Request $request, $parent_language_id, $child_language_id = '') {
    	
    	if( $child_language_id != '' && $child_language_id != null ) {

    		$insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

	    	$Industries = Industries::find($child_language_id);
	    	$Industries->name = trim( ucfirst($request->input('name')) );
	    	$Industries->slug = trim($request->input('slug'));
	    	$Industries->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
	    	$Industries->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );

	    	$Industries->meta_title = trim($request->input('meta_title'));
	        $Industries->meta_desc = trim($request->input('meta_desc'));
	        $Industries->meta_keyword = trim($request->input('meta_keyword'));
	        $Industries->canonical_url = trim($request->input('canonical_url'));
	        $Industries->lng_tag = trim($request->input('lng_tag'));
	        $Industries->follow = trim($request->input('follow'));
	        $Industries->index_tag = trim($request->input('index_tag'));
	    	
	    	$Industries->updated_by = Auth::user()->id;

            $bannerImageJson = json_decode( trim( $request->input('banner_image_infos') ) );
	    	
	    	if( $Industries->save() ) {

	    		$industry_id = $child_language_id;

	    		CmsLinks::where('table_type', '=', 'INDUSTRY')->where('table_id', '=', $industry_id)
	    		->update( [ 'slug_url' => trim($request->input('slug')) ] );

	    		/** Need For Page Builder -- Update Time **/
	            $cmsInfo = CmsLinks::where('table_id', '=', $industry_id)->where('table_type', '=', 'INDUSTRY')->first();
	            if( !empty($cmsInfo) ) {
	                
	                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
	                update_page_builder($insert_id, $cmsInfo->id, $industry_id, 'INDUSTRY');

	            }
	            /** End Page Builder **/

                if( !empty($bannerImageJson) ) {
                    $imageMap = array();
                    foreach ($bannerImageJson as $v) {
                        if( $v->img_id != '' ) {
                            $arr = array();
                            $arr['industry_id'] = $industry_id;
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
                        IndustryImagesMap::insert($imageMap);
                    }
                }
	    		
		    	return back()->with('msg', 'Industry Updated Successfully.')
	    		->with('msg_class', 'alert alert-success');
	    	}
    	}

    	if( $child_language_id == '' ) {

    		$insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

	    	$Industries = new Industries;
	    	$Industries->name = trim( ucfirst($request->input('name')) );
	    	$Industries->slug = trim($request->input('slug'));
	    	$Industries->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
	    	$Industries->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
	    	$Industries->insert_id = $insert_id;

	    	$Industries->meta_title = trim($request->input('meta_title'));
	        $Industries->meta_desc = trim($request->input('meta_desc'));
	        $Industries->meta_keyword = trim($request->input('meta_keyword'));
	        $Industries->canonical_url = trim($request->input('canonical_url'));
	        $Industries->lng_tag = trim($request->input('lng_tag'));
	        $Industries->follow = trim($request->input('follow'));
	        $Industries->index_tag = trim($request->input('index_tag'));
	    	
	    	$Industries->created_by = Auth::user()->id;
	    	$Industries->language_id = trim( $request->input('language_id') );
	    	$Industries->parent_language_id = $parent_language_id;

            $bannerImageJson = json_decode( trim( $request->input('banner_image_infos') ) );

	    	
	    	if( $Industries->save() ) {

	    		$industry_id = $Industries->id;

	    		$CmsLinks = new CmsLinks;
	    		$CmsLinks->table_id = $industry_id;
	    		$CmsLinks->slug_url = trim($request->input('slug'));
	    		$CmsLinks->table_type = 'INDUSTRY';
	    		$CmsLinks->save();

	    		$cms_link_id = $CmsLinks->id; // Need for page builder as parameter

	            /** For Page Builder -- Insert Time **/
	            /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
	            update_page_builder($insert_id, $cms_link_id, $industry_id, 'INDUSTRY');
	            /** End Page Builder **/

                if( !empty($bannerImageJson) ) {
                    $imageMap = array();
                    foreach ($bannerImageJson as $v) {
                        if( $v->img_id != '' ) {
                            $arr = array();
                            $arr['industry_id'] = $industry_id;
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
                        IndustryImagesMap::insert($imageMap);
                    }
                }

		    	return redirect()->route('edtIndus', array('id' => $parent_language_id))
		    	->with('msg', 'Industry Created Successfully.')
	    		->with('msg_class', 'alert alert-success');
	    	}
    	}

    	return back();
    }

    public function deleteLanguage($parent_language_id, $child_language_id) {

    	Industries::findOrFail($child_language_id)->delete();
    	IndustryImagesMap::where('industry_id', '=', $child_language_id)->delete();
    	//IndustryFilesMap::where('industry_id', '=', $child_language_id)->delete();
    	CmsLinks::where('table_type', '=', 'INDUSTRY')->where('table_id', '=', $child_language_id)->delete();

    	delete_navigation($child_language_id, 'INDUSTRY');
    	PageBuilder::where('table_id', '=', $child_language_id)->where('table_type', '=', 'INDUSTRY')->delete();

    	return redirect()->route('edtIndus', array('id' => $parent_language_id))
		->with('msg', 'Industry Deleted Successfully.')
	    ->with('msg_class', 'alert alert-success');
    	
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
                        $Industries = Industries::find($id);
                        $Industries->status = '1';
                        $Industries->save();
                    }
                    $msg = 'Industries Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $Industries = Industries::find($id);
                        $Industries->status = '2';
                        $Industries->save();
                    }
                    $msg = 'Industries Deactivated Succesfully.';
                    break;

                case 'delete':
                    foreach($idsArr as $id) {
                        $Industries = Industries::find($id);
                        $Industries->status = '3';
                        $Industries->save();
                        IndustryImagesMap::where('industry_id', '=', $id)->delete();
    					IndustryFilesMap::where('industry_id', '=', $id)->delete();
    					CmsLinks::where('table_type', '=', 'INDUSTRY')->where('table_id', '=', $id)->delete();

    					delete_navigation($id, 'INDUSTRY');
    					PageBuilder::where('table_id', '=', $id)->where('table_type', '=', 'INDUSTRY')->delete();
                    }
                    $msg = 'Industries Deleted Succesfully.';
                    break;
            }
            return back()->with('msg', $msg)->with('msg_class', 'alert alert-success');
        }
        return back();
    }
}
