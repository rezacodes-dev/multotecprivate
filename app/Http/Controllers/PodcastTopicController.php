<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media\FilesMaster;
use App\Models\Media\Images;
use App\Models\CmsLinks;

use App\Models\WebinarTopic;
use App\Models\PodcastTopic;
 
use Auth;
use Image;
use DB;
use Excel; 

class PodcastTopicController extends Controller
{
  
   	public function allWebinarTopics() {
   		$DataBag = array();
    	$DataBag['parentMenu'] = 'Podast';
    	$DataBag['childMenu'] = 'allPdCt';
    	$DataBag['allProdCats'] = PodcastTopic::where('status', '!=', '3')->orderBy('id', 'desc')->get();
        

    	return view('dashboard.podcastTopic.index', $DataBag);
   	} 

    public function addWebinarTopic() {
        $DataBag = array(); 
    	$DataBag['parentMenu'] = 'Podcast';
    	$DataBag['childMenu'] = 'allPdCt';
      
        $DataBag['insert_id'] = md5(microtime(TRUE));
    	return view('dashboard.podcastTopic.add', $DataBag);
    }
  
    /**** SAVE PRODUCT CATEGORY ***/

    public function saveWebinarTopic(Request $request) {
    	
    	$insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

    	$WebinarTopic = new PodcastTopic;
    	$WebinarTopic->name = trim( ucfirst($request->input('name')) );
    	 	
    	$resx = $WebinarTopic->save();
    	if( isset($resx) && $resx == 1 ) {
 
    		return back()->with('msg', 'Podcast Topic Created Successfully.')
    		->with('msg_class', 'alert alert-success');
    	}

    	return back()->with('msg', 'Something Went Wrong')
    	->with('msg_class', 'alert alert-danger');
    }


    public function deleteWebinarTopic($topic_id) {
    	$ck = PodcastTopic::find($topic_id);
    	if( isset($ck) && !empty($ck) ) {
    		$ck->status = '3';
    		$res = $ck->save();
    		if( isset($res) && $res == 1 ) {
 
                return back()->with('msg', 'Podcast Topic Deleted Successfully.')
    			->with('msg_class', 'alert alert-success');
    		}
    	}

    	return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function editWebinarTopic($topic_id,Request $request) {

		
        $DataBag = array();
        
    	$DataBag['parentMenu'] = 'Podcast';
    	$DataBag['childMenu'] = 'allPdCt';
    	
         
        $DataBag['content_id'] = $topic_id;
  
        $DataBag['prodCat'] = PodcastTopic::where('status', '=', '1')->where('id',$topic_id)->orderBy('name', 'asc')->first();
         
    	return view('dashboard.podcastTopic.add', $DataBag);
    }


    /**** UPDATE PRODUCT CATEGORY ***/

    public function updateWebinarTopic(Request $request, $topic_id) {

     
            $WebinarTopic = WebinarTopic::find($topic_id);
            $WebinarTopic->name = trim( ucfirst($request->input('name')) );
            
            $resx = $WebinarTopic->save();
            
            if( isset($resx) && $resx == 1 ) {
    
               
                return redirect()->route('allPdCt')->with('msg', 'Podcast Topic Updated Successfully.')
                ->with('msg_class', 'alert alert-success');
                
            }
        
    	return back()->with('msg', 'Something Went Wrong')
    	->with('msg_class', 'alert alert-danger');
    }

    
}
