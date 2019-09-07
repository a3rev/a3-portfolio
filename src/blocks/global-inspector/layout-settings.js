const { __ } = wp.i18n;
const { Component } = wp.element;
const { PanelBody, ToggleControl, RangeControl, TextControl } = wp.components;

/**
 * Inspector controls
 */
export default class InspectorLayoutSettings extends Component {
	render() {
		const { attributes, setAttributes, initialOpen = false } = this.props;

		const { enableCustomColumns, customColumns, numberItems, showNavBar } = attributes;

		return (
			<PanelBody title={ __( 'Layout Settings' ) } initialOpen={ initialOpen }>
				<ToggleControl
					label={ __( 'Portfolio Cards / Row' ) }
					help={
						enableCustomColumns ?
							__( 'Using custom Columns for this block' ) :
							__( 'Using global Columns from Settings Panel' )
					}
					checked={ !! enableCustomColumns }
					onChange={ () => setAttributes( { enableCustomColumns: ! enableCustomColumns } ) }
				/>

				{ enableCustomColumns ? (
					<RangeControl
						label={ __( 'Custom Columns' ) }
						value={ customColumns ? customColumns : parseInt( a3_portfolio_blocks_vars.globalColumn ) }
						onChange={ value => setAttributes( { customColumns: value } ) }
						min={ 1 }
						max={ 6 }
						allowReset
					/>
				) : null }

				<TextControl
					label={ __( 'Number of Items' ) }
					help={ __( 'Leave empty for get all items.' ) }
					value={ numberItems }
					onChange={ value => setAttributes( { numberItems: value } ) }
				/>

				<ToggleControl
					label={ __( 'Nav Bar' ) }
					help={ showNavBar ? __( 'Showing the Nav Bar' ) : __( 'Hiding the Nav Bar' ) }
					checked={ !! showNavBar }
					onChange={ () => setAttributes( { showNavBar: ! showNavBar } ) }
				/>
			</PanelBody>
		);
	}
}
