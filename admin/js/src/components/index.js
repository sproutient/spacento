const { useInstanceId, withState } = wp.compose;
const isEmpty = lodash.isEmpty;
const { BaseControl, SelectControl } = wp.components;
const { __ } = wp.i18n;
const spacentoInspectorLabels = { 'title' : __( 'Select Properties', 'spacento' ), 'wait' : __( 'Please Wait...', 'spacento' ), 'error' : __( 'Something went wrong...', 'spacento') };
const { registerStore, withSelect, withDispatch, dispatch } = wp.data;
	
function SpacentoControlEl( {
	help,
	label,
	multiple = false,
	onChange,
	options = [],
	spacentoClassName,
	spacentoOverlayText,
	children
} ) {
	
	const instanceId = useInstanceId( SpacentoControl );
	const id = `inspector-select-control-${ instanceId }`;
	const onChangeValue = ( event ) => {
		onChange( event.target.value );
	};
	const spacentoTempClass = `spacento-inspector-container ${spacentoClassName}`

	// Disable reason: A select with an onchange throws a warning

	/* eslint-disable jsx-a11y/no-onchange */
	return (
		! isEmpty( options ) && (

			<div id={spacentoClassName} className={spacentoTempClass}>
			
				<h3>{ spacentoInspectorLabels.title }</h3>

				{children}

				<div className="spacento-inspector-container-overlay">

					<p><span></span></p>
					<p>{ spacentoOverlayText }</p>

				</div>

			</div>
			
		)
	);
	/* eslint-enable jsx-a11y/no-onchange */
}	

export const SpacentoControl = withSelect( ( select ) => {

	const { getClass, getOverlayText } = select( 'spacento/properties' );
	//const { getClass } = select( 'spacento/properties' );

	return {
		spacentoClassName: getClass( 'outerContainer' ),
		spacentoOverlayText: getOverlayText(),
	};

} )( SpacentoControlEl );

export const SpacentoSelectControl = withSelect( ( select, ownProps ) => {

	if( 'propertyLocation' == ownProps.type ){

		const { getPropertyLocationOptions } = select( 'spacento/properties' );

		return {
			options: getPropertyLocationOptions(),
			value: select( 'spacento/properties' ).getSpacentoAttributes('propertyLocation'),
		};

	}

	if( 'propertyType' == ownProps.type ){

		const { getPropertyTypeOptions } = select( 'spacento/properties' );

		return {
			options: getPropertyTypeOptions(),
			value: select( 'spacento/properties' ).getSpacentoAttributes('propertyType'),
		};

	}	

	if( 'property' == ownProps.type ){

		const { getPropertyOptions } = select( 'spacento/properties' );

		return {
			options: getPropertyOptions(),
			value: select( 'spacento/properties' ).getSpacentoAttributes('property'),
		};

	}	

} )( 

	(props) => {

		return(

			<SelectControl
			label={props.label}
			value={ props.value }
			options={ props.options }
			onChange={ props.onChangeTest }
			/>			

		);

	}

);

export const SpacentoPropertyLayoutControl = withSelect( ( select, ownProps ) => {

	const  { getPropertyLayout } = select( 'spacento/properties' );

	return {
		spacentoClassObject : getPropertyLayout(),
	};


} )( 

	(props) => {

		//console.log( props );

		return(

			<div className={props.className} >

				<h4>{props.spacentoTitle}</h4>
				{Object.keys(props.options).map((keyName, i) => (
				<p className={ props.spacentoClassObject[keyName]}>
					<img 
						src={props.options[keyName].image} 
						onClick={ 
							() => {

								let spacentosourceAgain = {...props.spacentosource};
								spacentosourceAgain[keyName] = "spacento-layout-option-selected";
								dispatch( 'spacento/properties' ).updatePropertyLayout( spacentosourceAgain );
								props.spacentoCallback(keyName);
							} 
						} 
					/>
					<span>{props.options[keyName].name}</span>
				</p>
				))}
			
			</div>			

		);

	}

);	