@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
        All Countries
        <!--small>it all starts here</small-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('regio_page') }}">Regional Settings</a></li>
        <li class="active">Country List</li>
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
      <a href="{{ route('countryAdd') }}" class="btn btn-primary"> Add Country</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>

  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
    <div class="box-header with-border">
      <h3 class="box-title">Country List</h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
          <i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
          <i class="fa fa-times"></i></button>
      </div>
    </div>
    <div class="box-body">
      <table class="table table-bordered table-striped table-hover ar-datatable">
        <thead>
          <tr>
            <th>SL</th>
            <th>Country Name</th>
            <th>Region Name</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @if(isset($allCountries))
            @php $sl = 1; @endphp
            @forelse($allCountries as $cnt)
            <tr>
              <td>{{ $sl }}</td>
              <td>{{ $cnt->country_name }}</td>
              <td>{{ $cnt->Region->region_name }}</td>
              <td>
                @if( $cnt->status == '1') Active @endif
                @if( $cnt->status == '2') Inactive @endif
              </td>
              <td>
                <a href="{{ route('countryEdit', array('id' => $cnt->id)) }}" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="fa fa-pencil-square-o base-green"></i></a>

                <a href="{{ route('countryDelete', array('id' => $cnt->id)) }}" data-toggle="tooltip" data-placement="bottom" title="Delete" onclick="return confirm('Are You Sure To Delete ?');"><i class="fa fa-trash-o base-red"></i></a>
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
    "order": [[ 1, "asc" ]],
    "columnDefs": [ {
      "targets": [ 0, 4 ],
      "orderable": false
    } ]
  });
});
</script>
@endpush