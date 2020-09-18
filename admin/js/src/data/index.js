const { registerStore, withSelect, withDispatch, dispatch } = wp.data;
const { __ } = wp.i18n;
const spacentoInspectorLabels = { 'title' : __( 'Select Properties', 'spacento' ), 'wait' : __( 'Please Wait...', 'spacento' ), 'error' : __( 'Something went wrong...', 'spacento') };

const SPACENTO_DEFAULT_STATE = {
	spacentoClasses: { outerContainer : 'spacento-hide-overlay',  },
	spacentoAttributes: { propertyType: 'all', propertyLocation: 'all',	property: 'all' },
	propertyTypeOptions: spacentoBlockVariables.properties.types,
	propertyLocationOptions: spacentoBlockVariables.properties.locations,
	spacentoOverlayText: spacentoInspectorLabels.wait,
	propertyOptions: spacentoBlockVariables.properties.propertiesids,
	propertyLayout: {},
};

const spacentoData = registerStore( 'spacento/properties', {

	reducer( state = SPACENTO_DEFAULT_STATE, action ) {
		switch ( action.type ) {
			case 'SET_CLASS':
				return {
					...state,
					spacentoClasses: {
						...state.spacentoClasses,
						[ action.spacentoElement ]: action.spacentoClass,
					},
				};

			case 'SET_TYPE_OPTIONS':
				return {
					...state,
					propertyTypeOptions: action.propertyTypeOptions,
				};				

			case 'SET_LOCATION_OPTIONS':
				return {
					...state,
					propertyLocationOptions: action.propertyLocationOptions,
				};

			case 'SET_OVERLAY_TEXT':
				return {
					...state,
					spacentoOverlayText: action.overlayText,
				};
				
			case 'SET_ATTRIBUTE':
				return {
					...state,
					spacentoAttributes: {
						...state.spacentoAttributes,
						[ action.attribute ]: action.value,
					},
				};

			case 'SET_PROPERTY_OPTIONS':
				return {
					...state,
					propertyOptions: action.propertyOptions,
				};
				
			case 'SET_PROPERTY_LAYOUT':
				return {
					...state,
					propertyLayout: action.value,
				};
				
			case 'UPDATE_PROPERTY_LAYOUT':
				return {
					...state,
					propertyLayout: action.value,
				};				

		}

		return state;
	},

	actions: {

		setClass( spacentoElement, spacentoClass ) {
			return {
				type: 'SET_CLASS',
				spacentoElement,
				spacentoClass,
			};
		},
		setTypeOptions( propertyTypeOptions ) {
			return {
				type: 'SET_TYPE_OPTIONS',
				propertyTypeOptions,
			};
		},		
		setLocationOptions( propertyLocationOptions ) {
			return {
				type: 'SET_LOCATION_OPTIONS',
				propertyLocationOptions,
			};
		},
		setPropertyOptions( propertyOptions ) {
			return {
				type: 'SET_PROPERTY_OPTIONS',
				propertyOptions,
			};
		},		
		setOverlayText( overlayText ) {
			return {
				type: 'SET_OVERLAY_TEXT',
				overlayText,
			};
		},
		setSpacentoAttributes( attribute, value ) {
			return {
				type: 'SET_ATTRIBUTE',
				attribute,
				value,
			};
		},	
		setPropertyLayout( value ) {
			return {
				type: 'SET_PROPERTY_LAYOUT',
				value,
			};
		},
		updatePropertyLayout( value ) {
			return {
				type: 'UPDATE_PROPERTY_LAYOUT',
				value,
			};
		},										
	
	},

	selectors: {

		getClass( state, element ) {
			const { spacentoClasses, propertyType } = state;
			const spacentoClass = spacentoClasses[ element ];

			return spacentoClass;
        },
        
        getPropertyTypeOptions( state ) {
			const { propertyTypeOptions } = state;
			return propertyTypeOptions;
		}, 
		
        getPropertyLocationOptions( state ) {
			const { propertyLocationOptions } = state;
			return propertyLocationOptions;
		},	

        getPropertyOptions( state ) {
			const { propertyOptions } = state;
			return propertyOptions;
		},		
		
		getOverlayText( state ) {
			const { spacentoOverlayText } = state;
			return spacentoOverlayText;
		},	
		
		getSpacentoAttributes( state, element ) {
			const { spacentoAttributes } = state;
			return spacentoAttributes[element];
		},	
		
		getPropertyLayout( state ) {
			const { propertyLayout } = state;
			return propertyLayout;
        },	

	},

	controls: {


	},

	resolvers: {

	},

} );

export { spacentoData }