/**
 * Internal dependencies
 */
import map from 'lodash/map';

import { PaddingControl, SpacingAttributes, IconBox } from '@bit/a3revsoftware.blockpress.spacing';

const { __ } = wp.i18n;
const { Component } = wp.element;
const { PanelBody, BaseControl, ToggleControl, ButtonGroup, Button, RangeControl } = wp.components;

const fieldName = '';
const cardSpacingAttributes = SpacingAttributes( fieldName );

export { cardSpacingAttributes };

/**
 * Inspector controls
 */
export default class InspectorContainerStyle extends Component {
	render() {
		const { attributes, setAttributes } = this.props;

		const { align, alignWrap, width, widthUnit, enableCustomColumns, customColumns } = attributes;

		const widthUnitList = [ { key: 'px', name: __( 'px' ) }, { key: '%', name: __( '%' ) } ];

		return (
			<PanelBody
				className="a3-blockpress-inspect-panel a3-portfolio-inspect-panel"
				title={ __( 'Container Style' ) }
				initialOpen={ false }
			>
				{ 'left' === align || 'right' === align ? (
					<ToggleControl
						label={ __( 'Align Wrap' ) }
						checked={ !! alignWrap }
						onChange={ () => setAttributes( { alignWrap: ! alignWrap } ) }
					/>
				) : null }

				<ToggleControl
					label={ __( 'Portfolio Cards / Row' ) }
					help={
						enableCustomColumns ?
							__( 'Using custom Columns for this block' ) :
							__( 'Using global Columns from Settings Panel' )
					}
					checked={ !! enableCustomColumns }
					onChange={ () => setAttributes( { enableCustomColumns: ! enableCustomColumns } ) }
				/>

				{ enableCustomColumns ? (
					<RangeControl
						label={ __( 'Custom Columns' ) }
						value={ customColumns ? customColumns : parseInt( a3_portfolio_blocks_vars.globalColumn ) }
						onChange={ value => setAttributes( { customColumns: value } ) }
						min={ 1 }
						max={ 6 }
						allowReset
					/>
				) : null }

				<ButtonGroup
					className="a3-blockpress-size-type-options"
					aria-label={ __( 'Container Width Type' ) }
				>
					{ map( widthUnitList, ( { name, key } ) => (
						<Button
							key={ key }
							className="size-type-btn"
							isSmall
							isPrimary={ widthUnit === key }
							aria-pressed={ widthUnit === key }
							onClick={ () => setAttributes( { widthUnit: key } ) }
						>
							{ name }
						</Button>
					) ) }
				</ButtonGroup>
				<RangeControl
					label={ __( 'Container Width' ) }
					value={ width ? width : 600 }
					onChange={ value => setAttributes( { width: value } ) }
					min={ 'px' === widthUnit ? 300 : 10 }
					max={ 'px' === widthUnit ? 2000 : 100 }
					allowReset
				/>

				<BaseControl className="a3-blockpress-control-spacing">
					<IconBox />
					<PaddingControl { ...this.props } fieldName={ fieldName } />
				</BaseControl>
			</PanelBody>
		);
	}
}
