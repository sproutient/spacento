<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       sproutient.com
 * @since      1.0.0
 *
 * @package    Spacento
 * @subpackage Spacento/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Spacento
 * @subpackage Spacento/admin
 * @author     Sproutient <hello@sproutient.com>
 */
class Spacento_Admin {

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
	private $blockvariables;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->jsvariables = array();
		$this->blockvariables = array();

	}

	/**
	 * Register the stylesheets for the admin area.
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
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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

		$this->jsvariables['nonce'] = esc_html(wp_create_nonce( 'spacento' ));
		$this->jsvariables['wpRestNonce'] = esc_html(wp_create_nonce( 'wp_rest' ));
		
		$this->jsvariables['api'] = array();
		
		$this->jsvariables['api']['base'] = esc_url(get_rest_url());
		$this->jsvariables['api']['deletepropertyfeature'] = esc_url(get_rest_url(null, 'spacento/v1/deletepropertyfeature'));	
		
		$this->jsvariables['api']['getPropertyLocationOptions'] = esc_url(get_rest_url(null, 'spacento/v1/getproprtylocationoptions'));
		$this->jsvariables['api']['getPropertyTypeOptions'] = esc_url(get_rest_url(null, 'spacento/v1/getproprtytypeoptions'));
		$this->jsvariables['api']['getPropertyIds'] = esc_url(get_rest_url(null, 'spacento/v1/getpropertyids'));

		$this->jsvariables['text'] = array();
		$this->jsvariables['text']['select'] = esc_html('Select', 'spacento');

		$this->jsvariables['properties'] = array();

		$this->jsvariables['pluginUrl'] = esc_url(SPACENTO_URL);
		
		$this->jsvariables['properties']['types'] = array();
		$this->jsvariables['properties']['locations'] = array();
		$this->jsvariables['properties']['property'] = array();

		$terms = get_terms([
			'taxonomy' => 'spacento-property-location',
			'hide_empty' => false,
		]);
		
		$this->jsvariables['properties']['locations'][] = array( 'value' => 'all', 'label' => __( 'All', 'spacento' ) );
		
		//var_dump($terms);
		
		foreach( $terms as $term ){
			
			$tempArray = array();
			
			$tempArray['value'] = esc_html($term->term_id);
			$tempArray['label'] = esc_html($term->name);
			
			$this->jsvariables['properties']['locations'][] = $tempArray;
			
		}			
		
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/spacento.js', array('jquery'), $this->version, true );
		wp_localize_script( $this->plugin_name, 'spacentoJsVariables', $this->jsvariables );
		wp_enqueue_script( $this->plugin_name );	

	}
	


}
