<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://echo5digital.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Bookings_Resource_Slot_Pricing
 * @subpackage Woocommerce_Bookings_Resource_Slot_Pricing/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Woocommerce_Bookings_Resource_Slot_Pricing
 * @subpackage Woocommerce_Bookings_Resource_Slot_Pricing/admin
 * @author     Joshua Flowers <joshua@echo5digital.com>
 */
class Woocommerce_Bookings_Resource_Slot_Pricing_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Remove deault WC Bookings meta box
	 *
	 * @return void
	 */
	public function remove_default_resource_meta_box() {
		remove_meta_box( 'woocommerce-bookable-resource-data', 'bookable_resource', 'normal' );
	}

	/**
	 * Add meta boxes to resource page
	 *
	 * @return void
	 */
	public function add_resource_meta_box() {
		remove_action( 'save_post', array( 'WC_Bookable_Resource_Details_Meta_Box', 'meta_box_save' ), 10 );
		$this->meta_boxes[] = include( 'class-woocommerce-bookings-resource-slot-pricing-meta-box.php' );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 1 );
	}

	/**
	 * Loop through and add meta boxes
	 *
	 * @return void
	 */
	function add_meta_boxes() {
		foreach( $this->meta_boxes as $meta_box ) {
			add_meta_box(
				$meta_box->id,
				$meta_box->title,
				array( $meta_box, 'meta_box_inner' ),
				'bookable_resource',
				$meta_box->context,
				$meta_box->priority
			);
		}
	}



}
