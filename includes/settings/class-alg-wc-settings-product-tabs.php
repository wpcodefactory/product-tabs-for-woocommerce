<?php
/**
 * Product Tabs for WooCommerce - Settings
 *
 * @version 1.5.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Alg_WC_Settings_Product_Tabs' ) ) :

class Alg_WC_Settings_Product_Tabs extends WC_Settings_Page {

	/**
	 * Constructor.
	 *
	 * @version 1.5.0
	 * @since   1.0.0
	 *
	 * @todo    (desc) better descriptions for all settings section (check plugin homepage)
	 */
	function __construct() {
		$this->id    = 'alg_product_tabs';
		$this->label = __( 'Product Tabs', 'product-tabs-for-woocommerce' );
		parent::__construct();
		// Sections
		require_once( 'class-alg-wc-product-tabs-settings-section.php' );
		require_once( 'class-alg-wc-product-tabs-settings-general.php' );
		require_once( 'class-alg-wc-product-tabs-settings-standard.php' );
		require_once( 'class-alg-wc-product-tabs-settings-global.php' );
		require_once( 'class-alg-wc-product-tabs-settings-local.php' );
		require_once( 'class-alg-wc-product-tabs-settings-variations.php' );
	}

	/**
	 * get_settings.
	 *
	 * @version 1.5.0
	 * @since   1.0.0
	 */
	function get_settings() {
		global $current_section;
		return array_merge( apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() ), array(
			array(
				'title'     => __( 'Reset Settings', 'product-tabs-for-woocommerce' ),
				'type'      => 'title',
				'id'        => $this->id . '_' . $current_section . '_reset_options',
			),
			array(
				'title'     => __( 'Reset section settings', 'product-tabs-for-woocommerce' ),
				'desc'      => '<strong>' . __( 'Reset', 'product-tabs-for-woocommerce' ) . '</strong>',
				'desc_tip'  => __( 'Check the box and save changes to reset.', 'product-tabs-for-woocommerce' ),
				'id'        => $this->id . '_' . $current_section . '_reset',
				'default'   => 'no',
				'type'      => 'checkbox',
			),
			array(
				'type'      => 'sectionend',
				'id'        => $this->id . '_' . $current_section . '_reset_options',
			),
		) );
	}

	/**
	 * maybe_reset_settings.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function maybe_reset_settings() {
		global $current_section;
		if ( 'yes' === get_option( $this->id . '_' . $current_section . '_reset', 'no' ) ) {
			foreach ( $this->get_settings() as $value ) {
				if ( isset( $value['id'] ) ) {
					$id = explode( '[', $value['id'] );
					delete_option( $id[0] );
				}
			}
			add_action( 'admin_notices', array( $this, 'admin_notice_settings_reset' ) );
		}
	}

	/**
	 * admin_notice_settings_reset.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function admin_notice_settings_reset() {
		echo '<div class="notice notice-warning is-dismissible"><p><strong>' .
			__( 'Your settings have been reset.', 'product-tabs-for-woocommerce' ) . '</strong></p></div>';
	}

	/**
	 * Save settings.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function save() {
		parent::save();
		$this->maybe_reset_settings();
	}

}

endif;

return new Alg_WC_Settings_Product_Tabs();
