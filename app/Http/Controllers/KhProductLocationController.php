<?php

namespace App\Http\Controllers;

use App\Models\BrochureLanguage;
use App\Models\CmsLinks;
use App\Models\KhLocation;
use App\Models\Media\FilesMaster;
use App\Models\Media\Images;
use App\Models\WebinarIndustry;
use Auth;
use DB;
use Excel; 
use Illuminate\Http\Request;
use Image;

class KhProductLocationController extends Controller
{
    public function allBrochureIndustry() {
        $DataBag = array();
     $DataBag['parentMenu'] = 'KhLocation';
     $DataBag['childMenu'] = 'KhLocation';
     $DataBag['allProdCats'] = DB::table('kh_location')->where('status', '!=', '3')->orderBy('name', 'asc')->get();
     

     return view('dashboard.khlocation.index', $DataBag);
    } 

 public function addBrochureIndustry() {
     $DataBag = array(); 
     $DataBag['parentMenu'] = 'KhLocation';
     $DataBag['childMenu'] = 'KhLocation';
   
     $DataBag['insert_id'] = md5(microtime(TRUE));
     return view('dashboard.khlocation.add', $DataBag);
 }

 /**** SAVE PRODUCT CATEGORY ***/

 public function saveBrochureIndustry(Request $request) {
  
     
     $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

     $WebinarIndustry = new KhLocation;
     $WebinarIndustry->name = trim( ucfirst($request->input('name')) );
          
     $resx = $WebinarIndustry->save();
     if( isset($resx) && $resx == 1 ) {

        return redirect()->route('allKhlolgId')->with('msg', ' Location Updated Successfully.')
        ->with('msg_class', 'alert alert-success');
     }

     return back()->with('msg', 'Something Went Wrong')
     ->with('msg_class', 'alert alert-danger');
 }


 public function deleteBrochureIndustry($topic_id) {
     $ck = KhLocation::find($topic_id);
     if( isset($ck) && !empty($ck) ) {
         $ck->status = '3';
         $res = $ck->save();
         if( isset($res) && $res == 1 ) {

             return back()->with('msg', ' Location Deleted Successfully.')
             ->with('msg_class', 'alert alert-success');
         }
     }

     return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
 }

 public function editBrochureIndustry($topic_id,Request $request) {

     
     $DataBag = array();
     
     $DataBag['parentMenu'] = 'KhLocation';
     $DataBag['childMenu'] = 'KhLocation';
     
      
     $DataBag['content_id'] = $topic_id;

     $DataBag['prodCat'] = KhLocation::where('status', '=', '1')->where('id',$topic_id)->orderBy('name', 'asc')->first();
      
     return view('dashboard.khlocation.add', $DataBag);
 }


 /**** UPDATE PRODUCT CATEGORY ***/

 public function updateBrochureIndustry(Request $request, $topic_id) {

  
         $WebinarIndustry = KhLocation::find($topic_id);
         $WebinarIndustry->name = trim( ucfirst($request->input('name')) );
         
         $resx = $WebinarIndustry->save();
         
         if( isset($resx) && $resx == 1 ) {
 
            
             return redirect()->route('allKhlolgId')->with('msg', ' Location Updated Successfully.')
             ->with('msg_class', 'alert alert-success');
             
         }
     
     return back()->with('msg', 'Something Went Wrong')
     ->with('msg_class', 'alert alert-danger');
 }
}
