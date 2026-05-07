@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
        All Content Types
        <!--small>it all starts here</small-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">All Content Types</li>
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
      <a href="{{ route('addContTyp') }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add New Content Type</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>

  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
    <div class="box-header with-border">
      <h3 class="box-title">All Content Types</h3>

      <div class="box-tools pull-right">
        
      </div>
    </div>
    <div class="box-body">
      <table class="table table-bordered table-hover table-striped ar-datatable">
        <thead>
          <tr>
            <th>SL</th>
            <th>Action</th>
            <th>Status</th>
            <th>Name</th>
            {{-- <th>Banner</th> --}}
            <th>Contents</th>
            <th>Created</th>
            <th>Modified</th>
          </tr>
        </thead>
        <tbody>
        @if(isset($allTypes))
          @php $sl = 1; @endphp
          @forelse($allTypes as $type)
          <tr>
            <td>{{ $sl }}</td>
            <td>
              <a href="{{ route('edtContTyp', array('id' => $type->id)) }}"><i class="fa fa-pencil-square-o fa-2x base-green"></i></a>
              <a href="{{ route('delContTyp', array('id' => $type->id)) }}" onclick="return confirm('Sure To Delete This Content Type ?');"><i class="fa fa-2x fa-trash-o base-red"></i></a>
              <a href="{{ route('addDynaCont', array('type' => str_slug($type->name), 'type_id' =>$type->id)) }}"><i class="fa fa-2x fa-file-text base-blue"></i></a>
            </td>
            <td>
              @if($type->status == '1')
                <a href="{{ route('acInac') }}?id={{ $type->id }}&val=2&tab=content_type"> 
                  <i class="fa fa-check-circle-o base-green fa-2x" aria-hidden="true"></i> 
                </a>
              @endif
              @if($type->status == '2')
                <a href="{{ route('acInac') }}?id={{ $type->id }}&val=1&tab=content_type"> 
                  <i class="fa fa-ban base-red fa-2x" aria-hidden="true"></i>
                </a> 
              @endif
            </td>
            <td>{{ ucfirst( $type->name ) }}</td>
            {{-- <td>
              @if( isset($type->allImgIds) && count($type->allImgIds) )
                @php $i = 0; @endphp
                @foreach($type->allImgIds as $bimg)
                  @if( isset($bimg->imageInfo) && $i == 0 && $bimg->image_type == 'BANNER_IMAGE' )
                    <img src="{{ asset('public/uploads/files/media_images/thumb/'.$bimg->imageInfo->image) }}" class="img-thumbnail">
                  @php $i++; @endphp
                  @endif
                @endforeach
              @endif
            </td> --}}
            <td>
              {{ count($type->contentIds) }}
            </td>
            <td>{{ date('d-m-Y', strtotime($type->created_at)) }}</td>
            <td>{{ date('d-m-Y', strtotime($type->updated_at)) }}</td>
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