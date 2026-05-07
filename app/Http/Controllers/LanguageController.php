<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Languages;
use Image;
use Auth;

class LanguageController extends Controller
{
    
    public function index() {
		$dataBag = array();
		$dataBag['parentMenu'] = "settings";
		$dataBag['childMenu'] = "lng";
		$dataBag['languages'] = Languages::where('status', '!=', '3')->orderBy('name', 'asc')->get();
		return view('dashboard.language.index', $dataBag);
    }

    public function add() {
    	$dataBag = array();
		$dataBag['parentMenu'] = "settings";
		$dataBag['childMenu'] = "lng";
		return view('dashboard.language.create', $dataBag);
    }

    public function save(Request $request) {

        $request->validate([
            
            'code' => 'required|unique:languages,code'
        ],[
        
            'code.unique' => 'Code Already Exist.'
        ]);

    	$Languages = new Languages;
    	$Languages->name = trim($request->input('name'));
    	$Languages->code = trim($request->input('code'));
    	$Languages->not_msg = trim($request->input('not_msg'));
    	$Languages->status = trim($request->input('status'));
    	$Languages->header_name = trim($request->input('header_name'));
    	$Languages->page_content = htmlentities(trim($request->input('page_content')), ENT_QUOTES);
    	$Languages->meta_title = trim($request->input('meta_title'));
    	$Languages->meta_keywords = trim($request->input('meta_keywords'));
    	$Languages->meta_description = trim($request->input('meta_description'));
    	$Languages->created_by = Auth::user()->id;
    	if($request->exists('is_default')) {
    		$Languages->is_default = trim($request->input('is_default'));
    	}
    	if( $request->hasFile('flag') ) {
    		$image = $request->file('flag');
    		$real_path = $image->getRealPath();
            $file_orgname = $image->getClientOriginalName();
            $file_size = $image->getSize();
            $file_ext = strtolower($image->getClientOriginalExtension());
            $file_newname = "flag"."_".time().".".$file_ext;

            $destinationPath = public_path('/uploads/flags');
            $original_path = $destinationPath."/original";
            $thumb_path = $destinationPath."/thumb";
            
            $img = Image::make($real_path);
        	$img->resize(32, 32, function ($constraint) {
		    	$constraint->aspectRatio();
			})->save($thumb_path.'/'.$file_newname);

        	$image->move($original_path, $file_newname);
        	$Languages->flag = $file_newname;
    	}
    	$res = $Languages->save();
    	if( $res ) {
    		if($request->exists('is_default') && $request->input('is_default') == '1') {
    			$id = $Languages->id;
    			Languages::where('id', '!=', $id)->update(['is_default' => '0']);
    		}
    		return back()->with('msg', 'Language Saved Successfully.')
    		->with('msg_class', 'alert alert-success');
    	} else {
    		return back()->with('msg', 'Something Went Wrong.')
    		->with('msg_class', 'alert alert-danger');
    	}
    }

    public function edit($id) {
    	$dataBag = array();
		$dataBag['parentMenu'] = "settings";
		$dataBag['childMenu'] = "lng";
		$dataBag['language'] = Languages::findOrFail($id);
		return view('dashboard.language.create', $dataBag);
    }

    public function update(Request $request, $id) {

        $request->validate([
            
            'code' => 'required|unique:languages,code,'. $id
        ],[
        
            'code.unique' => 'Code Already Exist.'
        ]);

    	$Languages = Languages::find($id);
    	$Languages->name = trim($request->input('name'));
    	$Languages->code = trim($request->input('code'));
    	$Languages->not_msg = trim($request->input('not_msg'));
    	$Languages->status = trim($request->input('status'));
    	$Languages->header_name = trim($request->input('header_name'));
    	$Languages->page_content = htmlentities(trim($request->input('page_content')), ENT_QUOTES);
    	$Languages->meta_title = trim($request->input('meta_title'));
    	$Languages->meta_keywords = trim($request->input('meta_keywords'));
    	$Languages->meta_description = trim($request->input('meta_description'));
    	$Languages->created_by = Auth::user()->id;
    	if($request->exists('is_default')) {
    		$Languages->is_default = trim($request->input('is_default'));
    	}
    	if( $request->hasFile('flag') ) {
    		$image = $request->file('flag');
    		$real_path = $image->getRealPath();
            $file_orgname = $image->getClientOriginalName();
            $file_size = $image->getSize();
            $file_ext = strtolower($image->getClientOriginalExtension());
            $file_newname = "flag"."_".time().".".$file_ext;

            $destinationPath = public_path('/uploads/flags');
            $original_path = $destinationPath."/original";
            $thumb_path = $destinationPath."/thumb";
            
            $img = Image::make($real_path);
        	$img->resize(32, 32, function ($constraint) {
		    	$constraint->aspectRatio();
			})->save($thumb_path.'/'.$file_newname);

        	$image->move($original_path, $file_newname);
        	$Languages->flag = $file_newname;
    	}
    	$res = $Languages->save();
    	if( $res ) {
    		if($request->exists('is_default') && $request->input('is_default') == '1') {
    			Languages::where('id', '!=', $id)->update(['is_default' => '0']);
    		}
    		return back()->with('msg', 'Language Updated Successfully.')
    		->with('msg_class', 'alert alert-success');
    	} else {
    		return back()->with('msg', 'Something Went Wrong.')
    		->with('msg_class', 'alert alert-danger');
    	}
    }

    public function delete($id) {
    	$del = Languages::findOrFail($id);
    	$res = $del->delete();
    	if( $res ) {
    		return back()->with('msg', 'Language Deleted Successfully.')
    		->with('msg_class', 'alert alert-success');
    	} else {
    		return back()->with('msg', 'Something Went Wrong.')
    		->with('msg_class', 'alert alert-danger');
    	}
    }
}
