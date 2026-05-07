<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FrmBuilder\FrmMaster;
use App\Models\FrmBuilder\FrmFields;
use App\Models\FrmBuilder\FrmSettings;
use App\Models\FrmBuilder\FrmData;
use App\Models\FrmBuilder\FrmCategories;
use Session;
use Auth;
use Excel;
use Mail;
use DB;
use App\Models\Languages;

class FormBuilder extends Controller
{
    
    public function forms() {

        $DataBag = array();
        
        $DataBag['languages'] = Languages::get(); 
		$DataBag['translate_lang_id'] = Auth::user()->translate_lang_id;

		$roles=Auth::user()->roles;
		$superadmin=0;
		 if( isset($roles) ){
		 foreach($roles as $ur){
				 if($ur->id==1){
					 $superadmin=1; 
				 }
			 }
		 }
 
		$DataBag['superadmin'] =$superadmin;
 
        $DataBag['parentMenu'] = 'FrmB';
        $DataBag['childMenu'] = 'frms';
    	$DataBag['all_forms'] = FrmMaster::where('status', '!=', '3')->orderBy('id', 'desc')->where('parent_id', '=', '0')->get();
    	return view('dashboard.FormBuilder.forms', $DataBag);
    }

    public function createForm() {

        $DataBag = array();
        $DataBag['languages'] = Languages::get();
        $DataBag['language_id'] =  1;
        $DataBag['parentMenu'] = 'FrmB';
        $DataBag['childMenu'] = 'frm_crte';
        $DataBag['cats'] = FrmCategories::where('status', '!=', '3')->orderBy('category_name', 'asc')->get();
    	return view('dashboard.FormBuilder.create_form', $DataBag);
    }

    public function saveForm(Request $request) {

    	$FrmMaster = new FrmMaster;
    	$FrmMaster->frm_name = preg_replace('/\s+/', '', trim($request->input('frm_name')));
    	$FrmMaster->frm_heading = trim(ucfirst($request->input('frm_heading')));
    	$FrmMaster->frm_css_class = preg_replace('/\s+/', '', trim($request->input('frm_css_class')));
    	$FrmMaster->frm_css_id = preg_replace('/\s+/', '', trim($request->input('frm_css_id')));
    	$FrmMaster->frm_details = trim($request->input('frm_details'));
    	$FrmMaster->is_email_receive = trim($request->input('is_email_receive'));
        $FrmMaster->frm_bg_color = trim($request->input('frm_bg_color'));
        $FrmMaster->frm_txt_color = trim($request->input('frm_txt_color'));

        $FrmMaster->thankyou_url = trim($request->input('thankyou_url'));

        $is_captcha = 0;
        if( $request->is_captcha != '' && $request->is_captcha != null ) {
        $is_captcha = $request->is_captcha;
        }
        $FrmMaster->is_captcha = $is_captcha;
        $category_id = 0;
        if( $request->exists('category_id') ) {
        $category_id = trim($request->input('category_id'));
        }
    	$FrmMaster->category_id = $category_id;
    	$Final_emARR = array();
    	$emARR = $request->input('receive_emails');
    	if( !empty($emARR) && count($emARR) ) {
    		foreach( $emARR as $v ) {
    			if( $v != '' ) {
    				array_push($Final_emARR, $v);
    			}
    		}
    	}
    	
    	$FrmMaster->receive_emails = trim(serialize($Final_emARR));
    	$FrmMaster->status = trim($request->input('status'));
    	$FrmMaster->frm_raw_html = '';
    	$frm_auto_id = trim(md5(microtime(TRUE).rand('123456','999999')));
    	$FrmMaster->frm_auto_id = $frm_auto_id;
    	$FrmMaster->frm_scode = "[#FORM_".$frm_auto_id."#]";
    	$FrmMaster->created_at = date('Y-m-d H:i:s');
    	$res = $FrmMaster->save();
    	$frm_btn_name = $request->input('frm_btn_name') == "" ? "Submit" : trim(ucfirst($request->input('frm_btn_name')));

    	if( $res ) {

    		$FrmFields = new FrmFields;
    		$FrmFields->form_id = $frm_auto_id;
    		$FrmFields->field_name = "ok_".$frm_auto_id;
    		$FrmFields->field_type = "BUTTON";
    		$FrmFields->css_class = "btn btn-primary";
    		$FrmFields->default_value = $frm_btn_name;
    		$FrmFields->field_order = 100;
    		$FrmFields->status = '1';
    		$FrmFields->created_at = date('Y-m-d H:i:s');
    		if( $FrmFields->save() ) {

    			$insert_id = $FrmFields->id;
    			$rawHTML = getFieldHTML( $frm_auto_id, $insert_id );
    			$fieldHTML = htmlentities( $rawHTML );

    			FrmFields::where('id', $insert_id)->update([ 'field_raw_html' => $fieldHTML ]);

    			$frmRawHtml = htmlentities(getFormHTML( $frm_auto_id ));

    			FrmMaster::where('frm_auto_id', '=', $frm_auto_id)->update([ 'frm_raw_html' => $frmRawHtml ]);

    			return redirect()->route( 'crte_frm_flds', array('form_id' => $frm_auto_id) )->with('msg', 'Form created successfully. Now please setup your form fields.')->with('msg_class', 'alert alert-success');
    		}
    	}

    	return back()->with('msg', 'Something went wrong! Try again.')->with('msg_class', 'alert alert-danger');

    }

    public function editForm($form_id, Request $request) {
        $DataBag = array();
        $DataBag['parentMenu'] = 'FrmB';
        $DataBag['childMenu'] = 'frm_crte';


        $DataBag['language_id'] =  $request->input('language_id'); 
		$DataBag['content_id'] = $form_id;

       
        $DataBag['languages'] = Languages::get();

        $DataBag['cats'] = FrmCategories::where('status', '!=', '3')->orderBy('category_name', 'asc')->get();
    	if( $form_id != '' && $form_id != null ) {


            if($DataBag['language_id']==1){
                $form_details = FrmMaster::where('frm_auto_id', '=', trim($form_id))->first();
            }
            else{
                $form_details = FrmMaster::where('parent_id',$form_id)->where('language_id', $DataBag['language_id'])->first();
            
                if($form_details==null){ 
                    $form_details = FrmMaster::where('frm_auto_id',$form_id)->where('language_id', 1)->first(); 
                }
        
            }
  
    		// $form_details = FrmMaster::where('frm_auto_id', '=', trim($form_id))->first();
    		$btn_details = FrmFields::where('form_id', '=', trim($form_id))
    		->where('field_type', '=', 'BUTTON')->first();
    		if(!empty($form_details)) {
                $DataBag['form_details'] = $form_details;
                $DataBag['btn_details'] = $btn_details;
    			return view('dashboard.FormBuilder.create_form', $DataBag);		
    		}
    	}
        
        
    	return redirect()->route('frms');
    }

    public function editFormSave(Request $request, $form_id) {

        $language_id = 1;//$request->input('language_id');
        if($language_id!=1){
        $DataBag['dynaContent'] = FrmMaster::where('parent_id',$form_id)->where('language_id', $language_id)->first();
 
        if(isset($DataBag['dynaContent']) && $DataBag['dynaContent']!=null){

         $form_id = $DataBag['dynaContent']['frm_auto_id'];
            
    	$dataArr = array();
    	$dataArr['frm_name'] = preg_replace('/\s+/', '', trim($request->input('frm_name')));
    	$dataArr['frm_heading'] = trim(ucfirst($request->input('frm_heading')));
    	$dataArr['frm_css_class'] = preg_replace('/\s+/', '', trim($request->input('frm_css_class')));
    	$dataArr['frm_css_id'] = preg_replace('/\s+/', '', trim($request->input('frm_css_id')));
    	$dataArr['frm_details'] = trim($request->input('frm_details'));
    	$dataArr['is_email_receive'] = trim($request->input('is_email_receive'));
        $dataArr['frm_bg_color'] = trim($request->input('frm_bg_color'));
        $dataArr['frm_txt_color'] = trim($request->input('frm_txt_color'));

        $dataArr['thankyou_url'] = trim($request->input('thankyou_url'));


        $dataArr['is_captcha'] = 0;
        if( $request->is_captcha != '' && $request->is_captcha != null ) {
        $dataArr['is_captcha'] = $request->is_captcha;
        }
        $category_id = 0;
        if( $request->exists('category_id') ) {
        $category_id = trim($request->input('category_id'));
        }
        $dataArr['category_id'] = $category_id;
    	
    	$Final_emARR = array();
    	$emARR = $request->input('receive_emails');
    	if( !empty($emARR) && count($emARR) ) {
    		foreach( $emARR as $v ) {
    			if( $v != '' ) {
    				array_push($Final_emARR, $v);
    			}
    		}
    	}

    	$dataArr['receive_emails'] = trim(serialize($Final_emARR));
    	$dataArr['status'] = trim($request->input('status'));
    	$dataArr['updated_at'] = date('Y-m-d H:i:s');

    	$frm_btn_name = trim(ucfirst($request->input('frm_btn_name')));
    	$dataArr2 = array();
    	$dataArr2['default_value'] = $frm_btn_name;
        $dataArr2['updated_at'] = date('Y-m-d H:i:s');
        
        
    	
        $res1 = FrmMaster::where('frm_auto_id', '=', trim($form_id))->update($dataArr);

    	$res2 = FrmFields::where('form_id', '=', trim($form_id))
    	->where('field_type', '=', 'BUTTON')->update($dataArr2);

    	$fld_row_id = FrmFields::where('form_id', '=', trim($form_id))
    	->where('field_type', '=', 'BUTTON')->select('id')->first();

    	$fld_html = htmlentities( getFieldHTML( $form_id, $fld_row_id->id ) );

    	FrmFields::where('form_id', '=', trim($form_id))
    	->where('field_type', '=', 'BUTTON')->update([ 'field_raw_html' => $fld_html ]);

    	
    	$FormRawHtml = htmlentities( getFormHTML( $form_id ) );
    	FrmMaster::where('frm_auto_id', '=', trim($form_id))->update([ 'frm_raw_html' => $FormRawHtml ]);

    	if( $res1 && $res2 ) {

    		return back()->with('msg', 'Form updated successfully, thankyou.')->with('msg_class', 'alert alert-success');
    	}

    	return back()->with('msg', 'Something went wrong!')->with('msg_class', 'btn btn-danger');



        }

        else{
            $FrmMaster = new FrmMaster;

        $FrmMaster->parent_id = $form_id;
        $FrmMaster->language_id = trim( $request->input('language_id') );
    	$FrmMaster->frm_name = preg_replace('/\s+/', '', trim($request->input('frm_name')));
    	$FrmMaster->frm_heading = trim(ucfirst($request->input('frm_heading')));
    	$FrmMaster->frm_css_class = preg_replace('/\s+/', '', trim($request->input('frm_css_class')));
    	$FrmMaster->frm_css_id = preg_replace('/\s+/', '', trim($request->input('frm_css_id')));
    	$FrmMaster->frm_details = trim($request->input('frm_details'));
    	$FrmMaster->is_email_receive = trim($request->input('is_email_receive'));
        $FrmMaster->frm_bg_color = trim($request->input('frm_bg_color'));
        $FrmMaster->frm_txt_color = trim($request->input('frm_txt_color'));

        $FrmMaster->thankyou_url = trim($request->input('thankyou_url'));

        $is_captcha = 0;
        if( $request->is_captcha != '' && $request->is_captcha != null ) {
        $is_captcha = $request->is_captcha;
        }
        $FrmMaster->is_captcha = $is_captcha;
        $category_id = 0;
        if( $request->exists('category_id') ) {
        $category_id = trim($request->input('category_id'));
        }
    	$FrmMaster->category_id = $category_id;
    	$Final_emARR = array();
    	$emARR = $request->input('receive_emails');
    	if( !empty($emARR) && count($emARR) ) {
    		foreach( $emARR as $v ) {
    			if( $v != '' ) {
    				array_push($Final_emARR, $v);
    			}
    		}
    	}
    	
    	$FrmMaster->receive_emails = trim(serialize($Final_emARR));
    	$FrmMaster->status = trim($request->input('status'));
    	$FrmMaster->frm_raw_html = '';
    	$frm_auto_id = trim(md5(microtime(TRUE).rand('123456','999999')));
    	$FrmMaster->frm_auto_id = $frm_auto_id;
    	$FrmMaster->frm_scode = "[#FORM_".$frm_auto_id."#]";
    	$FrmMaster->created_at = date('Y-m-d H:i:s');
    	$res = $FrmMaster->save();
    	$frm_btn_name = $request->input('frm_btn_name') == "" ? "Submit" : trim(ucfirst($request->input('frm_btn_name')));

    	if( $res ) {

    		$FrmFields = new FrmFields;
    		$FrmFields->form_id = $frm_auto_id;
    		$FrmFields->field_name = "ok_".$frm_auto_id;
    		$FrmFields->field_type = "BUTTON";
    		$FrmFields->css_class = "btn btn-primary";
    		$FrmFields->default_value = $frm_btn_name;
    		$FrmFields->field_order = 100;
    		$FrmFields->status = '1';
    		$FrmFields->created_at = date('Y-m-d H:i:s');
    		if( $FrmFields->save() ) {

    			$insert_id = $FrmFields->id;
    			$rawHTML = getFieldHTML( $frm_auto_id, $insert_id );
    			$fieldHTML = htmlentities( $rawHTML );

    			FrmFields::where('id', $insert_id)->update([ 'field_raw_html' => $fieldHTML ]);

    			$frmRawHtml = htmlentities(getFormHTML( $frm_auto_id ));

    			FrmMaster::where('frm_auto_id', '=', $frm_auto_id)->update([ 'frm_raw_html' => $frmRawHtml ]);

    			// return redirect()->route( 'crte_frm_flds', array('form_id' => $frm_auto_id, 'language_id'=>1) )->with('msg', 'Form created successfully. Now please setup your form fields.')->with('msg_class', 'alert alert-success');
            
                return back()->with('msg', 'Form updated successfully, thankyou.')->with('msg_class', 'alert alert-success');
            }
    	}

    	return back()->with('msg', 'Something went wrong! Try again.')->with('msg_class', 'alert alert-danger');

        }
    }
    else{

    	$dataArr = array();
    	$dataArr['frm_name'] = preg_replace('/\s+/', '', trim($request->input('frm_name')));
    	$dataArr['frm_heading'] = trim(ucfirst($request->input('frm_heading')));
    	$dataArr['frm_css_class'] = preg_replace('/\s+/', '', trim($request->input('frm_css_class')));
    	$dataArr['frm_css_id'] = preg_replace('/\s+/', '', trim($request->input('frm_css_id')));
    	$dataArr['frm_details'] = trim($request->input('frm_details'));
    	$dataArr['is_email_receive'] = trim($request->input('is_email_receive'));
        $dataArr['frm_bg_color'] = trim($request->input('frm_bg_color'));
        $dataArr['frm_txt_color'] = trim($request->input('frm_txt_color'));

        $dataArr['thankyou_url'] = trim($request->input('thankyou_url'));


        $dataArr['is_captcha'] = 0;
        if( $request->is_captcha != '' && $request->is_captcha != null ) {
        $dataArr['is_captcha'] = $request->is_captcha;
        }
        $category_id = 0;
        if( $request->exists('category_id') ) {
        $category_id = trim($request->input('category_id'));
        }
        $dataArr['category_id'] = $category_id;
    	
    	$Final_emARR = array();
    	$emARR = $request->input('receive_emails');
    	if( !empty($emARR) && count($emARR) ) {
    		foreach( $emARR as $v ) {
    			if( $v != '' ) {
    				array_push($Final_emARR, $v);
    			}
    		}
    	}

    	$dataArr['receive_emails'] = trim(serialize($Final_emARR));
    	$dataArr['status'] = trim($request->input('status'));
    	$dataArr['updated_at'] = date('Y-m-d H:i:s');

    	$frm_btn_name = trim(ucfirst($request->input('frm_btn_name')));
    	$dataArr2 = array();
    	$dataArr2['default_value'] = $frm_btn_name;
    	$dataArr2['updated_at'] = date('Y-m-d H:i:s');
    	
        $res1 = FrmMaster::where('frm_auto_id', '=', trim($form_id))->update($dataArr);

    	$res2 = FrmFields::where('form_id', '=', trim($form_id))
    	->where('field_type', '=', 'BUTTON')->update($dataArr2);

    	$fld_row_id = FrmFields::where('form_id', '=', trim($form_id))
    	->where('field_type', '=', 'BUTTON')->select('id')->first();

    	$fld_html = htmlentities( getFieldHTML( $form_id, $fld_row_id->id ) );

    	FrmFields::where('form_id', '=', trim($form_id))
    	->where('field_type', '=', 'BUTTON')->update([ 'field_raw_html' => $fld_html ]);

    	
    	$FormRawHtml = htmlentities( getFormHTML( $form_id ) );
    	FrmMaster::where('frm_auto_id', '=', trim($form_id))->update([ 'frm_raw_html' => $FormRawHtml ]);

    	if( $res1 && $res2 ) {

    		return back()->with('msg', 'Form updated successfully, thankyou.')->with('msg_class', 'alert alert-success');
    	}

    	return back()->with('msg', 'Something went wrong!')->with('msg_class', 'btn btn-danger');
 
    }
 
    }


    public function createFormFields($form_id) {
        $DataBag = array();
        $DataBag['parentMenu'] = 'FrmB';
        $DataBag['childMenu'] = 'frm_crte';
    	if( $form_id != '' && $form_id != null ) {

    		$DataBag['form_details'] = FrmMaster::where('frm_auto_id', '=', trim($form_id))->first();
    		$DataBag['field_details'] = FrmFields::where('form_id', '=', trim($form_id))->orderBy('field_order', 'asc')->get();
    		return view('dashboard.FormBuilder.form_fields', $DataBag);
    	}

    	return redirect()->route('frms');
    } 


    public function showFormFields($form_id,Request $request ) {
        $DataBag = array();
        $DataBag['parentMenu'] = 'FrmB';
        $DataBag['childMenu'] = 'frm_crte';
    	if( $form_id != '' && $form_id != null ) {
 
            $DataBag['language_id'] =  $request->input('language_id'); 
            $DataBag['content_id'] = $form_id;
    
           
            $DataBag['languages'] = Languages::get();
       
                // if($DataBag['language_id']==1){
                    $form_details = FrmMaster::where('frm_auto_id', '=', trim($form_id))->first();
                // }
                // else{
                //     $form_details = FrmMaster::where('parent_id',$form_id)->where('language_id', $DataBag['language_id'])->first();
                
                    if($form_details==null){ 

                        return back()->with('msg', 'Create the Main Form First .')->with('msg_class', 'alert alert-danger');

                        // $form_details = FrmMaster::where('frm_auto_id',$form_id)->where('language_id', 1)->first(); 
                    }
            
                // }
 
            $DataBag['form_details'] = $form_details;
            
            $DataBag['field_details'] = FrmFields::where('form_id', '=', trim($form_details->frm_auto_id))->orderBy('field_order', 'asc')->get();
    		
            
    		return view('dashboard.FormBuilder.form_fields', $DataBag);
    	}

    	return redirect()->route('frms');	
    }

    public function addFields(Request $request) {

    	$rawHTML = "";

    	if( $request->input('ACTION_TYPE') == 'NEW_INSERT') {

    		$field_token = md5(time());
    		$form_id = trim($request->input('FORM_ID'));

    		$FrmFields = new FrmFields;
    		
    		if( $request->has('FORM_ID') ) {
    			
    			$FrmFields->form_id = trim($request->input('FORM_ID'));
    		}
    		if( $request->has('field_type') ) {
    			
    			$FrmFields->field_type = trim($request->input('field_type'));
    		}
    		$field_name = preg_replace('/\s+/', '', trim($request->input('field_name')));
    		if( $request->has('field_name') ) {
    		
    			$FrmFields->field_name = trim(strtolower($field_name)."_".$field_token);
    		}
    		if( $request->has('display_text') ) {
    		
    			$FrmFields->tbl_header_text = trim($request->input('display_text'));
    		}
    		if( $request->has('placeholder') ) {
    			
    			$FrmFields->placeholder = trim($request->input('placeholder'));
    		}
    		if( $request->has('display_text') ) {
    			
    			$FrmFields->display_text = trim($request->input('display_text'));
    		}
    		if( $request->has('is_required') ) {
    			
    			$FrmFields->is_required = trim($request->input('is_required'));
    		}
    		if( $request->has('default_value') ) {
    			
    			$FrmFields->default_value = trim($request->input('default_value'));
    		}
    		if( $request->has('min_value') ) {
    			
    			$FrmFields->min_value = trim($request->input('min_value'));
    		}
    		if( $request->has('max_value') ) {
    			
    			$FrmFields->max_value = trim($request->input('max_value'));
    		}
    		if( $request->has('css_class') ) {
    		
    			$FrmFields->css_class = preg_replace('/\s+/', '', trim($request->input('css_class')));
			}
			if( $request->has('css_id') ) {
    			
    			$FrmFields->css_id = preg_replace('/\s+/', '', trim($request->input('css_id')));
			}
			if( $request->has('title') ) {
    			
    			$FrmFields->title = trim($request->input('title'));
    		}
    		if( $request->has('help_text') ) {
    			
    			$FrmFields->help_text = trim($request->input('help_text'));
    		}
    		if( $request->has('status') ) {
    			
    			$FrmFields->status = trim($request->input('status'));
    		}
            if( $request->has('bgcolor') ) {
                
                $FrmFields->bgcolor = trim($request->input('bgcolor'));
            }
            if( $request->has('color') ) {
                
                $FrmFields->color = trim($request->input('color'));
            }
 
            if( $request->has('font') ) {
                
                $FrmFields->font = trim($request->input('font'));
            }
            if( $request->has('size') ) {
                
                $FrmFields->size = trim($request->input('size'));
            }
    		
    		$FrmFields->created_at = date('Y-m-d H:i:s');
    		
    		if( $request->has('options') ) {
	    		
	    		$FinalArr = array();
	    		$arr = $request->input('options');
	    		if( !empty($arr) && count($arr) > 0 ) {

	    			foreach( $arr as $v ) {
                        if( $v != '' ) {

	    				   array_push($FinalArr, trim($v));
                        }
	    			}
	    		}

	    		$FrmFields->options = trim(serialize($FinalArr));
    		}
    		
    		if( $FrmFields->save() ) {

    			$insert_id = $FrmFields->id;

    			$rawHTML = getFieldHTML( $form_id, $insert_id );
    			$fieldHTML = htmlentities( $rawHTML );

    			FrmFields::where('id', $insert_id)->update([ 'field_raw_html' => $fieldHTML ]);

    			$FRMrawHTML = getFormHTML( $form_id );
    			$FormHTML = htmlentities( $FRMrawHTML );

    			FrmMaster::where('frm_auto_id', '=', $form_id)->update([ 'frm_raw_html' => $FormHTML ]);
    		}
    	}


        if( $request->input('ACTION_TYPE') == 'EDIT' && $request->input('row_id') != '' ) {

            $updateData = array();
            $form_id = trim($request->input('FORM_ID'));
            $edit_row_id = trim($request->input('row_id'));
            
            
            $field_name = preg_replace('/\s+/', '', trim($request->input('field_name')));
            if( $request->has('field_name') ) {
            
                $updateData['field_name'] = trim(strtolower($field_name)."_".$field_token);
            }
            if( $request->exists('display_text') ) {
            
                $updateData['tbl_header_text'] = trim($request->input('display_text'));
            }
            if( $request->exists('placeholder') ) {
                
                $updateData['placeholder'] = trim($request->input('placeholder'));
            }
            if( $request->exists('display_text') ) {
                
                $updateData['display_text'] = trim($request->input('display_text'));
            }
            if( $request->exists('is_required') ) {
                
                $updateData['is_required'] = trim($request->input('is_required'));
            }
            if( $request->exists('default_value') ) {
                
                $updateData['default_value'] = trim($request->input('default_value'));
            }
            if( $request->exists('min_value') ) {
                
                $updateData['min_value'] = trim($request->input('min_value'));
            }
            if( $request->exists('max_value') ) {
                
                $updateData['max_value'] = trim($request->input('max_value'));
            }
            if( $request->exists('css_class') ) {
            
                $updateData['css_class'] = preg_replace('/\s+/', '', trim($request->input('css_class')));
            }
            if( $request->exists('css_id') ) {
                
                $updateData['css_id'] = preg_replace('/\s+/', '', trim($request->input('css_id')));
            }
            if( $request->has('title') ) {
                
                $updateData['title'] = trim($request->input('title'));
            }
            if( $request->exists('help_text') ) {
                
                $updateData['help_text'] = trim($request->input('help_text'));
            }
            if( $request->exists('status') ) {
                
                $updateData['status'] = trim($request->input('status'));
            }
            if( $request->has('bgcolor') ) {
                
                $updateData['bgcolor'] = trim($request->input('bgcolor'));
            }
            if( $request->has('color') ) {
                
                $updateData['color'] = trim($request->input('color'));
            }

            if( $request->has('font') ) {
                
                $updateData['font'] = trim($request->input('font'));
            }

            if( $request->has('size') ) {
                
                $updateData['size'] = trim($request->input('size'));
            }
            
            
            $updateData['updated_at'] = date('Y-m-d H:i:s');
            
            if( $request->exists('options') ) {
                
                $FinalArr = array();
                $arr = $request->input('options');
                if( !empty($arr) && count($arr) > 0 ) {

                    foreach( $arr as $v ) {
                        if( $v != '' ) {

                            array_push($FinalArr, trim($v));
                        }
                    }
                }

                $updateData['options'] = trim(serialize($FinalArr));
            }

            $ups = FrmFields::where('id', '=', $edit_row_id)
            ->where('form_id', '=', $form_id)->update($updateData);

            if( $ups ) {

                $rawHTML = getFieldHTML( $form_id, $edit_row_id );
                $fieldHTML = htmlentities( $rawHTML );

                FrmFields::where('id', $edit_row_id)->update([ 'field_raw_html' => $fieldHTML ]);

                $FRMrawHTML = getFormHTML( $form_id );
                $FormHTML = htmlentities( $FRMrawHTML );

                FrmMaster::where('frm_auto_id', '=', $form_id)->update([ 'frm_raw_html' => $FormHTML ]);
            }

        }

    	return $rawHTML;

    }


    public function formPreview($form_id) {
        $DataBag = array();
        $DataBag['parentMenu'] = 'FrmB';
        $DataBag['childMenu'] = 'frm_crte';
    	if( $form_id != '' && $form_id != null ) {

    		$DataBag['form_details'] = FrmMaster::where('frm_auto_id', '=', trim($form_id))->first();
    		return view('dashboard.FormBuilder.form_preview', $DataBag);
    	}

    	return redirect()->route('frms');

    }

    public function ajxEditModal(Request $request) {

        $data = "";
        if( $request->ajax() ) {

            if( $request->input('row_id') != '' ) {

                $data = FrmFields::where( 'id', '=', trim($request->input('row_id')) )->first()->toArray();
                $data['options'] = unserialize($data['options']);

            }
        }

        return json_encode($data, JSON_PRETTY_PRINT);
    }

    public function ajxFieldOrder(Request $request) {

        if($request->ajax())
        {
            $ids = trim($request->input('ids'));
            $frm_auto_id = trim($request->input('frm_auto_id'));
            $idArr = explode(',', $ids);
            if(!empty($idArr))
            {
                $i = 1;
                foreach($idArr as $val)
                {
                    $arr = explode('_', $val);
                    if( !empty($arr) ) {
                        
                        $id = trim(end($arr));
                        FrmFields::where('id', '=', $id)->update(['field_order' => $i]);

                        $i++;
                    }
                }
            }

            $FRMrawHTML = getFormHTML( $frm_auto_id );
            $FormHTML = htmlentities( $FRMrawHTML );

            FrmMaster::where('frm_auto_id', '=', $frm_auto_id)->update([ 'frm_raw_html' => $FormHTML ]);

            echo "ok";
        }
    }

    public function ajxFieldDelete(Request $request) {

        $r1 = FrmFields::where( 'id', '=', trim($request->input('id')) )->delete();

        $frm_auto_id = trim( $request->input('frm_auto_id') );
        $FRMrawHTML = getFormHTML( $frm_auto_id );
        $FormHTML = htmlentities( $FRMrawHTML );

        $r2 = FrmMaster::where('frm_auto_id', '=', $frm_auto_id)->update([ 'frm_raw_html' => $FormHTML ]);

        if( $r1 && $r2 ) {

            echo "ok";
        }

    }

    public function formDelete($form_id) {
        
        if( $form_id != '' ) {

            FrmMaster::where('frm_auto_id', '=', $form_id)->delete();
            FrmFields::where('form_id', '=', $form_id)->delete();

            return back()->with('msg', 'Form deleted successfully.')->with('msg_class', 'alert alert-success');
        }
    }

    public function captchaSettings() {
        $DataBag = array();
        $DataBag['parentMenu'] = 'FrmB';
        $DataBag['childMenu'] = 'capStt';
        $data = FrmSettings::where('id', '=', '1')->first();
        $DataBag['data'] = $data;
        return view('dashboard.FormBuilder.captcha_settings', $DataBag);
    }

    public function formSubmitData(Request $request) {
        $postData = $request->all();

        
 
        // $ip = $request->ip();
        // $today = \Carbon\Carbon::today();
        // $ckIP = DB::table('frm_data')->whereDate('created_at', '=', $today)->where('ip_address', '=', $ip)->count();
        // if( $ckIP > 0 ) {
        //     return back();
        // }

        $flag3=0;
        $flag1=0;
        $flag2=0;

        if(isset($postData['email_61226fd4585429e623016599e6fb44e1'])){

            $str = $postData['email_61226fd4585429e623016599e6fb44e1'];
            $pattern = "/.xyz/i";
            $flag1= preg_match($pattern, $str); 
        
            $str = $postData['email_61226fd4585429e623016599e6fb44e1'];
            $pattern = "/.ru/i";
            $flag2= preg_match($pattern, $str); 
        }

        if(isset($postData['requirements_8fa7670f330845d9f75c72ef098ad774'])){

            $str = $postData['requirements_8fa7670f330845d9f75c72ef098ad774'];
            $pattern = "/http/i";
            $flag3= preg_match($pattern, $str); 
         
        }

         
        if( !empty($postData) && $flag1!=1 && $flag2!=1  && $flag3!=1 && $postData['company_8d9f1569b3d5a8fba1a5463bc280b601']!='google' && isset($postData['g-recaptcha-response'])) {
            
            $captcha = $postData['g-recaptcha-response'];
            $secret = '6LfRP74UAAAAAI-e0TPiFl9pnWOpakV7xv3E2J9f';
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$captcha);
            $responseData = json_decode($verifyResponse);
            if(!$responseData->success) {
                return back()->with('captcha_error', 'Form not submitted due to validation. Please try again.');
            } 

            $frmID = $postData['ar_frm_id'];
            $thankyou = $postData['thankyou_url'];
            $subButt = "ok_".$frmID;

            $rerf_fullurl='';
            $rerf_url='';
            
 
          
            $rerf_fullurl=$postData['referral'];
            $rerf_url=$postData['referral'];

            
            if( $rerf_fullurl==''){
                $rerf_fullurl = url()->previous();
                $rerf_url = url()->previous();
            }
 
            $rerf_arr = explode('/', $rerf_url);
            if (!empty($rerf_arr)) {
                $rerf_url = end($rerf_arr);
                $arr = explode('?', $rerf_url);
                if(!empty($arr)) {
                   $rerf_url = $arr[0]; 
                }
            }

            $last_enq_id = 3500;
            $lastRow = DB::table('frm_data')->orderBy('id', 'desc')->first();
            if(!empty($lastRow)) {
                $last_enq_id = $lastRow->enq_id;
            }
            $enq_id = $last_enq_id + 1;

            $saveArray = array();
            $mailArr = array();
            
            foreach( $postData as $key => $val ) {
                $arr = array();
                if( $key != 'receive_email' && $key != 'ar_frm_id' && $key != $subButt && $key != 'g-recaptcha-response' && $key != 'thankyou_url' ) {

                    if( $request->hasFile($key) ) {
                        $file = $request->file($key);
                        $file_orgname = $file->getClientOriginalName();
                        $file_size = $file->getClientSize();
                        $file_ext = strtolower($file->getClientOriginalExtension());
                        $file_newname = $frmID."_".time().".".$file_ext;
                        $destinationPath = public_path('/frm_uploads');
                        $file->move($destinationPath, $file_newname);
                        $arr[$key] = 'public/frm_uploads/'.$file_newname;
                    } else {
                        $arr[$key] = $val;
                    }

                    array_push($saveArray, $arr);
                }

                if( $key == 'receive_email' ) {
                    $mailArr = $request->input('receive_email');
                }
            }
        //echo "<pre>";
        //print_r($saveArray); die;
        $post_data = serialize( $saveArray );
        $FrmData = new FrmData;
        $FrmData->frm_id = $frmID;
        $FrmData->rerf_url = $rerf_fullurl;
        $FrmData->post_data = $post_data;
        $FrmData->created_at = Date('Y-m-d H:i:s');
        $FrmData->enq_id = $enq_id;
        //$FrmData->ip_address = $request->ip();
        $res = $FrmData->save();

        }


        $source_typename='';
        $hits=array();

        if( isset($res) && $res == 1 ) {
 
           $r= explode('/',$rerf_fullurl);

           if(isset($r[2])){

            $pattern = '/https/i';
            $r[2]= preg_replace($pattern, '', $r[2]);
 
            $pattern = '/http/i';
            $r[2]= preg_replace($pattern, '', $r[2]);
 
            $pattern = '/www./i';
            $r[2]= preg_replace($pattern, '', $r[2]);

            $allcampaign=DB::table('campaign') 
            ->selectRaw('name,url,source_type')   
            ->get();

            
            foreach($allcampaign as $onerow){
            
                $str = $rerf_fullurl;


                $onerow->url=str_replace("/","#",$onerow->url); 
                $pattern = "/{$onerow->url}/i";

                // $pattern = "/".$onerow->url."/i";
                $flag= preg_match($pattern, $str);
                if($flag)
                {
                    $hits=$onerow;
                }
            
            }
   
            //  $hits=DB::table('campaign') 
            //  ->selectRaw('name,url,source_type')  
            //  ->where('url','like', '%'.$r[2].'%')
            //  ->first();

 
             if( $r[2]=='multotec.com' && !isset($hits->name)){
                $source_typename='Direct';
             }
 
           }
 
           if(isset($hits->name)){
 
            $source_type=DB::table('source_type') 
            ->selectRaw('name')  
            ->where('id','=', $hits->source_type)
            ->first();

            $source_typename=$source_type->name;

           }


            $mailBODY = '';
            $mailBODY .= 'Enquiry ID = '.$enq_id.'<br/>';
            $mailBODY .= 'Referral Url = '.$rerf_fullurl.'<br/>';

 
            if($source_typename!=''){
                $mailBODY .= 'Traffic Source = '.$source_typename.'<br/>';
            }

            if(isset($hits->name)){
                $mailBODY .= 'Campaign Name = '.$hits->name.'<br/>';
            }
           

            if(!empty($saveArray) && !empty($mailArr)) {
                foreach($saveArray as $k=>$v) {
                    if(is_array($v) && !empty($v)) {
                        foreach($v as $k1=>$v1) {
                            $arr = explode('_', $k1);
                            if(!empty($arr)) {
                                $mailBODY .= ucfirst(trim($arr[0]));
                            }
                            if( is_array($v1) && !empty($v1) ) {
                                $mailBODY .= ' = '. implode(',', $v1);
                            } else {
                                $arrdot = explode('.', $v1);
                                if( is_array($arrdot) && !empty($arrdot) ) {
                                    $ext = end($arrdot);
                                    if($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif' || $ext == 'doc' || $ext == 'docx' || $ext == 'pdf' || $ext == 'csv' || $ext == 'xls' || $ext == 'xlsx' || $ext == 'zip' || $ext == 'rar') {

                                        $mailBODY .= ' = '.url('/').'/'.$v1;    
                                    } else {
                                        $mailBODY .= ' = '.$v1;
                                    }
                                } else {
                                    $mailBODY .= ' = '.$v1;
                                }
                            }
                            $mailBODY .= '<br/>';
                        }
                    }
                }

               // $mailBODY .= 'IP Address = '.$request->ip().'<br/>';
                $mail_sub = "New Multotec Enquiry - " . $enq_id;
                $empTemp = \App\Models\EmailTemplate::find(3);
                if(!empty($empTemp)) {
                    $mail_sub = $empTemp->subject.' - '.$enq_id;
                    $content = $empTemp->description;
                    $mailBODY = str_replace("[ENQ_CONTENT]", $mailBODY, $content);
                }
                //echo html_entity_decode($mailBODY); die;
                $mailArr = array("heathl@cubicice.com","tarryn@cubicice.com","multotecwebenquiry@cubicice.com");
                foreach($mailArr as $vem) {

                    //     trim($vem) != 'koenal@multotec.com' && 
                    //     trim($vem) != 'viviennem@multotec.com' && 
                    //     trim($vem) != 'annahv@multotec.com'
                    
                    $emailData = array();
                    $emailData['subject'] = $mail_sub . ' '. $rerf_url;
                    $emailData['body'] = trim($mailBODY);
                    $emailData['to_email'] = trim($vem);
                    $emailData['from_email'] = env('MAIL_FROM_ADDRESS',"support@multotec.com");
                    $emailData['from_name'] = "Multotec";

                    Mail::send('emails.accountVerification', ['emailData' => $emailData], function ($message) use ($emailData) {

                        $message->from($emailData['from_email'], $emailData['from_name']);

                        $message->to($emailData['to_email'])->subject($emailData['subject']);
                    });
                }


                // $emailData['to_email'] = trim('multotecmailingserve@gmail.com');

                // Mail::send('emails.accountVerification', ['emailData' => $emailData], function ($message) use ($emailData) {

                //     $message->from($emailData['from_email'], $emailData['from_name']);

                //     $message->to($emailData['to_email'])->subject($emailData['subject']);
                // });
            }

            

            if( $thankyou != '' && $thankyou != NULL) {

                return redirect()->to($thankyou);
                
            } else {
                return back()->with('msg', 'Form submitted successfully.')->with('msg_class', 'alert alert-success');
            }
        }

        return back()->with('msg', 'Something went wrong!')->with('msg_class', 'alert alert-danger');

    }

    public function formSaveSettings(Request $request) {

        $isData = FrmSettings::where('id', '=', '1')->first();
        if( !empty($isData) ) {

            $updateData = array();
            $updateData['captcha_site_key'] = trim( $request->input('captcha_site_key') );
            $updateData['captcha_secret_key'] = trim( $request->input('captcha_secret_key') );
            $updateData['updated_at'] = date('Y-m-d H:i:s');

            $ups = FrmSettings::where('id', '=', '1')->update($updateData);

            if( $ups ) {

                return back()->with('msg', 'Captcha settings save successfully.')->with('msg_class', 'alert alert-success');
            } else {

                return back()->with('msg', 'Something went wrong!')->with('msg_class', 'alert alert-danger');
            }

        } else {
            
            $FrmSettings = new FrmSettings;
            $FrmSettings->captcha_site_key = trim( $request->input('captcha_site_key') );
            $FrmSettings->captcha_secret_key = trim( $request->input('captcha_secret_key') );
            $FrmSettings->created_at = date('Y-m-d H:i:s');

            if( $FrmSettings->save() ) {

                return back()->with('msg', 'Captcha settings added successfully.')->with('msg_class', 'alert alert-success');
            } else {

                return back()->with('msg', 'Something went wrong!')->with('msg_class', 'alert alert-danger');
            }
        }
    }


    public function showFormData($form_id) {

        $DataBag = array();
        $DataBag['parentMenu'] = 'FrmB';
        $DataBag['childMenu'] = 'frms';

        if( $form_id != '' && $form_id != null) {

            $DataBag['form_details'] = FrmMaster::where('frm_auto_id', '=', $form_id)->first();
            $DataBag['records'] = FrmData::where('frm_id', '=', $form_id)->orderBy('id', 'desc')->paginate(25);
            $DataBag['tbl_headers'] = FrmFields::where('form_id', '=', $form_id)
            ->where('status', '=', '1')->where('field_type', '!=', 'BUTTON')->orderBy('field_order', 'asc')->get();
            $DataBag['fields_key'] = FrmFields::where('form_id', '=', $form_id)
            ->where('status', '=', '1')->where('field_type', '!=', 'BUTTON')->orderBy('field_order', 'asc')
            ->pluck('field_name')->toArray();
        }
        //dd($DataBag['fields_key']);
        return view('dashboard.FormBuilder.forms_data', $DataBag);
    }

    public function deleteFormData($record_id) {

        $ck = FrmData::find($record_id);
        if( isset($ck) && !empty($ck) ) {
            $ck->delete();
            return back()->with('msg', 'Record Deleted Succesfully.')->with('msg_class', 'alert alert-success');
        }

        return back();
    }

    public function categories() {
        $DataBag = array();
        $DataBag['parentMenu'] = 'FrmB';
        $DataBag['childMenu'] = 'frmCats';
        $DataBag['allCats'] = FrmCategories::where('status', '!=', '3')->orderBy('created_at', 'desc')->get();
        return view('dashboard.FormBuilder.categories', $DataBag);
    }

    public function addCategory() {
        $DataBag = array();
        $DataBag['parentMenu'] = 'FrmB';
        $DataBag['childMenu'] = 'frmCats_crte';
        return view('dashboard.FormBuilder.create_category', $DataBag);
    }

    public function saveCategory(Request $request) {

        $FrmCategories = new FrmCategories;
        $FrmCategories->category_name = trim(ucfirst($request->input('category_name')));
        $FrmCategories->status = $request->input('status');
        $FrmCategories->created_by = Auth::user()->id;
        $res = $FrmCategories->save();
        if( $res ) {
            return back()->with('msg', 'Category Name Saved Successfully.')
            ->with('msg_class', 'alert alert-success');
        } else {
            return back()->with('msg', 'Something Went Wrong.')
            ->with('msg_class', 'alert alert-danger');
        }
    }

    public function editCategory($id) {
        $DataBag = array();
        $DataBag['parentMenu'] = 'FrmB';
        $DataBag['childMenu'] = 'frmCats_crte';
        $DataBag['category'] = FrmCategories::findOrFail($id);
        return view('dashboard.FormBuilder.create_category', $DataBag);
    }

    public function updateCategory(Request $request, $id) {

        $FrmCategories = FrmCategories::find($id);
        $FrmCategories->category_name = trim(ucfirst($request->input('category_name')));
        $FrmCategories->status = $request->input('status');
        $FrmCategories->created_by = Auth::user()->id;
        $res = $FrmCategories->save();
        if( $res ) {
            return back()->with('msg', 'Category Name Updated Successfully.')
            ->with('msg_class', 'alert alert-success');
        } else {
            return back()->with('msg', 'Something Went Wrong.')
            ->with('msg_class', 'alert alert-danger');
        }
    }

    public function deleteCategory($id) {
        $del = FrmCategories::findOrFail($id);
        $del->status = 3;
        $res = $del->save();
        if( $res ) {
            return back()->with('msg', 'Category Deleted Successfully.')
            ->with('msg_class', 'alert alert-success');
        } else {
            return back()->with('msg', 'Something Went Wrong.')
            ->with('msg_class', 'alert alert-danger');
        }
    }

    public function exportData($frm_id, $type) {

        /*$records = FrmData::where('frm_id', '=', $frm_id)->orderBy('id', 'desc')->get();
        $fields_key = FrmFields::where('form_id', '=', $frm_id)
        ->where('status', '=', '1')->where('field_type', '!=', 'BUTTON')->orderBy('field_order', 'asc')
        ->pluck('field_name')->toArray();
        $filename = 'Multotec_Leads_'.date('m-d-Y');
        $excelArr = array();
        foreach( $records as $obj ) {
          $data = unserialize($obj->post_data);
          if( !empty($data) ) {
            $arrx = array();
            foreach( $data as $index=>$vArr ) {
             foreach( $vArr as $k=>$v ) {
              $arrx[$k] = $v;
             }
            }
            $i = 0;
            $arr = array();
            foreach ($fields_key as $ft=>$fk) {
              if( array_key_exists( $fk, $arrx ) ) {
                
                
                $arr['Referral Url'] = $obj->rerf_url;
                $arr['Date'] = date('m-d-Y', strtotime($obj->created_at));
                
                $print = $arrx[$fk];
                $arr['field'. $i] = $print;
              } else {
                $arr['field'. $i] = '-';
              }
              $i++;
            }
            array_push($excelArr, $arr);
          }
        }
            
        return Excel::create($filename, function($excel) use ($excelArr) {
            
            $excel->setTitle('Multotec');
            $excel->setCreator('Multotec');
            $excel->setCompany('Multotec');
            $excel->setDescription('Multotec Products');
            
            $excel->sheet('All Products', function($sheet) use ($excelArr)
            {
                $sheet->fromArray($excelArr);
            });
        })->download($type);*/
    }

    /*********************** BULK ACTION ****************************/

    public function bulkAction(Request $request) {

        $msg = '';
        if( $request->has('action_btn') && $request->has('ids') ) {
            $actBtnValue = trim( $request->input('action_btn') );
            $idsArr = $request->input('ids');

            switch ( $actBtnValue ) {
                
                case 'activate':
                    foreach($idsArr as $id) {
                        $FrmMaster = FrmMaster::find($id);
                        $FrmMaster->status = '1';
                        $FrmMaster->save();
                    }
                    $msg = 'Form Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $FrmMaster = FrmMaster::find($id);
                        $FrmMaster->status = '2';
                        $FrmMaster->save();
                    }
                    $msg = 'Form Deactivated Succesfully.';
                    break;

                case 'delete':
                    foreach($idsArr as $id) {
                        $FrmMaster = FrmMaster::find($id);
                        $frm_auto_id = $FrmMaster->frm_auto_id;
                        FrmFields::where('form_id', '=', $frm_auto_id)->delete();
                        FrmData::where('frm_id', '=', $frm_auto_id)->delete();
                        $FrmMaster->delete();
                    }
                    $msg = 'Form Deleted Succesfully.';
                    break;
            }
            return back()->with('msg', $msg)->with('msg_class', 'alert alert-success');
        }
        return back();
    }

    public function bulkActionCat(Request $request) {

        $msg = '';
        if( $request->has('action_btn') && $request->has('ids') ) {
            $actBtnValue = trim( $request->input('action_btn') );
            $idsArr = $request->input('ids');

            switch ( $actBtnValue ) {
                
                case 'activate':
                    foreach($idsArr as $id) {
                        $FrmCategories = FrmCategories::find($id);
                        $FrmCategories->status = '1';
                        $FrmCategories->save();
                    }
                    $msg = 'Form Categories Activated Succesfully.';
                    break;

                case 'deactivate':
                    foreach($idsArr as $id) {
                        $FrmCategories = FrmCategories::find($id);
                        $FrmCategories->status = '2';
                        $FrmCategories->save();
                    }
                    $msg = 'Form Categories Deactivated Succesfully.';
                    break;

                case 'delete':
                    foreach($idsArr as $id) {
                        $FrmCategories = FrmCategories::find($id);
                        $FrmCategories->status = '3';
                        $FrmCategories->save();
                    }
                    $msg = 'Form Categories Deleted Succesfully.';
                    break;
            }
            return back()->with('msg', $msg)->with('msg_class', 'alert alert-success');
        }
        return back();
    }

    public function  testEmail(){
        $emailData = array();
        $emailData['subject'] = "Test Subject";
        $emailData['body'] = "Test";
        $emailData['to_email'] = "heathl@cubicice.com";
        $emailData['from_email'] = env("MAIL_FROM_ADDRESS");
        $emailData['from_name'] = "Multotec";

        Mail::send('emails.accountVerification', ['emailData' => $emailData], function ($message) use ($emailData) {

            $message->from($emailData['from_email'], $emailData['from_name']);

            $message->to($emailData['to_email'])->subject($emailData['subject']);
        });
    }
}
