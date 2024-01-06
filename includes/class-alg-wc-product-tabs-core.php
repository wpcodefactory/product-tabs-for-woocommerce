<?php
/**
 * Product Tabs for WooCommerce - Core Class
 *
 * @version 1.6.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Product_Tabs_Core' ) ) :

class Alg_WC_Product_Tabs_Core {

	/**
	 * tab_keys.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 */
	public $tab_keys;

	/**
	 * shortcodes.
	 *
	 * @version 1.6.0
	 * @since   1.6.0
	 */
	public $shortcodes;

	/**
	 * Constructor.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 *
	 * @todo    (feature) content: optional `wp_autop()`?
	 * @todo    (feature) content: `wp_oembed_get()`?
	 * @todo    (feature) content/title: optional `do_shortcode`?
	 * @todo    (feature) customizable tab keys?
	 * @todo    (dev) save all options (global, meta) in *arrays*?
	 */
	function __construct() {
		if ( 'yes' === get_option( 'alg_woocommerce_product_tabs_enabled', 'yes' ) ) {
			// Properties
			$this->tab_keys = array();
			// Shortcodes
			$this->shortcodes = require_once( 'class-alg-wc-product-tabs-shortcodes.php' );
			// Hooks
			add_filter( 'woocommerce_product_tabs', array( $this, 'get_product_tabs' ), 98 );
			add_action( 'wp_enqueue_scripts',       array( $this, 'enqueue_scripts' ) );
			// Per product
			require_once( 'settings/class-alg-wc-product-tabs-settings-per-product.php' );
		}
		do_action( 'alg_wc_product_tabs_core_loaded', $this );
	}

	/**
	 * get_current_product_id.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 *
	 * @todo    (dev) `get_the_ID()`: check if it's a product's ID (e.g., `get_post_type()`)?
	 * @todo    (dev) simplify this: remove `version_compare()`?
	 * @todo    (dev) simplify this: remove `get_parent_id()`?
	 */
	function get_current_product_id() {
		global $product;
		if ( $product && is_a( $product, 'WC_Product' ) ) {
			return ( version_compare( get_option( 'woocommerce_version', null ), '3.0.0', '<' ) ?
				$product->id : ( $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id() ) );
		} else {
			return get_the_ID();
		}
	}

	/**
	 * get_custom_tab_ids.
	 *
	 * @version 1.4.0
	 * @since   1.3.0
	 */
	function get_custom_tab_ids() {
		return array_merge( array_keys( $this->get_tabs_global() ), array_keys( $this->get_tabs_local() ), array_keys( $this->get_tabs_variations() ) );
	}

	/**
	 * enqueue_scripts.
	 *
	 * @version 1.4.0
	 * @since   1.3.0
	 */
	function enqueue_scripts() {
		if ( function_exists( 'is_product' ) && is_product() && ( $custom_tab_ids = $this->get_custom_tab_ids() ) ) {
			$min_suffix = ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ? '' : '.min' );
			wp_enqueue_script( 'class-alg-wc-product-tabs-js',
				alg_wc_product_tabs()->plugin_url() . '/includes/js/class-alg-wc-product-tabs' . $min_suffix . '.js', array( 'jquery' ), alg_wc_product_tabs()->version, true );
			wp_localize_script( 'class-alg-wc-product-tabs-js',
				'alg_wc_custom_tabs', array( 'ids' => $custom_tab_ids ) );
		}
	}

	/**
	 * get_tabs_standard.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 */
	function get_tabs_standard( $tabs ) {
		if ( 'yes' !== get_option( 'alg_wc_product_tabs_standard_tabs_enabled', 'yes' ) || ! ( $product_id = $this->get_current_product_id() ) ) {
			return $tabs;
		}
		$_tabs = array(
			'description'            => 10,
			'additional_information' => 20,
			'reviews'                => 30,
		);
		foreach ( $_tabs as $id => $priority ) {
			if ( isset( $tabs[ $id ] ) ) {
				if ( 'yes' === get_option( 'alg_product_info_product_tabs_' . $id . '_disable', 'no' ) ) {
					// Disable
					unset( $tabs[ $id ] );
				} else {
					// Priority
					$tabs[ $id ]['priority'] = get_option( 'alg_product_info_product_tabs_' . $id . '_priority', $priority );
					// Title
					if ( '' !== ( $title = $this->do_shortcode( get_option( 'alg_product_info_product_tabs_' . $id . '_title', '' ), $product_id ) ) ) {
						$tabs[ $id ]['title'] = $title;
					}
				}
			}
		}
		return $tabs;
	}

	/**
	 * get_tabs_global.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) pass `$key` via `$tabs` array (similar to `alg_wc_product_tabs_product_id`) (same in `get_tabs_local()`)?
	 */
	function get_tabs_global( $tabs = array() ) {
		if ( 'yes' !== get_option( 'alg_wc_product_tabs_global_tabs_enabled', 'yes' ) || ! ( $product_id = $this->get_current_product_id() ) ) {
			return $tabs;
		}
		for ( $i = 1; $i <= apply_filters( 'alg_wc_product_tabs_global', 1 ); $i++ ) {
			$key   = 'global_' . $i;
			$id    = sanitize_title( get_option( 'alg_custom_product_tabs_id_global_' . $i, 'global_' . $i ) );
			$title = $this->do_shortcode( get_option( 'alg_custom_product_tabs_title_' . $key, '' ), $product_id );
			if ( '' === $id ) {
				$id = 'global_' . $i;
			}
			if ( '' != $title && '' != get_option( 'alg_custom_product_tabs_content_' . $key, '' ) ) {
				if ( $this->is_global_tab_visible( $i, $product_id ) ) {
					// Adding the tab
					$tabs[ $id ] = array(
						'title'                          => $title,
						'priority'                       => get_option( 'alg_custom_product_tabs_priority_' . $key, 40 ),
						'callback'                       => array( $this, 'output_tab_global' ),
						'alg_wc_product_tabs_product_id' => $product_id,
					);
					$this->tab_keys[ $id ] = $key;
				}
			}
		}
		return $tabs;
	}

	/**
	 * get_products_show_hide_option.
	 *
	 * @version 1.1.3
	 * @since   1.1.3
	 */
	function get_products_show_hide_option( $option, $default = false ) {
		$option_type = get_option( 'alg_custom_product_tabs_global_show_hide_products_option_type', 'multiselect' );
		switch( $option_type ) {
			case 'text_skus':
				$option_value = get_option( $option . '_sku', $default );
				return ( empty( $option_value ) ? array() : array_map( 'wc_get_product_id_by_sku', array_map( 'trim', explode( ',', $option_value ) ) ) );
			case 'text_ids':
				return array_map( 'trim', explode( ',', get_option( $option . '_id', $default ) ) );
			default: // 'multiselect'
				return get_option( $option, $default );
		}
	}

	/**
	 * is_global_tab_visible.
	 *
	 * @version 1.3.0
	 * @since   1.3.0
	 */
	function is_global_tab_visible( $i, $product_id ) {
		// Exclude by product id
		$ids = $this->get_products_show_hide_option( 'alg_custom_product_tabs_title_global_hide_in_product_ids_' . $i );
		if ( ! empty( $ids ) && in_array( $product_id, $ids ) ) {
			return false;
		}
		// Exclude by product category
		$ids = get_option( 'alg_custom_product_tabs_title_global_hide_in_cats_ids_' . $i, '' );
		if ( ! empty( $ids ) ) {
			$product_cats = get_the_terms( $product_id, 'product_cat' );
			if ( ! empty( $product_cats ) && ! is_wp_error( $product_cats ) ) {
				$product_cats = wp_list_pluck( $product_cats, 'term_id' );
				$intersect    = array_intersect( $ids, $product_cats );
				if ( ! empty( $intersect ) ) {
					return false;
				}
			}
		}
		// Include by product id
		$ids = $this->get_products_show_hide_option( 'alg_custom_product_tabs_title_global_show_in_product_ids_' . $i );
		if ( ! empty( $ids ) && ! in_array( $product_id, $ids ) ) {
			return false;
		}
		// Include by product category
		$ids = get_option( 'alg_custom_product_tabs_title_global_show_in_cats_ids_' . $i, '' );
		if ( ! empty( $ids ) ) {
			$product_cats = get_the_terms( $product_id, 'product_cat' );
			if ( ! empty( $product_cats ) && ! is_wp_error( $product_cats ) ) {
				$product_cats = wp_list_pluck( $product_cats, 'term_id' );
				$intersect    = array_intersect( $ids, $product_cats );
				if ( empty( $intersect ) ) {
					return false;
				}
			}
		}
		// Visible
		return true;
	}

	/**
	 * output_tab_global.
	 *
	 * @version 1.6.0
	 * @since   1.0.0
	 */
	function output_tab_global( $key, $tab ) {
		$key        = ( $this->tab_keys[ $key ] ?? $key );
		$product_id = ( ! empty( $tab['alg_wc_product_tabs_product_id'] ) ? $tab['alg_wc_product_tabs_product_id'] : false );
		echo $this->do_shortcode( get_option( 'alg_custom_product_tabs_content_' . $key, '' ), $product_id );
	}

	/**
	 * get_tabs_local.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 */
	function get_tabs_local( $tabs = array() ) {
		if ( 'yes' !== get_option( 'alg_custom_product_tabs_local_enabled', 'yes' ) || ! ( $product_id = $this->get_current_product_id() ) ) {
			return $tabs;
		}
		if ( ! ( $total = apply_filters( 'alg_wc_product_tabs_local', '', $product_id ) ) ) {
			$total = apply_filters( 'alg_wc_product_tabs_local_default', 1 );
		}
		for ( $i = 1; $i <= $total; $i++ ) {
			$key   = 'local_' . $i;
			$id    = sanitize_title( get_post_meta( $product_id, '_' . 'alg_custom_product_tabs_id_' . $key, true ) );
			$title = $this->do_shortcode( get_post_meta( $product_id, '_' . 'alg_custom_product_tabs_title_' . $key, true ), $product_id );
			if ( '' === $id ) {
				$id = 'local_' . $i;
			}
			if ( '' != $title && '' != get_post_meta( $product_id, '_' . 'alg_custom_product_tabs_content_' . $key, true ) ) {
				if ( ! ( $priority = get_post_meta( $product_id, '_' . 'alg_custom_product_tabs_priority_' . $key, true ) ) ) {
					$priority = ( 50 + $i - 1 );
				}
				// Adding the tab
				$tabs[ $id ] = array(
					'title'                          => $title,
					'priority'                       => $priority,
					'callback'                       => array( $this, 'output_tab_local' ),
					'alg_wc_product_tabs_product_id' => $product_id,
				);
				$this->tab_keys[ $id ] = $key;
			}
		}
		return $tabs;
	}

	/**
	 * output_tab_local.
	 *
	 * @version 1.6.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) `get_the_ID()`?
	 */
	function output_tab_local( $key, $tab ) {
		$key        = ( $this->tab_keys[ $key ] ?? $key );
		$product_id = ( ! empty( $tab['alg_wc_product_tabs_product_id'] ) ? $tab['alg_wc_product_tabs_product_id'] : false );
		echo $this->do_shortcode( get_post_meta( get_the_ID(), '_' . 'alg_custom_product_tabs_content_' . $key, true ), $product_id );
	}

	/**
	 * get_tabs_variations.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	function get_tabs_variations( $tabs = array() ) {
		if ( 'yes' !== get_option( 'alg_wc_product_tabs_variations_tabs_enabled', 'no' ) || ! ( $product_id = $this->get_current_product_id() ) ) {
			return $tabs;
		}
		$product = wc_get_product( $product_id );
		if ( $product && $product->is_type( 'variable' ) ) {
			foreach ( $product->get_available_variations() as $variation ) {
				if ( ! empty( $variation['variation_id'] ) ) {
					$title = get_option( 'alg_wc_product_tabs_variations_tabs_title', '[alg_wc_pt_product_function name="get_name"]' );
					$tabs[ 'variation-' . $variation['variation_id'] ] = array(
						'title'                            => $this->do_shortcode( $title, $variation['variation_id'] ),
						'priority'                         => get_option( 'alg_wc_product_tabs_variations_tabs_priority', 100 ),
						'callback'                         => array( $this, 'output_tab_variation' ),
						'alg_wc_product_tabs_variation_id' => $variation['variation_id'],
					);
				}
			}
		}
		return $tabs;
	}

	/**
	 * output_tab_variation.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	function output_tab_variation( $key, $product_tab ) {
		if ( ! empty( $product_tab['alg_wc_product_tabs_variation_id'] ) ) {
			$content = get_option( 'alg_wc_product_tabs_variations_tabs_content', '<h2>[alg_wc_pt_product_function name="get_name"]</h2>' . PHP_EOL .
				'<p>Price: [alg_wc_pt_product_function name="get_price_html"]</p>' . PHP_EOL .
				'<p>[alg_wc_pt_product_function name="get_description"]</p>' . PHP_EOL .
				'<p><a class="button" href=\'[alg_wc_pt_product_function name="add_to_cart_url"]\'>Add to cart</a></p>' );
			echo $this->do_shortcode( $content, $product_tab['alg_wc_product_tabs_variation_id'] );
		}
	}

	/**
	 * do_shortcode.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 */
	function do_shortcode( $content, $product_id = false ) {
		$this->shortcodes->product_id = $product_id;
		$content = do_shortcode( $content );
		$this->shortcodes->product_id = false;
		return $content;
	}

	/**
	 * Customize the product tabs.
	 *
	 * @version 1.4.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) rewrite this, i.e.: `add_filter( 'woocommerce_product_tabs', 'get_tabs_standard' )` etc.?
	 */
	function get_product_tabs( $tabs ) {
		$tabs = $this->get_tabs_standard( $tabs );
		$tabs = $this->get_tabs_global( $tabs );
		$tabs = $this->get_tabs_local( $tabs );
		$tabs = $this->get_tabs_variations( $tabs );
		return $tabs;
	}

}

endif;

return new Alg_WC_Product_Tabs_Core();
