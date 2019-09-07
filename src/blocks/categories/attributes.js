const CategoriesAttributes = {
	blockID: {
		type: 'string',
	},
	catIDs: {
		type: 'array',
	},
	enableCustomColumns: {
		type: 'boolean',
		default: false,
	},
	customColumns: {
		type: 'number',
		default: parseInt( a3_portfolio_blocks_vars.globalColumn ),
	},
	numberItems: {
		type: 'string',
		default: '',
	},
	showNavBar: {
		type: 'boolean',
		default: false,
	},
};

export default CategoriesAttributes;
