@extends('dashboard.layouts.app')


@section('content_header')
<section class="content-header">
  <h1>
    @if(isset($content_type))
    Edit Content Type
    @else
    Add New Content Type
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('allContTyps') }}">All Content Types</a></li>
  </ol>
</section>
@endsection

@section('content')
<section class="content">

  @if(Session::has('msg'))
  <div class="ar-hide @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}</div>
  @endif

  <div class="row">
    <div class="col-md-6">
      <a href="{{ route('allContTyps') }}" class="btn btn-primary"> All Content Types</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">@if(isset($content_type)) Edit Content Type @else Add Content Type @endif</h3>

          <div class="box-tools pull-right">
            
          </div>
        </div>
        <div class="box-body">
          <form name="frm" id="frmx" action="@if( isset($content_type) ){{ route('updContTyp', array('id' => $content_type->id)) }}@else{{ route('sveContTyp') }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label>Content Type Name : <em>*</em></label>
                <input type="text" name="name" class="form-control" placeholder="Enter Content Type Name" value="@if( isset($content_type) ){{ $content_type->name }}@endif">
                @if($errors->has('name'))
                <span class="roy-vali-error"><small>{{$errors->first('name')}}</small></span>
                @endif
              </div>
              <div class="form-group">
                <label>Description : </label>
                <textarea name="description" class="form-control" style="height: 100px;" placeholder="Enter Content Type Description">@if( isset($content_type) ){{ $content_type->description }}@endif</textarea>
              </div>
              <div class="form-group">
                <label>Status :</label>
                <input type="radio" name="status" value="1" @if( isset($content_type) ) @if( $content_type->status == '1' ) checked="checked" @endif @else checked="checked" @endif> Active
                <input type="radio" name="status" value="2" @if( isset($content_type) && $content_type->status == '2' ) checked="checked" @endif> Inactive
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    
                    <!-- CALL SCRIPT -->
                    <button type="button" class="btn btn-default addMedImgBtn" title="Add Page Banner Image" data="bannerImgIds_Box"><i class="fa fa-picture-o" aria-hidden="true"></i> Add Page Banner Image</button>
                    
                    <input type="hidden" id="bannerImgIds_Box-idholder" name="banner_image_ids">

                    <input type="hidden" id="bannerImgIds_Box-infoholder" name="banner_image_infos">
                    
                    <div id="bannerImgIds_Box-dispDiv"></div>
                    <!-- END -->

                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                @if( isset($content_type) && isset($content_type->allImgIds) && count($content_type->allImgIds) > 0 )
                  @php $i = 0; @endphp
                  @foreach( $content_type->allImgIds as $imgs )
                  @if( $imgs->image_type == 'BANNER_IMAGE' )
                    @if( isset($imgs->imageInfo) && $i == 0 )
                    <div class="col-md-3">
                    <div class="thumbnail">
                      <img src="{{ asset('public/uploads/files/media_images/thumb/'.$imgs->imageInfo->image) }}">
                      <div class="caption">{{ sizeFilter($imgs->imageInfo->size) }}</div>
                      <a href="javascript:void(0);" class="idel ifdel" data="content_type_images_map" id="{{ $imgs->id }}">
                        <i class="fa fa-times base-red" aria-hidden="true"></i>
                      </a>
                    </div>
                    </div>
                    @php $i++; @endphp
                    @endif
                  @endif
                  @endforeach
                @endif
                </div>
              </div>


              <div class="form-group">
                @if( isset($content_type) )
                <input type="submit" class="btn btn-primary" value="Save Changes">
                @else
                <input type="submit" class="btn btn-primary" value="Add Content Type">
                @endif
              </div>
            </div>
          </div>
          </form>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->
    </div>
  </div>

</section>

@include('dashboard.modals.editor_imgmedia_modal')


@endsection

@push('page_js')
<script type="text/javascript">
$('#frmx').validate({
  errorElement: 'span',
  errorClass : 'roy-vali-error',
  ignore: [],
  normalizer: function( value ) {
    return $.trim( value );
  },
  rules: {

    name: {
      required: true,
      minlength: 3
    }
  },
  messages: {

    name: {
      required: 'Please Enter Content Type Name.'
    }
  },
  errorPlacement: function(error, element) {
    element.parent('.form-group').addClass('has-error');
    if (element.attr("data-error-container")) { 
      error.appendTo(element.attr("data-error-container"));
    } else {
      error.insertAfter(element); 
    }
  },
  success: function(label) {
    label.closest('.form-group').removeClass('has-error');
  }
});
</script>

@include('dashboard.modals.editor_imgmedia_modal_script')

@endpush