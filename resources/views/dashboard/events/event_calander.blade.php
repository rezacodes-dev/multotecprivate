@extends('dashboard.layouts.app')

@push('page_css')
<link rel="stylesheet" href="{{ asset('public/assets/jquery_ui/jquery-ui.css') }}">
<link rel="stylesheet" href="{{ asset('public/assets/bower_components/fullcalendar/dist/fullcalendar.min.css') }}">
<link rel="stylesheet" href="{{ asset('public/assets/bower_components/fullcalendar/dist/fullcalendar.print.min.css') }}" media="print">
@endpush


@section('content_header')
<section class="content-header">
      <h1>
        Event Calendar
        <!--small>it all starts here</small-->
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Event Calendar View</li>
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
      <a href="{{ route('evts_lst') }}" class="btn btn-primary"> All Events</a>
      <a href="{{ route('evts_crte') }}" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Create Event</a>
    </div>
    <div class="col-md-6">
    </div>
  </div>
  
  <!-- Default box -->
  <div class="box" style="margin-top: 10px;">
    <div class="box-header with-border">
      <h3 class="box-title">Calendar View</h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                title="Collapse">
          <i class="fa fa-minus"></i></button>
        <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
          <i class="fa fa-times"></i></button>
      </div>
    </div>
    <div class="box-body">
      <div class="row">
        <div class="col-md-12">
          <div id="calendar"></div>
        </div>
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
<script src="{{ asset('public/assets/jquery_ui/jquery-ui.js') }}"></script>
<script src="{{ asset('public/assets/bower_components/moment/moment.js') }}"></script>
<script src="{{ asset('public/assets/bower_components/fullcalendar/dist/fullcalendar.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
  $('#calendar').fullCalendar({
    customButtons: {
      myCustomButton: {
        text: 'List View',
        click: function() {
          window.location.href = "{{ route('evts_lst') }}";
        }
      }
    },
    header: {
      left: 'prev next today myCustomButton', //
      center: 'title',
      right: 'month,basicWeek,basicDay'
    },
    defaultDate: '<?php echo date('Y-m-d'); ?>',
    editable: true,
    eventLimit: true, // allow "more" link when too many events
    selectable: true,
    selectHelper: true,
    //eventStartEditable: false, // disable drag drop
    select: function(start, end) { 
      if(start.isBefore(moment())) {
          //previous date not select
      } else {
          
      }
    },
    eventRender: function(event, element) {
      
      element.bind('dblclick', function() {
          //alert(event.id);
      });
    },
    eventDrop: function(event, delta, revertFunc) { // si changement de position
      if(event.start.isBefore(moment())) {
          //$('#calendar').fullCalendar('removeEvents', event.id); // remove event
          revertFunc();
      } else {
          ev_modify(event);
      }
    },
    eventResize: function(event,dayDelta,minuteDelta,revertFunc) { // si changement de longueur

        ev_modify(event);

    },
    events: [
      <?php
      if(!empty($events) && count($events) > 0)
      {
          foreach($events as $data)
          {
      ?>
      {
          id: '<?php echo $data->id; ?>',
          title: '<?php echo ucwords($data->name); ?>',
          start: '<?php echo $data->start_date; ?>',
          end: '<?php echo date('Y-m-d',strtotime('+1 day', strtotime($data->end_date))); ?>',
          color: '<?php echo $data->color; ?>',
      },
      <?php
          }
      }
      ?>
    ]
  }); 
  function ev_modify(event) {
    var start_date = moment(event.start).format("DD-MM-YYYY");
    var end_date = moment(event.end).add(-1, 'days').format("DD-MM-YYYY");
    var token = "{{ csrf_token() }}";
    var id = event.id;
    $.ajax({
      type:"POST",
      url:"{{ route('ajaxevtCalModify') }}",
      data:"id="+id+"&start_date="+start_date+"&end_date="+end_date+"&_token="+token,
      beforeSend: function() {
        $('#calendar').block({ 
          message: '<h4>Please wait...</h4>', 
          css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
          } 
        });
      },
      success:function(res) {
        $('#calendar').unblock();
      }
    });
  }    
});
</script>
@endpush