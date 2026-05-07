@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
        All Image Galleries
        <!--small>it all starts here</small-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">All Image Galleries</li>
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
      <a href="{{ route('media_img_gals_crte') }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Create New Gallery</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>

  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
    <div class="box-header with-border">
      <h3 class="box-title">All Image Galleries</h3>

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
            <th>Gallery Name</th>
            <th>Short Code</th>
            <th>Images</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        @if(isset($galleries))
          @php $sl = 1; @endphp
          @forelse($galleries as $gl)
          <tr>
            <td>{{ $sl }}</td>
            <td>{{ $gl->name }}</td>
            <td>{{ $gl->short_code }}</td>
            <td>
              @if( $gl->gallery_source == '2' ) 
                @if( isset($gl->Image_Count) ){{ count($gl->Image_Count) }}@endif
              @endif
              @if( $gl->gallery_source == '1' )
                @if( isset($gl->GroupInfo) )
                  <a href="{{ route('media_img_cats_edt', array('id' => $gl->image_category_id)) }}" data-toggle="tooltip" data-placement="bottom" title="{{ $gl->GroupInfo->name }}">[Group]</a>
                @endif
              @endif
            </td>
            <td>
              @if($gl->status == '1') <span class="base-green">Active</span> @endif
              @if($gl->status == '2') <span class="base-red">Inctive</span> @endif
            </td>
            <td>
              <a href="{{ route('media_img_gals_edit', array('id' => $gl->id)) }}" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="fa fa-pencil-square-o base-green"></i></a>
              <a href="{{ route('media_img_gals_del', array('id' => $gl->id)) }}" data-toggle="tooltip" data-placement="bottom" title="Delete" onclick="return confirm('Sure To Delete This Gallery ?');"><i class="fa fa-trash-o base-red"></i></a>
              <a href="{{ route('media_img_gals_addImg', array('id' => $gl->id)) }}" data-toggle="tooltip" data-placement="bottom" title="Manage Images"><i class="fa fa-picture-o" aria-hidden="true"></i></a>
            </td>
          </tr>
          @php $sl++; @endphp
          @empty
          <tr>
            <td colspan="6">No Records Found!</td>
          </tr>
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
      "targets": [  2, 5 ],
      "orderable": false
    } ]
  });
});
</script>
@endpush