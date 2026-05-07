<?php

namespace App\Http\Controllers;

use App\Models\BrochureLanguage;
use App\Models\CmsLinks;
use App\Models\KhLanguage;
use App\Models\Media\FilesMaster;
use App\Models\Media\Images;
use App\Models\WebinarIndustry;
use Auth;
use DB;
use Excel; 
use Illuminate\Http\Request;
use Image;

class KhProductLanguageController extends Controller
{
    public function allBrochureIndustry() {
        $DataBag = array();
     $DataBag['parentMenu'] = 'khlanguage';
     $DataBag['childMenu'] = 'khlanguage';
     $DataBag['allProdCats'] = DB::table('kh_language')->where('status', '!=', '3')->orderBy('id', 'desc')->get();
     

     return view('dashboard.khlanguage.index', $DataBag);
    } 

 public function addBrochureIndustry() {
     $DataBag = array(); 
     $DataBag['parentMenu'] = 'khlanguage';
     $DataBag['childMenu'] = 'khlanguage';
   
     $DataBag['insert_id'] = md5(microtime(TRUE));
     return view('dashboard.khlanguage.add', $DataBag);
 }

 /**** SAVE PRODUCT CATEGORY ***/

 public function saveBrochureIndustry(Request $request) {
  
     
     $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

     $WebinarIndustry = new KhLanguage;
     $WebinarIndustry->name = trim( ucfirst($request->input('name')) );
          
     $resx = $WebinarIndustry->save();
     if( isset($resx) && $resx == 1 ) {

        return redirect()->route('allKhlgId')->with('msg', ' Language Updated Successfully.')
        ->with('msg_class', 'alert alert-success');
     }

     return back()->with('msg', 'Something Went Wrong')
     ->with('msg_class', 'alert alert-danger');
 }


 public function deleteBrochureIndustry($topic_id) {
     $ck = KhLanguage::find($topic_id);
     if( isset($ck) && !empty($ck) ) {
         $ck->status = '3';
         $res = $ck->save();
         if( isset($res) && $res == 1 ) {

             return back()->with('msg', ' Language Deleted Successfully.')
             ->with('msg_class', 'alert alert-success');
         }
     }

     return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
 }

 public function editBrochureIndustry($topic_id,Request $request) {

     
     $DataBag = array();
     
     $DataBag['parentMenu'] = 'Khlangauge';
     $DataBag['childMenu'] = 'Khlangauge';
     
      
     $DataBag['content_id'] = $topic_id;

     $DataBag['prodCat'] = KhLanguage::where('status', '=', '1')->where('id',$topic_id)->orderBy('name', 'asc')->first();
      
     return view('dashboard.khlanguage.add', $DataBag);
 }


 /**** UPDATE PRODUCT CATEGORY ***/

 public function updateBrochureIndustry(Request $request, $topic_id) {

  
         $WebinarIndustry = KhLanguage::find($topic_id);
         $WebinarIndustry->name = trim( ucfirst($request->input('name')) );
         
         $resx = $WebinarIndustry->save();
         
         if( isset($resx) && $resx == 1 ) {
 
            
             return redirect()->route('allKhlgId')->with('msg', ' Language Updated Successfully.')
             ->with('msg_class', 'alert alert-success');
             
         }
     
     return back()->with('msg', 'Something Went Wrong')
     ->with('msg_class', 'alert alert-danger');
 }
}
