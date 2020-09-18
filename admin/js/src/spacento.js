(function( $ ) {
	
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	
	jQuery(document).ready(function() {

		const { apiRequest } = wp;
		
		$('.spacento-meta-box-heading span').on('click', function(){
			
			$('.spacento-meta-box-heading-content').slideToggle(1000);
			
		});
	
		$('.spacento-meta-box-overlay').animate({'opacity':0}, 1000, function(){ $(this).css({'display':'none'}); });
		
		$('.spacento-meta-box-container .spacento-add').on('click', function(){
			
			var spacentoFeatureHtml = $('.spacento-property-feature-list-content-source').html();
			var spacentoFaetureId = Math.random().toString(36).substring(4);
			var spacentoFaetureField = $('.spacento-property-features-field').val();
			
			spacentoFaetureField = spacentoFaetureField + spacentoFaetureId + ',';
			
			$('.spacento-property-features-field').val(spacentoFaetureField);
			
			spacentoFeatureHtml = spacentoFeatureHtml.replace(/REPLACEWITHID/g, spacentoFaetureId);
			
			$('.spacento-property-feature-list-content .spacento-anchor').before(spacentoFeatureHtml);
			
		});
		
		$('.spacento-property-feature-list').on('click', '.spacento-property-feature-list-item .spacento-delete', function(){
			
			$(this).siblings('.spacento-delete-error').css({'display':'none'});
			
			var sendData = {};
			var propertyFeature = $(this);
			
			$('.spacento-meta-box-overlay').css({'opacity':0, 'display':'block'}).animate(
				{
					'opacity':1,
				}, 
				1000, 
				function(){
					
					var spacentoFaetureField = $('.spacento-property-features-field').val();
					var spacentoFaetureFieldId = propertyFeature.closest('.spacento-property-feature-list-item').attr('data-spacento-property-feature-id') + ',';
					spacentoFaetureField = spacentoFaetureField.replace(spacentoFaetureFieldId, '');
					propertyFeature.closest('.spacento-property-feature-list-item').remove();
					$('.spacento-property-features-field').val(spacentoFaetureField);
					$('.spacento-meta-box-overlay').animate(
						{
							'opacity':0,
						},
						1000,
						function(){
							$('.spacento-meta-box-overlay').css({'opacity':1, 'display':'none'});
						}
					);					
					
				}
			);
			
		});
		
		$('.spacento-meta-box-heading-content h3').on('click', function(){
			
			var spacentoViewportWidth = $(window).width();
			var spacentoTab = $(this).attr('data-spacento-tab');
			
			if( spacentoViewportWidth > 1200 ){
			   
				$('.spacento-meta-box-heading-content h3').removeClass('spacento-active');
				$(this).addClass('spacento-active');
			   
			}else{
				
				var spacentoMetaboxHeading = $(this).text();
				$('.spacento-meta-box-heading-actual').text(spacentoMetaboxHeading);
				$('.spacento-meta-box-heading-content').slideToggle(1000);
				
			}
			
			$('.spacento-meta-box-features-content, .spacento-meta-box-gallery-content, .spacento-meta-box-price-content').css({'display':'none'});
			
			$('.spacento-meta-box-overlay').css({'opacity':0, 'display':'block'}).animate(
				
				{
					'opacity':1,
				},
				1000,
				function(){
					
					if( "features" == spacentoTab ){
						$('.spacento-meta-box-features-content').css({'display':'block'});
					}else if( "price" == spacentoTab ){
						$('.spacento-meta-box-price-content').css({'display':'block'});
					}else{
						$('.spacento-meta-box-gallery-content').css({'display':'block'});
					}
					
					$('.spacento-meta-box-overlay').animate(
						{
							'opacity':0,
						},
						1000,
						function(){
							
							$('.spacento-meta-box-overlay').css({'display':'none'});
							
						}
					);	
					
				}
				
			);					
			
		});
		
		$('.spacento-add-image').on('click', function(){
			
			var spacentoGalleryItem = $('.spacento-meta-box-gallery-item-source').html(),
				attachment = {},
				spacentoPropertyGalleryIds = $('.spacento-property-gallery-field').val();
			
			var custom_uploader = wp.media({ 
					title: 'Insert image', 
					library : { type : 'image' }, 
					button: { text: 'Use this image' }, 
					multiple: false 
			}).on('select', function() { 
				attachment = custom_uploader.state().get('selection').first().toJSON(); 
				console.log(attachment);
				spacentoPropertyGalleryIds = spacentoPropertyGalleryIds + attachment.id + ',';
				console.log(spacentoPropertyGalleryIds);
				$('.spacento-property-gallery-field').val(spacentoPropertyGalleryIds);
				spacentoGalleryItem = spacentoGalleryItem.replace('REPLACEWITHURL', attachment.url); 
				$('.spacento-gallery-anchor').before(spacentoGalleryItem); 
			}).open();
		
		});
		
		$('.spacento-meta-box-gallery-item .spacento-delete').on('click', function(){
			
			var spacentoGalleryItem = $(this).closest('.spacento-meta-box-gallery-item'),
				spacentoGalleryItemId = spacentoGalleryItem.attr('data-spacento-id'), 
				spacentoPropertyGalleryIds = $('.spacento-property-gallery-field').val();
			
			$('.spacento-meta-box-overlay').css({'opacity':0, 'display':'block'}).animate(
				
				{
					'opacity':1,
				},
				1000,
				function(){
					
					spacentoPropertyGalleryIds = spacentoPropertyGalleryIds.replace(spacentoGalleryItemId + ',', '');console.log(spacentoPropertyGalleryIds);
					$('.spacento-property-gallery-field').val(spacentoPropertyGalleryIds);
					spacentoGalleryItem.remove();
					
					$('.spacento-meta-box-overlay').animate(
						{
							'opacity':0,
						},
						1000,
						function(){
							
							$('.spacento-meta-box-overlay').css({'display':'none'});
							
						}
					);	
					
				}
				
			);			
			
		});

		$('.widget-liquid-right').on('click', '.spacento-widget-property-type select option', function(){

			var tempPropertyLocationOptions = '';
			var newValue = $(this).attr('value'); console.log(newValue);
			$(this).closest('.spacento-widget-property-type').attr('data-spacento-id', newValue);
			$('.spacento-admin-widget-settings-overlay').css({'opacity':0, 'display':'block'}).animate(
				{
					'opacity':1,
				},
				1000,
				function(){

					if( 'all' == newValue ){

						if( '' == tempPropertyLocationOptions){
							tempPropertyLocationOptions += `<option value="select">${spacentoJsVariables.text.select}</option>`;
							spacentoJsVariables.properties.locations.forEach(element => {
								tempPropertyLocationOptions += `<option value="${element.value}">${element.label}</option>`;
							});
						}						
						
						$('.spacento-widget-property-location select').html(tempPropertyLocationOptions);
						$('.spacento-widget-property-location').css({'display':'block'});

						$('.spacento-admin-widget-settings-overlay').fadeOut(1000, function(){
							$(this).css({'display':'none', 'opacity':1});
						});						
					}else{

						var tempPropertyLocations = apiRequest( {url: `${spacentoJsVariables.api.getPropertyLocationOptions}/${newValue}`} );
						tempPropertyLocations.then( res => {
	
							if( typeof res === 'object' && res !== null && res.hasOwnProperty('data') && res.data.hasOwnProperty('payload') ){ console.log(res.data.payload);
	
								if( '' == tempPropertyLocationOptions){
									tempPropertyLocationOptions += `<option value="select">${spacentoJsVariables.text.select}</option>`;
									res.data.payload.forEach(element => {
										tempPropertyLocationOptions += `<option value="${element.value}">${element.label}</option>`;
									});
								}
	
								$('.spacento-widget-property-location select').html(tempPropertyLocationOptions);
								$('.spacento-widget-property-location').css({'display':'block'});
	
								$('.spacento-admin-widget-settings-overlay').fadeOut(1000, function(){
									$(this).css({'display':'none', 'opacity':1});
								});
		
							}else{ 
		
								
		
							}						
							
						} );						

					}

				}
			);

		});

		$('.widget-liquid-right').on('click', '.spacento-widget-property-location select option', function(){

			var tempPropertyOptions = '';
			var spacentoType = $(this).closest('.spacento-widget-property-location').siblings('.spacento-widget-property-type').attr('data-spacento-id');
			var spacentoLocation = $(this).attr('value');
			$('.spacento-admin-widget-settings-overlay').css({'opacity':0, 'display':'block'}).animate(
				{
					'opacity':1,
				},
				1000,
				function(){

					var tempProperty = apiRequest(
						{
							url: `${spacentoJsVariables.api.getPropertyIds}`,
							method: 'POST',
							data: { 
								type: spacentoType,
								location: spacentoLocation,
							},
						} 
						
					);
					tempProperty.then( res => {

						if( typeof res === 'object' && res !== null && res.hasOwnProperty('data') && res.data.hasOwnProperty('payload') ){ console.log(res.data.payload);

							if( '' == tempPropertyOptions){
								tempPropertyOptions += `<option value="select">${spacentoJsVariables.text.select}</option>`;
								res.data.payload.forEach(element => {
									tempPropertyOptions += `<option value="${element.value}">${element.label}</option>`;
								});
							}

							$('.spacento-widget-property select').html(tempPropertyOptions);
							$('.spacento-widget-property').css({'display':'block'});

							$('.spacento-admin-widget-settings-overlay').fadeOut(1000, function(){
								$(this).css({'display':'none', 'opacity':1});
							})
	
						}else{ 
	
							
	
						}						
						
					} );

				}
			);

		});		
	
	});
	

})( jQuery );