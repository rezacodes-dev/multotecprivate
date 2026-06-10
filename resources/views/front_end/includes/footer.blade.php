
@php
    
    $bodyScript2 = getSEOscripts('before_body');
    if (!empty($bodyScript2)) {
        foreach ($bodyScript2 as $v) {
            echo html_entity_decode($v->script_code, ENT_QUOTES);
        }
    }
    
@endphp


<div class="ar-cp">
    <div class="container">
        <div class="row">
            <div class="col-md-10 txtc">
                We use cookies to improve your experience on our website. By using our site you agree to <a
                    class="cplink" href="https://www.multotec.com/en/privacy-policy-and-paia-act">Cookies Policy</a>
            </div>
            <div class="col-md-2">
                <a href="javascript:;" id="Agree" class="cpbtn btn btn-xs btn-primary">Agree</a>
                <a href="javascript:;" id="Disagree" class="cpbtn btn btn-xs btn-danger">Dismiss</a>
            </div>
        </div>
    </div>
</div>


<!-- jQuery should be loaded first and not deferred -->
<!-- <script src="{{ asset('public/front_end/js/jquery.min.js') }}"></script> -->

<!-- Defer other scripts -->
<script  src="{{ asset('public/front_end/js/jquery.min.js') }}" ></script>
<script src="{{ asset('public/assets/bower_components/bootstrap/dist/js/bootstrap.min.js') }}" defer></script>
<script src="{{ asset('public/assets/bower_components/select2/dist/js/select2.full.min.js') }}" defer></script>
<script src="{{ asset('public/front_end/js/owl.carousel.js') }}" defer></script>
@if (!request()->is('/'))
    <script src="{{ asset('public/assets/jquery_validator/jquery.validate.min.js') }}" defer></script>
    <script src="{{ asset('public/assets/jquery_validator/additional-methods.min.js') }}" defer></script>
@endif
<script src="{{ asset('public/assets/jquery_ui/jquery-ui.js') }}" defer></script>
<script src="{{ asset('public/front_end/js/arjs.js') }}" defer></script>
<script src="{{ asset('public/front_end/js/menuzord.js') }}" defer></script>

<script src="{{ asset('public/front_end/js/slick.min.js') }}" defer></script>




<script>
    $(document).ready(function($) {
        $('.slick.marquee').slick({
            speed: 5000,
            autoplay: true,
            autoplaySpeed: 0,
            centerMode: true,
            cssEase: 'linear',
            slidesToShow: 1,
            slidesToScroll: 1,
            variableWidth: true,
            infinite: true,
            initialSlide: 1,
            arrows: false,
            buttons: false
        });
    });
</script>
<script type="text/javascript">
    $(function() {
        if (getCookie("multo_sitecp") == '') {
            $('.ar-cp').show();
        }
        $('.togsrcbtn').on('click', function() {
            $('.searchtoggle').slideToggle();
        });
    });
</script>

<script type="text/javascript">
    $(function() {
        $('body').on('click', '.showhide', function() {
            $(this).removeClass('showhide').addClass('showhide2');
        });
        $('body').on('click', '.showhide2', function() {
            $(this).removeClass('showhide2').addClass('showhide');
        });
    });
</script>

<script type="text/javascript">
    $(document).ready(function() {
        function alignModal() {
            var modalDialog = $(this).find(".modal-dialog");
            /* Applying the top margin on modal dialog to align it vertically center */
            modalDialog.css("margin-top", Math.max(0, ($(window).height() - modalDialog.height()) / 2));
        }
        // Align modal when it is displayed
        $(".modal").on("shown.bs.modal", alignModal);

        // Align modal when user resize the window
        $(window).on("resize", function() {
            $(".modal:visible").each(alignModal);
        });
		/** Append notes on each form*/
        let notes ="<div class='col-md-12 col-sm-12' id='box1' style='display:none' ><div class='form-group' style='margin-bottom: 0px !important;' ><p style='font-size:13px!important; '><strong>Note</strong>  : No job applications will be accepted via the form. Please click <a href='https://www.careers24.com/now-hiring/9744-multotec-pty-ltd/' target='_blank;' data-kmt='1'>here</a> to view Multotec current vacancies.</div></div><div class='col-md-12 col-sm-12' id='box2' style='display:none'><div class='form-group' style='margin-bottom: 0px !important;'><p style='font-size:13px!important; '></div></div>";

        // $('.ar_vali_class ').prepend(notes);
        $('#field_19').prepend(notes);
        $('.dsk-modal-frm #field_19').before('<div class="row fd_box">'+notes+'</div>');
      //  $('#field_19,.dsk-modal-frm #field_19').find('br').hide();
        // $('#field_19,.dsk-modal-frm ').find('br').hide();
    });
</script>

<script>
    $(function() {
        $('.cpbtn').on('click', function() {
            var _cpID = $.trim($(this).attr('id'));
            setCookie('multo_sitecp', _cpID, 365);
            $('.ar-cp').fadeOut(600);
        });
    });

    function setCookie(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }
</script>
<script>
    $(document).ready(function(){
        $('#field_18').hide();
           $('.modal').on('shown.bs.modal', function () {
        $(this).find('#field_18').hide();
    });
    })
</script>
<script>
$(document).on('change', 'input[type="file"][name="upload_fba14c8b01e43a8e2c25745ee78746df"]', function () {

    let file = this.files[0];
    let parent = $(this).closest('.form-group');

    // form and submit button
    let form = $(this).closest('form');
    let submitBtn = form.find('button[type="submit"], input[type="submit"]');

    // remove old message
    parent.find('.file_message').remove();

    // append message container
    parent.append('<small class="file_message" style="display:block; margin-top:8px;"></small>');

    let messageBox = parent.find('.file_message');

    if (!file) {

        submitBtn.prop('disabled', false);
        submitBtn.css({
            'opacity': '1',
            'cursor': 'pointer'
        });

        return;
    }

    let maxSize = 2 * 1024 * 1024; // 2 MB

    if (file.size > maxSize) {

        messageBox.html(file.name + ' is larger than 2 MB.');
        messageBox.css('color', 'red');

        // disable submit
        submitBtn.prop('disabled', true);
        submitBtn.css({
            'opacity': '0.5',
            'cursor': 'not-allowed'
        });

        $(this).val('');

    } else {

        messageBox.html('');

        // enable submit
        submitBtn.prop('disabled', false);
        submitBtn.css({
            'opacity': '1',
            'cursor': 'pointer'
        });
    }
});
</script>
<script>
    $(document).on('hidden.bs.modal', '.modal', function () {

    $(this).find('form[name="general"]').each(function () {

        // reset form
        this.reset();

        // clear all inputs manually
        $(this).find('input[type="text"], input[type="email"], input[type="number"], input[type="tel"], textarea').val('');

        $(this).find('select').prop('selectedIndex', 0);

        $(this).find('input[type="checkbox"], input[type="radio"]').prop('checked', false);

        $(this).find('input[type="file"]').val('');

        // remove messages
        $(this).find('.file_message').remove();

        // enable submit buttons
        $(this)
            .find('button[type="submit"], input[type="submit"]')
            .prop('disabled', false)
            .css({
                'opacity': '1',
                'cursor': 'pointer'
            });

    });

});
</script>
<script>
    $(document).on('shown.bs.modal', '#desktop_eform_modal', function () {

    $(this).find('form[name="general"]').each(function () {

        // remove old hidden input if exists
        $(this).find('input[name="selected_email"]').remove();

        // append hidden input
        $(this).append(
            '<input type="hidden" name="selected_email" value="dummy@example.com">'
        );

    });

});
</script>
<script>
$(document).ready(function () {

    $('strong').filter(function () {
        return $.trim($(this).text()).toLowerCase().replace(':', '') === 'tel';
    }).each(function () {

        var nextNode = this.nextSibling;

        if (!nextNode || nextNode.nodeType !== 3) {
            return;
        }

        var phone = $.trim(nextNode.nodeValue);

        if (!phone) {
            return;
        }

        // Ignore if already linked
        if ($(this).next('a').length) {
            return;
        }

        var cleanPhone = phone.replace(/[^\d+]/g, '');

        var $link = $('<a>', {
            href: 'tel:' + cleanPhone,
            text: phone
        });

        $link.on('click', function (e) {
            e.stopImmediatePropagation(); // prevent modal JS and other handlers
        });

        $(nextNode).replaceWith($link);
    });

});
</script>
@stack('page_js')

</body>

</html>
