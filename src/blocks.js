/**
 * Gutenberg Blocks
 *
 * All blocks related JavaScript files should be imported here.
 * You can create a new block folder in this dir and include code
 * for that block here as well.
 *
 * All blocks should be included here since this is the file that
 * Webpack is compiling as the input file.
 */

const { updateCategory } = wp.blocks;

import a3revBlocksIcon from './a3blockpress.svg';

/**
 * Add category icon.
 */
updateCategory( 'a3rev-blocks', {
	icon: a3revBlocksIcon,
} );

import './blocks/main/block';
import './blocks/items/block';
import './blocks/categories/block';
import './blocks/tags/block';
import './blocks/sticky/block';
import './blocks/recent/block';
import './blocks/item-tags/block';
