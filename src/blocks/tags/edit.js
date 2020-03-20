/**
 * Internal dependencies
 */

import Inspector from './inspector';

const { __ } = wp.i18n;
const { Component, Fragment } = wp.element;
const { Placeholder, Disabled } = wp.components;
const { serverSideRender: ServerSideRender } = wp;

export default class BlockEdit extends Component {
	render() {
		const { attributes, isSelected } = this.props;

		const { tagIDs } = attributes;

		return (
			<Fragment>
				{ isSelected && <Inspector { ...this.props } /> }
				{ tagIDs && tagIDs.length > 0 ? (
					<Disabled>
						<ServerSideRender block="a3-portfolio/tags" attributes={ attributes } />
					</Disabled>
				) : (
					<Placeholder label={ __( 'a3 Portfolio Tags' ) }>
						{ __( 'Please choose leatest a Portfolio Tag' ) }
					</Placeholder>
				) }
			</Fragment>
		);
	}
}
