<?php
/**
 * Product Tabs for WooCommerce - Section Settings
 *
 * @version 1.7.0
 * @since   1.2.0
 *
 * @author WPFactory
 *
 * @package WPFactory\WC_Product_Tabs\Settings
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Product_Tabs_Settings_Section' ) ) :

	/**
	 * Alg_WC_Product_Tabs_Settings_Section class.
	 *
	 * @version 1.7.0
	 * @since   1.2.0
	 */
	class Alg_WC_Product_Tabs_Settings_Section {

		/**
		 * ID.
		 *
		 * @version 1.6.0
		 * @since   1.6.0
		 *
		 * @var string
		 */
		public $id;

		/**
		 * Description.
		 *
		 * @version 1.6.0
		 * @since   1.6.0
		 *
		 * @var string
		 */
		public $desc;

		/**
		 * Constructor.
		 *
		 * @version 1.2.0
		 * @since   1.2.0
		 */
		public function __construct() {
			add_filter(
				'woocommerce_get_sections_alg_product_tabs',
				array( $this, 'settings_section' )
			);
			add_filter(
				'woocommerce_get_settings_alg_product_tabs_' . $this->id,
				array( $this, 'get_settings' ),
				PHP_INT_MAX
			);
		}

		/**
		 * Settings section.
		 *
		 * @version 1.2.0
		 * @since   1.2.0
		 *
		 * @param array $sections Array of existing sections.
		 *
		 * @return array Modified array of sections.
		 */
		public function settings_section( $sections ) {
			$sections[ $this->id ] = $this->desc;
			return $sections;
		}

		/**
		 * Get shortcodes notes section.
		 *
		 * @version 1.7.0
		 * @since   1.4.0
		 *
		 * @param bool $desc_title_options_only Whether to include only the title options in the description.
		 *
		 * @todo (desc) Better desc!
		 * @todo (desc) Add link to the "Shortcodes" section on plugin's site.
		 * @todo (desc) Examples: `[alg_wc_pt_product_function name="wc_get_formatted_variation" type="global"]`?
		 */
		public function get_shortcodes_notes_section( $desc_title_options_only = false ) {
			$examples = array(
				'alg_wc_pt_product_function' => array(
					'[alg_wc_pt_product_function name="get_id"]',
					'[alg_wc_pt_product_function name="get_name"]',
					'[alg_wc_pt_product_function name="get_price_html"]',
					'[alg_wc_pt_product_function name="get_description"]',
					'[alg_wc_pt_product_function name="add_to_cart_url"]',
				),
				'alg_wc_pt_product_meta'     => array(
					'[alg_wc_pt_product_meta key="my_custom_field"]',
				),
			);

			$desc = array(
				(
					$desc_title_options_only ?
					sprintf(
						/* Translators: %s: Option name. */
						__( 'You can use shortcodes in "%s" option(s).', 'product-tabs-for-woocommerce' ),
						__( 'Title', 'product-tabs-for-woocommerce' )
					) :
					sprintf(
						/* Translators: %1$s: Option name, %2$s: Option name. */
						__( 'You can use shortcodes in "%1$s" and "%2$s" option(s).', 'product-tabs-for-woocommerce' ),
						__( 'Title', 'product-tabs-for-woocommerce' ),
						__( 'Content', 'product-tabs-for-woocommerce' )
					)
				),
				sprintf(
					/* Translators: %1$s: Shortcode name, %2$s: Shortcode name. */
					__( 'There are two main shortcodes in plugin: %1$s and %2$s.', 'product-tabs-for-woocommerce' ),
					'<code>[alg_wc_pt_product_function]</code>',
					'<code>[alg_wc_pt_product_meta]</code>'
				),
				sprintf(
					/* Translators: %1$s: Shortcode name, %2$s: Documentation URL, %3$s: Shortcode examples. */
					__( '%1$s shortcode will display <a href="%2$s" target="_blank">product\'s function</a> result, for example: %3$s', 'product-tabs-for-woocommerce' ),
					'<code>[alg_wc_pt_product_function]</code>',
					'https://woocommerce.github.io/code-reference/classes/WC-Product.html',
					'<pre style="background-color:white;padding:10px;">' .
						implode( PHP_EOL, $examples['alg_wc_pt_product_function'] ) .
					'</pre>'
				),
				sprintf(
					/* Translators: %1$s: Shortcode name, %2$s: Shortcode example. */
					__( '%1$s shortcode will display product\'s meta value, for example: %2$s', 'product-tabs-for-woocommerce' ),
					'<code>[alg_wc_pt_product_meta]</code>',
					'<pre style="background-color:white;padding:10px;">' .
						implode( PHP_EOL, $examples['alg_wc_pt_product_meta'] ) .
					'</pre>'
				),
			);

			$icon = '<span class="dashicons dashicons-info"></span> ';

			return array(
				array(
					'title' => __( 'Shortcodes', 'product-tabs-for-woocommerce' ),
					'desc'  => '<p>' . $icon . implode( '</p><p>' . $icon, $desc ) . '</p>',
					'type'  => 'title',
					'id'    => 'alg_wc_product_tabs_shortcodes_notes',
				),
				array(
					'type' => 'sectionend',
					'id'   => 'alg_wc_product_tabs_shortcodes_notes',
				),
			);
		}
	}

endif;
