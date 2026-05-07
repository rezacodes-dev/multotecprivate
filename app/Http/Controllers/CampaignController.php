<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media\FilesMaster;
use App\Models\Media\Images;
use App\Models\CmsLinks;

use App\Models\Campaign;
use App\Models\SourceType;
use App\Models\Referral;
use App\Exports\ReferralExport;
use Auth;
use Image;
use DB;
use Excel; 

class CampaignController extends Controller
{
  
	public function downloadReferral(Request $request) {


		$actBtnValue = trim( $request->input('action_btn') );



		if($actBtnValue=='download')
        {


		$type='xlsx';

		$url=$request->input('url');
		$idsArr = $request->input('ids');
		$start_date = $request->input('start_date');
		$end_date = $request->input('end_date');

		// if(isset($url) && $url!=''){
		// 	$Data = Referral::where('referral','like', '%'.$url.'%')->orderBy('created_at', 'desc')->get();
		// }
		// else if(isset($idsArr) && count($idsArr)>0){
		// 	$Data = Referral::whereIn('id', $idsArr)->orderBy('created_at', 'desc')->get();
		// }
		// else{
		// 	$Data = Referral::orderBy('created_at', 'desc')->get();
		// }


		if( isset($url)  && $url!=''  && isset($start_date)  && $start_date!='' &&  isset($end_date)  && $end_date!='' ){
			$Data = Referral::where('referral','like', '%'.$url.'%')->whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'desc')->get();

		}
		
		else if(isset($start_date)  && $start_date!='' &&  isset($end_date)  && $end_date!='' ){
			$Data = Referral::whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'desc')->get();

		}

		else if(isset($url)  && $url!='' ){
			$Data = Referral::where('referral','like', '%'.$url.'%')->orderBy('id', 'desc')->get();

		} 

		else if(isset($idsArr) && count($idsArr)>0){
			$Data = Referral::whereIn('id', $idsArr)->orderBy('created_at', 'desc')->get();
		}
		else{
			$Data = Referral::orderBy('id', 'desc')->get();
		}

 
		
  
		foreach($Data as $key=>$row){

			$r= explode('/',$row->referral);
			
 
		if(isset($r[2])){


			$pattern = '/https/i';
			$r[2]= preg_replace($pattern, '', $r[2]);

			$pattern = '/http/i';
			$r[2]= preg_replace($pattern, '', $r[2]);

			$pattern = '/www./i';
			$r[2]= preg_replace($pattern, '', $r[2]);

			

					$hits=DB::table('campaign') 
					->selectRaw('name,url,source_type')  
					->where('url','like', '%'.$r[2].'%')
					->first();

		}

  
			 if(isset($hits->name)){
  
				 $source_type=DB::table('source_type') 
				 ->selectRaw('name')  
				 ->where('id','=', $hits->source_type)
				 ->first();
  
				 $Data[$key]['source_type']= $source_type->name;
				 $Data[$key]['campaign']= $hits->name;
			 }
			 else{
				$Data[$key]['source_type']= 'N/A';
				$Data[$key]['campaign']= 'N/A';
			 }
   
		 }

		

        $filename = 'Multotec_Referral_'.date('m-d-Y');
        $excelArr = array();
        foreach($Data as $v) {
            $arr = array();
            $arr['Referral URL'] = $v->referral;
            $arr['Source Type'] = $v->source_type;
            
            $arr['Campaign'] = $v->campaign;
            
            $arr['IP Address'] = $v->ip;
            array_push($excelArr, $arr);

			
        }
		 
        // return Excel::create($filename, function($excel) use ($excelArr) {
             
        //     $excel->setTitle('Multotec');
        //     $excel->setCreator('Multotec');
        //     $excel->setCompany('Multotec');
        //     $excel->setDescription('Multotec Referral');
            
        //     $excel->sheet('All Referral', function($sheet) use ($excelArr)
        //     {
        //         $sheet->fromArray($excelArr);
        //     });

			 
        // })->download($type);

		return Excel::download(new ReferralExport($excelArr), $filename . '.' . $type);
		 
	}

	else {

		$start_date = $request->input('start_date');
		$end_date = $request->input('end_date');
		$url = $request->input('url');
		$name = $request->input('name');

		 
		$DataBag = array();
        $DataBag['parentMenu'] = 'Traffic';
        $DataBag['childMenu'] = 'allCp';
      
		$DataBag['url']=$url;
		$DataBag['start_date']=$start_date;
		$DataBag['end_date']=$end_date;

		
		if( isset($url)  && $url!=''  && isset($start_date)  && $start_date!='' &&  isset($end_date)  && $end_date!='' ){
			$DataBag['allReferral'] = Referral::where('referral','like', '%'.$url.'%')->whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'desc')->get();

		}

		else if(isset($url)  && $url!=''  && isset($start_date)  && $start_date!='' &&  isset($end_date)  && $end_date!='' ){
			$DataBag['allReferral'] = Referral::where('referral','like', '%'.$url.'%')->whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'desc')->get();

		}
		
		else if(isset($start_date)  && $start_date!='' &&  isset($end_date)  && $end_date!='' ){
			$DataBag['allReferral'] = Referral::whereBetween('created_at', [$start_date, $end_date])->orderBy('id', 'desc')->get();

		}

		else if(isset($url)  && $url!='' ){
			$DataBag['allReferral'] = Referral::where('referral','like', '%'.$url.'%')->orderBy('id', 'desc')->get();

		}

		else{
			$DataBag['allReferral'] = Referral::orderBy('id', 'desc')->get();
		}

        foreach($DataBag['allReferral'] as $key=>$row){


			$r= explode('/',$row->referral);
			if(isset($r[2])){
				

          


		   $pattern = '/https/i';
           $r[2]= preg_replace($pattern, '', $r[2]);

           $pattern = '/http/i';
           $r[2]= preg_replace($pattern, '', $r[2]);

           $pattern = '/www./i';
           $r[2]= preg_replace($pattern, '', $r[2]);
 
           

			$hits=DB::table('campaign') 
            ->selectRaw('name,url,source_type')  
            ->where('url','like', '%'.$r[2].'%')
            ->first();
			}

 
			if(isset($hits->name)){
 
                $source_type=DB::table('source_type') 
                ->selectRaw('name')  
                ->where('id','=', $hits->source_type)
                ->first();
 
                $DataBag['allReferral'][$key]['source_type']= $source_type->name;
                $DataBag['allReferral'][$key]['campaign']= $hits->name;
            }
			else{
                $DataBag['allReferral'][$key]['source_type']= 'N/A';
                $DataBag['allReferral'][$key]['campaign']= 'N/A';
            }
  
		}
         
        return view('dashboard.list_referral', $DataBag);
	}
    }



	public function getReferral($url) {
        $DataBag = array();
        $DataBag['parentMenu'] = 'Traffic';
        $DataBag['childMenu'] = 'allCp';
        $DataBag['allReferral'] = Referral::where('referral','like', '%'.$url.'%')->orderBy('id', 'desc')->get();

		$DataBag['url']=$url;

        foreach($DataBag['allReferral'] as $key=>$row){

           $r= explode('/',$row->referral);
           
		   if(isset($r[2])){
				 
          
			$pattern = '/https/i';
			$r[2]= preg_replace($pattern, '', $r[2]);

			$pattern = '/http/i';
			$r[2]= preg_replace($pattern, '', $r[2]);

			$pattern = '/www./i';
			$r[2]= preg_replace($pattern, '', $r[2]);

 
			$hits=DB::table('campaign') 
            ->selectRaw('name,url,source_type')  
            ->where('url','like', '%'.$r[2].'%')
            ->first();

		   }

 
			if(isset($hits->name)){
 
                $source_type=DB::table('source_type') 
                ->selectRaw('name')  
                ->where('id','=', $hits->source_type)
                ->first();
 
                $DataBag['allReferral'][$key]['source_type']= $source_type->name;
                $DataBag['allReferral'][$key]['campaign']= $hits->name;
            }
			else{
                $DataBag['allReferral'][$key]['source_type']= 'N/A';
                $DataBag['allReferral'][$key]['campaign']= 'N/A';
            }
  
		}
         
        return view('dashboard.list_referral', $DataBag);
    } 

   	public function allCampaigns() {
   		$DataBag = array();
    	$DataBag['parentMenu'] = 'Traffic';
    	$DataBag['childMenu'] = 'allCp';
    	$DataBag['allProdCats'] = Campaign::with(['SourceType'])->where('status', '!=', '3')->orderBy('id', 'desc')->get();

		foreach($DataBag['allProdCats'] as $key=>$row){

			$hits=DB::table('referral') 
            ->selectRaw('count(id) as hits')  
            ->where('referral','like', '%'.$row->url.'%')
            ->first();
 
			$DataBag['allProdCats'][$key]['hits']= $hits->hits;
		}
        

    	return view('dashboard.campaign.index', $DataBag);
   	} 

    public function addCampaign() {
        $DataBag = array(); 
    	$DataBag['parentMenu'] = 'Traffic';
    	$DataBag['childMenu'] = 'allCp';

		$DataBag['allSource'] = SourceType::where('status', '=', '1')->select('name', 'id')
    	->orderBy('name', 'asc')->get();
      
        $DataBag['insert_id'] = md5(microtime(TRUE));
    	return view('dashboard.campaign.add', $DataBag);
    }
  
    /**** SAVE PRODUCT CATEGORY ***/

    public function saveCampaign(Request $request) {
    	
    	$insert_id = trim( $request->input('insert_id') ); // Page Builder -- Insert Time

    	$Campaign = new Campaign;
    	$Campaign->name = trim( ucfirst($request->input('name')) );
		$Campaign->source_type = trim( ucfirst($request->input('source_type')) ); 
		$Campaign->url = trim( $request->input('url') );

    	$resx = $Campaign->save();
    	if( isset($resx) && $resx == 1 ) {
 
    		return back()->with('msg', 'Source Type Created Successfully.')
    		->with('msg_class', 'alert alert-success');
    	}

    	return back()->with('msg', 'Something Went Wrong')
    	->with('msg_class', 'alert alert-danger');
    }


    public function deleteCampaign($category_id) {
    	$ck = Campaign::find($category_id);
    	if( isset($ck) && !empty($ck) ) {
    		$ck->status = '3';
    		$res = $ck->save();
    		if( isset($res) && $res == 1 ) {
 
                return back()->with('msg', 'Source Type Deleted Successfully.')
    			->with('msg_class', 'alert alert-success');
    		}
    	}

    	return back('msg', 'Something Went Wrong')->with('msg_class', 'alert alert-danger');
    }

    public function editCampaign($category_id,Request $request) {

		
        $DataBag = array();
        
    	$DataBag['parentMenu'] = 'Traffic';
    	$DataBag['childMenu'] = 'allCp';

		$DataBag['allSource'] = SourceType::where('status', '=', '1')->select('name', 'id')
    	->orderBy('name', 'asc')->get();
    	         
        $DataBag['content_id'] = $category_id;
  
        $DataBag['prodCat'] = Campaign::where('status', '=', '1')->where('id',$category_id)->orderBy('name', 'asc')->first();
         
    	return view('dashboard.campaign.add', $DataBag);
    }


    /**** UPDATE PRODUCT CATEGORY ***/

    public function updateCampaign(Request $request, $category_id) {
 
            $Campaign = Campaign::find($category_id); 
			$Campaign->name = trim( ucfirst($request->input('name')) );
			$Campaign->source_type = trim( ucfirst($request->input('source_type')) ); 
			$Campaign->url = trim( $request->input('url') );
            
            $resx = $Campaign->save();
            
            if( isset($resx) && $resx == 1 ) {
    
               
                return redirect()->route('allCp')->with('msg', 'Source Type Updated Successfully.')
                ->with('msg_class', 'alert alert-success');
                
            }
        
    	return back()->with('msg', 'Something Went Wrong')
    	->with('msg_class', 'alert alert-danger');
    }

    
}
