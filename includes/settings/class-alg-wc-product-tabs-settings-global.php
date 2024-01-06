<?php
/**
 * Product Tabs for WooCommerce - Global Section Settings
 *
 * @version 1.6.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Product_Tabs_Settings_Global' ) ) :

class Alg_WC_Product_Tabs_Settings_Global extends Alg_WC_Product_Tabs_Settings_Section {

	/**
	 * Constructor.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function __construct() {
		$this->id   = 'global';
		$this->desc = __( 'Custom Tabs: All Products', 'product-tabs-for-woocommerce' );
		parent::__construct();
	}

	/**
	 * get_products.
	 *
	 * @version 1.6.0
	 * @since   1.0.0
	 *
	 * @todo    (dev) use AJAX instead!
	 * @todo    (dev) use `wc_get_products()`
	 * @todo    (dev) correct way to get SKU would be `$product = wc_get_product( $product_id ); $sku = $product->get_sku();`
	 */
	function get_products( $products ) {
		$do_add_sku = ( 'yes' === get_option( 'alg_custom_product_tabs_global_add_sku_to_products_list', 'no' ) );
		$product_query_args = array(
			'post_type'      => 'product',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		);
		$product_loop = new WP_Query( $product_query_args );
		foreach ( $product_loop->posts as $product_id ) {
			$products[ $product_id ] = get_the_title( $product_id ) .
				' (#' . $product_id . ')' .
				( $do_add_sku && '' !== ( $sku = get_post_meta( $product_id, '_sku', true ) ) ? ' (' . $sku . ')' : '' );
		}
		return $products;
	}

	/**
	 * get_products_show_hide_settings.
	 *
	 * @version 1.2.0
	 * @since   1.1.3
	 */
	function get_products_show_hide_settings( $values, $option_type, $products = false ) {
		switch( $option_type ) {
			case 'text_skus':
				$values['type']    = 'text';
				$values['id']     .= '_sku';
				$values['css']     = 'width:100%;';
				break;
			case 'text_ids':
				$values['type']    = 'text';
				$values['id']     .= '_id';
				$values['css']     = 'width:100%;';
				break;
			default: // 'multiselect'
				$values['type']    = 'multiselect';
				$values['class']   = 'chosen_select';
				$values['options'] = $products;
				break;
		}
		return $values;
	}

	/**
	 * get_product_terms.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function get_product_terms( $taxonomy ) {
		$product_terms  = array();
		$_product_terms = get_terms( $taxonomy, 'orderby=name&hide_empty=0' );
		if ( ! empty( $_product_terms ) && ! is_wp_error( $_product_terms ) ){
			foreach ( $_product_terms as $_product_term ) {
				$product_terms[ $_product_term->term_id ] = $_product_term->name;
			}
		}
		return $product_terms;
	}

	/**
	 * get_settings.
	 *
	 * @version 1.5.0
	 * @since   1.0.0
	 *
	 * @todo    (desc) tab "ID": better description (i.e., "... visible on frontend (i.e., link)...", "... if empty...", "... will be sanitized..."); same for "local" tabs settings
	 * @todo    (dev) `alg_custom_product_tabs_title_global_hide_` and `alg_custom_product_tabs_title_global_show_` are mislabeled, should be `alg_custom_product_tabs_global_hide_` and `alg_custom_product_tabs_global_show_`
	 * @todo    (feature) show/hide tab by *product tags*
	 * @todo    (dev) tab "ID": forbid empty and unsanitized values; same for "local" tabs settings
	 */
	function get_settings() {
		// Prepare data
		$products = ( 'multiselect' === ( $products_option_type = get_option( 'alg_custom_product_tabs_global_show_hide_products_option_type', 'multiselect' ) ) ?
			$this->get_products( array() ) : false );
		$product_cats = $this->get_product_terms( 'product_cat' );
		// Settings
		$settings = array(
			array(
				'title'     => __( 'Custom Product Tabs: All Products', 'product-tabs-for-woocommerce' ),
				'type'      => 'title',
				'desc'      => __( 'This section lets you add custom product tabs for all products globally. You can use shortcodes in tab\'s content.', 'product-tabs-for-woocommerce' ),
				'id'        => 'alg_custom_product_tabs_options',
			),
			array(
				'title'     => __( 'Custom tabs', 'product-tabs-for-woocommerce' ) . ': ' . __( 'All products', 'product-tabs-for-woocommerce' ),
				'desc'      => '<strong>' . __( 'Enable section', 'product-tabs-for-woocommerce' ) . '</strong>',
				'id'        => 'alg_wc_product_tabs_global_tabs_enabled',
				'default'   => 'yes',
				'type'      => 'checkbox',
			),
			array(
				'title'     => __( 'Custom product tabs number', 'product-tabs-for-woocommerce' ),
				'desc_tip'  => __( 'Click "Save changes" after you change this number.', 'product-tabs-for-woocommerce' ),
				'id'        => 'alg_custom_product_tabs_global_total_number',
				'default'   => 1,
				'type'      => 'number',
				'custom_attributes' => apply_filters( 'alg_wc_product_tabs_settings', array( 'readonly' => 'readonly' ), 'total' ),
				'desc'      => apply_filters( 'alg_wc_product_tabs_settings',
					sprintf( 'Get <a target="_blank" href="%s">Product Tabs for WooCommerce Pro</a> to add more than one global product tab.',
						'https://wpfactory.com/item/product-tabs-for-woocommerce-plugin/' ) ),
			),
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_custom_product_tabs_options',
			),
		);
		for ( $i = 1; $i <= apply_filters( 'alg_wc_product_tabs_global', 1 ); $i++ ) {
			$settings = array_merge( $settings,
				array(
					array(
						'title'     => __( 'Tab', 'product-tabs-for-woocommerce' ) . ' #' . $i,
						'type'      => 'title',
						'id'        => 'alg_custom_product_tabs_options_global_' . $i,
					),
					array(
						'title'     => __( 'Title', 'product-tabs-for-woocommerce' ),
						'desc_tip'  => __( 'You can use shortcodes here.', 'product-tabs-for-woocommerce' ),
						'id'        => 'alg_custom_product_tabs_title_global_' . $i,
						'default'   => '',
						'type'      => 'text',
						'css'       => 'width:100%;',
					),
					array(
						'title'     => __( 'ID', 'product-tabs-for-woocommerce' ),
						'desc_tip'  => __( 'This must be unique and cannot be empty.', 'product-tabs-for-woocommerce' ),
						'id'        => 'alg_custom_product_tabs_id_global_' . $i,
						'default'   => 'global_' . $i,
						'type'      => 'text',
					),
					array(
						'title'     => __( 'Position', 'product-tabs-for-woocommerce' ),
						'id'        => 'alg_custom_product_tabs_priority_global_' . $i,
						'default'   => ( 40 + $i - 1 ),
						'type'      => 'number',
					),
					array(
						'title'     => __( 'Content', 'product-tabs-for-woocommerce' ),
						'desc_tip'  => __( 'You can use shortcodes here.', 'product-tabs-for-woocommerce' ),
						'id'        => 'alg_custom_product_tabs_content_global_' . $i,
						'default'   => '',
						'type'      => 'textarea',
						'css'       => 'width:100%;height:200px;',
					),
					$this->get_products_show_hide_settings( array(
						'title'     => __( 'Products to hide this tab for', 'product-tabs-for-woocommerce' ),
						'desc_tip'  => __( 'To hide this tab from some products, enter products here.', 'product-tabs-for-woocommerce' ) . ' ' .
							__( 'Leave empty to show to all products.', 'product-tabs-for-woocommerce' ),
						'id'        => 'alg_custom_product_tabs_title_global_hide_in_product_ids_' . $i,
						'default'   => '',
					), $products_option_type, $products ),
					array(
						'title'     => __( 'Categories to hide this tab for', 'product-tabs-for-woocommerce' ),
						'desc_tip'  => __( 'To hide this tab from some categories, enter categories here.', 'product-tabs-for-woocommerce' ) . ' ' .
							__( 'Leave empty to show to all products.', 'product-tabs-for-woocommerce' ),
						'id'        => 'alg_custom_product_tabs_title_global_hide_in_cats_ids_' . $i,
						'default'   => '',
						'type'      => 'multiselect',
						'class'     => 'chosen_select',
						'options'   => $product_cats,
					),
					$this->get_products_show_hide_settings( array(
						'title'     => __( 'Products to show this tab for', 'product-tabs-for-woocommerce' ),
						'desc_tip'  => __( 'To show this tab only for some products, enter products here.', 'product-tabs-for-woocommerce' ) . ' ' .
							__( 'Leave empty to show to all products.', 'product-tabs-for-woocommerce' ),
						'id'        => 'alg_custom_product_tabs_title_global_show_in_product_ids_' . $i,
						'default'   => '',
					), $products_option_type, $products ),
					array(
						'title'     => __( 'Categories to show this tab for', 'product-tabs-for-woocommerce' ),
						'desc_tip'  => __( 'To show this tab only for some categories, enter categories here.', 'product-tabs-for-woocommerce' ) . ' ' .
							__( 'Leave empty to show to all products.', 'product-tabs-for-woocommerce' ),
						'id'        => 'alg_custom_product_tabs_title_global_show_in_cats_ids_' . $i,
						'default'   => '',
						'type'      => 'multiselect',
						'class'     => 'chosen_select',
						'options'   => $product_cats,
					),
					array(
						'type'      => 'sectionend',
						'id'        => 'alg_custom_product_tabs_options_global_' . $i,
					),
				)
			);
		}
		$settings = array_merge( $settings, array(
			array(
				'title'     => __( 'Advanced Options', 'product-tabs-for-woocommerce' ),
				'type'      => 'title',
				'id'        => 'alg_custom_product_tabs_global_advanced_options',
			),
			array(
				'title'     => __( 'Products Show/Hide options type', 'product-tabs-for-woocommerce' ),
				'id'        => 'alg_custom_product_tabs_global_show_hide_products_option_type',
				'default'   => 'multiselect',
				'type'      => 'select',
				'options'   => array(
					'multiselect' => __( 'Multiselect list', 'product-tabs-for-woocommerce' ),
					'text_ids'    => __( 'Comma separated text: IDs', 'product-tabs-for-woocommerce' ),
					'text_skus'   => __( 'Comma separated text: SKUs', 'product-tabs-for-woocommerce' ),
				),
			),
		) );
		if ( 'multiselect' === $products_option_type ) {
			$settings[] = array(
				'desc'      => __( 'Add SKU to products lists', 'product-tabs-for-woocommerce' ),
				'desc_tip'  => __( 'Enable this, if you want SKUs to be added to the products lists in "Products to hide this tab" and "Products to show this tab" options.' , 'product-tabs-for-woocommerce' ),
				'id'        => 'alg_custom_product_tabs_global_add_sku_to_products_list',
				'default'   => 'no',
				'type'      => 'checkbox',
			);
		}
		$settings = array_merge( $settings, array(
			array(
				'type'      => 'sectionend',
				'id'        => 'alg_custom_product_tabs_global_advanced_options',
			),
		) );
		return array_merge( $settings, $this->get_shortcodes_notes_section() );
	}

}

endif;

return new Alg_WC_Product_Tabs_Settings_Global();
