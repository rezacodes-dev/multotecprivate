@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
        Dashboard
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      </ol>
    </section>
@endsection

@section('content')
<hr/>
<section class="content">

  @if(Session::has('msg'))
  <div class="ar-hide @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}</div>
  @endif
      
      @hasanyrole('Super-Admin|Admin')
      <div class="row">

        @if( isset($users) )
        <div class="col-md-3 col-sm-6 col-xs-12">
          <a href="{{ route('users_list') }}">
          <div class="info-box">
            <span class="info-box-icon bg-green">
                <i class="fa fa-users" aria-hidden="true"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Users</span>
              <span class="info-box-number">{{ $users }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          </a>
        </div>
        @endif

        @if( isset($products) )
        <div class="col-md-3 col-sm-6 col-xs-12">
          <a href="{{ route('allProds') }}">
          <div class="info-box">
            <span class="info-box-icon bg-aqua">
                <i class="fa fa-cubes" aria-hidden="true"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Products</span>
              <span class="info-box-number">{{ $products }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          </a>
        </div>
        @endif

        @if( isset($contents) )
        <div class="col-md-3 col-sm-6 col-xs-12">
          <a href="{{ route('typeList') }}">
          <div class="info-box">
            <span class="info-box-icon bg-yellow">
                <i class="fa fa-file-text" aria-hidden="true"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Contents</span>
              <span class="info-box-number">{{ $contents }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          </a>
        </div>
        @endif

        @if( isset($articles) )
        <div class="col-md-3 col-sm-6 col-xs-12">
          <a href="{{ route('allArts') }}">
          <div class="info-box">
            <span class="info-box-icon bg-green">
                <i class="fa fa-newspaper-o" aria-hidden="true"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Articles</span>
              <span class="info-box-number">{{ $articles }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          </a>
        </div>
        @endif

        {{--
        @if( isset($events) )
        <div class="col-md-3 col-sm-6 col-xs-12">
          <a href="{{ route('evts_lst') }}">
          <div class="info-box">
            <span class="info-box-icon bg-yellow">
                <i class="fa fa-bullhorn" aria-hidden="true"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Events</span>
              <span class="info-box-number">{{ $events }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          </a>
        </div>
        @endif
        --}}

        @if( isset($distributors) )
        <div class="col-md-3 col-sm-6 col-xs-12">
          <a href="{{ route('allDistrib') }}">
          <div class="info-box">
            <span class="info-box-icon bg-green">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Branches</span>
              <span class="info-box-number">{{ $distributors }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          </a>
        </div>
        @endif

        @if( isset($industries) )
        <div class="col-md-3 col-sm-6 col-xs-12">
          <a href="{{ route('allIndus') }}">
          <div class="info-box">
            <span class="info-box-icon bg-aqua">
                <i class="fa fa-building-o" aria-hidden="true"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Industries</span>
              <span class="info-box-number">{{ $industries }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          </a>
        </div>
        @endif

        @if( isset($industry_sheets) )
        <div class="col-md-3 col-sm-6 col-xs-12">
          <a href="{{ route('allFSc') }}">
          <div class="info-box">
            <span class="info-box-icon bg-yellow">
                <i class="fa fa-puzzle-piece" aria-hidden="true"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Flowsheets</span>
              <span class="info-box-number">{{ $industry_sheets }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          </a>
        </div>
        @endif


        @if( isset($profiles) )
        <div class="col-md-3 col-sm-6 col-xs-12">
          <a href="{{ route('allProfiles') }}">
          <div class="info-box">
            <span class="info-box-icon bg-aqua">
                <i class="fa fa-address-card-o" aria-hidden="true"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">User Profiles</span>
              <span class="info-box-number">{{ $profiles }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          </a>
        </div>
        @endif


        @if( isset($tech_resources) )
        <div class="col-md-3 col-sm-6 col-xs-12">
          <a href="{{ route('allResource') }}">
          <div class="info-box">
            <span class="info-box-icon bg-red">
                <i class="fa fa-diamond" aria-hidden="true"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Technical Resources</span>
              <span class="info-box-number">{{ $tech_resources }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          </a>
        </div>
        @endif

      </div>
      <div class="row">
        
        @if( isset($forms) )
        <div class="col-md-3 col-sm-6 col-xs-12">
          <a href="{{ route('frms') }}">
          <div class="info-box">
            <span class="info-box-icon">
                <i class="fa fa-check-square-o" aria-hidden="true" style="color: #000;"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Form Builders</span>
              <span class="info-box-number">{{ $forms }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          </a>
        </div>
        @endif

        @if( isset($images) )
        <div class="col-md-3 col-sm-6 col-xs-12">
          <a href="{{ route('media_all_imgs') }}">
          <div class="info-box">
            <span class="info-box-icon">
                <i class="fa fa-file-image-o" aria-hidden="true" style="color: #000;"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Images</span>
              <span class="info-box-number">{{ $images }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          </a>
        </div>
        @endif

        @if( isset($files) )
        <div class="col-md-3 col-sm-6 col-xs-12">
          <a href="{{ route('allFiles') }}">
          <div class="info-box">
            <span class="info-box-icon">
                <i class="fa fa-files-o" aria-hidden="true" style="color: #000;"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Files</span>
              <span class="info-box-number">{{ $files }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          </a>
        </div>
        @endif

        @if( isset($videos) )
        <div class="col-md-3 col-sm-6 col-xs-12">
          <a href="{{ route('allVideos') }}">
          <div class="info-box">
            <span class="info-box-icon">
                <i class="fa fa-video-camera" aria-hidden="true" style="color: #000;"></i>
            </span>
            <div class="info-box-content">
              <span class="info-box-text">Videos</span>
              <span class="info-box-number">{{ $videos }}</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
          </a>
        </div>
        @endif

      </div>
      @endhasanyrole

    </section>
@endsection

@push('page_js')

@endpush