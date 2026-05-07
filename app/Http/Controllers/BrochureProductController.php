<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BrochureProduct;
use Auth;
use Image;
use DB;
use Excel; 
class BrochureProductController extends Controller
{
    public function allBrochureProductIndustry() {
        $DataBag = array();
     $DataBag['parentMenu'] = 'Brochure';
     $DataBag['childMenu'] = 'brochure_product';
     $DataBag['allProdCats'] = DB::table('brochure_products')->orderBy('id', 'desc')->get();
     

     return view('dashboard.brochureproduct.index', $DataBag);
    } 

 public function addBrochureProduct() {
     $DataBag = array(); 
     $DataBag['parentMenu'] = 'Brochure';
     $DataBag['childMenu'] = 'brochure_product';
   
     $DataBag['insert_id'] = md5(microtime(TRUE));
     return view('dashboard.brochureproduct.add', $DataBag);
 }

 /**** SAVE PRODUCT CATEGORY ***/

 public function saveBrochureProduct(Request $request) {
  
     
     $insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

     $WebinarIndustry = new BrochureProduct;
     $WebinarIndustry->name = trim( ucfirst($request->input('name')) );
          
     $resx = $WebinarIndustry->save();
     if( isset($resx) && $resx == 1 ) {

        return redirect()->route('allBrprId')->with('msg', 'Brochure Language Updated Successfully.')
        ->with('msg_class', 'alert alert-success');
     }

     return back()->with('msg', 'Something Went Wrong')
     ->with('msg_class', 'alert alert-danger');
 }


 public function deleteBrochureProduct($topic_id) {
     $ck = BrochureProduct::find($topic_id);
     if( isset($ck) && !empty($ck) ) {
         $ck->status = '3';
         $res = $ck->save();
      
             return back()->with('msg', 'Brochure Language Deleted Successfully.')
             ->with('msg_class', 'alert alert-success');
         
     }

     return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
 }

 public function editBrochureProduct($topic_id,Request $request) {

     
     $DataBag = array();
     
     $DataBag['parentMenu'] = 'Brochure';
     $DataBag['childMenu'] = 'brochure_product';
     
      
     $DataBag['content_id'] = $topic_id;

     $DataBag['prodCat'] = BrochureProduct::where('status', '=', '1')->where('id',$topic_id)->orderBy('name', 'asc')->first();
      
     return view('dashboard.brochureproduct.add', $DataBag);
 }


 /**** UPDATE PRODUCT CATEGORY ***/

 public function updateBrochureProduct(Request $request, $topic_id) {

  
         $WebinarIndustry = BrochureProduct::find($topic_id);
         $WebinarIndustry->name = trim( ucfirst($request->input('name')) );
         
         $resx = $WebinarIndustry->save();
         
         if( isset($resx) && $resx == 1 ) {
 
            
             return redirect()->route('allBrprId')->with('msg', 'Brochure Language Updated Successfully.')
             ->with('msg_class', 'alert alert-success');
             
         }
     
     return back()->with('msg', 'Something Went Wrong')
     ->with('msg_class', 'alert alert-danger');
 }
}
