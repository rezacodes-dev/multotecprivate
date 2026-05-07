<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CmsLinks;
use App\Models\Media\Images;
use App\Models\Media\FilesMaster;
use App\Models\Countries;
use App\Models\Career\Career;
use App\Models\Career\CareerFilesMap;
use App\Models\Career\CareerImagesMap;
use App\Models\Languages;
use File;
use Image;
use Auth;
use DB;


class CareerController extends Controller
{
    
    public function index() {
    	$DataBag = array();
    	$DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'career';
    	$DataBag['childMenu'] = 'allJobs';
    	$DataBag['allJobs'] = Career::where('status', '!=', '3')->where('parent_language_id', '=', '0')
    	->orderBy('created_at', 'desc')->get();
    	return view('dashboard.careers.index', $DataBag);
    }

    public function addJob() {
    	$DataBag = array();
    	$DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'career';
    	$DataBag['childMenu'] = 'addJob';
    	$DataBag['allCountries'] = Countries::where('status', '!=', '3')->orderBy('country_name', 'asc')->get();
    	$DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
    	return view('dashboard.careers.create', $DataBag);
    }

    public function saveJob(Request $request) {

    	$country_id = 0 ;
    	$imagesMap = array();
    	$filesMap = array();

    	$Career = new Career;
    	$Career->name = trim( ucfirst($request->input('name')) );
    	$Career->designation = trim( ucfirst($request->input('designation')) );
    	$Career->experience = trim( ucfirst($request->input('experience')) );
    	$Career->job_location = trim( ucfirst($request->input('job_location')) );
        $Career->slug = trim($request->input('slug'));
        $Career->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
        $Career->mob_page_content = trim( htmlentities($request->input('mob_page_content'), ENT_QUOTES) );
        $Career->status = trim($request->input('status'));
        $Career->publish_status = trim($request->input('publish_status'));
        $Career->expiry_date = date( 'Y-m-d', strtotime( trim($request->input('expiry_date')) ) );
        $Career->meta_title = trim($request->input('meta_title'));
        $Career->meta_keywords = trim($request->input('meta_keywords'));
        $Career->meta_description = trim($request->input('meta_description'));
        $Career->created_by = Auth::user()->id;
        $Career->language_id = trim( $request->input('language_id') );

        if( $request->has('country_id') && $request->input('country_id') != '' ) {
            $country_id = trim($request->input('country_id'));
        }
        $Career->country_id = $country_id;

        if( $Career->save() ) {
        	$career_id = $Career->id;

            $CmsLinks = new CmsLinks;
            $CmsLinks->table_id = $career_id;
            $CmsLinks->slug_url = trim($request->input('slug'));
            $CmsLinks->table_type = 'CAREER';
            $CmsLinks->save();

            if( $request->hasFile('images') ) {
	    		foreach( $request->file('images') as $img ) {
	    			$Images = new Images;
		    		$real_path = $img->getRealPath();
		            $file_orgname = $img->getClientOriginalName();
		            $file_size = $img->getSize();
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
		        		$arr = array();
		        		$arr['career_id'] = $career_id;
		        		$arr['image_id'] = $Images->id;
		        		$arr['image_type'] = '';
		        		array_push( $imagesMap, $arr );
		        	}
	    		}
	    		if( !empty($imagesMap) ) {
	    			CareerImagesMap::insert( $imagesMap );
	    		}
	    	}

	    	if( $request->hasFile('files') ) {
	    		foreach( $request->file('files') as $file ) {
	    			$FilesMaster = new FilesMaster;
		    		$real_path = $file->getRealPath();
		            $file_orgname = $file->getClientOriginalName();
		            $file_size = $file->getSize();
		            $file_ext = strtolower($file->getClientOriginalExtension());
		            $file_newname = "file"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
		            $destinationPath = public_path('/uploads/files/media_files');
		        	$file->move($destinationPath, $file_newname);
		        	$FilesMaster->file = $file_newname;
		        	$FilesMaster->size = $file_size;
		        	$FilesMaster->extension = $file_ext;
		        	$FilesMaster->created_by = Auth::user()->id;
		        	if( $FilesMaster->save() ) {
		        		$arr = array();
		        		$arr['career_id'] = $career_id;
		        		$arr['file_id'] = $FilesMaster->id;
		        		$arr['file_type'] = 'OTHER_FILE';
		        		array_push( $filesMap, $arr );
		        	}
	    		}
	    		if( !empty($filesMap) ) {
	    			CareerFilesMap::insert( $filesMap );
	    		}
	    	}
	    return back()->with('msg', 'Career Job Created Succesfully.')->with('msg_class', 'alert alert-success');
        }
    return back()->with('msg', 'Something Went Wrong.')->with('msg_class', 'alert alert-danger');
    }

    public function deleteJob($career_id) {
    	$ck = Career::find($career_id);
    	if( isset($ck) && !empty($ck) ) {
    		$ck->status = '3';
    		if( $ck->save() ) {
    			CareerImagesMap::where('career_id', '=', $career_id)->delete();
    			CareerFilesMap::where('career_id', '=', $career_id)->delete();
    			CmsLinks::where('table_type', '=', 'CAREER')->where('table_id', '=', $career_id)->delete();
    			return back()->with('msg', 'Career Job Deleted Successfully.')
    			->with('msg_class', 'alert alert-success');
    		}
    	}

    	return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function editJob($career_id) {
    	$DataBag = array();
    	$DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'career';
    	$DataBag['childMenu'] = 'addJob';
    	$DataBag['job'] = Career::findOrFail($career_id);
    	$DataBag['allCountries'] = Countries::where('status', '!=', '3')->orderBy('country_name', 'asc')->get();
    	$DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
    	return view('dashboard.careers.create', $DataBag);
    }

    public function updateJob(Request $request, $career_id) {

    	$country_id = 0 ;
    	$imagesMap = array();
    	$filesMap = array();

    	$Career = Career::find($career_id);
    	$Career->name = trim( ucfirst($request->input('name')) );
    	$Career->designation = trim( ucfirst($request->input('designation')) );
    	$Career->experience = trim( ucfirst($request->input('experience')) );
    	$Career->job_location = trim( ucfirst($request->input('job_location')) );
        $Career->slug = trim($request->input('slug'));
        $Career->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
        $Career->mob_page_content = trim( htmlentities($request->input('mob_page_content'), ENT_QUOTES) );
        $Career->status = trim($request->input('status'));
        $Career->publish_status = trim($request->input('publish_status'));
        $Career->expiry_date = date( 'Y-m-d', strtotime( trim($request->input('expiry_date')) ) );
        $Career->meta_title = trim($request->input('meta_title'));
        $Career->meta_keywords = trim($request->input('meta_keywords'));
        $Career->meta_description = trim($request->input('meta_description'));
        $Career->updated_by = Auth::user()->id;
        if( $request->has('country_id') && $request->input('country_id') != '' ) {
            $country_id = trim($request->input('country_id'));
        }
        $Career->country_id = $country_id;

        if( $Career->save() ) {

            CmsLinks::where('table_type', '=', 'CAREER')->where('table_id', '=', $career_id)
            ->update( [ 'slug_url' => trim($request->input('slug')) ] );

            if( $request->hasFile('images') ) {
	    		foreach( $request->file('images') as $img ) {
	    			$Images = new Images;
		    		$real_path = $img->getRealPath();
		            $file_orgname = $img->getClientOriginalName();
		            $file_size = $img->getSize();
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
		        		$arr = array();
		        		$arr['career_id'] = $career_id;
		        		$arr['image_id'] = $Images->id;
		        		$arr['image_type'] = '';
		        		array_push( $imagesMap, $arr );
		        	}
	    		}
	    		if( !empty($imagesMap) ) {
	    			CareerImagesMap::insert( $imagesMap );
	    		}
	    	}

	    	if( $request->hasFile('files') ) {
	    		foreach( $request->file('files') as $file ) {
	    			$FilesMaster = new FilesMaster;
		    		$real_path = $file->getRealPath();
		            $file_orgname = $file->getClientOriginalName();
		            $file_size = $file->getSize();
		            $file_ext = strtolower($file->getClientOriginalExtension());
		            $file_newname = "file"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
		            $destinationPath = public_path('/uploads/files/media_files');
		        	$file->move($destinationPath, $file_newname);
		        	$FilesMaster->file = $file_newname;
		        	$FilesMaster->size = $file_size;
		        	$FilesMaster->extension = $file_ext;
		        	$FilesMaster->created_by = Auth::user()->id;
		        	if( $FilesMaster->save() ) {
		        		$arr = array();
		        		$arr['career_id'] = $career_id;
		        		$arr['file_id'] = $FilesMaster->id;
		        		$arr['file_type'] = 'OTHER_FILE';
		        		array_push( $filesMap, $arr );
		        	}
	    		}
	    		if( !empty($filesMap) ) {
	    			CareerFilesMap::insert( $filesMap );
	    		}
	    	}
	    return back()->with('msg', 'Career Job Updated Succesfully.')->with('msg_class', 'alert alert-success');
        }
    return back()->with('msg', 'Something Went Wrong.')->with('msg_class', 'alert alert-danger');
    }

    public function addEditLanguage($parent_language_id, $child_language_id = '') {

    	$DataBag = array();
    	$DataBag['parentMenu'] = 'media';
        $DataBag['subMenu'] = 'career';
    	$DataBag['childMenu'] = 'addJob';
    	$DataBag['parentLngCont'] = Career::findOrFail($parent_language_id);
    	if( $child_language_id != '' ) {
    		$DataBag['job'] = Career::findOrFail($child_language_id);
    	}
    	$DataBag['allCountries'] = Countries::where('status', '!=', '3')->orderBy('country_name', 'asc')->get();
    	$DataBag['languages'] = Languages::where('status', '=', '1')->orderBy('is_default', 'desc')->get();
    	return view('dashboard.careers.addedit_language', $DataBag);
    }

    public function addEditLanguagePost(Request $request, $parent_language_id, $child_language_id = '') {

    	if( $child_language_id != '' && $child_language_id != null ) {

    		$country_id = 0 ;
	    	$imagesMap = array();
	    	$filesMap = array();

	    	$Career = Career::find($child_language_id);
	    	$Career->name = trim( ucfirst($request->input('name')) );
	    	$Career->designation = trim( ucfirst($request->input('designation')) );
	    	$Career->experience = trim( ucfirst($request->input('experience')) );
	    	$Career->job_location = trim( ucfirst($request->input('job_location')) );
	        $Career->slug = trim($request->input('slug'));
	        $Career->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
	        $Career->mob_page_content = trim( htmlentities($request->input('mob_page_content'), ENT_QUOTES) );
	        $Career->status = trim($request->input('status'));
	        $Career->publish_status = trim($request->input('publish_status'));
	        $Career->expiry_date = date( 'Y-m-d', strtotime( trim($request->input('expiry_date')) ) );
	        $Career->meta_title = trim($request->input('meta_title'));
	        $Career->meta_keywords = trim($request->input('meta_keywords'));
	        $Career->meta_description = trim($request->input('meta_description'));
	        $Career->updated_by = Auth::user()->id;
	        if( $request->has('country_id') && $request->input('country_id') != '' ) {
	            $country_id = trim($request->input('country_id'));
	        }
	        $Career->country_id = $country_id;

	        if( $Career->save() ) {

	        	$career_id = $child_language_id;

	            CmsLinks::where('table_type', '=', 'CAREER')->where('table_id', '=', $career_id)
	            ->update( [ 'slug_url' => trim($request->input('slug')) ] );

	            if( $request->hasFile('images') ) {
		    		foreach( $request->file('images') as $img ) {
		    			$Images = new Images;
			    		$real_path = $img->getRealPath();
			            $file_orgname = $img->getClientOriginalName();
			            $file_size = $img->getSize();
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
			        		$arr = array();
			        		$arr['career_id'] = $career_id;
			        		$arr['image_id'] = $Images->id;
			        		$arr['image_type'] = '';
			        		array_push( $imagesMap, $arr );
			        	}
		    		}
		    		if( !empty($imagesMap) ) {
		    			CareerImagesMap::insert( $imagesMap );
		    		}
		    	}

		    	if( $request->hasFile('files') ) {
		    		foreach( $request->file('files') as $file ) {
		    			$FilesMaster = new FilesMaster;
			    		$real_path = $file->getRealPath();
			            $file_orgname = $file->getClientOriginalName();
			            $file_size = $file->getSize();
			            $file_ext = strtolower($file->getClientOriginalExtension());
			            $file_newname = "file"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
			            $destinationPath = public_path('/uploads/files/media_files');
			        	$file->move($destinationPath, $file_newname);
			        	$FilesMaster->file = $file_newname;
			        	$FilesMaster->size = $file_size;
			        	$FilesMaster->extension = $file_ext;
			        	$FilesMaster->created_by = Auth::user()->id;
			        	if( $FilesMaster->save() ) {
			        		$arr = array();
			        		$arr['career_id'] = $career_id;
			        		$arr['file_id'] = $FilesMaster->id;
			        		$arr['file_type'] = 'OTHER_FILE';
			        		array_push( $filesMap, $arr );
			        	}
		    		}
		    		if( !empty($filesMap) ) {
		    			CareerFilesMap::insert( $filesMap );
		    		}
		    	}
		    	return back()
		    	->with('msg', 'Career Job Updated Succesfully.')
		    	->with('msg_class', 'alert alert-success');
	        }
    	}

    	if( $child_language_id == '' ) {

    		$country_id = 0 ;
	    	$imagesMap = array();
	    	$filesMap = array();

	    	$Career = new Career;
	    	$Career->name = trim( ucfirst($request->input('name')) );
	    	$Career->designation = trim( ucfirst($request->input('designation')) );
	    	$Career->experience = trim( ucfirst($request->input('experience')) );
	    	$Career->job_location = trim( ucfirst($request->input('job_location')) );
	        $Career->slug = trim($request->input('slug'));
	        $Career->page_content = trim( htmlentities($request->input('page_content'), ENT_QUOTES) );
	        $Career->mob_page_content = trim( htmlentities($request->input('mob_page_content'), ENT_QUOTES) );
	        $Career->status = trim($request->input('status'));
	        $Career->publish_status = trim($request->input('publish_status'));
	        $Career->expiry_date = date( 'Y-m-d', strtotime( trim($request->input('expiry_date')) ) );
	        $Career->meta_title = trim($request->input('meta_title'));
	        $Career->meta_keywords = trim($request->input('meta_keywords'));
	        $Career->meta_description = trim($request->input('meta_description'));
	        $Career->created_by = Auth::user()->id;
	        $Career->language_id = trim( $request->input('language_id') );
	        $Career->parent_language_id = $parent_language_id;

	        if( $request->has('country_id') && $request->input('country_id') != '' ) {
	            $country_id = trim($request->input('country_id'));
	        }
	        $Career->country_id = $country_id;

	        if( $Career->save() ) {
	        	$career_id = $Career->id;

	            $CmsLinks = new CmsLinks;
	            $CmsLinks->table_id = $career_id;
	            $CmsLinks->slug_url = trim($request->input('slug'));
	            $CmsLinks->table_type = 'CAREER';
	            $CmsLinks->save();

	            if( $request->hasFile('images') ) {
		    		foreach( $request->file('images') as $img ) {
		    			$Images = new Images;
			    		$real_path = $img->getRealPath();
			            $file_orgname = $img->getClientOriginalName();
			            $file_size = $img->getSize();
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
			        		$arr = array();
			        		$arr['career_id'] = $career_id;
			        		$arr['image_id'] = $Images->id;
			        		$arr['image_type'] = '';
			        		array_push( $imagesMap, $arr );
			        	}
		    		}
		    		if( !empty($imagesMap) ) {
		    			CareerImagesMap::insert( $imagesMap );
		    		}
		    	}

		    	if( $request->hasFile('files') ) {
		    		foreach( $request->file('files') as $file ) {
		    			$FilesMaster = new FilesMaster;
			    		$real_path = $file->getRealPath();
			            $file_orgname = $file->getClientOriginalName();
			            $file_size = $file->getSize();
			            $file_ext = strtolower($file->getClientOriginalExtension());
			            $file_newname = "file"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
			            $destinationPath = public_path('/uploads/files/media_files');
			        	$file->move($destinationPath, $file_newname);
			        	$FilesMaster->file = $file_newname;
			        	$FilesMaster->size = $file_size;
			        	$FilesMaster->extension = $file_ext;
			        	$FilesMaster->created_by = Auth::user()->id;
			        	if( $FilesMaster->save() ) {
			        		$arr = array();
			        		$arr['career_id'] = $career_id;
			        		$arr['file_id'] = $FilesMaster->id;
			        		$arr['file_type'] = 'OTHER_FILE';
			        		array_push( $filesMap, $arr );
			        	}
		    		}
		    		if( !empty($filesMap) ) {
		    			CareerFilesMap::insert( $filesMap );
		    		}
		    	}
		    	return redirect()->route('edtJob', array('id' => $parent_language_id))
		    	->with('msg', 'Career Job Created Succesfully.')
		    	->with('msg_class', 'alert alert-success');
	        }
    	}

    	return back();
    }

    public function deleteLanguage($parent_language_id, $child_language_id) {

    	Career::find($child_language_id)->delete();
    	CareerImagesMap::where('career_id', '=', $child_language_id)->delete();
    	CareerFilesMap::where('career_id', '=', $child_language_id)->delete();
    	CmsLinks::where('table_type', '=', 'CAREER')->where('table_id', '=', $child_language_id)->delete();
    	return redirect()->route('edtJob', array('id' => $parent_language_id))
		->with('msg', 'Career Job Deleted Succesfully.')
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
                        $Career = Career::find($id);
                        $Career->status = '1';
                        $Career->save();
                    }
                    $msg = 'Job Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $Career = Career::find($id);
                        $Career->status = '2';
                        $Career->save();
                    }
                    $msg = 'Job Deactivated Succesfully.';
                    break;

                case 'delete':
                    foreach($idsArr as $id) {
                        $Career = Career::find($id);
                        $Career->status = '3';
                        $Career->save();
    					CareerImagesMap::where('career_id', '=', $id)->delete();
    					CareerFilesMap::where('career_id', '=', $id)->delete();
    					CmsLinks::where('table_type', '=', 'CAREER')->where('table_id', '=', $id)->delete();
                    }
                    $msg = 'Job Deleted Succesfully.';
                    break;
            }
            return back()->with('msg', $msg)->with('msg_class', 'alert alert-success');
        }
        return back();
    }
}
