/**
 * BLOCK: Portfolio Items
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

import BlockEdit, { cardSpacingAttributes } from './edit';

// icons
import IconItems from './../../assets/icons/items.svg';

import ItemsAttributes from './attributes';

// editor style
import './editor.scss';

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks;

const { Fragment } = wp.element;

/**
 * Register: a3 Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType( 'a3-portfolio/items', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __( 'a3 Portfolio Items' ), // Block title.
	description: __( 'Show the grid of Portfolio Items are selected' ),
	icon: {
		src: IconItems,
		foreground: '#24b6f1',
	}, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
	category: 'a3rev-blocks', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	keywords: [ __( 'a3 Portfolio' ), __( 'a3 Portfolio Items' ), __( 'Portfolio Items' ), __( 'a3rev' ) ],
	example: {
		attributes: {
			isPreview: true,
		},
	},

	attributes: {
		...ItemsAttributes,
		...cardSpacingAttributes,
	},

	supports: {
		customClassName: false,
		className: false,
	},

	// The "edit" property must be a valid function.
	edit( props ) {
		const { attributes } = props;

		if ( attributes.isPreview ) {
			return (
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
			);
		}

		return (
			<Fragment>
				<BlockEdit { ...props } />
			</Fragment>
		);
	},

	// The "save" property must be specified and must be a valid function.
	save() {
		// Rendering in PHP
		return null;
	},
} );
