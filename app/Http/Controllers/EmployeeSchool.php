<?php

namespace App\Http\Controllers;

use App\Models\missionary;
use App\Models\missionaries_school;
use Illuminate\Http\Request;
use App\Models\state;
use App\Models\country;
use App\Models\school;
use App\Models\city;
use App\Models\Degree;
use App\Models\Subject;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\User;
use Hash;
use Session;
use Redirect;
use Input;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class EmployeeSchool extends Controller
{
    private $pagenation_number=20;
    public function index(Request $request)
    {
        try{
            $data=[];
            $data=getPermissionArray('missionary/school');
            $user = Auth::user();
            $data["user"]=$user;
            $data["missionary_id"]=(isset($request->missionary_id))?$request->missionary_id:'';
            if( $data["permission_array"]["missionary/school"]["no_view"]==1 || $user->missionary_id== $data["missionary_id"]){
             $data["menu"]="missionary";
            $data["query_string"]='';
            $query_string=[];
            
            $data["missionary_query_string"]='';
            $missionary_query_string=[];
            $data["subject_id_filter"]='';
            $data["degree_id_filter"]='';
            $data["school_id_filter"]='';
        
            $data["missionary_school"]=[];
            $data["country"]=country::orderBy('name','asc')->where('status', '=', 1)->get();
            
            $data["subject"]=Subject::orderBy('name','asc')->where('status', '=', 1)->get();
            $data["degree"]=Degree::orderBy('name','asc')->where('status', '=', 1)->get();
            $data["states"]=[];
            $data["cities"]=[];
            $data["schools"]=state::join('cities', 'cities.state_id', '=', 'states.id')->join('schools', 'schools.city_id', '=', 'cities.id')->select('states.short_code','cities.name as city_name','schools.*')->where('schools.status', '=',1)->where('cities.status', '=',1)->orderBy('schools.name','ASC')->groupBy('schools.id')->get();
            $missionary_id=-1;
                if( $data["missionary_id"]!=''){
                    $missionary_id=$data["missionary_id"];
                }
                //$missionary_data=missionary::find($missionary_id);
                $missionary_data=fetchmissionarydetailsbyid($missionary_id);
              if(!empty($missionary_data)){
                $data["missionary_data"]=$missionary_data;
                $data["missionary_school"]=missionary::join('missionaries_schools', 'missionaries_schools.missionary_id', '=', 'missionaries.id')->leftjoin('users', 'users.id', '=', 'missionaries_schools.updated_by')->leftjoin('users as uc', 'uc.id', '=', 'missionaries_schools.created_by')->join('degrees', 'missionaries_schools.degree_id', '=', 'degrees.id')->join('subjects', 'missionaries_schools.subject_id', '=', 'subjects.id')->leftjoin('schools', 'missionaries_schools.school_id', '=', 'schools.id')->select( 'users.name as username', 'missionaries.fname','missionaries.lname', 'degrees.name as degree_name','subjects.name as subject_name', 'schools.name as school_name','uc.name as created_username','missionaries_schools.*')->where('missionaries_schools.missionary_id', '=',$missionary_id);

                
                if(isset($request->education_subject_id) && $request->education_subject_id!=''){
                    $data["subject_id_filter"]=$request->education_subject_id;
                    $query_string[]="education_subject_id=".$request->education_subject_id;
                    $data["missionary_school"]= $data["missionary_school"]->where('missionaries_schools.subject_id','=',$request->education_subject_id);
                }
                if(isset($request->education_degree_id) && $request->education_degree_id!=''){
                    $data["degree_id_filter"]=$request->education_degree_id;
                    $query_string[]="education_subject_id=".$request->education_degree_id;
                    $data["missionary_school"]= $data["missionary_school"]->where('missionaries_schools.degree_id','=',$request->education_degree_id);
                }
                if(isset($request->education_school_id) && $request->education_school_id!=''){
                    $data["school_id_filter"]=$request->education_school_id;
                    $query_string[]="education_school_id=".$request->education_school_id;
                    $data["missionary_school"]= $data["missionary_school"]->where('missionaries_schools.school_id','=',$request->education_school_id);
                }
                if(!isset($request->page)){
                    $request->page=1;
                }
                
                $data["page"]=(isset($request->page))?$request->page:1;
                $data["offset"]=($request->page-1)*$this->pagenation_number;
                $data["missionary_school"]=$data["missionary_school"]->orderBy('schools.name','ASC')->groupBy('missionaries_schools.id')->paginate($this->pagenation_number);
                $data["missionary_query_string"]=getMissionaryQueryString($request);
                   $data["missionary_query_string_back"]=str_replace("page_missionary=","page=", $data["missionary_query_string"]);
                if(!empty($query_string)){
                    $data["query_string"]="&&".implode("&&",$query_string);
                }
                $data["menu"]="missionary";
                $data["per_page"]=$this->pagenation_number;
                
                return view('missionary.school.list', ["data"=>$data])->with('count', 1);
              }else{
                return Redirect::to('missionary')->withErrors(["message"=>'Invalid Missionary'])  ->withInput();
              }
            }else{
                return Redirect::to('missionary')->with('error', "Access denied .");
            }
        }catch(exception $e){
                
        }
    }

    public function create(Request $request)
    {
        try{
            $data=[];
            $data=getPermissionArray('missionary/school');
            $user = Auth::user();
            $data["user"]=$user;
            $data["missionary_id"]=(isset($request->missionary_id))?$request->missionary_id:'';
            $data["query_string"]=$_SERVER['QUERY_STRING'];
            if(  $data["permission_array"]["missionary/school"]["is_add"]==1   || $user->missionary_id==$data["missionary_id"] || $user->role_id==1 ){
                
                $data["menu"]="missionary";
                 $data["missionary_query_string"]=getMissionaryQueryString($request);;
                $data["missionary_query_string_back"]=str_replace("page_missionary=","page=", $data["missionary_query_string"]);
                
                $missionary_id=-1;
                    if( $data["missionary_id"]!=''){
                        $missionary_id=$data["missionary_id"];
                    }
                  //  $missionary_data=missionary::find($missionary_id);
                  $missionary_data=fetchmissionarydetailsbyid($missionary_id);

                if(!empty($missionary_data)){
                    $data["subject"]=subject::orderBy('name','asc')->where('status', '=', 1)->get();
                    $data["degree"]=degree::orderBy('name','asc')->where('status', '=', 1)->get();
                    $data["schools"]=state::join('cities', 'cities.state_id', '=', 'states.id')->join('schools', 'schools.city_id', '=', 'cities.id')->select('states.short_code','cities.name as city_name','schools.*')->where('schools.status', '=',1)->where('cities.status', '=',1)->orderBy('schools.name','ASC')->groupBy('schools.id')->get();
                    $data["missionary_data"]=$missionary_data;
                return view('missionary.school.add', ["data"=>$data]);
                }else{
                    return Redirect::to('missionary'.'?'.$data["query_string"])->withErrors(["message"=>'Invalid Missionary'])  ->withInput();
                }
            }else{
                return Redirect::to('missionary'.'?'.$data["query_string"])->with('error', "Access denied .");
            }
          
        }catch(exception $e){
                
        }
    }
    public function store(Request $request)
    {
        try{
            $query_string=$request->query_string;
            $missionary_query_string=$request->missionary_query_string;
                $validator = Validator::make($request->all(), [
                    'school_id' => 'required',
                    'subject_id' => 'required',
                    'degree_id' => 'required'
                ]);
                if($validator->fails()){
                    $error=$validator->errors();
                    $messages=$error->messages();
                    $err_msg=[];
                    if(!empty($messages)){
                        foreach($messages as $msg){
                            $err_msg[]=implode(",",$msg);
                        }
                    }
                    return Redirect::to('missionary/school/add?'.$query_string)->withErrors(["message"=>implode(",",$err_msg)])  ->withInput();
                }else{

                    $missionary_school_exist=missionaries_school::where('missionary_id', '=',$request->missionary_id)->where('school_id', '=',$request->school_id)->where('degree_id', '=',$request->degree_id)->where('subject_id', '=',$request->subject_id)->first();
                    if(!empty($missionary_school_exist)){
                        return Redirect::to('missionary/school/add?missionary_id='.$request->missionary_id)->withErrors(["message"=>"Duplicate Entry"])  ->withInput();
                    }else{
                        $school_date=null;
                        if(isset($request->school_date) && trim($request->school_date)!=''){
                            $school_date=date('Y-m-d',strtotime("01-01-".$request->school_date));
                        }
                        missionaries_school::create([
                            'missionary_id'=>$request->missionary_id,
                            'school_id'=>$request->school_id,
                            'subject_id'=>$request->subject_id,
                            'degree_id'=>$request->degree_id,
                            'school_date'=>$school_date,
                            'created_by'=>Auth::user()->id
                            
                        ]);
                        return Redirect::to('missionary/school/?'.$query_string)->with('success', "Missionary School added successfully.");
                    }
                    }
                    
                   
           
            
        }catch(exception $e){
                
        }
    }
    public function school_ajax(Request $request){
        try{  
            $data=[];
            $data=getPermissionArray('missionary/school');
          //  $module_permission=getpermission();
            $user = Auth::user();
            $data["user"]=$user;
           
            $missionary_id=-1;
            if( isset($request->missionary_id) && $request->missionary_id!=''){
                $missionary_id=$request->missionary_id;
            }
            $missionary_data=missionary::find($missionary_id);
          if(!empty($missionary_data)){
            $missionary_school=missionary::join('missionaries_schools', 'missionaries_schools.missionary_id', '=', 'missionaries.id')->leftjoin('users', 'users.id', '=', 'missionaries_schools.updated_by')->leftjoin('users as uc', 'uc.id', '=', 'missionaries_schools.created_by')->join('degrees', 'missionaries_schools.degree_id', '=', 'degrees.id')->leftjoin('subjects', 'missionaries_schools.subject_id', '=', 'subjects.id')->leftjoin('schools', 'missionaries_schools.school_id', '=', 'schools.id')->select( 'users.name as username', 'missionaries.fname','missionaries.lname', 'degrees.name as degree_name','subjects.name as subject_name', 'schools.name as school_name','uc.name as created_username','missionaries_schools.*')->where('missionaries_schools.missionary_id', '=',$missionary_id);
            if(isset($request->degree_id)){

                $missionary_school=$missionary_school->where('degree_id', '=', $request->degree_id);
            }
            if(isset($request->school_id)){

                $missionary_school=$missionary_school->where('school_id', '=', $request->school_id);
            }
            if(isset($request->subject_id)){

                $missionary_school=$missionary_school->where('subject_id', '=', $request->subject_id);
            }
           $data["missionary_school"]= $missionary_school->orderBy('schools.name','ASC')->groupBy('missionaries_schools.id')->paginate($this->pagenation_number);
           $html='';
           $offset=($request->page-1)*$this->pagenation_number;
           
          
            foreach( $data["missionary_school"] as $key=>$missionaryschool){
                $html.='<tr>';
                $html.='<td  style="width:2%;">';
                $html.=$offset+$key+1 ;
                $html.='</td>';
               // $html.='<td style="width:13%;text-align:left;">'.$missionaryschool->fname.' '.$missionaryschool->lname.'</td>';
                $html.='<td style="width:20%;">'.$missionaryschool->school_name.'</td>';
                $html.='<td style="width:10%;">'.$missionaryschool->degree_name.'</td>';
                $html.='<td style="width:10%;">'.$missionaryschool->subject_name.'</td>';
                $html.='<td style="width:10%;">'.(($missionaryschool->school_date!='')?date('Y', strtotime($missionaryschool->school_date)):'').'</td>';
                if( $missionaryschool->username==''){
                    $missionaryschool->username= $missionaryschool->created_username;
                }
               
               
                if( $missionaryschool->updated_at==''){
                    $missionaryschool->updated_at= $missionaryschool->created_at;
                }
                $html.='<td style="width:10%;">'.$missionaryschool->username.'</td>';
                $html.='<td style="width:5%;">'.(($missionaryschool->updated_at!='')?date('m/d/Y', strtotime($missionaryschool->updated_at)):'').'</td>';
                $html.='<td class="text-nowrap" style="width:20%">';

                $html.='<div>';
                if($data["user"]["role_id"]==1 || ($data["is_read"]==1 || $missionary_id==$data["user"]["missionary_id"])){
                    $html.='<button  style="padding-right: 2px;padding-left: 2px;" class="btn btn-link " type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit" onclick="doedit('.$missionaryschool->id.','.$missionaryschool->missionary_id.')"><span class=" fa fa-edit" ></span></button>';
                }
                if($data["user"]["role_id"]==1 || ($data["is_delete"]==1 || $missionary_id==$data["user"]["missionary_id"])){
                    $html.='<button  style="padding-right: 2px;padding-left: 2px;" class="btn btn-link " type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete" onclick="dodelete('.$missionaryschool->id.','.$missionaryschool->missionary_id.')"><span class="fa fa-trash" ></span></button>';
                }
                $html.=' </div>';
                $html.='</td>';
            
                $html.='</tr>';
            }
            if(trim($html)!=''){
                $pagenation= \View::make('include.pagenation', ['paginator' =>$data["missionary_school"]])->render();
            }else{
                $pagenation= '';
                $html.='<tr>';
                $html.='<td  style="width:100%" class="text-nowrap" colspan="15">';
                $html.='No Data Found' ;
                $html.='</td>';
            }
            if(!isset($request->page)){
                $request->page=1;
            }
            $page=(isset($request->page))?$request->page:1;
            $offset=($request->page-1)*$this->pagenation_number;
            $start_page_number=$offset+1;
            $end_page_number=$data["missionary_school"]->total();
            if($end_page_number>=($start_page_number+$this->pagenation_number)){
                $end_page_number=$offset+$this->pagenation_number;
            }
            return response()->json([
                'message' => 'missionary school Data',
                'html'=>$html,
                'pagenation'=>$pagenation,
                'start_page_number'=>$start_page_number,
                'end_page_number'=>$end_page_number,
                'total_records'=>$data["missionary_school"]->total(),
                'status'=>1
            ], 200);
          }else{
            return response()->json(['message' => 'Missionary Not Exist','status'=>0], 401);
          }
         }catch(exception $e){
                        
        }
    }
    public function edit(Request $request,$id)
    {
        try{
          
            $data=[];
            $data=getPermissionArray('missionary/school');
           // $module_permission=getpermission();
            $user = Auth::user();
            $data["user"]=$user;
            $data["missionary_id"]=(isset($request->missionary_id))?$request->missionary_id:'';
            $data["query_string"]=$_SERVER['QUERY_STRING'];
            if(  $data["permission_array"]["missionary/school"]["is_read"]==1   || $data["user"]->missionary_id==$data["missionary_id"] || $user->role_id==1 ){
          
          
            $data["menu"]="missionary";
            $data["missionary_query_string"]=getMissionaryQueryString($request);;
            $data["missionary_query_string_back"]=str_replace("page_missionary=","page=", $data["missionary_query_string"]);
            $data["missionary_id"]=(isset($request->missionary_id))?$request->missionary_id:'';
            $missionary_id=-1;
                if( $data["missionary_id"]!=''){
                    $missionary_id=$data["missionary_id"];
                }
               // $missionary_data=missionary::find($missionary_id);
               $missionary_data=fetchmissionarydetailsbyid($missionary_id);
              if(!empty($missionary_data)){
                $data["subject"]=subject::orderBy('name','asc')->where('status', '=', 1)->get();
                $data["degree"]=degree::orderBy('name','asc')->where('status', '=', 1)->get();
                $data["schools"]=state::join('cities', 'cities.state_id', '=', 'states.id')->join('schools', 'schools.city_id', '=', 'cities.id')->select('states.short_code','cities.name as city_name','schools.*')->where('schools.status', '=',1)->where('cities.status', '=',1)->orderBy('schools.name','DESC')->groupBy('schools.id')->get();
                $data["missionary_data"]=$missionary_data;
                $data["missionary_school"]=missionary::join('missionaries_schools', 'missionaries_schools.missionary_id', '=', 'missionaries.id')->join('users', 'users.id', '=', 'missionaries.created_by')->join('degrees', 'missionaries_schools.degree_id', '=', 'degrees.id')->join('subjects', 'missionaries_schools.subject_id', '=', 'subjects.id')->leftjoin('schools', 'missionaries_schools.school_id', '=', 'schools.id')->select( 'users.name as username', 'missionaries.fname','missionaries.lname', 'degrees.name as degree_name','subjects.name as subject_name', 'schools.name as school_name','missionaries_schools.*')->where('missionaries_schools.missionary_id', '=',$missionary_id)->where('missionaries_schools.id', '=',$id)->first();
                if($data["missionary_school"]->school_date!=''){
                    $data["missionary_school"]->school_date=date("m/d/Y",strtotime($data["missionary_school"]->school_date));
                }
               return view('missionary.school.edit', ["data"=>$data]);
            }else{
                return Redirect::to('missionary?'. $data["missionary_query_string"])->withErrors(["message"=>'Invalid Missionary'])  ->withInput();
            }
        }else{
            return Redirect::to('missionary'.'?'.$data["query_string"])->with('error', "Access denied .");
        }
        }catch(exception $e){
                
        }
    }
    public function update(Request $request, $id)
    {
        try{
            $query_string=$request->query_string;
            $missionary_query_string=$request->missionary_query_string;
               $missionaries_school_data=missionaries_school::find($id);
                if( $missionaries_school_data!=null){
                    $validator = Validator::make($request->all(), [
                        'school_id' => 'required',
                        'subject_id' => 'required',
                        'degree_id' => 'required'
                    ]);
                    if($validator->fails()){
                        $error=$validator->errors();
                        $messages=$error->messages();
                        $err_msg=[];
                        if(!empty($messages)){
                            foreach($messages as $msg){
                                $err_msg[]=implode(",",$msg);
                            }
                        }
                        return Redirect::to('missionary/school/edit/'.$id.'?'.$query_string)->withErrors(["message"=>implode(",",$err_msg)])  ->withInput();
                    }else{
                        $missionary_school_exist=missionaries_school::where('missionary_id', '=',$request->missionary_id)->where('school_id', '=',$request->school_id)->where('degree_id', '=',$request->degree_id)->where('subject_id', '=',$request->subject_id)->where('id', '!=',$missionaries_school_data->id)->first();
                        if(!empty($missionary_school_exist)){
                            return Redirect::to('missionary/school/edit/'.$id.'?'.$query_string)->withErrors(["message"=>"Duplicate Entry"])  ->withInput();
                        }else{
                            $school_date=$missionaries_school_data->school_date;
                           
                            if(isset($request->school_date) && trim($request->school_date)!=''){
                                $school_date=date('Y-m-d',strtotime("01-01-".$request->school_date));
                            }
                            
                            $missionaries_school_data->school_id=$request->school_id;
                            $missionaries_school_data->subject_id=$request->subject_id;
                            $missionaries_school_data->degree_id=$request->degree_id;
                            $missionaries_school_data->school_date=$school_date;
                            $missionaries_school_data->updated_by =Auth::user()->id;
                
                            $missionaries_school_data->save();
                            return Redirect::to('missionary/school?'.$query_string)->with('success', "Missionary School updated successfully.");
                     }
                    }
                }else{
                    return Redirect::to('missionary/school/'.$id.'?'.$query_string)->withErrors(["message"=>'Missionary School Not Exist'])  ->withInput();
                }
            
        }catch(exception $e){
                
        }
    }

    public function destroy($id)
    {
        try{
            $data=[];
             if(missionaries_school::find($id)!=null){
                missionaries_school::find($id)->delete();
               return response()->json(['message' => 'Missionary School deleted successfully','status'=>1], 200);
             }else{
               return response()->json(['message' => 'Missionary School not found','status'=>0], 401);
             }
            
           
         }catch(exception $e){
            
         }
    }
}