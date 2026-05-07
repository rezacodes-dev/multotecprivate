<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media\FilesMaster;
use App\Models\Media\Images;
use App\Models\CmsLinks;
use App\Models\Languages;
use App\Models\ReusableContent;

use App\Models\FrmBuilder\FrmMaster;

use App\Models\PageBuilder\PageBuilder;
use App\Models\PageBuilder\PageBuilderImages;
use App\Models\PageBuilder\PageBuilderFiles;
use App\Models\PageBuilder\PageBuilderVideos;
use App\Models\PageBuilder\PageBuilderLinks;
use App\Models\PageBuilder\PageBuilderAccordion;

use Auth;
use Image;

class PageBuilderController extends Controller
{
    
	public function addEdit( Request $request ) {

		$jasonArr = array();
		$msg = '';
        $device = '';

    	$insert_id = trim( $request->input('insert_id') );
    	$builder_type = trim( $request->input('builder_type') );

        
    	$this_id = trim( $request->input('this_id') );
    	$main_content = trim( htmlentities($request->input('main_content'), ENT_QUOTES) );
    	$sub_content = trim( htmlentities($request->input('sub_content'), ENT_QUOTES) );
    	$main_title = trim( htmlentities($request->input('main_title'), ENT_QUOTES) );
    	$sub_title = trim( htmlentities($request->input('sub_title'), ENT_QUOTES) );
    	$link_text = trim( $request->input('link_text') );
    	$link_url = trim( $request->input('link_url') );
        $isDevice = trim( $request->input('device') );

        if( $isDevice == '1' ) {
            $device = '<i class="fa fa-2x fa-desktop base-green" aria-hidden="true"></i>';
        }
        if( $isDevice == '2' ) {
            $device = '<i class="fa fa-2x fa-mobile base-red" aria-hidden="true"></i>';
        }
        if( $isDevice == '3' ) {
            $device = '<i class="fa fa-2x fa-desktop base-green" aria-hidden="true"></i> <i class="fa fa-2x fa-mobile base-red" aria-hidden="true"></i>';
        }

    	if( $builder_type == 'EXTRA_SEO' ) {
    		$msg = 'Extra SEO Content ';
    	}

    	if( $builder_type == 'EXTRA_CONT' ) {
    		$msg = 'Extra Content ';
    	}

    	if( $builder_type == 'CTA' ) {
    		$msg = 'CTA Content ';
    	}

    	if( $builder_type == 'HERO_SPW' ) {
    		$msg = 'Page Width Hero Statement ';
    	}

    	if( $builder_type == 'HERO_SCW' ) {
    		$msg = 'Container Width Hero Statement ';
    	}

    	if( $builder_type == 'STICKY_BUTT' ) {
    		$msg = 'Sticky Button Content ';
    	}

        if( $builder_type == 'EFORM' ) {
            $msg = 'Form Content ';
        }

        if( $builder_type == 'IMAGE_CAROUSEL' ) {
            $msg = 'Image Carousel ';
        }

        if( $builder_type == 'IMAGE_GALLERY' ) {
            $msg = 'Image Gallery ';
        }

        if( $builder_type == 'BROCHURE_BUTT' ) {
            $msg = 'Brochure Download Button ';
        }

        if( $builder_type == 'TECHRES_BUTT' ) {
            $msg = 'Technical Resource Button ';
        }

        if( $builder_type == 'IMAGEGAL_BUTT' ) {
            $msg = 'Image Gallery Button ';
        }

        if( $builder_type == 'VIDEO_GALLERY' ) {
            $msg = 'Video Gallery ';
        }

        if( $builder_type == 'PRODUCT_LINKS' ) {
            $msg = 'Product Links ';
        }

        if( $builder_type == 'PRODUCT_CAT_LINKS' ) {
            $msg = 'Product Category Links ';
        }

        if( $builder_type == 'PRODUCT_BOX' ) {
            $msg = 'Product Box ';
        }

        if( $builder_type == 'NEWS_LINKS' ) {
            $msg = 'News Links ';
        }

        if( $builder_type == 'PEOPLE_LINKS' ) {
            $msg = 'Peoples Links ';
        }

        if( $builder_type == 'CUSTOM_LINKS' ) {
            $msg = 'Custom Links ';
        }

        if( $builder_type == 'METRIC' ) {
            $msg = $sub_content;
        }

        if( $builder_type == 'ACCORDION' ) {
            $msg = 'Accordion';   
        }

        if( $builder_type == 'REUSE' ) {
            $msg = 'Resuable Content';   
        }

        if( $builder_type == 'PRODUCT_CAT_BOX' ) {
            $msg = 'Product Category Content';   
        }

    
        if (strpos($builder_type, 'CONTENT_LINKS') !== false) {
            $msg = 'Content Links';  
        }

        $msg = $device .' '. $msg;

    	if( $this_id != '' && $this_id != '0' ) { // Edit Time

    		$PageBuilder = PageBuilder::find( $this_id );

            $page_builder_id = $this_id;

    		$PageBuilder->main_content = $main_content;
    		$PageBuilder->sub_content = $sub_content;
    		$PageBuilder->main_title = $main_title;
    		$PageBuilder->sub_title = $sub_title;
    		$PageBuilder->link_text = $link_text;
    		$PageBuilder->link_url = $link_url;
            $PageBuilder->device = $isDevice;

    		if( $PageBuilder->save() ) {
    			$jasonArr['success'] = 'success';
    			$jasonArr['msg'] = $msg . ' Updated Successfully.';
    			$jasonArr['action_status'] = 'update';
    			$jasonArr['insert_id'] = $insert_id;
    			$jasonArr['this_id'] = $this_id;
                $jasonArr['builder_type'] = $builder_type;
    		}

    	} else {

            $edtTime = PageBuilder::where('insert_id', '=', $insert_id)
            ->where('cms_link_id', '!=', '0')->where('table_id', '!=', '0')
            ->where('table_type', '!=', '')->first(); // Edit Page Insert
            
            if( !empty($edtTime) ) {
                
                $cms_link_id = $edtTime->cms_link_id;
                $table_id = $edtTime->table_id;
                $table_type = $edtTime->table_type;
                
                $PageBuilder = new PageBuilder;

                $PageBuilder->insert_id = $insert_id;
                $PageBuilder->builder_type = $builder_type;
                $PageBuilder->main_content = $main_content;
                $PageBuilder->sub_content = $sub_content;
                $PageBuilder->main_title = $main_title;
                $PageBuilder->sub_title = $sub_title;
                $PageBuilder->link_text = $link_text;
                $PageBuilder->link_url = $link_url;
                $PageBuilder->cms_link_id = $cms_link_id;
                $PageBuilder->table_id = $table_id;
                $PageBuilder->table_type = $table_type;
                $PageBuilder->device = $isDevice;

                if( $PageBuilder->save() ) {

                    $page_builder_id = $PageBuilder->id;
                    
                    $jasonArr['success'] = 'success';
                    $jasonArr['msg'] = $msg . ' Added Successfully.';
                    $jasonArr['action_status'] = 'insert';
                    $jasonArr['insert_id'] = $insert_id;
                    $jasonArr['this_id'] = $PageBuilder->id;
                    $jasonArr['builder_type'] = $builder_type;
                }
            } else { // Insert Time
        		$PageBuilder = new PageBuilder;

        		$PageBuilder->insert_id = $insert_id;
        		$PageBuilder->builder_type = $builder_type;
        		$PageBuilder->main_content = $main_content;
        		$PageBuilder->sub_content = $sub_content;
        		$PageBuilder->main_title = $main_title;
        		$PageBuilder->sub_title = $sub_title;
        		$PageBuilder->link_text = $link_text;
        		$PageBuilder->link_url = $link_url;
                $PageBuilder->device = $isDevice;

        		if( $PageBuilder->save() ) {

                    $page_builder_id = $PageBuilder->id;
    	    		
                    $jasonArr['success'] = 'success';
        			$jasonArr['msg'] = $msg . ' Added Successfully.';
        			$jasonArr['action_status'] = 'insert';
        			$jasonArr['insert_id'] = $insert_id;
        			$jasonArr['this_id'] = $PageBuilder->id;
                    $jasonArr['builder_type'] = $builder_type;
    	    	}
            }
    	}

        if( $request->has('carousel_images') && isset( $page_builder_id ) && $page_builder_id != '0' ) {

            $insertArr = array();
            $imgArr = json_decode( trim( $request->input('carousel_images') ) );
            if( !empty( $imgArr ) ) {
                foreach( $imgArr as $k => $v ) {
                    $arr = array();
                    $arr['page_builder_id'] = $page_builder_id;
                    $arr['insert_id'] = $insert_id;
                    $arr['img_id'] = $k;
                    $arr['img_title'] = $v->img_titl;
                    $arr['img_alt'] = $v->img_alt;
                    $arr['img_caption'] = $v->img_cap;
                    $arr['img_desc'] = $v->img_dsc;
                    array_push( $insertArr, $arr);
                }

                PageBuilderImages::insert( $insertArr );
            }
        }

        if( $request->has('brochures') && isset( $page_builder_id ) && $page_builder_id != '0' ) {

            $insertArr = array();
            $filArr = json_decode( trim( $request->input('brochures') ) );
            if( !empty( $filArr ) ) {
                foreach( $filArr as $k => $v ) {
                    $arr = array();
                    $arr['page_builder_id'] = $page_builder_id;
                    $arr['insert_id'] = $insert_id;
                    $arr['file_id'] = $k;
                    $arr['name'] = $v->brochure_name;
                    $arr['title'] = $v->brochure_title;
                    $arr['caption'] = $v->brochure_caption;
                    $arr['details'] = $v->brochure_desc;
                    array_push( $insertArr, $arr);
                }

                PageBuilderFiles::insert( $insertArr );
            }
        }

        if( $request->has('slugs') && isset( $page_builder_id ) && $page_builder_id != '0' ) {
            PageBuilderLinks::where('page_builder_id', '=', $page_builder_id)->delete();
            $link_orderArr = $request->input('link_order');
            $insertArr = array();
            $i = 0;
            foreach( $request->input('slugs') as $slg ) {
                $arr = array();
                $arr['page_builder_id'] = $page_builder_id;
                $arr['insert_id'] = $insert_id;
                $arr['slug'] = $slg;
                $arr['link_order'] = 0;
                if( $i < count($link_orderArr) ) {
                    if($link_orderArr[$i] != '') {
                        $arr['link_order'] = $link_orderArr[$i];
                    }
                } 
                array_push( $insertArr, $arr);
                $i++;
            }

            PageBuilderLinks::insert( $insertArr );
        }


        if( $request->has('custom_link_text') && isset( $page_builder_id ) && $page_builder_id != '0' ) {
            PageBuilderLinks::where('page_builder_id', '=', $page_builder_id)->delete();
            $inputTextArr = $request->input('custom_link_text');
            $inputLinkArr = $request->input('custom_link_slug');
            $insertArr = array();
            for( $i = 0; $i < count($inputTextArr);  $i++ ) {
                if( $inputTextArr[ $i ] != '' && $inputLinkArr[ $i ] != '' ) {
                    $arr = array();
                    $arr['page_builder_id'] = $page_builder_id;
                    $arr['insert_id'] = $insert_id;
                    $arr['slug'] = $inputLinkArr[ $i ];
                    $arr['link_text'] = $inputTextArr[ $i ];
                    $arr['link_type'] = 'CUSTOM_LINKS';
                    array_push( $insertArr, $arr);
                }
            }

            if( !empty($insertArr) ) {
                PageBuilderLinks::insert( $insertArr );
            }
        }


        if( $request->has('accordion_heading') && isset( $page_builder_id ) && $page_builder_id != '0' ) {
            PageBuilderAccordion::where('page_builder_id', '=', $page_builder_id)->delete();
            $headingArr = $request->input('accordion_heading');
            $contentArr = $request->input('accordion_body_content');
            $insertArr = array();
            for( $i = 0; $i < count($headingArr);  $i++ ) {
                if( $headingArr[ $i ] != '' && $contentArr[ $i ] != '' ) {
                    $arr = array();
                    $arr['page_builder_id'] = $page_builder_id;
                    $arr['insert_id'] = $insert_id;
                    $arr['heading'] = $headingArr[ $i ];
                    $arr['content'] = htmlentities( $contentArr[ $i ] , ENT_QUOTES );
                    array_push( $insertArr, $arr);
                }
            }

            if( !empty($insertArr) ) {
                PageBuilderAccordion::insert( $insertArr );
            }
        }

        if( $request->has('videos') && isset( $page_builder_id ) && $page_builder_id != '0' ) {

            $insertArr = array();
            $vidArr = json_decode( trim( $request->input('videos') ) );
            if( !empty( $vidArr ) ) {
                foreach( $vidArr as $k => $v ) {
                    $arr = array();
                    $arr['page_builder_id'] = $page_builder_id;
                    $arr['insert_id'] = $insert_id;
                    $arr['video_id'] = $k;
                    $arr['name'] = $v->vidName;
                    $arr['title'] = $v->vidTitle;
                    $arr['caption'] = $v->vidCaption;
                    array_push( $insertArr, $arr);
                }

                PageBuilderVideos::insert( $insertArr );
            }
        }

    	return json_encode( $jasonArr );
	}

    /** Need more delete code **/
	public function delete(Request $request) {

		$r = PageBuilder::where('id', '=', trim( $request->input('id') ) )
		->where('builder_type', '=', trim( $request->input('builder_type') ) )->delete();
		if( isset( $r ) ) {

            PageBuilderImages::where('page_builder_id', '=', trim( $request->input('id') ) )->delete();
            PageBuilderFiles::where('page_builder_id', '=', trim( $request->input('id') ) )->delete();
            PageBuilderLinks::where('page_builder_id', '=', trim( $request->input('id') ) )->delete();
            PageBuilderVideos::where('page_builder_id', '=', trim( $request->input('id') ) )->delete();
            PageBuilderAccordion::where('page_builder_id', '=', trim( $request->input('id') ) )->delete();
			return 'success';
		}

		return 'fail';
	}

	public function getContent(Request $request) {

		$r = PageBuilder::where('id', '=', trim( $request->input('id') ) )
		->where('builder_type', '=', trim( $request->input('builder_type') ) )->first();

		$jsonArr = array();
        $jsonArr['SeleSubCats'] = array();
        $jsonArr['carasoul_images'] = array();
        $jsonArr['video_data'] = array();
        $jsonArr['pboxReus'] = array();
        $jsonArr['all_links'] = array();
        $jsonArr['accr_data'] = array();


		if( !empty($r) ) {
			
			$jsonArr['id'] = $r->id;
			$jsonArr['insert_id'] = $r->insert_id;
			$jsonArr['builder_type'] = $r->builder_type;
			$jsonArr['main_content'] = $main_content = html_entity_decode( $r->main_content, ENT_QUOTES );
			$jsonArr['sub_content'] = $sub_content = html_entity_decode( $r->sub_content, ENT_QUOTES );
			$jsonArr['main_title'] = $r->main_title;
			$jsonArr['sub_title'] = $r->sub_title;
			$jsonArr['link_text'] = $r->link_text;
			$jsonArr['link_url'] = $r->link_url;
            $jsonArr['device'] = $r->device;

            if( $request->input('builder_type') == 'IMAGE_CAROUSEL' ) {
                
                $imgArr = array();
                $imgData = PageBuilderImages::with('masterImageInfo')->where('page_builder_id', '=', trim( $request->input('id') ) )->get();
                if( !empty($imgData) ) {
                    foreach($imgData as $img) {
                        if( isset( $img->masterImageInfo ) ) {
                            $arr = array();
                            $arr['img_id'] = $img->img_id;
                            $arr['img_title'] = $img->img_title;
                            $arr['img_alt'] = $img->img_alt;
                            $arr['img_caption'] = $img->img_caption;
                            $arr['img_desc'] = $img->img_desc;
                            $arr['image'] = asset( 'public/uploads/files/media_images/thumb/' . $img->masterImageInfo->image );
                            array_push($imgArr, $arr);
                        }
                    }
                }

                $jsonArr['carasoul_images'] = $imgArr;
            }

            if( $request->input('builder_type') == 'IMAGE_GALLERY' ) {
                
                $imgArr = array();
                $imgData = PageBuilderImages::with('masterImageInfo')->where('page_builder_id', '=', trim( $request->input('id') ) )->get();
                if( !empty($imgData) ) {
                    foreach($imgData as $img) {
                        if( isset( $img->masterImageInfo ) ) {
                            $arr = array();
                            $arr['img_id'] = $img->img_id;
                            $arr['img_title'] = $img->img_title;
                            $arr['img_alt'] = $img->img_alt;
                            $arr['img_caption'] = $img->img_caption;
                            $arr['img_desc'] = $img->img_desc;
                            $arr['image'] = asset( 'public/uploads/files/media_images/thumb/' . $img->masterImageInfo->image );
                            array_push($imgArr, $arr);
                        }
                    }
                }

                $jsonArr['carasoul_images'] = $imgArr;
            }

            if( $request->input('builder_type') == 'IMAGEGAL_BUTT' ) {
                
                $seleScat = array();
                $findFcId = \App\Models\Media\ImageCategories::where('slug', '=', $main_content)->first();
                if( !empty($findFcId) ) {
                    $findChilds = \App\Models\Media\ImageCategories::where('parent_category_id', '=', $findFcId->id)
                    ->where('parent_category_id', '!=', '0')->where('status', '=', '1')->get();

                    if( !empty($findChilds) ) {
                       foreach( $findChilds as $c ) {
                        $arr = array();
                        $arr['name'] = $c->name;
                        $arr['slug'] = $c->slug;
                        array_push($seleScat, $arr);
                       }
                    }

                    $jsonArr['SeleSubCats'] = $seleScat;
                }
                /*$imgArr = array();
                $imgData = PageBuilderImages::with('masterImageInfo')->where('page_builder_id', '=', trim( $request->input('id') ) )->get();
                if( !empty($imgData) ) {
                    foreach($imgData as $img) {
                        if( isset( $img->masterImageInfo ) ) {
                            $arr = array();
                            $arr['img_id'] = $img->img_id;
                            $arr['img_title'] = $img->img_title;
                            $arr['img_alt'] = $img->img_alt;
                            $arr['img_caption'] = $img->img_caption;
                            $arr['img_desc'] = $img->img_desc;
                            $arr['image'] = asset( 'public/uploads/files/media_images/thumb/' . $img->masterImageInfo->image );
                            array_push($imgArr, $arr);
                        }
                    }
                }

                $jsonArr['carasoul_images'] = $imgArr;*/
            }

            if( $request->input('builder_type') == 'BROCHURE_BUTT'  && $main_content != '' ) {
                
                $seleScat = array();
                $findFcId = \App\Models\Media\FileCategories::where('slug', '=', $main_content)->first();
                if( !empty($findFcId) ) {
                    $findChilds = \App\Models\Media\FileCategories::where('parent_category_id', '=', $findFcId->id)
                    ->where('parent_category_id', '!=', '0')->where('status', '=', '1')->get();

                    if( !empty($findChilds) ) {
                       foreach( $findChilds as $c ) {
                        $arr = array();
                        $arr['name'] = $c->name;
                        $arr['slug'] = $c->slug;
                        array_push($seleScat, $arr);
                       }
                    }

                    $jsonArr['SeleSubCats'] = $seleScat;
                }

                /*$fileArr = array();
                $fileData = PageBuilderFiles::with('masterFileInfo')->where('page_builder_id', '=', trim( $request->input('id') ) )->get();
                if( !empty($fileData) ) {
                    foreach($fileData as $fl) {
                        if( isset( $fl->masterFileInfo ) ) {
                            $arr = array();
                            $arr['file_id'] = $fl->file_id;
                            $arr['file_size'] = $fl->masterFileInfo->size;
                            $arr['file_ext'] = $fl->masterFileInfo->extension;
                            $arr['file_name'] = $fl->name;
                            $arr['file_title'] = $fl->title;
                            $arr['file_caption'] = $fl->caption;
                            $arr['file_desc'] = $fl->details;
                            array_push($fileArr, $arr);
                        }
                    }
                }

                $jsonArr['brochure_data'] = $fileArr;*/
            }

            /*if( $request->input('builder_type') == 'TECHRES_BUTT' ) {
                
                $fileArr = array();
                $fileData = PageBuilderFiles::with('masterFileInfo')->where('page_builder_id', '=', trim( $request->input('id') ) )->get();
                if( !empty($fileData) ) {
                    foreach($fileData as $fl) {
                        if( isset( $fl->masterFileInfo ) ) {
                            $arr = array();
                            $arr['file_id'] = $fl->file_id;
                            $arr['file_size'] = $fl->masterFileInfo->size;
                            $arr['file_ext'] = $fl->masterFileInfo->extension;
                            $arr['file_name'] = $fl->name;
                            $arr['file_title'] = $fl->title;
                            $arr['file_caption'] = $fl->caption;
                            $arr['file_desc'] = $fl->details;
                            array_push($fileArr, $arr);
                        }
                    }
                }

                $jsonArr['brochure_data'] = $fileArr;
            }*/

            if( $request->input('builder_type') == 'VIDEO_GALLERY' ) {
                
                $vidArr = array();
                $vidData = PageBuilderVideos::with('masterVideoInfo')->where('page_builder_id', '=', trim( $request->input('id') ) )->get();
                if( !empty($vidData) ) {
                    foreach($vidData as $vd) {
                        if( isset( $vd->masterVideoInfo ) ) {
                            $arr = array();
                            $arr['video_id'] = $vd->video_id;
                            $arr['video_name'] = $vd->name;
                            $arr['video_title'] = $vd->title;
                            $arr['video_caption'] = $vd->caption;
                            $arr['video_type'] = $vd->masterVideoInfo->video_type;
                            $arr['video_link'] = $vd->masterVideoInfo->video_link;
                            $arr['video_script'] = $vd->masterVideoInfo->video_script;
                            array_push($vidArr, $arr);
                        }
                    }
                }

                $jsonArr['video_data'] = $vidArr;
            }

            if( $request->input('builder_type') == 'PRODUCT_LINKS' || $request->input('builder_type') == 'PRODUCT_BOX' ) {
                
                $linkArr = array();
                $pboxReus = array();

                $masterData = \App\Models\Product\Products::where('status', '=', '1')
                ->where('name', '!=', '')->where('slug', '!=', '')->orderBy('id', 'desc')->get();
                if( !empty($masterData) ) {
                    foreach($masterData as $lnk) {
                        $arr = array();
                        $arr['id'] = $lnk->id;
                        $arr['slug'] = $lnk->slug;
                        $arr['display_slug'] = url( $lnk->slug );
                        $arr['name'] = $lnk->name;
                        $arr['order'] = getLinkOrder( trim( $request->input('id') ) , trim( $lnk->slug ) );
                        $isLinkSelected = isLinkSelected( trim( $request->input('id') ) , trim( $lnk->slug ) );

                        if( $isLinkSelected != '' && $isLinkSelected == 'SELECTED' ) {
                            $arr['isSelected'] = 'YES';
                        } else {
                            $arr['isSelected'] = 'NO';
                        }

                        array_push($linkArr, $arr);
                    }
                }

                $jsonArr['all_links'] = $linkArr;

                $pboxReus = \App\Models\PboxReusableContent::where('column_key', '=', $r->link_url)->get();
                $jsonArr['pboxReus'] = $pboxReus;
            }


            if( $request->input('builder_type') == 'PRODUCT_CAT_LINKS' || $request->input('builder_type') == 'PRODUCT_CAT_BOX' ) {
                
                $linkArr = array();
                $pboxReus = array();

                $masterData = \App\Models\Product\ProductCategories::where('status', '=', '1')
                ->where('name', '!=', '')->where('slug', '!=', '')->orderBy('id', 'desc')->get();
                if( !empty($masterData) ) {
                    foreach($masterData as $lnk) {
                        $arr = array();
                        $arr['id'] = $lnk->id;
                        $arr['slug'] = $lnk->slug;
                        $arr['display_slug'] = url( $lnk->slug );
                        $arr['name'] = $lnk->name;
                        $arr['order'] = getLinkOrder( trim( $request->input('id') ) , trim( $lnk->slug ) );
                        $isLinkSelected = isLinkSelected( trim( $request->input('id') ) , trim( $lnk->slug ) );

                        if( $isLinkSelected != '' && $isLinkSelected == 'SELECTED' ) {
                            $arr['isSelected'] = 'YES';
                        } else {
                            $arr['isSelected'] = 'NO';
                        }

                        array_push($linkArr, $arr);
                    }
                }

                $jsonArr['all_links'] = $linkArr;

                $pboxReus = \App\Models\PboxReusableContent::where('column_key', '=', $r->link_url)->get();
                $jsonArr['pboxReus'] = $pboxReus;
            }


            if( strpos($request->input('builder_type'), 'CONTENT_LINKS') !== false ) {
                
                $linkArr = array();
                $pboxReus = array();
                
                $expArr = explode('-', trim($request->input('builder_type')));
                
                $ctyId = 0;
                if( !empty($expArr) ) {
                    $ctyId = end($expArr);
                }

                $masterData = \App\Models\Content\Contents::where('status', '=', '1')->where('content_type_id', '=', $ctyId)
                ->where('name', '!=', '')->where('slug', '!=', '')->orderBy('id', 'desc')->get();
                if( !empty($masterData) ) {
                    foreach($masterData as $lnk) {
                        $arr = array();
                        $arr['id'] = $lnk->id;
                        $arr['slug'] = $lnk->slug;
                        $arr['display_slug'] = url( $lnk->slug );
                        $arr['name'] = $lnk->name;
                        $arr['order'] = getLinkOrder( trim( $request->input('id') ) , trim( $lnk->slug ) );
                        $isLinkSelected = isLinkSelected( trim( $request->input('id') ) , trim( $lnk->slug ) );

                        if( $isLinkSelected != '' && $isLinkSelected == 'SELECTED' ) {
                            $arr['isSelected'] = 'YES';
                        } else {
                            $arr['isSelected'] = 'NO';
                        }

                        array_push($linkArr, $arr);
                    }
                }

                $jsonArr['all_links'] = $linkArr;
            }

            if( $request->input('builder_type') == 'NEWS_LINKS' ) {
                
                $linkArr = array();
                $masterData = \App\Models\Article\Articles::where('status', '=', '1')
                ->where('name', '!=', '')->where('slug', '!=', '')->orderBy('id', 'desc')->get();
                if( !empty($masterData) ) {
                    foreach($masterData as $lnk) {
                        $arr = array();
                        $arr['id'] = $lnk->id;
                        $arr['slug'] = $lnk->slug;
                        $arr['display_slug'] = url( $lnk->slug );
                        $arr['name'] = $lnk->name;
                        $arr['order'] = getLinkOrder( trim( $request->input('id') ) , trim( $lnk->slug ) );
                        $isLinkSelected = isLinkSelected( trim( $request->input('id') ) , trim( $lnk->slug ) );

                        if( $isLinkSelected != '' && $isLinkSelected == 'SELECTED' ) {
                            $arr['isSelected'] = 'YES';
                        } else {
                            $arr['isSelected'] = 'NO';
                        }

                        array_push($linkArr, $arr);
                    }
                }

                $jsonArr['all_links'] = $linkArr;
            }

            if( $request->input('builder_type') == 'PEOPLE_LINKS' ) {
                
                $linkArr = array();
                $masterData = \App\Models\PeoplesProfile\PeoplesProfile::where('status', '=', '1')
                ->where('name', '!=', '')->where('slug', '!=', '')->orderBy('id', 'desc')->get();
                if( !empty($masterData) ) {
                    foreach($masterData as $lnk) {
                        $arr = array();
                        $arr['id'] = $lnk->id;
                        $arr['slug'] = $lnk->slug;
                        $arr['display_slug'] = url( $lnk->slug );
                        $arr['name'] = $lnk->name;
                        $arr['order'] = getLinkOrder( trim( $request->input('id') ) , trim( $lnk->slug ) );
                        $isLinkSelected = isLinkSelected( trim( $request->input('id') ) , trim( $lnk->slug ) );

                        if( $isLinkSelected != '' && $isLinkSelected == 'SELECTED' ) {
                            $arr['isSelected'] = 'YES';
                        } else {
                            $arr['isSelected'] = 'NO';
                        }

                        array_push($linkArr, $arr);
                    }
                }

                $jsonArr['all_links'] = $linkArr;
            }

            if( $request->input('builder_type') == 'DISTRIBUTOR' ) {
                
                $linkArr = array();
                $masterData = \App\Models\Distributor\Distributor::where('status', '=', '1')
                ->where('name', '!=', '')->where('slug', '!=', '')->orderBy('id', 'desc')->get();
                if( !empty($masterData) ) {
                    foreach($masterData as $lnk) {
                        $arr = array();
                        $arr['id'] = $lnk->id;
                        $arr['slug'] = $lnk->slug;
                        $arr['display_slug'] = url( $lnk->slug );
                        $arr['name'] = $lnk->name;
                        $arr['order'] = getLinkOrder( trim( $request->input('id') ) , trim( $lnk->slug ) );
                        $isLinkSelected = isLinkSelected( trim( $request->input('id') ) , trim( $lnk->slug ) );

                        if( $isLinkSelected != '' && $isLinkSelected == 'SELECTED' ) {
                            $arr['isSelected'] = 'YES';
                        } else {
                            $arr['isSelected'] = 'NO';
                        }

                        array_push($linkArr, $arr);
                    }
                }

                $jsonArr['all_links'] = $linkArr;
            }


            if( $request->input('builder_type') == 'DISTRIBUTOR_PAGE' ) {
                
                $linkArr = array();
                $masterData = \App\Models\Distributor\DistributorContents::where('status', '=', '1')
                ->where('name', '!=', '')->where('slug', '!=', '')->orderBy('id', 'desc')->get();
                if( !empty($masterData) ) {
                    foreach($masterData as $lnk) {
                        $arr = array();
                        $arr['id'] = $lnk->id;
                        $arr['slug'] = $lnk->slug;
                        $arr['display_slug'] = url( $lnk->slug );
                        $arr['name'] = $lnk->name;
                        $isLinkSelected = isLinkSelected( trim( $request->input('id') ) , trim( $lnk->slug ) );

                        if( $isLinkSelected != '' && $isLinkSelected == 'SELECTED' ) {
                            $arr['isSelected'] = 'YES';
                        } else {
                            $arr['isSelected'] = 'NO';
                        }

                        array_push($linkArr, $arr);
                    }
                }

                $jsonArr['all_links'] = $linkArr;
            }

            if( $request->input('builder_type') == 'CUSTOM_LINKS' ) {
                
                $linkArr = array();
                $linkData = PageBuilderLinks::where('page_builder_id', '=', trim( $request->input('id') ) )
                ->where('link_type', '=', 'CUSTOM_LINKS')->get();

                if( !empty($linkData) ) {
                    foreach( $linkData as $v ) {
                        $arr = array();
                        $arr['id'] = $v->id;
                        $arr['slug'] = $v->slug;
                        $arr['text'] = $v->link_text;
                        array_push($linkArr, $arr);
                    }
                }

                $jsonArr['all_links'] = $linkArr;
            }

            if( $request->input('builder_type') == 'ACCORDION' ) {
                
                $accrArr = array();
                $accrData = PageBuilderAccordion::where('page_builder_id', '=', trim( $request->input('id') ) )->get();

                if( !empty($accrData) ) {
                    foreach( $accrData as $v ) {
                        $arr = array();
                        $arr['id'] = $v->id;
                        $arr['heading'] = $v->heading;
                        $arr['content'] = html_entity_decode( $v->content , ENT_QUOTES );
                        array_push($accrArr, $arr);
                    }
                }

                $jsonArr['accr_data'] = $accrArr;
            }
		}

		return json_encode( $jsonArr );
	}

	public function ordering(Request $request) {

		$updateOrderArr = array();
		$i = 1;
		foreach( $request->input('ids') as $id ) {
			PageBuilder::where('id', '=', $id)->update( [ 'display_order' => $i ] );
			$i++;
		}

		return 'ok';
	}

	public function position(Request $request) {

		PageBuilder::where( 'id', '=', trim( $request->input('id') ) )
		->update( [ 'position' => trim( $request->input('position') ) ] );

		return 'ok';
	}

    public function allForms() {

        $data = FrmMaster::where('status', '=', '1')->orderBy('frm_heading', 'asc')->get()->toJson();
        return $data;
    }

    public function allReuse() {

        $data = ReusableContent::where('status', '=', '1')->orderBy('id', 'desc')->get()->toJson();
        return $data;
    }

    public function deleteImage(Request $request) {

        $r = PageBuilderImages::where('img_id', '=', trim( $request->input('img_id') ) )->delete(); 
        if( $r ) {
            return 'ok';
        }  
    }

    public function deleteFile(Request $request) {

        $r = PageBuilderFiles::where('file_id', '=', trim( $request->input('file_id') ) )->delete(); 
        if( $r ) {
            return 'ok';
        }   
    }

    public function editFile(Request $request) {

        $updateArr = array();
        $updateArr['name'] = trim( $request->input('name') );
        $updateArr['title'] = trim( $request->input('title') );
        $updateArr['caption'] = trim( $request->input('caption') );
        $updateArr['details'] = trim( $request->input('details') );
        $r = PageBuilderFiles::where('file_id', '=', trim( $request->input('file_id') ) )->update( $updateArr );

        if( $r ) {
            return 'ok';
        }
    }

    public function editImage(Request $request) {

        $updateArr = array();
        $updateArr['img_alt'] = trim( $request->input('img_alt') );
        $updateArr['img_title'] = trim( $request->input('img_title') );
        $updateArr['img_caption'] = trim( $request->input('img_caption') );
        $updateArr['img_desc'] = trim( $request->input('img_desc') );
        $r = PageBuilderImages::where('img_id', '=', trim( $request->input('img_id') ) )->update( $updateArr );

        if( $r ) {
            return 'ok';
        }   
    }

    public function deleteVideo(Request $request) {

        $r = PageBuilderVideos::where('video_id', '=', trim( $request->input('video_id') ) )->delete(); 
        if( $r ) {
            return 'ok';
        }     
    }

    public function editVideo(Request $request) {

        $updateArr = array();
        $updateArr['name'] = trim( $request->input('name') );
        $updateArr['title'] = trim( $request->input('title') );
        $updateArr['caption'] = trim( $request->input('caption') );
        $r = PageBuilderVideos::where('video_id', '=', trim( $request->input('video_id') ) )->update( $updateArr );

        if( $r ) {
            return 'ok';
        } 
    }

    public function getLinks(Request $request) {

        $data = array();
        $heading = '';
        $link_type = trim( $request->input('link_type') );
        $ele = '';

        $expArr = explode('-', $link_type);

        if( $link_type == 'PRODUCT_LINKS' || $link_type == 'PRODUCT_BOX' ) {

            $data = \App\Models\Product\Products::where('status', '=', '1')->where('is_duplicate', '=', '0')
            ->where('name', '!=', '')->where('slug', '!=', '')->orderBy('id', 'desc')->get();
            $heading = 'Select Product Links';
            $ele = 'tab';
        }

        if( $link_type == 'PRODUCT_CAT_LINKS' || $link_type == 'PRODUCT_CAT_BOX' ) {

            $data = \App\Models\Product\ProductCategories::where('status', '=', '1')->where('is_duplicate', '=', '0')
            ->where('name', '!=', '')->where('slug', '!=', '')->orderBy('id', 'desc')->get();
            $heading = 'Select Product Category Links';
            $ele = 'tab';
        }

        if( $link_type == 'NEWS_LINKS' ) {

            $data = \App\Models\Article\Articles::where('status', '=', '1')
            ->where('name', '!=', '')->where('slug', '!=', '')->orderBy('id', 'desc')->get();
            $heading = 'Select Article Links';
            $ele = 'tab';
        }

        if( $link_type == 'PEOPLE_LINKS' ) {

            $data = \App\Models\PeoplesProfile\PeoplesProfile::where('status', '=', '1')
            ->where('name', '!=', '')->where('slug', '!=', '')->orderBy('id', 'desc')->get();
            $heading = 'Select Peoples Profile Links';
            $ele = 'tab';
        }

        if( $link_type == 'DISTRIBUTOR' ) {

            $data = \App\Models\Distributor\Distributor::where('status', '=', '1')
            ->where('name', '!=', '')->where('slug', '!=', '')->orderBy('id', 'desc')->get();
            $heading = 'Select Distributor Links';
            $ele = 'tab';
        }

        if( $link_type == 'DISTRIBUTOR_PAGE' ) {

            $data = \App\Models\Distributor\DistributorContents::where('status', '=', '1')
            ->where('name', '!=', '')->where('slug', '!=', '')->orderBy('id', 'desc')->get();
            $heading = 'Select Distributor Content Links';
            $ele = 'tab';
        }

        if(strpos($link_type, 'CONTENT_LINKS') !== false) {

            $expArr = explode('-', $link_type);
            $ctypeId = 0;
            if(!empty($expArr)) {
                $ctypeId = trim(end($expArr));
            }

            $data = \App\Models\Content\Contents::where('status', '=', '1')->where('content_type_id', '=', $ctypeId)
            ->where('name', '!=', '')->where('slug', '!=', '')->orderBy('id', 'desc')->get();
            $heading = 'Select Links';
            $ele = 'tab';
        }
        

        $linksView = view( 'dashboard.any_render', array('links' => $data, 'heading' => $heading, 'ele' => $ele) )->render();

        return response()->json(['html' => $linksView, 'link_type' => $link_type, 'status' => 'ok', 'x' => count($expArr)]);
    }


    public function getPboxReusable(Request $request) {

        $data = \App\Models\PboxReusableContent::where('column_key', '=', trim($request->input('cid')))
        ->where('status', '=', '1')->orderBy('created_at', 'desc')->get();

        $reusView = view( 'dashboard.any_render', array('pboxreus' => $data) )->render();

        return response()->json(['html' => $reusView]);

    }
}
