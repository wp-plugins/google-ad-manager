<?php
/**
 * Plugin Name: Google Ad Console
 * Plugin URI: https://stevegrunwell.com/blog/google-ad-console
 * Description: Toggle the Google Ad Console widget within the WordPress admin bar.
 * Version: 0.1
 * Author: Steve Grunwell
 * Author URI: https://stevegrunwell.com
 * Text Domain: google-ad-console
 *
 * @package Google Ad Console
 * @author Steve Grunwell
 */

/**
 * Add the "Google Ads Console" item to the WordPress admin bar.
 *
 * @param WP_Admin_Bar $wp_admin_bar A reference to the global $wp_admin_bar object.
 */
function googleadconsole_add_node( $wp_admin_bar ) {

	// Don't display this in the admin area
	if ( is_admin() ) {
		return;
	}

	$url  = googleadconsole_toggle_console_url();
	$args = array(
		'id'    => 'google-ad-console-toggle',
		'title' => esc_html__( 'Google Ad Console', 'google-ad-console' ),
		'href'  => esc_url( $url ),
	);

	// Active state for the node
	if ( get_query_var( 'google_force_console', false ) ) {
		$args['meta'] = array(
			'class' => 'hover',
		);
	}

	/**
	 * Filter the arguments used to construct the "Google Ads Console" admin bar node.
	 *
	 * @param array $args WP admin bar node arguments.
	 */
	$args = apply_filters( 'googleadconsole_before_add_node', $args );

	$wp_admin_bar->add_node( $args );
}

add_action( 'admin_bar_menu', 'googleadconsole_add_node', 99 );

/**
 * Register the ?google_force_console var within WordPress.
 *
 * @param array $vars Registered query vars.
 * @return array The $vars array with a row for 'google_force_console' appended.
 */
function googleadconsole_register_query_var( $vars ) {
	$vars[] = 'google_force_console';

	return $vars;
}

add_filter( 'query_vars', 'googleadconsole_register_query_var' );

/**
 * Get the URL for the console toggle link.
 *
 * @return string The current URL with the console visible or hidden, depending on its current
 *                state (e.g. whatever it isn't currently).
 */
function googleadconsole_toggle_console_url() {
	$make_visible = ! get_query_var( 'google_force_console', false );

	return add_query_arg( 'google_force_console', $make_visible );
}

/**
 * Load the plugin language files.
 */
function googleadconsole_load_plugin_textdomain() {
	load_plugin_textdomain(
		'google-ad-console',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);
}
add_action( 'plugins_loaded', 'googleadconsole_load_plugin_textdomain' );