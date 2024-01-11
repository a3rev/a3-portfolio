/**
 * BLOCK: Portfolio Recent
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

// icons
import IconRecent from './../../assets/icons/recent.svg';

import edit from './edit';
import metadata from './block.json';

const { registerBlockType } = wp.blocks;
const { name, attributes } = metadata;

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
export const settings = {
	icon: {
		src: IconRecent,
		foreground: '#24b6f1',
	},

	attributes,
	example: {
		attributes: {
			isPreview: true,
		},
	},
	edit,
	save: () => {
		return null;
	},
};

registerBlockType( { name, ...metadata }, settings );
