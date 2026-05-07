<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CmsLinks;
use App\Models\Webinar;
use Image;
use Auth;
use DB;

class AjaxController extends Controller
{
    
    public function ajaxWebinar(Request $request) {
 
        $lng='en';
 
        $DataBag = array();  
         
        $query = \App\Models\Webinar::where('status', '=', '1')->with('WebinarCategory');

        if( isset($_POST['webinar_industry']) && $_POST['webinar_industry'] != '' ) {
            $query = $query->where( function($query) {
                $query = $query->where( 'webinar_industry', 'like', '%'.$_POST['webinar_industry'].',%' );
            } );
        }

 
        if( isset($_POST['webinar_category']) && $_POST['webinar_category'] != '' ) {
            $query = $query->where( function($query) {
                $query = $query->where( 'webinar_category', 'like', '%'.$_POST['webinar_category'].',%' );
            } );
        }
 
        if( isset($_POST['webinar_topic']) && $_POST['webinar_topic'] != '' ) {
            $query = $query->where( function($query) {
                $query = $query->where( 'webinar_topic', 'like', '%'.$_POST['webinar_topic'].',%' );
            } );
        }
 
        if( isset($_POST['search']) && $_POST['search'] != '' ) {
            $query = $query->where( function($query) {
                $query = $query->where( 'name', 'LIKE', '%'.trim($_POST['search']).'%' );
            } );
        }

        $articlesData = $query->orderBy('id', 'desc')->paginate( 20 );
        $DataBag['listData'] = $articlesData;
 
        foreach($DataBag['listData'] as $key=>$val){

            $cat= explode(',',$val['webinar_category']);

            $allcat='';

             foreach($cat as $row){
                 if($row!=''){
                   $wbcat= \App\Models\WebinarCategory::where('id', '=', $row)->first();
                   $allcat.= $wbcat->name.',';
                 } 
             }
  
             $DataBag['listData'][$key]['webinarcat']= $allcat; 
        }
          
        $listData = $DataBag['listData'];
        $printdata='';

 
        if(isset($listData)  && count($listData)>0){
            foreach( $listData as $v ){
        
            $imageURL='';
                    
            if(isset($v->image) && $v->image!=''){
                $imageURL = asset('public/uploads/user_images/original/'.$v->image); 
            }
            
            if(isset($imageURL) && !empty($imageURL)){
            $image='<img src="'.$imageURL.'" >';
            }
            else{
                $image= '<img src="'.asset('public/images/default_multotec.jpg').'" style="height: 100%;">';
            }
        
            $gh=route('front.webinarCont', array('lng' => $lng, 'id' => $v->slug));

            //echo $v->webinar_type;die;

            if($v->webinar_type == '1') 
                $stl = 'style="background: #3b8d65;"';
            elseif($v->webinar_type == '2') 
                $stl = 'style="background: #90c84c;"';
            

            $printdata.= '<div class="col-sm-4 col-md-4">
            <div class="picinner">
            <h4 '.$stl.'>'.$v->name.'</h4> 
            '.$image.'
            <div class="piccont">
            <ul>
            <li><a >Product: '.$v->webinarcat.'</a></li> 
            <li><a href="'.$gh.'">Watch Webinar</a></li>  
            </ul>
            <p>'.html_entity_decode( $v->short_description ).'</p>
            </div>
            </div>
            </div>';
        
            }
        }
        else{
            $printdata.= '<h3>No Record Found</h3>';
        }
     
        echo  $printdata;  
    }


    public function checkSlugUrl(Request $request) {

        $tabFilter = ['PRODUCT_CATEGORY', 'PRODUCT', 'DYNA_CONTENT', 'INDUSTRY', 'FLOWSHEET_CATEGORY', 'FLOWSHEET'];

        $slug_url = trim( $request->input('slug_url') );
        $table_id = trim( $request->input('id') );
        $ck = CmsLinks::where('slug_url', '=', $slug_url)->where('table_id', '!=', $table_id)
        ->whereIn('table_type', $tabFilter)->count();
        if( $ck > 0 ) {
            return "false";
        } else {
            return "true";
        }
    }

    public function checkSlugUrlWb(Request $request) {

        
        $slug_url = trim( $request->input('slug_url') );
        $table_id = trim( $request->input('id') );
        $ck = Webinar::where('slug', '=', $slug_url)->where('id', '!=', $table_id)->count();
        if( $ck > 0 ) {
            return "false";
        } else {
            return "true";
        }
    }
 
    public function checkSlugUrlSelf(Request $request) {

        $slug_url = trim( $request->input('slug_url') );
        $table_id = trim( $request->input('id') );
        $table = trim( $request->input('tab') );
        
        $ck = DB::table($table)->where('slug', '=', $slug_url)->where('id', '!=', $table_id)
        ->where('status', '!=', '3')->count();
        if( $ck > 0 ) {
            return "false";
        } else {
            return "true";
        }
    }

    public function fileDelete(Request $request) {

        $table = trim( $request->input('table_name') );
        $id = trim( $request->input('id') );

        $r = DB::table( $table )->where('id', '=', $id)->delete();
        if( $r ) {
            return 'ok';
        }

        return 'error';
    }

}
