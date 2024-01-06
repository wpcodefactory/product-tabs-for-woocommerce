<?php
/**
 * Product Tabs for WooCommerce - Section Settings
 *
 * @version 1.6.0
 * @since   1.2.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Product_Tabs_Settings_Section' ) ) :

class Alg_WC_Product_Tabs_Settings_Section {

	/**
	 * id.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 */
	public $id;

	/**
	 * desc.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 */
	public $desc;

	/**
	 * Constructor.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function __construct() {
		add_filter( 'woocommerce_get_sections_alg_product_tabs',              array( $this, 'settings_section' ) );
		add_filter( 'woocommerce_get_settings_alg_product_tabs_' . $this->id, array( $this, 'get_settings' ), PHP_INT_MAX );
	}

	/**
	 * settings_section.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function settings_section( $sections ) {
		$sections[ $this->id ] = $this->desc;
		return $sections;
	}

	/**
	 * get_shortcodes_notes_section.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 *
	 * @todo    (desc) better desc!
	 * @todo    (desc) add link to the "Shortcodes" section on plugin's site
	 * @todo    (desc) examples: `[alg_wc_pt_product_function name="wc_get_formatted_variation" type="global"]`?
	 */
	function get_shortcodes_notes_section( $desc_title_options_only = false ) {

		$examples = array(
			'alg_wc_pt_product_function' => array(
				'[alg_wc_pt_product_function name="get_id"]',
				'[alg_wc_pt_product_function name="get_name"]',
				'[alg_wc_pt_product_function name="get_price_html"]',
				'[alg_wc_pt_product_function name="get_description"]',
				'[alg_wc_pt_product_function name="add_to_cart_url"]',
			),
			'alg_wc_pt_product_meta' => array(
				'[alg_wc_pt_product_meta key="my_custom_field"]',
			),
		);

		$desc = array(
			( $desc_title_options_only ?
				 sprintf( __( 'You can use shortcodes in "%s" option(s).', 'product-tabs-for-woocommerce' ),          __( 'Title', 'product-tabs-for-woocommerce' ) ) :
				 sprintf( __( 'You can use shortcodes in "%s" and "%s" option(s).', 'product-tabs-for-woocommerce' ), __( 'Title', 'product-tabs-for-woocommerce' ), __( 'Content', 'product-tabs-for-woocommerce' ) )
			),
			sprintf( __( 'There are two main shortcodes in plugin: %s and %s.', 'product-tabs-for-woocommerce' ),
				'<code>[alg_wc_pt_product_function]</code>', '<code>[alg_wc_pt_product_meta]</code>' ),
			sprintf( __( '%s shortcode will display <a href="%s" target="_blank">product\'s function</a> result, for example: %s', 'product-tabs-for-woocommerce' ),
				'<code>[alg_wc_pt_product_function]</code>', 'https://woocommerce.github.io/code-reference/classes/WC-Product.html',
				'<pre style="background-color:white;padding:10px;">' . implode( PHP_EOL, $examples['alg_wc_pt_product_function'] ) . '</pre>' ),
			sprintf( __( '%s shortcode will display product\'s meta value, for example: %s', 'product-tabs-for-woocommerce' ),
				'<code>[alg_wc_pt_product_meta]</code>',
				'<pre style="background-color:white;padding:10px;">' . implode( PHP_EOL, $examples['alg_wc_pt_product_meta'] ) . '</pre>' ),
		);

		$icon = '<span class="dashicons dashicons-info"></span> ';

		return array(
			array(
				'title'     => __( 'Shortcodes', 'product-tabs-for-woocommerce' ),
				'desc'      => '<p>' . $icon . implode( '</p><p>' . $icon, $desc ) . '</p>',
				'type'      => 'title',
				'id'        => 'alg_wc_product_tabs_shortcodes_notes',
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_wc_product_tabs_shortcodes_notes',
			),
		);

	}

}

endif;
