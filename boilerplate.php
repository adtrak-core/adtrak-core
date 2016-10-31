<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://adtrak.co.uk
 * @since             1.0.0
 * @package           adtrak-core
 *
 * @wordpress-plugin
 * Plugin Name: 	Adtrak Core
 * Plugin URI: 		http://plugins.adtrakdev.com/core
 * Description: 	Core functionality for WordPress Development.
 * Version: 		1.0.0
 * Author: 			Adtrak
 * Author URI: 		https://adtrak.co.uk
 * License: 		GPL-2.0+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:     adtrak-core
 * Domain Path:     /languages
 */

# if this file is called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die;
}

# autoloader
require_once( 'vendor/autoload.php' );

/**
 * The code that runs during plugin activation.
 * This action is documented in classes/Activator.php
 */
function activate_boilerplate() 
{
	\Boilerplate\Classes\Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in classes/Deactivator.php
 */
function deactivate_boilerplate() 
{
	\Boilerplate\Classes\Deactivator::deactivate();
}

# github updater
if (! class_exists('Adtrak_Updater')) {
	include_once(plugin_dir_path( __FILE__ ) . 'updater.php');
}

$updater = new Adtrak_Updater(__FILE__);
$updater->set_username('adtrak');
$updater->set_repository('adtrak-core');
$updater->authorize('2681cf527dd6cee16e94bb4c7e0623d1cc07aa1b');
$updater->initialize();

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function prefix_run_boilerplate() {
	$plugin = new \Boilerplate\Classes\Boilerplate(); 
	$plugin->run();
}

prefix_run_boilerplate();