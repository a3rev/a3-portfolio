/**
 * Internal dependencies
 */

import Inspector from './inspector';

const { __ } = wp.i18n;
const { Component, Fragment } = wp.element;
const { ServerSideRender, Placeholder, Disabled } = wp.components;

export default class BlockEdit extends Component {
	render() {
		const { attributes, isSelected } = this.props;

		const { catIDs } = attributes;

		return (
			<Fragment>
				{ isSelected && <Inspector { ...this.props } /> }
				{ catIDs && catIDs.length > 0 ? (
					<Disabled>
						<ServerSideRender block="a3-portfolio/categories" attributes={ attributes } />
					</Disabled>
				) : (
					<Placeholder label={ __( 'a3 Portfolio Categories' ) }>
						{ __( 'Please choose leatest a Portfolio Category' ) }
					</Placeholder>
				) }
			</Fragment>
		);
	}
}
