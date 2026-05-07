@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
        All Products
        <!--small>it all starts here</small-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">All Products</li>
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
      <a href="{{ route('addProd') }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add New Product</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>

  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
    <form name="frmx" action="{{ route('prod.blkAct') }}" method="post">
    {{ csrf_field() }}
    <div class="box-header with-border">
      <h3 class="box-title">All Products</h3>

      <div class="box-tools pull-right">
        <button type="submit" name="action_btn" class="btn btn-success btn-sm" value="activate">Activate</button>
        <button type="submit" name="action_btn" class="btn btn-warning btn-sm" value="deactivate">Deactivate</button>
        <button type="submit" name="action_btn" class="btn btn-danger btn-sm" value="delete" onclick="return confirm('Are You Sure You Want To Delete Selected ?');">Delete</button>
        <a href="{{ route('proDWN', array('type' => 'xls')) }}" class="btn btn-default btn-sm">Download Excel</a>
      </div>
    </div>
    <div class="box-body">
      <table class="table table-bordered table-hover table-striped display nowrap ar-datatable" style="width:100%">
        <thead>
          <tr>
            <th><input type="checkbox" id="ckAll"></th>
            <th>Action</th>
            <th>Status</th>
            <th>Image</th>
            <th>Name</th>
            <th>URL</th>
            <th>Categories</th>
            <th>Created</th>
            <th>Modified</th>
            
          </tr>
        </thead>
        <tbody>
        @if(isset($allProducts))
          @php $sl = 1; @endphp
          @forelse($allProducts as $prod)
            @php
              $lngcode = getLngCode($prod->language_id);
            @endphp
          <tr>
            <td>
              {{ $sl }}
              <input type="checkbox" name="ids[]" class="ckbs" value="{{ $prod->id }}">
            </td>
            <td>
              <a href="{{ route('editProd', array('id' => $prod->id)) }}"><i class="fa fa-pencil-square-o fa-2x base-green"></i></a>
              <a href="{{ route('delProd', array('id' => $prod->id)) }}" onclick="return confirm('Sure To Delete This Product ?');"><i class="fa fa-2x fa-trash-o base-red"></i></a>

              <a href="{{ url($lngcode.'/'.$prod->slug) }}" target="_blank">
              <i class="fa fa-2x fa-eye" aria-hidden="true"></i></a>
              
              <a href="{{ route('crte.dup') }}?tab=products&id={{ $prod->id }}" onclick="return confirm('Are you sure to create duplicate of this page ?');"><i class="fa fa-clone fa-2x" aria-hidden="true"></i></a>
            </td>
            <td>
              @if($prod->status == '1')
                <a href="{{ route('acInac') }}?id={{ $prod->id }}&val=2&tab=products"> 
                  <i class="fa fa-check-circle-o base-green fa-2x" aria-hidden="true"></i> 
                </a>
              @endif
              @if($prod->status == '2')
                <a href="{{ route('acInac') }}?id={{ $prod->id }}&val=1&tab=products"> 
                  <i class="fa fa-ban base-red fa-2x" aria-hidden="true"></i>
                </a> 
              @endif
            </td>
            <td>
              @if( isset($prod->ProductImages) && count($prod->ProductImages) > 0 )
                @php $i = 0; @endphp

                  @foreach( $prod->ProductImages as $imgs )
                    @if( $i == 0 )
                      @if( isset($imgs->imageInfo) )
                      <img src="{{ asset('public/uploads/files/media_images/thumb/'. $imgs->imageInfo->image) }}" class="img-thumbnail" style="width : 60px;">
                      @endif
                    @endif

                    @php $i++; @endphp
                  @endforeach
              @endif
            </td>
            <td>{{ ucfirst( $prod->name ) }}</td>
            <td>{{ url($lngcode.'/'.$prod->slug) }}</td>
            <td>
              @if( isset($prod->ProductCategoryIds) && count($prod->ProductCategoryIds) > 0 )
                @foreach( $prod->ProductCategoryIds as $pcInfo )
                  @if( isset($pcInfo->ProductCategoryInfo) && !empty($pcInfo->ProductCategoryInfo) )
                    <li>{{ $pcInfo->ProductCategoryInfo->name }}</li>
                  @endif
                @endforeach
              @endif
            </td>
            <td>{{ date('m-d-Y', strtotime($prod->created_at)) }}</td>
            <td>{{ date('m-d-Y', strtotime($prod->updated_at)) }}</td>
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
  </form>
  </div>
  <!-- /.box -->

    </section>
@endsection

@push('page_js')
<script type="text/javascript">
$(function() {
  $('.ar-datatable').DataTable({
    "scrollX": true,
    "columnDefs": [ {
      "targets": [ 0,1,2 ],
      "orderable": false,
    } ]
  });
});
$( function() {
  $("#ckAll").on('click',function(){
    var isCK = $(this).is(':checked');
    if(isCK == true){
      $('.ckbs').prop('checked', true);
      $('#delAll').removeAttr('disabled');
    }
    if(isCK == false){
      $('.ckbs').prop('checked', false);
      $('#delAll').attr('disabled', 'disabled');
    }
    colMark();
    $('#delAll').val('Delete Selected');
  });
  $(".ckbs").on('click', function(){
    var c = 0;
    $(".ckbs").each(function(){
      colMark();
      if($(this).is(':checked')){
        c++;
      }
    });
    if(c == 0){
      $("#ckAll").prop('checked', false);
      $('#delAll').attr('disabled', 'disabled');
    }
    if(c > 0){
      $("#ckAll").prop('checked',true);
      $('#delAll').removeAttr('disabled');
    }
    $('#delAll').val('Delete Selected ('+c+')');
  });
} );
function colMark() {
  $( '.ckbs' ).each(function() {
    if($(this).is(':checked')) {
      $(this).parents('tr').css('background-color', '#ffe6e6');
    } else {
      $(this).parents('tr').removeAttr('style');
    }
  });
}
</script>
@endpush