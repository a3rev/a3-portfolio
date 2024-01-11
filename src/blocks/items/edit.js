/**
 * External dependencies
 */
import shorthash from 'shorthash';

/**
 * Internal dependencies
 */

import Inspector from './inspector';

const { useBlockProps, BlockControls, BlockAlignmentToolbar } = wp.blockEditor || wp.editor;
const { __ } = wp.i18n;
const { useEffect, Component, Fragment } = wp.element;
const { Placeholder, Disabled } = wp.components;
const { serverSideRender: ServerSideRender } = wp;

export default function BlockEdit( props ) {
	const { clientId, attributes, setAttributes } = props;

	const { blockID, isPreview, itemIDs, align } = attributes;

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
			} }>{ __( 'a3 Portfolio Items' ) }</h3>
			<img
				src={ a3_portfolio_blocks_vars.preview }
				alt={ __( 'a3 Portfolio Items Preview' ) }
				style={ {
					width: '100%',
					height: 'auto',
				} }
			/>
		</Fragment>
	)
	: (
		<Fragment>
			<BlockControls>
				<BlockAlignmentToolbar
					value={ align }
					onChange={ value => setAttributes( { align: value } ) }
					controls={ [ 'left', 'center', 'right' ] }
				/>
			</BlockControls>
			<Inspector { ...{ ...props } } />
			<div { ...blockProps }>
				{ itemIDs && itemIDs.length > 0 ? (
					<Disabled>
						<ServerSideRender block="a3-portfolio/items" attributes={ attributes } />
					</Disabled>
				) : (
					<Placeholder label={ __( 'a3 Portfolio Items' ) }>
						{ __( 'Please choose leatest a Portfolio Item' ) }
					</Placeholder>
				) }
			</div>
		</Fragment>
	);
}
