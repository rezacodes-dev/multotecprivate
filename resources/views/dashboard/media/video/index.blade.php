@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
        All Videos
        <!--small>it all starts here</small-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('addVideo') }}">Add New Video</a></li>
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
      <a href="{{ route('addVideo') }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add New Video</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>

  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
    <div class="box-header with-border">
      <h3 class="box-title">All Videos</h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                title="Collapse">
          <i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
          <i class="fa fa-times"></i></button>
      </div>
    </div>
    <div class="box-body">
      <table class="table table-bordered table-hover table-striped ar-datatable">
        <thead>
          <tr>
            <th>SL</th>
            <th style="width: 100px;">Action</th>
            <th>Status</th>
            <th>Name</th>
            <th>Category</th>
            <th>Subcategory</th>
          </tr>
        </thead>
        <tbody>
        @if(isset($allVideos))
          @php $sl = 1; @endphp
          @forelse($allVideos as $vd)
          <tr>
            <td>{{ $sl }}</td>
            <td>
              <a href="{{ route('editVideo', array('id' => $vd->id)) }}"><i class="fa fa-pencil-square-o base-green fa-2x"></i></a>
              <a href="{{ route('delVideo', array('id' => $vd->id)) }}" onclick="return confirm('Sure To Delete This Video ?');"><i class="fa fa-2x fa-trash-o base-red"></i></a>
            </td>
            <td>
              @if($vd->status == '1')
                <a href="{{ route('acInac') }}?id={{ $vd->id }}&val=2&tab=videos"> 
                  <i class="fa fa-check-circle-o base-green fa-2x" aria-hidden="true"></i> 
                </a>
              @endif
              @if($vd->status == '2')
                <a href="{{ route('acInac') }}?id={{ $vd->id }}&val=1&tab=videos"> 
                  <i class="fa fa-ban base-red fa-2x" aria-hidden="true"></i>
                </a> 
              @endif
            </td>
            <td>{{ $vd->name }}</td>
            <td>
              @if( isset($vd->getCatSubcat) && isset($vd->getCatSubcat->categoryInfo) )
              {{ $vd->getCatSubcat->categoryInfo->name}}
              @endif
            </td>
            <td>
              @if( isset($vd->getCatSubcat) && isset($vd->getCatSubcat->subcategoryInfo) )
              {{ $vd->getCatSubcat->subcategoryInfo->name}}
              @endif
            </td>
          </tr>
          @php $sl++; @endphp
          @empty
          @endforelse
        @endif
        </tbody>
      </table>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
      
    </div>
    <!-- /.box-footer-->
  </div>
  <!-- /.box -->

    </section>
@endsection

@push('page_js')
<script type="text/javascript">
$(function() {
  $('.ar-datatable').DataTable({
    "columnDefs": [ {
      "targets": [ 0,1,2 ],
      "orderable": false
    } ]
  });
});
</script>
@endpush