<?php
/**
 * Plugin Name: Gravity Submission Modal
 * Description: Adds a please wait modal after clicking the submit button.
 * Version: 1.0
 * Author: Samuel Aguilera
 * Author URI: https://www.samuelaguilera.com
 * License: GPL-3.0+
 *
 * @package Gravity Submission Modal
 */

/*
------------------------------------------------------------------------
Copyright 2021 Samuel Aguilera

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see http://www.gnu.org/licenses.
*/

defined( 'ABSPATH' ) || die();

// Defines the current version of the Gravity Submission Modal.
define( 'GRAVITY_SUBMISSION_MODAL_VERSION', '1.0' );

// Defines the minimum version of Gravity Forms required to run Gravity Submission Modal.
define( 'GRAVITY_SUBMISSION_MODAL_MIN_GF_VERSION', '2.5' );

// After Gravity Forms is loaded, load the Add-On.
add_action( 'gform_loaded', array( 'Gravity_Submission_Modal_Bootstrap', 'load_addon' ), 5 );

/**
 * Loads the Gravity Submission Modal Add-On.
 *
 * Includes the main class and registers it with GFAddOn.
 *
 * @since 1.0
 */
class Gravity_Submission_Modal_Bootstrap {

	/**
	 * Loads the required files.
	 *
	 * @since  1.0
	 */
	public static function load_addon() {

		// Requires the class file.
		require_once plugin_dir_path( __FILE__ ) . 'class-gravity-submission-modal.php';

		// Registers the class name with GFAddOn.
		GFAddOn::register( 'Gravity_Submission_Modal' );
	}

}

/**
 * Returns an instance of the Gravity_Submission_Modal class
 *
 * @since  1.0
 *
 * @return Gravity_Submission_Modal|bool An instance of the Gravity_Submission_Modal class
 */
function gravity_submission_modal() {
	return class_exists( 'Gravity_Submission_Modal' ) ? Gravity_Submission_Modal::get_instance() : false;
}
