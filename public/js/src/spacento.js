(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
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
	
	$(document).ready(function() {
		
		console.log(spacentoJsVariables);
		
		var spacentoViewportWidth = 0,
			spacentoFormSubmit = true;
		
		function spacentoViewport(){
			
			spacentoViewportWidth = $(window).width();
			
		}
		
		$('.spacento-property-gallery-item img').on('click', function(){
			
			if( spacentoViewportWidth > 1200 ){
				
				var spacentoPropertyImage = $(this).attr('src');
				$('.spacento-property-gallery-overlay-inner').html('<img src="' + spacentoPropertyImage + '" />');
				$('.spacento-property-gallery-overlay').css({'opacity':0, 'display':'block'}).animate(
					{
						'opacity':1,
					},
					1000,
					function(){

					}
				);				
				
			}else{
				
				var spacentoPropertyImage = $(this).attr('src');
				$('.spacento-property-gallery-rest-overlay-inner').html('<img src="' + spacentoPropertyImage + '" />');
				$('.spacento-property-gallery-rest-overlay').css({'opacity':0, 'display':'block'}).animate(
					{
						'opacity':1,
					},
					1000,
					function(){

					}
				);				
				
			}
			
		});
		
		$('.spacento-property-gallery-overlay .spacento-close').on('click', function(){
			
			$('.spacento-property-gallery-overlay').animate(
				{
					'opacity':0,
				},
				1000,
				function(){
					$(this).css({'display':'none'});
					$('.spacento-property-gallery-overlay-inner').html('');
				}
			);			
			
		});
		
		$('.spacento-property-gallery-rest-overlay .spacento-close').on('click', function(){
			
			$('.spacento-property-gallery-rest-overlay').animate(
				{
					'opacity':0,
				},
				1000,
				function(){
					$(this).css({'display':'none'});
					$('.spacento-property-gallery-rest-overlay-inner').html('');
				}
			);			
			
		});	
		
		$('.spacento-property-contact > span').on('click', function(){
			
			$('.spacento-property-contact-form').slideToggle(1000);
			
		});
		
		$('.spacento-property-contact-form .spacento-submit').on('click', function(){
			
			if(spacentoFormSubmit){
				
				spacentoFormSubmit = false;
				var spacentoProceed = true,
					spacentoData = {'nonce':spacentoJsVariables.nonce, 'property':spacentoJsVariables.propertyName};
				
				$('.spacento-property-contact-form .spacento-error, .spacento-submit-success-message').text('').fadeOut();
				$('.spacento-submit-error-message').css({'display':'none'}).text('');

				$('.spacento-submit-spinner').css({'opacity':0, 'display':'inline-block'}).animate(

					{
						'opacity':1,
					},
					500,
					function(){

						$('.spacento-property-contact-form .spacento-submit-spinner span').css({'opacity':0, 'display':'inline-block'}).animate(

							{
								'opacity':1,
							},
							500,
							function(){

								$('.spacento-field').each(function(){

									var spacentoVal = $(this).find('input').val(),
										spacentoId = $(this).attr('data-spacento-field');
									
									if( '' == spacentoVal ){
										
									   $(this).find('.spacento-error').text(spacentoJsVariables.messages.required).fadeIn();
										spacentoProceed = false;
										
									}else{
										spacentoData[spacentoId] = spacentoVal;
									}

								});
								
								if( spacentoProceed ){
								   
									jQuery.ajax({

										type: 'POST',
										url: spacentoJsVariables.api.contact,
										data: spacentoData,
										beforeSend: function (xhr) {
											xhr.setRequestHeader('X-WP-Nonce', spacentoJsVariables.wpRestNonce);
										},
										timeout: 10000,
										success: function(jsonData) {

											console.log(jsonData);
											
											if(jsonData.result){
											   
												$('.spacento-submit-success-message').text(spacentoJsVariables.messages.success).css({'display':'block'});
												spacentoFormSubmit = true;
												$('.spacento-submit-spinner, .spacento-property-contact-form .spacento-submit-spinner span').css({'display':'none'});
											   
											}else{
												
												if( '' != jsonData.data.error ){
												   
													$('.spacento-submit-error-message').text(jsonData.data.error).css({'display':'block'});
													
												}
												
												$('.spacento-field').each(function(){
													
													var spacentoFieldName = $(this).attr('data-spacento-field');
													console.log(spacentoFieldName);
													console.log(jsonData.data.errors[spacentoFieldName]);
													if( '' != jsonData.data.errors[spacentoFieldName] ){
													   $(this).find('.spacento-error').text(jsonData.data.errors[spacentoFieldName]).fadeIn();
													}
													
												});
												
												spacentoFormSubmit = true;
												$('.spacento-submit-spinner, .spacento-property-contact-form .spacento-submit-spinner span').css({'display':'none'});												
												
											}
											
										},
										error: function(xhr, status, error) {
											
											$('.spacento-submit-error-message').text(spacentoJsVariables.messages.submitError).css({'display':'block'});
											spacentoFormSubmit = true;
											$('.spacento-submit-spinner, .spacento-property-contact-form .spacento-submit-spinner span').css({'display':'none'});
											console.log(xhr, status, error);
											
										},

									});								   
								   
									
								}else{
									
									spacentoFormSubmit = true;
									$('.spacento-submit-spinner, .spacento-property-contact-form .spacento-submit-spinner span').css({'display':'none'});
									
								}

							}

						);					

					}

				);				
				
			}
			
		});
		
		jQuery(window).on("resize",function(){
			spacentoViewport();
		});
						  
		spacentoViewport();		  
		
	});

})( jQuery );
