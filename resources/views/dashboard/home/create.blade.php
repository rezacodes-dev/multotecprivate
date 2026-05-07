@extends('dashboard.layouts.app')

@push('page_css')
<link rel="stylesheet" href="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.css') }}">
@endpush


@section('content_header')
<section class="content-header">
  <h1>
    Home Page Content
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
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
      
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Manage Home Page Content</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name="jfrm" id="frmx" action="{{ route('home.contAct') }}" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}

          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Default Language :</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <span>
                    @if( isset($languages) && !empty($languages) )
                      @foreach( $languages as $lng )
                        @if( $lng->is_default == '1' )
                          <img src="{{ asset('public/uploads/flags/thumb/'. $lng->flag) }}" style="height: 20px;" id="flag">
                        @endif
                      @endforeach
                    @endif
                    </span>
                  </div>
                  <select name="language_id" id="language_id" class="form-control">
                  @if( isset($languages) && !empty($languages) )
                    @foreach( $languages as $lng )
                      @if( $lng->is_default == '1' )
                        <option value="{{ $lng->id }}">{{ $lng->name }}</option>
                      @endif
                    @endforeach
                  @endif
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-9" style="text-align: right;">
              @if( isset($home) && !empty($home) )
                @if( isset($home->ChildLanguages) && count($home->ChildLanguages) < count($languages) - 1 )
                <a href="{{ route('home.adedlng', array('pid' => $home->id)) }}"><i class="fa fa-plus"></i> Add Language ?</a>
                @endif
                @if( isset($home->ChildLanguages) )
                  @foreach( $home->ChildLanguages as $chl )
                    @if( isset($chl->Language) )
                      @php $Lng = $chl->Language; @endphp
                      <a href="{{ route('home.adedlng', array('pid' => $home->id, 'cid' => $chl->id)) }}" class="lngLink btn btn-xs">
                        <img src="{{ asset('public/uploads/flags/thumb/'. $Lng->flag) }}"> {{ $Lng->name }}
                      </a>
                    @endif
                  @endforeach
                @endif
              @endif
            </div>
          </div>


          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Home Page Heading (H1): <em>*</em></label>
                <input type="text" name="name" class="form-control" placeholder="Enter home page heading" value="@if( isset($home) ){{ $home->name }}@endif">
              </div>
              <div class="form-group">
                <label>URL/Slug: <em>*</em></label>
                <input type="text" name="slug" class="form-control" value="{{ url('/en') }}" disabled="disabled">
              </div>
            </div>
          </div>



          <!------------------------------------------------------------------------------------------------------->
          <!-- META INFO -->
          <div class="row">
            <div class="col-md-10">
              <h3>Page Meta Information</h3>
              <hr/>
            </div>
            <div class="col-md-10">
              <div class="form-group">
                <label>Meta Title:</label>
                <input type="text" name="meta_title" class="form-control" placeholder="Meta Title" value="@if( isset($home) ){{ $home->meta_title }}@endif">
              </div>
              <div class="form-group">
                <label>Meta Keywords:</label>
                <input type="text" name="meta_keyword" class="form-control" placeholder="Meta Keywords" value="@if( isset($home) ){{ $home->meta_keyword }}@endif">
              </div>
              <div class="form-group">
                <label>Meta Description:</label>
                <textarea name="meta_desc" class="form-control" placeholder="Meta Description">@if( isset($home) ){{ $home->meta_desc }}@endif</textarea>
              </div>
              <div class="form-group">
                <label>Canonical Url:</label>
                <input type="text" name="canonical_url" class="form-control" placeholder="Any Canonical url" value="@if( isset($home) ){{ $home->canonical_url }}@endif">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Language Tag:</label>
                <input type="text" name="lng_tag" class="form-control" placeholder="Language Tag" value="@if( isset($home) ){{ $home->lng_tag }}@endif">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Add No Follow Tag :</label>
                <select name="follow" class="form-control">
                  <option value="1" @if(isset($home) && $home->follow == '1') selected="selected" @endif>FOLLOW</option>
                  <option value="0" @if(isset($home) && $home->follow == '0') selected="selected" @endif>NO FOLLOW</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Add No Index Tag :</label>
                <select name="index_tag" class="form-control">
                  <option value="1" @if(isset($home) && $home->index_tag == '1') selected="selected" @endif>INDEX</option>
                  <option value="0" @if(isset($home) && $home->index_tag == '0') selected="selected" @endif>NO INDEX</option>
                </select>
              </div>
            </div>
            <div class="col-md-10">
              <div class="form-group">
                <label> Add Structured data mark-up (Json-LD) :</label>
                <textarea name="json_markup" class="form-control" rows="6">@if( isset($home) ){!! html_entity_decode($home->json_markup, ENT_QUOTES) !!}@endif</textarea>
              </div>
            </div>
          </div>
          <!-- END META INFO -->
          <!------------------------------------------------------------------------------------------------------->

          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Desktop Content : (Above the fold content)</label>
                <textarea name="page_content" class="form-control" id="pg_cont">@if( isset($home) ){{ html_entity_decode($home->page_content, ENT_QUOTES) }}@endif</textarea>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Read More Content :</label>
                <textarea name="readmore_content" class="form-control" id="readmore_content">@if( isset($home) ){{ html_entity_decode($home->readmore_content, ENT_QUOTES) }}@endif</textarea>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Add Reusable Content 1 : (Copy & Paste Reusable Short code)</label>
                <textarea name="reuse_content1" class="form-control" id="reuse_content1" rows="4">@if( isset($home) ){{ html_entity_decode($home->reuse_content1, ENT_QUOTES) }}@endif</textarea>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Add Reusable Content 2 : (Copy & Paste Reusable Short code)</label>
                <textarea name="reuse_content2" class="form-control" id="reuse_content2" rows="4">@if( isset($home) ){{ html_entity_decode($home->reuse_content2, ENT_QUOTES) }}@endif</textarea>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label>News Heading</label>
                <input type="text" name="news_heading" class="form-control" placeholder="Enter News Heading" value="@if( isset($home) ){{ $home->news_heading }}@endif">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>News No</label>
                <input type="text" name="news_no" class="form-control onlyNumber" style="width: 100px;" value="@if( isset($home) ){{ $home->news_no }}@endif">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Mineral Processing Heading</label>
                <input type="text" name="mineral_processing_heading" class="form-control" placeholder="Enter Mineral Processing Heading" value="@if( isset($home) ){{ $home->mineral_processing_heading }}@endif">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Minerals Heading</label>
                <input type="text" name="mineral_heading" class="form-control" placeholder="Enter Minerals Heading" value="@if( isset($home) ){{ $home->mineral_heading }}@endif">
              </div>
            </div>
          </div>
          
          <div class="row" style="margin-top: 20px;">
            <div class="col-md-6">
              @if( isset($home) )
              <input type="submit" class="btn btn-primary" value="Save Changes">
              <input type="hidden" id="table_id" value="{{ $home->id }}">
              <input type="hidden" name="insert_id" id="insert_id" value="{{ $home->insert_id }}">
              @else
              <input type="submit" class="btn btn-primary" value="Save All">
              <!--a href="{{ route('addProd') }}" class="btn btn-danger">Cancel</a-->
              <input type="hidden" id="table_id" value="0">
              <input type="hidden" name="insert_id" id="insert_id" value="{{ $insert_id }}">
              @endif
            </div>
            <div class="col-md-6"></div>
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

@endsection

@push('page_js')
<script src="{{ asset('public/assets/jquery_ui/jquery-ui.js') }}"></script>
<script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.js') }}"></script>


<script type="text/javascript">
var editor_pg_cont = CKEDITOR.replace( 'pg_cont', {
  customConfig: "{{ asset('public/assets/ckeditor/maxi_config.js') }}",
} );

var editor_rm_cont = CKEDITOR.replace( 'readmore_content', {
  customConfig: "{{ asset('public/assets/ckeditor/maxi_config.js') }}",
} );

var fm = $('#frmx');
fm.validate({
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
    },
    language_id: {
      required: true
    }
  },
  messages: {

    name: {
      required: 'Please Enter Home Page Heading.'
    },
    language_id: {
      required: 'Please Select Language.'
    }
  },
  errorPlacement: function(error, element) {
    element.parent('.form-group').addClass('has-error');
    if (element.attr("data-error-container")) { 
      error.appendTo(element.attr("data-error-container"));
    } else if(element.attr('id') == 'language_id') {
      error.insertAfter(element.parent('div'));
    } else {
      error.insertAfter(element); 
    }
  },
  success: function(label) {
    label.closest('.form-group').removeClass('has-error');
  }
});
</script>

@endpush