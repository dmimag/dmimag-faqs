<?php
/**
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://faqs.dmimag.site
 * @since             1.0.0
 * @package           Dmimag_Faqs
 *
 * @wordpress-plugin
 * Plugin Name:       DmiMag FAQs
 * Plugin URI:        https://faqs.dmimag.site
 * Description:       FAQs Plugin. FAQ. Accordion Style, Guide Style
 * Version:           1.2.5
 * Author:            dmimag <support.plugins@dmimag.site>
 * Author URI:        https://dmimag.site
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       dmimag-faqs
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
define( 'DMIMAG_FAQS_VERSION', '1.2.5' );

/**
 * Currently plugin dir.
 * 
 */
define( 'DMIMAG_FAQS_BASE_DIR', plugin_basename( __DIR__ ) );

/**
 * Currently plugin file.
 * 
 */
define( 'DMIMAG_FAQS_BASE_FILE', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-dmimag-faqs-activator.php
 */
function activate_dmimag_faqs() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-dmimag-faqs-activator.php';
  Dmimag_Faqs_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-dmimag-faqs-deactivator.php
 */
function deactivate_dmimag_faqs() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/class-dmimag-faqs-deactivator.php';
  Dmimag_Faqs_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dmimag_faqs' );
register_deactivation_hook( __FILE__, 'deactivate_dmimag_faqs' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dmimag-faqs.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_dmimag_faqs() {
  $plugin = new Dmimag_Faqs();
  $plugin->run();
}

run_dmimag_faqs();
?>