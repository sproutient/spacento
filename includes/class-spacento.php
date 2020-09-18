<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       sproutient.com
 * @since      1.0.0
 *
 * @package    Spacento
 * @subpackage Spacento/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Spacento
 * @subpackage Spacento/includes
 * @author     Sproutient <hello@sproutient.com>
 */
class Spacento {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Spacento_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SPACENTO_VERSION' ) ) {
			$this->version = SPACENTO_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'spacento';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Spacento_Loader. Orchestrates the hooks of the plugin.
	 * - Spacento_i18n. Defines internationalization functionality.
	 * - Spacento_Admin. Defines all hooks for the admin area.
	 * - Spacento_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-spacento-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-spacento-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-spacento-admin.php';
		
		/**
		 * The class responsible for adding property post type and its meta boxes
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-properties.php';		

		/**
		 * The class responsible for adding property blocks
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-blocks.php';		
		
		/**
		 * The class responsible for adding property widgets
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-widget.php';
		
		/**
		 * The class responsible for adding property shortcodes
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-shortcode.php';		
		
		/**
		 * The class responsible for REST API of spacento
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-rest-v1.php';			

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-spacento-public.php';
				

		$this->loader = new Spacento_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Spacento_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Spacento_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Spacento_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_properties = new Spacento_Properties( $this->get_plugin_name(), $this->get_version() );
		$plugin_rest = new Spacento_REST_V1( $this->get_plugin_name(), $this->get_version() );
		$plugin_blocks = new Spacento_Blocks( $this->get_plugin_name(), $this->get_version() );
		$plugin_widgets = new Spacento_Properties_Widget();
		$plugin_shortcodes = new Spacento_Shortcodes();
		//$plugin_widgets_test = new Foo_Widget();

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		$this->loader->add_action( 'init', $plugin_properties, 'create_property_post_type' );
		$this->loader->add_filter( 'use_block_editor_for_post_type', $plugin_properties, 'disable_gutenberg', 10, 2 );
		$this->loader->add_action( 'init', $plugin_properties, 'create_properties_taxonomy', 0 );	
		$this->loader->add_action( 'add_meta_boxes', $plugin_properties, 'create_properties_add_meta_box' );
		$this->loader->add_action( 'save_post', $plugin_properties, 'create_properties_save_meta', 10, 3 );
		
		$this->loader->add_filter( 'single_template', $plugin_properties, 'property_template' );
		
		/* Setup REST */
		$this->loader->add_action( 'rest_api_init', $plugin_rest, 'register_routes' );	
		
		/* Setup Blocks */
		$this->loader->add_action( 'enqueue_block_editor_assets', $plugin_blocks, 'admin_block_assets' );
		$this->loader->add_action( 'enqueue_block_assets', $plugin_blocks, 'block_assets' );
		$this->loader->add_action( 'init', $plugin_blocks, 'register_spacento_blocks' );
		$this->loader->add_filter( 'block_categories', $plugin_blocks, 'spacento_block_categories', 10, 2 );

		/* Widgets */
		$this->loader->add_action( 'widgets_init', $plugin_widgets, 'register' );
		//$this->loader->add_action( 'widgets_init', $plugin_widgets_test, 'register_foo' );

		/* Shortcodes */
		$this->loader->add_shortcode( 'spacento', $plugin_shortcodes, 'spacento_func' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Spacento_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Spacento_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
