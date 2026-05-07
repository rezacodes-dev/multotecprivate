@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
  <h1>
    @if(isset($category))
    Edit Category
    @else
    Add Category
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('frms') }}">All Forms</a></li>
    @if(isset($category))
    <li class="active">Edit Category</li>
    @else
    <li class="active">Add Category</li>
    @endif
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
      <a href="{{ route('frmCats') }}" class="btn btn-primary"> All Categories</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">@if(isset($category)) Edit Category @else Add Category @endif</h3>

          <div class="box-tools pull-right">
            
          </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <form name="frm" action="@if( isset($category) ){{ route('frmCats_upd', array('id' => $category->id)) }}@else{{ route('frmCats_sve') }}@endif" id="frm_frmx" method="post">
              {{ csrf_field() }}
              <div class="row">
                <div class="col-md-8">
                  <div class="form-group">
                    <label>Form Type or Category Name : <em>*</em></label>
                    <input type="text" name="category_name" class="form-control" placeholder="Enter Category Name" value="@if( isset($category) ){{ $category->category_name }}@endif">
                  </div>
                  <div class="form-group">
                    <label>Status : </label>
                    <input type="radio" name="status" value="1" @if(isset($category)) @if($category->status == '1') checked="checked" @endif @else checked="checked" @endif> Active
                    <input type="radio" name="status" value="2" @if(isset($category) && $category->status == '2') checked="checked" @endif> Inactive
                  </div>
                  <div class="form-group">
                    @if( isset($category) )
                    <input type="submit" class="btn btn-primary" value="Save Changes">
                    @else
                    <input type="submit" class="btn btn-primary" value="Add Category">
                    @endif
                  </div>
                </div>
              </div>
              </form>
            </div>
            <div class="col-md-6">
             
            </div>
          </div>
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
$("#frm_frmx").validate({
  errorElement: 'span',
  errorClass : 'ar-vali-error',
  rules: {

    category_name: {
      required: true,
    }
  },
  messages: {

    category_name: {
      required: 'Please Enter Form Category Name.',
    }
  }
  
});

</script>
@endpush