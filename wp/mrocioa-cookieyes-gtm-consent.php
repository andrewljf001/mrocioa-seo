<?php
/**
 * Plugin Name: MROCIOA CookieYes GTM Consent Gate
 * Description: Loads Google Tag Manager only after CookieYes analytics consent.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'after_setup_theme',
	function () {
		remove_action( 'wp_head', 'mrocioa_gtm_head', 1 );
		remove_action( 'wp_body_open', 'mrocioa_gtm_body', 1 );
	},
	99
);

add_action(
	'wp_head',
	function () {
		if ( is_admin() ) {
			return;
		}
		?>
<script data-cfasync="false" id="mrocioa-gtm-consent-loader">
(function(w,d){
	var gtmId = 'GTM-T5MW49C';
	var loaded = false;
	function analyticsAllowed(){
		try {
			if (typeof w.getCkyConsent === 'function') {
				var consent = w.getCkyConsent();
				return !!(consent && consent.categories && consent.categories.analytics);
			}
			var match = d.cookie.match(/(?:^|;\s*)cookieyes-consent=([^;]*)/);
			return !!(match && decodeURIComponent(match[1]).indexOf('analytics:yes') !== -1);
		} catch (e) {
			return false;
		}
	}
	function loadGtm(){
		if (loaded || !analyticsAllowed()) {
			return;
		}
		loaded = true;
		w.dataLayer = w.dataLayer || [];
		w.dataLayer.push({'gtm.start': new Date().getTime(), event: 'gtm.js'});
		var first = d.getElementsByTagName('script')[0];
		var script = d.createElement('script');
		script.async = true;
		script.src = 'https://www.googletagmanager.com/gtm.js?id=' + gtmId;
		first.parentNode.insertBefore(script, first);
	}
	d.addEventListener('cookieyes_consent_update', loadGtm);
	if (d.readyState === 'loading') {
		d.addEventListener('DOMContentLoaded', loadGtm);
	} else {
		loadGtm();
	}
})(window,document);
</script>
		<?php
	},
	2
);
