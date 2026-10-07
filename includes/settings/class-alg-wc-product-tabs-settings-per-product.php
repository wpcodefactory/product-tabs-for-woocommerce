<?php
/**
 * Product Tabs for WooCommerce - Per Product Settings
 *
 * @version 1.8.0
 * @since   1.4.0
 *
 * @author WPFactory
 *
 * @package WPFactory\WC_Product_Tabs\Settings
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Product_Tabs_Settings_Per_Product' ) ) :

	/**
	 * Alg_WC_Product_Tabs_Settings_Per_Product class.
	 *
	 * @version 1.8.0
	 * @since   1.4.0
	 */
	class Alg_WC_Product_Tabs_Settings_Per_Product {

		/**
		 * Constructor.
		 *
		 * @version 1.8.0
		 * @since   1.4.0
		 *
		 * @todo (dev) Code refactoring?
		 */
		public function __construct() {
			if ( 'yes' === get_option( 'alg_custom_product_tabs_local_enabled', 'yes' ) ) {
				add_action( 'add_meta_boxes', array( $this, 'add_custom_tabs_meta_box' ) );
				add_action( 'save_post_product', array( $this, 'save_custom_tabs_meta_box' ), 100 );
			}
		}

		/**
		 * Save custom tabs meta box.
		 *
		 * @version 1.8.0
		 * @since   1.0.0
		 *
		 * @param int $product_id The ID of the product being saved.
		 */
		public function save_custom_tabs_meta_box( $product_id ) {
			// Check that we are saving with custom tab meta box displayed.
			if ( ! isset( $_POST['alg_custom_product_tabs_save_post'] ) ) {
				return;
			}

			// Autosave check.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			// Nonce check.
			if (
				! isset( $_POST['alg_wc_pt_save_product_nonce'] ) ||
				! wp_verify_nonce(
					sanitize_text_field( wp_unslash( $_POST['alg_wc_pt_save_product_nonce'] ) ),
					'alg_wc_pt_save_product'
				)
			) {
				return;
			}

			// Capability check.
			if ( ! current_user_can( 'edit_post', $product_id ) ) {
				return;
			}

			// Save: title, id, priority, content.
			$options                         = array( 'title', 'id', 'priority', 'content' );
			$default_total_custom_tabs       = get_option( 'alg_custom_product_tabs_local_total_number_default', 1 );
			$total_custom_tabs_before_saving = get_post_meta( $product_id, '_alg_custom_product_tabs_local_total_number', true );
			$total_custom_tabs_before_saving = (
				$total_custom_tabs_before_saving ?
				$total_custom_tabs_before_saving :
				$default_total_custom_tabs
			);
			for ( $i = 1; $i <= $total_custom_tabs_before_saving; $i++ ) {
				foreach ( $options as $option ) {
					$option_id = 'alg_custom_product_tabs_' . $option . '_local_' . $i;
					if ( isset( $_POST[ $option_id ] ) ) {
						update_post_meta(
							$product_id,
							'_' . $option_id,
							wp_kses_post( trim( wp_unslash( $_POST[ $option_id ] ) ) ) // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
						);
					}
				}
			}

			// Save: total custom tabs number.
			$option_id         = 'alg_custom_product_tabs_local_total_number';
			$total_custom_tabs = (
				isset( $_POST[ $option_id ] ) ?
				intval( $_POST[ $option_id ] ) :
				$default_total_custom_tabs
			);
			update_post_meta( $product_id, '_' . $option_id, $total_custom_tabs );
		}

		/**
		 * Add custom tabs meta box.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function add_custom_tabs_meta_box() {
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
		 * Create custom tabs meta box.
		 *
		 * @version 1.8.0
		 * @since   1.0.0
		 *
		 * @todo (desc) `wc_help_tip`: "Save product..." vs "Click Update..."?
		 * @todo (desc) Maybe add info about the shortcodes (i.e., `get_shortcodes_notes_section()`)?
		 */
		public function create_custom_tabs_meta_box() {
			$product_id        = get_the_ID();
			$total_custom_tabs = get_post_meta( $product_id, '_alg_custom_product_tabs_local_total_number', true );
			if ( ! $total_custom_tabs ) {
				$total_custom_tabs = get_option( 'alg_custom_product_tabs_local_total_number_default', 1 );
			}

			$option_name = 'alg_custom_product_tabs_local_total_number';

			$html = '';

			$html .= '<table>';
			$html .= '<tr>';
			$html .= '<th>';
			$html .= __( 'Total number of custom tabs', 'product-tabs-for-woocommerce' );
			$html .= '</th>';
			$html .= '<td>';
			$html .= '<input
				type="number"
				min="0"
				id="' . $option_name . '"
				name="' . $option_name . '"
				value="' . $total_custom_tabs . '"
			/>';
			$html .= '</td>';
			$html .= '<td>';
			$html .= wc_help_tip( __( 'Save product after you change this number.', 'product-tabs-for-woocommerce' ) );
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
				$data  = array();
				$html .= '<hr>';
				$html .= '<h4>' . __( 'Custom Product Tab', 'product-tabs-for-woocommerce' ) . ' #' . $i . '</h4>';
				foreach ( $options as $option ) {
					$option_id    = $option['id'] . $i;
					$option_value = get_post_meta( $product_id, '_' . $option_id, true );
					if ( ! $option_value && 'alg_custom_product_tabs_priority_local_' === $option['id'] ) {
						$option_value = 50 + $i - 1;
					}
					if ( ! $option_value && 'alg_custom_product_tabs_id_local_' === $option['id'] ) {
						$option_value = 'local_' . $i;
					}
					switch ( $option['type'] ) {
						case 'number':
						case 'text':
							$the_field = '<input
								style="' . $option['style'] . '"
								type="' . $option['type'] . '"
								id="' . $option_id . '"
								name="' . $option_id . '"
								value="' . $option_value . '"
							/>';
							break;
						case 'textarea':
							if ( 'yes' === get_option( 'alg_custom_product_tabs_local_wp_editor_enabled', 'yes' ) ) {
								ob_start();
								wp_editor( $option_value, $option_id );
								$the_field = ob_get_clean();
							} else {
								$the_field = '<textarea
									style="' . $option['style'] . '"
									id="' . $option_id . '"
									name="' . $option_id . '"
								>' .
									$option_value .
								'</textarea>';
							}
							break;
					}
					$data[] = array(
						$option['title'] .
						(
							! empty( $option['tip'] ) ?
							wc_help_tip( $option['tip'], true ) :
							''
						),
						$the_field,
					);
				}
				$html .= $this->get_table_html(
					$data,
					array(
						'table_class'        => 'widefat',
						'table_heading_type' => 'vertical',
						'columns_styles'     => array( 'width:10%;' ),
					)
				);
			}

			$html .= '<input
				type="hidden"
				name="alg_custom_product_tabs_save_post"
				value="alg_custom_product_tabs_save_post"
			/>';

			echo wp_kses(
				$html,
				$this->get_allowed_html_backend()
			);

			wp_nonce_field(
				'alg_wc_pt_save_product',
				'alg_wc_pt_save_product_nonce'
			);
		}

		/**
		 * Get table HTML.
		 *
		 * @version 1.8.0
		 * @since   1.0.0
		 *
		 * @param array $data The table data.
		 * @param array $args The table arguments.
		 *
		 * @return string The table HTML.
		 */
		public function get_table_html( $data, $args = array() ) {
			$args = array_merge(
				array(
					'table_class'        => '',
					'table_style'        => '',
					'row_styles'         => '',
					'table_heading_type' => 'horizontal',
					'columns_classes'    => array(),
					'columns_styles'     => array(),
				),
				$args
			);

			$table_class = ( '' === $args['table_class'] ) ? '' : ' class="' . $args['table_class'] . '"';
			$table_style = ( '' === $args['table_style'] ) ? '' : ' style="' . $args['table_style'] . '"';
			$row_styles  = ( '' === $args['row_styles'] ) ? '' : ' style="' . $args['row_styles'] . '"';

			$html  = '';
			$html .= '<table' . $table_class . $table_style . '>';
			$html .= '<tbody>';

			foreach ( $data as $row_nr => $row ) {
				$html .= '<tr' . $row_styles . '>';
				foreach ( $row as $column_nr => $value ) {
					$th_or_td     = (
						( 0 === $row_nr && 'horizontal' === $args['table_heading_type'] ) ||
						( 0 === $column_nr && 'vertical' === $args['table_heading_type'] )
					) ? 'th' : 'td';
					$column_class = ( ! empty( $args['columns_classes'][ $column_nr ] ) ) ? ' class="' . $args['columns_classes'][ $column_nr ] . '"' : '';
					$column_style = ( ! empty( $args['columns_styles'][ $column_nr ] ) ) ? ' style="' . $args['columns_styles'][ $column_nr ] . '"' : '';
					$html        .= '<' . $th_or_td . $column_class . $column_style . '>' . $value . '</' . $th_or_td . '>';
				}
				$html .= '</tr>';
			}

			$html .= '</tbody>';
			$html .= '</table>';

			return $html;
		}

		/**
		 * Get allowed HTML tags for the backend.
		 *
		 * @version 1.8.0
		 * @since   1.8.0
		 *
		 * @return array The allowed HTML tags for the backend.
		 */
		public function get_allowed_html_backend() {
			$allowed_html = wp_kses_allowed_html( 'post' );

			$allowed_html['button']['aria-pressed'] = true;

			$allowed_html['textarea']['autocomplete'] = true;

			$allowed_html['option'] = array(
				'value'    => true,
				'selected' => true,
			);

			$allowed_html['select'] = array(
				'multiple' => true,
				'class'    => true,
				'style'    => true,
				'id'       => true,
				'name'     => true,
				'min'      => true,
				'max'      => true,
				'disabled' => true,
			);

			$allowed_html['input'] = array(
				'class'    => true,
				'style'    => true,
				'type'     => true,
				'id'       => true,
				'name'     => true,
				'value'    => true,
				'min'      => true,
				'max'      => true,
				'disabled' => true,
			);

			return $allowed_html;
		}
	}

endif;

return new Alg_WC_Product_Tabs_Settings_Per_Product();
