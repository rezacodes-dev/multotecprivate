<!-- Media Modal -->
<div class="modal fade" id="mediaModal" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close eleModalClose base-red">&times;</button>
        <h4 class="modal-title">
          <strong><i class="fa fa-random" aria-hidden="true"></i> Media <span class="addOnEdtName"></span></strong>
        </h4>
      </div>
      <div class="modal-body modal_min_height">
        <input type="hidden" id="Media_modalEleSetEdtId" value="">
        <ul class="nav nav-tabs" id="eleTabs">
          <li class="active"><a data-toggle="tab" href="#Media_imgUpload"><i class="fa fa-upload" aria-hidden="true"></i> Upload</a></li>
          <li><a data-toggle="tab" href="#Media_imgLibrary"><i class="fa fa-picture-o" aria-hidden="true"></i> Images</a></li>
        </ul>
        <div class="tab-content">
          <div id="Media_imgUpload" class="tab-pane fade in active">
            <form name="Media_mediaUpload" id="Media_mediaUpload" action="{{ route('ajxMediaImgUpload') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row" style="margin-top: 30px;">
              <div class="col-md-3">
                <span><small><code>[Max 10 Images At Once]</code></small></span><br/>
                <span><small><code>[Max 12MB Each Image Size]</code></small></span>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Choose Image(s) :</label>
                  <input type="file" name="images[]" id="Media_imgUpload" multiple="multiple" accept="image/*" required="required">
                  <span id="Media_imgUpload_Info"></span>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <input type="submit" class="btn btn-primary" id="mediaUpload_BTN" value="Upload" style="margin-top: 20px;">
                </div>
              </div>
              <div class="col-md-3">
                <div id="Media_mediaStatus"></div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12"><hr/></div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div id="Media_previewBox"></div>
              </div>
            </div>
          </form>
          </div>
          <div id="Media_imgLibrary" class="tab-pane fade">
            <div class="row" style="margin-top: 5px;">
              <div class="col-md-3"><h4><strong>Image Library</strong></h4></div>
              <div class="col-md-3">
                <select name="Media_img_gal_id" id="Media_img_gal_id" class="form-control select2 lodGals" style="width: 100%;">
                  <option value="0">-Category-</option>
                </select>
              </div>
              <div class="col-md-3">
                <input type="text" id="Media_img_src_txt" name="Media_img_src_txt" class="form-control" placeholder="Search By Name or Alt Tag">
              </div>
              <div class="col-md-3" style="text-align: right;">
                <button class="btn btn-primary btn-sm" id="Media_findImg">Find</button>
                <button class="btn btn-danger btn-sm" id="Media_reloadImgs">Reload</button>
                <button class="btn btn-success btn-sm" id="Media_addSeletImgs">Add Images</button>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12"><hr/></div>
            </div>
            <div class="row">
              <div class="col-md-8">
                <div id="Media_LibraryBox"></div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Title :</label>
                  <input type="text" name="Media_img_title" id="Media_img_title" placeholder="Image Title" class="form-control" readonly="readonly">
                </div>
                <div class="form-group">
                  <label>Alt Tag :</label>
                  <input type="text" name="Media_img_alt" id="Media_img_alt" placeholder="Image Alt Tag" class="form-control" readonly="readonly">
                </div>
                <div class="form-group">
                  <label>Caption :</label>
                  <textarea name="Media_img_caption" id="Media_img_caption" class="form-control" placeholder="Image Caption" readonly="readonly"></textarea>
                </div>
                <div class="form-group">
                  <label>Description :</label>
                  <textarea name="Media_img_desc" id="Media_img_desc" class="form-control" placeholder="Image Description" readonly="readonly"></textarea>
                </div>
                <div class="form-group">
                  <input type="button" id="Media_setTag" value="Save" class="btn btn-success btn-sm arp_btn" disabled="disabled">
                  <input type="hidden" name="Media_sele_img_id" id="Media_sele_img_id">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>