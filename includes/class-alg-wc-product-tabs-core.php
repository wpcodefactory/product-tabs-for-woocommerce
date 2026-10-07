<?php
/**
 * Product Tabs for WooCommerce - Core Class
 *
 * @version 1.8.0
 * @since   1.0.0
 *
 * @author WPFactory
 *
 * @package WPFactory\WC_Product_Tabs
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Product_Tabs_Core' ) ) :

	/**
	 * Alg_WC_Product_Tabs_Core class.
	 *
	 * @version 1.8.0
	 * @since   1.0.0
	 */
	class Alg_WC_Product_Tabs_Core {

		/**
		 * Tab keys.
		 *
		 * @version 1.6.0
		 * @since   1.6.0
		 *
		 * @var array
		 */
		public $tab_keys;

		/**
		 * Shortcodes.
		 *
		 * @version 1.6.0
		 * @since   1.6.0
		 *
		 * @var Alg_WC_Product_Tabs_Shortcodes
		 */
		public $shortcodes;

		/**
		 * Constructor.
		 *
		 * @version 1.7.0
		 * @since   1.0.0
		 *
		 * @todo (feature) Content: `wp_oembed_get()`?
		 * @todo (feature) Content/title: Optional `do_shortcode`?
		 * @todo (feature) Customizable tab keys?
		 * @todo (dev) Save all options (global, meta) in *arrays*?
		 */
		public function __construct() {
			if ( 'yes' === get_option( 'alg_woocommerce_product_tabs_enabled', 'yes' ) ) {
				// Tab keys.
				$this->tab_keys = array();

				// Shortcodes.
				$this->shortcodes = require_once plugin_dir_path( __FILE__ ) . 'class-alg-wc-product-tabs-shortcodes.php';

				// Hooks.
				add_filter( 'woocommerce_product_tabs', array( $this, 'get_product_tabs' ), 98 );
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

				// Per product.
				require_once plugin_dir_path( __FILE__ ) . 'settings/class-alg-wc-product-tabs-settings-per-product.php';
			}
			do_action( 'alg_wc_product_tabs_core_loaded', $this );
		}

		/**
		 * Get current product ID.
		 *
		 * @version 1.4.0
		 * @since   1.4.0
		 *
		 * @todo (dev) `get_the_ID()`: Check if it's a product's ID (e.g., `get_post_type()`)?
		 * @todo (dev) Simplify this: Remove `version_compare()`?
		 * @todo (dev) Simplify this: Remove `get_parent_id()`?
		 */
		public function get_current_product_id() {
			global $product;
			if ( $product && is_a( $product, 'WC_Product' ) ) {
				return (
					version_compare( get_option( 'woocommerce_version', null ), '3.0.0', '<' ) ?
					$product->id :
					(
						$product->is_type( 'variation' ) ?
						$product->get_parent_id() :
						$product->get_id()
					)
				);
			} else {
				return get_the_ID();
			}
		}

		/**
		 * Get custom tab IDs.
		 *
		 * @version 1.4.0
		 * @since   1.3.0
		 */
		public function get_custom_tab_ids() {
			return array_merge(
				array_keys( $this->get_tabs_global() ),
				array_keys( $this->get_tabs_local() ),
				array_keys( $this->get_tabs_variations() )
			);
		}

		/**
		 * Enqueue scripts.
		 *
		 * @version 1.8.0
		 * @since   1.3.0
		 */
		public function enqueue_scripts() {
			if ( ! function_exists( 'is_product' ) || ! is_product() ) {
				return;
			}

			$custom_tab_ids = $this->get_custom_tab_ids();
			if ( ! $custom_tab_ids ) {
				return;
			}

			$min_suffix = ( defined( 'WP_DEBUG' ) && true === WP_DEBUG ? '' : '.min' );
			wp_enqueue_script(
				'alg-wc-product-tabs',
				alg_wc_product_tabs()->plugin_url() . '/assets/js/alg-wc-product-tabs' . $min_suffix . '.js',
				array( 'jquery' ),
				alg_wc_product_tabs()->version,
				true
			);

			wp_localize_script(
				'alg-wc-product-tabs',
				'alg_wc_custom_tabs',
				array( 'ids' => $custom_tab_ids )
			);
		}

		/**
		 * Get tabs standard.
		 *
		 * @version 1.8.0
		 * @since   1.0.0
		 *
		 * @param array $tabs Tabs.
		 *
		 * @return array Modified tabs array.
		 */
		public function get_tabs_standard( $tabs ) {
			if ( 'yes' !== get_option( 'alg_wc_product_tabs_standard_tabs_enabled', 'yes' ) ) {
				return $tabs;
			}

			$product_id = $this->get_current_product_id();
			if ( ! $product_id ) {
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
						// Disable.
						unset( $tabs[ $id ] );
					} else {
						// Priority.
						$tabs[ $id ]['priority'] = get_option( 'alg_product_info_product_tabs_' . $id . '_priority', $priority );
						// Title.
						$title = $this->do_shortcode( get_option( 'alg_product_info_product_tabs_' . $id . '_title', '' ), $product_id );
						if ( '' !== $title ) {
							$tabs[ $id ]['title'] = $title;
						}
					}
				}
			}

			return $tabs;
		}

		/**
		 * Get tabs global.
		 *
		 * @version 1.8.0
		 * @since   1.0.0
		 *
		 * @param array $tabs Tabs.
		 *
		 * @return array Modified tabs array.
		 *
		 * @todo (dev) Pass `$key` via `$tabs` array (similar to `alg_wc_product_tabs_product_id`) (same in `get_tabs_local()`)?
		 */
		public function get_tabs_global( $tabs = array() ) {
			if ( 'yes' !== get_option( 'alg_wc_product_tabs_global_tabs_enabled', 'yes' ) ) {
				return $tabs;
			}

			$product_id = $this->get_current_product_id();
			if ( ! $product_id ) {
				return $tabs;
			}

			$total_tabs = get_option( 'alg_custom_product_tabs_global_total_number', 1 );
			for ( $i = 1; $i <= $total_tabs; $i++ ) {
				$key   = 'global_' . $i;
				$id    = sanitize_title( get_option( 'alg_custom_product_tabs_id_global_' . $i, 'global_' . $i ) );
				$title = $this->do_shortcode( get_option( 'alg_custom_product_tabs_title_' . $key, '' ), $product_id );
				if ( '' === $id ) {
					$id = 'global_' . $i;
				}

				if ( '' !== $title && '' !== get_option( 'alg_custom_product_tabs_content_' . $key, '' ) ) {
					if ( $this->is_global_tab_visible( $i, $product_id ) ) {
						// Adding the tab.
						$tabs[ $id ] = array(
							'title'    => $title,
							'priority' => get_option( 'alg_custom_product_tabs_priority_' . $key, 40 ),
							'callback' => array( $this, 'output_tab_global' ),
							'alg_wc_product_tabs_product_id' => $product_id,
						);

						$this->tab_keys[ $id ] = $key;
					}
				}
			}

			return $tabs;
		}

		/**
		 * Get products show/hide option.
		 *
		 * @version 1.8.0
		 * @since   1.1.3
		 *
		 * @param string $option        The option name.
		 * @param mixed  $default_value The default value if the option is not set.
		 *
		 * @return mixed
		 */
		public function get_products_show_hide_option( $option, $default_value = false ) {
			$option_type = get_option( 'alg_custom_product_tabs_global_show_hide_products_option_type', 'multiselect' );
			switch ( $option_type ) {
				case 'text_skus':
					$option_value = get_option( $option . '_sku', $default_value );
					return (
						empty( $option_value ) ?
						array() :
						array_map(
							'wc_get_product_id_by_sku',
							array_map( 'trim', explode( ',', $option_value ) )
						)
					);
				case 'text_ids':
					return array_map( 'trim', explode( ',', get_option( $option . '_id', $default_value ) ) );
				default: // 'multiselect'
					return get_option( $option, $default_value );
			}
		}

		/**
		 * Is global tab visible.
		 *
		 * @version 1.8.0
		 * @since   1.3.0
		 *
		 * @param int $i          The tab index.
		 * @param int $product_id The product ID.
		 *
		 * @return bool Whether the global tab is visible.
		 */
		public function is_global_tab_visible( $i, $product_id ) {
			// Exclude by product id.
			$ids = $this->get_products_show_hide_option( 'alg_custom_product_tabs_title_global_hide_in_product_ids_' . $i );
			if (
				! empty( $ids ) &&
				in_array( (int) $product_id, array_map( 'intval', $ids ), true )
			) {
				return false;
			}

			// Exclude by product category.
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

			// Include by product id.
			$ids = $this->get_products_show_hide_option( 'alg_custom_product_tabs_title_global_show_in_product_ids_' . $i );
			if (
				! empty( $ids ) &&
				! in_array( (int) $product_id, array_map( 'intval', $ids ), true )
			) {
				return false;
			}

			// Include by product category.
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

			// Visible.
			return true;
		}

		/**
		 * Output tab global.
		 *
		 * @version 1.8.0
		 * @since   1.0.0
		 *
		 * @param string $key The tab key.
		 * @param array  $tab The product tab data.
		 *
		 * @return void
		 */
		public function output_tab_global( $key, $tab ) {
			$key = ( $this->tab_keys[ $key ] ?? $key );

			$product_id = (
				! empty( $tab['alg_wc_product_tabs_product_id'] ) ?
				$tab['alg_wc_product_tabs_product_id'] :
				false
			);

			$content = $this->do_shortcode(
				get_option( 'alg_custom_product_tabs_content_' . $key, '' ),
				$product_id
			);

			$content = $this->format_content( $content );

			echo wp_kses_post( $content );
		}

		/**
		 * Get tabs local.
		 *
		 * @version 1.8.0
		 * @since   1.0.0
		 *
		 * @param array $tabs The existing tabs.
		 *
		 * @return array The modified tabs.
		 */
		public function get_tabs_local( $tabs = array() ) {
			if ( 'yes' !== get_option( 'alg_custom_product_tabs_local_enabled', 'yes' ) ) {
				return $tabs;
			}

			$product_id = $this->get_current_product_id();
			if ( ! $product_id ) {
				return $tabs;
			}

			$total = get_post_meta( $product_id, '_alg_custom_product_tabs_local_total_number', true );
			if ( ! $total ) {
				$total = get_option( 'alg_custom_product_tabs_local_total_number_default', 1 );
			}

			for ( $i = 1; $i <= $total; $i++ ) {
				$key   = 'local_' . $i;
				$id    = sanitize_title( get_post_meta( $product_id, '_alg_custom_product_tabs_id_' . $key, true ) );
				$title = $this->do_shortcode( get_post_meta( $product_id, '_alg_custom_product_tabs_title_' . $key, true ), $product_id );
				if ( '' === $id ) {
					$id = 'local_' . $i;
				}

				if ( '' !== $title && '' !== get_post_meta( $product_id, '_alg_custom_product_tabs_content_' . $key, true ) ) {
					$priority = get_post_meta( $product_id, '_alg_custom_product_tabs_priority_' . $key, true );
					if ( ! $priority ) {
						$priority = ( 50 + $i - 1 );
					}

					// Adding the tab.
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
		 * Output tab local.
		 *
		 * @version 1.8.0
		 * @since   1.0.0
		 *
		 * @param string $key The tab key.
		 * @param array  $tab The product tab data.
		 *
		 * @return void
		 *
		 * @todo (dev) `get_the_ID()`?
		 */
		public function output_tab_local( $key, $tab ) {
			$key = ( $this->tab_keys[ $key ] ?? $key );

			$product_id = (
				! empty( $tab['alg_wc_product_tabs_product_id'] ) ?
				$tab['alg_wc_product_tabs_product_id'] :
				false
			);

			$content = $this->do_shortcode(
				get_post_meta( get_the_ID(), '_alg_custom_product_tabs_content_' . $key, true ),
				$product_id
			);

			$content = $this->format_content( $content );

			echo wp_kses_post( $content );
		}

		/**
		 * Get tabs variations.
		 *
		 * @version 1.8.0
		 * @since   1.4.0
		 *
		 * @param array $tabs The existing tabs.
		 *
		 * @return array The modified tabs.
		 */
		public function get_tabs_variations( $tabs = array() ) {
			if ( 'yes' !== get_option( 'alg_wc_product_tabs_variations_tabs_enabled', 'no' ) ) {
				return $tabs;
			}

			$product_id = $this->get_current_product_id();
			if ( ! $product_id ) {
				return $tabs;
			}

			$product = wc_get_product( $product_id );

			if ( $product && $product->is_type( 'variable' ) ) {
				foreach ( $product->get_available_variations() as $variation ) {
					if ( ! empty( $variation['variation_id'] ) ) {
						$title = get_option( 'alg_wc_product_tabs_variations_tabs_title', '[alg_wc_pt_product_function name="get_name"]' );

						$tabs[ 'variation-' . $variation['variation_id'] ] = array(
							'title'    => $this->do_shortcode( $title, $variation['variation_id'] ),
							'priority' => get_option( 'alg_wc_product_tabs_variations_tabs_priority', 100 ),
							'callback' => array( $this, 'output_tab_variation' ),
							'alg_wc_product_tabs_variation_id' => $variation['variation_id'],
						);
					}
				}
			}

			return $tabs;
		}

		/**
		 * Output tab variation.
		 *
		 * @version 1.8.0
		 * @since   1.4.0
		 *
		 * @param string $key         The tab key.
		 * @param array  $product_tab The product tab data.
		 *
		 * @return void
		 */
		public function output_tab_variation( $key, $product_tab ) {
			if ( ! empty( $product_tab['alg_wc_product_tabs_variation_id'] ) ) {
				$content = get_option(
					'alg_wc_product_tabs_variations_tabs_content',
					(
						'<h2>[alg_wc_pt_product_function name="get_name"]</h2>' . PHP_EOL .
						'<p>Price: [alg_wc_pt_product_function name="get_price_html"]</p>' . PHP_EOL .
						'<p>[alg_wc_pt_product_function name="get_description"]</p>' . PHP_EOL .
						'<p><a class="button" href="[alg_wc_pt_product_add_to_cart_url]">Add to cart</a></p>'
					)
				);

				$content = $this->do_shortcode(
					$content,
					$product_tab['alg_wc_product_tabs_variation_id']
				);

				$content = $this->format_content( $content );

				echo wp_kses_post( $content );
			}
		}

		/**
		 * Format content.
		 *
		 * @version 1.7.2
		 * @since   1.7.2
		 *
		 * @param string $content The content to format.
		 *
		 * @return string The formatted content.
		 */
		public function format_content( $content ) {
			return (
				'yes' === get_option( 'alg_wc_product_tabs_wpautop', 'no' ) ?
				wpautop( $content ) :
				$content
			);
		}

		/**
		 * Do shortcode.
		 *
		 * @version 1.4.0
		 * @since   1.4.0
		 *
		 * @param string    $content    The content.
		 * @param int|false $product_id The product ID.
		 *
		 * @return string The output after processing shortcodes.
		 */
		public function do_shortcode( $content, $product_id = false ) {
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
		 * @param array $tabs The product tabs.
		 *
		 * @return array The modified product tabs.
		 *
		 * @todo (dev) Rewrite this, i.e.: `add_filter( 'woocommerce_product_tabs', 'get_tabs_standard' )` etc.?
		 */
		public function get_product_tabs( $tabs ) {
			$tabs = $this->get_tabs_standard( $tabs );
			$tabs = $this->get_tabs_global( $tabs );
			$tabs = $this->get_tabs_local( $tabs );
			$tabs = $this->get_tabs_variations( $tabs );
			return $tabs;
		}
	}

endif;

return new Alg_WC_Product_Tabs_Core();
