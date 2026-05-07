<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CmsLinks;
use Jenssegers\Agent\Agent;

class PreviewController extends Controller
{
    public function preview( $device, $slug ) {

    	$DataBag = array();

    	if( $device != '' && $device != null && $slug != '' && $slug != null ) {

    		$cms = CmsLinks::where('slug_url', '=', trim($slug))->first();
    		if( !empty($cms) ) {

    			$pageInfo = getCmsPageInfo( $cms->id );
                $DataBag['pageInfo'] = $pageInfo;
    		}
    	}

        $DataBag['device'] = $device;
    	return view('front_end.preview', $DataBag);
    }

    public function previewTool() {

        //https://github.com/jenssegers/agent
    	return view('front_end.preview_tool');
    }
}
