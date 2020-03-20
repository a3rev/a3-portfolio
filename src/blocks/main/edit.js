/**
 * Internal dependencies
 */

import Inspector from './inspector';

const { __ } = wp.i18n;
const { Component, Fragment } = wp.element;
const { Disabled } = wp.components;
const { serverSideRender: ServerSideRender } = wp;

export default class BlockEdit extends Component {
	render() {
		const { attributes, isSelected } = this.props;

		return (
			<Fragment>
				{ isSelected && <Inspector { ...this.props } /> }
				<Disabled>
					<ServerSideRender block="a3-portfolio/main" attributes={ attributes } />
				</Disabled>
			</Fragment>
		);
	}
}
