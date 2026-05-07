<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media\FilesMaster;
use App\Models\Media\Images;
use App\Models\CmsLinks;

use App\Models\WebinarIndustry;
use App\Models\PodcastIndustry;
 
use Auth;
use Image;
use DB;
use Excel; 

class PodcastIndustryController extends Controller
{
  
   	public function allWebinarIndustry() {
   		$DataBag = array();
    	$DataBag['parentMenu'] = 'Podcast';
    	$DataBag['childMenu'] = 'allWbId';
    	$DataBag['allProdCats'] = PodcastIndustry::where('status', '!=', '3')->orderBy('id', 'desc')->get();
        

    	return view('dashboard.podcastIndustry.index', $DataBag);
   	} 

    public function addWebinarIndustry() {
        $DataBag = array(); 
    	$DataBag['parentMenu'] = 'Podcast';
    	$DataBag['childMenu'] = 'allPdId';
      
        $DataBag['insert_id'] = md5(microtime(TRUE));
    	return view('dashboard.podcastIndustry.add', $DataBag);
    }
  
    /**** SAVE PRODUCT CATEGORY ***/

    public function saveWebinarIndustry(Request $request) {
    	
    	$insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

    	$WebinarIndustry = new PodcastIndustry;
    	$WebinarIndustry->name = trim( ucfirst($request->input('name')) );
    	 	
    	$resx = $WebinarIndustry->save();
    	if( isset($resx) && $resx == 1 ) {
 
    		return back()->with('msg', 'Podcast Industry Created Successfully.')
    		->with('msg_class', 'alert alert-success');
    	}

    	return back()->with('msg', 'Something Went Wrong')
    	->with('msg_class', 'alert alert-danger');
    }


    public function deleteWebinarIndustry($topic_id) {
    	$ck = PodcastIndustry::find($topic_id);
    	if( isset($ck) && !empty($ck) ) {
    		$ck->status = '3';
    		$res = $ck->save();
    		if( isset($res) && $res == 1 ) {
 
                return back()->with('msg', 'Podcast Industry Deleted Successfully.')
    			->with('msg_class', 'alert alert-success');
    		}
    	}

    	return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function editWebinarIndustry($topic_id,Request $request) {

		
        $DataBag = array();
        
    	$DataBag['parentMenu'] = 'Podcast';
    	$DataBag['childMenu'] = 'allPdId';
    	
         
        $DataBag['content_id'] = $topic_id;
  
        $DataBag['prodCat'] = PodcastIndustry::where('status', '=', '1')->where('id',$topic_id)->orderBy('name', 'asc')->first();
         
    	return view('dashboard.podcastIndustry.add', $DataBag);
    }


    /**** UPDATE PRODUCT CATEGORY ***/

    public function updateWebinarIndustry(Request $request, $topic_id) {

   
            $WebinarIndustry = PodcastIndustry::find($topic_id);
            $WebinarIndustry->name = trim( ucfirst($request->input('name')) );
            
            $resx = $WebinarIndustry->save();
            
            if( isset($resx) && $resx == 1 ) {
    
               
                return redirect()->route('allPdId')->with('msg', 'Podcast Industry Updated Successfully.')
                ->with('msg_class', 'alert alert-success');
                
            }
        
    	return back()->with('msg', 'Something Went Wrong')
    	->with('msg_class', 'alert alert-danger');
    }

    
}
