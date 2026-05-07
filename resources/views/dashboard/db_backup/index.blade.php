@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
        All Database Backups
        <!--small>it all starts here</small-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      </ol>
    </section>
@endsection

@section('content')
<section class="content">

  @if(Session::has('msg'))
  <div class="ar-hide @if(Session::has('msg_class')){{ Session::get('msg_class') }}@endif">{{ Session::get('msg') }}</div>
  @endif

  <div class="row">
    <div class="col-md-4">
      <a href="javascript:void(0);" class="btn btn-success" id="bkpBtn"> <i></i> &nbsp;Create Database Backup</a>
    </div>
    <div class="col-md-8">
      <label id="status"></label>
    </div>
  </div>

  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
    <div class="box-header with-border">
      <h3 class="box-title">All Backup Files</h3>

      <div class="box-tools pull-right">
        <a href="{{ route('dele_alldbbacks') }}" class="btn btn-danger btn-sm">Delete All Backups</a>
      </div>
    </div>
    <div class="box-body">
      <table class="table table-bordered table-hover table-striped ar-datatable">
        <thead>
          <tr>
            <th style="width: 20px;">SL</th>
            <th>Date Time</th>
            <th>File Name</th>
            <th>Size</th>
            <th>Download</th>
            <th>Delete</th>
          </tr>
        </thead>
        <tbody>
        @if(isset($allFiles))
          @php $sl = 1; @endphp
          @forelse($allFiles as $fl)
            @php $fullname = File::name($fl).'.'.File::extension($fl); @endphp
            <tr>
              <td>{{ $sl }}</td>
              <td>
                @php
                $lastmodified = File::lastModified($fl);
                $lastmodified = DateTime::createFromFormat("U", $lastmodified)->format('d-m-Y h:i:s A');
                echo $lastmodified;
                @endphp
              </td>
              <td>{{ File::name($fl) }}.{{ File::extension($fl) }}</td>
              <td>{{ sizeFilter( File::size($fl) ) }}</td>
              <td>
                <a href="{{ asset('public/dbbackups/'.$fullname) }}" download="_{{ $fullname }}">Download</a>
              </td>
              <td>
                <a href="javascript:void(0);" class="rmFl" id="{{ $fullname }}">
                  <i class="fa fa-times base-red"></i> <span class="base-red">Remove</span>
                </a>
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
    "columnDefs": [ {
      "targets": [ 3 ],
      "orderable": false
    } ]
  });
  $('#bkpBtn').on('click', function() {
    swal( {
      title: "Are you sure ?",
      text: "",
      type: "warning",
      showCancelButton: true,
      confirmButtonClass: "btn-success",
      confirmButtonText: "Yes",
      closeOnConfirm: false
    },
    function() {
      $.ajax({
        type : 'POST',
        url : "{{ route('crte_dbbacks') }}",
        data : {
          '_token' : "{{ csrf_token() }}"
        },
        beforeSend : function() {
          $('#bkpBtn').attr('disabled', 'disabled').find('i').addClass('fa fa-cog fa-spin');
          $('#status').show().html('<span class="base-red">Please Wait... Script Running...</span>');
        },
        success : function(res) {
          if( res == '1' || res == 1 ) {
            swal("Backup Created!", "New Database Backup Created Successfully, Wait...", "success");
            $('#bkpBtn').removeAttr('disabled').find('i').removeClass('fa fa-cog fa-spin');
            $('#status').html('<span class="base-green">New Database Backup Successfully.</span>').fadeOut(6000);
            setTimeout( function() { location.reload(); }, 2000 );
          }
        }
      });
    } );
  } );
  $('.rmFl').on('click', function() {
    var filename = $.trim( $(this).attr('id') );
    var currThis = $(this);
    $.ajax({
      type : 'POST',
      url : "{{ route('dele_dbbacks') }}",
      data : {
        'filename' : filename,
        '_token' : "{{ csrf_token() }}"
      },
      beforeSend : function() {
        currThis.text('Wait..');
      },
      success : function(rs) {
        if( rs == '1' || rs == 1 ) {
          currThis.parents('tr').remove();
        }
      }
    });
  } );
});
</script>
@endpush