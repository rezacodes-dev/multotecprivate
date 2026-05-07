<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media\FilesMaster;
use App\Models\Media\Images;
use App\Models\CmsLinks;

use App\Models\WebinarCategory;
 
use Auth;
use Image;
use DB;
use Excel; 

class WebinarCategoryController extends Controller
{
  
   	public function allWebinarCategorys() {
   		$DataBag = array();
    	$DataBag['parentMenu'] = 'Webinar';
    	$DataBag['childMenu'] = 'allWbCt';
    	$DataBag['allProdCats'] = WebinarCategory::where('status', '!=', '3')->orderBy('id', 'desc')->get();
        

    	return view('dashboard.webinarCategory.index', $DataBag);
   	} 

    public function addWebinarCategory() {
        $DataBag = array(); 
    	$DataBag['parentMenu'] = 'Webinar';
    	$DataBag['childMenu'] = 'allWbCt';
      
        $DataBag['insert_id'] = md5(microtime(TRUE));
    	return view('dashboard.webinarCategory.add', $DataBag);
    }
  
    /**** SAVE PRODUCT CATEGORY ***/

    public function saveWebinarCategory(Request $request) {
    	
    	$insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

    	$WebinarCategory = new WebinarCategory;
    	$WebinarCategory->name = trim( ucfirst($request->input('name')) );
    	 	
    	$resx = $WebinarCategory->save();
    	if( isset($resx) && $resx == 1 ) {
 
    		return back()->with('msg', 'Webinar Category Created Successfully.')
    		->with('msg_class', 'alert alert-success');
    	}

    	return back()->with('msg', 'Something Went Wrong')
    	->with('msg_class', 'alert alert-danger');
    }


    public function deleteWebinarCategory($category_id) {
    	$ck = WebinarCategory::find($category_id);
    	if( isset($ck) && !empty($ck) ) {
    		$ck->status = '3';
    		$res = $ck->save();
    		if( isset($res) && $res == 1 ) {
 
                return back()->with('msg', 'Webinar Category Deleted Successfully.')
    			->with('msg_class', 'alert alert-success');
    		}
    	}

    	return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function editWebinarCategory($category_id,Request $request) {

		
        $DataBag = array();
        
    	$DataBag['parentMenu'] = 'Webinar';
    	$DataBag['childMenu'] = 'allWbCt';
    	
         
        $DataBag['content_id'] = $category_id;
  
        $DataBag['prodCat'] = WebinarCategory::where('status', '=', '1')->where('id',$category_id)->orderBy('name', 'asc')->first();
         
    	return view('dashboard.webinarCategory.add', $DataBag);
    }


    /**** UPDATE PRODUCT CATEGORY ***/

    public function updateWebinarCategory(Request $request, $category_id) {

     
            $WebinarCategory = WebinarCategory::find($category_id);
            $WebinarCategory->name = trim( ucfirst($request->input('name')) );
            
            $resx = $WebinarCategory->save();
            
            if( isset($resx) && $resx == 1 ) {
    
               
                return redirect()->route('allWbCt')->with('msg', 'Webinar Category Updated Successfully.')
                ->with('msg_class', 'alert alert-success');
                
            }
        
    	return back()->with('msg', 'Something Went Wrong')
    	->with('msg_class', 'alert alert-danger');
    }

    
}
