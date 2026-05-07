<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Redirection;
use Auth;
use DB;
use Excel;

class RedirectionController extends Controller
{
    
    public function redir404() {
    	$DataBag = array();
    	$DataBag['parentMenu'] = 'redirect';
    	$DataBag['childMenu'] = 'r404';

        $DataBag['data'] = Redirection::where('type', '=', '404')->first();

    	return view('dashboard.redirection.r404', $DataBag);
    }


    public function redir404Save(Request $request) {

        $ck = Redirection::where('type', '=', '404')->first();

        if( !empty($ck) ) {
            $updateArr = array();
            $updateArr['source_url'] = trim( $request->input('source_url') );
            $updateArr['updated_by'] = Auth::user()->id;
            Redirection::where('type', '=', '404')->update( $updateArr );
        } else {
           $Redirection = new Redirection;
           $Redirection->source_url = trim( $request->input('source_url') ); 
           $Redirection->type = '404';
           $Redirection->status = 1;
           $Redirection->created_by = Auth::user()->id;
           $Redirection->save();
        }   

        return back()->with('msg', '404 Redirection Saved Successfully.')->with('msg_class', 'alert alert-success');
    }

    public function redir301() {
        $DataBag = array();
        $DataBag['parentMenu'] = 'redirect';
        $DataBag['childMenu'] = 'r301';
        $DataBag['list'] = Redirection::where('type', '=', '301')->where('status', '!=', '3')
        ->orderBy('id', 'desc')->paginate(25);
        return view('dashboard.redirection.r301list', $DataBag);
    }

    public function redir301Add() {
        $DataBag = array();
        $DataBag['parentMenu'] = 'redirect';
        $DataBag['childMenu'] = 'r301';
        return view('dashboard.redirection.r301', $DataBag); 
    }


    public function redir301save(Request $request) {

        $Redirection = new Redirection;
        $Redirection->source_url = trim( $request->input('source_url') );
        $Redirection->destination_url = trim( $request->input('destination_url') );
        $Redirection->created_by = Auth::user()->id;
        $Redirection->type = '301';

        if($Redirection->save()) {
            return back()->with('msg', '301 Redirection Created Successfully')->with('msg_class', 'alert alert-success');
        }

        return back();
    }


    public function redir301Edit( $id ) {
        $DataBag = array();
        $DataBag['parentMenu'] = 'redirect';
        $DataBag['childMenu'] = 'r301';

        $DataBag['data'] = Redirection::findOrFail($id);

        return view('dashboard.redirection.r301', $DataBag);
    }

    public function redir301Update(Request $request, $id) {

        $Redirection = Redirection::findOrFail($id);
        $Redirection->source_url = trim( $request->input('source_url') );
        $Redirection->destination_url = trim( $request->input('destination_url') );
        $Redirection->updated_by = Auth::user()->id;

        if($Redirection->save()) {
            return back()->with('msg', '301 Redirection Created Successfully')->with('msg_class', 'alert alert-success');
        }

        return back();
    }

    public function redir301Delete($id) {

        Redirection::findOrFail($id)->delete();
        return back()->with('msg', '301 Redirection Deleted Successfully')->with('msg_class', 'alert alert-success');   
    }

    public function upload(Request $request) {

        if($request->has('excel')) {

            $insArr = array();
            $c = 1;
            $excel = $request->file('excel');
            $real_path = $excel->getRealPath();
            $file_orgname = $excel->getClientOriginalName();
            $file_size = $excel->getSize();
            $file_ext = strtolower($excel->getClientOriginalExtension());
            if($file_ext == 'xlsx' || $file_ext == 'csv' || $file_ext == 'xls') {
                $data = Excel::load($real_path)->get();
                if( !empty($data) && count($data) > 0) {
                    $headArr = $data->first()->keys()->toArray(); /*$data->getHeading();*/
                    if( !empty($headArr) && count($headArr) >= 2) {
                        if( $headArr[0] == 'old' && $headArr[1] == 'new' ) {
                            foreach ($data as $key => $value) {
                                if( trim($value->old) != '' && trim($value->new) != '' ) {
                                    $arr = array();
                                    $arr['source_url'] = trim( $value->old );
                                    $arr['destination_url'] = trim( $value->new );
                                    $arr['type'] = '301';
                                    $arr['created_by'] = Auth::user()->id;
                                    array_push($insArr, $arr);
                                    $c++;
                                }
                            }
                            if(!empty($insArr)) {
                                Redirection::insert($insArr);
                                return back()->with('msg', $c.' - URL - 301 Redirection Created Successfully')
                                ->with('msg_class', 'alert alert-success');
                            }
                        } else {
                            return back()->with('msg', 'File heading name incorrect, please download sample file')
                            ->with('msg_class', 'alert alert-danger');    
                        }
                    } else {
                        return back()->with('msg', 'File have no heading, please download sample file')
                        ->with('msg_class', 'alert alert-danger');    
                    }
                } else {
                    return back()->with('msg', 'No Record Found')
                    ->with('msg_class', 'alert alert-danger');
                }
            } else {
                return back()->with('msg', 'File Extension Not Correct')
                ->with('msg_class', 'alert alert-danger');
            }
        }
    }
}
