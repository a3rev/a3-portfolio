const { __ } = wp.i18n;
const { Component } = wp.element;
const { PanelBody, SelectControl } = wp.components;

/**
 * Inspector controls
 */
export default class InspectorGlobalSettings extends Component {
	render() {
		const { attributes, setAttributes } = this.props;

		const { catIDs } = attributes;

		const catList = [ ...JSON.parse( a3_portfolio_blocks_vars.catList ) ];

		return (
			<PanelBody title={ __( 'Portfolio Categories' ) }>
				<SelectControl
					multiple
					label={ __( 'Select Portfolio Categories' ) }
					help={ __( 'Portfolio Items from the selected categories will be gotten' ) }
					value={ catIDs }
					onChange={ value => setAttributes( { catIDs: value } ) }
					options={ catList }
				/>
			</PanelBody>
		);
	}
}
