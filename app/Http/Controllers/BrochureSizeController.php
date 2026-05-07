<?php

namespace App\Http\Controllers;

use DB;
use Auth;

use Image;
use Excel; 
use App\Models\CmsLinks;

use App\Models\BrochureType;
 
use App\Models\Media\Images;
use Illuminate\Http\Request;
use App\Models\WebinarIndustry;
use App\Models\BrochureLanguage;
use App\Models\BrochureSize;
use App\Models\Media\FilesMaster;

class BrochureSizeController extends Controller
{
    public function allBrochureIndustry() {
        $DataBag = array();
     $DataBag['parentMenu'] = 'Brochure';
     $DataBag['childMenu'] = 'brochuresize';
     $DataBag['allProdCats'] = DB::table('brochure_size')->where('status', '!=', '3')->orderBy('id', 'desc')->get();
     

     return view('dashboard.brochuresize.index', $DataBag);
    } 

 public function addBrochureIndustry() {
     $DataBag = array(); 
     $DataBag['parentMenu'] = 'Brochure';
     $DataBag['childMenu'] = 'brochuresize';
   
     $DataBag['insert_id'] = md5(microtime(TRUE));
     return view('dashboard.brochuresize.add', $DataBag);
 }

 /**** SAVE PRODUCT CATEGORY ***/

 public function saveBrochureIndustry(Request $request) {
  
     
     $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

     $WebinarIndustry = new BrochureSize;
     $WebinarIndustry->name = trim( ucfirst($request->input('name')) );
          
     $resx = $WebinarIndustry->save();
     if( isset($resx) && $resx == 1 ) {

        return redirect()->route('allBrsiId')->with('msg', 'Brochure  Size Updated Successfully.')
        ->with('msg_class', 'alert alert-success');
     }

     return back()->with('msg', 'Something Went Wrong')
     ->with('msg_class', 'alert alert-danger');
 }


 public function deleteBrochureIndustry($topic_id) {
     $ck = BrochureSize::find($topic_id);
     if( isset($ck) && !empty($ck) ) {
         $ck->status = '3';
         $res = $ck->save();
         if( isset($res) && $res == 1 ) {

             return back()->with('msg', 'Brochure Type Deleted Successfully.')
             ->with('msg_class', 'alert alert-success');
         }
     }

     return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
 }

 public function editBrochureIndustry($topic_id,Request $request) {

     
     $DataBag = array();
     
     $DataBag['parentMenu'] = 'Brochure';
     $DataBag['childMenu'] = 'brochuresize';
     
      
     $DataBag['content_id'] = $topic_id;

     $DataBag['prodCat'] = BrochureSize::where('status', '=', '1')->where('id',$topic_id)->orderBy('name', 'asc')->first();
      
     return view('dashboard.brochuresize.add', $DataBag);
 }


 /**** UPDATE PRODUCT CATEGORY ***/

 public function updateBrochureIndustry(Request $request, $topic_id) {

  
         $WebinarIndustry = BrochureSize::find($topic_id);
         $WebinarIndustry->name = trim( ucfirst($request->input('name')) );
         
         $resx = $WebinarIndustry->save();
         
         if( isset($resx) && $resx == 1 ) {
 
            
             return redirect()->route('allBrsiId')->with('msg', 'Brochure Type Updated Successfully.')
             ->with('msg_class', 'alert alert-success');
             
         }
     
     return back()->with('msg', 'Something Went Wrong')
     ->with('msg_class', 'alert alert-danger');
 }
}
