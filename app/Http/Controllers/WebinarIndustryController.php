<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media\FilesMaster;
use App\Models\Media\Images;
use App\Models\CmsLinks;

use App\Models\WebinarIndustry;
 
use Auth;
use Image;
use DB;
use Excel; 

class WebinarIndustryController extends Controller
{
  
   	public function allWebinarIndustry() {
   		$DataBag = array();
    	$DataBag['parentMenu'] = 'Webinar';
    	$DataBag['childMenu'] = 'allWbId';
    	$DataBag['allProdCats'] = WebinarIndustry::where('status', '!=', '3')->orderBy('id', 'desc')->get();
        

    	return view('dashboard.webinarIndustry.index', $DataBag);
   	} 

    public function addWebinarIndustry() {
        $DataBag = array(); 
    	$DataBag['parentMenu'] = 'Webinar';
    	$DataBag['childMenu'] = 'allWbId';
      
        $DataBag['insert_id'] = md5(microtime(TRUE));
    	return view('dashboard.webinarIndustry.add', $DataBag);
    }
  
    /**** SAVE PRODUCT CATEGORY ***/

    public function saveWebinarIndustry(Request $request) {
    	
    	$insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

    	$WebinarIndustry = new WebinarIndustry;
    	$WebinarIndustry->name = trim( ucfirst($request->input('name')) );
    	 	
    	$resx = $WebinarIndustry->save();
    	if( isset($resx) && $resx == 1 ) {
 
    		return back()->with('msg', 'Webinar Industry Created Successfully.')
    		->with('msg_class', 'alert alert-success');
    	}

    	return back()->with('msg', 'Something Went Wrong')
    	->with('msg_class', 'alert alert-danger');
    }


    public function deleteWebinarIndustry($topic_id) {
    	$ck = WebinarIndustry::find($topic_id);
    	if( isset($ck) && !empty($ck) ) {
    		$ck->status = '3';
    		$res = $ck->save();
    		if( isset($res) && $res == 1 ) {
 
                return back()->with('msg', 'Webinar Industry Deleted Successfully.')
    			->with('msg_class', 'alert alert-success');
    		}
    	}

    	return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function editWebinarIndustry($topic_id,Request $request) {

		
        $DataBag = array();
        
    	$DataBag['parentMenu'] = 'Webinar';
    	$DataBag['childMenu'] = 'allWbId';
    	
         
        $DataBag['content_id'] = $topic_id;
  
        $DataBag['prodCat'] = WebinarIndustry::where('status', '=', '1')->where('id',$topic_id)->orderBy('name', 'asc')->first();
         
    	return view('dashboard.webinarIndustry.add', $DataBag);
    }


    /**** UPDATE PRODUCT CATEGORY ***/

    public function updateWebinarIndustry(Request $request, $topic_id) {

     
            $WebinarIndustry = WebinarIndustry::find($topic_id);
            $WebinarIndustry->name = trim( ucfirst($request->input('name')) );
            
            $resx = $WebinarIndustry->save();
            
            if( isset($resx) && $resx == 1 ) {
    
               
                return redirect()->route('allWbId')->with('msg', 'Webinar Industry Updated Successfully.')
                ->with('msg_class', 'alert alert-success');
                
            }
        
    	return back()->with('msg', 'Something Went Wrong')
    	->with('msg_class', 'alert alert-danger');
    }

    
}
