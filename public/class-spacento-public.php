<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       sproutient.com
 * @since      1.0.0
 *
 * @package    Spacento
 * @subpackage Spacento/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Spacento
 * @subpackage Spacento/public
 * @author     Sproutient <hello@sproutient.com>
 */
class Spacento_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	
	/**
	 * The JS variables.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */	
	private $jsvariables;	

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->jsvariables = array();

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Spacento_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Spacento_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/spacento.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		global $post;
		
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Spacento_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Spacento_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$this->jsvariables['nonce'] = wp_create_nonce( 'spacento' );
		$this->jsvariables['wpRestNonce'] = wp_create_nonce( 'wp_rest' );
		
		$this->jsvariables['api'] = array();
		
		$this->jsvariables['api']['base'] = get_rest_url();
		$this->jsvariables['api']['contact'] = get_rest_url(null, 'spacento/v1/contact');
		
		$this->jsvariables['messages'] = array();
		$this->jsvariables['messages']['required'] = esc_html__('Required', 'spacento');
		$this->jsvariables['messages']['submitError'] = esc_html__('Something went wrong, Please try again.', 'spacento');
		$this->jsvariables['messages']['success'] = esc_html__('Message sent. Thank you.', 'spacento');
		
		$this->jsvariables['propertyName'] = '';
		
		if( 'spacento_property' == get_post_type() ){
			$this->jsvariables['propertyName'] = esc_html(get_the_title());
		}
		
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/spacento.js', array('jquery'), $this->version, true );
		wp_localize_script( $this->plugin_name, 'spacentoJsVariables', $this->jsvariables );
		wp_enqueue_script( $this->plugin_name );
		
		//wp_enqueue_script( $this->plugin_name . '-babel', plugin_dir_url( __FILE__ ) . 'js/babel-polyfill.js', array(), $this->version, false );
		
	}

}
