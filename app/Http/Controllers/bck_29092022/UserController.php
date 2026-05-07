<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Users;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Image;
use Auth;
use DB;

class UserController extends Controller
{


    public function index() {
    	$dataBag = array();
        $dataBag['GparentMenu'] = 'management';
    	$dataBag['parentMenu'] = 'userManagement';
    	$dataBag['childMenu'] = 'usersList';
    	$dataBag['userList'] = Users::where('status', '!=', '3')->orderBy('first_name', 'asc')->get();
    	return view('dashboard.users.index', $dataBag);
    }

    public function createUser() {
    	$dataBag = array();
        $dataBag['GparentMenu'] = 'management';
    	$dataBag['parentMenu'] = 'userManagement';
    	$dataBag['childMenu'] = 'createUser';
    	$dataBag['roles'] = Role::get();
    	return view('dashboard.users.create', $dataBag);	
    }

    public function saveUser(Request $request) {

    	$request->validate([
			
            'email_id' => 'required|email|unique:users,email_id'
		],[
		
			'email_id.unique' => 'This Email-id Already Exist, Try Another.'
		]);

    	$Users = new Users;
    	$Users->timestamp_id = md5(microtime(TRUE));
    	$Users->first_name = trim($request->input('first_name'));
    	$Users->last_name = trim($request->input('last_name'));
    	$Users->email_id = trim($request->input('email_id'));
    	$Users->contact_no = trim($request->input('contact_no'));
    	$Users->password = md5(trim($request->input('password')));
        $Users->created_by = Auth::user()->id;

    	if( $Users->save() ) {
            
            if( $request->has('role_ids') ) {
                $Users->syncRoles($request->input('role_ids'));
            }

    		return back()->with('msg_class', 'alert alert-success')
    		->with('msg', 'New User Created Succesfully.');

    	} else {
    		return back()->with('msg_class', 'alert alert-danger')
    		->with('msg', 'Something Went Wrong.');
    	}
    }

    public function editUser($user_timestamp_id) {
    	$dataBag = array();
        $dataBag['GparentMenu'] = 'management';
    	$dataBag['parentMenu'] = 'userManagement';
    	$dataBag['roles'] = Role::get();
    	$dataBag['user_data'] = Users::where('timestamp_id', '=', $user_timestamp_id)->first();
    	return view('dashboard.users.edit', $dataBag);
    }

    public function updateUser(Request $request, $user_timestamp_id) {

    	$User = Users::where('timestamp_id', '=', $user_timestamp_id)->first();
    	if( !empty($User) ) {

            $request->validate([
            
            'email_id' => 'required|email|unique:users,email_id,'.$User->id
            ],[
            
                'email_id.unique' => 'This Email-id Already Exist, Try Another.'
            ]);

    		$updateData = array();
    		$updateData['first_name'] = trim($request->input('first_name'));
    		$updateData['last_name'] = trim($request->input('last_name'));
    		$updateData['email_id'] = trim($request->input('email_id'));
    		$updateData['contact_no'] = trim($request->input('contact_no'));
    		$updateData['sex'] = trim($request->input('sex'));
    		$updateData['address'] = trim($request->input('address'));
    		$updateData['status'] = trim($request->input('status'));
    		$updateData['updated_by'] = Auth::user()->id;
    		$updateData['updated_at'] = date('Y-m-d H:i:s');
    		if( $request->hasFile('image') ) {

	    		$image = $request->file('image');
	    		$real_path = $image->getRealPath();
	            $file_orgname = $image->getClientOriginalName();
	            $file_size = $image->getClientSize();
	            $file_ext = strtolower($image->getClientOriginalExtension());
	            $file_newname = "user"."_".time().".".$file_ext;

	            $destinationPath = public_path('/uploads/user_images');
	            $original_path = $destinationPath."/original";
	            $thumb_path = $destinationPath."/thumb";
	            
	            $img = Image::make($real_path);
	        	$img->resize(150, 150, function ($constraint) {
			    	$constraint->aspectRatio();
				})->save($thumb_path.'/'.$file_newname);

	        	$image->move($original_path, $file_newname);
	        	$updateData['image'] = $file_newname;
	    	}
	    	$res = Users::where('timestamp_id', '=', $user_timestamp_id)->update($updateData);
	    	if( $res ) {
                
                if( $request->has('role_ids') ) {
                    $User->syncRoles($request->input('role_ids'));
                }

	    		return back()->with('msg_class', 'alert alert-success')
    			->with('msg', 'User Updated Succesfully.');
	    	} else {
	    		return back()->with('msg_class', 'alert alert-danger')
    			->with('msg', 'Something Went Wrong.');
	    	}
    	} else {
    		return back()->with('msg_class', 'alert alert-danger')
    		->with('msg', 'Something Went Wrong. User Missmatch');
    	}
    }

    public function resetPassword( $user_timestamp_id ) {
    	$dataBag = array();
        $dataBag['GparentMenu'] = 'management';
    	$dataBag['parentMenu'] = 'userManagement';
    	$dataBag['user_data'] = Users::where('timestamp_id', '=', $user_timestamp_id)->first();
    	return view('dashboard.users.reset_password', $dataBag);
    }

    public function updatePassword(Request $request, $user_timestamp_id) {

    	$ck = Users::where('timestamp_id', '=', $user_timestamp_id)->first();
    	if( !empty($ck) ) {
    		$updateData = array();
    		$updateData['password'] = md5(trim($request->input('password')));
    		$res = Users::where('timestamp_id', '=', $user_timestamp_id)->update($updateData);
	    	if( $res ) {
	    		return back()->with('msg_class', 'alert alert-success')
    			->with('msg', 'User Password Updated Succesfully.');
	    	} else {
	    		return back()->with('msg_class', 'alert alert-danger')
    			->with('msg', 'Something Went Wrong.');
	    	}
    	} else {
    		return back()->with('msg_class', 'alert alert-danger')
    		->with('msg', 'Something Went Wrong. User Missmatch');
    	}
    }

    public function deleteUser( $user_timestamp_id ) {

    	$res = Users::where('timestamp_id', '=', $user_timestamp_id)->update(['status' => '3']);
    	if( $res ) {
    		return back()->with('msg_class', 'alert alert-success')
			->with('msg', 'User Deleted Succesfully.');
    	} else {
    		return back()->with('msg_class', 'alert alert-danger')
			->with('msg', 'Something Went Wrong.');
    	}
    }

    public function profile() {
        $dataBag = array();
        $dataBag['GparentMenu'] = 'management';
        $dataBag['parentMenu'] = 'settings';
        $dataBag['childMenu'] = 'profile';
        return view('dashboard.users.profile', $dataBag);
    }

    public function profileUpdate(Request $request) {

        $user_id = Auth::user()->id;
        $request->validate([
            
        'email_id' => 'required|email|unique:users,email_id,'.$user_id
        ],[
        
            'email_id.unique' => 'This Email-id Already Exist, Try Another.'
        ]);

        $updateData = array();
        $updateData['first_name'] = trim($request->input('first_name'));
        $updateData['last_name'] = trim($request->input('last_name'));
        $updateData['email_id'] = trim($request->input('email_id'));
        $updateData['contact_no'] = trim($request->input('contact_no'));
        $updateData['sex'] = trim($request->input('sex'));
        $updateData['address'] = trim($request->input('address'));
        $updateData['updated_by'] = $user_id;
        $updateData['updated_at'] = date('Y-m-d H:i:s');
        if( $request->hasFile('image') ) {

            $image = $request->file('image');
            $real_path = $image->getRealPath();
            $file_orgname = $image->getClientOriginalName();
            $file_size = $image->getClientSize();
            $file_ext = strtolower($image->getClientOriginalExtension());
            $file_newname = "user"."_".time().".".$file_ext;

            $destinationPath = public_path('/uploads/user_images');
            $original_path = $destinationPath."/original";
            $thumb_path = $destinationPath."/thumb";
            
            $img = Image::make($real_path);
            $img->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            })->save($thumb_path.'/'.$file_newname);

            $image->move($original_path, $file_newname);
            $updateData['image'] = $file_newname;
        }
        $res = Users::where('id', '=', $user_id)->update($updateData);
        if( $res ) {
            return back()->with('msg_class', 'alert alert-success')
            ->with('msg', 'Profile Updated Succesfully.');
        } else {
            return back()->with('msg_class', 'alert alert-danger')
            ->with('msg', 'Something Went Wrong.');
        }
    }

    public function changePassword() {
        $dataBag = array();
        $dataBag['GparentMenu'] = 'management';
        $dataBag['parentMenu'] = 'settings';
        $dataBag['childMenu'] = 'cngPwd';
        return view('dashboard.users.change_password', $dataBag);
    }

    public function changePasswordSave(Request $request) {

        $current_password = md5(trim($request->input('current_password')));
        $new_password = md5(trim($request->input('new_password')));
        $ck = Users::where('id', '=', Auth::user()->id)
        ->where('password', '=', $current_password)->first();
        if( !empty($ck) ) {
            $res = Users::where('id', '=', Auth::user()->id)->update(['password' => $new_password]);
            if( $res ) {
                return back()->with('msg_class', 'alert alert-success')
                ->with('msg', 'Password Changed Succesfully.');
            } else {
                return back()->with('msg_class', 'alert alert-danger')
                ->with('msg', 'Something Went Wrong.');
            }
        } else {
            return back()->with('msg_class', 'alert alert-danger')
            ->with('msg', 'Current Password Not Match.');
        }
    }

    public function allRoles() {
        $dataBag = array();
        $dataBag['GparentMenu'] = 'management';
        $dataBag['parentMenu'] = 'userManagement';
        $dataBag['childMenu'] = 'usrRoles';
        $dataBag['roles'] = Role::all();
        return view('dashboard.user_roles.index', $dataBag);
    }

    public function roleManagePermissions($role_id) {
        $dataBag = array();
        $dataBag['GparentMenu'] = 'management';
        $dataBag['parentMenu'] = 'userManagement';
        $dataBag['childMenu'] = 'usrRoles';
        $dataBag['role'] = Role::findOrFail($role_id);
        $dataBag['permissions'] = Permission::all();
        return view('dashboard.user_roles.manage_permissions', $dataBag);
    }

    public function saveRolePermissions(Request $request, $role_id) {

        if( $request->input('permissions') ) {
            $r = Role::find($role_id)->syncPermissions( $request->input('permissions') );
            return back()->with('msg_class', 'alert alert-success')
            ->with('msg', 'Permissions Set Succesfully.');
        }

        return back()->with('msg_class', 'alert alert-danger')
        ->with('msg', 'Current Password Not Match.');
    }

    public function takeAction(Request $request) {
        $msg = '';
        if( $request->has('action_btn') && $request->has('user_ids') ) {
            $actBtnValue = trim( $request->input('action_btn') );
            $idsArr = $request->input('user_ids');

            switch ( $actBtnValue ) {
                
                case 'activate':
                    foreach($idsArr as $id) {
                        $user = Users::find($id);
                        $user->status = '1';
                        $user->save();
                    }
                    $msg = 'Users Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $user = Users::find($id);
                        $user->status = '2';
                        $user->save();
                    }
                    $msg = 'Users Deactivated Succesfully.';
                    break;

                case 'delete_user':
                    foreach($idsArr as $id) {
                        $user = Users::find($id);
                        $user->status = '3';
                        $user->save();
                    }
                    $msg = 'Users Deleted Succesfully.';
                    break;
            }
            return back()->with('msg', $msg)->with('msg_class', 'alert alert-success');
        }
        return back();
    }

}
