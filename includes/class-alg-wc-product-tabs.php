<?php
/**
 * Product Tabs for WooCommerce - Main Class
 *
 * @version 1.8.0
 * @since   1.0.0
 *
 * @author WPFactory
 *
 * @package WPFactory\WC_Product_Tabs
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Product_Tabs' ) ) :

	/**
	 * Alg_WC_Product_Tabs class.
	 *
	 * @version 1.8.0
	 * @since   1.0.0
	 */
	final class Alg_WC_Product_Tabs {

		/**
		 * Plugin version.
		 *
		 * @version 1.1.0
		 * @since   1.1.0
		 *
		 * @var string
		 */
		public $version = ALG_WC_PRODUCT_TABS_VERSION;

		/**
		 * Core.
		 *
		 * @version 1.6.0
		 * @since   1.6.0
		 *
		 * @var Alg_WC_Product_Tabs_Core
		 */
		public $core;

		/**
		 * The single instance of the class.
		 *
		 * @version 1.8.0
		 * @since   1.0.0
		 *
		 * @var Alg_WC_Product_Tabs
		 */
		protected static $instance = null;

		/**
		 * Main Alg_WC_Product_Tabs Instance.
		 *
		 * Ensures only one instance of Alg_WC_Product_Tabs is loaded or can be loaded.
		 *
		 * @version 1.8.0
		 * @since   1.0.0
		 *
		 * @static
		 *
		 * @return Alg_WC_Product_Tabs
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Alg_WC_Product_Tabs Constructor.
		 *
		 * @version 1.8.0
		 * @since   1.0.0
		 *
		 * @access public
		 */
		public function __construct() {
			// Check for active WooCommerce plugin.
			if ( ! function_exists( 'WC' ) ) {
				return;
			}

			// Load libs.
			if ( is_admin() ) {
				require_once plugin_dir_path( ALG_WC_PRODUCT_TABS_FILE ) . 'vendor/autoload.php';
			}

			// Declare compatibility with custom order tables for WooCommerce.
			add_action( 'before_woocommerce_init', array( $this, 'wc_declare_compatibility' ) );

			// Pro.
			if ( 'product-tabs-for-woocommerce-pro.php' === basename( ALG_WC_PRODUCT_TABS_FILE ) ) {
				require_once plugin_dir_path( __FILE__ ) . 'pro/class-alg-wc-product-tabs-pro.php';
			}

			// Include required files.
			$this->includes();

			// Admin.
			if ( is_admin() ) {
				$this->admin();
			}
		}

		/**
		 * WC declare compatibility.
		 *
		 * @version 1.6.0
		 * @since   1.6.0
		 *
		 * @see https://developer.woocommerce.com/docs/features/high-performance-order-storage/recipe-book/
		 */
		public function wc_declare_compatibility() {
			if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
				$files = (
					defined( 'ALG_WC_PRODUCT_TABS_FILE_FREE' ) ?
					array( ALG_WC_PRODUCT_TABS_FILE, ALG_WC_PRODUCT_TABS_FILE_FREE ) :
					array( ALG_WC_PRODUCT_TABS_FILE )
				);
				foreach ( $files as $file ) {
					\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
						'custom_order_tables',
						$file,
						true
					);
				}
			}
		}

		/**
		 * Include required core files used in admin and on the frontend.
		 *
		 * @version 1.7.0
		 * @since   1.3.0
		 */
		public function includes() {
			$this->core = require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-product-tabs-core.php';
		}

		/**
		 * Admin.
		 *
		 * @version 1.7.3
		 * @since   1.2.0
		 */
		public function admin() {
			// Action links.
			add_filter(
				'plugin_action_links_' . plugin_basename( ALG_WC_PRODUCT_TABS_FILE ),
				array( $this, 'action_links' )
			);

			// "Recommendations" page.
			add_action( 'init', array( $this, 'add_cross_selling_library' ) );

			// WC Settings tab as WPFactory submenu item.
			add_action( 'init', array( $this, 'move_wc_settings_tab_to_wpfactory_menu' ) );

			// Settings.
			add_filter( 'woocommerce_get_settings_pages', array( $this, 'add_woocommerce_settings_tab' ) );

			// Version update.
			if ( get_option( 'alg_wc_product_tabs_version', '' ) !== $this->version ) {
				add_action( 'admin_init', array( $this, 'version_updated' ) );
			}
		}

		/**
		 * Show action links on the plugin screen.
		 *
		 * @version 1.7.0
		 * @since   1.0.0
		 *
		 * @param mixed $links Action links for the plugin.
		 *
		 * @return array
		 */
		public function action_links( $links ) {
			$custom_links = array();

			$custom_links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=alg_product_tabs' ) . '">' .
				__( 'Settings', 'product-tabs-for-woocommerce' ) .
			'</a>';

			if ( 'product-tabs-for-woocommerce.php' === basename( ALG_WC_PRODUCT_TABS_FILE ) ) {
				$custom_links[] = '<a target="_blank" style="font-weight: bold; color: green;" href="https://wpfactory.com/item/product-tabs-for-woocommerce-plugin/">' .
					__( 'Go Pro', 'product-tabs-for-woocommerce' ) .
				'</a>';
			}

			return array_merge( $custom_links, $links );
		}

		/**
		 * Add cross selling library.
		 *
		 * @version 1.7.0
		 * @since   1.7.0
		 */
		public function add_cross_selling_library() {
			if ( ! class_exists( '\WPFactory\WPFactory_Cross_Selling\WPFactory_Cross_Selling' ) ) {
				return;
			}

			$cross_selling = new \WPFactory\WPFactory_Cross_Selling\WPFactory_Cross_Selling();
			$cross_selling->setup( array( 'plugin_file_path' => ALG_WC_PRODUCT_TABS_FILE ) );
			$cross_selling->init();
		}

		/**
		 * Move WC settings tab to WPFactory menu.
		 *
		 * @version 1.7.3
		 * @since   1.7.0
		 */
		public function move_wc_settings_tab_to_wpfactory_menu() {
			if ( ! class_exists( '\WPFactory\WPFactory_Admin_Menu\WPFactory_Admin_Menu' ) ) {
				return;
			}

			$wpfactory_admin_menu = \WPFactory\WPFactory_Admin_Menu\WPFactory_Admin_Menu::get_instance();

			if ( ! method_exists( $wpfactory_admin_menu, 'move_wc_settings_tab_to_wpfactory_menu' ) ) {
				return;
			}

			$wpfactory_admin_menu->move_wc_settings_tab_to_wpfactory_menu(
				array(
					'wc_settings_tab_id' => 'alg_product_tabs',
					'menu_title'         => __( 'Product Tabs', 'product-tabs-for-woocommerce' ),
					'page_title'         => __( 'Additional Custom Product Tabs for WooCommerce', 'product-tabs-for-woocommerce' ),
					'plugin_icon'        => array(
						'get_url_method'    => 'wporg_plugins_api',
						'wporg_plugin_slug' => 'product-tabs-for-woocommerce',
					),
				)
			);
		}

		/**
		 * Add Woocommerce settings tab to WooCommerce settings.
		 *
		 * @version 1.7.0
		 * @since   1.0.0
		 *
		 * @param array $settings The WooCommerce settings tabs array.
		 */
		public function add_woocommerce_settings_tab( $settings ) {
			$settings[] = require_once plugin_dir_path( __FILE__ ) . 'settings/class-alg-wc-settings-product-tabs.php';
			return $settings;
		}

		/**
		 * Version updated.
		 *
		 * @version 1.2.0
		 * @since   1.2.0
		 */
		public function version_updated() {
			update_option( 'alg_wc_product_tabs_version', $this->version );
		}

		/**
		 * Get the plugin url.
		 *
		 * @version 1.5.0
		 * @since   1.0.0
		 *
		 * @return string
		 */
		public function plugin_url() {
			return untrailingslashit( plugin_dir_url( ALG_WC_PRODUCT_TABS_FILE ) );
		}

		/**
		 * Get the plugin path.
		 *
		 * @version 1.5.0
		 * @since   1.0.0
		 *
		 * @return string
		 */
		public function plugin_path() {
			return untrailingslashit( plugin_dir_path( ALG_WC_PRODUCT_TABS_FILE ) );
		}
	}

endif;
