<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media\FilesMaster;
use App\Models\Media\Images;
use App\Models\CmsLinks;

use App\Models\WebinarTopic;
 
use Auth;
use Image;
use DB;
use Excel; 

class WebinarTopicController extends Controller
{
  
   	public function allWebinarTopics() {
   		$DataBag = array();
    	$DataBag['parentMenu'] = 'Webinar';
    	$DataBag['childMenu'] = 'allWbCt';
    	$DataBag['allProdCats'] = WebinarTopic::where('status', '!=', '3')->orderBy('id', 'desc')->get();
        

    	return view('dashboard.webinarTopic.index', $DataBag);
   	} 

    public function addWebinarTopic() {
        $DataBag = array(); 
    	$DataBag['parentMenu'] = 'Webinar';
    	$DataBag['childMenu'] = 'allWbCt';
      
        $DataBag['insert_id'] = md5(microtime(TRUE));
    	return view('dashboard.webinarTopic.add', $DataBag);
    }
  
    /**** SAVE PRODUCT CATEGORY ***/

    public function saveWebinarTopic(Request $request) {
    	
    	$insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

    	$WebinarTopic = new WebinarTopic;
    	$WebinarTopic->name = trim( ucfirst($request->input('name')) );
    	 	
    	$resx = $WebinarTopic->save();
    	if( isset($resx) && $resx == 1 ) {
 
    		return back()->with('msg', 'Webinar Topic Created Successfully.')
    		->with('msg_class', 'alert alert-success');
    	}

    	return back()->with('msg', 'Something Went Wrong')
    	->with('msg_class', 'alert alert-danger');
    }


    public function deleteWebinarTopic($topic_id) {
    	$ck = WebinarTopic::find($topic_id);
    	if( isset($ck) && !empty($ck) ) {
    		$ck->status = '3';
    		$res = $ck->save();
    		if( isset($res) && $res == 1 ) {
 
                return back()->with('msg', 'Webinar Topic Deleted Successfully.')
    			->with('msg_class', 'alert alert-success');
    		}
    	}

    	return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function editWebinarTopic($topic_id,Request $request) {

		
        $DataBag = array();
        
    	$DataBag['parentMenu'] = 'Webinar';
    	$DataBag['childMenu'] = 'allWbCt';
    	
         
        $DataBag['content_id'] = $topic_id;
  
        $DataBag['prodCat'] = WebinarTopic::where('status', '=', '1')->where('id',$topic_id)->orderBy('name', 'asc')->first();
         
    	return view('dashboard.webinarTopic.add', $DataBag);
    }


    /**** UPDATE PRODUCT CATEGORY ***/

    public function updateWebinarTopic(Request $request, $topic_id) {

     
            $WebinarTopic = WebinarTopic::find($topic_id);
            $WebinarTopic->name = trim( ucfirst($request->input('name')) );
            
            $resx = $WebinarTopic->save();
            
            if( isset($resx) && $resx == 1 ) {
    
               
                return redirect()->route('allWbCt')->with('msg', 'Webinar Topic Updated Successfully.')
                ->with('msg_class', 'alert alert-success');
                
            }
        
    	return back()->with('msg', 'Something Went Wrong')
    	->with('msg_class', 'alert alert-danger');
    }

    
}
