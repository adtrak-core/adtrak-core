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
	
	public function __construct($version) 
	{
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 * @since    1.0.0
	 */
	public function enqueue_styles() 
	{
		// wp_enqueue_style('core-admin', AC_PLUGIN_URL . 'assets/css/core-admin.css', [], $this->version, 'all');
		wp_enqueue_style('adtrak-admin', AC_PLUGIN_URL . 'assets/css/adtrak-admin.css', [], $this->version, 'all');
		wp_enqueue_style('adtrak-default', AC_PLUGIN_URL . 'assets/css/adtrak-default.css', [], $this->version, 'all');
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

	/*
     * Add custom footer content
     */
    function adtrak_footer_content()
    {
        $footer_content = '<p>Powered by WordPress and <a href="http://adtrak.co.uk">Adtrak</a></p>';
        echo $footer_content;
        remove_filter('update_footer', 'core_update_footer');
    }

	// Function that outputs the contents of the dashboard widget
	function dashboard_widget_hello($post, $callback_args) 
	{
		include_once AC_PLUGIN_PATH . 'templates/widgets/hello.php';
	}

	// Function that outputs the contents of the dashboard widget
	function dashboard_widget_quick_links($post, $callback_args) 
	{
		// echo "Hello, this is the administration of your site. If you require assistance please get in touch!";
		include_once AC_PLUGIN_PATH . 'templates/widgets/quick-links.php';
	}

	// Function used in the action hook
	function add_dashboard_widgets() 
	{
		wp_add_dashboard_widget(
			'hello_dashboard_widget', 
			'Dashboard', 
			[$this, 'dashboard_widget_hello']
		);

		if(current_user_can('manage_options')) {
			wp_add_dashboard_widget(
				'shortcuts_dashboard_widget', 
				'Adtrak: Quick Links', 
				[$this, 'dashboard_widget_quick_links']
			);
		}
	}

	public function render_options_page()
	{
		if (function_exists('acf_add_options_page')) {

			$specific_page = acf_add_options_page([
				'page_title' 	=> 'Site Options',
				'menu_title' 	=> 'Site Options',
				'menu_slug' 	=> 'site-options',
				'position' 		=> 75,
				'capability' 	=> 'edit_themes',
				'icon_url' 		=> 'dashicons-hammer',
				'redirect' 		=> false
			]);

			$marketing_page = acf_add_options_page([
				'page_title' 	=> 'Marketing',
				'menu_title' 	=> 'Marketing',
				'menu_slug' 	=> 'marketing',
				'position' 		=> 75,
				'capability' 	=> 'edit_themes',
				'icon_url' 		=> 'dashicons-randomize',
				'redirect' 		=> false
			]);
		}

		if (function_exists('acf_add_local_field_group')) {
			include_once AC_PLUGIN_PATH . 'views/options-site.php';
			include_once AC_PLUGIN_PATH . 'views/options-marketing.php';
		}
	}
}