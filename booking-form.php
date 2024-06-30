<?php
/**
 * Plugin Name:       Booking Form
 * Description:       An interactive booking form using Interactivity API
 * Version:           0.1.0
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Author:            The WordPress Contributors
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       booking-form
 *
 * @package           booking-form
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function booking_form_block_init() {
	register_block_type_from_metadata( __DIR__ . '/build' );
}
add_action( 'init', 'booking_form_block_init' );

/**
 * Registers the booking post type.
 */
function booking_form_register_post_type() {
	register_post_type(
		'booking',
		[
			'labels'       => [
				'name'          => 'Bookings',
				'singular_name' => 'Booking',
				'add_new'       => 'Add Booking',
				'add_new_item'  => 'Add New Booking',
				'edit_item'     => 'Edit Booking',
			],
			'public'       => false,
			'show_ui'      => true,
			'show_in_menu' => true,
			'supports'     => [ 'title', 'editor' ],
			'show_in_rest' => true,
		]
	);
}
add_action( 'init', 'booking_form_register_post_type' );

/**
 * Enqueue non-module scripts.
 */
function booking_form_enqueue_scripts() {
	wp_enqueue_script( 'wp-api-fetch' );
	wp_enqueue_script( 'wp-i18n' );
}
add_action( 'wp_enqueue_scripts', 'booking_form_enqueue_scripts' );

/**
 * Registers the REST route for the booking form submission.
 */
function booking_form_register_rest_routes() {
	register_rest_route(
		'booking-form/v1',
		'/booking',
		[
			'methods'             => 'POST',
			'callback'            => function ( WP_REST_Request $request ) {
				$title   = $request->get_param( 'title' );
				$content = $request->get_param( 'content' );

				$booking_id = wp_insert_post(
					[
						'post_type'    => 'booking',
						'post_title'   => $title,
						'post_content' => $content,
					]
				);

				if ( ! $booking_id || is_wp_error( $booking_id ) ) {
					return new WP_Error( 'failed_to_create_booking', 'Failed to create booking', [ 'status' => 500 ] );
				}

				return new WP_REST_Response( [ 'id' => $booking_id ], 200 );
			},
			'permission_callback' => '__return_true',
		]
	);
}
add_action( 'rest_api_init', 'booking_form_register_rest_routes' );
