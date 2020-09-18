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
class Spacento_Properties {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Create property post type
	 *
	 * To add a class to body tag of plugin admin page	 
	 * @since    1.0.0
	 * @access   public
	 */
	public function create_property_post_type() {

		$labels = array(
			'name'                  => esc_html_x( 'Properties', 'Post type general name', 'spacento' ),
			'singular_name'         => esc_html_x( 'Property', 'Post type singular name', 'spacento' ),
			'menu_name'             => esc_html_x( 'Properties', 'Admin Menu text', 'spacento' ),
			'name_admin_bar'        => esc_html_x( 'Property', 'Add New on Toolbar', 'spacento' ),
			'add_new'               => esc_html__( 'Add New Property', 'spacento' ),
			'add_new_item'          => esc_html__( 'Add New Property', 'spacento' ),
			'new_item'              => esc_html__( 'New Property', 'spacento' ),
			'edit_item'             => esc_html__( 'Edit Property', 'spacento' ),
			'view_item'             => esc_html__( 'View Property', 'spacento' ),
			'all_items'             => esc_html__( 'All Properties', 'spacento' ),
			'search_items'          => esc_html__( 'Search Properties', 'spacento' ),
			'parent_item_colon'     => esc_html__( 'Parent Property:', 'spacento' ),
			'not_found'             => esc_html__( 'No Properties found.', 'spacento' ),
			'not_found_in_trash'    => esc_html__( 'No Properties found in Trash.', 'spacento' ),
			'featured_image'        => esc_html_x( 'Property Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'spacento' ),
			'set_featured_image'    => esc_html_x( 'Set Property image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'spacento' ),
			'remove_featured_image' => esc_html_x( 'Remove Property image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'spacento' ),
			'use_featured_image'    => esc_html_x( 'Use as Property image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'spacento' ),
			'archives'              => esc_html_x( 'Property archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'spacento' ),
			'insert_into_item'      => esc_html_x( 'Insert into Property', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'spacento' ),
			'uploaded_to_this_item' => esc_html_x( 'Uploaded to this Property', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'spacento' ),
			'filter_items_list'     => esc_html_x( 'Filter Properties', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'spacento' ),
			'items_list_navigation' => esc_html_x( 'Wishlist navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Propertys navigation”. Added in 4.4', 'spacento' ),
			'items_list'            => esc_html_x( 'Properties', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'spacento' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'property' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => true,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom-fields'. 'page-attributes' ),
			'show_in_rest' => true,
		);

		register_post_type( 'spacento_property', $args );

	}

	/**
	 * Diable gutenbery editor
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function disable_gutenberg($current_status, $post_type){
		
		if ($post_type === 'spacento_property') return false;
		return $current_status;
		
	}
	
	/**
	 * Add meta box to property post type
	 *	 
	 * @since    1.0.0
	 * @access   public
	 */
	public function create_properties_add_meta_box() {
	
		add_meta_box("spacento_property_meta_box", esc_html__( 'Spacento Settings', 'spacento' ), array($this, 'create_properties_add_meta_box_html'), 'spacento_property', "normal", "high", null);
		
	}
	
	/**
	 * Html for meta box to property post type
	 *	 
	 * @since    1.0.0
	 * @access   public
	 */
	public function create_properties_add_meta_box_html($object) {
		
		wp_nonce_field(basename(__FILE__), "spacento-property-features-nonce");
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/meta-box.php';
		
	}
	
	/**
	 * Save property meta data
	 *	 
	 * @since    1.0.0
	 * @access   public
	 */
	public function create_properties_save_meta($post_id) {

		$propertyFeatures = array();
		
		if (!isset($_POST["spacento-property-features-nonce"]) || !wp_verify_nonce($_POST["spacento-property-features-nonce"], basename(__FILE__)))
			return $post_id;

		if(!current_user_can("edit_post", $post_id))
			return $post_id;

		if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
			return $post_id;
		
		if ( isset( $_POST['post_type'] ) && 'spacento_property' === $_POST['post_type'] ) {

			if( isset($_REQUEST['spacento-property-features']) ):
			
				$spacento_features = explode(',', rtrim(strip_tags($_REQUEST['spacento-property-features']), ','));
			
				foreach( $spacento_features as $feature ):
			
					if( isset($_REQUEST[$feature . 'name']) && !empty($_REQUEST[$feature . 'name']) ):
			
						$propertyFeatures[$feature] = array(
							'name' => sanitize_text_field($_REQUEST[$feature . 'name']),
							'value' => '',
						);
			
					endif;
			
					if( isset($_REQUEST[$feature . 'value']) && !empty($_REQUEST[$feature . 'value']) ):
			
						$propertyFeatures[$feature]['value'] = sanitize_text_field($_REQUEST[$feature . 'value']);
			
					endif;			
			
				endforeach;
			
				$updatePropertyFeatures = update_post_meta( $post_id, 'spacento-property-features', wp_slash( $propertyFeatures ) );
			
				if( false === $updatePropertyFeatures){
					
					add_action( 'admin_notices', function(){
					
						?>
						<div class="error">
							<p><?php esc_html_e( 'Failed to add/update property features', 'spacento' ); ?></p>
						</div>
						<?php						
						
					} );
					
				}
				
			endif;
			
			if( isset($_REQUEST['spacento-property-gallery']) ):
			
				$updatePropertyGallery = update_post_meta( $post_id, 'spacento-property-gallery', sanitize_text_field( $_REQUEST['spacento-property-gallery'] ) );
			
				if( false === $updatePropertyGallery){
					
					add_action( 'admin_notices', function(){
					
						?>
						<div class="error">
							<p><?php esc_html_e( 'Failed to add/update property gallery', 'spacento' ); ?></p>
						</div>
						<?php						
						
					} );
					
				}			
			
			endif;

			if( isset($_REQUEST['spacento-property-price']) ):
			
				$updatePropertyPrice = update_post_meta( $post_id, 'spacento-property-price', sanitize_text_field( $_REQUEST['spacento-property-price'] ) );
			
				if( false === $updatePropertyPrice){
					
					add_action( 'admin_notices', function(){
					
						?>
						<div class="error">
							<p><?php esc_html_e( 'Failed to add/update property price', 'spacento' ); ?></p>
						</div>
						<?php						
						
					} );
					
				}		
			
			endif;		
			
			if( isset($_REQUEST['spacento-property-currency']) ):
			
				$updatePropertyPrice = update_post_meta( $post_id, 'spacento-property-currency', sanitize_text_field( $_REQUEST['spacento-property-currency'] ) );
			
				if( false === $updatePropertyPrice){
					
					add_action( 'admin_notices', function(){
					
						?>
						<div class="error">
							<p><?php esc_html_e( 'Failed to add/update property currency', 'spacento' ); ?></p>
						</div>
						<?php						
						
					} );
					
				}		
			
			endif;			

		}else{
			
			return $post_id;
			
		}		
		
	}
	
	/**
	 * Add taxonomy to property CPT
	 *	 
	 * @since    1.0.0
	 * @access   public
	 */
	public function create_properties_taxonomy() {
		
		$location_labels = array(
			'name'              => esc_html_x( 'Property Locations', 'taxonomy general name', 'spacento' ),
			'singular_name'     => esc_html_x( 'Property Location', 'taxonomy singular name', 'spacento' ),
			'search_items'      => esc_html__( 'Search Property Locations', 'spacento' ),
			'all_items'         => esc_html__( 'All Property Locations', 'spacento' ),
			'parent_item'       => esc_html__( 'Parent Property Location', 'spacento' ),
			'parent_item_colon' => esc_html__( 'Parent Property Location:', 'spacento' ),
			'edit_item'         => esc_html__( 'Edit Property Location', 'spacento' ),
			'update_item'       => esc_html__( 'Update Property Location', 'spacento' ),
			'add_new_item'      => esc_html__( 'Add New Property Location', 'spacento' ),
			'new_item_name'     => esc_html__( 'New Property Location', 'spacento' ),
			'menu_name'         => esc_html__( 'Property Location', 'spacento' ),
		);

		$location_args = array(
			'hierarchical'      => true,
			'labels'            => $location_labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'property-location' ),
		);

		register_taxonomy( 'spacento-property-location', array( 'spacento_property' ), $location_args );
		
		$type_labels = array(
			'name'              => esc_html_x( 'Property Types', 'taxonomy general name', 'spacento' ),
			'singular_name'     => esc_html_x( 'Property Type', 'taxonomy singular name', 'spacento' ),
			'search_items'      => esc_html__( 'Search Property Types', 'spacento' ),
			'all_items'         => esc_html__( 'All Property Types', 'spacento' ),
			'parent_item'       => esc_html__( 'Parent Property Type', 'spacento' ),
			'parent_item_colon' => esc_html__( 'Parent Property Type:', 'spacento' ),
			'edit_item'         => esc_html__( 'Edit Property Type', 'spacento' ),
			'update_item'       => esc_html__( 'Update Property Type', 'spacento' ),
			'add_new_item'      => esc_html__( 'Add New Property Type', 'spacento' ),
			'new_item_name'     => esc_html__( 'New Genre Property Type', 'spacento' ),
			'menu_name'         => esc_html__( 'Property Type', 'spacento' ),
		);

		$type_args = array(
			'hierarchical'      => true,
			'labels'            => $type_labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'property-type' ),
		);

		register_taxonomy( 'spacento-property-type', array( 'spacento_property' ), $type_args );			

	}
	
	public function property_template( $template ){

		$property_template = SPACENTO_PATH . 'public/templates/property.php';
		$property_theme = get_stylesheet_directory() . '/spacento/templates/property.php';
		
		if ( is_singular( 'spacento_property' ) ) {
			
			if( file_exists( $property_theme ) ){
				$template = $property_theme;
			}elseif( file_exists( $property_template ) ){
				$template = $property_template;
			}
			
		}				
		
		return $template;
		
	}	
	
	
	

}
