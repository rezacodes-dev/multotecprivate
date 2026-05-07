<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CmsLinks;
use App\Models\Media\Images;
use App\Models\Media\FilesMaster;
use App\Models\Article\Articles;
use App\Models\Article\ArticleCategories;
use App\Models\Article\ArticleCategoryImagesMap;
use App\Models\Article\ArticleCategoriesMap;
use App\Models\Article\ArticleImagesMap;
use App\Models\Article\ArticleFilesMap;
use App\Models\Languages;
use App\Models\PageBuilder\PageBuilder;
use App\Models\Media\MediaExtraContent;
use File;
use Image;
use Auth;
use DB;

class ArticleController extends Controller
{
    
    public function allCategories() {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'articleManagement';
    	$DataBag['childMenu'] = 'allArtCats';
    	$DataBag['allCats'] = ArticleCategories::where('status', '!=', '3')->where('parent_language_id', '=', '0')
        ->orderBy('created_at', 'desc')->get();
    	return view('dashboard.article.all_categories', $DataBag);
    }

    public function addCategory() {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'articleManagement';
    	$DataBag['childMenu'] = 'addArtCat';
    	$DataBag['allCats'] = ArticleCategories::where('status', '=', '1')->orderBy('name', 'asc')->get();
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
    	return view('dashboard.article.add_category', $DataBag);
    }

    public function saveCategory(Request $request) {

    	$ArticleCategories = new ArticleCategories;
    	$ArticleCategories->name = trim( ucfirst($request->input('name')) );
    	$ArticleCategories->slug = trim($request->input('slug'));
    	$ArticleCategories->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
    	$ArticleCategories->mob_page_content = trim( htmlentities($request->input('mob_page_content'), ENT_QUOTES) );
    	$ArticleCategories->parent_category_id = 0;
    	$ArticleCategories->created_by = Auth::user()->id;
        $ArticleCategories->language_id = trim( $request->input('language_id') );

        $ArticleCategories->image_title = trim($request->input('image_title'));
        $ArticleCategories->image_alt = trim($request->input('image_alt'));
        $ArticleCategories->image_caption = trim($request->input('image_caption'));


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

                $ArticleCategories->image_id = $Images->id;  
            }
        }

    	if( $ArticleCategories->save() ) {
    		
    		$article_category_id = $ArticleCategories->id;
    		
    		$CmsLinks = new CmsLinks;
    		$CmsLinks->table_id = $article_category_id;
    		$CmsLinks->slug_url = trim($request->input('slug'));
    		$CmsLinks->table_type = 'ARTICLE_CATEGORY';
    		$CmsLinks->save();

    		return back()->with('msg', 'Article Category Created Successfully.')
    		->with('msg_class', 'alert alert-success');
    	}
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }

    public function editCategory($art_cat_id) {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'articleManagement';
    	$DataBag['childMenu'] = 'addArtCat';
    	$DataBag['artCat'] = ArticleCategories::findOrFail($art_cat_id);
    	$DataBag['allCats'] = ArticleCategories::where('status', '=', '1')->orderBy('name', 'asc')->get();
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
    	return view('dashboard.article.add_category', $DataBag);
    }

    public function updateCategory(Request $request, $art_cat_id) {

    	$ArticleCategories = ArticleCategories::find($art_cat_id);
    	$ArticleCategories->name = trim( ucfirst($request->input('name')) );
    	$ArticleCategories->slug = trim($request->input('slug'));
    	$ArticleCategories->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
    	$ArticleCategories->mob_page_content = trim( htmlentities($request->input('mob_page_content'), ENT_QUOTES) );
    	$ArticleCategories->updated_by = Auth::user()->id;
        
        $ArticleCategories->image_title = trim($request->input('image_title'));
        $ArticleCategories->image_alt = trim($request->input('image_alt'));
        $ArticleCategories->image_caption = trim($request->input('image_caption'));


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

                $ArticleCategories->image_id = $Images->id;  
            }
        }

    	if( $ArticleCategories->save() ) {
    		
    		$article_category_id = $art_cat_id;
    		
    		CmsLinks::where('table_type', '=', 'ARTICLE_CATEGORY')->where('table_id', '=', $article_category_id)
    		->update([ 'slug_url' => trim($request->input('slug')) ]);

    		return back()->with('msg', 'Article Category Updated Successfully.')
    		->with('msg_class', 'alert alert-success');
    	}
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }

    public function deleteCategory($art_cat_id) {

    	$ck = ArticleCategories::find($art_cat_id);
    	if( isset($ck) && !empty($ck) ) {
    		$ck->status = '3';
    		if( $ck->save() ) {
                ArticleCategoryImagesMap::where('article_category_id', '=', $art_cat_id)->delete();
    			CmsLinks::where('table_type', '=', 'ARTICLE_CATEGORY')->where('table_id', '=', $art_cat_id)->delete();
                delete_navigation($art_cat_id, 'ARTICLE_CATEGORY');

    			return back()->with('msg', 'Article Category Deleted Successfully.')
    			->with('msg_class', 'alert alert-success');
    		}
    	}

    	return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function index() {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'articleManagement';
    	$DataBag['childMenu'] = 'allArticle';
        $DataBag['allArticles'] = Articles::where('status', '!=', '3')->where('parent_language_id', '=', '0')
        ->orderBy('created_at', 'desc')->get();
    	return view('dashboard.article.index', $DataBag);
    }

    public function create() {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'articleManagement';
    	$DataBag['childMenu'] = 'addArticle';
    	$DataBag['allArtCats'] = ArticleCategories::where('status', '=', '1')->orderBy('name', 'asc')->get();
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));
    	return view('dashboard.article.create', $DataBag);
    }

    public function edit($art_id) {
    	$DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['parentMenu'] = 'articleManagement';
    	$DataBag['childMenu'] = 'addArticle';
    	$DataBag['article'] = Articles::findOrFail($art_id);
        $DataBag['pageBuilderData'] = $DataBag['article'];
    	$DataBag['allArtCats'] = ArticleCategories::where('status', '=', '1')->orderBy('name', 'asc')->get();
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
    	return view('dashboard.article.create', $DataBag);
    }

    public function save(Request $request) {

    	
    	$imagesMap = array();
    	$categoriesMap = array();

        $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

    	$Articles = new Articles;
    	$Articles->name = trim( ucfirst($request->input('name')) );
        $Articles->insert_id = $insert_id;
    	$Articles->slug = trim($request->input('slug'));
    	$Articles->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
    	$Articles->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
    	//$Articles->status = trim($request->input('status'));
    	//$Articles->publish_status = trim($request->input('publish_status'));
        $Articles->publish_date = date('Y-m-d', strtotime( trim($request->input('publish_date') ) ));
    	$Articles->created_by = Auth::user()->id;
        $Articles->language_id = trim($request->input('language_id'));

        $Articles->meta_title = trim($request->input('meta_title'));
        $Articles->meta_desc = trim($request->input('meta_desc'));
        $Articles->meta_keyword = trim($request->input('meta_keyword'));
        $Articles->canonical_url = trim($request->input('canonical_url'));
        $Articles->lng_tag = trim($request->input('lng_tag'));
        $Articles->follow = trim($request->input('follow'));
        $Articles->index_tag = trim($request->input('index_tag'));
        $Articles->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );

        $Articles->display_order = trim($request->input('display_order'));

        $article_image_infos = json_decode( trim( $request->input('article_image_infos') ) );
        $thumb_image_infos = json_decode( trim( $request->input('thumb_image_infos') ) );

    
    	$resx = $Articles->save();
    	if( isset($resx) && $resx == 1 ) {

    		$article_id = $Articles->id;

    		$CmsLinks = new CmsLinks;
    		$CmsLinks->table_id = $article_id;
    		$CmsLinks->slug_url = trim($request->input('slug'));
    		$CmsLinks->table_type = 'ARTICLE';
    		$CmsLinks->save();
            $cms_link_id = $CmsLinks->id; // Need for page builder as parameter

            /** For Page Builder -- Insert Time **/
            /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
            update_page_builder($insert_id, $cms_link_id, $article_id, 'ARTICLE');
            /** End Page Builder **/

            if( !empty($article_image_infos) ) {
                foreach ($article_image_infos as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['article_id'] = $article_id;
                        $arr['image_id'] = $v->img_id;
                        $arr['title'] = $v->img_titl;
                        $arr['caption'] = $v->img_cap;
                        $arr['alt_tag'] = $v->img_alt;
                        $arr['description'] = $v->img_dsc;
                        $arr['image_type'] = "MAIN_IMAGE";
                        array_push( $imagesMap, $arr );
                    }
                }

                if( !empty($imagesMap) ) {
                    ArticleImagesMap::insert($imagesMap);
                }
            }

            if( !empty($thumb_image_infos) ) {
                foreach ($thumb_image_infos as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['article_id'] = $article_id;
                        $arr['image_id'] = $v->img_id;
                        $arr['title'] = $v->img_titl;
                        $arr['caption'] = $v->img_cap;
                        $arr['alt_tag'] = $v->img_alt;
                        $arr['description'] = $v->img_dsc;
                        $arr['image_type'] = "THUMB_IMAGE";
                        array_push( $imagesMap, $arr );
                    }
                }

                if( !empty($imagesMap) ) {
                    ArticleImagesMap::insert($imagesMap);
                }
            }

	    	if( $request->has('article_category_ids') ) {
	    		foreach( $request->input('article_category_ids') as $cats ) {
	    			$arr = array();
	    			$arr['article_id'] = $article_id;
	    			$arr['article_category_id'] = $cats;
	    			array_push( $categoriesMap, $arr );
	    		}
	    		if( !empty($categoriesMap) ) {
	    			ArticleCategoriesMap::insert( $categoriesMap );
	    		}
	    	}

	    return back()->with('msg', 'Article Created Successfully.')
    	->with('msg_class', 'alert alert-success');

    	}
    
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }

    public function update(Request $request, $art_id) {

    	$imagesMap = array();
    	$categoriesMap = array();

        $insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

    	$Articles = Articles::find($art_id);
    	$Articles->name = trim( ucfirst($request->input('name')) );
        $Articles->insert_id = $insert_id;
    	$Articles->slug = trim($request->input('slug'));
    	$Articles->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
    	$Articles->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
    	//$Articles->status = trim($request->input('status'));
    	//$Articles->publish_status = trim($request->input('publish_status'));
        $Articles->publish_date = date('Y-m-d', strtotime( trim($request->input('publish_date') ) ));
    	$Articles->updated_by = Auth::user()->id;

        $Articles->meta_title = trim($request->input('meta_title'));
        $Articles->meta_desc = trim($request->input('meta_desc'));
        $Articles->meta_keyword = trim($request->input('meta_keyword'));
        $Articles->canonical_url = trim($request->input('canonical_url'));
        $Articles->lng_tag = trim($request->input('lng_tag'));
        $Articles->follow = trim($request->input('follow'));
        $Articles->index_tag = trim($request->input('index_tag'));
        $Articles->json_markup = trim( htmlentities($request->input('json_markup'), ENT_QUOTES) );

        $Articles->display_order = trim($request->input('display_order'));

        $article_image_infos = json_decode( trim( $request->input('article_image_infos') ) );
        $thumb_image_infos = json_decode( trim( $request->input('thumb_image_infos') ) );

    	$resx = $Articles->save();
    	if( isset($resx) && $resx == 1 ) {

    		$article_id = $art_id;

    		CmsLinks::where('table_type', '=', 'ARTICLE')->where('table_id', '=', $article_id)
    		->update([ 'slug_url' => trim($request->input('slug')) ]);

            /** Need For Page Builder -- Update Time **/
            $cmsInfo = CmsLinks::where('table_id', '=', $article_id)->where('table_type', '=', 'ARTICLE')->first();

            if( !empty($cmsInfo) ) {
                
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                update_page_builder($insert_id, $cmsInfo->id, $article_id, 'ARTICLE');

            }
            /** End Page Builder **/

    		if( !empty($article_image_infos) ) {
                foreach ($article_image_infos as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['article_id'] = $article_id;
                        $arr['image_id'] = $v->img_id;
                        $arr['title'] = $v->img_titl;
                        $arr['caption'] = $v->img_cap;
                        $arr['alt_tag'] = $v->img_alt;
                        $arr['description'] = $v->img_dsc;
                        $arr['image_type'] = "MAIN_IMAGE";
                        array_push( $imagesMap, $arr );
                    }
                }

                if( !empty($imagesMap) ) {
                    ArticleImagesMap::insert($imagesMap);
                }
            }

            if( !empty($thumb_image_infos) ) {
                foreach ($thumb_image_infos as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['article_id'] = $article_id;
                        $arr['image_id'] = $v->img_id;
                        $arr['title'] = $v->img_titl;
                        $arr['caption'] = $v->img_cap;
                        $arr['alt_tag'] = $v->img_alt;
                        $arr['description'] = $v->img_dsc;
                        $arr['image_type'] = "THUMB_IMAGE";
                        array_push( $imagesMap, $arr );
                    }
                }

                if( !empty($imagesMap) ) {
                    ArticleImagesMap::insert($imagesMap);
                }
            }

	    	ArticleCategoriesMap::where('article_id', '=', $article_id)->delete();
	    	if( $request->has('article_category_ids') ) {
	    		foreach( $request->input('article_category_ids') as $cats ) {
	    			$arr = array();
	    			$arr['article_id'] = $article_id;
	    			$arr['article_category_id'] = $cats;
	    			array_push( $categoriesMap, $arr );
	    		}
	    		if( !empty($categoriesMap) ) {
	    			ArticleCategoriesMap::insert( $categoriesMap );
	    		}
	    	}

	    return back()->with('msg', 'Article Updated Successfully.')
    	->with('msg_class', 'alert alert-success');

    	}
    
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
    }

    public function delete($art_id) {

    	$ck = Articles::find($art_id);
    	if( isset($ck) && !empty($ck) ) {
    		$ck->status = '3';
    		if( $ck->save() ) {
    			
    			CmsLinks::where('table_type', '=', 'ARTICLE')->where('table_id', '=', $art_id)->delete();
    			ArticleCategoriesMap::where('article_id', '=', $art_id)->delete();
    			ArticleImagesMap::where('article_id', '=', $art_id)->delete();
                delete_navigation($art_id, 'ARTICLE');
    			//ArticleFilesMap::where('article_id', '=', $art_id)->delete();
                PageBuilder::where('table_id', '=', $art_id)->where('table_type', '=', 'ARTICLE')->delete();
    			
    			return back()->with('msg', 'Article Deleted Successfully.')
    			->with('msg_class', 'alert alert-success');
    		}
    	}

    	return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }












    /******************************** Language ****************************************/

    public function addEditCatLanguage( $parent_language_id, $child_language_id = '' ) {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'articleManagement';
        $DataBag['childMenu'] = 'addArtCat';
        $DataBag['parentLngCont'] = ArticleCategories::findOrFail($parent_language_id);
        if( $child_language_id != '' ) {
            $DataBag['artCat'] = ArticleCategories::findOrFail($child_language_id);
        }
        $DataBag['allCats'] = ArticleCategories::where('status', '=', '1')->orderBy('name', 'asc')->get();
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
        return view('dashboard.article.addedit_language_category', $DataBag);
    }

    public function addEditCatLanguagePost( Request $request, $parent_language_id, $child_language_id = '' ) {
         
         if( $child_language_id != '' && $child_language_id != null ) {

            $ArticleCategories = ArticleCategories::find($child_language_id);
            $ArticleCategories->name = trim( ucfirst($request->input('name')) );
            $ArticleCategories->slug = trim($request->input('slug'));
            $ArticleCategories->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $ArticleCategories->mob_page_content = trim( htmlentities($request->input('mob_page_content'), ENT_QUOTES) );
            $ArticleCategories->status = trim($request->input('status'));
            $ArticleCategories->parent_category_id = trim($request->input('parent_category_id'));
            $ArticleCategories->updated_by = Auth::user()->id;

            $bannerImageJson = json_decode( trim( $request->input('banner_image_infos') ) );            

            if( $ArticleCategories->save() ) {
                
                $article_category_id = $child_language_id;
                
                CmsLinks::where('table_type', '=', 'ARTICLE_CATEGORY')->where('table_id', '=', $article_category_id)
                ->update([ 'slug_url' => trim($request->input('slug')) ]);

                if( !empty($bannerImageJson) ) {
                    $imageMap = array();
                    foreach ($bannerImageJson as $v) {
                        if( $v->img_id != '' ) {
                            $arr = array();
                            $arr['article_category_id'] = $article_category_id;
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
                        ArticleCategoryImagesMap::insert($imageMap);
                    }
                }

                return back()->with('msg', 'Article Category Updated Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
         }

         if( $child_language_id == '' ) {

            $ArticleCategories = new ArticleCategories;
            $ArticleCategories->name = trim( ucfirst($request->input('name')) );
            $ArticleCategories->slug = trim($request->input('slug'));
            $ArticleCategories->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $ArticleCategories->mob_page_content = trim( htmlentities($request->input('mob_page_content'), ENT_QUOTES) );
            $ArticleCategories->status = trim($request->input('status'));
            $ArticleCategories->parent_category_id = trim($request->input('parent_category_id'));
            $ArticleCategories->created_by = Auth::user()->id;
            $ArticleCategories->language_id = trim( $request->input('language_id') );
            $ArticleCategories->parent_language_id = $parent_language_id;

            $bannerImageJson = json_decode( trim( $request->input('banner_image_infos') ) );   

            if( $ArticleCategories->save() ) {
                
                $article_category_id = $ArticleCategories->id;
                
                $CmsLinks = new CmsLinks;
                $CmsLinks->table_id = $article_category_id;
                $CmsLinks->slug_url = trim($request->input('slug'));
                $CmsLinks->table_type = 'ARTICLE_CATEGORY';
                $CmsLinks->save();

                if( !empty($bannerImageJson) ) {
                    $imageMap = array();
                    foreach ($bannerImageJson as $v) {
                        if( $v->img_id != '' ) {
                            $arr = array();
                            $arr['article_category_id'] = $article_category_id;
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
                        ArticleCategoryImagesMap::insert($imageMap);
                    }
                }

                return redirect()->route('edtArtCats', array('id' => $parent_language_id))
                ->with('msg', 'Article Category Created Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
         }

         return back();
    }

    public function deleteCatLanguage( $parent_language_id, $child_language_id ) {
       
        ArticleCategories::find($child_language_id)->delete();
        ArticleCategoryImagesMap::where('article_category_id', '=', $child_language_id)->delete();
        CmsLinks::where('table_type', '=', 'ARTICLE_CATEGORY')->where('table_id', '=', $child_language_id)->delete();
        delete_navigation($child_language_id, 'ARTICLE_CATEGORY');
        return redirect()->route('edtArtCats', array('id' => $parent_language_id))
        ->with('msg', 'Article Category Deleted Successfully.')
        ->with('msg_class', 'alert alert-success');
    }

    public function addEditLanguage( $parent_language_id, $child_language_id = '' ) {
        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'articleManagement';
        $DataBag['childMenu'] = 'addArticle';
        $DataBag['parentLngCont'] = Articles::findOrFail($parent_language_id);
        if( $child_language_id != '' ) {
            $DataBag['article'] = Articles::findOrFail($child_language_id);
            $DataBag['pageBuilderData'] = $DataBag['article'];
        }
        $DataBag['allArtCats'] = ArticleCategories::where('status', '=', '1')->orderBy('name', 'asc')->get();
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));
        return view('dashboard.article.addedit_language', $DataBag);
    }

    public function addEditLanguagePost( Request $request, $parent_language_id, $child_language_id = '' ) {
        
        if( $child_language_id != '' && $child_language_id != null ) {

            $imagesMap = array();
            $categoriesMap = array();

            $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

            $Articles = Articles::find($child_language_id);
            $Articles->name = trim( ucfirst($request->input('name')) );
            $Articles->insert_id = $insert_id;
            $Articles->slug = trim($request->input('slug'));
            $Articles->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
            $Articles->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            //$Articles->status = trim($request->input('status'));
            //$Articles->publish_status = trim($request->input('publish_status'));
            $Articles->updated_by = Auth::user()->id;
        
            $Articles->meta_title = trim($request->input('meta_title'));
            $Articles->meta_desc = trim($request->input('meta_desc'));
            $Articles->meta_keyword = trim($request->input('meta_keyword'));
            $Articles->canonical_url = trim($request->input('canonical_url'));
            $Articles->lng_tag = trim($request->input('lng_tag'));
            $Articles->follow = trim($request->input('follow'));
            $Articles->index_tag = trim($request->input('index_tag'));

            $article_image_infos = json_decode( trim( $request->input('article_image_infos') ) );


            $resx = $Articles->save();
            if( isset($resx) && $resx == 1 ) {

                $article_id = $child_language_id;

                CmsLinks::where('table_type', '=', 'ARTICLE')->where('table_id', '=', $article_id)
                ->update([ 'slug_url' => trim($request->input('slug')) ]);

                /** Need For Page Builder -- Update Time **/
                $cmsInfo = CmsLinks::where('table_id', '=', $article_id)->where('table_type', '=', 'ARTICLE')->first();

                if( !empty($cmsInfo) ) {
                    
                    /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                    update_page_builder($insert_id, $cmsInfo->id, $article_id, 'ARTICLE');

                }
                /** End Page Builder **/

                if( !empty($article_image_infos) ) {
                    foreach ($article_image_infos as $v) {
                        if( $v->img_id != '' ) {
                            $arr = array();
                            $arr['article_id'] = $article_id;
                            $arr['image_id'] = $v->img_id;
                            $arr['title'] = $v->img_titl;
                            $arr['caption'] = $v->img_cap;
                            $arr['alt_tag'] = $v->img_alt;
                            $arr['description'] = $v->img_dsc;
                            $arr['image_type'] = "MAIN_IMAGE";
                            array_push( $imagesMap, $arr );
                        }
                    }

                    if( !empty($imagesMap) ) {
                        ArticleImagesMap::insert($imagesMap);
                    }
                }

                ArticleCategoriesMap::where('article_id', '=', $article_id)->delete();
                if( $request->has('article_category_ids') ) {
                    foreach( $request->input('article_category_ids') as $cats ) {
                        $arr = array();
                        $arr['article_id'] = $article_id;
                        $arr['article_category_id'] = $cats;
                        array_push( $categoriesMap, $arr );
                    }
                    if( !empty($categoriesMap) ) {
                        ArticleCategoriesMap::insert( $categoriesMap );
                    }
                }

                return back()->with('msg', 'Article Updated Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
        }

        if( $child_language_id == '' ) {

            $imagesMap = array();
            $categoriesMap = array();

            $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

            $Articles = new Articles;
            $Articles->name = trim( ucfirst($request->input('name')) );
            $Articles->insert_id = $insert_id;
            $Articles->slug = trim($request->input('slug'));
            $Articles->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
            $Articles->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            //$Articles->status = trim($request->input('status'));
            //$Articles->publish_status = trim($request->input('publish_status'));
            $Articles->created_by = Auth::user()->id;
            $Articles->language_id = trim($request->input('language_id'));
            $Articles->parent_language_id = $parent_language_id;

            $Articles->meta_title = trim($request->input('meta_title'));
            $Articles->meta_desc = trim($request->input('meta_desc'));
            $Articles->meta_keyword = trim($request->input('meta_keyword'));
            $Articles->canonical_url = trim($request->input('canonical_url'));
            $Articles->lng_tag = trim($request->input('lng_tag'));
            $Articles->follow = trim($request->input('follow'));
            $Articles->index_tag = trim($request->input('index_tag'));

            $article_image_infos = json_decode( trim( $request->input('article_image_infos') ) );

            $resx = $Articles->save();
            if( isset($resx) && $resx == 1 ) {

                $article_id = $Articles->id;

                $CmsLinks = new CmsLinks;
                $CmsLinks->table_id = $article_id;
                $CmsLinks->slug_url = trim($request->input('slug'));
                $CmsLinks->table_type = 'ARTICLE';
                $CmsLinks->save();

                $cms_link_id = $CmsLinks->id; // Need for page builder as parameter

                /** For Page Builder -- Insert Time **/
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                update_page_builder($insert_id, $cms_link_id, $article_id, 'ARTICLE');
                /** End Page Builder **/

                if( !empty($article_image_infos) ) {
                    foreach ($article_image_infos as $v) {
                        if( $v->img_id != '' ) {
                            $arr = array();
                            $arr['article_id'] = $article_id;
                            $arr['image_id'] = $v->img_id;
                            $arr['title'] = $v->img_titl;
                            $arr['caption'] = $v->img_cap;
                            $arr['alt_tag'] = $v->img_alt;
                            $arr['description'] = $v->img_dsc;
                            $arr['image_type'] = "MAIN_IMAGE";
                            array_push( $imagesMap, $arr );
                        }
                    }

                    if( !empty($imagesMap) ) {
                        ArticleImagesMap::insert($imagesMap);
                    }
                }

                if( $request->has('article_category_ids') ) {
                    foreach( $request->input('article_category_ids') as $cats ) {
                        $arr = array();
                        $arr['article_id'] = $article_id;
                        $arr['article_category_id'] = $cats;
                        array_push( $categoriesMap, $arr );
                    }
                    if( !empty($categoriesMap) ) {
                        ArticleCategoriesMap::insert( $categoriesMap );
                    }
                }

                return redirect()->route('edtArt', array('id' => $parent_language_id))
                ->with('msg', 'Article Created Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
        }

        return back();
    }

    public function deleteLanguage( $parent_language_id, $child_language_id ) {
        
        Articles::find($child_language_id)->delete();
        CmsLinks::where('table_type', '=', 'ARTICLE')->where('table_id', '=', $child_language_id)->delete();
        ArticleCategoriesMap::where('article_id', '=', $child_language_id)->delete();
        ArticleImagesMap::where('article_id', '=', $child_language_id)->delete();
        //ArticleFilesMap::where('article_id', '=', $child_language_id)->delete();
        delete_navigation($child_language_id, 'ARTICLE');
        PageBuilder::where('table_id', '=', $child_language_id)->where('table_type', '=', 'ARTICLE')->delete();
        return redirect()->route('edtArt', array('id' => $parent_language_id))
        ->with('msg', 'Article Created Successfully.')
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
                        $Articles = Articles::find($id);
                        $Articles->status = '1';
                        $Articles->save();
                    }
                    $msg = 'Articles Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $Articles = Articles::find($id);
                        $Articles->status = '2';
                        $Articles->save();
                    }
                    $msg = 'Articles Deactivated Succesfully.';
                    break;

                case 'delete':
                    foreach($idsArr as $id) {
                        $Articles = Articles::find($id);
                        $Articles->status = '3';
                        $Articles->save();
                        CmsLinks::where('table_type', '=', 'ARTICLE')->where('table_id', '=', $id)->delete();
                        ArticleCategoriesMap::where('article_id', '=', $id)->delete();
                        ArticleImagesMap::where('article_id', '=', $id)->delete();
                        //ArticleFilesMap::where('article_id', '=', $id)->delete();

                        delete_navigation($id, 'ARTICLE');
                        PageBuilder::where('table_id', '=', $id)->where('table_type', '=', 'ARTICLE')->delete();
                    }
                    $msg = 'Articles Deleted Succesfully.';
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
                        $ArticleCategories = ArticleCategories::find($id);
                        $ArticleCategories->status = '1';
                        $ArticleCategories->save();
                    }
                    $msg = 'Article Categories Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $ArticleCategories = ArticleCategories::find($id);
                        $ArticleCategories->status = '2';
                        $ArticleCategories->save();
                    }
                    $msg = 'Article Categories Deactivated Succesfully.';
                    break;

                case 'delete':
                    foreach($idsArr as $id) {
                        $ArticleCategories = ArticleCategories::find($id);
                        $ArticleCategories->status = '3';
                        $ArticleCategories->save();
                        ArticleCategoryImagesMap::where('article_category_id', '=', $id)->delete();
                        CmsLinks::where('table_type', '=', 'ARTICLE_CATEGORY')->where('table_id', '=', $id)->delete();
                        delete_navigation($id, 'ARTICLE_CATEGORY');
                    }
                    $msg = 'Article Categories Deleted Succesfully.';
                    break;
            }
            return back()->with('msg', $msg)->with('msg_class', 'alert alert-success');
        }
        return back();
    }


    public function extraConten() {

        $DataBag = array();
        $DataBag['GparentMenu'] = 'contentManagement';
        $DataBag['parentMenu'] = 'articleManagement';
        $DataBag['childMenu'] = 'artExCont';
        $DataBag['extraCont'] = MediaExtraContent::where('type', '=', 'ARTICLE')->first();
        return view('dashboard.article.extra_content', $DataBag);
    }

    public function extraContentSave(Request $request) {
        
        $MediaExtraContent = MediaExtraContent::where('type', '=', 'ARTICLE')->first();

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
            $MediaExtraContent->type = 'ARTICLE';

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
