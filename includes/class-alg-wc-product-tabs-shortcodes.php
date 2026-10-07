<?php
/**
 * Product Tabs for WooCommerce - Shortcodes Class
 *
 * @version 1.8.0
 * @since   1.4.0
 *
 * @author WPFactory
 *
 * @package WPFactory\WC_Product_Tabs
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Product_Tabs_Shortcodes' ) ) :

	/**
	 * Alg_WC_Product_Tabs_Shortcodes class.
	 *
	 * @version 1.8.0
	 * @since   1.4.0
	 */
	class Alg_WC_Product_Tabs_Shortcodes {

		/**
		 * Product ID.
		 *
		 * @version 1.6.0
		 * @since   1.6.0
		 *
		 * @var int|false
		 */
		public $product_id;

		/**
		 * Constructor.
		 *
		 * @version 1.8.0
		 * @since   1.4.0
		 *
		 * @todo (feature) Add `[alg_wc_pt_option]` shortcode.
		 * @todo (feature) Add aliases, e.g., `[alg_wc_pt_product_price]`, `[alg_wc_pt_product_description]`, etc.?
		 */
		public function __construct() {
			add_shortcode( 'alg_wc_pt_product_function', array( $this, 'product_function' ) );
			add_shortcode( 'alg_wc_pt_product_meta', array( $this, 'product_meta' ) );
			add_shortcode( 'alg_wc_pt_product_add_to_cart_url', array( $this, 'product_add_to_cart_url' ) );
			add_shortcode( 'alg_wc_pt_translate', array( $this, 'translate' ) );
			add_shortcode( 'alg_wc_cpt_translate', array( $this, 'translate' ) ); // Deprecated.
		}

		/**
		 * Get product ID.
		 *
		 * @version 1.4.0
		 * @since   1.4.0
		 *
		 * @param array $atts Attributes.
		 *
		 * @return int|false The product ID or false.
		 */
		public function get_product_id( $atts ) {
			return (
				! empty( $atts['product_id'] ) ?
				$atts['product_id'] :
				(
					! empty( $this->product_id ) ?
					$this->product_id :
					false
				)
			);
		}

		/**
		 * Output.
		 *
		 * @version 1.8.0
		 * @since   1.4.0
		 *
		 * @param string $content The content to output.
		 * @param array  $atts    The shortcode attributes.
		 *
		 * @todo (feature) Optional formatting, e.g., `wc_price()` (e.g., `$atts['format_func']` or `$atts['output_func']`).
		 * @todo (feature) Add `on_zero` att?
		 * @todo (feature) Add `lang` and `not_lang` atts?
		 */
		public function output( $content, $atts ) {
			if ( '' === $content || false === $content ) {
				return ( isset( $atts['on_empty'] ) ? wp_kses_post( $atts['on_empty'] ) : '' );
			} else {
				return (
					( isset( $atts['before'] ) ? wp_kses_post( $atts['before'] ) : '' ) .
					wp_kses_post( $content ) .
					( isset( $atts['after'] ) ? wp_kses_post( $atts['after'] ) : '' )
				);
			}
		}

		/**
		 * Product add to cart URL.
		 *
		 * @version 1.8.0
		 * @since   1.8.0
		 *
		 * @param array $atts The shortcode attributes.
		 *
		 * @return string The output.
		 */
		public function product_add_to_cart_url( $atts ) {
			$product_id = $this->get_product_id( $atts );
			if ( ! $product_id ) {
				return '';
			}
			$product = wc_get_product( $product_id );
			if ( ! $product ) {
				return '';
			}

			return $this->output( $product->add_to_cart_url(), $atts );
		}

		/**
		 * Product function.
		 *
		 * @version 1.8.0
		 * @since   1.4.0
		 *
		 * @param array $atts The shortcode attributes.
		 *
		 * @return string The output.
		 */
		public function product_function( $atts ) {
			if ( ! isset( $atts['name'] ) ) {
				return '';
			}
			$product_id = $this->get_product_id( $atts );
			if ( ! $product_id ) {
				return '';
			}
			$product = wc_get_product( $product_id );
			if ( ! $product ) {
				return '';
			}

			$func = $atts['name'];

			if ( isset( $atts['type'] ) && 'global' === $atts['type'] ) {
				if ( function_exists( $func ) ) {
					$allowed_functions = apply_filters(
						'alg_wc_product_tabs_shortcode_allowed_global_functions',
						array(
							'wc_get_price_including_tax',
							'wc_get_price_excluding_tax',
							'wc_get_price_to_display',
						)
					);
					if ( in_array( $func, $allowed_functions, true ) ) {
						return $this->output( $func( $product ), $atts );
					}
				}
			} elseif ( is_callable( array( $product, $atts['name'] ) ) ) { // 'local'
				$allowed_functions = apply_filters(
					'alg_wc_product_tabs_shortcode_allowed_functions',
					array(
						'add_to_cart_url',
						'get_average_rating',
						'get_backorders',
						'get_catalog_visibility',
						'get_clone_mode',
						'get_cogs_effective_value',
						'get_cogs_total_value',
						'get_cogs_value',
						'get_cogs_value_html',
						'get_description',
						'get_download_expiry',
						'get_download_limit',
						'get_downloadable',
						'get_featured',
						'get_file_download_path',
						'get_formatted_name',
						'get_global_unique_id',
						'get_height',
						'get_id',
						'get_image',
						'get_image_id',
						'get_length',
						'get_low_stock_amount',
						'get_manage_stock',
						'get_max_purchase_quantity',
						'get_menu_order',
						'get_meta_cache_key',
						'get_min_purchase_quantity',
						'get_name',
						'get_object_read',
						'get_parent_id',
						'get_permalink',
						'get_post_password',
						'get_price',
						'get_price_html',
						'get_price_suffix',
						'get_purchase_note',
						'get_purchase_quantity_step',
						'get_rating_count',
						'get_regular_price',
						'get_review_count',
						'get_reviews_allowed',
						'get_sale_price',
						'get_shipping_class',
						'get_shipping_class_id',
						'get_short_description',
						'get_sku',
						'get_slug',
						'get_sold_individually',
						'get_status',
						'get_stock_managed_by_id',
						'get_stock_quantity',
						'get_stock_status',
						'get_tax_class',
						'get_tax_status',
						'get_title',
						'get_total_sales',
						'get_type',
						'get_virtual',
						'get_weight',
						'get_width',
					)
				);
				if ( in_array( $func, $allowed_functions, true ) ) {
					return $this->output( $product->$func(), $atts );
				}
			}
		}

		/**
		 * Product meta.
		 *
		 * @version 1.8.0
		 * @since   1.4.0
		 *
		 * @param array $atts The shortcode attributes.
		 *
		 * @return string The output.
		 */
		public function product_meta( $atts ) {
			if ( isset( $atts['key'] ) ) {
				$product_id = $this->get_product_id( $atts );
				if ( $product_id ) {
					return $this->output( get_post_meta( $product_id, $atts['key'], true ), $atts );
				}
			}
		}

		/**
		 * Translate.
		 *
		 * @version 1.8.0
		 * @since   1.3.0
		 *
		 * @param array  $atts    The shortcode attributes.
		 * @param string $content The content to output.
		 *
		 * @return string The output.
		 *
		 * @todo (v1.7.0) `do_shortcode`: Make it optional?
		 */
		public function translate( $atts, $content = '' ) {
			// E.g.: `[alg_wc_pt_translate lang="EN,DE" lang_text="Text for EN & DE" not_lang_text="Text for other languages"]`.
			if (
				isset( $atts['lang_text'] ) &&
				isset( $atts['not_lang_text'] ) &&
				! empty( $atts['lang'] )
			) {
				return (
					(
						! defined( 'ICL_LANGUAGE_CODE' ) ||
						! in_array(
							strtolower( ICL_LANGUAGE_CODE ),
							array_map( 'trim', explode( ',', strtolower( $atts['lang'] ) ) ),
							true
						)
					) ?
					wp_kses_post( $atts['not_lang_text'] ) :
					wp_kses_post( $atts['lang_text'] )
				);
			}

			// E.g.: `[alg_wc_pt_translate lang="EN,DE"]Text for EN & DE[/alg_wc_pt_translate][alg_wc_pt_translate not_lang="EN,DE"]Text for other languages[/alg_wc_pt_translate]`.
			return (
				(
					(
						! empty( $atts['lang'] ) &&
						(
							! defined( 'ICL_LANGUAGE_CODE' ) ||
							! in_array(
								strtolower( ICL_LANGUAGE_CODE ),
								array_map( 'trim', explode( ',', strtolower( $atts['lang'] ) ) ),
								true
							)
						)
					) ||
					(
						! empty( $atts['not_lang'] ) &&
						(
							defined( 'ICL_LANGUAGE_CODE' ) &&
							in_array(
								strtolower( ICL_LANGUAGE_CODE ),
								array_map( 'trim', explode( ',', strtolower( $atts['not_lang'] ) ) ),
								true
							)
						)
					)
				) ?
				'' :
				do_shortcode( wp_kses_post( $content ) )
			);
		}
	}

endif;

return new Alg_WC_Product_Tabs_Shortcodes();
