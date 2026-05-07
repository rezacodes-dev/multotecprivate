@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
        All Form Categories
        <!--small>it all starts here</small-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">All Forms</li>
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
      <a href="{{ route('frmCats_crte') }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add New Category</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>

  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
    <form name="frmx" action="{{ route('frmcat.blkAct') }}" method="post">
    {{ csrf_field() }}
    <div class="box-header with-border">
      <h3 class="box-title">All Form Categories</h3>

      <div class="box-tools pull-right">
        <button type="submit" name="action_btn" class="btn btn-success btn-sm" value="activate">Activate</button>
        <button type="submit" name="action_btn" class="btn btn-warning btn-sm" value="deactivate">Deactivate</button>
        <button type="submit" name="action_btn" class="btn btn-danger btn-sm" value="delete" onclick="return confirm('Are You Sure You Want To Delete Selected ?');">Delete</button>
      </div>
    </div>
    <div class="box-body">
      <table class="table table-bordered table-striped table-hover ar-datatable">
        <thead>
          <tr>
            <th style="width: 5%;"><input type="checkbox" id="ckAll"></th>
            <th style="width: 10%;">Action</th>
            <th style="width: 10%;">Status</th>
            <th style="width: 35%;">Category Name</th>
            <th style="width: 10%;">Forms</th>
            <th style="width: 10%;">Created</th>
            <th style="width: 10%;">Modified</th>
          </tr>
        </thead>
        <tbody>
        @if( isset($allCats) && !empty($allCats) )
          @php $sl = 1; @endphp
          @foreach($allCats as $v)
          <tr>
            <td>
              {{ $sl }}
              <input type="checkbox" name="ids[]" class="ckbs" value="{{ $v->id }}">
            </td>
            <td>
              <a href="{{ route('frmCats_edt', array('id' => $v->id)) }}">
                <i class="fa fa-pencil-square-o base-green fa-2x" aria-hidden="true"></i>
              </a>
              <a href="{{ route('frmCats_del', array('id' => $v->id)) }}" onclick="return confirm('Sure to delete this Category ?');">
                <i class="fa fa-trash-o base-red fa-2x" aria-hidden="true"></i>
              </a>
            </td>
            <td>
              @if($v->status == '1')
                <a href="{{ route('acInac') }}?id={{ $v->id }}&val=2&tab=frm_category"> 
                  <i class="fa fa-check-circle-o base-green fa-2x" aria-hidden="true"></i> 
                </a>
              @endif
              @if($v->status == '2')
                <a href="{{ route('acInac') }}?id={{ $v->id }}&val=1&tab=frm_category"> 
                  <i class="fa fa-ban base-red fa-2x" aria-hidden="true"></i>
                </a> 
              @endif
            </td>
            <td>{{ $v->category_name }}</td>
            <td>@if( isset($v->TotalForms) ){{ count($v->TotalForms) }}@endif</td>
            <td>{{ date('d-m-Y', strtotime($v->created_at)) }}</td>
            <td>{{ date('d-m-Y', strtotime($v->updated_at)) }}</td>
          </tr>
          @php $sl++; @endphp
          @endforeach
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
    "columnDefs": [ {
      "targets": [  0, 2, 6 ],
      "orderable": false
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