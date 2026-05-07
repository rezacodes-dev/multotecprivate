@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
  <h1>
    All People Profiles
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('addProfile') }}">Add New Profile</a></li>
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
      <a href="{{ route('addProfile') }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Add New Profile</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>

  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
    <form name="frmx" action="{{ route('pp.blkAct') }}" method="post">
    {{ csrf_field() }}
    <div class="box-header with-border">
      <h3 class="box-title">All People Profiles</h3>

      <div class="box-tools pull-right">
        <button type="submit" name="action_btn" class="btn btn-success btn-sm" value="activate">Activate</button>
        <button type="submit" name="action_btn" class="btn btn-warning btn-sm" value="deactivate">Deactivate</button>
        <button type="submit" name="action_btn" class="btn btn-danger btn-sm" value="delete" onclick="return confirm('Are You Sure You Want To Delete Selected ?');">Delete</button>
      </div>
    </div>
    <div class="box-body">
      <table class="table table-bordered table-hover table-striped display nowrap ar-datatable" style="width:100%">
        <thead>
          <tr>
            <th><input type="checkbox" id="ckAll"></th>
            <th>Action</th>
            <th>Status</th>
            <th>Name</th>
            <th>Image</th>
            <th>Category</th>
            <th>URL</th>
            <th>Created</th>
            <th>Modified</th>
          </tr>
        </thead>
        <tbody>
        @if(isset($allProfiles))
          @php $sl = 1; @endphp
          @forelse($allProfiles as $pp)
            @php
              $lngcode = getLngCode($pp->language_id);
            @endphp
          <tr>
            <td>
              {{ $sl }}
              <input type="checkbox" name="ids[]" class="ckbs" value="{{ $pp->id }}">
            </td>
            <td>
              <a href="{{ route('edtProfile', array('id' => $pp->id)) }}"><i class="fa fa-pencil-square-o base-green fa-2x"></i></a>
              <a href="{{ route('delProfile', array('id' => $pp->id)) }}" onclick="return confirm('Sure To Delete This Profile ?');"><i class="fa fa-trash-o base-red fa-2x"></i></a>
              <a href="{{ route('front.profCont', array('lng' => 'en', 'slug' => $pp->slug)) }}" target="_blank">
              <i class="fa fa-eye fa-2x" aria-hidden="true"></i></a>
            </td>
            <td>
              @if($pp->status == '1')
                <a href="{{ route('acInac') }}?id={{ $pp->id }}&val=2&tab=peoples_profile"> 
                  <i class="fa fa-check-circle-o base-green fa-2x" aria-hidden="true"></i> 
                </a>
              @endif
              @if($pp->status == '2')
                <a href="{{ route('acInac') }}?id={{ $pp->id }}&val=1&tab=peoples_profile"> 
                  <i class="fa fa-ban base-red fa-2x" aria-hidden="true"></i>
                </a> 
              @endif
            </td>
            <td>{{ $pp->name }}</td>
            <td>
              @if( isset($pp->ProfileImageId) && isset($pp->ProfileImageId->imageInfo) )
              <img src="{{ asset('public/uploads/files/media_images/thumb/'.$pp->ProfileImageId->imageInfo->image) }}" class="img-thumbnail">
              @endif
            </td>
            <td>
              @if( isset($pp->PP_categoryOneIds) && isset($pp->PP_categoryOneIds->PPcatInfo) )
              {{ $pp->PP_categoryOneIds->PPcatInfo->name }}
              @endif
            </td>
            <td>{{ route('front.profCont', array('lng' => 'en', 'slug' => $pp->slug)) }}</td>
            <td>{{ date('d-m-Y', strtotime($pp->created_at)) }}</td>
            <td>{{ date('d-m-Y', strtotime($pp->updated_at)) }}</td>
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