<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media\FilesMaster;
use App\Models\Media\Images;
use App\Models\CmsLinks;
use App\Models\TechResource\Personas;
use App\Models\Industry\Industries;
use App\Models\Product\ProductCategories;
use App\Models\TechResource\TechResource;
use App\Models\TechResource\TechResourceFilesMap;
use App\Models\TechResource\TechResourceImagesMap;
use App\Models\TechResource\TechResourceIndustriesMap;
use App\Models\TechResource\TechResourcePersonaMap;
use App\Models\TechResource\TechResourceProcatMap;
use App\Models\Languages;
use App\Models\PageBuilder\PageBuilder;
use App\Models\Media\MediaExtraContent;
use Auth;
use Image;

class TechResourceController extends Controller
{
    
    public function index() {
   		$DataBag = array();
    	$DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'techResManagement';
    	$DataBag['childMenu'] = 'allTechRes';
    	$DataBag['allResources'] = TechResource::where('status', '!=', '3')->where('parent_language_id', '=', '0')
        ->orderBy('created_at', 'desc')->get();
    	return view('dashboard.technical_resource.index', $DataBag);
   	} 

   	public function add() {
   		$DataBag = array();
    	$DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'techResManagement';
    	$DataBag['childMenu'] = 'addTechRes';
    	//$DataBag['allPersonas'] = Personas::where('status', '!=', '3')->orderBy('name', 'asc')->get();
    	//$DataBag['allIndustries'] = Industries::where('status', '!=', '3')->orderBy('name', 'asc')->get();
    	$DataBag['allProCats'] = ProductCategories::where('status', '!=', '3')->where('parent_id', '=', '0')->orderBy('name', 'asc')->get();
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));
    	return view('dashboard.technical_resource.add', $DataBag);
   	} 

   	public function allPersonas() {
   		$DataBag = array();
    	$DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'techResManagement';
    	$DataBag['childMenu'] = 'allPersona';
    	$DataBag['allPersona'] = Personas::where('status', '!=', '3')->where('parent_language_id', '=', '0')
        ->orderBy('created_at', 'desc')->get();
    	return view('dashboard.technical_resource.all_personas', $DataBag);
   	} 

   	public function addPersona() {
   		$DataBag = array();
    	$DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'techResManagement';
    	$DataBag['childMenu'] = 'addPersona';
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));
    	return view('dashboard.technical_resource.create_persona', $DataBag);
   	} 

   	public function savePersona(Request $request) {

        $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

   		$Personas = new Personas;
    	$Personas->name = trim(ucfirst($request->input('name')));
    	$Personas->slug = trim($request->input('slug'));
    	$Personas->page_content = htmlentities( trim($request->input('page_content')), ENT_QUOTES);
    	$Personas->created_by = Auth::user()->id;
        $Personas->language_id = trim( $request->input('language_id') );
        $Personas->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );

        $Personas->insert_id = $insert_id;

        $Personas->meta_title = trim($request->input('meta_title'));
        $Personas->meta_desc = trim($request->input('meta_desc'));
        $Personas->meta_keyword = trim($request->input('meta_keyword'));
        $Personas->canonical_url = trim($request->input('canonical_url'));
        $Personas->lng_tag = trim($request->input('lng_tag'));
        $Personas->follow = trim($request->input('follow'));
        $Personas->index_tag = trim($request->input('index_tag'));


    	if( $Personas->save() ) {
    		
    		$persona_id = $Personas->id;

    		$CmsLinks = new CmsLinks;
    		$CmsLinks->table_id = $persona_id;
    		$CmsLinks->slug_url = trim($request->input('slug'));
    		$CmsLinks->table_type = 'PERSONA';
    		$CmsLinks->save();
            $cms_link_id = $CmsLinks->id; // Need for page builder as parameter

            /** For Page Builder -- Insert Time **/
            /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
            update_page_builder($insert_id, $cms_link_id, $persona_id, 'PERSONA');
            /** End Page Builder **/

    		return back()->with('msg', 'Persona Created Succesfully')->with('msg_class', 'alert alert-success');
    	}
    return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
   	}

   	public function editPersona($persona_id) {
   		$DataBag = array();
    	$DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'techResManagement';
    	$DataBag['childMenu'] = 'addPersona';
    	$DataBag['persona'] = Personas::findorFail($persona_id);
        $DataBag['pageBuilderData'] = $DataBag['persona']; /* For pagebuilder */
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
    	return view('dashboard.technical_resource.create_persona', $DataBag);
   	}

   	public function updatePersona(Request $request, $persona_id) {

        $insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

   		$Personas = Personas::find($persona_id);
    	$Personas->name = trim(ucfirst($request->input('name')));
    	$Personas->slug = trim($request->input('slug'));
    	$Personas->page_content = htmlentities( trim($request->input('page_content')), ENT_QUOTES);
    	$Personas->updated_by = Auth::user()->id;
        $Personas->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );

        $Personas->meta_title = trim($request->input('meta_title'));
        $Personas->meta_desc = trim($request->input('meta_desc'));
        $Personas->meta_keyword = trim($request->input('meta_keyword'));
        $Personas->canonical_url = trim($request->input('canonical_url'));
        $Personas->lng_tag = trim($request->input('lng_tag'));
        $Personas->follow = trim($request->input('follow'));
        $Personas->index_tag = trim($request->input('index_tag'));

    	if( $Personas->save() ) {
    		
    		CmsLinks::where('table_type', '=', 'PERSONA')->where('table_id', '=', $persona_id)
    		->update( ['slug_url' => trim($request->input('slug'))] );

            /** Need For Page Builder -- Update Time **/
            $cmsInfo = CmsLinks::where('table_id', '=', $persona_id)->where('table_type', '=', 'PERSONA')->first();
            if( !empty($cmsInfo) ) {
                
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                update_page_builder($insert_id, $cmsInfo->id, $persona_id, 'PERSONA');

            }
            /** End Page Builder **/
    		
    		return back()->with('msg', 'Persona Updated Succesfully')->with('msg_class', 'alert alert-success');
    	}
    return back()->with('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
   	}

   	public function deletePersona($persona_id) {

   		$ck = Personas::find($persona_id);
    	if( isset($ck) && !empty($ck) ) {
    		$ck->status = '3';
    		$res = $ck->save();
    		if( isset($res) && $res == 1 ) {
    			
                delete_navigation($persona_id, 'PERSONA');
    			CmsLinks::where('table_type', '=', 'PERSONA')->where('table_id', '=', $persona_id)->delete();
                PageBuilder::where('table_id', '=', $persona_id)->where('table_type', '=', 'PERSONA')->delete();
    			
    			return back()->with('msg', 'Persona Deleted Successfully.')
    			->with('msg_class', 'alert alert-success');
    		}
    	}

    	return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
   	}









   	public function save(Request $request) {

        $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

    	$personasMap = array();
    	$industriesMap = array();
    	$procatsMap = array();

    	$TechResource = new TechResource;
    	$TechResource->name = trim( ucfirst($request->input('name')) );
    	//$TechResource->slug = trim($request->input('slug'));
    	$TechResource->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
    	$TechResource->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
    	$TechResource->created_by = Auth::user()->id;
        $TechResource->language_id = trim( $request->input('language_id') );

        $TechResource->tab_section = trim( $request->input('tab_section') );
        $TechResource->publish_date = date('Y-m-d', strtotime( trim($request->input('publish_date') ) ));
        $TechResource->display_order = trim($request->input('display_order'));

    	/*$TechResource->insert_id = $insert_id;
        $TechResource->meta_title = trim($request->input('meta_title'));
        $TechResource->meta_desc = trim($request->input('meta_desc'));
        $TechResource->meta_keyword = trim($request->input('meta_keyword'));
        $TechResource->canonical_url = trim($request->input('canonical_url'));
        $TechResource->lng_tag = trim($request->input('lng_tag'));
        $TechResource->follow = trim($request->input('follow'));
        $TechResource->index_tag = trim($request->input('index_tag'));*/

        $techResImageJson = json_decode( trim( $request->input('main_image_infos') ) );

    	$resx = $TechResource->save();
    	if( isset($resx) && $resx == 1 ) {

    		$tech_resource_id = $TechResource->id;

    		//$CmsLinks = new CmsLinks;
    		//$CmsLinks->table_id = $tech_resource_id;
    		//$CmsLinks->slug_url = trim($request->input('slug'));
    		//$CmsLinks->table_type = 'TECH_RESOURCE';
    		//$CmsLinks->save();
            //$cms_link_id = $CmsLinks->id; // Need for page builder as parameter

            /** For Page Builder -- Insert Time **/
            /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
            //update_page_builder($insert_id, $cms_link_id, $tech_resource_id, 'TECH_RESOURCE');
            /** End Page Builder **/

            if( !empty($techResImageJson) ) {
                $imageMap = array();
                foreach ($techResImageJson as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['tech_resource_id'] = $tech_resource_id;
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
                    TechResourceImagesMap::insert($imageMap);
                }
            }

            if( $request->has('techpdf') ) {
                $pdf = $request->file('techpdf');
                $real_path = $pdf->getRealPath();
                $file_orgname = $pdf->getClientOriginalName();
                $file_size = $pdf->getSize();
                $file_ext = strtolower($pdf->getClientOriginalExtension());
                $file_newname = "file"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
                $destinationPath = public_path('/uploads/files/media_files');
                $pdf->move($destinationPath, $file_newname);

                $FilesMaster = new FilesMaster;
                $FilesMaster->file = $file_newname;
                $FilesMaster->size = $file_size;
                $FilesMaster->extension = $file_ext;
                $FilesMaster->created_by = Auth::user()->id;
                $FilesMaster->file_type = '1';
                if( $FilesMaster->save() ) {
                    $file_id = $FilesMaster->id;
                    $TechResourceFilesMap = new TechResourceFilesMap;
                    $TechResourceFilesMap->tech_resource_id = $tech_resource_id;
                    $TechResourceFilesMap->file_id = $file_id;
                    $TechResourceFilesMap->file_type = 'MAIN_FILE';
                    $TechResourceFilesMap->save();
                }
            }

	    	/*if( $request->has('persona_id') ) {
	    		foreach( $request->input('persona_id') as $cats ) {
	    			$arr = array();
	    			$arr['tech_resource_id'] = $tech_resource_id;
	    			$arr['persona_id'] = $cats;
	    			array_push( $personasMap, $arr );
	    		}
	    		if( !empty($personasMap) ) {
	    			TechResourcePersonaMap::insert( $personasMap );
	    		}
	    	}*/

	    	/*if( $request->has('industry_id') ) {
	    		foreach( $request->input('industry_id') as $cats ) {
	    			$arr = array();
	    			$arr['tech_resource_id'] = $tech_resource_id;
	    			$arr['industry_id'] = $cats;
	    			array_push( $industriesMap, $arr );
	    		}
	    		if( !empty($industriesMap) ) {
	    			TechResourceIndustriesMap::insert( $industriesMap );
	    		}
	    	}*/

	    	if( $request->has('product_category_id') ) {
	    		foreach( $request->input('product_category_id') as $cats ) {
                    if( $cats != '' && $cats != '0') {
	    			    $arr = array();
	    			    $arr['tech_resource_id'] = $tech_resource_id;
	    			    $arr['product_category_id'] = $cats;
	    			    array_push( $procatsMap, $arr );
                    }
	    		}
	    		if( !empty($procatsMap) ) {
	    			TechResourceProcatMap::insert( $procatsMap );
	    		}
	    	}
	    return back()->with('msg', 'Technical Resource Created Successfully.')
    	->with('msg_class', 'alert alert-success');
    	}
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
   	}

   	public function edit($tr_id) {
   		$DataBag = array();
    	$DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'techResManagement';
    	$DataBag['childMenu'] = 'addTechRes';
    	$DataBag['resource'] = TechResource::findorFail($tr_id);
        $DataBag['pageBuilderData'] = $DataBag['resource']; /* For pagebuilder */
    	//$DataBag['allPersonas'] = Personas::where('status', '!=', '3')->orderBy('name', 'asc')->get();
    	//$DataBag['allIndustries'] = Industries::where('status', '!=', '3')->orderBy('name', 'asc')->get();
    	$DataBag['allProCats'] = ProductCategories::where('status', '!=', '3')->where('parent_id', '=', '0')->orderBy('name', 'asc')->get();
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
    	return view('dashboard.technical_resource.add', $DataBag);
   	}

   	public function update(Request $request, $tr_id) {

   		$insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

    	$personasMap = array();
    	$industriesMap = array();
    	$procatsMap = array();

    	$TechResource = TechResource::find($tr_id);
        $TechResource->name = trim( ucfirst($request->input('name')) );
        //$TechResource->slug = trim($request->input('slug'));
        $TechResource->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
        $TechResource->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
        $TechResource->updated_by = Auth::user()->id;
        $TechResource->language_id = trim( $request->input('language_id') );

        $TechResource->tab_section = trim( $request->input('tab_section') );
        $TechResource->publish_date = date('Y-m-d', strtotime( trim($request->input('publish_date') ) ));
        $TechResource->display_order = trim($request->input('display_order'));

        /*$TechResource->meta_title = trim($request->input('meta_title'));
        $TechResource->meta_desc = trim($request->input('meta_desc'));
        $TechResource->meta_keyword = trim($request->input('meta_keyword'));
        $TechResource->canonical_url = trim($request->input('canonical_url'));
        $TechResource->lng_tag = trim($request->input('lng_tag'));
        $TechResource->follow = trim($request->input('follow'));
        $TechResource->index_tag = trim($request->input('index_tag'));*/

        $techResImageJson = json_decode( trim( $request->input('main_image_infos') ) );

    	$resx = $TechResource->save();
    	if( isset($resx) && $resx == 1 ) {

    		$tech_resource_id = $tr_id;

    		/*CmsLinks::where('table_type', '=', 'TECH_RESOURCE')->where('table_type', '=', $tech_resource_id)
    		->update( ['slug_url' => trim($request->input('slug'))] );*/

            /** Need For Page Builder -- Update Time **/
            //$cmsInfo = CmsLinks::where('table_id', '=', $tech_resource_id)->where('table_type', '=', 'TECH_RESOURCE')->first();
            //if( !empty($cmsInfo) ) {
                
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                //update_page_builder($insert_id, $cmsInfo->id, $tech_resource_id, 'TECH_RESOURCE');

            //}
            /** End Page Builder **/

            if( !empty($techResImageJson) ) {
                $imageMap = array();
                foreach ($techResImageJson as $v) {
                    if( $v->img_id != '' ) {
                        $arr = array();
                        $arr['tech_resource_id'] = $tech_resource_id;
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
                    TechResourceImagesMap::insert($imageMap);
                }
            }

            if( $request->has('techpdf') ) {
                $pdf = $request->file('techpdf');
                $real_path = $pdf->getRealPath();
                $file_orgname = $pdf->getClientOriginalName();
                $file_size = $pdf->getSize();
                $file_ext = strtolower($pdf->getClientOriginalExtension());
                $file_newname = "file"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
                $destinationPath = public_path('/uploads/files/media_files');
                $pdf->move($destinationPath, $file_newname);

                $FilesMaster = new FilesMaster;
                $FilesMaster->file = $file_newname;
                $FilesMaster->size = $file_size;
                $FilesMaster->extension = $file_ext;
                $FilesMaster->created_by = Auth::user()->id;
                $FilesMaster->file_type = '1';
                if( $FilesMaster->save() ) {
                    $file_id = $FilesMaster->id;
                    $TechResourceFilesMap = new TechResourceFilesMap;
                    $TechResourceFilesMap->tech_resource_id = $tech_resource_id;
                    $TechResourceFilesMap->file_id = $file_id;
                    $TechResourceFilesMap->file_type = 'MAIN_FILE';
                    $TechResourceFilesMap->save();
                }
            }

	    	/*TechResourcePersonaMap::where('tech_resource_id', '=', $tech_resource_id)->delete();
	    	if( $request->has('persona_id') ) {
	    		foreach( $request->input('persona_id') as $cats ) {
	    			$arr = array();
	    			$arr['tech_resource_id'] = $tech_resource_id;
	    			$arr['persona_id'] = $cats;
	    			array_push( $personasMap, $arr );
	    		}
	    		if( !empty($personasMap) ) {
	    			TechResourcePersonaMap::insert( $personasMap );
	    		}
	    	}*/

	    	/*TechResourceIndustriesMap::where('tech_resource_id', '=', $tech_resource_id)->delete();
	    	if( $request->has('industry_id') ) {
	    		foreach( $request->input('industry_id') as $cats ) {
	    			$arr = array();
	    			$arr['tech_resource_id'] = $tech_resource_id;
	    			$arr['industry_id'] = $cats;
	    			array_push( $industriesMap, $arr );
	    		}
	    		if( !empty($industriesMap) ) {
	    			TechResourceIndustriesMap::insert( $industriesMap );
	    		}
	    	}*/

	    	TechResourceProcatMap::where('tech_resource_id', '=', $tech_resource_id)->delete();
	    	if( $request->has('product_category_id') ) {
	    		foreach( $request->input('product_category_id') as $cats ) {
                    if($cats != '' && $cats != '0') {
	    			    $arr = array();
	    			    $arr['tech_resource_id'] = $tech_resource_id;
	    			    $arr['product_category_id'] = $cats;
	    			    array_push( $procatsMap, $arr );
                    }
	    		}
	    		if( !empty($procatsMap) ) {
	    			TechResourceProcatMap::insert( $procatsMap );
	    		}
	    	}
	    return back()->with('msg', 'Technical Resource Updated Successfully.')
    	->with('msg_class', 'alert alert-success');
    	}
    return back()->with('msg', 'Something Went Wrong')
    ->with('msg_class', 'alert alert-danger');
   	}

   	public function delete($tr_id) {

   		$ck = TechResource::find($tr_id);
    	if( isset($ck) && !empty($ck) ) {
    		$ck->status = '3';
    		$res = $ck->save();
    		if( isset($res) && $res == 1 ) {
    			
    			//CmsLinks::where('table_type', '=', 'TECH_RESOURCE')->where('table_id', '=', $tr_id)->delete();
    			TechResourceProcatMap::where('tech_resource_id', '=', $tr_id)->delete();
    			//TechResourceIndustriesMap::where('tech_resource_id', '=', $tr_id)->delete();
    			//TechResourcePersonaMap::where('tech_resource_id', '=', $tr_id)->delete();
    			TechResourceImagesMap::where('tech_resource_id', '=', $tr_id)->delete();
    			TechResourceFilesMap::where('tech_resource_id', '=', $tr_id)->delete();

                //delete_navigation($tr_id, 'TECH_RESOURCE');
                //PageBuilder::where('table_id', '=', $tr_id)->where('table_type', '=', 'TECH_RESOURCE')->delete();
    			
    			return back()->with('msg', 'Technical Resource Deleted Successfully.')
    			->with('msg_class', 'alert alert-success');
    		}
    	}

    	return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
   	}


    /******************************** Language *************************************/

    public function addEditCatLanguage( $parent_language_id, $child_language_id = '' ) {
        $DataBag = array();
        $DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'techResManagement';
        $DataBag['childMenu'] = 'addPersona';
        $DataBag['parentLngCont'] = Personas::findorFail($parent_language_id);
        if( $child_language_id != '' ) {
            $DataBag['persona'] = Personas::findorFail($child_language_id);
            $DataBag['pageBuilderData'] = $DataBag['persona'];
        }
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));
        return view('dashboard.technical_resource.addedit_language_persona', $DataBag);
    }

    public function addEditCatLanguagePost( Request $request, $parent_language_id, $child_language_id = '' ) {

        if( $child_language_id != '' && $child_language_id != null ) {

            $insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

            $Personas = Personas::find($child_language_id);
            $Personas->name = trim(ucfirst($request->input('name')));
            $Personas->slug = trim($request->input('slug'));
            $Personas->page_content = htmlentities( trim($request->input('page_content')), ENT_QUOTES);
            $Personas->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
            $Personas->updated_by = Auth::user()->id;

            $Personas->meta_title = trim($request->input('meta_title'));
            $Personas->meta_desc = trim($request->input('meta_desc'));
            $Personas->meta_keyword = trim($request->input('meta_keyword'));
            $Personas->canonical_url = trim($request->input('canonical_url'));
            $Personas->lng_tag = trim($request->input('lng_tag'));
            $Personas->follow = trim($request->input('follow'));
            $Personas->index_tag = trim($request->input('index_tag'));

            if( $Personas->save() ) {
                
                CmsLinks::where('table_type', '=', 'PERSONA')->where('table_id', '=', $child_language_id)
                ->update( ['slug_url' => trim($request->input('slug'))] );

                /** Need For Page Builder -- Update Time **/
                $cmsInfo = CmsLinks::where('table_id', '=', $child_language_id)->where('table_type', '=', 'PERSONA')->first();
                if( !empty($cmsInfo) ) {
                    
                    /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                    update_page_builder($insert_id, $cmsInfo->id, $child_language_id, 'PERSONA');

                }
                /** End Page Builder **/
                
                return back()->with('msg', 'Persona Updated Succesfully')
                ->with('msg_class', 'alert alert-success');
            }
        }

        if( $child_language_id == '' ) {

            $insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

            $Personas = new Personas;
            $Personas->name = trim(ucfirst($request->input('name')));
            $Personas->slug = trim($request->input('slug'));
            $Personas->page_content = htmlentities( trim($request->input('page_content')), ENT_QUOTES);
            $Personas->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
            $Personas->created_by = Auth::user()->id;
            $Personas->language_id = trim( $request->input('language_id') );
            $Personas->parent_language_id = $parent_language_id;

            $Personas->insert_id = $insert_id;
            $Personas->meta_title = trim($request->input('meta_title'));
            $Personas->meta_desc = trim($request->input('meta_desc'));
            $Personas->meta_keyword = trim($request->input('meta_keyword'));
            $Personas->canonical_url = trim($request->input('canonical_url'));
            $Personas->lng_tag = trim($request->input('lng_tag'));
            $Personas->follow = trim($request->input('follow'));
            $Personas->index_tag = trim($request->input('index_tag'));

            if( $Personas->save() ) {
                
                $persona_id = $Personas->id;

                $CmsLinks = new CmsLinks;
                $CmsLinks->table_id = $persona_id;
                $CmsLinks->slug_url = trim($request->input('slug'));
                $CmsLinks->table_type = 'PERSONA';
                $CmsLinks->save();

                $cms_link_id = $CmsLinks->id; // Need for page builder as parameter

                /** For Page Builder -- Insert Time **/
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                update_page_builder($insert_id, $cms_link_id, $persona_id, 'PERSONA');
                /** End Page Builder **/

                return redirect()->route('editPersona', array('id' => $parent_language_id))
                ->with('msg', 'Persona Created Succesfully')
                ->with('msg_class', 'alert alert-success');
            }
        }

        return back();
    }

    public function deleteCatLanguage( $parent_language_id, $child_language_id ) {

        Personas::find($child_language_id)->delete();
        CmsLinks::where('table_type', '=', 'PERSONA')->where('table_id', '=', $child_language_id)->delete();
        delete_navigation($child_language_id, 'PERSONA');
        PageBuilder::where('table_id', '=', $child_language_id)->where('table_type', '=', 'PERSONA')->delete();

        return redirect()->route('editPersona', array('id' => $parent_language_id))
        ->with('msg', 'Persona Deleted Succesfully')
        ->with('msg_class', 'alert alert-success');
    }

    public function addEditLanguage( $parent_language_id, $child_language_id = '' ) {

        $DataBag = array();
        $DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'techResManagement';
        $DataBag['childMenu'] = 'addTechRes';
        $DataBag['parentLngCont'] = TechResource::findorFail($parent_language_id);
        if( $child_language_id != '' ) {
            $DataBag['resource'] = TechResource::findorFail($child_language_id);
            $DataBag['pageBuilderData'] = $DataBag['resource'];
        }
        //$DataBag['allPersonas'] = Personas::where('status', '!=', '3')->orderBy('name', 'asc')->get();
        //$DataBag['allIndustries'] = Industries::where('status', '!=', '3')->orderBy('name', 'asc')->get();
        $DataBag['allProCats'] = ProductCategories::where('status', '!=', '3')->where('parent_id', '=', '0')->orderBy('name', 'asc')->get();
        $DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
        $DataBag['insert_id'] = md5(microtime(TRUE));

        return view('dashboard.technical_resource.addedit_language', $DataBag);
    }

    public function addEditLanguagePost( Request $request, $parent_language_id, $child_language_id = '' ) {

        if( $child_language_id != '' && $child_language_id != null ) {

            $insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

            $personasMap = array();
            $industriesMap = array();
            $procatsMap = array();

            $TechResource = TechResource::find($child_language_id);
            $TechResource->name = trim( ucfirst($request->input('name')) );
            //$TechResource->slug = trim($request->input('slug'));
            $TechResource->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
            $TechResource->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $TechResource->created_by = Auth::user()->id;

            $TechResource->tab_section = trim( $request->input('tab_section') );

            /*$TechResource->meta_title = trim($request->input('meta_title'));
            $TechResource->meta_desc = trim($request->input('meta_desc'));
            $TechResource->meta_keyword = trim($request->input('meta_keyword'));
            $TechResource->canonical_url = trim($request->input('canonical_url'));
            $TechResource->lng_tag = trim($request->input('lng_tag'));
            $TechResource->follow = trim($request->input('follow'));
            $TechResource->index_tag = trim($request->input('index_tag'));*/
            
            $techResImageJson = json_decode( trim( $request->input('main_image_infos') ) );

            $resx = $TechResource->save();
            if( isset($resx) && $resx == 1 ) {

                $tech_resource_id = $child_language_id;

                /*CmsLinks::where('table_type', '=', 'TECH_RESOURCE')->where('table_type', '=', $tech_resource_id)
                ->update( ['slug_url' => trim($request->input('slug'))] );*/

                /** Need For Page Builder -- Update Time **/
                //$cmsInfo = CmsLinks::where('table_id', '=', $tech_resource_id)->where('table_type', '=', 'TECH_RESOURCE')->first();
                //if( !empty($cmsInfo) ) {
                    
                    /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                    //update_page_builder($insert_id, $cmsInfo->id, $tech_resource_id, 'TECH_RESOURCE');

                //}
                /** End Page Builder **/


                if( !empty($techResImageJson) ) {
                    $imageMap = array();
                    foreach ($techResImageJson as $v) {
                        if( $v->img_id != '' ) {
                            $arr = array();
                            $arr['tech_resource_id'] = $tech_resource_id;
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
                        TechResourceImagesMap::insert($imageMap);
                    }
                }

                if( $request->has('techpdf') ) {
                    $pdf = $request->file('techpdf');
                    $real_path = $pdf->getRealPath();
                    $file_orgname = $pdf->getClientOriginalName();
                    $file_size = $pdf->getSize();
                    $file_ext = strtolower($pdf->getClientOriginalExtension());
                    $file_newname = "file"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
                    $destinationPath = public_path('/uploads/files/media_files');
                    $pdf->move($destinationPath, $file_newname);

                    $FilesMaster = new FilesMaster;
                    $FilesMaster->file = $file_newname;
                    $FilesMaster->size = $file_size;
                    $FilesMaster->extension = $file_ext;
                    $FilesMaster->created_by = Auth::user()->id;
                    $FilesMaster->file_type = '1';
                    if( $FilesMaster->save() ) {
                        $file_id = $FilesMaster->id;
                        $TechResourceFilesMap = new TechResourceFilesMap;
                        $TechResourceFilesMap->tech_resource_id = $tech_resource_id;
                        $TechResourceFilesMap->file_id = $file_id;
                        $TechResourceFilesMap->file_type = 'MAIN_FILE';
                        $TechResourceFilesMap->save();
                    }
                }

                /*TechResourcePersonaMap::where('tech_resource_id', '=', $tech_resource_id)->delete();
                if( $request->has('persona_id') ) {
                    foreach( $request->input('persona_id') as $cats ) {
                        $arr = array();
                        $arr['tech_resource_id'] = $tech_resource_id;
                        $arr['persona_id'] = $cats;
                        array_push( $personasMap, $arr );
                    }
                    if( !empty($personasMap) ) {
                        TechResourcePersonaMap::insert( $personasMap );
                    }
                }*/

                /*TechResourceIndustriesMap::where('tech_resource_id', '=', $tech_resource_id)->delete();
                if( $request->has('industry_id') ) {
                    foreach( $request->input('industry_id') as $cats ) {
                        $arr = array();
                        $arr['tech_resource_id'] = $tech_resource_id;
                        $arr['industry_id'] = $cats;
                        array_push( $industriesMap, $arr );
                    }
                    if( !empty($industriesMap) ) {
                        TechResourceIndustriesMap::insert( $industriesMap );
                    }
                }*/

                TechResourceProcatMap::where('tech_resource_id', '=', $tech_resource_id)->delete();
                if( $request->has('product_category_id') ) {
                    foreach( $request->input('product_category_id') as $cats ) {
                        if( $cats != '0' && $cats != '') {
                            $arr = array();
                            $arr['tech_resource_id'] = $tech_resource_id;
                            $arr['product_category_id'] = $cats;
                            array_push( $procatsMap, $arr );
                        }
                    }
                    if( !empty($procatsMap) ) {
                        TechResourceProcatMap::insert( $procatsMap );
                    }
                }
                return back()->with('msg', 'Technical Resource Updated Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
        }

        if( $child_language_id == '' ) {
            
            $insert_id = trim( $request->input('insert_id') ); // For Page Builder -- Update time

            $personasMap = array();
            $industriesMap = array();
            $procatsMap = array();

            $TechResource = new TechResource;
            $TechResource->name = trim( ucfirst($request->input('name')) );
            //$TechResource->slug = trim($request->input('slug'));
            $TechResource->description = trim( htmlentities($request->input('description'), ENT_QUOTES) );
            $TechResource->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
            $TechResource->created_by = Auth::user()->id;
            $TechResource->language_id = trim( $request->input('language_id') );
            $TechResource->parent_language_id = $parent_language_id;

            $TechResource->tab_section = trim( $request->input('tab_section') );

            /*$TechResource->insert_id = $insert_id;
            $TechResource->meta_title = trim($request->input('meta_title'));
            $TechResource->meta_desc = trim($request->input('meta_desc'));
            $TechResource->meta_keyword = trim($request->input('meta_keyword'));
            $TechResource->canonical_url = trim($request->input('canonical_url'));
            $TechResource->lng_tag = trim($request->input('lng_tag'));
            $TechResource->follow = trim($request->input('follow'));
            $TechResource->index_tag = trim($request->input('index_tag'));*/

            $techResImageJson = json_decode( trim( $request->input('main_image_infos') ) );

            $resx = $TechResource->save();
            if( isset($resx) && $resx == 1 ) {

                $tech_resource_id = $TechResource->id;

                //$CmsLinks = new CmsLinks;
                //$CmsLinks->table_id = $tech_resource_id;
                //$CmsLinks->slug_url = trim($request->input('slug'));
                //$CmsLinks->table_type = 'TECH_RESOURCE';
                //$CmsLinks->save();

                //$cms_link_id = $CmsLinks->id; // Need for page builder as parameter

                /** For Page Builder -- Insert Time **/
                /* Format :: update_page_builder($insert_id, $cms_link_id, $table_id, $table_type) */
                //update_page_builder($insert_id, $cms_link_id, $tech_resource_id, 'TECH_RESOURCE');
                /** End Page Builder **/


                if( !empty($techResImageJson) ) {
                    $imageMap = array();
                    foreach ($techResImageJson as $v) {
                        if( $v->img_id != '' ) {
                            $arr = array();
                            $arr['tech_resource_id'] = $tech_resource_id;
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
                        TechResourceImagesMap::insert($imageMap);
                    }
                }

                if( $request->has('techpdf') ) {
                    $pdf = $request->file('techpdf');
                    $real_path = $pdf->getRealPath();
                    $file_orgname = $pdf->getClientOriginalName();
                    $file_size = $pdf->getSize();
                    $file_ext = strtolower($pdf->getClientOriginalExtension());
                    $file_newname = "file"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
                    $destinationPath = public_path('/uploads/files/media_files');
                    $pdf->move($destinationPath, $file_newname);

                    $FilesMaster = new FilesMaster;
                    $FilesMaster->file = $file_newname;
                    $FilesMaster->size = $file_size;
                    $FilesMaster->extension = $file_ext;
                    $FilesMaster->created_by = Auth::user()->id;
                    $FilesMaster->file_type = '1';
                    if( $FilesMaster->save() ) {
                        $file_id = $FilesMaster->id;
                        $TechResourceFilesMap = new TechResourceFilesMap;
                        $TechResourceFilesMap->tech_resource_id = $tech_resource_id;
                        $TechResourceFilesMap->file_id = $file_id;
                        $TechResourceFilesMap->file_type = 'MAIN_FILE';
                        $TechResourceFilesMap->save();
                    }
                }

                /*if( $request->has('persona_id') ) {
                    foreach( $request->input('persona_id') as $cats ) {
                        $arr = array();
                        $arr['tech_resource_id'] = $tech_resource_id;
                        $arr['persona_id'] = $cats;
                        array_push( $personasMap, $arr );
                    }
                    if( !empty($personasMap) ) {
                        TechResourcePersonaMap::insert( $personasMap );
                    }
                }*/

                /*if( $request->has('industry_id') ) {
                    foreach( $request->input('industry_id') as $cats ) {
                        $arr = array();
                        $arr['tech_resource_id'] = $tech_resource_id;
                        $arr['industry_id'] = $cats;
                        array_push( $industriesMap, $arr );
                    }
                    if( !empty($industriesMap) ) {
                        TechResourceIndustriesMap::insert( $industriesMap );
                    }
                }*/

                if( $request->has('product_category_id') ) {
                    foreach( $request->input('product_category_id') as $cats ) {
                        if( $cats != '' && $cats != '0' ) {
                            $arr = array();
                            $arr['tech_resource_id'] = $tech_resource_id;
                            $arr['product_category_id'] = $cats;
                            array_push( $procatsMap, $arr );
                        }
                    }
                    if( !empty($procatsMap) ) {
                        TechResourceProcatMap::insert( $procatsMap );
                    }
                }
                return redirect()->route('editResource', array('id' => $parent_language_id))
                ->with('msg', 'Technical Resource Created Successfully.')
                ->with('msg_class', 'alert alert-success');
            }
        }

        return back();
    }

    public function deleteLanguage( $parent_language_id, $child_language_id ) {

        TechResource::find($child_language_id)->delete();
        //CmsLinks::where('table_type', '=', 'TECH_RESOURCE')->where('table_id', '=', $child_language_id)->delete();
        TechResourceProcatMap::where('tech_resource_id', '=', $child_language_id)->delete();
        //TechResourceIndustriesMap::where('tech_resource_id', '=', $child_language_id)->delete();
        //TechResourcePersonaMap::where('tech_resource_id', '=', $child_language_id)->delete();
        TechResourceImagesMap::where('tech_resource_id', '=', $child_language_id)->delete();
        TechResourceFilesMap::where('tech_resource_id', '=', $child_language_id)->delete();

        //delete_navigation($child_language_id, 'TECH_RESOURCE');
        //PageBuilder::where('table_id', '=', $child_language_id)->where('table_type', '=', 'TECH_RESOURCE')->delete();

        return redirect()->route('editResource', array('id' => $parent_language_id))
        ->with('msg', 'Technical Resource Deleted Successfully.')
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
                        $TechResource = TechResource::find($id);
                        $TechResource->status = '1';
                        $TechResource->save();
                    }
                    $msg = 'Technical Resource Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $TechResource = TechResource::find($id);
                        $TechResource->status = '2';
                        $TechResource->save();
                    }
                    $msg = 'Technical Resource Deactivated Succesfully.';
                    break;

                case 'delete':
                    foreach($idsArr as $id) {
                        $TechResource = TechResource::find($id);
                        $TechResource->status = '3';
                        $TechResource->save();
                        //CmsLinks::where('table_type', '=', 'TECH_RESOURCE')->where('table_id', '=', $id)->delete();
                        TechResourceProcatMap::where('tech_resource_id', '=', $id)->delete();
                        //TechResourceIndustriesMap::where('tech_resource_id', '=', $id)->delete();
                        //TechResourcePersonaMap::where('tech_resource_id', '=', $id)->delete();
                        TechResourceImagesMap::where('tech_resource_id', '=', $id)->delete();
                        TechResourceFilesMap::where('tech_resource_id', '=', $id)->delete();

                        //delete_navigation($id, 'TECH_RESOURCE');
                        //PageBuilder::where('table_id', '=', $id)->where('table_type', '=', 'TECH_RESOURCE')->delete();
                    }
                    $msg = 'Technical Resource Deleted Succesfully.';
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
                        $Personas = Personas::find($id);
                        $Personas->status = '1';
                        $Personas->save();
                    }
                    $msg = 'Personas Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $Personas = Personas::find($id);
                        $Personas->status = '2';
                        $Personas->save();
                    }
                    $msg = 'Personas Deactivated Succesfully.';
                    break;

                case 'delete':
                    foreach($idsArr as $id) {
                        $Personas = Personas::find($id);
                        $Personas->status = '3';
                        $Personas->save();
                        CmsLinks::where('table_type', '=', 'PERSONA')->where('table_id', '=', $id)->delete();

                        delete_navigation($id, 'PERSONA');
                        PageBuilder::where('table_id', '=', $id)->where('table_type', '=', 'PERSONA')->delete();
                    }
                    $msg = 'Personas Deleted Succesfully.';
                    break;
            }
            return back()->with('msg', $msg)->with('msg_class', 'alert alert-success');
        }
        return back();
    }


    public function extraConten() {

        $DataBag = array();
        $DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'techResManagement';
        $DataBag['childMenu'] = 'tresExCont';
        $DataBag['extraCont'] = MediaExtraContent::where('type', '=', 'TECHRES')->first();
        return view('dashboard.technical_resource.extra_content', $DataBag);
    }

    public function extraContentSave(Request $request) {
        
        $MediaExtraContent = MediaExtraContent::where('type', '=', 'TECHRES')->first();

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
            $MediaExtraContent->type = 'TECHRES';

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
