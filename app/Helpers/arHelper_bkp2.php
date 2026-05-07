<?php

function getGeneralSettings() {
	$arr = DB::table('general_settings')->where('id', '=', '1')->first();
	return $arr;
}

function getAllParentEventsByCategory($category_id) {
	$arr = array();
	if( $category_id != '' && $category_id != null) {
		$arr = DB::table('event_category_map as ecm')->where('ecm.event_category_id', '=', $category_id)
		->join('events', 'events.id', '=', 'ecm.event_id')->where('events.parent_event_id', '=', '0')
		->where('events.status', '!=', '3')->get();
	}
	return $arr;
}

function sizeFilter( $bytes ) {
	if( $bytes != '' && $bytes != null ) {
    $label = array( 'B', 'KB', 'MB', 'GB', 'TB', 'PB' );
    for( $i = 0; $bytes >= 1024 && $i < ( count( $label ) -1 ); $bytes /= 1024, $i++ );
    return( round( $bytes, 2 ) . " " . $label[$i] );
	} else {
		return "0";
	}
}

function fileInfo( $fileId ) {
	$dataArr = array();
	if( $fileId != null && $fileId != '' ) {
		$dataArr = DB::table('files_master')->where('id', '=', $fileId)->first();
	}
	return $dataArr;
}

function imageInfo( $imgId ) {
	$dataArr = array();
	if( $imgId != null && $imgId != '' ) {
		$dataArr = DB::table('image')->where('id', '=', $imgId)->first();
	}
	return $dataArr;
}

function getCmsPageInfo( $cms_link_id ) {

	$data = array();
	if( $cms_link_id != '' && $cms_link_id != null ) {

		$cms = DB::table('cms_links')->where('id', '=', $cms_link_id)->first();
		if( isset($cms) && !empty($cms) ) {

			$tabId = $cms->table_id;
			$tabType = $cms->table_type;

			if( $tabType == 'DYNA_CONTENT' ) {
				$data = DB::table('contents')->where('id', '=', $tabId)->first();
			}
			if( $tabType == 'PRODUCT_CATEGORY' ) {
				$data = DB::table('product_categories')->where('id', '=', $tabId)->first();
			}
			if( $tabType == 'PRODUCT' ) {
				$data = DB::table('products')->where('id', '=', $tabId)->first();
			}
			if( $tabType == 'ARTICLE_CATEGORY' ) {
				$data = DB::table('article_categories')->where('id', '=', $tabId)->first();
			}
			if( $tabType == 'ARTICLE' ) {
				$data = DB::table('articles')->where('id', '=', $tabId)->first();
			}
			if( $tabType == 'INDUSTRY' ) {
				$data = DB::table('industries')->where('id', '=', $tabId)->first();
			}
			if( $tabType == 'FLOWSHEET_CATEGORY' ) {
				$data = DB::table('flowsheet_category')->where('id', '=', $tabId)->first();
			}
			if( $tabType == 'FLOWSHEET' ) {
				$data = DB::table('flowsheet')->where('id', '=', $tabId)->first();
			}
			if( $tabType == 'DISTRIBUTOR' ) {
				$data = DB::table('distributor')->where('id', '=', $tabId)->first();
			}
			if( $tabType == 'DISTRIBUTOR_CATEGORY' ) {
				$data = DB::table('distributor_category')->where('id', '=', $tabId)->first();
			}
			if( $tabType == 'DISTRIBUTOR_CONTENT' ) {
				$data = DB::table('distributor_contents')->where('id', '=', $tabId)->first();
			}
			if( $tabType == 'CAREER' ) {
				$data = DB::table('careers')->where('id', '=', $tabId)->first();
			}
			if( $tabType == 'PEOPLE_PROFILE' ) {
				$data = DB::table('peoples_profile')->where('id', '=', $tabId)->first();
			}
			if( $tabType == 'PEOPLE_PROFILE_CATEGORY' ) {
				$data = DB::table('people_profile_categories')->where('id', '=', $tabId)->first();
			}
			if( $tabType == 'TECH_RESOURCE' ) {
				$data = DB::table('tech_resource')->where('id', '=', $tabId)->first();
			}
			if( $tabType == 'PERSONA' ) {
				$data = DB::table('personas')->where('id', '=', $tabId)->first();
			}
			if( $tabType == 'EVENT' ) {
				$data = DB::table('events')->where('id', '=', $tabId)->first();
			}
			if( $tabType == 'EVENT_CATEGORY' ) {
				$data = DB::table('event_categories')->where('id', '=', $tabId)->first();
			}
			if( $tabType == 'VIDEO' ) {
				$data = DB::table('videos')->where('id', '=', $tabId)->first();
			}
			if( $tabType == 'VIDEO_CATEGORY' ) {
				$data = DB::table('video_categories')->where('id', '=', $tabId)->first();
			}
			if( $tabType == 'FILE_CATEGORY' ) {
				$data = DB::table('file_categories')->where('id', '=', $tabId)->first();
			}
		}
	}

	return $data;
}

function getMenuLink( $cms_link_id, $table_type, $table_id ) {

	$data = array();
	if( $cms_link_id != '' && $table_type != '' && $table_id != '' ) {

		$cms = DB::table('cms_links')->where('id', '=', $cms_link_id)
		->where('table_type', '=', $table_type)->where('table_id', '=', $table_id)->first();
		
		if( isset($cms) && !empty($cms) ) {

			$tabId = $cms->table_id;
			$tabType = $cms->table_type;

			if( $tabType == 'DYNA_CONTENT' ) {
				$data = DB::table('contents')->where('id', '=', $tabId)->select('slug', 'language_id')->first();
			}
			if( $tabType == 'PRODUCT_CATEGORY' ) {
				$data = DB::table('product_categories')->where('id', '=', $tabId)->select('slug', 'language_id')->first();
			}
			if( $tabType == 'PRODUCT' ) {
				$data = DB::table('products')->where('id', '=', $tabId)->select('slug', 'language_id')->first();
			}
			if( $tabType == 'ARTICLE_CATEGORY' ) {
				$data = DB::table('article_categories')->where('id', '=', $tabId)->select('slug', 'language_id')->first();
			}
			if( $tabType == 'ARTICLE' ) {
				$data = DB::table('articles')->where('id', '=', $tabId)->select('slug', 'language_id')->first();
			}
			if( $tabType == 'INDUSTRY' ) {
				$data = DB::table('industries')->where('id', '=', $tabId)->select('slug', 'language_id')->first();
			}
			if( $tabType == 'FLOWSHEET_CATEGORY' ) {
				$data = DB::table('flowsheet_category')->where('id', '=', $tabId)->select('slug', 'language_id')->first();
			}
			if( $tabType == 'FLOWSHEET' ) {
				$data = DB::table('flowsheet')->where('id', '=', $tabId)->select('slug', 'language_id')->first();
			}
			if( $tabType == 'DISTRIBUTOR' ) {
				$data = DB::table('distributor')->where('id', '=', $tabId)->select('slug', 'language_id')->first();
			}
			if( $tabType == 'DISTRIBUTOR_CATEGORY' ) {
				$data = DB::table('distributor_category')->where('id', '=', $tabId)->select('slug', 'language_id')->first();
			}
			if( $tabType == 'DISTRIBUTOR_CONTENT' ) {
				$data = DB::table('distributor_contents')->where('id', '=', $tabId)->select('slug', 'language_id')->first();
			}
			if( $tabType == 'CAREER' ) {
				$data = DB::table('careers')->where('id', '=', $tabId)->select('slug', 'language_id')->first();
			}
			if( $tabType == 'PEOPLE_PROFILE' ) {
				$data = DB::table('peoples_profile')->where('id', '=', $tabId)->select('slug', 'language_id')->first();
			}
			if( $tabType == 'PEOPLE_PROFILE_CATEGORY' ) {
				$data = DB::table('people_profile_categories')->where('id', '=', $tabId)->select('slug', 'language_id')->first();
			}
			if( $tabType == 'TECH_RESOURCE' ) {
				$data = DB::table('tech_resource')->where('id', '=', $tabId)->select('slug', 'language_id')->first();
			}
			if( $tabType == 'PERSONA' ) {
				$data = DB::table('personas')->where('id', '=', $tabId)->select('slug', 'language_id')->first();
			}
			if( $tabType == 'EVENT' ) {
				$data = DB::table('events')->where('id', '=', $tabId)->select('slug', 'language_id')->first();
			}
			if( $tabType == 'EVENT_CATEGORY' ) {
				$data = DB::table('event_categories')->where('id', '=', $tabId)->select('slug', 'language_id')->first();
			}
			if( $tabType == 'VIDEO' ) {
				$data = DB::table('videos')->where('id', '=', $tabId)->select('slug', 'language_id')->first();
			}
			if( $tabType == 'VIDEO_CATEGORY' ) {
				$data = DB::table('video_categories')->where('id', '=', $tabId)->select('slug', 'language_id')->first();
			}
			if( $tabType == 'FILE_CATEGORY' ) {
				$data = DB::table('file_categories')->where('id', '=', $tabId)->select('slug', 'language_id')->first();
			}
		}
	}

	return $data;
}

function delete_navigation($table_id, $table_type) {

	if( $table_id != '' && $table_type != '' ) {

		$cmsLink = DB::table('cms_links')->where('table_id', '=', $table_id)
		->where('table_type', '=', $table_type)->first();

		if( !empty( $cmsLink ) ) {

			DB::table('navigation_master')->where( 'cms_link_id', '=', $cmsLink->id )
			->where( 'table_type', '=', $table_type )->delete();
		}
	}
}

function getHtmlFormBySCODE( $scode ) {

	$htmlFrm = '';
	if( $scode != '' && $scode != null ) {

		$Data = DB::table('frm_master')->where('frm_scode', '=', $scode)->first();
		if( !empty($Data) ) {
			$htmlFrm = $Data->frm_raw_html;
		}
	}
	return trim( html_entity_decode( $htmlFrm, ENT_QUOTES ) );
}


function getHtmlReuseBySCODE( $scode ) {

	$htmlCont = '';
	$backimg = '';
	$backdiv = '';
	$final = '';

	if( $scode != '' && $scode != null ) {

		$Data = DB::table('reusable_content')->where('short_code', '=', $scode)->first();
		if( !empty($Data) ) {
			$backimg = $Data->backimg;
			if( $backimg != '' ) {
				$backimgurl = asset('public/uploads/files/media_images/' . $backimg);
				$backdiv = '<div style="background : url('.$backimgurl.') 0 0 no-repeat; background-size:cover;">';
				$htmlCont = $backdiv . $Data->content . '</div>';
			} else {
				$htmlCont = $Data->content;
			}
			
		}
	}
	
	return trim( html_entity_decode( $htmlCont, ENT_QUOTES ) );
}

function contentHtmlGenerator( $content ) {

	$finalContent = '';
	if( $content != '' && $content != null ) {

		$contentRmvSlash = stripslashes( $content );
		$contentHtmlDecode = html_entity_decode( $contentRmvSlash, ENT_QUOTES );
		
		$finalContent = trim( $contentHtmlDecode );

		preg_match_all("/\[#(.*)\#]/", $finalContent, $contentArr, PREG_PATTERN_ORDER);

		if( is_array($contentArr) && !empty($contentArr) && count($contentArr) > 0 ) {

			$i = 0;
			foreach( $contentArr[1] as $scExp ) {
				
				$expArr = explode( '_' , $scExp );
				if( !empty($expArr) && count($expArr) > 0 ) {

					$scTag = $expArr[0];
					$scID = $expArr[1];

					if( $scTag == 'Reusable' ) {

						$Data = DB::table('reusable_content')->where('short_code', '=', '[#'. $scExp .'#]')->first();
						if( !empty($Data) ) {
							$finalContent = str_replace( $contentArr[0][$i], $Data->content, $finalContent );
						}
					}
					if( $scTag == 'FORM' ) {

						$Data = DB::table('frm_master')->where('frm_scode', '=', '[#'. $scExp .'#]')->first();
						if( !empty($Data) ) {
							$finalContent = str_replace( $contentArr[0][$i], $Data->frm_raw_html, $finalContent );
						}
					}
					if( $scTag == 'Gallery' ) {

						$Data = DB::table('image_gallery')->where('short_code', '=', '[#'. $scExp .'#]')->first();
						if( !empty($Data) ) {
							$imageHtml = "<div class='ar-galimg-container'>";
							if( $Data->gallery_source == '2' ) {
								$galImages = DB::table('image_gallery_map')->where('image_gallery_id', '=', $Data->id)->paginate(25);
								if( !empty($galImages) ) {
									foreach( $galImages as $gImg ) {
										$image = DB::table('image')->where('id', '=', $gImg->image_id)->where('status', '=', '1')->first();
										if( !empty($image) ) {
											$imgPath =  asset( 'public/uploads/files/media_images/thumb/'. $image->image );
											$img = "<img src='". $imgPath ."' class=''>";
											$imageHtml .= $img;
										}
									}
									$imageHtml .= "<div>". $galImages->links() ."</div>";
								}
							}
							if( $Data->gallery_source == '1' ) {
								$catImages = DB::table('image_category_map')->where('image_category_id', '=', $Data->image_category_id)->paginate(25);
								if( !empty($catImages) ) {
									foreach( $catImages as $gImg ) {
										$image = DB::table('image')->where('id', '=', $gImg->image_id)->where('status', '=', '1')->first();
										if( !empty($image) ) {
											$imgPath =  asset( 'public/uploads/files/media_images/thumb/'. $image->image );
											$img = "<img src='". $imgPath ."' class=''>";
											$imageHtml .= $img;
										}
									}
									$imageHtml .= "<div>". $catImages->links() ."</div>";
								}
							}
							$imageHtml .= "</div>";
							$finalContent = str_replace( $contentArr[0][$i], $imageHtml, $finalContent );
						}
					}
				}
				$i++;
			}
		}
	}

	return trim( html_entity_decode( $finalContent, ENT_QUOTES ) );
}


function update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) {

	if($insert_id != '' && $cms_link_id != '' && $table_id != '' && $table_type != '') {

		$updateArr = array();
		$updateArr['cms_link_id'] = $cms_link_id;
		$updateArr['table_id'] = $table_id;
		$updateArr['table_type'] = $table_type;

		DB::table('page_builder')->where('insert_id', '=', $insert_id)->update( $updateArr );
	}
}

function getOtherBrochureLinks( $table_type, $table_id ) {

	$output = array();
	if( $table_type != '' && $table_id != '' ) {

		if( $table_type == 'PRODUCT' ){

			$catIds = DB::table('product_categories_map')->where('product_id', '=', $table_id)->pluck('product_category_id')->toArray();
			if( !empty($catIds) ) {
				$proIds = DB::table('product_categories_map')->whereIn('product_category_id', $catIds)->pluck('product_id')->toArray();
				if( !empty($proIds) ) {
					$products = DB::table('products')->whereIn('id', $proIds)->where('status', '=', '1')->get();
					$output = $products;
				}
			}
		}

		if( $table_type == 'PRODUCT_CATEGORY' ){

			$proIds = DB::table('product_categories_map')->where('product_category_id', '=', $table_id)->pluck('product_id')->toArray();
			if( !empty($proIds) ) {
				$products = DB::table('products')->whereIn('id', $proIds)->where('status', '=', '1')->get();
				$output = $products;
			}
		}
	}

	return $output; 
}

function isLinkSelected($page_builder_id , $slug) {

	if($page_builder_id != '' && $slug != '') {

		$ck = DB::table('page_builder_links')->where('page_builder_id', '=', $page_builder_id)
		->where('slug', '=', $slug)->first();

		if( !empty($ck) ) {
			return 'SELECTED';
		}
	}

	return '';

}

function linkSlugToContent( $slug ) {

	$data = array();
	if( $slug != '' ) {

		$cms = DB::table('cms_links')->where('slug_url', '=', trim($slug))->first();
		if( !empty($cms) ) {
			$data = getCmsPageInfo( $cms->id );
		}
	}

	return $data;
}


function getProductImage( $product_id ) {

	$data = array();
	if( $product_id != '' ) {
		$data = DB::table('products_images_map as pim')
		->where('pim.product_id', '=', $product_id)
		->join('image', 'image.id', '=', 'pim.image_id')
		->select('image.image', 'pim.*')
		->orderBy('pim.id', 'desc')
		->first();
	}

	return $data;
}

function getFileDownloadLink( $file_id ) {

	$rtn = '';
	if( $file_id != '' && $file_id != '0' ) {
		$data = DB::table('files_master')->where('id', '=', $file_id)->first();
		if( !empty($data) ) {
			$rtn = $data->file;
		}
	}

	return $rtn;
}

function getLanguage( $lng_id ) {

	$rtn = '';
	if( $lng_id != '' && $lng_id != '0' ) {
		$data = DB::table('languages')->where('id', '=', $lng_id)->first();
		if( !empty($data) ) {
			$rtn = $data->name;
		}
	}

	return $rtn;
}

function genPBOXreusContent($id) {

	$reuContent = '';
	if( $id != '' && $id != null ) {	
		$data = DB::table('productbox_reusable_content')->where('id', '=', $id)->first();
		if( !empty($data) ) {
			if( $data->column_key == '1' ) {
				$reuContent .= '<div class="col-sm-3">';
					$reuContent .= html_entity_decode( $data->content, ENT_QUOTES );
				$reuContent .= '</div>';
			}
			if( $data->column_key == '2' ) {
				$reuContent .= '<div class="col-sm-6">';
					$reuContent .= html_entity_decode( $data->content, ENT_QUOTES );
				$reuContent .= '</div>';
				
			}
			if( $data->column_key == '3' ) {
				$reuContent .= '<div class="col-sm-9">';
					$reuContent .= html_entity_decode( $data->content, ENT_QUOTES );
				$reuContent .= '</div>';
				
			}
		}
	}

	return $reuContent;
}


function getChildLngPageInfo( $table_type, $table_id ) {

	$data = array();
	if( $table_type != '' && $table_type != null && $table_id != '' && $table_id != null ) {

		$tabId = $table_id;
		$tabType = $table_type;

		if( $tabType == 'DYNA_CONTENT' ) {
			$data = DB::table('contents')->where('parent_language_id', '=', $tabId)->get();
		}
		if( $tabType == 'PRODUCT_CATEGORY' ) {
			$data = DB::table('product_categories')->where('parent_language_id', '=', $tabId)->get();
		}
		if( $tabType == 'PRODUCT' ) {
			$data = DB::table('products')->where('parent_language_id', '=', $tabId)->get();
		}
		if( $tabType == 'ARTICLE_CATEGORY' ) {
			$data = DB::table('article_categories')->where('parent_language_id', '=', $tabId)->get();
		}
		if( $tabType == 'ARTICLE' ) {
			$data = DB::table('articles')->where('parent_language_id', '=', $tabId)->get();
		}
		if( $tabType == 'INDUSTRY' ) {
			$data = DB::table('industries')->where('parent_language_id', '=', $tabId)->get();
		}
		if( $tabType == 'FLOWSHEET_CATEGORY' ) {
			$data = DB::table('flowsheet_category')->where('parent_language_id', '=', $tabId)->get();
		}
		if( $tabType == 'FLOWSHEET' ) {
			$data = DB::table('flowsheet')->where('parent_language_id', '=', $tabId)->get();
		}
		if( $tabType == 'DISTRIBUTOR' ) {
			$data = DB::table('distributor')->where('parent_language_id', '=', $tabId)->get();
		}
		if( $tabType == 'DISTRIBUTOR_CATEGORY' ) {
			$data = DB::table('distributor_category')->where('parent_language_id', '=', $tabId)->get();
		}
		if( $tabType == 'DISTRIBUTOR_CONTENT' ) {
			$data = DB::table('distributor_contents')->where('parent_language_id', '=', $tabId)->get();
		}
		if( $tabType == 'CAREER' ) {
			$data = DB::table('careers')->where('parent_language_id', '=', $tabId)->get();
		}
		if( $tabType == 'PEOPLE_PROFILE' ) {
			$data = DB::table('peoples_profile')->where('parent_language_id', '=', $tabId)->get();
		}
		if( $tabType == 'PEOPLE_PROFILE_CATEGORY' ) {
			$data = DB::table('people_profile_categories')->where('parent_language_id', '=', $tabId)->get();
		}
		if( $tabType == 'TECH_RESOURCE' ) {
			$data = DB::table('tech_resource')->where('parent_language_id', '=', $tabId)->get();
		}
		if( $tabType == 'PERSONA' ) {
			$data = DB::table('personas')->where('parent_language_id', '=', $tabId)->get();
		}
		if( $tabType == 'EVENT' ) {
			$data = DB::table('events')->where('parent_language_id', '=', $tabId)->get();
		}
		if( $tabType == 'EVENT_CATEGORY' ) {
			$data = DB::table('event_categories')->where('parent_language_id', '=', $tabId)->get();
		}
		if( $tabType == 'VIDEO' ) {
			$data = DB::table('videos')->where('parent_language_id', '=', $tabId)->get();
		}
		if( $tabType == 'VIDEO_CATEGORY' ) {
			$data = DB::table('video_categories')->where('parent_language_id', '=', $tabId)->get();
		}
		if( $tabType == 'FILE_CATEGORY' ) {
			$data = DB::table('file_categories')->where('parent_language_id', '=', $tabId)->get();
		}
	}

	return $data;
}


function getCmsLinkId($slug_url, $table_id) {

	if($slug_url != '' && $table_id != '') {

		$cms = DB::table('cms_links')->where('slug_url', '=', $slug_url)->where('table_id', '=', $table_id)->first();
		if( !empty($cms) ) {

			return $cms->id;
		}
	}

	return 0;
}

function getLngNaviInfo($whos_id, $lng_id) {

	$data = array();
	if($whos_id != '' && $lng_id != '') {

		$data = DB::table('navigation_master')->where('whos_id', '=', $whos_id)->where('lng_id', '=', $lng_id)->first();
	}

	return $data;
}


function getLngCode($lng_id) {

	if($lng_id != '') {

		$data = DB::table('languages')->where('id', '=', $lng_id)->first();
		if( !empty($data) ) {
			return strtolower($data->code);
		}
	}

	return '';
}

function getLngIDbyCode($lng_code) {

	if($lng_code != '') {

		$data = DB::table('languages')->where('code', '=', $lng_code)->first();
		if( !empty($data) ) {
			return $data->id;
		}
	}

	return '0';
}


function getImageById($imgid) {

	$data = array();
	if($imgid != '' && $imgid != '0') {
		$data = DB::table('image')->where('id', '=', $imgid)->first();
	}

	return $data;
}


/** For Product Section **/
function getProductCategory($product_id) {

	$rtnArr = array();
	if($product_id != '') {
		$data = DB::table('product_categories_map')->where('product_id', '=', $product_id)->first();
		if(!empty($data)) {
			$data2 = DB::table('product_categories')->where('id', '=', $data->product_category_id)->first();
			$rtnArr = $data2;
		}
	}

	return $rtnArr;
}

function getProductBanner($product_category_id) {
	$rtnArr = array();
	if($product_category_id != '') {
		$data = DB::table('product_categories_images_map')->where('product_category_id', '=', $product_category_id)
		->where('image_type', '=', 'BANNER_IMAGE')->orderBy('id', 'desc')->first();
		if(!empty($data)) {
			$image = getImageById($data->image_id);
			if(!empty($image)) {
				$rtnArr['image'] = $image->image;
				$rtnArr['image_id'] = $image->id;
				$rtnArr['title'] = $data->title;
				$rtnArr['caption'] = $data->caption;
				$rtnArr['alt_tag'] = $data->alt_tag;
				$rtnArr['description'] = $data->description;
			}
		}
	}
	return $rtnArr;
}
/** End Product Section **/



/** Dyna Content Section **/
function getContentBanner($content_type_id) {
	$rtnArr = array();
	if($content_type_id != '') {
		$data = DB::table('content_type_images_map')->where('content_type_id', '=', $content_type_id)
		->where('image_type', '=', 'BANNER_IMAGE')->orderBy('id', 'desc')->first();
		if(!empty($data)) {
			$image = getImageById($data->image_id);
			if(!empty($image)) {
				$rtnArr['image'] = $image->image;
				$rtnArr['image_id'] = $image->id;
				$rtnArr['title'] = $data->title;
				$rtnArr['caption'] = $data->caption;
				$rtnArr['alt_tag'] = $data->alt_tag;
				$rtnArr['description'] = $data->description;
			}
		}
	}
	return $rtnArr;
}
/** End Dyna Content Section **/

function getIndustryBanner($industry_id) {
	$rtnArr = array();
	if($industry_id != '') {
		$data = DB::table('industry_images_map')->where('industry_id', '=', $industry_id)
		->where('image_type', '=', 'BANNER_IMAGE')->orderBy('id', 'desc')->first();
		if(!empty($data)) {
			$image = getImageById($data->image_id);
			if(!empty($image)) {
				$rtnArr['image'] = $image->image;
				$rtnArr['image_id'] = $image->id;
				$rtnArr['title'] = $data->title;
				$rtnArr['caption'] = $data->caption;
				$rtnArr['alt_tag'] = $data->alt_tag;
				$rtnArr['description'] = $data->description;
			}
		}
	}
	return $rtnArr;
}

/** For Industry Flowsheet **/
function getFlowsheetCategory($flowsheet_id) {

	$rtnArr = array();
	if($flowsheet_id != '') {
		$data = DB::table('flowsheet_category_map')->where('flowsheet_id', '=', $flowsheet_id)->first();
		if(!empty($data)) {
			$data2 = DB::table('flowsheet_category')->where('id', '=', $data->flowsheet_category_id)->first();
			$rtnArr = $data2;
		}
	}

	return $rtnArr;
}
/** End Industry Flowsheet **/



/** For Distributor **/
function getDistCategory($dist_id) {

	$rtnArr = array();
	if($dist_id != '') {
		$data = DB::table('distributor_categories_map')->where('distributor_id', '=', $dist_id)->orderBy('id', 'desc')->first();
		if(!empty($data)) {
			$data2 = DB::table('distributor_category')->where('id', '=', $data->distributor_category_id)->first();
			$rtnArr = $data2;
		}
	}

	return $rtnArr;
}

function getDistInfo($dist_id) {

	$rtnArr = array();
	if($dist_id != '') {
		$data = DB::table('distributor')->where('id', '=', $dist_id)->first();
		$rtnArr = $data;
	}

	return $rtnArr;
}
/** End Distributor **/


function getArticleNewsImage($atr_id) {
	$rtnArr = array();
	if($atr_id != '') {
		$data = DB::table('article_images_map')->where('article_id', '=', $atr_id)
		->where('image_type', '=', 'MAIN_IMAGE')->orderBy('id', 'desc')->first();
		if(!empty($data)) {
			$image = getImageById($data->image_id);
			if(!empty($image)) {
				$rtnArr['image'] = $image->image;
				$rtnArr['image_id'] = $image->id;
				$rtnArr['title'] = $data->title;
				$rtnArr['caption'] = $data->caption;
				$rtnArr['alt_tag'] = $data->alt_tag;
				$rtnArr['description'] = $data->description;
			}
		}
	}
	return $rtnArr;
}

function getEventImage($atr_id) {
	$rtnArr = array();
	if($atr_id != '') {
		$data = DB::table('event_images_map')->where('event_id', '=', $atr_id)
		->where('image_type', '=', 'MAIN_IMAGE')->orderBy('id', 'desc')->first();
		if(!empty($data)) {
			$image = getImageById($data->image_id);
			if(!empty($image)) {
				$rtnArr['image'] = $image->image;
				$rtnArr['image_id'] = $image->id;
				$rtnArr['title'] = $data->title;
				$rtnArr['caption'] = $data->caption;
				$rtnArr['alt_tag'] = $data->alt_tag;
				$rtnArr['description'] = $data->description;
			}
		}
	}
	return $rtnArr;
}


function getSEOscripts($pos) {
	$data = array();
	if($pos != '') {
		$data = DB::table('analytics_scripts')->where('script_placement', '=', $pos)
		->where('status', '=', '1')->get();
	}
	return $data;
}


function getContinentName($id) {

	if( $id != '' ) {

		$data = DB::table('continents')->where('id', '=', $id)->first();
		if( !empty($data) ) {
			return $data->continents_name;
		}
	}

	return '';
}

function getCountryName($id) {

	if( $id != '' ) {

		$data = DB::table('countries')->where('id', '=', $id)->first();
		if( !empty($data) ) {
			return $data->country_name;
		}
	}

	return '';
}

function getCityName($id) {

	if( $id != '' ) {

		$data = DB::table('cities')->where('id', '=', $id)->first();
		if( !empty($data) ) {
			return $data->city_name;
		}
	}

	return '';
}

function getAnyTabObj($tab, $id) {

	if($id != '' && $tab !=  '') {
		$data = DB::table($tab)->where('id', '=', $id)->first();
		if( !empty($data) ) {
			return $data;
		}
	}

	return array();
}

function getAnyLatLng( $continent_id ) {

	if( $continent_id != '' ) {
		$data = DB::table('location_network')->where('continent_id', '=', $continent_id)->first();
		if( !empty($data) ) {
			return $data;
		}
	}

	return array();
}

function getAnyLatLng2( $city_id ) {

	if( $city_id != '' ) {
		$data = DB::table('location_network')->where('city_id', '=', $city_id)->first();
		if( !empty($data) ) {
			return $data;
		}
	}

	return array();
} 

function getProfilePageBanner( $profile_id ) {

	$rtnArr = array();
	if( $profile_id != '' ) {
		$data = DB::table('peoples_profile_category_map')->where('people_profile_id', '=', $profile_id)->first();
		if( !empty($data) ) {
			$data2 = DB::table('people_profile_categories')->where('id', '=', $data->people_profile_category_id)->first();
			if( !empty($data2) ) {
				$image = getImageById($data2->image_id);
				if(!empty($image)) {
					$rtnArr['image'] = $image->image;
					$rtnArr['image_id'] = $image->id;
					$rtnArr['title'] = $data2->image_title;
					$rtnArr['caption'] = $data2->image_caption;
					$rtnArr['alt_tag'] = $data2->image_alt;
				}
			}
		}
	}

	return $rtnArr;
}

function getArticlePageBanner( $article_id ) {

	$rtnArr = array();
	if( $article_id != '' ) {
		$data = DB::table('article_categories_map')->where('article_id', '=', $article_id)->first();
		if( !empty($data) ) {
			$data2 = DB::table('article_categories')->where('id', '=', $data->article_category_id)->first();
			if( !empty($data2) ) {
				$image = getImageById($data2->image_id);
				if(!empty($image)) {
					$rtnArr['image'] = $image->image;
					$rtnArr['image_id'] = $image->id;
					$rtnArr['title'] = $data2->image_title;
					$rtnArr['caption'] = $data2->image_caption;
					$rtnArr['alt_tag'] = $data2->image_alt;
				}
			}
		}
	}

	return $rtnArr;
}

function getEventPageBanner( $event_id ) {

	$rtnArr = array();
	if( $event_id != '' ) {
		$data = DB::table('event_category_map')->where('event_id', '=', $event_id)->first();
		if( !empty($data) ) {
			$data2 = DB::table('event_categories')->where('id', '=', $data->event_category_id)->first();
			if( !empty($data2) ) {
				$image = getImageById($data2->image_id);
				if(!empty($image)) {
					$rtnArr['image'] = $image->image;
					$rtnArr['image_id'] = $image->id;
					$rtnArr['title'] = $data2->image_title;
					$rtnArr['caption'] = $data2->image_caption;
					$rtnArr['alt_tag'] = $data2->image_alt;
				}
			}
		}
	}

	return $rtnArr;
}

function getFlowsheetPageBanner( $fs_id ) {

	$rtnArr = array();
	if( $fs_id != '' ) {
		$data = DB::table('flowsheet_category_map')->where('flowsheet_id', '=', $fs_id)->first();
		if( !empty($data) ) {
			$data2 = DB::table('flowsheet_category')->where('id', '=', $data->flowsheet_category_id)->first();
			if( !empty($data2) ) {
				$image = getImageById($data2->image_id);
				if(!empty($image)) {
					$rtnArr['image'] = $image->image;
					$rtnArr['image_id'] = $image->id;
					$rtnArr['title'] = $data2->image_title;
					$rtnArr['caption'] = $data2->image_caption;
					$rtnArr['alt_tag'] = $data2->image_alt;
				}
			}
		}
	}

	return $rtnArr;
}

function getContentTypes() {

	$data = DB::table('content_type')->where('status', '=', '1')->get();
	return $data;
}
?>