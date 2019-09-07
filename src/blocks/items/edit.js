/**
 * Internal dependencies
 */

import Inspector, { cardSpacingAttributes } from './inspector';

const { __ } = wp.i18n;
const { Component, Fragment } = wp.element;
const { ServerSideRender, Placeholder, Disabled } = wp.components;
const { BlockControls, BlockAlignmentToolbar } = wp.blockEditor;

export { cardSpacingAttributes };

export default class BlockEdit extends Component {
	render() {
		const { attributes, isSelected, setAttributes } = this.props;

		const { itemIDs, align } = attributes;

		return (
			<Fragment>
				<BlockControls>
					<BlockAlignmentToolbar
						value={ align }
						onChange={ value => setAttributes( { align: value } ) }
						controls={ [ 'left', 'center', 'right' ] }
					/>
				</BlockControls>
				{ isSelected && <Inspector { ...this.props } /> }
				{ itemIDs && itemIDs.length > 0 ? (
					<Disabled>
						<ServerSideRender block="a3-portfolio/items" attributes={ attributes } />
					</Disabled>
				) : (
					<Placeholder label={ __( 'a3 Portfolio Items' ) }>
						{ __( 'Please choose leatest a Portfolio Item' ) }
					</Placeholder>
				) }
			</Fragment>
		);
	}
}
