jQuery(document).ready(function () {
	var swpForm = jQuery('#swpm-registration-form'),
	backtoblog = jQuery( "#backtoblog" );
	
	if(backtoblog.length != 0){
		jQuery("<div class='start-trial'><h3>Not a member yet?</h3><a href='#'>Start free trial</a></div>").insertBefore( backtoblog );	
	}	
	/* if(swpForm.length != 0){
		jQuery(swpForm).append('<input type="hidden" name="member_role" />');
	} */
});