<?php
/**
 * Product Tabs for WooCommerce - Shortcodes Class
 *
 * @version 1.6.0
 * @since   1.4.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Product_Tabs_Shortcodes' ) ) :

class Alg_WC_Product_Tabs_Shortcodes {

	/**
	 * product_id.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 */
	public $product_id;

	/**
	 * Constructor.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 *
	 * @todo    (feature) add `[alg_wc_pt_option]` shortcode
	 * @todo    (feature) add aliases, e.g., `[alg_wc_pt_product_price]`, `[alg_wc_pt_product_description]`, etc.?
	 */
	function __construct() {
		add_shortcode( 'alg_wc_pt_product_function',     array( $this, 'product_function' ) );
		add_shortcode( 'alg_wc_pt_product_meta',         array( $this, 'product_meta' ) );
		add_shortcode( 'alg_wc_pt_translate',            array( $this, 'translate' ) );
		add_shortcode( 'alg_wc_cpt_translate',           array( $this, 'translate' ) ); // deprecated
	}

	/**
	 * get_product_id.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	function get_product_id( $atts ) {
		return ( ! empty( $atts['product_id'] ) ? $atts['product_id'] : ( ! empty( $this->product_id ) ? $this->product_id : false ) );
	}

	/**
	 * output.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 *
	 * @todo    (feature) optional formatting, e.g., `wc_price()` (e.g., `$atts['format_func']` or `$atts['output_func']`)
	 * @todo    (feature) add `on_zero` att?
	 * @todo    (feature) add `lang` and `not_lang` atts?
	 */
	function output( $content, $atts ) {
		if ( '' === $content || false === $content ) {
			return ( isset( $atts['on_empty'] ) ? $atts['on_empty'] : '' );
		} else {
			return ( isset( $atts['before'] ) ? $atts['before'] : '' ) . $content . ( isset( $atts['after'] ) ? $atts['after'] : '' );
		}
	}

	/**
	 * product_function.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	function product_function( $atts, $content = '' ) {
		if ( isset( $atts['name'] ) && ( $product_id = $this->get_product_id( $atts ) ) && ( $product = wc_get_product( $product_id ) ) ) {
			$func = $atts['name'];
			if ( isset( $atts['type'] ) && 'global' === $atts['type'] ) {
				if ( function_exists( $func ) ) {
					return $this->output( $func( $product ), $atts );
				}
			} else { // 'local' === $atts['type']
				if ( is_callable( array( $product, $atts['name'] ) ) ) {
					return $this->output( $product->$func(), $atts );
				}
			}
		}
	}

	/**
	 * product_meta.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	function product_meta( $atts, $content = '' ) {
		if ( isset( $atts['key'] ) && ( $product_id = $this->get_product_id( $atts ) ) ) {
			return $this->output( get_post_meta( $product_id, $atts['key'], true ), $atts );
		}
	}

	/**
	 * translate.
	 *
	 * @version 1.4.0
	 * @since   1.3.0
	 */
	function translate( $atts, $content = '' ) {
		// E.g.: `[alg_wc_pt_translate lang="EN,DE" lang_text="Text for EN & DE" not_lang_text="Text for other languages"]`
		if ( isset( $atts['lang_text'] ) && isset( $atts['not_lang_text'] ) && ! empty( $atts['lang'] ) ) {
			return ( ! defined( 'ICL_LANGUAGE_CODE' ) || ! in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['lang'] ) ) ) ) ) ?
				$atts['not_lang_text'] : $atts['lang_text'];
		}
		// E.g.: `[alg_wc_pt_translate lang="EN,DE"]Text for EN & DE[/alg_wc_pt_translate][alg_wc_pt_translate not_lang="EN,DE"]Text for other languages[/alg_wc_pt_translate]`
		return (
			( ! empty( $atts['lang'] )     && ( ! defined( 'ICL_LANGUAGE_CODE' ) || ! in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['lang'] ) ) ) ) ) ) ||
			( ! empty( $atts['not_lang'] ) &&     defined( 'ICL_LANGUAGE_CODE' ) &&   in_array( strtolower( ICL_LANGUAGE_CODE ), array_map( 'trim', explode( ',', strtolower( $atts['not_lang'] ) ) ) ) )
		) ? '' : $content;
	}

}

endif;

return new Alg_WC_Product_Tabs_Shortcodes();
