<?php
/**
 * Product Tabs for WooCommerce - Per Product Settings
 *
 * @version 1.6.0
 * @since   1.4.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Product_Tabs_Settings_Per_Product' ) ) :

class Alg_WC_Product_Tabs_Settings_Per_Product {

	/**
	 * Constructor.
	 *
	 * @version 1.4.0
	 * @since   1.4.0
	 *
	 * @todo    (dev) code refactoring?
	 */
	function __construct() {
		if ( 'yes' === get_option( 'alg_custom_product_tabs_local_enabled', 'yes' ) ) {
			add_action( 'add_meta_boxes',    array( $this, 'add_custom_tabs_meta_box' ) );
			add_action( 'save_post_product', array( $this, 'save_custom_tabs_meta_box' ), 100, 2 );
		}
	}

	/**
	 * save_custom_tabs_meta_box.
	 *
	 * @version 1.6.0
	 * @since   1.0.0
	 */
	function save_custom_tabs_meta_box( $product_id, $post ) {

		// Check that we are saving with custom tab metabox displayed.
		if ( ! isset( $_POST['alg_custom_product_tabs_save_post'] ) ) {
			return;
		}

		// Save: title, id, priority, content
		$options                         = array( 'title', 'id', 'priority', 'content' );
		$default_total_custom_tabs       = apply_filters( 'alg_wc_product_tabs_local_default', 1 );
		$total_custom_tabs_before_saving = apply_filters( 'alg_wc_product_tabs_local', '', $product_id );
		$total_custom_tabs_before_saving = ( '' != $total_custom_tabs_before_saving ? $total_custom_tabs_before_saving : $default_total_custom_tabs );
		for ( $i = 1; $i <= $total_custom_tabs_before_saving; $i++ ) {
			foreach ( $options as $option ) {
				$option_id = 'alg_custom_product_tabs_' . $option . '_local_' . $i;
				if ( isset( $_POST[ $option_id ] ) ) {
					update_post_meta( $product_id, '_' . $option_id, wp_kses_post( trim( $_POST[ $option_id ] ) ) );
				}
			}
		}

		// Save: total custom tabs number
		$option_id         = 'alg_custom_product_tabs_local_total_number';
		$total_custom_tabs = ( isset( $_POST[ $option_id ] ) ? intval( $_POST[ $option_id ] ) : $default_total_custom_tabs );
		update_post_meta( $product_id, '_' . $option_id, $total_custom_tabs );
	}

	/**
	 * add_custom_tabs_meta_box.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function add_custom_tabs_meta_box() {
		add_meta_box(
			'alg-wc-product-custom-tabs',
			__( 'Custom Product Tabs', 'product-tabs-for-woocommerce' ),
			array( $this, 'create_custom_tabs_meta_box' ),
			'product',
			'normal',
			'high'
		);
	}

	/**
	 * create_custom_tabs_meta_box.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 *
	 * @todo    (desc) maybe add info about the shortcodes (i.e., `get_shortcodes_notes_section()`)?
	 */
	function create_custom_tabs_meta_box() {

		$product_id = get_the_ID();
		if ( ! ( $total_custom_tabs = apply_filters( 'alg_wc_product_tabs_local', '', $product_id ) ) ) {
			$total_custom_tabs = apply_filters( 'alg_wc_product_tabs_local_default', 1 );
		}
		$html = '';

		$html .= '<table>';
		$html .= '<tr>';
		$html .= '<th>';
		$html .= __( 'Total number of custom tabs', 'product-tabs-for-woocommerce' );
		$html .= '</th>';
		$html .= '<td>';
		$option_name = 'alg_custom_product_tabs_local_total_number';
		$html .= '<input type="number" min="0" id="' . $option_name . '" name="' . $option_name . '" value="' . $total_custom_tabs . '"' . apply_filters( 'alg_wc_product_tabs_settings', ' readonly' ) . '>';
		$html .= '</td>';
		$html .= '<td>';
		$html .= apply_filters( 'alg_wc_product_tabs_settings', sprintf( __( 'Get <a href="%s">Product Tabs for WooCommerce Pro</a> to change value.', 'product-tabs-for-woocommerce' ), 'https://wpfactory.com/item/product-tabs-for-woocommerce-plugin/' ), 'message' );
		$html .= '</td>';
		$html .= '</tr>';
		$html .= '</table>';

		$options = array(
			array(
				'id'    => 'alg_custom_product_tabs_title_local_',
				'title' => __( 'Title', 'product-tabs-for-woocommerce' ),
				'type'  => 'text',
				'style' => 'width:100%;',
			),
			array(
				'id'    => 'alg_custom_product_tabs_id_local_',
				'title' => __( 'ID', 'product-tabs-for-woocommerce' ),
				'tip'   => __( 'This must be unique and cannot be empty.', 'product-tabs-for-woocommerce' ),
				'type'  => 'text',
				'style' => 'width:100%;',
			),
			array(
				'id'    => 'alg_custom_product_tabs_priority_local_',
				'title' => __( 'Position', 'product-tabs-for-woocommerce' ),
				'type'  => 'number',
				'style' => 'width:50%;min-width:150px;',
			),
			array(
				'id'    => 'alg_custom_product_tabs_content_local_',
				'title' => __( 'Content', 'product-tabs-for-woocommerce' ),
				'type'  => 'textarea',
				'style' => 'width:100%;height:300px;',
			),
		);
		for ( $i = 1; $i <= $total_custom_tabs; $i++ ) {
			$data = array();
			$html .= '<hr>';
			$html .= '<h4>' . __( 'Custom Product Tab', 'product-tabs-for-woocommerce' ) . ' #' . $i . '</h4>';
			foreach ( $options as $option ) {
				$option_id = $option['id'] . $i;
				$option_value = get_post_meta( $product_id, '_' . $option_id, true );
				if ( ! $option_value && 'alg_custom_product_tabs_priority_local_' == $option['id'] ) {
					$option_value = 50 + $i - 1;
				}
				if ( ! $option_value && 'alg_custom_product_tabs_id_local_' == $option['id'] ) {
					$option_value = 'local_' . $i;
				}
				switch ( $option['type'] ) {
					case 'number':
					case 'text':
						$the_field = '<input style="' . $option['style'] . '" type="' . $option['type'] . '" id="' . $option_id . '" name="' . $option_id . '" value="' . $option_value . '">';
						break;
					case 'textarea':
						if ( 'yes' === get_option( 'alg_custom_product_tabs_local_wp_editor_enabled', 'yes' ) ) {
							ob_start();
							wp_editor( $option_value, $option_id );
							$the_field = ob_get_clean();
						} else {
							$the_field = '<textarea style="' . $option['style'] . '" id="' . $option_id . '" name="' . $option_id . '">' . $option_value . '</textarea>';
						}
						break;
				}
				$data[] = array( $option['title'] . ( ! empty( $option['tip'] ) ? wc_help_tip( $option['tip'], true ) : '' ), $the_field );
			}
			$html .= $this->get_table_html( $data, array( 'table_class' => 'widefat', 'table_heading_type' => 'vertical', 'columns_styles' => array( 'width:10%;', ) ) );
		}
		$html .= '<input type="hidden" name="alg_custom_product_tabs_save_post" value="alg_custom_product_tabs_save_post">';
		echo $html;
	}

	/**
	 * get_table_html.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 */
	function get_table_html( $data, $args = array() ) {
		$args = array_merge( array(
			'table_class'        => '',
			'table_style'        => '',
			'row_styles'         => '',
			'table_heading_type' => 'horizontal',
			'columns_classes'    => array(),
			'columns_styles'     => array(),
		), $args );
		$table_class = ( '' == $args['table_class'] ) ? '' : ' class="' . $args['table_class'] . '"';
		$table_style = ( '' == $args['table_style'] ) ? '' : ' style="' . $args['table_style'] . '"';
		$row_styles  = ( '' == $args['row_styles'] )  ? '' : ' style="' . $args['row_styles']  . '"';
		$html = '';
		$html .= '<table' . $table_class . $table_style . '>';
		$html .= '<tbody>';
		foreach( $data as $row_nr => $row ) {
			$html .= '<tr' . $row_styles . '>';
			foreach( $row as $column_nr => $value ) {
				$th_or_td = ( ( 0 === $row_nr && 'horizontal' === $args['table_heading_type'] ) || ( 0 === $column_nr && 'vertical' === $args['table_heading_type'] ) ) ? 'th' : 'td';
				$column_class = ( ! empty( $args['columns_classes'][ $column_nr ] ) ) ? ' class="' . $args['columns_classes'][ $column_nr ] . '"' : '';
				$column_style = ( ! empty( $args['columns_styles'][ $column_nr ] ) )  ? ' style="' . $args['columns_styles'][ $column_nr ]  . '"' : '';
				$html .= '<' . $th_or_td . $column_class . $column_style . '>' . $value . '</' . $th_or_td . '>';
			}
			$html .= '</tr>';
		}
		$html .= '</tbody>';
		$html .= '</table>';
		return $html;
	}

}

endif;

return new Alg_WC_Product_Tabs_Settings_Per_Product();
