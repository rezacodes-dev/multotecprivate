<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media\FilesMaster;
use App\Models\Media\Images;
use App\Models\CmsLinks;

use App\Models\Product\Products;
use App\Models\Product\ProductCategories;
use App\Models\Product\ProductCategoriesMap;
use App\Models\Product\ProductCategoriesFilesMap;
use App\Models\Product\ProductCategoriesImagesMap;
use App\Models\Product\ProductsImagesMap;
use App\Models\Languages;
use App\Models\PageBuilder\PageBuilder;
use Auth;
use Image;
use DB;
use Excel;
use App\Models\Referral;
use App\Exports\ProductExport;

class ProductController extends Controller
{

    public function listReferral() {
        $DataBag = array();
        $DataBag['parentMenu'] = 'Traffic';
        $DataBag['childMenu'] = 'list_referral';
        $DataBag['allReferral'] = Referral::orderBy('id', 'desc')->paginate(100);
        // where('referral','not like', '%'.'multotec.com'.'%')

        foreach($DataBag['allReferral'] as $key=>$row){

           $r= explode('/',$row->referral);

           if(isset($r[2])){

            $pattern = '/https/i';
            $r[2]= preg_replace($pattern, '', $r[2]);
 
            $pattern = '/http/i';
            $r[2]= preg_replace($pattern, '', $r[2]);
 
            $pattern = '/www./i';
            $r[2]= preg_replace($pattern, '', $r[2]);
  

            $allcampaign=DB::table('campaign') 
            ->selectRaw('name,url,source_type')   
            ->get();
 
            foreach($allcampaign as $onerow){
 
                $row->referral;

                $str = $row->referral;
                 $onerow->url;

                

                $onerow->url=str_replace("/","#",$onerow->url);


                $pattern = "/{$onerow->url}/i";
               
                $flag= preg_match($pattern, $str);
                
                if($flag)
                {
                    $hits=$onerow;
                }
                // $hits=DB::table('campaign') 
                // ->selectRaw('name,url,source_type')  
                // ->where('url','like', '%'.$r[2].'%')
                // ->first(); 
            } 
  
           }

 
			if(isset($hits->name)){
 
                $source_type=DB::table('source_type') 
                ->selectRaw('name')  
                ->where('id','=', $hits->source_type)
                ->first();
 
                $DataBag['allReferral'][$key]['source_type']= $source_type->name;
                $DataBag['allReferral'][$key]['campaign']= $hits->name;
            }
			else{
                $DataBag['allReferral'][$key]['source_type']= 'N/A';
                $DataBag['allReferral'][$key]['campaign']= 'N/A';
            }
  
		}
         
        return view('dashboard.list_referral', $DataBag);
    } 

    public function deleteReferral($product_id) {
    	 
        Referral::where('id', '=', $product_id)->delete();
                
    	return back()->with('msg', 'Deleted Succesfully.')->with('msg_class', 'alert alert-success');
     
    }
        
   	public function allCategories() {
   		$DataBag = array();
    	$DataBag['parentMenu'] = 'prodManagement';
    	$DataBag['childMenu'] = 'prodCats';
    	$DataBag['allProdCats'] = ProductCategories::where('status', '!=', '3')->where('parent_language_id', '=', '0')->where('parent_language_id', '=', '0')
        ->where('is_duplicate', '=', '0')->orderBy('id', 'desc')->get();
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();

        $DataBag['translate_lang_id'] = Auth::user()->translate_lang_id;
 
        $DataBag['languages'] = Languages::get();

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

    	return view('dashboard.products.categories', $DataBag);
   	} 

    public function createCategory() {
        $DataBag = array();
        $DataBag['language_id'] = 1;
    	$DataBag['parentMenu'] = 'prodManagement';
    	$DataBag['childMenu'] = 'prodAddCats';
    	$DataBag['allCats'] = ProductCategories::where('status', '=', '1')->select('name', 'id')
    	->orderBy('name', 'asc')->get();
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));
    	return view('dashboard.products.create_category', $DataBag);
    }
  
    /**** SAVE PRODUCT CATEGORY ***/

    public function saveCategory(Request $request) {
    	
    	$insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

    	$ProductCategories = new ProductCategories;
    	$ProductCategories->name = trim( ucfirst($request->input('name')) );
    	$ProductCategories->slug = trim($request->input('slug'));
    	$ProductCategories->parent_id = trim($request->input('parent_id'));
    	$ProductCategories->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
        $ProductCategories->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
    	$ProductCategories->created_by = Auth::user()->id;
        $ProductCategories->language_id = trim( $request->input('language_id') );
        $ProductCategories->insert_id = $insert_id;

        $ProductCategories->meta_title = trim($request->input('meta_title'));
        $ProductCategories->meta_desc = trim($request->input('meta_desc'));
        $ProductCategories->meta_keyword = trim($request->input('meta_keyword'));
        $ProductCategories->canonical_url = trim($request->input('canonical_url'));
        $ProductCategories->lng_tag = trim($request->input('lng_tag'));
        $ProductCategories->follow = trim($request->input('follow'));
        $ProductCategories->index_tag = trim($request->input('index_tag'));
        $ProductCategories->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );
 
        $ProCatImageJson = json_decode( trim( $request->input('main_image_infos') ) );
        $bannerCatImageJson = json_decode( trim( $request->input('banner_image_infos') ) );
    	
    	$resx = $ProductCategories->save();
    	if( isset($resx) && $resx == 1 ) {

    		$product_category_id = $ProductCategories->id;
    		
    		$CmsLinks = new CmsLinks;
    		$CmsLinks->table_id = $product_category_id;
    		$CmsLinks->slug_url = trim($request->input('slug'));
    		$CmsLinks->table_type = 'PRODUCT_CATEGORY';
    		$CmsLinks->save();
            $cms_link_id = $CmsLinks->id; // Need for page builder as parameter

            /** For Page Builder -- Insert Time **/
            /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
            update_page_builder($insert_id, $cms_link_id, $product_category_id, 'PRODUCT_CATEGORY');
            /** End Page Builder **/

            if( !empty($ProCatImageJson) ) {
                $imageMap = array();
                foreach ($ProCatImageJson as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['product_category_id'] = $product_category_id;
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
                    ProductCategoriesImagesMap::insert($imageMap);
                }
            }

            if( !empty($bannerCatImageJson) ) {
                $imageMap = array();
                foreach ($bannerCatImageJson as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['product_category_id'] = $product_category_id;
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
                    ProductCategoriesImagesMap::insert($imageMap);
                }
            }

    		return back()->with('msg', 'Product Category Created Successfully.')
    		->with('msg_class', 'alert alert-success');
    	}

    	return back()->with('msg', 'Something Went Wrong')
    	->with('msg_class', 'alert alert-danger');
    }


    public function deleteCategory($category_id) {
    	$ck = ProductCategories::find($category_id);
    	if( isset($ck) && !empty($ck) ) {
    		$ck->status = '3';
    		$res = $ck->save();
    		if( isset($res) && $res == 1 ) {

                delete_navigation($category_id, 'PRODUCT_CATEGORY');
    			CmsLinks::where('table_type', '=', 'PRODUCT_CATEGORY')->where('table_id', '=', $category_id)->delete();
                
                PageBuilder::where('table_id', '=', $category_id)->where('table_type', '=', 'PRODUCT_CATEGORY')->delete();
    			
                ProductCategoriesImagesMap::where('product_category_id', '=', $category_id)->delete();

                return back()->with('msg', 'Product Category Deleted Successfully.')
    			->with('msg_class', 'alert alert-success');
    		}
    	}

    	return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function editCategory($category_id,Request $request) {
        $DataBag = array();
        $DataBag['language_id'] =  $request->input('language_id');
    	$DataBag['parentMenu'] = 'prodManagement';
    	$DataBag['childMenu'] = 'prodAddCats';
    	
         
        $DataBag['content_id'] = $category_id;
 
        if($DataBag['language_id']==1){
            $DataBag['prodCat'] = ProductCategories::where('status', '=', '1')->where('id',$category_id)
        ->orderBy('name', 'asc')->first();
        }
        else{

            $DataBag['prodCat'] =  ProductCategories::where('status', '=', '1')->where('parent_language_id',$category_id)->where('language_id', $DataBag['language_id'])
        ->orderBy('name', 'asc')->first();

         
            if($DataBag['prodCat']==null){  
               
                $DataBag['prodCat'] = ProductCategories::where('status', '=', '1')->where('id',$category_id)
                ->orderBy('name', 'asc')->first();
            }
        
        }
        $DataBag['pageBuilderData'] = $DataBag['prodCat']; /* For pagebuilder */
        
        $DataBag['allCats'] = ProductCategories::get();

        $DataBag['languages'] = Languages::get();
    	return view('dashboard.products.create_category', $DataBag);
    }


    /**** UPDATE PRODUCT CATEGORY ***/

    public function updateCategory(Request $request, $category_id) {

        $language_id=$request->input('language_id');
        if($language_id!=1){

            $DataBag['dynaContent'] = ProductCategories::where('parent_language_id',$category_id)->where('language_id', $language_id)->first();
 
         if(isset($DataBag['dynaContent']) && $DataBag['dynaContent']!=null){
            $insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

            $category_id = $DataBag['dynaContent']['id'];
 
            $ProductCategories = ProductCategories::find($DataBag['dynaContent']['id']);
            $ProductCategories->name = trim( ucfirst($request->input('name')) );
            $ProductCategories->slug = trim($request->input('slug'));
            $ProductCategories->parent_id = trim($request->input('parent_id'));
            $ProductCategories->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $ProductCategories->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
            $ProductCategories->updated_by = Auth::user()->id;
    
            $ProductCategories->meta_title = trim($request->input('meta_title'));
            $ProductCategories->meta_desc = trim($request->input('meta_desc'));
            $ProductCategories->meta_keyword = trim($request->input('meta_keyword'));
            $ProductCategories->canonical_url = trim($request->input('canonical_url'));
            $ProductCategories->lng_tag = trim($request->input('lng_tag'));
            $ProductCategories->follow = trim($request->input('follow'));
            $ProductCategories->index_tag = trim($request->input('index_tag'));
            $ProductCategories->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );
    
            $ProductCategories->is_duplicate = 0;
    
            $ProCatImageJson = json_decode( trim( $request->input('main_image_infos') ) );
            $bannerCatImageJson = json_decode( trim( $request->input('banner_image_infos') ) );
            
            $resx = $ProductCategories->save();
            
            if( isset($resx) && $resx == 1 ) {
    
                CmsLinks::where('table_id', '=', $category_id)->where('table_type', '=', 'PRODUCT_CATEGORY')
                ->update(['slug_url' => trim($request->input('slug'))]);
                
                /** Need For Page Builder -- Update Time **/
                $cmsInfo = CmsLinks::where('table_id', '=', $category_id)->where('table_type', '=', 'PRODUCT_CATEGORY')->first();
                if( !empty($cmsInfo) ) {
                    
                    /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                    update_page_builder($insert_id, $cmsInfo->id, $category_id, 'PRODUCT_CATEGORY');
    
                }
                /** End Page Builder **/
    
                if( !empty($ProCatImageJson) ) {
                    $imageMap = array();
                    foreach ($ProCatImageJson as $v) {
                        if( $v->img_id != '' ) {
                            $arr = array();
                            $arr['product_category_id'] = $category_id;
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
                        ProductCategoriesImagesMap::insert($imageMap);
                    }
                }
    
                if( !empty($bannerCatImageJson) ) {
                    $imageMap = array();
                    foreach ($bannerCatImageJson as $v) {
                        if( $v->img_id != '' ) {
                            $arr = array();
                            $arr['product_category_id'] = $category_id;
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
                        ProductCategoriesImagesMap::insert($imageMap);
                    }
                }
    
                
                return redirect()->route('prodCats')->with('msg', 'Product Category Updated Successfully.')
                ->with('msg_class', 'alert alert-success');
                
            }
        }

        else{
            $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

        $ProductCategories = new ProductCategories;
        $ProductCategories->parent_language_id = $category_id;

        $parent_id= $category_id;

    	$ProductCategories->name = trim( ucfirst($request->input('name')) );
    	$ProductCategories->slug = trim($request->input('slug'));
    	$ProductCategories->parent_id = trim($request->input('parent_id'));
    	$ProductCategories->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
        $ProductCategories->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
    	$ProductCategories->created_by = Auth::user()->id;
        $ProductCategories->language_id = trim( $request->input('language_id') );
        $ProductCategories->insert_id = $insert_id;

        $ProductCategories->meta_title = trim($request->input('meta_title'));
        $ProductCategories->meta_desc = trim($request->input('meta_desc'));
        $ProductCategories->meta_keyword = trim($request->input('meta_keyword'));
        $ProductCategories->canonical_url = trim($request->input('canonical_url'));
        $ProductCategories->lng_tag = trim($request->input('lng_tag'));
        $ProductCategories->follow = trim($request->input('follow'));
        $ProductCategories->index_tag = trim($request->input('index_tag'));
        $ProductCategories->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );
 
        $ProCatImageJson = json_decode( trim( $request->input('main_image_infos') ) );
        $bannerCatImageJson = json_decode( trim( $request->input('banner_image_infos') ) );
    	
    	$resx = $ProductCategories->save();
    	if( isset($resx) && $resx == 1 ) {

    		$product_category_id = $ProductCategories->id;
    		
    		$CmsLinks = new CmsLinks;
    		$CmsLinks->table_id = $product_category_id;
    		$CmsLinks->slug_url = trim($request->input('slug'));
    		$CmsLinks->table_type = 'PRODUCT_CATEGORY';
    		$CmsLinks->save();
            $cms_link_id = $CmsLinks->id; // Need for page builder as parameter

            /** For Page Builder -- Insert Time **/
            /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
            // update_page_builder($insert_id, $cms_link_id, $product_category_id, 'PRODUCT_CATEGORY');

            $datapage = DB::table('page_builder')->where('insert_id', '=', $insert_id)->where('table_id', '=',  $parent_id)->get();
            
                    
            foreach($datapage as $row){
                $updateArr = array();
                $updateArr['insert_id'] = $insert_id;
                $updateArr['cms_link_id'] = $cms_link_id;
                $updateArr['table_id'] =  $product_category_id;
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

            if( !empty($ProCatImageJson) ) {
                $imageMap = array();
                foreach ($ProCatImageJson as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['product_category_id'] = $product_category_id;
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
                    ProductCategoriesImagesMap::insert($imageMap);
                }
            }
            else{
                $datapage = DB::table('product_categories_images_map')->where('product_category_id', '=', $parent_id)->get();
                foreach($datapage as $row){
                
                    $updateArr = array();
                    $updateArr['product_category_id'] = $product_category_id;
                    $updateArr['image_id'] = $row->image_id;
                    $updateArr['title'] = $row->title;
                    $updateArr['caption'] = $row->caption;
                    $updateArr['alt_tag'] = $row->alt_tag;
                    $updateArr['description'] = $row->description;
                    $updateArr['image_type'] = $row->image_type; 
                    DB::table('product_categories_images_map')->insert( $updateArr );

                }
            }

            if( !empty($bannerCatImageJson) ) {
                $imageMap = array();
                foreach ($bannerCatImageJson as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['product_category_id'] = $product_category_id;
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
                    ProductCategoriesImagesMap::insert($imageMap);
                }
            }

    		 
            return redirect()->route('prodCats')->with('msg', 'Product Category Created Successfully.')
            ->with('msg_class', 'alert alert-success');
    	}
        }

        }



        else{

 
        $insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time
        
    	$ProductCategories = ProductCategories::find($category_id);
    	$ProductCategories->name = trim( ucfirst($request->input('name')) );
    	$ProductCategories->slug = trim($request->input('slug'));
    	$ProductCategories->parent_id = trim($request->input('parent_id'));
    	$ProductCategories->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
        $ProductCategories->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
    	$ProductCategories->updated_by = Auth::user()->id;

        $ProductCategories->meta_title = trim($request->input('meta_title'));
        $ProductCategories->meta_desc = trim($request->input('meta_desc'));
        $ProductCategories->meta_keyword = trim($request->input('meta_keyword'));
        $ProductCategories->canonical_url = trim($request->input('canonical_url'));
        $ProductCategories->lng_tag = trim($request->input('lng_tag'));
        $ProductCategories->follow = trim($request->input('follow'));
        $ProductCategories->index_tag = trim($request->input('index_tag'));
        $ProductCategories->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );

        $ProductCategories->is_duplicate = 0;

        $ProCatImageJson = json_decode( trim( $request->input('main_image_infos') ) );
        $bannerCatImageJson = json_decode( trim( $request->input('banner_image_infos') ) );
    	
    	$resx = $ProductCategories->save();
    	
        if( isset($resx) && $resx == 1 ) {

    		CmsLinks::where('table_id', '=', $category_id)->where('table_type', '=', 'PRODUCT_CATEGORY')
    		->update(['slug_url' => trim($request->input('slug'))]);
    		
    		/** Need For Page Builder -- Update Time **/
            $cmsInfo = CmsLinks::where('table_id', '=', $category_id)->where('table_type', '=', 'PRODUCT_CATEGORY')->first();
            if( !empty($cmsInfo) ) {
                
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                update_page_builder($insert_id, $cmsInfo->id, $category_id, 'PRODUCT_CATEGORY');

            }
            /** End Page Builder **/

            if( !empty($ProCatImageJson) ) {
                $imageMap = array();
                foreach ($ProCatImageJson as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['product_category_id'] = $category_id;
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
                    ProductCategoriesImagesMap::insert($imageMap);
                }
            }

            if( !empty($bannerCatImageJson) ) {
                $imageMap = array();
                foreach ($bannerCatImageJson as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['product_category_id'] = $category_id;
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
                    ProductCategoriesImagesMap::insert($imageMap);
                }
            }
 
            return redirect()->route('prodCats')->with('msg', 'Product Category Updated Successfully.')
            ->with('msg_class', 'alert alert-success');
    	}
    }
    	return back()->with('msg', 'Something Went Wrong')
    	->with('msg_class', 'alert alert-danger');
    }

    public function allProducts() {
    	$DataBag = array();
    	$DataBag['parentMenu'] = 'prodManagement';
    	$DataBag['childMenu'] = 'prodList';
    	$DataBag['allProducts'] = Products::where('status', '!=', '3')->where('parent_language_id', '=', '0')
        ->where('is_duplicate', '=', '0')->where('parent_id', '=', '0')->orderBy('id', 'desc')->get();

        $DataBag['translate_lang_id'] = Auth::user()->translate_lang_id;


        $DataBag['languages'] = Languages::get();

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

    	return view('dashboard.products.index', $DataBag);
    }

    public function addProduct() {
        $DataBag = array();
        $DataBag['language_id'] = 1;
    	$DataBag['parentMenu'] = 'prodManagement';
    	$DataBag['childMenu'] = 'prodAdd';
    	$DataBag['allCats'] = ProductCategories::where('status', '=', '1')->select('name', 'id')
    	->orderBy('name', 'asc')->get();
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));
    	return view('dashboard.products.add', $DataBag);
    }

    public function deleteProduct($product_id) {
    	$ck = Products::find($product_id);
    	if( isset($ck) && !empty($ck) ) {
    		$ck->status = '3';
    		$res = $ck->save();
    		if( isset($res) && $res == 1 ) {
    			
                ProductCategoriesMap::where('product_id', '=', $product_id)->delete();
    			
                /* delete_navigation($table_id, $table_type) */
                delete_navigation($product_id, 'PRODUCT');
                CmsLinks::where('table_type', '=', 'PRODUCT')->where('table_id', '=', $product_id)->delete();
                
                PageBuilder::where('table_id', '=', $product_id)->where('table_type', '=', 'PRODUCT')->delete();

                ProductsImagesMap::where('product_id', '=', $product_id)->delete();

    			return back()->with('msg', 'Product Deleted Succesfully.')->with('msg_class', 'alert alert-success');
    		}
    	}
    	return back()->with('msg', 'Something Went Wrong.')->with('msg_class', 'alert alert-danger');
    }

    public function editProduct($product_id,Request $request) {
        $DataBag = array();
        
        $DataBag['language_id'] =  $request->input('language_id'); 
    	$DataBag['parentMenu'] = 'prodManagement';
        $DataBag['childMenu'] = 'prodAdd';
          
        $DataBag['content_id'] = $product_id;
        
        if($DataBag['language_id']==1){
            $DataBag['product'] = Products::with( ['pageBuilderContent'] )->findOrFail($product_id);
        }
        else{

            $DataBag['product'] = Products::with( ['pageBuilderContent'] )->where('parent_id',$product_id)->where('language_id', $DataBag['language_id'])->first();
        


            if($DataBag['product']==null){ 
                
                $DataBag['product'] = Products::where('id',$product_id)->first(); 
                // dd( $DataBag['product']);
            }
        
        }
        $DataBag['insert_id'] = md5(microtime(TRUE));
        $DataBag['pageBuilderData'] = $DataBag['product'];
    	$DataBag['allCats'] = ProductCategories::where('status', '=', '1')->select('name', 'id')
    	->orderBy('name', 'asc')->get();
        $DataBag['languages'] = Languages::get();
    	return view('dashboard.products.add', $DataBag);
    }



    /**** SAVE PRODUCT ***/

    public function saveProduct(Request $request) {

    	$categoriesMap = array();
        $imageMap = array();

        $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time
    	
        $Products = new Products;
    	$Products->name = trim( ucfirst($request->input('name')) );
        $Products->description = trim( $request->input('description') );
        $Products->insert_id = $insert_id;
    	$Products->slug = trim($request->input('slug'));
    	$Products->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
    	$Products->created_by = Auth::user()->id;
        $Products->language_id = trim( $request->input('language_id') );

        $productImageJson = json_decode( trim( $request->input('main_image_infos') ) );
        $bannerCatImageJson = json_decode( trim( $request->input('banner_image_infos') ) );

        $Products->meta_title = trim($request->input('meta_title'));
        $Products->meta_desc = trim($request->input('meta_desc'));
        $Products->meta_keyword = trim($request->input('meta_keyword'));
        $Products->canonical_url = trim($request->input('canonical_url'));
        $Products->lng_tag = trim($request->input('lng_tag'));
        $Products->follow = trim($request->input('follow'));
        $Products->index_tag = trim($request->input('index_tag'));
        $Products->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );
        
    	
    	$resx = $Products->save();
    	if( isset($resx) && $resx == 1 ) {

    		$product_id = $Products->id;
    		
    		$CmsLinks = new CmsLinks;
    		$CmsLinks->table_id = $product_id;
    		$CmsLinks->slug_url = trim($request->input('slug'));
    		$CmsLinks->table_type = 'PRODUCT';
    		$CmsLinks->save();
            $cms_link_id = $CmsLinks->id; // Need for page builder as parameter

            /** For Page Builder -- Insert Time **/
            /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
            update_page_builder($insert_id, $cms_link_id, $product_id, 'PRODUCT');
            /** End Page Builder **/

            if( !empty($productImageJson) ) {
                foreach ($productImageJson as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['product_id'] = $product_id;
                        $arr['image_id'] = $v->img_id;
                        $arr['title'] = $v->img_titl;
                        $arr['caption'] = $v->img_cap;
                        $arr['alt_tag'] = $v->img_alt;
                        $arr['description'] = $v->img_dsc;
                        $arr['image_type'] = "MAIN_IMAGE";
                        array_push( $imageMap, $arr );
                    }
                }
            }

            if( !empty($imageMap) ) {
                ProductsImagesMap::insert($imageMap);
            }

            if( !empty($bannerCatImageJson) ) {
                $imageMap = array();
                foreach ($bannerCatImageJson as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['product_id'] = $product_id;
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
                    ProductsImagesMap::insert($imageMap);
                }
            }

	    	if( $request->has('category_id') ) {
	    		foreach( $request->input('category_id') as $cats ) {
	    			if($cats != '') {
                        $arr = array();
	    			    $arr['product_id'] = $product_id;
	    			    $arr['product_category_id'] = $cats;
	    			    array_push( $categoriesMap, $arr );
                    }
	    		}
	    		if( !empty($categoriesMap) ) {
	    			ProductCategoriesMap::insert( $categoriesMap );
	    		}
	    	}
    		return back()->with('msg', 'Product Created Successfully.')
    		->with('msg_class', 'alert alert-success');
    	}

    	return back()->with('msg', 'Something Went Wrong')
    	->with('msg_class', 'alert alert-danger');
    }

 
    /**** UPDATE PRODUCT ***/

    public function updateProduct(Request $request, $product_id) {


          $language_id=$request->input('language_id');  
        if($language_id!=1){

        $DataBag['dynaContent'] = Products::where('parent_id',$product_id)->where('language_id', $language_id)->first();
 
        if(isset($DataBag['dynaContent']) && $DataBag['dynaContent']!=null){

            $categoriesMap = array();
            $imageMap = array();
    
            $insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time
            $product_id = $DataBag['dynaContent']['id'];
            $Products = Products::find($product_id);
            $Products->insert_id = $insert_id;
            $Products->name = trim( ucfirst($request->input('name')) );
            $Products->description = trim( $request->input('description') );
            $Products->slug = trim($request->input('slug'));
            $Products->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $Products->updated_by = Auth::user()->id;
    
            $productImageJson = json_decode( trim( $request->input('main_image_infos') ) );
            $bannerCatImageJson = json_decode( trim( $request->input('banner_image_infos') ) );
     
            $Products->meta_title = trim($request->input('meta_title'));
            $Products->meta_desc = trim($request->input('meta_desc'));
            $Products->meta_keyword = trim($request->input('meta_keyword'));
            $Products->canonical_url = trim($request->input('canonical_url'));
            $Products->lng_tag = trim($request->input('lng_tag'));
            $Products->follow = trim($request->input('follow'));
            $Products->index_tag = trim($request->input('index_tag'));
            $Products->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );
    
            $Products->is_duplicate = 0;
        
            $resx = $Products->save();
            if( isset($resx) && $resx == 1 ) {
    
                ProductCategoriesMap::where('product_id', '=', $product_id)->delete();
    
                CmsLinks::where('table_id', '=', $product_id)->where('table_type', '=', 'PRODUCT')
                ->update( [ 'slug_url' => trim($request->input('slug')) ] );
    
                
                /** Need For Page Builder -- Update Time **/
                $cmsInfo = CmsLinks::where('table_id', '=', $product_id)->where('table_type', '=', 'PRODUCT')->first();
    
                if( !empty($cmsInfo) ) {
                    
                    /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                    update_page_builder($insert_id, $cmsInfo->id, $product_id, 'PRODUCT');
    
                }
                /** End Page Builder **/
    
                if( !empty($productImageJson) ) {
                    foreach ($productImageJson as $v) {
                        if( $v->img_id != '' ) {
                            $arr = array();
                            $arr['product_id'] = $product_id;
                            $arr['image_id'] = $v->img_id;
                            $arr['title'] = $v->img_titl;
                            $arr['caption'] = $v->img_cap;
                            $arr['alt_tag'] = $v->img_alt;
                            $arr['description'] = $v->img_dsc;
                            $arr['image_type'] = "MAIN_IMAGE";
                            array_push( $imageMap, $arr );
                        }
                    }
                }
    
                if( !empty($imageMap) ) {
                    ProductsImagesMap::insert($imageMap);
                }
    
                if( !empty($bannerCatImageJson) ) {
                    ProductsImagesMap::where('product_id', '=', $product_id)->where('image_type', '=', 'BANNER_IMAGE')->delete();
                    $imageMap = array();
                    foreach ($bannerCatImageJson as $v) {
                        if( $v->img_id != '' ) {
                            $arr = array();
                            $arr['product_id'] = $product_id;
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
                        ProductsImagesMap::insert($imageMap);
                    }
                }
    
    
                if( $request->has('category_id') ) {
                    foreach( $request->input('category_id') as $cats ) {
                        if($cats != '') {
                            $arr = array();
                            $arr['product_id'] = $product_id;
                            $arr['product_category_id'] = $cats;
                            array_push( $categoriesMap, $arr );
                        }
                    }
                    if( !empty($categoriesMap) ) {
                        ProductCategoriesMap::insert( $categoriesMap );
                    }
                }
    
                // return back()->with('msg', 'Product Updated Successfully.')
                // ->with('msg_class', 'alert alert-success');


                return redirect()->route('allProds')->with('msg', 'Product Updated Successfully.')
            ->with('msg_class', 'alert alert-success');
            }

        } 
        else{
 
            $categoriesMap = array();
        $imageMap = array();

        $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time
    	
        $Products = new Products;
        $Products->parent_id = $product_id;
        $parent_id= $product_id;
    	$Products->name = trim( ucfirst($request->input('name')) );
        $Products->description = trim( $request->input('description') );
        $Products->insert_id = $insert_id;
    	$Products->slug = trim($request->input('slug'));
    	$Products->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
    	$Products->created_by = Auth::user()->id;
        $Products->language_id = trim( $request->input('language_id') );

        $productImageJson = json_decode( trim( $request->input('main_image_infos') ) );
        $bannerCatImageJson = json_decode( trim( $request->input('banner_image_infos') ) );

        $Products->meta_title = trim($request->input('meta_title'));
        $Products->meta_desc = trim($request->input('meta_desc'));
        $Products->meta_keyword = trim($request->input('meta_keyword'));
        $Products->canonical_url = trim($request->input('canonical_url'));
        $Products->lng_tag = trim($request->input('lng_tag'));
        $Products->follow = trim($request->input('follow'));
        $Products->index_tag = trim($request->input('index_tag'));
        $Products->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );
        
    	
    	$resx = $Products->save();
    	if( isset($resx) && $resx == 1 ) {

    		$product_id = $Products->id;
    		
    		$CmsLinks = new CmsLinks;
    		$CmsLinks->table_id = $product_id;
    		$CmsLinks->slug_url = trim($request->input('slug'));
    		$CmsLinks->table_type = 'PRODUCT';
    		$CmsLinks->save();
            $cms_link_id = $CmsLinks->id; // Need for page builder as parameter

            /** For Page Builder -- Insert Time **/
            /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
            // update_page_builder($insert_id, $cms_link_id, $product_id, 'PRODUCT');

            $datapage = DB::table('page_builder')->where('insert_id', '=', $insert_id)->where('table_id', '=',  $parent_id)->get();
            
                    
            foreach($datapage as $row){
                $updateArr = array();
                $updateArr['insert_id'] = $insert_id;
                $updateArr['cms_link_id'] = $cms_link_id;
                $updateArr['table_id'] =  $product_id;
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
 
            if( !empty($productImageJson) ) {
                foreach ($productImageJson as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['product_id'] = $product_id;
                        $arr['image_id'] = $v->img_id;
                        $arr['title'] = $v->img_titl;
                        $arr['caption'] = $v->img_cap;
                        $arr['alt_tag'] = $v->img_alt;
                        $arr['description'] = $v->img_dsc;
                        $arr['image_type'] = "MAIN_IMAGE";
                        array_push( $imageMap, $arr );
                    }
                }
            }

            else{
                $datapage = DB::table('products_images_map')->where('product_id', '=', $parent_id)->get();
                foreach($datapage as $row){
                
                $updateArr = array();
                $updateArr['product_id'] = $product_id;
                $updateArr['image_id'] = $row->image_id;
                $updateArr['title'] = $row->title;
                $updateArr['caption'] = $row->caption;
                $updateArr['alt_tag'] = $row->alt_tag;
                $updateArr['description'] = $row->description;
                $updateArr['image_type'] = $row->image_type; 
                DB::table('products_images_map')->insert( $updateArr );

                }

            
            }

            if( !empty($imageMap) ) {
                ProductsImagesMap::insert($imageMap);
            }

            if( !empty($bannerCatImageJson) ) {
                $imageMap = array();
                foreach ($bannerCatImageJson as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['product_id'] = $product_id;
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
                    ProductsImagesMap::insert($imageMap);
                }
            }

	    	if( $request->has('category_id') ) {
	    		foreach( $request->input('category_id') as $cats ) {
	    			if($cats != '') {
                        $arr = array();
	    			    $arr['product_id'] = $product_id;
	    			    $arr['product_category_id'] = $cats;
	    			    array_push( $categoriesMap, $arr );
                    }
	    		}
	    		if( !empty($categoriesMap) ) {
	    			ProductCategoriesMap::insert( $categoriesMap );
	    		}
	    	}
    		// return back()->with('msg', 'Product Created Successfully.')
            // ->with('msg_class', 'alert alert-success');
            

            return redirect()->route('allProds')->with('msg', 'Product Created Successfully.')
            ->with('msg_class', 'alert alert-success');
    	}
        }
        
        }
        else{
    	$categoriesMap = array();
        $imageMap = array();

        $insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

    	$Products = Products::find($product_id);
        $Products->insert_id = $insert_id;
    	$Products->name = trim( ucfirst($request->input('name')) );
        $Products->description = trim( $request->input('description') );
    	$Products->slug = trim($request->input('slug'));
    	$Products->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
    	$Products->updated_by = Auth::user()->id;

        $productImageJson = json_decode( trim( $request->input('main_image_infos') ) );
        $bannerCatImageJson = json_decode( trim( $request->input('banner_image_infos') ) );


        $Products->meta_title = trim($request->input('meta_title'));
        $Products->meta_desc = trim($request->input('meta_desc'));
        $Products->meta_keyword = trim($request->input('meta_keyword'));
        $Products->canonical_url = trim($request->input('canonical_url'));
        $Products->lng_tag = trim($request->input('lng_tag'));
        $Products->follow = trim($request->input('follow'));
        $Products->index_tag = trim($request->input('index_tag'));
        $Products->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );

        $Products->is_duplicate = 0;
    
    	$resx = $Products->save();
    	if( isset($resx) && $resx == 1 ) {

    		ProductCategoriesMap::where('product_id', '=', $product_id)->delete();

    		CmsLinks::where('table_id', '=', $product_id)->where('table_type', '=', 'PRODUCT')
    		->update( [ 'slug_url' => trim($request->input('slug')) ] );

            
            /** Need For Page Builder -- Update Time **/
            $cmsInfo = CmsLinks::where('table_id', '=', $product_id)->where('table_type', '=', 'PRODUCT')->first();

            if( !empty($cmsInfo) ) {
                
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                update_page_builder($insert_id, $cmsInfo->id, $product_id, 'PRODUCT');

            }
            /** End Page Builder **/

            if( !empty($productImageJson) ) {
                foreach ($productImageJson as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['product_id'] = $product_id;
                        $arr['image_id'] = $v->img_id;
                        $arr['title'] = $v->img_titl;
                        $arr['caption'] = $v->img_cap;
                        $arr['alt_tag'] = $v->img_alt;
                        $arr['description'] = $v->img_dsc;
                        $arr['image_type'] = "MAIN_IMAGE";
                        array_push( $imageMap, $arr );
                    }
                }
            }

            if( !empty($imageMap) ) {
                ProductsImagesMap::insert($imageMap);
            }

            if( !empty($bannerCatImageJson) ) {
                ProductsImagesMap::where('product_id', '=', $product_id)->where('image_type', '=', 'BANNER_IMAGE')->delete();
                $imageMap = array();
                foreach ($bannerCatImageJson as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['product_id'] = $product_id;
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
                    ProductsImagesMap::insert($imageMap);
                }
            }


	    	if( $request->has('category_id') ) {
	    		foreach( $request->input('category_id') as $cats ) {
                    if($cats != '') {
	    			    $arr = array();
	    			    $arr['product_id'] = $product_id;
	    			    $arr['product_category_id'] = $cats;
	    			    array_push( $categoriesMap, $arr );
                    }
	    		}
	    		if( !empty($categoriesMap) ) {
	    			ProductCategoriesMap::insert( $categoriesMap );
	    		}
	    	}

    		// return back()->with('msg', 'Product Updated Successfully.')
            // ->with('msg_class', 'alert alert-success');
            

            return redirect()->route('allProds')->with('msg', 'Product Updated Successfully.')
            ->with('msg_class', 'alert alert-success');
        }
        
    }

    	return back()->with('msg', 'Something Went Wrong')
    	->with('msg_class', 'alert alert-danger');	
    }

    public function downloadProducts($type) {

        $Data = Products::where('status', '!=', '3')->orderBy('created_at', 'desc')->get();
        $filename = 'Multotec_Products_'.date('m-d-Y');
        $excelArr = array();
        foreach($Data as $v) {
            $arr = array();
            $arr['Name'] = $v->name;
            $arr['Description'] = $v->description;
            if( $v->slug != '' ) {
                $arr['URL'] = url($v->slug);
            } else {
                $arr['URL'] = '';
            }
            $arr['Meta Title'] = $v->meta_title;
            $arr['Meta Keywords'] = $v->meta_keywords;
            $arr['Meta Description'] = $v->meta_description;
            array_push($excelArr, $arr);
        }
        return Excel::download(new ProductExport($excelArr),$filename.'.'.$type);

        return Excel::create($filename, function($excel) use ($excelArr) {
            
            $excel->setTitle('Multotec');
            $excel->setCreator('Multotec');
            $excel->setCompany('Multotec');
            $excel->setDescription('Multotec Products');
            
            $excel->sheet('All Products', function($sheet) use ($excelArr)
            {
                $sheet->fromArray($excelArr);
            });
        })->download($type);
    }




    /************************ PRODUCT WITH LANGUAGE ***********************************/

    public function addEditLanguage($parent_language_id, $child_language_id = '') {

        $DataBag = array();
        $DataBag['parentMenu'] = 'prodManagement';
        $DataBag['childMenu'] = 'prodAdd';
        $DataBag['parentLngCont'] = Products::findOrFail($parent_language_id);
        if( $child_language_id != '' ) {
            $DataBag['product'] = Products::findOrFail($child_language_id);
            $DataBag['pageBuilderData'] = $DataBag['product'];
        }
        $DataBag['allCats'] = ProductCategories::where('status', '=', '1')->select('name', 'id')
        ->orderBy('name', 'asc')->get();
        $DataBag['imgGals'] = \App\Models\Media\ImageGalleries::where('status', '=', '1')->get();
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));
        return view('dashboard.products.addedit_language', $DataBag);

    }

    public function addEditLanguagePost(Request $request, $parent_language_id, $child_language_id = '') {

        if( $child_language_id != '' && $child_language_id != null ) {

            $categoriesMap = array();
            $imageMap = array();

            $insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

            $Products = Products::find($child_language_id);
            $Products->insert_id = $insert_id;
            $Products->name = trim( ucfirst($request->input('name')) );
            $Products->description = trim( $request->input('description') );
            $Products->slug = trim($request->input('slug'));
            $Products->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $Products->updated_by = Auth::user()->id;
            $productImageJson = json_decode( trim( $request->input('main_image_infos') ) );

            $Products->meta_title = trim($request->input('meta_title'));
            $Products->meta_desc = trim($request->input('meta_desc'));
            $Products->meta_keyword = trim($request->input('meta_keyword'));
            $Products->canonical_url = trim($request->input('canonical_url'));
            $Products->lng_tag = trim($request->input('lng_tag'));
            $Products->follow = trim($request->input('follow'));
            $Products->index_tag = trim($request->input('index_tag'));
        
            $resx = $Products->save();
            if( isset($resx) && $resx == 1 ) {

                $product_id = $child_language_id;
                ProductCategoriesMap::where('product_id', '=', $product_id)->delete();

                CmsLinks::where('table_id', '=', $product_id)->where('table_type', '=', 'PRODUCT')
                ->update(['slug_url' => trim($request->input('slug'))]);

                /** Need For Page Builder -- Update Time **/
                $cmsInfo = CmsLinks::where('table_id', '=', $product_id)->where('table_type', '=', 'PRODUCT')->first();

                if( !empty($cmsInfo) ) {
                    
                    /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                    update_page_builder($insert_id, $cmsInfo->id, $product_id, 'PRODUCT');

                }
                /** End Page Builder **/

                if( !empty($productImageJson) ) {
                    foreach ($productImageJson as $v) {
                        if( $v->img_id != '' ) {
                            $arr = array();
                            $arr['product_id'] = $product_id;
                            $arr['image_id'] = $v->img_id;
                            $arr['title'] = $v->img_titl;
                            $arr['caption'] = $v->img_cap;
                            $arr['alt_tag'] = $v->img_alt;
                            $arr['description'] = $v->img_dsc;
                            $arr['image_type'] = "MAIN_IMAGE";
                            array_push( $imageMap, $arr );
                        }
                    }
                }

                if( !empty($imageMap) ) {
                    ProductsImagesMap::insert($imageMap);
                }


                if( $request->has('category_id') ) {
                    foreach( $request->input('category_id') as $cats ) {
                        if($cats != '') {
                            $arr = array();
                            $arr['product_id'] = $product_id;
                            $arr['product_category_id'] = $cats;
                            array_push( $categoriesMap, $arr );
                        }
                    }
                }

                if( !empty($categoriesMap) ) {
                    ProductCategoriesMap::insert( $categoriesMap );
                }

                return back()->with('msg', 'Product Updated Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
        }

        if( $child_language_id == '' ) {

            $categoriesMap = array();
            $imageMap = array();

            $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time
            
            $Products = new Products;
            $Products->name = trim( ucfirst($request->input('name')) );
            $Products->description = trim( $request->input('description') );
            $Products->insert_id = $insert_id;
            $Products->slug = trim($request->input('slug'));
            $Products->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $Products->created_by = Auth::user()->id;
            $productImageJson = json_decode( trim( $request->input('main_image_infos') ) );
            $Products->language_id = trim( $request->input('language_id') );
            $Products->parent_language_id = $parent_language_id;

            $Products->meta_title = trim($request->input('meta_title'));
            $Products->meta_desc = trim($request->input('meta_desc'));
            $Products->meta_keyword = trim($request->input('meta_keyword'));
            $Products->canonical_url = trim($request->input('canonical_url'));
            $Products->lng_tag = trim($request->input('lng_tag'));
            $Products->follow = trim($request->input('follow'));
            $Products->index_tag = trim($request->input('index_tag'));
            
            $resx = $Products->save();

            if( isset($resx) && $resx == 1 ) {

                $product_id = $Products->id;
                
                $CmsLinks = new CmsLinks;
                $CmsLinks->table_id = $product_id;
                $CmsLinks->slug_url = trim($request->input('slug'));
                $CmsLinks->table_type = 'PRODUCT';
                $CmsLinks->save();

                $cms_link_id = $CmsLinks->id; // Need for page builder as parameter
                /** For Page Builder -- Insert Time **/
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                update_page_builder($insert_id, $cms_link_id, $product_id, 'PRODUCT');
                /** End Page Builder **/

                if( !empty($productImageJson) ) {
                    foreach ($productImageJson as $v) {
                        if( $v->img_id != '' ) {
                            $arr = array();
                            $arr['product_id'] = $product_id;
                            $arr['image_id'] = $v->img_id;
                            $arr['title'] = $v->img_titl;
                            $arr['caption'] = $v->img_cap;
                            $arr['alt_tag'] = $v->img_alt;
                            $arr['description'] = $v->img_dsc;
                            $arr['image_type'] = "MAIN_IMAGE";
                            array_push( $imageMap, $arr );
                        }
                    }
                }

                if( !empty($imageMap) ) {
                    ProductsImagesMap::insert($imageMap);
                }

                if( $request->has('category_id') ) {
                    foreach( $request->input('category_id') as $cats ) {
                        if($cats != '') {
                            $arr = array();
                            $arr['product_id'] = $product_id;
                            $arr['product_category_id'] = $cats;
                            array_push( $categoriesMap, $arr );
                        }
                    }
                }

                if( !empty($categoriesMap) ) {
                    ProductCategoriesMap::insert( $categoriesMap );
                }

                return redirect()->route('editProd', array('id' => $parent_language_id))
                ->with('msg', 'Product Created Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
        }

        return back();
    }

    public function deleteLanguage($parent_language_id, $child_language_id) {

        Products::find($child_language_id)->delete();
        
        ProductCategoriesMap::where('product_id', '=', $child_language_id)->delete();
        ProductsImagesMap::where('product_id', '=', $child_language_id)->delete();
        
        /* delete_navigation($table_id, $table_type) */
        delete_navigation($child_language_id, 'PRODUCT');
        CmsLinks::where('table_type', '=', 'PRODUCT')->where('table_id', '=', $child_language_id)->delete();
        
        PageBuilder::where('table_id', '=', $child_language_id)->where('table_type', '=', 'PRODUCT')->delete();
        
        return redirect()->route('editProd', array('id' => $parent_language_id))
        ->with('msg', 'Product Deleted Successfully.')
        ->with('msg_class', 'alert alert-success');
    }




    /**************************** CATEGORY LANGUAGE **************************************/


    public function addEditCatLanguage($parent_language_id, $child_language_id = '') {

        $DataBag = array();
        $DataBag['parentMenu'] = 'prodManagement';
        $DataBag['childMenu'] = 'prodAddCats';
        $DataBag['parentLngCont'] = ProductCategories::findOrFail($parent_language_id);
        if( $child_language_id != '' ) {
            $DataBag['prodCat'] = ProductCategories::findOrFail($child_language_id);
            $DataBag['pageBuilderData'] = $DataBag['prodCat'];
        }
        $DataBag['allCats'] = ProductCategories::where('status', '=', '1')->select('name', 'id')
        ->orderBy('name', 'asc')->get();
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));
        return view('dashboard.products.addedit_language_category', $DataBag);
    }

    public function addEditCatLanguagePost(Request $request, $parent_language_id, $child_language_id = '') {

        if( $child_language_id != '' && $child_language_id != null ) {

            $insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

            $ProductCategories = ProductCategories::find($child_language_id);
            $ProductCategories->name = trim( ucfirst($request->input('name')) );
            $ProductCategories->slug = trim($request->input('slug'));
            $ProductCategories->parent_id = trim($request->input('parent_id'));
            $ProductCategories->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $ProductCategories->updated_by = Auth::user()->id;

            $ProductCategories->meta_title = trim($request->input('meta_title'));
            $ProductCategories->meta_desc = trim($request->input('meta_desc'));
            $ProductCategories->meta_keyword = trim($request->input('meta_keyword'));
            $ProductCategories->canonical_url = trim($request->input('canonical_url'));
            $ProductCategories->lng_tag = trim($request->input('lng_tag'));
            $ProductCategories->follow = trim($request->input('follow'));
            $ProductCategories->index_tag = trim($request->input('index_tag'));

            $ProCatImageJson = json_decode( trim( $request->input('main_image_infos') ) );
            $bannerImageJson = json_decode( trim( $request->input('banner_image_infos') ) );
            
            $resx = $ProductCategories->save();
            if( isset($resx) && $resx == 1 ) {
                
                $product_category_id = $child_language_id;
                CmsLinks::where('table_id', '=', $child_language_id)->where('table_type', '=', 'PRODUCT_CATEGORY')
                ->update(['slug_url' => trim($request->input('slug'))]);
                
                /** Need For Page Builder -- Update Time **/
                $cmsInfo = CmsLinks::where('table_id', '=', $product_category_id)->where('table_type', '=', 'PRODUCT_CATEGORY')->first();

                if( !empty($cmsInfo) ) {
                    
                    /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                    update_page_builder($insert_id, $cmsInfo->id, $product_category_id, 'PRODUCT_CATEGORY');

                }
                /** End Page Builder **/

                if( !empty($ProCatImageJson) ) {
                    $imageMap = array();
                    foreach ($ProCatImageJson as $v) {
                        if( $v->img_id != '' ) {
                            $arr = array();
                            $arr['product_category_id'] = $product_category_id;
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
                        ProductCategoriesImagesMap::insert($imageMap);
                    }
                }

                if( !empty($bannerImageJson) ) {
                    $imageMap = array();
                    foreach ($bannerImageJson as $v) {
                        if( $v->img_id != '' ) {
                            $arr = array();
                            $arr['product_category_id'] = $product_category_id;
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
                        ProductCategoriesImagesMap::insert($imageMap);
                    }
                }

                return back()->with('msg', 'Product Category Updated Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
        }

        if( $child_language_id == '' ) {

            $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

            $ProductCategories = new ProductCategories;
            $ProductCategories->name = trim( ucfirst($request->input('name')) );
            $ProductCategories->slug = trim($request->input('slug'));
            $ProductCategories->parent_id = trim($request->input('parent_id'));
            $ProductCategories->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $ProductCategories->created_by = Auth::user()->id;
            $ProductCategories->insert_id = $insert_id;
            $ProductCategories->language_id = trim( $request->input('language_id') );
            $ProductCategories->parent_language_id = $parent_language_id;

            $ProductCategories->meta_title = trim($request->input('meta_title'));
            $ProductCategories->meta_desc = trim($request->input('meta_desc'));
            $ProductCategories->meta_keyword = trim($request->input('meta_keyword'));
            $ProductCategories->canonical_url = trim($request->input('canonical_url'));
            $ProductCategories->lng_tag = trim($request->input('lng_tag'));
            $ProductCategories->follow = trim($request->input('follow'));
            $ProductCategories->index_tag = trim($request->input('index_tag'));

            $ProCatImageJson = json_decode( trim( $request->input('main_image_infos') ) );
            $bannerImageJson = json_decode( trim( $request->input('banner_image_infos') ) );

            $resx = $ProductCategories->save();
            if( isset($resx) && $resx == 1 ) {

                $product_category_id = $ProductCategories->id;
                
                $CmsLinks = new CmsLinks;
                $CmsLinks->table_id = $product_category_id;
                $CmsLinks->slug_url = trim($request->input('slug'));
                $CmsLinks->table_type = 'PRODUCT_CATEGORY';
                $CmsLinks->save();

                $cms_link_id = $CmsLinks->id; // Need for page builder as parameter

                /** For Page Builder -- Insert Time **/
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                update_page_builder($insert_id, $cms_link_id, $product_category_id, 'PRODUCT_CATEGORY');
                /** End Page Builder **/

                if( !empty($ProCatImageJson) ) {
                    $imageMap = array();
                    foreach ($ProCatImageJson as $v) {
                        if( $v->img_id != '' ) {
                            $arr = array();
                            $arr['product_category_id'] = $product_category_id;
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
                        ProductCategoriesImagesMap::insert($imageMap);
                    }
                }

                if( !empty($bannerImageJson) ) {
                    $imageMap = array();
                    foreach ($bannerImageJson as $v) {
                        if( $v->img_id != '' ) {
                            $arr = array();
                            $arr['product_category_id'] = $product_category_id;
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
                        ProductCategoriesImagesMap::insert($imageMap);
                    }
                }


                return redirect()->route('prodCatEdt', array('id' => $parent_language_id))
                ->with('msg', 'Product Category Created Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
        }

        return back();
    }

    public function deleteCatLanguage($parent_language_id, $child_language_id) {

        ProductCategories::find($child_language_id)->delete();

        delete_navigation($child_language_id, 'PRODUCT_CATEGORY');
        CmsLinks::where('table_type', '=', 'PRODUCT_CATEGORY')->where('table_id', '=', $child_language_id)->delete();
        
        PageBuilder::where('table_id', '=', $child_language_id)->where('table_type', '=', 'PRODUCT_CATEGORY')->delete();

        ProductCategoriesImagesMap::where('product_category_id', '=', $child_language_id)->delete();
        
        return redirect()->route('prodCatEdt', array('id' => $parent_language_id))
        ->with('msg', 'Product Category Deleted Successfully.')
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
                        $Products = Products::find($id);
                        $Products->status = '1';
                        $Products->save();
                    }
                    $msg = 'Products Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $Products = Products::find($id);
                        $Products->status = '2';
                        $Products->save();
                    }
                    $msg = 'Products Deactivated Succesfully.';
                    break;

                case 'delete':
                    foreach($idsArr as $id) {
                        $Products = Products::find($id);
                        $Products->status = '3';
                        $Products->save();
                        ProductCategoriesMap::where('product_id', '=', $id)->delete();
                        ProductsImagesMap::where('product_id', '=', $id)->delete();
                        //ProductsFilesMap::where('product_id', '=', $id)->delete();
                        CmsLinks::where('table_type', '=', 'PRODUCT')->where('table_id', '=', $id)->delete();
                        delete_navigation($id, 'PRODUCT');
                        PageBuilder::where('table_id', '=', $id)->where('table_type', '=', 'PRODUCT')->delete();
                    }
                    $msg = 'Products Deleted Succesfully.';
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
                        $ProductCategories = ProductCategories::find($id);
                        $ProductCategories->status = '1';
                        $ProductCategories->save();
                    }
                    $msg = 'Product Categories Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $ProductCategories = ProductCategories::find($id);
                        $ProductCategories->status = '2';
                        $ProductCategories->save();
                    }
                    $msg = 'Product Categories Deactivated Succesfully.';
                    break;

                case 'delete':
                    foreach($idsArr as $id) {
                        $ProductCategories = ProductCategories::find($id);
                        $ProductCategories->status = '3';
                        $ProductCategories->save();
                        ProductCategoriesImagesMap::where('product_category_id', '=', $id)->delete();
                        //ProductCategoriesFilesMap::where('product_category_id', '=', $id)->delete();
                        CmsLinks::where('table_type', '=', 'PRODUCT_CATEGORY')->where('table_id', '=', $id)->delete();

                        delete_navigation($id, 'PRODUCT_CATEGORY');
                        PageBuilder::where('table_id', '=', $id)->where('table_type', '=', 'PRODUCT_CATEGORY')->delete();
                    }
                    $msg = 'Product Categories Deleted Succesfully.';
                    break;
            }
            return back()->with('msg', $msg)->with('msg_class', 'alert alert-success');
        }
        return back();
    }
}
