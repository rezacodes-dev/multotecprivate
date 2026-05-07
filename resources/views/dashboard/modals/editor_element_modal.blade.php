<!-- Element Modal -->
<div id="elementModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close arEleClose">&times;</button>
        <h4 class="modal-title">
          <strong><i class="fa fa-random" aria-hidden="true"></i> Elements <span class="addOnEdtName"></span></strong>
        </h4>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs" id="eleMentTabs">
          <li class="active"><a data-toggle="tab" href="#reUseCont">Reusable Content</a></li>
          <li><a data-toggle="tab" href="#frmBuilder">Form Builder</a></li>
          <li><a data-toggle="tab" href="#imageGalleries">Image Galleries</a></li>
          <li><a data-toggle="tab" href="#proDucts">Products</a></li>
          <li><a data-toggle="tab" href="#neWs">News</a></li>
          <li><a data-toggle="tab" href="#serVice">Services</a></li>
        </ul>
        <div class="tab-content">
          <div id="reUseCont" class="tab-pane fade in active">
            <div class="row">
              <div class="col-md-12">
                <h3>Select Reusable Content & Attached To Editor</h3>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div id="reusableContentDropLoad">
                  <i class="fa fa-cog fa-spin fa-3x fa-fw"></i>
                  <span>Please wait.... Getting All Reusable Contents....</span>
                </div>
              </div>
            </div>
          </div>
          <div id="frmBuilder" class="tab-pane fade">
            <div class="row">
              <div class="col-md-12">
                <h3>Select Form Builders Form & Attached To Editor</h3>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div id="formBuilderDropLoad">
                  <i class="fa fa-cog fa-spin fa-3x fa-fw"></i>
                  <span>Please wait.... Getting All Form Builders....</span>
                </div>
              </div>
            </div>
          </div>
          <div id="imageGalleries" class="tab-pane fade">
            <div class="row">
              <div class="col-md-12">
                <h3>Select Image Gallery & Attached To Editor</h3>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div id="imageGalleriesDropLoad">
                  <i class="fa fa-cog fa-spin fa-3x fa-fw"></i>
                  <span>Please wait.... Getting All Gallery List....</span>
                </div>
              </div>
            </div>
          </div>
          <div id="proDucts" class="tab-pane fade">
            <h3>Do You Want To Add Product ? <small>Checked Below Checkbox</small></h3>
            <p>
              <input type="checkbox" class="eleModalCkb" value="[#PRODUCTS_latest#]">
              ( click here to add latest products display short code )
            </p>
          </div>
          <div id="neWs" class="tab-pane fade">
            <h3>Do You Want To Add News ? <small>Checked Below Checkbox</small></h3>
            <p>
              <input type="checkbox" class="eleModalCkb" value="[#NEWS_latest#]">
              ( click here to add latest news display short code )
            </p>
          </div>
          <div id="serVice" class="tab-pane fade">
            <h3>Do You Want To Add Service ? <small>Checked Below Checkbox</small></h3>
            <p>
              <input type="checkbox" class="eleModalCkb" value="[#SERVICE_latest#]">
              ( click here to add service display short code )
            </p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary addCkEdtBtnEle" disabled="disabled">Add To Editor</button>
        <button type="button" class="btn btn-danger arEleClose">Close</button>
        <input type="hidden" id="eleScodeHidden" value="">
      </div>
    </div>

  </div>
</div>