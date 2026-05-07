@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
        All Email Templates
        <!--small>it all starts here</small-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Email Template Lists</li>
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
      <a href="{{ route('add_empTemp') }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Create Email Template</a>
      <a href="{{ route('emp_sett') }}" class="btn btn-warning"> Email Settings</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  
  @if( isset($isActiveSetting) && $isActiveSetting == false)
  <div class="notice notice-warning" style="margin-top: 10px;">
      <strong>Notice :</strong> Please Setup Your Email Settings First.
  </div>
  @endif
  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
    <div class="box-header with-border">
      <h3 class="box-title">Template List</h3>

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
            <th>Template Name</th>
            <th>Subject</th>
          </tr>
        </thead>
        <tbody>
        @if(isset($templates))
          @php $sl = 1; @endphp
          @forelse($templates as $tmp)
          <tr>
            <td>{{ $sl }}</td>
            <td>
              <a href="{{ route('edit_empTemp', array('id' => $tmp->id)) }}"><i class="fa fa-pencil-square-o base-green fa-2x"></i></a>

              <a href="{{ route('delete_empTemp', array('id' => $tmp->id)) }}" onclick="return confirm('Sure To Delete This Template ?');"><i class="fa fa-trash-o base-red fa-2x"></i></a>
            </td>
            <td>
              @if($tmp->status == '1')
                <a href="{{ route('acInac') }}?id={{ $tmp->id }}&val=2&tab=email_templates"> 
                  <i class="fa fa-check-circle-o base-green fa-2x" aria-hidden="true"></i> 
                </a>
              @endif
              @if($tmp->status == '2')
                <a href="{{ route('acInac') }}?id={{ $tmp->id }}&val=1&tab=email_templates"> 
                  <i class="fa fa-ban base-red fa-2x" aria-hidden="true"></i>
                </a> 
              @endif
            </td>
            <td>{{ $tmp->name }}</td>
            <td>{{ $tmp->subject }}</td>
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
    "order": [[ 1, "asc" ]],
    "columnDefs": [ {
      "targets": [ 0,1,2 ],
      "orderable": false
    } ]
  });
});
</script>
@endpush