import { spacentoData } from './data';
import { SpacentoControl, SpacentoSelectControl, SpacentoPropertyLayoutControl } from './components';

const { apiFetch } = wp;
const { __ } = wp.i18n;
const { registerBlockType, query } = wp.blocks;
const { RichText, InspectorControls } = wp.blockEditor;
const {
	CheckboxControl,
	RadioControl,
	TextControl,
	ToggleControl,
	SelectControl,
} = wp.components;	
const { serverSideRender: ServerSideRender } = wp;
const { PanelBody } = wp.components;

const { useInstanceId, withState } = wp.compose;
const { Component } = wp.element;
const isEmpty = lodash.isEmpty;
const { BaseControl } = wp.components;
const spacentoInspectorLabels = { 'title' : __( 'Select Properties', 'spacento' ), 'wait' : __( 'Please Wait...', 'spacento' ), 'error' : __( 'Something went wrong...', 'spacento') };

const { registerStore, withSelect, withDispatch, dispatch, select } = wp.data;

const isObject = lodash.isObject;

var { createElement : el, useState } = wp.element;
var SVG = wp.primitives.SVG;
var circle = el( 'circle', { cx: 10, cy: 10, r: 10, fill: 'red', stroke: 'blue', strokeWidth: '10' } );
var svgIcon = el( SVG, { width: 20, height: 20, viewBox: '0 0 20 20'}, circle);
console.log(spacentoBlockVariables.pluginUrl);
const spacentoLayoutOptions = {

	layoutOne : { image : spacentoBlockVariables.pluginUrl + '/admin/images/layout-one.jpg', name : __( 'Layout One', 'spacento' ) },
	layoutTwo : { image : spacentoBlockVariables.pluginUrl + '/admin/images/layout-two.jpg', name : __( 'Layout Two', 'spacento' ) },

}

let spacentoClassState = {}, spacentoClassKeys;

spacentoClassKeys = Object.keys(spacentoLayoutOptions);

spacentoClassKeys.forEach( (item, index) => {
	spacentoClassState[item] = "spacento-layout-option";
} );

var spacentoClassStateSource = { ...spacentoClassState };

dispatch( 'spacento/properties' ).setPropertyLayout( spacentoClassState );
		
registerBlockType(

	'spacento/properties',
			
	{
		title: __( 'Spacento properties', 'spacento' ),
		icon: 'building',
		category: 'spacento',
		keywords: [ __( 'real estate', 'spacento' ), __( 'property', 'spacento' ), __( 'properties', 'spacento' ) ],
		supports: { 'customClassName' : true },
		attributes: {

			propertyType: {
				type: 'string',
				default: 'all',
			},
			propertyLocation: {
				type: 'string',
				default: 'all',
			},
			property: {
				type: 'string',
				default: 'all',
			},
			layout: {
				type: 'string',
				default: 'layoutOne',
			},
			testone: {
				type: 'string',
				default: 'all',
			},
		},
		example: {

		},				
		edit( { attributes, setAttributes, isSelected } ) {

						
			const { propertyType, propertyLocation, property, layout } = attributes;
			const { getAttributes } = select( 'spacento/properties' );

			if(isSelected){
				dispatch( 'spacento/properties' ).setClass( 'outerContainer', 'spacento-hide-overlay' );
				dispatch( 'spacento/properties' ).setSpacentoAttributes( 'propertyType', propertyType );
				dispatch( 'spacento/properties' ).setSpacentoAttributes( 'propertyLocation', propertyLocation );
				dispatch( 'spacento/properties' ).setSpacentoAttributes( 'property', property );
				var spacentoClassStateOrigin = {...spacentoClassStateSource};
				spacentoClassStateOrigin[layout] = "spacento-layout-option-selected";
				dispatch( 'spacento/properties' ).updatePropertyLayout( spacentoClassStateOrigin );
			}
					
			let propertyTypeNew = ( '' != propertyType ) ? propertyType : 'all',
				propertyLocationNew = ( '' != propertyLocation ) ? propertyLocation : 'all',
				propertyNew = ( '' != property ) ? property : 'all',
				propertyLayout = ( '' != layout ) ? layout : 'layoutOne';
			

			function spacentoFetchProperties(){

				var spacentoLocation = select( 'spacento/properties' ).getSpacentoAttributes('propertyLocation');
				var spacentoType = select( 'spacento/properties' ).getSpacentoAttributes('propertyType');

				if( 'all' == spacentoLocation && 'all' == spacentoType ){

					dispatch( 'spacento/properties' ).setPropertyOptions(spacentoBlockVariables.properties.propertiesids);
					dispatch( 'spacento/properties' ).setSpacentoAttributes( 'property', 'all' );

				}else{

					var tempPropertyLocations = apiFetch(
	
						{
							url: `${spacentoBlockVariables.api.getPropertyIds}`,
							method: 'POST',
							data: { 
								type: spacentoType,
								location: spacentoLocation,
							},
						} 
						
					);
					tempPropertyLocations.then( res => {
						
						if( isObject(res) && res.hasOwnProperty('data') && res.data.hasOwnProperty('payload') ){
							dispatch( 'spacento/properties' ).setPropertyOptions(res.data.payload);
						}else{
							dispatch( 'spacento/properties' ).setPropertyOptions(spacentoBlockVariables.properties.propertiesids);
						}
						dispatch( 'spacento/properties' ).setSpacentoAttributes( 'property', 'all' );
						
					} );

				}
	
			}				

			function onChangePropertyType( newValue ) {

				dispatch( 'spacento/properties' ).setClass( 'outerContainer', 'test123' );
				var tempPropertyLocationValue = select( 'spacento/properties' ).getSpacentoAttributes('propertyLocation');

				if( 'all' == newValue ){

					setAttributes( { propertyType: newValue } );
					propertyTypeNew = newValue;					
					dispatch( 'spacento/properties' ).setLocationOptions(spacentoBlockVariables.properties.locations);
					dispatch( 'spacento/properties' ).setSpacentoAttributes( 'propertyType', newValue );
					dispatch( 'spacento/properties' ).setSpacentoAttributes( 'propertyLocation', tempPropertyLocationValue );
					spacentoFetchProperties();
					dispatch( 'spacento/properties' ).setClass( 'outerContainer', 'spacento-hide-overlay' );

				}else{

					var tempPropertyLocations = apiFetch( {url: `${spacentoBlockVariables.api.getPropertyLocationOptions}/${newValue}`} );
					tempPropertyLocations.then( res => { 
						
						if( isObject(res) && res.hasOwnProperty('data') && res.data.hasOwnProperty('payload') ){
	
							setAttributes( { propertyType: newValue } );
							propertyTypeNew = newValue;
													
							dispatch( 'spacento/properties' ).setSpacentoAttributes( 'propertyType', newValue );
							dispatch( 'spacento/properties' ).setLocationOptions(res.data.payload);

							var tempSelectAvailable = false;
							res.data.payload.forEach(element => {
								if( tempPropertyLocationValue == element.value ){
									tempSelectAvailable = true;
								}
							});

							if(tempSelectAvailable){
								dispatch( 'spacento/properties' ).setSpacentoAttributes( 'propertyLocation', tempPropertyLocationValue );
							}else{
								dispatch( 'spacento/properties' ).setSpacentoAttributes( 'propertyLocation', 'all' );
								setAttributes( { propertyLocation: 'all' } );
							}

							spacentoFetchProperties();

							dispatch( 'spacento/properties' ).setClass( 'outerContainer', 'spacento-hide-overlay' );
	
						}else{ 
	
							dispatch( 'spacento/properties' ).setOverlayText(spacentoInspectorLabels.error);
	
						}
	
					} );

				}
				

			}
					
			function onChangePropertyLocation( newValue ) { 

				dispatch( 'spacento/properties' ).setClass( 'outerContainer', 'test123' );
				var tempPropertyTypeValue = select( 'spacento/properties' ).getSpacentoAttributes('propertyType');

				if( 'all' == newValue ){

					setAttributes( { propertyLocation: newValue } );
					propertyLocationNew = newValue;					
					dispatch( 'spacento/properties' ).setTypeOptions(spacentoBlockVariables.properties.types);
					dispatch( 'spacento/properties' ).setSpacentoAttributes( 'propertyLocation', newValue );
					dispatch( 'spacento/properties' ).setSpacentoAttributes( 'propertyType', tempPropertyTypeValue );
					spacentoFetchProperties();
					dispatch( 'spacento/properties' ).setClass( 'outerContainer', 'spacento-hide-overlay' );

				}else{

					var tempPropertyLocations = apiFetch( {url: `${spacentoBlockVariables.api.getPropertyTypeOptions}/${newValue}`} );
					tempPropertyLocations.then( res => { 
						
						if( isObject(res) && res.hasOwnProperty('data') && res.data.hasOwnProperty('payload') ){
	
							setAttributes( { propertyLocation: newValue } );
							propertyLocationNew = newValue;
													
							dispatch( 'spacento/properties' ).setSpacentoAttributes( 'propertyLocation', newValue );
							dispatch( 'spacento/properties' ).setTypeOptions(res.data.payload);

							var tempSelectAvailable = false;
							res.data.payload.forEach(element => {
								if( tempPropertyTypeValue == element.value ){
									tempSelectAvailable = true;
								}
							});

							if(tempSelectAvailable){
								dispatch( 'spacento/properties' ).setSpacentoAttributes( 'propertyType', tempPropertyTypeValue );
							}else{
								dispatch( 'spacento/properties' ).setSpacentoAttributes( 'propertyType', 'all' );
								setAttributes( { propertyType: 'all' } );
							}
							
							spacentoFetchProperties();

							dispatch( 'spacento/properties' ).setClass( 'outerContainer', 'spacento-hide-overlay' );
	
						}else{
	
							dispatch( 'spacento/properties' ).setOverlayText(spacentoInspectorLabels.error);
	
						}
	
					} );

				}
				

			}

			function onChangeProperty( newValue ) {
				dispatch( 'spacento/properties' ).setSpacentoAttributes( 'property', property );
				propertyNew = newValue;
				setAttributes( { property: newValue } );
			}

			function onChangePropertyLayout( newValue ) {
				propertyLayout = newValue;
				setAttributes( { layout: newValue } );
			}			

			return (
							<>
								<InspectorControls>

									<PanelBody>

										<SpacentoControl
											label={__( 'Select Properties', 'spacento' )}
											value={ propertyType }
											options={ spacentoBlockVariables.properties.types }
											onChange={ onChangePropertyType }
										>

											<SpacentoSelectControl label={__( 'Property Type :', 'spacento' )} type="propertyType" onChangeTest={onChangePropertyType} />	
											<SpacentoSelectControl label={__( 'Property Location :', 'spacento' )} type="propertyLocation" onChangeTest={onChangePropertyLocation} />
											<SpacentoSelectControl label={__( 'Property :', 'spacento' )} type="property" onChangeTest={onChangeProperty} />
											<SpacentoPropertyLayoutControl className="spacento-layout-container" options={ spacentoLayoutOptions } spacentosource={ spacentoClassStateSource } spacentoCallback={ onChangePropertyLayout } spacentoTitle={__( 'Layout Type :', 'spacento' )} />

										</SpacentoControl>
			
									</PanelBody>

								</InspectorControls>

								<ServerSideRender
							
									block="spacento/properties"
									attributes={{
										propertyType: propertyTypeNew,
										propertyLocation: propertyLocationNew,
										property: propertyNew,
										layout: propertyLayout,
									}}

								/>
							
							</>
			);

		},
		save( { attributes } ) {
			return null;
		},

	}

);