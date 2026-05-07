<script type="text/javascript">
$( function() {
	var pgbFrmLoad = 0;
	var pgbReuseLoad = 0;
	$('body').on('click', '.pgb_del', function() {
		if( confirm('Sure To Delete This Section ?') ) {
			var _getId = $.trim( $(this).attr('id') );
			var _getBuilder_type = $.trim( $(this).attr('data') );
			var _holder = $(this).closest('.altTop');
			//alert( $(this).closest('.altTop').attr('class') );
			if( _getId != '' && _getBuilder_type != '' ) {

				$.ajax({
					type : "POST",
					url : "{{ route('pgbDel') }}",
					data : {
						"id" : _getId,
						"builder_type" : _getBuilder_type,
						"_token" : "{{ csrf_token() }}"
					},
					beforeSend : function() {
						_holder.block({ message: null });
					},
					success : function(resp) {
						if( resp == 'success' ) {
							_holder.unblock();	
							_holder.remove();	
						}
					}
				});
			}
		}
	} );
	$('body').on('click', '.pgb_edt', function() {
		var _getId = $.trim( $(this).attr('id') );
		var _getBuilder_type = $.trim( $(this).attr('data') );
		var _holder = $(this).closest('.altTop');
		//alert( $(this).closest('.altTop').attr('class') );
		if( _getId != '' && _getBuilder_type != '' ) {

			$.ajax({
				type : "POST",
				url : "{{ route('pgbGet') }}",
				data : {
					"id" : _getId,
					"builder_type" : _getBuilder_type,
					"_token" : "{{ csrf_token() }}"
				},
				beforeSend : function() {
					_holder.block({ message: null });
				},
				success : function(respx) {
					if( respx != '' ) {
						var objx = JSON.parse( respx );
						var _insertID = objx.insert_id;
						var _ID = objx.id;
						var _builderType = $.trim( objx.builder_type );
						var _mainContent = objx.main_content;
						var _subContent = objx.sub_content;
						var _mainTitle = objx.main_title;
						var _subTitle = objx.sub_title;
						var _linkText = objx.link_text;
						var _linkUrl = objx.link_url;
						var _device = objx.device;

						$('select[name="device"]').val( _device );
						
						if( _builderType == 'CTA' ) {
							
							$('#CTA_link_text').val( _linkText );
							$('#CTA_link_url').val( _linkUrl );
							$('#CTA_main_title').val( _mainTitle );
							$('#CTA_this_id').val( _ID );

							$('#pgb_cta_modal').modal( {
								backdrop: 'static',
      							keyboard: false
							} );
						}

						if( _builderType == 'HERO_SCW' ) {
							
							$('#HERO_SCW_main_content').val( _mainContent );
							$('#HERO_SCW_this_id').val( _ID );

							$('#pgb_herostat_cw_modal').modal( {
								backdrop: 'static',
      							keyboard: false
							} );
						}

						if( _builderType == 'HERO_SPW' ) {
							
							$('#HERO_SPW_main_content').val( _mainContent );
							$('#HERO_SPW_this_id').val( _ID );

							$('#pgb_herostat_pw_modal').modal( {
								backdrop: 'static',
      							keyboard: false
							} );
						}

						if( _builderType == 'STICKY_BUTT' ) {
							
							$('#STICKY_BUTT_main_title').val( _mainTitle );
							$('#STICKY_BUTT_sub_title').val( _subTitle );
							$('#STICKY_BUTT_link_text').val( _linkText );
							$('#STICKY_BUTT_this_id').val( _ID );

							$('#pgb_stkbutt_modal').modal( {
								backdrop: 'static',
      							keyboard: false
							} );
						}

						if( _builderType == 'EXTRA_SEO' ) {
							
							$('#EXTRA_SEO_this_id').val( _ID );
							
							CKEDITOR.instances[ 'pgb_ext_seo_edt' ].setData( _mainContent );
							
							$('#pgb_ext_seo_modal').modal( {
								backdrop: 'static',
      							keyboard: false
							} );

							/*$("#pgb_ext_seo_modal").on("shown.bs.modal", function () { 
								//
    							
							}).modal('show');*/
						}

						if( _builderType == 'EXTRA_CONT' ) {
							
							$('#EXTRA_CONT_this_id').val( _ID );
							
							CKEDITOR.instances[ 'pgb_ext_cont_edt' ].setData( _mainContent );
							
							$('#pgb_ext_cont_modal').modal( {
								backdrop: 'static',
      							keyboard: false
							} );
						}

						if( _builderType == 'EFORM' ) {
							
							if( pgbFrmLoad == 0 ) {
								$.ajax({
									type: "GET",
									url : "{{ route('pgbAllFrms') }}",
									beforeSend : function() {
										$('#EFORM_ajx_status').html( 'Form loading.. , Please wait...' );
									},
									success : function(allFrms) {
										var objFrm = JSON.parse( allFrms );
										var objLen = objFrm.length;
										var drpHtml = '<option></option>';
										if( objLen > 0 ) {
											for( var frm = 0; frm < objLen; frm++ ) {
												drpHtml += '<option value="' + objFrm[ frm ].frm_scode + '">' + objFrm[ frm ].frm_heading + '</option>'; 
											}
										}
										$('#EFORM_main_content').html( drpHtml );
										$('#EFORM_ajx_status').html( '' );
										$('#EFORM_main_content').val( _mainContent ).trigger('change');
										pgbFrmLoad++;
									}
								});
							}
							$('#EFORM_main_title').val( _mainTitle );
							$('#EFORM_sub_title').val( _subTitle );
							$('#EFORM_main_content').val( _mainContent ).trigger('change');
							$('#EFORM_this_id').val( _ID );

							$('#pgb_eform_modal').modal( {
								backdrop: 'static',
      							keyboard: false
							} );
						}


						if( _builderType == 'REUSE' ) {
							
							if( pgbReuseLoad == 0 ) {
								$.ajax({
									type: "GET",
									url : "{{ route('pgbAllReuse') }}",
									beforeSend : function() {
										$('#REUSE_ajx_status').html( 'Form loading.. , Please wait...' );
									},
									success : function(allFrms) {
										var objFrm = JSON.parse( allFrms );
										var objLen = objFrm.length;
										var drpHtml = '<option></option>';
										if( objLen > 0 ) {
											for( var frm = 0; frm < objLen; frm++ ) {
												drpHtml += '<option value="' + objFrm[ frm ].short_code + '">' + objFrm[ frm ].name + '</option>'; 
											}
										}
										$('#REUSE_main_content').html( drpHtml );
										$('#REUSE_ajx_status').html( '' );
										$('#REUSE_main_content').val( _mainContent ).trigger('change');
										pgbReuseLoad++;
									}
								});
							}
							
							
							$('#REUSE_main_content').val( _mainContent ).trigger('change');
							$('#REUSE_this_id').val( _ID );

							$('#pgb_reuse_modal').modal( {
								backdrop: 'static',
      							keyboard: false
							} );
						}

						if( _builderType == 'IMAGE_CAROUSEL' ) {
							
							$('#IMAGE_CAROUSEL_this_id').val( _ID );

							//$('a[href="#carSeleImgs"]').show();
							$('.nav-tabs a[href="#carSeleImgs"]').show().tab('show');
							var carImgObj = objx.carasoul_images;
							var carImgObjLen = carImgObj.length;
							var viewImgHtml = '<div style="margin-top: 15px; max-height: 400px; overflow-y: auto;">';
								viewImgHtml += '<table class="table table-bordered">';
								viewImgHtml += '<tr>';
									viewImgHtml += '<th>Image</th>';
									viewImgHtml += '<th>Title</th>';
									viewImgHtml += '<th>Alt Tag</th>';
									viewImgHtml += '<th>Caption</th>';
									viewImgHtml += '<th>Action</th>';
								viewImgHtml += '</tr>';
							for( var c = 0; c < carImgObjLen; c++ ) {
								viewImgHtml += '<tr id="edtImgtr_' + carImgObj[ c ].img_id + '">';
									viewImgHtml += '<td>';
										viewImgHtml += '<img src="' + carImgObj[ c ].image + '" style="width: 60px; height: 50px;">'
									viewImgHtml += '</td>';
									viewImgHtml += '<td>'+ carImgObj[ c ].img_title +'</td>';
									viewImgHtml += '<td>'+ carImgObj[ c ].img_alt +'</td>';
									viewImgHtml += '<td>'+ carImgObj[ c ].img_caption +'</td>';
									viewImgHtml += '<td>';
										viewImgHtml += '<a href="javascript:void(0);" class="pgb_img_edt" id="' + carImgObj[ c ].img_id + '"><i class="fa fa-pencil-square base-green" aria-hidden="true"></i></i></a>&nbsp;';
										viewImgHtml += '<a href="javascript:void(0);" class="pgb_img_del" id="' + carImgObj[ c ].img_id + '"><i class="fa fa-trash base-red" aria-hidden="true"></i></a>&nbsp;';
										viewImgHtml += '<input type="hidden" id="img_desc_' + carImgObj[ c ].img_id + '" value="' + carImgObj[ c ].img_desc + '">';
									viewImgHtml += '</td>';
								viewImgHtml += '</tr>';
							}
							viewImgHtml += '</table>';
							viewImgHtml += '</div>';
							$('#carSeleImgs').html( viewImgHtml );
							$('#img_builder_type').val( 'IMAGE_CAROUSEL' );
							$('#imgCarModal').modal( {
								backdrop: 'static',
      							keyboard: false
							} );
						}

						if( _builderType == 'IMAGE_GALLERY' ) {
							
							$('#IMAGE_CAROUSEL_this_id').val( _ID );

							//$('a[href="#carSeleImgs"]').show();
							$('.nav-tabs a[href="#carSeleImgs"]').show().tab('show');
							var carImgObj = objx.carasoul_images;
							var carImgObjLen = carImgObj.length;
							var viewImgHtml = '<div style="margin-top: 15px; max-height: 400px; overflow-y: auto;">';
								viewImgHtml += '<table class="table table-bordered">';
								viewImgHtml += '<tr>';
									viewImgHtml += '<th>Image</th>';
									viewImgHtml += '<th>Title</th>';
									viewImgHtml += '<th>Alt Tag</th>';
									viewImgHtml += '<th>Caption</th>';
									viewImgHtml += '<th>Action</th>';
								viewImgHtml += '</tr>';
							for( var c = 0; c < carImgObjLen; c++ ) {
								viewImgHtml += '<tr id="edtImgtr_' + carImgObj[ c ].img_id + '">';
									viewImgHtml += '<td>';
										viewImgHtml += '<img src="' + carImgObj[ c ].image + '" style="width: 60px; height: 50px;">'
									viewImgHtml += '</td>';
									viewImgHtml += '<td>'+ carImgObj[ c ].img_title +'</td>';
									viewImgHtml += '<td>'+ carImgObj[ c ].img_alt +'</td>';
									viewImgHtml += '<td>'+ carImgObj[ c ].img_caption +'</td>';
									viewImgHtml += '<td>';
										viewImgHtml += '<a href="javascript:void(0);" class="pgb_img_edt" id="' + carImgObj[ c ].img_id + '"><i class="fa fa-pencil-square base-green" aria-hidden="true"></i></i></a>&nbsp;';
										viewImgHtml += '<a href="javascript:void(0);" class="pgb_img_del" id="' + carImgObj[ c ].img_id + '"><i class="fa fa-trash base-red" aria-hidden="true"></i></a>&nbsp;';
										viewImgHtml += '<input type="hidden" id="img_desc_' + carImgObj[ c ].img_id + '" value="' + carImgObj[ c ].img_desc + '">';
									viewImgHtml += '</td>';
								viewImgHtml += '</tr>';
							}
							viewImgHtml += '</table>';
							viewImgHtml += '</div>';
							$('#carSeleImgs').html( viewImgHtml );
							$('#img_builder_type').val( 'IMAGE_GALLERY' );
							$('#imgCarModal').modal( {
								backdrop: 'static',
      							keyboard: false
							} );
						}

						if( _builderType == 'IMAGEGAL_BUTT' ) {
							
							$('#IMAGEGAL_BUTT_this_id').val( _ID );
							$('#img_category').val( _mainContent );

							var subCatObj = objx.SeleSubCats;
							var subCatObjLen = subCatObj.length;
							if( subCatObjLen > 0 ) {
								var ddHtml = '<select name="sub_content" class="form-control" id="img_subcategory">';
									ddHtml = '<option value="">SELECT SUBCATEGORY</option>';
								for( var c = 0; c < subCatObjLen; c++ ) {
									if( subCatObj[c].slug == _subContent ) {
										ddHtml += '<option value="' + subCatObj[c].slug + '" selected="selected">'+ subCatObj[c].name +'</option>';
								    } else {
								    	ddHtml += '<option value="' + subCatObj[c].slug + '">'+ subCatObj[c].name +'</option>';
								    }
								} 

								$('#img_subcategory').html( ddHtml );
							}

							//$('a[href="#carSeleImgs"]').show();
							/*$('.nav-tabs a[href="#carSeleImgs"]').show().tab('show');
							var carImgObj = objx.carasoul_images;
							var carImgObjLen = carImgObj.length;
							var viewImgHtml = '<div style="margin-top: 15px; max-height: 400px; overflow-y: auto;">';
								viewImgHtml += '<table class="table table-bordered">';
								viewImgHtml += '<tr>';
									viewImgHtml += '<th>Image</th>';
									viewImgHtml += '<th>Title</th>';
									viewImgHtml += '<th>Alt Tag</th>';
									viewImgHtml += '<th>Caption</th>';
									viewImgHtml += '<th>Action</th>';
								viewImgHtml += '</tr>';
							for( var c = 0; c < carImgObjLen; c++ ) {
								viewImgHtml += '<tr id="edtImgtr_' + carImgObj[ c ].img_id + '">';
									viewImgHtml += '<td>';
										viewImgHtml += '<img src="' + carImgObj[ c ].image + '" style="width: 60px; height: 50px;">'
									viewImgHtml += '</td>';
									viewImgHtml += '<td>'+ carImgObj[ c ].img_title +'</td>';
									viewImgHtml += '<td>'+ carImgObj[ c ].img_alt +'</td>';
									viewImgHtml += '<td>'+ carImgObj[ c ].img_caption +'</td>';
									viewImgHtml += '<td>';
										viewImgHtml += '<a href="javascript:void(0);" class="pgb_img_edt" id="' + carImgObj[ c ].img_id + '"><i class="fa fa-pencil-square base-green" aria-hidden="true"></i></i></a>&nbsp;';
										viewImgHtml += '<a href="javascript:void(0);" class="pgb_img_del" id="' + carImgObj[ c ].img_id + '"><i class="fa fa-trash base-red" aria-hidden="true"></i></a>&nbsp;';
										viewImgHtml += '<input type="hidden" id="img_desc_' + carImgObj[ c ].img_id + '" value="' + carImgObj[ c ].img_desc + '">';
									viewImgHtml += '</td>';
								viewImgHtml += '</tr>';
							}
							viewImgHtml += '</table>';
							viewImgHtml += '</div>';
							$('#carSeleImgs').html( viewImgHtml );
							$('#img_builder_type').val( 'IMAGE_GALLERY' );*/
							$('#imageGalModal').modal( {
								backdrop: 'static',
      							keyboard: false
							} );
						}

						if( _builderType == 'VIDEO_GALLERY' ) {
							
							$('#VIDEO_this_id').val( _ID );

							$('.nav-tabs a[href="#vidSelected"]').show().tab('show');
							var vidObj = objx.video_data;
							var vidObjLen = vidObj.length;
							var viewHtml = '<div style="margin-top: 10px; max-height: 400px; overflow-y: auto;">';
								viewHtml += '<table class="table table-bordered">';
									viewHtml += '<tr style="background-color: #ccc;">';
										viewHtml += '<th>Video</th>';
										viewHtml += '<th>Name</th>';
										viewHtml += '<th>Title</th>';
										viewHtml += '<th>Caption</th>';
										viewHtml += '<th>Action</th>';
									viewHtml += '</tr>';
							for( var c = 0; c < vidObjLen; c++ ) {
								viewHtml += '<tr id="editVidTr_' + vidObj[ c ].video_id +'">';
										viewHtml += '<td>';
											if( vidObj[ c ].video_type == '1' ) {
												viewHtml += '<img src="//img.youtube.com/vi/' + vidObj[ c ].video_link + '/sddefault.jpg" width="100" height="75" />';
											}
										viewHtml += '</td>'
										viewHtml += '<td>' + vidObj[ c ].video_name + '</td>';
										viewHtml += '<td>' + vidObj[ c ].video_title + '</td>';
										viewHtml += '<td>' + vidObj[ c ].video_caption + '</td>';
										viewHtml += '<td>';
											viewHtml += '<a href="javascript:void(0);" class="pgb_vid_del" id="' + vidObj[ c ].video_id + '"><i class="fa fa-trash base-red" aria-hidden="true"></i></a>&nbsp;';
											viewHtml += '&nbsp;<a href="javascript:void(0);" class="pgb_vid_edt" id="' + vidObj[ c ].video_id + '"><i class="fa fa-pencil-square base-green" aria-hidden="true"></i></a>';
											/** Extra Data **/
											
										viewHtml += '</td>';
									viewHtml += '</tr>';
							}
							viewHtml += '</table>';
							viewImgHtml += '</div>';
							$('#vidSelected').html( viewHtml );
							$('#vid_builder_type').val( 'VIDEO_GALLERY' );
							$('#videoModal').modal( {
								backdrop: 'static',
      							keyboard: false
							} );
						}

						/*if( _builderType == 'TECHRES_BUTT' ) {
							
							$('#BROCHURE_BUTT_this_id').val( _ID );

							$('.nav-tabs a[href="#brochureSeletFiles"]').show().tab('show');
							var fileObj = objx.brochure_data;
							var fileObjLen = fileObj.length;
							var viewHtml = '<div style="margin-top: 10px; max-height: 400px; overflow-y: auto;">';
								viewHtml += '<table class="table table-bordered">';
									viewHtml += '<tr style="background-color: #ccc;">';
										viewHtml += '<th>File</th>';
										viewHtml += '<th>Name</th>';
										viewHtml += '<th>Title</th>';
										viewHtml += '<th>Caption</th>';
										viewHtml += '<th>Action</th>';
									viewHtml += '</tr>';
							for( var c = 0; c < fileObjLen; c++ ) {
								viewHtml += '<tr id="editFilTr_' + fileObj[ c ].file_id +'">';
										viewHtml += '<td>';
											var icon = '';
											if( fileObj[ c ].file_ext == 'pdf' ) {
												icon = '<i class="fa fa-file-pdf-o base-red" aria-hidden="true"></i>';
											} else if( fileObj[ c ].file_ext == 'docx' ) {
												icon = '<i class="fa fa-file-word-o base-blue" aria-hidden="true"></i>';
											} else if( fileObj[ c ].file_ext == 'csv' ) {
												icon = '<i class="fa fa-file-excel-o base-green" aria-hidden="true"></i>';
											} else if( fileObj[ c ].file_ext == 'pptx' ) {
												icon = '<i class="fa fa-file-powerpoint-o base-red" aria-hidden="true"></i>';
											} else {
												icon = '<i class="fa fa-file-powerpoint-o base-red" aria-hidden="true"></i>';
											}
											viewHtml += icon;
										viewHtml += '</td>'
										viewHtml += '<td>' + fileObj[ c ].file_name + '</td>';
										viewHtml += '<td>' + fileObj[ c ].file_title + '</td>';
										viewHtml += '<td>' + fileObj[ c ].file_caption + '</td>';
										viewHtml += '<td>';
											viewHtml += '<a href="javascript:void(0);" class="pgb_fil_del" id="' + fileObj[ c ].file_id + '"><i class="fa fa-trash base-red" aria-hidden="true"></i></a>&nbsp;';
											viewHtml += '&nbsp;<a href="javascript:void(0);" class="pgb_fil_edt" id="' + fileObj[ c ].file_id + '"><i class="fa fa-pencil-square base-green" aria-hidden="true"></i></a>';
											
											viewHtml += '<input type="hidden" id="file_desc_' + fileObj[ c ].file_id + '" value="' + fileObj[ c ].file_desc + '">';
										viewHtml += '</td>';
									viewHtml += '</tr>';
							}
							viewHtml += '</table>';
							viewImgHtml += '</div>';
							$('#brochureSeletFiles').html( viewHtml );
							$('#file_builder_type').val( 'TECHRES_BUTT' );
							$('#brochureModal').modal( {
								backdrop: 'static',
      							keyboard: false
							} );
						}*/

						if( _builderType == 'BROCHURE_BUTT' ) {
							
							$('#BROCHURE_BUTT_this_id').val( _ID );
							$('#file_category').val( _mainContent );

							var subCatObj = objx.SeleSubCats;
							var subCatObjLen = subCatObj.length;
							if( subCatObjLen > 0 ) {
								var ddHtml = '<select name="sub_content" class="form-control" id="file_subcategory">';
									ddHtml = '<option value="">SELECT SUBCATEGORY</option>';
								for( var c = 0; c < subCatObjLen; c++ ) {
									if( subCatObj[c].slug == _subContent ) {
										ddHtml += '<option value="' + subCatObj[c].slug + '" selected="selected">'+ subCatObj[c].name +'</option>';
								    } else {
								    	ddHtml += '<option value="' + subCatObj[c].slug + '">'+ subCatObj[c].name +'</option>';
								    }
								} 

								$('#file_subcategory').html( ddHtml );
							}

							/*$('.nav-tabs a[href="#brochureSeletFiles"]').show().tab('show');
							var fileObj = objx.brochure_data;
							var fileObjLen = fileObj.length;
							var viewHtml = '<div style="margin-top: 10px; max-height: 400px; overflow-y: auto;">';
								viewHtml += '<table class="table table-bordered">';
									viewHtml += '<tr style="background-color: #ccc;">';
										viewHtml += '<th>File</th>';
										viewHtml += '<th>Name</th>';
										viewHtml += '<th>Title</th>';
										viewHtml += '<th>Caption</th>';
										viewHtml += '<th>Action</th>';
									viewHtml += '</tr>';
							for( var c = 0; c < fileObjLen; c++ ) {
								viewHtml += '<tr id="editFilTr_' + fileObj[ c ].file_id +'">';
										viewHtml += '<td>';
											var icon = '';
											if( fileObj[ c ].file_ext == 'pdf' ) {
												icon = '<i class="fa fa-file-pdf-o base-red" aria-hidden="true"></i>';
											} else if( fileObj[ c ].file_ext == 'docx' ) {
												icon = '<i class="fa fa-file-word-o base-blue" aria-hidden="true"></i>';
											} else if( fileObj[ c ].file_ext == 'csv' ) {
												icon = '<i class="fa fa-file-excel-o base-green" aria-hidden="true"></i>';
											} else if( fileObj[ c ].file_ext == 'pptx' ) {
												icon = '<i class="fa fa-file-powerpoint-o base-red" aria-hidden="true"></i>';
											} else {
												icon = '<i class="fa fa-file-powerpoint-o base-red" aria-hidden="true"></i>';
											}
											viewHtml += icon;
										viewHtml += '</td>'
										viewHtml += '<td>' + fileObj[ c ].file_name + '</td>';
										viewHtml += '<td>' + fileObj[ c ].file_title + '</td>';
										viewHtml += '<td>' + fileObj[ c ].file_caption + '</td>';
										viewHtml += '<td>';
											viewHtml += '<a href="javascript:void(0);" class="pgb_fil_del" id="' + fileObj[ c ].file_id + '"><i class="fa fa-trash base-red" aria-hidden="true"></i></a>&nbsp;';
											viewHtml += '&nbsp;<a href="javascript:void(0);" class="pgb_fil_edt" id="' + fileObj[ c ].file_id + '"><i class="fa fa-pencil-square base-green" aria-hidden="true"></i></a>';
											/** Extra Data **/
											/*viewHtml += '<input type="hidden" id="file_desc_' + fileObj[ c ].file_id + '" value="' + fileObj[ c ].file_desc + '">';
										viewHtml += '</td>';
									viewHtml += '</tr>';
							}
							viewHtml += '</table>';
							viewImgHtml += '</div>';
							$('#brochureSeletFiles').html( viewHtml );
							$('#file_builder_type').val( 'BROCHURE_BUTT' );*/
							$('#brochureModal').modal( {
								backdrop: 'static',
      							keyboard: false
							} );
						}


						if( _builderType == 'PRODUCT_LINKS' ) {
							
							$('#LINKS_this_id').val( _ID );
							$('#link_heading').val( _mainTitle );

							var linksObj = objx.all_links;
							var linksObjLen = linksObj.length;
							var viewHtml = '<label>Manage Product Links</label>';
							    viewHtml += '<div style="margin-top: 15px; max-height: 400px; overflow-y: auto;">';
								viewHtml += '<table class="table table-bordered">';
							
							for( var c = 0; c < linksObjLen; c++ ) {

								viewHtml += '<tr>';
									viewHtml += '<td>';
										if( linksObj[ c ].isSelected == 'YES') {
											viewHtml += '<input type="checkbox" name="slugs[]" checked="checked" class="ckblinks" value="' + linksObj[ c ].slug +'">&nbsp;';
										} else {
											viewHtml += '<input type="checkbox" name="slugs[]" class="ckblinks" value="' + linksObj[ c ].slug +'">&nbsp;';
										}
										viewHtml += linksObj[ c ].name ;
									viewHtml += '</td>';
									viewHtml += '<td>';
										viewHtml += linksObj[ c ].display_slug ;
									viewHtml += '</td>';
								viewHtml += '</tr>';
							}
							viewHtml += '</table>';
							viewHtml += '</div>';
							$('#linkbox').html( viewHtml );
							$('#linkType').attr('disabled','disabled');
							$('#links_builder_type').val( _builderType );
							$('#linksModal').modal( {
								backdrop: 'static',
      							keyboard: false
							} );
						}


						if( _builderType == 'PRODUCT_BOX' ) {
							
							$('#PrdBoxLINKS_this_id').val( _ID );
							$('#prdbox_link_heading').val( _mainTitle );

							var linksObj = objx.all_links;
							var linksObjLen = linksObj.length;
							var viewHtml = '<label>Manage Product Box Links</label>';
							    viewHtml += '<div style="margin-top: 15px; max-height: 400px; overflow-y: auto;">';
								viewHtml += '<table class="table table-bordered">';
							
							for( var c = 0; c < linksObjLen; c++ ) {

								viewHtml += '<tr>';
									viewHtml += '<td>';
										if( linksObj[ c ].isSelected == 'YES') {
											viewHtml += '<input type="checkbox" name="slugs[]" checked="checked" class="ckblinks" value="' + linksObj[ c ].slug +'">&nbsp;';
										} else {
											viewHtml += '<input type="checkbox" name="slugs[]" class="ckblinks" value="' + linksObj[ c ].slug +'">&nbsp;';
										}
										viewHtml += linksObj[ c ].name ;
									viewHtml += '</td>';
									viewHtml += '<td>';
										viewHtml += linksObj[ c ].display_slug ;
									viewHtml += '</td>';
								viewHtml += '</tr>';
							}
							viewHtml += '</table>';
							viewHtml += '</div>';
							$('#prodbox_linkbox').html( viewHtml );
							$('#addPrdBoxSeleLinks').removeAttr('disabled');

							/* Reusable */
							var reusObj = objx.pboxReus;
							var reusObjLen = reusObj.length;
							var reuHtml = '<option value="">-SELECT-</option>';
							for( var c = 0; c < reusObjLen; c++ ) {
								if( _linkText == reusObj[c].id ) {
									reuHtml += "<option value='"+ reusObj[c].id +"' selected='selected'>"+ reusObj[c].name +"</option>";
								} else {
									reuHtml += "<option value='"+ reusObj[c].id +"'>"+ reusObj[c].name +"</option>";
								}
							}
							
							$('#pbox_reu_id').html(reuHtml).removeAttr('disabled');

							$('#column_key').val( _linkUrl );
							/**/
							
							$('#prdboxModal').modal( {
								backdrop: 'static',
      							keyboard: false
							} );
						}

						if( _builderType == 'NEWS_LINKS' ) {
							
							$('#LINKS_this_id').val( _ID );
							$('#link_heading').val( _mainTitle );

							var linksObj = objx.all_links;
							var linksObjLen = linksObj.length;
							var viewHtml = '<label>Manage News Links</label>';
							    viewHtml += '<div style="margin-top: 15px; max-height: 400px; overflow-y: auto;">';
								viewHtml += '<table class="table table-bordered">';
							
							for( var c = 0; c < linksObjLen; c++ ) {

								viewHtml += '<tr>';
									viewHtml += '<td>';
										if( linksObj[ c ].isSelected == 'YES') {
											viewHtml += '<input type="checkbox" name="slugs[]" checked="checked" class="ckblinks" value="' + linksObj[ c ].slug +'">&nbsp;';
										} else {
											viewHtml += '<input type="checkbox" name="slugs[]" class="ckblinks" value="' + linksObj[ c ].slug +'">&nbsp;';
										}
										viewHtml += linksObj[ c ].name ;
									viewHtml += '</td>';
									viewHtml += '<td>';
										viewHtml += linksObj[ c ].display_slug ;
									viewHtml += '</td>';
								viewHtml += '</tr>';
							}
							viewHtml += '</table>';
							viewHtml += '</div>';
							$('#linkbox').html( viewHtml );
							$('#linkType').attr('disabled','disabled');
							$('#links_builder_type').val( _builderType );
							$('#linksModal').modal( {
								backdrop: 'static',
      							keyboard: false
							} );
						}


						if( _builderType == 'PEOPLE_LINKS' ) {
							
							$('#LINKS_this_id').val( _ID );
							$('#link_heading').val( _mainTitle );

							var linksObj = objx.all_links;
							var linksObjLen = linksObj.length;
							var viewHtml = '<label>Manage People Links</label>';
							    viewHtml += '<div style="margin-top: 15px; max-height: 400px; overflow-y: auto;">';
								viewHtml += '<table class="table table-bordered">';
							
							for( var c = 0; c < linksObjLen; c++ ) {

								viewHtml += '<tr>';
									viewHtml += '<td>';
										if( linksObj[ c ].isSelected == 'YES') {
											viewHtml += '<input type="checkbox" name="slugs[]" checked="checked" class="ckblinks" value="' + linksObj[ c ].slug +'">&nbsp;';
										} else {
											viewHtml += '<input type="checkbox" name="slugs[]" class="ckblinks" value="' + linksObj[ c ].slug +'">&nbsp;';
										}
										viewHtml += linksObj[ c ].name ;
									viewHtml += '</td>';
									viewHtml += '<td>';
										viewHtml += linksObj[ c ].display_slug ;
									viewHtml += '</td>';
								viewHtml += '</tr>';
							}
							viewHtml += '</table>';
							viewHtml += '</div>';
							$('#linkbox').html( viewHtml );
							$('#linkType').attr('disabled','disabled');
							$('#links_builder_type').val( _builderType );
							$('#linksModal').modal( {
								backdrop: 'static',
      							keyboard: false
							} );
						}


						if( _builderType == 'CUSTOM_LINKS' ) {
							
							$('#CUSTOMLINKS_this_id').val( _ID );
							$('#custom_link_heading').val( _mainTitle );

							var linksObj = objx.all_links;
							var linksObjLen = linksObj.length;
							
							var clHtml = '';
							var clMore = 0;

							for( var c = 0; c < linksObjLen; c++ ) {

								if( c == 0 ) {
									$('#fclText').val( linksObj[ c ].text );
									$('#fclSlug').val( linksObj[ c ].slug );
								} else {
									clHtml += '<div class="row" id="clDiv_' + clMore + '">';
						        	clHtml += '<div class="col-md-5"><div class="form-group"><input type="text" name="custom_link_text[]" class="form-control custom_link_text" placeholder="Link Text" required="required" value="'+ linksObj[ c ].text +'"></div></div>';
						        	clHtml += '<div class="col-md-6"><div class="form-group"><input type="url" name="custom_link_slug[]" class="form-control custom_link_slug" placeholder="Link" required="required" value="'+ linksObj[ c ].slug +'"></div></div>';
						        	clHtml += '<div class="col-md-1"><div class="form-group"><a href="javascript:void(0);" class="rmcl" id="'+ clMore +'">[x]</a></div></div>';
						        	clHtml += '</div>';

						        	clMore++;
						    	}
						    
							}
							
							$('#CUSTOMLINKS_more').html( clHtml );
							$('#addSeleCustomLinks').val('Update Custom Links');
							
							$('#customlinksModal').modal( {
								backdrop: 'static',
      							keyboard: false
							} );
						}


						if( _builderType == 'METRIC' ) {
							
							$('#METRIC_this_id').val( _ID );

							$('#mtext1').val( _mainTitle );
						    $('#mtext2').val( _subTitle );
						    $('#mtextbg').val( _linkText );
						    $('#mtextco').val( _linkUrl );
						    $('#mtcont').val( _mainContent );
						    $('#mtyp').val( _subContent );

							$('#metricModal').modal( {
								backdrop: 'static',
      							keyboard: false
							} );
						}

						if( _builderType == 'ACCORDION' ) {
							
							$('#ACCORDION_this_id').val( _ID );

							var accrObj = objx.accr_data;
							var accrObjLen = accrObj.length;
							
							var clHtml = '';
							var clMore = 1;

							for( var c = 0; c < accrObjLen; c++ ) {

								if( c == 0 ) {
									$('#accr0_heading').val( accrObj[ c ].heading );
									CKEDITOR.instances[ 'accr0' ].setData( accrObj[ c ].content );
								} else {
									clHtml = '<div class="row" id="clDiv_' + clMore + '">';
							          clHtml += '<div class="col-md-10">';
							            clHtml += '<div class="form-group">';
							              clHtml += '<label>Accordion Heading</label><input type="text" name="accordion_heading[]" id="accordion_heading" class="form-control" placeholder="accordion heading" required="required" value="' + accrObj[ c ].heading + '">';
							            clHtml += '</div>';
							            clHtml += '<div class="form-group">';
							              clHtml += '<label>Accordion Body Content</label><textarea name="accordion_body_content[]" id="accr' + clMore + '" class="form-control accordion" placeholder="accordion body content" required="required">' + accrObj[ c ].content + '</textarea>';
							            clHtml += '</div>';
							          clHtml += '</div>';
							          clHtml += '<div class="col-md-2">';
							            clHtml += '<div class="form-group">';
							              clHtml += '<a href="javascript:void(0)" class="rmcl" id="' + clMore + '">[x]</a>';
							            clHtml += '</div>';
							          clHtml += '</div>';
							        clHtml += '</div>';

						        	clMore++;
						    	}
						    
							}
							
							$('#ACCORDION_more').html( clHtml );

							/** For generate editor */
							var clMore = 1;
							for( var c = 0; c < accrObjLen; c++ ) {
								if( c == 0 ) {
								} else {
									CKEDITOR.replace( 'accr' + clMore, {
								      customConfig: "{{ asset('public/assets/ckeditor/accr_config.js') }}",
								    } );
								  	clMore++;
								}
							}
							/** For generate editor */
							
							$('#addAccordion').val('Update Accordion');
							
							$('#accordionModal').modal( {
								backdrop: 'static',
      							keyboard: false
							} );
						}

						_holder.unblock();
					}
				}
			});
		}
	} );
} );
</script>





<!-- 
	Shorting & Position Change
------------------------------------------------------------------- 
-->


<script type="text/javascript">

/** DOC
https://github.com/SortableJS/Sortable
**/

var pgContentAppend = document.getElementById('pgContentAppend');
var pgContentAppendRight = document.getElementById('pgContentAppendRight');

new Sortable(pgContentAppend, {
	group: 'shared', // set both lists to same group
	animation: 150,
	onAdd: function ( evt ) {
		positionChange( evt.item.id , 'BODY' );
	},
	onUpdate: function () {
		var selectedData = new Array();
		$('#pgContentAppend .ar-order').each(function() {
			selectedData.push( $(this).attr('id') );
		});
		updateOrder( selectedData );
	}
});

new Sortable(pgContentAppendRight, {
	group: 'shared', // set both lists to same group
	animation: 150,
	onAdd: function ( evt ) {
		positionChange( evt.item.id , 'RIGHT' );
	},
	onUpdate: function () {
		var selectedData = new Array();
		$('#pgContentAppendRight .ar-order').each(function() {
			selectedData.push( $(this).attr('id') );
		});
		updateOrder( selectedData );
	}
});

function updateOrder( data ) {
       
  $.ajax({
    type : "POST",
    url : "{{ route('pgbOrd') }}",
    data : {
    	"ids" : data,
    	"_token" : "{{ csrf_token() }}"
    },
    beforeSend : function() {
      $('#pgContentAppend , #pgContentAppendRight').block({ 
          message: '<h4>Please wait...</h4>', 
          css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
          } 
      }); 
    },
    success : function(st){
      if(st == 'ok'){
        $( '#pgContentAppend , #pgContentAppendRight' ).unblock();
      }
    }
  });
}

function positionChange( id , pos ) {
	
 $.ajax({
    type : "POST",
    url : "{{ route('pgbChng') }}",
    data : {
    	"id" : id,
    	"position" : pos,
    	"_token" : "{{ csrf_token() }}"
    },
    beforeSend : function() {
      $( '#pgContentAppend , #pgContentAppendRight' ).block({ 
          message: '<h4>Please wait...</h4>', 
          css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
          } 
      }); 
    },
    success : function(st){
      if(st == 'ok'){
        $( '#pgContentAppend , #pgContentAppendRight' ).unblock();
      }
    }
  });	
}
</script>


<!-- Page Builder Images Delete -->
<script type="text/javascript">
$( function() {
	$('body').on('click', '.pgb_img_del', function() {
		if( confirm('Are You Sure To Delete This Image From Carousel ?') ) {
			if( $( this ).attr('id') != '' || $( this ).attr('id') != 'undefined' ) {
				var img_id = $( this ).attr('id');
				$.ajax({
					type : "POST",
					url : "{{ route('pgbDelImg') }}",
					data : {
						"img_id" : img_id,
						"_token" : "{{ csrf_token() }}"
					},
					beforeSend : function() {
						$('a#' + img_id).removeClass('pgb_img_del').text('wait..');
					},
					success : function(res) {
						if( res == 'ok' ) {
							$( 'tr#edtImgtr_'+ img_id ).remove();
						}
					}
				});
			}
		}
	} );
} );
</script>

<!-- Page Builder Videos Delete -->
<script type="text/javascript">
$( function() {
	$('body').on('click', '.pgb_vid_del', function() {
		if( confirm('Are You Sure To Delete This Video ?') ) {
			if( $( this ).attr('id') != '' || $( this ).attr('id') != 'undefined' ) {
				var video_id = $( this ).attr('id');
				$.ajax({
					type : "POST",
					url : "{{ route('pgbDelVid') }}",
					data : {
						"video_id" : video_id,
						"_token" : "{{ csrf_token() }}"
					},
					beforeSend : function() {
						$('a#' + video_id).removeClass('pgb_vid_del').text('wait..');
					},
					success : function(res) {
						if( res == 'ok' ) {
							$( 'tr#editVidTr_'+ video_id ).remove();
						}
					}
				});
			}
		}
	} );
} );
</script>


<!-- Page Builder File Delete -->
<script type="text/javascript">
$( function() {
	$('body').on('click', '.pgb_fil_del', function() {
		if( confirm('Are You Sure To Delete This File ?') ) {
			if( $( this ).attr('id') != '' || $( this ).attr('id') != 'undefined' ) {
				var file_id = $( this ).attr('id');
				$.ajax({
					type : "POST",
					url : "{{ route('pgbDelFil') }}",
					data : {
						"file_id" : file_id,
						"_token" : "{{ csrf_token() }}"
					},
					beforeSend : function() {
						$('a#' + file_id).removeClass('pgb_fil_del');
						$('tr#editFilTr_' + file_id).css('background-color', '#ffcccc');
					},
					success : function(res) {
						if( res == 'ok' ) {
							$('tr#editFilTr_' + file_id).remove();
						}
					}
				});
			}
		}
	} );
} );
</script>

<script type="text/javascript">
$( function() {
	$('body').on('click', '.pgb_fil_edt', function() {
		var getFilID = $.trim( $(this).attr('id') );
		if( getFilID != '' && getFilID != 'undefined' ) {

			$('#brochure_name').val( $('tr#editFilTr_' + getFilID).find( "td:eq(1)" ).text() ).removeAttr( 'readonly' );
			$('#brochure_title').val( $('tr#editFilTr_' + getFilID).find( "td:eq(2)" ).text() ).removeAttr( 'readonly' );
			$('#brochure_caption').val( $('tr#editFilTr_' + getFilID).find( "td:eq(3)" ).text() ).removeAttr( 'readonly' );
			$('#brochure_desc').val( $('#file_desc_' + getFilID).val() ).removeAttr( 'readonly' );
			$('#BROCHURE_FILE').val( 'Media-File-' + getFilID );
			$('#sele_brochure_id').val( getFilID );

			$('.nav-tabs a[href="#brochureFileLibrary"]').tab('show');

			$('#setBrochureInfo').removeAttr( 'disabled' );

			$('#addSeletBrochure').text('Add Files').attr('disabled', 'disabled');
            $('#brochureLibraryBox .fileTr').removeClass('ar-select-tr');
            var brochureFileInfoCollection = new Object();

            $('#setBrochureInfo_Action').val( 'RESET' );
		}
	} );
	$('body').on('click', '.pgb_img_edt', function() {
		var getFilID = $.trim( $(this).attr('id') );
		if( getFilID != '' && getFilID != 'undefined' ) {

			$('#img_title').val( $('tr#edtImgtr_' + getFilID).find( "td:eq(1)" ).text() ).removeAttr( 'readonly' );
			$('#img_alt').val( $('tr#edtImgtr_' + getFilID).find( "td:eq(2)" ).text() ).removeAttr( 'readonly' );
			$('#img_caption').val( $('tr#edtImgtr_' + getFilID).find( "td:eq(3)" ).text() ).removeAttr( 'readonly' );
			$('#img_desc').val( $('#img_desc_' + getFilID).val() ).removeAttr( 'readonly' );
			$('#IMG_ID').val( 'Media-Image-' + getFilID );
			$('#sele_img_id').val( getFilID );

			$('.nav-tabs a[href="#imgLibrary"]').tab('show');

			$('#setTag').removeAttr( 'disabled' );

			$('#addSeletImgs').text('Add Files').attr('disabled', 'disabled');
            $('.lib-img-box').removeClass('selet-lib-img');
            var imageCollection = new Object();
            var imageInfoCollection = new Object();

            $('#setImageInfo_Action').val( 'RESET' );
		}
	} );
	$('body').on('click', '.pgb_vid_edt', function() {
		var getVidID = $.trim( $(this).attr('id') );
		if( getVidID != '' && getVidID != 'undefined' ) {

			$('#vid_name').val( $('tr#editVidTr_' + getVidID).find( "td:eq(1)" ).text() ).removeAttr( 'readonly' );
			$('#vid_title').val( $('tr#editVidTr_' + getVidID).find( "td:eq(2)" ).text() ).removeAttr( 'readonly' );
			$('#vid_caption').val( $('tr#editVidTr_' + getVidID).find( "td:eq(3)" ).text() ).removeAttr( 'readonly' );
			$('#VID_INFO_ID').val( 'Media-Video-' + getVidID );
			$('#sele_vid_id').val( getVidID );

			$('.nav-tabs a[href="#vidLibrary"]').tab('show');

			$('#setVidTag').removeAttr( 'disabled' );

			$('#addSeletVids').text('Add Videos').attr('disabled', 'disabled');
            $('#VidLibContainer .vbox').removeClass('ar-select-tr');
            var vidInfoCollection = new Object();

            $('#setVidInfo_Action').val( 'RESET' );
		}
	} );
} );
</script>

<script type="text/javascript">
$( function () {
	var hg = $('#pgContentAppend').height() + 50;
	var hg2 = $('#pgContentAppendRight').height() + 50;

	if( hg > hg2 ) {
		$('#pgContentAppendRight').css('height' , hg + 'px');
	} else if( hg2 > hg ) {
		$('#pgContentAppend').css('height' , hg2 + 'px');
	} else {
		$('#pgContentAppendRight').css('height' , 'auto');
		$('#pgContentAppend').css('height' , 'auto');
	}
	
	$('#savePgContent').on('click', function() {
		$('form[name="jfrm"]').submit();
	} );
} );
</script>


