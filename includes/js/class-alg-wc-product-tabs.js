/**
 * Product Tabs for WooCommerce - JS
 *
 * @version 1.4.1
 * @since   1.3.0
 *
 * @author  Algoritmika Ltd.
 */

jQuery( function() {
	function alg_wc_go_to_custom_tab() {
		var hash = window.location.hash;
		for ( var i = 0, l = alg_wc_custom_tabs.ids.length; i < l; i++ ) {
			if ( '#tab-' + alg_wc_custom_tabs.ids[ i ] === hash ) {
				jQuery( '#tab-title-' + alg_wc_custom_tabs.ids[ i ] + ' a' ).trigger( 'click' );
				break;
			}
		}
	}
	jQuery( document ).ready( function() {
		window.onhashchange = function() {
			alg_wc_go_to_custom_tab();
		};
		alg_wc_go_to_custom_tab();
	} );
} );
