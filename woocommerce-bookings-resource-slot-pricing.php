<?php

/**
 * The plugin bootstrap file
 *
 * @link              https://echo5digital.com
 * @since             1.0.0
 * @package           Woocommerce_Bookings_Resource_Slot_Pricing
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Bookings Resource Slot Pricing
 * Plugin URI:        #
 * Description:       This plugin adds a price change field to invidual resource time slots to alter base cost.
 * Version:           1.0.0
 * Author:            Joshua Flowers
 * Author URI:        https://echo5digital.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-bookings-resource-slot-pricing
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WC_BOOKINGS_RESOURCE_SLOT_PRICING', '1.0.0' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-bookings-resource-slot-pricing.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woocommerce_bookings_resource_slot_pricing() {

	$plugin = new Woocommerce_Bookings_Resource_Slot_Pricing();
	$plugin->run();

}
run_woocommerce_bookings_resource_slot_pricing();
