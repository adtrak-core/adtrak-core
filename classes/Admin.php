<?php
/**
 * Handler for admin functionality.
 *
 * @since      1.0.0
 * @package    AdtrakCore
 * @subpackage AdtrakCore/Classes
 * @author     Jack Whiting <jack.whiting@adtrak.co.uk>
 */

namespace AdtrakCore\Classes;

class Admin
{
	protected $version;

	public function __construct()
	{
		$this->version = '0.1';
	}

	/**
	 * Register the stylesheets for the admin area.
	 * @since    1.0.0
	 */
	public function enqueue_styles() 
	{
		wp_enqueue_style('core-admin', AC_PLUGIN_URL . 'assets/css/core-admin.css', [], $this->version, 'all');
	}

	/**
	 * Remove the default meta boxes from the wordpress admin dashboard.
	 * @since    1.0.0
	 */
	public function remove_default_meta_box()
	{
		remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
		remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
		remove_meta_box('dashboard_primary', 'dashboard', 'normal');
		remove_meta_box('dashboard_secondary', 'dashboard', 'normal');
		remove_meta_box('dashboard_activity', 'dashboard', 'normal');
		remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
		remove_meta_box('dashboard_quick_press', 'dashboard', 'normal');
	}
}