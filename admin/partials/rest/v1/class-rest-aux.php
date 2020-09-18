<?php

class Spacento_REST_aux {
	
	private $plugin_name;

	private $version;
	
	private $schema;

	public function __construct() {


	}
	
    public function prepare( $data, $request ) {
        
		$post_data = array();
 
        $schema = $this->get_schema( $request );
 
        // We are also renaming the fields to more understandable names.
        if ( isset( $schema['properties']['result'] ) ) {
            $post_data['result'] = (boolean) $data['result'];
        }
		
        if ( isset( $schema['properties']['data'] ) ) {
            $post_data['data'] = (array) $data['data'];
        }		
 
        return rest_ensure_response( $post_data );
		
    }
	
    public function get_schema( $request ) {
		
        if ( $this->schema ) {
            // Since WordPress 5.3, the schema can be cached in the $schema property.
            return $this->schema;
        }
 
        $this->schema = array(
            // This tells the spec of JSON Schema we are using which is draft 4.
            '$schema'              => 'http://json-schema.org/draft-04/schema#',
            // The title property marks the identity of the resource.
            'title'                => esc_html__( 'Testimonials By Sproutient REST Output', 'spacento' ),
            'type'                 => 'object',
            // In JSON Schema you can specify object properties in the properties attribute.
            'properties'           => array(

                'result' => array(
                    'description'  => esc_html__( 'Result', 'spacento' ),
                    'type'         => 'boolean',
                    'context'      => array( 'view' ),
                    'readonly'     => true,
					'default'	   => false,
                ),
                'data' => array(
                    'description'  => esc_html__( 'Data', 'spacento' ),
                    'type'         => 'array',
                    'context'      => array( 'view' ),
                    'readonly'     => true,
					'default'	   => '',
                ),				
				
            ),
        );
 
        return $this->schema;
    }
	
    public function validate_number( $value, $request, $param ) {

		$attributes = $request->get_attributes();

		if ( isset( $attributes['args'][ $param ] ) ) {
			$argument = $attributes['args'][ $param ];
			// Check to make sure our argument is a string.
			if ( 'number' === $argument['type'] && ! is_numeric( $value ) ) {
				return new WP_Error( 'rest_invalid_param', sprintf( esc_html__( '%1$s is not of type %2$s', 'spacento' ), $param, 'string' ), array( 'status' => 400 ) );
			}
		}

		// If we got this far then the data is valid.
		return true;		
		
	}
	
	public function sanitize_number( $value, $request, $param ) {

		$attributes = $request->get_attributes();

		if ( isset( $attributes['args'][ $param ] ) ) {

			$argument = $attributes['args'][ $param ];
			// Check to make sure our argument is a integer.
			if ( 'number' === $argument['type'] ) {
				return absint( $value );
			}

		}
		
		// If we got this far then something went wrong don't use user input.
		return new WP_Error( 'rest_api_sad', esc_html__( 'Something went terribly wrong.', 'spacento' ), array( 'status' => 500 ) );		

	}
	
    public function validate_string( $value, $request, $param ) {

		$attributes = $request->get_attributes();

		if ( isset( $attributes['args'][ $param ] ) ) {
			$argument = $attributes['args'][ $param ];
			// Check to make sure our argument is a string.
			if ( 'string' === $argument['type'] && ! is_string( $value ) ) {
				return new WP_Error( 'rest_invalid_param', sprintf( esc_html__( '%1$s is not of type %2$s', 'spacento' ), $param, 'string' ), array( 'status' => 400 ) );
			}
		}

		// If we got this far then the data is valid.
		return true;		
		
	}

	public function sanitize_string( $value, $request, $param ) {

		$attributes = $request->get_attributes();

		if ( isset( $attributes['args'][ $param ] ) ) {

			$argument = $attributes['args'][ $param ];
			// Check to make sure our argument is a integer.
			if ( 'string' === $argument['type'] ) {
				return sanitize_text_field($value);
			}

		}
		
		// If we got this far then something went wrong don't use user input.
		return new WP_Error( 'rest_api_sad', esc_html__( 'Something went terribly wrong.', 'spacento' ), array( 'status' => 500 ) );	

	}		
	
}