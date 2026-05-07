@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
        Regional Settings Control
        <!--small>it all starts here</small-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Regional Settings</li>
      </ol>
    </section>
@endsection

@section('content')
<section class="content">

  @if(Session::has('msg'))
  <div class="ar-hide @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}</div>
  @endif


  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
    <div class="box-header with-border">
      <h3 class="box-title">Regional Settings</h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
          <i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
          <i class="fa fa-times"></i></button>
      </div>
    </div>
    <div class="box-body">
      <div class="row">
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">Continents</div>
            <div class="panel-body">
              <ul>
                <li><a href="{{ route('continentsList') }}">Continents List</a></li>
                <li><a href="{{ route('continentsAdd') }}">Add New Continent</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">Regions</div>
            <div class="panel-body">
              <ul>
                <li><a href="{{ route('regionList') }}">Regions List</a></li>
                <li><a href="{{ route('regionAdd') }}">Add New Region</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">Countries</div>
            <div class="panel-body">
              <ul>
                <li><a href="{{ route('countryList') }}">Countries List</a></li>
                <li><a href="{{ route('countryAdd') }}">Add New Country</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">Provinces/Territories</div>
            <div class="panel-body">
              <ul>
                <li><a href="{{ route('provincesList') }}">Provinces List</a></li>
                <li><a href="{{ route('provincesAdd') }}">Add New Province</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">Cities</div>
            <div class="panel-body">
              <ul>
                <li><a href="{{ route('cityList') }}">Cities List</a></li>
                <li><a href="{{ route('cityAdd') }}">Add New City</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="col-md-4"></div>
      </div>
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

</script>
@endpush