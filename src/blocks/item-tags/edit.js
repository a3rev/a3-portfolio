/**
 * External dependencies
 */
import classnames from 'classnames';
import shorthash from 'shorthash';

import Inspector from './inspector';

const { useBlockProps } = wp.blockEditor || wp.editor;
const { __ } = wp.i18n;
const { useEffect, Component, Fragment } = wp.element;
const { Disabled } = wp.components;
const { serverSideRender: ServerSideRender } = wp;

const postTerms = [
	{
		id: 1,
		link: '#',
		name: __( 'Tag 1' ),
		style: {
			color: '#ffffff',
			backgroundColor: '#10e600',
			borderColor: '#595959'
		}
	},
	{
		id: 2,
		link: '#',
		name: __( 'Tag 2' ),
		style: {
			color: '#ffffff',
			backgroundColor: '#595959',
			borderColor: '#10e600'
		}
	},
]

export default function ItemTermsEdit( props ) {
	const { clientId, attributes, setAttributes } = props;

	const { blockID, itemID } = attributes;

	useEffect( () => {
		if ( ! blockID ) {
			setAttributes( { blockID: shorthash.unique( clientId ) } );
		}
	}, [ blockID ] );

	const blockProps = useBlockProps( {
		className: classnames( {
			[ `wp-block-a3-portfolios-${ blockID }` ]: blockID
		} ),
	} );

	return (
		<Fragment>
			<Inspector { ...{ ...props } } />
			<div { ...blockProps }>
				{ ! attributes.isPreview && (
				<Disabled>
					<ServerSideRender block="a3-portfolio/tags-meta" attributes={ attributes } />
				</Disabled>
				) }
				{ ( ! itemID || "0" == itemID ) && (
					<div className="a3-portfolio-tags-sticker under-image">
						{ postTerms.map( ( postTerm ) => (
								<span
									key={ postTerm.id }
									className="a3-portfolio-tag-sticker"
									style={ postTerm.style }

								>
									{ unescape( postTerm.name ) }
								</span>
							) )
						}
					</div>
				) }
			</div>
		</Fragment>
	);
}
