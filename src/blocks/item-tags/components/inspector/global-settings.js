const { __ } = wp.i18n;
const { Component } = wp.element;
const { PanelBody, SelectControl } = wp.components;

/**
 * Inspector controls
 */
export default class InspectorGlobalSettings extends Component {
	render() {
		const { attributes, setAttributes } = this.props;

		const { itemID } = attributes;

		const itemList = [ { label: __( 'Current Portfolio Item' ), value: 0 }, ...JSON.parse( a3_portfolio_blocks_vars.itemList ) ];

		return (
			<PanelBody title={ __( 'Portfolio Item' ) }>
				<SelectControl
					label={ __( 'Select Portfolio Item' ) }
					value={ itemID }
					onChange={ value => setAttributes( { itemID: value } ) }
					options={ itemList }
				/>
			</PanelBody>
		);
	}
}
