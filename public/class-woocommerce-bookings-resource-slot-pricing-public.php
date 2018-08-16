<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://echo5digital.com
 * @since      1.0.0
 *
 * @package    Woocommerce_Bookings_Resource_Slot_Pricing
 * @subpackage Woocommerce_Bookings_Resource_Slot_Pricing/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Woocommerce_Bookings_Resource_Slot_Pricing
 * @subpackage Woocommerce_Bookings_Resource_Slot_Pricing/public
 * @author     Joshua Flowers <joshua@echo5digital.com>
 */
class Woocommerce_Bookings_Resource_Slot_Pricing_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Modify the booking cost if within price change rule
	 *
	 * @param int $booking_cost
	 * @param WC_Product_Booking $booking_form
	 * @param array $posted
	 * @return int
	 */
	public function modify_booking_cost( $booking_cost, $booking_form, $posted ) {
		$data = $booking_form->get_posted_data( $posted );
		$additional_cost = 0;

		if ( isset( $data['_resource_id'] ) ) {
			$resource = $booking_form->product->get_resource( $data['_resource_id'] );
			$availability_rules = $resource->get_availability();
			$processed_rules = WC_Product_Booking_Rule_Manager::process_availability_rules( $availability_rules, 'resource' );

			$timestamp = $data['_start_date'];

			while ( $timestamp < $data['_end_date'] ) {
				foreach ( $processed_rules as $key => $rule ) {
					if ( $this->check_if_timestamp_in_rule( $timestamp, $rule, false ) ) {
						$additional_cost += isset( $availability_rules[ $key ][ 'price_change' ] ) ? $availability_rules[ $key ][ 'price_change' ] * $data[ '_qty' ] : 0;
					}
				}
				$timestamp = strtotime( '+1 day', $timestamp );
			}
		}
		return $booking_cost + $additional_cost;
	}

	/**
	 * Given a timestamp and rule check to see if the time stamp is bookable based on the rule.
	 *
	 * @since 1.10.0
	 *
	 * @param integer $timestamp
	 * @param array $rule
	 * @param boolean $default
	 * @return boolean
	 */
	public static function check_if_timestamp_in_rule( $timestamp, $rule, $default ) {
		$year        = intval( date( 'Y', $timestamp ) );
		$month       = intval( date( 'n', $timestamp ) );
		$day         = intval( date( 'j', $timestamp ) );
		$day_of_week = intval( date( 'N', $timestamp ) );
		$week        = intval( date( 'W', $timestamp ) );
		$hour        = intval( date( 'H', $timestamp ) );

		$type  = $rule['type'];
		$range = $rule['range'];
		$bookable = $default;
		
		switch ( $type ) {
			case 'months':
				if ( isset( $range[ $month ] ) ) {
					$bookable = $range[ $month ];
				}
				break;
			case 'weeks':
				if ( isset( $range[ $week ] ) ) {
					$bookable = $range[ $week ];
				}
				break;
			case 'days':
				if ( isset( $range[ $day_of_week ] ) ) {
					$bookable = $range[ $day_of_week ];
				}
				break;
			case 'custom':
				if ( isset( $range[ $year ][ $month ][ $day ] ) ) {
					$bookable = $range[ $year ][ $month ][ $day ];
				}
				break;
			case 'time':
			case 'time:1':
			case 'time:2':
			case 'time:3':
			case 'time:4':
			case 'time:5':
			case 'time:6':
			case 'time:7':
				if ( false === $default && ( $day_of_week === $range['day'] || 0 === $range['day'] )
					&& $hour >= intval( $range['from'] ) && $hour < intval( $range['to'] ) ) {
					$bookable = $range['rule'];
				}
				break;
			case 'time:range':
				if ( false === $default && ( isset( $range[ $year ][ $month ][ $day ] ) ) ) {
					$bookable = $range[ $year ][ $month ][ $day ]['rule'];
				}
				break;
		}

		return $bookable;
	}

}
