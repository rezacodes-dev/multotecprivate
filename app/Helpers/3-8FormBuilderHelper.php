<?php

function getFieldHTML( $form_id, $row_id ) {

	$fhtml = '';
	if( $form_id != '' && $row_id != '') {

		$formData = DB::table('frm_master')->where('frm_auto_id', '=', $form_id)->first();
		$data = DB::table('frm_fields')->where('form_id', '=', $form_id)
		->where('id', '=', $row_id)->first();

		if( !empty($data) && !empty($formData) ) {

			$field_type = $data->field_type;

			if( $field_type == 'BUTTON' ) {

				$fhtml .= "<div class='row fd_box btnf' id='field_". $row_id ."'>";
			} else {

				$fhtml .= "<div class='row fd_box' id='field_". $row_id ."'>";
			}

			$fhtml .= "<div class='col-md-12 col-sm-12'><div class='form-group'>";

			if( $field_type == 'TEXTFIELD' ) {

				if( $data->display_text != '') {
					
					$fhtml .= "<label>". $data->display_text ." :</label> ";
				}
				if( $data->is_required == 'required') {

					$fhtml .= "<em>*</em> ";
				}
				if( $data->help_text != '') {

					$fhtml .= "<span><small><i>". $data->help_text ."</i></small></span> ";
				}

				$fhtml .= "<input type='text' ";
				if( $data->field_name != '') {

					$fhtml .= "name='". $data->field_name ."' ";
				}
				if( $data->placeholder != '') {

					$fhtml .= "placeholder='". $data->placeholder ."' ";
				}
				if( $data->is_required != '') {

					$fhtml .= $data->is_required." ";
				}
				if( $data->default_value != '') {

					$fhtml .= "value='". $data->default_value ."' ";
				}
				if( $data->css_class != '') {
					$css_class = "";
					$cssArr = explode(',', $data->css_class);
					if( !empty($cssArr) ) {
						foreach( $cssArr as $css ) {
							$css_class .= trim($css)." ";
						}
					}

					$fhtml .= "class='". $css_class ."' ";
				}
				if( $data->css_class == '') {

					$fhtml .= "class='form-control' ";	
				}
				if( $data->css_id != '') {

					$fhtml .= "id='". $data->css_id ."' ";
				}

				$fhtml .= " />";
				
			}

			if( $field_type == 'EMAILFIELD' ) {

				if( $data->display_text != '') {
					
					$fhtml .= "<label>". $data->display_text ." :</label> ";
				}
				if( $data->is_required == 'required') {

					$fhtml .= "<em>*</em> ";
				}
				if( $data->help_text != '') {

					$fhtml .= "<span><small><i>". $data->help_text ."</i></small></span> ";
				}

				$fhtml .= "<input type='email' ";
				if( $data->field_name != '') {

					$fhtml .= "name='". $data->field_name ."' ";
				}
				if( $data->placeholder != '') {

					$fhtml .= "placeholder='". $data->placeholder ."' ";
				}
				if( $data->is_required != '') {

					$fhtml .= $data->is_required." ";
				}
				if( $data->default_value != '') {

					$fhtml .= "value='". $data->default_value ."' ";
				}
				if( $data->css_class != '') {
					$css_class = "";
					$cssArr = explode(',', $data->css_class);
					if( !empty($cssArr) ) {
						foreach( $cssArr as $css ) {
							$css_class .= trim($css)." ";
						}
					}

					$fhtml .= "class='". $css_class ."' ";
				}
				if( $data->css_class == '') {

					$fhtml .= "class='form-control' ";	
				}
				if( $data->css_id != '') {

					$fhtml .= "id='". $data->css_id ."' ";
				}

				$fhtml .= " />";
				
			}


			if( $field_type == 'NUMBERFIELD' ) {

				if( $data->display_text != '') {
					
					$fhtml .= "<label>". $data->display_text ." :</label> ";
				}
				if( $data->is_required == 'required') {

					$fhtml .= "<em>*</em> ";
				}
				if( $data->help_text != '') {

					$fhtml .= "<span><small><i>". $data->help_text ."</i></small></span> ";
				}

				$fhtml .= "<input type='number' ";
				if( $data->field_name != '') {

					$fhtml .= "name='". $data->field_name ."' ";
				}
				if( $data->min_value != '') {

					$fhtml .= "min='". $data->min_value ."' ";
				}
				if( $data->max_value != '') {

					$fhtml .= "max='". $data->max_value ."' ";
				}
				if( $data->placeholder != '') {

					$fhtml .= "placeholder='". $data->placeholder ."' ";
				}
				if( $data->is_required != '') {

					$fhtml .= $data->is_required." ";
				}
				if( $data->default_value != '') {

					$fhtml .= "value='". $data->default_value ."' ";
				}
				if( $data->css_class != '') {
					$css_class = "";
					$cssArr = explode(',', $data->css_class);
					if( !empty($cssArr) ) {
						foreach( $cssArr as $css ) {
							$css_class .= trim($css)." ";
						}
					}

					$fhtml .= "class='onlyNumber ". $css_class ."' ";
				}
				if( $data->css_class == '') {

					$fhtml .= "class='form-control onlyNumber' ";	
				}
				if( $data->css_id != '') {

					$fhtml .= "id='". $data->css_id ."' ";
				}

				$fhtml .= " />";
				
			}

			if( $field_type == 'PHONEFIELD' ) {

				if( $data->display_text != '') {
					
					$fhtml .= "<label>". $data->display_text ." :</label> ";
				}
				if( $data->is_required == 'required') {

					$fhtml .= "<em>*</em> ";
				}
				if( $data->help_text != '') {

					$fhtml .= "<span><small><i>". $data->help_text ."</i></small></span> ";
				}

				$fhtml .= "<input type='text' ";
				if( $data->field_name != '') {

					$fhtml .= "name='". $data->field_name ."' ";
				}
				if( $data->placeholder != '') {

					$fhtml .= "placeholder='". $data->placeholder ."' ";
				}
				if( $data->is_required != '') {

					$fhtml .= $data->is_required." ";
				}
				if( $data->default_value != '') {

					$fhtml .= "value='". $data->default_value ."' ";
				}
				if( $data->css_class != '') {
					$css_class = "";
					$cssArr = explode(',', $data->css_class);
					if( !empty($cssArr) ) {
						foreach( $cssArr as $css ) {
							$css_class .= trim($css)." ";
						}
					}

					$fhtml .= "class='onlyPHNO ". $css_class ."' ";
				}
				if( $data->css_class == '') {

					$fhtml .= "class='form-control onlyPHNO' ";	
				}
				if( $data->css_id != '') {

					$fhtml .= "id='". $data->css_id ."' ";
				}

				$fhtml .= " />";
				
			}


			if( $field_type == 'URLFIELD' ) {

				if( $data->display_text != '') {
					
					$fhtml .= "<label>". $data->display_text ." :</label> ";
				}
				if( $data->is_required == 'required') {

					$fhtml .= "<em>*</em> ";
				}
				if( $data->help_text != '') {

					$fhtml .= "<span><small><i>". $data->help_text ."</i></small></span> ";
				}

				$fhtml .= "<input type='url' ";
				if( $data->field_name != '') {

					$fhtml .= "name='". $data->field_name ."' ";
				}
				if( $data->placeholder != '') {

					$fhtml .= "placeholder='". $data->placeholder ."' ";
				}
				if( $data->is_required != '') {

					$fhtml .= $data->is_required." ";
				}
				if( $data->default_value != '') {

					$fhtml .= "value='". $data->default_value ."' ";
				}
				if( $data->css_class != '') {
					$css_class = "";
					$cssArr = explode(',', $data->css_class);
					if( !empty($cssArr) ) {
						foreach( $cssArr as $css ) {
							$css_class .= trim($css)." ";
						}
					}

					$fhtml .= "class='". $css_class ."' ";
				}
				if( $data->css_class == '') {

					$fhtml .= "class='form-control' ";	
				}
				if( $data->css_id != '') {

					$fhtml .= "id='". $data->css_id ."' ";
				}

				$fhtml .= " />";
				
			}


			if( $field_type == 'DATEFIELD' ) {

				if( $data->display_text != '') {
					
					$fhtml .= "<label>". $data->display_text ." :</label> ";
				}
				if( $data->is_required == 'required') {

					$fhtml .= "<em>*</em> ";
				}
				if( $data->help_text != '') {

					$fhtml .= "<span><small><i>". $data->help_text ."</i></small></span> ";
				}

				$fhtml .= "<input type='text' ";
				if( $data->field_name != '') {

					$fhtml .= "name='". $data->field_name ."' ";
				}
				if( $data->placeholder != '') {

					$fhtml .= "placeholder='". $data->placeholder ."' ";
				}
				if( $data->is_required != '') {

					$fhtml .= $data->is_required." ";
				}
				if( $data->default_value != '') {

					$fhtml .= "value='". $data->default_value ."' ";
				}
				if( $data->css_class != '') {
					$css_class = "";
					$cssArr = explode(',', $data->css_class);
					if( !empty($cssArr) ) {
						foreach( $cssArr as $css ) {
							$css_class .= trim($css)." ";
						}
					}

					$fhtml .= "class='datepicker ". $css_class ."' ";
				}
				if( $data->css_class == '') {

					$fhtml .= "class='datepicker form-control' ";	
				}
				if( $data->css_id != '') {

					$fhtml .= "id='". $data->css_id ."' ";
				}

				$fhtml .= " />";
				
			}


			if( $field_type == 'PARAFIELD' ) {

				if( $data->display_text != '') {
					
					$fhtml .= "<label>". $data->display_text ." :</label> ";
				}
				if( $data->is_required == 'required') {

					$fhtml .= "<em>*</em> ";
				}
				if( $data->help_text != '') {

					$fhtml .= "<span><small><i>". $data->help_text ."</i></small></span> ";
				}

				$fhtml .= "<textarea ";
				if( $data->field_name != '') {

					$fhtml .= "name='". $data->field_name ."' ";
				}
				if( $data->placeholder != '') {

					$fhtml .= "placeholder='". $data->placeholder ."' ";
				}
				if( $data->is_required != '') {

					$fhtml .= $data->is_required." ";
				}
				if( $data->css_class != '') {
					$css_class = "";
					$cssArr = explode(',', $data->css_class);
					if( !empty($cssArr) ) {
						foreach( $cssArr as $css ) {
							$css_class .= trim($css)." ";
						}
					}

					$fhtml .= "class='". $css_class ."' ";
				}
				if( $data->css_class == '') {

					$fhtml .= "class='form-control' ";	
				}
				if( $data->css_id != '') {

					$fhtml .= "id='". $data->css_id ."' ";
				}

				$fhtml .= " ></textarea>";
				
			}


			if( $field_type == 'FILEFIELD' ) {

				if( $data->display_text != '') {
					
					$fhtml .= "<label>". $data->display_text ." :</label> ";
				}
				if( $data->is_required == 'required') {

					$fhtml .= "<em>*</em> ";
				}
				if( $data->help_text != '') {

					$fhtml .= "<span><small><i>". $data->help_text ."</i></small></span> ";
				}

				$fhtml .= "<label class='custom-file-upload'><i class='fa fa-upload' aria-hidden='true'></i> Upload documents (optional)";
				$fhtml .= "<input type='file' ";
				if( $data->field_name != '') {

					$fhtml .= "name='". $data->field_name ."' ";
				}
				if( $data->is_required != '') {

					$fhtml .= $data->is_required." ";
				}
				if( $data->css_class != '') {
					$css_class = "";
					$cssArr = explode(',', $data->css_class);
					if( !empty($cssArr) ) {
						foreach( $cssArr as $css ) {
							$css_class .= trim($css)." ";
						}
					}

					$fhtml .= "class='". $css_class ."' ";
				}
				if( $data->css_id != '') {

					$fhtml .= "id='". $data->css_id ."' ";
				}

				$fhtml .= " style='display:none;' />";
				$fhtml .= "</label>";
				
			}


			if( $field_type == 'DROPDOWN' ) {

				if( $data->display_text != '') {
					
					$fhtml .= "<label>". $data->display_text ." :</label> ";
				}
				if( $data->is_required == 'required') {

					$fhtml .= "<em>*</em> ";
				}
				if( $data->help_text != '') {

					$fhtml .= "<span><small><i>". $data->help_text ."</i></small></span> ";
				}

				$fhtml .= "<select ";
				if( $data->field_name != '') {

					$fhtml .= "name='". $data->field_name ."' ";
				}
				if( $data->is_required != '') {

					$fhtml .= $data->is_required." ";
				}
				if( $data->css_class != '') {
					$css_class = "";
					$cssArr = explode(',', $data->css_class);
					if( !empty($cssArr) ) {
						foreach( $cssArr as $css ) {
							$css_class .= trim($css)." ";
						}
					}

					$fhtml .= "class='". $css_class ."' ";
				}
				if( $data->css_class == '') {

					$fhtml .= "class='form-control' ";	
				}
				if( $data->css_id != '') {

					$fhtml .= "id='". $data->css_id ."' ";
				}

				$fhtml .= " >";
					$svalue = '';
					$stitle = '-SELECT-';
					if( $data->title != '' ) {

						$stitle = trim($data->title);
					}
					if( $data->default_value != '' ) {

						$svalue = trim($data->default_value);
					}
					$fhtml .= "<option value='". $svalue ."'>". $stitle ."</option>";
					if( $data->options != '' ) {

						$sarr = unserialize( $data->options );
						if( !empty( $sarr ) ) {
							foreach( $sarr as $op ) {
								$strop = str_replace(' ', '-', $op);
								$fhtml .= "<option value='". $strop ."'>". trim($op) ."</option>";
							}
						}
					}
				$fhtml .= "</select>";	
			}


			if( $field_type == 'LISTBOX' ) {

				if( $data->display_text != '') {
					
					$fhtml .= "<label>". $data->display_text ." :</label> ";
				}
				if( $data->is_required == 'required') {

					$fhtml .= "<em>*</em> ";
				}
				if( $data->help_text != '') {

					$fhtml .= "<span><small><i>". $data->help_text ."</i></small></span> ";
				}

				$fhtml .= "<select ";
				if( $data->field_name != '') {

					$fhtml .= "name='". $data->field_name ."' ";
				}
				if( $data->is_required != '') {

					$fhtml .= $data->is_required." ";
				}
				if( $data->css_class != '') {
					$css_class = "";
					$cssArr = explode(',', $data->css_class);
					if( !empty($cssArr) ) {
						foreach( $cssArr as $css ) {
							$css_class .= trim($css)." ";
						}
					}

					$fhtml .= "class='". $css_class ."' ";
				}
				if( $data->css_class == '') {

					$fhtml .= "class='form-control' ";	
				}
				if( $data->css_id != '') {

					$fhtml .= "id='". $data->css_id ."' ";
				}

				$fhtml .= " multiple='multiple' >";
					$svalue = '';
					$stitle = '-SELECT-';
					if( $data->title != '' ) {

						$stitle = trim($data->title);
					}
					if( $data->default_value != '' ) {

						$svalue = trim($data->default_value);
					}
					$fhtml .= "<option value='". $svalue ."'>". $stitle ."</option>";
					if( $data->options != '' ) {

						$sarr = unserialize( $data->options );
						if( !empty( $sarr ) ) {
							foreach( $sarr as $op ) {
								$strop = str_replace(' ', '-', $op);
								$fhtml .= "<option value='". $strop ."'>". trim($op) ."</option>";
							}
						}
					}
				$fhtml .= "</select>";	
			}



			if( $field_type == 'RADIOFIELD' ) {

				if( $data->display_text != '') {
					
					$fhtml .= "<label>". $data->display_text ." :</label> ";
				}
				if( $data->is_required == 'required') {

					$fhtml .= "<em>*</em> ";
				}
				if( $data->help_text != '') {

					$fhtml .= "<span><small><i>". $data->help_text ."</i></small></span> ";
				}

				$fhtml .= "<br/>";

				$css_class = "ar-radio ";
				if( $data->css_class != '') {
					$cssArr = explode(',', $data->css_class);
					if( !empty($cssArr) ) {
						foreach( $cssArr as $css ) {
							$css_class .= trim($css)." ";
						}
					}
				}
					
				if( $data->options != '' ) {

					$sarr = unserialize( $data->options );
					if( !empty( $sarr ) ) {
						$x = 0;
						foreach( $sarr as $op ) {
							$strop = str_replace(' ', '-', $op);
							$fhtml .= "<input type='radio' ";
							if( $data->field_name != '') {

								$fhtml .= " name='". trim($data->field_name) ."' ";
							}
							$fhtml .= " class='". trim($css_class) ."' ";
							if( $data->css_id != '') {

								$fhtml .= " id='". $data->css_id ."' ";
							}
							$fhtml .= " value='". $strop ."' ";

							if( $x == 0 && $data->is_required != '') {

								$fhtml .= $data->is_required ." ";
							}

							$fhtml .= " > ";
							$fhtml .= $op ." ";

							$x++;

						}
					}
				}
				
			}


			if( $field_type == 'CHECKFIELD' ) {

				if( $data->display_text != '') {
					
					$fhtml .= "<label>". $data->display_text ." :</label> ";
				}
				if( $data->is_required == 'required') {

					$fhtml .= "<em>*</em> ";
				}
				if( $data->help_text != '') {

					$fhtml .= "<span><small><i>". $data->help_text ."</i></small></span> ";
				}

				$fhtml .= "<br/>";

				$css_class = "ar-ckb ";
				if( $data->css_class != '') {
					$cssArr = explode(',', $data->css_class);
					if( !empty($cssArr) ) {
						foreach( $cssArr as $css ) {
							$css_class .= trim($css)." ";
						}
					}
				}
					
				if( $data->options != '' ) {

					$sarr = unserialize( $data->options );
					if( !empty( $sarr ) ) {
						$x = 0;
						foreach( $sarr as $op ) {
							$strop = str_replace(' ', '-', $op);
							$fhtml .= "<p><input type='checkbox' ";
							if( $data->field_name != '') {

								$fhtml .= " name='". trim($data->field_name) ."[]' ";
							}
							$fhtml .= " class='". trim($css_class) ."' ";
							if( $data->css_id != '') {

								$fhtml .= " id='". $data->css_id ."' ";
							}
							$fhtml .= " value='". $strop ."' ";

							if( $x == 0 && $data->is_required != '') {

								$fhtml .= $data->is_required ." ";
							}

							$fhtml .= " > ";
							$fhtml .= $op ."</p> ";

							$x++;

						}
					}
				}
				
			}


			if( $field_type == 'BUTTON' ) {

				$fhtml .= "<input type='submit' ";
				if( $data->field_name != '') {

					$fhtml .= "name='". $data->field_name ."' ";
				}
				if( $data->default_value != '') {

					$fhtml .= "value='". $data->default_value ."' ";
				}
				if( $data->default_value == '') {
					
					$fhtml .= "value='Submit' ";
				}
				if( $data->css_id != '') {

					$fhtml .= "id='". $data->css_id ."' ";
				}
				if( $data->css_class != '') {
					$css_class = "";
					$cssArr = explode(',', $data->css_class);
					if( !empty($cssArr) ) {
						foreach( $cssArr as $css ) {
							$css_class .= trim($css)." ";
						}
					}

					//$fhtml .= "class='". $css_class ."' ";
					$fhtml .= "class='submit-btn' ";
				}
				if( $data->css_class == '') {

					$fhtml .= "class='submit-btn' ";	
				}

				$fhtml .= "style='";
				
				if( $data->bgcolor != '' ) {

					$fhtml .= "background-color:". $data->bgcolor ."; ";	
				}
				if( $data->color != '' ) {

					$fhtml .= "color:". $data->color ."; ";	
				}

				$fhtml .= "' ";

				$fhtml .= " />";
			}

			//if( $field_type != 'BUTTON' ) {
				
				$fhtml .= "<div id='ed_action_box_". $row_id ."'></div>";
			//}

			$fhtml .= "</div></div></div>";
		}
	}

	return $fhtml;
}

function getFormHTML($form_id) {

	$frhtml = '';
	if( $form_id != '' ) {

		$formData = DB::table('frm_master')->where('frm_auto_id', '=', $form_id)->first();
		$formSettings = DB::table('frm_settings')->where('id', '=', '1')->first();
		$subURL = route('frm_submit');

		if( !empty($formData) && !empty($formSettings) ) {

			$frhtml .= "<div class='ar_frm_container' ";
			$frhtml .= "style='background-color:". $formData->frm_bg_color ."; color:". $formData->frm_txt_color .";'";
			$frhtml .= ">";
				if( $formData->frm_heading != '' ) {

					//$frhtml .= "<h3 class='ar_frm_heading'>". $formData->frm_heading ."</h3>";
				}
				$frhtml .= "<form ";
				if( $formData->frm_name != '' ) {
					$frhtml .= "name='". $formData->frm_name ."' ";
				}
				if( $formData->frm_css_id != '' ) {
					$frhtml .= "id='". $formData->frm_css_id ."' ";
				}
				$frhtml .= "action='". $subURL ."' ";

				$frhtml .= "method='post' ";

				$css_class = "";
				if( $formData->frm_css_class != '') {
					$cssArr = explode(',', $formData->frm_css_class);
					if( !empty($cssArr) ) {
						foreach( $cssArr as $css ) {
							$css_class .= trim($css)." ";
						}
					}

				}

				$frhtml .= "class='ar_vali_class ". trim($css_class) ."' ";
				$frhtml .= " enctype='multipart/form-data' >";


				if( $formData->is_email_receive == '1' && $formData->receive_emails != '' ) {

					$emArr = unserialize( $formData->receive_emails );
					if( !empty($emArr) ) {
						foreach( $emArr as $em ) {

							$frhtml .= "<input type='hidden' name='receive_email[]' value='". trim($em) ."' />";
						}
					}
				}

				$captcha = "<div class='form-group'><div class='g-recaptcha mt5 mb5' data-sitekey='". trim($formSettings->captcha_site_key) ."'></div></div>";
				$captcha .= "<div class='ar-captcha-vali'></div>";

				$fldData = DB::table('frm_fields')->where('form_id', '=', $form_id)
				->where('status', '=', '1')->orderBy('field_order', 'asc')->get();

				if( !empty($fldData) ) {

					foreach( $fldData as $fld ) {

						if( $fld->field_type == 'BUTTON' ) {
							if( $formData->is_captcha == '1' ) {

								$frhtml .= $captcha;
							}
						}

						$frhtml .= trim( html_entity_decode($fld->field_raw_html) );
					}
				}

				$frhtml .= "<input type='hidden' name='ar_frm_id' value='". $form_id ."'>";
				$frhtml .= "<input type='hidden' name='thankyou_url' value='". trim($formData->thankyou_url) ."'>";
				$frhtml .= "</form>";
			$frhtml .= "</div>";
		}
	}

	return $frhtml;

}

?>