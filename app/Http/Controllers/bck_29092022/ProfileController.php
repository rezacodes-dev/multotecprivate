<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CmsLinks;
use App\Models\Media\Images;
use App\Models\Media\FilesMaster;
use App\Models\PeoplesProfile\PeopleProfileCategories;
use App\Models\PeoplesProfile\PeoplesProfile;
use App\Models\PeoplesProfile\PeoplesProfileFilesMap;
use App\Models\PeoplesProfile\PeoplesProfileImagesMap;
use App\Models\PeoplesProfile\PeoplesProfileCategoriesMap;
use App\Models\Languages;
use App\Models\PageBuilder\PageBuilder;
use App\Models\Media\MediaExtraContent;
use File;
use Image;
use Auth;
use DB;

class ProfileController extends Controller
{
    
    public function allCategories() {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'peopleManagement';
    	$DataBag['childMenu'] = 'profileCats';
    	$DataBag['allppCats'] = PeopleProfileCategories::where('status', '!=', '3')
        ->where('parent_language_id', '=', '0')->orderBy('created_at', 'desc')->get();
    	return view('dashboard.profile.all_categories', $DataBag);
    }

    public function addCategory() {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'peopleManagement';
    	$DataBag['childMenu'] = 'profileAddCat';
        $DataBag['languages'] = Languages::where('status', '=', '1')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));
    	return view('dashboard.profile.create_category', $DataBag);
    }

    public function saveCategory(Request $request) {

        $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

    	$PeopleProfileCategories = new PeopleProfileCategories;
    	$PeopleProfileCategories->name = trim(ucfirst($request->input('name')));
    	$PeopleProfileCategories->slug = trim($request->input('slug'));
    	$PeopleProfileCategories->page_content = htmlentities( trim($request->input('page_content')), ENT_QUOTES);
        $PeopleProfileCategories->mob_page_content = htmlentities( trim($request->input('mob_page_content')), ENT_QUOTES);
    	$PeopleProfileCategories->created_by = Auth::user()->id;
        $PeopleProfileCategories->language_id = trim($request->input('language_id'));
        $PeopleProfileCategories->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
        $PeopleProfileCategories->insert_id = $insert_id;

        $PeopleProfileCategories->display_order = trim($request->input('display_order'));

        /*$PeopleProfileCategories->meta_title = trim($request->input('meta_title'));
        $PeopleProfileCategories->meta_desc = trim($request->input('meta_desc'));
        $PeopleProfileCategories->meta_keyword = trim($request->input('meta_keyword'));
        $PeopleProfileCategories->canonical_url = trim($request->input('canonical_url'));
        $PeopleProfileCategories->lng_tag = trim($request->input('lng_tag'));
        $PeopleProfileCategories->follow = trim($request->input('follow'));
        $PeopleProfileCategories->index_tag = trim($request->input('index_tag'));
        $PeopleProfileCategories->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );*/

        $PeopleProfileCategories->image_title = trim($request->input('image_title'));
        $PeopleProfileCategories->image_alt = trim($request->input('image_alt'));
        $PeopleProfileCategories->image_caption = trim($request->input('image_caption'));

        if( $request->hasFile('page_banner') ) {
            
            $img = $request->file('page_banner');
            $real_path = $img->getRealPath();
            $file_orgname = $img->getClientOriginalName();
            $file_size = $img->getClientSize();
            $file_ext = strtolower($img->getClientOriginalExtension());
            $file_newname = "banner"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
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

            $Images->name = "Banner Image";
            $Images->alt_title = trim($request->input('image_alt'));
            $Images->caption = trim($request->input('image_caption'));
            $Images->title = trim($request->input('image_title'));

            $Images->created_by = Auth::user()->id;

            if($Images->save()) {

                $PeopleProfileCategories->image_id = $Images->id;  
            }
        }

    	if( $PeopleProfileCategories->save() ) {
    		
    		$people_profile_category_id = $PeopleProfileCategories->id;

    		$CmsLinks = new CmsLinks;
    		$CmsLinks->table_id = $people_profile_category_id;
    		$CmsLinks->slug_url = trim($request->input('slug'));
    		$CmsLinks->table_type = 'PEOPLE_PROFILE_CATEGORY';
    		$CmsLinks->save();
            //$cms_link_id = $CmsLinks->id; // Need for page builder as parameter

            /** For Page Builder -- Insert Time **/
            /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
            //update_page_builder($insert_id, $cms_link_id, $people_profile_category_id, 'PEOPLE_PROFILE_CATEGORY');
            /** End Page Builder **/

    		return back()->with('msg', 'People Profile Category Created Succesfully')->with('msg_class', 'alert alert-success');
    	}
    return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }




    public function editCategory($ppc_id) {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'peopleManagement';
    	$DataBag['childMenu'] = 'profileAddCat';
    	$DataBag['profileCat'] = PeopleProfileCategories::findOrFail($ppc_id);
        $DataBag['pageBuilderData'] = $DataBag['profileCat']; /* For pagebuilder */
        $DataBag['languages'] = Languages::where('status', '=', '1')->get();
    	return view('dashboard.profile.create_category', $DataBag);
    }

    public function updateCategory(Request $request, $ppc_id) {

        $insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

    	$PeopleProfileCategories = PeopleProfileCategories::find($ppc_id);
    	$PeopleProfileCategories->name = trim(ucfirst($request->input('name')));
    	$PeopleProfileCategories->slug = trim($request->input('slug'));
    	$PeopleProfileCategories->page_content = htmlentities( trim($request->input('page_content')), ENT_QUOTES);
        $PeopleProfileCategories->mob_page_content = htmlentities( trim($request->input('mob_page_content')), ENT_QUOTES);
    	$PeopleProfileCategories->updated_by = Auth::user()->id;
        $PeopleProfileCategories->language_id = trim($request->input('language_id'));
        $PeopleProfileCategories->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );

        $PeopleProfileCategories->display_order = trim($request->input('display_order'));

        /*$PeopleProfileCategories->meta_title = trim($request->input('meta_title'));
        $PeopleProfileCategories->meta_desc = trim($request->input('meta_desc'));
        $PeopleProfileCategories->meta_keyword = trim($request->input('meta_keyword'));
        $PeopleProfileCategories->canonical_url = trim($request->input('canonical_url'));
        $PeopleProfileCategories->lng_tag = trim($request->input('lng_tag'));
        $PeopleProfileCategories->follow = trim($request->input('follow'));
        $PeopleProfileCategories->index_tag = trim($request->input('index_tag'));
        $PeopleProfileCategories->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );*/

        $PeopleProfileCategories->image_title = trim($request->input('image_title'));
        $PeopleProfileCategories->image_alt = trim($request->input('image_alt'));
        $PeopleProfileCategories->image_caption = trim($request->input('image_caption'));


        if( $request->hasFile('page_banner') ) {
            
            $img = $request->file('page_banner');
            $real_path = $img->getRealPath();
            $file_orgname = $img->getClientOriginalName();
            $file_size = $img->getClientSize();
            $file_ext = strtolower($img->getClientOriginalExtension());
            $file_newname = "banner"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
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

            $Images->name = "Banner Image";
            $Images->alt_title = trim($request->input('image_alt'));
            $Images->caption = trim($request->input('image_caption'));
            $Images->title = trim($request->input('image_title'));

            $Images->created_by = Auth::user()->id;

            if($Images->save()) {

                $PeopleProfileCategories->image_id = $Images->id;  
            }
        }

    	if( $PeopleProfileCategories->save() ) {
    		
    		$people_profile_category_id = $ppc_id;

    		CmsLinks::where('table_type', '=', 'PEOPLE_PROFILE_CATEGORY')->where('table_id', '=', $people_profile_category_id)
    		->update( ['slug_url' => trim($request->input('slug'))] );

            /** Need For Page Builder -- Update Time **/
            //$cmsInfo = CmsLinks::where('table_id', '=', $people_profile_category_id)->where('table_type', '=', 'PEOPLE_PROFILE_CATEGORY')->first();
            //if( !empty($cmsInfo) ) {
                
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                //update_page_builder($insert_id, $cmsInfo->id, $people_profile_category_id, 'PEOPLE_PROFILE_CATEGORY');

            //}
            /** End Page Builder **/
    		
    		return back()->with('msg', 'People Profile Category Updated Succesfully')->with('msg_class', 'alert alert-success');
    	}
    return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function deleteCategory($ppc_id) {

    	$ck = PeopleProfileCategories::find($ppc_id);
    	if( isset($ck) && !empty($ck) ) {
    		$ck->status = '3';
    		$res = $ck->save();
    		if( isset($res) && $res == 1 ) {
    			
                delete_navigation($ppc_id, 'PEOPLE_PROFILE_CATEGORY');
    			CmsLinks::where('table_type', '=', 'PEOPLE_PROFILE_CATEGORY')->where('table_id', '=', $ppc_id)->delete();
                PageBuilder::where('table_id', '=', $ppc_id)->where('table_type', '=', 'PEOPLE_PROFILE_CATEGORY')->delete();
    			
    			return back()->with('msg', 'People Profile Category Deleted Successfully.')
    			->with('msg_class', 'alert alert-success');
    		}
    	}

    	return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }




    public function index() {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'peopleManagement';
    	$DataBag['childMenu'] = 'profileList';
    	$DataBag['allProfiles'] = PeoplesProfile::where('status', '!=', '3')
        ->where('parent_language_id', '=', '0')->orderBy('created_at', 'desc')->get();
    	return view('dashboard.profile.index', $DataBag);
    }

    public function addProfile() {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'peopleManagement';
    	$DataBag['childMenu'] = 'profileAdd';
    	$DataBag['allCats'] = PeopleProfileCategories::where('status', '=', '1')->orderBy('name', 'asc')->get();
        $DataBag['languages'] = Languages::where('status', '=', '1')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));
    	return view('dashboard.profile.add', $DataBag);
    }

    public function saveProfile(Request $request) {

    	$categoriesMap = array();

        $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

    	$PeoplesProfile = new PeoplesProfile;
    	$PeoplesProfile->name = trim( ucfirst($request->input('name')) );
    	$PeoplesProfile->slug = trim($request->input('slug'));
    	$PeoplesProfile->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
    	$PeoplesProfile->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
    	$PeoplesProfile->designation = trim($request->input('designation'));
    	$PeoplesProfile->created_by = Auth::user()->id;
        $PeoplesProfile->language_id = trim( $request->input('language_id') );

        $PeoplesProfile->insert_id = $insert_id;
        $PeoplesProfile->display_order = trim($request->input('display_order'));

        $PeoplesProfile->meta_title = trim($request->input('meta_title'));
        $PeoplesProfile->meta_desc = trim($request->input('meta_desc'));
        $PeoplesProfile->meta_keyword = trim($request->input('meta_keyword'));
        $PeoplesProfile->canonical_url = trim($request->input('canonical_url'));
        $PeoplesProfile->lng_tag = trim($request->input('lng_tag'));
        $PeoplesProfile->follow = trim($request->input('follow'));
        $PeoplesProfile->index_tag = trim($request->input('index_tag'));
        $PeoplesProfile->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );

        $profileImgJson = json_decode( trim( $request->input('prof_image_infos') ) );

    	if( $PeoplesProfile->save() ) {

    		$people_profile_id = $PeoplesProfile->id;

    		$CmsLinks = new CmsLinks;
    		$CmsLinks->table_id = $people_profile_id;
    		$CmsLinks->slug_url = trim($request->input('slug'));
    		$CmsLinks->table_type = 'PEOPLE_PROFILE';
    		$CmsLinks->save();
            $cms_link_id = $CmsLinks->id; // Need for page builder as parameter

            /** For Page Builder -- Insert Time **/
            /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
            update_page_builder($insert_id, $cms_link_id, $people_profile_id, 'PEOPLE_PROFILE');
            /** End Page Builder **/

            if( !empty($profileImgJson) ) {
                $imageMap = array();
                foreach ($profileImgJson as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['people_profile_id'] = $people_profile_id;
                        $arr['image_id'] = $v->img_id;
                        $arr['title'] = $v->img_titl;
                        $arr['caption'] = $v->img_cap;
                        $arr['alt_tag'] = $v->img_alt;
                        $arr['description'] = $v->img_dsc;
                        $arr['image_type'] = "MAIN_IMAGE";
                        array_push( $imageMap, $arr );
                    }
                }

                if( !empty($imageMap) ) {
                    PeoplesProfileImagesMap::insert($imageMap);
                }
            }

	    	if( $request->has('category_id') ) {
	    		foreach( $request->input('category_id') as $cats ) {
                    if( $cats != '' ) {
    	    			$arr = array();
    	    			$arr['people_profile_id'] = $people_profile_id;
    	    			$arr['people_profile_category_id'] = $cats;
    	    			array_push( $categoriesMap, $arr );
                    }
	    		}
	    		if( !empty($categoriesMap) ) {
	    			PeoplesProfileCategoriesMap::insert( $categoriesMap );
	    		}
	    	}
	    return back()->with('msg', 'People Profile Created Successfully.')
    	->with('msg_class', 'alert alert-success');
    	}
    
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }




    public function editProfile($pp_id) {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'peopleManagement';
    	$DataBag['childMenu'] = 'profileAdd';
    	$DataBag['profile'] = PeoplesProfile::findOrFail($pp_id);
        $DataBag['pageBuilderData'] = $DataBag['profile']; /* For pagebuilder */
    	$DataBag['allCats'] = PeopleProfileCategories::where('status', '=', '1')->orderBy('name', 'asc')->get();
        $DataBag['languages'] = Languages::where('status', '=', '1')->get();
    	return view('dashboard.profile.add', $DataBag);
    }

    public function updateProfile(Request $request, $pp_id) {

        $insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

    	$categoriesMap = array();
    	
    	$PeoplesProfile = PeoplesProfile::find($pp_id);
    	$PeoplesProfile->name = trim( ucfirst($request->input('name')) );
    	$PeoplesProfile->slug = trim($request->input('slug'));
    	$PeoplesProfile->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
    	$PeoplesProfile->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
    	$PeoplesProfile->designation = trim($request->input('designation'));
    	$PeoplesProfile->updated_by = Auth::user()->id;
        $PeoplesProfile->language_id = trim( $request->input('language_id') );

        $PeoplesProfile->display_order = trim($request->input('display_order'));

    	$PeoplesProfile->meta_title = trim($request->input('meta_title'));
        $PeoplesProfile->meta_desc = trim($request->input('meta_desc'));
        $PeoplesProfile->meta_keyword = trim($request->input('meta_keyword'));
        $PeoplesProfile->canonical_url = trim($request->input('canonical_url'));
        $PeoplesProfile->lng_tag = trim($request->input('lng_tag'));
        $PeoplesProfile->follow = trim($request->input('follow'));
        $PeoplesProfile->index_tag = trim($request->input('index_tag'));
        $PeoplesProfile->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );

        $profileImgJson = json_decode( trim( $request->input('prof_image_infos') ) );

    	if( $PeoplesProfile->save() ) {

    		$people_profile_id = $pp_id;

    		CmsLinks::where('table_type', '=', 'PEOPLE_PROFILE')->where('table_id', '=', $people_profile_id)
    		->update( ['slug_url' => trim($request->input('slug'))] );

            /** Need For Page Builder -- Update Time **/
            $cmsInfo = CmsLinks::where('table_id', '=', $people_profile_id)->where('table_type', '=', 'PEOPLE_PROFILE')->first();
            if( !empty($cmsInfo) ) {
                
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                update_page_builder($insert_id, $cmsInfo->id, $people_profile_id, 'PEOPLE_PROFILE');

            }
            /** End Page Builder **/

            if( !empty($profileImgJson) ) {
                $imageMap = array();
                foreach ($profileImgJson as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['people_profile_id'] = $people_profile_id;
                        $arr['image_id'] = $v->img_id;
                        $arr['title'] = $v->img_titl;
                        $arr['caption'] = $v->img_cap;
                        $arr['alt_tag'] = $v->img_alt;
                        $arr['description'] = $v->img_dsc;
                        $arr['image_type'] = "MAIN_IMAGE";
                        array_push( $imageMap, $arr );
                    }
                }

                if( !empty($imageMap) ) {
                    PeoplesProfileImagesMap::insert($imageMap);
                }
            }
            
	    	PeoplesProfileCategoriesMap::where('people_profile_id', '=', $people_profile_id)->delete();
	    	if( $request->has('category_id') ) {
	    		foreach( $request->input('category_id') as $cats ) {
                    if( $cats != '' ) {
    	    			$arr = array();
    	    			$arr['people_profile_id'] = $people_profile_id;
    	    			$arr['people_profile_category_id'] = $cats;
    	    			array_push( $categoriesMap, $arr );
                    }
	    		}
	    		if( !empty($categoriesMap) ) {
	    			PeoplesProfileCategoriesMap::insert( $categoriesMap );
	    		}
	    	}
	    return back()->with('msg', 'People Profile Updated Successfully.')
    	->with('msg_class', 'alert alert-success');
    	}
    
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }


    public function deleteProfile($pp_id) {

        $ck = PeoplesProfile::find($pp_id);
        if( isset($ck) && !empty($ck) ) {
            $ck->status = '3';
            $res = $ck->save();
            if( isset($res) && $res == 1 ) {

                PeoplesProfileCategoriesMap::where('people_profile_id', '=', $pp_id)->delete();
                //PeoplesProfileImagesMap::where('people_profile_id', '=', $pp_id)->delete();
                //PeoplesProfileFilesMap::where('people_profile_id', '=', $pp_id)->delete();
                CmsLinks::where('table_type', '=', 'PEOPLE_PROFILE')->where('table_id', '=', $pp_id)->delete();

                delete_navigation($pp_id, 'PEOPLE_PROFILE');
                PageBuilder::where('table_id', '=', $pp_id)->where('table_type', '=', 'PEOPLE_PROFILE')->delete();


                return back()->with('msg', 'People Profile Deleted Succesfully.')->with('msg_class', 'alert alert-success');
            }
        }
        return back()->with('msg', 'Something Went Wrong.')->with('msg_class', 'alert alert-danger');
    }













    /* Language */

    public function addEditLngProfile($pid, $cid = '') {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'peopleManagement';
        $DataBag['childMenu'] = 'profileAdd';
        $DataBag['parentLngCont'] = PeoplesProfile::findOrFail($pid);
        if( $cid != '' ) {
            $DataBag['profile'] = PeoplesProfile::findOrFail($cid);
            $DataBag['pageBuilderData'] = $DataBag['profile'];
        }
        $DataBag['allCats'] = PeopleProfileCategories::where('status', '=', '1')->orderBy('name', 'asc')->get();
        $DataBag['languages'] = Languages::where('status', '=', '1')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));

        return view('dashboard.profile.add_language', $DataBag);
    }

    public function addEditLngProfilePost(Request $request, $pid, $cid = '') {

        if( $cid != '' && $cid != null ) {

            $insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

            $categoriesMap = array();

            $PeoplesProfile = PeoplesProfile::find($cid);
            $PeoplesProfile->name = trim( ucfirst($request->input('name')) );
            $PeoplesProfile->slug = trim($request->input('slug'));
            $PeoplesProfile->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
            $PeoplesProfile->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $PeoplesProfile->designation = trim($request->input('designation'));
            $PeoplesProfile->updated_by = Auth::user()->id;
            $PeoplesProfile->language_id = trim( $request->input('language_id') );
            $PeoplesProfile->parent_language_id = $pid;

            $PeoplesProfile->meta_title = trim($request->input('meta_title'));
            $PeoplesProfile->meta_desc = trim($request->input('meta_desc'));
            $PeoplesProfile->meta_keyword = trim($request->input('meta_keyword'));
            $PeoplesProfile->canonical_url = trim($request->input('canonical_url'));
            $PeoplesProfile->lng_tag = trim($request->input('lng_tag'));
            $PeoplesProfile->follow = trim($request->input('follow'));
            $PeoplesProfile->index_tag = trim($request->input('index_tag'));

            if( $PeoplesProfile->save() ) {

                $people_profile_id = $cid;

                CmsLinks::where('table_type', '=', 'PEOPLE_PROFILE')->where('table_id', '=', $people_profile_id)
                ->update( ['slug_url' => trim($request->input('slug'))] );

                /** Need For Page Builder -- Update Time **/
                $cmsInfo = CmsLinks::where('table_id', '=', $people_profile_id)->where('table_type', '=', 'PEOPLE_PROFILE')->first();
                if( !empty($cmsInfo) ) {
                    
                    /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                    update_page_builder($insert_id, $cmsInfo->id, $people_profile_id, 'PEOPLE_PROFILE');

                }
                /** End Page Builder **/


                PeoplesProfileCategoriesMap::where('people_profile_id', '=', $people_profile_id)->delete();
                if( $request->has('category_id') ) {
                    foreach( $request->input('category_id') as $cats ) {
                        $arr = array();
                        $arr['people_profile_id'] = $people_profile_id;
                        $arr['people_profile_category_id'] = $cats;
                        array_push( $categoriesMap, $arr );
                    }
                    if( !empty($categoriesMap) ) {
                        PeoplesProfileCategoriesMap::insert( $categoriesMap );
                    }
                }
                return back()->with('msg', 'People Profile Updated Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
        }

        if( $cid == '' ) {

            $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

            $categoriesMap = array();

            $PeoplesProfile = new PeoplesProfile;
            $PeoplesProfile->name = trim( ucfirst($request->input('name')) );
            $PeoplesProfile->slug = trim($request->input('slug'));
            $PeoplesProfile->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
            $PeoplesProfile->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $PeoplesProfile->designation = trim($request->input('designation'));
            $PeoplesProfile->created_by = Auth::user()->id;
            $PeoplesProfile->language_id = trim( $request->input('language_id') );
            $PeoplesProfile->parent_language_id = $pid;

            $PeoplesProfile->insert_id = $insert_id;

            $PeoplesProfile->meta_title = trim($request->input('meta_title'));
            $PeoplesProfile->meta_desc = trim($request->input('meta_desc'));
            $PeoplesProfile->meta_keyword = trim($request->input('meta_keyword'));
            $PeoplesProfile->canonical_url = trim($request->input('canonical_url'));
            $PeoplesProfile->lng_tag = trim($request->input('lng_tag'));
            $PeoplesProfile->follow = trim($request->input('follow'));
            $PeoplesProfile->index_tag = trim($request->input('index_tag'));

            if( $PeoplesProfile->save() ) {

                $people_profile_id = $PeoplesProfile->id;

                $CmsLinks = new CmsLinks;
                $CmsLinks->table_id = $people_profile_id;
                $CmsLinks->slug_url = trim($request->input('slug'));
                $CmsLinks->table_type = 'PEOPLE_PROFILE';
                $CmsLinks->save();

                $cms_link_id = $CmsLinks->id; // Need for page builder as parameter

                /** For Page Builder -- Insert Time **/
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                update_page_builder($insert_id, $cms_link_id, $people_profile_id, 'PEOPLE_PROFILE');
                /** End Page Builder **/

                if( $request->has('category_id') ) {
                    foreach( $request->input('category_id') as $cats ) {
                        $arr = array();
                        $arr['people_profile_id'] = $people_profile_id;
                        $arr['people_profile_category_id'] = $cats;
                        array_push( $categoriesMap, $arr );
                    }
                    if( !empty($categoriesMap) ) {
                        PeoplesProfileCategoriesMap::insert( $categoriesMap );
                    }
                }
                return redirect()->route('edtProfile', array('id' => $pid))->with('msg', 'People Profile Created Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
        }
        return back()->with('msg', 'Something Went Wrong')
        ->with('msg_class', 'alert alert-danger');
    }

    public function deleteLngCont( $pid, $cid ) {

        PeoplesProfile::find($cid)->delete();
        PeoplesProfileCategoriesMap::where('people_profile_id', '=', $cid)->delete();
        //PeoplesProfileImagesMap::where('people_profile_id', '=', $cid)->delete();
        //PeoplesProfileFilesMap::where('people_profile_id', '=', $cid)->delete();
        CmsLinks::where('table_type', '=', 'PEOPLE_PROFILE')->where('table_id', '=', $cid)->delete();

        delete_navigation($cid, 'PEOPLE_PROFILE');
        PageBuilder::where('table_id', '=', $cid)->where('table_type', '=', 'PEOPLE_PROFILE')->delete();

        return redirect()->route('edtProfile', array('id' => $pid))->with('msg', 'Language Content Deleted Successfully.')
        ->with('msg_class', 'alert alert-success');
    }




    public function addEditLngProfileCat($pid, $cid = '') {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'peopleManagement';
        $DataBag['childMenu'] = 'profileAddCat';
        $DataBag['parentLngCont'] = PeopleProfileCategories::findOrFail($pid);
        if( $cid != '' ) {
            $DataBag['profileCat'] = PeopleProfileCategories::findOrFail($cid);
            $DataBag['pageBuilderData'] = $DataBag['profileCat'];
        }
        $DataBag['languages'] = Languages::where('status', '=', '1')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));
        return view('dashboard.profile.create_language_category', $DataBag); 
    }

    public function addEditLngProfileCatPost(Request $request, $pid, $cid = '') {

        if( $cid != '' && $cid != null ) {

            $insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

            $PeopleProfileCategories = PeopleProfileCategories::find($cid);
            $PeopleProfileCategories->name = trim(ucfirst($request->input('name')));
            $PeopleProfileCategories->slug = trim($request->input('slug'));
            $PeopleProfileCategories->page_content = htmlentities( trim($request->input('page_content')), ENT_QUOTES);
            $PeopleProfileCategories->updated_by = Auth::user()->id;
            $PeopleProfileCategories->language_id = trim($request->input('language_id'));
            $PeopleProfileCategories->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );

            $PeopleProfileCategories->meta_title = trim($request->input('meta_title'));
            $PeopleProfileCategories->meta_desc = trim($request->input('meta_desc'));
            $PeopleProfileCategories->meta_keyword = trim($request->input('meta_keyword'));
            $PeopleProfileCategories->canonical_url = trim($request->input('canonical_url'));
            $PeopleProfileCategories->lng_tag = trim($request->input('lng_tag'));
            $PeopleProfileCategories->follow = trim($request->input('follow'));
            $PeopleProfileCategories->index_tag = trim($request->input('index_tag'));


            if( $PeopleProfileCategories->save() ) {
                
                $people_profile_category_id = $cid;

                CmsLinks::where('table_type', '=', 'PEOPLE_PROFILE_CATEGORY')->where('table_id', '=', $people_profile_category_id)
                ->update( ['slug_url' => trim($request->input('slug'))] );

                /** Need For Page Builder -- Update Time **/
                $cmsInfo = CmsLinks::where('table_id', '=', $people_profile_category_id)->where('table_type', '=', 'PEOPLE_PROFILE_CATEGORY')->first();
                if( !empty($cmsInfo) ) {
                    
                    /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                    update_page_builder($insert_id, $cmsInfo->id, $people_profile_category_id, 'PEOPLE_PROFILE_CATEGORY');

                }
                /** End Page Builder **/
                
                return back()->with('msg', 'People Profile Category Updated Succesfully')->with('msg_class', 'alert alert-success');
            }
        }

        if( $cid == '' ) {

            $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

            $PeopleProfileCategories = new PeopleProfileCategories;
            $PeopleProfileCategories->name = trim(ucfirst($request->input('name')));
            $PeopleProfileCategories->slug = trim($request->input('slug'));
            $PeopleProfileCategories->page_content = htmlentities( trim($request->input('page_content')), ENT_QUOTES);
            $PeopleProfileCategories->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
            $PeopleProfileCategories->created_by = Auth::user()->id;
            $PeopleProfileCategories->language_id = trim($request->input('language_id'));
            $PeopleProfileCategories->parent_language_id = $pid;

            $PeopleProfileCategories->insert_id = $insert_id;

            $PeopleProfileCategories->meta_title = trim($request->input('meta_title'));
            $PeopleProfileCategories->meta_desc = trim($request->input('meta_desc'));
            $PeopleProfileCategories->meta_keyword = trim($request->input('meta_keyword'));
            $PeopleProfileCategories->canonical_url = trim($request->input('canonical_url'));
            $PeopleProfileCategories->lng_tag = trim($request->input('lng_tag'));
            $PeopleProfileCategories->follow = trim($request->input('follow'));
            $PeopleProfileCategories->index_tag = trim($request->input('index_tag'));

            if( $PeopleProfileCategories->save() ) {
                
                $people_profile_category_id = $PeopleProfileCategories->id;

                $CmsLinks = new CmsLinks;
                $CmsLinks->table_id = $people_profile_category_id;
                $CmsLinks->slug_url = trim($request->input('slug'));
                $CmsLinks->table_type = 'PEOPLE_PROFILE_CATEGORY';
                $CmsLinks->save();
                $cms_link_id = $CmsLinks->id; // Need for page builder as parameter

                /** For Page Builder -- Insert Time **/
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                update_page_builder($insert_id, $cms_link_id, $people_profile_category_id, 'PEOPLE_PROFILE_CATEGORY');
                /** End Page Builder **/

                return redirect()->route('edtProfileCat', array('pid' => $pid))
                ->with('msg', 'People Profile Category Created Succesfully')
                ->with('msg_class', 'alert alert-success');
            }  
        }
         return back()->with('msg', 'Something Went Wrong')
        ->with('msg_class', 'alert alert-danger');
    }




    public function deleteLngCatCont($pid, $cid) {

        PeopleProfileCategories::find($cid)->delete();
        CmsLinks::where('table_type', '=', 'PEOPLE_PROFILE_CATEGORY')->where('table_id', '=', $cid)->delete();

        delete_navigation($cid, 'PEOPLE_PROFILE_CATEGORY');
        PageBuilder::where('table_id', '=', $cid)->where('table_type', '=', 'PEOPLE_PROFILE_CATEGORY')->delete();

        return redirect()->route('edtProfileCat', array('pid' => $pid))
        ->with('msg', 'Language Content Deleted Succesfully')
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
                        $PeoplesProfile = PeoplesProfile::find($id);
                        $PeoplesProfile->status = '1';
                        $PeoplesProfile->save();
                    }
                    $msg = 'Peoples Profile Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $PeoplesProfile = PeoplesProfile::find($id);
                        $PeoplesProfile->status = '2';
                        $PeoplesProfile->save();
                    }
                    $msg = 'Peoples Profile Deactivated Succesfully.';
                    break;

                case 'delete':
                    foreach($idsArr as $id) {
                        $PeoplesProfile = PeoplesProfile::find($id);
                        $PeoplesProfile->status = '3';
                        $PeoplesProfile->save();
                        PeoplesProfileCategoriesMap::where('people_profile_id', '=', $id)->delete();
                        //PeoplesProfileImagesMap::where('people_profile_id', '=', $pp_id)->delete();
                        //PeoplesProfileFilesMap::where('people_profile_id', '=', $pp_id)->delete();
                        CmsLinks::where('table_type', '=', 'PEOPLE_PROFILE')->where('table_id', '=', $id)->delete();

                        delete_navigation($id, 'PEOPLE_PROFILE');
                        PageBuilder::where('table_id', '=', $id)->where('table_type', '=', 'PEOPLE_PROFILE')->delete();
                    }
                    $msg = 'Peoples Profile Deleted Succesfully.';
                    break;
            }
            return back()->with('msg', $msg)->with('msg_class', 'alert alert-success');
        }
        return back();
    }

    public function bulkActionCat(Request $request) {

        $msg = '';
        if( $request->has('action_btn') && $request->has('ids') ) {
            $actBtnValue = trim( $request->input('action_btn') );
            $idsArr = $request->input('ids');

            switch ( $actBtnValue ) {
                
                case 'activate':
                    foreach($idsArr as $id) {
                        $PeopleProfileCategories = PeopleProfileCategories::find($id);
                        $PeopleProfileCategories->status = '1';
                        $PeopleProfileCategories->save();
                    }
                    $msg = 'Peoples Profile Categories Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $PeopleProfileCategories = PeopleProfileCategories::find($id);
                        $PeopleProfileCategories->status = '2';
                        $PeopleProfileCategories->save();
                    }
                    $msg = 'Peoples Profile Categories Deactivated Succesfully.';
                    break;

                case 'delete':
                    foreach($idsArr as $id) {
                        $PeopleProfileCategories = PeopleProfileCategories::find($id);
                        $PeopleProfileCategories->status = '3';
                        $PeopleProfileCategories->save();
                        delete_navigation($id, 'PEOPLE_PROFILE_CATEGORY');
                        CmsLinks::where('table_type', '=', 'PEOPLE_PROFILE_CATEGORY')->where('table_id', '=', $id)->delete();
                        PageBuilder::where('table_id', '=', $id)->where('table_type', '=', 'PEOPLE_PROFILE_CATEGORY')->delete();
                    }
                    $msg = 'Peoples Profile Categories Deleted Succesfully.';
                    break;
            }
            return back()->with('msg', $msg)->with('msg_class', 'alert alert-success');
        }
        return back();
    }



    public function extraConten() {

        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'peopleManagement';
        $DataBag['childMenu'] = 'profExCont';
        $DataBag['extraCont'] = MediaExtraContent::where('type', '=', 'PROFILE')->first();
        return view('dashboard.profile.extra_content', $DataBag);
    }

    public function extraContentSave(Request $request) {
        
        $MediaExtraContent = MediaExtraContent::where('type', '=', 'PROFILE')->first();

        if( !empty($MediaExtraContent) ) {

            $MediaExtraContent->page_content = htmlentities(trim($request->input('page_content')), ENT_QUOTES);
            $MediaExtraContent->meta_title = trim($request->input('meta_title'));
            $MediaExtraContent->meta_desc = trim($request->input('meta_desc'));
            $MediaExtraContent->meta_keyword = trim($request->input('meta_keyword'));
            $MediaExtraContent->canonical_url = trim($request->input('canonical_url'));
            $MediaExtraContent->lng_tag = trim($request->input('lng_tag'));
            $MediaExtraContent->follow = trim($request->input('follow'));
            $MediaExtraContent->index_tag = trim($request->input('index_tag'));
            $MediaExtraContent->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );

            $MediaExtraContent->title = trim($request->input('title'));
            $MediaExtraContent->image_title = trim($request->input('image_title'));
            $MediaExtraContent->image_alt = trim($request->input('image_alt'));
            $MediaExtraContent->image_caption = trim($request->input('image_caption'));

            if( $request->hasFile('page_banner') ) {
            
                $img = $request->file('page_banner');
                $real_path = $img->getRealPath();
                $file_orgname = $img->getClientOriginalName();
                $file_size = $img->getClientSize();
                $file_ext = strtolower($img->getClientOriginalExtension());
                $file_newname = "banner"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
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

                $Images->name = "Banner Image";
                $Images->alt_title = trim($request->input('image_alt'));
                $Images->caption = trim($request->input('image_caption'));
                $Images->title = trim($request->input('image_title'));

                $Images->updated_by = Auth::user()->id;

                if($Images->save()) {

                    $MediaExtraContent->image_id = $Images->id;  
                }
            }

            if( $MediaExtraContent->save() ) {
                return back()->with('msg', 'Content Saved Successfully.')->with('msg_class', 'alert alert-success');
            }
        } else {

            $MediaExtraContent = new MediaExtraContent;
            $MediaExtraContent->page_content = htmlentities(trim($request->input('page_content')), ENT_QUOTES);
            $MediaExtraContent->meta_title = trim($request->input('meta_title'));
            $MediaExtraContent->meta_desc = trim($request->input('meta_desc'));
            $MediaExtraContent->meta_keyword = trim($request->input('meta_keyword'));
            $MediaExtraContent->canonical_url = trim($request->input('canonical_url'));
            $MediaExtraContent->lng_tag = trim($request->input('lng_tag'));
            $MediaExtraContent->follow = trim($request->input('follow'));
            $MediaExtraContent->index_tag = trim($request->input('index_tag'));
            $MediaExtraContent->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );
            $MediaExtraContent->type = 'PROFILE';

            $MediaExtraContent->title = trim($request->input('title'));
            $MediaExtraContent->image_title = trim($request->input('image_title'));
            $MediaExtraContent->image_alt = trim($request->input('image_alt'));
            $MediaExtraContent->image_caption = trim($request->input('image_caption'));

            if( $request->hasFile('page_banner') ) {
            
                $img = $request->file('page_banner');
                $real_path = $img->getRealPath();
                $file_orgname = $img->getClientOriginalName();
                $file_size = $img->getClientSize();
                $file_ext = strtolower($img->getClientOriginalExtension());
                $file_newname = "banner"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
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

                $Images->name = "Banner Image";
                $Images->alt_title = trim($request->input('image_alt'));
                $Images->caption = trim($request->input('image_caption'));
                $Images->title = trim($request->input('image_title'));

                $Images->created_by = Auth::user()->id;

                if($Images->save()) {

                    $MediaExtraContent->image_id = $Images->id;  
                }
            }

            
            if( $MediaExtraContent->save() ) {
                return back()->with('msg', 'Content Saved Successfully.')->with('msg_class', 'alert alert-success');
            }
        }

        return back();
    }
}
