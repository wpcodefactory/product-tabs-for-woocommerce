<?php
/**
 * Product Tabs for WooCommerce - Standard Section Settings
 *
 * @version 1.5.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Product_Tabs_Settings_Standard' ) ) :

class Alg_WC_Product_Tabs_Settings_Standard extends Alg_WC_Product_Tabs_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id   = 'standard';
		$this->desc = __( 'Standard Tabs', 'product-tabs-for-woocommerce' );
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
				'title'     => __( 'Standard Product Tabs Options', 'product-tabs-for-woocommerce' ),
				'type'      => 'title',
				'desc'      => __( 'This section lets you customize standard product tabs.', 'product-tabs-for-woocommerce' ),
				'id'        => 'alg_product_info_product_tabs_options',
			),
			array(
				'title'     => __( 'Standard product tabs', 'product-tabs-for-woocommerce' ),
				'desc'      => '<strong>' . __( 'Enable section', 'product-tabs-for-woocommerce' ) . '</strong>',
				'id'        => 'alg_wc_product_tabs_standard_tabs_enabled',
				'default'   => 'yes',
				'type'      => 'checkbox',
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_product_info_product_tabs_options',
			),
			array(
				'title'     => __( 'Description', 'product-tabs-for-woocommerce' ),
				'type'      => 'title',
				'id'        => 'alg_product_info_product_tabs_description_options',
			),
			array(
				'title'     => __( 'Title', 'product-tabs-for-woocommerce' ),
				'desc_tip'  => __( 'Leave blank for WooCommerce default title.', 'product-tabs-for-woocommerce' ) . ' ' .
					__( 'You can use shortcodes here.', 'product-tabs-for-woocommerce' ),
				'id'        => 'alg_product_info_product_tabs_description_title',
				'default'   => '',
				'type'      => 'text',
				'css'       => 'width:100%;',
			),
			array(
				'title'     => __( 'Position', 'product-tabs-for-woocommerce' ),
				'id'        => 'alg_product_info_product_tabs_description_priority',
				'default'   => 10,
				'type'      => 'number',
			),
			array(
				'title'     => __( 'Remove', 'product-tabs-for-woocommerce' ),
				'desc'      => __( 'Remove tab from product page', 'product-tabs-for-woocommerce' ),
				'id'        => 'alg_product_info_product_tabs_description_disable',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_product_info_product_tabs_description_options',
			),
			array(
				'title'     => __( 'Additional information', 'product-tabs-for-woocommerce' ),
				'type'      => 'title',
				'id'        => 'alg_product_info_product_tabs_additional_info_options',
			),
			array(
				'title'     => __( 'Title', 'product-tabs-for-woocommerce' ),
				'desc_tip'  => __( 'Leave blank for WooCommerce default title.', 'product-tabs-for-woocommerce' ) . ' ' .
					__( 'You can use shortcodes here.', 'product-tabs-for-woocommerce' ),
				'id'        => 'alg_product_info_product_tabs_additional_information_title',
				'default'   => '',
				'type'      => 'text',
				'css'       => 'width:100%;',
			),
			array(
				'title'     => __( 'Position', 'product-tabs-for-woocommerce' ),
				'id'        => 'alg_product_info_product_tabs_additional_information_priority',
				'default'   => 20,
				'type'      => 'number',
			),
			array(
				'title'     => __( 'Remove', 'product-tabs-for-woocommerce' ),
				'desc'      => __( 'Remove tab from product page', 'product-tabs-for-woocommerce' ),
				'id'        => 'alg_product_info_product_tabs_additional_information_disable',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_product_info_product_tabs_additional_info_options',
			),
			array(
				'title'     => __( 'Reviews', 'product-tabs-for-woocommerce' ),
				'type'      => 'title',
				'id'        => 'alg_product_info_product_tabs_reviews_options',
			),
			array(
				'title'     => __( 'Title', 'product-tabs-for-woocommerce' ),
				'desc_tip'  => __( 'Leave blank for WooCommerce default title.', 'product-tabs-for-woocommerce' ) . ' ' .
					__( 'You can use shortcodes here.', 'product-tabs-for-woocommerce' ),
				'id'        => 'alg_product_info_product_tabs_reviews_title',
				'default'   => '',
				'type'      => 'text',
				'css'       => 'width:100%;',
			),
			array(
				'title'     => __( 'Position', 'product-tabs-for-woocommerce' ),
				'id'        => 'alg_product_info_product_tabs_reviews_priority',
				'default'   => 30,
				'type'      => 'number',
			),
			array(
				'title'     => __( 'Remove', 'product-tabs-for-woocommerce' ),
				'desc'      => __( 'Remove tab from product page', 'product-tabs-for-woocommerce' ),
				'id'        => 'alg_product_info_product_tabs_reviews_disable',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_product_info_product_tabs_reviews_options',
			),
		);
		return array_merge( $settings, $this->get_shortcodes_notes_section( true ) );
	}

}

endif;

return new Alg_WC_Product_Tabs_Settings_Standard();
