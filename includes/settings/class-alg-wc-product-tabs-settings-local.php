<?php
/**
 * Product Tabs for WooCommerce - Local Section Settings
 *
 * @version 1.8.0
 * @since   1.0.0
 *
 * @author WPFactory
 *
 * @package WPFactory\WC_Product_Tabs\Settings
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Product_Tabs_Settings_Local' ) ) :

	/**
	 * Alg_WC_Product_Tabs_Settings_Local class.
	 *
	 * @version 1.8.0
	 * @since   1.0.0
	 */
	class Alg_WC_Product_Tabs_Settings_Local extends Alg_WC_Product_Tabs_Settings_Section {

		/**
		 * Constructor.
		 *
		 * @version 1.4.0
		 * @since   1.0.0
		 */
		public function __construct() {
			$this->id   = 'local';
			$this->desc = __( 'Custom Tabs: Per Product', 'product-tabs-for-woocommerce' );
			parent::__construct();
		}

		/**
		 * Get settings.
		 *
		 * @version 1.8.0
		 * @since   1.0.0
		 *
		 * @todo (feature) Add default title, content, priority.
		 */
		public function get_settings() {
			$settings = array(
				array(
					'title' => __( 'Custom Product Tabs: Per Product', 'product-tabs-for-woocommerce' ),
					'type'  => 'title',
					'desc'  => __( 'This section lets you set options for custom tabs on per product basis. When enabled, will add meta boxes on each product\'s admin edit page.', 'product-tabs-for-woocommerce' ),
					'id'    => 'alg_custom_product_tabs_options_local',
				),
				array(
					'title'   => __( 'Custom tabs', 'product-tabs-for-woocommerce' ) . ': ' . __( 'Per product', 'product-tabs-for-woocommerce' ),
					'desc'    => '<strong>' . __( 'Enable section', 'product-tabs-for-woocommerce' ) . '</strong>',
					'id'      => 'alg_custom_product_tabs_local_enabled',
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
					'title'   => __( 'Enable WP editor', 'product-tabs-for-woocommerce' ),
					'desc'    => __( 'Enable', 'product-tabs-for-woocommerce' ),
					'id'      => 'alg_custom_product_tabs_local_wp_editor_enabled',
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
					'title'             => __( 'Default per product custom product tabs number', 'product-tabs-for-woocommerce' ),
					'id'                => 'alg_custom_product_tabs_local_total_number_default',
					'default'           => 1,
					'type'              => 'number',
					'custom_attributes' => array( 'min' => '0' ),
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_custom_product_tabs_options_local',
				),
			);
			return $settings;
		}
	}

endif;

return new Alg_WC_Product_Tabs_Settings_Local();
