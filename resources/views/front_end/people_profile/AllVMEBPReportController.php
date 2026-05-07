<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\missionary;
use App\Models\Employee;
use App\Models\Participants;
use App\Models\claim_villagemission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\country;
use function PHPUnit\Framework\isNull;
class AllVMEBPReportController extends Controller
{
    private $pagenation_number=20;

    public function index(Request $request)
    {
        try{
            $data=[];
            $data=getPermissionArray('vmebp_reports');//get permission for each module if its add in module table
            $user = Auth::user();
            $data["user"]=$user;
            if($data["permission_array"]["vmebp_reports"]["no_view"]==1){
            $data["menu"]="vmebp_reports";
            $query_string=[];
            $data["query_string"]='';
            $data["search_filter"]='';
            $data["search_batch"]='';
            $data["status_filter"]='';
            if(!isset($request->page)){
                $request->page=1;
            }
            $data["page"]=(isset($request->page))?$request->page:1;
            $data["offset"]=($request->page-1)*$this->pagenation_number;
            
            return view('all_vmebp_reports.list',["data"=>$data]);
        }else{
            return Redirect::to('dashboard')->with('error', "Access denied .");
        } 
        }catch(exception $e){
                
        }
    }
    public function employeechild18vmebp(Request $request){
        try{
            $currentYear=date('Y');
           // dd('test');
            $data=[];
            $data=getPermissionArray('vmebp_reports');//get permission for each module if its add in module table
            $user = Auth::user();
            $data["user"]=$user;
            if($data["permission_array"]["vmebp_reports"]["no_view"]==1){
            $data["menu"]="vmebp_reports";
            $query_string=[];
            $data["query_string"]='';
            $data["search_filter"]='';
            $data["search_batch"]='';
            $data["status_filter"]='';
            if(!isset($request->page)){
                $request->page=1;
            }
            $data["page"]=(isset($request->page))?$request->page:1;
            $data["offset"]=($request->page-1)*$this->pagenation_number;
            $data["results"] = DB::table('participants')
            ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
            ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
            ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
            ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
            ->leftJoin('employee','employee.id','=','participants.emp_id')
            ->leftJoin('childrens','childrens.id','=','participants.children_id')
            ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
            , 'participants.fname as e_f_name'
            , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
            ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
            ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','relation_code.name as relation_name','participant_healthcare.group_type_id',
            'missionaries.lname as m_l_name','missionaries.fname as m_f_name',
            'employee.lname as emp_l_name','employee.fname as emp_f_name','childrens.lname as child_l_name','childrens.dob as childdob','childrens.fname as child_f_name','participants.missionary_id','participants.emp_id','participants.spouse_id','participants.children_id')
            // ->whereNotNull('participant_opt_outs.opt_out_date')
             ->where('participants.status',1)
             ->where('participant_healthcare.health_coverage_member_id',2)
             ->orderByRaw('isNULL(childrens.dob) asc, datediff(childrens.dob,now()) asc')
             //->orderByRaw('childrens.dob asc')
            
            ->where('health_coverage_members.id','=',2)
            ->whereNotNull('participants.children_id')
             //->whereRaw('coverage_id = 2')

          //   ->whereRaw('timestampdiff(year, childrens.dob, curdate()) <= 17')
        //   ->whereRaw('timestampdiff(year, childrens.dob, curdate()) = 17') // Currently 17 years old changed
        ->whereRaw("timestampdiff(year, childrens.dob, '".$currentYear."-01-01') = 17")
         // ->whereIn(DB::raw('MONTH(childrens.dob)'), [ 11]) 
        //  ->whereRaw('YEAR(childrens.dob) = YEAR(CURDATE()) - 18') // Will turn 18 this year
             ->groupBy('childrens.id')
             //->get();
            // dd($data["results"]);

             ->paginate($this->pagenation_number);
         //   dd($data["results"]);
           //print_r($data["results"]);die;
           $data["per_page"]=$this->pagenation_number;
           $data["data"]= $data["results"];
            return view('all_vmebp_reports.employeechild18vmebp',["data"=>$data])->with('count', 1);;
        }else{
            return Redirect::to('dashboard')->with('error', "Access denied .");
        } 
        }catch(exception $e){
                
        }

    }
    public function employeechild18vmebpsearch(Request $request){
       // dd($request->all());

        try {
            $currentYear=date('Y');
            $data = [];
            $data = getPermissionArray('all-reports');
            $user = Auth::user();
            $data["user"] = $user;
            $search_data =  DB::table('participants')
            ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
            ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
            ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
            ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
            ->leftJoin('employee','employee.id','=','participants.emp_id')
            ->leftJoin('childrens','childrens.id','=','participants.children_id')
            ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
            , 'participants.fname as e_f_name'
            , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
            ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
            ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','relation_code.name as relation_name','participant_healthcare.group_type_id',
            'missionaries.lname as m_l_name','missionaries.fname as m_f_name',
            'employee.lname as emp_l_name','employee.fname as emp_f_name','childrens.lname as child_l_name','childrens.dob as childdob','childrens.fname as child_f_name','participants.missionary_id','participants.emp_id','participants.spouse_id','participants.children_id')
            ->where('participants.status',1)
             ->where('participant_healthcare.health_coverage_member_id',2)
            
             ->orderByRaw('isNULL(childrens.dob) asc, datediff(childrens.dob,now()) asc')
             
            ->whereNotNull('participants.children_id');
          
            
           
            if (isset($request->search_batch) && $request->search_batch != '') {
                $search_string = $request->search_batch;
                $search_data = $search_data->where(function ($q) use ($search_string) {
                    $q->where(
                        DB::raw("CONCAT(
                            COALESCE(childrens.lname, ''), ' ', COALESCE(childrens.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(participants.lname, ''), ' ', COALESCE(participants.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                   // $q->whereRaw('timestampdiff(year, childrens.dob, curdate()) = 17') ;
                });
            }
            if (!empty($request->search_year)) {
               
                $search_data = $search_data->selectRaw("timestampdiff(year, childrens.dob, '".$request->search_year."-01-01') as childA1 ");
           }
          
           
           if (!empty($request->search_year)) {
            $search_data =  $search_data->whereRaw("timestampdiff(year, childrens.dob, '".$request->search_year."-01-01') = 17");
           }
           if(empty($request->search_year))
           {
         //   $search_data = $search_data->whereRaw('timestampdiff(year, childrens.dob, curdate()) = 17') ;
            $search_data = $search_data->whereRaw("timestampdiff(year, childrens.dob, '".$currentYear."-01-01') = 17");
           }
           
          //$search_data = $search_data->toSql();
         $search_data = $search_data->groupBy('childrens.id')->paginate($this->pagenation_number);
          //dd($search_data);
          
          
            $html = '';
            $offset = ($request->page - 1) * $this->pagenation_number;
            foreach ($search_data as $key => $res) {
                $html .= '<tr id="row-">';
                $html .= '<td  style="width:2%;;text-align:left;" >';
                $html .= $offset + $key + 1;
                $html .= '</td>';
                $html .= '<td style="width:10%;text-align:left">';
                $html .= $res->m_f_name . ($res->m_l_name && $res->m_f_name ? ',' : '') . ' ' . $res->m_l_name;
                $html .= '</td>';
                $html .= '<td style="width:10%;text-align:left">';
                $html .= $res->e_vmcode??'';
                $html .= '</td>';
                $html .= '<td style="width:10%; text-align:left;">';
                $html .= $res->child_f_name . ($res->child_l_name && $res->child_f_name ? ',' : '') . ' ' . $res->child_l_name;
                $html .= '</td>';   
                
                $html .= '<td style="width:10%;text-align:left">';
                 $html .=  $res->childdob ? date("m-d-Y", strtotime($res->childdob)) : '' ;
                 $html .= '</td>'; 
                $html .= '<td style="width:10%;text-align:left">';
                if(isset($res->childdob) && $res->childdob != '')
                $birth_date = $res->childdob;
                $current_date = date('Y-m-d');
                $birth_timestamp = strtotime($birth_date);
                $current_timestamp = strtotime($current_date);
                $diff_seconds = $current_timestamp - $birth_timestamp;
                $age_years = $diff_seconds / (60 * 60 * 24 * 365.25);
                $age_years = round($age_years);
                $html .=  $age_years ."Years"  ;
                $html .= '</td>';
                $html .= '<td style="width:10%;text-align:left">';
                $html .= $res->coverage_name??'';
                $html .= '</td>';
               
                $html .= '</tr>';
            }
            if (trim($html) != '') {
                $pagenation = \View::make('include.pagenation', ['paginator' => $search_data])->render();
            } else {
                $pagenation = '';
                $html .= '<tr>';
                $html .= '<td  style="width:100%" class="text-nowrap" colspan="10">';
                $html .= 'No Data Found';
                $html .= '</td>';
            }
            if (!isset($request->page)) {
                $request->page = 1;
            }
            $page = (isset($request->page)) ? $request->page : 1;
            $offset = ($request->page - 1) * $this->pagenation_number;
            $start_page_number = $offset + 1;
            $end_page_number = $search_data->total();
            if ($end_page_number >= ($start_page_number + $this->pagenation_number)) {
                $end_page_number = $offset + $this->pagenation_number;
            }
            return response()->json([
                'message' => 'contributions',
                'html' => $html,
                'pagenation' => $pagenation,
                'start_page_number' => $start_page_number,
                'end_page_number' => $end_page_number,
                'total_records' => $search_data->total(),
                'status' => 1
            ], 200);

        } catch (exception $e) {

        }

    }
    public function employeechild18vmebpdownload(Request $request){
        $currentYear=date('Y');
       // dd($request->all());
        $data=[];
        $data = getPermissionArray('all-reports');
        $user = Auth::user();
        $data["user"]=$user;
        $csv_data=[];
        $csv_data[]=array('Sl.No', 	'Primary Insurer', 	'VMCODE', 	'Child', 	'Child Date of Birth', 	'Age', 	'Coverage');
     
        $search_data =  $search_data =  DB::table('participants')
        ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
        ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
        ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
        ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
        ->leftJoin('employee','employee.id','=','participants.emp_id')
        ->leftJoin('childrens','childrens.id','=','participants.children_id')
        ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
        , 'participants.fname as e_f_name'
        , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
        ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
        ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','relation_code.name as relation_name','participant_healthcare.group_type_id',
        'missionaries.lname as m_l_name','missionaries.fname as m_f_name',
        'employee.lname as emp_l_name','employee.fname as emp_f_name','childrens.lname as child_l_name','childrens.dob as childdob','childrens.fname as child_f_name','participants.missionary_id','participants.emp_id','participants.spouse_id','participants.children_id')
        ->where('participants.status',1)
         ->where('participant_healthcare.health_coverage_member_id',2)
        
         ->orderByRaw('isNULL(childrens.dob) asc, datediff(childrens.dob,now()) asc')
         
        ->whereNotNull('participants.children_id');
      
        
       
        if (isset($request->search_batch) && $request->search_batch != '') {
            $search_string = $request->search_batch;
            $search_data = $search_data->where(function ($q) use ($search_string) {
                $q->where(
                    DB::raw("CONCAT(
                        COALESCE(childrens.lname, ''), ' ', COALESCE(childrens.fname, '')
                    )"),
                    'LIKE',
                    "%{$search_string}%"
                );
                $q->orWhere(
                    DB::raw("CONCAT(
                        COALESCE(participants.lname, ''), ' ', COALESCE(participants.fname, '')
                    )"),
                    'LIKE',
                    "%{$search_string}%"
                );
               
            });
        }
        if (!empty($request->search_year)) {
               
            $search_data = $search_data->selectRaw("timestampdiff(year, childrens.dob, '".$request->search_year."-01-01') as childA1 ");
       }
      
       
       if (!empty($request->search_year)) {
        $search_data =  $search_data->whereRaw("timestampdiff(year, childrens.dob, '".$request->search_year."-01-01') = 17");
       }
       if(empty($request->search_year))
       {
      //  $search_data = $search_data->whereRaw('timestampdiff(year, childrens.dob, curdate()) = 17') ;
        $search_data = $search_data->whereRaw("timestampdiff(year, childrens.dob, '".$currentYear."-01-01') = 17");
       }
      
      // $search_data =  $search_data->groupBy('childrens.id');
      $search_data = $search_data->groupBy('childrens.id')->get();
     // dump($search_data->toArray());
      
       // dd($search_data);
        


        foreach($search_data as $key=>$val){
            $tmp_arr=[];
            $tmp_arr[0]=$key + 1;
            $tmp_arr[1]=$val->m_l_name . ($val->m_l_name && $val->m_f_name ? ',' : '') . ' ' . $val->m_f_name;
            $tmp_arr[2]=$val->e_vmcode??'';
            $tmp_arr[3]=$val->child_l_name . ($val->child_l_name && $val->child_f_name ? ',' : '') . ' ' . $val->child_l_name;
           
            if (!empty($val->childdob) && strtotime($val->childdob)) {
                $tmp_arr[4] = date("M, Y", strtotime($val->childdob));
            } else {
                $tmp_arr[4] = ''; 
            }
            if (!empty($val->childdob) && strtotime($val->childdob)) {
                $birth_date = $val->childdob;
                $current_date = date('Y-m-d');
                $birth_timestamp = strtotime($birth_date);
                $current_timestamp = strtotime($current_date);
                $diff_seconds = $current_timestamp - $birth_timestamp;
                $age_years = $diff_seconds / (60 * 60 * 24 * 365.25);
                $age_years = round($age_years);
                $tmp_arr[5] = $age_years ." Years" ;
            } else {
                $tmp_arr[5] = ''; 
            }
            $tmp_arr[6]=$val->coverage_name;
            $csv_data[]= $tmp_arr;
        }
    
        csvDownlaod($csv_data,"employeechild18vmebp.csv");   


    }
    public function employeespouse65medvmebp(Request $request){
        try{
            $currentYear =date('Y'); // Use provided year or current year
             $data=[];
             $data=getPermissionArray('vmebp_reports');//get permission for each module if its add in module table
             $user = Auth::user();
             $data["user"]=$user;
             if($data["permission_array"]["vmebp_reports"]["no_view"]==1){
             $data["menu"]="vmebp_reports";
             $query_string=[];
             $data["query_string"]='';
             $data["search_filter"]='';
             $data["search_batch"]='';
             $data["status_filter"]='';
             if(!isset($request->page)){
                 $request->page=1;
             }
             $data["page"]=(isset($request->page))?$request->page:1;
             $data["offset"]=($request->page-1)*$this->pagenation_number;
             $currentYear = $request->search_year ?? date('Y'); // use the request year or default to current year

             $data["results"] = DB::table('participants')
                 ->leftJoin('participant_healthcare', 'participant_healthcare.participants_id', '=', 'participants.id')
                 ->leftJoin('health_coverage_members', 'health_coverage_members.id', '=', 'participant_healthcare.health_coverage_member_id')
                 ->leftJoin('relation_code', 'relation_code.id', '=', 'participants.relationcode_id')
                 ->leftJoin('missionaries', 'missionaries.id', '=', 'participants.missionary_id')
                 ->leftJoin('employee', 'employee.id', '=', 'participants.emp_id')
                 ->leftJoin('spouses', 'spouses.id', '=', 'participants.spouse_id')
                 ->select(
                     'participants.id as participantid',
                     'participant_healthcare.pss_no as pssno',
                     'participants.fname as e_f_name',
                     'participants.lname as e_l_name',
                     'participants.vmcode as e_vmcode',
                     'health_coverage_members.id as coverage_id',
                     'health_coverage_members.member_name as coverage_name',
                     'participant_healthcare.start_date',
                     'participant_healthcare.end_date',
                     'participant_healthcare.is_primary',
                     'participants.id as participant_id',
                     'relation_code.name as relation_name',
                     'participant_healthcare.group_type_id',
                     'missionaries.lname as m_l_name',
                     'missionaries.fname as m_f_name',
                     'employee.lname as emp_l_name',
                     'employee.fname as emp_f_name',
                     'spouses.spouse_fname',
                     'spouses.spouse_lname',
                     'spouses.spouse_dob',
                     'spouses.spouse_phone',
                     'participants.missionary_id',
                     'participants.emp_id',
                     'participants.spouse_id'
                 )
                 ->whereNotNull('participants.spouse_id')
                 ->where('participants.status', 1)
                 ->whereIn('health_coverage_members.id', [2, 1])
                 ->where(function ($query) use ($currentYear) {
                     $query->where(function ($q) use ($currentYear) {
                         $q->whereRaw("timestampdiff(year, spouses.spouse_dob, ?) = 64", [$currentYear . "-01-01"])
                           ->whereRaw("YEAR(spouses.spouse_dob) = ?", [$currentYear - 65]);
                     })
                     ->orWhere(function ($q) use ($currentYear) {
                         $q->whereRaw("timestampdiff(year, employee.dob, ?) = 64", [$currentYear . "-01-01"])
                           ->whereRaw("YEAR(employee.dob) = ?", [$currentYear - 65]);
                     });
                 })
                 ->groupBy('participants.id')
                 ->paginate($this->pagenation_number);
             
         
         
 
            //dd($data["results"]);
            //print_r($data["results"]);die;
            $data["per_page"]=$this->pagenation_number;
            $data["data"]= $data["results"];
            return view('all_vmebp_reports.employeespouse65medvmebp',["data"=>$data])->with('count', 1);;
        }else{
            return Redirect::to('dashboard')->with('error', "Access denied .");
        } 
        }catch(exception $e){
                
        }


    }
    public function employeespouse65medvmebpsearch(Request $request){
        try {
            $data = [];
            $data = getPermissionArray('all-reports');
            $user = Auth::user();
            $data["user"] = $user;
            $search_data =   DB::table('participants')
            ->leftJoin('participant_healthcare', 'participant_healthcare.participants_id', '=', 'participants.id')
            ->leftJoin('health_coverage_members', 'health_coverage_members.id', '=', 'participant_healthcare.health_coverage_member_id')
            ->leftJoin('relation_code', 'relation_code.id', '=', 'participants.relationcode_id')
            ->leftJoin('missionaries', 'missionaries.id', '=', 'participants.missionary_id')
            ->leftJoin('employee', 'employee.id', '=', 'participants.emp_id')
            ->leftJoin('spouses', 'spouses.id', '=', 'participants.spouse_id')
            ->select(
                'participants.id as participantid',
                'participant_healthcare.pss_no as pssno',
                'participants.fname as e_f_name',
                'participants.lname as e_l_name',
                'participants.vmcode as e_vmcode',
                'health_coverage_members.id as coverage_id',
                'health_coverage_members.member_name as coverage_name',
                'participant_healthcare.start_date',
                'participant_healthcare.end_date',
                'participant_healthcare.is_primary',
                'participants.id as participant_id',
                'relation_code.name as relation_name',
                'participant_healthcare.group_type_id',
                'missionaries.lname as m_l_name',
                'missionaries.fname as m_f_name',
                'employee.lname as emp_l_name',
                'employee.fname as emp_f_name',
                'spouses.spouse_fname',
                'spouses.spouse_lname',
                'spouses.spouse_dob',
                'spouses.spouse_phone',
                'participants.missionary_id',
                'participants.emp_id',
                'participants.spouse_id'
            )
            ->whereNotNull('participants.spouse_id')
            ->where('participants.status', 1)
            ->whereIn('health_coverage_members.id', [2, 1]);
           
            
           if (isset($request->search_batch) && $request->search_batch != '') {
            $search_string = $request->search_batch;
            $search_data = $search_data->where(function ($q) use ($search_string) {
                $q->where(
                    DB::raw("CONCAT(
                        COALESCE(spouses.spouse_fname, ''), ' ', COALESCE(spouses.spouse_lname, '')
                    )"),
                    'LIKE',
                    "%{$search_string}%"
                );
                $q->orWhere(
                    DB::raw("CONCAT(
                        COALESCE(participants.lname, ''), ' ', COALESCE(participants.fname, '')
                    )"),
                    'LIKE',
                    "%{$search_string}%"
                );
             
            });
        }
    //     if (!empty($request->search_year)) {
           
    //         $search_data = $search_data->selectRaw("timestampdiff(year, spouses.spouse_dob, '".$request->search_year."-01-01') as spouseA ");
    //    }
      
       
       if (!empty($request->search_year)) {
        $currentYear=$request->search_year;
        // $search_data =  $search_data->whereRaw("timestampdiff(year, spouses.spouse_dob, '".$request->search_year."-01-01') = 65");
        $search_data=$search_data->where(function ($query) use ($currentYear) {
            $query->where(function ($q) use ($currentYear) {
                $q->whereRaw("timestampdiff(year, spouses.spouse_dob, ?) = 64", [$currentYear . "-01-01"])
                  ->whereRaw("YEAR(spouses.spouse_dob) = ?", [$currentYear - 65]);
            })
            ->orWhere(function ($q) use ($currentYear) {
                $q->whereRaw("timestampdiff(year, employee.dob, ?) = 64", [$currentYear . "-01-01"])
                  ->whereRaw("YEAR(employee.dob) = ?", [$currentYear - 65]);
            });
        });
       }
       if(empty($request->search_year))
       {
        $currentYear=date('Y');
        $search_data=$search_data->where(function ($query) use ($currentYear) {
            $query->where(function ($q) use ($currentYear) {
                $q->whereRaw("timestampdiff(year, spouses.spouse_dob, ?) = 64", [$currentYear . "-01-01"])
                  ->whereRaw("YEAR(spouses.spouse_dob) = ?", [$currentYear - 65]);
            })
            ->orWhere(function ($q) use ($currentYear) {
                $q->whereRaw("timestampdiff(year, employee.dob, ?) = 64", [$currentYear . "-01-01"])
                  ->whereRaw("YEAR(employee.dob) = ?", [$currentYear - 65]);
            });
        });
       }
      
           
          
            
            
          else {
 
            }
           //dd($search_data);
            
         
           $search_data =  $search_data->groupBy('participants.id')->paginate($this->pagenation_number);
            $html = '';
            $offset = ($request->page - 1) * $this->pagenation_number;
            foreach ($search_data as $key => $res) {
                $html .= '<tr id="row-">';
                $html .= '<td  style="width:2%;;text-align:left;" >';
                $html .= $offset + $key + 1;
                $html .= '</td>';
                if (!empty($res->missionary_id) && empty($res->emp_id) && empty($res->children_id) && empty($res->spouse_id)){
                    $html .= '<td style="width:10%;text-align:left">';
                    $html .= $res->m_l_name . ($res->m_l_name && $res->m_f_name ? ',' : '') . ' ' . $res->m_f_name;
                    $html .= '</td>';
                }
                else if($res->spouse_id){
                    $html .= '<td style="width:10%;text-align:left">';
                    $html .= $res->spouse_lname . ($res->spouse_lname && $res->spouse_fname ? ',' : '') . ' ' . $res->spouse_fname;
                    $html .= '</td>';
                }
                else if($res->emp_id){
                    $html .= '<td style="width:10%;text-align:left">';
                    $html .= $res->emp_l_name . ($res->emp_l_name && $res->emp_f_name ? ',' : '') . ' ' . $res->emp_f_name;
                    $html .= '</td>';
                }
          
                $html .= '<td style="width:10%;text-align:left">';
                $html .= $res->spouse_lname . ($res->spouse_lname && $res->spouse_fname ? ',' : '') . ' ' . $res->spouse_fname;
                $html .= '</td>';
                $html .= '<td style="width:10%;text-align:left">';
                $html .= $res->e_vmcode??'';
                $html .= '</td>';
                $html .= '<td style="width:10%;text-align:left">';
                $html .= $res->coverage_name??'';
                $html .= '</td>';
                $html .= '<td style="width:10%;text-align:left">';
                $html .=  $res->start_date ? date("m-d-Y", strtotime($res->start_date)) : '' ;
                $html .= '</td>'; 
                $html .= '<td style="width:10%;text-align:left">';
                $html .=  $res->end_date ? date("m-d-Y", strtotime($res->end_date)) : '' ;
                $html .= '</td>'; 
                $html .= '</tr>';
            }
            if (trim($html) != '') {
                $pagenation = \View::make('include.pagenation', ['paginator' => $search_data])->render();
            } else {
                $pagenation = '';
                $html .= '<tr>';
                $html .= '<td  style="width:100%" class="text-nowrap" colspan="10">';
                $html .= 'No Data Found';
                $html .= '</td>';
            }
            if (!isset($request->page)) {
                $request->page = 1;
            }
            $page = (isset($request->page)) ? $request->page : 1;
            $offset = ($request->page - 1) * $this->pagenation_number;
            $start_page_number = $offset + 1;
            $end_page_number = $search_data->total();
            if ($end_page_number >= ($start_page_number + $this->pagenation_number)) {
                $end_page_number = $offset + $this->pagenation_number;
            }
            return response()->json([
                'message' => 'contributions',
                'html' => $html,
                'pagenation' => $pagenation,
                'start_page_number' => $start_page_number,
                'end_page_number' => $end_page_number,
                'total_records' => $search_data->total(),
                'status' => 1
            ], 200);

        } catch (exception $e) {

        }
    }
    public function employeespouse65medvmebpdownload (Request $request){
        // dd($request->all());
        $data=[];
        $data = getPermissionArray('all-reports');
        $user = Auth::user();
        $data["user"]=$user;
        $csv_data=[];
        $csv_data[]=array('Sl.No', 	'Primary Insurer', 	'Spouse Name', 	'VMCODE', 	'Coverage Name', 	'Coverage Start Date', 	'Coverage End Date');
     
        $search_data =    DB::table('participants')
        ->leftJoin('participant_healthcare', 'participant_healthcare.participants_id', '=', 'participants.id')
        ->leftJoin('health_coverage_members', 'health_coverage_members.id', '=', 'participant_healthcare.health_coverage_member_id')
        ->leftJoin('relation_code', 'relation_code.id', '=', 'participants.relationcode_id')
        ->leftJoin('missionaries', 'missionaries.id', '=', 'participants.missionary_id')
        ->leftJoin('employee', 'employee.id', '=', 'participants.emp_id')
        ->leftJoin('spouses', 'spouses.id', '=', 'participants.spouse_id')
        ->select(
            'participants.id as participantid',
            'participant_healthcare.pss_no as pssno',
            'participants.fname as e_f_name',
            'participants.lname as e_l_name',
            'participants.vmcode as e_vmcode',
            'health_coverage_members.id as coverage_id',
            'health_coverage_members.member_name as coverage_name',
            'participant_healthcare.start_date',
            'participant_healthcare.end_date',
            'participant_healthcare.is_primary',
            'participants.id as participant_id',
            'relation_code.name as relation_name',
            'participant_healthcare.group_type_id',
            'missionaries.lname as m_l_name',
            'missionaries.fname as m_f_name',
            'employee.lname as emp_l_name',
            'employee.fname as emp_f_name',
            'spouses.spouse_fname',
            'spouses.spouse_lname',
            'spouses.spouse_dob',
            'spouses.spouse_phone',
            'participants.missionary_id',
            'participants.emp_id',
            'participants.spouse_id'
        )
        ->whereNotNull('participants.spouse_id')
        ->where('participants.status', 1)
        ->whereIn('health_coverage_members.id', [2, 1]);
       
        
       if (isset($request->search_batch) && $request->search_batch != '') {
        $search_string = $request->search_batch;
        $search_data = $search_data->where(function ($q) use ($search_string) {
            $q->where(
                DB::raw("CONCAT(
                    COALESCE(spouses.spouse_fname, ''), ' ', COALESCE(spouses.spouse_lname, '')
                )"),
                'LIKE',
                "%{$search_string}%"
            );
            $q->orWhere(
                DB::raw("CONCAT(
                    COALESCE(participants.lname, ''), ' ', COALESCE(participants.fname, '')
                )"),
                'LIKE',
                "%{$search_string}%"
            );
         
        });
    }
//     if (!empty($request->search_filter)) {
       
//         $search_data = $search_data->selectRaw("timestampdiff(year, spouses.spouse_dob, '".$request->search_filter."-01-01') as spouseA ");
//    }
  
   
//    if (!empty($request->search_filter)) {
//     $search_data =  $search_data->whereRaw("timestampdiff(year, spouses.spouse_dob, '".$request->search_filter."-01-01') = 65");
//    }
//    if(empty($request->search_filter))
//    {
//     $search_data = $search_data->where(function ($query) {
//         $query->where(function ($q) {
//             $q->whereRaw('timestampdiff(year, spouses.spouse_dob, curdate()) = 64') // Currently 64 years old
            
//               ->whereRaw('YEAR(spouses.spouse_dob) = YEAR(CURDATE()) - 65'); // Will turn 65 this year
//         });
//     });
//    }
  
if (!empty($request->search_year)) {
    $currentYear=$request->search_year;
    // $search_data =  $search_data->whereRaw("timestampdiff(year, spouses.spouse_dob, '".$request->search_year."-01-01') = 65");
    $search_data=$search_data->where(function ($query) use ($currentYear) {
        $query->where(function ($q) use ($currentYear) {
            $q->whereRaw("timestampdiff(year, spouses.spouse_dob, ?) = 64", [$currentYear . "-01-01"])
              ->whereRaw("YEAR(spouses.spouse_dob) = ?", [$currentYear - 65]);
        })
        ->orWhere(function ($q) use ($currentYear) {
            $q->whereRaw("timestampdiff(year, employee.dob, ?) = 64", [$currentYear . "-01-01"])
              ->whereRaw("YEAR(employee.dob) = ?", [$currentYear - 65]);
        });
    });
   }
   if(empty($request->search_year))
   {
    $currentYear=date('Y');
    $search_data=$search_data->where(function ($query) use ($currentYear) {
        $query->where(function ($q) use ($currentYear) {
            $q->whereRaw("timestampdiff(year, spouses.spouse_dob, ?) = 64", [$currentYear . "-01-01"])
              ->whereRaw("YEAR(spouses.spouse_dob) = ?", [$currentYear - 65]);
        })
        ->orWhere(function ($q) use ($currentYear) {
            $q->whereRaw("timestampdiff(year, employee.dob, ?) = 64", [$currentYear . "-01-01"])
              ->whereRaw("YEAR(employee.dob) = ?", [$currentYear - 65]);
        });
    });
   }
      
        
        
      else {

        }
       //dd($search_data);
        
     
       $search_data =  $search_data->groupBy('participants.id')->get();


        foreach($search_data as $key=>$val){
          
            $tmp_arr=[];
            $tmp_arr[0]=$key + 1;
            if (!empty($val->missionary_id) && empty($val->emp_id) && empty($val->children_id) && empty($val->spouse_id)){
                $tmp_arr[1]=$val->m_l_name . ($val->m_l_name && $val->m_f_name ? ',' : '') . ' ' . $val->m_f_name;
            }
            else if(!empty($val->spouse_id)){
                $tmp_arr[1]=$val->spouse_lname . ($val->spouse_lname && $val->spouse_fname ? ',' : '') . ' ' . $val->spouse_fname;
            }
            else if(!empty($val->emp_id)){
                $tmp_arr[1]=$val->emp_l_name . ($val->emp_l_name && $val->emp_f_name ? ',' : '') . ' ' . $val->emp_f_name;
            }



            $tmp_arr[2]=$val->spouse_lname . ($val->spouse_lname && $val->spouse_fname ? ',' : '') . ' ' . $val->spouse_fname;

            $tmp_arr[3]=$val->e_vmcode??'';
            $tmp_arr[4]=$val->coverage_name??'';
           
            if (!empty($val->start_date) && strtotime($val->start_date)) {
                $tmp_arr[5] = date("M, Y", strtotime($val->start_date));
            } else {
                $tmp_arr[5] = ''; 
            }
            if (!empty($val->end_date) && strtotime($val->end_date)) {
              
                $tmp_arr[6] = date("M, Y", strtotime($val->end_date));
            } else {
                $tmp_arr[6] = ''; 
            }
            $csv_data[]= $tmp_arr;
        }
    
        csvDownlaod($csv_data,"employeespouse65medvmebp.csv");   

    }
    public function adultchild182426vmebp(Request $request){
        try{
            $currentYear=date('Y');
            $data=[];
            $data=getPermissionArray('vmebp_reports');//get permission for each module if its add in module table
            $user = Auth::user();
            $data["user"]=$user;
            if($data["permission_array"]["vmebp_reports"]["no_view"]==1){
            $data["menu"]="vmebp_reports";
            $query_string=[];
            $data["query_string"]='';
            $data["search_filter"]='';
            $data["search_batch"]='';
            $data["status_filter"]='';
            if(!isset($request->page)){
                $request->page=1;
            }
            $data["page"]=(isset($request->page))?$request->page:1;
            $data["offset"]=($request->page-1)*$this->pagenation_number;
            $data["results"] = DB::table('participants')
            ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
            ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
            ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
            ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
            ->leftJoin('employee','employee.id','=','participants.emp_id')
            ->leftJoin('childrens','childrens.id','=','participants.children_id')
            ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
            , 'participants.fname as e_f_name'
            , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
            ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
            ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','relation_code.name as relation_name','participant_healthcare.group_type_id',
            'missionaries.lname as m_l_name','missionaries.fname as m_f_name',
            'employee.lname as emp_l_name','employee.fname as emp_f_name','childrens.lname as child_l_name','childrens.dob as childdob','childrens.fname as child_f_name','participants.missionary_id','participants.emp_id','participants.spouse_id','participants.children_id')
            ->where('participants.status',1)
             ->where('participant_healthcare.health_coverage_member_id',2)
            ->whereNotNull('participants.children_id')
            ->where('childrens.is_deceased',0)
          //  ->where('childrens.is_deceased',0)
            // ->where(function ($query) {
            //     $query->whereRaw('timestampdiff(year, childrens.dob, curdate()) = 17')
            //          ->orWhereRaw('timestampdiff(year, childrens.dob, curdate()) = 23')
            //          ->orWhereRaw('timestampdiff(year, childrens.dob, curdate()) = 25');
            // })
            ->where(db::raw("timestampdiff(year,  childrens.dob ,'".$currentYear."-01-01') = 17 or timestampdiff(year,  childrens.dob ,'".$currentYear."-01-01') = 23 or timestampdiff(year,  childrens.dob ,'".$currentYear."-01-01')= 25"))
            ->groupBy('childrens.id')
            ->orderByRaw('ISNULL(childrens.dob), childrens.dob')
            ->paginate($this->pagenation_number);
    
           $data["per_page"]=$this->pagenation_number;
           $data["data"]= $data["results"];
            return view('all_vmebp_reports.adultchild182426vmebp',["data"=>$data])->with('count', 1);;
        }else{
            return Redirect::to('dashboard')->with('error', "Access denied .");
        } 
        }catch(exception $e){
                
        }


    }
    public function adultchild182426vmebpsearch(Request $request){
        //dump($request->all());
        try {
            $currentYear=date('Y');
            $data = [];
            $data = getPermissionArray('all-reports');
            $user = Auth::user();
            $data["user"] = $user;
            $search_data =  DB::table('participants')
            ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
            ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
            ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
            ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
            ->leftJoin('employee','employee.id','=','participants.emp_id')
            ->leftJoin('childrens','childrens.id','=','participants.children_id')
            ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
            , 'participants.fname as e_f_name'
            , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
            ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
            ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','relation_code.name as relation_name','participant_healthcare.group_type_id',
            'missionaries.lname as m_l_name','missionaries.fname as m_f_name',
            'employee.lname as emp_l_name','employee.fname as emp_f_name','childrens.lname as child_l_name','childrens.dob as childdob','childrens.fname as child_f_name','participants.missionary_id','participants.emp_id','participants.spouse_id','participants.children_id')
            ->where('participants.status',1)
             ->where('participant_healthcare.health_coverage_member_id',2)
            ->whereNotNull('participants.children_id');
          
            
            
           
            if (isset($request->search_batch) && $request->search_batch != '') {
                $search_string = $request->search_batch;
                $search_data = $search_data->where(function ($q) use ($search_string) {
                    $q->where(
                        DB::raw("CONCAT(
                            COALESCE(childrens.lname, ''), ' ', COALESCE(childrens.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
            
                });
            }
           
           
           if (!empty($request->search_year) && empty($request->search_age)) {
           // $search_data =  $search_data->whereRaw("timestampdiff(year, childrens.dob, '".$request->search_year."-01-01') = 17");
           $search_data = $search_data->where(function ($q) use ($request) {
            $q
            ->where(db::raw("timestampdiff(year,  childrens.dob ,'".$request->search_year."-01-01') = 17 or timestampdiff(year,  childrens.dob ,'".$request->search_year."-01-01') = 23 or timestampdiff(year,  childrens.dob ,'".$request->search_year."-01-01')= 25"));
          
        });
           }

           if(empty($request->search_year))
           {
            $search_data = $search_data->where(db::raw("timestampdiff(year,  childrens.dob ,'".$currentYear."-01-01') = 17 or timestampdiff(year,  childrens.dob ,'".$currentYear."-01-01') = 23 or timestampdiff(year,  childrens.dob ,'".$currentYear."-01-01')= 25"));
            // $search_data = $search_data->where(function ($q) use ($request,$currentYear) {
            //  //   $q->where(db::raw("timestampdiff(year, childrens.dob, curdate()) = 17 or timestampdiff(year, childrens.dob, curdate()) = 23 or timestampdiff(year, childrens.dob, curdate()) = 25"));
            //     $q  ->where(db::raw("timestampdiff(year,  childrens.dob ,'".$currentYear."-01-01') = 17 or timestampdiff(year,  childrens.dob ,'".$currentYear."-01-01') = 23 or timestampdiff(year,  childrens.dob ,'".$currentYear."-01-01')= 25"));
            
            //  });
    }


           
            
           
           if(!empty($request->search_age) && empty($request->search_year))
           {

            $search_data = $search_data->where(function ($q) use ($request) {
                $q->whereRaw("timestampdiff(year, childrens.dob, curdate()) = ".$request->search_age."");
                
            });


           }
           if(!empty($request->search_age) && !empty($request->search_year))
           {

            $search_data = $search_data->where(function ($q) use ($request) {
                $q->whereRaw("timestampdiff(year, childrens.dob, '".$request->search_year."-01-01') = ".$request->search_age."");
                
            });


           }
            
          else {
                
                 
            }
         
            
         
         // $search_data =  $search_data->groupBy('childrens.id')->toSql();
       // dd($search_data);
           $search_data =  $search_data
          // ->where('childrens.is_deceased',0)
           ->groupBy('childrens.id')->paginate($this->pagenation_number);
        
            $html = '';
            $offset = ($request->page - 1) * $this->pagenation_number;
            foreach ($search_data as $key => $res) {
                $html .= '<tr id="row-">';
                $html .= '<td  style="width:2%;;text-align:left;" >';
                $html .= $offset + $key + 1;
                $html .= '</td>';
                $html .= '<td style="width:10%;text-align:left">';
                $html .= $res->e_l_name . ($res->e_l_name && $res->e_f_name ? ',' : '') . ' ' . $res->e_f_name;
                $html .= '</td>';
                $html .= '<td style="width:10%;text-align:left">';
                $html .= $res->e_vmcode??'';
                $html .= '</td>';
                $html .= '<td style="width:10%;text-align:left">';
                $html .= $res->child_l_name . ($res->child_l_name && $res->child_f_name ? ',' : '') . ' ' . $res->child_f_name;
                $html .= '</td>';
                $html .= '<td style="width:10%;text-align:left">';
                $html .=  $res->childdob ? date("m-d-Y", strtotime($res->childdob)) : '' ;
                $html .= '</td>'; 
                $html .= '<td style="width:10%;text-align:left">';
                if(isset($res->childdob) && $res->childdob != '')
                $dateOfBirth = \Carbon\Carbon::parse($res->childdob);
                                                                                $now = \Carbon\Carbon::now();
                                                                                $diff = $dateOfBirth->diff($now);
                                                                                $age_years = $diff->y . ' Years ' . $diff->m . ' Months ' . $diff->d . ' Days';
                // $birth_date = $res->childdob;
                // $current_date = date('Y-m-d');
                // $birth_timestamp = strtotime($birth_date);
                // $current_timestamp = strtotime($current_date);
                // $diff_seconds = $current_timestamp - $birth_timestamp;
                // $age_years = $diff_seconds / (60 * 60 * 24 * 365.25);
                // $age_years = round($age_years);
                $html .=  $age_years ;
                $html .= '</td>';
             

                $html .= '<td style="width:10%;text-align:left">';
                $html .=  $res->coverage_name??'' ;
                $html .= '</td>'; 
                $html .= '</tr>';
            }
            if (trim($html) != '') {
                $pagenation = \View::make('include.pagenation', ['paginator' => $search_data])->render();
            } else {
                $pagenation = '';
                $html .= '<tr>';
                $html .= '<td  style="width:100%" class="text-nowrap" colspan="10">';
                $html .= 'No Data Found';
                $html .= '</td>';
            }
            if (!isset($request->page)) {
                $request->page = 1;
            }
            $page = (isset($request->page)) ? $request->page : 1;
            $offset = ($request->page - 1) * $this->pagenation_number;
            $start_page_number = $offset + 1;
            $end_page_number = $search_data->total();
            if ($end_page_number >= ($start_page_number + $this->pagenation_number)) {
                $end_page_number = $offset + $this->pagenation_number;
            }
            return response()->json([
                'message' => 'contributions',
                'html' => $html,
                'pagenation' => $pagenation,
                'start_page_number' => $start_page_number,
                'end_page_number' => $end_page_number,
                'total_records' => $search_data->total(),
                'status' => 1
            ], 200);

        } catch (exception $e) {

        }
    }
    public function adultchild182426vmebpdownload(Request $request){
          // dd($request->all());
          $data=[];
          $data = getPermissionArray('all-reports');
          $user = Auth::user();
          $data["user"]=$user;
          $csv_data=[];
          $csv_data[]=array('Sl.No', 	'Primary Insurer', 	'VMCODE', 	'Child',	'Child Date of Birth' 	,'Age', 	'Coverage');
       
          $search_data =    DB::table('participants')
          ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
          ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
          ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
          ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
          ->leftJoin('employee','employee.id','=','participants.emp_id')
          ->leftJoin('childrens','childrens.id','=','participants.children_id')
          ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
          , 'participants.fname as e_f_name'
          , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
          ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
          ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','relation_code.name as relation_name','participant_healthcare.group_type_id',
          'missionaries.lname as m_l_name','missionaries.fname as m_f_name',
          'employee.lname as emp_l_name','employee.fname as emp_f_name','childrens.lname as child_l_name','childrens.dob as childdob','childrens.fname as child_f_name','participants.missionary_id','participants.emp_id','participants.spouse_id','participants.children_id')
          ->where('participants.status',1)
           ->where('participant_healthcare.health_coverage_member_id',2)
          ->whereNotNull('participants.children_id');
          
          
         
         
          if (isset($request->search_batch) && $request->search_batch != '') {
            $search_string = $request->search_batch;
            $search_data = $search_data->where(function ($q) use ($search_string) {
                $q->where(
                    DB::raw("CONCAT(
                        COALESCE(childrens.lname, ''), ' ', COALESCE(childrens.fname, '')
                    )"),
                    'LIKE',
                    "%{$search_string}%"
                );
        
            });
        }
       
       
       if (!empty($request->search_filter) && empty($request->search_age)) {
       // $search_data =  $search_data->whereRaw("timestampdiff(year, childrens.dob, '".$request->search_year."-01-01') = 17");
       $search_data = $search_data->where(function ($q) use ($request) {
        $q->where(db::raw("timestampdiff(year,  childrens.dob ,'".$request->search_filter."-01-01') = 17 or timestampdiff(year,  childrens.dob ,'".$request->search_filter."-01-01') = 23 or timestampdiff(year,  childrens.dob ,'".$request->search_filter."-01-01')= 25"));
      
    });
       }

       if(empty($request->search_filter))
       {

        $search_data = $search_data->where(function ($q) use ($request) {
            $q->where(db::raw("timestampdiff(year, childrens.dob, curdate()) = 17 or timestampdiff(year, childrens.dob, curdate()) = 23 or timestampdiff(year, childrens.dob, curdate()) = 25"));
        
    });}


       
        
       
       if(!empty($request->search_age) && empty($request->search_filter))
       {

        $search_data = $search_data->where(function ($q) use ($request) {
            $q->whereRaw("timestampdiff(year, childrens.dob, curdate()) = ".$request->search_age."");
            
        });


       }
       if(!empty($request->search_age) && !empty($request->search_filter))
       {

        $search_data = $search_data->where(function ($q) use ($request) {
            $q->whereRaw("timestampdiff(year, childrens.dob, '".$request->search_filter."-01-01') = ".$request->search_age."");
            
        });


       }
        
      else {
            
             
        }
     
        
          //dd($search_data);
          $search_data = $search_data->groupBy('childrens.id')->get();
  
  
          foreach($search_data as $key=>$val){
              $tmp_arr=[];
              $tmp_arr[0]=$key + 1;
              $tmp_arr[1]=$val->m_l_name . ($val->m_l_name && $val->m_f_name ? ',' : '') . ' ' . $val->m_f_name;
              $tmp_arr[2]=$val->e_vmcode??'';
              $tmp_arr[3]=$val->child_l_name . ($val->child_l_name && $val->child_f_name ? ',' : '') . ' ' . $val->child_f_name;
              if (!empty($val->childdob) && strtotime($val->childdob)) {
                $tmp_arr[4] = date("M, Y", strtotime($val->childdob));
            } else {
                $tmp_arr[4] = ''; 
            }

            if (!empty($val->childdob) && strtotime($val->childdob)) {
                // $birth_date = $val->childdob;
                // $current_date = date('Y-m-d');
                // $birth_timestamp = strtotime($birth_date);
                // $current_timestamp = strtotime($current_date);
                // $diff_seconds = $current_timestamp - $birth_timestamp;
                // $age_years = $diff_seconds / (60 * 60 * 24 * 365.25);
                // $age_years = round($age_years);
                $dateOfBirth = \Carbon\Carbon::parse($val->childdob);
                $now = \Carbon\Carbon::now();
                $diff = $dateOfBirth->diff($now);
                $age_years = $diff->y . ' Years ' . $diff->m . ' Months ' . $diff->d . ' Days';
                $tmp_arr[5] = $age_years;
            } else {
                $tmp_arr[5] = ''; 
            }
            
              $tmp_arr[6]=$val->coverage_name??'';
             
           
             
              $csv_data[]= $tmp_arr;
          }
      
          csvDownlaod($csv_data,"adultchild182426vmebpdownload.csv"); 

    }
    public function vmebpgroupcoveragedetails(Request $request){
        try{
            $data=[];
            $data=getPermissionArray('vmebp_reports');//get permission for each module if its add in module table
            $user = Auth::user();
            $data["user"]=$user;
            if($data["permission_array"]["vmebp_reports"]["no_view"]==1){
            $data["menu"]="vmebp_reports";
            $query_string=[];
            $data["query_string"]='';
            $data["search_filter"]='';
            $data["search_batch"]='';
            $data["status_filter"]='';
            if(!isset($request->page)){
                $request->page=1;
            }
            $data["page"]=(isset($request->page))?$request->page:1;
            $data["offset"]=($request->page-1)*$this->pagenation_number;
            $search_year = date('Y');
       

        $data["results"] = DB::table('participants')
        ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
        ->leftJoin('participant_opt_outs','participant_opt_outs.participants_id','=','participants.id')
        ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
        ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
        ->leftJoin('group_types','group_types.id','=','participant_healthcare.group_type_id')
        ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
        ->leftJoin('employee','employee.id','=','participants.emp_id')
        ->leftJoin('childrens','childrens.id','=','participants.children_id')
        ->leftJoin('spouses','spouses.id','=','participants.spouse_id')

        ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
        , 'participants.fname as e_f_name'
        , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
        ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
        ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','participant_opt_outs.opt_out_date','relation_code.name as relation_name','participant_healthcare.group_type_id',
        'participant_opt_outs.term_end_date','participant_opt_outs.opt_out_reason' ,'group_types.group_name',
        'missionaries.lname as m_l_name','missionaries.fname as m_f_name',
        'employee.lname as emp_l_name','employee.fname as emp_f_name','childrens.lname as child_l_name','childrens.fname as child_f_name','spouses.spouse_fname','spouses.spouse_lname','spouses.spouse_dob'
        ,'spouses.spouse_phone','participants.missionary_id','participants.emp_id','participants.spouse_id','participants.children_id')
       ->where('health_coverage_members.id','=',2)
    //   ->whereNotNull('participant_healthcare.status')
   
      ->where(function ($query) use ($search_year) {
        $query->whereRaw("YEAR(participant_healthcare.start_date) <= ?", [$search_year])
              ->where(function ($q) use ($search_year) {
                  $q->whereNull('participant_healthcare.end_date')
                    ->orWhereRaw("YEAR(participant_healthcare.end_date) >= ?", [$search_year]);
              });
    })
    // ->orderByRaw("participants.vmcode,
    //     CASE 
    //         WHEN relation_code.name = 'Self' THEN 1
    //         WHEN relation_code.name = 'Spouse' THEN 2
    //         WHEN relation_code.name = 'Children' THEN 3
    //         ELSE 4 
    //     END
    // ")
        ->orderByRaw("COALESCE(NULLIF(participants.lname, ''), participants.fname) ASC")
        ->groupBy('participants.id')
        ->paginate($this->pagenation_number);
           // dd($data["results"]);
           ///print_r($data["results"]);die;
           $data["per_page"]=$this->pagenation_number;
           $data["data"]= $data["results"];
            return view('all_vmebp_reports.vmebpgroupcoveragedetails',["data"=>$data])->with('count', 1);;
        }else{
            return Redirect::to('dashboard')->with('error', "Access denied .");
        } 
        }catch(exception $e){
                
        }


    }
    public function vmebpgroupcoveragedetailssearch(Request $request){
      
            try {
                $data = [];
                $data = getPermissionArray('vmebp_reports');
                $user = Auth::user();
                $data["user"] = $user;
                $search_year = date('Y');
            //     $search_data =  Employee::leftjoin('childrens', 'childrens.emp_id', '=', 'employee.id')
            //     ->leftJoin('spouses', 'spouses.emp_id', '=', 'employee.id')
            //     ->leftJoin('participants','participants.vmcode','=','employee.vmcode')
            //     ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
            //     ->leftJoin('participant_opt_outs','participant_opt_outs.participants_id','=','participants.id')
            //     ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
            //     ->leftjoin('users', 'users.id', '=', 'childrens.updated_by')
            //     ->leftjoin('users as uc', 'uc.id', '=', 'childrens.created_by')
            //     ->leftjoin('users as un', 'un.id', '=', 'childrens.user_id')
            //     ->orderByRaw('isNULL(childrens.dob) asc, datediff(childrens.dob,now()) asc')
            //     //->orderByRaw('childrens.dob asc')
            //     ->select('users.name as username', 'uc.name as created_username', 'childrens.*','spouses.id as spouse_id','participants.id as participantid','participant_healthcare.pss_no as pssno'
            //     ,'spouses.spouse_fname','spouses.spouse_lname', 'employee.fname as e_f_name'
            //     , 'employee.lname as e_l_name', 'employee.vmcode as e_vmcode','employee.joining_date','employee.termination_date'
            //     ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
            //     ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','participant_opt_outs.opt_out_date',
            //     'participant_opt_outs.term_end_date','participant_opt_outs.opt_out_reason')
            //    ->where('health_coverage_members.id','=',2)
            //    ->whereNotNull('participant_opt_outs.opt_out_date')
            //    ->whereNotNull('employee.termination_date');
            $search_data = DB::table('participants')
            ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
            ->leftJoin('participant_opt_outs','participant_opt_outs.participants_id','=','participants.id')
            ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
            ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
            ->leftJoin('group_types','group_types.id','=','participant_healthcare.group_type_id')
            ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
            ->leftJoin('employee','employee.id','=','participants.emp_id')
            ->leftJoin('childrens','childrens.id','=','participants.children_id')
            ->leftJoin('spouses','spouses.id','=','participants.spouse_id')
    
            ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
            , 'participants.fname as e_f_name'
            , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
            ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
            ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','participant_opt_outs.opt_out_date','relation_code.name as relation_name','participant_healthcare.group_type_id',
            'participant_opt_outs.term_end_date','participant_opt_outs.opt_out_reason' ,'group_types.group_name',
            'missionaries.lname as m_l_name','missionaries.fname as m_f_name',
            'employee.lname as emp_l_name','employee.fname as emp_f_name','childrens.lname as child_l_name','childrens.fname as child_f_name','spouses.spouse_fname','spouses.spouse_lname','spouses.spouse_dob'
            ,'spouses.spouse_phone','participants.missionary_id','participants.emp_id','participants.spouse_id','participants.children_id')
           ->where('health_coverage_members.id','=',2);
         //  ->whereNotNull('participant_healthcare.status');
        //   ->whereNotNull('participant_opt_outs.opt_out_date')
           //->whereNotNull('participants.termination_date')
          // ->whereNotNull('participant_opt_outs.termination_date')
 
    
    
    
                if (isset($request->search_batch) && $request->search_batch != '') {
                    $search_string = $request->search_batch;
                    $search_data = $search_data->where(function ($q) use ($search_string) {
                        $q->where(
                            DB::raw("CONCAT(
                                COALESCE(participants.lname, ''), ' ', COALESCE(participants.fname, '')
                            )"),
                            'LIKE',
                            "%{$search_string}%"
                        );
                        $q->orWhere(
                            DB::raw("CONCAT(
                                COALESCE(spouses.spouse_fname, ''), ' ', COALESCE(spouses.spouse_lname, '')
                            )"),
                            'LIKE',
                            "%{$search_string}%"
                        );
                        $q->orWhere(
                            DB::raw("CONCAT(
                                COALESCE(childrens.lname, ''), ' ', COALESCE(childrens.fname, '')
                            )"),
                            'LIKE',
                            "%{$search_string}%"
                        );
                        $q->orWhere(
                            DB::raw("CONCAT(
                                COALESCE(missionaries.lname, ''), ' ', COALESCE(missionaries.fname, '')
                            )"),
                            'LIKE',
                            "%{$search_string}%"
                        );
                        $q->orWhere(
                            DB::raw("CONCAT(
                                COALESCE(employee.lname, ''), ' ', COALESCE(employee.fname, '')
                            )"),
                            'LIKE',
                            "%{$search_string}%"
                        );
                    });
                }
    
                // if (!empty($request->search_year)) {
                //     $search_data = $search_data->whereRaw("YEAR(participant_healthcare.start_date) = ?", [$request->search_year]);
                                     
                // }
                
                if (!empty($request->search_year)) {
                    $search_year = $request->search_year;
                
                    $search_data = $search_data->where(function ($query) use ($search_year) {
                        $query->whereRaw("YEAR(participant_healthcare.start_date) <= ?", [$search_year]) 
                              ->where(function ($q) use ($search_year) {
                                  $q->whereNull('participant_healthcare.end_date') 
                                    ->orWhereRaw("YEAR(participant_healthcare.end_date) >= ?", [$search_year]); 
                              });
                    });
                }
                else{
                    $search_data = $search_data->where(function ($query) use ($search_year) {
                        $query->whereRaw("YEAR(participant_healthcare.start_date) <= ?", [$search_year]) 
                              ->where(function ($q) use ($search_year) {
                                  $q->whereNull('participant_healthcare.end_date') 
                                    ->orWhereRaw("YEAR(participant_healthcare.end_date) >= ?", [$search_year]); 
                              });
                    });
                }
              
                
                // if (!empty($request->search_filter)) {
                //     // Convert date_from (MM-DD-YYYY or MM-YYYY) to Y-m-d format
                //    // $searchyear = \Carbon\Carbon::createFromFormat('Y', $request->date_from)->startOfMonth()->format('Y-m-d');
                // $searchyear = \Carbon\Carbon::createFromFormat('Y-m-d', $request->search_filter)->startOfMonth()->format('Y-m-d');
                //     // $search_data = $search_data->where('mf.form_date', '>=', $date_from);
                //     $search_data = $search_data->where(function($q) use ($searchyear) {
                //         $q->where(function($q1) use ($searchyear){
                //             $q1->whereRaw('timestampdiff(year, childrens.dob, curdate()) = 17') ;// Currently 17 years old
                //             $q1->whereIn(DB::raw('MONTH(childrens.dob)'), [ 11]) ;
                //             $q1->whereRaw('YEAR(childrens.dob) = YEAR(CURDATE()) - 18') ;
                           
                //         });
                       
                //     });
                // }
                
            //   else {
            //         $search_data = $search_data
            //         ->whereRaw('timestampdiff(year, childrens.dob, curdate()) = 17') // Currently 17 years old
            //         ->whereIn(DB::raw('MONTH(childrens.dob)'), [ 11]) 
            //         ->whereRaw('YEAR(childrens.dob) = YEAR(CURDATE()) - 18'); // Will turn 18 this year
                     
            //     }
             
                
             
               $search_data =  $search_data
               ->groupBy('participants.id')
               ->orderByRaw("COALESCE(NULLIF(participants.lname, ''), participants.fname) ASC")
        //        ->orderByRaw("participants.vmcode,
        //        CASE 
        //            WHEN relation_code.name = 'Self' THEN 1
        //            WHEN relation_code.name = 'Spouse' THEN 2
        //            WHEN relation_code.name = 'Children' THEN 3
        //            ELSE 4 
        //        END
        //    ")
               ->paginate($this->pagenation_number);
             //  dd($search_data);
                $html = '';
                $offset = ($request->page - 1) * $this->pagenation_number;
            
                foreach ($search_data as $key => $res) {
                 
                    if (!empty($res->missionary_id) && empty($res->emp_id) && empty($res->children_id) && empty($res->spouse_id)) {
           
                        $insurerName=$res->m_l_name . ($res->m_l_name && $res->m_f_name ? ',' : '') . ' ' . $res->m_f_name;
                 } elseif (!empty($res->children_id)) {
                       $insurerName=$res->child_l_name . ($res->child_l_name && $res->child_f_name ? ',' : '') . ' ' . $res->child_f_name;
                 } elseif (!empty($res->spouse_id)) {
                       $insurerName=$res->spouse_lname . ($res->spouse_lname && $res->spouse_fname ? ',' : '') . ' ' . $res->spouse_fname;
                 } elseif (!empty($res->emp_id)) {
                  $insurerName=$res->emp_l_name . ($res->emp_l_name && $res->emp_f_name ? ',' : '') . ' ' . $res->emp_f_name;
                 } else {
                   $insurerName="";
                 }
                 if ($res->group_name === 'G') {
                    $groupLabel = "G (Single)";
                } elseif ($res->group_name === 'GG') {
                    $groupLabel = "GG (Couple)";
                } elseif ($res->group_name === 'GGG') {
                    $groupLabel = "GGG (Family)";
                } else {
                    $groupLabel = isset($res->group_name) ? $res->group_name : '';
                }
                
                    if(isset($res->spouse_dob) && $res->spouse_dob != ''){
                   
                        $dateOfBirth = \Carbon\Carbon::parse($res->spouse_dob);
                        $age = $dateOfBirth->diff(\Carbon\Carbon::now())->format('%y Years');
                    }
                   
                else{
                    $age ="-";
                }

                
                    $html .= '<tr id="row-' . ($offset + $key + 1) . '">';
                    
                    // Serial Number
                    $html .= '<td style="width:2%; text-align:left;">' .  ($offset + $key + 1). '</td>';
                    $html .= '<td style="width:10%; text-align:left;">' . $insurerName . '</td>';
                
                    // Participant ID
                    $html .= '<td style="width:2%;">' . $groupLabel. '</td>';
                    if (!empty($res->missionary_id) && empty($res->emp_id) && empty($res->children_id) && empty($res->spouse_id)){
                        $html .= '<td style="width:10%; text-align:left;">' . 'Missionary' . '</td>';
                    }
                    elseif (!empty($res->children_id)){
                        $html .= '<td style="width:10%; text-align:left;">' . 'Child' . '</td>';
                    }
                    elseif (!empty($res->spouse_id)){
                        $html .= '<td style="width:10%; text-align:left;">' . 'Spouse' . '</td>';
                    }
                    elseif(!empty($res->emp_id)){
                        $html .= '<td style="width:10%; text-align:left;">' . 'Employee' . '</td>';
                
                    }
                
    
                    $html .= '<td style="width:10%; text-align:left;">' . ($res->e_vmcode ?? '') . '</td>';
                
                    if (!empty($res->start_date)) {
                        $html .= '<td style="width:10%;text-align:left">' . date('m-d-Y', strtotime($res->start_date)) . '</td>';
                    } else {
                        $html .= '<td style="width:10%;text-align:left"></td>';
                    }
                    
                    if (!empty($res->end_date)) {
                        $html .= '<td style="width:10%;text-align:left">' . date('m-d-Y', strtotime($res->end_date)) . '</td>';
                    } else {
                        $html .= '<td style="width:10%;text-align:left"></td>';
                    }
                    
    
                
                    $html .= '</tr>';
                }
                if (trim($html) != '') {
                    $pagenation = \View::make('include.pagenation', ['paginator' => $search_data])->render();
                } else {
                    $pagenation = '';
                    $html .= '<tr>';
                    $html .= '<td  style="width:100%" class="text-nowrap" colspan="10">';
                    $html .= 'No Data Found';
                    $html .= '</td>';
                }
                if (!isset($request->page)) {
                    $request->page = 1;
                }
                $page = (isset($request->page)) ? $request->page : 1;
                $offset = ($request->page - 1) * $this->pagenation_number;
                $start_page_number = $offset + 1;
                $end_page_number = $search_data->total();
                if ($end_page_number >= ($start_page_number + $this->pagenation_number)) {
                    $end_page_number = $offset + $this->pagenation_number;
                }
                return response()->json([
                    'message' => 'contributions',
                    'html' => $html,
                    'pagenation' => $pagenation,
                    'start_page_number' => $start_page_number,
                    'end_page_number' => $end_page_number,
                    'total_records' => $search_data->total(),
                    'status' => 1
                ], 200);
    
            } catch (exception $e) {
    
            }
       
    }
    public function vmebpgroupcoveragedetailsdownload(Request $request){
    
            $data = [];
            $data = getPermissionArray('vmebp_reports');
            $user = Auth::user();
        $data["user"]=$user;
        $csv_data=[];
        $csv_data[]=array('Sl.No', 	'Name', 	'Enrollment Level', 	'Relation', 	'VMCODE', 'Coverage Start Date','Coverage End Date');
        $search_year=date('Y');
        //     $search_data =  Employee::leftjoin('childrens', 'childrens.emp_id', '=', 'employee.id')
        //     ->leftJoin('spouses', 'spouses.emp_id', '=', 'employee.id')
        //     ->leftJoin('participants','participants.vmcode','=','employee.vmcode')
        //     ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
        //     ->leftJoin('participant_opt_outs','participant_opt_outs.participants_id','=','participants.id')
        //     ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
        //     ->leftjoin('users', 'users.id', '=', 'childrens.updated_by')
        //     ->leftjoin('users as uc', 'uc.id', '=', 'childrens.created_by')
        //     ->leftjoin('users as un', 'un.id', '=', 'childrens.user_id')
        //     ->orderByRaw('isNULL(childrens.dob) asc, datediff(childrens.dob,now()) asc')
        //     //->orderByRaw('childrens.dob asc')
        //     ->select('users.name as username', 'uc.name as created_username', 'childrens.*','spouses.id as spouse_id','participants.id as participantid','participant_healthcare.pss_no as pssno'
        //     ,'spouses.spouse_fname','spouses.spouse_lname', 'employee.fname as e_f_name'
        //     , 'employee.lname as e_l_name', 'employee.vmcode as e_vmcode','employee.joining_date','employee.termination_date'
        //     ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
        //     ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','participant_opt_outs.opt_out_date',
        //     'participant_opt_outs.term_end_date','participant_opt_outs.opt_out_reason')
        //    ->where('health_coverage_members.id','=',2)
        //    ->whereNotNull('participant_opt_outs.opt_out_date')
        //    ->whereNotNull('employee.termination_date');
        $search_data = DB::table('participants')
        ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
        ->leftJoin('participant_opt_outs','participant_opt_outs.participants_id','=','participants.id')
        ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
        ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
        ->leftJoin('group_types','group_types.id','=','participant_healthcare.group_type_id')
        ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
        ->leftJoin('employee','employee.id','=','participants.emp_id')
        ->leftJoin('childrens','childrens.id','=','participants.children_id')
        ->leftJoin('spouses','spouses.id','=','participants.spouse_id')

        ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
        , 'participants.fname as e_f_name'
        , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
        ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
        ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','participant_opt_outs.opt_out_date','relation_code.name as relation_name','participant_healthcare.group_type_id',
        'participant_opt_outs.term_end_date','participant_opt_outs.opt_out_reason' ,'group_types.group_name',
        'missionaries.lname as m_l_name','missionaries.fname as m_f_name',
        'employee.lname as emp_l_name','employee.fname as emp_f_name','childrens.lname as child_l_name','childrens.fname as child_f_name','spouses.spouse_fname','spouses.spouse_lname','spouses.spouse_dob'
        ,'spouses.spouse_phone','participants.missionary_id','participants.emp_id','participants.spouse_id','participants.children_id')
       ->where('health_coverage_members.id','=',2);
      // ->whereNotNull('participant_healthcare.status');
    //   ->whereNotNull('participant_opt_outs.opt_out_date')
       //->whereNotNull('participants.termination_date')
      // ->whereNotNull('participant_opt_outs.termination_date')




            if (isset($request->search_batch) && $request->search_batch != '') {
                $search_string = $request->search_batch;
                $search_data = $search_data->where(function ($q) use ($search_string) {
                    $q->where(
                        DB::raw("CONCAT(
                            COALESCE(participants.lname, ''), ' ', COALESCE(participants.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(spouses.spouse_fname, ''), ' ', COALESCE(spouses.spouse_lname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(childrens.lname, ''), ' ', COALESCE(childrens.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(missionaries.lname, ''), ' ', COALESCE(missionaries.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(employee.lname, ''), ' ', COALESCE(employee.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                });
            }

            // if (!empty($request->search_year)) {
            //     $search_data = $search_data->whereRaw("YEAR(participant_healthcare.start_date) = ?", [$request->search_year]);
                                 
            // }
            
            if (!empty($request->search_year)) {
                $search_year = $request->search_year;
            
                $search_data = $search_data->where(function ($query) use ($search_year) {
                    $query->whereRaw("YEAR(participant_healthcare.start_date) <= ?", [$search_year]) 
                          ->where(function ($q) use ($search_year) {
                              $q->whereNull('participant_healthcare.end_date') 
                                ->orWhereRaw("YEAR(participant_healthcare.end_date) >= ?", [$search_year]); 
                          });
                });
            }
            else{
                $search_data = $search_data->where(function ($query) use ($search_year) {
                    $query->whereRaw("YEAR(participant_healthcare.start_date) <= ?", [$search_year]) 
                          ->where(function ($q) use ($search_year) {
                              $q->whereNull('participant_healthcare.end_date') 
                                ->orWhereRaw("YEAR(participant_healthcare.end_date) >= ?", [$search_year]); 
                          });
                });
            }
            
            // if (!empty($request->search_filter)) {
            //     // Convert date_from (MM-DD-YYYY or MM-YYYY) to Y-m-d format
            //    // $searchyear = \Carbon\Carbon::createFromFormat('Y', $request->date_from)->startOfMonth()->format('Y-m-d');
            // $searchyear = \Carbon\Carbon::createFromFormat('Y-m-d', $request->search_filter)->startOfMonth()->format('Y-m-d');
            //     // $search_data = $search_data->where('mf.form_date', '>=', $date_from);
            //     $search_data = $search_data->where(function($q) use ($searchyear) {
            //         $q->where(function($q1) use ($searchyear){
            //             $q1->whereRaw('timestampdiff(year, childrens.dob, curdate()) = 17') ;// Currently 17 years old
            //             $q1->whereIn(DB::raw('MONTH(childrens.dob)'), [ 11]) ;
            //             $q1->whereRaw('YEAR(childrens.dob) = YEAR(CURDATE()) - 18') ;
                       
            //         });
                   
            //     });
            // }
            
        //   else {
        //         $search_data = $search_data
        //         ->whereRaw('timestampdiff(year, childrens.dob, curdate()) = 17') // Currently 17 years old
        //         ->whereIn(DB::raw('MONTH(childrens.dob)'), [ 11]) 
        //         ->whereRaw('YEAR(childrens.dob) = YEAR(CURDATE()) - 18'); // Will turn 18 this year
                 
        //     }
         
            
         
           $search_data =  $search_data
           ->groupBy('participants.id')
           ->orderByRaw("COALESCE(NULLIF(participants.lname, ''), participants.fname) ASC")
    //        ->orderByRaw("participants.vmcode,
    //        CASE 
    //            WHEN relation_code.name = 'Self' THEN 1
    //            WHEN relation_code.name = 'Spouse' THEN 2
    //            WHEN relation_code.name = 'Children' THEN 3
    //            ELSE 4 
    //        END
    //    ")
           ->get();
         //  dd($search_data);
            $html = '';
            $offset = ($request->page - 1) * $this->pagenation_number;
        
            foreach ($search_data as $key => $res) {
                // Calculate age if spouse_dob is available
                if (!empty($res->spouse_dob)) {
                    $dateOfBirth = \Carbon\Carbon::parse($res->spouse_dob);
                    $age = $dateOfBirth->diff(\Carbon\Carbon::now())->format('%y Years');
                } else {
                    $age = "-";
                }
            
                $tmp_arr = [];
            
                // Serial Number
                $tmp_arr[0] = $key + 1;
            
                // Determine Insurer Name
                if (!empty($res->missionary_id) && empty($res->emp_id) && empty($res->children_id) && empty($res->spouse_id)) {
                    $insurerName = trim($res->m_l_name . ($res->m_l_name && $res->m_f_name ? ',' : '') . ' ' . $res->m_f_name);
                } elseif (!empty($res->children_id)) {
                    $insurerName = trim($res->child_l_name . ($res->child_l_name && $res->child_f_name ? ',' : '') . ' ' . $res->child_f_name);
                } elseif (!empty($res->spouse_id)) {
                    $insurerName = trim($res->spouse_lname . ($res->spouse_lname && $res->spouse_fname ? ',' : '') . ' ' . $res->spouse_fname);
                } elseif (!empty($res->emp_id)) {
                    $insurerName = trim($res->emp_l_name . ($res->emp_l_name && $res->emp_f_name ? ',' : '') . ' ' . $res->emp_f_name);
                } else {
                    $insurerName = "";
                }
                if($res->group_name === 'G'){
                $groupLabel="G (Single)";
                }
                elseif($res->group_name === 'GG'){
                 $groupLabel="GG (Couple)"; 
                }
                elseif($res->group_name === 'GGG'){
                    $groupLabel="GGG (Family)"; 
                   }
                   else{
                    $groupLabel=$res->group_name ?? '';
                   }
                                                
                               
            
                $tmp_arr[1] = $insurerName; // Participant Name
                $tmp_arr[2] = $groupLabel ?? ''; // PSS#
                if (!empty($res->missionary_id) && empty($res->emp_id) && empty($res->children_id) && empty($res->spouse_id)){
                    $tmp_arr[3] = 'Missionary' ;
                }
                elseif (!empty($res->children_id)){
                   $tmp_arr[3] = 'Child';
                }
                elseif (!empty($res->spouse_id)){
                    $tmp_arr[3] = 'Spouse';
                }
                elseif(!empty($res->emp_id)){
                    $tmp_arr[3] = 'Employee';
            
                }
               $tmp_arr[4] = $res->e_vmcode??'';
            
                // Coverage Start Date
                $tmp_arr[5] = !empty($res->start_date) && strtotime($res->start_date) ? date("m-d-Y", strtotime($res->start_date)) : '';
                $tmp_arr[6] = !empty($res->end_date) && strtotime($res->end_date) ? date("m-d-Y", strtotime($res->end_date)) : '';
            
      
            
                // Add row to CSV data array
                $csv_data[] = $tmp_arr;
            }
            
            csvDownlaod($csv_data,"optoutvmebpgroupcoverage.csv");  
          
    }
    public function employeespouse65novembervmebpalternative(Request $request){
        try{
            $searchYear=date('Y');
            $searchDate = "$searchYear-01-01";
            $data=[];
            $data=getPermissionArray('vmebp_reports');//get permission for each module if its add in module table
            $user = Auth::user();
            $data["user"]=$user;
            if($data["permission_array"]["vmebp_reports"]["no_view"]==1){
            $data["menu"]="vmebp_reports";
            $query_string=[];
            $data["query_string"]='';
            $data["search_filter"]='';
            $data["search_batch"]='';
            $data["status_filter"]='';
            if(!isset($request->page)){
                $request->page=1;
            }
            $data["page"]=(isset($request->page))?$request->page:1;
            $data["offset"]=($request->page-1)*$this->pagenation_number;
            $data["results"] = DB::table('participants')
            ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
            ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
            ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
            ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
            ->leftJoin('employee','employee.id','=','participants.emp_id')
          //  ->leftJoin('childrens','childrens.id','=','participants.children_id')
            ->leftJoin('spouses','spouses.id','=','participants.spouse_id')
            ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
            , 'participants.fname as e_f_name'
            , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
            ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
            ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','relation_code.name as relation_name','participant_healthcare.group_type_id',
            'missionaries.lname as m_l_name','missionaries.fname as m_f_name',
            'employee.lname as emp_l_name','employee.fname as emp_f_name','spouses.spouse_fname','spouses.spouse_lname','spouses.spouse_dob'
            ,'spouses.spouse_phone','participants.missionary_id','participants.emp_id','participants.spouse_id')
         
         ->whereNotNull('participants.spouse_id')
            ->whereIn('health_coverage_members.id', [2,7])
            // ->where(function ($query) {
            //     $query->where(function ($q) {
            //         $q->whereRaw('timestampdiff(year, spouses.spouse_dob, curdate()) = 64') // Currently 64 years old
                     
            //           ->whereRaw('YEAR(spouses.spouse_dob) = YEAR(CURDATE()) - 65'); // Will turn 65 this year
            //     })->orWhere(function ($q) {
            //         $q->whereRaw('timestampdiff(year, employee.dob, curdate()) = 64') // Employee is currently 64
                     
            //           ->whereRaw('YEAR(employee.dob) = YEAR(CURDATE()) - 65'); // Will turn 65 this year
            //     });
            // })
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereRaw("timestampdiff(year, spouses.spouse_dob, DATE_FORMAT(CURDATE(), '%Y-01-01')) = 64")
                      ->whereRaw("YEAR(spouses.spouse_dob) = YEAR(DATE_FORMAT(CURDATE(), '%Y-01-01')) - 65");
                })->orWhere(function ($q) {
                    $q->whereRaw("timestampdiff(year, employee.dob, DATE_FORMAT(CURDATE(), '%Y-01-01')) = 64")
                      ->whereRaw("YEAR(employee.dob) = YEAR(DATE_FORMAT(CURDATE(), '%Y-01-01')) - 65");
                });
            })
            ->groupBy('participants.id')
            ->paginate($this->pagenation_number);
        

        //    dd($data["results"]);
           ///print_r($data["results"]);die;
           $data["per_page"]=$this->pagenation_number;
           $data["data"]= $data["results"];
            return view('all_vmebp_reports.employeespouse65novembervmebpalternative',["data"=>$data])->with('count', 1);;
        }else{
            return Redirect::to('dashboard')->with('error', "Access denied .");
        } 
        }catch(exception $e){
                
        }
    }
    public function employeespouse65novembervmebpalternativesearch(Request $request){
        try {
            $data = [];
            $data = getPermissionArray('all-reports');
            $user = Auth::user();
            $data["user"] = $user;
            $search_data = DB::table('participants')
            ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
            ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
            ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
            ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
            ->leftJoin('employee','employee.id','=','participants.emp_id')
          //  ->leftJoin('childrens','childrens.id','=','participants.children_id')
            ->leftJoin('spouses','spouses.id','=','participants.spouse_id')
            ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
            , 'participants.fname as e_f_name'
            , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
            ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
            ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','relation_code.name as relation_name','participant_healthcare.group_type_id',
            'missionaries.lname as m_l_name','missionaries.fname as m_f_name',
            'employee.lname as emp_l_name','employee.fname as emp_f_name','spouses.spouse_fname','spouses.spouse_lname','spouses.spouse_dob'
            ,'spouses.spouse_phone','participants.missionary_id','participants.emp_id','participants.spouse_id')
            ->whereNotNull('participants.spouse_id')
            ->whereIn('health_coverage_members.id', [2,7]);
           
            
           if (isset($request->search_batch) && $request->search_batch != '') {
            $search_string = $request->search_batch;
            $search_data = $search_data->where(function ($q) use ($search_string) {
                $q->where(
                    DB::raw("CONCAT(
                        COALESCE(spouses.spouse_fname, ''), ' ', COALESCE(spouses.spouse_lname, '')
                    )"),
                    'LIKE',
                    "%{$search_string}%"
                );
                $q->orWhere(
                    DB::raw("CONCAT(
                        COALESCE(participants.lname, ''), ' ', COALESCE(participants.fname, '')
                    )"),
                    'LIKE',
                    "%{$search_string}%"
                );
             
            });
        }
    //     if (!empty($request->search_year)) {
           
    //         $search_data = $search_data->selectRaw("timestampdiff(year, spouses.spouse_dob, '".$request->search_year."-01-01') as spouseA ");
    //    }
      
       
    //    if (!empty($request->search_year)) {
    //     $search_data =  $search_data->whereRaw("timestampdiff(year, spouses.spouse_dob, '".$request->search_year."-01-01') = 65");
    //    }
    //    if(empty($request->search_year))
    //    {
    //     $search_data = $search_data->where(function ($query) {
    //         $query->where(function ($q) {
    //             $q->whereRaw('timestampdiff(year, spouses.spouse_dob, curdate()) = 64') // Currently 64 years old
                
    //               ->whereRaw('YEAR(spouses.spouse_dob) = YEAR(CURDATE()) - 65'); // Will turn 65 this year
    //         });
    //     });
    //    }
      
           
          
            
            
    //       else {
 
    //         }
           //dd($search_data);
           if (!empty($request->search_year)) {
            $searchYear = $request->search_year;
            $searchDate = "$searchYear-01-01";
        
            $search_data->where(function ($query) use ($searchDate) {
                $query->where(function ($q) use ($searchDate) {
                    $q->whereRaw('timestampdiff(year, spouses.spouse_dob, ?) = 64', [$searchDate])
                      ->whereRaw('YEAR(spouses.spouse_dob) = YEAR(?) - 65', [$searchDate]);
                })->orWhere(function ($q) use ($searchDate) {
                    $q->whereRaw('timestampdiff(year, employee.dob, ?) = 64', [$searchDate])
                      ->whereRaw('YEAR(employee.dob) = YEAR(?) - 65', [$searchDate]);
                });
            });
        
        } else {
            $search_data->where(function ($query) {
                $query->where(function ($q) {
                    $q->whereRaw("timestampdiff(year, spouses.spouse_dob, DATE_FORMAT(CURDATE(), '%Y-01-01')) = 64")
                      ->whereRaw("YEAR(spouses.spouse_dob) = YEAR(DATE_FORMAT(CURDATE(), '%Y-01-01')) - 65");
                })->orWhere(function ($q) {
                    $q->whereRaw("timestampdiff(year, employee.dob, DATE_FORMAT(CURDATE(), '%Y-01-01')) = 64")
                      ->whereRaw("YEAR(employee.dob) = YEAR(DATE_FORMAT(CURDATE(), '%Y-01-01')) - 65");
                });
            });
        }
        
        
               
         
           $search_data =  $search_data->groupBy('participants.id')->paginate($this->pagenation_number);
            $html = '';
            $offset = ($request->page - 1) * $this->pagenation_number;
            foreach ($search_data as $key => $res) {
                $html .= '<tr id="row-">';
                $html .= '<td  style="width:2%;;text-align:left;" >';
                $html .= $offset + $key + 1;
                $html .= '</td>';
                $html .= '<td style="width:10%;text-align:left">';
                $html .= $res->e_f_name . ($res->e_l_name && $res->e_f_name ? ',' : '') . ' ' . $res->e_l_name;
                $html .= '</td>';
                $html .= '<td style="width:10%;text-align:left">';
                $html .= $res->e_vmcode?$res->e_vmcode:'';
                $html .= '</td>';
                $html .= '<td style="width:10%;text-align:left">';
                $html .= $res->spouse_fname . ($res->spouse_lname && $res->spouse_fname ? ',' : '') . ' ' . $res->spouse_lname;
                $html .= '</td>';
                $html .= '<td style="width:10%;text-align:left">';
                $html .=  $res->spouse_dob ? date("m-d-Y", strtotime($res->spouse_dob)) : '' ;
                $html .= '</td>'; 
                $html .= '<td style="width:10%;text-align:left">';
                if(isset($res->spouse_dob) && $res->spouse_dob != '')
                $dateOfBirth = \Carbon\Carbon::parse($res->spouse_dob);
                $now = \Carbon\Carbon::now();
                $diff = $dateOfBirth->diff($now);
                $age_years = $diff->y . ' Years ' . $diff->m . ' Months ' . $diff->d . ' Days';
                $html .=  $age_years  ;
                $html .= '</td>';

                
                $html .= '<td style="width:10%;text-align:left">';
                $html .=  $res->coverage_name??'' ;
                $html .= '</td>'; 
                $html .= '</tr>';
            }
            if (trim($html) != '') {
                $pagenation = \View::make('include.pagenation', ['paginator' => $search_data])->render();
            } else {
                $pagenation = '';
                $html .= '<tr>';
                $html .= '<td  style="width:100%" class="text-nowrap" colspan="10">';
                $html .= 'No Data Found';
                $html .= '</td>';
            }
            if (!isset($request->page)) {
                $request->page = 1;
            }
            $page = (isset($request->page)) ? $request->page : 1;
            $offset = ($request->page - 1) * $this->pagenation_number;
            $start_page_number = $offset + 1;
            $end_page_number = $search_data->total();
            if ($end_page_number >= ($start_page_number + $this->pagenation_number)) {
                $end_page_number = $offset + $this->pagenation_number;
            }
            return response()->json([
                'message' => 'contributions',
                'html' => $html,
                'pagenation' => $pagenation,
                'start_page_number' => $start_page_number,
                'end_page_number' => $end_page_number,
                'total_records' => $search_data->total(),
                'status' => 1
            ], 200);

        } catch (exception $e) {

        }
    }
    public function employeespouse65novembervmebpalternativedownload(Request $request){
        $data=[];
        $data = getPermissionArray('all-reports');
        $user = Auth::user();
        $data["user"]=$user;
        $csv_data=[];
        $csv_data[]=array('Sl.No', 	'Primary Insurer', 	'VMCODE', 	'Spouse', 	'Spouse Date of Birth', 	'Age', 	'Coverage');
     
        $search_data =    DB::table('participants')
        ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
        ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
        ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
        ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
        ->leftJoin('employee','employee.id','=','participants.emp_id')
      //  ->leftJoin('childrens','childrens.id','=','participants.children_id')
        ->leftJoin('spouses','spouses.id','=','participants.spouse_id')
        ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
        , 'participants.fname as e_f_name'
        , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
        ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
        ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','relation_code.name as relation_name','participant_healthcare.group_type_id',
        'missionaries.lname as m_l_name','missionaries.fname as m_f_name',
        'employee.lname as emp_l_name','employee.fname as emp_f_name','spouses.spouse_fname','spouses.spouse_lname','spouses.spouse_dob'
        ,'spouses.spouse_phone','participants.missionary_id','participants.emp_id','participants.spouse_id')
        ->whereNotNull('participants.spouse_id')
        ->whereIn('health_coverage_members.id', [2,7]);
       
        
       if (isset($request->search_batch) && $request->search_batch != '') {
        $search_string = $request->search_batch;
        $search_data = $search_data->where(function ($q) use ($search_string) {
            $q->where(
                DB::raw("CONCAT(
                    COALESCE(spouses.spouse_fname, ''), ' ', COALESCE(spouses.spouse_lname, '')
                )"),
                'LIKE',
                "%{$search_string}%"
            );
            $q->orWhere(
                DB::raw("CONCAT(
                    COALESCE(participants.lname, ''), ' ', COALESCE(participants.fname, '')
                )"),
                'LIKE',
                "%{$search_string}%"
            );
         
        });
    }
//     if (!empty($request->search_filter)) {
       
//         $search_data = $search_data->selectRaw("timestampdiff(year, spouses.spouse_dob, '".$request->search_filter."-01-01') as spouseA ");
//    }
  
   
//    if (!empty($request->search_filter)) {
//     $search_data =  $search_data->whereRaw("timestampdiff(year, spouses.spouse_dob, '".$request->search_filter."-01-01') = 65");
//    }
//    if(empty($request->search_filter))
//    {
//     $search_data = $search_data->where(function ($query) {
//         $query->where(function ($q) {
//             $q->whereRaw('timestampdiff(year, spouses.spouse_dob, curdate()) = 64') // Currently 64 years old
            
//               ->whereRaw('YEAR(spouses.spouse_dob) = YEAR(CURDATE()) - 65'); // Will turn 65 this year
//         });
//     });
//    }
  
       
      
        
        
//       else {

//         }
       //dd($search_data);
        
       if (!empty($request->search_year)) {
        $searchYear = $request->search_year;
        $searchDate = "$searchYear-01-01";
    
        $search_data->where(function ($query) use ($searchDate) {
            $query->where(function ($q) use ($searchDate) {
                $q->whereRaw('timestampdiff(year, spouses.spouse_dob, ?) = 64', [$searchDate])
                  ->whereRaw('YEAR(spouses.spouse_dob) = YEAR(?) - 65', [$searchDate]);
            })->orWhere(function ($q) use ($searchDate) {
                $q->whereRaw('timestampdiff(year, employee.dob, ?) = 64', [$searchDate])
                  ->whereRaw('YEAR(employee.dob) = YEAR(?) - 65', [$searchDate]);
            });
        });
    
    } else {
        $search_data->where(function ($query) {
            $query->where(function ($q) {
                $q->whereRaw("timestampdiff(year, spouses.spouse_dob, DATE_FORMAT(CURDATE(), '%Y-01-01')) = 64")
                  ->whereRaw("YEAR(spouses.spouse_dob) = YEAR(DATE_FORMAT(CURDATE(), '%Y-01-01')) - 65");
            })->orWhere(function ($q) {
                $q->whereRaw("timestampdiff(year, employee.dob, DATE_FORMAT(CURDATE(), '%Y-01-01')) = 64")
                  ->whereRaw("YEAR(employee.dob) = YEAR(DATE_FORMAT(CURDATE(), '%Y-01-01')) - 65");
            });
        });
    }
     
       $search_data =  $search_data->groupBy('participants.id')->get();


        foreach($search_data as $key=>$val){
            $tmp_arr=[];
            $tmp_arr[0]=$key + 1;
            $tmp_arr[1]=$val->m_l_name . ($val->m_l_name && $val->m_f_name ? ',' : '') . ' ' . $val->m_f_name;
            $tmp_arr[2]=$val->e_vmcode??'';
            $tmp_arr[3]=$val->spouse_lname . ($val->spouse_lname && $val->spouse_fname ? ',' : '') . ' ' . $val->spouse_fname;
            if (!empty($val->spouse_dob) && strtotime($val->spouse_dob)) {
              $tmp_arr[4] = date("M, Y", strtotime($val->spouse_dob));
          } else {
              $tmp_arr[4] = ''; 
          }

          if (!empty($val->spouse_dob) && strtotime($val->spouse_dob)) {
            $dateOfBirth = \Carbon\Carbon::parse($val->spouse_dob);
            $now = \Carbon\Carbon::now();
            $diff = $dateOfBirth->diff($now);
            $age_years = $diff->y . ' Years ' . $diff->m . ' Months ' . $diff->d . ' Days';
              $tmp_arr[5] = $age_years;
          } else {
              $tmp_arr[5] = ''; 
          }
          
            $tmp_arr[6]=$val->coverage_name??'';
           
         
           
            $csv_data[]= $tmp_arr;
        }
    
        csvDownlaod($csv_data,"employeespouse65novembervmebpalternative.csv"); 
    }
    public function employeefamilyvmebp(Request $request){
        try{
            $data=[];
            $data=getPermissionArray('vmebp_reports');//get permission for each module if its add in module table
            $user = Auth::user();
            $data["user"]=$user;
            if($data["permission_array"]["vmebp_reports"]["no_view"]==1){
            $data["menu"]="vmebp_reports";
            $query_string=[];
            $data["query_string"]='';
            $data["search_filter"]='';
            $data["search_batch"]='';
            $data["status_filter"]='';
            if(!isset($request->page)){
                $request->page=1;
            }
            $data["page"]=(isset($request->page))?$request->page:1;
            $data["offset"]=($request->page-1)*$this->pagenation_number;
            $search_year=date('Y');
             $data["results"] =  DB::table('participants')
             ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
             ->leftJoin('participant_opt_outs','participant_opt_outs.participants_id','=','participants.id')
             ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
             ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
             ->leftJoin('group_types','group_types.id','=','participant_healthcare.group_type_id')
             ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
             ->leftJoin('employee','employee.id','=','participants.emp_id')
             ->leftJoin('childrens','childrens.id','=','participants.children_id')
             ->leftJoin('spouses','spouses.id','=','participants.spouse_id')
     
             ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
             , 'participants.fname as e_f_name'
             , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
             ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
             ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','participant_opt_outs.opt_out_date','relation_code.name as relation_name','participant_healthcare.group_type_id',
             'participant_opt_outs.term_end_date','participant_opt_outs.opt_out_reason' ,'group_types.group_name',
             'missionaries.lname as m_l_name','missionaries.fname as m_f_name','missionaries.dob as m_dob',
             'employee.lname as emp_l_name','employee.fname as emp_f_name','employee.dob as emp_dob','childrens.lname as child_l_name','childrens.fname as child_f_name','childrens.dob as child_dob','spouses.spouse_fname','spouses.spouse_lname','spouses.spouse_dob'
             ,'spouses.spouse_phone','participants.missionary_id','participants.emp_id','participants.spouse_id','participants.children_id')
             ->where('health_coverage_members.id','=',2)
             ->whereNotNull('participant_healthcare.status')
             ->where(function ($query) use ($search_year) {
                $query->whereRaw("YEAR(participant_healthcare.start_date) <= ?", [$search_year])
                      ->where(function ($q) use ($search_year) {
                          $q->whereNull('participant_healthcare.end_date')
                            ->orWhereRaw("YEAR(participant_healthcare.end_date) >= ?", [$search_year]);
                      });
            })
         ->orderByRaw("participants.vmcode,
             CASE 
                 WHEN relation_code.name = 'Self' THEN 1
                 WHEN relation_code.name = 'Spouse' THEN 2
                 WHEN relation_code.name = 'Children' THEN 3
                 ELSE 4 
             END
         ")
             ->groupBy('participants.id')
             ->paginate($this->pagenation_number);
         //  dd($data["results"]);
           ///print_r($data["results"]);die;
           $data["per_page"]=$this->pagenation_number;
           $data["data"]= $data["results"];
            return view('all_vmebp_reports.employeefamilyvmebp',["data"=>$data])->with('count', 1);;
        }else{
            return Redirect::to('dashboard')->with('error', "Access denied .");
        } 
        }catch(exception $e){
                
        }


    }
    public function employeefamilyvmebpsearch(Request $request){
        try {
            $data = [];
            $data = getPermissionArray('vmebp_reports');
            $user = Auth::user();
            $data["user"] = $user;
            $search_year=date('Y');
            $search_data = DB::table('participants')
            ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
            ->leftJoin('participant_opt_outs','participant_opt_outs.participants_id','=','participants.id')
            ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
            ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
            ->leftJoin('group_types','group_types.id','=','participant_healthcare.group_type_id')
            ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
            ->leftJoin('employee','employee.id','=','participants.emp_id')
            ->leftJoin('childrens','childrens.id','=','participants.children_id')
            ->leftJoin('spouses','spouses.id','=','participants.spouse_id')
    
            ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
            , 'participants.fname as e_f_name'
            , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
            ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
            ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','participant_opt_outs.opt_out_date','relation_code.name as relation_name','participant_healthcare.group_type_id',
            'participant_opt_outs.term_end_date','participant_opt_outs.opt_out_reason' ,'group_types.group_name',
            'missionaries.lname as m_l_name','missionaries.fname as m_f_name','missionaries.dob as m_dob',
            'employee.lname as emp_l_name','employee.fname as emp_f_name','employee.dob as emp_dob','childrens.lname as child_l_name','childrens.fname as child_f_name','childrens.dob as child_dob','spouses.spouse_fname','spouses.spouse_lname','spouses.spouse_dob'
            ,'spouses.spouse_phone','participants.missionary_id','participants.emp_id','participants.spouse_id','participants.children_id')
            ->where('health_coverage_members.id','=',2)
            ->whereNotNull('participant_healthcare.status');
        //   ->whereNotNull('participant_opt_outs.opt_out_date')
           //->whereNotNull('participants.termination_date')
          // ->whereNotNull('participant_opt_outs.termination_date')
  
    
       
            
           
          if (isset($request->search_batch) && $request->search_batch != '') {
            $search_string = $request->search_batch;
            $search_data = $search_data->where(function ($q) use ($search_string) {
                $q->where(
                    DB::raw("CONCAT(
                        COALESCE(participants.lname, ''), ' ', COALESCE(participants.fname, '')
                    )"),
                    'LIKE',
                    "%{$search_string}%"
                );
                $q->orWhere(
                    DB::raw("CONCAT(
                        COALESCE(spouses.spouse_fname, ''), ' ', COALESCE(spouses.spouse_lname, '')
                    )"),
                    'LIKE',
                    "%{$search_string}%"
                );
                $q->orWhere(
                    DB::raw("CONCAT(
                        COALESCE(childrens.lname, ''), ' ', COALESCE(childrens.fname, '')
                    )"),
                    'LIKE',
                    "%{$search_string}%"
                );
                $q->orWhere(
                    DB::raw("CONCAT(
                        COALESCE(missionaries.lname, ''), ' ', COALESCE(missionaries.fname, '')
                    )"),
                    'LIKE',
                    "%{$search_string}%"
                );
                $q->orWhere(
                    DB::raw("CONCAT(
                        COALESCE(employee.lname, ''), ' ', COALESCE(employee.fname, '')
                    )"),
                    'LIKE',
                    "%{$search_string}%"
                );
            });
        }
            if (!empty($request->searchvmcode)) {
         
              $search_data = $search_data->where("participants.vmcode", $request->searchvmcode);
            }
          
            if (!empty($request->search_year)) {
                $search_year = $request->search_year;
            
                $search_data = $search_data->where(function ($query) use ($search_year) {
                    $query->whereRaw("YEAR(participant_healthcare.start_date) <= ?", [$search_year]) 
                          ->where(function ($q) use ($search_year) {
                              $q->whereNull('participant_healthcare.end_date') 
                                ->orWhereRaw("YEAR(participant_healthcare.end_date) >= ?", [$search_year]); 
                          });
                });
            }
            else{
                $search_data = $search_data->where(function ($query) use ($search_year) {
                    $query->whereRaw("YEAR(participant_healthcare.start_date) <= ?", [$search_year]) 
                          ->where(function ($q) use ($search_year) {
                              $q->whereNull('participant_healthcare.end_date') 
                                ->orWhereRaw("YEAR(participant_healthcare.end_date) >= ?", [$search_year]); 
                          });
                });
            }
       
      
           //dd($search_data);
            
         
           $search_data =  $search_data
           ->groupBy('participants.id')
          ->orderByRaw("participants.vmcode,
          CASE 
              WHEN relation_code.name = 'Self' THEN 1
              WHEN relation_code.name = 'Spouse' THEN 2
              WHEN relation_code.name = 'Children' THEN 3
              ELSE 4 
          END
      ")
           ->paginate($this->pagenation_number);
          // $search_data =  $search_data->groupBy('childrens.id')->toSql();
          // dd($search_data);
            $html = '';
            $offset = ($request->page - 1) * $this->pagenation_number;
            foreach ($search_data as $key => $res) {
       
            
                $html .= '<tr>';
                $html .= '<td style="width:2%; text-align:left;">' . ($offset + $key + 1) . '</td>';
                
                // Determine full name based on available fields
                $full_name = '-';
                if (!empty($res->missionary_id) && empty($res->emp_id) && empty($res->children_id) && empty($res->spouse_id)) {
                    $full_name = trim(($res->m_l_name ?? '') . ($res->m_l_name && $res->m_f_name ? ', ' : '') . ($res->m_f_name ?? '')) . ' [Missionary]';
                } elseif (!empty($res->children_id)) {
                    $full_name = trim(($res->child_l_name ?? '') . ($res->child_l_name && $res->child_f_name ? ', ' : '') . ($res->child_f_name ?? '')) . ' [Child]';
                } elseif (!empty($res->spouse_id)) {
                    $full_name = trim(($res->spouse_lname ?? '') . ($res->spouse_lname && $res->spouse_fname ? ', ' : '') . ($res->spouse_fname ?? '')) . ' [Spouse]';
                } elseif (!empty($res->emp_id)) {
                    $full_name = trim(($res->emp_l_name ?? '') . ($res->emp_l_name && $res->emp_f_name ? ', ' : '') . ($res->emp_f_name ?? '')) . ' [Employee]';
                }
                
               //age

               if (!empty($res->missionary_id) && empty($res->emp_id) && empty($res->children_id) && empty($res->spouse_id)) {
                $dob = $res->m_dob ?? null;
            } elseif (!empty($res->children_id)) {
                $dob = $res->child_dob ?? null;
            } elseif (!empty($res->spouse_id)) {
                $dob = $res->spouse_dob ?? null;
            } elseif (!empty($res->emp_id)) {
                $dob = $res->emp_dob ?? null;
            } else {
                $dob = null;
            }
            
            if (!empty($dob)) {
                $dateOfBirth = \Carbon\Carbon::parse($dob);
                $age = $dateOfBirth->diff(\Carbon\Carbon::now())->format('%y Years');
            } else {
                $age = '-';
            }
                
                $html .= "<td style='width:10%; text-align:left'>$full_name</td>";
                $html .= "<td style='width:10%; text-align:left'>" . ($res->e_vmcode ?? '') . "</td>";
               
                if (!empty($res->missionary_id) && empty($res->emp_id) && empty($res->children_id) && empty($res->spouse_id)) {
                    $html .= "<td style='width:10%; text-align:left'>" . (!empty($res->m_dob) ? date('m-d-Y', strtotime($res->m_dob)) : '') . "</td>";
                } elseif (!empty($res->children_id)) {
                    $html .= "<td style='width:10%; text-align:left'>" . (!empty($res->child_dob) ? date('m-d-Y', strtotime($res->child_dob)) : '') . "</td>";
                } elseif (!empty($res->spouse_id)) {
                    $html .= "<td style='width:10%; text-align:left'>" . (!empty($res->spouse_dob) ? date('m-d-Y', strtotime($res->spouse_dob)) : '') . "</td>";
                } elseif (!empty($res->emp_id)) {
                    $html .= "<td style='width:10%; text-align:left'>" . (!empty($res->emp_dob) ? date('m-d-Y', strtotime($res->emp_dob)) : '') . "</td>";
                }
                
                
          
                $html .= "<td style='width:10%; text-align:left'>".$age."</td>";
                $html .= "<td style='width:10%; text-align:left'>" . ($res->coverage_name ?? '') . "</td>";
                $html .= '</tr>';
            }
            
            
            
            if (trim($html) != '') {
                $pagenation = \View::make('include.pagenation', ['paginator' => $search_data])->render();
            } else {
                $pagenation = '';
                $html .= '<tr>';
                $html .= '<td  style="width:100%" class="text-nowrap" colspan="10">';
                $html .= 'No Data Found';
                $html .= '</td>';
            }
            if (!isset($request->page)) {
                $request->page = 1;
            }
            $page = (isset($request->page)) ? $request->page : 1;
            $offset = ($request->page - 1) * $this->pagenation_number;
            $start_page_number = $offset + 1;
            $end_page_number = $search_data->total();
            if ($end_page_number >= ($start_page_number + $this->pagenation_number)) {
                $end_page_number = $offset + $this->pagenation_number;
            }
            return response()->json([
                'message' => 'contributions',
                'html' => $html,
                'pagenation' => $pagenation,
                'start_page_number' => $start_page_number,
                'end_page_number' => $end_page_number,
                'total_records' => $search_data->total(),
                'status' => 1
            ], 200);

        } catch (exception $e) {

        }

    }
    public function employeefamilyvmebpdownload(Request $request){
      //  dd($request->all());
        $data=[];
        $data = getPermissionArray('vmebp_reports');
        $user = Auth::user();
        $search_year=date('Y');
        $data["user"]=$user;
        $csv_data=[];
        $csv_data[]=array('Sl.No','Name', 'VMCODE', 'DOB','AGE','Coverage',
   );
     
        $search_data = DB::table('participants')
        ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
        ->leftJoin('participant_opt_outs','participant_opt_outs.participants_id','=','participants.id')
        ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
        ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
        ->leftJoin('group_types','group_types.id','=','participant_healthcare.group_type_id')
        ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
        ->leftJoin('employee','employee.id','=','participants.emp_id')
        ->leftJoin('childrens','childrens.id','=','participants.children_id')
        ->leftJoin('spouses','spouses.id','=','participants.spouse_id')

        ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
        , 'participants.fname as e_f_name'
        , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
        ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
        ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','participant_opt_outs.opt_out_date','relation_code.name as relation_name','participant_healthcare.group_type_id',
        'participant_opt_outs.term_end_date','participant_opt_outs.opt_out_reason' ,'group_types.group_name',
        'missionaries.lname as m_l_name','missionaries.fname as m_f_name','missionaries.dob as m_dob',
        'employee.lname as emp_l_name','employee.fname as emp_f_name','employee.dob as emp_dob','childrens.lname as child_l_name','childrens.fname as child_f_name','childrens.dob as child_dob','spouses.spouse_fname','spouses.spouse_lname','spouses.spouse_dob'
        ,'spouses.spouse_phone','participants.missionary_id','participants.emp_id','participants.spouse_id','participants.children_id')
        ->where('health_coverage_members.id','=',2)
        ->whereNotNull('participant_healthcare.status');
    //   ->whereNotNull('participant_opt_outs.opt_out_date')
       //->whereNotNull('participants.termination_date')
      // ->whereNotNull('participant_opt_outs.termination_date')


   
        
       
      if (isset($request->search_batch) && $request->search_batch != '') {
        $search_string = $request->search_batch;
        $search_data = $search_data->where(function ($q) use ($search_string) {
            $q->where(
                DB::raw("CONCAT(
                    COALESCE(participants.lname, ''), ' ', COALESCE(participants.fname, '')
                )"),
                'LIKE',
                "%{$search_string}%"
            );
            $q->orWhere(
                DB::raw("CONCAT(
                    COALESCE(spouses.spouse_fname, ''), ' ', COALESCE(spouses.spouse_lname, '')
                )"),
                'LIKE',
                "%{$search_string}%"
            );
            $q->orWhere(
                DB::raw("CONCAT(
                    COALESCE(childrens.lname, ''), ' ', COALESCE(childrens.fname, '')
                )"),
                'LIKE',
                "%{$search_string}%"
            );
            $q->orWhere(
                DB::raw("CONCAT(
                    COALESCE(missionaries.lname, ''), ' ', COALESCE(missionaries.fname, '')
                )"),
                'LIKE',
                "%{$search_string}%"
            );
            $q->orWhere(
                DB::raw("CONCAT(
                    COALESCE(employee.lname, ''), ' ', COALESCE(employee.fname, '')
                )"),
                'LIKE',
                "%{$search_string}%"
            );
        });
    }
        if (!empty($request->searchvmcode)) {
     
          $search_data = $search_data->where("participants.vmcode", $request->searchvmcode);
        }
      
        if (!empty($request->search_year)) {
            $search_year = $request->search_year;
        
            $search_data = $search_data->where(function ($query) use ($search_year) {
                $query->whereRaw("YEAR(participant_healthcare.start_date) <= ?", [$search_year]) 
                      ->where(function ($q) use ($search_year) {
                          $q->whereNull('participant_healthcare.end_date') 
                            ->orWhereRaw("YEAR(participant_healthcare.end_date) >= ?", [$search_year]); 
                      });
            });
        }
        else{
            $search_data = $search_data->where(function ($query) use ($search_year) {
                $query->whereRaw("YEAR(participant_healthcare.start_date) <= ?", [$search_year]) 
                      ->where(function ($q) use ($search_year) {
                          $q->whereNull('participant_healthcare.end_date') 
                            ->orWhereRaw("YEAR(participant_healthcare.end_date) >= ?", [$search_year]); 
                      });
            }); 
        }
   
  
       //dd($search_data);
        
     
       $search_data =  $search_data
      ->groupBy('participants.id')
      ->orderByRaw("participants.vmcode,
      CASE 
          WHEN relation_code.name = 'Self' THEN 1
          WHEN relation_code.name = 'Spouse' THEN 2
          WHEN relation_code.name = 'Children' THEN 3
          ELSE 4 
      END
  ")
       ->get();
      // dd($search_data);

       foreach ($search_data as $key => $val) {
     // Determine full name based on available fields
     $full_name = '-';
     $userDOB="";
     if (!empty($val->missionary_id) && empty($val->emp_id) && empty($val->children_id) && empty($val->spouse_id)) {
         $full_name = trim(($val->m_l_name ?? '') . ($val->m_l_name && $val->m_f_name ? ', ' : '') . ($val->m_f_name ?? '')) . ' [Missionary]';
     } elseif (!empty($val->children_id)) {
         $full_name = trim(($val->child_l_name ?? '') . ($val->child_l_name && $val->child_f_name ? ', ' : '') . ($val->child_f_name ?? '')) . ' [Child]';
     } elseif (!empty($val->spouse_id)) {
         $full_name = trim(($val->spouse_lname ?? '') . ($val->spouse_lname && $val->spouse_fname ? ', ' : '') . ($val->spouse_fname ?? '')) . ' [Spouse]';
     } elseif (!empty($val->emp_id)) {
         $full_name = trim(($val->emp_l_name ?? '') . ($val->emp_l_name && $val->emp_f_name ? ', ' : '') . ($val->emp_f_name ?? '')) . ' [Employee]';
     }
     //dob

     if (!empty($val->missionary_id) && empty($val->emp_id) && empty($val->children_id) && empty($val->spouse_id)) {
        $userDOB = !empty($val->m_dob) ? date('m-d-Y', strtotime($val->m_dob)) : '';
    } elseif (!empty($val->children_id)) {
        $userDOB = !empty($val->child_dob) ? date('m-d-Y', strtotime($val->child_dob)) : '';
    } elseif (!empty($val->spouse_id)) {
        $userDOB = !empty($val->spouse_dob) ? date('m-d-Y', strtotime($val->spouse_dob)) : '';
    } elseif (!empty($val->emp_id)) {
        $userDOB = !empty($val->emp_dob) ? date('m-d-Y', strtotime($val->emp_dob)) : '';
    }
    
    //age

    if (!empty($val->missionary_id) && empty($val->emp_id) && empty($val->children_id) && empty($val->spouse_id)) {
     $dob = $val->m_dob ?? null;
 } elseif (!empty($val->children_id)) {
     $dob = $val->child_dob ?? null;
 } elseif (!empty($val->spouse_id)) {
     $dob = $val->spouse_dob ?? null;
 } elseif (!empty($val->emp_id)) {
     $dob = $val->emp_dob ?? null;
 } else {
     $dob = null;
 }
 
 if (!empty($dob)) {
     $dateOfBirth = \Carbon\Carbon::parse($dob);
     $age = $dateOfBirth->diff(\Carbon\Carbon::now())->format('%y Years');
 } else {
     $age = '-';
 }
        $tmp_arr = [];
    
        $tmp_arr[0] = $key + 1; // Sl.No
        $tmp_arr[1] =  $full_name;
        $tmp_arr[2] = $val->e_vmcode ?? ''; 
        $tmp_arr[3] = $userDOB ?? ''; 
        $tmp_arr[4] = $age ?? ''; 
        $tmp_arr[5] = $val->coverage_name ?? '';
    
     
    
        $csv_data[] = $tmp_arr;
    }
    
        csvDownlaod($csv_data,"optoutvmebpgroupcoverage.csv");   
    }
    public function employeeenrollmentdetailsvmebp(Request $request){
        try{
            $data=[];
            $data=getPermissionArray('vmebp_reports');//get permission for each module if its add in module table
            $user = Auth::user();
            $data["user"]=$user;
            if($data["permission_array"]["vmebp_reports"]["no_view"]==1){
            $data["menu"]="vmebp_reports";
            $query_string=[];
            $data["query_string"]='';
            $data["search_filter"]='';
            $data["search_batch"]='';
            $data["status_filter"]='';
            if(!isset($request->page)){
                $request->page=1;
            }
            $data["page"]=(isset($request->page))?$request->page:1;
            $data["offset"]=($request->page-1)*$this->pagenation_number;
            $search_year=date('Y');
        //     $data["results"] = Employee::leftjoin('childrens', 'childrens.emp_id', '=', 'employee.id')
        //     ->leftJoin('spouses', 'spouses.emp_id', '=', 'employee.id')
        //     ->leftJoin('participants','participants.vmcode','=','employee.vmcode')
        //     ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
        //     ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
        //     ->leftjoin('users', 'users.id', '=', 'childrens.updated_by')
        //     ->leftjoin('users as uc', 'uc.id', '=', 'childrens.created_by')
        //     ->leftjoin('users as un', 'un.id', '=', 'childrens.user_id')
        //     ->orderByRaw('isNULL(childrens.dob) asc, datediff(childrens.dob,now()) asc')
        //     //->orderByRaw('childrens.dob asc')
        //     ->select('users.name as username', 'uc.name as created_username', 'childrens.*','spouses.id as spouse_id'
        //     ,'spouses.spouse_fname','spouses.spouse_lname', 'spouses.spouse_dob','employee.fname as e_f_name'
        //     , 'employee.lname as e_l_name', 'employee.vmcode as e_vmcode'
        //     ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
        //     ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary')
        //    ->where('health_coverage_members.id','=',2)
 
        //     ->groupBy('employee.id')
        //     ->paginate($this->pagenation_number);

        $data["results"] = DB::table('participants')
        ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
        ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
        ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
        ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
        ->leftJoin('employee','employee.id','=','participants.emp_id')
        ->leftJoin('childrens','childrens.id','=','participants.children_id')
        ->leftJoin('spouses','spouses.id','=','participants.spouse_id')
        ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
        , 'participants.fname as e_f_name'
        , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
        ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
        ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','relation_code.name as relation_name','participant_healthcare.group_type_id',
        'missionaries.lname as m_l_name','missionaries.fname as m_f_name',
        'employee.lname as emp_l_name','employee.fname as emp_f_name','childrens.lname as child_l_name','childrens.fname as child_f_name','spouses.spouse_fname','spouses.spouse_lname','spouses.spouse_dob'
        ,'spouses.spouse_phone','participants.missionary_id','participants.emp_id','participants.spouse_id','participants.children_id')
        // ->whereNotNull('participant_opt_outs.opt_out_date')
         ->where('participant_healthcare.is_primary',1)
         ->where('participant_healthcare.health_coverage_member_id',2)
         ->whereNotNull('participant_healthcare.status')
         ->where(function ($query) use ($search_year) {
            $query->whereRaw("YEAR(participant_healthcare.start_date) <= ?", [$search_year])
                  ->where(function ($q) use ($search_year) {
                      $q->whereNull('participant_healthcare.end_date')
                        ->orWhereRaw("YEAR(participant_healthcare.end_date) >= ?", [$search_year]);
                  });
        })
        ->groupBy('participants.id')
        ->orderBy('participants.vmcode')
        ->paginate($this->pagenation_number);
       //   dd($data["results"]);
           // dd($data["results"]);
           ///print_r($data["results"]);die;
           $data["per_page"]=$this->pagenation_number;
           $data["data"]= $data["results"];
            return view('all_vmebp_reports.employeeenrollmentdetailsvmebp',["data"=>$data])->with('count', 1);;
        }else{
            return Redirect::to('dashboard')->with('error', "Access denied .");
        } 
        }catch(exception $e){
                
        }


    }
    public function employeeenrollmentdetailsvmebpsearch(Request $request){
        try {
            $data = [];
            $data = getPermissionArray('vmebp_reports');
            $user = Auth::user();
            $data["user"] = $user;
            $search_year=date('Y');
        //     $search_data =  Employee::leftjoin('childrens', 'childrens.emp_id', '=', 'employee.id')
        //     ->leftJoin('spouses', 'spouses.emp_id', '=', 'employee.id')
        //     ->leftJoin('participants','participants.vmcode','=','employee.vmcode')
        //     ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
        //     ->leftJoin('participant_opt_outs','participant_opt_outs.participants_id','=','participants.id')
        //     ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
        //     ->leftjoin('users', 'users.id', '=', 'childrens.updated_by')
        //     ->leftjoin('users as uc', 'uc.id', '=', 'childrens.created_by')
        //     ->leftjoin('users as un', 'un.id', '=', 'childrens.user_id')
        //     ->orderByRaw('isNULL(childrens.dob) asc, datediff(childrens.dob,now()) asc')
        //     //->orderByRaw('childrens.dob asc')
        //     ->select('users.name as username', 'uc.name as created_username', 'childrens.*','spouses.id as spouse_id','participants.id as participantid','participant_healthcare.pss_no as pssno'
        //     ,'spouses.spouse_fname','spouses.spouse_lname', 'employee.fname as e_f_name'
        //     , 'employee.lname as e_l_name', 'employee.vmcode as e_vmcode','employee.joining_date','employee.termination_date'
        //     ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
        //     ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','participant_opt_outs.opt_out_date',
        //     'participant_opt_outs.term_end_date','participant_opt_outs.opt_out_reason')
        //    ->where('health_coverage_members.id','=',2)
        //    ->whereNotNull('participant_opt_outs.opt_out_date')
        //    ->whereNotNull('employee.termination_date');
        $search_data = DB::table('participants')
        ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
        ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
        ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
        ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
        ->leftJoin('employee','employee.id','=','participants.emp_id')
        ->leftJoin('childrens','childrens.id','=','participants.children_id')
        ->leftJoin('spouses','spouses.id','=','participants.spouse_id')
        ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
        , 'participants.fname as e_f_name'
        , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
        ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
        ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','relation_code.name as relation_name','participant_healthcare.group_type_id',
        'missionaries.lname as m_l_name','missionaries.fname as m_f_name',
        'employee.lname as emp_l_name','employee.fname as emp_f_name','childrens.lname as child_l_name','childrens.fname as child_f_name','spouses.spouse_fname','spouses.spouse_lname','spouses.spouse_dob'
        ,'spouses.spouse_phone' ,'participants.missionary_id','participants.emp_id','participants.spouse_id','participants.children_id')
        // ->whereNotNull('participant_opt_outs.opt_out_date')
         ->where('participant_healthcare.is_primary',1)
         ->where('participant_healthcare.health_coverage_member_id',2)
         ->whereNotNull('participant_healthcare.status');


            if (isset($request->search_batch) && $request->search_batch != '') {
                $search_string = $request->search_batch;
                $search_data = $search_data->where(function ($q) use ($search_string) {
                    $q->where(
                        DB::raw("CONCAT(
                            COALESCE(participants.lname, ''), ' ', COALESCE(participants.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(spouses.spouse_fname, ''), ' ', COALESCE(spouses.spouse_lname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(childrens.lname, ''), ' ', COALESCE(childrens.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(missionaries.lname, ''), ' ', COALESCE(missionaries.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(employee.lname, ''), ' ', COALESCE(employee.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                });
            }

            if (!empty($request->search_year)) {
                $search_year = $request->search_year;
            
                $search_data = $search_data->where(function ($query) use ($search_year) {
                    $query->whereRaw("YEAR(participant_healthcare.start_date) <= ?", [$search_year]) 
                          ->where(function ($q) use ($search_year) {
                              $q->whereNull('participant_healthcare.end_date') 
                                ->orWhereRaw("YEAR(participant_healthcare.end_date) >= ?", [$search_year]); 
                          });
                });
            }
            else{
            
                $search_data = $search_data->where(function ($query) use ($search_year) {
                    $query->whereRaw("YEAR(participant_healthcare.start_date) <= ?", [$search_year]) 
                          ->where(function ($q) use ($search_year) {
                              $q->whereNull('participant_healthcare.end_date') 
                                ->orWhereRaw("YEAR(participant_healthcare.end_date) >= ?", [$search_year]); 
                          });
                });  
            }
            
            
            
            
          
            
            // if (!empty($request->search_filter)) {
            //     // Convert date_from (MM-DD-YYYY or MM-YYYY) to Y-m-d format
            //    // $searchyear = \Carbon\Carbon::createFromFormat('Y', $request->date_from)->startOfMonth()->format('Y-m-d');
            // $searchyear = \Carbon\Carbon::createFromFormat('Y-m-d', $request->search_filter)->startOfMonth()->format('Y-m-d');
            //     // $search_data = $search_data->where('mf.form_date', '>=', $date_from);
            //     $search_data = $search_data->where(function($q) use ($searchyear) {
            //         $q->where(function($q1) use ($searchyear){
            //             $q1->whereRaw('timestampdiff(year, childrens.dob, curdate()) = 17') ;// Currently 17 years old
            //             $q1->whereIn(DB::raw('MONTH(childrens.dob)'), [ 11]) ;
            //             $q1->whereRaw('YEAR(childrens.dob) = YEAR(CURDATE()) - 18') ;
                       
            //         });
                   
            //     });
            // }
            
        //   else {
        //         $search_data = $search_data
        //         ->whereRaw('timestampdiff(year, childrens.dob, curdate()) = 17') // Currently 17 years old
        //         ->whereIn(DB::raw('MONTH(childrens.dob)'), [ 11]) 
        //         ->whereRaw('YEAR(childrens.dob) = YEAR(CURDATE()) - 18'); // Will turn 18 this year
                 
        //     }
         
            
         
           $search_data =  $search_data->groupBy('participants.id')->orderBy('participants.vmcode')->paginate($this->pagenation_number);
         //  dd($search_data);
            $html = '';
            $offset = ($request->page - 1) * $this->pagenation_number;
        
            foreach ($search_data as $key => $res) {
             
                if (!empty($res->missionary_id) && empty($res->emp_id) && empty($res->children_id) && empty($res->spouse_id)) {
       
                    $insurerName=$res->m_l_name . ($res->m_l_name && $res->m_f_name ? ',' : '') . ' ' . $res->m_f_name;
             } elseif (!empty($res->children_id)) {
                   $insurerName=$res->child_l_name . ($res->child_l_name && $res->child_f_name ? ',' : '') . ' ' . $res->child_f_name;
             } elseif (!empty($res->spouse_id)) {
                   $insurerName=$res->spouse_lname . ($res->spouse_lname && $res->spouse_fname ? ',' : '') . ' ' . $res->spouse_fname;
             } elseif (!empty($res->emp_id)) {
              $insurerName=$res->emp_l_name . ($res->emp_l_name && $res->emp_f_name ? ',' : '') . ' ' . $res->emp_f_name;
             } else {
               $insurerName="";
             }

                if(isset($res->spouse_dob) && $res->spouse_dob != ''){
               
                    $dateOfBirth = \Carbon\Carbon::parse($res->spouse_dob);
                    $age = $dateOfBirth->diff(\Carbon\Carbon::now())->format('%y Years');
                }
               
            else{
                $age ="-";
            }
                $html .= '<tr id="row-' . ($offset + $key + 1) . '">';
                
                // Serial Number
                $html .= '<td style="width:2%; text-align:left;">' .  ($offset + $key + 1). '</td>';
                $html .= '<td style="width:10%; text-align:left;">' . $insurerName . '</td>';
            
                // Participant ID
                $html .= '<td style="width:2%;">' . ($res->e_vmcode ?? '') . '</td>';
                $html .= '<td style="width:2%;">' .  date('m-d-Y',strtotime($res->start_date)). '</td>';
            

            

                $html .= '<td style="width:10%; text-align:left;">' . $res->spouse_lname . ($res->spouse_fname && $res->spouse_lname ? ',' : '') . ' ' . $res->spouse_fname . '</td>';
            
                // // Spouse Name
                // $html .= '<td style="width:10%; text-align:left;">' . ($res->spouse_lname ?? '') . ', ' . ($res->spouse_fname ?? '') . '</td>';
            
                // Start Date
                $html .= '<td style="width:10%; text-align:left;">' . (!empty($res->spouse_dob) ? date("m-d-Y", strtotime($res->spouse_dob)) : '') . '</td>';
            
                // Opt-out Date
                $html .= '<td style="width:10%; text-align:left;">' . $age . '</td>';
            
                // Opt-out Reason
                $html .= '<td style="width:10%; text-align:left;">' . ($res->coverage_name ?? '') . '</td>';
            
                // Term End Date

            
                $html .= '</tr>';
            }
            if (trim($html) != '') {
                $pagenation = \View::make('include.pagenation', ['paginator' => $search_data])->render();
            } else {
                $pagenation = '';
                $html .= '<tr>';
                $html .= '<td  style="width:100%" class="text-nowrap" colspan="10">';
                $html .= 'No Data Found';
                $html .= '</td>';
            }
            if (!isset($request->page)) {
                $request->page = 1;
            }
            $page = (isset($request->page)) ? $request->page : 1;
            $offset = ($request->page - 1) * $this->pagenation_number;
            $start_page_number = $offset + 1;
            $end_page_number = $search_data->total();
            if ($end_page_number >= ($start_page_number + $this->pagenation_number)) {
                $end_page_number = $offset + $this->pagenation_number;
            }
            return response()->json([
                'message' => 'contributions',
                'html' => $html,
                'pagenation' => $pagenation,
                'start_page_number' => $start_page_number,
                'end_page_number' => $end_page_number,
                'total_records' => $search_data->total(),
                'status' => 1
            ], 200);

        } catch (exception $e) {

        }
    }
    public function employeeenrollmentdetailsvmebpdownload(Request $request){
        $data=[];
        $data = getPermissionArray('vmebp_reports');
        $user = Auth::user();
        $data["user"]=$user;
        $csv_data=[];
        $csv_data[]=array('Sl.No', 	'Primary Insurer', 	'VMCODE', 	'Coverage Start Date', 	'Spouse', 'Spouse Date of Birth',	'Age', 	'Coverage');
     
    //     $search_data =  Employee::leftjoin('childrens', 'childrens.emp_id', '=', 'employee.id')
    //     ->leftJoin('spouses', 'spouses.emp_id', '=', 'employee.id')
    //     ->leftJoin('participants','participants.vmcode','=','employee.vmcode')
    //     ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
    //     ->leftJoin('participant_opt_outs','participant_opt_outs.participants_id','=','participants.id')
    //     ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
    //     ->leftjoin('users', 'users.id', '=', 'childrens.updated_by')
    //     ->leftjoin('users as uc', 'uc.id', '=', 'childrens.created_by')
    //     ->leftjoin('users as un', 'un.id', '=', 'childrens.user_id')
    //     ->orderByRaw('isNULL(childrens.dob) asc, datediff(childrens.dob,now()) asc')
    //     //->orderByRaw('childrens.dob asc')
    //     ->select('users.name as username', 'uc.name as created_username', 'childrens.*','spouses.id as spouse_id','participants.id as participantid','participant_healthcare.pss_no as pssno'
    //     ,'spouses.spouse_fname','spouses.spouse_lname', 'employee.fname as e_f_name'
    //     , 'employee.lname as e_l_name', 'employee.vmcode as e_vmcode','employee.joining_date','employee.termination_date'
    //     ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
    //     ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','participant_opt_outs.opt_out_date',
    //     'participant_opt_outs.term_end_date','participant_opt_outs.opt_out_reason')
    //    ->where('health_coverage_members.id','=',2)
    //    ->whereNotNull('participant_opt_outs.opt_out_date')
    //    ->whereNotNull('employee.termination_date');
        
       
    //    if (isset($request->search_batch) && $request->search_batch != '') {
    //     $search_string = $request->search_batch;
    //     $search_data = $search_data->where(function ($q) use ($search_string) {
    //         $q->where(
    //             DB::raw("CONCAT(
    //                 COALESCE(employee.lname, ''), ' ', COALESCE(employee.fname, '')
    //             )"),
    //             'LIKE',
    //             "%{$search_string}%"
    //         );
    //         $q->orWhere(
    //             DB::raw("CONCAT(
    //                 COALESCE(spouses.spouse_fname, ''), ' ', COALESCE(spouses.spouse_lname, '')
    //             )"),
    //             'LIKE',
    //             "%{$search_string}%"
    //         );
    //     });
    // }

    // if (!empty($request->search_year)) {
    //     $search_data = $search_data->whereRaw("YEAR(participant_opt_outs.opt_out_date) = ?", [$request->search_year])
    //                                ->orWhereRaw("YEAR(participant_opt_outs.term_end_date) = ?", [$request->search_year]);
    // }
        //dd($search_data);
        $search_data = DB::table('participants')
        ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
        ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
        ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
        ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
        ->leftJoin('employee','employee.id','=','participants.emp_id')
        ->leftJoin('childrens','childrens.id','=','participants.children_id')
        ->leftJoin('spouses','spouses.id','=','participants.spouse_id')
        ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
        , 'participants.fname as e_f_name'
        , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
        ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
        ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','relation_code.name as relation_name','participant_healthcare.group_type_id',
        'missionaries.lname as m_l_name','missionaries.fname as m_f_name',
        'employee.lname as emp_l_name','employee.fname as emp_f_name','childrens.lname as child_l_name','childrens.fname as child_f_name','spouses.spouse_fname','spouses.spouse_lname','spouses.spouse_dob'
        ,'spouses.spouse_phone','participants.missionary_id','participants.emp_id','participants.spouse_id','participants.children_id')
        // ->whereNotNull('participant_opt_outs.opt_out_date')
         ->where('participant_healthcare.is_primary',1)
         ->where('participant_healthcare.health_coverage_member_id',2)
         ->whereNotNull('participant_healthcare.status');



            if (isset($request->search_batch) && $request->search_batch != '') {
                $search_string = $request->search_batch;
                $search_data = $search_data->where(function ($q) use ($search_string) {
                    $q->where(
                        DB::raw("CONCAT(
                            COALESCE(participants.lname, ''), ' ', COALESCE(participants.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(spouses.spouse_fname, ''), ' ', COALESCE(spouses.spouse_lname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(childrens.lname, ''), ' ', COALESCE(childrens.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(missionaries.lname, ''), ' ', COALESCE(missionaries.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(employee.lname, ''), ' ', COALESCE(employee.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                });
            }

            if (!empty($request->search_year)) {
                $search_year = $request->search_year;
            
                $search_data = $search_data->where(function ($query) use ($search_year) {
                    $query->whereRaw("YEAR(participant_healthcare.start_date) <= ?", [$search_year]) 
                          ->where(function ($q) use ($search_year) {
                              $q->whereNull('participant_healthcare.end_date') 
                                ->orWhereRaw("YEAR(participant_healthcare.end_date) >= ?", [$search_year]); 
                          });
                });
            }
            else{
                $search_year = date('Y');
            
                $search_data = $search_data->where(function ($query) use ($search_year) {
                    $query->whereRaw("YEAR(participant_healthcare.start_date) <= ?", [$search_year]) 
                          ->where(function ($q) use ($search_year) {
                              $q->whereNull('participant_healthcare.end_date') 
                                ->orWhereRaw("YEAR(participant_healthcare.end_date) >= ?", [$search_year]); 
                          });
                });
            }
        $search_data = $search_data->groupBy('participants.id')->orderBy('participants.vmcode')->get();
      // dd($search_data);

      foreach ($search_data as $key => $res) {
        if(isset($res->spouse_dob) && $res->spouse_dob != ''){
        
            $dateOfBirth = \Carbon\Carbon::parse($res->spouse_dob);
            $age = $dateOfBirth->diff(\Carbon\Carbon::now())->format('%y Years');
        }
            
        else{
        $age="-";
        }

        $tmp_arr = [];
    
        $tmp_arr[0] = $key + 1; // Sl.No
        if (!empty($res->missionary_id) && empty($res->emp_id) && empty($res->children_id) && empty($res->spouse_id)) {
        
                $insurerName=$res->m_l_name . ($res->m_l_name && $res->m_f_name ? ',' : '') . ' ' . $res->m_f_name;
        } elseif (!empty($res->children_id)) {
            $insurerName=$res->child_l_name . ($res->child_l_name && $res->child_f_name ? ',' : '') . ' ' . $res->child_f_name;
        } elseif (!empty($res->spouse_id)) {
            $insurerName=$res->spouse_lname . ($res->spouse_lname && $res->spouse_fname ? ',' : '') . ' ' . $res->spouse_fname;
        } elseif (!empty($res->emp_id)) {
        $insurerName=$res->emp_l_name . ($res->emp_l_name && $res->emp_f_name ? ',' : '') . ' ' . $res->emp_f_name;
        } else {
        $insurerName="";
        }
        $tmp_arr[1] = $insurerName; // Participant #
        $tmp_arr[2] = $res->e_vmcode ?? ''; // PSS#
    
      
        // coverage Date
        $tmp_arr[3] = (!empty($res->start_date) && strtotime($res->start_date)) ? date("m-d-Y", strtotime($res->start_date)) : '';
        $tmp_arr[4] = $res->spouse_lname . ($res->spouse_fname && $res->spouse_lname ? ',' : '') . ' ' . $res->spouse_fname;
        if(!empty($res->spouse_dob)){
            $tmp_arr[5] = date('m-d-Y',strtotime($res->spouse_dob));
        }
        else{
            $tmp_arr[5] = '';
        }
         $tmp_arr[6]=$age;
         $tmp_arr[7]=$res->coverage_name ?? '';
    
        // Termination Date → 'NA'

        $csv_data[] = $tmp_arr;
    }
    
        csvDownlaod($csv_data,"optoutvmebpgroupcoverage.csv");   
    }
    public function annualpersonalresponsibility(Request $request){
        try{
            $data=[];
            $data=getPermissionArray('vmebp_reports');//get permission for each module if its add in module table
            $user = Auth::user();
            $data["user"]=$user;
            if($data["permission_array"]["vmebp_reports"]["no_view"]==1){
            $data["menu"]="vmebp_reports";
            $query_string=[];
            $data["query_string"]='';
            $data["search_filter"]='';
            $data["search_batch"]='';
            $data["status_filter"]='';
            if(!isset($request->page)){
                $request->page=1;
            }
            $data["page"]=(isset($request->page))?$request->page:1;
            $data["offset"]=($request->page-1)*$this->pagenation_number;
             $data["results"] = participants::leftjoin('participant_healthcare', 'participant_healthcare.participants_id', '=', 'participants.id')
            
             ->select('participants.*','participant_healthcare.uid','participant_healthcare.is_deductable_paid','participant_healthcare.amount','participant_healthcare.persoal_responsibility_date')
             ->where('participant_healthcare.is_deductable_paid','=',1);
            
             $data["results"] =$data["results"]
             ->groupBy('participants.id')
          

             ->paginate($this->pagenation_number);
           // dd($data["results"]);
           ///print_r($data["results"]);die;
           $data["per_page"]=$this->pagenation_number;
           $data["data"]= $data["results"];
            return view('all_vmebp_reports.annualpersonalresponsibility',["data"=>$data])->with('count', 1);;
        }else{
            return Redirect::to('dashboard')->with('error', "Access denied .");
        } 
        }catch(exception $e){
                
        }


    }
    public function annualpersonalresponsibilitysearch(Request $request){
        try {
            $data = [];
            $data = getPermissionArray('all-reports');
            $user = Auth::user();
            $data["user"] = $user;
            $search_data = participants::leftjoin('participant_healthcare', 'participant_healthcare.participants_id', '=', 'participants.id')
            
            ->select('participants.*','participant_healthcare.uid','participant_healthcare.is_deductable_paid','participant_healthcare.amount','participant_healthcare.persoal_responsibility_date')
            ->where('participant_healthcare.is_deductable_paid','=',1);
           
            
           
            if (isset($request->search_batch) && $request->search_batch != '') {
                $search_string = $request->search_batch;
                $search_data = $search_data->where(function ($q) use ($search_string) {
                    $q->where(
                        DB::raw("CONCAT(
                            COALESCE(participants.lname, ''), ' ', COALESCE(participants.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    
                });
            }
            if (!empty($request->search_year)) {
                $search_string = $request->search_year;
                $search_data = $search_data->where(function ($q) use ($search_string) {
                    $q->whereRaw("YEAR(participant_healthcare.persoal_responsibility_date) = ?", [$search_string])
                    ;
                  
                });
            }

           
          
            
            
          else {
                 
            }
           //dd($search_data);
            
         
           $search_data =  $search_data->groupBy('participants.id')->paginate($this->pagenation_number);
          // $search_data =  $search_data->groupBy('participants.id')->toSql();
           //dd($search_data);
            $html = '';
            $offset = ($request->page - 1) * $this->pagenation_number;
            foreach ($search_data as $key => $res) {
                $html .= '<tr id="row-">';
                $html .= '<td  style="width:2%;;text-align:left;" >';
                $html .= $offset + $key + 1;
                $html .= '</td>';
                $html .= '<td style="width:10%;text-align:left">';
                $html .= $res->vmcode;
                $html .= '</td>';
                $html .= '<td style="width:10%; text-align:left;">';
                $html .= $res->lname??'';
                $html .= '</td>';   
                $html .= '<td style="width:10%; text-align:left;">';
                $html .= $res->fname??'';
                $html .= '</td>';   
               
                $html .= '<td style="width:10%;text-align:left">';
                $html .= $res->uid??'';
                $html .= '</td>';
                $current_year = date("Y");
                for($i = -3; $i < 1; $i++)
                {
                    $html .= '<td style="width:10%;text-align:left">';
                    if(date('Y', strtotime($res->persoal_responsibility_date)) == $current_year + $i)
                    {
                      
                        $html .= 'YES';
                      
        
                    }
                    $html .= '</td>';
                }
               
               

                $html .= '</tr>';
            }
            if (trim($html) != '') {
                $pagenation = \View::make('include.pagenation', ['paginator' => $search_data])->render();
            } else {
                $pagenation = '';
                $html .= '<tr>';
                $html .= '<td  style="width:100%" class="text-nowrap" colspan="10">';
                $html .= 'No Data Found';
                $html .= '</td>';
            }
            if (!isset($request->page)) {
                $request->page = 1;
            }
            $page = (isset($request->page)) ? $request->page : 1;
            $offset = ($request->page - 1) * $this->pagenation_number;
            $start_page_number = $offset + 1;
            $end_page_number = $search_data->total();
            if ($end_page_number >= ($start_page_number + $this->pagenation_number)) {
                $end_page_number = $offset + $this->pagenation_number;
            }
            return response()->json([
                'message' => 'contributions',
                'html' => $html,
                'pagenation' => $pagenation,
                'start_page_number' => $start_page_number,
                'end_page_number' => $end_page_number,
                'total_records' => $search_data->total(),
                'status' => 1
            ], 200);

        } catch (exception $e) {

        }

    }
    public function annualpersonalresponsibilitydownload(Request $request){
        // dd($request->all());
        $data=[];
        $data = getPermissionArray('all-reports');
        $user = Auth::user();
        $data["user"]=$user;
        $csv_data=[];
        $csv_data[]=array('Sl.No',	'Participant id ',	'Last Name', 	'First Name ',	'CHM ID ',	'YEAR');
     
        $search_data =   participants::leftjoin('participant_healthcare', 'participant_healthcare.participants_id', '=', 'participants.id')
            
        ->select('participants.*','participant_healthcare.uid','participant_healthcare.is_deductable_paid','participant_healthcare.amount','participant_healthcare.persoal_responsibility_date');
       
        
       
        if (isset($request->search_batch) && $request->search_batch != '') {
            $search_string = $request->search_batch;
            $search_data = $search_data->where(function ($q) use ($search_string) {
                $q->where(
                    DB::raw("CONCAT(
                        COALESCE(participants.lname, ''), ' ', COALESCE(participants.fname, '')
                    )"),
                    'LIKE',
                    "%{$search_string}%"
                );
                
            });
        }
        if (!empty($request->search_year)) {
            $search_string = $request->search_year;
            $search_data = $search_data->where(function ($q) use ($search_string) {
                $q->whereRaw("YEAR(participant_healthcare.persoal_responsibility_date) = ?", [$search_string])
                ;
              
            });
        }
       

   
         else {
               
           
             
            }
        //dd($search_data);
        $search_data = $search_data->where('participant_healthcare.is_deductable_paid','=',1)->groupBy('participants.id')->get();


        foreach($search_data as $key=>$val){
            $tmp_arr=[];
            $tmp_arr[0]=$key + 1;
            $tmp_arr[1]=$val->vmcode;
            $tmp_arr[2]=$val->lname;
            $tmp_arr[3]=$val->fname;
            $tmp_arr[4]=$val->uid??'';
            $current_year = date("Y");
                for($i = -3; $i < 1; $i++)
                {
                  
                    if(date('Y', strtotime($val->persoal_responsibility_date)) == $current_year + $i)
                    {
                      
                        $tmp_arr[5]='YES'.'('.date('Y', strtotime($val->persoal_responsibility_date)).')';
                      
        
                    }
                   
                    
                }
            
            $csv_data[]= $tmp_arr;
        }
    
        csvDownlaod($csv_data,"annualpersonalresponsibilitydownload.csv");   

    }
    public function wellnessreimbursement(Request $request){
        try{
            $data=[];
            $data=getPermissionArray('vmebp_reports');//get permission for each module if its add in module table
            $user = Auth::user();
            $data["user"]=$user;
            if($data["permission_array"]["vmebp_reports"]["no_view"]==1){
            $data["menu"]="vmebp_reports";
            $query_string=[];
            $data["query_string"]='';
            $data["search_filter"]='';
            $data["search_batch"]='';
            $data["status_filter"]='';
            if(!isset($request->page)){
                $request->page=1;
            }
            $data["page"]=(isset($request->page))?$request->page:1;
            $data["offset"]=($request->page-1)*$this->pagenation_number;
            $data["results"] = claim_villagemission::join("participants", "participants.id", "=", "claim_villagemissions.participant_id")->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')->leftJoin("missionaries", "missionaries.vmcode", "=", "claim_villagemissions.vmcode")->leftjoin("claim_types", "claim_types.id", "=", "claim_villagemissions.claim_type_id")->select('missionaries.fname as misfname', 'missionaries.lname as mislname', 'claim_villagemissions.*','participants.id as participants_id', 'participant_healthcare.pss_no','participants.fname', 'participants.lname', 'participants.mname', 'claim_types.type as claim_type_name', 'participants.spouse_id', 'participants.children_id')
            
        
            ->where('claim_villagemissions.claim_type_id','=',9)
            ->orderByRaw("COALESCE(NULLIF(participants.lname, ''), participants.fname) ASC")
            ->groupBy('claim_villagemissions.id')
            ->paginate($this->pagenation_number);
            //dd($data["results"]);
           ///print_r($data["results"]);die;
           $data["per_page"]=$this->pagenation_number;
           $data["data"]= $data["results"];
            return view('all_vmebp_reports.wellnessreimbursement',["data"=>$data])->with('count', 1);;
        }else{
            return Redirect::to('dashboard')->with('error', "Access denied .");
        } 
        }catch(exception $e){
                
        }


    }
    public function wellnessreimbursementsearch(Request $request){
        try {
            $data = [];
            $data = getPermissionArray('all-reports');
            $user = Auth::user();
            $data["user"] = $user;
            $search_data =  claim_villagemission::join("participants", "participants.id", "=", "claim_villagemissions.participant_id")->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')->leftJoin("missionaries", "missionaries.vmcode", "=", "claim_villagemissions.vmcode")->leftjoin("claim_types", "claim_types.id", "=", "claim_villagemissions.claim_type_id")
            ->select('missionaries.fname as misfname', 'missionaries.lname as mislname', 'claim_villagemissions.*','participants.id as participants_id', 'participant_healthcare.pss_no','participants.fname', 'participants.lname', 'participants.mname', 'claim_types.type as claim_type_name', 'participants.spouse_id', 'participants.children_id')->where('claim_villagemissions.claim_type_id','=',9);
           
           
            if (isset($request->search_batch) && $request->search_batch != '') {
                $search_string = $request->search_batch;
                $search_data = $search_data->where(function ($q) use ($search_string) {
                    $q->where(
                        DB::raw("CONCAT(
                            COALESCE(participants.fname, ''), ' ', COALESCE(participants.lname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                  
                });
            }
          

            if (!empty($request->search_year)) {
                $search_string = $request->search_year;
                $search_data = $search_data->where(function ($q) use ($search_string) {
                    $q->whereRaw("YEAR(claim_villagemissions.date_of_service) = ?", [$search_string])
                    ->orWhereRaw("YEAR(claim_villagemissions.reimbursement_date) = ?", [$search_string])
                    ->orWhereRaw("YEAR(claim_villagemissions.date_of_request) = ?", [$search_string]);
                  
                });


                // $search_data = $search_data->whereRaw("YEAR(claim_villagemissions.date_of_service) = ?", [$request->search_year])
                //                            ->orWhereRaw("YEAR(claim_villagemissions.reimbursement_date) = ?", [$request->search_year])
                //                            ->orWhereRaw("YEAR(claim_villagemissions.date_of_request) = ?", [$request->search_year]);
            }
          
          
         
          // $search_data =  $search_data->groupBy('claim_villagemissions.id')->toSql();
          // dd($search_data);
           $search_data =  $search_data->groupBy('claim_villagemissions.id')
           ->orderByRaw("COALESCE(NULLIF(participants.lname, ''), participants.fname) ASC")
        //    ->orderBy('date_of_request','DESC')
           ->paginate($this->pagenation_number);
          
            $html = '';
            $offset = ($request->page - 1) * $this->pagenation_number;
            foreach ($search_data as $key => $res) {
                $html .= '<tr id="row-">';
                $html .= '<td  style="width:2%;;text-align:left;" >';
                $html .= $offset + $key + 1;
                $html .= '</td>';
                $html .= '<td style="width:10%;text-align:left">';
                $html .= $res->vmcode;
                $html .= '</td>';
                $html .= '<td style="width:10%;text-align:left">';
                $html .= $res->pss_no??'';
                $html .= '</td>';
                $html .= '<td style="width:10%; text-align:left;">';
                $html .= $res->lname??'';
                $html .= '</td>';   
                $html .= '<td style="width:10%; text-align:left;">';
                $html .= $res->fname??'';
                $html .= '</td>';   
                
                $html .= '<td style="width:10%;text-align:left">';
                 $html .=  $res->date_of_service ? date("m-d-Y", strtotime($res->date_of_service)) : '' ;
                 $html .= '</td>'; 
                 $html .= '<td style="width:10%; text-align:left;">';
                 $html .= $res->provider_name??'';
                 $html .= '</td>';  
                 $html .= '<td style="width:10%; text-align:left;">';
                 $html .= $res->claim_type_name??'';
                 $html .= '</td>';  
                 $html .= '<td style="width:10%; text-align:left;">';
                 $html .= number_format($res->claim_amount ?? 0, 2);
                 $html .= '</td>';  
                 $html .= '<td style="width:10%;text-align:left">';
                 $html .=  $res->date_of_request ? date("m-d-Y", strtotime($res->date_of_request)) : '' ;
                 $html .= '</td>'; 
                 $html .= '<td style="width:10%;text-align:left">';
                 $html .=  $res->reimbursement_date ? date("m-d-Y", strtotime($res->reimbursement_date)) : '' ;
                 $html .= '</td>'; 
                 $html .= '<td style="width:10%; text-align:left;">';
                 $html .= number_format($res->reimbursement_amount ?? 0, 2);
                 $html .= '</td>';  
     
                $html .= '<td style="width:10%;text-align:left">';
                $html .= $res->note??'';
                $html .= '</td>';
               
                $html .= '</tr>';
            }
            if (trim($html) != '') {
                $pagenation = \View::make('include.pagenation', ['paginator' => $search_data])->render();
            } else {
                $pagenation = '';
                $html .= '<tr>';
                $html .= '<td  style="width:100%" class="text-nowrap" colspan="10">';
                $html .= 'No Data Found';
                $html .= '</td>';
            }
            if (!isset($request->page)) {
                $request->page = 1;
            }
            $page = (isset($request->page)) ? $request->page : 1;
            $offset = ($request->page - 1) * $this->pagenation_number;
            $start_page_number = $offset + 1;
            $end_page_number = $search_data->total();
            if ($end_page_number >= ($start_page_number + $this->pagenation_number)) {
                $end_page_number = $offset + $this->pagenation_number;
            }
            return response()->json([
                'message' => 'contributions',
                'html' => $html,
                'pagenation' => $pagenation,
                'start_page_number' => $start_page_number,
                'end_page_number' => $end_page_number,
                'total_records' => $search_data->total(),
                'status' => 1
            ], 200);

        } catch (exception $e) {

        }

    }
    public function wellnessreimbursementdownload(Request $request){
        // dd($request->all());
        $data=[];
        $data = getPermissionArray('all-reports');
        $user = Auth::user();
        $data["user"]=$user;
        $csv_data=[];
        

        $csv_data[]=array('Sl.No', 	'Participant id', 	'PSS.No', 	'Last Name', 	'First Name', 	'Date of Service', 	'Provider', 	'Type', 	'Amount Submitted', 	'Submit Date', 	'Credit Date', 	'Amount Credited', 	'Note');
     
        $search_data =  claim_villagemission::join("participants", "participants.id", "=", "claim_villagemissions.participant_id")->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')->leftJoin("missionaries", "missionaries.vmcode", "=", "claim_villagemissions.vmcode")->leftjoin("claim_types", "claim_types.id", "=", "claim_villagemissions.claim_type_id")
            ->select('missionaries.fname as misfname', 'missionaries.lname as mislname', 'claim_villagemissions.*','participants.id as participants_id', 'participant_healthcare.pss_no','participants.fname', 'participants.lname', 'participants.mname', 'claim_types.type as claim_type_name', 'participants.spouse_id', 'participants.children_id') ->where('claim_villagemissions.claim_type_id','=',9);
          
           
            if (isset($request->search_batch) && $request->search_batch != '') {
                $search_string = $request->search_batch;
                $search_data = $search_data->where(function ($q) use ($search_string) {
                    $q->where(
                        DB::raw("CONCAT(
                            COALESCE(participants.fname, ''), ' ', COALESCE(participants.lname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                  
                });
            }
            // if (isset($request->search_year) && $request->search_year != '') {
            //     $search_string = $request->search_year;
            //     $search_data = $search_data->where(function ($q) use ($search_string) {
            //         $q->where(
            //             DB::raw("YEAR(claim_villagemissions.date_of_service) = ?", [$request->search_year])
            //         );
                  
            //     });
            // }

            if (!empty($request->search_filter)) {
                $search_string = $request->search_filter;
                $search_data = $search_data->where(function ($q) use ($search_string) {
                    $q->whereRaw("YEAR(claim_villagemissions.date_of_service) = ?", [$search_string])
                    ->orWhereRaw("YEAR(claim_villagemissions.reimbursement_date) = ?", [$search_string])
                    ->orWhereRaw("YEAR(claim_villagemissions.date_of_request) = ?", [$search_string]);
                  
                });
                // $search_data = $search_data->whereRaw("YEAR(claim_villagemissions.date_of_service) = ?", [$request->search_year])
                //                            ->orWhereRaw("YEAR(claim_villagemissions.reimbursement_date) = ?", [$request->search_year])
                //                            ->orWhereRaw("YEAR(claim_villagemissions.date_of_request) = ?", [$request->search_year]);
            }
            
            
            
         
           $search_data =  $search_data
           ->orderByRaw("COALESCE(NULLIF(participants.lname, ''), participants.fname) ASC")
           ->groupBy('claim_villagemissions.id')->get();


        foreach($search_data as $key=>$val){
            $tmp_arr=[];
            $tmp_arr[0]=$key + 1;
            $tmp_arr[1]=$val->vmcode??'';
            $tmp_arr[2]=$val->pss_no??'';
            $tmp_arr[3]=$val->lname??'';
            $tmp_arr[4]=$val->fname??'';
            
           
            if (!empty($val->date_of_service) && strtotime($val->date_of_service)) {
                $tmp_arr[5] = date("M, Y", strtotime($val->date_of_service));
            } else {
                $tmp_arr[5] = ''; 
            }
            $tmp_arr[6]=$val->provider_name??'';
            $tmp_arr[7]=$val->claim_type_name??'';
            $tmp_arr[8]= "$". number_format($val->claim_amount ?? 0, 2);
            if (!empty($val->date_of_request) && strtotime($val->date_of_request)) {
                $tmp_arr[9] = date("m-d-Y", strtotime($val->date_of_request));
            } else {
                $tmp_arr[9] = ''; 
            }
            if (!empty($val->reimbursement_date) && strtotime($val->reimbursement_date)) {
                $tmp_arr[10] = date("m-d-Y", strtotime($val->reimbursement_date));
            } else {
                $tmp_arr[10] = ''; 
            }
            $tmp_arr[11]="$". number_format($val->reimbursement_amount ?? 0, 2);
        
            $tmp_arr[13]=$val->note??'';
            
            $csv_data[]= $tmp_arr;
        }
    
        csvDownlaod($csv_data,"wellnessreport.csv");   



    }
    public function currentenrolledvmebpcontacts(Request $request){
        try{
           
            $data=[];
            $data=getPermissionArray('vmebp_reports');//get permission for each module if its add in module table
            $user = Auth::user();
            $data["user"]=$user;
            if($data["permission_array"]["vmebp_reports"]["no_view"]==1){
            $data["menu"]="vmebp_reports";
            $query_string=[];
            $data["query_string"]='';
            $data["search_filter"]='';
            $data["search_batch"]='';
            $data["status_filter"]='';
            if(!isset($request->page)){
                $request->page=1;
            }
            $data["country"]=country::orderBy('name','ASC')->where('status', '=', 1)->get();
            $data["page"]=(isset($request->page))?$request->page:1;
            $data["offset"]=($request->page-1)*$this->pagenation_number;
            $search_year=date('Y');
            $data["results"] =DB::table('participants')
            ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
           // ->leftJoin('participant_opt_outs','participant_opt_outs.participants_id','=','participants.id')
            ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
            ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
            ->leftJoin('group_types','group_types.id','=','participant_healthcare.group_type_id')
            ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
            ->leftJoin('employee','employee.id','=','participants.emp_id')
            ->leftJoin('childrens','childrens.id','=','participants.children_id')
            ->leftJoin('spouses','spouses.id','=','participants.spouse_id')
         
         
            ->leftJoin('cities as m_cities', 'm_cities.id', '=', 'missionaries.city_id')
            ->leftJoin('states as m_states', 'm_states.id', '=', 'm_cities.state_id')
            ->leftJoin('cities as e_cities', 'e_cities.id', '=', 'employee.city_id')
            ->leftJoin('cities as e_states', 'e_states.id', '=', 'e_cities.state_id')
            ->leftJoin('cities as s_cities', 's_cities.id', '=', 'spouses.city_id')
            ->leftJoin('states as s_states', 's_states.id', '=', 's_cities.state_id')
            
    
            ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
            , 'participants.fname as e_f_name'
            , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
            ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
            ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','relation_code.name as relation_name','participant_healthcare.group_type_id',
             'group_types.group_name',
            'missionaries.lname as m_l_name','missionaries.fname as m_f_name','missionaries.email as m_email','missionaries.phone as m_phone','missionaries.address as m_address',
            'employee.lname as emp_l_name','employee.fname as emp_f_name','employee.email as e_email','employee.phone as e_phone','employee.address as e_address','childrens.lname as child_l_name','childrens.fname as child_f_name','childrens.email as c_email','childrens.phone as c_phone','childrens.address as c_address','spouses.spouse_fname','spouses.spouse_lname','spouses.spouse_dob','spouses.spouse_email','spouses.address as spouse_address'
            ,'spouses.spouse_phone','participants.missionary_id','participants.emp_id','participants.spouse_id','participants.children_id','missionaries.city_id as m_city_id','employee.city_id as e_city_id','spouses.city_id as spouses_city_id'
            ,'missionaries.zip as m_zip','employee.zip as e_zip','spouses.spouse_zip as spouses_zip','m_cities.name as m_city','e_cities.name as e_city','s_cities.name as s_city','m_states.name as m_state','e_states.name as e_state','s_states.name as s_state')
           ->where('participant_healthcare.health_coverage_member_id','=',2)
           ->where('participant_healthcare.is_primary','=',1)
           ->whereNotNull('participant_healthcare.status')
        //   ->whereNotNull('participant_opt_outs.opt_out_date')
           //->whereNotNull('participants.termination_date')
          // ->whereNotNull('participant_opt_outs.termination_date')
        // ->orderByRaw("participants.vmcode,
        //     CASE 
        //         WHEN relation_code.name = 'Self' THEN 1
        //         WHEN relation_code.name = 'Spouse' THEN 2
        //         WHEN relation_code.name = 'Children' THEN 3
        //         ELSE 4 
        //     END
        // ")
        ->where(function ($query) use ($search_year) {
            $query->whereRaw("YEAR(participant_healthcare.start_date) <= ?", [$search_year])
                  ->where(function ($q) use ($search_year) {
                      $q->whereNull('participant_healthcare.end_date')
                        ->orWhereRaw("YEAR(participant_healthcare.end_date) >= ?", [$search_year]);
                  });
        })
        ->orderByRaw("COALESCE(NULLIF(participants.lname, ''), participants.fname) ASC")
            ->groupBy('participants.id')
            ->paginate($this->pagenation_number);
         //   dd($data["results"]);
           // dd($data["results"]);
           ///print_r($data["results"]);die;
           $data["per_page"]=$this->pagenation_number;
           $data["data"]= $data["results"];
            return view('all_vmebp_reports.currentenrolledvmebpcontacts',["data"=>$data])->with('count', 1);;
        }else{
            return Redirect::to('dashboard')->with('error', "Access denied .");
        } 
        }catch(exception $e){
                
        }


    }
    public function currentenrolledvmebpcontactssearch(Request $request){
        try {
            $data = [];
            $data = getPermissionArray('vmebp_reports');
            $user = Auth::user();
            $data["user"] = $user;
     
        $search_data = DB::table('participants')
        ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
       // ->leftJoin('participant_opt_outs','participant_opt_outs.participants_id','=','participants.id')
        ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
        ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
        ->leftJoin('group_types','group_types.id','=','participant_healthcare.group_type_id')
        ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
        ->leftJoin('employee','employee.id','=','participants.emp_id')
        ->leftJoin('childrens','childrens.id','=','participants.children_id')
        ->leftJoin('spouses','spouses.id','=','participants.spouse_id')
     
     
        ->leftJoin('cities as m_cities', 'm_cities.id', '=', 'missionaries.city_id')
        ->leftJoin('states as m_states', 'm_states.id', '=', 'm_cities.state_id')
        ->leftJoin('cities as e_cities', 'e_cities.id', '=', 'employee.city_id')
        ->leftJoin('cities as e_states', 'e_states.id', '=', 'e_cities.state_id')
        ->leftJoin('cities as s_cities', 's_cities.id', '=', 'spouses.city_id')
        ->leftJoin('states as s_states', 's_states.id', '=', 's_cities.state_id')
        

        ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
        , 'participants.fname as e_f_name'
        , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
        ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
        ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','relation_code.name as relation_name','participant_healthcare.group_type_id',
         'group_types.group_name',
        'missionaries.lname as m_l_name','missionaries.fname as m_f_name','missionaries.email as m_email','missionaries.phone as m_phone','missionaries.address as m_address',
        'employee.lname as emp_l_name','employee.fname as emp_f_name','employee.email as e_email','employee.phone as e_phone','employee.address as e_address','childrens.lname as child_l_name','childrens.fname as child_f_name','childrens.email as c_email','childrens.phone as c_phone','childrens.address as c_address','spouses.spouse_fname','spouses.spouse_lname','spouses.spouse_dob','spouses.spouse_email','spouses.address as spouse_address'
        ,'spouses.spouse_phone','participants.missionary_id','participants.emp_id','participants.spouse_id','participants.children_id','missionaries.city_id as m_city_id','employee.city_id as e_city_id','spouses.city_id as spouses_city_id'
        ,'missionaries.zip as m_zip','employee.zip as e_zip','spouses.spouse_zip as spouses_zip','m_cities.name as m_city','e_cities.name as e_city','s_cities.name as s_city','m_states.name as m_state','e_states.name as e_state','s_states.name as s_state')
       ->where('health_coverage_members.id','=',2)
       ->where('participant_healthcare.is_primary','=',1)
       ->whereNotNull('participant_healthcare.status');
    //   ->whereNotNull('participant_opt_outs.opt_out_date')
       //->whereNotNull('participants.termination_date')
      // ->whereNotNull('participant_opt_outs.termination_date')


      if (!empty($request->state)) {
        $state = $request->state;
        $search_data = $search_data->where(function ($query) use ($state) {
            $query->where('m_cities.state_id', $state)
                  ->orWhere('e_cities.state_id', $state)
                  ->orWhere('s_cities.state_id', $state);
        });
    }
    
    if (!empty($request->city)) {
        $city = $request->city;
        $search_data = $search_data->where(function ($query) use ($city) {
            $query->where('m_cities.id', $city)
                  ->orWhere('e_cities.id', $city)
                  ->orWhere('s_cities.id', $city);
        });
    }
    
    if (!empty($request->zip)) {
        $zip = $request->zip;
        $search_data = $search_data->where(function ($query) use ($zip) {
            $query->where('missionaries.zip', 'LIKE', "%{$zip}%")
                  ->orWhere('employee.zip', 'LIKE', "%{$zip}%")
                  ->orWhere('spouses.spouse_zip', 'LIKE', "%{$zip}%");
        });
    }
    

            if (isset($request->search_batch) && $request->search_batch != '') {
                $search_string = $request->search_batch;
                $search_data = $search_data->where(function ($q) use ($search_string) {
                    $q->where(
                        DB::raw("CONCAT(
                            COALESCE(participants.lname, ''), ' ', COALESCE(participants.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(spouses.spouse_fname, ''), ' ', COALESCE(spouses.spouse_lname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(childrens.lname, ''), ' ', COALESCE(childrens.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(missionaries.lname, ''), ' ', COALESCE(missionaries.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(employee.lname, ''), ' ', COALESCE(employee.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                });
            }

            if (!empty($request->search_year)) {
                $search_year = $request->search_year;
            
                $search_data = $search_data->where(function ($query) use ($search_year) {
                    $query->whereRaw("YEAR(participant_healthcare.start_date) <= ?", [$search_year]) 
                          ->where(function ($q) use ($search_year) {
                              $q->whereNull('participant_healthcare.end_date') 
                                ->orWhereRaw("YEAR(participant_healthcare.end_date) >= ?", [$search_year]); 
                          });
                });
            }
            else{
                $search_year = date('Y');
            
                $search_data = $search_data->where(function ($query) use ($search_year) {
                    $query->whereRaw("YEAR(participant_healthcare.start_date) <= ?", [$search_year]) 
                          ->where(function ($q) use ($search_year) {
                              $q->whereNull('participant_healthcare.end_date') 
                                ->orWhereRaw("YEAR(participant_healthcare.end_date) >= ?", [$search_year]); 
                          });
                });
            }
            
            
          
            
            // if (!empty($request->search_filter)) {
            //     // Convert date_from (MM-DD-YYYY or MM-YYYY) to Y-m-d format
            //    // $searchyear = \Carbon\Carbon::createFromFormat('Y', $request->date_from)->startOfMonth()->format('Y-m-d');
            // $searchyear = \Carbon\Carbon::createFromFormat('Y-m-d', $request->search_filter)->startOfMonth()->format('Y-m-d');
            //     // $search_data = $search_data->where('mf.form_date', '>=', $date_from);
            //     $search_data = $search_data->where(function($q) use ($searchyear) {
            //         $q->where(function($q1) use ($searchyear){
            //             $q1->whereRaw('timestampdiff(year, childrens.dob, curdate()) = 17') ;// Currently 17 years old
            //             $q1->whereIn(DB::raw('MONTH(childrens.dob)'), [ 11]) ;
            //             $q1->whereRaw('YEAR(childrens.dob) = YEAR(CURDATE()) - 18') ;
                       
            //         });
                   
            //     });
            // }
            
        //   else {
        //         $search_data = $search_data
        //         ->whereRaw('timestampdiff(year, childrens.dob, curdate()) = 17') // Currently 17 years old
        //         ->whereIn(DB::raw('MONTH(childrens.dob)'), [ 11]) 
        //         ->whereRaw('YEAR(childrens.dob) = YEAR(CURDATE()) - 18'); // Will turn 18 this year
                 
        //     }
         
            
         
           $search_data =  $search_data->groupBy('participants.id')
           ->orderByRaw("COALESCE(NULLIF(participants.lname, ''), participants.fname) ASC")
           ->paginate($this->pagenation_number);
         //  dd($search_data);
            $html = '';
            $offset = ($request->page - 1) * $this->pagenation_number;
        
            foreach ($search_data as $key => $res) {
    
                if (!empty($res->missionary_id) && empty($res->emp_id) && empty($res->children_id) && empty($res->spouse_id)) {
                    $insurerLastName = $res->m_l_name??'';
                    $insurerFirstName =  $res->m_f_name??'';
                    $address = $res->m_address;
                    $city = $res->m_city;
                    $state = $res->m_state;
                    $zip = $res->m_zip;
                    $phone = $res->m_phone;
                    $email = $res->m_email;
                } elseif (!empty($res->children_id)) {
                    $insurerLastName = $res->child_l_name??'';
                    $insurerFirstName =  $res->child_f_name??'';
                    $address = $res->c_address;
                    $city = '';
                    $state = '';
                    $zip = '';
                    $phone = $res->c_phone;
                    $email = $res->c_email;
                } elseif (!empty($res->spouse_id)) {
                    $insurerLastName = $res->spouse_lname??'';
                    $insurerFirstName =  $res->spouse_fname??'';
                    $address = $res->spouse_address;
                    $city = $res->s_city;
                    $state = $res->s_state;
                    $zip = $res->spouses_zip;
                    $phone = $res->spouse_phone;
                    $email = $res->spouse_email;
                } elseif (!empty($res->emp_id)) {
                    $insurerLastName = $res->emp_l_name??'';
                    $insurerFirstName =  $res->emp_f_name??'';
                    $address = $res->e_address;
                    $city = $res->e_city;
                    $state = $res->e_state;
                    $zip = $res->e_zip;
                    $phone = $res->e_phone;
                    $email = $res->e_email;
                } else {
                    $insurerLastName = "";
                    $insurerFirstName =  "";
                    $address = "";
                    $city = "";
                    $state = "";
                    $zip = "";
                    $phone = "";
                    $email = "";
                }
            
                $html .= '<tr id="row-' . ($offset + $key + 1) . '">' .
                    '<td style="width:2%; text-align:left;">' . ($offset + $key + 1) . '</td>' .
                    '<td style="width:10%; text-align:left;">' . $insurerLastName . '</td>' .
                    '<td style="width:10%; text-align:left;">' . $insurerFirstName . '</td>' .
                    '<td style="width:10%; text-align:left;">' . $address . '</td>' .
                    '<td style="width:10%; text-align:left;">' . $city . '</td>' .
                    '<td style="width:10%; text-align:left;">' . $state . '</td>' .
                    '<td style="width:10%; text-align:left;">' . $zip . '</td>' .
                    '<td style="width:10%; text-align:left;">' . $phone . '</td>' .
                    '<td style="width:10%; text-align:left;">' . $email . '</td>' .
                    '</tr>';
            }
            
            if (trim($html) != '') {
                $pagenation = \View::make('include.pagenation', ['paginator' => $search_data])->render();
            } else {
                $pagenation = '';
                $html .= '<tr>';
                $html .= '<td  style="width:100%" class="text-nowrap" colspan="10">';
                $html .= 'No Data Found';
                $html .= '</td>';
            }
            if (!isset($request->page)) {
                $request->page = 1;
            }
            $page = (isset($request->page)) ? $request->page : 1;
            $offset = ($request->page - 1) * $this->pagenation_number;
            $start_page_number = $offset + 1;
            $end_page_number = $search_data->total();
            if ($end_page_number >= ($start_page_number + $this->pagenation_number)) {
                $end_page_number = $offset + $this->pagenation_number;
            }
            return response()->json([
                'message' => 'contributions',
                'html' => $html,
                'pagenation' => $pagenation,
                'start_page_number' => $start_page_number,
                'end_page_number' => $end_page_number,
                'total_records' => $search_data->total(),
                'status' => 1
            ], 200);

        } catch (exception $e) {

        }
    }
    public function currentenrolledvmebpcontactsdownload(Request $request){
        $data=[];
        $data = getPermissionArray('vmebp_reports');
        $user = Auth::user();
        $data["user"]=$user;
        $csv_data=[];
        $csv_data[]=array('Sl.No', 	'Primary Last Name', 	'Primary First Name', 	'Mailing Address', 	'City', 'State',	'Zip', 	'Phone','Email');
     

        $search_data = DB::table('participants')
        ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
       // ->leftJoin('participant_opt_outs','participant_opt_outs.participants_id','=','participants.id')
        ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
        ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
        ->leftJoin('group_types','group_types.id','=','participant_healthcare.group_type_id')
        ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
        ->leftJoin('employee','employee.id','=','participants.emp_id')
        ->leftJoin('childrens','childrens.id','=','participants.children_id')
        ->leftJoin('spouses','spouses.id','=','participants.spouse_id')
     
     
        ->leftJoin('cities as m_cities', 'm_cities.id', '=', 'missionaries.city_id')
        ->leftJoin('states as m_states', 'm_states.id', '=', 'm_cities.state_id')
        ->leftJoin('cities as e_cities', 'e_cities.id', '=', 'employee.city_id')
        ->leftJoin('cities as e_states', 'e_states.id', '=', 'e_cities.state_id')
        ->leftJoin('cities as s_cities', 's_cities.id', '=', 'spouses.city_id')
        ->leftJoin('states as s_states', 's_states.id', '=', 's_cities.state_id')
        

        ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
        , 'participants.fname as e_f_name'
        , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
        ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
        ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','relation_code.name as relation_name','participant_healthcare.group_type_id',
         'group_types.group_name',
        'missionaries.lname as m_l_name','missionaries.fname as m_f_name','missionaries.email as m_email','missionaries.phone as m_phone','missionaries.address as m_address',
        'employee.lname as emp_l_name','employee.fname as emp_f_name','employee.email as e_email','employee.phone as e_phone','employee.address as e_address','childrens.lname as child_l_name','childrens.fname as child_f_name','childrens.email as c_email','childrens.phone as c_phone','childrens.address as c_address','spouses.spouse_fname','spouses.spouse_lname','spouses.spouse_dob','spouses.spouse_email','spouses.address as spouse_address'
        ,'spouses.spouse_phone','participants.missionary_id','participants.emp_id','participants.spouse_id','participants.children_id','missionaries.city_id as m_city_id','employee.city_id as e_city_id','spouses.city_id as spouses_city_id'
        ,'missionaries.zip as m_zip','employee.zip as e_zip','spouses.spouse_zip as spouses_zip','m_cities.name as m_city','e_cities.name as e_city','s_cities.name as s_city','m_states.name as m_state','e_states.name as e_state','s_states.name as s_state')
       ->where('health_coverage_members.id','=',2)
       ->where('participant_healthcare.is_primary','=',1)
       ->whereNotNull('participant_healthcare.status');
    //   ->whereNotNull('participant_opt_outs.opt_out_date')
       //->whereNotNull('participants.termination_date')
      // ->whereNotNull('participant_opt_outs.termination_date')

    //   if (isset($request->country) && $request->country != '') {
    //     $country = $request->country;
    //     $search_data = $search_data->where('countries.id', $country);

    // }
    if (!empty($request->state)) {
        $state = $request->state;
        $search_data = $search_data->where(function ($query) use ($state) {
            $query->where('m_cities.state_id', $state)
                  ->orWhere('e_cities.state_id', $state)
                  ->orWhere('s_cities.state_id', $state);
        });
    }
    
    if (!empty($request->city)) {
        $city = $request->city;
        $search_data = $search_data->where(function ($query) use ($city) {
            $query->where('m_cities.id', $city)
                  ->orWhere('e_cities.id', $city)
                  ->orWhere('s_cities.id', $city);
        });
    }
    
    if (!empty($request->zip)) {
        $zip = $request->zip;
        $search_data = $search_data->where(function ($query) use ($zip) {
            $query->where('missionaries.zip', 'LIKE', "%{$zip}%")
                  ->orWhere('employee.zip', 'LIKE', "%{$zip}%")
                  ->orWhere('spouses.spouse_zip', 'LIKE', "%{$zip}%");
        });
    }


            if (isset($request->search_batch) && $request->search_batch != '') {
                $search_string = $request->search_batch;
                $search_data = $search_data->where(function ($q) use ($search_string) {
                    $q->where(
                        DB::raw("CONCAT(
                            COALESCE(participants.lname, ''), ' ', COALESCE(participants.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(spouses.spouse_fname, ''), ' ', COALESCE(spouses.spouse_lname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(childrens.lname, ''), ' ', COALESCE(childrens.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(missionaries.lname, ''), ' ', COALESCE(missionaries.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(employee.lname, ''), ' ', COALESCE(employee.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                });
            }

            if (!empty($request->search_year)) {
                $search_year = $request->search_year;
            
                $search_data = $search_data->where(function ($query) use ($search_year) {
                    $query->whereRaw("YEAR(participant_healthcare.start_date) <= ?", [$search_year]) 
                          ->where(function ($q) use ($search_year) {
                              $q->whereNull('participant_healthcare.end_date') 
                                ->orWhereRaw("YEAR(participant_healthcare.end_date) >= ?", [$search_year]); 
                          });
                });
            }
            else{
                $search_year = date('Y');
            
                $search_data = $search_data->where(function ($query) use ($search_year) {
                    $query->whereRaw("YEAR(participant_healthcare.start_date) <= ?", [$search_year]) 
                          ->where(function ($q) use ($search_year) {
                              $q->whereNull('participant_healthcare.end_date') 
                                ->orWhereRaw("YEAR(participant_healthcare.end_date) >= ?", [$search_year]); 
                          });
                });
            }
            
            
          
            
            // if (!empty($request->search_filter)) {
            //     // Convert date_from (MM-DD-YYYY or MM-YYYY) to Y-m-d format
            //    // $searchyear = \Carbon\Carbon::createFromFormat('Y', $request->date_from)->startOfMonth()->format('Y-m-d');
            // $searchyear = \Carbon\Carbon::createFromFormat('Y-m-d', $request->search_filter)->startOfMonth()->format('Y-m-d');
            //     // $search_data = $search_data->where('mf.form_date', '>=', $date_from);
            //     $search_data = $search_data->where(function($q) use ($searchyear) {
            //         $q->where(function($q1) use ($searchyear){
            //             $q1->whereRaw('timestampdiff(year, childrens.dob, curdate()) = 17') ;// Currently 17 years old
            //             $q1->whereIn(DB::raw('MONTH(childrens.dob)'), [ 11]) ;
            //             $q1->whereRaw('YEAR(childrens.dob) = YEAR(CURDATE()) - 18') ;
                       
            //         });
                   
            //     });
            // }
            
        //   else {
        //         $search_data = $search_data
        //         ->whereRaw('timestampdiff(year, childrens.dob, curdate()) = 17') // Currently 17 years old
        //         ->whereIn(DB::raw('MONTH(childrens.dob)'), [ 11]) 
        //         ->whereRaw('YEAR(childrens.dob) = YEAR(CURDATE()) - 18'); // Will turn 18 this year
                 
        //     }
         
            
         
           $search_data =  $search_data
           ->groupBy('participants.id')
           ->orderByRaw("COALESCE(NULLIF(participants.lname, ''), participants.fname) ASC")
       ->get();
      // dd($search_data);

      foreach ($search_data as $key => $res) {
        if (!empty($res->missionary_id) && empty($res->emp_id) && empty($res->children_id) && empty($res->spouse_id)) {
            $lastName = $res->m_l_name;
            $firstName = $res->m_f_name;
            $address = $res->m_address;
            $city = $res->m_city;
            $state = $res->m_state;
            $zip = $res->m_zip;
            $phone = $res->m_phone;
            $email = $res->m_email;
        } elseif (!empty($res->children_id)) {
            $lastName = $res->child_l_name;
            $firstName = $res->child_f_name;
            $address = $res->c_address;
            $city = '';
            $state = '';
            $zip = '';
            $phone = $res->c_phone;
            $email = $res->c_email;
        } elseif (!empty($res->spouse_id)) {
            $lastName = $res->spouse_lname;
            $firstName = $res->spouse_fname;
            $address = $res->spouse_address;
            $city = $res->s_city;
            $state = $res->s_state;
            $zip = $res->spouses_zip;
            $phone = $res->spouse_phone;
            $email = $res->spouse_email;
        } elseif (!empty($res->emp_id)) {
            $lastName = $res->emp_l_name;
            $firstName = $res->emp_f_name;
            $address = $res->e_address;
            $city = $res->e_city;
            $state = $res->e_state;
            $zip = $res->e_zip;
            $phone = $res->e_phone;
            $email = $res->e_email;
        } else {
            $lastName = "";
            $firstName = "";
            $address = "";
            $city = "";
            $state = "";
            $zip = "";
            $phone = "";
            $email = "";
        }

        $tmp_arr = [];
    
        $tmp_arr[0] = $key + 1; // Sl.No
        $tmp_arr[1] = $lastName; // Participant #
        $tmp_arr[2] = $firstName; // Participant #
        $tmp_arr[3] = $address; // Participant #
        $tmp_arr[4] = $city; // Participant #
        $tmp_arr[5] = $state; // Participant #
        $tmp_arr[6] = $zip; // Participant #
        $tmp_arr[7] = $phone; // Participant #
        $tmp_arr[8] = $email; // Participant #

    
        // Termination Date → 'NA'

        $csv_data[] = $tmp_arr;
    }
    
        csvDownlaod($csv_data,"optoutvmebpgroupcoverage.csv"); 
    }
    public function optoutvmebpcov(Request $request){
        try{
            $data=[];
            $data=getPermissionArray('vmebp_reports');//get permission for each module if its add in module table
            $user = Auth::user();
            $data["user"]=$user;
            if($data["permission_array"]["vmebp_reports"]["no_view"]==1){
            $data["menu"]="vmebp_reports";
            $query_string=[];
            $data["query_string"]='';
            $data["search_filter"]='';
            $data["search_batch"]='';
            $data["status_filter"]='';
            if(!isset($request->page)){
                $request->page=1;
            }
            $data["page"]=(isset($request->page))?$request->page:1;
            $data["offset"]=($request->page-1)*$this->pagenation_number;
            $search_year=date('Y');
        //     $data["results"] = Participants::leftjoin('childrens', 'childrens.emp_id', '=', 'employee.id')
        //     ->leftJoin('spouses', 'spouses.emp_id', '=', 'employee.id')
        //     ->leftJoin('participants','participants.vmcode','=','employee.vmcode')
        //     ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
        //     ->leftJoin('participant_opt_outs','participant_opt_outs.participants_id','=','participants.id')
        //     ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
        //     ->leftjoin('users', 'users.id', '=', 'childrens.updated_by')
        //     ->leftjoin('users as uc', 'uc.id', '=', 'childrens.created_by')
        //     ->leftjoin('users as un', 'un.id', '=', 'childrens.user_id')
        //     ->orderByRaw('isNULL(childrens.dob) asc, datediff(childrens.dob,now()) asc')
        //     //->orderByRaw('childrens.dob asc')
        //     ->select('users.name as username', 'uc.name as created_username', 'childrens.*','spouses.id as spouse_id','participants.id as participantid','participant_healthcare.pss_no as pssno'
        //     ,'spouses.spouse_fname','spouses.spouse_lname', 'employee.fname as e_f_name'
        //     , 'employee.lname as e_l_name', 'employee.vmcode as e_vmcode','employee.joining_date','employee.termination_date'
        //     ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
        //     ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','participant_opt_outs.opt_out_date',
        //     'participant_opt_outs.term_end_date','participant_opt_outs.opt_out_reason')
        //  //  ->where('health_coverage_members.id','=',2)
        //    ->whereNotNull('participant_opt_outs.opt_out_date')
        //    ->whereNotNull('employee.termination_date')
        //     ->groupBy('employee.id')
        //     ->paginate($this->pagenation_number);



        $data["results"] = DB::table('participants')
        ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
        ->leftJoin('participant_opt_outs','participant_opt_outs.participants_id','=','participants.id')
        ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
        ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
        ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
        ->leftJoin('employee','employee.id','=','participants.emp_id')
        ->leftJoin('childrens','childrens.id','=','participants.children_id')
        ->leftJoin('spouses','spouses.id','=','participants.spouse_id')
        ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
        , 'participants.fname as e_f_name'
        , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
        ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
        ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','participant_opt_outs.opt_in_date','participant_opt_outs.opt_out_date','relation_code.name as relation_name','participant_healthcare.group_type_id',
        'participants.term_end_date','participant_opt_outs.opt_out_reason','missionaries.lname as m_l_name','missionaries.fname as m_f_name',
        'employee.lname as emp_l_name','employee.fname as emp_f_name','childrens.lname as child_l_name','childrens.fname as child_f_name','spouses.spouse_fname','spouses.spouse_lname',
        'participants.missionary_id','participants.emp_id','participants.spouse_id','participants.children_id')
        ->whereNotNull('participant_opt_outs.opt_out_date')
        ->whereNotNull('participant_healthcare.status')
        ->whereRaw("YEAR(participants.opt_out_date) = ?", [$search_year])
        ->orWhereRaw("YEAR(participants.term_end_date) = ?", [$search_year])
        //->groupBy('participants.id')
        ->orderBy('participants.vmcode')
        ->orderBy('participants.id', 'DESC')
        ->orderByRaw('(participants.lname IS NULL), participants.lname ASC')
        ->paginate($this->pagenation_number);
           // dd($data["results"]);
           // dd($data["results"]);
           ///print_r($data["results"]);die;
           $data["per_page"]=$this->pagenation_number;
           $data["data"]= $data["results"];
            return view('all_vmebp_reports.optoutvmebpcov',["data"=>$data])->with('count', 1);;
        }else{
            return Redirect::to('dashboard')->with('error', "Access denied .");
        } 
        }catch(exception $e){
                
        }


    }
    public function optoutvmebpcovsearch(Request $request){
        try {
            $data = [];
            $data = getPermissionArray('vmebp_reports');
            $user = Auth::user();
            $data["user"] = $user;
            $search_year=date('Y');
        //     $search_data =  Employee::leftjoin('childrens', 'childrens.emp_id', '=', 'employee.id')
        //     ->leftJoin('spouses', 'spouses.emp_id', '=', 'employee.id')
        //     ->leftJoin('participants','participants.vmcode','=','employee.vmcode')
        //     ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
        //     ->leftJoin('participant_opt_outs','participant_opt_outs.participants_id','=','participants.id')
        //     ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
        //     ->leftjoin('users', 'users.id', '=', 'childrens.updated_by')
        //     ->leftjoin('users as uc', 'uc.id', '=', 'childrens.created_by')
        //     ->leftjoin('users as un', 'un.id', '=', 'childrens.user_id')
        //     ->orderByRaw('isNULL(childrens.dob) asc, datediff(childrens.dob,now()) asc')
        //     //->orderByRaw('childrens.dob asc')
        //     ->select('users.name as username', 'uc.name as created_username', 'childrens.*','spouses.id as spouse_id','participants.id as participantid','participant_healthcare.pss_no as pssno'
        //     ,'spouses.spouse_fname','spouses.spouse_lname', 'employee.fname as e_f_name'
        //     , 'employee.lname as e_l_name', 'employee.vmcode as e_vmcode','employee.joining_date','employee.termination_date'
        //     ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
        //     ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','participant_opt_outs.opt_out_date',
        //     'participant_opt_outs.term_end_date','participant_opt_outs.opt_out_reason')
        //    ->where('health_coverage_members.id','=',2)
        //    ->whereNotNull('participant_opt_outs.opt_out_date')
        //    ->whereNotNull('employee.termination_date');
        $search_data = DB::table('participants')
        ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
        ->leftJoin('participant_opt_outs','participant_opt_outs.participants_id','=','participants.id')
        ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
        ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
        ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
        ->leftJoin('employee','employee.id','=','participants.emp_id')
        ->leftJoin('childrens','childrens.id','=','participants.children_id')
        ->leftJoin('spouses','spouses.id','=','participants.spouse_id')
        ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
        , 'participants.fname as e_f_name'
        , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
        ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
        ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','participant_opt_outs.opt_in_date','participant_opt_outs.opt_out_date','relation_code.name as relation_name','participant_healthcare.group_type_id',
        'participants.term_end_date','participant_opt_outs.opt_out_reason','missionaries.lname as m_l_name','missionaries.fname as m_f_name',
        'employee.lname as emp_l_name','employee.fname as emp_f_name','childrens.lname as child_l_name','childrens.fname as child_f_name','spouses.spouse_fname','spouses.spouse_lname',
        'participants.missionary_id','participants.emp_id','participants.spouse_id','participants.children_id')
        ->whereNotNull('participant_opt_outs.opt_out_date')
        ->whereNotNull('participant_healthcare.status');
      
 


            if (isset($request->search_batch) && $request->search_batch != '') {
                $search_string = $request->search_batch;
                $search_data = $search_data->where(function ($q) use ($search_string) {
                    $q->where(
                        DB::raw("CONCAT(
                            COALESCE(participants.lname, ''), ' ', COALESCE(participants.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(spouses.spouse_fname, ''), ' ', COALESCE(spouses.spouse_lname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(childrens.lname, ''), ' ', COALESCE(childrens.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(missionaries.lname, ''), ' ', COALESCE(missionaries.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(employee.lname, ''), ' ', COALESCE(employee.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                });
            }

            if (!empty($request->search_year)) {
                $search_data = $search_data->whereRaw("YEAR(participants.opt_out_date) = ?", [$request->search_year])
                                           ->orWhereRaw("YEAR(participants.term_end_date) = ?", [$request->search_year]);
            }
            else{
                $search_data = $search_data->whereRaw("YEAR(participants.opt_out_date) = ?", [$search_year])
                ->orWhereRaw("YEAR(participants.term_end_date) = ?", [$search_year]);
            }
            
            
          
            
            // if (!empty($request->search_filter)) {
            //     // Convert date_from (MM-DD-YYYY or MM-YYYY) to Y-m-d format
            //    // $searchyear = \Carbon\Carbon::createFromFormat('Y', $request->date_from)->startOfMonth()->format('Y-m-d');
            // $searchyear = \Carbon\Carbon::createFromFormat('Y-m-d', $request->search_filter)->startOfMonth()->format('Y-m-d');
            //     // $search_data = $search_data->where('mf.form_date', '>=', $date_from);
            //     $search_data = $search_data->where(function($q) use ($searchyear) {
            //         $q->where(function($q1) use ($searchyear){
            //             $q1->whereRaw('timestampdiff(year, childrens.dob, curdate()) = 17') ;// Currently 17 years old
            //             $q1->whereIn(DB::raw('MONTH(childrens.dob)'), [ 11]) ;
            //             $q1->whereRaw('YEAR(childrens.dob) = YEAR(CURDATE()) - 18') ;
                       
            //         });
                   
            //     });
            // }
            
        //   else {
        //         $search_data = $search_data
        //         ->whereRaw('timestampdiff(year, childrens.dob, curdate()) = 17') // Currently 17 years old
        //         ->whereIn(DB::raw('MONTH(childrens.dob)'), [ 11]) 
        //         ->whereRaw('YEAR(childrens.dob) = YEAR(CURDATE()) - 18'); // Will turn 18 this year
                 
        //     }
         
            
         
           $search_data =  $search_data->orderByRaw('(participants.lname IS NULL), participants.lname ASC')->paginate($this->pagenation_number);
         //  dd($search_data);
            $html = '';
            $offset = ($request->page - 1) * $this->pagenation_number;
        
            foreach ($search_data as $key => $res) {
             

         
           
    




                $html .= '<tr id="row-' . ($offset + $key + 1) . '">';
                
                // Serial Number
                $html .= '<td style="width:2%; text-align:left;">' . ($offset + $key + 1) . '</td>';
            
                // Participant ID
                $html .= '<td style="width:2%;">' . ($res->e_vmcode ?? '') . '</td>';
            
                // PSS Number
                $html .= '<td style="width:2%;">' . ($res->pssno ?? '') . '</td>';
            
                // Employee Name
                if (!empty($res->missionary_id) && empty($res->emp_id) && empty($res->children_id) && empty($res->spouse_id)){
                    $html .= '<td style="width:10%; text-align:left;">' . ($res->m_l_name ?? '') . '</td>';
                    $html .= '<td style="width:10%; text-align:left;">' . ($res->m_f_name ?? '')  . '</td>';
                }
                elseif (!empty($res->children_id)){
                    $html .= '<td style="width:10%; text-align:left;">' . ($res->child_l_name ?? '') . '</td>';
                    $html .= '<td style="width:10%; text-align:left;">' . ($res->child_f_name ?? '')  . '</td>';
                }
                elseif (!empty($res->spouse_id)){
                    $html .= '<td style="width:10%; text-align:left;">' . ($res->spouse_lname ?? '') . '</td>';
                    $html .= '<td style="width:10%; text-align:left;">' . ($res->spouse_fname ?? '')  . '</td>';
                }
                elseif(!empty($res->emp_id)){
                    $html .= '<td style="width:10%; text-align:left;">' . ($res->emp_l_name ?? '') . '</td>';
                    $html .= '<td style="width:10%; text-align:left;">' . ($res->emp_f_name ?? '')  . '</td>';
            
                }

                
                if (!empty($res->missionary_id) && empty($res->emp_id) && empty($res->children_id) && empty($res->spouse_id)){
                    $html .= '<td style="width:10%; text-align:left;">' . 'Missionary' . '</td>';
                }
                elseif (!empty($res->children_id)){
                    $html .= '<td style="width:10%; text-align:left;">' . 'Children' . '</td>';
                }
                elseif (!empty($res->spouse_id)){
                    $html .= '<td style="width:10%; text-align:left;">' . 'Spouse' . '</td>';
                }
                elseif(!empty($res->emp_id)){
                    $html .= '<td style="width:10%; text-align:left;">' . 'Employee' . '</td>';
            
                }
            
                // // Spouse Name
                // $html .= '<td style="width:10%; text-align:left;">' . ($res->spouse_lname ?? '') . ', ' . ($res->spouse_fname ?? '') . '</td>';
            
                // Start Date
                $html .= '<td style="width:10%; text-align:left;">' . (!empty($res->start_date) ? date("m-d-Y", strtotime($res->start_date)) : '') . '</td>';
            
                // Opt-out Date
                $html .= '<td style="width:10%; text-align:left;">' . (!empty($res->opt_out_date) ? date("m-d-Y", strtotime($res->opt_out_date)) : '') . '</td>';
            
                // Opt-out Reason
                $html .= '<td style="width:10%; text-align:left;">' . ($res->opt_out_reason ?? '') . '</td>';
            
                // Term End Date
                // $html .= '<td style="width:10%; text-align:left;">' . 'NA'. '</td>';
                // $html .= '<td style="width:10%; text-align:left;">' . (!empty($res->opt_out_date) ? date("m-d-Y", strtotime($res->opt_out_date)) : '') . '</td>';
                // Notes
                $html .= '<td style="width:10%; text-align:left;">' . ($res->notes ?? '') . '</td>';
            
                $html .= '</tr>';
            }
            if (trim($html) != '') {
                $pagenation = \View::make('include.pagenation', ['paginator' => $search_data])->render();
            } else {
                $pagenation = '';
                $html .= '<tr>';
                $html .= '<td  style="width:100%" class="text-nowrap" colspan="10">';
                $html .= 'No Data Found';
                $html .= '</td>';
            }
            if (!isset($request->page)) {
                $request->page = 1;
            }
            $page = (isset($request->page)) ? $request->page : 1;
            $offset = ($request->page - 1) * $this->pagenation_number;
            $start_page_number = $offset + 1;
            $end_page_number = $search_data->total();
            if ($end_page_number >= ($start_page_number + $this->pagenation_number)) {
                $end_page_number = $offset + $this->pagenation_number;
            }
            return response()->json([
                'message' => 'contributions',
                'html' => $html,
                'pagenation' => $pagenation,
                'start_page_number' => $start_page_number,
                'end_page_number' => $end_page_number,
                'total_records' => $search_data->total(),
                'status' => 1
            ], 200);

        } catch (exception $e) {

        }
    }
    public function optoutvmebpcovdownload(Request $request){
        $data=[];
        $data = getPermissionArray('vmebp_reports');
        $user = Auth::user();
        $data["user"]=$user;
        $csv_data=[];
        $csv_data[]=array('Sl.No', 	'VMCODE', 	'PSS#', 	'Last Name', 	'First Name', 'Relation',	'Enrollment  Date', 	'Opt Out VMEBP Date',
    'Reason','Notes');
     
    //     $search_data =  Employee::leftjoin('childrens', 'childrens.emp_id', '=', 'employee.id')
    //     ->leftJoin('spouses', 'spouses.emp_id', '=', 'employee.id')
    //     ->leftJoin('participants','participants.vmcode','=','employee.vmcode')
    //     ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
    //     ->leftJoin('participant_opt_outs','participant_opt_outs.participants_id','=','participants.id')
    //     ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
    //     ->leftjoin('users', 'users.id', '=', 'childrens.updated_by')
    //     ->leftjoin('users as uc', 'uc.id', '=', 'childrens.created_by')
    //     ->leftjoin('users as un', 'un.id', '=', 'childrens.user_id')
    //     ->orderByRaw('isNULL(childrens.dob) asc, datediff(childrens.dob,now()) asc')
    //     //->orderByRaw('childrens.dob asc')
    //     ->select('users.name as username', 'uc.name as created_username', 'childrens.*','spouses.id as spouse_id','participants.id as participantid','participant_healthcare.pss_no as pssno'
    //     ,'spouses.spouse_fname','spouses.spouse_lname', 'employee.fname as e_f_name'
    //     , 'employee.lname as e_l_name', 'employee.vmcode as e_vmcode','employee.joining_date','employee.termination_date'
    //     ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
    //     ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','participant_opt_outs.opt_out_date',
    //     'participant_opt_outs.term_end_date','participant_opt_outs.opt_out_reason')
    //    ->where('health_coverage_members.id','=',2)
    //    ->whereNotNull('participant_opt_outs.opt_out_date')
    //    ->whereNotNull('employee.termination_date');
        
       
    //    if (isset($request->search_batch) && $request->search_batch != '') {
    //     $search_string = $request->search_batch;
    //     $search_data = $search_data->where(function ($q) use ($search_string) {
    //         $q->where(
    //             DB::raw("CONCAT(
    //                 COALESCE(employee.lname, ''), ' ', COALESCE(employee.fname, '')
    //             )"),
    //             'LIKE',
    //             "%{$search_string}%"
    //         );
    //         $q->orWhere(
    //             DB::raw("CONCAT(
    //                 COALESCE(spouses.spouse_fname, ''), ' ', COALESCE(spouses.spouse_lname, '')
    //             )"),
    //             'LIKE',
    //             "%{$search_string}%"
    //         );
    //     });
    // }

    // if (!empty($request->search_year)) {
    //     $search_data = $search_data->whereRaw("YEAR(participant_opt_outs.opt_out_date) = ?", [$request->search_year])
    //                                ->orWhereRaw("YEAR(participant_opt_outs.term_end_date) = ?", [$request->search_year]);
    // }
        //dd($search_data);
        $search_data = DB::table('participants')
        ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
        ->leftJoin('participant_opt_outs','participant_opt_outs.participants_id','=','participants.id')
        ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
        ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
        ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
        ->leftJoin('employee','employee.id','=','participants.emp_id')
        ->leftJoin('childrens','childrens.id','=','participants.children_id')
        ->leftJoin('spouses','spouses.id','=','participants.spouse_id')
        ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
        , 'participants.fname as e_f_name'
        , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
        ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
        ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','participant_opt_outs.opt_in_date','participant_opt_outs.opt_out_date','relation_code.name as relation_name','participant_healthcare.group_type_id',
        'participants.term_end_date','participant_opt_outs.opt_out_reason','missionaries.lname as m_l_name','missionaries.fname as m_f_name',
        'employee.lname as emp_l_name','employee.fname as emp_f_name','childrens.lname as child_l_name','childrens.fname as child_f_name','spouses.spouse_fname','spouses.spouse_lname',
        'participants.missionary_id','participants.emp_id','participants.spouse_id','participants.children_id')
        ->whereNotNull('participant_opt_outs.opt_out_date')
        ->whereNotNull('participant_healthcare.status');

            if (isset($request->search_batch) && $request->search_batch != '') {
                $search_string = $request->search_batch;
                $search_data = $search_data->where(function ($q) use ($search_string) {
                    $q->where(
                        DB::raw("CONCAT(
                            COALESCE(participants.lname, ''), ' ', COALESCE(participants.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(spouses.spouse_fname, ''), ' ', COALESCE(spouses.spouse_lname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(childrens.lname, ''), ' ', COALESCE(childrens.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(missionaries.lname, ''), ' ', COALESCE(missionaries.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(employee.lname, ''), ' ', COALESCE(employee.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                });
            }

            if (!empty($request->search_year)) {
                $search_data = $search_data->whereRaw("YEAR(participants.opt_out_date) = ?", [$request->search_year])
                                           ->orWhereRaw("YEAR(participants.term_end_date) = ?", [$request->search_year]);
            }
        $search_data = $search_data->orderByRaw('(participants.lname IS NULL), participants.lname ASC')->get();
      // dd($search_data);

      foreach ($search_data as $key => $val) {
        $tmp_arr = [];

        $tmp_arr[0] = $key + 1; // Sl.No
        $tmp_arr[1] = $val->e_vmcode ?? ''; // Participant #
        $tmp_arr[2] = $val->pssno ?? ''; // PSS#
    
        // Employee/Family Member Name Based on Relation
        if (!empty($val->missionary_id) && empty($val->emp_id) && empty($val->children_id) && empty($val->spouse_id)) {
            $tmp_arr[3] = $val->m_l_name ?? '';
            $tmp_arr[4] = $val->m_f_name ?? '';
            $tmp_arr[5] = 'Missionary';
        } elseif (!empty($val->children_id)) {
            $tmp_arr[3] = $val->child_l_name ?? '';
            $tmp_arr[4] = $val->child_f_name ?? '';
            $tmp_arr[5] = 'Children';
        } elseif (!empty($val->spouse_id)) {
            $tmp_arr[3] = $val->spouse_lname ?? '';
            $tmp_arr[4] = $val->spouse_fname ?? '';
            $tmp_arr[5] = 'Spouse';
        } elseif (!empty($val->emp_id)) {
            $tmp_arr[3] = $val->e_f_name ?? '';
            $tmp_arr[4] = $val->e_l_name ?? '';
            $tmp_arr[5] = 'Employee';
        } else {
            $tmp_arr[3] = '';
            $tmp_arr[4] = '';
            $tmp_arr[5] = '';
        }
        
        // Start Date
        $tmp_arr[6] = (!empty($val->start_date) && strtotime($val->start_date)) ? date("m-d-Y", strtotime($val->start_date)) : '';
        
        // Opt Out Date
        $tmp_arr[7] = (!empty($val->opt_out_date) && strtotime($val->opt_out_date)) ? date("m-d-Y", strtotime($val->opt_out_date)) : '';
        
        // Opt Out Reason
        $tmp_arr[8] = $val->opt_out_reason ?? '';
        
        // // Termination Date → 'NA' per Blade logic
        // $tmp_arr[9] = 'NA';
        
        // // Opt Out VMEBP Date (used instead of termination date)
        // $tmp_arr[10] = (!empty($val->opt_out_date) && strtotime($val->opt_out_date)) ? date("m-d-Y", strtotime($val->opt_out_date)) : '';
        
        // Notes
        $tmp_arr[9] = $val->notes ?? '';
    
        $csv_data[] = $tmp_arr; 
    }
    
        csvDownlaod($csv_data,"optoutvmebpgroupcoverage.csv ");   
    }
    public function usretiredassocdobanniversary(Request $request){
        try{
            $data=[];
            $data=getPermissionArray('vmebp_reports');//get permission for each module if its add in module table
            $user = Auth::user();
            $data["user"]=$user;
            $missionary_employee_type='missionary';
            if($data["permission_array"]["vmebp_reports"]["no_view"]==1){
            $data["menu"]="vmebp_reports";
            $query_string=[];
            $data["query_string"]='';
            $data["search_filter"]='';
            $data["search_batch"]='';
            $data["status_filter"]='';
            if(!isset($request->page)){
                $request->page=1;
            }
            $data["page"]=(isset($request->page))?$request->page:1;
            $data["offset"]=($request->page-1)*$this->pagenation_number;
             $data["results"] = missionary::
             //leftJoin('missionaries','missionaries.vmcode','=','employee.vmcode')
             leftJoin('participants','participants.vmcode','=','missionaries.vmcode')
             ->leftJoin('participant_healthcare','missionaries.vmcode','=','participants.vmcode')
             ->leftJoin('missionaries_imp_dates','missionaries_imp_dates.missionary_id','=','missionaries.id')
             ->leftJoin('spouses', 'spouses.missionary_id', '=', 'missionaries.id')
             ->leftjoin('users', 'users.id', '=', 'missionaries.updated_by')
             ->leftjoin('users as uc', 'uc.id', '=', 'missionaries.created_by')
            // ->leftjoin('users as un', 'un.id', '=', 'missionaries.user_id')
            //  ->orderByRaw('isNULL(missionaries.dob) asc, datediff(missionaries.dob,now()) asc')
             //->orderByRaw('childrens.dob asc')
             ->select('missionaries.fname','missionaries.lname','missionaries.vmcode','missionaries.dob','missionaries.is_deceased','missionaries.deceased_date','missionaries.retired_date','participant_healthcare.pss_no as pssno','participants.id as participatsid','missionaries.missionary_type','spouses.spouse_fname','spouses.spouse_lname','spouses.spouse_dob','spouses.spouse_is_deceased','spouses.spouse_deceased_date','missionaries_imp_dates.date_type','missionaries_imp_dates.imp_date')
          
          //   ->where('missionaries.missionary_type', '=' ,'Associate')
             ->where('missionaries.retired_date', '!=' ,'')

             
          //   ->whereRaw('timestampdiff(year, childrens.dob, curdate()) <= 17')
             ->groupBy('missionaries.id')
             ->orderByRaw("COALESCE(NULLIF(missionaries.lname, ''), missionaries.fname) ASC")
             ->paginate($this->pagenation_number);
          // dd($data["results"]);
           ///print_r($data["results"]);die;
           $data["per_page"]=$this->pagenation_number;
           $data["data"]= $data["results"];
           $data["missionary_employee_type"]= $missionary_employee_type;
            return view('all_vmebp_reports.usretiredassocdobanniversary',["data"=>$data])->with('count', 1);;
        }else{
            return Redirect::to('dashboard')->with('error', "Access denied .");
        } 
        }catch(exception $e){
                
        }

    }

    public function usretiredassocdobanniversarymissionariesajax(Request $request){
        $missionary_employee_type=$request->missionary_employee_type??'';
        if(!empty($missionary_employee_type))
        {
            if($missionary_employee_type == 'missionary'){

           
            $missionaries = DB::table('missionaries')->select('id', DB::raw("CONCAT(lname, ', ', fname, ' [', vmcode, ']') as name"))
            ->orderByRaw("COALESCE(NULLIF(missionaries.lname, ''), missionaries.fname) ASC")
            ->whereNotNull('retired_date')->get();

            return response()->json([
                'status' => 'success',
                'data' => $missionaries??[]
            ]);

        }

        if($missionary_employee_type == 'employee'){

            $employees = DB::table('employee')->select('id', DB::raw("CONCAT(lname, ', ', fname, ' [', vmcode, ']') as name"))
            ->orderByRaw("COALESCE(NULLIF(employee.lname, ''), employee.fname) ASC")
            ->get();


            return response()->json([
                'status' => 'success',
                'data' => $employees
            ]);
        }

        }
  
    }
    public function usretiredassocdobanniversarysearch(Request $request){
        $missionary_employee=$request->missionary_employee??'missionary';
        $dobanniversary=false;
        if($missionary_employee=='missionary'){
            
            $missionary_employee_populate=$request->missionary_employee_populate??'';
            try {
                $data = [];
                $data = getPermissionArray('all-reports');
                $user = Auth::user();
                $data["user"] = $user;
                $search_data =  missionary::
                //leftJoin('missionaries','missionaries.vmcode','=','employee.vmcode')
                leftJoin('participants','participants.vmcode','=','missionaries.vmcode')
                ->leftJoin('participant_healthcare','missionaries.vmcode','=','participants.vmcode')
                ->leftJoin('missionaries_imp_dates','missionaries_imp_dates.missionary_id','=','missionaries.id')
                ->leftJoin('spouses', 'spouses.missionary_id', '=', 'missionaries.id')
                ->leftjoin('users', 'users.id', '=', 'missionaries.updated_by')
                ->leftjoin('users as uc', 'uc.id', '=', 'missionaries.created_by')
               // ->leftjoin('users as un', 'un.id', '=', 'missionaries.user_id')
                // ->orderByRaw('isNULL(missionaries.dob) asc, datediff(missionaries.dob,now()) asc')
                //->orderByRaw('childrens.dob asc')
            
                ->select('missionaries.id as missionary_id','missionaries.fname','missionaries.lname','missionaries.vmcode','missionaries.dob','missionaries.is_deceased','missionaries.deceased_date','missionaries.retired_date','participant_healthcare.pss_no as pssno','participants.id as participatsid','missionaries.missionary_type','spouses.spouse_fname','spouses.spouse_lname','spouses.spouse_dob','spouses.spouse_is_deceased','spouses.spouse_deceased_date','missionaries_imp_dates.date_type','missionaries_imp_dates.imp_date')
             
              //  ->where('missionaries.missionary_type', '=' ,'Associate')
                ->where('missionaries.retired_date', '!=' ,'');
               
               
      
                if (!empty($request->missionary_employee_populate)) {
                    $missionary_employee_populate = $request->missionary_employee_populate; // your input is actually month number (1 to 12)
                
                    $search_data = $search_data->where(function ($q) use ($missionary_employee_populate) {
                        $q->where("missionaries.id", $missionary_employee_populate);
                    });
                }
                if (!empty($request->retired_on)) {
                    $selectedMonth = $request->retired_on; // your input is actually month number (1 to 12)
                
                    $search_data = $search_data->where(function ($q) use ($selectedMonth) {
                        $q->whereRaw("MONTH(missionaries.retired_date) = ?", [$selectedMonth]);
                    });
                }
                // if (!empty($request->search_year)) {
                //     $selectedMonth = $request->search_year; // your input is actually month number (1 to 12)
                
                //     $search_data = $search_data->where(function ($q) use ($selectedMonth) {
                //         $q->whereRaw("MONTH(missionaries.dob) = ?", [$selectedMonth])
                //           ->orWhereRaw("MONTH(missionaries_imp_dates.imp_date) = ?", [$selectedMonth]);
                //     });
                // }
                
               
              
              
             
               //$search_data =  $search_data->groupBy('missionaries.id')->toSql();
               //dd($search_data);
              // $search_data =  $search_data->groupBy('missionaries.id')->paginate($this->pagenation_number);
               $search_data =  $search_data
            //    ->where('missionaries.missionary_type', '=' ,'Associate')
            //    ->orWhere('missionaries.retired_date', '!=' ,'')
    
               
            //   ->whereRaw('timestampdiff(year, childrens.dob, curdate()) <= 17')
               ->groupBy('missionaries.id')
               ->orderByRaw("COALESCE(NULLIF(missionaries.lname, ''), missionaries.fname) ASC")
               ->paginate($this->pagenation_number);
              
                $html = '';
                $fetchanniversarydate='';
                $offset = ($request->page - 1) * $this->pagenation_number;
                if(!empty($request->search_year)){
                $dobtype=$request->search_year;
                }
                foreach ($search_data as $key => $res) {
                    $missionaryfullName='';
                    $spouseFullName = '';
                    if(!empty($res->lname) && !empty($res->fname)){
                        $missionaryfullName= $res->lname .','.  $res->fname ;
                    }
                    
                    elseif(!empty($res->lname)){
                        $missionaryfullName= $res->lname ;
                    }
                    
                    elseif(!empty($res->fname)){
                        $missionaryfullName= $res->fname ;
                    }



                 

                    if (!empty($res->spouse_lname) && !empty($res->spouse_fname)) {
                        $spouseFullName = $res->spouse_lname . ', ' . $res->spouse_fname;
                    } elseif (!empty($res->spouse_lname)) {
                        $spouseFullName = $res->spouse_lname;
                    } elseif (!empty($res->spouse_fname)) {
                        $spouseFullName = $res->spouse_fname;
                    }
                    
 
                    $html .= '<tr id="row-">';
                    $html .= '<td  style="width:2%;;text-align:left;" >';
                    $html .= $offset + $key + 1;
                    $html .= '</td>';
                    
                    $html .= '<td style="width:10%">';
                    $html .= $missionaryfullName??'';
                    $html .= '</td>';

                    $html .= '<td style="width:2%">';
                    $html .= $res->vmcode??'';
                    $html .= '</td>';
                    // if($dobtype=='Anniversary'){
                    
                    //     //  dd($dobanniversary);
                    //  if(  !empty($res->date_type) && $res->date_type == 'Anniversary')
                    //                      {
                    //                          $html .= '<td style="width:5%;text-align:left">';
                    //                          $html .= date('m-d-Y',strtotime($res->imp_date)) ;
                    //                          $html .= '</td>';
                    //                      }
                        
                    //      else
                    //      {
                    //           $html .= ' <td style="width:5%;text-align:left">NA</td>';
                    //      }
                    //      }
                    if ($request->search_year == 'Anniversary') {
                        // Fetch anniversary date
                        $fetchAnniversaryDate = DB::table('missionaries_imp_dates')
                            ->where('missionary_id', $res->missionary_id)
                            ->where('date_type', 'Anniversary')
                            ->first();
                    
                        $anniversaryDate = 'NA';
                        if (!empty($fetchAnniversaryDate) && !empty($fetchAnniversaryDate->imp_date)) {
                            $anniversaryDate = date('M, Y', strtotime($fetchAnniversaryDate->imp_date));
                        }
                    
                        $html .= '<td style="width:5%;text-align:left">';
                        $html .= $anniversaryDate;
                        $html .= '</td>';
                    }
                    
                  else{
                    if($res->dob != '' )
                    {
                        $html .= '<td style="width:5%;text-align:left">';
                        $html .= date('M, Y',strtotime($res->dob));
                        $html .= '</td>';
                    }
                   
                    else
                    {
                        $html .= '<td style="width:10%;text-align:left">';
                        $html .= '';
                        $html .= '</td>';
    
                    }
                  }
                   
                    if($res->is_deceased == 1)
                    {
                        $html .= '<td style="width:10%;text-align:left">';
                        $html .= 'YES';
                        $html .= '</td>';
                    }
                    else{
                        $html .= '<td style="width:10%;text-align:left">';
                        $html .= 'NO';
                        $html .= '</td>';
                    }
                    $html .= '<td style="width:10%">';
                    $html .= $spouseFullName??'';
                    $html .= '</td>';
                    if($res->spouse_dob != '')
                    {
                        $html .= '<td style="width:5%;text-align:left">';
                        $html .= date('M, Y',strtotime($res->spouse_dob));
                        $html .= '</td>';
                    }
                   
                    else
                    {
                        $html .= '<td style="width:5%;text-align:left">';
                        $html .= '';
                        $html .= '</td>';
    
                    }
                    if($res->spouse_is_deceased == 1)
                    {
                        $html .= '<td style="width:5%;text-align:left">';
                        $html .= 'YES';
                        $html .= '</td>';
                    }
                    else{
                        $html .= '<td style="width:5%;text-align:left">';
                        $html .= 'NO';
                        $html .= '</td>';
                    }
          
             
                    // if(!empty($request->searchyear)){
                  
                    // if(  !empty($res->date_type) && $res->date_type == 'Anniversary')
                    // {
                    //     $html .= '<td style="width:5%;text-align:left">';
                    //     $html .= date('m-d-Y',strtotime($res->imp_date)) ;
                    //     $html .= '</td>';
                    // }
                   
                    // else
                    // {
                    //      $html .= ' <td style="width:5%;text-align:left">NA</td>';
                    // }
                //    }
                //    else{

                //    }
                                               
              
                   
                    $html .= '</tr>';
                }
                if (trim($html) != '') {
                    $pagenation = \View::make('include.pagenation', ['paginator' => $search_data])->render();
                } else {
                    $pagenation = '';
                    $html .= '<tr>';
                    $html .= '<td  style="width:100%" class="text-nowrap" colspan="10">';
                    $html .= 'No Data Found';
                    $html .= '</td>';
                }
                if (!isset($request->page)) {
                    $request->page = 1;
                }
                $page = (isset($request->page)) ? $request->page : 1;
                $offset = ($request->page - 1) * $this->pagenation_number;
                $start_page_number = $offset + 1;
                $end_page_number = $search_data->total();
                if ($end_page_number >= ($start_page_number + $this->pagenation_number)) {
                    $end_page_number = $offset + $this->pagenation_number;
                }
                return response()->json([
                    'message' => 'contributions',
                    'html' => $html,
                    'pagenation' => $pagenation,
                    'start_page_number' => $start_page_number,
                    'end_page_number' => $end_page_number,
                    'total_records' => $search_data->total(),
                    'status' => 1
                ], 200);
    
            } catch (exception $e) {
    
            }
        }
        else{
            $missionary_employee_populate=$request->missionary_employee_populate??'';
         
            try {
                $data = [];
                $data = getPermissionArray('all-reports');
                $user = Auth::user();
                $data["user"] = $user;
                $search_data =  employee::
                //leftJoin('missionaries','missionaries.vmcode','=','employee.vmcode')
                 leftJoin('participants','participants.vmcode','=','employee.vmcode')
                ->leftJoin('participant_healthcare','employee.vmcode','=','employee.vmcode')
              //  ->leftJoin('missionaries_imp_dates','missionaries_imp_dates.missionary_id','=','missionaries.id')
                ->leftJoin('spouses', 'spouses.emp_id', '=', 'employee.id')
                ->leftjoin('users', 'users.id', '=', 'employee.updated_by')
                ->leftjoin('users as uc', 'uc.id', '=', 'employee.created_by')
               // ->leftjoin('users as un', 'un.id', '=', 'missionaries.user_id')
          
                //->orderByRaw('childrens.dob asc')
                ->select('employee.id as missionary_id','employee.fname','employee.lname','employee.vmcode','employee.dob','employee.is_deceased','employee.deceased_date','employee.retired_date','participant_healthcare.pss_no as pssno','participants.id as participatsid','spouses.spouse_fname','spouses.spouse_lname','spouses.spouse_dob','spouses.spouse_is_deceased','spouses.spouse_deceased_date')
             
              //  ->where('missionaries.missionary_type', '=' ,'Associate')
                ->where('employee.retired_date', '!=' ,'');
               
               
      
                if (!empty($request->missionary_employee_populate)) {
                    $missionary_employee_populate = $request->missionary_employee_populate; // your input is actually month number (1 to 12)
                
                    $search_data = $search_data->where(function ($q) use ($missionary_employee_populate) {
                        $q->where("employee.id", $missionary_employee_populate);
                    });
                }
                if (!empty($request->retired_on)) {
                    $selectedMonth = $request->retired_on; // your input is actually month number (1 to 12)
                
                    $search_data = $search_data->where(function ($q) use ($selectedMonth) {
                        $q->whereRaw("MONTH(employee.retired_date) = ?", [$selectedMonth]);
                    });
                }
                // if (!empty($request->search_year)) {
                //     $selectedMonth = $request->search_year; // your input is actually month number (1 to 12)
                
                //     $search_data = $search_data->where(function ($q) use ($selectedMonth) {
                //         $q->whereRaw("MONTH(missionaries.dob) = ?", [$selectedMonth])
                //           ->orWhereRaw("MONTH(missionaries_imp_dates.imp_date) = ?", [$selectedMonth]);
                //     });
                // }
                
               
              
              
             
               //$search_data =  $search_data->groupBy('missionaries.id')->toSql();
               //dd($search_data);
              // $search_data =  $search_data->groupBy('missionaries.id')->paginate($this->pagenation_number);
               $search_data =  $search_data
            //    ->where('missionaries.missionary_type', '=' ,'Associate')
            //    ->orWhere('missionaries.retired_date', '!=' ,'')
    
               
            //   ->whereRaw('timestampdiff(year, childrens.dob, curdate()) <= 17')
               ->groupBy('employee.id')
               ->orderByRaw("COALESCE(NULLIF(employee.lname, ''), employee.fname) ASC")
               ->paginate($this->pagenation_number);
          
                $html = '';
                $offset = ($request->page - 1) * $this->pagenation_number;
                if(!empty($request->search_year)){
                $dobanniversary=true;
                }
                 foreach ($search_data as $key => $res) {
                    $missionaryfullName='';
                    $spouseFullName = '';
                    if(!empty($res->lname) && !empty($res->fname)){
                        $missionaryfullName= $res->lname .','.  $res->fname ;
                    }
                    
                    elseif(!empty($res->lname)){
                        $missionaryfullName= $res->lname ;
                    }
                    
                    elseif(!empty($res->fname)){
                        $missionaryfullName= $res->fname ;
                    }



                 

                    if (!empty($res->spouse_lname) && !empty($res->spouse_fname)) {
                        $spouseFullName = $res->spouse_lname . ', ' . $res->spouse_fname;
                    } elseif (!empty($res->spouse_lname)) {
                        $spouseFullName = $res->spouse_lname;
                    } elseif (!empty($res->spouse_fname)) {
                        $spouseFullName = $res->spouse_fname;
                    }
                    
 
                    $html .= '<tr id="row-">';
                    $html .= '<td  style="width:2%;;text-align:left;" >';
                    $html .= $offset + $key + 1;
                    $html .= '</td>';
                    
                    $html .= '<td style="width:10%">';
                    $html .= $missionaryfullName??'';
                    $html .= '</td>';

                    $html .= '<td style="width:2%">';
                    $html .= $res->vmcode??'';
                    $html .= '</td>';
                    // if($dobtype=='Anniversary'){
                    
                    //     //  dd($dobanniversary);
                    //  if(  !empty($res->date_type) && $res->date_type == 'Anniversary')
                    //                      {
                    //                          $html .= '<td style="width:5%;text-align:left">';
                    //                          $html .= date('m-d-Y',strtotime($res->imp_date)) ;
                    //                          $html .= '</td>';
                    //                      }
                        
                    //      else
                    //      {
                    //           $html .= ' <td style="width:5%;text-align:left">NA</td>';
                    //      }
                    //      }
                    if ($request->search_year == 'Anniversary') {
                        // Fetch anniversary date
                        $fetchAnniversaryDate = DB::table('missionaries_imp_dates')
                            ->where('missionary_id', $res->missionary_id)
                            ->where('date_type', 'Anniversary')
                            ->first();
                    
                        $anniversaryDate = 'NA';
                        if (!empty($fetchAnniversaryDate) && !empty($fetchAnniversaryDate->imp_date)) {
                            $anniversaryDate = date('M, Y', strtotime($fetchAnniversaryDate->imp_date));
                        }
                    
                        $html .= '<td style="width:5%;text-align:left">';
                        $html .= $anniversaryDate;
                        $html .= '</td>';
                    }
                  else{
                    if($res->dob != '' )
                    {
                        $html .= '<td style="width:5%;text-align:left">';
                        $html .= date('M, Y',strtotime($res->dob));
                        $html .= '</td>';
                    }
                   
                    else
                    {
                        $html .= '<td style="width:10%;text-align:left">';
                        $html .= '';
                        $html .= '</td>';
    
                    }
                  }
                   
                    if($res->is_deceased == 1)
                    {
                        $html .= '<td style="width:10%;text-align:left">';
                        $html .= 'YES';
                        $html .= '</td>';
                    }
                    else{
                        $html .= '<td style="width:10%;text-align:left">';
                        $html .= 'NO';
                        $html .= '</td>';
                    }
                    $html .= '<td style="width:10%">';
                    $html .= $spouseFullName??'';
                    $html .= '</td>';
                    if($res->spouse_dob != '')
                    {
                        $html .= '<td style="width:5%;text-align:left">';
                        $html .= date('M, Y',strtotime($res->spouse_dob));
                        $html .= '</td>';
                    }
                   
                    else
                    {
                        $html .= '<td style="width:5%;text-align:left">';
                        $html .= '';
                        $html .= '</td>';
    
                    }
                    if($res->spouse_is_deceased == 1)
                    {
                        $html .= '<td style="width:5%;text-align:left">';
                        $html .= 'YES';
                        $html .= '</td>';
                    }
                    else{
                        $html .= '<td style="width:5%;text-align:left">';
                        $html .= 'NO';
                        $html .= '</td>';
                    }
          
             
                    // if(!empty($request->searchyear)){
                  
                    // if(  !empty($res->date_type) && $res->date_type == 'Anniversary')
                    // {
                    //     $html .= '<td style="width:5%;text-align:left">';
                    //     $html .= date('m-d-Y',strtotime($res->imp_date)) ;
                    //     $html .= '</td>';
                    // }
                   
                    // else
                    // {
                    //      $html .= ' <td style="width:5%;text-align:left">NA</td>';
                    // }
                //    }
                //    else{

                //    }
                                               
              
                   
                    $html .= '</tr>';
                }
                if (trim($html) != '') {
                    $pagenation = \View::make('include.pagenation', ['paginator' => $search_data])->render();
                } else {
                    $pagenation = '';
                    $html .= '<tr>';
                    $html .= '<td  style="width:100%" class="text-nowrap" colspan="10">';
                    $html .= 'No Data Found';
                    $html .= '</td>';
                }
                if (!isset($request->page)) {
                    $request->page = 1;
                }
                $page = (isset($request->page)) ? $request->page : 1;
                $offset = ($request->page - 1) * $this->pagenation_number;
                $start_page_number = $offset + 1;
                $end_page_number = $search_data->total();
                if ($end_page_number >= ($start_page_number + $this->pagenation_number)) {
                    $end_page_number = $offset + $this->pagenation_number;
                }
                return response()->json([
                    'message' => 'contributions',
                    'html' => $html,
                    'pagenation' => $pagenation,
                    'start_page_number' => $start_page_number,
                    'end_page_number' => $end_page_number,
                    'total_records' => $search_data->total(),
                    'status' => 1
                ], 200);
    
            } catch (exception $e) {
    
            }  
        }

    }
    public function usretiredassocdobanniversarydownload(Request $request){
            $missionary_employee=$request->missionary_employee??'missionary';
        $isAnniversary=0;
          if($request->search_year=="Anniversary"){
      $isAnniversary=1;
     }
     else{
     $isAnniversary=0;
     }
        if($missionary_employee=='missionary'){
        $data=[];
        $data = getPermissionArray('all-reports');
        $user = Auth::user();
        $data["user"]=$user;
        $csv_data=[];
        
     if($isAnniversary==1){
$csv_data[]=array('Sl.No', 	'Missionary/Employee Name','VMCODE', 	'Anniversary', 	'Deceased?' 	,'Missionary/Employee Spouse', 	'Spouse DOB', 	'Spouse Deceased?');
     }
     else{
$csv_data[]=array('Sl.No', 	'Missionary/Employee Name','VMCODE', 	'DOB', 	'Deceased?' 	,'Missionary/Employee Spouse', 	'Spouse DOB', 	'Spouse Deceased?');
     }
        
     
        $search_data =  missionary::
                //leftJoin('missionaries','missionaries.vmcode','=','employee.vmcode')
                leftJoin('participants','participants.vmcode','=','missionaries.vmcode')
                ->leftJoin('participant_healthcare','missionaries.vmcode','=','participants.vmcode')
                ->leftJoin('missionaries_imp_dates','missionaries_imp_dates.missionary_id','=','missionaries.id')
                ->leftJoin('spouses', 'spouses.missionary_id', '=', 'missionaries.id')
                ->leftjoin('users', 'users.id', '=', 'missionaries.updated_by')
                ->leftjoin('users as uc', 'uc.id', '=', 'missionaries.created_by')
               // ->leftjoin('users as un', 'un.id', '=', 'missionaries.user_id')
                // ->orderByRaw('isNULL(missionaries.dob) asc, datediff(missionaries.dob,now()) asc')
                //->orderByRaw('childrens.dob asc')
            
                ->select('missionaries.id as missionary_id','missionaries.fname','missionaries.lname','missionaries.vmcode','missionaries.dob','missionaries.is_deceased','missionaries.deceased_date','missionaries.retired_date','participant_healthcare.pss_no as pssno','participants.id as participatsid','missionaries.missionary_type','spouses.spouse_fname','spouses.spouse_lname','spouses.spouse_dob','spouses.spouse_is_deceased','spouses.spouse_deceased_date','missionaries_imp_dates.date_type','missionaries_imp_dates.imp_date')
             
              //  ->where('missionaries.missionary_type', '=' ,'Associate')
                ->where('missionaries.retired_date', '!=' ,'');
               
               
      
                if (!empty($request->missionary_employee_populate)) {
                    $missionary_employee_populate = $request->missionary_employee_populate; // your input is actually month number (1 to 12)
                
                    $search_data = $search_data->where(function ($q) use ($missionary_employee_populate) {
                        $q->where("missionaries.id", $missionary_employee_populate);
                    });
                }
                if (!empty($request->retired_on)) {
                    $selectedMonth = $request->retired_on; // your input is actually month number (1 to 12)
                
                    $search_data = $search_data->where(function ($q) use ($selectedMonth) {
                        $q->whereRaw("MONTH(missionaries.retired_date) = ?", [$selectedMonth]);
                    });
                }
                // if (!empty($request->search_year)) {
                //     $selectedMonth = $request->search_year; // your input is actually month number (1 to 12)
                
                //     $search_data = $search_data->where(function ($q) use ($selectedMonth) {
                //         $q->whereRaw("MONTH(missionaries.dob) = ?", [$selectedMonth])
                //           ->orWhereRaw("MONTH(missionaries_imp_dates.imp_date) = ?", [$selectedMonth]);
                //     });
                // }
                
               
              
              
             
               //$search_data =  $search_data->groupBy('missionaries.id')->toSql();
               //dd($search_data);
              // $search_data =  $search_data->groupBy('missionaries.id')->paginate($this->pagenation_number);
               $search_data =  $search_data
            //    ->where('missionaries.missionary_type', '=' ,'Associate')
            //    ->orWhere('missionaries.retired_date', '!=' ,'')
    
               
            //   ->whereRaw('timestampdiff(year, childrens.dob, curdate()) <= 17')
               ->groupBy('missionaries.id')
               ->orderByRaw("COALESCE(NULLIF(missionaries.lname, ''), missionaries.fname) ASC")
               ->get();
              

 
       if($isAnniversary==1){
                 
        foreach($search_data as $key=>$res){
            $tmp_arr=[];
            $tmp_arr[0]=$key + 1;
              if (!empty($res->lname) && !empty($res->fname)) {
           $tmp_arr[1] = $res->lname . ', ' . $res->fname;
            } elseif (!empty($res->lname)) {
                $tmp_arr[1] = $res->lname;
            } elseif (!empty($res->fname)) {
                $tmp_arr[1] = $res->fname;
            } else {
                $tmp_arr[1] = '';
            }
             $tmp_arr[2] = $res->vmcode ?? '';

    if (!empty($res->date_type) && $res->date_type == 'Anniversary') {
        $tmp_arr[3] = date("M, Y", strtotime($res->imp_date));
    } else {
        $tmp_arr[3] = 'NA';
    }

    if ($isAnniversary==1) {
        // Fetch anniversary date
        $fetchAnniversaryDate = DB::table('missionaries_imp_dates')
            ->where('missionary_id', $res->missionary_id)
            ->where('date_type', 'Anniversary')
            ->first();
    
        $anniversaryDate = 'NA';
        if (!empty($fetchAnniversaryDate) && !empty($fetchAnniversaryDate->imp_date)) {
            $tmp_arr[3] = date("M, Y", strtotime($res->imp_date));
        }
        else{
            $tmp_arr[3] = 'NA';
        }

    }
    $tmp_arr[4] = ($res->is_deceased == 1) ? 'YES' : 'NO';

      if (!empty($res->spouse_lname) && !empty($res->spouse_fname)) {
           $tmp_arr[5] = $res->spouse_lname . ', ' . $res->spouse_fname;
            } elseif (!empty($res->spouse_lname)) {
                $tmp_arr[5] = $res->spouse_lname;
            } elseif (!empty($res->spouse_fname)) {
                $tmp_arr[5] = $res->spouse_fname;
            } else {
                $tmp_arr[5] = '';
            }

    if (!empty($res->spouse_dob) && strtotime($res->spouse_dob)) {
        $tmp_arr[6] = date("M, Y", strtotime($res->spouse_dob));
    } else {
        $tmp_arr[6] = '';
    }

    $tmp_arr[7] = ($res->spouse_is_deceased == 1) ? 'YES' : 'NO';

  
 
            
            $csv_data[]= $tmp_arr;
        }
    
        csvDownlaod($csv_data,"usretiredassocdobanniversarydownload.csv");   
//    }
    }
    else{
          foreach($search_data as $key=>$res){
            $tmp_arr=[];
            $tmp_arr[0]=$key + 1;

           if (!empty($res->lname) && !empty($res->fname)) {
           $tmp_arr[1] = $res->lname . ', ' . $res->fname;
            } elseif (!empty($res->lname)) {
                $tmp_arr[1] = $res->lname;
            } elseif (!empty($res->fname)) {
                $tmp_arr[1] = $res->fname;
            } else {
                $tmp_arr[1] = '';
            }
    $tmp_arr[2] = $res->vmcode ?? '';

    if (!empty($res->dob) && strtotime($res->dob)) {
        $tmp_arr[3] = date("M, Y", strtotime($res->dob));
    } else {
        $tmp_arr[3] = '';
    }

    $tmp_arr[4] = ($res->is_deceased == 1) ? 'YES' : 'NO';

        if (!empty($res->spouse_lname) && !empty($res->spouse_fname)) {
           $tmp_arr[5] = $res->spouse_lname . ', ' . $res->spouse_fname;
            } elseif (!empty($res->spouse_lname)) {
                $tmp_arr[5] = $res->spouse_lname;
            } elseif (!empty($res->spouse_fname)) {
                $tmp_arr[5] = $res->spouse_fname;
            } else {
                $tmp_arr[5] = '';
            }

    if (!empty($res->spouse_dob) && strtotime($res->spouse_dob)) {
        $tmp_arr[6] = date("M, Y", strtotime($res->spouse_dob));
    } else {
        $tmp_arr[6] = '';
    }

    $tmp_arr[7] = ($res->spouse_is_deceased == 1) ? 'YES' : 'NO';

  
 
            
            $csv_data[]= $tmp_arr;
        }
    
        csvDownlaod($csv_data,"usretiredassocdobanniversarydownload.csv");   
    }
    }
    else{
 $data=[];
        $data = getPermissionArray('all-reports');
        $user = Auth::user();
        $data["user"]=$user;
        $csv_data=[];
        

       if($isAnniversary==1){
$csv_data[]=array('Sl.No', 	'Missionary/Employee Name','VMCODE', 	'Anniversary', 	'Deceased?' 	,'Missionary/Employee Spouse', 	'Spouse DOB', 	'Spouse Deceased?');
     }
     else{
$csv_data[]=array('Sl.No', 	'Missionary/Employee Name','VMCODE', 	'DOB', 	'Deceased?' 	,'Missionary/Employee Spouse', 	'Spouse DOB', 	'Spouse Deceased?');
     }
     
         $missionary_employee_populate=$request->missionary_employee_populate??'';
         
          //  try {
                $data = [];
                $data = getPermissionArray('all-reports');
                $user = Auth::user();
                $data["user"] = $user;
                $search_data =  employee::
                //leftJoin('missionaries','missionaries.vmcode','=','employee.vmcode')
                 leftJoin('participants','participants.vmcode','=','employee.vmcode')
                ->leftJoin('participant_healthcare','employee.vmcode','=','employee.vmcode')
              //  ->leftJoin('missionaries_imp_dates','missionaries_imp_dates.missionary_id','=','missionaries.id')
                ->leftJoin('spouses', 'spouses.emp_id', '=', 'employee.id')
                ->leftjoin('users', 'users.id', '=', 'employee.updated_by')
                ->leftjoin('users as uc', 'uc.id', '=', 'employee.created_by')
               // ->leftjoin('users as un', 'un.id', '=', 'missionaries.user_id')
          
                //->orderByRaw('childrens.dob asc')
                ->select('employee.id as missionary_id','employee.fname','employee.lname','employee.vmcode','employee.dob','employee.is_deceased','employee.deceased_date','employee.retired_date','participant_healthcare.pss_no as pssno','participants.id as participatsid','spouses.spouse_fname','spouses.spouse_lname','spouses.spouse_dob','spouses.spouse_is_deceased','spouses.spouse_deceased_date');
             
              //  ->where('missionaries.missionary_type', '=' ,'Associate')
               // ->where('employee.retired_date', '!=' ,'');
               
               
      
                if (!empty($request->missionary_employee_populate)) {
                    $missionary_employee_populate = $request->missionary_employee_populate; // your input is actually month number (1 to 12)
                
                    $search_data = $search_data->where(function ($q) use ($missionary_employee_populate) {
                        $q->where("employee.id", $missionary_employee_populate);
                    });
                }
                if (!empty($request->retired_on)) {
                    $selectedMonth = $request->retired_on; // your input is actually month number (1 to 12)
                
                    $search_data = $search_data->where(function ($q) use ($selectedMonth) {
                        $q->whereRaw("MONTH(employee.retired_date) = ?", [$selectedMonth]);
                    });
                }
                // if (!empty($request->search_year)) {
                //     $selectedMonth = $request->search_year; // your input is actually month number (1 to 12)
                
                //     $search_data = $search_data->where(function ($q) use ($selectedMonth) {
                //         $q->whereRaw("MONTH(missionaries.dob) = ?", [$selectedMonth])
                //           ->orWhereRaw("MONTH(missionaries_imp_dates.imp_date) = ?", [$selectedMonth]);
                //     });
                // }
                
               
              
              
             
               //$search_data =  $search_data->groupBy('missionaries.id')->toSql();
               //dd($search_data);
              // $search_data =  $search_data->groupBy('missionaries.id')->paginate($this->pagenation_number);
               $search_data =  $search_data
            //    ->where('missionaries.missionary_type', '=' ,'Associate')
                ->where('missionaries.retired_date', '!=' ,'')
    
               
            //   ->whereRaw('timestampdiff(year, childrens.dob, curdate()) <= 17')
               ->groupBy('employee.id')
               ->orderByRaw("COALESCE(NULLIF(employee.lname, ''), employee.fname) ASC")
               ->get();
          
                $html = '';
                $offset = ($request->page - 1) * $this->pagenation_number;
                if(!empty($request->search_year)){
                $dobanniversary=true;
                }
          
    if($isAnniversary==1){
                 
        foreach($search_data as $key=>$res){
            $tmp_arr=[];
            $tmp_arr[0]=$key + 1;
              if (!empty($res->lname) && !empty($res->fname)) {
           $tmp_arr[1] = $res->lname . ', ' . $res->fname;
            } elseif (!empty($res->lname)) {
                $tmp_arr[1] = $res->lname;
            } elseif (!empty($res->fname)) {
                $tmp_arr[1] = $res->fname;
            } else {
                $tmp_arr[1] = '';
            }
             $tmp_arr[2] = $res->vmcode ?? '';

             if ($isAnniversary==1) {
                // Fetch anniversary date
                $fetchAnniversaryDate = DB::table('missionaries_imp_dates')
                    ->where('missionary_id', $res->missionary_id)
                    ->where('date_type', 'Anniversary')
                    ->first();
            
                $anniversaryDate = 'NA';
                if (!empty($fetchAnniversaryDate) && !empty($fetchAnniversaryDate->imp_date)) {
                    $tmp_arr[3] = date("M, Y", strtotime($res->imp_date));
                }
                else{
                    $tmp_arr[3] = 'NA';
                }
        
            }

    $tmp_arr[4] = ($res->is_deceased == 1) ? 'YES' : 'NO';

      if (!empty($res->spouse_lname) && !empty($res->spouse_fname)) {
           $tmp_arr[5] = $res->spouse_lname . ', ' . $res->spouse_fname;
            } elseif (!empty($res->spouse_lname)) {
                $tmp_arr[5] = $res->spouse_lname;
            } elseif (!empty($res->spouse_fname)) {
                $tmp_arr[5] = $res->spouse_fname;
            } else {
                $tmp_arr[5] = '';
            }

    if (!empty($res->spouse_dob) && strtotime($res->spouse_dob)) {
        $tmp_arr[6] = date("M, Y", strtotime($res->spouse_dob));
    } else {
        $tmp_arr[6] = '';
    }

    $tmp_arr[7] = ($res->spouse_is_deceased == 1) ? 'YES' : 'NO';

  
 
            
            $csv_data[]= $tmp_arr;
        }
    
        csvDownlaod($csv_data,"usretiredassocdobanniversarydownload.csv");   
//    }
    }
    else{
          foreach($search_data as $key=>$res){
            $tmp_arr=[];
            $tmp_arr[0]=$key + 1;

           if (!empty($res->lname) && !empty($res->fname)) {
           $tmp_arr[1] = $res->lname . ', ' . $res->fname;
            } elseif (!empty($res->lname)) {
                $tmp_arr[1] = $res->lname;
            } elseif (!empty($res->fname)) {
                $tmp_arr[1] = $res->fname;
            } else {
                $tmp_arr[1] = '';
            }
    $tmp_arr[2] = $res->vmcode ?? '';

    if (!empty($res->dob) && strtotime($res->dob)) {
        $tmp_arr[3] = date("M, Y", strtotime($res->dob));
    } else {
        $tmp_arr[3] = '';
    }

    $tmp_arr[4] = ($res->is_deceased == 1) ? 'YES' : 'NO';

        if (!empty($res->spouse_lname) && !empty($res->spouse_fname)) {
           $tmp_arr[5] = $res->spouse_lname . ', ' . $res->spouse_fname;
            } elseif (!empty($res->spouse_lname)) {
                $tmp_arr[5] = $res->spouse_lname;
            } elseif (!empty($res->spouse_fname)) {
                $tmp_arr[5] = $res->spouse_fname;
            } else {
                $tmp_arr[5] = '';
            }

    if (!empty($res->spouse_dob) && strtotime($res->spouse_dob)) {
        $tmp_arr[6] = date("M, Y", strtotime($res->spouse_dob));
    } else {
        $tmp_arr[6] = '';
    }

    $tmp_arr[7] = ($res->spouse_is_deceased == 1) ? 'YES' : 'NO';

  
 
            
            $csv_data[]= $tmp_arr;
        }
    
        csvDownlaod($csv_data,"usretiredassocdobanniversarydownload.csv");   
    }
}
}
    public function enrollmentlevelvmebp(Request $request){
        try{
            $data=[];
            $data=getPermissionArray('vmebp_reports');//get permission for each module if its add in module table
            $user = Auth::user();
            $data["user"]=$user;
            if($data["permission_array"]["vmebp_reports"]["no_view"]==1){
            $data["menu"]="vmebp_reports";
            $query_string=[];
            $data["query_string"]='';
            $data["search_filter"]='';
            $data["search_batch"]='';
            $data["status_filter"]='';
            if(!isset($request->page)){
                $request->page=1;
            }
            $data["page"]=(isset($request->page))?$request->page:1;
            $data["offset"]=($request->page-1)*$this->pagenation_number;
            $search_year=date('Y');
            $data["results"]=DB::table('participants')
            ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
            ->leftJoin('participant_opt_outs','participant_opt_outs.participants_id','=','participants.id')
            ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
            ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
            ->leftJoin('group_types','group_types.id','=','participant_healthcare.group_type_id')
            ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
            ->leftJoin('employee','employee.id','=','participants.emp_id')
            ->leftJoin('childrens','childrens.id','=','participants.children_id')
            ->leftJoin('spouses','spouses.id','=','participants.spouse_id')
    
            ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
            , 'participants.fname as e_f_name'
            , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
            ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
            ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','participant_opt_outs.opt_out_date','relation_code.name as relation_name','participant_healthcare.group_type_id',
            'participant_opt_outs.term_end_date','participant_opt_outs.opt_out_reason' ,'group_types.group_name',
            'missionaries.lname as m_l_name','missionaries.fname as m_f_name','missionaries.email as m_email','missionaries.phone as m_phone',
            'employee.lname as emp_l_name','employee.fname as emp_f_name','employee.email as e_email','employee.phone as e_phone','childrens.lname as child_l_name','childrens.fname as child_f_name','childrens.email as c_email','childrens.phone as c_phone','spouses.spouse_fname','spouses.spouse_lname','spouses.spouse_dob','spouses.spouse_email'
            ,'spouses.spouse_phone','participants.missionary_id','participants.emp_id','participants.spouse_id','participants.children_id','participants.dob')
           ->where('health_coverage_members.id','=',2)
           ->where('participant_healthcare.is_primary','=',1)
           ->whereNotNull('participant_healthcare.status')
           ->where(function ($query) use ($search_year) {
            $query->whereRaw("YEAR(participant_healthcare.start_date) <= ?", [$search_year])
                  ->where(function ($q) use ($search_year) {
                      $q->whereNull('participant_healthcare.end_date')
                        ->orWhereRaw("YEAR(participant_healthcare.end_date) >= ?", [$search_year]);
                  });
        })
        //   ->whereNotNull('participant_opt_outs.opt_out_date')
           //->whereNotNull('participants.termination_date')
          // ->whereNotNull('participant_opt_outs.termination_date')
        // ->orderByRaw("participants.vmcode,
        //     CASE 
        //         WHEN relation_code.name = 'Self' THEN 1
        //         WHEN relation_code.name = 'Spouse' THEN 2
        //         WHEN relation_code.name = 'Children' THEN 3
        //         ELSE 4 
        //     END
        // ")
            ->orderByRaw("COALESCE(NULLIF(participants.lname, ''), participants.fname) ASC")
            ->groupBy('participants.id')
            ->paginate($this->pagenation_number);
            
           // dd($data["results"]);
           ///print_r($data["results"]);die;
           $data["per_page"]=$this->pagenation_number;
           $data["data"]= $data["results"];
            return view('all_vmebp_reports.enrollmentlevelvmebp',["data"=>$data])->with('count', 1);;
        }else{
            return Redirect::to('dashboard')->with('error', "Access denied .");
        } 
        }catch(exception $e){
                
        }


    }
    public function enrollmentlevelvmebpsearch(Request $request){
        try {
            $data = [];
            $data = getPermissionArray('vmebp_reports');
            $user = Auth::user();
            $data["user"] = $user;
            $search_year=date('Y');
            $search_data =DB::table('participants')
            ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
            ->leftJoin('participant_opt_outs','participant_opt_outs.participants_id','=','participants.id')
            ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
            ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
            ->leftJoin('group_types','group_types.id','=','participant_healthcare.group_type_id')
            ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
            ->leftJoin('employee','employee.id','=','participants.emp_id')
            ->leftJoin('childrens','childrens.id','=','participants.children_id')
            ->leftJoin('spouses','spouses.id','=','participants.spouse_id')
    
            ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
            , 'participants.fname as e_f_name'
            , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
            ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
            ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','participant_opt_outs.opt_out_date','relation_code.name as relation_name','participant_healthcare.group_type_id',
            'participant_opt_outs.term_end_date','participant_opt_outs.opt_out_reason' ,'group_types.group_name',
            'missionaries.lname as m_l_name','missionaries.fname as m_f_name','missionaries.email as m_email','missionaries.phone as m_phone',
            'employee.lname as emp_l_name','employee.fname as emp_f_name','employee.email as e_email','employee.phone as e_phone','childrens.lname as child_l_name','childrens.fname as child_f_name','childrens.email as c_email','childrens.phone as c_phone','spouses.spouse_fname','spouses.spouse_lname','spouses.spouse_dob','spouses.spouse_email'
            ,'spouses.spouse_phone','participants.missionary_id','participants.emp_id','participants.spouse_id','participants.children_id','participants.dob')
           ->where('health_coverage_members.id','=',2)
           ->where('participant_healthcare.is_primary','=',1)
           ->whereNotNull('participant_healthcare.status');
        //   ->whereNotNull('participant_opt_outs.opt_out_date')
           //->whereNotNull('participants.termination_date')
          // ->whereNotNull('participant_opt_outs.termination_date')
 
    
         
 
           
            if (isset($request->search_batch) && $request->search_batch != '') {
                $search_string = $request->search_batch;
                $search_data = $search_data->where(function ($q) use ($search_string) {
                    $q->where(
                        DB::raw("CONCAT(
                            COALESCE(participants.lname, ''), ' ', COALESCE(participants.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    // $q->orWhere(
                    //     DB::raw("CONCAT(
                    //         COALESCE(spouses.spouse_fname, ''), ' ', COALESCE(spouses.spouse_lname, '')
                    //     )"),
                    //     'LIKE',
                    //     "%{$search_string}%"
                    // );
                });
            }

            // if (!empty($request->search_year)) {
            //     $search_data = $search_data->whereRaw("YEAR(participant_healthcare.start_date) = ?", [$request->search_year]);
                                         
            // }
            if (!empty($request->search_year)) {
                $search_year = $request->search_year;
            
                $search_data = $search_data->where(function ($query) use ($search_year) {
                    $query->whereRaw("YEAR(participant_healthcare.start_date) <= ?", [$search_year]) 
                          ->where(function ($q) use ($search_year) {
                              $q->whereNull('participant_healthcare.end_date') 
                                ->orWhereRaw("YEAR(participant_healthcare.end_date) >= ?", [$search_year]); 
                          });
                });
            }
            else{
                // $search_data = $search_data->where(function ($query) use ($search_year) {
                //     $query->whereRaw("YEAR(participant_healthcare.start_date) <= ?", [$search_year]) 
                //           ->where(function ($q) use ($search_year) {
                //               $q->whereNull('participant_healthcare.end_date') 
                //                 ->orWhereRaw("YEAR(participant_healthcare.end_date) >= ?", [$search_year]); 
                //           });
                // });
            }
          
            
  
            
         
           $search_data =  $search_data
           ->orderByRaw("COALESCE(NULLIF(participants.lname, ''), participants.fname) ASC")
    //        ->orderByRaw("participants.vmcode,
    //        CASE 
    //            WHEN relation_code.name = 'Self' THEN 1
    //            WHEN relation_code.name = 'Spouse' THEN 2
    //            WHEN relation_code.name = 'Children' THEN 3
    //            ELSE 4 
    //        END
    //    ")
       ->groupBy('participants.id')
       ->paginate($this->pagenation_number);
         //  dd($search_data);
            $html = '';
            $offset = ($request->page - 1) * $this->pagenation_number;
        
               $spouseName="";
            foreach ($search_data as $key => $res) {
                $spouses=DB::table('spouses')->where('missionary_id',$res->missionary_id)->first();
                if(empty($res->spouse_id) && !empty($spouses)){
                    $spouseName=trim($spouses->spouse_lname . ($spouses->spouse_lname && $spouses->spouse_fname ? ', ' : '') . $spouses->spouse_fname);
                }
                else{
                    $spouseName="";
                }
                // Determine group name
                if ($res->group_name === 'G') {
                    $groupName = "G (Single)";
                } elseif ($res->group_name === 'GG') {
                    $groupName = "GG (Couple)";
                } elseif ($res->group_name === 'GGG') {
                    $groupName = "GGG (Family)";
                } else {
                    $groupName = $res->group_name ?? '';
                }
            
                // Determine name based on available ID
                if (!empty($res->missionary_id) && empty($res->emp_id) && empty($res->children_id) && empty($res->spouse_id)) {
                    $fullName = trim($res->m_l_name . ($res->m_l_name && $res->m_f_name ? ', ' : '') . $res->m_f_name);
                    $email = $res->m_email ?? '';
                    $phone = $res->m_phone ?? '';
                } elseif (!empty($res->children_id)) {
                    $fullName = trim($res->child_l_name . ($res->child_l_name && $res->child_f_name ? ', ' : '') . $res->child_f_name);
                    $email = $res->c_email ?? '';
                    $phone = $res->c_phone ?? '';
                } elseif (!empty($res->spouse_id)) {
                    $fullName = trim($res->spouse_lname . ($res->spouse_lname && $res->spouse_fname ? ', ' : '') . $res->spouse_fname);
                    $email = $res->spouse_email ?? '';
                    $phone = $res->spouse_phone ?? '';
                } elseif (!empty($res->emp_id)) {
                    $fullName = trim($res->emp_l_name . ($res->emp_l_name && $res->emp_f_name ? ', ' : '') . $res->emp_f_name);
                    $email = $res->e_email ?? '';
                    $phone = $res->e_phone ?? '';
                } else {
                    $fullName = '';
                    $email = '';
                    $phone = '';
                }
               
            
                $html .= '<tr id="row-' . ($offset + $key + 1) . '">' .
                    '<td style="width:2%; text-align:left;">' . ($offset + $key + 1) . '</td>' .
                    '<td style="width:10%; text-align:left">' . $fullName . '</td>' .
                    '<td style="width:10%; text-align:left">' . ($res->e_vmcode ?? '') . '</td>' .
                    '<td style="width:10%; text-align:left">' . (!empty($res->dob) ? date('m-d-Y', strtotime($res->dob)) : '') . '</td>' .
                    '<td style="width:10%; text-align:left">' . $spouseName . '</td>' .
                    '<td style="width:10%; text-align:left">' . $groupName . '</td>' .
                    '<td style="width:10%; text-align:left">' . (!empty($res->start_date) ? date('m-d-Y', strtotime($res->start_date)) : '') . '</td>' .
                    '<td style="width:10%; text-align:left">' . (!empty($res->end_date) ? date('m-d-Y', strtotime($res->end_date)) : '') . '</td>' .
                    '<td style="width:10%; text-align:left">' . $email . '</td>' .
                    '<td style="width:10%; text-align:left">' . $phone . '</td>' .
                '</tr>';
            }
            
            if (trim($html) != '') {
                $pagenation = \View::make('include.pagenation', ['paginator' => $search_data])->render();
            } else {
                $pagenation = '';
                $html .= '<tr>';
                $html .= '<td  style="width:100%" class="text-nowrap" colspan="10">';
                $html .= 'No Data Found';
                $html .= '</td>';
            }
            if (!isset($request->page)) {
                $request->page = 1;
            }
            $page = (isset($request->page)) ? $request->page : 1;
            $offset = ($request->page - 1) * $this->pagenation_number;
            $start_page_number = $offset + 1;
            $end_page_number = $search_data->total();
            if ($end_page_number >= ($start_page_number + $this->pagenation_number)) {
                $end_page_number = $offset + $this->pagenation_number;
            }
            return response()->json([
                'message' => 'contributions',
                'html' => $html,
                'pagenation' => $pagenation,
                'start_page_number' => $start_page_number,
                'end_page_number' => $end_page_number,
                'total_records' => $search_data->total(),
                'status' => 1
            ], 200);

        } catch (exception $e) {

        }
        
    }
    public function enrollmentlevelvmebpdownload(Request $request){
        $data=[];
        $data = getPermissionArray('vmebp_reports');
        $user = Auth::user();
        $data["user"]=$user;
        $search_year=date('Y');
        $csv_data=[];
        $csv_data[]=array('Sl.No', 	'Primary Insurer', 	'VMCODE','DOB','Spouse', 'Enrollment Level',	'Coverage Start Date', 	'Coverage End Date', 	'Email', 	'Phone',
    );
     
        $search_data = DB::table('participants')
        ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
        ->leftJoin('participant_opt_outs','participant_opt_outs.participants_id','=','participants.id')
        ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
        ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
        ->leftJoin('group_types','group_types.id','=','participant_healthcare.group_type_id')
        ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
        ->leftJoin('employee','employee.id','=','participants.emp_id')
        ->leftJoin('childrens','childrens.id','=','participants.children_id')
        ->leftJoin('spouses','spouses.id','=','participants.spouse_id')

        ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
        , 'participants.fname as e_f_name'
        , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
        ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
        ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','participant_opt_outs.opt_out_date','relation_code.name as relation_name','participant_healthcare.group_type_id',
        'participant_opt_outs.term_end_date','participant_opt_outs.opt_out_reason' ,'group_types.group_name',
        'missionaries.lname as m_l_name','missionaries.fname as m_f_name','missionaries.email as m_email','missionaries.phone as m_phone',
        'employee.lname as emp_l_name','employee.fname as emp_f_name','employee.email as e_email','employee.phone as e_phone','childrens.lname as child_l_name','childrens.fname as child_f_name','childrens.email as c_email','childrens.phone as c_phone','spouses.spouse_fname','spouses.spouse_lname','spouses.spouse_dob','spouses.spouse_email'
        ,'spouses.spouse_phone','participants.missionary_id','participants.emp_id','participants.spouse_id','participants.children_id','participants.dob')
       ->where('health_coverage_members.id','=',2)
       ->where('participant_healthcare.is_primary','=',1)
       ->whereNotNull('participant_healthcare.status');
       
       if (isset($request->search_batch) && $request->search_batch != '') {
        $search_string = $request->search_batch;
        $search_data = $search_data->where(function ($q) use ($search_string) {
            $q->where(
                DB::raw("CONCAT(
                    COALESCE(participants.lname, ''), ' ', COALESCE(participants.fname, '')
                )"),
                'LIKE',
                "%{$search_string}%"
            );
            // $q->orWhere(
            //     DB::raw("CONCAT(
            //         COALESCE(spouses.spouse_fname, ''), ' ', COALESCE(spouses.spouse_lname, '')
            //     )"),
            //     'LIKE',
            //     "%{$search_string}%"
            // );
        });
    }

        // if (!empty($request->search_year)) {
        //     $search_data = $search_data->whereRaw("YEAR(participant_healthcare.start_date) = ?", [$request->search_year]);
                                     
        // }
        if (!empty($request->search_year)) {
            $search_year = $request->search_year;
        
            $search_data = $search_data->where(function ($query) use ($search_year) {
                $query->whereRaw("YEAR(participant_healthcare.start_date) <= ?", [$search_year]) 
                      ->where(function ($q) use ($search_year) {
                          $q->whereNull('participant_healthcare.end_date') 
                            ->orWhereRaw("YEAR(participant_healthcare.end_date) >= ?", [$search_year]); 
                      });
            });
        }
        else{
            $search_data = $search_data->where(function ($query) use ($search_year) {
                $query->whereRaw("YEAR(participant_healthcare.start_date) <= ?", [$search_year]) 
                      ->where(function ($q) use ($search_year) {
                          $q->whereNull('participant_healthcare.end_date') 
                            ->orWhereRaw("YEAR(participant_healthcare.end_date) >= ?", [$search_year]); 
                      });
            }); 
        }
      
        //dd($search_data);
        $search_data = $search_data
        ->groupBy('participants.id')
        ->orderByRaw("COALESCE(NULLIF(participants.lname, ''), participants.fname) ASC")
    //     ->orderByRaw("participants.vmcode,
    //        CASE 
    //            WHEN relation_code.name = 'Self' THEN 1
    //            WHEN relation_code.name = 'Spouse' THEN 2
    //            WHEN relation_code.name = 'Children' THEN 3
    //            ELSE 4 
    //        END
    //    ")
        ->get();
      // dd($search_data);

   //   $csv_data = [];
          $spouseName="";
      foreach ($search_data as $key => $val) {
        $spouses=DB::table('spouses')->where('missionary_id',$val->missionary_id)->first();
        if(empty($val->spouse_id) && !empty($spouses)){
            $spouseName=trim($spouses->spouse_lname . ($spouses->spouse_lname && $spouses->spouse_fname ? ', ' : '') . $spouses->spouse_fname);
        }
        else{
            $spouseName="";
        }
          // Determine group name
          if ($val->group_name === 'G') {
              $groupName = "G (Single)";
          } elseif ($val->group_name === 'GG') {
              $groupName = "GG (Couple)";
          } elseif ($val->group_name === 'GGG') {
              $groupName = "GGG (Family)";
          } else {
              $groupName = $val->group_name ?? '';
          }
      
          // Determine name, email, and phone based on available ID
          if (!empty($val->missionary_id) && empty($val->emp_id) && empty($val->children_id) && empty($val->spouse_id)) {
              $fullName = trim($val->m_l_name . ($val->m_l_name && $val->m_f_name ? ', ' : '') . $val->m_f_name);
              $email = $val->m_email ?? '';
              $phone = $val->m_phone ?? '';
          } elseif (!empty($val->children_id)) {
              $fullName = trim($val->child_l_name . ($val->child_l_name && $val->child_f_name ? ', ' : '') . $val->child_f_name);
              $email = $val->c_email ?? '';
              $phone = $val->c_phone ?? '';
          } elseif (!empty($val->spouse_id)) {
              $fullName = trim($val->spouse_lname . ($val->spouse_lname && $val->spouse_fname ? ', ' : '') . $val->spouse_fname);
              $email = $val->spouse_email ?? '';
              $phone = $val->spouse_phone ?? '';
          } elseif (!empty($val->emp_id)) {
              $fullName = trim($val->emp_l_name . ($val->emp_l_name && $val->emp_f_name ? ', ' : '') . $val->emp_f_name);
              $email = $val->e_email ?? '';
              $phone = $val->e_phone ?? '';
          } else {
              $fullName = '';
              $email = '';
              $phone = '';
          }
      
          $tmp_arr = [];
          $tmp_arr[0] = $key + 1; // Sl.No
          $tmp_arr[1] = $fullName; // Participant #
          $tmp_arr[2] = $val->e_vmcode ?? ''; // PSS#
          $tmp_arr[3] = date("m-d-Y", strtotime($val->dob)) ?? ''; // PSS#
          $tmp_arr[4] = $spouseName ?? ''; // PSS#
          $tmp_arr[5] = $groupName; // Group Name
      
          // Enrollment Date (Joining Date)
          $tmp_arr[6] = (!empty($val->start_date) && strtotime($val->start_date)) ? date("m-d-Y", strtotime($val->start_date)) : '';
          
          // Opt Out VMEBP Date
          $tmp_arr[7] = (!empty($val->end_date) && strtotime($val->end_date)) ? date("m-d-Y", strtotime($val->end_date)) : '';
      
          $tmp_arr[8] = $email;
          $tmp_arr[9] = $phone;
      
          $csv_data[] = $tmp_arr;
      }
      
      csvDownlaod($csv_data, "enrollmentlevelforvmebp.csv");
    
        
    }
    public function vmebppastcurrent(Request $request){
        try{
            $data=[];
            $data=getPermissionArray('vmebp_reports');//get permission for each module if its add in module table
            $user = Auth::user();
            $data["user"]=$user;
            if($data["permission_array"]["vmebp_reports"]["no_view"]==1){
            $data["menu"]="vmebp_reports";
            $query_string=[];
            $data["query_string"]='';
            $data["search_filter"]='';
            $data["search_batch"]='';
            $data["status_filter"]='';
            if(!isset($request->page)){
                $request->page=1;
            }
            $data["page"]=(isset($request->page))?$request->page:1;
            $data["offset"]=($request->page-1)*$this->pagenation_number;
            $data["results"] = DB::table('participants')
            ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
            ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
            ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
            ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
            ->leftJoin('employee','employee.id','=','participants.emp_id')
           ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
            , 'participants.fname as e_f_name'
            , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
            ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
            ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','relation_code.name as relation_name','participant_healthcare.group_type_id',
            'missionaries.lname as m_l_name','missionaries.fname as m_f_name',
            'employee.lname as emp_l_name','employee.fname as emp_f_name','participants.missionary_id','participants.emp_id','participants.spouse_id','participants.children_id')
            ->where('participant_healthcare.is_primary',1)
             ->where('health_coverage_members.id','=',2)
             ->where('participants.status','=',1)
             ->orderByRaw("COALESCE(NULLIF(participants.lname, ''), participants.fname) ASC")
            

 
            ->groupBy('participants.id')
      
            ->paginate($this->pagenation_number);
        //    dd($data["results"]);
           // dd($data["results"]);
           ///print_r($data["results"]);die;
           $data["per_page"]=$this->pagenation_number;
           $data["data"]= $data["results"];
            return view('all_vmebp_reports.vmebppastcurrent',["data"=>$data])->with('count', 1);;
        }else{
            return Redirect::to('dashboard')->with('error', "Access denied .");
        } 
        }catch(exception $e){
                
        }


    }
    public function vmebppastcurrentsearch(Request $request){
        try {
            $data = [];
            $data = getPermissionArray('all-reports');
            $user = Auth::user();
            $data["user"] = $user;
            $search_data =  DB::table('participants')
            ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
            ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
            ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
            ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
            ->leftJoin('employee','employee.id','=','participants.emp_id')
           ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
            , 'participants.fname as e_f_name'
            , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
            ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
            ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','relation_code.name as relation_name','participant_healthcare.group_type_id',
            'missionaries.lname as m_l_name','missionaries.fname as m_f_name',
            'employee.lname as emp_l_name','employee.fname as emp_f_name','participants.missionary_id','participants.emp_id','participants.spouse_id','participants.children_id')
            ->where('participant_healthcare.is_primary',1)
             ->where('health_coverage_members.id','=',2)
             ->where('participants.status','=',1)
             ->orderByRaw("COALESCE(NULLIF(participants.lname, ''), participants.fname) ASC");
           
           
            if (isset($request->search_batch) && $request->search_batch != '') {
                $search_string = $request->search_batch;
                $search_data = $search_data->where(function ($q) use ($search_string) {
                    $q->where(
                        DB::raw("CONCAT(
                            COALESCE(missionaries.lname, ''), ' ', COALESCE(missionaries.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    $q->orWhere(
                        DB::raw("CONCAT(
                            COALESCE(employee.lname, ''), ' ', COALESCE(employee.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                    
                  
                });
            }
          

            if (!empty($request->search_year)) {
                $search_string = $request->search_year;
                $search_data = $search_data->where(function ($q) use ($search_string) {
                    $q->whereRaw("YEAR(participant_healthcare.start_date) = ?", [$search_string])
                    ->orWhereRaw("YEAR(participant_healthcare.end_date) = ?", [$search_string]);
                  
                });


        
            }
          
          
         
          // $search_data =  $search_data->groupBy('claim_villagemissions.id')->toSql();
          // dd($search_data);
           $search_data =  $search_data->groupBy('participants.id')
           ->paginate($this->pagenation_number);
          
            $html = '';
            $offset = ($request->page - 1) * $this->pagenation_number;
            foreach ($search_data as $key => $res) {
                $html .= '<tr id="row-">';
                $html .= '<td  style="width:2%;;text-align:left;" >';
                $html .= $offset + $key + 1;
                $html .= '</td>';
                $html .= '<td style="width:10%;text-align:left">';
                $html .= $res->e_f_name . ($res->e_l_name && $res->e_f_name ? ',' : '') . ' ' . $res->e_l_name;
                $html .= '</td>';
                $html .= '<td style="width:10%;text-align:left">';
                $html .= $res->e_vmcode??'';
                $html .= '</td>';
             
                
                $html .= '<td style="width:10%;text-align:left">';
                 $html .=  $res->start_date ? date("m-d-Y", strtotime($res->start_date)) : '' ;
                 $html .= '</td>'; 
                
                 $html .= '<td style="width:10%;text-align:left">';
                 $html .=  $res->end_date ? date("m-d-Y", strtotime($res->end_date)) : '' ;
                 $html .= '</td>'; 
                
               
                $html .= '</tr>';
            }
            if (trim($html) != '') {
                $pagenation = \View::make('include.pagenation', ['paginator' => $search_data])->render();
            } else {
                $pagenation = '';
                $html .= '<tr>';
                $html .= '<td  style="width:100%" class="text-nowrap" colspan="10">';
                $html .= 'No Data Found';
                $html .= '</td>';
            }
            if (!isset($request->page)) {
                $request->page = 1;
            }
            $page = (isset($request->page)) ? $request->page : 1;
            $offset = ($request->page - 1) * $this->pagenation_number;
            $start_page_number = $offset + 1;
            $end_page_number = $search_data->total();
            if ($end_page_number >= ($start_page_number + $this->pagenation_number)) {
                $end_page_number = $offset + $this->pagenation_number;
            }
            return response()->json([
                'message' => 'contributions',
                'html' => $html,
                'pagenation' => $pagenation,
                'start_page_number' => $start_page_number,
                'end_page_number' => $end_page_number,
                'total_records' => $search_data->total(),
                'status' => 1
            ], 200);

        } catch (exception $e) {

        }
    }
    public function vmebppastcurrentdownload(Request $request){
        $data=[];
        $data = getPermissionArray('all-reports');
        $user = Auth::user();
        $data["user"]=$user;
        $csv_data=[];
        

        $csv_data[]=array('Sl.No', 	'Primary Insurer' 	,'VMCODE' 	,'Coverage Start Date', 	'Coverage End Date');
     
        $search_data =   DB::table('participants')
        ->leftJoin('participant_healthcare','participant_healthcare.participants_id','=','participants.id')
        ->leftJoin('health_coverage_members','health_coverage_members.id','=','participant_healthcare.health_coverage_member_id')
        ->leftJoin('relation_code','relation_code.id','=','participants.relationcode_id')
        ->leftJoin('missionaries','missionaries.id','=','participants.missionary_id')
        ->leftJoin('employee','employee.id','=','participants.emp_id')
       ->select('participants.id as participantid','participant_healthcare.pss_no as pssno'
        , 'participants.fname as e_f_name'
        , 'participants.lname as e_l_name', 'participants.vmcode as e_vmcode'
        ,'health_coverage_members.id as coverage_id','health_coverage_members.member_name as coverage_name'
        ,'participant_healthcare.start_date','participant_healthcare.end_date','participant_healthcare.is_primary','participants.id as participant_id','relation_code.name as relation_name','participant_healthcare.group_type_id',
        'missionaries.lname as m_l_name','missionaries.fname as m_f_name',
        'employee.lname as emp_l_name','employee.fname as emp_f_name','participants.missionary_id','participants.emp_id','participants.spouse_id','participants.children_id')
        ->where('participant_healthcare.is_primary',1)
         ->where('health_coverage_members.id','=',2)
         ->where('participants.status','=',1);
       
       
         if (isset($request->search_batch) && $request->search_batch != '') {
            $search_string = $request->search_batch;
            $search_data = $search_data->where(function ($q) use ($search_string) {
                $q->where(
                    DB::raw("CONCAT(
                        COALESCE(missionaries.lname, ''), ' ', COALESCE(missionaries.fname, '')
                    )"),
                    'LIKE',
                    "%{$search_string}%"
                );
                $q->orWhere(
                    DB::raw("CONCAT(
                        COALESCE(employee.lname, ''), ' ', COALESCE(employee.fname, '')
                    )"),
                    'LIKE',
                    "%{$search_string}%"
                );
                
              
            });
        }
      

        if (!empty($request->search_filter)) {
            $search_string = $request->search_filter;
            $search_data = $search_data->where(function ($q) use ($search_string) {
                $q->whereRaw("YEAR(participant_healthcare.start_date) = ?", [$search_string])
                ->orWhereRaw("YEAR(participant_healthcare.end_date) = ?", [$search_string]);
              
            });


    
        }
      
            
            
            
         
           $search_data =  $search_data->groupBy('participants.id')
           ->orderByRaw("COALESCE(NULLIF(participants.lname, ''), participants.fname) ASC")->get();


        foreach($search_data as $key=>$val){
            $tmp_arr=[];
            $tmp_arr[0]=$key + 1;
            $tmp_arr[1]=$val->e_l_name . ($val->e_f_name && $val->e_l_name ? ',' : '') . ' ' . $val->e_f_name;
            $tmp_arr[2]=$val->e_vmcode??'';
            if(!empty($val->start_date))
            {
                $tmp_arr[3]=date("m-d-Y", strtotime($val->start_date));
            }
            else{
                $tmp_arr[3]='';
            }
            if(!empty($val->end_date))
            {
                $tmp_arr[4]=date("m-d-Y", strtotime($val->end_date));
            }
            else{
                $tmp_arr[4]='';
            }
            
            
            $csv_data[]= $tmp_arr;
        }
    
        csvDownlaod($csv_data,"vmebppastcurrent.csv");   


    }
    public function missionaryhistorybyfield(Request $request){
        try{
            $data=[];
            $data=getPermissionArray('contribution');//get permission for each module if its add in module table
            $user = Auth::user();
            $data["user"]=$user;
            if($data["permission_array"]["contribution"]["no_view"]==1){
            $data["menu"]="all-reports";
            $query_string=[];
            $data["query_string"]='';
            $data["search_filter"]='';
            $data["search_batch"]='';
            $data["status_filter"]='';
            if(!isset($request->page)){
                $request->page=1;
            }
            $data["page"]=(isset($request->page))?$request->page:1;
            $data["offset"]=($request->page-1)*$this->pagenation_number;
             $data["results"] = missionary::leftjoin('missionaries_fields as mf', 'mf.missionary_id', '=', 'missionaries.id')
             ->leftjoin('fields', 'fields.id', '=', 'mf.field_id')
             ->select('fields.id','fields.church_name','missionaries.lname','missionaries.vmcode','missionaries.fname',DB::raw("CONCAT(missionaries.fname, ' ', missionaries.lname) as missionary_name"),'mf.form_date','mf.to_date');
            // $data["total_contribution"] = Contribution::
            // select(DB::raw("SUM(gift_amount) as totalcontribution"))
            // ->first();
            // $data["paid_contribution"] = Contribution::where('is_cleared', 1)
            // ->where('status',1)
            // ->select(DB::raw("SUM(gift_amount) as paidcontribution"))
            // ->first();
            // $data["unpaid_contribution"] = Contribution::where('is_cleared', 0)
            // ->where('status',1)
            // ->select(DB::raw("SUM(gift_amount) as unpaidcontribution"))
            // ->first();
            // //dd($data["total_contribution"]['totalcontribution']);
   
            // if(isset($request->searchbychurchname) && $request->searchbychurchname!=''){ 
         
            //     $query_string[]="searchbychurchname=".$request->searchbychurchname;
            //     $data["search_filter"]=$request->searchbychurchname;
            //     $s_value = $request->searchbychurchname;
            //     $data["results"] = $data["results"]->where(function ($q) use ($s_value) {
            //         $q->where('fields.church_name','LIKE',"%{$s_value}%")
            //         ->orWhere('fields.fieldcode','LIKE',"%{$s_value}%");
            //     });
            // }

            // if(isset($request->searchbymissionaryname) && $request->searchbymissionaryname!=''){ 
          
            //     $query_string[]="searchbymissionaryname=".$request->searchbymissionaryname;
            //     $data["search_filter"]=$request->searchbymissionaryname;
            //     $s_value = $request->searchbymissionaryname;
            //     $data["results"] = $data["results"]->where(function ($q) use ($s_value) {
            //         $q->where('missionary_name','LIKE',"%{$s_value}%");
            //     });
            // }

            // if (!empty($request->searchbyvmcode)) {
            //     $query_string[]="searchbyvmcode=".$request->searchbyvmcode;
            //     $data["search_filter"]=$request->searchbyvmcode;
            //     $s_value = $request->searchbyvmcode;
            //     $data["results"] = $data["results"]->where(function ($q) use ($s_value) {
            //         $q->where('missionaries.vmcode','LIKE',"%{$s_value}%");
            //     });
            // }
            // if(isset($request->batchid) && $request->batchid!=''){ 
            //    // $query_string[]="batchid=".$request->batchid;
            //     $data["search_batch"]=$request->batchid;
            //     $data["results"]=$data["results"]->where('contributions.batch_id','LIKE',"%{$request->batchid}%");
                
            // }
            // if(isset($request->status) && $request->status!=''){ 
            //     $query_string[]="status=".$request->status;
            //     $data["status_filter"]=$request->status;
            //     $data["results"]=$data["results"]->where('contributions.status','=',$request->status);
            // }
            // if(!empty($query_string)){
            //     $data["query_string"]="&&".implode("&&",$query_string);
            // }
             $data["results"] =$data["results"]
             ->where('fields.status',1)
             ->groupBy('fields.id','missionaries.id')
          //   ->havingRaw("CONCAT(missionaries.lname, ' ', missionaries.fname) IS NOT NULL") 
         //    ->orderBy(DB::raw("CONCAT(COALESCE(missionaries.lname, ''), ' ', COALESCE(missionaries.fname, ''))"), 'ASC')
         ->orderBy(DB::raw("
                CONCAT(
                    COALESCE(NULLIF(TRIM(missionaries.lname), ''), 'zzzzz'),
                    ' ',
                    COALESCE(NULLIF(TRIM(missionaries.fname), ''), 'zzzzz')
                )
            "), 'ASC')

             ->paginate($this->pagenation_number);
           // dd($data["results"]);
           ///print_r($data["results"]);die;
           $data["per_page"]=$this->pagenation_number;
           $data["data"]= $data["results"];
            return view('missionaryhistorybyfield.list',["data"=>$data])->with('count', 1);;
        }else{
            return Redirect::to('dashboard')->with('error', "Access denied .");
        } 
        }catch(exception $e){
                
        }
    }
    public function missionaryhistorybyfieldsearch(){}
    public function missionaryhistorybyfielddownload(){}

    public function searchHistoryByField(Request $request)
    
    {
     
       
        try {
            $data = [];
            $data = getPermissionArray('all-reports');
            $user = Auth::user();
            $data["user"] = $user;
            $search_data =  missionary::leftjoin('missionaries_fields as mf', 'mf.missionary_id', '=', 'missionaries.id')
            ->leftjoin('fields', 'fields.id', '=', 'mf.field_id')
            ->select('fields.id','fields.church_name','missionaries.lname','missionaries.fname','missionaries.vmcode',DB::raw("CONCAT(missionaries.fname, ' ', missionaries.lname) as missionary_name"),'mf.form_date','mf.to_date');
            
            if (isset($request->searchbychurchname) && $request->searchbychurchname != '') {
                $search_data = $search_data->where('fields.church_name', 'LIKE', "%{$request->searchbychurchname}%");
            }
            
            if (isset($request->searchbymissionaryname) && $request->searchbymissionaryname != '') {
                $search_string = $request->searchbymissionaryname;
                $search_data = $search_data->where(function ($q) use ($search_string) {
                    $q->where(
                        DB::raw("CONCAT(
                            COALESCE(missionaries.lname, ''), ' ', COALESCE(missionaries.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                });
            }
            // if (!empty($request->date_from)) {
            //     // Convert date_from (MM-DD-YYYY or MM-YYYY) to Y-m-d format
            //     $date_from = \Carbon\Carbon::createFromFormat('m-Y', $request->date_from)->startOfMonth()->format('Y-m-d');
            
            //     $search_data = $search_data->where('mf.form_date', '>=', $date_from);
            // }
            
            // if (!empty($request->date_to)) {
            //     // Convert date_to (MM-DD-YYYY or MM-YYYY) to Y-m-d format
            //     $date_to = \Carbon\Carbon::createFromFormat('m-Y', $request->date_to)->endOfMonth()->format('Y-m-d');
            
            //     $search_data = $search_data->where(function ($query) use ($date_to) {
            //         $query->where('mf.to_date', '<=', $date_to)
            //            ->orWhereNull('mf.to_date');
            //              // ->orWhere('missionaries.termination_date', '<=', $date_to);
            //     });
            // }
            
            if (!empty($request->date_from)) {
                // Convert date_from (MM-DD-YYYY or MM-YYYY) to Y-m-d format
                $date_from = \Carbon\Carbon::createFromFormat('m-Y', $request->date_from)->startOfMonth()->format('Y-m-d');
            
                // $search_data = $search_data->where('mf.form_date', '>=', $date_from);
                $search_data = $search_data->where(function($q) use ($date_from) {
                    $q->where(function($q1) use ($date_from){
                        $q1->where('mf.form_date', '<=', $date_from);
                        $q1->where(function($q2) use ($date_from){
                            $q2->where('mf.to_date', '>=', $date_from);
                            $q2->orWhereNull('mf.to_date');
                        });
                    });
                    $q->orWhere('mf.form_date', '>=', $date_from);
                });
            }
            
            // if (!empty($request->date_to)) {
            //     // Convert date_to (MM-DD-YYYY or MM-YYYY) to Y-m-d format
            //     $date_to = \Carbon\Carbon::createFromFormat('m-Y', $request->date_to)->endOfMonth()->format('Y-m-d');
            
            //     $search_data = $search_data->where(function ($query) use ($date_to) {
            //         $query->where('missionaries.retired_date', '<=', $date_to)
            //               ->orWhere('missionaries.termination_date', '<=', $date_to);
            //     });
            // }
            if (!empty($request->date_to)) {
                // Convert date_to (MM-DD-YYYY or MM-YYYY) to Y-m-d format
                $date_to = \Carbon\Carbon::createFromFormat('m-Y', $request->date_to)->endOfMonth()->format('Y-m-d');
            
                $search_data = $search_data->where(function ($query) use ($date_to) {                    
                    $query->where('mf.form_date', '<=', $date_to);                    
                    $query->where(function($q) use ($date_to) {
                        $q->where('mf.to_date', '<=', $date_to);
                        $q->orWhereNull('mf.to_date');
                    });                          
                });
            }
          
            
            if (!empty($request->searchbyvmcode)) {
                $search_string = $request->searchbyvmcode;
                $search_data = $search_data->where(function ($q) use ($search_string) {
                    $q->where('missionaries.vmcode', 'LIKE', "%{$search_string}%");
                });
            }
            
            if (isset($request->sortBy) && $request->sortBy != '') {
                $search_string = $request->sortBy;
                if($search_string == "church_a_z"){
                    $search_data = $search_data->orderBy('fields.church_name', 'ASC')->paginate($this->pagenation_number);
                }
                if($search_string == "form_old_date"){
                    $search_data = $search_data->orderBy('mf.form_date', 'ASC')->paginate($this->pagenation_number);
                }
                if($search_string == "form_new_date"){
                    $search_data = $search_data->orderBy('mf.form_date', 'DESC')->paginate($this->pagenation_number);
                }
                if($search_string == "form_new_date"){
                    $search_data = $search_data->orderBy('mf.form_date', 'DESC')->paginate($this->pagenation_number);
                } 
                if($search_string == "missionary_a_z"){
                    $search_data = $search_data->orderBy(DB::raw("CONCAT(missionaries.fname, ' ', missionaries.lname)"), 'ASC')->paginate($this->pagenation_number);
                } 
   
            } else {
                $search_data = $search_data
                ->where('fields.status',1)
                ->groupBy('fields.id','missionaries.id')
             //   ->havingRaw("CONCAT(missionaries.fname, ' ', missionaries.lname) IS NOT NULL") 
             //   ->orderBy(DB::raw("CONCAT(missionaries.lname, ' ', missionaries.fname)"), 'ASC') // Order by missionary name
             ->orderBy(DB::raw("
             CONCAT(
                 COALESCE(NULLIF(TRIM(missionaries.lname), ''), 'zzzzz'),
                 ' ',
                 COALESCE(NULLIF(TRIM(missionaries.fname), ''), 'zzzzz')
             )
         "), 'ASC')
                ->paginate($this->pagenation_number);
            }
            
            
         
           
            $html = '';
            $offset = ($request->page - 1) * $this->pagenation_number;
            foreach ($search_data as $key => $res) {
                $html .= '<tr id="row-">';
                $html .= '<td  style="width:2%;;text-align:left;" >';
                $html .= $offset + $key + 1;
                $html .= '</td>';
                $html .= '<td style="width:10%;text-align:left">';
                $html .= $res->lname . ($res->lname && $res->fname ? ',' : '') . ' ' . $res->fname;
                $html .= '</td>';
                $html .= '<td style="width:10%;text-align:left">';
                $html .= $res->vmcode??'';
                $html .= '</td>';
                $html .= '<td style="width:5%; text-align:left;">';
                $html .= $res->church_name??'';
                $html .= '</td>';      
                $html .= '<td style="width:10%;text-align:left">';
                $html .=  $res->form_date ? date("M, Y", strtotime($res->form_date)) : '' ;
                $html .= '</td>';
                $html .= '<td style="width:10%;text-align:left">';
                $html .=  $res->to_date ? date("M, Y", strtotime($res->to_date)) : '' ;
                $html .= '</td>';
               
              
                $html .= '</tr>';
            }
            if (trim($html) != '') {
                $pagenation = \View::make('include.pagenation', ['paginator' => $search_data])->render();
            } else {
                $pagenation = '';
                $html .= '<tr>';
                $html .= '<td  style="width:100%" class="text-nowrap" colspan="10">';
                $html .= 'No Data Found';
                $html .= '</td>';
            }
            if (!isset($request->page)) {
                $request->page = 1;
            }
            $page = (isset($request->page)) ? $request->page : 1;
            $offset = ($request->page - 1) * $this->pagenation_number;
            $start_page_number = $offset + 1;
            $end_page_number = $search_data->total();
            if ($end_page_number >= ($start_page_number + $this->pagenation_number)) {
                $end_page_number = $offset + $this->pagenation_number;
            }
            return response()->json([
                'message' => 'contributions',
                'html' => $html,
                'pagenation' => $pagenation,
                'start_page_number' => $start_page_number,
                'end_page_number' => $end_page_number,
                'total_records' => $search_data->total(),
                'status' => 1
            ], 200);

        } catch (exception $e) {

        }
    }
     
    public function missionaryhistorybyfieldcsvDownLoad(Request $request){
     
 
        $data=[];
        $data = getPermissionArray('all-reports');
        $user = Auth::user();
        $data["user"]=$user;
        $csv_data=[];
        $csv_data[]=array('#','Missionary Name', 'VM Code' ,'Church Name','Start Date', 'End Date');
     
        $search_data =  missionary::leftjoin('missionaries_fields as mf', 'mf.missionary_id', '=', 'missionaries.id')
            ->leftjoin('fields', 'fields.id', '=', 'mf.field_id')
            ->select('fields.id','fields.church_name','missionaries.lname','missionaries.fname','missionaries.vmcode',DB::raw("CONCAT(missionaries.fname, ' ', missionaries.lname) as missionary_name"),'mf.form_date','mf.to_date');
            
            if (isset($request->searchbychurchname) && $request->searchbychurchname != '') {
                $search_data = $search_data->where('fields.church_name', 'LIKE', "%{$request->searchbychurchname}%");
            }
            // if (!empty($request->date_from)) {
            //     // Convert date_from (MM-DD-YYYY or MM-YYYY) to Y-m-d format
            //     $date_from = \Carbon\Carbon::createFromFormat('m-Y', $request->date_from)->startOfMonth()->format('Y-m-d');
            
            //     $search_data = $search_data->where('mf.form_date', '>=', $date_from);
            // }
            
            // if (!empty($request->date_to)) {
            //     // Convert date_to (MM-DD-YYYY or MM-YYYY) to Y-m-d format
            //     $date_to = \Carbon\Carbon::createFromFormat('m-Y', $request->date_to)->endOfMonth()->format('Y-m-d');
            
            //     $search_data = $search_data->where(function ($query) use ($date_to) {
            //         $query->where('mf.to_date', '<=', $date_to)
            //         ->orWhereNull('mf.to_date');
            //             //  ->orWhere('missionaries.termination_date', '<=', $date_to);
            //     });
            // }
            if (!empty($request->date_from)) {
                // Convert date_from (MM-DD-YYYY or MM-YYYY) to Y-m-d format
                $date_from = \Carbon\Carbon::createFromFormat('m-Y', $request->date_from)->startOfMonth()->format('Y-m-d');
            
                // $search_data = $search_data->where('mf.form_date', '>=', $date_from);
                $search_data = $search_data->where(function($q) use ($date_from) {
                    $q->where(function($q1) use ($date_from){
                        $q1->where('mf.form_date', '<=', $date_from);
                        $q1->where(function($q2) use ($date_from){
                            $q2->where('mf.to_date', '>=', $date_from);
                            $q2->orWhereNull('mf.to_date');
                        });
                    });
                    $q->orWhere('mf.form_date', '>=', $date_from);
                });
            }
            

            if (!empty($request->date_to)) {
                // Convert date_to (MM-DD-YYYY or MM-YYYY) to Y-m-d format
                $date_to = \Carbon\Carbon::createFromFormat('m-Y', $request->date_to)->endOfMonth()->format('Y-m-d');
            
                $search_data = $search_data->where(function ($query) use ($date_to) {                    
                    $query->where('mf.form_date', '<=', $date_to);                    
                    $query->where(function($q) use ($date_to) {
                        $q->where('mf.to_date', '<=', $date_to);
                        $q->orWhereNull('mf.to_date');
                    });                          
                });
            }
            if (isset($request->searchbymissionaryname) && $request->searchbymissionaryname != '') {
                $search_string = $request->searchbymissionaryname;
                $search_data = $search_data->where(function ($q) use ($search_string) {
                    $q->where(
                        DB::raw("CONCAT(
                            COALESCE(missionaries.lname, ''), ' ', COALESCE(missionaries.fname, '')
                        )"),
                        'LIKE',
                        "%{$search_string}%"
                    );
                });
            }
            
            if (!empty($request->searchbyvmcode)) {
                $search_string = $request->searchbyvmcode;
                $search_data = $search_data->where(function ($q) use ($search_string) {
                    $q->where('missionaries.vmcode', 'LIKE', "%{$search_string}%");
                });
            }
      
            
            if (isset($request->sortBy) && $request->sortBy != '') {
                $search_string = $request->sortBy;
                if($search_string == "church_a_z"){
                    $search_data = $search_data->orderBy('fields.church_name', 'ASC')->paginate($this->pagenation_number);
                }
                if($search_string == "form_old_date"){
                    $search_data = $search_data->orderBy('mf.form_date', 'ASC')->paginate($this->pagenation_number);
                }
                if($search_string == "form_new_date"){
                    $search_data = $search_data->orderBy('mf.form_date', 'DESC')->paginate($this->pagenation_number);
                }
                if($search_string == "form_new_date"){
                    $search_data = $search_data->orderBy('mf.form_date', 'DESC')->paginate($this->pagenation_number);
                } 
                if($search_string == "missionary_a_z"){
                    $search_data = $search_data->orderBy(DB::raw("CONCAT(missionaries.fname, ' ', missionaries.lname)"), 'ASC')->paginate($this->pagenation_number);
                } 
   
            } else {
                $search_data = $search_data
                ->where('fields.status',1)
                ->groupBy('fields.id','missionaries.id')
             //   ->havingRaw("CONCAT(missionaries.fname, ' ', missionaries.lname) IS NOT NULL") 
             //   ->orderBy(DB::raw("CONCAT(missionaries.lname, ' ', missionaries.fname)"), 'ASC') // Order by missionary name
             ->orderBy(DB::raw("
             CONCAT(
                 COALESCE(NULLIF(TRIM(missionaries.lname), ''), 'zzzzz'),
                 ' ',
                 COALESCE(NULLIF(TRIM(missionaries.fname), ''), 'zzzzz')
             )
         "), 'ASC')
                ->get();
            }
       

        foreach($search_data as $key=>$val){
            $tmp_arr=[];
            $tmp_arr[0]=$key + 1;
            $tmp_arr[1]=$val->lname . ($val->lname && $val->fname ? ',' : '') . ' ' . $val->fname;
            $tmp_arr[2]=$val->vmcode??'';
            $tmp_arr[3]=$val->church_name??'';
            if (!empty($val->form_date) && strtotime($val->form_date)) {
                $tmp_arr[4] = date("M, Y", strtotime($val->form_date));
            } else {
                $tmp_arr[4] = ''; 
            }
            if (!empty($val->to_date) && strtotime($val->to_date)) {
                $tmp_arr[5] = date("M, Y", strtotime($val->to_date));
            } else {
                $tmp_arr[5] = ''; 
            }
            $csv_data[]= $tmp_arr;
        }
    
        csvDownlaod($csv_data,"missionary_history_by_field_report.csv");    
   

    
}

}
