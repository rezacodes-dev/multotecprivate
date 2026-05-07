@extends('dashboard.layouts.app')


@section('content_header')
<section class="content-header">
  <h1>
    Content Management
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('allContTyps') }}">All Content Types</a></li>
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
      <a href="{{ route('allContTyps') }}" class="btn btn-primary"> All Content Types</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">All Content Types</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          @if(isset($allTypes))
          <ul class="list-group">
            @foreach($allTypes as $list)
            <li class="list-group-item" style="font-size: 16px;">
              <a href="{{ route('mngLists', array('type' => str_slug($list->name), 'type_id' => $list->id)) }}">
                <i class="fa fa-2x fa-file-text" aria-hidden="true"></i>  {{ ucfirst($list->name) }}
                <span class="badge pull-right" style="font-size: 16px;">{{ count($list->contentIds) }}</span>
              </a>
            </li>
            @endforeach
          </ul>
          @endif
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
          
        </div>
        <!-- /.box-footer-->
      </div>
      <!-- /.box -->
    </div>
  </div>

</section>
@endsection

@push('page_js')
<script type="text/javascript">

</script>
@endpush