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
}