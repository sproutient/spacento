<?php get_header(); ?>
<div class="spacento-property-details-container">
	
	<div class="spacento-property-gallery-overlay">
		
		<span class="spacento-close">
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="2.4em" height="2.4em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><path d="M12.12 10l3.53 3.53l-2.12 2.12L10 12.12l-3.54 3.54l-2.12-2.12L7.88 10L4.34 6.46l2.12-2.12L10 7.88l3.54-3.53l2.12 2.12z" fill="#C83133"/></svg>		
		</span>
		<div class="spacento-property-gallery-overlay-inner">
			
		</div>
		
	</div>

	<?php

		while ( have_posts() ) :

			the_post();

	?>

	<div class="spacento-property-details">

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		
		<?php the_content(); ?>	
		
		<div class="spacento-property-features">
			
		<?php
		
			$propertyFeatures = get_post_meta( get_the_ID(), 'spacento-property-features', true );
		
			foreach( $propertyFeatures as $key => $value ):
		
		?>
		
		<p><span><?php echo esc_html($value['name']); ?></span><span><?php echo esc_html($value['value']); ?></span></p>
		
		<?php endforeach; ?>
		</div>

		<div class="spacento-property-price">
		<?php 
			$propertyPrice = get_post_meta( get_the_ID(), 'spacento-property-price', true );
			$propertyCurrency = get_post_meta( get_the_ID(), 'spacento-property-currency', true );
			if(!empty($propertyPrice)):
		?>
		<h3><span><?php esc_html_e($propertyCurrency); ?></span><?php esc_html_e($propertyPrice); ?></h3>
		<?php endif; ?>
		</div>
		
		<p class="spacento-property-contact">
			<span><?php esc_html_e('Contact', 'spacento') ?></span>
		</p>
		
		<div class="spacento-property-contact-form">
		
			<p data-spacento-field="name" class="spacento-field">
				<label for="name"><?php esc_html_e('Name :', 'spacento'); ?></label>
				<input type="text" id="name" name="name">
				<span class="spacento-error">Required</span>
			</p>
			
			<p data-spacento-field="email" class="spacento-field">
				<label for="email"><?php esc_html_e('Email :', 'spacento'); ?></label>
				<input type="text" id="name" name="email">
				<span class="spacento-error">Required</span>
			</p>
			
			<p data-spacento-field="phone" class="spacento-field">
				<label for="phone"><?php esc_html_e('Phone :', 'spacento'); ?></label>
				<input type="text" id="phone" name="phone">
				<span class="spacento-error">Required</span>
			</p>
			
			<p>
				<span class="spacento-submit-error-message"></span>
				<span class="spacento-submit-success-message"></span>
				<span class="spacento-submit">
					<?php esc_html_e('Submit', 'spacento'); ?>
					<span class="spacento-submit-spinner">
						<span></span>
					</span>
				</span>
			</p>			
		
		</div>

	</div>
	
	<div class="spacento-property-gallery">

		<?php
		
			$propertyGallery = get_post_meta( get_the_ID(), 'spacento-property-gallery', true );

			if(!empty($propertyGallery)):

				$currentPropertGallery =  rtrim($propertyGallery, ',');
				$currentPropertGallery = explode(',', $currentPropertGallery);	
		
				$imageNum = 0;
		
				foreach( $currentPropertGallery as $i ):
		
				$currentPropertGalleryImage = wp_get_attachment_url($i);
		
		?>
		<?php if( 0 === $imageNum ): ?>
		<div class="spacento-property-gallery-first">
		<?php elseif( 1 === $imageNum ): ?>
		</div>	
		<div class="spacento-property-gallery-rest">
			
			<div class="spacento-property-gallery-rest-overlay">

				<span class="spacento-close">
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20"><path d="M12.12 10l3.53 3.53l-2.12 2.12L10 12.12l-3.54 3.54l-2.12-2.12L7.88 10L4.34 6.46l2.12-2.12L10 7.88l3.54-3.53l2.12 2.12z" fill="#C83133"/></svg>		
				</span>
				<div class="spacento-property-gallery-rest-overlay-inner">

				</div>

			</div>
			
		<div class="spacento-property-gallery-rest-inner">	
		<?php endif; ?>
			<div class="spacento-property-gallery-item">
				<img src="<?php echo esc_url($currentPropertGalleryImage); ?>" />
			</div>
		<?php $imageNum++; endforeach; endif; ?>
		</div>
		</div>
	
	</div>	
	
	<?php

		endwhile; // End of the loop.

	?>	

</div>
<?php get_footer(); ?>