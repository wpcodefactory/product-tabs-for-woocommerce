<?php
/**
 * Plugin Name: Additional Custom Product Tabs for WooCommerce
 * Plugin URI: https://wpfactory.com/item/product-tabs-for-woocommerce-plugin/
 * Description: Manage product tabs in WooCommerce. Beautifully.
 * Version: 1.8.0
 * Author: WPFactory
 * Author URI: https://wpfactory.com
 * Requires at least: 4.4
 * Requires PHP: 7.4
 * Text Domain: product-tabs-for-woocommerce
 * Domain Path: /langs
 * WC tested up to: 11.1
 * Requires Plugins: woocommerce
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package WPFactory\WC_Product_Tabs
 */

defined( 'ABSPATH' ) || exit;

if ( 'product-tabs-for-woocommerce.php' === basename( __FILE__ ) ) {
	if ( ! function_exists( 'alg_wc_product_tabs_is_pro_activated' ) ) {
		/**
		 * Check if Pro plugin version is activated.
		 *
		 * @version 1.8.0
		 * @since   1.5.0
		 */
		function alg_wc_product_tabs_is_pro_activated() {
			$plugin = 'product-tabs-for-woocommerce-pro/product-tabs-for-woocommerce-pro.php';
			return (
				in_array(
					$plugin,
					(array) get_option( 'active_plugins', array() ),
					true
				) ||
				(
					is_multisite() &&
					array_key_exists(
						$plugin,
						(array) get_site_option( 'active_sitewide_plugins', array() )
					)
				)
			);
		}
	}

	if ( alg_wc_product_tabs_is_pro_activated() ) {
		defined( 'ALG_WC_PRODUCT_TABS_FILE_FREE' ) || define( 'ALG_WC_PRODUCT_TABS_FILE_FREE', __FILE__ );
		return;
	}
}

/**
 * Plugin version.
 */
defined( 'ALG_WC_PRODUCT_TABS_VERSION' ) || define( 'ALG_WC_PRODUCT_TABS_VERSION', '1.8.0' );

/**
 * Plugin file.
 */
defined( 'ALG_WC_PRODUCT_TABS_FILE' ) || define( 'ALG_WC_PRODUCT_TABS_FILE', __FILE__ );

/**
 * Include the main plugin class.
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-alg-wc-product-tabs.php';

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

/**
 * Initialize the plugin.
 */
add_action( 'plugins_loaded', 'alg_wc_product_tabs' );
