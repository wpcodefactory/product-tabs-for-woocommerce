<?php
/**
 * Product Tabs for WooCommerce - Variations Section Settings
 *
 * @version 1.5.0
 * @since   1.4.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Product_Tabs_Settings_Variations' ) ) :

class Alg_WC_Product_Tabs_Settings_Variations extends Alg_WC_Product_Tabs_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 *
	 * @todo    (desc) rename to "Custom Tabs: Variations Tabs"?
	 */
	function __construct() {
		$this->id   = 'variations';
		$this->desc = __( 'Variations Tabs', 'product-tabs-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 1.5.0
	 * @since   1.4.0
	 *
	 * @todo    (dev) `alg_wc_product_tabs_variations_tabs_content`: better default value?
	 * @todo    (desc) better section desc?
	 */
	function get_settings() {
		$settings = array(
			array(
				'title'     => __( 'Variations Product Tabs Options', 'product-tabs-for-woocommerce' ),
				'type'      => 'title',
				'desc'      => __( 'This section lets you automatically add variations tabs to all variable products (one tab for each variation).', 'product-tabs-for-woocommerce' ),
				'id'        => 'alg_wc_product_tabs_variations_tabs_options',
			),
			array(
				'title'     => __( 'Variations tabs', 'product-tabs-for-woocommerce' ),
				'desc'      => '<strong>' . __( 'Enable section', 'product-tabs-for-woocommerce' ) . '</strong>',
				'id'        => 'alg_wc_product_tabs_variations_tabs_enabled',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'title'     => __( 'Position', 'product-tabs-for-woocommerce' ),
				'id'        => 'alg_wc_product_tabs_variations_tabs_priority',
				'default'   => 100,
				'type'      => 'number',
			),
			array(
				'title'     => __( 'Title', 'product-tabs-for-woocommerce' ),
				'desc_tip'  => __( 'You can use shortcodes here.', 'product-tabs-for-woocommerce' ),
				'id'        => 'alg_wc_product_tabs_variations_tabs_title',
				'default'   => '[alg_wc_pt_product_function name="get_name"]',
				'type'      => 'text',
				'css'       => 'width:100%;',
			),
			array(
				'title'     => __( 'Content', 'product-tabs-for-woocommerce' ),
				'desc_tip'  => __( 'You can use shortcodes here.', 'product-tabs-for-woocommerce' ),
				'id'        => 'alg_wc_product_tabs_variations_tabs_content',
				'default'   => '<h2>[alg_wc_pt_product_function name="get_name"]</h2>' . PHP_EOL .
					'<p>Price: [alg_wc_pt_product_function name="get_price_html"]</p>' . PHP_EOL .
					'<p>[alg_wc_pt_product_function name="get_description"]</p>' . PHP_EOL .
					'<p><a class="button" href=\'[alg_wc_pt_product_function name="add_to_cart_url"]\'>Add to cart</a></p>',
				'type'      => 'textarea',
				'css'       => 'width:100%;height:200px;',
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_wc_product_tabs_variations_tabs_options',
			),
		);
		return array_merge( $settings, $this->get_shortcodes_notes_section() );
	}

}

endif;

return new Alg_WC_Product_Tabs_Settings_Variations();
