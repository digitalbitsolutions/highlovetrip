<?php
/**
 * Plugin Name:     Ship to a Different Address Checked/Unchecked for WooCommerce
 * Plugin URI:      https://wordpress.org/plugins/ship-to-a-different-address-checked-unchecked/
 * Description:     Easily set WooCommerce's 'Ship to a different address' checkbox default to checked or unchecked on the checkout page.
 * Author:          Naresh Parmar
 * Author URI:      https://profiles.wordpress.org/nareshparmar827/
 * Donate link:     https://paypal.me/NARESHBHAIPARMAR?locale.x=en_GB
 * Text Domain:     ship-to-a-different-address-checked-unchecked
 * Domain Path:     /languages
 * Version:         1.1
 * License:         GPL v2 or later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html 
 * @package         NGD_Ship_Different_Address_Checked_For_WooCommerce_Class
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

require_once 'includes/class-' . basename( __FILE__ );

/**
 * Check WooCommerce dependency on plugin activation.
 */
function ngd_ship_address_checked_unchecked_activation() {
	// If check pro plugin activated or not.
	if ( ! class_exists( 'WooCommerce' ) ) {
		// Deactivate plugin if WooCommerce is not active.
		deactivate_plugins( plugin_basename( __FILE__ ) );

		// Display error message.
		wp_die(
			esc_html__( 'Please activate WooCommerce before activating this plugin.', 'ship-to-a-different-address-checked-unchecked' ),
			esc_html__( 'Plugin dependency check', 'ship-to-a-different-address-checked-unchecked' ),
			array( 'back_link' => true )
		);
	} else {
        $option_name = 'ngd_ship_address_checked_unchecked';
        if ( get_option( $option_name ) === false ) {
            add_option( $option_name, '1', '', 'no' ); // Default checked
        } else {
            update_option( $option_name, '1' );
        }
    }
}
register_activation_hook( __FILE__, 'ngd_ship_address_checked_unchecked_activation' );

/**
 * Plugin deactivation.
 */
function ngd_ship_address_checked_unchecked_deactivation() {
	// Deactivation code here.
	$option_name = 'ngd_ship_address_checked_unchecked' ;
	 
	if ( get_option( $option_name ) !== false ) {
		delete_option( $option_name );
	}
}
register_deactivation_hook( __FILE__, 'ngd_ship_address_checked_unchecked_deactivation' );

// Add Settings link on Plugins page
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), function( $links ) {
    $settings_url = admin_url( 'admin.php?page=ngd-ship-to-a-different' );
    $settings_link = '<a href="' . esc_url( $settings_url ) . '">' . __( 'Settings', 'ship-to-a-different-address-checked-unchecked' ) . '</a>';
    array_unshift( $links, $settings_link );
    return $links;
});

/**
 * Initialization class.
 */
function ngd_ship_address_checked_unchecked_init() {
	global $ngd_ship_address_checked_unchecked;
	$ngd_ship_address_checked_unchecked = new NGD_Ship_Different_Address_Checked_For_WooCommerce_Class();
}
add_action( 'plugins_loaded', 'ngd_ship_address_checked_unchecked_init' );
