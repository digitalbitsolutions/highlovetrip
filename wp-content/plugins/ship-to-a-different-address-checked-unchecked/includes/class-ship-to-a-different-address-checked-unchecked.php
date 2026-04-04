<?php
/**
 * Class for Custom Text Field support.
 *
 * @package WordPress
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// If class does not exist.
if ( ! class_exists( 'NGD_Ship_Different_Address_Checked_For_WooCommerce_Class' ) ) {

	class NGD_Ship_Different_Address_Checked_For_WooCommerce_Class {

		public function __construct() {

			add_action( 'admin_init', array( $this, 'ngd_ship_address_checked_unchecked_admin_init_fields' ) );
			add_action( 'admin_menu', array( $this, 'ngd_ship_address_checked_unchecked_register_sub_menu' ) );

			$option = get_option( 'wp_ngd_ship_option_checked', 0 );

			if ( $option == 1 ) {
				add_filter( 'woocommerce_ship_to_different_address_checked', array( $this, 'ngd_ship_address_checked_unchecked_checked_function' ) );
			} else {
				add_filter( 'woocommerce_ship_to_different_address_checked', array( $this, 'ngd_ship_address_checked_unchecked_unchecked_function' ) );
			}
		}

		/**
		 * Register settings and fields.
		 */
		public function ngd_ship_address_checked_unchecked_admin_init_fields() {
			add_settings_section(
				'wp-ngd-section',
				__( 'Settings', 'ship-to-a-different-address-checked-unchecked' ),
				null,
				'ship-to-a-different-address-checked-unchecked'
			);

			add_settings_field(
				'wp_ngd_ship_option_checked',
				'',
				array( $this, 'wp_ngd_woocommerce_ship_checked' ),
				'ship-to-a-different-address-checked-unchecked',
				'wp-ngd-section'
			);

			// Register setting with sanitization
			register_setting(
				'wp-ngd-section',
				'wp_ngd_ship_option_checked',
				array(
					'sanitize_callback' => array( $this, 'sanitize_checkbox' ),
				)
			);
		}

		/**
		 * Checkbox field output.
		 */
		public function wp_ngd_woocommerce_ship_checked() {
			$checked = get_option( 'wp_ngd_ship_option_checked', 0 ) ? 'checked' : '';
			?>
			<input type="checkbox" name="wp_ngd_ship_option_checked" id="wp_ngd_ship_option_checked" value="1" <?php echo esc_attr( $checked ); ?> />
			<label for="wp_ngd_ship_option_checked"><?php esc_html_e( 'Ship to a different address?', 'ship-to-a-different-address-checked-unchecked' ); ?></label>
			<?php
		}

		/**
		 * Sanitize checkbox value.
		 */
		public function sanitize_checkbox( $input ) {
			return $input ? 1 : 0;
		}

		/**
		 * Add submenu under WooCommerce.
		 */
		public function ngd_ship_address_checked_unchecked_register_sub_menu() {
			add_submenu_page(
				'woocommerce',
				__( 'Ship to a different address?', 'ship-to-a-different-address-checked-unchecked' ),
				__( 'Ship to a different address?', 'ship-to-a-different-address-checked-unchecked' ),
				'manage_options',
				'ngd-ship-to-a-different',
				array( $this, 'ngd_ship_address_checked_unchecked_submenu_page_callback' )
			);
		}

		/**
		 * Submenu page output.
		 */
		public function ngd_ship_address_checked_unchecked_submenu_page_callback() {
			if ( ! current_user_can( 'manage_options' ) ) {
				wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'ship-to-a-different-address-checked-unchecked' ) );
			}

			echo '<div class="wrap">';
			echo '<h2>' . esc_html( get_admin_page_title() ) . '</h2>';
			?>
			<form method="post" action="options.php">
				<?php
				settings_fields( 'wp-ngd-section' );
				do_settings_sections( 'ship-to-a-different-address-checked-unchecked' );
				submit_button();
				?>
			</form>
			<?php
			echo '</div>';
		}

		/**
		 * WooCommerce: force "Ship to a different address" checked.
		 */
		public function ngd_ship_address_checked_unchecked_checked_function() {
			add_filter( 'woocommerce_ship_to_different_address_checked', '__return_true', 999 );
		}

		/**
		 * WooCommerce: force "Ship to a different address" unchecked.
		 */
		public function ngd_ship_address_checked_unchecked_unchecked_function() {
			add_filter( 'woocommerce_ship_to_different_address_checked', '__return_false', 999 );
		}

	}
}