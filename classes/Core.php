<?php
/**
 * Handler for functionality.
 * This class binds overs together.
 *
 * @since      1.0.0
 * @package    AdtrakCore
 * @subpackage AdtrakCore/Classes
 * @author     Jack Whiting <jack.whiting@adtrak.co.uk>
 */

namespace AdtrakCore\Classes;

use \AdtrakCore\Classes\Loader as Loader;
use \AdtrakCore\Classes\Admin as Admin;
use \AdtrakCore\Classes\CookieNotification as Cookies;
use \AdtrakCore\Classes\Cleanup as Cleanup;

class Core
{
	protected $loader;
	protected $version;

	public function __construct()
	{
		$this->version = '0.1';

		$this->loader = new Loader;

		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Register all of the hooks related to the admin-facing functionality of the plugin.
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{
		$admin = new Admin($this->version);
		$this->loader->add_action('admin_enqueue_scripts', $admin, 'enqueue_styles');
		$this->loader->add_action('admin_init', $admin, 'remove_default_meta_box');

		$cookie = new Cookies($this->version);
		$this->loader->add_action('init', $cookie, 'register_shortcodes');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality of the plugin.
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() 
	{
		$cookie = new Cookies($this->version);
		$this->loader->add_action('wp_enqueue_scripts', $cookie, 'enqueue_public_scripts');
		$this->loader->add_action('wp_enqueue_scripts', $cookie, 'enqueue_public_styles');

		# clean up head
		$cleanup = new Cleanup($this->version);
		$this->loader->add_action('init', $cleanup, 'headers');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 * @since    1.0.0
	 */
	public function run() 
	{
		$this->loader->run();
	}
}