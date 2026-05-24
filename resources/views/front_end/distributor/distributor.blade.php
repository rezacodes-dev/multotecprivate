@extends('front_end.layout.layout_master')
@include('front_end.structure.page_meta')


@section('page_content')
<style>
    .open-email-modal {
    cursor: pointer;
}
.fbg * {
    color: #fff !important;
}
#desktop_eform_modal_email h2.sph2 {
    background: #008d5c;
    margin: 0;
    color: #fff;
    text-align: center;
    padding: 15px;
    font-size: 24px !important;
    font-weight: 500 !important;
    line-height: 29px !important;
}
#desktop_eform_modal_email h2.sph2 span {
    display: block;
    font-weight: 300;
    font-size: 20px;
}
</style>
<input type="hidden" id="mLat" name="mLat" value="{{ $allData->latitude??'' }}">
<input type="hidden" id="mLng" name="mLng" value="{{ $allData->longitude??'' }}">
    @if(isset($allData) && !empty($allData))

      
        @php $distCat = getDistCategory($allData->id); @endphp

        <section class="container">
            <div class="breadcrumb" style="margin-top: 20px;"> <!-- Breadcrumb Segment -->
                <ul>
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ route('front.distrbMap', array('lng' => $lng)) }}">Location</a></li>
                    @if(isset($allData->distrCategorytOne) && isset($allData->distrCategorytOne->catInfo))
                    <li>
                      <a href="{{ route('front.distrbCat', array('lng' => $lng, 'catslug' => $allData->distrCategorytOne->catInfo->slug)) }}">
                        {{ $allData->distrCategorytOne->catInfo->name }}
                      </a>
                    </li> 
                    @endif
                    <li class="active">{{$allData->name}}</li>
                </ul>
            </div>
              <div class="modal fade" id="desktop_eform_modal_email" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2email">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content modal-bacg">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 greenbg">
                                <div class="fbg">
                                    <h3>Get in touch with Multotec</h3>
                                    <div class="frm-data-cbox">
                                        <p style="text-align:left; font-size:19px; padding-bottom:28px;"><span>Our engineers and
                                                metallurgists will help you process minerals faster and more efficiently.</span>
                                        </p>

                                        <ul>
                                            <li>
                                                <p style="text-align:left;"><span style="font-size:21px; font-weight:500;">Full
                                                        range of process equipment</span><br />
                                                    <span style="font-size:17px;">to optimise your mineral processing
                                                        plant</span>
                                                </p>
                                            </li>
                                            <li>
                                                <p style="text-align:left"><span style="font-size:21px; font-weight:500;">Large
                                                        stockholdings &amp; fast delivery</span><br />
                                                    <span style="font-size:17px;">of equipment and spares to support your plant
                                                    </span>
                                                </p>
                                            </li>
                                            <li>
                                                <p style="text-align:left"><span
                                                        style="font-size:21px; font-weight:500;">24-hour field services,
                                                    </span><br />
                                                    <span style="font-size:17px;">technical and maintenance support</span>
                                                </p>
                                            </li>
                                            <li>
                                                <p style="text-align:left"><span
                                                        style="font-size:21px; font-weight:500;">Metallurgical &amp; engineering
                                                        support</span><br />
                                                    <span style="font-size:17px;">to optimise your process and plant</span>
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 dsk-modal-frm">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                                <div class="dsk-modal-frmright">
                                    <h2 class="sph2">Need more info?<span>Contact Multotec</span></h2>
                                    <div class='ar_frm_container' style='background-color:#eaeaea; color:#000000;'>
                                        <form name='general' action='https://www.multotec.com/arindam-form-submit' method='post'
                                            class='ar_vali_class ' enctype='multipart/form-data'><input type='hidden'
                                                name='receive_email[]' value='heathl@cubicice.com' /><input type='hidden'
                                                name='receive_email[]' value='KoenaL@multotec.com' /><input type='hidden'
                                                name='receive_email[]' value='AnnahV@multotec.com' /><input type='hidden'
                                                name='receive_email[]' value='VivienneM@multotec.com' /><input type='hidden'
                                                name='receive_email[]' value='tarryn@cubicice.com' /><input type='hidden'
                                                name='receive_email[]' value='heatherr@Multotec.com' />
                                            <div class='row fd_box' id='field_22'>
                                                <div class='col-md-12 col-sm-12'>
                                                    <div class='form-group'><label>I want to enquire about :</label> <em>*</em>
                                                        <select name='iwanttoenquireabout_6ff314aae2564ef958f6739b4b07e185'
                                                            required class='form-control'>
                                                            <option value=''>I want to enquire about:</option>
                                                            <option value='1.-Mineral-processing-products-and-services'>1.
                                                                Mineral processing products and services</option>
                                                            <option value='2.-Job-applications'>2. Job applications</option>
                                                            <option value='3.-Training-Opportunities'>3. Training Opportunities
                                                            </option>
                                                            <option value='4.-Other'>4. Other</option>
                                                        </select>
                                                        <div id='ed_action_box_22'></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='row fd_box' id='field_24'>
                                                <div class='col-md-12 col-sm-12'>
                                                    <div class='form-group'><label>Country :</label> <em>*</em> <select
                                                            name='country_bdf6b0663c3d12b26de9d64a0331d39f' required
                                                            class='form-control'>
                                                            <option value=''>Select Country</option>
                                                            <option value='USA'>USA</option>
                                                        </select>
                                                        <div id='ed_action_box_24'></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='row fd_box' id='field_16'>
                                                <div class='col-md-12 col-sm-12'>
                                                    <div class='form-group'><label>Name :</label> <em>*</em> <input type='text'
                                                            name='name-full_e07b31bd420ff4b70e17fe441e78461b' placeholder='Name'
                                                            required class='form-control' />
                                                        <div id='ed_action_box_16'></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='row fd_box' id='field_3'>
                                                <div class='col-md-12 col-sm-12'>
                                                    <div class='form-group'><label>Your Email-id :</label> <em>*</em> <input
                                                            type='email' name='email_61226fd4585429e623016599e6fb44e1'
                                                            placeholder='Email:' required class='form-control' />
                                                        <div id='ed_action_box_3'></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='row fd_box' id='field_4'>
                                                <div class='col-md-12 col-sm-12'>
                                                    <div class='form-group'><label>Phone Number :</label> <em>*</em> <input
                                                            type='text' name='contactno_07351a4ae50ef96a1c50a5cc650473f3'
                                                            placeholder='Phone:' required class='form-control onlyPHNO' />
                                                        <div id='ed_action_box_4'></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='row fd_box' id='field_17'>
                                                <div class='col-md-12 col-sm-12'>
                                                    <div class='form-group'><label>Company :</label> <em>*</em> <input
                                                            type='text' name='company_8d9f1569b3d5a8fba1a5463bc280b601'
                                                            placeholder='Company' required class='form-control' />
                                                        <div id='ed_action_box_17'></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='row fd_box' id='field_5'>
                                                <div class='col-md-12 col-sm-12'>
                                                    <div class='form-group'><label>Your Requirements :</label> <textarea
                                                            name='requirements_8fa7670f330845d9f75c72ef098ad774'
                                                            placeholder='What equipment or solutions does your mineral processing operation require from Multotec?'
                                                            class='form-control'></textarea>
                                                        <div id='ed_action_box_5'></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='row fd_box' id='field_19'>
                                                <div class='col-md-12 col-sm-12'>
                                                    <div class='form-group'><label>Acceptance Info :</label> <br />
                                                        <p style='font-size:13px!important; '><input type='checkbox'
                                                                name='terms_f1b78704ea2449a379eaaf6c129751cb[]' class='ar-ckb'
                                                                value='I-agree-to-receive-Multotec-training-and-event-info'> I
                                                            agree to receive Multotec training and event info</p>
                                                        <div id='ed_action_box_19'></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='row fd_box' id='field_6'>
                                                <div class='col-md-12 col-sm-12'>
                                                    <div class='form-group'><label>Upload :</label> <label
                                                            class='custom-file-upload'><i class='fa fa-upload'
                                                                aria-hidden='true'></i> Upload supporting documents
                                                            (optional)<input type='file'
                                                                name='upload_fba14c8b01e43a8e2c25745ee78746df'
                                                                style='display:none;' /></label>
                                                        <div id='ed_action_box_6'></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='form-group'>
                                                <div class='g-recaptcha mt5 mb5'
                                                    data-sitekey='6LfRP74UAAAAAB3GY81dorfwC6HLGoNyG69DUI8n'></div>
                                            </div>
                                            <div class='ar-captcha-vali'></div>
                                            <div class='row fd_box btnf' id='field_1'>
                                                <div class='col-md-12 col-sm-12'>
                                                    <div class='form-group'><input type='submit'
                                                            name='ok_d5d8517aae26dc072e04284ffdd0d267' value='Request a quote'
                                                            class='submit-btn' style='' />
                                                        <div id='ed_action_box_1'></div>
                                                    </div>
                                                </div>
                                            </div><input type='hidden' name='referral' id='referral'><input type='hidden'
                                                name='ar_frm_id' value='d5d8517aae26dc072e04284ffdd0d267'><input type='hidden'
                                                name='thankyou_url'
                                                value='https://www.multotec.com/en/thank-you-for-contacting-multotec'>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
 

<script>
$(document).on('click', '#firstBlock a[href]:not(#landing), .open-email-modal', function (e) {

    e.preventDefault();

    // fetch ONLY visible anchor text
    let email = $.trim($(this).text());

    // fallback if text empty
    if (email === '') {

        email = $(this).data('email') || '';
    }

    // modal
    let modal = $('#desktop_eform_modal_email');

    // form
    let form = modal.children().find('form').first();

    // hidden email field
    let hiddenInput = form.find('input[name="selected_email"]');

    if (hiddenInput.length === 0) {

        $('<input>', {
            type: 'hidden',
            name: 'selected_email',
            value: email
        }).appendTo(form);

    } else {

        hiddenInput.val(email);
    }

    // country select
    let countrySelect = form.find(
        'select[name="country_bdf6b0663c3d12b26de9d64a0331d39f"]'
    ).first();

    // clear previous selection
    countrySelect.val('');

    /*
    |--------------------------------------------------------------------------
    | Fetch country using latitude / longitude
    |--------------------------------------------------------------------------
    */

    if ($('#mLat').length && $('#mLng').length) {

        let getMLat = $('#mLat').val();
        let getMLng = $('#mLng').val();

        if (getMLat !== '' && getMLng !== '') {

            $.getJSON(
                'https://maps.googleapis.com/maps/api/geocode/json?latlng=' 
                + getMLat + ',' + getMLng 
                + '&key=AIzaSyC1ctyLhYi1UVzqbsc1fLA6evrrdGWeoWs',
                function(response) {

                    if (response.status === 'OK') {

                        let detectedCountry = '';

                        $.each(response.results, function(i, result) {

                            $.each(result.address_components, function(j, component) {

                                if ($.inArray('country', component.types) !== -1) {

                                    detectedCountry = component.long_name;
                                    return false;
                                }

                            });

                            if (detectedCountry !== '') {

                                return false;
                            }

                        });

                        if (detectedCountry !== '') {

                            let matchedOption = countrySelect.find('option').filter(function () {

                                return $.trim($(this).text()).toLowerCase() === detectedCountry.toLowerCase();

                            });

                            if (matchedOption.length) {

                                countrySelect.val(matchedOption.val());
                                countrySelect.trigger('change');
                            }
                        }
                    }

                    modal.modal('show');

                }
            );

            return;
        }
    }

    // fallback
    modal.modal('show');

});
</script>

<script>
$(document).ready(function () {

    $('#desktop_eform_modal_email').on('hidden.bs.modal', function () {

        let form = $(this).children().find('form').first();

        // completely remove hidden field
        form.find('input[name="selected_email"]').remove();

    });

});
</script>
@endpush

    