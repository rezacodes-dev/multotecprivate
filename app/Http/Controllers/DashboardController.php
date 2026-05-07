<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
use App\Models\Users;
use Session;
use Auth;
use DB;
use Mail;

class DashboardController extends Controller
{

    public function login() {
    	
    	return view('dashboard_login');
    }

    public function loginAction(Request $request) {

    	$request->validate([
			
            'email_id' => 'required',
            'password' => 'required'
		],[
		
			'email_id.required' => 'Please enter email-id.',
			'password.required' => 'Please enter password.'
		]);

    	$email_id = trim($request->input('email_id'));
    	$password = md5(trim($request->input('password')));
    	$rm_me = trim($request->input('rm_me'));
    	$norPwd = trim($request->input('password'));

        $rerf_url = trim($request->input('rerf_url'));

    	$loginUser = Users::where('email_id', '=', $email_id)
    	->where('password', '=', $password)->where('status', '=', '1')->first(); 

    	if(!empty($loginUser)) {
    		
    		Auth::login($loginUser);
    		if( $rm_me == '1' ) {
    			setcookie("multotec_admin_email", $email_id, time() + (86400 * 30));
                setcookie("multotec_admin_password", $norPwd, time() + (86400 * 30));
    		} else {
    			unset($_COOKIE['multotec_admin_email']);
                unset($_COOKIE['multotec_admin_password']);
                setcookie("multotec_admin_email", '', time() - 3600);
                setcookie("multotec_admin_password", '', time() - 3600);
    		}

    		Session::put('ar_login_user_id', $loginUser->id);
            Session::put('is_ar_admin_logged_in', 'yes');

            if( $rerf_url != NULL && $rerf_url != '' && strpos($rerf_url, 'admin/dashboard') !== false) {

                return redirect( $rerf_url ); // redirect to admin page

            } else {

                return redirect()->route('dashboard'); // redirect admin dashboard
            }

    	} else {
    		return back()->with('msg', 'Sorry! Login Information Incorrect.');
    	}
    }

    public function logout() {

    	if(Session::has('ar_login_user_id')) { Session::forget('ar_login_user_id'); }
    	if(Session::has('is_ar_admin_logged_in')) { Session::forget('is_ar_admin_logged_in'); }
    	Auth::logout();
    	Session::flush();
    	return redirect()->route('dashboard_login');
    }

    public function index() {
       
        $dataBag = array();
        $dataBag['articles'] = \App\Models\Article\Articles::where('status', '!=', '3')->where('parent_language_id', '=', '0')->count();
        $dataBag['events'] = \App\Models\Events::where('status', '!=', '3')->where('parent_language_id', '=', '0')->count();
        $dataBag['jobs'] = \App\Models\Career\Career::where('status', '!=', '3')->where('parent_language_id', '=', '0')->count();
        $dataBag['contents'] = \App\Models\Content\Contents::where('status', '!=', '3')->where('parent_language_id', '=', '0')->count();
        $dataBag['distributors'] = \App\Models\Distributor\Distributor::where('status', '!=', '3')->where('parent_language_id', '=', '0')->count();
        $dataBag['distributor_contents'] = \App\Models\Distributor\DistributorContents::where('status', '!=', '3')
        ->where('parent_language_id', '=', '0')->count();
        $dataBag['forms'] = \App\Models\FrmBuilder\FrmMaster::where('status', '!=', '3')->count();
        $dataBag['industries'] = \App\Models\Industry\Industries::where('status', '!=', '3')->where('parent_language_id', '=', '0')->count();
        $dataBag['industry_sheets'] = \App\Models\IndustryFlowsheet\Flowsheet::where('status', '!=', '3')
        ->where('parent_language_id', '=', '0')->count();
        $dataBag['images'] = \App\Models\Media\Images::where('status', '!=', '3')->count();
        $dataBag['files'] = \App\Models\Media\FilesMaster::where('status', '!=', '3')->count();
        $dataBag['videos'] = \App\Models\Media\Videos::where('status', '!=', '3')->count();
        $dataBag['profiles'] = \App\Models\PeoplesProfile\PeoplesProfile::where('status', '!=', '3')->where('parent_language_id', '=', '0')->count();
        $dataBag['products'] = \App\Models\Product\Products::where('status', '!=', '3')->where('parent_language_id', '=', '0')->count();
        $dataBag['tech_resources'] = \App\Models\TechResource\TechResource::where('status', '!=', '3')->where('parent_language_id', '=', '0')->count();
        $dataBag['users'] = \App\Models\Users::where('status', '!=', '3')->count();
        
    	return view('dashboard.index', $dataBag);
    }

    public function resetLink() {

        return view('pwd_reset_link');
    }

    public function sendLink(Request $request) {

        $email_id = trim( $request->input('email_id') );

        $ckEmail = Users::where('email_id', '=', $email_id)->first();
        if(!empty($ckEmail)) {
            $userFname = $ckEmail->first_name;
            $token = md5(microtime(TRUE).$email_id);
            $link = route('reset_pwd', array('token' => $token));
            $link = "<a href='".$link."' target='_blank'>". $link ."</a>";
            $empTemp = \App\Models\EmailTemplate::find(2);
            if( !empty($empTemp) ) {
                $emSubject = $empTemp->subject;
                $content = $empTemp->description;
                $content = str_replace("[FIRSTNAME]", $userFname, $content);
                $content = str_replace("[PWD_RESET_LINK]", $link, $content);
                
                $emailData = array();
                $emailData['subject'] = $emSubject;
                $emailData['body'] = trim($content);
                $emailData['to_email'] = $email_id;
                $emailData['from_email'] = "karmicksol54@gmail.com";
                $emailData['from_name'] = "Multotec";
                
                Mail::send('emails.accountVerification', ['emailData' => $emailData], function ($message) use ($emailData) {
                    
                    $message->from($emailData['from_email'], $emailData['from_name']);

                    $message->to($emailData['to_email'])->subject($emailData['subject']);
                });

                $res = Users::where('email_id', '=', $email_id)->update(['remember_token' => $token]);

                if( $res ) {
                    return back()->with('msg', 'Password Reset Link Send To Your Mail.');   
                } else {
                    return back()->with('msg', 'Mail Sending Error.');        
                }

            } else {
                return back()->with('msg', 'Mail Sending Error.');    
            }
        } else {
            return back()->with('msg', 'Sorry! Email-Id Not Registered.');
        }
    }

    public function resetPassword( $token ) {

        $usr = Users::where('remember_token', '=', $token)->first();
        if( !empty($usr) ) { 
            $dataBag = array();
            $dataBag['name'] = $usr->first_name;
            $dataBag['token'] = $token;
            return view('pwd_reset', $dataBag);
        } else {
            return redirect()->route('reset_link')->with('msg', 'Invalid Password Reset Link, Try Again');
        }
    }

    public function resetPasswordAction(Request $request, $token) {

        $res = Users::where('remember_token', '=', $token)->update( ['password' => trim($request->input('password')) ] );
        if($res) {
            return redirect()->route('dashboard_login')->with('msg', 'Password reset Successfully, Please Login.');
        } else {
            return back()->with('msg', 'Sorry! Server Error.');
        }
    }

    public function globalImageDelete() {

        if( isset($_GET['tab']) && isset($_GET['id']) && !isset($_GET['field']) && $_GET['tab'] != '' && $_GET['id'] != '' ) {

            $tab = trim($_GET['tab']);
            $id = trim($_GET['id']);

            DB::table( $tab )->where('id', '=', $id)->update(['image_id' => '0']);
        }

        if( isset($_GET['tab']) && isset($_GET['id']) && isset($_GET['field']) && $_GET['tab'] != '' && $_GET['id'] != '' && $_GET['field'] != '') {

            $tab = trim($_GET['tab']);
            $id = trim($_GET['id']);
            $field = trim($_GET['field']);

            DB::table( $tab )->where('id', '=', $id)->update([ $field => '']);
        }

        return back();
    }

}
