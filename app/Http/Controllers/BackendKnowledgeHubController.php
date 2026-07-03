<?php

namespace App\Http\Controllers;

use App\Models\BrochureBrand;
use App\Models\BrochureContent;
use App\Models\BrochureDetails;
use App\Models\BrochureLanguage;
use App\Models\BrochureMaster;
use App\Models\BrochureProduct;
use App\Models\BrochureProductDetails;
use App\Models\BrochureType;
use App\Models\CmsLinks;
use App\Models\KhCommodities;
use App\Models\KhLanguage;
use App\Models\KhLocation;
use App\Models\KhProduct;
use App\Models\KnowledgeContent;
use App\Models\KnowledgeDetails;
use App\Models\KnowledgeHubMaster;
use App\Models\Media\FilesMaster;
use App\Models\Media\Images;
use App\Models\WebinarIndustry;
use Auth;
use DB;
use Excel; 
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Image;

class BackendKnowledgeHubController extends Controller
{
    public function allBrochureIndustry() {
     $DataBag = array();
     $DataBag['parentMenu'] = 'knowledge_hub';
     $DataBag['childMenu'] = 'knowledge_hub';
     $DataBag['allProdCats'] = DB::table('knowledge_hub')->where('status', '!=', '3')->orderBy('id', 'desc')->get();
     

     return view('dashboard.khmaster.index', $DataBag);
    } 

    public function allKnowledgeContent(Request $request){
        $DataBag = array();
		$DataBag['parentMenu'] = 'knowledge_hub';
		$DataBag['childMenu'] = 'knowledge_hub';
		$DataBag['prodCat'] = KnowledgeContent::where('id', '=', '1')->first();

		return view('dashboard.khmaster.brochurecontent', $DataBag);
    }

    public function updateKnowledgeContent(Request $request)
	{


		$Webinar = KnowledgeContent::find(1);
		$Webinar->heading = trim(ucfirst($request->input('heading')));

		$Webinar->description = trim(htmlentities($request->input('description'), ENT_QUOTES));


		$resx = $Webinar->save();

		if (isset($resx) && $resx == 1) {


			return back()->with('msg', ' Content Updated Successfully.')
				->with('msg_class', 'alert alert-success');
		}

		return back()->with('msg', 'Something Went Wrong')
			->with('msg_class', 'alert alert-danger');
	}

 public function addBrochureIndustry() {
     $DataBag = array(); 
     $DataBag['parentMenu'] = 'knowledge_hub';
     $DataBag['childMenu'] = 'knowledge_hub';
   
     $DataBag['insert_id'] = md5(microtime(TRUE));
    //  $DataBag['language'] = BrochureLanguage::where('status','!=',3)->get();
    //  $DataBag['type'] = BrochureType::where('status','!=',3)->get();
    //  $DataBag['brand'] = BrochureBrand::where('status','!=',3)->get();
     $DataBag['product'] = KhProduct::where('status','!=',3)->get();
     $DataBag['commodities'] = KhCommodities::where('status','!=',3)->get();
     $DataBag['language'] = KhLanguage::where('status','!=',3)->get();
     $DataBag['location'] = KhLocation::where('status','!=',3)->get();

     return view('dashboard.khmaster.add', $DataBag);
 }

 
 
// public function saveBrochureIndustry(Request $request)
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

//     // Generate slug
//     $slug = Str::slug($request->name);
//     $originalSlug = $slug;
//     $count = 1;

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
//     $new_brochure_id = $mainbrochure->id;

//     $brochureDetailsArray = [];
//     $brochureDetailsInsert = [];

//     // ✅ Prepare Brochure Details for Bulk Insert
//     foreach ($sl_no as $key => $value) {
//         $brochure_main_pdf = null;

//         if ($request->hasFile('brochure') && isset($brochure_pdf[$key])) {
//             $file = $brochure_pdf[$key];
//             $filename = time() . '_' . $file->getClientOriginalName();
//             $destinationPath = public_path('uploads/files/pdf_brochures');

//             if (!file_exists($destinationPath)) {
//                 mkdir($destinationPath, 0755, true);
//             }

//             $file->move($destinationPath, $filename);
//             $brochure_main_pdf = 'uploads/files/pdf_brochures/' . $filename;
//         }

//         $brochureDetailsInsert[] = [
//             'brochure_id' => $new_brochure_id,
//             'language_id' => $language[$key] ?? '',
//             'type_id' => $type[$key] ?? '',
//             'download_name' => $download_name[$key] ?? '',
//             'brochure_pdf' => $brochure_main_pdf ?? '',
//             'brand_id' => $brand[$key] ?? '',
//             'created_at' => now(),
//             'updated_at' => now()
//         ];
//     }

//     // ✅ Insert all brochure details at once
//     BrochureDetails::insert($brochureDetailsInsert);

//     // ✅ Fetch the inserted brochure details (to get their IDs)
//     $insertedDetails = BrochureDetails::where('brochure_id', $new_brochure_id)->pluck('id')->toArray();

//     $brochureProductsInsert = [];

//     // ✅ Loop through and prepare Brochure Product Details for Bulk Insert
//     foreach ($insertedDetails as $key => $val) {
//         $count = $key + 1;
//         $value_product = $request->input('product_' . $count);

//         if (!empty($value_product)) {
//             foreach ($value_product as $productId) {
//                 $brochureProductsInsert[] = [
//                     'brochure_details_id' => $val,
//                     'brochure_id' => $new_brochure_id,
//                     'product_id' => $productId,
//                     'created_at' => now(),
//                     'updated_at' => now()
//                 ];
//             }
//         }
//     }

//     // ✅ Insert all brochure products at once
//     if (!empty($brochureProductsInsert)) {
//         BrochureProductDetails::insert($brochureProductsInsert);
//     }

//     return redirect()->route('allBrallId')->with('msg', 'Brochure saved successfully!');
// }


public function saveBrochureIndustry(Request $request)
{  
    $mainbrochure = new KnowledgeHubMaster();
    $mainbrochure->name = $request->name;
 

    $sl_no = $request->input('sl_no') ?? [];
    $product = $request->input('product') ?? [];
    $commodities = $request->input('commodities') ?? [];
    $language = $request->input('language') ?? [];
    $location = $request->input('location') ?? [];
    $description = $request->input('description') ?? [];
    $shortdescription = $request->input('short_description') ?? [];
    $webinar_link = $request->input('webinar_link') ?? [];
    $podcast_link = $request->input('podcast_link') ?? [];
    $brochure_link = $request->input('brochure_link') ?? [];
    $podcast_time = $request->input('podcast_time') ?? [];
   

    // Generate slug
    $slug = Str::slug($request->name);
    $originalSlug = $slug;
    $count = 1;

    while (KnowledgeHubMaster::where('slug', $slug)->exists()) {
        $slug = $originalSlug . '-' . $count++;
    }

    $mainbrochure->slug = $slug;

    // Handle brochure image upload
    if ($request->hasFile('brochure_image')) {
        $file = $request->file('brochure_image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $destinationPath = public_path('uploads/files/knowledge_hub');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $file->move($destinationPath, $filename);
        $mainbrochure->image = 'uploads/files/knowledge_hub/' . $filename;
    } else {
        $mainbrochure->image = null;
    
    }

    $mainbrochure->save();
    $new_brochure_id = $mainbrochure->id;

    $brochureDetailsArray = [];
    $brochureDetailsInsert = [];

    // ✅ Prepare Brochure Details for Bulk Insert
    foreach ($sl_no as $key => $value) {
     

        $brochureDetailsInsert[] = [
            'kh_id' => $new_brochure_id,
            'product_id' => $product[$key] ?? '',
            'commodity_id' => $commodities[$key] ?? '',
            'language_id' => $language[$key] ?? '',
            'location_id' => $location[$key] ?? '',
            'short_description' => $shortdescription[$key] ?? '',
            'description' => $description[$key] ?? '',
            'webinar_link'  => !empty($webinar_link[$key])  ? $webinar_link[$key]  : NULL,
            'podcast_link'  => !empty($podcast_link[$key])  ? $podcast_link[$key]  : NULL,
            'podcast_time'  => !empty($podcast_time[$key])  ? $podcast_time[$key]  : NULL,
            'brochure_link' => !empty($brochure_link[$key]) ? $brochure_link[$key] : NULL,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

        // ✅ Insert all brochure details at once
        KnowledgeDetails::insert($brochureDetailsInsert);

    

    return redirect()->route('allKhallId')->with('msg', 'Details saved successfully!');
}


 public function deleteBrochureIndustry($topic_id) {

     $ck = KnowledgeHubMaster::find($topic_id);
     if( isset($ck) && !empty($ck) ) {
         $ck->status = '3';
         $res = $ck->save();
        //  if( isset($res) && $res == 1 ) {

        return redirect()->back()
        ->with('msg', 'Deleted Successfully.');
  
    
        //  }
     }

     return back()->with('msg', 'Something Went Wrong')
     ->with('msg_class', 'alert alert-danger');
 }

 public function editBrochureIndustry($topic_id,Request $request) {

     
     $DataBag = array();
     
     $DataBag['parentMenu'] = 'knowledge_hub';
     $DataBag['childMenu'] = 'knowledge_hub';
     
      
     $DataBag['content_id'] = $topic_id;
     $DataBag['khdetails'] = KnowledgeHubMaster::where('status', '=', '1')->where('id',$topic_id)->orderBy('name', 'asc')->first();
     $DataBag['product'] = KhProduct::where('status','!=',3)->get();
     $DataBag['commodities'] = KhCommodities::where('status','!=',3)->get();
     $DataBag['language'] = KhLanguage::where('status','!=',3)->get();
     $DataBag['location'] = KhLocation::where('status','!=',3)->get();

     $brochure = KnowledgeHubMaster::with('knowledgeDetails')->find($topic_id);
     $DataBag['knowledge_hub'] = $brochure->toArray();
  
     return view('dashboard.khmaster.add', $DataBag);
 }


 /**** UPDATE PRODUCT CATEGORY ***/

public function updateBrochureIndustry(Request $request, $topic_id)
{  // dd($request->all());
    // ✅ Find existing record
    $mainbrochure = KnowledgeHubMaster::findOrFail($topic_id);

    // ✅ Update basic fields
    $mainbrochure->name = $request->name;

    // ✅ Slug generation (exclude current ID)
    $slug = Str::slug($request->name);
    $originalSlug = $slug;
    $count = 1;

    while (KnowledgeHubMaster::where('slug', $slug)->where('id', '!=', $topic_id)->exists()) {
        $slug = $originalSlug . '-' . $count++;
    }

    $mainbrochure->slug = $slug;

    // ✅ Image upload (same as add)
    if ($request->hasFile('brochure_image')) {
        $file = $request->file('brochure_image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $destinationPath = public_path('uploads/files/knowledge_hub');

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        $file->move($destinationPath, $filename);
        $mainbrochure->image = 'uploads/files/knowledge_hub/' . $filename;
    }

    $mainbrochure->save();
    $brochure_id = $mainbrochure->id;

    // ✅ DELETE OLD DETAILS (simple approach like fresh insert)
    KnowledgeDetails::where('kh_id', $brochure_id)->delete();

    // ✅ Get array inputs (same as add function)
    $sl_no = $request->input('sl_no') ?? [];
    $product = $request->input('product') ?? [];
    $commodities = $request->input('commodities') ?? [];
    $language = $request->input('language') ?? [];
    $location = $request->input('location') ?? [];
    $description = $request->input('description') ?? [];
    $shortdescription = $request->input('short_description') ?? [];
    $webinar_link = $request->input('webinar_link') ?? [];
    $podcast_link = $request->input('podcast_link') ?? [];
    $podcast_time = $request->input('podcast_time') ?? [];
    $brochure_link = $request->input('brochure_link') ?? [];
 
    $brochureDetailsInsert = [];

    // ✅ Loop & prepare insert (same as add)
    foreach ($sl_no as $key => $value) {

        $brochureDetailsInsert[] = [
            'kh_id' => $brochure_id,
            'product_id' => $product[$key] ?? '',
            'commodity_id' => $commodities[$key] ?? '',
            'language_id' => $language[$key] ?? '',
            'location_id' => $location[$key] ?? '',
            'short_description' => $shortdescription[$key] ?? '',
            'description' => $description[$key] ?? '',
            'webinar_link'  => !empty($webinar_link[$key])  ? $webinar_link[$key]  : NULL,
            'podcast_link'  => !empty($podcast_link[$key])  ? $podcast_link[$key]  : NULL,
            'podcast_time'  => !empty($podcast_time[$key])  ? $podcast_time[$key]  : NULL,
            'brochure_link' => !empty($brochure_link[$key]) ? $brochure_link[$key] : NULL,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
 // dd($brochureDetailsInsert);
    // ✅ Bulk insert
    if (!empty($brochureDetailsInsert)) {
        KnowledgeDetails::insert($brochureDetailsInsert);
    }

    return redirect()->route('allKhallId')->with('msg', 'Details updated successfully!');
}


 
}
