@extends('front_end.layout.layout_master')
@include('front_end.structure.page_meta')



@section('page_content')
@if( isset($allData) && !empty($allData) )

@php
    $banner = getProductBanner($allData->id);
@endphp
@if( isset($banner) && !empty($banner) )
<section class="innerpage-banner">
    <img src="{{ asset('public/uploads/files/media_images/'. $banner['image']) }}" alt="{{$banner['alt_tag']}}" 
    title="{{$banner['title']}}" caption="{{$banner['caption']}}">
</section>
@endif


<section class="container">
    <div class="breadcrumb"> <!-- Breadcrumb Segment -->
        <ul>
            <li><a href="{{ url('/') }}">Home    <input type="hidden" name="r1" id="r1" value="<?php echo $referral;?>"> </a></li>
            @if( isset($allData->myParent) && !empty($allData->myParent) )
            <li><a href="{{ route('front_cms_page', array('lng' => $lng, 'slug' => $allData->myParent->slug)) }}">{{ ucfirst($allData->myParent->name) }}</a></li>
            @endif
            <li>{{ $allData->name }}</li>
        </ul>
    </div>
</section>

@include('front_end.structure.page_builder')

@endif
@endsection
 
@push('page_js')
<script type="text/javascript" src="{{ asset('public/front_end/js/ddaccordion.js') }}"></script>
<script type="text/javascript">
//Initialize 2nd demo:
ddaccordion.init({
  headerclass: "accor_heading", //Shared CSS class name of headers group
  contentclass: "accor_body", //Shared CSS class name of contents group
  revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
  mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
  collapseprev: false, //Collapse previous content (so only one open at any time)? true/false 
  defaultexpanded: [true], //index of content(s) open by default [index1, index2, etc]. [] denotes no content.
  onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
  animatedefault: false, //Should contents open by default be animated into view?
  scrolltoheader: false, //scroll to header each time after it's been expanded by the user?
  persiststate: false, //persist state of opened contents within browser session?
  toggleclass: ["closed_arrow", "open_arrow"], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
  togglehtml: ["prefix", "<img src='{{ asset('public/front_end/images/arrow_down_accor.png') }}' style='width:24px; height:24px' /> ", "<img src='{{ asset('public/front_end/images/arrow_up_accor.png') }}' style='width:24px; height:24px' /> "],  //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
  animatespeed: "normal", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
  oninit:function(expandedindices){ //custom code to run when headers have initalized
    //do nothing
  },
  onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
    //do nothing
  }
});


$(document).ready(function(){
  $(".form-controln").click(function(){
    $("#referral").val($("#r1").val());
  });
});


</script>
<script>
  $(document).ready(function () {
    // Fetch the country data via AJAX
    $.ajax({
        url: "{{route('getCountries')}}", // The Laravel route to fetch countries
        method: 'GET',
        success: function (response) {
            console.log("Response:", response);
            // Populate the country dropdown
            let countryDropdown = $("select[name='country_bdf6b0663c3d12b26de9d64a0331d39f']");
            countryDropdown.empty(); // Clear existing options
            countryDropdown.append('<option value="">Select Country</option>'); // Add default option

            $.each(response, function (index, country) {
                countryDropdown.append(
                    `<option value="${country.country_name}" data-dialing-code="${country.dialing_code}">${country.country_name}</option>`
                );
            });
                  // If a country is already selected, update the phone number field with the dialing code
        let selectedDialingCode = countryDropdown.val();
            if (selectedDialingCode) {
                $("input[name='contactno_07351a4ae50ef96a1c50a5cc650473f3']").val(selectedDialingCode);
            }
        },
        error: function (xhr, status, error) {
            console.error('Error fetching countries:', error);
        }
    });

    // Update the phone number field with the selected dialing code
    $("select[name='country_bdf6b0663c3d12b26de9d64a0331d39f']").on('change', function () {
        let selectedDialingCode = $(this).find(':selected').data('dialing-code') || '';
        $("input[name='contactno_07351a4ae50ef96a1c50a5cc650473f3']").val(selectedDialingCode);
    });
});



// $(document).ready(function () {
//     // Automatically select the first option in the dropdown and toggle fields
//     $("select[name='iwanttoenquireabout_6ff314aae2564ef958f6739b4b07e185']").each(function () {
//         // $(this).attr('size', '4'); 
//         $(this).val(''); // Set the default option
//     });

//     // Trigger the visibility logic based on the default selection
//     toggleFieldsBasedOnSelection();

//     // Handle change event for the dropdown
//     $(document).on('change', "select[name='iwanttoenquireabout_6ff314aae2564ef958f6739b4b07e185']", function () {
//         toggleFieldsBasedOnSelection();
//     });

//     // Function to toggle the visibility of fields based on the selected option
//     function toggleFieldsBasedOnSelection() {
//         $("select[name='iwanttoenquireabout_6ff314aae2564ef958f6739b4b07e185']").each(function () {
//             var selectedOption = $(this).val();
//             var container = $(this).closest('.form, .modal'); // Adjust for context (main div or modal)

//             // Hide all fields initially within the specific container
//             container.find('#field_16').hide();
//             container.find('#field_23').hide();
//             container.find('#field_3').hide();
//             container.find('#field_4').hide();
//             container.find('#field_17').hide();
//             container.find('#field_5').hide();
//             container.find('#field_19').hide();
//             container.find('#field_24').hide();
//             container.find('#terms_f1b78704ea2449a379eaaf6c129751cb').hide();
//             container.find('#box2').next('div').hide();
//             container.find('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').css('display', 'none');
//             container.find('.custom-file-upload').css('display', 'none');

//             // Logic for specific selections
//             if (selectedOption === '1.-Mineral-processing-products-and-services') {
//                 container.find('#field_16').show();
//                 container.find('#field_23').show();
//                 container.find('#field_3').show();
//                 container.find('#field_4').show();
//                 container.find('#field_17').show();
//                 container.find('#field_5').show();
//                 container.find('#field_24').show();
//                 container.find('.custom-file-upload').css('display', 'block');
//                 container.find('#field_19').show();
//                 container.find('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').val('Request a quote');
//                 container.find('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').css('display', 'block');
//                 container.find('#box1').hide();
//                 container.find('#box2').hide();
//                 container.find('#box2').next('div').show();
//             } else if (selectedOption === '4.-Other') {
//                 container.find('#field_16').show();
//                 container.find('#field_23').show();
//                 container.find('#field_3').show();
//                 container.find('#field_4').show();
//                 container.find('#field_17').show();
//                 container.find('#field_5').show();
//                 container.find('#field_24').show();
//                 container.find('.custom-file-upload').css('display', 'block');
//                 container.find('#field_19').show();
//                 container.find('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').val('Submit Enquiry');
//                 container.find('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').css('display', 'block');
//                 container.find('#box1').hide();
//                 container.find('#box2').hide();
//                 container.find('#box2').next('div').show();
//             } else if (selectedOption === '2.-Job-applications') {
//                 container.find('#field_19').show();
//                 container.find('#box1').show();
//                 container.find('#box2').hide();
//                 container.find('#box2').next('div').hide();
//             } else if (selectedOption === '3.-Training-Opportunities') {
//                 container.find('#field_19').show();
//                 container.find('#box1').hide();
//                 container.find('#box2').show();
//                 container.find('#box2').next('div').hide();
//                 window.open("https://www.multotec.com/en/training", "_blank");
//             } else {
//                 container.find('#field_19').hide();
//                 container.find('#box2').next('div').hide();
//             }
//         });
//     }
// });

$(document).ready(function () {
    $("select[name='iwanttoenquireabout_6ff314aae2564ef958f6739b4b07e185'] option[value='']").attr("disabled", "disabled");

    // Hide all fields by default on page load
    $("select[name='iwanttoenquireabout_6ff314aae2564ef958f6739b4b07e185']").each(function () {
        var container = $(this).closest('.form, .modal'); // Adjust for context (main div or modal)
        
        // Hide all fields initially within the specific container
        // container.find('#field_16').hide();
        // container.find('#field_23').hide();
        // container.find('#field_3').hide();
        // container.find('#field_4').hide();
        // container.find('#field_17').hide();
        // container.find('#field_5').hide();
        // container.find('#field_19').hide();
        // container.find('#field_24').hide();
        // container.find('#terms_f1b78704ea2449a379eaaf6c129751cb').hide();
        // container.find('#box2').next('div').hide();
        // container.find('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').css('display', 'none');
        // container.find('.custom-file-upload').css('display', 'none');
    });

    // Handle change events for the select element
    $(document).on('change', "select[name='iwanttoenquireabout_6ff314aae2564ef958f6739b4b07e185']", function () {
        // Remove the option with an empty value
        // $("select[name='iwanttoenquireabout_6ff314aae2564ef958f6739b4b07e185'] option[value='']").remove();
        toggleFieldsBasedOnSelection();
    });

    // Call toggle function to handle default selection
    toggleFieldsBasedOnSelection();

    // Function to toggle the visibility of fields based on the selected option
    function toggleFieldsBasedOnSelection() {
        $("select[name='iwanttoenquireabout_6ff314aae2564ef958f6739b4b07e185']").each(function () {
            var selectedOption = $(this).val();
            var container = $(this).closest('.form, .modal'); // Adjust for context (main div or modal)
            

            // Hide all fields initially within the specific container
            // container.find('#field_16').hide();
            // container.find('#field_23').hide();
            // container.find('#field_3').hide();
            // container.find('#field_4').hide();
            // container.find('#field_17').hide();
            // container.find('#field_5').hide();
            // container.find('#field_19').hide();
            // container.find('#field_24').hide();
            // container.find('#terms_f1b78704ea2449a379eaaf6c129751cb').hide();
            // container.find('#box2').next('div').hide();
            // container.find('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').css('display', 'none');
            // container.find('.custom-file-upload').css('display', 'none');

            // Logic for specific selections
            if (selectedOption === '1.-Mineral-processing-products-and-services') {
                container.find('#field_16').show();
                container.find('#field_23').show();
                container.find('#field_3').show();
                container.find('#field_4').show();
                container.find('#field_17').show();
                container.find('#field_5').show();
                container.find('#field_24').show();
                container.find('.custom-file-upload').css('display', 'block');
                container.find('#field_19').show();
                container.find('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').val('Request a quote');
                container.find('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').css('display', 'block');
                container.find('#box1').hide();
                container.find('#box2').hide();
                container.find('#box2').next('div').show();
                $('.g-recaptcha').css('display','block');
            } 
            else if (selectedOption === '4.-Other') {
                container.find('#field_16').show();
                container.find('#field_23').show();
                container.find('#field_3').show();
                container.find('#field_4').show();
                container.find('#field_17').show();
                container.find('#field_5').show();
                container.find('#field_24').show();
                container.find('.custom-file-upload').css('display', 'block');
                container.find('#field_19').show();
                container.find('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').val('Submit Enquiry');
                container.find('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').css('display', 'block');
                container.find('#box1').hide();
                container.find('#box2').hide();
                container.find('#box2').next('div').show();
                $('.g-recaptcha').css('display','block');
            } else if (selectedOption === '2.-Job-applications') {
              //  container.find('#field_19').show();
              container.find('#field_16').hide();
            container.find('#field_23').hide();
            container.find('#field_3').hide();
            container.find('#field_4').hide();
            container.find('#field_17').hide();
            container.find('#field_5').hide();
            container.find('#field_19').hide();
            container.find('#field_24').hide();
            container.find('#terms_f1b78704ea2449a379eaaf6c129751cb').hide();
            container.find('#box2').next('div').hide();
            container.find('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').css('display', 'none');
            container.find('.custom-file-upload').css('display', 'none');
            $('.g-recaptcha').hide()
                container.find('#box2').next('div').hide();
                window.open("https://www.careers24.com/now-hiring/9744-multotec-pty-ltd/", "_blank");
            } else if (selectedOption === '3.-Training-Opportunities') {
                  container.find('#field_16').hide();
            container.find('#field_23').hide();
            container.find('#field_3').hide();
            container.find('#field_4').hide();
            container.find('#field_17').hide();
            container.find('#field_5').hide();
            container.find('#field_19').hide();
            container.find('#field_24').hide();
            container.find('#terms_f1b78704ea2449a379eaaf6c129751cb').hide();
            container.find('#box2').next('div').hide();
            container.find('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').css('display', 'none');
            container.find('.custom-file-upload').css('display', 'none');
            $('.g-recaptcha').hide()
                container.find('#box2').next('div').hide();
                window.open("https://www.multotec.com/en/training", "_blank");
            } else {
                container.find('#field_19').hide();
                container.find('#box2').next('div').hide();
            }
        });
    }
});



</script>
<!-- <script>
$('input[name="ok_d5d8517aae26dc072e04284ffdd0d267"]').on('click', function(event) {
    const selectElement = $('select[name="iwanttoenquireabout_6ff314aae2564ef958f6739b4b07e185"]');
    const selectedOption = selectElement.find('option:selected');
    console.log(selectElement.val());
    // Validation
    if (!selectElement.val() || selectElement.val() === "") {
        event.preventDefault(); // Prevent the form from submitting
        alert('Please select a Service Option.');
    } else {
        // The form is valid
        console.log('Form is valid. Submitting...');
        // Allow form submission
    }
});




</script> -->
@endpush

    