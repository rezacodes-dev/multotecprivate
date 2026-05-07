<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CmsLinks;
use App\Models\Media\Images;
use App\Models\EventCategoryMap;
use App\Models\EventCategories;
use App\Models\EventCategoryImagesMap;
use App\Models\Events;
use App\Models\EventImagesMap;
use App\Models\Languages;
use App\Models\PageBuilder\PageBuilder;
use App\Models\Media\MediaExtraContent;
use Image;
use Auth;

class EventController extends Controller
{
    
    public function categories() {
    	$dataBag = array();
        $dataBag['GparentMenu'] = 'contentManagement';
    	$dataBag['parentMenu'] = "eventManagement";
    	$dataBag['childMenu'] = "catsEvent";
    	$dataBag['allCategories'] = EventCategories::where('status', '!=', '3')->where('parent_language_id', '=', '0')
        ->orderBy('created_at', 'desc')->get();
    	return view('dashboard.events.categories', $dataBag);
    }

    public function createCategory() {
    	$dataBag = array();
        $dataBag['GparentMenu'] = 'contentManagement';
    	$dataBag['parentMenu'] = "eventManagement";
    	$dataBag['childMenu'] = "crteEvtCat";
        $dataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
    	return view('dashboard.events.create_category', $dataBag);
    }

    public function saveCategory(Request $request) {

    	$request->validate([
			
            'name' => 'required|unique:event_categories,name',
            'slug' => 'required|unique:event_categories,slug'
		],[
		
			'name.unique' => 'Category Name Already Exist, Try Another.',
			'slug.unique' => 'Category Slug Already Exist, Try Another.'
		]);

		$EventCategories = new EventCategories;
		$EventCategories->name = trim($request->input('name'));
		$EventCategories->slug = trim($request->input('slug'));
		$EventCategories->page_content = htmlentities(trim($request->input('page_content')), ENT_QUOTES);
        $EventCategories->mob_page_content = htmlentities(trim($request->input('mob_page_content')), ENT_QUOTES);
		$EventCategories->parent_id = 0;
		$EventCategories->created_by = Auth::user()->id;
        $EventCategories->language_id = trim( $request->input('language_id') );

        $EventCategories->image_title = trim($request->input('image_title'));
        $EventCategories->image_alt = trim($request->input('image_alt'));
        $EventCategories->image_caption = trim($request->input('image_caption'));


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

                $EventCategories->image_id = $Images->id;  
            }
        }

		$res = $EventCategories->save();
		if( $res ) {

			$evt_cat_id = $EventCategories->id;

			$CmsLinks = new CmsLinks;
    		$CmsLinks->table_id = $evt_cat_id;
    		$CmsLinks->slug_url = trim($request->input('slug'));
    		$CmsLinks->table_type = 'EVENT_CATEGORY';
    		$CmsLinks->save();

    		return back()->with('msg', 'Event Category Created Successfully.')
    		->with('msg_class', 'alert alert-success');
    	} else {
    		return back()->with('msg', 'Something Went Wrong.')
    		->with('msg_class', 'alert alert-danger');
    	}
    }

    public function editCategory($id) {
    	$dataBag = array();
        $dataBag['GparentMenu'] = 'contentManagement';
    	$dataBag['parentMenu'] = "eventManagement";
    	$dataBag['childMenu'] = "crteEvtCat";
    	$dataBag['category'] = EventCategories::findOrFail($id);
        $dataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
    	return view('dashboard.events.create_category', $dataBag);
    }

    public function updateCategory(Request $request, $id) {
    	$request->validate([
			
            'name' => 'required|unique:event_categories,name,'.$id,
            'slug' => 'required|unique:event_categories,slug,'.$id
		],[
		
			'name.unique' => 'Category Name Already Exist, Try Another.',
			'slug.unique' => 'Category Slug Already Exist, Try Another.'
		]);

		$EventCategories = EventCategories::find($id);
		$EventCategories->name = trim($request->input('name'));
		$EventCategories->slug = trim($request->input('slug'));
		$EventCategories->page_content = htmlentities(trim($request->input('page_content')), ENT_QUOTES);
        $EventCategories->mob_page_content = htmlentities(trim($request->input('mob_page_content')), ENT_QUOTES);
		$EventCategories->parent_id = 0;
		$EventCategories->updated_by = Auth::user()->id;
		$EventCategories->updated_at = date('Y-m-d H:i:s');

        $EventCategories->image_title = trim($request->input('image_title'));
        $EventCategories->image_alt = trim($request->input('image_alt'));
        $EventCategories->image_caption = trim($request->input('image_caption'));


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

                $EventCategories->image_id = $Images->id;  
            }
        }

		$res = $EventCategories->save();
		if( $res ) {

			CmsLinks::where('table_type', '=', 'EVENT_CATEGORY')->where('table_id', '=', $id)
    		->update( [ 'slug_url' => trim($request->input('category_slug')) ] );

    		return back()->with('msg', 'Event Category Updated Successfully.')
    		->with('msg_class', 'alert alert-success');
    	} else {
    		return back()->with('msg', 'Something Went Wrong.')
    		->with('msg_class', 'alert alert-danger');
    	}
    }

    public function deleteCategory($id) {
    	$res = EventCategories::where('id', '=', $id)->update(['status' => '3']);
    	if( $res ) {

            EventCategoryImagesMap::where('event_category_id', '=', $id)->delete();
    		CmsLinks::where('table_type', '=', 'EVENT_CATEGORY')->where('table_id', '=', $id)->delete();
            delete_navigation($id, 'EVENT_CATEGORY');

    		return back()->with('msg', 'Event Category Deleted Successfully.')
    		->with('msg_class', 'alert alert-success');
    	} else {
    		return back()->with('msg', 'Something Went Wrong.')
    		->with('msg_class', 'alert alert-danger');
    	}
    }

    public function calView() {
    	$dataBag = array();
        $dataBag['GparentMenu'] = 'contentManagement';
    	$dataBag['parentMenu'] = "eventManagement";
    	$dataBag['childMenu'] = "calView";
    	$dataBag['events'] = Events::where('parent_language_id', '=', '0')->where('end_date', '>=', date('Y-m-d'))
    	->where('status', '=', '1')->get();
    	return view('dashboard.events.event_calander', $dataBag);
    }

    public function index() {
    	$dataBag = array();
        $dataBag['GparentMenu'] = 'contentManagement';
    	$dataBag['parentMenu'] = "eventManagement";
    	$dataBag['childMenu'] = "listView";
    	$dataBag['allEvents'] = Events::where('status', '!=', '3')->where('parent_language_id', '=', '0')
    	->orderBy('created_at', 'desc')->get();
    	return view('dashboard.events.index', $dataBag);
    }

    public function create() {
    	$dataBag = array();
        $dataBag['GparentMenu'] = 'contentManagement';
    	$dataBag['parentMenu'] = "eventManagement";
    	$dataBag['childMenu'] = "crteEvent";
    	$dataBag['cats'] = EventCategories::where('status', '!=', '3')->orderBy('name', 'asc')->get();
    	$dataBag['countries'] = \App\Models\Countries::where('status', '!=', '3')->orderBy('country_name', 'asc')->get();
    	$dataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
        $dataBag['insert_id'] = md5(microtime(TRUE));
    	return view('dashboard.events.create', $dataBag);
    }

    public function save(Request $request) {
    	$request->validate([
			
            'name' => 'required|unique:events,name',
            'slug' => 'required|unique:events,slug'
		],[
		
			'name.unique' => 'Event Name Already Exist, Try Another.',
			'slug.unique' => 'Event URL Already Exist, Try Another.'
		]);

        $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

		$Events = new Events;
		$Events->name = trim($request->input('name'));
        $Events->insert_id = $insert_id;
		$Events->slug = trim($request->input('slug'));
		$Events->description = htmlentities( trim($request->input('description')), ENT_QUOTES );
        $Events->page_content = htmlentities( trim($request->input('page_content')), ENT_QUOTES );
		$Events->meta_title = trim($request->input('meta_title'));
		$Events->meta_desc = trim($request->input('meta_desc'));
		$Events->meta_keyword = trim($request->input('meta_keyword'));
        $Events->canonical_url = trim($request->input('canonical_url'));
        $Events->follow = trim($request->input('follow'));
        $Events->lng_tag = trim($request->input('lng_tag'));
        $Events->index_tag = trim($request->input('index_tag'));
        $Events->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );

		$Events->start_date = date( 'Y-m-d', strtotime(trim($request->input('start_date'))) );
		$Events->start_time = date( 'H:i:s', strtotime(trim($request->input('start_time'))) );
		$Events->end_date = date( 'Y-m-d', strtotime(trim($request->input('end_date'))) );
		$Events->end_time = date( 'H:i:s', strtotime(trim($request->input('end_time'))) );
		$Events->venue_name = trim($request->input('venue_name'));
		$Events->venue_address = trim($request->input('venue_address'));
		$Events->pincode = trim($request->input('pincode'));
		//$Events->country_id = trim($request->input('country_id'));
		//$Events->province_id = trim($request->input('province_id'));
		//$Events->city_id = trim($request->input('city_id'));
		$Events->status = trim($request->input('status'));
		$Events->created_by = Auth::user()->id;
		$Events->language_id = trim($request->input('language_id'));

        $Events->display_order = trim($request->input('display_order'));

        $Events->publish_date = date('Y-m-d', strtotime( trim($request->input('publish_date') ) ));

		if( $request->exists('color') ) {
			$Events->color = trim($request->input('color'));	
		}
		
        $evtCatArr = $request->input('category_id');

        $eventImageJson = json_decode( trim( $request->input('event_image_infos') ) );
        $thumbImageJson = json_decode( trim( $request->input('thumb_image_infos') ) );

		$res = $Events->save();
		if( $res ) {
			$event_id = $Events->id;

			$CmsLinks = new CmsLinks;
    		$CmsLinks->table_id = $event_id;
    		$CmsLinks->slug_url = trim($request->input('slug'));
    		$CmsLinks->table_type = 'EVENT';
    		$CmsLinks->save();
            $cms_link_id = $CmsLinks->id; // Need for page builder as parameter

            /** For Page Builder -- Insert Time **/
            /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
            update_page_builder($insert_id, $cms_link_id, $event_id, 'EVENT');
            /** End Page Builder **/


            if( !empty($eventImageJson) ) {
                $imageMap = array();
                foreach ($eventImageJson as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['event_id'] = $event_id;
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
                    EventImagesMap::insert($imageMap);
                }
            }

            if( !empty($thumbImageJson) ) {
                $imageMap = array();
                foreach ($thumbImageJson as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['event_id'] = $event_id;
                        $arr['image_id'] = $v->img_id;
                        $arr['title'] = $v->img_titl;
                        $arr['caption'] = $v->img_cap;
                        $arr['alt_tag'] = $v->img_alt;
                        $arr['description'] = $v->img_dsc;
                        $arr['image_type'] = "THUMB_IMAGE";
                        array_push( $imageMap, $arr );
                    }
                }

                if( !empty($imageMap) ) {
                    EventImagesMap::insert($imageMap);
                }
            }

			if( !empty($evtCatArr) ) {
				$insertArr = array();
				foreach($evtCatArr as $v) {
					$arr = array();
					$arr['event_category_id'] = $v;
					$arr['event_id'] = $event_id;
					$arr['created_at'] = date('Y-m-d H:i:s');
					array_push($insertArr, $arr);
				}
				EventCategoryMap::insert($insertArr);
			}
			return back()->with('msg', 'Event Created Saved Successfully.')
    		->with('msg_class', 'alert alert-success');
		} else {
			return back()->with('msg', 'Something Went Wrong.')
    		->with('msg_class', 'alert alert-danger');
		}
    }

    public function delete($id) {
    	$res = Events::where('id', '=', $id)->update(['status' => '3']);
    	if( $res ) {

    		EventCategoryMap::where('event_id', '=', $id)->delete();
            EventImagesMap::where('event_id', '=', $id)->delete();
    		CmsLinks::where('table_type', '=', 'EVENT')->where('table_id', '=', $id)->delete();
            delete_navigation($id, 'EVENT');
            PageBuilder::where('table_id', '=', $id)->where('table_type', '=', 'EVENT')->delete();

    		return back()->with('msg', 'Email Template Deleted Successfully.')
    		->with('msg_class', 'alert alert-success');
    	} else {
    		return back()->with('msg', 'Something Went Wrong.')
    		->with('msg_class', 'alert alert-danger');
    	}
    }

    public function edit($id) {
    	$dataBag = array();
        $dataBag['GparentMenu'] = 'contentManagement';
    	$dataBag['parentMenu'] = "eventManagement";
    	$dataBag['childMenu'] = "crteEvent";
    	$dataBag['cats'] = EventCategories::where('status', '=', '1')->orderBy('name', 'asc')->get();
    	$dataBag['countries'] = \App\Models\Countries::where('status', '!=', '3')->orderBy('country_name', 'asc')->get();
    	$event = Events::findOrFail($id);
    	$selectedCountryID = 0;
    	$selectedProvinceID = 0;
    	$dataBag['event'] = $event;
        $dataBag['pageBuilderData'] = $dataBag['event'];
    	$dataBag['eventCats'] = EventCategoryMap::where('event_id', '=', $id)->pluck('event_category_id')->toArray();
    	
    	if( isset($event) && !empty($event) ) {
    		$selectedCountryID = $event->country_id;
    		$selectedProvinceID = $event->province_id;
    	}

    	$dataBag['SelectedProvince'] = \App\Models\Provinces::where('status', '=', '1')
    	->where('country_id', '=', $selectedCountryID)->orderBy('province_name', 'asc')->get();

    	$dataBag['SelectedCity'] = \App\Models\Cities::where('status', '=', '1')
    	->where('province_id', '=', $selectedProvinceID)->orderBy('city_name', 'asc')->get();

    	$dataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
    	
    	return view('dashboard.events.create', $dataBag);
    }

    public function update(Request $request, $id) {
    	$request->validate([
			
            'name' => 'required|unique:events,name,'. $id,
            'slug' => 'required|unique:events,slug,'. $id
		],[
		
			'name.unique' => 'Event Name Already Exist, Try Another.',
			'slug.unique' => 'Event URL Already Exist, Try Another.'
		]);

        $insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

		$Events = Events::find($id);
		$Events->name = trim($request->input('name'));
        $Events->insert_id = $insert_id;
		$Events->slug = trim($request->input('slug'));
        $Events->description = htmlentities( trim($request->input('description')), ENT_QUOTES );
		$Events->page_content = htmlentities( trim($request->input('page_content')), ENT_QUOTES );
		$Events->meta_title = trim($request->input('meta_title'));
        $Events->meta_desc = trim($request->input('meta_desc'));
        $Events->meta_keyword = trim($request->input('meta_keyword'));
        $Events->canonical_url = trim($request->input('canonical_url'));
        $Events->follow = trim($request->input('follow'));
        $Events->lng_tag = trim($request->input('lng_tag'));
        $Events->index_tag = trim($request->input('index_tag'));
        $Events->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );
        
		$Events->start_date = date( 'Y-m-d', strtotime(trim($request->input('start_date'))) );
		$Events->start_time = date( 'H:i:s', strtotime(trim($request->input('start_time'))) );
		$Events->end_date = date( 'Y-m-d', strtotime(trim($request->input('end_date'))) );
		$Events->end_time = date( 'H:i:s', strtotime(trim($request->input('end_time'))) );
		$Events->venue_name = trim($request->input('venue_name'));
		$Events->venue_address = trim($request->input('venue_address'));
		$Events->pincode = trim($request->input('pincode'));
		//$Events->country_id = trim($request->input('country_id'));
		//$Events->province_id = trim($request->input('province_id'));
		//$Events->city_id = trim($request->input('city_id'));
		$Events->status = trim($request->input('status'));
		$Events->updated_by = Auth::user()->id;
		$Events->updated_at = date('Y-m-d H:i:s');

        $Events->display_order = trim($request->input('display_order'));

        $Events->publish_date = date('Y-m-d', strtotime( trim($request->input('publish_date') ) ));
        
		$Events->language_id = trim($request->input('language_id'));
        
		if( $request->exists('color') ) {
			$Events->color = trim($request->input('color'));	
		}
		
        $evtCatArr = $request->input('category_id');

        $eventImageJson = json_decode( trim( $request->input('event_image_infos') ) );
        $thumbImageJson = json_decode( trim( $request->input('thumb_image_infos') ) );

		$res = $Events->save();
		if( $res ) {
			$event_id = $id;

			CmsLinks::where('table_type', '=', 'EVENT')->where('table_id', '=', $event_id)
    		->update( [ 'slug_url' => trim($request->input('slug')) ] );
            /** Need For Page Builder -- Update Time **/
            $cmsInfo = CmsLinks::where('table_id', '=', $event_id)->where('table_type', '=', 'EVENT')->first();

            if( !empty($cmsInfo) ) {
                
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                update_page_builder($insert_id, $cmsInfo->id, $event_id, 'EVENT');

            }
            /** End Page Builder **/

            if( !empty($eventImageJson) ) {
                $imageMap = array();
                foreach ($eventImageJson as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['event_id'] = $event_id;
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
                    EventImagesMap::insert($imageMap);
                }
            }

            if( !empty($thumbImageJson) ) {
                $imageMap = array();
                foreach ($thumbImageJson as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['event_id'] = $event_id;
                        $arr['image_id'] = $v->img_id;
                        $arr['title'] = $v->img_titl;
                        $arr['caption'] = $v->img_cap;
                        $arr['alt_tag'] = $v->img_alt;
                        $arr['description'] = $v->img_dsc;
                        $arr['image_type'] = "THUMB_IMAGE";
                        array_push( $imageMap, $arr );
                    }
                }

                if( !empty($imageMap) ) {
                    EventImagesMap::insert($imageMap);
                }
            }

    		EventCategoryMap::where('event_id', '=', $event_id)->delete();
			if( !empty($evtCatArr) ) {
				$insertArr = array();
				foreach($evtCatArr as $v) {
					$arr = array();
					$arr['event_category_id'] = $v;
					$arr['event_id'] = $event_id;
					$arr['created_at'] = date('Y-m-d H:i:s');
					array_push($insertArr, $arr);
				}
				EventCategoryMap::insert($insertArr);
			}
			return back()->with('msg', 'Event Updated Saved Successfully.')
    		->with('msg_class', 'alert alert-success');
		} else {
			return back()->with('msg', 'Something Went Wrong.')
    		->with('msg_class', 'alert alert-danger');
		}
    }

    public function ajaxevtCalModify(Request $request) {

    	if( $request->ajax() ) {
    		$Events = Events::find(trim($request->input('id')));
    		$Events->start_date = date( 'Y-m-d', strtotime(trim($request->input('start_date'))) );
    		$Events->end_date = date( 'Y-m-d', strtotime(trim($request->input('end_date'))) );
    		$res = $Events->save();
    	}
    }

    public function ajaxevtCkEdtUpload(Request $request) {

    	if(isset($_FILES) && !empty($_FILES)) {

            $image_name = $_FILES['upload']['name'];
            $expArr = explode('.', $image_name);
            
            if( !empty($expArr) ) {
                
                $ext = end($expArr);
                if( $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                    
                    $new_imgname = time().rand(123456,999999).".".$ext;
                    $upload_path = "public/uploads/editor_uploads/".$new_imgname;
                    move_uploaded_file($_FILES['upload']['tmp_name'], $upload_path) or die('error');
                    $CKEditorFuncNum = $_GET['CKEditorFuncNum'];
                    echo "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$upload_path', '')</script>";
                
                } else {

                    echo "<span style='color : RED;'><small>Select only images (jpg/png/gif)</small></span>";
                }
            
            } else {

                echo "<span style='color : RED;'>ERROR</span>";
            }    
       	}
    }

    /************** With Language *****************/


    public function addEditCatLanguage( $parent_language_id, $child_language_id = '' ) {
        $dataBag = array();
        $dataBag['GparentMenu'] = 'contentManagement';
        $dataBag['parentMenu'] = "eventManagement";
        $dataBag['childMenu'] = "crteEvtCat";
        $dataBag['parentLngCont'] = EventCategories::findOrFail($parent_language_id);
        if( $child_language_id != '' ) {
            $dataBag['category'] = EventCategories::findOrFail($child_language_id);
        }
        $dataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
        return view('dashboard.events.addedit_language_category', $dataBag);
    }

    public function addEditCatLanguagePost( Request $request, $parent_language_id, $child_language_id = '' ) {

        if( $child_language_id != '' && $child_language_id != null ) {

            $EventCategories = EventCategories::find($child_language_id);
            $EventCategories->name = trim($request->input('name'));
            $EventCategories->slug = trim($request->input('slug'));
            $EventCategories->page_content = htmlentities(trim($request->input('page_content')), ENT_QUOTES);
            $EventCategories->mob_page_content = htmlentities(trim($request->input('mob_page_content')), ENT_QUOTES);
            $EventCategories->status = trim($request->input('status'));
            $EventCategories->parent_id = 0;
            $EventCategories->updated_by = Auth::user()->id;
            $EventCategories->updated_at = date('Y-m-d H:i:s');

            $bannerImageJson = json_decode( trim( $request->input('banner_image_infos') ) );

            $res = $EventCategories->save();
            if( $res ) {

                CmsLinks::where('table_type', '=', 'EVENT_CATEGORY')->where('table_id', '=', $child_language_id)
                ->update( [ 'slug_url' => trim($request->input('category_slug')) ] );

                if( !empty($bannerImageJson) ) {
                    $imageMap = array();
                    foreach ($bannerImageJson as $v) {
                        if( $v->img_id != '' ) {
                            $arr = array();
                            $arr['event_category_id'] = $child_language_id;
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
                        EventCategoryImagesMap::insert($imageMap);
                    }
                }

                return back()->with('msg', 'Event Category Updated Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
        }

        if( $child_language_id == '' ) {

            $EventCategories = new EventCategories;
            $EventCategories->name = trim($request->input('name'));
            $EventCategories->slug = trim($request->input('slug'));
            $EventCategories->page_content = htmlentities(trim($request->input('page_content')), ENT_QUOTES);
            $EventCategories->mob_page_content = htmlentities(trim($request->input('mob_page_content')), ENT_QUOTES);
            $EventCategories->status = trim($request->input('status'));
            $EventCategories->parent_id = 0;
            $EventCategories->created_by = Auth::user()->id;
            $EventCategories->language_id = trim( $request->input('language_id') );
            $EventCategories->parent_language_id = $parent_language_id;

            $bannerImageJson = json_decode( trim( $request->input('banner_image_infos') ) );

            $res = $EventCategories->save();
            if( $res ) {

                $evt_cat_id = $EventCategories->id;

                $CmsLinks = new CmsLinks;
                $CmsLinks->table_id = $evt_cat_id;
                $CmsLinks->slug_url = trim($request->input('slug'));
                $CmsLinks->table_type = 'EVENT_CATEGORY';
                $CmsLinks->save();

                if( !empty($bannerImageJson) ) {
                    $imageMap = array();
                    foreach ($bannerImageJson as $v) {
                        if( $v->img_id != '' ) {
                            $arr = array();
                            $arr['event_category_id'] = $evt_cat_id;
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
                        EventCategoryImagesMap::insert($imageMap);
                    }
                }

                return redirect()->route('evt_edt_cat', array('id' => $parent_language_id))
                ->with('msg', 'Event Category Created Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
        }

        return back();
    }

    public function deleteCatLanguage( $parent_language_id, $child_language_id ) {

        EventCategories::where('id', '=', $child_language_id)->delete();

        EventCategoryImagesMap::where('event_category_id', '=', $child_language_id)->delete();

        CmsLinks::where('table_type', '=', 'EVENT_CATEGORY')->where('table_id', '=', $child_language_id)->delete();

        delete_navigation($child_language_id, 'EVENT_CATEGORY');

        return redirect()->route('evt_edt_cat', array('id' => $parent_language_id))
        ->with('msg', 'Event Category Deleted Successfully.')
        ->with('msg_class', 'alert alert-success');
    }


    public function addEditLanguage( $parent_language_id, $child_language_id = '' ) {

        $dataBag = array();
        $dataBag['GparentMenu'] = 'contentManagement';
        $dataBag['parentMenu'] = "eventManagement";
        $dataBag['childMenu'] = "crteEvent";
        $dataBag['cats'] = EventCategories::where('status', '=', '1')->orderBy('name', 'asc')->get();
        $dataBag['countries'] = \App\Models\Countries::where('status', '!=', '3')->orderBy('country_name', 'asc')->get();
        $selectedCountryID = 0;
        $selectedProvinceID = 0;
        
        $dataBag['parentLngCont'] = Events::findOrFail($parent_language_id);

        if( $child_language_id != '' ) {
            $dataBag['event'] =  $event = Events::findOrFail($child_language_id);
            $selectedCountryID = $event->country_id;
            $selectedProvinceID = $event->province_id;
            $dataBag['eventCats'] = EventCategoryMap::where('event_id', '=', $child_language_id)
            ->pluck('event_category_id')->toArray();   
        }
        
        $dataBag['SelectedProvince'] = \App\Models\Provinces::where('status', '=', '1')
        ->where('country_id', '=', $selectedCountryID)->orderBy('province_name', 'asc')->get();

        $dataBag['SelectedCity'] = \App\Models\Cities::where('status', '=', '1')
        ->where('province_id', '=', $selectedProvinceID)->orderBy('city_name', 'asc')->get();

        $dataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
        
        return view('dashboard.events.addedit_language', $dataBag);
    }

    public function addEditLanguagePost( Request $request, $parent_language_id, $child_language_id = '' ) {
        
        if( $child_language_id != '' && $child_language_id != null ) {

            $Events = Events::find($child_language_id);
            $Events->name = trim($request->input('name'));
            $Events->description = htmlentities( trim($request->input('description')), ENT_QUOTES );
            $Events->slug = trim($request->input('slug'));
            $Events->page_content = htmlentities( trim($request->input('page_content')), ENT_QUOTES );
            $Events->meta_title = trim($request->input('meta_title'));
            $Events->meta_desc = trim($request->input('meta_desc'));
            $Events->meta_keyword = trim($request->input('meta_keyword'));
            $Events->canonical_url = trim($request->input('canonical_url'));
            $Events->follow = trim($request->input('follow'));
            $Events->lng_tag = trim($request->input('lng_tag'));
            $Events->index_tag = trim($request->input('index_tag'));
            $Events->start_date = date( 'Y-m-d', strtotime(trim($request->input('start_date'))) );
            $Events->start_time = date( 'H:i:s', strtotime(trim($request->input('start_time'))) );
            $Events->end_date = date( 'Y-m-d', strtotime(trim($request->input('end_date'))) );
            $Events->end_time = date( 'H:i:s', strtotime(trim($request->input('end_time'))) );
            $Events->venue_name = trim($request->input('venue_name'));
            $Events->venue_address = trim($request->input('venue_address'));
            $Events->pincode = trim($request->input('pincode'));
            $Events->country_id = trim($request->input('country_id'));
            $Events->province_id = trim($request->input('province_id'));
            $Events->city_id = trim($request->input('city_id'));
            $Events->status = trim($request->input('status'));
            $Events->updated_by = Auth::user()->id;
            $Events->updated_at = date('Y-m-d H:i:s');
            
            if( $request->exists('color') ) {
                $Events->color = trim($request->input('color'));    
            }

            $evtCatArr = $request->input('category_id');

            $eventImageJson = json_decode( trim( $request->input('event_image_infos') ) );
            
            $res = $Events->save();
            
            if( $res ) {
                $event_id = $child_language_id;

                CmsLinks::where('table_type', '=', 'EVENT')->where('table_id', '=', $event_id)
                ->update( [ 'slug_url' => trim($request->input('slug')) ] );

                if( !empty($eventImageJson) ) {
                    $imageMap = array();
                    foreach ($eventImageJson as $v) {
                        if( $v->img_id != '' ) {
                            $arr = array();
                            $arr['event_id'] = $event_id;
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
                        EventImagesMap::insert($imageMap);
                    }
                }

                EventCategoryMap::where('event_id', '=', $event_id)->delete();
                if( !empty($evtCatArr) ) {
                    $insertArr = array();
                    foreach($evtCatArr as $v) {
                        $arr = array();
                        $arr['event_category_id'] = $v;
                        $arr['event_id'] = $event_id;
                        $arr['created_at'] = date('Y-m-d H:i:s');
                        array_push($insertArr, $arr);
                    }
                    EventCategoryMap::insert($insertArr);
                }
                return back()->with('msg', 'Event Updated Saved Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
        }

        if( $child_language_id == '' ) {

            $Events = new Events;
            $Events->name = trim($request->input('name'));
            $Events->description = htmlentities( trim($request->input('description')), ENT_QUOTES );
            $Events->slug = trim($request->input('slug'));
            $Events->page_content = htmlentities( trim($request->input('page_content')), ENT_QUOTES );
            $Events->meta_title = trim($request->input('meta_title'));
            $Events->meta_desc = trim($request->input('meta_desc'));
            $Events->meta_keyword = trim($request->input('meta_keyword'));
            $Events->canonical_url = trim($request->input('canonical_url'));
            $Events->follow = trim($request->input('follow'));
            $Events->lng_tag = trim($request->input('lng_tag'));
            $Events->index_tag = trim($request->input('index_tag'));
            $Events->start_date = date( 'Y-m-d', strtotime(trim($request->input('start_date'))) );
            $Events->start_time = date( 'H:i:s', strtotime(trim($request->input('start_time'))) );
            $Events->end_date = date( 'Y-m-d', strtotime(trim($request->input('end_date'))) );
            $Events->end_time = date( 'H:i:s', strtotime(trim($request->input('end_time'))) );
            $Events->venue_name = trim($request->input('venue_name'));
            $Events->venue_address = trim($request->input('venue_address'));
            $Events->pincode = trim($request->input('pincode'));
            $Events->country_id = trim($request->input('country_id'));
            $Events->province_id = trim($request->input('province_id'));
            $Events->city_id = trim($request->input('city_id'));
            $Events->status = trim($request->input('status'));
            $Events->created_by = Auth::user()->id;
            $Events->language_id = trim($request->input('language_id'));
            $Events->parent_language_id = $parent_language_id;

            if( $request->exists('color') ) {
                $Events->color = trim($request->input('color'));    
            }
            
            $evtCatArr = $request->input('category_id');

            $eventImageJson = json_decode( trim( $request->input('event_image_infos') ) );

            $res = $Events->save();
            if( $res ) {
                $event_id = $Events->id;

                $CmsLinks = new CmsLinks;
                $CmsLinks->table_id = $event_id;
                $CmsLinks->slug_url = trim($request->input('slug'));
                $CmsLinks->table_type = 'EVENT';
                $CmsLinks->save();

                if( !empty($eventImageJson) ) {
                    $imageMap = array();
                    foreach ($eventImageJson as $v) {
                        if( $v->img_id != '' ) {
                            $arr = array();
                            $arr['event_id'] = $event_id;
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
                        EventImagesMap::insert($imageMap);
                    }
                }

                if( !empty($evtCatArr) ) {
                    $insertArr = array();
                    foreach($evtCatArr as $v) {
                        $arr = array();
                        $arr['event_category_id'] = $v;
                        $arr['event_id'] = $event_id;
                        $arr['created_at'] = date('Y-m-d H:i:s');
                        array_push($insertArr, $arr);
                    }
                    EventCategoryMap::insert($insertArr);
                }
                return redirect()->route('evts_edt', array('id' => $parent_language_id))
                ->with('msg', 'Event Created Saved Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
        }   

        return back();
    }

    public function deleteLanguage( $parent_language_id, $child_language_id ) {
        
        Events::find($child_language_id)->delete();
        EventCategoryMap::where('event_id', '=', $child_language_id)->delete();
        EventImagesMap::where('event_id', '=', $child_language_id)->delete();
        CmsLinks::where('table_type', '=', 'EVENT')->where('table_id', '=', $child_language_id)->delete();
        delete_navigation($child_language_id, 'EVENT');
        return redirect()->route('evts_edt', array('id' => $parent_language_id))
        ->with('msg', 'Event Deleted Successfully.')
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
                        $Events = Events::find($id);
                        $Events->status = '1';
                        $Events->save();
                    }
                    $msg = 'Events Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $Events = Events::find($id);
                        $Events->status = '2';
                        $Events->save();
                    }
                    $msg = 'Events Deactivated Succesfully.';
                    break;

                case 'delete':
                    foreach($idsArr as $id) {
                        $Events = Events::find($id);
                        $Events->status = '3';
                        $Events->save();
                        EventCategoryMap::where('event_id', '=', $id)->delete();
                        EventImagesMap::where('event_id', '=', $id)->delete();
                        CmsLinks::where('table_type', '=', 'EVENT')->where('table_id', '=', $id)->delete();
                        delete_navigation($id, 'EVENT');
                        PageBuilder::where('table_id', '=', $id)->where('table_type', '=', 'EVENT')->delete();
                    }
                    $msg = 'Events Deleted Succesfully.';
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
                        $EventCategories = EventCategories::find($id);
                        $EventCategories->status = '1';
                        $EventCategories->save();
                    }
                    $msg = 'Event Categories Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $EventCategories = EventCategories::find($id);
                        $EventCategories->status = '2';
                        $EventCategories->save();
                    }
                    $msg = 'Event Categories Deactivated Succesfully.';
                    break;

                case 'delete':
                    foreach($idsArr as $id) {
                        $EventCategories = EventCategories::find($id);
                        $EventCategories->status = '3';
                        $EventCategories->save();
                        EventCategoryImagesMap::where('event_category_id', '=', $id)->delete();
                        CmsLinks::where('table_type', '=', 'EVENT_CATEGORY')->where('table_id', '=', $id)->delete();
                        delete_navigation($id, 'EVENT_CATEGORY');
                    }
                    $msg = 'Event Categories Deleted Succesfully.';
                    break;
            }
            return back()->with('msg', $msg)->with('msg_class', 'alert alert-success');
        }
        return back();
    }



    public function extraConten() {

        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'eventManagement';
        $DataBag['childMenu'] = 'evtExCont';
        $DataBag['extraCont'] = MediaExtraContent::where('type', '=', 'EVENT')->first();
        return view('dashboard.events.extra_content', $DataBag);
    }

    public function extraContentSave(Request $request) {
        
        $MediaExtraContent = MediaExtraContent::where('type', '=', 'EVENT')->first();

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
            $MediaExtraContent->type = 'EVENT';

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
