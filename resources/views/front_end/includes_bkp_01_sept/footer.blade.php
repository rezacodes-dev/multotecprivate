@php

	$bodyScript2 = getSEOscripts('before_body');
	if( !empty($bodyScript2) ) {
		foreach($bodyScript2 as $v) {
			echo html_entity_decode( $v->script_code, ENT_QUOTES );
		}
	}

@endphp
	

<script src="{{ asset('public/front_end/js/jquery.min.js') }}"></script>
<script src="{{ asset('public/assets/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('public/assets/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('public/front_end/js/owl.carousel.js') }}"></script>
 <!-- menu Js -->
<script type="text/javascript" src="{{ asset('public/front_end/js/menuzord.js') }}"></script> 

<script type="text/javascript" src="{{ asset('public/assets/jquery_validator/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('public/assets/jquery_validator/additional-methods.min.js') }}"></script>
<script src="{{ asset('public/assets/jquery_ui/jquery-ui.js') }}"></script>
<script src="{{ asset('public/front_end/js/arjs.js') }}"></script>

<script type="text/javascript">
$(function() { 
	$('.togsrcbtn').on('click', function() { 
		$('.searchtoggle').slideToggle();
	} );
} );
</script>

<script type="text/javascript">
$( function() { 
	$('body').on('click', '.showhide', function() { 
		$(this).removeClass('showhide').addClass('showhide2');
	} );
	$('body').on('click', '.showhide2', function() { 
		$(this).removeClass('showhide2').addClass('showhide');
	} );
} );
</script>

@stack('page_js')

</body>
</html>
