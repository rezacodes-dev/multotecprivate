<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\Models\EmailSettings;
use Auth;

class EmailTemplateController extends Controller
{
    
    public function index() {
    	$dataBag = array();
    	$dataBag['parentMenu'] = "settings";
    	$dataBag['childMenu'] = "emTemp";
    	$dataBag['templates'] = EmailTemplate::where('status', '!=', '3')->get();
        $dataBag['isActiveSetting'] = EmailSettings::where('id', '=', '1')->exists();
    	return view('dashboard.email_temps.index', $dataBag);
    }

    public function addEmTemplate() {
    	$dataBag = array();
    	$dataBag['parentMenu'] = "settings";
    	$dataBag['childMenu'] = "emTemp";
    	return view('dashboard.email_temps.create', $dataBag);
    }

    public function saveEmTemplate(Request $request) {

    	$EmailTemplate = new EmailTemplate;
    	$EmailTemplate->name = trim(ucfirst($request->input('name')));
    	$EmailTemplate->subject = trim(ucfirst($request->input('subject')));
    	$EmailTemplate->description = htmlentities(trim($request->input('description')), ENT_QUOTES);
    	$EmailTemplate->status = trim($request->input('status'));
    	$EmailTemplate->created_by = Auth::user()->id;
    	$res = $EmailTemplate->save();
    	if( $res ) {
    		return back()->with('msg', 'Email Template Saved Successfully.')
    		->with('msg_class', 'alert alert-success');
    	} else {
    		return back()->with('msg', 'Something Went Wrong.')
    		->with('msg_class', 'alert alert-danger');
    	}
    }

    public function editEmTemplate($id) {
    	$dataBag = array();
    	$dataBag['parentMenu'] = "settings";
    	$dataBag['childMenu'] = "emTemp";
    	$dataBag['template'] = EmailTemplate::findOrFail($id);
    	return view('dashboard.email_temps.create', $dataBag);
    }

    public function updateEmTemplate(Request $request, $id) {

    	$EmailTemplate = EmailTemplate::find($id);	
    	$EmailTemplate->name = trim(ucfirst($request->input('name')));
    	$EmailTemplate->subject = trim(ucfirst($request->input('subject')));
    	$EmailTemplate->description = htmlentities(trim($request->input('description')), ENT_QUOTES);
    	$EmailTemplate->status = trim($request->input('status'));
    	$EmailTemplate->updated_by = Auth::user()->id;
    	$EmailTemplate->updated_at = date('Y-m-d H:i:s');
    	$res = $EmailTemplate->save();
    	if( $res ) {
    		return back()->with('msg', 'Email Template Updated Successfully.')
    		->with('msg_class', 'alert alert-success');
    	} else {
    		return back()->with('msg', 'Something Went Wrong.')
    		->with('msg_class', 'alert alert-danger');
    	}
    }

    public function deleteEmTemplate($id) {
    	$res = EmailTemplate::where('id', '=', $id)->update(['status' => '3']);
    	if( $res ) {
    		return back()->with('msg', 'Email Template Deleted Successfully.')
    		->with('msg_class', 'alert alert-success');
    	} else {
    		return back()->with('msg', 'Something Went Wrong.')
    		->with('msg_class', 'alert alert-danger');
    	}
    }


    public function settings() {
        $dataBag = array();
        $dataBag['parentMenu'] = "settings";
        $dataBag['childMenu'] = "emTemp";
        $dataBag['settings'] = EmailSettings::find(1);
        return view('dashboard.email_temps.settings', $dataBag);
    }

    public function settingsSave(Request $request) {
        $settings = EmailSettings::find(1);
        if( isset($settings) && $settings != null && !empty($settings)) {
            $settings->sender_email_id = trim($request->input('sender_email_id'));
            $settings->sender_name = trim($request->input('sender_name'));
            $settings->email_signature = trim($request->input('email_signature'));
            $settings->status = trim($request->input('status'));
            $settings->updated_by = Auth::user()->id;
            $settings->updated_at = date('Y-m-d H:i:s');
            $res = $settings->save();
        } else {
            $settings = new EmailSettings;
            $settings->sender_email_id = trim($request->input('sender_email_id'));
            $settings->sender_name = trim($request->input('sender_name'));
            $settings->email_signature = trim($request->input('email_signature'));
            $settings->status = trim($request->input('status'));
            $settings->created_by = Auth::user()->id;
            $res = $settings->save();
        }
        if( $res ) {
            return back()->with('msg', 'Email Settings Saved Successfully.')
            ->with('msg_class', 'alert alert-success');
        } else {
            return back()->with('msg', 'Something Went Wrong.')
            ->with('msg_class', 'alert alert-danger');
        }
    }
}
