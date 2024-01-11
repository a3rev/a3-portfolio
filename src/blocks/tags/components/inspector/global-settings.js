const { __ } = wp.i18n;
const { Component } = wp.element;
const { PanelBody, SelectControl } = wp.components;

/**
 * Inspector controls
 */
export default class InspectorGlobalSettings extends Component {
	render() {
		const { attributes, setAttributes } = this.props;

		const { tagIDs } = attributes;

		const tagList = [ ...JSON.parse( a3_portfolio_blocks_vars.tagList ) ];

		return (
			<PanelBody title={ __( 'Portfolio Tags' ) }>
				<SelectControl
					multiple
					label={ __( 'Select Portfolio Tags' ) }
					help={ __( 'Portfolio Items from the selected tags will be gotten' ) }
					value={ tagIDs }
					onChange={ value => setAttributes( { tagIDs: value } ) }
					options={ tagList }
				/>
			</PanelBody>
		);
	}
}
