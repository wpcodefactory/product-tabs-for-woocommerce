<?php
/*
Plugin Name: Additional Custom Product Tabs for WooCommerce
Plugin URI: https://wpfactory.com/item/product-tabs-for-woocommerce-plugin/
Description: Manage product tabs in WooCommerce. Beautifully.
Version: 1.6.2
Author: WPFactory
Author URI: https://wpfactory.com
Text Domain: product-tabs-for-woocommerce
Domain Path: /langs
WC tested up to: 9.1
Requires Plugins: woocommerce
*/

defined( 'ABSPATH' ) || exit;

if ( 'product-tabs-for-woocommerce.php' === basename( __FILE__ ) ) {
	/**
	 * Check if Pro plugin version is activated.
	 *
	 * @version 1.6.0
	 * @since   1.5.0
	 */
	$plugin = 'product-tabs-for-woocommerce-pro/product-tabs-for-woocommerce-pro.php';
	if (
		in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) ||
		( is_multisite() && array_key_exists( $plugin, (array) get_site_option( 'active_sitewide_plugins', array() ) ) )
	) {
		defined( 'ALG_WC_PRODUCT_TABS_FILE_FREE' ) || define( 'ALG_WC_PRODUCT_TABS_FILE_FREE', __FILE__ );
		return;
	}
}

defined( 'ALG_WC_PRODUCT_TABS_VERSION' ) || define( 'ALG_WC_PRODUCT_TABS_VERSION', '1.6.2' );

defined( 'ALG_WC_PRODUCT_TABS_FILE' ) || define( 'ALG_WC_PRODUCT_TABS_FILE', __FILE__ );

require_once( 'includes/class-alg-wc-product-tabs.php' );

if ( ! function_exists( 'alg_wc_product_tabs' ) ) {
	/**
	 * Returns the main instance of Alg_WC_Product_Tabs to prevent the need to use globals.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 */
	function alg_wc_product_tabs() {
		return Alg_WC_Product_Tabs::instance();
	}
}

add_action( 'plugins_loaded', 'alg_wc_product_tabs' );
