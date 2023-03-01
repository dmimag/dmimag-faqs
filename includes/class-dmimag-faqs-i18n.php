<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://dmimag.site
 * @since      1.0.0
 *
 * @package    Dmimag_Faqs
 * @subpackage Dmimag_Faqs/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Dmimag_Faqs
 * @subpackage Dmimag_Faqs/includes
 * @author     Anton A. Sawko <anton.sawko@gmail.com>
 */
class Dmimag_Faqs_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'dmimag-faqs',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
