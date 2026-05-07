@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
        All Languages
        <!--small>it all starts here</small-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('langCret') }}">Add Language</a></li>
        <li class="active">Languages</li>
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
      <a href="{{ route('langCret') }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add Language</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>

  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
    <div class="box-header with-border">
      <h3 class="box-title">All Languages</h3>

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
            <th>Action</th>
            <th>Status</th>
            <th>Name</th>
            <th>Flag</th>
            <th>Code</th>
            <th>Created</th>
          </tr>
        </thead>
        <tbody>
        @if(isset($languages))
          @php $sl = 1; @endphp
          @forelse($languages as $lng)
          <tr>
            <td>{{ $sl }}</td>
            <td>
              <a href="{{ route('langEdit', array('id' => $lng->id)) }}"><i class="fa fa-pencil-square-o base-green fa-2x"></i></a>
              @if( strtoupper(trim($lng->code)) != 'EN' )
              <a href="{{ route('langDel', array('id' => $lng->id)) }}" onclick="return confirm('Sure To Delete This Language ?');"><i class="fa fa-trash-o base-red fa-2x"></i></a>
              @endif
            </td>
            <td>{{ ucfirst($lng->name) }} @if($lng->is_default == '1') <span class="base-green">(Default)</span> @endif</td>
            <td>
              @if($lng->flag != '' && $lng->flag != null)
              <img src="{{ asset('public/uploads/flags/thumb/'. $lng->flag) }}">
              @endif
            </td>
            <td>{{ strtoupper($lng->code) }}</td>
            <td>
              @if( strtoupper(trim($lng->code)) != 'EN' )
              @if($lng->status == '1')
                <a href="{{ route('acInac') }}?id={{ $lng->id }}&val=2&tab=languages"> 
                  <i class="fa fa-check-circle-o base-green fa-2x" aria-hidden="true"></i> 
                </a>
              @endif
              @if($lng->status == '2')
                <a href="{{ route('acInac') }}?id={{ $lng->id }}&val=1&tab=languages"> 
                  <i class="fa fa-ban base-red fa-2x" aria-hidden="true"></i>
                </a> 
              @endif
              @endif
            </td>
            <td>{{ date('d-m-Y', strtotime($lng->created_at)) }}</td>
            
          </tr>
          @php $sl++; @endphp
          @empty
          <tr>
            <td colspan="7">No Records Found!</td>
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
      "targets": [  0,1,2 ],
      "orderable": false
    } ]
  });
});
</script>
@endpush