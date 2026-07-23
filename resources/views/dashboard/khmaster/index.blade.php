@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
        All Knowledge Hub
        <!--small>it all starts here</small-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">All Knowledge Hub</li>
      </ol>
    </section>
@endsection

@section('content')

 
<section class="content">
@if(session('msg'))
<div class="alert alert-success">
{{ session('msg') }}
</div>
@endif



  <div class="row">
    <div class="col-md-6">
      <a href="{{ route('addKhallId') }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Create New Knowledge Hub</a>
    </div>
    <div class="col-md-6">
    </div>
    
  </div>

  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
    <form name="frmx" action="{{ route('prodcat.blkAct') }}" method="post">
    {{ csrf_field() }}
    <div class="box-header with-border">
      <h3 class="box-title">All Knowledge Hub</h3>

      

      <div class="box-tools pull-right">
        <!-- <button type="submit" name="action_btn" class="btn btn-success btn-sm" value="activate">Activate</button>
        <button type="submit" name="action_btn" class="btn btn-warning btn-sm" value="deactivate">Deactivate</button>
        <button type="submit" name="action_btn" class="btn btn-danger btn-sm" value="delete" onclick="return confirm('Are You Sure You Want To Delete Selected ?');">Delete</button> -->
      </div>
    </div>
    <div class="box-body">
      <table class="table table-bordered table-hover table-striped display nowrap ar-datatable" style="width:100%">
        <thead>
          <tr>
            <th><input type="checkbox" id="ckAll"></th>
            <th>Action</th>
            <th>Status</th>
            <th>Slug</th> 
            <th>Name</th> 
            <th>Created</th>
            <th>Modified</th>
       
          </tr>
        </thead>
        <tbody>
        @if(isset($allProdCats))
          @php $sl = 1; @endphp
          @forelse($allProdCats as $pc)
       
          <tr>
            <td>
              {{ $sl }}
          
            </td>
            <td>

            
              @php $url=route('editKhallId', array('id' => $pc->id))  @endphp
              <a href="{{ url('/en' . route('front.knowledgehubCont', ['id' => $pc->slug??''], false)) }}" target="_blank">
                  <i class="fa fa-eye fa-2x base-green"></i>
              </a>

              <a href="{{ route('editKhallId', array('id' => $pc->id)) }}"><i class="fa fa-pencil-square-o fa-2x base-green"></i></a>
              <a href="{{ route('delKhallId', array('id' => $pc->id)) }}" onclick="return confirm('Sure To Delete This Item ?');"><i class="fa fa-2x fa-trash-o base-red"></i></a>
              
             
             </td>
         
            <td>
              @if($pc->status == '1')
                <a href="{{ route('acInac') }}?id={{ $pc->id }}&val=2&tab=knowledge_hub"> 
                  <i class="fa fa-check-circle-o base-green fa-2x" aria-hidden="true"></i> 
                </a>
              @endif
              @if($pc->status == '2')
                <a href="{{ route('acInac') }}?id={{ $pc->id }}&val=1&tab=knowledge_hub"> 
                  <i class="fa fa-ban base-red fa-2x" aria-hidden="true"></i>
                </a> 
              @endif
            </td>
            <td>{{ $pc->slug??'' }}</td>
            <td>{{ ucfirst($pc->name) }}</td>
            
            <td> {{ date('m-d-Y', strtotime($pc->created_at)) }} </td>
            <td> {{ date('m-d-Y', strtotime($pc->updated_at)) }} </td>
     
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


<script type='text/javascript'>
        function action(url,id){

          var language_id= $("#language_id"+id).val();
          var csrf= $("#csrf"+id).val();

          var form = document.createElement("form");
          element1 = document.createElement("input");
          element2 = document.createElement("input"); 
          form.action = url;
          form.method = "post";
          element1.name = "language_id";
          element1.value = language_id;
          element2.name = "_token";
          element2.value = csrf;
          form.appendChild(element1);
          form.appendChild(element2);
          document.body.appendChild(form);
          form.submit();
        }
               
</script>
@endpush