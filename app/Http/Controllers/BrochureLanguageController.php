<?php

namespace App\Http\Controllers;

use App\Models\BrochureLanguage;
use Illuminate\Http\Request;

use App\Models\Media\FilesMaster;
use App\Models\Media\Images;
use App\Models\CmsLinks;

use App\Models\WebinarIndustry;
 
use Auth;
use Image;
use DB;
use Excel; 

class BrochureLanguageController extends Controller
{
    public function allBrochureIndustry() {
        $DataBag = array();
     $DataBag['parentMenu'] = 'Brochure';
     $DataBag['childMenu'] = 'brochure';
     $DataBag['allProdCats'] = DB::table('brochure_language')->where('status', '!=', '3')->orderBy('id', 'desc')->get();
     

     return view('dashboard.brochurelanguage.index', $DataBag);
    } 

 public function addBrochureIndustry() {
     $DataBag = array(); 
     $DataBag['parentMenu'] = 'Brochure';
     $DataBag['childMenu'] = 'brochure';
   
     $DataBag['insert_id'] = md5(microtime(TRUE));
     return view('dashboard.brochurelanguage.add', $DataBag);
 }

 /**** SAVE PRODUCT CATEGORY ***/

 public function saveBrochureIndustry(Request $request) {
  
     
     $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

     $WebinarIndustry = new BrochureLanguage;
     $WebinarIndustry->name = trim( ucfirst($request->input('name')) );
          
     $resx = $WebinarIndustry->save();
     if( isset($resx) && $resx == 1 ) {

        return redirect()->route('allBrlgId')->with('msg', 'Brochure Language Updated Successfully.')
        ->with('msg_class', 'alert alert-success');
     }

     return back()->with('msg', 'Something Went Wrong')
     ->with('msg_class', 'alert alert-danger');
 }


 public function deleteBrochureIndustry($topic_id) {
     $ck = BrochureLanguage::find($topic_id);
     if( isset($ck) && !empty($ck) ) {
         $ck->status = '3';
         $res = $ck->save();
         if( isset($res) && $res == 1 ) {

             return back()->with('msg', 'Brochure Language Deleted Successfully.')
             ->with('msg_class', 'alert alert-success');
         }
     }

     return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
 }

 public function editBrochureIndustry($topic_id,Request $request) {

     
     $DataBag = array();
     
     $DataBag['parentMenu'] = 'Brochure';
     $DataBag['childMenu'] = 'brochure';
     
      
     $DataBag['content_id'] = $topic_id;

     $DataBag['prodCat'] = BrochureLanguage::where('status', '=', '1')->where('id',$topic_id)->orderBy('name', 'asc')->first();
      
     return view('dashboard.brochurelanguage.add', $DataBag);
 }


 /**** UPDATE PRODUCT CATEGORY ***/

 public function updateBrochureIndustry(Request $request, $topic_id) {

  
         $WebinarIndustry = BrochureLanguage::find($topic_id);
         $WebinarIndustry->name = trim( ucfirst($request->input('name')) );
         
         $resx = $WebinarIndustry->save();
         
         if( isset($resx) && $resx == 1 ) {
 
            
             return redirect()->route('allBrlgId')->with('msg', 'Brochure Language Updated Successfully.')
             ->with('msg_class', 'alert alert-success');
             
         }
     
     return back()->with('msg', 'Something Went Wrong')
     ->with('msg_class', 'alert alert-danger');
 }
}
