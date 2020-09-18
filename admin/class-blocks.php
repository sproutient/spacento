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
 * Add Property CPT.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Spacento
 * @subpackage Spacento/admin
 * @author     Sproutient <hello@sproutient.com>
 */
class Spacento_Blocks {

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
	 * Register the JavaScript/CSS for the blocks in editor.
	 *
	 * @since    1.0.0
	 */
	public function admin_block_assets() {

		$this->blockvariables['api'] = array();
		$this->blockvariables['api']['getPropertyLocationOptions'] = esc_url(get_rest_url(null, 'spacento/v1/getproprtylocationoptions'));
		$this->blockvariables['api']['getPropertyTypeOptions'] = esc_url(get_rest_url(null, 'spacento/v1/getproprtytypeoptions'));
		$this->blockvariables['api']['getPropertyIds'] = esc_url(get_rest_url(null, 'spacento/v1/getpropertyids'));

		$this->blockvariables['properties'] = array();
		
		$this->blockvariables['properties']['types'] = array();
		$this->blockvariables['properties']['locations'] = array();
		$this->blockvariables['properties']['property'] = array();
		
		$terms = get_terms([
			'taxonomy' => 'spacento-property-type',
			'hide_empty' => false,
		]);
		
		$this->blockvariables['properties']['types'][] = array( 'value' => 'all', 'label' => esc_html__( 'All', 'spacento' ) );
		
		//var_dump($terms);
		
		foreach( $terms as $term ){
			
			$tempArray = array();
			
			$tempArray['value'] = esc_html($term->term_id);
			$tempArray['label'] = esc_html($term->name);
			
			$this->blockvariables['properties']['types'][] = $tempArray;
			
		}
		
		$terms = get_terms([
			'taxonomy' => 'spacento-property-location',
			'hide_empty' => false,
		]);
		
		$this->blockvariables['properties']['locations'][] = array( 'value' => 'all', 'label' => esc_html__( 'All', 'spacento' ) );
		
		//var_dump($terms);
		
		foreach( $terms as $term ){
			
			$tempArray = array();
			
			$tempArray['value'] = esc_html($term->term_id);
			$tempArray['label'] = esc_html($term->name);
			
			$this->blockvariables['properties']['locations'][] = $tempArray;
			
		}	
		
		$this->blockvariables['properties']['propertiesids'][] = array( 'value' => 'all', 'label' => esc_html__( 'All', 'spacento' ) );
		$propArray = array(
			'post_type'   => 'spacento_property',
			'fields' => 'ids'					
		);
		$tempPosts = get_posts( $propArray );
		foreach( $tempPosts as $post ){
			
			$tempPost = get_post($post);
			$tempArray = array();
			$tempArray['value'] = esc_html($tempPost->ID);
			$tempArray['label'] = esc_html($tempPost->post_title);
			
			$this->blockvariables['properties']['propertiesids'][] = $tempArray;
			
		}
		
		$this->blockvariables['pluginUrl'] = esc_url(SPACENTO_URL);
		
		wp_enqueue_style( $this->plugin_name . '-editor', esc_url(SPACENTO_URL) . 'admin/css/spacento-blocks.css', array( 'wp-edit-blocks' ), $this->version, 'all' );
		//wp_enqueue_style( $this->plugin_name . '-blocks', esc_url(SPACENTO_URL) . 'public/css/spacento-blocks.css', array( 'wp-blocks' ), $this->version, 'all' );
		
		//wp_enqueue_script( $this->plugin_name . '-velocity', '//cdnjs.cloudflare.com/ajax/libs/velocity/1.5.2/velocity.min.js', array(), $this->version, false );
		//wp_enqueue_script( $this->plugin_name . '-components', esc_url(SPACENTO_URL) . 'admin/js/spacento-components.js', array(), $this->version, true );
		wp_register_script( $this->plugin_name . '-editor', esc_url(SPACENTO_URL) . 'admin/js/spacento-blocks.js', array( 'jquery', 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components' ), $this->version, true );
		wp_localize_script( $this->plugin_name . '-editor', 'spacentoBlockVariables', $this->blockvariables );
		wp_enqueue_script( $this->plugin_name . '-editor' );

	}
	
	/**
	 * Register the JavaScript/CSS for the blocks.
	 *
	 * @since    1.0.0
	 */
	public function block_assets() {

		wp_enqueue_style( $this->plugin_name . '-blocks-css', esc_url(SPACENTO_URL) . 'public/css/spacento-blocks.css', array(), $this->version, 'all' );
		
		wp_register_script( $this->plugin_name . '-blocks', esc_url(SPACENTO_URL) . 'public/js/spacento-blocks.js', array( 'jquery', 'wp-blocks', 'wp-polyfill' ), $this->version, true );
		wp_enqueue_script( $this->plugin_name . '-blocks' );

	}	

	public function register_spacento_blocks() {
		
		register_block_type(
			'spacento/properties',
			array(

				'attributes'  => array(
					'propertyType'  => array(
						'type' => 'string',
						'default' => 'all',
					),
					'propertyLocation'  => array(
						'type' => 'string',
						'default' => 'all',
					),
					'property'  => array(
						'type' => 'string',
					),
					'layout'  => array(
						'type' => 'string',
						'default'   => 'layoutOne',
					),															
				),				
				'render_callback' => array( $this, 'spacento_properties_render'),
				
			)
		);
		
	}

	function limitedstring($output, $max_char=100){

	

		$output = str_replace(']]>', ']]&gt;', $output);
	
		$output = strip_tags($output);
	
		$output = strip_shortcodes($output);
	
	
	
		  if ((strlen($output)>$max_char)){
	
			$output = substr($output, 0, $max_char);
	
			return $output;
	
		   }else{
	
			  return $output;
	
		   }
	
		
	
	}	

	public function spacento_property_render($args){

		$block_content = '';

		$block_content .= '<div class="spacento-property-container">';

		$block_content .= 	'<div class="spacento-property-media">';
		$block_content .= 	'<img src="' . esc_url($args['image']) . '"/>';
		$block_content .= 	'</div>';

		$block_content .= 	'<div class="spacento-property-content">';

		$block_content .=	 	'<h2>' . esc_html($args['title']) . '</h2>';
		$block_content .=	 	'<p>' . esc_html($args['text']) . '</p>';
		if( '' != $args['price'] ):
		$block_content .=	 	'<div class="spacento-property-price">';	
		$block_content .=	 	'<p><span>' . esc_html($args['currency']) . '</span>' . esc_html($args['price']) . '</p>';
		$block_content .=	 	'</div">';
		endif;
		$block_content .=	 	'<p><a href="'. esc_url($args['url']) . '" >' . esc_html('View Details', 'spacento') . '</a></p>';
		
		
		$block_content .= 	'</div>';		

		$block_content .= '</div>';

		return $block_content;		
		
	}	
	
	public function spacento_properties_render($args){

		$block_content = '';
		$blockLayout = 'layoutOne';
		if( array_key_exists("layout", $args ) && "" != $args['layout']){
			$blockLayout = strip_tags($args["layout"]);
		}

		$block_content_actual = '';

		$propertyType = '';
		$propertyLocation = '';

		$fetchAllPropertiesSwitch = false;
		if(

			( array_key_exists("propertyType", $args ) && "" != $args['propertyType'] && "all" == $args['propertyType'] ) &&
			( array_key_exists("propertyLocation", $args ) && "" != $args['propertyLocation'] && "all" == $args['propertyLocation'] )

		){

			$fetchAllPropertiesSwitch = true;

		}

		if( array_key_exists("propertyType", $args ) && "" != $args['propertyType'] && "all" != $args['propertyType'] ){
			$propertyType = $args['propertyType'];
			$propertyType = absint((int)$propertyType);
		}

		if( array_key_exists("propertyLocation", $args ) && "" != $args['propertyLocation'] && "all" != $args['propertyLocation'] ){
			$propertyLocation = $args['propertyLocation'];
			$propertyLocation = absint((int)$propertyLocation);
		}	
		

		if( array_key_exists("property", $args ) && "" != $args["property"] && "all" != $args["property"] ):

			$tempPostId = $args["property"];
			$tempPostId = absint((int)$tempPostId);
			$tempPost = get_post($tempPostId);

			$propertyGallery = get_post_meta( $tempPostId, 'spacento-property-gallery', true );
			$propertyPrice = '';
			$propertyCurrency = '';			
			$propertyPrice = get_post_meta( $tempPostId, 'spacento-property-price', true );
			$propertyCurrency = get_post_meta( $tempPostId, 'spacento-property-currency', true );			

			if(!empty($propertyGallery)):

				$currentPropertGallery =  rtrim($propertyGallery, ',');
				$currentPropertGallery = explode(',', $currentPropertGallery);	
				
			endif;

			$tempPostArray = array();
			$tempPostArray['title'] = esc_html($tempPost->post_title);
			$tempPostArray['image'] = esc_url(wp_get_attachment_url($currentPropertGallery[0]));
			$tempPostArray['text'] = esc_html($tempPost->post_excerpt);
			$tempPostArray['url'] = esc_url(get_permalink($tempPostId));
			$tempPostArray['currency'] = esc_html($propertyCurrency);
			$tempPostArray['price'] = esc_html($propertyPrice);

			if(empty($tempPostArray['text'])){
				$tempPostArray['text'] = esc_html($this->limitedstring($tempPost->post_content, 200));
			}

			$block_content_actual = $this->spacento_property_render($tempPostArray);

		else:

			if( !empty($propertyType) || !empty($propertyLocation) || $fetchAllPropertiesSwitch ):
		
				if( empty($propertyType) && !empty($propertyLocation) ):
	
					$propArray = array(
						'post_type'   => 'spacento_property',
						'tax_query' => array(
							array(
								'taxonomy' => 'spacento-property-location',
								'field'    => 'term_id',
								'terms'    => array( $propertyLocation ),
							)
						),
						'fields' => 'ids'					
					);
	
				elseif( !empty($propertyType) && empty($propertyLocation) ):
	
					$propArray = array(
						'post_type'   => 'spacento_property',
						'tax_query' => array(
							array(
								'taxonomy' => 'spacento-property-type',
								'field'    => 'term_id',
								'terms'    => array( $propertyType ),
							)
						),
						'fields' => 'ids'					
					);				
	
				elseif( !empty($propertyType) && !empty($propertyLocation) ):
	
					$propArray = array(
						'post_type'   => 'spacento_property',
						'tax_query' => array(
							'relation' => 'AND',
							array(
								'taxonomy' => 'spacento-property-type',
								'field'    => 'term_id',
								'terms'    => array( $propertyType ),
							),
							array(
								'taxonomy' => 'spacento-property-location',
								'field'    => 'term_id',
								'terms'    => array( $propertyLocation ),
							)						
						),
						'fields' => 'ids'					
					);
					
				elseif($fetchAllPropertiesSwitch):
					
					$propArray = array(
						'post_type'   => 'spacento_property',
						'fields' => 'ids'					
					);					
	
				endif;
	
				$tempPosts = get_posts( $propArray );
	
				 if( !empty($tempPosts)): foreach( $tempPosts as $id ):
	
					$tempPost = get_post($id);

					$propertyGallery = get_post_meta( $id, 'spacento-property-gallery', true );

					$propertyPrice = '';
					$propertyCurrency = '';			
					$propertyPrice = get_post_meta( get_the_ID(), 'spacento-property-price', true );
					$propertyCurrency = get_post_meta( get_the_ID(), 'spacento-property-currency', true );

					if(!empty($propertyGallery)):
		
						$currentPropertGallery =  rtrim($propertyGallery, ',');
						$currentPropertGallery = explode(',', $currentPropertGallery);	
						
					endif;					

					$tempPostArray = array();
					$tempPostArray['title'] = $tempPost->post_title;
					$tempPostArray['image'] = wp_get_attachment_url($currentPropertGallery[0]);
					$tempPostArray['text'] = $tempPost->post_excerpt;
					$tempPostArray['url'] = get_permalink($id);
					$tempPostArray['currency'] = esc_html($propertyCurrency);
					$tempPostArray['price'] = esc_html($propertyPrice);					
		
					if(empty($tempPostArray['text'])){
						$tempPostArray['text'] = $this->limitedstring($tempPost->post_content, 200);
					}
		
					$block_content_actual .= $this->spacento_property_render($tempPostArray);
	
				 endforeach; endif;
			
			else:
			
				$data['data']['error'] = esc_html__('Empty', 'spacento' );
			
			endif; 

		endif;

		

		if( 'layoutOne' == $blockLayout ):
			$block_content .= '<div class="spacento-properties-one-container">';
		elseif( 'layoutTwo' == $blockLayout ):
			$block_content .= '<div class="spacento-properties-two-container">';
		else:
			$block_content .= '<div class="spacento-properties-three-container">';
		endif;
			$block_content .= $block_content_actual;
			$block_content .= '</div>';		

		// Return the frontend output for our block 
		return $block_content;		
		
	}
	
	public function spacento_block_categories( $categories, $post ) {
		if ( $post->post_type !== 'post' ) {
			return $categories;
		}
		return array_merge(
			$categories,
			array(
				array(
					'slug' => 'spacento',
					'title' => esc_html__( 'Spacento', 'spacento' ),
					'icon'  => 'wordpress',
				),
			)
		);
	}	

}
