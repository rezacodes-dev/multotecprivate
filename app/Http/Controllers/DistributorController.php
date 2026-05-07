<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CmsLinks;
use App\Models\Media\FilesMaster;
use App\Models\Media\Images;
use App\Models\Product\ProductCategories;
use App\Models\Distributor\Distributor;
use App\Models\Distributor\DistributorCategories;
use App\Models\Distributor\DistributorCategoriesMap;
use App\Models\Distributor\DistributorProductCategoriesMap;
use App\Models\Distributor\DistributorIndustriesMap;
use App\Models\Distributor\DistributorImagesMap;
use App\Models\Distributor\DistributorFilesMap;
use App\Models\Industry\Industries;
use App\Models\Distributor\DistributorContents;
use App\Models\Distributor\DistributorContentFilesMap;
use App\Models\Distributor\DistributorContentImagesMap;
use App\Models\Languages;
use App\Models\PageBuilder\PageBuilder;
use App\Models\LocationNetwork;
use App\Models\Media\MediaExtraContent;
use Image;
use Auth;
use DB;

class DistributorController extends Controller
{
    
    public function allCats() {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'distributorManagement';
    	$DataBag['childMenu'] = 'distributorCats';
    	$DataBag['allCats'] = DistributorCategories::where('status', '!=', '3')->where('parent_language_id', '=', '0')
        ->orderBy('name', 'asc')->get();
    	return view('dashboard.distributors.categories', $DataBag);
    }

    public function createCats() {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'distributorManagement';
    	$DataBag['childMenu'] = 'distributorAddCats';
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));
    	return view('dashboard.distributors.create_category', $DataBag);
    }

    public function saveCats(Request $request) {

        $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

    	$DistributorCategories = new DistributorCategories;
    	$DistributorCategories->name = trim( ucfirst($request->input('name')) );
        $DistributorCategories->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
    	$DistributorCategories->slug = trim( $request->input('slug') );
    	$DistributorCategories->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
    	$DistributorCategories->created_by = Auth::user()->id;
        $DistributorCategories->language_id = trim( $request->input('language_id') );

        $DistributorCategories->insert_id = $insert_id;

        $DistributorCategories->meta_title = trim($request->input('meta_title'));
        $DistributorCategories->meta_desc = trim($request->input('meta_desc'));
        $DistributorCategories->meta_keyword = trim($request->input('meta_keyword'));
        $DistributorCategories->canonical_url = trim($request->input('canonical_url'));
        $DistributorCategories->lng_tag = trim($request->input('lng_tag'));
        $DistributorCategories->follow = trim($request->input('follow'));
        $DistributorCategories->index_tag = trim($request->input('index_tag'));
        $DistributorCategories->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );

        $DistributorCategories->image_title = trim($request->input('image_title'));
        $DistributorCategories->image_alt = trim($request->input('image_alt'));
        $DistributorCategories->image_caption = trim($request->input('image_caption'));

        if( $request->hasFile('page_banner') ) {
            
            $img = $request->file('page_banner');
            $real_path = $img->getRealPath();
            $file_orgname = $img->getClientOriginalName();
            $file_size = $img->getSize();
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

                $DistributorCategories->image_id = $Images->id;  
            }
        }

    	if( $DistributorCategories->save() ) {

            $disCatId = $DistributorCategories->id;
    		
            $CmsLinks = new CmsLinks;
    		$CmsLinks->table_id = $DistributorCategories->id;
    		$CmsLinks->table_type = 'DISTRIBUTOR_CATEGORY';
    		$CmsLinks->slug_url = trim( $request->input('slug') );
    		$CmsLinks->save();
            //$cms_link_id = $CmsLinks->id; // Need for page builder as parameter

            /** For Page Builder -- Insert Time **/
            /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
            //update_page_builder($insert_id, $cms_link_id, $disCatId, 'DISTRIBUTOR_CATEGORY');
            /** End Page Builder **/


    		return back()->with('msg', 'Distributor Category Created Successfully')
    		->with('msg_class', 'alert alert-success');
    	}

    	return back()->with('msg', 'Something Went Wrong')
    	->with('msg_class', 'alert alert-danger');
    }

    public function deleteCats( $category_id ) {
    	$ck = DistributorCategories::find($category_id);
    	if( isset($ck) && !empty($ck) ) {
    		$ck->status = '3';
    		$res = $ck->save();
    		if( isset($res) && $res == 1 ) {

                delete_navigation($category_id, 'DISTRIBUTOR_CATEGORY');
    			CmsLinks::where('table_type', '=', 'DISTRIBUTOR_CATEGORY')->where('table_id', '=', $category_id)->delete();
                PageBuilder::where('table_id', '=', $category_id)->where('table_type', '=', 'DISTRIBUTOR_CATEGORY')->delete();


    			return back()->with('msg', 'Distributor Category Deleted Successfully.')
    			->with('msg_class', 'alert alert-success');
    		}
    	}

    	return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger'); 
    }

    public function editCats( $category_id ) {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'distributorManagement';
    	$DataBag['childMenu'] = 'distributorCats';
    	$DataBag['category'] = DistributorCategories::findOrFail($category_id);
        $DataBag['pageBuilderData'] = $DataBag['category']; /* For pagebuilder */
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
    	return view('dashboard.distributors.create_category', $DataBag);
    }

    public function updateCats(Request $request, $category_id) {

        $insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

    	$DistributorCategories = DistributorCategories::find($category_id);
    	$DistributorCategories->name = trim( ucfirst($request->input('name')) );
        $DistributorCategories->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
    	$DistributorCategories->slug = trim( $request->input('slug') );
    	$DistributorCategories->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
    	$DistributorCategories->updated_by = Auth::user()->id;

        $DistributorCategories->meta_title = trim($request->input('meta_title'));
        $DistributorCategories->meta_desc = trim($request->input('meta_desc'));
        $DistributorCategories->meta_keyword = trim($request->input('meta_keyword'));
        $DistributorCategories->canonical_url = trim($request->input('canonical_url'));
        $DistributorCategories->lng_tag = trim($request->input('lng_tag'));
        $DistributorCategories->follow = trim($request->input('follow'));
        $DistributorCategories->index_tag = trim($request->input('index_tag'));
        $DistributorCategories->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );

        $DistributorCategories->image_title = trim($request->input('image_title'));
        $DistributorCategories->image_alt = trim($request->input('image_alt'));
        $DistributorCategories->image_caption = trim($request->input('image_caption'));

        if( $request->hasFile('page_banner') ) {
            
            $img = $request->file('page_banner');
            $real_path = $img->getRealPath();
            $file_orgname = $img->getClientOriginalName();
            $file_size = $img->getSize();
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

                $DistributorCategories->image_id = $Images->id;  
            }
        }


    	if( $DistributorCategories->save() ) {
    		CmsLinks::where('table_type', '=', 'DISTRIBUTOR_CATEGORY')->where('table_id', '=', $category_id)
    		->update(['slug_url' => trim( $request->input('slug') )]);

            /** Need For Page Builder -- Update Time **/
            //$cmsInfo = CmsLinks::where('table_id', '=', $category_id)->where('table_type', '=', 'DISTRIBUTOR_CATEGORY')->first();
           //if( !empty($cmsInfo) ) {
                
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                //update_page_builder($insert_id, $cmsInfo->id, $category_id, 'DISTRIBUTOR_CATEGORY');

            //}
            /** End Page Builder **/

    		return back()->with('msg', 'Distributor Category Updated Successfully')
    		->with('msg_class', 'alert alert-success');
    	}

    	return back()->with('msg', 'Something Went Wrong')
    	->with('msg_class', 'alert alert-danger');
    }






/*** DISTRIBUTOR ***/
    public function allDistributors() {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'distributorManagement';
    	$DataBag['childMenu'] = 'distributorList';
    	$DataBag['allDistrs'] = Distributor::where('status', '!=', '3')->where('parent_language_id', '=', '0')
        ->orderBy('created_at', 'desc')->get();
    	return view('dashboard.distributors.index', $DataBag);
    }

    public function createDistributors() {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'distributorManagement';
    	$DataBag['childMenu'] = 'distributorAdd';
        $DataBag['allIndustries'] = Industries::where('status', '=', '1')->orderBy('name', 'asc')->get();
    	$DataBag['allDistrCats'] = DistributorCategories::where('status', '=', '1')->orderBy('name', 'asc')->get();
    	$DataBag['allProdCats'] = ProductCategories::where('status', '=', '1')->orderBy('name', 'asc')->get();
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));
    	return view('dashboard.distributors.create', $DataBag);
    }

    public function saveDistributors(Request $request) {


        $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time


    	$prodCatsMap = array();
        $indusMap = array();
    	$distrCatsMap = array();

    	$Distributor = new Distributor;
    	$Distributor->name = trim( ucfirst($request->input('name')) );
    	$Distributor->slug = trim($request->input('slug'));
    	$Distributor->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
    	$Distributor->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
    	$Distributor->created_by = Auth::user()->id;
        $Distributor->language_id = trim( $request->input('language_id') );
    	
        $Distributor->insert_id = $insert_id;

        $Distributor->meta_title = trim($request->input('meta_title'));
        $Distributor->meta_desc = trim($request->input('meta_desc'));
        $Distributor->meta_keyword = trim($request->input('meta_keyword'));
        $Distributor->canonical_url = trim($request->input('canonical_url'));
        $Distributor->lng_tag = trim($request->input('lng_tag'));
        $Distributor->follow = trim($request->input('follow'));
        $Distributor->index_tag = trim($request->input('index_tag'));
        $Distributor->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );

    	if( $Distributor->save() ) {

    		$distributor_id = $Distributor->id;
    		
            $CmsLinks = new CmsLinks;
    		$CmsLinks->table_id = $distributor_id;
    		$CmsLinks->table_type = 'DISTRIBUTOR';
    		$CmsLinks->slug_url = trim( $request->input('slug') );
    		$CmsLinks->save();
            $cms_link_id = $CmsLinks->id; // Need for page builder as parameter

            /** For Page Builder -- Insert Time **/
            /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
            update_page_builder($insert_id, $cms_link_id, $distributor_id, 'DISTRIBUTOR');
            /** End Page Builder **/
    		
            if( $request->has('distributor_category_id') ) {
	    		foreach( $request->input('distributor_category_id') as $cats ) {
                    if( $cats != '' ) {
    	    			$arr = array();
    	    			$arr['distributor_id'] = $distributor_id;
    	    			$arr['distributor_category_id'] = $cats;
    	    			array_push( $distrCatsMap, $arr );
                    }
	    		}
	    		if( !empty($distrCatsMap) ) {
	    			DistributorCategoriesMap::insert( $distrCatsMap );
	    		}
	    	}
	    	if( $request->has('product_category_id') ) {
	    		foreach( $request->input('product_category_id') as $cats ) {
	    			$arr = array();
	    			$arr['distributor_id'] = $distributor_id;
	    			$arr['product_category_id'] = $cats;
	    			array_push( $prodCatsMap, $arr );
	    		}
	    		if( !empty($prodCatsMap) ) {
	    			DistributorProductCategoriesMap::insert( $prodCatsMap );
	    		}
	    	}
            if( $request->has('industry_id') ) {
                foreach( $request->input('industry_id') as $cats ) {
                    $arr = array();
                    $arr['distributor_id'] = $distributor_id;
                    $arr['industry_id'] = $cats;
                    array_push( $indusMap, $arr );
                }
                if( !empty($indusMap) ) {
                    DistributorIndustriesMap::insert( $indusMap );
                }
            }
	    	return back()->with('msg', 'Distributor Created Successfully.')
    		->with('msg_class', 'alert alert-success');
    	}

    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }


    public function deleteDistributors( $distributor_id ) {
    	$ck = Distributor::find($distributor_id);
    	if( isset($ck) && !empty($ck) ) {
    		$ck->status = '3';
    		$res = $ck->save();
    		if( isset($res) && $res == 1 ) {
    			
                CmsLinks::where('table_type', '=', 'DISTRIBUTOR')->where('table_id', '=', $distributor_id)->delete();
    			DistributorProductCategoriesMap::where('distributor_id', '=', $distributor_id)->delete();
    			DistributorCategoriesMap::where('distributor_id', '=', $distributor_id)->delete();
                DistributorIndustriesMap::where('distributor_id', '=', $distributor_id)->delete();
                //DistributorImagesMap::where('distributor_id', '=', $distributor_id)->delete();
                //DistributorFilesMap::where('distributor_id', '=', $distributor_id)->delete();
                
                delete_navigation($distributor_id, 'DISTRIBUTOR');
                PageBuilder::where('table_id', '=', $distributor_id)->where('table_type', '=', 'DISTRIBUTOR')->delete();

    			return back()->with('msg', 'Distributor Deleted Successfully.')
    			->with('msg_class', 'alert alert-success');
    		}
    	}

    	return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger'); 
    }

    public function editDistributors( $distributor_id ) {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'distributorManagement';
    	$DataBag['childMenu'] = 'distributorAdd';
    	$DataBag['distributor'] = Distributor::findOrFail($distributor_id);
        $DataBag['pageBuilderData'] = $DataBag['distributor']; /* For pagebuilder */
        $DataBag['allIndustries'] = Industries::where('status', '=', '1')->orderBy('name', 'asc')->get();
    	$DataBag['allDistrCats'] = DistributorCategories::where('status', '=', '1')->orderBy('name', 'asc')->get();
    	$DataBag['allProdCats'] = ProductCategories::where('status', '=', '1')->orderBy('name', 'asc')->get();
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
    	return view('dashboard.distributors.create', $DataBag);
    }

    public function updateDistributors(Request $request, $distributor_id) {

        $insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

    	$prodCatsMap = array();
        $indusMap = array();
    	$distrCatsMap = array();

    	$Distributor = Distributor::find($distributor_id);
    	$Distributor->name = trim( ucfirst($request->input('name')) );
    	$Distributor->slug = trim($request->input('slug'));
    	$Distributor->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
    	$Distributor->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
    	$Distributor->updated_by = Auth::user()->id;

        $Distributor->meta_title = trim($request->input('meta_title'));
        $Distributor->meta_desc = trim($request->input('meta_desc'));
        $Distributor->meta_keyword = trim($request->input('meta_keyword'));
        $Distributor->canonical_url = trim($request->input('canonical_url'));
        $Distributor->lng_tag = trim($request->input('lng_tag'));
        $Distributor->follow = trim($request->input('follow'));
        $Distributor->index_tag = trim($request->input('index_tag'));
        $Distributor->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );

    	
    	if( $Distributor->save() ) {

            DistributorCategoriesMap::where('distributor_id', '=', $distributor_id)->delete();
    		DistributorProductCategoriesMap::where('distributor_id', '=', $distributor_id)->delete();
            DistributorIndustriesMap::where('distributor_id', '=', $distributor_id)->delete();
    		
    		CmsLinks::where('table_type', '=', 'DISTRIBUTOR')->where('table_id', '=', $distributor_id)
    		->update(['slug_url' => trim( $request->input('slug') )]);

            /** Need For Page Builder -- Update Time **/
            $cmsInfo = CmsLinks::where('table_id', '=', $distributor_id)->where('table_type', '=', 'DISTRIBUTOR')->first();
            if( !empty($cmsInfo) ) {
                
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                update_page_builder($insert_id, $cmsInfo->id, $distributor_id, 'DISTRIBUTOR');

            }
            /** End Page Builder **/
    		
    		if( $request->has('distributor_category_id') ) {
	    		foreach( $request->input('distributor_category_id') as $cats ) {
                    if( $cats != '' ) {
    	    			$arr = array();
    	    			$arr['distributor_id'] = $distributor_id;
    	    			$arr['distributor_category_id'] = $cats;
    	    			array_push( $distrCatsMap, $arr );
                    }
	    		}
	    		if( !empty($distrCatsMap) ) {
	    			DistributorCategoriesMap::insert( $distrCatsMap );
	    		}
	    	}
	    	if( $request->has('product_category_id') ) {
	    		foreach( $request->input('product_category_id') as $cats ) {
	    			$arr = array();
	    			$arr['distributor_id'] = $distributor_id;
	    			$arr['product_category_id'] = $cats;
	    			array_push( $prodCatsMap, $arr );
	    		}
	    		if( !empty($prodCatsMap) ) {
	    			DistributorProductCategoriesMap::insert( $prodCatsMap );
	    		}
	    	}
            if( $request->has('industry_id') ) {
                foreach( $request->input('industry_id') as $cats ) {
                    $arr = array();
                    $arr['distributor_id'] = $distributor_id;
                    $arr['industry_id'] = $cats;
                    array_push( $indusMap, $arr );
                }
                if( !empty($indusMap) ) {
                    DistributorIndustriesMap::insert( $indusMap );
                }
            }
	    	return back()->with('msg', 'Distributor Updated Successfully.')
    		->with('msg_class', 'alert alert-success');
    	}

    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }







    public function allContents() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'distributorManagement';
        $DataBag['childMenu'] = 'distributorContns';
        $DataBag['allDisConts'] = DistributorContents::where('status', '!=', '3')->where('parent_language_id', '=', '0')
        ->orderBy('created_at', 'desc')->get();
        return view('dashboard.distributors.all_contents', $DataBag);
    }

    public function createContent() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'distributorManagement';
        $DataBag['childMenu'] = 'distributorAddContns';
        $DataBag['allDistrbs'] = Distributor::where('status', '=', '1')->orderBy('name', 'asc')->get();
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));
        return view('dashboard.distributors.create_content', $DataBag); 
    }

    public function editContent($content_id) {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'distributorManagement';
        $DataBag['childMenu'] = 'distributorAddContns';
        $DataBag['content'] = DistributorContents::findOrFail($content_id);
        $DataBag['pageBuilderData'] = $DataBag['content']; /* For pagebuilder */
        $DataBag['allDistrbs'] = Distributor::where('status', '=', '1')->orderBy('name', 'asc')->get();
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
        return view('dashboard.distributors.create_content', $DataBag);
    }

    public function saveContent(Request $request) {

        $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

        $distributor_id = 0;

        $DistributorContents = new DistributorContents;
        $DistributorContents->name = trim( ucfirst($request->input('name')) );
        $DistributorContents->slug = trim($request->input('slug'));
        $DistributorContents->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
        $DistributorContents->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
        $DistributorContents->created_by = Auth::user()->id;
        $DistributorContents->language_id = trim( $request->input('language_id') );

        $DistributorContents->insert_id = $insert_id;

        $DistributorContents->meta_title = trim($request->input('meta_title'));
        $DistributorContents->meta_desc = trim($request->input('meta_desc'));
        $DistributorContents->meta_keyword = trim($request->input('meta_keyword'));
        $DistributorContents->canonical_url = trim($request->input('canonical_url'));
        $DistributorContents->lng_tag = trim($request->input('lng_tag'));
        $DistributorContents->follow = trim($request->input('follow'));
        $DistributorContents->index_tag = trim($request->input('index_tag'));
        $DistributorContents->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );

        $DistributorContents->branch_type = trim($request->input('branch_type'));
        $DistributorContents->map_heading = trim($request->input('map_heading'));
        $DistributorContents->latitude = trim($request->input('latitude'));
        $DistributorContents->longitude = trim($request->input('longitude'));
        $DistributorContents->address = trim($request->input('address'));
        
        if( $request->has('distributor_id') && $request->input('distributor_id') != '' ) {
            $distributor_id = trim($request->input('distributor_id'));
        }
        $DistributorContents->distributor_id = $distributor_id;

        if( $DistributorContents->save() ) {

            $distributor_content_id = $DistributorContents->id;

            $CmsLinks = new CmsLinks;
            $CmsLinks->table_id = $distributor_content_id;
            $CmsLinks->slug_url = trim($request->input('slug'));
            $CmsLinks->table_type = 'DISTRIBUTOR_CONTENT';
            $CmsLinks->save();
            $cms_link_id = $CmsLinks->id; // Need for page builder as parameter

            /** For Page Builder -- Insert Time **/
            /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
            update_page_builder($insert_id, $cms_link_id, $distributor_content_id, 'DISTRIBUTOR_CONTENT');
            /** End Page Builder **/
            
        return back()->with('msg', 'Distributor Content Created Successfully.')
        ->with('msg_class', 'alert alert-success');
        }
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }



    public function updateContent(Request $request, $content_id) {

        $insert_id = trim( $request->input('insert_id') );

        $distributor_id = 0;

        $DistributorContents = DistributorContents::find($content_id);
        $DistributorContents->name = trim( ucfirst($request->input('name')) );
        $DistributorContents->slug = trim($request->input('slug'));
        $DistributorContents->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
        $DistributorContents->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
        $DistributorContents->created_by = Auth::user()->id;

        $DistributorContents->meta_title = trim($request->input('meta_title'));
        $DistributorContents->meta_desc = trim($request->input('meta_desc'));
        $DistributorContents->meta_keyword = trim($request->input('meta_keyword'));
        $DistributorContents->canonical_url = trim($request->input('canonical_url'));
        $DistributorContents->lng_tag = trim($request->input('lng_tag'));
        $DistributorContents->follow = trim($request->input('follow'));
        $DistributorContents->index_tag = trim($request->input('index_tag'));
        $DistributorContents->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );

        $DistributorContents->branch_type = trim($request->input('branch_type'));
        $DistributorContents->map_heading = trim($request->input('map_heading'));
        $DistributorContents->latitude = trim($request->input('latitude'));
        $DistributorContents->longitude = trim($request->input('longitude'));
        $DistributorContents->address = trim($request->input('address'));
       
        if( $request->has('distributor_id') && $request->input('distributor_id') != '' ) {
            $distributor_id = trim($request->input('distributor_id'));
        }
        $DistributorContents->distributor_id = $distributor_id;

        if( $DistributorContents->save() ) {

            $distributor_content_id = $content_id;

            CmsLinks::where('table_type', '=', 'DISTRIBUTOR_CONTENT')->where('table_id', '=', $content_id)
            ->where( ['slug_url' => trim($request->input('slug'))] );

            /** Need For Page Builder -- Update Time **/
            $cmsInfo = CmsLinks::where('table_id', '=', $content_id)->where('table_type', '=', 'DISTRIBUTOR_CONTENT')->first();
            if( !empty($cmsInfo) ) {
                
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                update_page_builder($insert_id, $cmsInfo->id, $content_id, 'DISTRIBUTOR_CONTENT');

            }
            /** End Page Builder **/

        return back()->with('msg', 'Distributor Content Updated Successfully.')
        ->with('msg_class', 'alert alert-success');
        }
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }



    public function deleteContent($content_id) {

        $ck = DistributorContents::find($content_id);
        if( isset($ck) && !empty($ck) ) {
            $ck->status = '3';
            $res = $ck->save();
            if( isset($res) && $res == 1 ) {

                CmsLinks::where('table_type', '=', 'DISTRIBUTOR_CONTENT')->where('table_id', '=', $content_id)->delete();
                //DistributorContentImagesMap::where('distributor_content_id', '=', $content_id)->delete();
                //DistributorContentFilesMap::where('distributor_content_id', '=', $content_id)->delete();
                delete_navigation($content_id, 'DISTRIBUTOR_CONTENT');
                PageBuilder::where('table_id', '=', $content_id)->where('table_type', '=', 'DISTRIBUTOR_CONTENT')->delete();
                
                return back()->with('msg', 'Distributor Content Deleted Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
        }

        return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger'); 
    }










    /********************** Language **************************/

    public function addEditCatLanguage( $parent_language_id, $child_language_id = '') {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'distributorManagement';
        $DataBag['childMenu'] = 'distributorCats';
        $DataBag['parentLngCont'] = DistributorCategories::findOrFail($parent_language_id);
        if( $child_language_id != '' ) {
            $DataBag['category'] = DistributorCategories::findOrFail($child_language_id);
            $DataBag['pageBuilderData'] = $DataBag['category'];
        }
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));
        return view('dashboard.distributors.addedit_language_category', $DataBag);
    }

    public function addEditCatLanguagePost( Request $request, $parent_language_id, $child_language_id = '') {
       
       if( $child_language_id != '' && $child_language_id != null ) {

            $insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

            $DistributorCategories = DistributorCategories::find($child_language_id);
            $DistributorCategories->name = trim( ucfirst($request->input('name')) );
            $DistributorCategories->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
            $DistributorCategories->slug = trim( $request->input('slug') );
            $DistributorCategories->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $DistributorCategories->updated_by = Auth::user()->id;

            $DistributorCategories->meta_title = trim($request->input('meta_title'));
            $DistributorCategories->meta_desc = trim($request->input('meta_desc'));
            $DistributorCategories->meta_keyword = trim($request->input('meta_keyword'));
            $DistributorCategories->canonical_url = trim($request->input('canonical_url'));
            $DistributorCategories->lng_tag = trim($request->input('lng_tag'));
            $DistributorCategories->follow = trim($request->input('follow'));
            $DistributorCategories->index_tag = trim($request->input('index_tag'));

            $DistributorCategories->image_title = trim($request->input('image_title'));
            $DistributorCategories->image_alt = trim($request->input('image_alt'));
            $DistributorCategories->image_caption = trim($request->input('image_caption'));

            if( $request->hasFile('page_banner') ) {
                
                $img = $request->file('page_banner');
                $real_path = $img->getRealPath();
                $file_orgname = $img->getClientOriginalName();
                $file_size = $img->getSize();
                $file_ext = strtolower($img->getClientOriginalExtension());
                $file_newname = "banner"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
                $destinationPath = public_path('/uploads/files/media_images');
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

                    $DistributorCategories->image_id = $Images->id;  
                }
            }


            if( $DistributorCategories->save() ) {

                CmsLinks::where('table_type', '=', 'DISTRIBUTOR_CATEGORY')->where('table_id', '=', $child_language_id)
                ->update(['slug_url' => trim( $request->input('slug') )]);

                /** Need For Page Builder -- Update Time **/
                $cmsInfo = CmsLinks::where('table_id', '=', $child_language_id)->where('table_type', '=', 'DISTRIBUTOR_CATEGORY')->first();
                if( !empty($cmsInfo) ) {
                    
                    /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                    update_page_builder($insert_id, $cmsInfo->id, $child_language_id, 'DISTRIBUTOR_CATEGORY');

                }
                /** End Page Builder **/

                return back()->with('msg', 'Distributor Category Updated Successfully')
                ->with('msg_class', 'alert alert-success');
            }
       }

       if( $child_language_id == '' ) {

            $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

            $DistributorCategories = new DistributorCategories;
            $DistributorCategories->name = trim( ucfirst($request->input('name')) );
            $DistributorCategories->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
            $DistributorCategories->slug = trim( $request->input('slug') );
            $DistributorCategories->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $DistributorCategories->created_by = Auth::user()->id;
            $DistributorCategories->language_id = trim( $request->input('language_id') );
            $DistributorCategories->parent_language_id = $parent_language_id;

            $DistributorCategories->insert_id = $insert_id;

            $DistributorCategories->meta_title = trim($request->input('meta_title'));
            $DistributorCategories->meta_desc = trim($request->input('meta_desc'));
            $DistributorCategories->meta_keyword = trim($request->input('meta_keyword'));
            $DistributorCategories->canonical_url = trim($request->input('canonical_url'));
            $DistributorCategories->lng_tag = trim($request->input('lng_tag'));
            $DistributorCategories->follow = trim($request->input('follow'));
            $DistributorCategories->index_tag = trim($request->input('index_tag'));

            $DistributorCategories->image_title = trim($request->input('image_title'));
            $DistributorCategories->image_alt = trim($request->input('image_alt'));
            $DistributorCategories->image_caption = trim($request->input('image_caption'));

            if( $request->hasFile('page_banner') ) {
                
                $img = $request->file('page_banner');
                $real_path = $img->getRealPath();
                $file_orgname = $img->getClientOriginalName();
                $file_size = $img->getSize();
                $file_ext = strtolower($img->getClientOriginalExtension());
                $file_newname = "banner"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
                $destinationPath = public_path('/uploads/files/media_images');
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

                    $DistributorCategories->image_id = $Images->id;  
                }
            }


            if( $DistributorCategories->save() ) {

                $disCatId = $DistributorCategories->id;

                $CmsLinks = new CmsLinks;
                $CmsLinks->table_id = $DistributorCategories->id;
                $CmsLinks->table_type = 'DISTRIBUTOR_CATEGORY';
                $CmsLinks->slug_url = trim( $request->input('slug') );
                $CmsLinks->save();
                $cms_link_id = $CmsLinks->id; // Need for page builder as parameter

                /** For Page Builder -- Insert Time **/
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                update_page_builder($insert_id, $cms_link_id, $disCatId, 'DISTRIBUTOR_CATEGORY');
                /** End Page Builder **/


                return redirect()->route('editDistribCats', array('id' => $parent_language_id))
                ->with('msg', 'Distributor Category Created Successfully')
                ->with('msg_class', 'alert alert-success');
            }
       }

       return back(); 
    }

    public function deleteCatLanguage( $parent_language_id, $child_language_id ) {
        
        DistributorCategories::find($child_language_id)->delete();
        CmsLinks::where('table_type', '=', 'DISTRIBUTOR_CATEGORY')->where('table_id', '=', $child_language_id)->delete();
        
        delete_navigation($child_language_id, 'DISTRIBUTOR_CATEGORY');
        PageBuilder::where('table_id', '=', $child_language_id)->where('table_type', '=', 'DISTRIBUTOR_CATEGORY')->delete();

        return redirect()->route('editDistribCats', array('id' => $parent_language_id))
        ->with('msg', 'Distributor Category Deleted Successfully')
        ->with('msg_class', 'alert alert-success');
    }



    public function addEditContLanguage( $parent_language_id, $child_language_id = '' ) {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'distributorManagement';
        $DataBag['childMenu'] = 'distributorAddContns';
        $DataBag['parentLngCont'] = DistributorContents::findOrFail($parent_language_id);
        if( $child_language_id != '' ) {
            $DataBag['content'] = DistributorContents::findOrFail($child_language_id);
            $DataBag['pageBuilderData'] = $DataBag['content'];
        }
        $DataBag['allDistrbs'] = Distributor::where('status', '=', '1')->orderBy('name', 'asc')->get();
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));

        return view('dashboard.distributors.addedit_language_content', $DataBag);
    }

    public function addEditContLanguagePost( Request $request, $parent_language_id, $child_language_id = '' ) {
        
        if( $child_language_id != '' && $child_language_id != null ) {
            
            $insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

            $distributor_id = 0;

            $DistributorContents = DistributorContents::find($child_language_id);
            $DistributorContents->name = trim( ucfirst($request->input('name')) );
            $DistributorContents->slug = trim($request->input('slug'));
            $DistributorContents->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $DistributorContents->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
            $DistributorContents->created_by = Auth::user()->id;

            $DistributorContents->meta_title = trim($request->input('meta_title'));
            $DistributorContents->meta_desc = trim($request->input('meta_desc'));
            $DistributorContents->meta_keyword = trim($request->input('meta_keyword'));
            $DistributorContents->canonical_url = trim($request->input('canonical_url'));
            $DistributorContents->lng_tag = trim($request->input('lng_tag'));
            $DistributorContents->follow = trim($request->input('follow'));
            $DistributorContents->index_tag = trim($request->input('index_tag'));
            
            if( $request->has('distributor_id') && $request->input('distributor_id') != '' ) {
                $distributor_id = trim($request->input('distributor_id'));
            }
            
            $DistributorContents->distributor_id = $distributor_id;

            if( $DistributorContents->save() ) {

                $distributor_content_id = $child_language_id;

                CmsLinks::where('table_type', '=', 'DISTRIBUTOR_CONTENT')->where('table_id', '=', $child_language_id)
                ->where( ['slug_url' => trim($request->input('slug'))] );

                /** Need For Page Builder -- Update Time **/
                $cmsInfo = CmsLinks::where('table_id', '=', $distributor_content_id)->where('table_type', '=', 'DISTRIBUTOR_CONTENT')->first();
                if( !empty($cmsInfo) ) {
                    
                    /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                    update_page_builder($insert_id, $cmsInfo->id, $distributor_content_id, 'DISTRIBUTOR_CONTENT');

                }
                /** End Page Builder **/
                
                return back()->with('msg', 'Distributor Content Updated Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
        }

        if( $child_language_id == '' ) {

            $insert_id = trim( $request->input('insert_id') );
            $distributor_id = 0;

            $DistributorContents = new DistributorContents;
            $DistributorContents->name = trim( ucfirst($request->input('name')) );
            $DistributorContents->slug = trim($request->input('slug'));
            $DistributorContents->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $DistributorContents->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
            
            $DistributorContents->created_by = Auth::user()->id;
            $DistributorContents->language_id = trim( $request->input('language_id') );
            $DistributorContents->parent_language_id = $parent_language_id;
            $DistributorContents->insert_id = $insert_id;

            $DistributorContents->meta_title = trim($request->input('meta_title'));
            $DistributorContents->meta_desc = trim($request->input('meta_desc'));
            $DistributorContents->meta_keyword = trim($request->input('meta_keyword'));
            $DistributorContents->canonical_url = trim($request->input('canonical_url'));
            $DistributorContents->lng_tag = trim($request->input('lng_tag'));
            $DistributorContents->follow = trim($request->input('follow'));
            $DistributorContents->index_tag = trim($request->input('index_tag'));
            
            if( $request->has('distributor_id') && $request->input('distributor_id') != '' ) {
                $distributor_id = trim($request->input('distributor_id'));
            }
            $DistributorContents->distributor_id = $distributor_id;

            if( $DistributorContents->save() ) {

                $distributor_content_id = $DistributorContents->id;

                $CmsLinks = new CmsLinks;
                $CmsLinks->table_id = $distributor_content_id;
                $CmsLinks->slug_url = trim($request->input('slug'));
                $CmsLinks->table_type = 'DISTRIBUTOR_CONTENT';
                $CmsLinks->save();

                $cms_link_id = $CmsLinks->id; // Need for page builder as parameter

                /** For Page Builder -- Insert Time **/
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                update_page_builder($insert_id, $cms_link_id, $distributor_content_id, 'DISTRIBUTOR_CONTENT');
                /** End Page Builder **/
                 
                return redirect()->route('edtDistribCont', array('id' => $parent_language_id))
                ->with('msg', 'Distributor Content Created Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
        }

        return back();
    }


    public function deleteContLanguage( $parent_language_id, $child_language_id ) {

        DistributorContents::find($child_language_id)->delete();
        CmsLinks::where('table_type', '=', 'DISTRIBUTOR_CONTENT')->where('table_id', '=', $child_language_id)->delete();
        //DistributorContentImagesMap::where('distributor_content_id', '=', $child_language_id)->delete();
        //DistributorContentFilesMap::where('distributor_content_id', '=', $child_language_id)->delete();

        delete_navigation($child_language_id, 'DISTRIBUTOR_CONTENT');
        PageBuilder::where('table_id', '=', $child_language_id)->where('table_type', '=', 'DISTRIBUTOR_CONTENT')->delete();

        return redirect()->route('edtDistribCont', array('id' => $parent_language_id))
        ->with('msg', 'Distributor Content Deleted Successfully.')
        ->with('msg_class', 'alert alert-success');
    }



    public function addEditLanguage( $parent_language_id, $child_language_id = '' ) {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'distributorManagement';
        $DataBag['childMenu'] = 'distributorAdd';
        $DataBag['parentLngCont'] = Distributor::findOrFail($parent_language_id);
        if( $child_language_id != '' ) {
            $DataBag['distributor'] = Distributor::findOrFail($child_language_id);
            $DataBag['pageBuilderData'] = $DataBag['distributor'];
        }
        $DataBag['allIndustries'] = Industries::where('status', '=', '1')->orderBy('name', 'asc')->get();
        $DataBag['allDistrCats'] = DistributorCategories::where('status', '=', '1')->orderBy('name', 'asc')->get();
        $DataBag['allProdCats'] = ProductCategories::where('status', '=', '1')->orderBy('name', 'asc')->get();
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));

        return view('dashboard.distributors.addedit_language', $DataBag);
    }

    public function addEditLanguagePost( Request $request, $parent_language_id, $child_language_id = '' ) {
       
       if( $child_language_id != '' && $child_language_id != null ) {

            $insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

            $prodCatsMap = array();
            $indusMap = array();
            $distrCatsMap = array();

            $Distributor = Distributor::find($child_language_id);
            $Distributor->name = trim( ucfirst($request->input('name')) );
            $Distributor->slug = trim($request->input('slug'));
            $Distributor->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
            $Distributor->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $Distributor->updated_by = Auth::user()->id;

            $Distributor->meta_title = trim($request->input('meta_title'));
            $Distributor->meta_desc = trim($request->input('meta_desc'));
            $Distributor->meta_keyword = trim($request->input('meta_keyword'));
            $Distributor->canonical_url = trim($request->input('canonical_url'));
            $Distributor->lng_tag = trim($request->input('lng_tag'));
            $Distributor->follow = trim($request->input('follow'));
            $Distributor->index_tag = trim($request->input('index_tag'));
            
            if( $Distributor->save() ) {
                
                $distributor_id = $child_language_id;

                DistributorCategoriesMap::where('distributor_id', '=', $distributor_id)->delete();
                DistributorProductCategoriesMap::where('distributor_id', '=', $distributor_id)->delete();
                DistributorIndustriesMap::where('distributor_id', '=', $distributor_id)->delete();
                
                CmsLinks::where('table_type', '=', 'DISTRIBUTOR')->where('table_id', '=', $distributor_id)
                ->update(['slug_url' => trim( $request->input('slug') )]);

                /** Need For Page Builder -- Update Time **/
                $cmsInfo = CmsLinks::where('table_id', '=', $distributor_id)->where('table_type', '=', 'DISTRIBUTOR')->first();
                if( !empty($cmsInfo) ) {
                    
                    /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                    update_page_builder($insert_id, $cmsInfo->id, $distributor_id, 'DISTRIBUTOR');

                }
                /** End Page Builder **/
                
                if( $request->has('distributor_category_id') ) {
                    foreach( $request->input('distributor_category_id') as $cats ) {
                        $arr = array();
                        $arr['distributor_id'] = $distributor_id;
                        $arr['distributor_category_id'] = $cats;
                        array_push( $distrCatsMap, $arr );
                    }
                    if( !empty($distrCatsMap) ) {
                        DistributorCategoriesMap::insert( $distrCatsMap );
                    }
                }
                if( $request->has('product_category_id') ) {
                    foreach( $request->input('product_category_id') as $cats ) {
                        $arr = array();
                        $arr['distributor_id'] = $distributor_id;
                        $arr['product_category_id'] = $cats;
                        array_push( $prodCatsMap, $arr );
                    }
                    if( !empty($prodCatsMap) ) {
                        DistributorProductCategoriesMap::insert( $prodCatsMap );
                    }
                }
                if( $request->has('industry_id') ) {
                    foreach( $request->input('industry_id') as $cats ) {
                        $arr = array();
                        $arr['distributor_id'] = $distributor_id;
                        $arr['industry_id'] = $cats;
                        array_push( $indusMap, $arr );
                    }
                    if( !empty($indusMap) ) {
                        DistributorIndustriesMap::insert( $indusMap );
                    }
                }
                return back()->with('msg', 'Distributor Updated Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
       }

       if( $child_language_id == '' ) {

            $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

            $prodCatsMap = array();
            $indusMap = array();
            $distrCatsMap = array();
            
            $Distributor = new Distributor;
            $Distributor->name = trim( ucfirst($request->input('name')) );
            $Distributor->slug = trim($request->input('slug'));
            $Distributor->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
            $Distributor->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $Distributor->created_by = Auth::user()->id;
            $Distributor->language_id = trim( $request->input('language_id') );
            $Distributor->parent_language_id = $parent_language_id;

            $Distributor->insert_id = $insert_id;

            $Distributor->meta_title = trim($request->input('meta_title'));
            $Distributor->meta_desc = trim($request->input('meta_desc'));
            $Distributor->meta_keyword = trim($request->input('meta_keyword'));
            $Distributor->canonical_url = trim($request->input('canonical_url'));
            $Distributor->lng_tag = trim($request->input('lng_tag'));
            $Distributor->follow = trim($request->input('follow'));
            $Distributor->index_tag = trim($request->input('index_tag'));

            if( $Distributor->save() ) {

                $distributor_id = $Distributor->id;
                
                $CmsLinks = new CmsLinks;
                $CmsLinks->table_id = $distributor_id;
                $CmsLinks->table_type = 'DISTRIBUTOR';
                $CmsLinks->slug_url = trim( $request->input('slug') );
                $CmsLinks->save();

                $cms_link_id = $CmsLinks->id; // Need for page builder as parameter

                /** For Page Builder -- Insert Time **/
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                update_page_builder($insert_id, $cms_link_id, $distributor_id, 'DISTRIBUTOR');
                /** End Page Builder **/
                
                if( $request->has('distributor_category_id') ) {
                    foreach( $request->input('distributor_category_id') as $cats ) {
                        $arr = array();
                        $arr['distributor_id'] = $distributor_id;
                        $arr['distributor_category_id'] = $cats;
                        array_push( $distrCatsMap, $arr );
                    }
                    if( !empty($distrCatsMap) ) {
                        DistributorCategoriesMap::insert( $distrCatsMap );
                    }
                }
                if( $request->has('product_category_id') ) {
                    foreach( $request->input('product_category_id') as $cats ) {
                        $arr = array();
                        $arr['distributor_id'] = $distributor_id;
                        $arr['product_category_id'] = $cats;
                        array_push( $prodCatsMap, $arr );
                    }
                    if( !empty($prodCatsMap) ) {
                        DistributorProductCategoriesMap::insert( $prodCatsMap );
                    }
                }
                if( $request->has('industry_id') ) {
                    foreach( $request->input('industry_id') as $cats ) {
                        $arr = array();
                        $arr['distributor_id'] = $distributor_id;
                        $arr['industry_id'] = $cats;
                        array_push( $indusMap, $arr );
                    }
                    if( !empty($indusMap) ) {
                        DistributorIndustriesMap::insert( $indusMap );
                    }
                }
                return redirect()->route('edtDistrib', array('id' => $parent_language_id))
                ->with('msg', 'Distributor Created Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
       }

       return back(); 
    }

    public function deleteLanguage( $parent_language_id, $child_language_id ) {
        
        Distributor::find($child_language_id)->delete();
        
        CmsLinks::where('table_type', '=', 'DISTRIBUTOR')->where('table_id', '=', $child_language_id)->delete();
        DistributorProductCategoriesMap::where('distributor_id', '=', $child_language_id)->delete();
        DistributorCategoriesMap::where('distributor_id', '=', $child_language_id)->delete();
        DistributorIndustriesMap::where('distributor_id', '=', $child_language_id)->delete();
        //DistributorImagesMap::where('distributor_id', '=', $child_language_id)->delete();
        //DistributorFilesMap::where('distributor_id', '=', $child_language_id)->delete();

        delete_navigation($child_language_id, 'DISTRIBUTOR');
        PageBuilder::where('table_id', '=', $child_language_id)->where('table_type', '=', 'DISTRIBUTOR')->delete();

        return redirect()->route('edtDistrib', array('id' => $parent_language_id))
        ->with('msg', 'Distributor Deleted Successfully.')
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
                        $Distributor = Distributor::find($id);
                        $Distributor->status = '1';
                        $Distributor->save();
                    }
                    $msg = 'Distributor Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $Distributor = Distributor::find($id);
                        $Distributor->status = '2';
                        $Distributor->save();
                    }
                    $msg = 'Distributor Deactivated Succesfully.';
                    break;

                case 'delete':
                    foreach($idsArr as $id) {
                        $Distributor = Distributor::find($id);
                        $Distributor->status = '3';
                        $Distributor->save();
                        CmsLinks::where('table_type', '=', 'DISTRIBUTOR')->where('table_id', '=', $id)->delete();
                        DistributorProductCategoriesMap::where('distributor_id', '=', $id)->delete();
                        DistributorCategoriesMap::where('distributor_id', '=', $id)->delete();
                        DistributorIndustriesMap::where('distributor_id', '=', $id)->delete();
                        //DistributorImagesMap::where('distributor_id', '=', $id)->delete();
                        //DistributorFilesMap::where('distributor_id', '=', $id)->delete();
                
                        delete_navigation($id, 'DISTRIBUTOR');
                        PageBuilder::where('table_id', '=', $id)->where('table_type', '=', 'DISTRIBUTOR')->delete();
                    }
                    $msg = 'Distributor Deleted Succesfully.';
                    break;
            }
            return back()->with('msg', $msg)->with('msg_class', 'alert alert-success');
        }
        return back();
    }

    public function bulkActionCont(Request $request) {

        $msg = '';
        if( $request->has('action_btn') && $request->has('ids') ) {
            $actBtnValue = trim( $request->input('action_btn') );
            $idsArr = $request->input('ids');

            switch ( $actBtnValue ) {
                
                case 'activate':
                    foreach($idsArr as $id) {
                        $DistributorContents = DistributorContents::find($id);
                        $DistributorContents->status = '1';
                        $DistributorContents->save();
                    }
                    $msg = 'Distributor Content Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $DistributorContents = DistributorContents::find($id);
                        $DistributorContents->status = '2';
                        $DistributorContents->save();
                    }
                    $msg = 'Distributor Content Deactivated Succesfully.';
                    break;

                case 'delete':
                    foreach($idsArr as $id) {
                        $DistributorContents = DistributorContents::find($id);
                        $DistributorContents->status = '3';
                        $DistributorContents->save();
                        CmsLinks::where('table_type', '=', 'DISTRIBUTOR_CONTENT')->where('table_id', '=', $id)->delete();
                        //DistributorContentImagesMap::where('distributor_content_id', '=', $id)->delete();
                        //DistributorContentFilesMap::where('distributor_content_id', '=', $id)->delete();
                        delete_navigation($id, 'DISTRIBUTOR_CONTENT');
                        PageBuilder::where('table_id', '=', $id)->where('table_type', '=', 'DISTRIBUTOR_CONTENT')->delete();
                    }
                    $msg = 'Distributor Content Deleted Succesfully.';
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
                        $DistributorCategories = DistributorCategories::find($id);
                        $DistributorCategories->status = '1';
                        $DistributorCategories->save();
                    }
                    $msg = 'Distributor Categories Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $DistributorCategories = DistributorCategories::find($id);
                        $DistributorCategories->status = '2';
                        $DistributorCategories->save();
                    }
                    $msg = 'Distributor Categories Deactivated Succesfully.';
                    break;

                case 'delete':
                    foreach($idsArr as $id) {
                        $DistributorCategories = DistributorCategories::find($id);
                        $DistributorCategories->status = '3';
                        $DistributorCategories->save();
                        delete_navigation($id, 'DISTRIBUTOR_CATEGORY');
                        CmsLinks::where('table_type', '=', 'DISTRIBUTOR_CATEGORY')->where('table_id', '=', $id)->delete();
                        PageBuilder::where('table_id', '=', $id)->where('table_type', '=', 'DISTRIBUTOR_CATEGORY')->delete();
                    }
                    $msg = 'Distributor Categories Deleted Succesfully.';
                    break;
            }
            return back()->with('msg', $msg)->with('msg_class', 'alert alert-success');
        }
        return back();
    }



    public function bulkActionLoc(Request $request) {

        $msg = '';
        if( $request->has('action_btn') && $request->has('ids') ) {
            $actBtnValue = trim( $request->input('action_btn') );
            $idsArr = $request->input('ids');

            switch ( $actBtnValue ) {
                
                case 'activate':
                    foreach($idsArr as $id) {
                        $LocationNetwork = LocationNetwork::find($id);
                        $LocationNetwork->status = '1';
                        $LocationNetwork->save();
                    }
                    $msg = 'Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $LocationNetwork = LocationNetwork::find($id);
                        $LocationNetwork->status = '2';
                        $LocationNetwork->save();
                    }
                    $msg = 'Deactivated Succesfully.';
                    break;

                case 'delete':
                    foreach($idsArr as $id) {
                        $LocationNetwork = LocationNetwork::find($id)->delete();
                    }
                    $msg = 'Deleted Succesfully.';
                    break;
            }
            return back()->with('msg', $msg)->with('msg_class', 'alert alert-success');
        }
        return back();
    }


    /*******************************************************************************************************************************/

    public function addLocation() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'distributorManagement';
        $DataBag['childMenu'] = 'disloc';

        $DataBag['allDistrbs'] = Distributor::where('status', '=', '1')->orderBy('name', 'asc')->get();
        $DataBag['allContinents'] = \App\Models\Continents::where('status', '=', '1')->get();

        return view('dashboard.distributors.location.add', $DataBag);
    }


    public function addLocationAction(Request $request) {

        $LocationNetwork = new LocationNetwork;

        $LocationNetwork->title = trim( $request->input('title') );
        $LocationNetwork->distrb_branch_id = trim( $request->input('distrb_branch_id') );
        $LocationNetwork->url = trim( $request->input('url') );
        $LocationNetwork->phno = trim( $request->input('phno') );
        $LocationNetwork->zip = trim( $request->input('zip') );
        $LocationNetwork->country_id = trim( $request->input('country_id') );
        $LocationNetwork->continent_id = trim( $request->input('continent_id') );
        $LocationNetwork->city_id = trim( $request->input('city_id') );
        $LocationNetwork->address = trim( $request->input('address') );
        $LocationNetwork->lat = trim( $request->input('lat') );
        $LocationNetwork->lng = trim( $request->input('lng') );

        if($LocationNetwork->save()) {
            return back()->with('msg', 'Location Added Successfully')->with('msg_class', 'alert alert-success');
        }

        return back();
    }

    public function allLocations() {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'distributorManagement';
        $DataBag['childMenu'] = 'allloc';
        $DataBag['allLocs'] = LocationNetwork::where('status', '!=', '3')->orderBy('id', 'desc')->get();
        return view('dashboard.distributors.location.list', $DataBag);
    }

    public function deleteLocation($id) {

        $res = LocationNetwork::find($id)->delete();
        return back()->with('msg', 'Location Deleted Successfully')->with('msg_class', 'alert alert-success');
    }

    public function editLocation($id) {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'distributorManagement';
        $DataBag['childMenu'] = 'disloc';
        
        $DataBag['location'] = LocationNetwork::findOrFail($id);
        $DataBag['allDistrbs'] = Distributor::where('status', '=', '1')->orderBy('name', 'asc')->get();
        $DataBag['allContinents'] = \App\Models\Continents::where('status', '=', '1')->get();

        $continent_id = $DataBag['location']->continent_id;

        $DataBag['seleCountry'] = DB::table('regions')->where('regions.continent_id', '=', $continent_id)
        ->join('countries', 'countries.region_id', '=', 'regions.id')
        ->orderBy('countries.country_name', 'asc')->select('countries.country_name as name', 'countries.id as id')
        ->get();

        $country_id = $DataBag['location']->country_id;

        $DataBag['seleCity'] = DB::table('provinces')->where('provinces.country_id', '=', $country_id)
        ->join('cities', 'cities.province_id', '=', 'provinces.id')
        ->orderBy('cities.city_name', 'asc')->select('cities.city_name as name', 'cities.id as id')
        ->get();

        return view('dashboard.distributors.location.add', $DataBag); 
    }


    public function updateLocation(Request $request, $id) {

        $LocationNetwork = LocationNetwork::findOrFail($id);

        $LocationNetwork->title = trim( $request->input('title') );
        $LocationNetwork->distrb_branch_id = trim( $request->input('distrb_branch_id') );
        $LocationNetwork->url = trim( $request->input('url') );
        $LocationNetwork->phno = trim( $request->input('phno') );
        $LocationNetwork->zip = trim( $request->input('zip') );
        $LocationNetwork->country_id = trim( $request->input('country_id') );
        $LocationNetwork->continent_id = trim( $request->input('continent_id') );
        $LocationNetwork->city_id = trim( $request->input('city_id') );
        $LocationNetwork->address = trim( $request->input('address') );
        $LocationNetwork->lat = trim( $request->input('lat') );
        $LocationNetwork->lng = trim( $request->input('lng') );

        if($LocationNetwork->save()) {
            return back()->with('msg', 'Location Updated Successfully')->with('msg_class', 'alert alert-success');
        }

        return back();
    }

    public function extraConten() {

        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'distributorManagement';
        $DataBag['childMenu'] = 'disExCont';
        $DataBag['extraCont'] = MediaExtraContent::where('type', '=', 'DISTRIBUTOR')->first();
        return view('dashboard.distributors.extra_content', $DataBag);
    }

    public function extraContentSave(Request $request) {
        
        $MediaExtraContent = MediaExtraContent::where('type', '=', 'DISTRIBUTOR')->first();

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
                $file_size = $img->getSize();
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
            $MediaExtraContent->type = 'DISTRIBUTOR';

            $MediaExtraContent->title = trim($request->input('title'));
            $MediaExtraContent->image_title = trim($request->input('image_title'));
            $MediaExtraContent->image_alt = trim($request->input('image_alt'));
            $MediaExtraContent->image_caption = trim($request->input('image_caption'));

            if( $request->hasFile('page_banner') ) {
            
                $img = $request->file('page_banner');
                $real_path = $img->getRealPath();
                $file_orgname = $img->getClientOriginalName();
                $file_size = $img->getSize();
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
