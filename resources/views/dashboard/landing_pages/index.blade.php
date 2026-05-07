@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
        All Landing Pages
        <!--small>it all starts here</small-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('land.new') }}">Create New Landing Page</a></li>
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
      <a href="{{ route('land.new') }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Create New</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>

  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
    <div class="box-header with-border">
      <h3 class="box-title">All Landing Pages</h3>

      <div class="box-tools pull-right">
        
      </div>
    </div>
    <div class="box-body">
      <table class="table table-bordered table-hover table-striped ar-datatable">
        <thead>
          <tr>
            <th>SL</th>
            <th>Action</th>
            <th>Name</th>
            <th>Slug</th>
            <th>Created</th>
            <th>Updated</th>
          </tr>
        </thead>
        <tbody>
        @if(isset($LandingPages))
          @php $sl = 1; @endphp
          @forelse($LandingPages as $lp)
          <tr>
            <td>{{ $sl }}</td>
            <td>
              <a href="{{ route('land.edt', array('id' => $lp->id)) }}"><i class="fa fa-pencil-square-o base-green fa-2x"></i></a>
              
              <a href="{{ route('land.del', array('id' => $lp->id)) }}" onclick="return confirm('Sure To Delete This Landing Page URL ?');"><i class="fa fa-trash-o base-red fa-2x"></i></a>
              <a href="{{ route('landing_page_view', array('lng' => 'en', 'slug' => $lp->slug)) }}" target="_blank">
              <i class="fa fa-eye fa-2x" aria-hidden="true"></i></a>
              
            </td>
            <td>{{ $lp->name }}</td>
            <td>
              <a href="{{ route('landing_page_view', array('lng' => 'en', 'slug' => $lp->slug)) }}" target="_blank">
                {{ route('landing_page_view', array('lng' => 'en', 'slug' => $lp->slug)) }}</a>
            </td>
            <td>{{ date('d-m-Y', strtotime($lp->created_at)) }}</td>
            <td>{{ date('d-m-Y', strtotime($lp->created_at)) }}</td>
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
      "targets": [ 5 ],
      "orderable": false
    } ]
  });
});
</script>
@endpush