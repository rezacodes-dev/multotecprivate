@extends('dashboard.layouts.app')

@section('content_header')
<section class="content-header">
      <h1>
        @if( isset($form_details) )
        All Records of <strong>{{ $form_details->frm_name }}</strong>
        @endif
        <!--small>it all starts here</small-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ route('frms') }}"><i class="fa fa-dashboard"></i> All Forms</a></li>
        <li class="active">All Records</li>
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
      <a href="{{ route('frms') }}" class="btn btn-primary"> All Forms</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
 
  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
    <div class="box-header with-border">
      <h3 class="box-title">All Records</h3>
      <br/>
      <form action="{{ url()->current() }}" method="get" id="filterForm">
      <div class="row">
        <div class="col-md-2">
          <label for="">Select Items</label>
          <select name="items" aria-controls="exportTab" class="form-control">
            <option value="10" @if($selectedItem==10) selected @endif>10</option>
            <option value="25" @if($selectedItem==25) selected @endif>25</option>
            <option value="50" @if($selectedItem==50) selected @endif>50</option>
            <option value="100" @if($selectedItem==100) selected @endif>100</option>
            <option value="200" @if($selectedItem==200) selected @endif>200</option>
          </select> 
        </div>
        <div class="col-md-4">
          <label for=""> Start Date</label>
          
         <input class="form-control" type="date" name="startdate" id="startdate" value="{{$startDate??''}}">
        </div>
        <div class="col-md-4">
          <label for="">End Date</label>
         <input  class="form-control" type="date" name="enddate" id="enddate" value="{{$endDate??''}}">
        </div>
     
        <div class="col-md-2">
          <label for=""> </label>
          <input type="submit" value="Filter" class="btn btn-info form-control" id="submit">
        </div>
      </div>
      </form>
      <div>
      
       </div>
      <div class="box-tools pull-right">
        @if( isset($form_details) )
        <a href="javascript:void(0);" id="export" class="btn btn-primary btn-sm">Download Excel</a>
        @endif
      </div>
    </div>
    <div class="box-body">
      @if( isset($tbl_headers) && count($tbl_headers) > 1)
      <table class="table table-bordered table-striped ar-datatable table-hover" id="exportTab" style="width: 100%;">
        <thead>
        @if( isset($tbl_headers) )
        <tr>
          <th>ID</th>
          <th>Referral URL</th>
          <th>Date</th>
       @foreach($tbl_headers as $obj)
     
    @if($obj->field_name != "country_9c62720c1b8b66770b57067db53705ce")
        <th>{{ trim(ucfirst($obj->display_text)) }}</th>
    @endif
@endforeach
          <th style="width: 5%;">#</th>
        </tr>
        @endif
        </thead>
        <tbody>
          <?php 
          if( isset($fields_key) && isset($records) ) {
            $i=1;
            foreach( $records as $obj ) {
              $data = array();
              if($obj->post_data != '' && $obj->post_data != null) {
                $data = @unserialize($obj->post_data);
              }
              if( !empty($data) ) {
                  echo "<tr>";
                  echo "<td>".$i++."</td>";
                  echo "<td  style='word-break: break-word;width:20%;'>".$obj->rerf_url."</td>";
                  echo "<td>".date('m-d-Y', strtotime($obj->created_at))."</td>";
                $arrx = array();
                foreach( $data as $index=>$vArr ) {
                 foreach( $vArr as $k=>$v ) {
                  $arrx[$k] = $v;
                 }
                }
                foreach ($fields_key as $ft=>$fk) {
                  if( array_key_exists( $fk, $arrx ) ) {
                    $print = $arrx[$fk];
                    $arrz = array();
                    if(is_array($print) && !empty($print)) {
                      $print = implode(',', $print);
                    }else {
                      $arrz = explode('.', $print);
                    }
                    if( !empty($arrz) ) {
                      $ext = strtolower(end($arrz));
                      if( $ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif' ) {
                        $print = "<img src='".url('/')."/".$print."' style='width: 30px; height: 30px;'>";
                      } 
                      if( $ext == 'doc' || $ext == 'docx' || $ext == 'pdf' || $ext == 'csv' || $ext == 'xls') {
                        $print = "<a href='".url('/')."/".$print."'>View</a>";
                      } 
                    }
                    echo "<td >".$print."</td>";
                  } else {
                    echo "<td>-</td>";
                  }
                }
                echo '<td><a href="'.route('frm_del_data', array('record_id' => $obj->id)).'" onclick="return confirm(\'Sure To Delete ?\');"><i class="fa fa-times base-red"></i></a></td>';
                echo "</tr>";
              }
            }
          }
          ?>
        </tbody>
      </table>
      @else
      FORM FIELDS ARE NOT SET YET, THANKS.
      @endif
    </div>
    <!-- /.box-body -->
    <div class="box-footer" style="text-align:right;">
       @if(isset($records)){{ $records->links() }}@endif
    </div>
    <!-- /.box-footer-->
  </div>
  <!-- /.box -->

    </section>
@endsection

@push('page_js')
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="{{ asset('public/assets/jquery.table2excel.min.js') }}"></script>
<script type="text/javascript">
$(function() {
  // $('.ar-datatable').DataTable({
  //   "ordering": false,
  //   "scrollX": true,
  //   "paging":   false
  // });
  $("#export").click(function(){
  $("#exportTab").table2excel({
    // exclude CSS class
    exclude: ".noExl",
    name: "Worksheet Name",
    filename: "SomeFile" //do not include extension
  }); 
});
});
</script>
<script>
  $(document).ready(function () {
      // Listen for changes in the select element
      $('select[name="items"]').on('change', function () {
          // Get the selected value
          var selectedValue = $(this).val();

          // Update the URL with the selected value
          var currentUrl = window.location.href;
          var newUrl = updateQueryStringParameter(currentUrl, 'items', selectedValue);

          // Reload the page with the updated URL
          window.location.href = newUrl;
      });

      // Function to update a query string parameter in the URL
      function updateQueryStringParameter(uri, key, value) {
          var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
          var separator = uri.indexOf('?') !== -1 ? "&" : "?";
          if (uri.match(re)) {
              return uri.replace(re, '$1' + key + "=" + value + '$2');
          } else {
              return uri + separator + key + "=" + value;
          }
      }
  });
</script>


<script>
    $(document).ready(function () {
        $('#filterForm').validate({
            rules: {
                startdate: {
                    required: true,
                },
                enddate: {
                    required: true,
                },
            
            },
            messages: {
                startdate: {
                    required: 'Please enter a start date',
                },
                enddate: {
                    required: 'Please enter an end date',
                },
                search: {
                    required: 'Please enter a search term',
                },
            },
            submitHandler: function (form) {
                // If the form is valid, you can submit it here
                form.submit();
            }
        });
    });
</script>

@endpush