@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
        All Referer List
        <!--small>it all starts here</small-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"> All Referer List</li>
      </ol>
    </section>
@endsection

@push('page_css')
<style type="text/css">
.pagination {
  float: right;
}
 
</style> 
@endpush

@section('content')
<section class="content">

  @if(Session::has('msg'))
  <div class="ar-hide @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}</div>
  @endif

    
  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
    <form name="frmx" action="{{ route('reffDWN') }}" method="post">
    {{ csrf_field() }}
    <div class="box-header with-border">
      <h3 class="box-title"> All Referer List</h3>

     
           
     

      <div class="box-tools pull-right">

      <input type="text" placeholder="Enter URL" name="url" value="@if(isset($url)){{$url}} @endif">

      <!-- <input type="text" placeholder="Enter URL" name="name" > -->
     

      <label  style="font-weight: 500;">Start Date: <input  value="@if(isset($start_date)){{$start_date}}@endif" type="date" placeholder="Enter Start Date" name="start_date" ></label>
     
      <label style="font-weight: 500;">End Date: <input  value="@if(isset($end_date)){{$end_date}}@endif" type="date" placeholder="Enter End Date" name="end_date" ></label>

      <button  name="action_btn" value="search" type="submit" class="btn btn-default btn-sm">Search</button>

      <button  name="action_btn" value="download" type="submit" class="btn btn-default btn-sm">Download Excel</button>
       
       <!-- <button type="submit" name="action_btn" class="btn btn-danger btn-sm" value="delete" onclick="return confirm('Are You Sure You Want To Delete Selected ?');">Delete</button> -->
        <!--  <a href="{{ route('proDWN', array('type' => 'xls')) }}" class="btn btn-default btn-sm">Download Excel</a> -->
      </div>
    </div>
    <div class="box-body">
      <table class="table table-bordered table-hover table-striped display nowrap ar-datatable" style="width:100%">
        <thead>
          <tr>
            <th><input type="checkbox" id="ckAll"></th>
            <th>Action</th>
            <th>Referral</th>
            <th>Souce Type</th>
            <th>Campaign</th>
            <th>IP Address</th> 
            <th>Created</th>  
          </tr>
        </thead>
        <tbody>
        @if(isset($allReferral))
          @php $sl = 1; @endphp
          @forelse($allReferral as $prod)
            
          <tr>
            <td>
              {{ $sl }}
              <input type="checkbox" name="ids[]" class="ckbs" value="{{ $prod->id }}">
            </td>
 
            <td> 
             <a href="{{ route('delReferral', array('id' => $prod->id)) }}" onclick="return confirm('Sure To Delete This Item ?');"><i class="fa fa-2x fa-trash-o base-red"></i></a>
              </td>
             
            <td>{{$prod->referral}}</td>
            <td>{{$prod->source_type}}</td>
            <td>{{$prod->campaign}}</td>
            <td>{{$prod->ip}}</td> 
            <td>{{ date('m-d-Y', strtotime($prod->created_at)) }}</td> 
          </tr>
          @php $sl++; @endphp
          @empty
          @endforelse
        @endif
        </tbody>
     
      </table>
      @if( isset($allReferral) ){{ $allReferral->links() }}@endif


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
    "searching": false,
    "scrollX": true,
    "paging": false,
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