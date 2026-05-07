<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LandingPages;
use File;
use Storage;
use Auth;
use DB;

class LandingPagesController extends Controller
{
    
    public function index() {
    	$DataBag = array();
    	$DataBag['parentMenu'] = 'landPage';
    	$DataBag['childMenu'] = 'landList';
    	$DataBag['LandingPages'] = LandingPages::orderBy('created_at', 'desc')->get();
    	return view('dashboard.landing_pages.index', $DataBag);
    }

    public function newCreate() {
    	$DataBag = array();
    	$DataBag['parentMenu'] = 'landPage';
    	$DataBag['childMenu'] = 'landNew';
    	return view('dashboard.landing_pages.create', $DataBag);
    }

    public function newCreateUpload(Request $request) {

    	if( $request->hasFile('zip') ) {
    		$slug = trim( $request->input('slug') );
    		$name = trim( $request->input('name') );
    		$zip = $request->file('zip');
    		$real_path = $zip->getRealPath();
            $file_orgname = $zip->getClientOriginalName();
            $expArr = explode('.', $file_orgname);
            $zipDirName = '';
            if( !empty($expArr) ) {
            	$zipDirName = $expArr[0];
            }
            if( $zipDirName != '' ) {
	            $file_size = $zip->getClientSize();
	            $file_ext = strtolower($zip->getClientOriginalExtension());
	            $file_newname = md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
	            $destinationPath = public_path('landing_pages_zip');
	            if( $zip->move($destinationPath, $file_newname) ) {

	            	$currentZip = public_path('landing_pages_zip/' . $file_newname);
	            	$extractZip = public_path('landing_pages');
	            	$zipper = new \Chumper\Zipper\Zipper;
	            	$zipper->make($currentZip)->extractTo( $extractZip );
	            	$zipper->close();

	            	$LandingPages = new LandingPages;
	            	$LandingPages->slug = $slug;
	            	$LandingPages->name = $name;
	            	$LandingPages->zip_name = $file_newname;
	            	$LandingPages->dir_name = $zipDirName;
	            	$LandingPages->page_name = 'index.html';
	            	$LandingPages->created_by = Auth::user()->id;
	            	if( $LandingPages->save() ) {
	            		return back()->with('msg', 'Landing Page Uploaded & Extract Successfully.')
	            		->with('msg_class', 'alert alert-success');
	            	}
	            	//$r = File::allFiles( $extractZip.'/'.$zipDirName );
	            	//echo $zipDirName;
	            	//dd($r);

	            } else {

	            }
        	} else {

        	}
    	}

    	return back();
    }

    public function delete( $id ) {

    	LandingPages::findOrFail($id)->delete();
    	return back()->with('msg', 'Landing Page Deleted Successfully.')
	    ->with('msg_class', 'alert alert-success');	
    }

    public function edit( $id ) {

    	$DataBag = array();
    	$DataBag['parentMenu'] = 'landPage';
    	$DataBag['childMenu'] = 'landNew';
    	$DataBag['land'] = LandingPages::findOrFail($id);
    	return view('dashboard.landing_pages.create', $DataBag);
    }

    public function update(Request $request, $id) {

    	$slug = trim( $request->input('slug') );
    	$name = trim( $request->input('name') );
    	if( $request->hasFile('zip') ) {
    		$zip = $request->file('zip');
    		$real_path = $zip->getRealPath();
            $file_orgname = $zip->getClientOriginalName();
            $expArr = explode('.', $file_orgname);
            $zipDirName = '';
            if( !empty($expArr) ) {
            	$zipDirName = $expArr[0];
            }
            if( $zipDirName != '' ) {
	            $file_size = $zip->getClientSize();
	            $file_ext = strtolower($zip->getClientOriginalExtension());
	            $file_newname = md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
	            $destinationPath = public_path('landing_pages_zip');
	            if( $zip->move($destinationPath, $file_newname) ) {

	            	$currentZip = public_path('landing_pages_zip/' . $file_newname);
	            	$extractZip = public_path('landing_pages');
	            	$zipper = new \Chumper\Zipper\Zipper;
	            	$zipper->make($currentZip)->extractTo( $extractZip );
	            	$zipper->close();

	            	$LandingPages = LandingPages::findOrFail($id);
	            	$LandingPages->slug = $slug;
	            	$LandingPages->name = $name;
	            	$LandingPages->zip_name = $file_newname;
	            	$LandingPages->dir_name = $zipDirName;
	            	$LandingPages->page_name = 'index.html';
	            	$LandingPages->updated_by = Auth::user()->id;
	            	if( $LandingPages->save() ) {
	            		return back()->with('msg', 'Landing Page Updated Successfully.')
	            		->with('msg_class', 'alert alert-success');
	            	}	
	            }
	        }
    	}

    	return back();
    }
}
