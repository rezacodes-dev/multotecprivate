<?php

namespace App\Http\Controllers;

use App\Models\BrochureBrand;
use App\Models\BrochureContent;
use App\Models\BrochureDetails;
use App\Models\BrochureLanguage;
use App\Models\BrochureMaster;
use App\Models\BrochureProduct;
use App\Models\BrochureProductDetails;
use App\Models\BrochureSize;
use App\Models\BrochureType;
use App\Models\CmsLinks;
use App\Models\Media\FilesMaster;
use App\Models\Media\Images;
use App\Models\WebinarIndustry;
use Auth;
use DB;
use Excel; 
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Image;


class AllBrochureController extends Controller
{
    public function allBrochureIndustry() {
        $DataBag = array();
     $DataBag['parentMenu'] = 'Brochure';
     $DataBag['childMenu'] = 'allbrochures';
     $DataBag['allProdCats'] = DB::table('brochure_master')->where('status', '!=', '3')->orderBy('id', 'desc')->get();
     

     return view('dashboard.brochuremaster.index', $DataBag);
    } 

    public function allBrochureContent(Request $request){
        $DataBag = array();
		$DataBag['parentMenu'] = 'Brochure';
		$DataBag['childMenu'] = 'allbrochures';
		$DataBag['prodCat'] = BrochureContent::where('id', '=', '1')->first();

		return view('dashboard.brochuremaster.brochurecontent', $DataBag);
    }

    public function updateBrochureContent(Request $request)
	{


		$Webinar = BrochureContent::find(1);
		$Webinar->heading = trim(ucfirst($request->input('heading')));

		$Webinar->description = trim(htmlentities($request->input('description'), ENT_QUOTES));


		$resx = $Webinar->save();

		if (isset($resx) && $resx == 1) {


			return back()->with('msg', 'Webinar Content Updated Successfully.')
				->with('msg_class', 'alert alert-success');
		}

		return back()->with('msg', 'Something Went Wrong')
			->with('msg_class', 'alert alert-danger');
	}

 public function addBrochureIndustry() {
     $DataBag = array(); 
     $DataBag['parentMenu'] = 'Brochure';
     $DataBag['childMenu'] = 'allbrochures';
   
     $DataBag['insert_id'] = md5(microtime(TRUE));
     $DataBag['language'] = BrochureLanguage::where('status','!=',3)->get();
     $DataBag['type'] = BrochureType::where('status','!=',3)->get();
     $DataBag['brand'] = BrochureBrand::where('status','!=',3)->get();
          $DataBag['size'] = BrochureSize::where('status','!=',3)->get();
     $DataBag['product'] = BrochureProduct::where('status','!=',3)->get();

     return view('dashboard.brochuremaster.add', $DataBag);
 }

 /**** SAVE PRODUCT CATEGORY ***/

//  public function saveBrochureIndustry(Request $request)
// {  
//     $mainbrochure = new BrochureMaster();
//     $mainbrochure->name = $request->name;
//     $mainbrochure->description = $request->page_content ?? null;


//     $sl_no = $request->input('sl_no') ?? [];
  
//     $language = $request->input('language') ?? [];
//     $type = $request->input('type') ?? [];
//     $brand = $request->input('brand') ?? [];
//     $download_name = $request->input('download_name') ?? [];
//     $brochure_pdf = $request->file('brochure') ?? [];
//     $brand = $request->input('brand') ?? [];
//     // $product = $request->input('product') ?? [];
    
   

   
    
//     // Generate slug
//     $slug = Str::slug($request->name);
//     $originalSlug = $slug;
//     $count = 1;

//     // Check if slug exists and make it unique
//     while (BrochureMaster::where('slug', $slug)->exists()) {
//         $slug = $originalSlug . '-' . $count++;
//     }

//     $mainbrochure->slug = $slug;

    

//     // Handle brochure image upload
//     if ($request->hasFile('brochure_image')) {
//         $file = $request->file('brochure_image');
//         $filename = time() . '_' . $file->getClientOriginalName();

//         $destinationPath = public_path('uploads/files/pdf_brochures');

//         if (!file_exists($destinationPath)) {
//             mkdir($destinationPath, 0755, true);
//         }

//         $file->move($destinationPath, $filename);

//         $mainbrochure->thumbnail_image = 'uploads/files/pdf_brochures/' . $filename;
//     } else {
//         $mainbrochure->thumbnail_image = null;
//         $mainbrochure->size = null;
//     }

//     $mainbrochure->save();
    

//     $new_brochure_id=$mainbrochure->id;

//     foreach($sl_no as $key=>$value){


//         if ($request->hasFile('brochure')) {
//             $file = $brochure_pdf[$key] ?? '';
//             $filename = time() . '_' . $file->getClientOriginalName();
    
//             $destinationPath = public_path('uploads/files/pdf_brochures');
    
//             if (!file_exists($destinationPath)) {
//                 mkdir($destinationPath, 0755, true);
//             }
    
//             $file->move($destinationPath, $filename);
    
//            $brochure_main_pdf= 'uploads/files/pdf_brochures/' . $filename;
//         }

        
//         $brochureDetails = [
//             'brochure_id' => $new_brochure_id ?? '',
//             'language_id' => $language[$key] ?? '',
//             'type_id' => $type[$key] ?? '',
//             'download_name' => $download_name[$key] ?? '',
//             'brochure_pdf' => $brochure_main_pdf ?? '',
//             'brand_id' => $brand[$key] ?? '',
//         ];



//         $brochuredetails=BrochureDetails::create($brochureDetails);
        

//         $brochuredetailsid=$brochuredetails->id??'';
       
      
//          $brochureDetailsArray[]=$brochuredetailsid;
       



//          }

//         // dd($brochureDetailsArray);
//          foreach($brochureDetailsArray as $key=>$val){
//             $count=$key+1;
//             $value_product = $request->input('product'. '_'.$count);
//             // dd($value_product);
//              foreach($value_product as $key=>$value)
//         {

//             $brochureProducts = [
//                 'brochure_details_id' => $val ?? '',
//                 'brochure_id' => $new_brochure_id ?? '',
//                 'product_id' => $value ?? '',
//                 'created_at' => now(),
//                 'updated_at' => now(),
           
//             ];
//             BrochureProductDetails::create($brochureProducts);
//         }
//         }

//     return redirect()->route('allBrallId')->with('msg', 'Brochure saved successfully!');
// }
 
public function saveBrochureIndustry(Request $request)
{  
   // dd($request->all());
    $mainbrochure = new BrochureMaster();
    $mainbrochure->name = $request->name;
    $mainbrochure->description = $request->page_content ?? null;

    $sl_no = $request->input('sl_no') ?? [];
    $language = $request->input('language') ?? [];
    $type = $request->input('type') ?? [];
    $brand = $request->input('brand') ?? [];
    $size = $request->input('size') ?? [];
    $download_name = $request->input('download_name') ?? [];
    $brochure_pdf = $request->file('brochure') ?? [];

    // Generate slug
    $slug = Str::slug($request->name);
    $originalSlug = $slug;
    $count = 1;

  
 

    while (BrochureMaster::where('slug', $slug)->exists()) {
        $slug = $originalSlug . '-' . $count++;
    }

    $mainbrochure->slug = $slug;

    // Handle brochure image upload
    if ($request->hasFile('brochure_image')) {
        $file = $request->file('brochure_image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $destinationPath = public_path('uploads/files/pdf_brochures');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $file->move($destinationPath, $filename);
        $mainbrochure->thumbnail_image = 'uploads/files/pdf_brochures/' . $filename;
    } else {
        $mainbrochure->thumbnail_image = null;
        $mainbrochure->size = null;
    }

    $mainbrochure->save();
    $new_brochure_id = $mainbrochure->id;

    $brochureDetailsArray = [];
    $brochureDetailsInsert = [];

    // ✅ Prepare Brochure Details for Bulk Insert
     
        foreach ($sl_no as $key => $value) {
     
        $brochure_main_pdf = null;
        
         $name = $download_name[$key] ?? 'brochure';

         $cleanName = preg_replace('/\s+/', '', $name);

        $short_url = 'whatsapp/' . round(microtime(true) * 1000) . '_' . $key . '_' . $cleanName;

        if ($request->hasFile('brochure') && isset($brochure_pdf[$key])) {
            $file = $brochure_pdf[$key];
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads/files/pdf_brochures');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);
            $brochure_main_pdf = 'uploads/files/pdf_brochures/' . $filename;
        }

        $brochureDetailsInsert[] = [
            'brochure_id' => $new_brochure_id,
            'language_id' => $language[$key] ?? '',
            'type_id' => $type[$key] ?? '',
            'size_id' => $size[$key] ?? '',
            'download_name' => $download_name[$key] ?? '',
            'short_url' => $short_url ?? '',
            'brochure_pdf' => $brochure_main_pdf ?? '',
            'brand_id' => $brand[$key] ?? '',
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    // ✅ Insert all brochure details at once
    BrochureDetails::insert($brochureDetailsInsert);

    // ✅ Fetch the inserted brochure details (to get their IDs)
    $insertedDetails = BrochureDetails::where('brochure_id', $new_brochure_id)->pluck('id')->toArray();

    $brochureProductsInsert = [];

    // ✅ Loop through and prepare Brochure Product Details for Bulk Insert
    foreach ($insertedDetails as $key => $val) {
        $count = $key + 1;
        $value_product = $request->input('product_' . $count);

        if (!empty($value_product)) {
            foreach ($value_product as $productId) {
                $brochureProductsInsert[] = [
                    'brochure_details_id' => $val,
                    'brochure_id' => $new_brochure_id,
                    'product_id' => $productId,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }
    }

    // ✅ Insert all brochure products at once
    if (!empty($brochureProductsInsert)) {
        BrochureProductDetails::insert($brochureProductsInsert);
    }

    return redirect()->route('allBrallId')->with('msg', 'Brochure saved successfully!');
}


 public function deleteBrochureIndustry($topic_id) {

     $ck = BrochureMaster::find($topic_id);
     if( isset($ck) && !empty($ck) ) {
         $ck->status = '3';
         $res = $ck->save();
        //  if( isset($res) && $res == 1 ) {

        return redirect()->back()
        ->with('msg', 'Brochure Brand Deleted Successfully.');
  
    
        //  }
     }

     return back()->with('msg', 'Something Went Wrong')
     ->with('msg_class', 'alert alert-danger');
 }

 public function editBrochureIndustry($topic_id,Request $request) {

     
     $DataBag = array();
     
     $DataBag['parentMenu'] = 'Brochure';
     $DataBag['childMenu'] = 'allbrochures';
     
      
     $DataBag['content_id'] = $topic_id;

     $DataBag['prodCat'] = BrochureMaster::where('status', '=', '1')->where('id',$topic_id)->orderBy('name', 'asc')->first();
     $DataBag['language'] = BrochureLanguage::where('status','!=',3)->get();
     $DataBag['type'] = BrochureType::where('status','!=',3)->get();
     $DataBag['brand'] = BrochureBrand::where('status','!=',3)->get();
        $DataBag['size'] = BrochureSize::where('status','!=',3)->get();
     $DataBag['product'] = BrochureProduct::where('status','!=',3)->get();

     $brochure = BrochureMaster::with('brochureDetails.brochureProducts')->find($topic_id);
     $DataBag['brochure'] = $brochure->toArray();
  
     return view('dashboard.brochuremaster.add', $DataBag);
 }


 /**** UPDATE PRODUCT CATEGORY ***/

public function updateBrochureIndustry(Request $request, $topic_id)
{
    // ✅ Find the existing brochure by topic_id
    $mainbrochure = BrochureMaster::findOrFail($topic_id);

    // ✅ Update brochure master fields
    $mainbrochure->name = $request->name;
    $mainbrochure->description = $request->page_content ?? null;

    // Generate slug (only if the name is updated)
    $slug = Str::slug($request->name);
    $originalSlug = $slug;
    $count = 1;

    while (BrochureMaster::where('slug', $slug)->where('id', '!=', $topic_id)->exists()) {
        $slug = $originalSlug . '-' . $count++;
    }

    $mainbrochure->slug = $slug;



    // ✅ Handle brochure image upload (replace if new image uploaded)
    if ($request->hasFile('brochure_image')) {
        $file = $request->file('brochure_image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $destinationPath = public_path('uploads/files/pdf_brochures');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $file->move($destinationPath, $filename);
        $mainbrochure->thumbnail_image = 'uploads/files/pdf_brochures/' . $filename;
    }

    $mainbrochure->save();
    $brochure_id = $mainbrochure->id;

    // ✅ Fetch existing brochure details before deletion
    $oldDetailsData = BrochureDetails::where('brochure_id', $brochure_id)->get()->keyBy('id')->toArray();

    // Delete old brochure products & details
    $oldDetailsIds = array_keys($oldDetailsData);
    if (!empty($oldDetailsIds)) {
        BrochureProductDetails::whereIn('brochure_details_id', $oldDetailsIds)->delete();
    }
    BrochureDetails::where('brochure_id', $brochure_id)->delete();

    // ✅ Prepare data for new insert
    $sl_no = $request->input('sl_no') ?? [];
    $language = $request->input('language') ?? [];
    $type = $request->input('type') ?? [];
    $brand = $request->input('brand') ?? [];
    $size = $request->input('size') ?? [];
    $download_name = $request->input('download_name') ?? [];
    $brochure_pdf = $request->file('brochure') ?? [];

    $brochureDetailsInsert = [];

    // ✅ Insert new brochure details
    foreach ($sl_no as $key => $value) {
        $brochure_main_pdf = null;

           $name = $download_name[$key] ?? 'brochure';

           $cleanName = preg_replace('/\s+/', '', $name);

           $short_url = 'whatsapp/' . round(microtime(true) * 1000) . '_' . $key . '_' . $cleanName;

        // Use new file if uploaded
        if ($request->hasFile('brochure') && isset($brochure_pdf[$key])) {
            $file = $brochure_pdf[$key];
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads/files/pdf_brochures');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $file->move($destinationPath, $filename);
            $brochure_main_pdf = 'uploads/files/pdf_brochures/' . $filename;
        } else {
            // Retain old PDF if exists
            $oldDetailKeys = array_values($oldDetailsIds); // reset keys
            if (isset($oldDetailKeys[$key])) {
                $oldDetailId = $oldDetailKeys[$key];
                $brochure_main_pdf = $oldDetailsData[$oldDetailId]['brochure_pdf'] ?? null;
            }
        }


       


        $brochureDetailsInsert[] = [
            'brochure_id' => $brochure_id,
            'language_id' => $language[$key] ?? '',
            'type_id' => $type[$key] ?? '',
            'size_id' => $size[$key] ?? '',
            'download_name' => $download_name[$key] ?? '',
            'short_url' => $short_url ?? '',
            'brochure_pdf' => $brochure_main_pdf ?? '',
            'brand_id' => $brand[$key] ?? '',
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    BrochureDetails::insert($brochureDetailsInsert);

    // ✅ Fetch the new inserted brochure details
    $insertedDetails = BrochureDetails::where('brochure_id', $brochure_id)->pluck('id')->toArray();

    $brochureProductsInsert = [];

    foreach ($insertedDetails as $key => $val) {
        $count = $key + 1;
        $value_product = $request->input('product_' . $count);

        if (!empty($value_product)) {
            foreach ($value_product as $productId) {
                $brochureProductsInsert[] = [
                    'brochure_details_id' => $val,
                    'brochure_id' => $brochure_id,
                    'product_id' => $productId,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }
    }

    if (!empty($brochureProductsInsert)) {
        BrochureProductDetails::insert($brochureProductsInsert);
    }

    return redirect()->route('allBrallId')->with('msg', 'Brochure updated successfully!');
}


 
}
