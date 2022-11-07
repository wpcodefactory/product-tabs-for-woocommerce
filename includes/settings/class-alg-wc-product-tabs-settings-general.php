<?php
/**
 * Product Tabs for WooCommerce - General Section Settings
 *
 * @version 1.5.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Product_Tabs_Settings_General' ) ) :

class Alg_WC_Product_Tabs_Settings_General extends Alg_WC_Product_Tabs_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id   = '';
		$this->desc = __( 'General', 'product-tabs-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 1.5.0
	 * @since   1.0.0
	 */
	function get_settings() {
		$settings = array(
			array(
				'title'     => __( 'Product Tabs', 'product-tabs-for-woocommerce' ),
				'type'      => 'title',
				'id'        => 'alg_wc_product_tabs_plugin_options',
			),
			array(
				'title'     => __( 'Product Tabs', 'product-tabs-for-woocommerce' ),
				'desc'      => '<strong>' . __( 'Enable plugin', 'product-tabs-for-woocommerce' ) . '</strong>',
				'id'        => 'alg_woocommerce_product_tabs_enabled',
				'default'   => 'yes',
				'type'      => 'checkbox',
			),
			array(
				'title'     => __( 'Sections', 'product-tabs-for-woocommerce' ),
				'desc'      => sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=wc-settings&tab=alg_product_tabs&section=standard' ),
					__( 'Standard Tabs', 'product-tabs-for-woocommerce' ) ),
				'desc_tip'  => __( 'Customizes standard WooCommerce product tabs.', 'product-tabs-for-woocommerce' ),
				'id'        => 'alg_wc_product_tabs_standard_tabs_enabled',
				'default'   => 'yes',
				'type'      => 'checkbox',
				'checkboxgroup' => 'start',
			),
			array(
				'desc'      => sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=wc-settings&tab=alg_product_tabs&section=global' ),
					__( 'Custom Tabs: All Products', 'product-tabs-for-woocommerce' ) ),
				'desc_tip'  => __( 'Adds custom product tabs for all products globally.', 'product-tabs-for-woocommerce' ),
				'id'        => 'alg_wc_product_tabs_global_tabs_enabled',
				'default'   => 'yes',
				'type'      => 'checkbox',
				'checkboxgroup' => '',
			),
			array(
				'desc'      => sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=wc-settings&tab=alg_product_tabs&section=local' ),
					__( 'Custom Tabs: Per Product', 'product-tabs-for-woocommerce' ) ),
				'desc_tip'  => __( 'Sets options for custom tabs on per product basis (when enabled, will add meta boxes on each product\'s admin edit page).', 'product-tabs-for-woocommerce' ),
				'id'        => 'alg_custom_product_tabs_local_enabled',
				'default'   => 'yes',
				'type'      => 'checkbox',
				'checkboxgroup' => '',
			),
			array(
				'desc'      => sprintf( '<a href="%s">%s</a>', admin_url( 'admin.php?page=wc-settings&tab=alg_product_tabs&section=variations' ),
					__( 'Variations Tabs', 'product-tabs-for-woocommerce' ) ),
				'desc_tip'  => __( 'Automatically add variations tabs to all variable products.', 'product-tabs-for-woocommerce' ),
				'id'        => 'alg_wc_product_tabs_variations_tabs_enabled',
				'default'   => 'no',
				'type'      => 'checkbox',
				'checkboxgroup' => 'end',
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_wc_product_tabs_plugin_options',
			),
		);
		return $settings;
	}

}

endif;

return new Alg_WC_Product_Tabs_Settings_General();
