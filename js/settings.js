jQuery(document).ready(function($){
    $('.color-field').wpColorPicker();
	
	eclshowhide();
	
	$( "#networkshareurl" ).prop( "disabled", !$('#networkshare').is(':checked') );
	
	$('#blinkid').on('change', eclshowhide );

	$('#networkshare').on('change', function() {
		$( "#networkshareurl" ).prop( "disabled", !$('#networkshare').is(':checked') );
	});

	// On Change
	$('#autoblock').on('change', function() {
		if ( $('#autoblock').is(':checked') ) {
			$('#fineblock').fadeIn('slow');
		} else {
			$('#fineblock').fadeOut('slow');
		}
	});
	
	function eclshowhide() {
		if ($('#blinkid').val() == "C") {
			$( "#boxlinkblank" ).prop( "disabled", false );
			$( "#customurl" ).prop( "disabled", false );
			$( "#box_content" ).prop( "disabled", true );
			$( "#close_link" ).prop( "disabled", true );
		} else if ($('#blinkid').val()) {
			$( "#boxlinkblank" ).prop( "disabled", false );
			$( "#customurl" ).prop( "disabled", true );
			$( "#box_content" ).prop( "disabled", true );
			$( "#close_link" ).prop( "disabled", true );
		} else {
			$( "#boxlinkblank" ).prop( "disabled", true );
			$( "#customurl" ).prop( "disabled", true );
			$( "#box_content" ).prop( "disabled", false );
			$( "#close_link" ).prop( "disabled", false );
		}
	}
});