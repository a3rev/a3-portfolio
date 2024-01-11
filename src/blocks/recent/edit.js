/**
 * External dependencies
 */
import shorthash from 'shorthash';

/**
 * Internal dependencies
 */

import Inspector from './inspector';

const { useBlockProps } = wp.blockEditor || wp.editor;
const { __ } = wp.i18n;
const { useEffect, Component, Fragment } = wp.element;
const { Placeholder, Disabled } = wp.components;
const { serverSideRender: ServerSideRender } = wp;

export default function BlockEdit( props ) {
	const { clientId, attributes, setAttributes } = props;

	const { blockID, isPreview } = attributes;

	useEffect( () => {
		if ( ! blockID ) {
			setAttributes( { blockID: shorthash.unique( clientId ) } );
		}
	}, [ blockID ] );

	const blockProps = useBlockProps();

	return attributes.isPreview ?
	(
		<Fragment>
			<h3 style={ {
				textAlign: 'center'
			} }>{ __( 'a3 Portfolio Recent' ) }</h3>
			<img
				src={ a3_portfolio_blocks_vars.preview }
				alt={ __( 'a3 Portfolio Recent Preview' ) }
				style={ {
					width: '100%',
					height: 'auto',
				} }
			/>
		</Fragment>
	)
	: (
		<Fragment>
			<Inspector { ...{ ...props } } />
			<div { ...blockProps }>
				<Disabled>
					<ServerSideRender block="a3-portfolio/recent" attributes={ attributes } />
				</Disabled>
			</div>
		</Fragment>
	);
}
