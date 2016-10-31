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

class Core
{
	protected $loader;

	public function __construct()
	{
		$this->loader = new Loader;

		$this->define_admin_hooks();
	}

	public function define_admin_hooks()
	{
		$admin = new Admin;
		$this->loader->add_action('admin_enqueue_scripts', $admin, 'enqueue_styles');
		$this->loader->add_action('admin_init', $admin, 'remove_default_meta_box');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() 
	{
		$this->loader->run();
	}
}