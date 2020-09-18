<?php
 
 class Spacento_Shortcodes {

    use Spacento_Property_Renderer;

	public function limitedstring($output, $max_char=100){

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

    public function spacento_func( $atts ) {

        $instance = shortcode_atts( array(
            'propertytype' => '',
            'propertylocation' => '',
            'property' => '',
            'propertylayout' => 'one',
            'title' => '',
        ), $atts );

        $args = array();

        return $this->property_display( $args, $instance );

    }

 }
     
