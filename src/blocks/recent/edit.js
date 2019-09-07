/**
 * Internal dependencies
 */

import Inspector from './inspector';

const { __ } = wp.i18n;
const { Component, Fragment } = wp.element;
const { ServerSideRender, Disabled } = wp.components;

export default class BlockEdit extends Component {
	render() {
		const { attributes, isSelected } = this.props;

		return (
			<Fragment>
				{ isSelected && <Inspector { ...this.props } /> }
				<Disabled>
					<ServerSideRender block="a3-portfolio/recent" attributes={ attributes } />
				</Disabled>
			</Fragment>
		);
	}
}
