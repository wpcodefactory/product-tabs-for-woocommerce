<?php
/**
 * Product Tabs for WooCommerce - Local Section Settings
 *
 * @version 1.5.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Product_Tabs_Settings_Local' ) ) :

class Alg_WC_Product_Tabs_Settings_Local extends Alg_WC_Product_Tabs_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id   = 'local';
		$this->desc = __( 'Custom Tabs: Per Product', 'product-tabs-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 1.5.0
	 * @since   1.0.0
	 *
	 * @todo    (feature) add default title, content, priority
	 */
	function get_settings() {
		$settings = array(
			array(
				'title'     => __( 'Custom Product Tabs: Per Product', 'product-tabs-for-woocommerce' ),
				'type'      => 'title',
				'desc'      => __( 'This section lets you set options for custom tabs on per product basis. When enabled, will add metaboxes on each product\'s admin edit page.', 'product-tabs-for-woocommerce' ),
				'id'        => 'alg_custom_product_tabs_options_local',
			),
			array(
				'title'     => __( 'Custom tabs', 'product-tabs-for-woocommerce' ) . ': ' . __( 'Per product', 'product-tabs-for-woocommerce' ),
				'desc'      => '<strong>' . __( 'Enable section', 'product-tabs-for-woocommerce' ) . '</strong>',
				'id'        => 'alg_custom_product_tabs_local_enabled',
				'default'   => 'yes',
				'type'      => 'checkbox',
			),
			array(
				'title'     => __( 'Enable WP editor', 'product-tabs-for-woocommerce' ),
				'desc'      => __( 'Enable', 'product-tabs-for-woocommerce' ),
				'id'        => 'alg_custom_product_tabs_local_wp_editor_enabled',
				'default'   => 'yes',
				'type'      => 'checkbox',
			),
			array(
				'title'     => __( 'Default per product custom product tabs number', 'product-tabs-for-woocommerce' ),
				'id'        => 'alg_custom_product_tabs_local_total_number_default',
				'default'   => 1,
				'type'      => 'number',
				'desc'      => apply_filters( 'alg_wc_product_tabs_settings', sprintf( 'Get <a href="%s">Product Tabs for WooCommerce Pro</a> to change this option.',
					'https://wpfactory.com/item/product-tabs-for-woocommerce-plugin/' ) ),
				'custom_attributes' => apply_filters( 'alg_wc_product_tabs_settings', array( 'readonly' => 'readonly' ), 'total' ),
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_custom_product_tabs_options_local',
			),
		);
		return $settings;
	}

}

endif;

return new Alg_WC_Product_Tabs_Settings_Local();
