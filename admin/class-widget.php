<?php
 
/**
 * Adds Foo_Widget widget.
 */
class Spacento_Properties_Widget extends WP_Widget {
 
    use Spacento_Property_Renderer;
    
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'spacento-properties-widget', // Base ID
            'Spacento Properties Widget', // Name
            array( 'description' => esc_html__( 'A Foo Widget', 'spacento' ), ) // Args
        );
    }

    public function register(){

        register_widget( 'Spacento_Properties_Widget' );

    }

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
 
    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $this->property_display( $args, $instance );
    }
 
    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
            $title = esc_html($instance[ 'title' ]);
        }
        else {
            $title = '';
        }

        if ( isset( $instance[ 'propertytype' ] ) ) {
            $propertytype = esc_html($instance[ 'propertytype' ]);
        }
        else {
            $propertytype = '';
        }
        
        if ( isset( $instance[ 'propertylocation' ] ) ) {
            $propertylocation = esc_html($instance[ 'propertylocation' ]);
        }  
        else{
            $propertylocation = '';
        } 
        
        if ( isset( $instance[ 'property' ] ) ) {
            $property = esc_html($instance[ 'property' ]);
        }  
        else{
            $property = '';
        }  
        
        if ( isset( $instance[ 'propertylayout' ] ) ) {
            $propertylayout = esc_html($instance[ 'propertylayout' ]);
        }  
        else{
            $propertylayout = '';
        }        
        
        ?>
        <div class="spacento-admin-widget-settings">

            <div class="spacento-admin-widget-settings-overlay">

                <p><span></span></p>
                <p><?php esc_html_e('Please wait...', 'spacento'); ?></p>

            </div>        

                <p>
                <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'spacento' ); ?></label>
                    <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
                </p>

                <p data-spacento-id="" class="spacento-widget-property-type">
                    <label for="<?php echo esc_attr( $this->get_field_name( 'propertytype' )); ?>"><?php esc_html_e( 'Property Type :', 'spacento' ); ?></label>
                    <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'propertytype' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'propertytype' ) ); ?>" >
                        <option value="select" <?php selected( 'Select', $propertytype ); ?>><?php esc_html_e('Select', 'spacento'); ?></option>
                        <option value="all" <?php selected( 'all', $propertytype ); ?>><?php esc_html_e('All', 'spacento'); ?></option>
                        <?php
                                $terms = get_terms([
                                    'taxonomy' => 'spacento-property-type',
                                    'hide_empty' => false,
                                ]);
                                
                                if(!empty($terms)): foreach( $terms as $term ):
                                                    
                            ?>
                            <option value="<?php esc_html_e($term->term_id); ?>" <?php selected( $term->term_id, $propertytype ); ?>><?php esc_html_e($term->name); ?></option>
                            <?php endforeach; endif; ?>
                    </select>
                </p>

                <p data-spacento-id="" class="spacento-widget-property-location" <?php if( isset( $instance[ 'propertytype' ]) && '' != $instance[ 'propertytype' ] ){ echo 'style="display:block"'; } ?> >
                    <label for="<?php echo esc_attr( $this->get_field_name( 'propertylocation' ) ); ?>"><?php esc_html_e( 'Property Location :', 'spacento' ); ?></label>
                    <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'propertylocation' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'propertylocation' ) ); ?>" >
                    <option value="select" <?php selected( 'Select', $propertylocation ); ?>><?php esc_html_e('Select', 'spacento'); ?></option>
                    <option value="all" <?php selected( 'all', $propertylocation ); ?>><?php esc_html_e('All', 'spacento'); ?></option>
                        <?php
                            if( isset( $instance[ 'propertytype' ] ) ):

                                $terms = get_terms([
                                    'taxonomy' => 'spacento-property-location',
                                    'hide_empty' => false,
                                ]);
                                
                                if(!empty($terms)): foreach( $terms as $term ):                                

                        ?>
                        <option value="<?php esc_html_e($term->term_id); ?>" <?php selected( $term->term_id, $propertylocation ); ?>><?php esc_html_e($term->name); ?></option>
                        <?php endforeach; endif; endif; ?>
                    </select>
                </p>     
                
                <p 
                    data-spacento-id="" 
                    class="spacento-widget-property" 
                    <?php 
                        if( 
                            ( isset( $instance[ 'property' ]) && '' != $instance[ 'property' ] ) 
                            || (
                                (isset( $instance[ 'propertytype' ] ) && '' != $instance[ 'propertytype' ] )
                                &&
                                (
                                    isset( $instance[ 'propertylocation' ] ) && '' != $instance[ 'propertylocation' ] 
                                )
                            ) 
                        )
                            { echo 'style="display:block"'; } 
                    ?>
                >
                    <?php
                        $propertytype = str_replace('all', '', $propertytype);
                        $propertylocation = str_replace('all', '', $propertylocation);
                    ?>
                    <label for="<?php echo esc_attr( $this->get_field_name( 'property' ) ); ?>"><?php esc_html_e( 'Property :', 'spacento' ); ?></label>
                    <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'property' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'property' ) ); ?>" >
                    <option value="select" <?php selected( 'Select', $propertytype ); ?>><?php esc_html_e('Select', 'spacento'); ?></option>
                    <option value="all" <?php selected( 'all', $propertytype ); ?>><?php esc_html_e('All', 'spacento'); ?></option>
                        <?php

                            if( !empty($propertytype) || !empty($propertylocation) ):

                                if( !empty($propertytype) && !empty($propertylocation) ):
                                $terms = get_posts(
                                    array(
                                        'post_type' => 'spacento_property',
                                        'tax_query' => array(
                                            'relation' => 'AND',
                                            array(
                                                'taxonomy' => 'spacento-property-type',
                                                'field'    => 'term_id',
                                                'terms'    => array( (int)$propertytype ),
                                            ),
                                            array(
                                                'taxonomy' => 'spacento-property-location',
                                                'field'    => 'term_id',
                                                'terms'    => array( (int)$propertylocation ),
                                            ),
                                        ),                                        
                                    )
                                );
                                elseif( !empty($propertytype) && empty($propertylocation) ):
                                    $terms = get_posts(
                                        array(
                                            'post_type' => 'spacento_property',
                                            'tax_query' => array(
                                                array(
                                                    'taxonomy' => 'spacento-property-type',
                                                    'field'    => 'term_id',
                                                    'terms'    => array( (int)$propertytype ),
                                                ),
                                            ),                                        
                                        )
                                    );
                                elseif( empty($propertytype) && !empty($propertylocation) ):
                                    $terms = get_posts(
                                        array(
                                            'post_type' => 'spacento_property',
                                            'tax_query' => array(
                                                array(
                                                    'taxonomy' => 'spacento-property-location',
                                                    'field'    => 'term_id',
                                                    'terms'    => array( (int)$propertylocation ),
                                                ),
                                            ),                                        
                                        )
                                    );
                                endif;
                                
                                if(!empty($terms)): foreach( $terms as $term ):   
                                    
                                    $tempPost = get_post($term);    

                        ?>
                        <option value="<?php esc_html_e($tempPost->ID); ?>" <?php selected( $tempPost->ID, $property ); ?>><?php esc_html_e($tempPost->post_title); ?></option>
                        <?php endforeach; endif; endif; ?>                        
                    </select>
                </p> 

                <p data-spacento-id="" class="spacento-widget-property-layout">
                    <label for="<?php echo esc_attr( $this->get_field_name( 'propertylayout' ) ); ?>"><?php esc_html_e( 'Property Layout :', 'spacento' ); ?></label>
                    <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'propertylayout' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'propertylayout' ) ); ?>" >
                        <option value="select" <?php selected( 'Select', $propertylayout ); ?>><?php esc_html_e('Select', 'spacento'); ?></option>
                        <option value="one" <?php selected( 'one', $propertylayout ); ?>><?php esc_html_e('One', 'spacento'); ?></option>
                        <option value="two" <?php selected( 'two', $propertylayout ); ?>><?php esc_html_e('Two', 'spacento'); ?></option>
                    </select>
                </p>                               

            </div>                       
    <?php
    }
 
    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['propertytype'] = ( !empty( $new_instance['propertytype'] ) && 'select' != $new_instance['propertytype'] ) ? strip_tags( $new_instance['propertytype'] ) : '';
        $instance['propertylocation'] = ( !empty( $new_instance['propertylocation'] ) && 'select' != $new_instance['propertylocation'] ) ? strip_tags( $new_instance['propertylocation'] ) : '';
        $instance['property'] = ( !empty( $new_instance['property'] ) && 'select' != $new_instance['property'] ) ? strip_tags( $new_instance['property'] ) : '';
        $instance['propertylayout'] = ( !empty( $new_instance['propertylayout'] ) && 'select' != $new_instance['propertylayout'] ) ? strip_tags( $new_instance['propertylayout'] ) : '';
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
    }
 
}
     
