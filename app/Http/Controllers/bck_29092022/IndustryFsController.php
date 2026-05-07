<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CmsLinks;
use App\Models\Media\Images;
use App\Models\Media\FilesMaster;
use App\Models\IndustryFlowsheet\Flowsheet;
use App\Models\IndustryFlowsheet\FlowsheetCategories;
use App\Models\IndustryFlowsheet\FlowsheetCategoriesMap;
use App\Models\IndustryFlowsheet\FlowsheetFilesMap;
use App\Models\IndustryFlowsheet\FlowsheetImagesMap;
use App\Models\IndustryFlowsheet\FlowsheetMarker;
use App\Models\Languages;
use App\Models\PageBuilder\PageBuilder;
use File;
use Image;
use Auth;
use DB;

class IndustryFsController extends Controller
{
    
    public function addeditMarker($fsid) {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'indusFsheet';
        $DataBag['childMenu'] = 'allFsheets';
        $DataBag['languages'] = Languages::get();
        $DataBag['flowsheet'] = Flowsheet::findOrFail($fsid);
 
        // $DataBag['language_id'] =  $request->input('language_id'); 
		// $DataBag['content_id'] = $fsid;

        // if($DataBag['language_id']==1){
        //     $DataBag['flowsheet'] = Flowsheet::findOrFail($fsid);
        // }
        // else{
        //     $DataBag['flowsheet'] = Flowsheet::where('parent_id',$fsid)->where('language_id', $DataBag['language_id'])->first();
        
        //     if($DataBag['flowsheet']==null){ 
        //         $DataBag['flowsheet'] = Flowsheet::where('id',$fsid)->where('language_id', 1)->first(); 
        //     }
       
        // }

        // $fsid=$DataBag['flowsheet']['id'];
 
        $DataBag['fsmarkers'] = FlowsheetMarker::where('flowsheet_id', '=', $fsid)->get();
 
   
        return view('dashboard.industry_flowsheet.marker', $DataBag);
    }

    public function addeditMarker1($fsid, Request $request) {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'indusFsheet';
        $DataBag['childMenu'] = 'allFsheets';
        $DataBag['languages'] = Languages::get();
        // $DataBag['flowsheet'] = Flowsheet::findOrFail($fsid);


        $DataBag['language_id'] =  $request->input('language_id'); 
		$DataBag['content_id'] = $fsid;

        if($DataBag['language_id']==1){
            $DataBag['flowsheet'] = Flowsheet::findOrFail($fsid);
        }
        else{
            $DataBag['flowsheet'] = Flowsheet::where('parent_id',$fsid)->where('language_id', $DataBag['language_id'])->first();
        
            if($DataBag['flowsheet']==null){ 
                $DataBag['flowsheet'] = Flowsheet::where('id',$fsid)->where('language_id', 1)->first(); 
            }
       
        }

        // $fsid=$DataBag['flowsheet']['id'];
 
        $DataBag['fsmarkers'] = FlowsheetMarker::where('flowsheet_id', '=', $fsid)->get();
 
   
        return view('dashboard.industry_flowsheet.marker', $DataBag);
    }

    public function addeditMarkerSave(Request $request, $fsid) {

        $rtnArr = array();

        $marker_id = trim( $request->input('marker_id') );

        if( $marker_id == '0' ) {

            $FlowsheetMarker = new FlowsheetMarker;
            $FlowsheetMarker->name = trim( $request->input('name') );
            $FlowsheetMarker->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $FlowsheetMarker->left_pos = $rtnArr['left_pos'] = trim( $request->input('left_pos') );
            $FlowsheetMarker->top_pos = $rtnArr['top_pos'] = trim( $request->input('top_pos') );
            $FlowsheetMarker->pin_image = $rtnArr['pin_image'] = trim( $request->input('pin_image') );
            $FlowsheetMarker->flowsheet_id = trim( $request->input('flowsheet_id') );

            if( $request->hasFile('imgx') ) {
                $img = $request->file('imgx');
                $Images = new Images;
                $real_path = $img->getRealPath();
                $file_orgname = $img->getClientOriginalName();
                $file_size = $img->getClientSize();
                $file_ext = strtolower($img->getClientOriginalExtension());
                $file_newname = "media"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;

                $destinationPath = public_path('/uploads/files/media_images');
                $thumb_path = $destinationPath."/thumb";
                
                $imgObj = Image::make($real_path);
                $imgObj->resize(100, 100, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($thumb_path.'/'.$file_newname);

                $img->move($destinationPath, $file_newname);
                $Images->image = $file_newname;
                $Images->size = $file_size;
                $Images->extension = $file_ext;
                $Images->created_by = Auth::user()->id;
                if( $Images->save() ) {
                    $FlowsheetMarker->image_id = $Images->id;
                }
            }

            if($FlowsheetMarker->save()) {
                $rtnArr['marker_id'] = $FlowsheetMarker->id;
                $rtnArr['status'] = 'OK';
            } else {
                $rtnArr['status'] = 'ERROR';
            }
            
            $rtnArr['marker_url'] =  asset('public/pin.png');
            
            return json_encode($rtnArr);

        } else {

            $FlowsheetMarker = FlowsheetMarker::find($marker_id);
            $FlowsheetMarker->name = trim( $request->input('name') );
            $FlowsheetMarker->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $FlowsheetMarker->left_pos = $rtnArr['left_pos'] = trim( $request->input('left_pos') );
            $FlowsheetMarker->top_pos = $rtnArr['top_pos'] = trim( $request->input('top_pos') );
            $FlowsheetMarker->pin_image = $rtnArr['pin_image'] = trim( $request->input('pin_image') );
            $FlowsheetMarker->flowsheet_id = trim( $request->input('flowsheet_id') );
            
            if( $request->hasFile('imgx') ) {
                $img = $request->file('imgx');
                $Images = new Images;
                $real_path = $img->getRealPath();
                $file_orgname = $img->getClientOriginalName();
                $file_size = $img->getClientSize();
                $file_ext = strtolower($img->getClientOriginalExtension());
                $file_newname = "media"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;

                $destinationPath = public_path('/uploads/files/media_images');
                $thumb_path = $destinationPath."/thumb";
                
                $imgObj = Image::make($real_path);
                $imgObj->resize(100, 100, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($thumb_path.'/'.$file_newname);

                $img->move($destinationPath, $file_newname);
                $Images->image = $file_newname;
                $Images->size = $file_size;
                $Images->extension = $file_ext;
                $Images->created_by = Auth::user()->id;
                if( $Images->save() ) {
                    $FlowsheetMarker->image_id = $Images->id;
                }
            }

            if($FlowsheetMarker->save()) {
                $rtnArr['marker_id'] = $FlowsheetMarker->id;
                $rtnArr['status'] = 'DONE';
            } else {
                $rtnArr['status'] = 'ERROR';
            }
 
            $rtnArr['marker_url'] =  asset('public/pin.png');
            
            return json_encode($rtnArr);
        }

    }


    public function getMarkerInfo(Request $request) {

        $jsArr = array();
        $id = trim( $request->input('id') );
        $data = FlowsheetMarker::find($id);

        $jsArr['name'] = $data->name;
        $jsArr['page_content'] = html_entity_decode($data->page_content, ENT_QUOTES);
        $jsArr['id'] = $data->id;
        $jsArr['left_pos'] = $data->left_pos;
        $jsArr['top_pos'] = $data->top_pos;
        $jsArr['flowsheet_id'] = $data->flowsheet_id;
        $jsArr['image_id'] = $data->image_id;
        $jsArr['pin_image'] = $data->pin_image;

        $imageObj = getImageById($data->image_id);
        if( !empty($imageObj) ) {
            $jsArr['imgx'] = asset('public/uploads/files/media_images/thumb/'.$imageObj->image);
        } else {
            $jsArr['imgx'] = '';
        }

        return json_encode($jsArr);
    }

    public function delMarkerInfo(Request $request) {

        $id = trim( $request->input('id') );
        $data = FlowsheetMarker::find($id);
        if($data->delete()) {
            return 'OK';
        }

        return '';
    }

    public function delMarkerImage(Request $request) {

        $id = trim( $request->input('id') );
        $data = FlowsheetMarker::where('id', '=', $id)->update(['image_id' => '0']);
        if( $data ) {
            return 'OK';
        }

        return '';
    }


    public function allCategories() {
        $DataBag = array();
        
        $DataBag['languages'] = Languages::get(); 
		$DataBag['translate_lang_id'] = Auth::user()->translate_lang_id;

		$roles=Auth::user()->roles;
		$superadmin=0;
		 if( isset($roles) ){
		 foreach($roles as $ur){
				 if($ur->id==1){
					 $superadmin=1; 
				 }
			 }
		 }
 
		$DataBag['superadmin'] =$superadmin;
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'indusFsheet';
    	$DataBag['childMenu'] = 'FsheetCats';
    	$DataBag['allCats'] = FlowsheetCategories::where('status', '!=', '3')->where('parent_language_id', '=', '0')->where('parent_id', '=', '0')
        ->orderBy('created_at', 'desc')->get();
    	return view('dashboard.industry_flowsheet.all_categories', $DataBag);
    }

    public function addCategory() {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'indusFsheet';
    	$DataBag['childMenu'] = 'FsheetAddCat';
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));
    	return view('dashboard.industry_flowsheet.create_category', $DataBag);
    }

    public function saveCategory(Request $request) {

        $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

    	$FlowsheetCategories = new FlowsheetCategories;
    	$FlowsheetCategories->name = trim( ucfirst($request->input('name')) );
    	$FlowsheetCategories->slug = trim($request->input('slug'));
    	$FlowsheetCategories->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
    	$FlowsheetCategories->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
    	$FlowsheetCategories->created_by = Auth::user()->id;
        $FlowsheetCategories->language_id = trim( $request->input('language_id') );

        $FlowsheetCategories->insert_id = $insert_id;

        $FlowsheetCategories->meta_title = trim($request->input('meta_title'));
        $FlowsheetCategories->meta_desc = trim($request->input('meta_desc'));
        $FlowsheetCategories->meta_keyword = trim($request->input('meta_keyword'));
        $FlowsheetCategories->canonical_url = trim($request->input('canonical_url'));
        $FlowsheetCategories->lng_tag = trim($request->input('lng_tag'));
        $FlowsheetCategories->follow = trim($request->input('follow'));
        $FlowsheetCategories->index_tag = trim($request->input('index_tag'));
        $FlowsheetCategories->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );

        $FlowsheetCategories->image_title = trim( $request->input('image_title') );
        $FlowsheetCategories->image_alt = trim( $request->input('image_alt') );
        $FlowsheetCategories->image_caption = trim( $request->input('image_caption') );

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

                $FlowsheetCategories->image_id = $Images->id;  
            }
        }

    	if( $FlowsheetCategories->save() ) {

    		$flowsheet_category_id = $FlowsheetCategories->id;

    		$CmsLinks = new CmsLinks;
    		$CmsLinks->table_id = $flowsheet_category_id;
    		$CmsLinks->slug_url = trim($request->input('slug'));
    		$CmsLinks->table_type = 'FLOWSHEET_CATEGORY';
    		$CmsLinks->save();
            $cms_link_id = $CmsLinks->id; // Need for page builder as parameter

            /** For Page Builder -- Insert Time **/
            /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
            update_page_builder($insert_id, $cms_link_id, $flowsheet_category_id, 'FLOWSHEET_CATEGORY');
            /** End Page Builder **/

    		return back()->with('msg', 'Industry Flowsheet Category Created Successfully.')
    		->with('msg_class', 'alert alert-success');
    	}
    	return back()->with('msg', 'Something Went Wrong')
    	->with('msg_class', 'alert alert-danger');
    }

    public function editCategory($fsc_id,Request $request) {
        $DataBag = array();
        
        $DataBag['content_id'] = $fsc_id;
        $DataBag['language_id'] =  $request->input('language_id'); 
        if($DataBag['language_id']==1){
            $DataBag['fscategory'] = FlowsheetCategories::findOrFail($fsc_id);
        }
        else{
            $DataBag['fscategory'] = FlowsheetCategories::where('parent_id',$fsc_id)->where('language_id', $DataBag['language_id'])->first();
        
            if($DataBag['fscategory']==null){ 
                $DataBag['fscategory'] = FlowsheetCategories::where('id',$fsc_id)->where('language_id', 1)->first(); 
            }
       
        }


        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'indusFsheet';
    	$DataBag['childMenu'] = 'FsheetAddCat';
    	// $DataBag['fscategory'] = FlowsheetCategories::findOrFail($fsc_id);
        $DataBag['pageBuilderData'] = $DataBag['fscategory']; /* For pagebuilder */
        $DataBag['languages'] = Languages::get();
    	return view('dashboard.industry_flowsheet.create_category', $DataBag);
    }

    public function updateCategory(Request $request, $fsc_id) {

        $language_id=$request->input('language_id');
        if($language_id!=1){
        $DataBag['dynaContent'] = FlowsheetCategories::where('parent_id',$fsc_id)->where('language_id', $language_id)->first();
 
        if(isset($DataBag['dynaContent']) && $DataBag['dynaContent']!=null){

            $insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

            $fsc_id = $DataBag['dynaContent']['id'];

            $FlowsheetCategories = FlowsheetCategories::find($fsc_id);
            $FlowsheetCategories->name = trim( ucfirst($request->input('name')) );
            $FlowsheetCategories->slug = trim($request->input('slug'));
            $FlowsheetCategories->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
            $FlowsheetCategories->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $FlowsheetCategories->updated_by = Auth::user()->id;
    
            $FlowsheetCategories->meta_title = trim($request->input('meta_title'));
            $FlowsheetCategories->meta_desc = trim($request->input('meta_desc'));
            $FlowsheetCategories->meta_keyword = trim($request->input('meta_keyword'));
            $FlowsheetCategories->canonical_url = trim($request->input('canonical_url'));
            $FlowsheetCategories->lng_tag = trim($request->input('lng_tag'));
            $FlowsheetCategories->follow = trim($request->input('follow'));
            $FlowsheetCategories->index_tag = trim($request->input('index_tag'));
            $FlowsheetCategories->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );
    
            $FlowsheetCategories->image_title = trim( $request->input('image_title') );
            $FlowsheetCategories->image_alt = trim( $request->input('image_alt') );
            $FlowsheetCategories->image_caption = trim( $request->input('image_caption') );
            
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
    
                    $FlowsheetCategories->image_id = $Images->id;  
                }
            }
            
            if( $FlowsheetCategories->save() ) {
    
                CmsLinks::where('table_type', '=', 'FLOWSHEET_CATEGORY')->where('table_id', '=', $fsc_id)
                ->update([ 'slug_url' => trim($request->input('slug')) ]);
    
                /** Need For Page Builder -- Update Time **/
                $cmsInfo = CmsLinks::where('table_id', '=', $fsc_id)->where('table_type', '=', 'FLOWSHEET_CATEGORY')->first();
                if( !empty($cmsInfo) ) {
                    
                    /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                    update_page_builder($insert_id, $cmsInfo->id, $fsc_id, 'FLOWSHEET_CATEGORY');
    
                }
                /** End Page Builder **/
    
                // return back()->with('msg', 'Industry Flowsheet Category Updated Successfully.')
                // ->with('msg_class', 'alert alert-success');

                return redirect()->route('allFSc')->with('msg', 'Industry Flowsheet Category Updated Successfully.')
            ->with('msg_class', 'alert alert-success');
            }

        }
        else{
            $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

            $FlowsheetCategories = new FlowsheetCategories;
            $FlowsheetCategories->parent_id = $fsc_id;
            $parent_id= $fsc_id;
            $FlowsheetCategories->name = trim( ucfirst($request->input('name')) );
            $FlowsheetCategories->slug = trim($request->input('slug'));
            $FlowsheetCategories->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
            $FlowsheetCategories->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $FlowsheetCategories->created_by = Auth::user()->id;
            $FlowsheetCategories->language_id = trim( $request->input('language_id') );
    
            $FlowsheetCategories->insert_id = $insert_id;
    
            $FlowsheetCategories->meta_title = trim($request->input('meta_title'));
            $FlowsheetCategories->meta_desc = trim($request->input('meta_desc'));
            $FlowsheetCategories->meta_keyword = trim($request->input('meta_keyword'));
            $FlowsheetCategories->canonical_url = trim($request->input('canonical_url'));
            $FlowsheetCategories->lng_tag = trim($request->input('lng_tag'));
            $FlowsheetCategories->follow = trim($request->input('follow'));
            $FlowsheetCategories->index_tag = trim($request->input('index_tag'));
            $FlowsheetCategories->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );
    
            $FlowsheetCategories->image_title = trim( $request->input('image_title') );
            $FlowsheetCategories->image_alt = trim( $request->input('image_alt') );
            $FlowsheetCategories->image_caption = trim( $request->input('image_caption') );
    
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
    
                    $FlowsheetCategories->image_id = $Images->id;  
                }
            }

 
    
            if( $FlowsheetCategories->save() ) {
    
                $flowsheet_category_id = $FlowsheetCategories->id;
    
                $CmsLinks = new CmsLinks;
                $CmsLinks->table_id = $flowsheet_category_id;
                $CmsLinks->slug_url = trim($request->input('slug'));
                $CmsLinks->table_type = 'FLOWSHEET_CATEGORY';
                $CmsLinks->save();
                $cms_link_id = $CmsLinks->id; // Need for page builder as parameter
    
                /** For Page Builder -- Insert Time **/
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                // update_page_builder($insert_id, $cms_link_id, $flowsheet_category_id, 'FLOWSHEET_CATEGORY');

                $datapage = DB::table('page_builder')->where('insert_id', '=', $insert_id)->where('table_id', '=',  $parent_id)->get();
            
                    
                foreach($datapage as $row){
                    $updateArr = array();
                    $updateArr['insert_id'] = $insert_id;
                    $updateArr['cms_link_id'] = $cms_link_id;
                    $updateArr['table_id'] = $flowsheet_category_id;
                    $updateArr['table_type'] = $row->table_type;
                    $updateArr['builder_type'] = $row->builder_type;
                    $updateArr['main_content'] = $row->main_content;
                    $updateArr['sub_content'] = $row->sub_content;

                    $updateArr['main_title'] = $row->main_title;
                    $updateArr['sub_title'] = $row->sub_title;
                    $updateArr['link_text'] = $row->link_text;
                    $updateArr['link_url'] = $row->link_url;
                    $updateArr['display_order'] = $row->display_order;
                    $updateArr['position'] = $row->position;
                    $updateArr['device'] = $row->device;
                    DB::table('page_builder')->insert( $updateArr );
                }


                $datapage = DB::table('flowsheet_images_map')->where('flowsheet_id', '=', $flowsheet_category_id)->get();
                foreach($datapage as $row){
                
                    $updateArr = array();
                    $updateArr['flowsheet_id'] = $flowsheet_category_id;
                    $updateArr['image_id'] = $row->image_id;
                    $updateArr['title'] = $row->title;
                    $updateArr['caption'] = $row->caption;
                    $updateArr['alt_tag'] = $row->alt_tag;
                    $updateArr['description'] = $row->description;
                    $updateArr['image_type'] = $row->image_type; 
                    DB::table('flowsheet_images_map')->insert( $updateArr );

                }


                $datapage = DB::table('flowsheet_marker_map')->where('flowsheet_id', '=', $flowsheet_category_id)->get();
                foreach($datapage as $row){ 
                    $updateArr = array();
                    $updateArr['flowsheet_id'] = $flowsheet_category_id;
                    $updateArr['left_pos'] = $row->left_pos;
                    $updateArr['top_pos'] = $row->top_pos;
                    $updateArr['name'] = $row->name;
                    $updateArr['page_content'] = $row->page_content;
                    $updateArr['image_id'] = $row->image_id; 
                    DB::table('flowsheet_marker_map')->insert( $updateArr );

                }

                /** End Page Builder **/
    
                // return back()->with('msg', 'Industry Flowsheet Category Created Successfully.')
                // ->with('msg_class', 'alert alert-success');


                return redirect()->route('allFSc')->with('msg', 'Industry Flowsheet Category Created Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
            // return back()->with('msg', 'Something Went Wrong')
            // ->with('msg_class', 'alert alert-danger');
        }
    }

    else{

     

    	$insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

    	$FlowsheetCategories = FlowsheetCategories::find($fsc_id);
    	$FlowsheetCategories->name = trim( ucfirst($request->input('name')) );
    	$FlowsheetCategories->slug = trim($request->input('slug'));
    	$FlowsheetCategories->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
    	$FlowsheetCategories->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
    	$FlowsheetCategories->updated_by = Auth::user()->id;

        $FlowsheetCategories->meta_title = trim($request->input('meta_title'));
        $FlowsheetCategories->meta_desc = trim($request->input('meta_desc'));
        $FlowsheetCategories->meta_keyword = trim($request->input('meta_keyword'));
        $FlowsheetCategories->canonical_url = trim($request->input('canonical_url'));
        $FlowsheetCategories->lng_tag = trim($request->input('lng_tag'));
        $FlowsheetCategories->follow = trim($request->input('follow'));
        $FlowsheetCategories->index_tag = trim($request->input('index_tag'));
        $FlowsheetCategories->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );

        $FlowsheetCategories->image_title = trim( $request->input('image_title') );
        $FlowsheetCategories->image_alt = trim( $request->input('image_alt') );
        $FlowsheetCategories->image_caption = trim( $request->input('image_caption') );
        
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

                $FlowsheetCategories->image_id = $Images->id;  
            }
        }
    	
    	if( $FlowsheetCategories->save() ) {

    		CmsLinks::where('table_type', '=', 'FLOWSHEET_CATEGORY')->where('table_id', '=', $fsc_id)
    		->update([ 'slug_url' => trim($request->input('slug')) ]);

            /** Need For Page Builder -- Update Time **/
            $cmsInfo = CmsLinks::where('table_id', '=', $fsc_id)->where('table_type', '=', 'FLOWSHEET_CATEGORY')->first();
            if( !empty($cmsInfo) ) {
                
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                update_page_builder($insert_id, $cmsInfo->id, $fsc_id, 'FLOWSHEET_CATEGORY');

            }
            /** End Page Builder **/

    		// return back()->with('msg', 'Industry Flowsheet Category Updated Successfully.')
            // ->with('msg_class', 'alert alert-success');
            
            return redirect()->route('allFSc')->with('msg', 'Industry Flowsheet Updated Created Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
        
    }
    	return back()->with('msg', 'Something Went Wrong')
    	->with('msg_class', 'alert alert-danger');
    }

    public function deleteCategory($fsc_id) {

    	$ck = FlowsheetCategories::find($fsc_id);
    	if( isset($ck) && !empty($ck) ) {
    		$ck->status = '3';
    		$res = $ck->save();
    		if( isset($res) && $res == 1 ) {

                delete_navigation($fsc_id, 'FLOWSHEET_CATEGORY');
    			CmsLinks::where('table_type', '=', 'FLOWSHEET_CATEGORY')->where('table_id', '=', $fsc_id)->delete();
                PageBuilder::where('table_id', '=', $fsc_id)->where('table_type', '=', 'FLOWSHEET_CATEGORY')->delete();
    			
    			return back()->with('msg', 'Industry Flowsheet Category Deleted Successfully.')
    			->with('msg_class', 'alert alert-success');
    		}
    	}

    	return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }










/*** Flowsheet ***/


    public function index() {
        $DataBag = array();
        
        $DataBag['languages'] = Languages::get(); 
		$DataBag['translate_lang_id'] = Auth::user()->translate_lang_id;

		$roles=Auth::user()->roles;
		$superadmin=0;
		 if( isset($roles) ){
		 foreach($roles as $ur){
				 if($ur->id==1){
					 $superadmin=1; 
				 }
			 }
		 }
 
		$DataBag['superadmin'] =$superadmin;

        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'indusFsheet';
    	$DataBag['childMenu'] = 'allFsheets';
    	$DataBag['flowsheets'] = Flowsheet::where('status', '!=', '3')->where('parent_language_id', '=', '0')->where('parent_id', '=', '0')
        ->orderBy('created_at', 'desc')->get();
    	return view('dashboard.industry_flowsheet.index', $DataBag);
    }

    public function create() {
        $DataBag = array();
        $DataBag['language_id'] =  1; 
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'indusFsheet';
    	$DataBag['childMenu'] = 'addFsheet';
    	$DataBag['allCats'] = FlowsheetCategories::where('status', '=', '1')->orderBy('name', 'asc')->get();
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));
    	return view('dashboard.industry_flowsheet.create', $DataBag);
    }

    public function save(Request $request) {

        $categoriesMap = array();
        $imagesMap = array();

        $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

    	$Flowsheet = new Flowsheet;
    	$Flowsheet->name = trim( ucfirst($request->input('name')) );
    	$Flowsheet->slug = trim($request->input('slug'));
    	$Flowsheet->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
    	$Flowsheet->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
    	$Flowsheet->created_by = Auth::user()->id;
        $Flowsheet->language_id = trim( $request->input('language_id') );

        $Flowsheet->insert_id = $insert_id;

    	$Flowsheet->meta_title = trim($request->input('meta_title'));
        $Flowsheet->meta_desc = trim($request->input('meta_desc'));
        $Flowsheet->meta_keyword = trim($request->input('meta_keyword'));
        $Flowsheet->canonical_url = trim($request->input('canonical_url'));
        $Flowsheet->lng_tag = trim($request->input('lng_tag'));
        $Flowsheet->follow = trim($request->input('follow'));
        $Flowsheet->index_tag = trim($request->input('index_tag'));
        $Flowsheet->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );

        $fs_image_infos = json_decode( trim( $request->input('fs_image_infos') ) );

    	if( $Flowsheet->save() ) {

    		$flowsheet_id = $Flowsheet->id;

    		$CmsLinks = new CmsLinks;
    		$CmsLinks->table_id = $flowsheet_id;
    		$CmsLinks->slug_url = trim($request->input('slug'));
    		$CmsLinks->table_type = 'FLOWSHEET';
    		$CmsLinks->save();
            $cms_link_id = $CmsLinks->id; // Need for page builder as parameter

            /** For Page Builder -- Insert Time **/
            /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
            update_page_builder($insert_id, $cms_link_id, $flowsheet_id, 'FLOWSHEET');
            /** End Page Builder **/

            if( !empty($fs_image_infos) ) {
                foreach ($fs_image_infos as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['flowsheet_id'] = $flowsheet_id;
                        $arr['image_id'] = $v->img_id;
                        $arr['title'] = $v->img_titl;
                        $arr['caption'] = $v->img_cap;
                        $arr['alt_tag'] = $v->img_alt;
                        $arr['description'] = $v->img_dsc;
                        $arr['image_type'] = "FS_IMAGE";
                        array_push( $imagesMap, $arr );
                    }
                }

                if( !empty($imagesMap) ) {
                    FlowsheetImagesMap::insert($imagesMap);
                }
            }


	    	if( $request->has('flowsheet_category_id') ) {
	    		foreach( $request->input('flowsheet_category_id') as $cats ) {
	    			$arr = array();
	    			$arr['flowsheet_id'] = $flowsheet_id;
	    			$arr['flowsheet_category_id'] = $cats;
	    			array_push( $categoriesMap, $arr );
	    		}
	    		if( !empty($categoriesMap) ) {
	    			FlowsheetCategoriesMap::insert( $categoriesMap );
	    		}
	    	}

	    return back()->with('msg', 'Industry Flowsheet Created Successfully.')
    	->with('msg_class', 'alert alert-success');

    	}
    
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }



    public function edit($fs_id,Request $request) {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'indusFsheet';
        $DataBag['childMenu'] = 'addFsheet';
        $DataBag['language_id'] =  $request->input('language_id'); 
        $DataBag['content_id'] = $fs_id;

        if($DataBag['language_id']==1){
            $DataBag['flowsheet'] = Flowsheet::findOrFail($fs_id);
        }
        else{
            $DataBag['flowsheet'] = Flowsheet::where('parent_id',$fs_id)->where('language_id', $DataBag['language_id'])->first();
        
            if($DataBag['flowsheet']==null){ 
                $DataBag['flowsheet'] = Flowsheet::where('id',$fs_id)->where('language_id', 1)->first(); 
            }
       
        }

    	// $DataBag['flowsheet'] = Flowsheet::findOrFail($fs_id);
        $DataBag['pageBuilderData'] = $DataBag['flowsheet']; /* For pagebuilder */
    	$DataBag['allCats'] = FlowsheetCategories::where('status', '=', '1')->orderBy('name', 'asc')->get();
        $DataBag['languages'] = Languages::get();
    	return view('dashboard.industry_flowsheet.create', $DataBag);
    }

    public function update(Request $request, $fs_id) {


        $language_id=$request->input('language_id');
        if($language_id!=1){
        $DataBag['dynaContent'] = Flowsheet::where('parent_id',$fs_id)->where('language_id', $language_id)->first();
 
        if(isset($DataBag['dynaContent']) && $DataBag['dynaContent']!=null){

            
    	$insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

    	$categoriesMap = array();
        $imagesMap = array();

        $fs_id = $DataBag['dynaContent']['id'];
    	$Flowsheet = Flowsheet::find($fs_id);
    	$Flowsheet->name = trim( ucfirst($request->input('name')) );
    	$Flowsheet->slug = trim($request->input('slug'));
    	$Flowsheet->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
    	$Flowsheet->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
    	$Flowsheet->updated_by = Auth::user()->id;

        $Flowsheet->meta_title = trim($request->input('meta_title'));
        $Flowsheet->meta_desc = trim($request->input('meta_desc'));
        $Flowsheet->meta_keyword = trim($request->input('meta_keyword'));
        $Flowsheet->canonical_url = trim($request->input('canonical_url'));
        $Flowsheet->lng_tag = trim($request->input('lng_tag'));
        $Flowsheet->follow = trim($request->input('follow'));
        $Flowsheet->index_tag = trim($request->input('index_tag'));
        $Flowsheet->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );

        $fs_image_infos = json_decode( trim( $request->input('fs_image_infos') ) );
    	
    	if( $Flowsheet->save() ) {

    		$flowsheet_id = $fs_id;

    		CmsLinks::where('table_type', '=', 'FLOWSHEET')->where('table_id', '=', $flowsheet_id)
    		->update([ 'slug_url' => trim($request->input('slug')) ]);

            /** Need For Page Builder -- Update Time **/
            $cmsInfo = CmsLinks::where('table_id', '=', $flowsheet_id)->where('table_type', '=', 'FLOWSHEET')->first();
            if( !empty($cmsInfo) ) {
                
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                update_page_builder($insert_id, $cmsInfo->id, $flowsheet_id, 'FLOWSHEET');

            }
            /** End Page Builder **/ 


            if( !empty($fs_image_infos) ) {
                foreach ($fs_image_infos as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['flowsheet_id'] = $flowsheet_id;
                        $arr['image_id'] = $v->img_id;
                        $arr['title'] = $v->img_titl;
                        $arr['caption'] = $v->img_cap;
                        $arr['alt_tag'] = $v->img_alt;
                        $arr['description'] = $v->img_dsc;
                        $arr['image_type'] = "FS_IMAGE";
                        array_push( $imagesMap, $arr );
                    }
                }

                if( !empty($imagesMap) ) {
                    FlowsheetImagesMap::insert($imagesMap);
                }
            }   		

	    	FlowsheetCategoriesMap::where('flowsheet_id', '=', $flowsheet_id)->delete();
	    	if( $request->has('flowsheet_category_id') ) {
	    		foreach( $request->input('flowsheet_category_id') as $cats ) {
	    			$arr = array();
	    			$arr['flowsheet_id'] = $flowsheet_id;
	    			$arr['flowsheet_category_id'] = $cats;
	    			array_push( $categoriesMap, $arr );
	    		}
	    		if( !empty($categoriesMap) ) {
	    			FlowsheetCategoriesMap::insert( $categoriesMap );
	    		}
	    	}

	    	

	    // return back()->with('msg', 'Industry Flowsheet Updated Successfully.')
        // ->with('msg_class', 'alert alert-success');
        

        return redirect()->route('allFSs')->with('msg', 'Industry Flowsheet Updated Successfully.')
            ->with('msg_class', 'alert alert-success');

        }

        }

        else{
            $categoriesMap = array();
            $imagesMap = array();
    
            $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time
    
            $Flowsheet = new Flowsheet;
            $Flowsheet->parent_id = $fs_id;
            $parent_id= $fs_id;
            $Flowsheet->name = trim( ucfirst($request->input('name')) );
            $Flowsheet->slug = trim($request->input('slug'));
            $Flowsheet->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
            $Flowsheet->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $Flowsheet->created_by = Auth::user()->id;
            $Flowsheet->language_id = trim( $request->input('language_id') );
    
            $Flowsheet->insert_id = $insert_id;
    
            $Flowsheet->meta_title = trim($request->input('meta_title'));
            $Flowsheet->meta_desc = trim($request->input('meta_desc'));
            $Flowsheet->meta_keyword = trim($request->input('meta_keyword'));
            $Flowsheet->canonical_url = trim($request->input('canonical_url'));
            $Flowsheet->lng_tag = trim($request->input('lng_tag'));
            $Flowsheet->follow = trim($request->input('follow'));
            $Flowsheet->index_tag = trim($request->input('index_tag'));
            $Flowsheet->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );
    
            $fs_image_infos = json_decode( trim( $request->input('fs_image_infos') ) );
    
            if( $Flowsheet->save() ) {
    
                $flowsheet_id = $Flowsheet->id;
    
                $CmsLinks = new CmsLinks;
                $CmsLinks->table_id = $flowsheet_id;
                $CmsLinks->slug_url = trim($request->input('slug'));
                $CmsLinks->table_type = 'FLOWSHEET';
                $CmsLinks->save();
                $cms_link_id = $CmsLinks->id; // Need for page builder as parameter
    
                /** For Page Builder -- Insert Time **/
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                // update_page_builder($insert_id, $cms_link_id, $flowsheet_id, 'FLOWSHEET');

                $datapage = DB::table('page_builder')->where('insert_id', '=', $insert_id)->where('table_id', '=',  $parent_id)->get();
            
                    
                    foreach($datapage as $row){
                        $updateArr = array();
                        $updateArr['insert_id'] = $insert_id;
                        $updateArr['cms_link_id'] = $cms_link_id;
                        $updateArr['table_id'] = $flowsheet_id;
                        $updateArr['table_type'] = $row->table_type;
                        $updateArr['builder_type'] = $row->builder_type;
                        $updateArr['main_content'] = $row->main_content;
                        $updateArr['sub_content'] = $row->sub_content;

                        $updateArr['main_title'] = $row->main_title;
                        $updateArr['sub_title'] = $row->sub_title;
                        $updateArr['link_text'] = $row->link_text;
                        $updateArr['link_url'] = $row->link_url;
                        $updateArr['display_order'] = $row->display_order;
                        $updateArr['position'] = $row->position;
                        $updateArr['device'] = $row->device;
                        DB::table('page_builder')->insert( $updateArr );
                    }


                /** End Page Builder **/
    
                if( !empty($fs_image_infos) ) {
                    foreach ($fs_image_infos as $v) {
                        if( $v->img_id != '' ) {
                            $arr = array();
                            $arr['flowsheet_id'] = $flowsheet_id;
                            $arr['image_id'] = $v->img_id;
                            $arr['title'] = $v->img_titl;
                            $arr['caption'] = $v->img_cap;
                            $arr['alt_tag'] = $v->img_alt;
                            $arr['description'] = $v->img_dsc;
                            $arr['image_type'] = "FS_IMAGE";
                            array_push( $imagesMap, $arr );
                        }
                    }
    
                    if( !empty($imagesMap) ) {
                        FlowsheetImagesMap::insert($imagesMap);
                    }
                }
    
    
                if( $request->has('flowsheet_category_id') ) {
                    foreach( $request->input('flowsheet_category_id') as $cats ) {
                        $arr = array();
                        $arr['flowsheet_id'] = $flowsheet_id;
                        $arr['flowsheet_category_id'] = $cats;
                        array_push( $categoriesMap, $arr );
                    }
                    if( !empty($categoriesMap) ) {
                        FlowsheetCategoriesMap::insert( $categoriesMap );
                    }
                }
    
            // return back()->with('msg', 'Industry Flowsheet Created Successfully.')
            // ->with('msg_class', 'alert alert-success');

            return redirect()->route('allFSs')->with('msg', 'Industry Flowsheet Created Successfully.')
            ->with('msg_class', 'alert alert-success');
    
            }
        }
    
    
    }

else{



    	$insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

    	$categoriesMap = array();
        $imagesMap = array();

        $Flowsheet = Flowsheet::find($fs_id);
        
    	$Flowsheet->name = trim( ucfirst($request->input('name')) );
    	$Flowsheet->slug = trim($request->input('slug'));
    	$Flowsheet->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
    	$Flowsheet->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
    	$Flowsheet->updated_by = Auth::user()->id;
        $Flowsheet->language_id = trim( $request->input('language_id') );
        $Flowsheet->meta_title = trim($request->input('meta_title'));
        $Flowsheet->meta_desc = trim($request->input('meta_desc'));
        $Flowsheet->meta_keyword = trim($request->input('meta_keyword'));
        $Flowsheet->canonical_url = trim($request->input('canonical_url'));
        $Flowsheet->lng_tag = trim($request->input('lng_tag'));
        $Flowsheet->follow = trim($request->input('follow'));
        $Flowsheet->index_tag = trim($request->input('index_tag'));
        $Flowsheet->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );

        $fs_image_infos = json_decode( trim( $request->input('fs_image_infos') ) );
    	
    	if( $Flowsheet->save() ) {

    		$flowsheet_id = $fs_id;

    		CmsLinks::where('table_type', '=', 'FLOWSHEET')->where('table_id', '=', $flowsheet_id)
    		->update([ 'slug_url' => trim($request->input('slug')) ]);

            /** Need For Page Builder -- Update Time **/
            $cmsInfo = CmsLinks::where('table_id', '=', $flowsheet_id)->where('table_type', '=', 'FLOWSHEET')->first();
            if( !empty($cmsInfo) ) {
                
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                update_page_builder($insert_id, $cmsInfo->id, $flowsheet_id, 'FLOWSHEET');

            }
            /** End Page Builder **/ 


            if( !empty($fs_image_infos) ) {
                foreach ($fs_image_infos as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['flowsheet_id'] = $flowsheet_id;
                        $arr['image_id'] = $v->img_id;
                        $arr['title'] = $v->img_titl;
                        $arr['caption'] = $v->img_cap;
                        $arr['alt_tag'] = $v->img_alt;
                        $arr['description'] = $v->img_dsc;
                        $arr['image_type'] = "FS_IMAGE";
                        array_push( $imagesMap, $arr );
                    }
                }

                if( !empty($imagesMap) ) {
                    FlowsheetImagesMap::insert($imagesMap);
                }
            }   		

	    	FlowsheetCategoriesMap::where('flowsheet_id', '=', $flowsheet_id)->delete();
	    	if( $request->has('flowsheet_category_id') ) {
	    		foreach( $request->input('flowsheet_category_id') as $cats ) {
	    			$arr = array();
	    			$arr['flowsheet_id'] = $flowsheet_id;
	    			$arr['flowsheet_category_id'] = $cats;
	    			array_push( $categoriesMap, $arr );
	    		}
	    		if( !empty($categoriesMap) ) {
	    			FlowsheetCategoriesMap::insert( $categoriesMap );
	    		}
	    	}

	    	

	    // return back()->with('msg', 'Industry Flowsheet Updated Successfully.')
        // ->with('msg_class', 'alert alert-success');
        
        return redirect()->route('allFSs')->with('msg', 'Industry Flowsheet Updated Successfully.')
        ->with('msg_class', 'alert alert-success');

        }
        
    }
    
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }



    public function delete($fs_id) {

    	$ck = Flowsheet::find($fs_id);
    	if( isset($ck) && !empty($ck) ) {
    		$ck->status = '3';
    		$res = $ck->save();
    		if( isset($res) && $res == 1 ) {
    			
                delete_navigation($fs_id, 'FLOWSHEET');
    			CmsLinks::where('table_type', '=', 'FLOWSHEET')->where('table_id', '=', $fs_id)->delete();
    			FlowsheetCategoriesMap::where('flowsheet_id', '=', $fs_id)->delete();
    			//FlowsheetImagesMap::where('flowsheet_id', '=', $fs_id)->delete();
    			//FlowsheetFilesMap::where('flowsheet_id', '=', $fs_id)->delete();
                PageBuilder::where('table_id', '=', $fs_id)->where('table_type', '=', 'FLOWSHEET')->delete();

    			return back()->with('msg', 'Industry Flowsheet Deleted Successfully.')
    			->with('msg_class', 'alert alert-success');
    		}
    	}

    	return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }
















/*** Language Flowsheet Category***/

    public function addEditCatLanguage($parent_language_id, $child_language_id = '') {

        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'indusFsheet';
        $DataBag['childMenu'] = 'FsheetAddCat';
        $DataBag['parentLngCont'] = FlowsheetCategories::findOrFail($parent_language_id);
        if( $child_language_id != '' ) {
            $DataBag['fscategory'] = FlowsheetCategories::findOrFail($child_language_id);
            $DataBag['pageBuilderData'] = $DataBag['fscategory'];
        }
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));
        return view('dashboard.industry_flowsheet.addedit_language_category', $DataBag);
    }

    public function addEditCatLanguagePost(Request $request, $parent_language_id, $child_language_id = '') {
        
        if( $child_language_id != '' && $child_language_id != null ) {

            $insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

            $FlowsheetCategories = FlowsheetCategories::find($child_language_id);
            $FlowsheetCategories->name = trim( ucfirst($request->input('name')) );
            $FlowsheetCategories->slug = trim($request->input('slug'));
            $FlowsheetCategories->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
            $FlowsheetCategories->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $FlowsheetCategories->updated_by = Auth::user()->id;
            
            $FlowsheetCategories->meta_title = trim($request->input('meta_title'));
            $FlowsheetCategories->meta_desc = trim($request->input('meta_desc'));
            $FlowsheetCategories->meta_keyword = trim($request->input('meta_keyword'));
            $FlowsheetCategories->canonical_url = trim($request->input('canonical_url'));
            $FlowsheetCategories->lng_tag = trim($request->input('lng_tag'));
            $FlowsheetCategories->follow = trim($request->input('follow'));
            $FlowsheetCategories->index_tag = trim($request->input('index_tag'));

            if( $FlowsheetCategories->save() ) {

                CmsLinks::where('table_type', '=', 'FLOWSHEET_CATEGORY')->where('table_id', '=', $child_language_id)
                ->update([ 'slug_url' => trim($request->input('slug')) ]);

                /** Need For Page Builder -- Update Time **/
                $cmsInfo = CmsLinks::where('table_id', '=', $child_language_id)->where('table_type', '=', 'FLOWSHEET_CATEGORY')->first();
                if( !empty($cmsInfo) ) {
                    
                    /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                    update_page_builder($insert_id, $cmsInfo->id, $child_language_id, 'FLOWSHEET_CATEGORY');

                }
                /** End Page Builder **/

                return back()->with('msg', 'Industry Flowsheet Category Updated Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
        }

        if( $child_language_id == '' ) {

            $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

            $FlowsheetCategories = new FlowsheetCategories;
            $FlowsheetCategories->name = trim( ucfirst($request->input('name')) );
            $FlowsheetCategories->slug = trim($request->input('slug'));
            $FlowsheetCategories->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
            $FlowsheetCategories->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $FlowsheetCategories->created_by = Auth::user()->id;
            $FlowsheetCategories->language_id = trim( $request->input('language_id') );
            $FlowsheetCategories->parent_language_id = $parent_language_id;

            $FlowsheetCategories->insert_id = $insert_id;

            $FlowsheetCategories->meta_title = trim($request->input('meta_title'));
            $FlowsheetCategories->meta_desc = trim($request->input('meta_desc'));
            $FlowsheetCategories->meta_keyword = trim($request->input('meta_keyword'));
            $FlowsheetCategories->canonical_url = trim($request->input('canonical_url'));
            $FlowsheetCategories->lng_tag = trim($request->input('lng_tag'));
            $FlowsheetCategories->follow = trim($request->input('follow'));
            $FlowsheetCategories->index_tag = trim($request->input('index_tag'));

            if( $FlowsheetCategories->save() ) {

                $flowsheet_category_id = $FlowsheetCategories->id;

                $CmsLinks = new CmsLinks;
                $CmsLinks->table_id = $flowsheet_category_id;
                $CmsLinks->slug_url = trim($request->input('slug'));
                $CmsLinks->table_type = 'FLOWSHEET_CATEGORY';
                $CmsLinks->save();

                $cms_link_id = $CmsLinks->id; // Need for page builder as parameter

                /** For Page Builder -- Insert Time **/
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                update_page_builder($insert_id, $cms_link_id, $flowsheet_category_id, 'FLOWSHEET_CATEGORY');
                /** End Page Builder **/

                return redirect()->route('edtFSc', array('id' => $parent_language_id))
                ->with('msg', 'Industry Flowsheet Category Created Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
        }

        return back();
    }

    public function deleteCatLanguage($parent_language_id, $child_language_id) {
        
        FlowsheetCategories::find($child_language_id)->delete();
        delete_navigation($child_language_id, 'FLOWSHEET_CATEGORY');
        CmsLinks::where('table_type', '=', 'FLOWSHEET_CATEGORY')->where('table_id', '=', $child_language_id)->delete();


        return redirect()->route('edtFSc', array('id' => $parent_language_id))
        ->with('msg', 'Industry Flowsheet Category Deleted Successfully.')
        ->with('msg_class', 'alert alert-success');
    }





/*** Language Flowsheet ***/

    public function addEditLanguage($parent_language_id, $child_language_id = '') {

        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'indusFsheet';
        $DataBag['childMenu'] = 'addFsheet';
        $DataBag['parentLngCont'] = Flowsheet::findOrFail($parent_language_id);
        if( $child_language_id != '' ) {
            $DataBag['flowsheet'] = Flowsheet::findOrFail($child_language_id);
            $DataBag['pageBuilderData'] = $DataBag['flowsheet'];
        }
        $DataBag['allCats'] = FlowsheetCategories::where('status', '=', '1')->orderBy('name', 'asc')->get();
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));
        return view('dashboard.industry_flowsheet.addedit_language', $DataBag);
    }

    public function addEditLanguagePost(Request $request, $parent_language_id, $child_language_id = '') {
        
        if( $child_language_id != '' && $child_language_id != null ) {

            $insert_id = trim( $request->input('insert_id') ); // Page Builder --

            $categoriesMap = array();
            $imagesMap = array();

            $Flowsheet = Flowsheet::find($child_language_id);
            $Flowsheet->name = trim( ucfirst($request->input('name')) );
            $Flowsheet->slug = trim($request->input('slug'));
            $Flowsheet->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
            $Flowsheet->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $Flowsheet->updated_by = Auth::user()->id;

            $Flowsheet->meta_title = trim($request->input('meta_title'));
            $Flowsheet->meta_desc = trim($request->input('meta_desc'));
            $Flowsheet->meta_keyword = trim($request->input('meta_keyword'));
            $Flowsheet->canonical_url = trim($request->input('canonical_url'));
            $Flowsheet->lng_tag = trim($request->input('lng_tag'));
            $Flowsheet->follow = trim($request->input('follow'));
            $Flowsheet->index_tag = trim($request->input('index_tag'));

            $fs_image_infos = json_decode( trim( $request->input('fs_image_infos') ) );
            
            if( $Flowsheet->save() ) {

                $flowsheet_id = $child_language_id;

                CmsLinks::where('table_type', '=', 'FLOWSHEET')->where('table_id', '=', $flowsheet_id)
                ->update([ 'slug_url' => trim($request->input('slug')) ]);

                /** Need For Page Builder -- Update Time **/
                $cmsInfo = CmsLinks::where('table_id', '=', $flowsheet_id)->where('table_type', '=', 'FLOWSHEET')->first();
                if( !empty($cmsInfo) ) {
                    
                    /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                    update_page_builder($insert_id, $cmsInfo->id, $flowsheet_id, 'FLOWSHEET');

                }
                /** End Page Builder **/


                if( !empty($fs_image_infos) ) {
                    foreach ($fs_image_infos as $v) {
                        if( $v->img_id != '' ) {
                            $arr = array();
                            $arr['flowsheet_id'] = $flowsheet_id;
                            $arr['image_id'] = $v->img_id;
                            $arr['title'] = $v->img_titl;
                            $arr['caption'] = $v->img_cap;
                            $arr['alt_tag'] = $v->img_alt;
                            $arr['description'] = $v->img_dsc;
                            $arr['image_type'] = "FS_IMAGE";
                            array_push( $imagesMap, $arr );
                        }
                    }

                    if( !empty($imagesMap) ) {
                        FlowsheetImagesMap::insert($imagesMap);
                    }
                }


                FlowsheetCategoriesMap::where('flowsheet_id', '=', $flowsheet_id)->delete();
                if( $request->has('flowsheet_category_id') ) {
                    foreach( $request->input('flowsheet_category_id') as $cats ) {
                        $arr = array();
                        $arr['flowsheet_id'] = $flowsheet_id;
                        $arr['flowsheet_category_id'] = $cats;
                        array_push( $categoriesMap, $arr );
                    }
                    if( !empty($categoriesMap) ) {
                        FlowsheetCategoriesMap::insert( $categoriesMap );
                    }
                }

                
                return back()->with('msg', 'Industry Flowsheet Updated Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
        }

        if( $child_language_id == '' ) {

            $insert_id = trim( $request->input('insert_id') ); // Page Builder --
            $categoriesMap = array();
            $imagesMap = array();

            $Flowsheet = new Flowsheet;
            $Flowsheet->name = trim( ucfirst($request->input('name')) );
            $Flowsheet->slug = trim($request->input('slug'));
            $Flowsheet->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
            $Flowsheet->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $Flowsheet->created_by = Auth::user()->id;
    
            $Flowsheet->insert_id = $insert_id;
            $Flowsheet->language_id = trim( $request->input('language_id') );
            $Flowsheet->parent_language_id = $parent_language_id;

            $Flowsheet->meta_title = trim($request->input('meta_title'));
            $Flowsheet->meta_desc = trim($request->input('meta_desc'));
            $Flowsheet->meta_keyword = trim($request->input('meta_keyword'));
            $Flowsheet->canonical_url = trim($request->input('canonical_url'));
            $Flowsheet->lng_tag = trim($request->input('lng_tag'));
            $Flowsheet->follow = trim($request->input('follow'));
            $Flowsheet->index_tag = trim($request->input('index_tag'));

            $fs_image_infos = json_decode( trim( $request->input('fs_image_infos') ) );

            if( $Flowsheet->save() ) {

                $flowsheet_id = $Flowsheet->id;

                $CmsLinks = new CmsLinks;
                $CmsLinks->table_id = $flowsheet_id;
                $CmsLinks->slug_url = trim($request->input('slug'));
                $CmsLinks->table_type = 'FLOWSHEET';
                $CmsLinks->save();

                $cms_link_id = $CmsLinks->id; // Need for page builder as parameter

                /** For Page Builder -- Insert Time **/
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                update_page_builder($insert_id, $cms_link_id, $flowsheet_id, 'FLOWSHEET');
                /** End Page Builder **/


                if( !empty($fs_image_infos) ) {
                    foreach ($fs_image_infos as $v) {
                        if( $v->img_id != '' ) {
                            $arr = array();
                            $arr['flowsheet_id'] = $flowsheet_id;
                            $arr['image_id'] = $v->img_id;
                            $arr['title'] = $v->img_titl;
                            $arr['caption'] = $v->img_cap;
                            $arr['alt_tag'] = $v->img_alt;
                            $arr['description'] = $v->img_dsc;
                            $arr['image_type'] = "FS_IMAGE";
                            array_push( $imagesMap, $arr );
                        }
                    }

                    if( !empty($imagesMap) ) {
                        FlowsheetImagesMap::insert($imagesMap);
                    }
                }

                if( $request->has('flowsheet_category_id') ) {
                    foreach( $request->input('flowsheet_category_id') as $cats ) {
                        $arr = array();
                        $arr['flowsheet_id'] = $flowsheet_id;
                        $arr['flowsheet_category_id'] = $cats;
                        array_push( $categoriesMap, $arr );
                    }
                    if( !empty($categoriesMap) ) {
                        FlowsheetCategoriesMap::insert( $categoriesMap );
                    }
                }

                

                return redirect()->route('editFS', array('id' => $parent_language_id))
                ->with('msg', 'Industry Flowsheet Created Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
        }

        return back();
    }



    public function deleteLanguage($parent_language_id, $child_language_id) {
        
        Flowsheet::find($child_language_id)->delete();
        CmsLinks::where('table_type', '=', 'FLOWSHEET')->where('table_id', '=', $child_language_id)->delete();
        FlowsheetCategoriesMap::where('flowsheet_id', '=', $child_language_id)->delete();

        delete_navigation($child_language_id, 'FLOWSHEET');
        CmsLinks::where('table_type', '=', 'FLOWSHEET')->where('table_id', '=', $child_language_id)->delete();
        
        //FlowsheetImagesMap::where('flowsheet_id', '=', $child_language_id)->delete();
        //FlowsheetFilesMap::where('flowsheet_id', '=', $child_language_id)->delete();
        
        return redirect()->route('editFS', array('id' => $parent_language_id))
        ->with('msg', 'Industry Flowsheet Deleted Successfully.')
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
                        $Flowsheet = Flowsheet::find($id);
                        $Flowsheet->status = '1';
                        $Flowsheet->save();
                    }
                    $msg = 'Flow Sheet Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $Flowsheet = Flowsheet::find($id);
                        $Flowsheet->status = '2';
                        $Flowsheet->save();
                    }
                    $msg = 'Flow Sheet Deactivated Succesfully.';
                    break;

                case 'delete':
                    foreach($idsArr as $id) {
                        $Flowsheet = Flowsheet::find($id);
                        $Flowsheet->status = '3';
                        $Flowsheet->save();
                        delete_navigation($id, 'FLOWSHEET');
                        CmsLinks::where('table_type', '=', 'FLOWSHEET')->where('table_id', '=', $id)->delete();
                        FlowsheetCategoriesMap::where('flowsheet_id', '=', $id)->delete();
                        //FlowsheetImagesMap::where('flowsheet_id', '=', $id)->delete();
                        //FlowsheetFilesMap::where('flowsheet_id', '=', $id)->delete();
                        PageBuilder::where('table_id', '=', $id)->where('table_type', '=', 'FLOWSHEET')->delete();
                    }
                    $msg = 'Flow Sheet Deleted Succesfully.';
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
                        $FlowsheetCategories = FlowsheetCategories::find($id);
                        $FlowsheetCategories->status = '1';
                        $FlowsheetCategories->save();
                    }
                    $msg = 'Flow Sheet Category Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $FlowsheetCategories = FlowsheetCategories::find($id);
                        $FlowsheetCategories->status = '2';
                        $FlowsheetCategories->save();
                    }
                    $msg = 'Flow Sheet Category Deactivated Succesfully.';
                    break;

                case 'delete':
                    foreach($idsArr as $id) {
                        $FlowsheetCategories = FlowsheetCategories::find($id);
                        $FlowsheetCategories->status = '3';
                        $FlowsheetCategories->save();
                        delete_navigation($id, 'FLOWSHEET_CATEGORY');
                        CmsLinks::where('table_type', '=', 'FLOWSHEET_CATEGORY')->where('table_id', '=', $id)->delete();
                        PageBuilder::where('table_id', '=', $id)->where('table_type', '=', 'FLOWSHEET_CATEGORY')->delete();
                    }
                    $msg = 'Flow Sheet Category Deleted Succesfully.';
                    break;
            }
            return back()->with('msg', $msg)->with('msg_class', 'alert alert-success');
        }
        return back();
    }
}
