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
 * Add Property CPT and associated meta boxes.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Spacento
 * @subpackage Spacento/admin
 * @author     Sproutient <hello@sproutient.com>
 */
class Spacento_REST_V1 {

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
	 * The helper functions for REST API.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      Class    $aux    Helper functions.
	 */	
	private $aux;
	private $contact;
	private $locations;
	private $types;
	private $propertyids;

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
		
		require_once SPACENTO_PATH . 'admin/partials/rest/v1/class-rest-aux.php';
		require_once SPACENTO_PATH . 'admin/partials/rest/v1/class-contact.php';
		require_once SPACENTO_PATH . 'admin/partials/rest/v1/class-property-location-options.php';
		require_once SPACENTO_PATH . 'admin/partials/rest/v1/class-property-type-options.php';
		require_once SPACENTO_PATH . 'admin/partials/rest/v1/class-property-ids.php';
		
		$this->rest_namespace     = 'spacento/v1';
		$this->aux     = new Spacento_REST_aux();
		$this->contact = new Spacento_Contact();
		$this->locations = new Spacento_Property_Location_Options();
		$this->types = new Spacento_Property_Type_Options();
		$this->propertyids = new Spacento_Property_Ids();

	}

	/**
	 * Add routes for the wishlist in rest api
	 *
	 * @since    1.0.0
	 */
	public function register_routes() {
	
        register_rest_route( $this->rest_namespace, '/contact', array(
            
			// Here we register the readable endpoint for collections.
            array(
                'methods'   => 'GET, POST',
                'callback'  => array( $this->contact, 'contact' ),
				'args'	=> array(
					'name' => array(
						'description'       => esc_html__( 'Name.', 'spacento' ),
						'type'              => 'string',
						'validate_callback' => array( $this->aux, 'validate_string' ),
						'sanitize_callback' => array( $this->aux, 'sanitize_string' ),
						'required'          => true,
						'default'			=> '',
					),	
					'email' => array(
						'description'       => esc_html__( 'Email.', 'spacento' ),
						'type'              => 'string',
						'validate_callback' => array( $this->aux, 'validate_string' ),
						'sanitize_callback' => array( $this->aux, 'sanitize_string' ),
						'required'          => true,
						'default'			=> '',
					),
					'phone' => array(
						'description'       => esc_html__( 'Phone.', 'spacento' ),
						'type'              => 'string',
						'validate_callback' => array( $this->aux, 'validate_string' ),
						'sanitize_callback' => array( $this->aux, 'sanitize_string' ),
						'required'          => true,
						'default'			=> '',
					),
					'property' => array(
						'description'       => esc_html__( 'Property.', 'spacento' ),
						'type'              => 'string',
						'validate_callback' => array( $this->aux, 'validate_string' ),
						'sanitize_callback' => array( $this->aux, 'sanitize_string' ),
						'required'          => true,
						'default'			=> '',
					),						
					'nonce' => array(
						'description'       => esc_html__( 'Nonce.', 'spacento' ),
						'type'              => 'bool',
						'sanitize_callback' => function($value) {
                    		return (bool) $value;
                		},
						'validate_callback' => function($value) {
                    		return wp_verify_nonce($value, 'spacento');
                		},
                		'required'	=> true,
						'default'	=> false,
					),
					
				),
                'permission_callback' => '',
            ),
            // Register our schema callback.
            'schema' => array( $this->aux, 'get_schema' ),
			
		) );
		
		register_rest_route( $this->rest_namespace, '/getproprtylocationoptions/(?P<id>\d+)', array(
            
			// Here we register the readable endpoint for collections.
			array(
				'methods'   => 'GET',
				'callback'  => array( $this->locations, 'locations' ),
				'args'	=> array(
					'id' => array(
						'description'       => esc_html__( 'Property Type.', 'spacento' ),
						'type'              => 'number',
						'validate_callback' => array( $this->aux, 'validate_number' ),
						'sanitize_callback' => array( $this->aux, 'sanitize_number' ),
						'required'          => true,
						'default'			=> '',
					),
					
				),
				'permission_callback' => '',
			),
			// Register our schema callback.
			'schema' => array( $this->aux, 'get_schema' ),
			
		) );
		
		register_rest_route( $this->rest_namespace, '/getproprtytypeoptions/(?P<id>\d+)', array(
            
			// Here we register the readable endpoint for collections.
			array(
				'methods'   => 'GET',
				'callback'  => array( $this->types, 'types' ),
				'args'	=> array(
					'id' => array(
						'description'       => esc_html__( 'Property Type.', 'spacento' ),
						'type'              => 'number',
						'validate_callback' => array( $this->aux, 'validate_number' ),
						'sanitize_callback' => array( $this->aux, 'sanitize_number' ),
						'required'          => true,
						'default'			=> '',
					),
					
				),
				'permission_callback' => '',
			),
			// Register our schema callback.
			'schema' => array( $this->aux, 'get_schema' ),
			
		) );	
		
		register_rest_route( $this->rest_namespace, '/getpropertyids', array(
            
			// Here we register the readable endpoint for collections.
			array(
				'methods'   => 'GET, POST',
				'callback'  => array( $this->propertyids, 'properties' ),
				'args'	=> array(
					'type' => array(
						'description'       => esc_html__( 'Property Type.', 'spacento' ),
						'type'              => 'string',
						'validate_callback' => array( $this->aux, 'validate_string' ),
						'sanitize_callback' => array( $this->aux, 'sanitize_string' ),
						'required'          => true,
						'default'			=> '',
					),
					'location' => array(
						'description'       => esc_html__( 'Property Location.', 'spacento' ),
						'type'              => 'string',
						'validate_callback' => array( $this->aux, 'validate_string' ),
						'sanitize_callback' => array( $this->aux, 'sanitize_string' ),
						'required'          => true,
						'default'			=> '',
					),					
				),
				'permission_callback' => '',
			),
			// Register our schema callback.
			'schema' => array( $this->aux, 'get_schema' ),
			
		) );		

	}				

}
