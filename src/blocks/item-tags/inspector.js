/*
 * Inspector Settings
 */
import InspectorGlobalSettings from './components/inspector/global-settings';

const { __ } = wp.i18n;
const { Component } = wp.element;
const {
	InspectorControls,
	FontSizePicker,
	LineHeightControl,
	__experimentalFontAppearanceControl: FontAppearanceControl,
	__experimentalLetterSpacingControl: LetterSpacingControl,
	__experimentalTextTransformControl: TextTransformControl,
} = wp.blockEditor || wp.editor;
const {
	PanelBody,
	BaseControl,
	RangeControl,
	__experimentalBorderBoxControl: BorderBoxControl,
	__experimentalBoxControl: BoxControl
} = wp.components;

/**
 * Inspector controls
 */
export default class Inspector extends Component {
	render() {
		const { attributes, setAttributes } = this.props;

		const {
			styleCardSticker
		} = attributes;

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

		return (
			<InspectorControls>
				<InspectorGlobalSettings { ...this.props } initialOpen={ true } />
				<PanelBody title={ __( 'Sticker Settings' ) }>
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
				</PanelBody>
			</InspectorControls>
		);
	}
}
