@extends('dashboard.layouts.app')



@section('content_header')
<section class="content-header">
  <h1>
    @if(isset($land))
    Edit Landing Page
    @else
    Add Landing Page
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('land.list') }}">All Landing Pages</a></li>
    
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
      <a href="{{ route('land.list') }}" class="btn btn-primary"> All Landing Pages</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">@if(isset($land)) Edit Landing Page @else Add Landing Page @endif</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name="frm" id="frmx" action="@if(isset($land)){{ route('land.update', array('id' => $land->id)) }}@else{{ route('land.new.upd') }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}
          
          <div class="row">
            <div class="col-md-10">
              <div class="form-group">
                <label>Name : <em>*</em></label>
                <input type="text" name="name" class="form-control" placeholder="Enter Name of Landing Page" value="@if( isset($land) ){{ $land->name }}@endif">
              </div>
              <div class="form-group">
                <label>Set URL/Link : <em>*</em></label>
                <input type="text" name="slug" class="form-control" placeholder="Enter Page URL or LINK" @if( isset($land) ) readonly="readonly" @endif value="@if( isset($land) ){{ $land->slug }}@endif">
              </div>
              <div class="form-group">
                <label>Upload zip :</label>
                <input type="file" name="zip" @if( !isset($land) ) required="required" @endif>
                <span><small><strong>NOTE :</strong> Zip file should contain a index.html file</small></span>
              </div>
              <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Upload and Save">
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
@endsection

@push('page_js')
<script type="text/javascript">
$('#frmx').validate({
  errorElement: 'span',
  errorClass : 'roy-vali-error',
  ignore: [],
  rules: {

    name: {
      required: true
    },
    slug: {
      required: true,
      nowhitespace: true,
      pattern: /^[A-Za-z\d-.]+$/,
    },
    zip: {
      extension: 'zip'
    }
  },
  messages: {

    name: {
      required: 'Please Enter Name.'
    },
    slug: {
      required: 'Please Enter Page URL or Link.',
      nowhitespace: 'White Space or Blank Space Not Allowed, Use Hyphen.',
      pattern: 'Any Special Character Not Allowed, Except Hyphen.',
      remote: 'This URL Already Exist, Try Another.'
    },
    zip: {
      required: 'Please upload zip.',
      extension: 'Please select zip file only.'
    }
  },
  success: function(label) {
    console.log(label);
  }
});
</script>
@endpush