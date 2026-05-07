@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
        All Roles
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

  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
    <div class="box-header with-border">
      <h3 class="box-title">User Roles</h3>

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
            <th style="width: 20px;">SL</th>
            <th>Role Name</th>
            <th>Description</th>
            <th>Created At</th>
            <!--th>Permissions</th-->
          </tr>
        </thead>
        <tbody>
        @if(isset($roles))
          @php $sl = 1; @endphp
          @forelse($roles as $rl)
          <tr>
            <td>{{ $sl }}</td>
            <td>{{ ucfirst( $rl->name ) }}</td>
            <td>{{ $rl->description }}</td>
            <td>{{ date('d-m-Y', strtotime( $rl->created_at )) }}</td>
            <!--td>
              <a href="{{ route('rlMnPer', array('role_id' => $rl->id)) }}"> 
                <i class="fa fa-lock" aria-hidden="true"></i> Manage Permissions</a>
            </td-->
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
      "targets": [ 3 ],
      "orderable": false
    } ]
  });
});
</script>
@endpush