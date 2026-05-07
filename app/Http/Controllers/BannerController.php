<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media\Images;
use App\Models\Banners;
use Image;
use Auth;
use File;

class BannerController extends Controller
{
    
    public function index() {

    	$DataBag = array();
    	$DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['childMenu'] = 'banner';
    	$DataBag['banners'] = Banners::with('BannerImages')->get();
    	return view('dashboard.banners.index', $DataBag);
    }

    public function add() {

    	$DataBag = array();
    	$DataBag['GparentMenu'] = 'contentManagement';
    	$DataBag['childMenu'] = 'banner';
    	return view('dashboard.banners.create', $DataBag);
    }

    public function save(Request $request) {

    	$this->validate($request,[
            
            'banner_image' => 'required|image|mimes:jpeg,jpg,png|dimensions:min_width=1280,min_height=400|max:2048'        
        ],[
        
            'banner_image.required' => 'Please select banner image',
            'banner_image.dimensions' => 'Banner dimensions should be (1280 x 400)',
            'banner_image.max' => 'Maximum 2MB Image Size Accept.'
        ]);

    	$Images = new Images;
    	$Images->name = trim($request->input('name'));
        $Images->alt_title = trim($request->input('alt_title'));
        $Images->caption = trim($request->input('caption'));
        $Images->title = trim($request->input('title'));
        $Images->description = trim($request->input('description'));
        $Images->created_by = Auth::user()->id;

        $banner_image = $request->file('banner_image');
		$real_path = $banner_image->getRealPath();
        $file_orgname = $banner_image->getClientOriginalName();
        $file_size = $banner_image->getSize();
        $file_ext = strtolower($banner_image->getClientOriginalExtension());
        $file_newname = "homebanner"."_".time().".".$file_ext;
        $destinationPath = public_path('/uploads/files/media_images/');
	    $thumb_path = $destinationPath."thumb"; 
        
        $imgObj = Image::make($real_path);
    	$imgObj->resize(100, 100, function ($constraint) {
	    	$constraint->aspectRatio();
		})->save($thumb_path.'/'.$file_newname);

    	$banner_image->move($destinationPath, $file_newname);
    	
    	$Images->image = $file_newname;
    	$Images->size = $file_size;
    	$Images->extension = $file_ext;
    	if( $Images->save() ) {
    		$Banner = new Banners;
    		$Banner->image_id = $Images->id;
    		$Banner->created_by = Auth::user()->id;
    		$Banner->save();
    		return back()->with('msg', 'Banner Uploaded Successfully')
    		->with('msg_class', 'alert alert-success');
    	}

    	return back();
    }

    public function delete($imgid) {

    	Banners::where('image_id', '=', $imgid)->delete();
    	$ck = Images::find($imgid);
    	$image = $ck->name;
    	$ck->delete();
    	File::delete(['public/uploads/files/media_images/thumb/'. $image, 'public/uploads/files/media_images/'. $image]);
        return back()->with('msg', 'Banner Deleted Successfully.')->with('msg_class', 'alert alert-success');
    }
}
