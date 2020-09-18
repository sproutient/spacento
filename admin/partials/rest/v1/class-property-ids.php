<?php

/**
 * Add wishlist buttons to product pages and product lists.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Voltize_Wishlist
 * @subpackage Voltize_Wishlist/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Voltize_Wishlist
 * @subpackage Voltize_Wishlist/public
 * @author     Your Name <email@example.com>
 */
class Spacento_Property_Ids {

	//private $aux;
	
	private $aux;
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $voltize      The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct() {

		require_once SPACENTO_PATH . 'admin/partials/rest/v1/class-rest-aux.php';
		
		$this->aux = new Spacento_REST_aux();

	}
	
    /**
     * Get user details.
     *
     * @param WP_REST_Request $request Current request.
     */
    public function properties( $request ) {
		
		$data['result'] = false;
		$data['data'] = array();
		$data['data']['errors'] = array();
		$data['data']['error'] = '';

		$propertyType = '';
		if( isset($request['type']) ){
			$propertyType = $request['type'];
			$propertyType = (int)$propertyType;
		}		
		$propertyLocation = '';
		if( isset($request['location']) ){
			$propertyLocation = $request['location'];
			$propertyLocation = (int)$propertyLocation;
		}		
		
		$data['data']['aur'] = array();
		$data['data']['aur']['propertyType'] = $propertyType;
		$data['data']['aur']['propertyLocation'] = $propertyLocation;

		$data['data']['payload'][] = array(

			'value' => 'all',
			'label' => esc_html__( 'All', 'spacento' ),

		);
				
		if( !empty($propertyType) || !empty($propertyLocation) ):
		
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

			endif;

			$tempPosts = get_posts( $propArray );

			 if( !empty($tempPosts)): foreach( $tempPosts as $id ):

				$tempPost = get_post($id);
				$tempArray = array();
				$tempArray['value'] = esc_html($tempPost->ID);
				$tempArray['label'] = esc_html($tempPost->post_title);
				$data['data']['payload'][] = $tempArray;

			 endforeach; endif;

			 $data['result'] = true;
			 //$data['data']['payload'] = $tempPosts;
		
		else:
		
			$data['data']['error'] = esc_html__('Empty', 'spacento' );
		
		endif; 
		
        $response = $this->aux->prepare( $data, $request );
 
        // Return all of our post response data.
        return $response;		
		
		
	}	

}
