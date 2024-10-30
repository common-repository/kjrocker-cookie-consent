jQuery(document).ready(function($){

	var cookies = document.cookie.split(";");

	var kjcookieSet = kjcookielaw_data.kjcookieSet;
	var expireTimer = kjcookielaw_data.expireTimer;
	var scrollConsent = kjcookielaw_data.scrollConsent;
	var networkShareURL = kjcookielaw_data.networkShareURL;
	var isCookiePage = kjcookielaw_data.isCookiePage;
	var isRefererWebsite = kjcookielaw_data.isRefererWebsite;
	var deleteCookieUrl = kjcookielaw_data.deleteCookieUrl;
	var autoBlock = kjcookielaw_data.autoBlock;

	// Navigation Consent
	if ( autoBlock == 0 && isRefererWebsite && document.cookie.indexOf('kjcookie') < 0 ) {
		kjcookieConsent();
	}

	// Scroll Consent
	jQuery(window).scroll(function(){
		if ( autoBlock == 0 && scrollConsent > 0 && document.cookie.indexOf("kjcookie") < 0 && !kjcookieSet ) {
			if (!isCookiePage && getCookie('kjcookie') != "block" ) {
				kjcookieConsent();
			}
		}	
	});

	// Accept Button
	$('#pea_cook_btn, .kjcookie').click(function() {
		kjcookieConsent();
	});

	if ( getCookie('kjcookie') == "set" || kjcookieSet == 1 ) {
	  $(".pea_cook_wrapper").fadeOut("fast");
	}

	// Cookie-Control shortcode - REVOKE
	$("#kj_revoke_cookies").click(function() {
		deleteCookies();
		//createCookie( "block" );
		location.reload();
	});
	

	// Banner open / close
	$("#fom").click(function() {
		if( $('#fom').attr('href') === '#') { 
			$(".pea_cook_more_info_popover").fadeIn("slow");
			$(".pea_cook_wrapper").fadeOut("fast");
		}
	});
	
	$("#pea_close").click(function() {
		$(".pea_cook_wrapper").fadeIn("fast");
		$(".pea_cook_more_info_popover").fadeOut("slow");
	});

	// AUX Functions
	function kjcookieConsent() {
		if (typeof kjcookieConsentFilter === "function") {
			kjcookieConsentFilter();
		}
		deleteCookies()
		createCookie();
		if (autoBlock == 1) {
			location.reload();
		}
	}
	
	function createCookie() {
		var today = new Date(), expire = new Date();
		
		if (expireTimer > 0) {
			expire.setTime(today.getTime() + (expireTimer * 24 * 60 * 60 * 1000) );
			cookiestring = "kjcookie=set; "+networkShareURL+"expires=" + expire.toUTCString() + "; path=/";
		} else {
			cookiestring = "kjcookie=set; "+networkShareURL+"path=/";
		}
		document.cookie = cookiestring;
		$(".pea_cook_wrapper").fadeOut("fast");
	}

	function getCookie(cname) {
		var name = cname + "=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		for(var i = 0; i <ca.length; i++) {
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

	function deleteCookies() {
		document.cookie.split(";").forEach(function(c) { document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/"); });
		window.location = window.location;
	}
});