<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use Session;
use Auth;
use DB;


class DuplicateController extends Controller
{

    public function createDuplicate(Request $request) {
     	
     	if( isset($_GET['tab']) && isset($_GET['id']) && $_GET['tab'] != '' && $_GET['id'] != '' ) {

     		$table = trim( $_GET['tab'] );
     		$id = trim( $_GET['id'] );

     		$insert_id = md5(microtime(TRUE));
     		$old_insertid = '';
     		$table_type = '';
     		$getID = 0;
     		$cmsSlug = 'forduplicate-'.$insert_id;

     		/** For Product **/
     		if( $table == 'products' ) {

     			$masterRecord = DB::table('products')->where('id', '=', $id)->first();
     			if( !empty($masterRecord) ) {

     				$old_insertid = $masterRecord->insert_id;
     				$table_type = 'PRODUCT';

     				$insertMRC = array();
     				$insertMRC['insert_id'] = $insert_id;
     				$insertMRC['description'] = $masterRecord->description;
     				$insertMRC['page_content'] = $masterRecord->page_content;
     				$insertMRC['status'] = $masterRecord->status;
     				$insertMRC['language_id'] = $masterRecord->language_id;
     				$insertMRC['parent_language_id'] = $masterRecord->parent_language_id;
     				$insertMRC['meta_title'] = $masterRecord->meta_title;
     				$insertMRC['meta_desc'] = $masterRecord->meta_desc;
     				$insertMRC['meta_keyword'] = $masterRecord->meta_keyword;
     				$insertMRC['canonical_url'] = $masterRecord->canonical_url;
     				$insertMRC['lng_tag'] = $masterRecord->lng_tag;
     				$insertMRC['follow'] = $masterRecord->follow;
     				$insertMRC['index_tag'] = $masterRecord->index_tag;
     				$insertMRC['created_by'] = Auth::user()->id;
     				$insertMRC['json_markup'] = $masterRecord->json_markup;
     				$insertMRC['is_duplicate'] = 1;

     				$getID = DB::table('products')->insertGetId($insertMRC);
     				
     				if( $getID != NULL && $getID != '') {

	     				$masterImg = DB::table('products_images_map')->where('product_id', '=', $id)->get();
	     				if( !empty($masterImg) && count($masterImg) > 0 ) {
	     					$upArr = array();
	     					foreach($masterImg as $img) {
	     						$inArr = array();
	     						$inArr['product_id'] = $getID;
	     						$inArr['image_id'] = $img->image_id;
	     						$inArr['image_type'] = $img->image_type;
	     						$inArr['title'] = $img->title;
	     						$inArr['alt_tag'] = $img->alt_tag;
	     						$inArr['caption'] = $img->caption;
	     						$inArr['description'] = $img->description;
	     						array_push($upArr, $inArr);
	     					}

	     					DB::table('products_images_map')->insert($upArr);
	     				}

	     				$masterFil = DB::table('products_files_map')->where('product_id', '=', $id)->get();
	     				if( !empty($masterFil) && count($masterFil) > 0 ) {
	     					$upArr = array();
	     					foreach($masterFil as $fl) {
	     						$inArr = array();
	     						$inArr['product_id'] = $getID;
	     						$inArr['file_id'] = $masterFil->file_id;
	     						$inArr['file_type'] = $masterFil->file_type;
	     						array_push($upArr, $inArr);
	     					}

	     					DB::table('products_files_map')->insert($upArr);
	     				}

	     				$masterCat = DB::table('product_categories_map')->where('product_id', '=', $id)->get();
	     				if( !empty($masterCat) && count($masterCat) > 0 ) {
	     					$upArr = array();
	     					foreach($masterCat as $ct) {
	     						$inArr = array();
	     						$inArr['product_id'] = $getID;
	     						$inArr['product_category_id'] = $ct->product_category_id;
	     						array_push($upArr, $inArr);
	     					}

	     					DB::table('product_categories_map')->insert($upArr);
	     				}

	     				$routeName = "editProd";
     				}
     			}
     		}


     		/** For Product Categories**/
     		if( $table == 'product_categories' ) {

     			$masterRecord = DB::table('product_categories')->where('id', '=', $id)->first();
     			if( !empty($masterRecord) ) {

     				$old_insertid = $masterRecord->insert_id;
     				$table_type = 'PRODUCT_CATEGORY';

     				$insertMRC = array();
     				$insertMRC['insert_id'] = $insert_id;
     				$insertMRC['description'] = $masterRecord->description;
     				$insertMRC['page_content'] = $masterRecord->page_content;
     				$insertMRC['status'] = $masterRecord->status;
     				$insertMRC['parent_id'] = $masterRecord->parent_id;
     				$insertMRC['language_id'] = $masterRecord->language_id;
     				$insertMRC['parent_language_id'] = $masterRecord->parent_language_id;
     				$insertMRC['meta_title'] = $masterRecord->meta_title;
     				$insertMRC['meta_desc'] = $masterRecord->meta_desc;
     				$insertMRC['meta_keyword'] = $masterRecord->meta_keyword;
     				$insertMRC['canonical_url'] = $masterRecord->canonical_url;
     				$insertMRC['lng_tag'] = $masterRecord->lng_tag;
     				$insertMRC['follow'] = $masterRecord->follow;
     				$insertMRC['index_tag'] = $masterRecord->index_tag;
     				$insertMRC['created_by'] = Auth::user()->id;
     				$insertMRC['json_markup'] = $masterRecord->json_markup;
     				$insertMRC['is_duplicate'] = 1;

     				$getID = DB::table('product_categories')->insertGetId($insertMRC);
     				
     				if( $getID != NULL && $getID != '') {

	     				$masterImg = DB::table('product_categories_images_map')->where('product_category_id', '=', $id)->get();
	     				if( !empty($masterImg) && count($masterImg) > 0 ) {
	     					$upArr = array();
	     					foreach($masterImg as $img) {
	     						$inArr = array();
	     						$inArr['product_category_id'] = $getID;
	     						$inArr['image_id'] = $img->image_id;
	     						$inArr['image_type'] = $img->image_type;
	     						$inArr['title'] = $img->title;
	     						$inArr['alt_tag'] = $img->alt_tag;
	     						$inArr['caption'] = $img->caption;
	     						$inArr['description'] = $img->description;
	     						array_push($upArr, $inArr);
	     					}

	     					DB::table('product_categories_images_map')->insert($upArr);
	     				}

	     				$masterFil = DB::table('product_categories_files_map')->where('product_category_id', '=', $id)->get();
	     				if( !empty($masterFil) && count($masterFil) > 0 ) {
	     					$upArr = array();
	     					foreach($masterFil as $fl) {
	     						$inArr = array();
	     						$inArr['product_category_id'] = $getID;
	     						$inArr['file_id'] = $masterFil->file_id;
	     						$inArr['file_type'] = $masterFil->file_type;
	     						array_push($upArr, $inArr);
	     					}

	     					DB::table('product_categories_files_map')->insert($upArr);
	     				}
	     				
	     				$routeName = "prodCatEdt";
     				}
     			}
     		}

     		if( $getID != '0' && $table_type != '' ) {

     			$this->pageBuilderDuplocate($id, $table_type, $old_insertid, $getID, $insert_id);
     			
     			DB::table('cms_links')->insert(['table_id' => $getID, 'table_type' => $table_type, 'slug_url' => $cmsSlug]);
     			
     			return redirect()->route($routeName, array('id' => $getID));
     		}
     	} 

     	return back()->with('msg', 'Problem to create duplicate page')->with('msg_class', 'alert alert-danger');
    }




    public function pageBuilderDuplocate($old_id, $table_type, $old_insertid, $new_id, $new_insertid) {
    	
    	if( $old_id != '' && $table_type != '' && $old_insertid != '' && $new_id != '' && $new_insertid != '' ) {

    		$pgMain = DB::table('page_builder')->where('insert_id', '=', $old_insertid)
    		->where('table_id', '=', $old_id)->where('table_type', '=', $table_type)->get();

    		if( !empty($pgMain) && count($pgMain) > 0 ) {

    			foreach($pgMain as $pg) {
    				
    				$insArr = array();
					$insArr['insert_id'] = $new_insertid;
					$insArr['table_id'] = $new_id;
					$insArr['table_type'] = $table_type;
					$insArr['builder_type'] = $pg->builder_type;
					$insArr['main_content'] = $pg->main_content;
					$insArr['sub_content'] = $pg->sub_content;
					$insArr['main_title'] = $pg->main_title;
					$insArr['sub_title'] = $pg->sub_title;
					$insArr['link_text'] = $pg->link_text;
					$insArr['link_url'] = $pg->link_url;
					$insArr['display_order'] = $pg->display_order;
					$insArr['position'] = $pg->position;
					$insArr['device'] = $pg->device;

					$getPgID = DB::table('page_builder')->insertGetId($insArr);

					if( $getPgID != NULL && $getPgID != '' ) {
	    				
	    				if( $pg->builder_type == 'IMAGE_CAROUSEL' ) {	
	    					$oldImgs = DB::table('page_builder_images')->where('page_builder_id', '=', $pg->id)
	    					->where('insert_id', '=', $old_insertid)->get();
	    					if( !empty($oldImgs) && count($oldImgs) > 0 ) {
	    						$upArr1 = array();
	    						foreach($oldImgs as $oi) {
	    							$innArr1 = array();
	    							$innArr1['page_builder_id'] = $getPgID;
	    							$innArr1['insert_id'] = $new_insertid;
	    							$innArr1['img_id'] = $oi->img_id;
	    							$innArr1['img_title'] = $oi->img_title;
	    							$innArr1['img_alt'] = $oi->img_alt;
	    							$innArr1['img_caption'] = $oi->img_caption;
	    							$innArr1['img_desc'] = $oi->img_desc;
	    							array_push($upArr1, $innArr1);
	    						}

	    						DB::table('page_builder_images')->insert($upArr1);
	    					}
	    				}

	    				if( $pg->builder_type == 'VIDEO_GALLERY' ) {
	    					$oldVids = DB::table('page_builder_videos')->where('page_builder_id', '=', $pg->id)
	    					->where('insert_id', '=', $old_insertid)->get();
	    					if( !empty($oldVids) && count($oldVids) > 0 ) {
	    						$upArr2 = array();
	    						foreach($oldVids as $vd) {
	    							$innArr2 = array();
	    							$innArr2['page_builder_id'] = $getPgID;
	    							$innArr2['insert_id'] = $new_insertid;
	    							$innArr2['video_id'] = $vd->video_id;
	    							$innArr2['title'] = $vd->title;
	    							$innArr2['name'] = $vd->name;
	    							$innArr2['caption'] = $vd->caption;
	    							array_push($upArr2, $innArr2);
	    						}

	    						DB::table('page_builder_videos')->insert($upArr2);
	    					}	
	    				}

	    				if( $pg->builder_type == 'ACCORDION' ) {
	    					$oldAccr = DB::table('page_builder_accordion')->where('page_builder_id', '=', $pg->id)
	    					->where('insert_id', '=', $old_insertid)->get();
	    					if( !empty($oldAccr) && count($oldAccr) > 0 ) {
	    						$upArr3 = array();
	    						foreach($oldAccr as $ar) {
	    							$innArr3 = array();
	    							$innArr3['page_builder_id'] = $getPgID;
	    							$innArr3['insert_id'] = $new_insertid;
	    							$innArr3['heading'] = $ar->heading;
	    							$innArr3['content'] = $ar->content;
	    							array_push($upArr3, $innArr3);
	    						}

	    						DB::table('page_builder_accordion')->insert($upArr3);
	    					}
	    				}

	    				if (strpos($pg->builder_type, 'CONTENT_LINKS') !== false || $pg->builder_type == 'PRODUCT_LINKS' || $pg->builder_type == 'PRODUCT_CAT_LINKS' || $pg->builder_type == 'NEWS_LINKS' || $pg->builder_type == 'PEOPLE_LINKS' || $pg->builder_type == 'DISTRIBUTOR' || $pg->builder_type == 'DISTRIBUTOR_PAGE' || $pg->builder_type == 'CUSTOM_LINKS' || $pg->builder_type == 'PRODUCT_BOX') {

	    					$oldLks = DB::table('page_builder_links')->where('page_builder_id', '=', $pg->id)
	    					->where('insert_id', '=', $old_insertid)->get();
	    					
	    					if( !empty($oldLks) && count($oldLks) > 0 ) {
	    						$upArr4 = array();
	    						foreach($oldLks as $lk) {
	    							$innArr4 = array();
	    							$innArr4['page_builder_id'] = $getPgID;
	    							$innArr4['insert_id'] = $new_insertid;
	    							$innArr4['slug'] = $lk->slug;
	    							$innArr4['link_type'] = $lk->link_type;
	    							$innArr4['link_text'] = $lk->link_text;
	    							array_push($upArr4, $innArr4);
	    						}

	    						DB::table('page_builder_links')->insert($upArr4);
	    					}

	    				}
    				}
    			}
    		}
    	}
    }

}
