const { __ } = wp.i18n;
const { Component } = wp.element;
const { PanelBody, SelectControl } = wp.components;

/**
 * Inspector controls
 */
export default class InspectorGlobalSettings extends Component {
	render() {
		const { attributes, setAttributes } = this.props;

		const { itemIDs } = attributes;

		const itemList = [ ...JSON.parse( a3_portfolio_blocks_vars.itemList ) ];

		return (
			<PanelBody title={ __( 'Portfolio Items' ) }>
				<SelectControl
					multiple
					label={ __( 'Select Portfolio Items' ) }
					value={ itemIDs }
					onChange={ value => setAttributes( { itemIDs: value } ) }
					options={ itemList }
				/>
			</PanelBody>
		);
	}
}
