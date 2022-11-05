<?php
ob_start();
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.fiverr.com/junaidzx90
 * @since             1.0.0
 * @package           Page_Views
 *
 * @wordpress-plugin
 * Plugin Name:       Page views
 * Plugin URI:        https://www.fiverr.com
 * Description:       This is a description of the plugin.
 * Version:           1.0.0
 * Author:            Developer Junayed
 * Author URI:        https://www.fiverr.com/junaidzx90
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       page-views
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
define( 'PAGE_VIEWS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-page-views-activator.php
 */
function activate_page_views() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-page-views-activator.php';
	Page_Views_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-page-views-deactivator.php
 */
function deactivate_page_views() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-page-views-deactivator.php';
	Page_Views_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_page_views' );
register_deactivation_hook( __FILE__, 'deactivate_page_views' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-page-views.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_page_views() {

	$plugin = new Page_Views();
	$plugin->run();

}
run_page_views();
