<!-- Media Modal -->
<div class="modal fade" id="imgCarModal" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="eleModalClose btn btn-xs btn-danger pull-right" data-dismiss="modal">Exit</button>
        <h4 class="modal-title">
          <strong><i class="fa fa-random" aria-hidden="true"></i> Page Builder Image Master</strong>
        </h4>
      </div>
      <div class="modal-body modal_min_height">
        <input type="hidden" id="modalEleSetEdtId" value="NOT-USE"> 
        <ul class="nav nav-tabs" id="eleTabs">
          <li class="active"><a data-toggle="tab" href="#imgUpload"><i class="fa fa-upload" aria-hidden="true"></i> Upload</a></li>
          <li><a data-toggle="tab" href="#imgLibrary"><i class="fa fa-picture-o" aria-hidden="true"></i> Images</a></li>
          <!--li><a data-toggle="tab" href="#imgGalleries"><i class="fa fa-folder" aria-hidden="true"></i> Image Galleries</a></li-->
          <li><a data-toggle="tab" href="#carSeleImgs" style="display: none;"> <i class="fa fa-check-square-o" aria-hidden="true"></i>
          Current Images</a></li>
        </ul>
        <div class="tab-content">
          <div id="imgUpload" class="tab-pane fade in active">
            <form name="mediaUpload" id="mediaUpload" action="{{ route('ajxMediaImgUpload') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row" style="margin-top: 30px;">
              <div class="col-md-3">
                <span><small><code>[Max 10 Images At Once]</code></small></span><br/>
                <span><small><code>[Max 2MB Each Image Size]</code></small></span>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Choose Image(s) :</label>
                  <input type="file" name="images[]" id="imgUpload" multiple="multiple" accept="image/*" required="required">
                  <span id="imgUpload_Info"></span>
                </div>
              </div>
              <div class="col-md-2">
                <div class="form-group">
                  <input type="submit" class="btn btn-primary" id="mediaUpload_BTN" value="Upload" style="margin-top: 20px;">
                </div>
              </div>
              <div class="col-md-3">
                <div id="mediaStatus"></div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12"><hr/></div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div id="previewBox"></div>
              </div>
            </div>
          </form>
          </div>
          <div id="imgLibrary" class="tab-pane fade">
            <div class="row" style="margin-top: 5px;">
              <div class="col-md-3">
                <select name="device" class="form-control" id="imgDevice">
                  <option value="3">For Both</option>
                  <option value="1">For Desktop</option>
                  <option value="2">For Mobile</option>
                </select>
              </div>
              <div class="col-md-3">
                <select name="img_gal_id" id="img_gal_id" class="form-control select2 lodGals" style="width: 100%;">
                  <option value="0">-Galleries-</option>
                </select>
              </div>
              <div class="col-md-3">
                <input type="text" id="img_src_txt" name="img_src_txt" class="form-control" placeholder="Search By Name or Alt Tag">
              </div>
              <div class="col-md-3" style="text-align: right;">
                <button class="btn btn-primary btn-sm" id="findImg">Find</button>
                <button class="btn btn-danger btn-sm" id="reloadImgs">Reload</button>
                <button class="btn btn-success btn-sm" id="addSeletImgs">Add Images</button>
                @if( isset($pageBuilderData) && !empty($pageBuilderData) )
                <input type="hidden" name="insert_id" id="IMAGE_CAROUSEL_insert_id" value="{{ $pageBuilderData->insert_id }}">
                @else
                <input type="hidden" name="insert_id" id="IMAGE_CAROUSEL_insert_id" value="@if( isset( $insert_id ) ){{ $insert_id }}@endif">
                @endif
                <input type="hidden" class="this_id" name="this_id" id="IMAGE_CAROUSEL_this_id" value="0">
                <input type="hidden" name="builder_type" id="img_builder_type">
              </div>
            </div>
            <div class="row">
              <div class="col-md-12"><hr/></div>
            </div>
            <div class="row">
              <div class="col-md-8">
                <div id="LibraryBox"></div>
              </div>
              <div class="col-md-4">
                <div class="ar-form-group">
                  <!--label>ID :</label-->
                  <input type="text" id="IMG_ID" placeholder="Click on Image" class="form-control" readonly="readonly">
                </div>
                <div class="ar-form-group">
                  <label>Title :</label>
                  <input type="text" name="img_title" id="img_title" placeholder="Image Title" class="form-control" readonly="readonly">
                </div>
                <div class="ar-form-group">
                  <label>Alt Tag :</label>
                  <input type="text" name="img_alt" id="img_alt" placeholder="Image Alt Tag" class="form-control" readonly="readonly">
                </div>
                <div class="ar-form-group">
                  <label>Caption :</label>
                  <textarea name="img_caption" id="img_caption" class="form-control" placeholder="Image Caption" readonly="readonly"></textarea>
                </div>
                <div class="ar-form-group">
                  <label>Description :</label>
                  <textarea name="img_desc" id="img_desc" class="form-control" placeholder="Image Description" readonly="readonly"></textarea>
                </div>
                <div class="ar-form-group" style="margin-top: 6px;">
                  <input type="button" id="setTag" value="Save All Tags" class="btn btn-success btn-sm arp_btn" disabled="disabled">
                  <input type="hidden" name="sele_img_id" id="sele_img_id">
                  <input type="hidden" id="setImageInfo_Action" value="SET">
                </div>
              </div>
            </div>
          </div>
          <!--div id="imgGalleries" class="tab-pane fade">
            <div class="row" style="margin-top: 5px;">
              <div class="col-md-6">
                <h3>Select Image Gallery :</h3>
                <select id="onlyImgGal" class="form-control select2" style="width: 100%;">
                  <option value="0">-Image Galleries-</option>
                </select>
              </div>
              <div class="col-md-3">
                <input type="button" id="addImgGalToEdtBtn" class="btn btn-primary" value="Add To Editor" disabled="disabled" style="margin-top: 55px;">
              </div>
            </div>
          </div-->
          <div id="carSeleImgs" class="tab-pane fade">
            <div class="row" style="margin-top: 5px;">
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>