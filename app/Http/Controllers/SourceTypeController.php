<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media\FilesMaster;
use App\Models\Media\Images;
use App\Models\CmsLinks;

use App\Models\SourceType;
 
use Auth;
use Image;
use DB;
use Excel; 

class SourceTypeController extends Controller
{
  
   	public function allSourceTypes() {
   		$DataBag = array();
    	$DataBag['parentMenu'] = 'Traffic';
    	$DataBag['childMenu'] = 'allSrc';
    	$DataBag['allProdCats'] = SourceType::where('status', '!=', '3')->orderBy('id', 'desc')->get();
        

    	return view('dashboard.sourceType.index', $DataBag);
   	} 

    public function addSourceType() {
        $DataBag = array(); 
    	$DataBag['parentMenu'] = 'Traffic';
    	$DataBag['childMenu'] = 'allSrc';
      
        $DataBag['insert_id'] = md5(microtime(TRUE));
    	return view('dashboard.sourceType.add', $DataBag);
    }
  
    /**** SAVE PRODUCT CATEGORY ***/

    public function saveSourceType(Request $request) {
    	
    	$insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

    	$SourceType = new SourceType;
    	$SourceType->name = trim( ucfirst($request->input('name')) );
    	 	
    	$resx = $SourceType->save();
    	if( isset($resx) && $resx == 1 ) {
 
    		return back()->with('msg', 'Source Type Created Successfully.')
    		->with('msg_class', 'alert alert-success');
    	}

    	return back()->with('msg', 'Something Went Wrong')
    	->with('msg_class', 'alert alert-danger');
    }


    public function deleteSourceType($category_id) {
    	$ck = SourceType::find($category_id);
    	if( isset($ck) && !empty($ck) ) {
    		$ck->status = '3';
    		$res = $ck->save();
    		if( isset($res) && $res == 1 ) {
 
                return back()->with('msg', 'Source Type Deleted Successfully.')
    			->with('msg_class', 'alert alert-success');
    		}
    	}

    	return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function editSourceType($category_id,Request $request) {

		
        $DataBag = array();
        
    	$DataBag['parentMenu'] = 'Traffic';
    	$DataBag['childMenu'] = 'allSrc';
    	
         
        $DataBag['content_id'] = $category_id;
  
        $DataBag['prodCat'] = SourceType::where('status', '=', '1')->where('id',$category_id)->orderBy('name', 'asc')->first();
         
    	return view('dashboard.sourceType.add', $DataBag);
    }


    /**** UPDATE PRODUCT CATEGORY ***/

    public function updateSourceType(Request $request, $category_id) {

     
            $SourceType = SourceType::find($category_id);
            $SourceType->name = trim( ucfirst($request->input('name')) );
            
            $resx = $SourceType->save();
            
            if( isset($resx) && $resx == 1 ) {
    
               
                return redirect()->route('allSrc')->with('msg', 'Source Type Updated Successfully.')
                ->with('msg_class', 'alert alert-success');
                
            }
        
    	return back()->with('msg', 'Something Went Wrong')
    	->with('msg_class', 'alert alert-danger');
    }

    
}
