<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              sproutient.com
 * @since             1.0.0
 * @package           Spacento
 *
 * @wordpress-plugin
 * Plugin Name:       Spacento
 * Plugin URI:        spacento.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Sproutient
 * Author URI:        sproutient.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       spacento
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
define( 'SPACENTO_VERSION', '1.0.0' );
define( 'SPACENTO_PATH', plugin_dir_path( __FILE__ ) );
define( 'SPACENTO_URL', plugin_dir_url( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-spacento-activator.php
 */
function activate_spacento() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-spacento-activator.php';
	Spacento_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-spacento-deactivator.php
 */
function deactivate_spacento() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-spacento-deactivator.php';
	Spacento_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_spacento' );
register_deactivation_hook( __FILE__, 'deactivate_spacento' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-spacento.php';

/**
 * Traits for our classes,
 * Responsible for rendering blocks, widgets and shortcodes.
 */
require plugin_dir_path( __FILE__ ) . 'admin/traits.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_spacento() {

	$plugin = new Spacento();
	$plugin->run();

}
run_spacento();
