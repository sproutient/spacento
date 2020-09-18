<?php
			
	global $post;
	$currentPropertFeatures = '';
	$currentPropertGallery = '';
	$currentPropertyPrice = '';
	$currentPropertyCurrency = '';

	$propertyFeatures = get_post_meta( $post->ID, 'spacento-property-features', true );

	
	if(is_array($propertyFeatures)){
		
		foreach($propertyFeatures as $key => $value){
			$currentPropertFeatures .= $key . ',';
		}
		
	}

	$propertyGallery = get_post_meta( $post->ID, 'spacento-property-gallery', true );
	
	if(!empty($propertyGallery)){
		
		$currentPropertGallery = explode(',', $propertyGallery);
		
	}

	$currentPropertyPrice = get_post_meta( $post->ID, 'spacento-property-price', true );
	$currentPropertyCurrency = get_post_meta( $post->ID, 'spacento-property-currency', true );	

?>

<div class="spacento-meta-box-container">
	
	<div class="spacento-meta-box-heading">
		
		<h3 class="spacento-meta-box-heading-actual"><?php esc_html_e('Select', 'spacento'); ?></h3>
		<div class="spacento-meta-box-heading-content">
			
			<h3 data-spacento-tab="features" class="spacento-active"><?php esc_html_e('Property Features', 'spacento'); ?></h3>
			<h3 data-spacento-tab="gallery"><?php esc_html_e('Property Gallery', 'spacento'); ?></h3>
			<h3 data-spacento-tab="price"><?php esc_html_e('Price Info', 'spacento'); ?></h3>		
			
		</div>
	
		<span>
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="20px" height="20px" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><g fill="none"><path d="M8 6.983a1 1 0 1 0 0 2h8a1 1 0 1 0 0-2H8z" fill="#626262"/><path d="M7 12a1 1 0 0 1 1-1h8a1 1 0 1 1 0 2H8a1 1 0 0 1-1-1z" fill="#626262"/><path d="M8 15.017a1 1 0 1 0 0 2h8a1 1 0 1 0 0-2H8z" fill="#626262"/><path fill-rule="evenodd" clip-rule="evenodd" d="M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12S6.477 2 12 2s10 4.477 10 10zm-2 0a8 8 0 1 1-16 0a8 8 0 0 1 16 0z" fill="#626262"/></g></svg>
		</span>
	
	</div>
	
	<div class="spacento-meta-box-content">
		
		<div class="spacento-meta-box-overlay">

			<div class="spacento-meta-box-overlay-content">

				<p><span></span></p>
				<p><?php esc_html_e('Please Wait...', 'spacento'); ?></p>

			</div>			

		</div>		
	
		<div class="spacento-meta-box-features-content">

			<input class="spacento-property-features-field" type="hidden" name="spacento-property-features" value="<?php echo esc_attr($currentPropertFeatures); ?>" />
			<div class="spacento-property-feature-list">

				<div class="spacento-property-feature-list-content-source">

					<div data-spacento-property-feature-id="REPLACEWITHID" class="spacento-property-feature-list-item">

						<p><span><?php esc_html_e('Feature Name', 'spacento'); ?></span><input name="REPLACEWITHIDname" type="text" value="" /></p>
						<p><span><?php esc_html_e('Feature Value', 'spacento'); ?></span><input name="REPLACEWITHIDvalue" type="text" value="" /></p>
						<span class="spacento-delete-spinner">

						</span>
						<span class="spacento-delete">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="2.4em" height="2.4em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><path d="M12.12 10l3.53 3.53l-2.12 2.12L10 12.12l-3.54 3.54l-2.12-2.12L7.88 10L4.34 6.46l2.12-2.12L10 7.88l3.54-3.53l2.12 2.12z" fill="#626262"/></svg>
						</span>				
						<span class="spacento-delete-error"><?php esc_html_e('Failed to delete', 'spacento'); ?></span>				

					</div>

				</div>

				<div class="spacento-property-feature-list-content">

					<?php

						if(is_array($propertyFeatures)):
						foreach( $propertyFeatures as $key => $value ):

					?>
					<div data-spacento-property-feature-id="<?php echo $key; ?>" class="spacento-property-feature-list-item">

						<p><span><?php esc_html_e('Feature Name', 'spacento'); ?></span><input name="<?php echo esc_attr($key); ?>name" type="text" value="<?php echo esc_attr($value['name']); ?>" /></p>
						<p><span><?php esc_html_e('Feature Value', 'spacento'); ?></span><input name="<?php echo esc_attr($key); ?>value" type="text" value="<?php echo esc_attr($value['value']); ?>" /></p>
						<span class="spacento-delete-spinner">

						</span>
						<span class="spacento-delete">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="2.4em" height="2.4em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><path d="M12.12 10l3.53 3.53l-2.12 2.12L10 12.12l-3.54 3.54l-2.12-2.12L7.88 10L4.34 6.46l2.12-2.12L10 7.88l3.54-3.53l2.12 2.12z" fill="#626262"/></svg>
						</span>				
						<span class="spacento-delete-error"><?php esc_html_e('Failed to delete', 'spacento'); ?></span>				

					</div>			
					<?php endforeach; endif; ?>

					<span class="spacento-anchor"></span>

				</div>

			</div>

			<span class="spacento-add"><?php esc_html_e('Add', 'spacento'); ?></span>

		</div>
		
		<div class="spacento-meta-box-gallery-content">
			
			<input class="spacento-property-gallery-field" type="hidden" name="spacento-property-gallery" value="<?php echo esc_attr($propertyGallery); ?>" />
			<div class="spacento-meta-box-gallery-item-source">
				
				<div class="spacento-meta-box-gallery-item">

					<img src="REPLACEWITHURL" />
					<span class="spacento-delete">
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="2.4em" height="2.4em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><path d="M12.12 10l3.53 3.53l-2.12 2.12L10 12.12l-3.54 3.54l-2.12-2.12L7.88 10L4.34 6.46l2.12-2.12L10 7.88l3.54-3.53l2.12 2.12z" fill="#C83133"/></svg>
					</span>	
		
				</div>				
				
			</div>
			
			<div class="spacento-meta-box-gallery-items">
				
					<?php

						if(is_array($currentPropertGallery)):
						foreach( $currentPropertGallery as $i ):
						if(!empty($i)):

					?>
					<div data-spacento-id="<?php echo esc_attr($i); ?>" class="spacento-meta-box-gallery-item">

						<img src="<?php echo esc_url(wp_get_attachment_url($i)); ?>" />
						<span class="spacento-delete">
							<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="2.4em" height="2.4em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><path d="M12.12 10l3.53 3.53l-2.12 2.12L10 12.12l-3.54 3.54l-2.12-2.12L7.88 10L4.34 6.46l2.12-2.12L10 7.88l3.54-3.53l2.12 2.12z" fill="#C83133"/></svg>
						</span>	

					</div>				
					<?php endif; endforeach; endif; ?>
				
				<span class="spacento-gallery-anchor"></span>
				
			</div>
			
			<span class="spacento-add-image"><?php esc_html_e('Add', 'spacento'); ?></span>
			
		</div>
		
		<div class="spacento-meta-box-price-content">
			<div class="spacento-property-price-item">
				<p>
					<span><?php esc_html_e('Price :'); ?></span>
					<input type="text" id="propertyprice" name="spacento-property-price" value="<?php echo esc_attr($currentPropertyPrice); ?>">
				</p>
				<p>
					<label for="spacento-property-currency"><?php esc_html_e('Currency :'); ?></label><br>
					<input type="text" id="propertypricecurrency" name="spacento-property-currency" value="<?php echo esc_attr($currentPropertyCurrency); ?>">
				</p>
			</div>			
		</div>
	
	</div>
	
</div>