<?php
/**
 * Product Tabs for WooCommerce - Main Class
 *
 * @version 1.6.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Product_Tabs' ) ) :

final class Alg_WC_Product_Tabs {

	/**
	 * Plugin version.
	 *
	 * @var   string
	 * @since 1.1.0
	 */
	public $version = ALG_WC_PRODUCT_TABS_VERSION;

	/**
	 * core.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 */
	public $core;

	/**
	 * @var Alg_WC_Product_Tabs The single instance of the class
	 */
	protected static $_instance = null;

	/**
	 * Main Alg_WC_Product_Tabs Instance
	 *
	 * Ensures only one instance of Alg_WC_Product_Tabs is loaded or can be loaded.
	 *
	 * @static
	 * @return Alg_WC_Product_Tabs - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	}

	/**
	 * Alg_WC_Product_Tabs Constructor.
	 *
	 * @version 1.6.0
	 * @since   1.0.0
	 *
	 * @access  public
	 */
	function __construct() {

		// Check for active WooCommerce plugin
		if ( ! function_exists( 'WC' ) ) {
			return;
		}

		// Set up localisation
		add_action( 'init', array( $this, 'localize' ) );

		// Declare compatibility with custom order tables for WooCommerce
		add_action( 'before_woocommerce_init', array( $this, 'wc_declare_compatibility' ) );

		// Pro
		if ( 'product-tabs-for-woocommerce-pro.php' === basename( ALG_WC_PRODUCT_TABS_FILE ) ) {
			require_once( 'pro/class-alg-wc-product-tabs-pro.php' );
		}

		// Include required files
		$this->includes();

		// Admin
		if ( is_admin() ) {
			$this->admin();
		}
	}

	/**
	 * localize.
	 *
	 * @version 1.5.0
	 * @since   1.4.0
	 */
	function localize() {
		load_plugin_textdomain( 'product-tabs-for-woocommerce', false, dirname( plugin_basename( ALG_WC_PRODUCT_TABS_FILE ) ) . '/langs/' );
	}

	/**
	 * wc_declare_compatibility.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 *
	 * @see     https://github.com/woocommerce/woocommerce/wiki/High-Performance-Order-Storage-Upgrade-Recipe-Book#declaring-extension-incompatibility
	 */
	function wc_declare_compatibility() {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			$files = ( defined( 'ALG_WC_PRODUCT_TABS_FILE_FREE' ) ? array( ALG_WC_PRODUCT_TABS_FILE, ALG_WC_PRODUCT_TABS_FILE_FREE ) : array( ALG_WC_PRODUCT_TABS_FILE ) );
			foreach ( $files as $file ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', $file, true );
			}
		}
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @version 1.5.0
	 * @since   1.3.0
	 */
	function includes() {
		$this->core = require_once( 'class-alg-wc-product-tabs-core.php' );
	}

	/**
	 * admin.
	 *
	 * @version 1.5.0
	 * @since   1.2.0
	 */
	function admin() {
		// Action links
		add_filter( 'plugin_action_links_' . plugin_basename( ALG_WC_PRODUCT_TABS_FILE ), array( $this, 'action_links' ) );
		// Settings
		add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );
		// Version update
		if ( get_option( 'alg_wc_product_tabs_version', '' ) !== $this->version ) {
			add_action( 'admin_init', array( $this, 'version_updated' ) );
		}
	}

	/**
	 * Show action links on the plugin screen.
	 *
	 * @version 1.5.0
	 * @since   1.0.0
	 *
	 * @param   mixed $links
	 * @return  array
	 */
	function action_links( $links ) {
		$custom_links = array();
		$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_product_tabs' ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>';
		if ( 'product-tabs-for-woocommerce.php' === basename( ALG_WC_PRODUCT_TABS_FILE ) ) {
			$custom_links[] = '<a target="_blank" style="font-weight: bold; color: green;" href="https://wpfactory.com/item/product-tabs-for-woocommerce-plugin/">' .
				__( 'Go Pro', 'product-tabs-for-woocommerce' ) . '</a>';
		}
		return array_merge( $custom_links, $links );
	}

	/**
	 * Add Woocommerce settings tab to WooCommerce settings.
	 *
	 * @version 1.5.0
	 * @since   1.0.0
	 *
	 * @param   array $settings The WooCommerce settings tabs array.
	 */
	function add_woocommerce_settings_tab( $settings ) {
		$settings[] = require_once( 'settings/class-alg-wc-settings-product-tabs.php' );
		return $settings;
	}

	/**
	 * version_updated.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function version_updated() {
		update_option( 'alg_wc_product_tabs_version', $this->version );
	}

	/**
	 * Get the plugin url.
	 *
	 * @version 1.5.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_url() {
		return untrailingslashit( plugin_dir_url( ALG_WC_PRODUCT_TABS_FILE ) );
	}

	/**
	 * Get the plugin path.
	 *
	 * @version 1.5.0
	 * @since   1.0.0
	 *
	 * @return  string
	 */
	function plugin_path() {
		return untrailingslashit( plugin_dir_path( ALG_WC_PRODUCT_TABS_FILE ) );
	}
}

endif;
