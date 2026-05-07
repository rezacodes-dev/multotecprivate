<!-- Media Modal -->
<div class="modal fade" id="brochureModal" role="dialog"> <!-- brochureModal -->
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="brochureModalClose btn btn-xs btn-danger pull-right" data-dismiss="modal">Exit</button>
        <h4 class="modal-title">
          <strong><i class="fa fa-random" aria-hidden="true"></i> Media Files | Brochure</strong>
        </h4>
      </div>
      <div class="modal-body modal_min_height">
        <ul class="nav nav-tabs" id="brochureModalTabs">
          <li class="active"><a data-toggle="tab" href="#brochureUpload"><i class="fa fa-upload" aria-hidden="true"></i> Upload</a></li>
          <li><a data-toggle="tab" href="#brochureFileLibrary"><i class="fa fa-file-text-o" aria-hidden="true"></i> File Library</a></li>
          <li><a data-toggle="tab" href="#brochureSeletFiles" style="display: none;"> <i class="fa fa-check-square-o" aria-hidden="true"></i>
          Current Files</a></li>
        </ul>
        <div class="tab-content">
          <div id="brochureUpload" class="tab-pane fade in active">
            <form name="brochureFileUploadFrm" id="brochureFileUploadFrm" action="{{ route('ajxMediaFileUpload') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row" style="margin-top: 30px;">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Choose File(s) :</label>
                  <input type="file" name="brochure[]" id="brochureFiles" multiple="multiple" accept=".pdf,application/pdf,.csv,text/csv,.doc,.docx,application/msword,.xls,.xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,application/vnd.msexcel,.ppt,application/vnd.ms-powerpoint,.pptx,application/vnd.openxmlformats-officedocument.presentationml.presentation" required="required">
                  <span id="brochureUpload_Info"></span>
                </div>
                <div>
                  <span><small><code>[Max 10 Files At Once]</code></small></span><br/>
                  <span><small><code>[Max 2MB Each File Size]</code></small></span>
                </div>
                <div id="brochureUploadpreviewBox"></div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Name :</label>
                  <input type="text" name="name" class="form-control" placeholder="File Name">
                </div>
                <div class="form-group">
                  <label>Title :</label>
                  <input type="text" name="title" class="form-control" placeholder="File Title">
                </div>
                <div class="form-group">
                  <label>Caption :</label>
                  <textarea name="caption" class="form-control" placeholder="File Caption"></textarea>
                </div>
                <div class="form-group">
                  <label>Desctiption :</label>
                  <textarea name="details" class="form-control" placeholder="Details"></textarea>
                </div>
                <div class="form-group">
                  <input type="submit" class="btn btn-primary" id="brochureUpload_BTN" value="Upload">
                  <div id="brochureUploadStatus"></div>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Category :</label>
                  <select name="file_category_id" class="form-control lodFilCats">
                    
                  </select>
                </div>
                <div class="form-group">
                  <label>Subcategory :</label>
                  <select name="file_subcategory_id" class="form-control lodFilSCats">
                    
                  </select>
                </div>
                <div class="form-group">
                  <label>Upload A4 File :</label>
                  <input type="file" name="a4file">
                </div>
                <div class="form-group">
                  <label>Upload Letter File :</label>
                  <input type="file" name="letterfile">
                </div>
              </div>
            </div>
          </form>
          </div>
          <div id="brochureFileLibrary" class="tab-pane fade">
            <div class="row" style="margin-top: 5px;">
              <div class="col-md-3">
                <select name="device" class="form-control" id="filDevice">
                  <option value="3">For Both</option>
                  <option value="1">For Desktop</option>
                  <option value="2">For Mobile</option>
                </select>
              </div>
              <div class="col-md-3">
                <select name="category_id" id="brochure_category_id" class="form-control select2 lodFilCats" style="width: 100%;">
                  <option value="0">-Categories-</option>
                </select>
              </div>
              <div class="col-md-3">
                <input type="text" id="brochure_src_txt" name="src_txt" class="form-control" placeholder="File Name">
              </div>
              <div class="col-md-3" style="text-align: right;">
                <button class="btn btn-primary btn-sm" id="brochure_find">Find</button>
                <button class="btn btn-danger btn-sm" id="reloadBrochure">Reload</button>
                <button class="btn btn-success btn-sm" id="addSeletBrochure">Add Files</button>
                @if( isset($pageBuilderData) && !empty($pageBuilderData) )
                <input type="hidden" name="insert_id" id="BROCHURE_BUTT_insert_id" value="{{ $pageBuilderData->insert_id }}">
                @else
                <input type="hidden" name="insert_id" id="BROCHURE_BUTT_insert_id" value="@if( isset( $insert_id ) ){{ $insert_id }}@endif">
                @endif
                <input type="hidden" class="this_id" name="this_id" id="BROCHURE_BUTT_this_id" value="0">
                <input type="hidden" name="builder_type" id="file_builder_type">
              </div>
            </div>
            <div class="row">
              <div class="col-md-12"><hr style="margin: 0; margin-bottom: 6px;" /></div>
            </div>
            <div class="row">
              <div class="col-md-8">
                <div id="brochureLibraryBox"></div>
              </div>
              <div class="col-md-4">
                <div class="ar-form-group">
                  <!--label>ID :</label-->
                  <input type="text" id="BROCHURE_FILE" placeholder="BROCHURE FILE" class="form-control" readonly="readonly">
                </div>
                <div class="ar-form-group">
                  <label>Name :</label>
                  <input type="text" name="brochure_name" id="brochure_name" placeholder="Brochure Name" class="form-control" readonly="readonly">
                </div>
                <div class="ar-form-group">
                  <label>Title :</label>
                  <input type="text" name="brochure_title" id="brochure_title" placeholder="brochure Title" class="form-control" readonly="readonly">
                </div>
                <div class="ar-form-group">
                  <label>Caption :</label>
                  <textarea name="brochure_caption" id="brochure_caption" class="form-control" placeholder="Brochure Caption" readonly="readonly"></textarea>
                </div>
                <div class="ar-form-group">
                  <label>Description :</label>
                  <textarea name="brochure_desc" id="brochure_desc" class="form-control" placeholder="Brochure Description" readonly="readonly"></textarea>
                </div>
                <div class="ar-form-group" style="margin-top: 6px;">
                  <input type="button" id="setBrochureInfo" value="Save All Info" class="btn btn-success btn-sm arp_btn" disabled="disabled">
                  <input type="hidden" name="sele_brochure_id" id="sele_brochure_id">
                  <input type="hidden" id="setBrochureInfo_Action" value="SET">
                </div>
              </div>
            </div>
          </div>
          <div id="brochureSeletFiles" class="tab-pane fade">
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>