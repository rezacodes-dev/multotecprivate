@extends('dashboard.layouts.app')

@push('page_css')
 
 
<link rel="stylesheet" href="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.css') }}">
@endpush

@section('content_header')
<section class="content-header">
  <h1>
    @if(isset($prodCat))
    Edit Event
    @else
    Add Event
    @endif
    <!--small>it all starts here</small-->
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{ route('alleventManagement') }}"><i class="fa fa-dashboard"></i> Home</a></li>
    <li><a href="{{ route('alleventManagement') }}">All Events</a></li>
    @if(isset($prodCat))
    <li class="active">Edit Event</li>
    @else
    <li class="active">Add Event</li>
    @endif
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
      <a href="{{ route('alleventManagement') }}" class="btn btn-primary"> All Events</a>
      @if(isset($prodCat))
      <a href="{{ url($prodCat->event_url) }}" target="_blank" class="btn btn-primary"> View Registration Link</a>
      @endif
    </div>
    <div class="col-md-6">
    </div>
  </div>
  <div class="row" style="margin-top: 10px;">
    <div class="col-md-12">
      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">@if(isset($prodCat)) Edit Event @else Add Event @endif</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <form name="jfrm" id="frmx" action="@if( isset($prodCat)){{ route('updateEventManagement', array('id' => $content_id)) }}@else{{ route('saveEventManagement') }}@endif" method="post" enctype="multipart/form-data">
          {{ csrf_field() }}

          
          <div class="row">

          @if( isset($prodCat) ) 
              <input type="hidden" id="table_id" name="table_id" value="{{ $prodCat->id }}"> 
              @else 
              <input type="hidden" id="table_id" value="0" name="table_id"> 
              @endif

            <div class="col-md-10">
              <div class="form-group">
                <label>Event Name : <em>*</em></label>
                <input type="text" name="name"  id="name" class="form-control" placeholder="Enter Event Name " value="@if( isset($prodCat) ){{ $prodCat->name }}@endif">
                <span id="nameError" style="color: red;"></span><br><br>
              </div> 
              </div>


              <div class="col-md-10">
              <div class="form-group">
                <label>Event Slug : <em>*</em></label>
                <input type="text" readonly name="slug" id="slug" class="form-control"  value="@if( isset($prodCat) ){{ $prodCat->slug }}@endif">
                <span id="slugError" style="color: red;"></span><br><br>
              </div> 
              </div>

             <div class="col-md-10">
              <div class="form-group">
                <label>Event Type : <!-- <em>*</em> --></label>
                <select class="form-control" name="event_type_id" id="event_type" >
                  <option value="">-Select Event Type-</option>
                  
                   @foreach($eventManagementType as $eventManagementType)
                  <option 
                  @if(isset($prodCat) && $eventManagementType->id == $prodCat->event_type_id) selected @endif
                  value="{{$eventManagementType->id}}">{{$eventManagementType->name}}</option>
                   @endforeach
                </select>
           
                    <span id="eventTypeError" style="color: red;"></span><br><br>
              </div> 
              </div>
              <div class="col-md-10">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="singleCheck" value="" id="flexCheckChecked"  @if(isset($prodCat) && empty($prodCat->event_end_date)) 
                 checked
               @else
               
                 @endif>
                  <label class="form-check-label" for="flexCheckChecked">
                   Single Day Event
                  </label>
                </div>
                </div>
              <div class="col-md-10" >
              <div class="form-group">
                <label>Event Start Date : <em>*</em></label>
                <input type="date" name="event_start_date"  id="event_start_date" class="form-control"  value="@if( isset($prodCat) ){{ $prodCat->event_start_date }}@endif">
                <span id="startDateError" style="color: red;"></span><br><br>
              </div> 
              </div>

              <div class="col-md-10" id="end_date_hide">
              <div class="form-group">
                <label>Event End Date : <em>*</em></label>
                <input type="date" name="event_end_date"  id="event_end_date" class="form-control"  value="@if( isset($prodCat) ){{ $prodCat->event_end_date }}@endif">
                <span id="endDateError" style="color: red;"></span><br><br>
              </div> 
              </div>  

            

              <div class="col-md-10">
                <div class="form-group">
                  <label>Select Region : </label>
                  <select class="form-control" name="region_id" id="region">
                    <option value="">Select Region</option>
                    @foreach($regions as $region)
                      <option value="{{ $region->id }}"
                        @if(isset($prodCat) && $region->id == $prodCat->region_id) selected @endif
                        >{{ $region->continent_name }}</option>
                       @endforeach
                  </select>
                  <span id="regionError" style="color: red;"></span><br><br>
                </div> 
              </div>
              
              <div class="col-md-10">
                <div class="form-group">
                  <label>Select Country: </label>
                  <select class="form-control" name="country_id" id="country">
                    <option value="">All</option>
                  </select>
                </div> 
              </div>
              
              <div class="col-md-10">
                <div class="form-group">
                  <label>Event Link : </label>
                  <input type="text" name="event_link" id="event_link" class="form-control" placeholder="Event Link" value="@if( isset($prodCat) ){{ $prodCat->event_url}}@endif" >
                  <span id="eventLinkError" style="color: red;"></span><br><br>
                </div> 
                </div>  

           

          
 
              <div class="col-md-10">
              <div class="form-group">
                <label>Image : <em>*</em></label>
                <input type="file" name="image" id="image" class="form-control" >
                <span id="imageError" style="color: red;"></span><br><br>
                <span style="color: red;">
                  @if ($errors->any())
                      {{ $errors->first() }}
                  @endif
              </span>
                @if( isset($prodCat) )

                    @if($prodCat->image != '' && $prodCat->image != null)
                      @php
                      $imageURL = asset('public/uploads/event_images/thumb/'.$prodCat->image);
                      @endphp
                      <img src="{{ $imageURL }}" id="user_image_preview" class="ar_img_preview" data="{{ $imageURL }}">
                    @else
                      <!-- <img src="{{ asset('public/images/user-avatar.png') }}" id="user_image_preview" class="ar_img_preview" 
                      data="{{ asset('public/images/user-avatar.png') }}"> -->
                    @endif
                   
                @endif    
              </div> 
              </div>

              <div class="col-md-10">
                <div class="form-group">
                  <label>Description : <em>*</em></label>
                  <textarea  name="description" required id="description" class="form-control" placeholder="Enter Description " >@if( isset($prodCat) ){{ $prodCat->description }}@endif</textarea>
                </div> 
                </div>
  

              <div class="col-md-10">
             
              <input type="submit" class="btn btn-primary" value="Save"> 
              </div> 
 

              </div>
          
          </form>
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
$(document).ready(function() {


})
<script>
  $(document).ready(function () {
    // Bind the change event to the checkbox
    $('#singleCheck').on('change', function () {
      if ($(this).is(':checked')) {
        // If checkbox is checked, hide the End Date div and set the input value to null
        $('#end_date_hide').hide();
        $('#event_end_date').val(null);
      } else {
        // If checkbox is not checked, show the End Date div
        $('#end_date_hide').show();
      }
    });

    // Trigger the change event once on document load to handle the initial state
    $('#singleCheck').trigger('change');
  });
</script>
<script src="{{ asset('public/assets/jquery_ui/jquery-ui.js') }}"></script>
<script src="{{ asset('public/assets/shortable/Sortable.min.js') }}"></script>
<script src="{{ asset('public/assets/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('public/assets/bs_multi_select/bootstrap-multiselect.js') }}"></script>

<script type="text/javascript">
//my script

$(document).ready(function() {
      // Function to validate the form
      function validateForm() {
        var name = $("#name").val();
        var slug = $("#slug").val();
   
        var eventLink = $("#event_link").val();
        var eventType = $("#event_type").val();
        var region = $("#region").val();
        var image = $("#image").val();
        var startDate = $("#event_start_date").val();
        var endDate = $("#event_end_date").val();
        var isValid = true;
        
        if (name === "") {
          $("#nameError").text("Please enter your name.");
          isValid = false;
        } else {
          $("#nameError").text("");
        }
        if (region === "") {
          $("#regionError").text("Please Enter Your Region.");
          isValid = false;
        } else {
          $("#regionError").text("");
        }
        
      
       
        
        if (eventLink === "") {
          $("#eventLinkError").text("Please enter an event link.");
          isValid = false;
        } else {
          $("#eventLinkError").text("");
        }
        
        if (startDate === "") {
          $("#startDateError").text("Please enter a Start Date.");
          isValid = false;
        } else {
          $("#startDateError").text("");
        }

    
        // Check file size
       // Check file size and type
  if (image !== "") {
    var fileSize = $("#image")[0].files[0].size; // Get the file size in bytes
    var maxSize = 200000; // 200 KB in bytes
    
    var fileExtension = image.split('.').pop().toLowerCase();
    var validExtensions = ["jpg", "jpeg", "png", "gif"]; // Valid image extensions
    

    if (fileSize > maxSize) {
      $("#imageError").text("File size should be less than 200KB");
      isValid = false;
    } else if (!validExtensions.includes(fileExtension)) {
      $("#imageError").text("Only JPG, JPEG, PNG, and GIF files are allowed.");
      isValid = false;
    } else {
      $("#imageError").text("");
    }
  }

  if (eventType === "") {
    $("#eventTypeError").text("Please select an option."); // Replace "selectBoxError" with the actual ID of the error message span
    isValid = false;
  } else {
    $("#eventTypeError").text("");
  }
        // Check event dates

        
  if (startDate !== "" && endDate !== "") {
   
    if (new Date(startDate) > new Date(endDate)) {
      $("#endDateError").text("Start date cannot be greater than end date."); // Replace "dateError" with the actual ID of the error message span
      isValid = false;
    } else {
      $("#endDateError").text("");
    }
  } 
        // Add additional validation rules if needed
        
        return isValid;
      }
      
      // Attach form submit event handler
      $("#frmx").submit(function() {
        return validateForm();
      });
      
      // Attach input event handlers for real-time validation
      $("#name").on("input", function() {
        $("#nameError").text("");
      });
      
      $("#slug").on("input", function() {
        $("#slugError").text("");
      });
      
  
      
      $("#event_link").on("input", function() {
        $("#eventLinkError").text("");
      });
      
      $("#image").on("input", function() {
        $("#imageError").text("");
      });
      $("#event_type").on("input", function() {
        $("#eventTypeError").text("");
      });

      $("#event_start_date").on("input", function() {
        $("#startDateError").text("");
      });

      $("#event_end_date").on("input", function() {
        $("#endDateError").text("");
      });
      
    });


// end my script

$( function() {
 
  $('#name').on('blur', function() {
    if( $.trim( $(this).val() ) != '' ) {
      $('#slug').val( string_to_slug( $(this).val() ) );
    }
  });
  
});


function string_to_slug(str) {
  str = str.replace(/^\s+|\s+$/g, "");
  str = str.toLowerCase();
  var from = "åàáãäâèéëêìíïîòóöôùúüûñç·/_,:;";
  var to = "aaaaaaeeeeiiiioooouuuunc------";
  for (var i = 0, l = from.length; i < l; i++) {
    str = str.replace(new RegExp(from.charAt(i), "g"), to.charAt(i));
  }
  str = str
    .replace(/[^a-z0-9 -]/g, "") // remove invalid chars
    .replace(/\s+/g, "-") // collapse whitespace and replace by -
    .replace(/-+/g, "-") // collapse dashes
    .replace(/^-+/, "") // trim - from start of text
    .replace(/-+$/, ""); // trim - from end of text
  return str;
}



$( function() {
  $('body .pgb_rightControl #pageBuilderBtn').on('click', function() {
    $('.pgb_rightControl .cdiv').toggle('slide', { direction:'right' }, 200);
  });
} );
var editor_pg_cont = CKEDITOR.replace( 'description', {
  customConfig: "{{ asset('public/assets/ckeditor/maxi_config.js') }}",
} );


var editor_pg_cont = CKEDITOR.replace( 'short_description', {
  customConfig: "{{ asset('public/assets/ckeditor/maxi_config.js') }}",
} );




$( function() {
  <?php //if( !isset($prodCat) || ( isset($prodCat) && $prodCat->is_duplicate == '1') ) { ?>
  $('#scName').on('blur', function() {
    if( $.trim( $(this).val() ) != '' ) {
      $('#pgSlug').val( string_to_slug( $(this).val() ) );
    }
  });
  <?php //} ?>
});
function string_to_slug(str) {
  str = str.replace(/^\s+|\s+$/g, "");
  str = str.toLowerCase();
  var from = "åàáãäâèéëêìíïîòóöôùúüûñç·/_,:;";
  var to = "aaaaaaeeeeiiiioooouuuunc------";
  for (var i = 0, l = from.length; i < l; i++) {
    str = str.replace(new RegExp(from.charAt(i), "g"), to.charAt(i));
  }
  str = str
    .replace(/[^a-z0-9 -]/g, "") // remove invalid chars
    .replace(/\s+/g, "-") // collapse whitespace and replace by -
    .replace(/-+/g, "-") // collapse dashes
    .replace(/^-+/, "") // trim - from start of text
    .replace(/-+$/, ""); // trim - from end of text
  return str;
}
</script>

<script>
  $(document).ready(function() {
    var selectedCountryId = '{{ $prodCat->country_id ?? '' }}'; 
    function fetchCountriesByRegion(regionId) {
      $.ajax({
        url: "{{ route('ajaxgetCountries') }}", 
        type: 'GET',
        data: { region_id: regionId },
        success: function(data) {
   
          $('#country').empty();
          $('#country').append('<option value="">All</option>');
       
          $.each(data, function(index, country) {
            $('#country').append('<option value="' + country.id + '" ' + (selectedCountryId == country.id ? 'selected' : '') + '>' + country.country_name + '</option>');
          });
        },
        error: function(xhr, status, error) {
   
          console.error(error);
        }
      });
    }

    // Handle the region select change event
    $('#region').on('change', function() {
      var regionId = $(this).val();
      if (regionId !== '') {
        // Fetch countries based on the selected region
        fetchCountriesByRegion(regionId);
      } else {
        // If no region is selected, clear the country options
        $('#country').empty();
        $('#country').append('<option value="">All</option>');
      }
    });

 
    if (selectedCountryId !== '') {
      var regionId = $('#region').val();
      if (regionId !== '') {
        fetchCountriesByRegion(regionId);
      }
    }
    if (selectedCountryId == '') {
      var regionId = $('#region').val();
      if (regionId !== '') {
        fetchCountriesByRegion(regionId);
      }
    }
  });
</script>


 

@endpush