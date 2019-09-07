const ItemsAttributes = {
	blockID: {
		type: 'string',
	},
	itemIDs: {
		type: 'array',
	},
	align: {
		type: 'string',
		default: 'none',
	},
	alignWrap: {
		type: 'boolean',
		default: false,
	},
	enableCustomColumns: {
		type: 'boolean',
		default: false,
	},
	customColumns: {
		type: 'number',
		default: parseInt( a3_portfolio_blocks_vars.globalColumn ),
	},
	width: {
		type: 'number',
		default: 600,
	},
	widthUnit: {
		type: 'string',
		default: 'px',
	},
};

export default ItemsAttributes;
