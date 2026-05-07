<!-- Modal -->
<div id="videoModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="eleModalClose btn btn-xs btn-danger pull-right" data-dismiss="modal">Exit</button>
        <h4 class="modal-title">Videos</h4>
      </div>
      <div class="modal-body modal_min_height">
        <ul class="nav nav-tabs" id="vidTabs">
          <li class="active"><a data-toggle="tab" href="#vidUpload"><i class="fa fa-upload" aria-hidden="true"></i> Add New Video</a></li>
          <li><a data-toggle="tab" href="#vidLibrary"><i class="fa fa-picture-o" aria-hidden="true"></i> All Videos</a></li>
          <li><a data-toggle="tab" href="#vidSelected" style="display: none;"> <i class="fa fa-check-square-o" aria-hidden="true"></i>
          Selected Videos</a></li>
        </ul>
        <div class="tab-content">
          <div id="vidUpload" class="tab-pane fade in active" style="margin-top: 10px;">
            <form name="vidAddFrm" id="vidAddFrm" action="{{ route('ajxMediaVidAdd') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="row">
              <div class="col-md-8">
                <div class="form-group">
                  <label>Video Name : <em>*</em></label>
                  <input type="text" name="name" class="form-control" placeholder="Video Name">
                </div>
                <div class="form-group">
                  <label>Video Title : <em>*</em></label>
                  <input type="text" name="title" class="form-control" placeholder="Video Title">
                </div>
                <div class="form-group">
                  <label>Video Type : <em>*</em></label>
                  <select name="video_type" class="form-control">
                    <option value="1">Youtebe Link</option>
                    <option value="2">Embedded Script</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>Youtube Link : <em>*</em></label>
                  <textarea name="link_script" class="form-control" placeholder="Enter Video"></textarea>
                </div>
                <div class="form-group">
                  <label>Video Caption :</label>
                  <textarea name="video_caption" class="form-control" placeholder="Enter Video Caption"></textarea>
                </div>
                <div class="form-group">
                  <input type="submit" class="btn btn-primary" value="Add Video" id="vidAddBtn">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group" style="text-align: center; margin-top: 60px;">
                  <div id="addvidStatus"></div>
                </div>
              </div>
            </div>
            </form>
          </div>
          <div id="vidLibrary" class="tab-pane fade">
            <div class="row" style="margin-top: 10px;">
              <div class="col-md-4">
                <select name="device" class="form-control" id="vidDevice">
                  <option value="3">For Both</option>
                  <option value="1">For Desktop</option>
                  <option value="2">For Mobile</option>
                </select>
              </div>
              <div class="col-md-5">
                <input type="text" id="vid_src_txt" class="form-control" placeholder="Search By Name or Caption or Title">
              </div>
              <div class="col-md-3" style="text-align: right;">
                <button class="btn btn-primary btn-sm" id="findVids">Find</button>
                <button class="btn btn-danger btn-sm" id="reloadVids">Reload</button>
                <button class="btn btn-success btn-sm" id="addSeletVids" disabled="disabled">Add Videos</button>
                @if( isset($pageBuilderData) && !empty($pageBuilderData) )
                <input type="hidden" name="insert_id" id="VIDEO_insert_id" value="{{ $pageBuilderData->insert_id }}">
                @else
                <input type="hidden" name="insert_id" id="VIDEO_insert_id" value="@if( isset( $insert_id ) ){{ $insert_id }}@endif">
                @endif
                <input type="hidden" class="this_id" name="this_id" id="VIDEO_this_id" value="0">
                <input type="hidden" name="builder_type" id="vid_builder_type">
              </div>
              <div class="col-md-12"><hr/></div>
              <div class="col-md-9">
                <div id="VidLibContainer"></div>
              </div>
              <div class="col-md-3">
                <div class="ar-form-group">
                  <!--label>ID :</label-->
                  <input type="text" id="VID_INFO_ID" placeholder="MEDIA-VIDEO" class="form-control" readonly="readonly">
                </div>
                <div class="ar-form-group">
                  <label>Name :</label>
                  <input type="text" name="vid_name" id="vid_name" placeholder="Name" class="form-control" readonly="readonly">
                </div>
                <div class="ar-form-group">
                  <label>Title :</label>
                  <input type="text" name="vid_title" id="vid_title" placeholder="Title" class="form-control" readonly="readonly">
                </div>
                <div class="ar-form-group">
                  <label>Caption :</label>
                  <textarea name="vid_caption" id="vid_caption" class="form-control" placeholder="Caption" readonly="readonly"></textarea>
                </div>
                <div class="ar-form-group" style="margin-top: 6px;">
                  <input type="button" id="setVidTag" value="Save All Information" class="btn btn-success btn-sm arp_btn" disabled="disabled">
                  <input type="hidden" name="sele_vid_id" id="sele_vid_id">
                  <input type="hidden" id="setVidInfo_Action" value="SET">
                </div>
              </div>
            </div>
          </div>
          <div id="vidSelected" class="tab-pane fade">
          </div>
        </div>
      </div>
      <!--div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div-->
    </div>

  </div>
</div>