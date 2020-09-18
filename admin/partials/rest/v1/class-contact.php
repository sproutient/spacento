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
class Spacento_Contact {

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
		
		$this->aux     = new Spacento_REST_aux();

	}
	
    /**
     * Get user details.
     *
     * @param WP_REST_Request $request Current request.
     */
    public function contact( $request ) {
		
		$data['result'] = false;
		$data['data'] = array();
		$data['data']['errors'] = array();
		$data['data']['error'] = '';
		
		$nonce = '';
		$name = '';
		$email = '';
		$phone = '';

		if( isset($request['nonce']) ){
			$nonce = $request['nonce'];
		}	
		if( isset($request['name']) ){
			$name = $request['name'];
		}
		if( isset($request['email']) ){
			$email = $request['email'];
		}		
		if( isset($request['phone']) ){
			$phone = $request['phone'];
		}
		if( isset($request['property']) ){
			$property = $request['property'];
		}
				
		if( $nonce ):
		
			if( '' != $name && '' != $email && '' != $phone ):
		
				if( ctype_alpha($name) && filter_var($email, FILTER_VALIDATE_EMAIL) && is_numeric($phone) ){
					
					$to = sanitize_email(get_option('admin_email'));
					$subject = $property . ' : ' . $name . esc_html__(' is interested.', 'spacento' );
					$message = '<p>' . esc_html__('Name : ', 'spacento' ) . $name . '</p>';
					$message .= '<p>' . esc_html__('Email : ', 'spacento' ) . $email . '</p>';
					$message .= '<p>' . esc_html__('Phone : ', 'spacento' ) . $phone . '</p>';
					$headers = array('Content-Type: text/html; charset=UTF-8');
					if(wp_mail( $to, $subject, $message, $headers, array( '' ) )){
						$data['result'] = true;
					}else{
						$data['data']['error'] = esc_html__('Something went wrong, Please try again.', 'spacento' );
					}
					
				}else{
					
					if( !ctype_alpha($name) ){
						$data['data']['errors']['name'] = esc_html__('Name should be only letters.', 'spacento' );
					}
					
					if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
						$data['data']['errors']['email'] = esc_html__('Email does not seem to be valid.', 'spacento' );
					}
					
					if( !is_numeric($phone) ){
						$data['data']['errors']['phone'] = esc_html__('Phone should be numbers.', 'spacento' );
					}					
					
				}
		
			else:
		
				if( '' == $name ){
					$data['data']['errors']['name'] = esc_html__('Name is required.', 'spacento' );
				}
				if( '' == $email ){
					$data['data']['errors']['email'] = esc_html__('Email is required.', 'spacento' );
				}
				if( '' == $phone ){
					$data['data']['errors']['phone'] = esc_html__('Phone is required.', 'spacento' );
				}		
		
			endif;
		
		else:
		
			$data['data']['error'] = esc_html__('unAuthorized', 'spacento' );
		
		endif; 
		
        $response = $this->aux->prepare( $data, $request );
 
        // Return all of our post response data.
        return $response;		
		
		
	}	

}
