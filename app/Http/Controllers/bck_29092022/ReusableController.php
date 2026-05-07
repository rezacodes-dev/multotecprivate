<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReusableContent;
use App\Models\PboxReusableContent;
use Auth;

class ReusableController extends Controller
{
    
    public function index() {
    	$DataBag = array();
    	$DataBag['parentMenu'] = 'rsbC';
    	$DataBag['childMenu'] = 'rsbC_list';
    	$DataBag['contents'] = ReusableContent::where('status', '!=', '3')->orderBy('created_at', 'desc')->get();
    	return view('dashboard.reusable_content.index', $DataBag);
    }

    public function create() {
    	$DataBag = array();
    	$DataBag['parentMenu'] = 'rsbC';
    	$DataBag['childMenu'] = 'rsbC_crte';
    	return view('dashboard.reusable_content.create', $DataBag);
    }

    public function save(Request $request) {

    	$ReusableContent = new ReusableContent;
    	$ReusableContent->name = trim(ucfirst($request->input('name')));
    	$ReusableContent->content = htmlentities(trim($request->input('content')), ENT_QUOTES);
    	$ReusableContent->short_code = "[#Reusable_".md5( microtime(TRUE).rand(123456, 999999) )."#]";
    	$ReusableContent->status = trim($request->input('status'));
        $ReusableContent->title = trim($request->input('title'));
        $ReusableContent->code_key = trim($request->input('code_key'));
    	$ReusableContent->created_by = Auth::user()->id;

        if( $request->hasFile('backimg') ) {
            
            $img = $request->file('backimg');
            $real_path = $img->getRealPath();
            $file_orgname = $img->getClientOriginalName();
            $file_size = $img->getClientSize();
            $file_ext = strtolower($img->getClientOriginalExtension());
            $file_newname = "backimg"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
            $destinationPath = public_path('/uploads/files/media_images');
            $img->move($destinationPath, $file_newname);

            $ReusableContent->backimg = $file_newname;
        }


    	$res = $ReusableContent->save();
    	if( $res ) {
    		return back()->with('msg', 'Reusable Content Added Succesfully.')
    		->with('msg_class', 'alert alert-success');
    	} else {
    		return back()->with('msg', 'Something Went Wrong.')
    		->with('msg_class', 'alert alert-danger');
    	}
    }

    public function edit($id) {
    	$DataBag = array();
    	$DataBag['parentMenu'] = 'rsbC';
    	$DataBag['childMenu'] = 'rsbC_crte';
    	$DataBag['content'] = ReusableContent::findOrFail($id);
    	return view('dashboard.reusable_content.create', $DataBag);
    }

    public function update(Request $request, $id) {

    	$ReusableContent = ReusableContent::find($id);
    	if( isset($ReusableContent) && !empty($ReusableContent) ) {
	    	
	    	$ReusableContent->name = trim(ucfirst($request->input('name')));
            $ReusableContent->title = trim($request->input('title'));
	    	$ReusableContent->content = htmlentities(trim($request->input('content')), ENT_QUOTES);
	    	$ReusableContent->status = trim($request->input('status'));
	    	$ReusableContent->updated_by = Auth::user()->id;
	    	$ReusableContent->updated_at = date('Y-m-d H:i:s');

            if( $request->hasFile('backimg') ) {
            
                $img = $request->file('backimg');
                $real_path = $img->getRealPath();
                $file_orgname = $img->getClientOriginalName();
                $file_size = $img->getClientSize();
                $file_ext = strtolower($img->getClientOriginalExtension());
                $file_newname = "backimg"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
                $destinationPath = public_path('/uploads/files/media_images');
                $img->move($destinationPath, $file_newname);

                $ReusableContent->backimg = $file_newname;
            }

        
	    	$res = $ReusableContent->save();
	    	if( $res ) {
	    		return back()->with('msg', 'Reusable Content Updated Succesfully.')
	    		->with('msg_class', 'alert alert-success');
	    	} else {
	    		return back()->with('msg', 'Something Went Wrong.')
	    		->with('msg_class', 'alert alert-danger');
	    	}
	    	
    	} else {
    		return back()->with('msg', 'Something Went Wrong. ID Missmatch')
	    	->with('msg_class', 'alert alert-danger');
    	}
    }

    public function delete($id) {
        $res = ReusableContent::findOrFail($id);
        $res->status = '3';
        $del = $res->save(); 
        if( $res ) {
            return back()->with('msg', 'Reusable Content Deleted Successfully.')
            ->with('msg_class', 'alert alert-success');
        } else {
            return back()->with('msg', 'Something Went Wrong.')
            ->with('msg_class', 'alert alert-danger');
        }
    }



    public function list() {
        $DataBag = array();
        $DataBag['parentMenu'] = 'rsbC';
        $DataBag['childMenu'] = 'prod_content';
        $DataBag['contents'] = PboxReusableContent::where('status', '!=', '3')->orderBy('created_at', 'desc')->get();
        return view('dashboard.reusable_content.pbox_list', $DataBag);
    }

    public function pboxCreate() {
        $DataBag = array();
        $DataBag['parentMenu'] = 'rsbC';
        $DataBag['childMenu'] = 'prod_content';
        return view('dashboard.reusable_content.pbox_create', $DataBag); 
    }

    public function pboxCreateAction(Request $request) {

        $PboxReusableContent = new PboxReusableContent;
        $PboxReusableContent->column_key = trim($request->input('column_key'));
        $PboxReusableContent->name = trim(ucfirst($request->input('name')));
        $PboxReusableContent->content = htmlentities(trim($request->input('content')), ENT_QUOTES);
        $PboxReusableContent->status = trim($request->input('status'));

        if( $request->hasFile('backimg') ) {
            
            $img = $request->file('backimg');
            $real_path = $img->getRealPath();
            $file_orgname = $img->getClientOriginalName();
            $file_size = $img->getClientSize();
            $file_ext = strtolower($img->getClientOriginalExtension());
            $file_newname = "backimg"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
            $destinationPath = public_path('/uploads/files/media_images');
            $img->move($destinationPath, $file_newname);

            $PboxReusableContent->backimg = $file_newname;
        }

        if( $PboxReusableContent->save() ) {
            return back()->with('msg', 'Product Box Reusable Content Added Succesfully.')
            ->with('msg_class', 'alert alert-success');
        } else {
            return back()->with('msg', 'Something Went Wrong.')
            ->with('msg_class', 'alert alert-danger');
        }
    }

    public function pboxEdit($id) {
       $DataBag = array();
        $DataBag['parentMenu'] = 'rsbC';
        $DataBag['childMenu'] = 'prod_content';
        $DataBag['content'] = PboxReusableContent::findOrFail($id);
        return view('dashboard.reusable_content.pbox_create', $DataBag); 
    }

    public function pboxEditAction(Request $request, $id) {

        $PboxReusableContent = PboxReusableContent::find($id);
        $PboxReusableContent->column_key = trim($request->input('column_key'));
        $PboxReusableContent->name = trim(ucfirst($request->input('name')));
        $PboxReusableContent->content = htmlentities(trim($request->input('content')), ENT_QUOTES);
        $PboxReusableContent->status = trim($request->input('status'));

        if( $request->hasFile('backimg') ) {
            
            $img = $request->file('backimg');
            $real_path = $img->getRealPath();
            $file_orgname = $img->getClientOriginalName();
            $file_size = $img->getClientSize();
            $file_ext = strtolower($img->getClientOriginalExtension());
            $file_newname = "backimg"."_".md5(microtime(TRUE).rand(123, 999)).".".$file_ext;
            $destinationPath = public_path('/uploads/files/media_images');
            $img->move($destinationPath, $file_newname);

            $PboxReusableContent->backimg = $file_newname;
        }

        if( $PboxReusableContent->save() ) {
            return back()->with('msg', 'Product Box Reusable Content Updated Succesfully.')
            ->with('msg_class', 'alert alert-success');
        } else {
            return back()->with('msg', 'Something Went Wrong.')
            ->with('msg_class', 'alert alert-danger');
        }
    }

    public function pboxDelete($id) {

        $res = PboxReusableContent::findOrFail($id);
        $res->status = '3';
        $del = $res->save(); 
        if( $res ) {
            return back()->with('msg', 'Product Box Reusable Content Deleted Successfully.')
            ->with('msg_class', 'alert alert-success');
        } else {
            return back()->with('msg', 'Something Went Wrong.')
            ->with('msg_class', 'alert alert-danger');
        }
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
                        $ReusableContent = ReusableContent::find($id);
                        $ReusableContent->status = '1';
                        $ReusableContent->save();
                    }
                    $msg = 'Content Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $ReusableContent = ReusableContent::find($id);
                        $ReusableContent->status = '2';
                        $ReusableContent->save();
                    }
                    $msg = 'Content Deactivated Succesfully.';
                    break;

                case 'delete':
                    foreach($idsArr as $id) {
                        $ReusableContent = ReusableContent::find($id);
                        $ReusableContent->status = '3';
                        $ReusableContent->save();
                    }
                    $msg = 'Content Deleted Succesfully.';
                    break;
            }
            return back()->with('msg', $msg)->with('msg_class', 'alert alert-success');
        }
        return back();
    }

    public function pboxBulkAction(Request $request) {

        $msg = '';
        if( $request->has('action_btn') && $request->has('ids') ) {
            $actBtnValue = trim( $request->input('action_btn') );
            $idsArr = $request->input('ids');

            switch ( $actBtnValue ) {
                
                case 'activate':
                    foreach($idsArr as $id) {
                        $PboxReusableContent = PboxReusableContent::find($id);
                        $PboxReusableContent->status = '1';
                        $PboxReusableContent->save();
                    }
                    $msg = 'Content Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $PboxReusableContent = PboxReusableContent::find($id);
                        $PboxReusableContent->status = '2';
                        $PboxReusableContent->save();
                    }
                    $msg = 'Content Deactivated Succesfully.';
                    break;

                case 'delete':
                    foreach($idsArr as $id) {
                        $PboxReusableContent = PboxReusableContent::find($id);
                        $PboxReusableContent->status = '3';
                        $PboxReusableContent->save();
                    }
                    $msg = 'Content Deleted Succesfully.';
                    break;
            }
            return back()->with('msg', $msg)->with('msg_class', 'alert alert-success');
        }
        return back();
    }
}
