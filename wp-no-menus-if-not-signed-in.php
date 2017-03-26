<?php

/*
 * Plugin Name: WP No Menus If Not Signed In
 * Description: Don't show any WordPress menus if the current visitor isn't signed in.
 * Version:     0.1
 * Plugin URI:  https://github.com/richardtape/wp-go-home-if-not-signed-in
 * Author:      Richard Tape
 * Author URI:  https://richardtape.com/
 * Text Domain: wpnminsi
 * License:     GPL v2 or later
 * Domain Path: languages
 *
 * wpnminsi is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * wpnminsi is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with wpnminsi. If not, see <http://www.gnu.org/licenses/>.
 *
 */

// No direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// Nothing here for wp-cli
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	return;
}

/**
 * A class to prevent display of menus when a user isn't signed in.
 *
 * @since 1.0.0
 */

class WP_No_Menus_If_Not_Signed_In {

	/**
	 * Initiazlie ourselves by setting up constants and hooks
	 *
	 * @since 1.0.0
	 * @method
	 *
	 * @param null
	 * @return null
	 */

	public function init() {

		// Set up actions and filters as necessary
		$this->add_hooks();

	}/* init() */


	/**
	 * Add our hooks (actions/filters)
	 *
	 * @since 1.0.0
	 *
	 * @param null
	 * @return null
	 */

	public function add_hooks() {

		// Add action hooks
		$this->add_actions();

		// Add filter hooks
		$this->add_filters();

	}/* add_hooks() */


	/**
	 * Add our action hook(s).
	 *
	 * @since 1.0.0
	 *
	 * @param null
	 * @return null
	 */

	public function add_actions() {

	}/* add_actions() */


	/**
	 * Add our filter hook(s)
	 *
	 * @since 1.0.0
	 *
	 * @param null
	 * @return null
	 */

	public function add_filters() {

		// Hook in late to do our best to ensure other plugins don't overwrite this.
		add_filter( 'pre_wp_nav_menu', array( $this, 'pre_wp_nav_menu__hide_menu' ), 99, 2 );

	}/* add_filters() */


	/**
	 * If a user isn't signed in, we hook in to pre_wp_nav_menu to short-circuit the display.
	 * We do this by adjusting two things;
	 * 1) We return anything that isn't NULL
	 * 2) We set $args->echo to false
	 *
	 * @since 1.0.0
	 *
	 * @param string|null $output Nav menu output to short-circuit with. Default null.
	 * @param stdClass    $args   An object containing wp_nav_menu() arguments.
	 * @return null
	 */

	public function pre_wp_nav_menu__hide_menu( $output = null, $args ) {

		// Bail if we're in the admin as we only want to stop front-end menus
		if ( $this->is_admin() ) {
			return null;
		}

		// Only hide menus for those not signed in
		if ( $this->is_user_signed_in() ) {
			return null;
		}

		// Ensure that the menu isn't echod as this still gets output if the menu forces echo
		$args->echo = false;

		// We need to return something other than null
		$output = 'no';

		return $output;

	}/* pre_wp_nav_menu__hide_menu() */


	/**
	 * Determine if we're in the dashboard or not.
	 *
	 * @since 1.0.0
	 *
	 * @param null
	 * @return bool
	 */

	public function is_admin() {

		return is_admin();

	}/* is_admin() */


	/**
	 * Determine if the current visitor is signed in or not.
	 *
	 * @since 1.0.0
	 *
	 * @param null
	 * @return bool
	 */

	public function is_user_signed_in() {

		return is_user_logged_in();

	}/* is_user_signed_in() */

}/* WP_No_Menus_If_Not_Signed_In() */


// Set ourselves up.
add_action( 'plugins_loaded', 'rt_wp_no_menus_if_not_signed_in' );

/**
 * Initialize our class
 *
 * @since 1.0.0
 *
 * @param null
 * @return null
 */

function rt_wp_no_menus_if_not_signed_in() {

	$wpnminsi = new WP_No_Menus_If_Not_Signed_In();

	$wpnminsi->init();

}/* rt_wp_no_menus_if_not_signed_in() */
