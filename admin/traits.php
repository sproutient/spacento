<?php

trait Spacento_Property_Renderer{

    public function property_display( $args, $instance ){

        extract( $args );
        $title = apply_filters( 'widget_title', $instance['title'] );

        if(!isset($before_widget)){
            $before_widget = '';
        }
        if(!isset($after_widget)){
            $after_widget = '';
        }  
        if(!isset($before_title)){
            $before_title = '';
        }
        if(!isset($after_title)){
            $after_title = '';
        }

        $propertyType = '';
        $propertyLocation = '';
        $property = '';
        $propertylayout = '';

        $tempPosts = array();

        if( array_key_exists("propertytype", $instance) ){
            $propertyType = esc_html($instance["propertytype"]);
        }

        if( array_key_exists("propertylocation", $instance) ){
            $propertyLocation = esc_html($instance["propertylocation"]);
        }        
        
        if( array_key_exists("property", $instance) ){
            $property = esc_html($instance["property"]);
        } 

        if( array_key_exists("propertylayout", $instance) ){
            $propertylayout = esc_html($instance["propertylayout"]);
        }

        if( empty($propertyType) && empty($propertyLocation) && empty($property) ):

            $propArray = array(
                'post_type'   => 'spacento_property',
                'fields' => 'ids'					
            );            
        
        elseif( !empty($property) && 'all' != $property ):    

            $tempPosts[] = get_post( (int)$property );
            
        else:

            if( empty($propertyType) && !empty($propertyLocation) ):

                $propertyLocation = str_replace('all', '', $propertyLocation);

                if(empty($propertyType)){
                    $propArray = array(
                        'post_type'   => 'spacento_property',
                        'fields' => 'ids'					
                    );
                }else{
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
                }
    
            elseif( !empty($propertyType) && empty($propertyLocation) ):

                $propertyType = str_replace('all', '', $propertyType);
                if(empty($propertyType)){
                    $propArray = array(
                        'post_type'   => 'spacento_property',
                        'fields' => 'ids'					
                    );
                }else{
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
                }
				
    
            elseif( !empty($propertyType) && !empty($propertyLocation) ):

                $propertyType = str_replace('all', '', $propertyType);
                $propertyLocation = str_replace('all', '', $propertyLocation);                
    
                if( empty($propertyType) && empty($propertyLocation) ){
                    $propArray = array(
                        'post_type'   => 'spacento_property',
                        'fields' => 'ids'					
                    );
                }
                elseif(empty($propertyType)){
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
                }
                elseif(empty($propertyLocation)){
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
                }
                else{
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
                }
				
    
            endif;
            $tempPosts = get_posts( $propArray );

        endif;
        
        $output = '';
        $output .= $before_widget;
        $output .= '<div class="spacento-widget-container">';
        if ( ! empty( $title ) ) {
            esc_html($before_title) . esc_html($title) . esc_html($after_title);
        }

            if( !empty($tempPosts) ): foreach( $tempPosts as $id ):
                $tempPost = get_post($id);

                $propertyGallery = get_post_meta( $id, 'spacento-property-gallery', true );
                $currentPropertGallery = '';
                if(!empty($propertyGallery)):
    
                    $currentPropertGallery =  rtrim($propertyGallery, ',');
                    $currentPropertGallery = explode(',', $currentPropertGallery);	
                    
                endif;
                $propertyPrice = '';
                $propertyCurrency = '';			
                $propertyPrice = get_post_meta( $id, 'spacento-property-price', true );
                $propertyCurrency = get_post_meta( $id, 'spacento-property-currency', true );              					

                $tempPostArray = array();
                $tempPostArray['title'] = $tempPost->post_title;
                if(!empty($currentPropertGallery)){
                    $tempPostArray['image'] = wp_get_attachment_url($currentPropertGallery[0]);
                }
                $tempPostArray['text'] = $tempPost->post_excerpt;
                $tempPostArray['url'] = get_permalink($id);

                if(empty($tempPostArray['text'])){
                    $tempPostArray['text'] = esc_html($this->limitedstring($tempPost->post_content, 200));
                }

                if( 'two' == $instance['propertylayout'] ){
                $output .= '<div class="spacento-widget-property-two-item">';
                }else{
                $output .= '<div class="spacento-widget-property-one-item">';
                }
                
                if( array_key_exists('image', $tempPostArray) ):
                $output .= '<div class="spacento-property-media">';
                $output .= '<img src="' . esc_url($tempPostArray['image']) . '"/>';
                $output .= '</div>';
                endif;
                $output .= '<div class="spacento-property-content">';       
                $output     .= '<h2>' . esc_html($tempPostArray['title']) . '</h2>';
                $output     .= '<p>' . esc_html($tempPostArray['text']) . '</p>';
                if( '' != $propertyPrice ):
                $output     .= '<div class="spacento-property-price">';
                $output     .=  '<p class=""><span>' . esc_html($propertyCurrency) . '</span>' . esc_html($propertyPrice) . '</p>';
                $output     .= '</div>';
                endif;
                $output     .= '<p><a href="'. esc_url($tempPostArray['url']) . '" >' . esc_html__('View Details', 'spacento') . '</a></p>';  
                $output .= '</div>';		
                $output .= '</div>';

            endforeach; endif;

        $output .= '</div>';
        $output .= $after_widget;

        return $output;

    }

}