<?php

class Adtrak_Updater {

	private $file;
	private $plugin;
	private $basename;
	private $active;
	private $username;
	private $repository;
	private $authorize_token;
	private $github_response;

	public function __construct ($file) 
	{
		$this->file = $file;
		add_action('admin_init', [$this, 'set_plugin_properties']);
		return $this;
	}

	public function set_plugin_properties () 
	{
		$this->plugin	= get_plugin_data( $this->file );
		$this->basename = plugin_basename( $this->file );
		$this->active	= is_plugin_active( $this->basename );
	}

	public function set_username ($username) 
	{
		$this->username = $username;
	}

	public function set_repository($repository) 
	{
		$this->repository = $repository;
	}

	public function authorize ($token) 
	{
		$this->authorize_token = $token;
	}

	private function get_repository_info ()
	{
		// Do we have a response?
	    if (is_null($this->github_response)) { 
			// Build URI
	        $request_uri = sprintf('https://api.github.com/repos/%s/%s/releases', $this->username, $this->repository); 

			// Is there an access token?
	        if ($this->authorize_token) {
				// Append it 
	            $request_uri = add_query_arg('access_token', $this->authorize_token, $request_uri); 
	        }

			// Get JSON and parse it
	        $response = json_decode(wp_remote_retrieve_body(wp_remote_get($request_uri)), true); 

			// If it is an array
	        if (is_array($response)) {
				// Get the first item
	            $response = current($response); 
	        }

			// Is there an access token?
	        if ($this->authorize_token) { 
				// Update our zip url with token
	            $response['zipball_url'] = add_query_arg('access_token', $this->authorize_token, $response['zipball_url']); 
	        }

			// Set it to our property
	        $this->github_response = $response; 
	    }
	}

	public function initialize () 
	{
		add_filter('pre_set_site_transient_update_plugins', [$this, 'modify_transient'], 10, 1 );
		add_filter('plugins_api', [$this, 'plugin_popup'], 10, 3);
		add_filter('upgrader_post_install', [$this, 'after_install'], 10, 3);
	}

	public function modify_transient ($transient)
	{
		// Check if transient has a checked property
		if (property_exists($transient, 'checked')) { 

			// Did Wordpress check for updates?
			if ($checked = $transient->checked) { 

				// Get the repo info
				$this->get_repository_info(); 
				// Check if we're out of date
				$out_of_date = version_compare($this->github_response['tag_name'], $checked[ $this->basename ], 'gt'); 

				if ($out_of_date) {
					// Get the ZIP
					$new_files = $this->github_response['zipball_url'];

					// Create valid slug
					$slug = current(explode('/', $this->basename));

					// setup our plugin info
					$plugin = [ 
						'url' 			=> $this->plugin["PluginURI"],
						'slug' 			=> $slug,
						'package' 		=> $new_files,
						'new_version' 	=> $this->github_response['tag_name']
					];

					// Return it in response
					$transient->response[$this->basename] = (object) $plugin; 
				}
			}
		}
		
		// Return filtered transient
		return $transient; 
	}

	public function plugin_popup ($result, $action, $args) 
	{
		// If there is a slug
		if (!empty($args->slug)) { 

			// And it's our slug
			if($args->slug == current(explode( '/' , $this->basename))) { 

				// Get our repo info
				$this->get_repository_info(); 

				// Set it to an array
				$plugin = [
					'name'				=> $this->plugin["Name"],
					'slug'				=> $this->basename,
					'requires'			=> '4.4',
					'tested'			=> '4.7.1',
					'rating'			=> '',
					'num_ratings'		=> '',
					'downloaded'		=> '',
					'added'				=> '2016-01-05',
					'version'			=> $this->github_response['tag_name'],
					'author'			=> $this->plugin["AuthorName"],
					'author_profile'	=> $this->plugin["AuthorURI"],
					'last_updated'		=> $this->github_response['published_at'],
					'homepage'			=> $this->plugin["PluginURI"],
					'short_description' => $this->plugin["Description"],
					'sections'			=> [
						'Description'	=> $this->plugin["Description"],
						'Updates'		=> $this->github_response['body'],
					],
					'download_link'		=> $this->github_response['zipball_url']
				];

				// Return the data
				return (object) $plugin; 
			}
		}

		// Otherwise return default
		return $result;
	}

	public function after_install ($response, $hook_extra, $result)
	{
		// Get global FS object
		global $wp_filesystem; 

		// Our plugin directory
		$install_directory = plugin_dir_path($this->file); 
		// Move files to the plugin dir
		$wp_filesystem->move($result['destination'], $install_directory); 
		// Set the destination for the rest of the stack
		$result['destination'] = $install_directory; 

 		// If it was active, reactivate
		if ($this->active) {
			activate_plugin($this->basename);
		}

		return $result;
	}
}
