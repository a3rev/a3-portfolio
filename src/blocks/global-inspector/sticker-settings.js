const { __ } = wp.i18n;
const { Component, Fragment } = wp.element;
const {
	FontSizePicker,
	LineHeightControl,
	__experimentalFontAppearanceControl: FontAppearanceControl,
	__experimentalLetterSpacingControl: LetterSpacingControl,
	__experimentalTextTransformControl: TextTransformControl,
} = wp.blockEditor || wp.editor;
const {
	PanelBody,
	TabPanel,
	BaseControl,
	ToggleControl,
	SelectControl,
	RangeControl,
	__experimentalBorderBoxControl: BorderBoxControl,
	__experimentalBoxControl: BoxControl
} = wp.components;

/**
 * Inspector controls
 */
export default class InspectorStickerSettings extends Component {
	render() {
		const { attributes, setAttributes, initialOpen = false } = this.props;

		const {
			enableCardSticker,
			cardStickerPosition,
			enableDropDownSticker,
			dropDownStickerPosition,
			styleCardSticker,
			styleExSticker
		} = attributes;

		const tabList = [
			{ name: 'card', title: __( 'Card' ) },
			{ name: 'dropdown', title: __( 'Expander' ) }
		];
		const dropdownPosition = [
			{ label: 'Top Left', value: 'top-left' },
			{ label: 'Top Center', value: 'top-center' },
			{ label: 'Top Right', value: 'top-right' },
			{ label: 'Center Left', value: 'center-left' },
			{ label: 'Center Center', value: 'center-center' },
			{ label: 'Center Right', value: 'center-right' },
			{ label: 'Bottom Left', value: 'bottom-left' },
			{ label: 'Bottom Center', value: 'bottom-center' },
			{ label: 'Bottom Right', value: 'bottom-right' }
		];
		const cardPosition = [
			{ label: 'Under Image', value: 'under-image' },
			...dropdownPosition
		];

		const onChangeStyleSticker = ( option, key, value ) => {
			if ( key === false ) {
				const newUpdate = { ... attributes[ option ], ...value };
				setAttributes( { [ option ]: newUpdate } );
			} else {
				const newUpdate = { ... attributes[ option ] };
				newUpdate[ key ] = value;
				setAttributes( { [ option ]: newUpdate } );
			}
		};

		const fontStyleCard = styleCardSticker.fontStyle ? styleCardSticker.fontStyle : undefined;
		const fontWeightCard = styleCardSticker.fontWeight ? styleCardSticker.fontWeight : undefined;

		const fontStyleEx = styleExSticker.fontStyle ? styleExSticker.fontStyle : undefined;
		const fontWeightEx = styleExSticker.fontWeight ? styleExSticker.fontWeight : undefined;

		return (
			<PanelBody title={ __( 'Sticker Settings' ) } initialOpen={ initialOpen }>
				<TabPanel
					tabs = { tabList }
				>
					{ ( tab ) => {
						if ( 'card' === tab.name ) {
							return (
								<Fragment>
									<ToggleControl
										label={ __( 'Sticker on Card' ) }
										checked={ !! enableCardSticker }
										onChange={ () => setAttributes( { enableCardSticker: ! enableCardSticker } ) }
									/>

									{ enableCardSticker ? (
										<Fragment>
											<SelectControl
												label={ __( 'Sticker Position' ) }
												help={ __( 'Position of sticker on Card' ) }
												value={ cardStickerPosition }
												onChange={ value => setAttributes( { cardStickerPosition: value } ) }
												options={ cardPosition }
											/>

											<BoxControl
												label={ __( 'Padding' ) }
												values={ styleCardSticker.padding }
												onChange={ value => { onChangeStyleSticker( [ 'styleCardSticker' ], 'padding', value ) } }
											/>

											<BoxControl
												label={ __( 'Margin' ) }
												values={ styleCardSticker.margin }
												onChange={ value => { onChangeStyleSticker( [ 'styleCardSticker' ], 'margin', value ) } }
											/>

											<BorderBoxControl
												label={ __( 'Border' ) }
												disableCustomColors={ true }
												enableAlpha={ false }
												value={ styleCardSticker.border }
												onChange={ value => { onChangeStyleSticker( [ 'styleCardSticker' ], 'border', value ) } }
											/>

											<RangeControl
												label={ __( 'Border Radius' ) }
												value={ styleCardSticker.radius }
												onChange={ value => { onChangeStyleSticker( [ 'styleCardSticker' ], 'radius', value ) } }
												min={ 0 }
												max={ 100 }
											/>

											<BaseControl
												label={ __( 'Font' ) }
											>
												<FontSizePicker
													disableCustomFontSizes={ true }
													value={ styleCardSticker.fontSize }
													onChange={ value => { onChangeStyleSticker( [ 'styleCardSticker' ], 'fontSize', value ) } }
													withReset={ true }
												/>
											</BaseControl>

											<BaseControl
												className={ 'components-custom-font-control' }
											>
												<LineHeightControl
													value={ styleCardSticker.lineHeight }
													onChange={ value => { onChangeStyleSticker( [ 'styleCardSticker' ], 'lineHeight', value ) } }
													size="__unstable-large"
													__unstableInputWidth="auto"
												/>
												
												<FontAppearanceControl
													value={ {
														fontStyle: fontStyleCard,
														fontWeight: fontWeightCard,
													} }
													onChange={ value => { onChangeStyleSticker( [ 'styleCardSticker' ], false, value ) } }
													size="__unstable-large"
												/>

												<LetterSpacingControl
													value={ styleCardSticker.letterSpacing }
													onChange={ value => { onChangeStyleSticker( [ 'styleCardSticker' ], 'letterSpacing', value ) } }
													size="__unstable-large"
													__unstableInputWidth="auto"
												/>

												<TextTransformControl
													value={ styleCardSticker.textTransform }
													onChange={ value => { onChangeStyleSticker( [ 'styleCardSticker' ], 'textTransform', value ) } }
													showNone
													isBlock
													size="__unstable-large"
												/>
											</BaseControl>
										</Fragment>
									) : null }
								</Fragment>
							);
						}

						else if ( 'dropdown' === tab.name ) { 
							return (
								<Fragment>
									<ToggleControl
										label={ __( 'Sticker on Expander' ) }
										checked={ !! enableDropDownSticker }
										onChange={ () => setAttributes( { enableDropDownSticker: ! enableDropDownSticker } ) }
									/>

									{ enableDropDownSticker ? (
										<Fragment>
											<SelectControl
												label={ __( 'Sticker Position' ) }
												help={ __( 'Position of sticker on Expander' ) }
												value={ dropDownStickerPosition }
												onChange={ value => setAttributes( { dropDownStickerPosition: value } ) }
												options={ dropdownPosition }
											/>

											<BoxControl
												label={ __( 'Padding' ) }
												values={ styleExSticker.padding }
												onChange={ value => { onChangeStyleSticker( [ 'styleExSticker' ], 'padding', value ) } }
											/>

											<BoxControl
												label={ __( 'Margin' ) }
												values={ styleExSticker.margin }
												onChange={ value => { onChangeStyleSticker( [ 'styleExSticker' ], 'margin', value ) } }
											/>

											<BorderBoxControl
												label={ __( 'Border' ) }
												disableCustomColors={ true }
												enableAlpha={ false }
												value={ styleExSticker.border }
												onChange={ value => { onChangeStyleSticker( [ 'styleExSticker' ], 'border', value ) } }
											/>

											<RangeControl
												label={ __( 'Border Radius' ) }
												value={ styleExSticker.radius }
												onChange={ value => { onChangeStyleSticker( [ 'styleExSticker' ], 'radius', value ) } }
												min={ 0 }
												max={ 100 }
											/>

											<BaseControl
												label={ __( 'Font' ) }
											>
												<FontSizePicker
													disableCustomFontSizes={ true }
													value={ styleExSticker.fontSize }
													onChange={ value => { onChangeStyleSticker( [ 'styleExSticker' ], 'fontSize', value ) } }
													withReset={ true }
												/>
											</BaseControl>

											<BaseControl
												className={ 'components-custom-font-control' }
											>
												<LineHeightControl
													value={ styleExSticker.lineHeight }
													onChange={ value => { onChangeStyleSticker( [ 'styleExSticker' ], 'lineHeight', value ) } }
													size="__unstable-large"
													__unstableInputWidth="auto"
												/>
												
												<FontAppearanceControl
													value={ {
														fontStyle: fontStyleEx,
														fontWeight: fontWeightEx,
													} }
													onChange={ value => { onChangeStyleSticker( [ 'styleExSticker' ], false, value ) } }
													size="__unstable-large"
												/>

												<LetterSpacingControl
													value={ styleExSticker.letterSpacing }
													onChange={ value => { onChangeStyleSticker( [ 'styleExSticker' ], 'letterSpacing', value ) } }
													size="__unstable-large"
													__unstableInputWidth="auto"
												/>

												<TextTransformControl
													value={ styleExSticker.textTransform }
													onChange={ value => { onChangeStyleSticker( [ 'styleExSticker' ], 'textTransform', value ) } }
													showNone
													isBlock
													size="__unstable-large"
												/>
											</BaseControl>
										</Fragment>
									) : null }
								</Fragment>
							);
						}
					} }
				</TabPanel>
			</PanelBody>
		);
	}
}
