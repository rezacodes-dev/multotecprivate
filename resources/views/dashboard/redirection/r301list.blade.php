@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
        301 Redirect Lists
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
      <a href="{{ route('r301.add') }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Create New</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>

  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
    <div class="box-header with-border">
      <h3 class="box-title">All 301 Redirection Pages</h3>

      <div class="box-tools pull-right">
        
      </div>
    </div>
    <div class="box-body">
      <table class="table table-bordered table-hover table-striped">
        <thead>
          <tr>
            <th>SL</th>
            <th style="width: 138px;">Action</th>
            <th>Source URL</th>
            <th>Destination URL</th>
            <th style="width: 100px;">Created</th>
            <th style="width: 100px;">Updated</th>
          </tr>
        </thead>
        <tbody>
        @if(isset($list))
          @php $sl = 1; @endphp
          @forelse($list as $lst)
          <tr>
            <td>{{ $sl }}</td>
            <td>
              <a href="{{ route('r301.edit', array('id' => $lst->id)) }}"><i class="fa fa-pencil-square-o base-green fa-2x"></i></a>
              
              <a href="{{ route('r301.del', array('id' => $lst->id)) }}" onclick="return confirm('Sure To Delete This URL ?');"><i class="fa fa-trash-o base-red fa-2x"></i></a>
              <a href="{{ $lst->destination_url }}" target="_blank">
              <i class="fa fa-eye fa-2x" aria-hidden="true"></i></a>
              
            </td>
            <td>{{ $lst->source_url }}</td>
            <td>{{ $lst->destination_url }}</td>
            <td>{{ date('d-m-Y', strtotime($lst->created_at)) }}</td>
            <td>{{ date('d-m-Y', strtotime($lst->created_at)) }}</td>
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
      {{ $list->links() }}
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
      "targets": [ 5 ],
      "orderable": false
    } ]
  });
});
</script>
@endpush