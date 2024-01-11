<?php
/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package CGB
 */

namespace A3Rev\Portfolio\Blocks;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Styles {

	public function minimizeCSSsimple( $css ) {
		if(trim($css) === "") return $css;
		$css = preg_replace(
			array(
				// Remove comment(s)
				'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
				// Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
				'#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
				// Replace `:0 0 0 0` with `:0`
				'#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
				// Replace `background-position:0` with `background-position:0 0`
				'#(background-position):0(?=[;\}])#si',
				// Replace `0.6` with `.6`, but only when preceded by `:`, `,`, `-` or a white-space
				'#(?<=[\s:,\-])0+\.(\d+)#s',
				// Minify string value
				'#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
				'#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
				// Minify HEX color code
				'#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
				// Replace `(border|outline):none` with `(border|outline):0`
				'#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
				// Remove empty selector(s)
				'#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
			),
			array(
				'$1',
				'$1',
				':0',
				'$1:0 0',
				'.$1',
				'$1$3',
				'$1$2$4$5',
				'$1$2$3',
				'$1:0',
				'$1$2'
			),
		$css);

		$css = preg_replace('/\/\*((?!\*\/).)*\*\//', '', $css); // negative look ahead
		$css = preg_replace('/\s{2,}/', ' ', $css);
		$css = preg_replace('/\s*([:;{}])\s*/', '$1', $css);
		$css = preg_replace('/;}/', '}', $css);

		return $css;
	}

	public function getSpacingPresetCssVar( $value ) {
		if ( ! $value ) {
			return;
		}

		if ( empty( $value ) ) {
			return;
		}

		$slug = preg_match( '/var:preset\|spacing\|(.+)/', (String)$value, $matches );

		if ( ! $slug ) {
			return $value;
		}

		return "var(--wp--preset--spacing--{$matches[1]})";
	}

	public function spacingPresetCssVar( $value, $type = 'padding' ) {
		if ( ! $value ) {
			return;
		}
		$spacingTop    = isset( $value['top'] ) && !empty( $value['top'] ) ? "{$type}-top:" . $this->getSpacingPresetCssVar( $value['top'] ) . ';' : '';
		$spacingBottom = isset( $value['bottom'] ) && !empty( $value['bottom'] ) ? "{$type}-bottom:" . $this->getSpacingPresetCssVar( $value['bottom'] ) . ';' : '';
		$spacingLeft   = isset( $value['left'] ) && !empty( $value['left'] ) ? "{$type}-left:" . $this->getSpacingPresetCssVar( $value['left'] ) . ';' : '';
		$spacingRight  = isset( $value['right'] ) && !empty( $value['right'] ) ? "{$type}-right:" . $this->getSpacingPresetCssVar( $value['right'] ) . ';' : '';
		$css           = $spacingTop . $spacingBottom . $spacingLeft . $spacingRight;
		return $css;
	}

	public function borderPresetCssVar( $value ) {
		if ( ! $value ) {
			return;
		}
		$border = '';
		if ( isset( $value['color'] ) || isset( $value['style'] ) || isset( $value['width'] ) ) {
			$borderStyle = isset( $value['style'] ) && !empty( $value['style'] ) ? $value['style'] : '';
			$borderColor = isset( $value['color'] ) && !empty( $value['color'] ) ? $value['color'] : '';
			$borderWidth = isset( $value['width'] ) && !empty( $value['width'] ) ? $value['width'] : '';

			$borderstyle = "{$borderWidth} {$borderStyle} {$borderColor}";
			if( !empty( $borderstyle ) ){
				$border .= "border:{$borderstyle};";
			}
			
		} else if ( isset( $value['top'] ) || isset( $value['bottom'] ) || isset( $value['left'] ) || isset( $value['right'] ) ) {
			$borderTopStyle = isset( $value['top']['style'] ) ? $value['top']['style'] : '';
			$borderTopColor = isset( $value['top']['color'] ) ? $value['top']['color'] : '';
			$borderTopWidth = isset( $value['top']['width'] ) ? $value['top']['width'] : '';

			$borderTopCss = "{$borderTopWidth} {$borderTopStyle} {$borderTopColor}";
			if( !empty( $borderTopCss ) ){
				$border .= "border-top:{$borderTopCss};";
			}

			
			$borderLeftStyle = isset( $value['left']['style'] ) ? $value['left']['style'] : '';
			$borderLeftColor = isset( $value['left']['color'] ) ? $value['left']['color'] : '';
			$borderLeftWidth = isset( $value['left']['width'] ) ? $value['left']['width'] : '';

			$borderLeftCss = "{$borderLeftWidth} {$borderLeftStyle} {$borderLeftColor}";
			if( !empty( $borderLeftCss ) ){
				$border .= "border-left:{$borderLeftCss};";
			}

			$borderRightStyle = isset( $value['right']['style'] ) ? $value['right']['style'] : '';
			$borderRightColor = isset( $value['right']['color'] ) ? $value['right']['color'] : '';
			$borderRightWidth = isset( $value['right']['width'] ) ? $value['right']['width'] : '';

			$borderRightCss = "{$borderRightWidth} {$borderRightStyle} {$borderRightColor}";
			if( !empty( $borderRightCss ) ){
				$border .= "border-right:{$borderRightCss};";
			}

			$borderBottomStyle = isset( $value['bottom']['style'] ) ? $value['bottom']['style'] : '';
			$borderBottomColor = isset( $value['bottom']['color'] ) ? $value['bottom']['color'] : '';
			$borderBottomWidth = isset( $value['bottom']['width'] ) ? $value['bottom']['width'] : '';
			$border .= "border-bottom:{$borderBottomWidth} {$borderBottomStyle} {$borderBottomColor};";

			$borderBottomCss = "{$borderBottomWidth} {$borderBottomStyle} {$borderBottomColor}";
			if( !empty( $borderBottomCss ) ){
				$border .= "border-right:{$borderBottomCss};";
			}
		}
		return $border;
	}

	public function borderRadiusPresetCssVar($value) {
		if (!$value) {
			return;
		}

		$borderRadius = '';
		if (isset($value['topLeft']) || isset($value['topRight']) || isset($value['bottomLeft']) || isset($value['bottomRight'])) {
			$topLeft = isset($value['topLeft']) && !empty( $value['topLeft'] ) ? "border-top-left-radius:{$value['topLeft']};" : '';
			$topRight = isset($value['topRight']) && !empty( $value['topRight'] ) ? "border-top-right-radius:{$value['topRight']};" : '';
			$bottomRight = isset($value['bottomRight']) && !empty( $value['bottomRight'] ) ? "border-bottom-right-radius:{$value['bottomRight']};" : '';
			$bottomLeft = isset($value['bottomLeft']) && !empty( $value['bottomLeft'] ) ? "border-bottom-left-radius:{$value['bottomLeft']};" : '';
			$borderRadius = $topLeft . $topRight . $bottomLeft . $bottomRight;
		} else {
			$borderRadius = is_string($value) && !empty( $value ) ? "border-radius:{$value};" : '';
		}
		return $borderRadius;
	}

	public function typographyPresetCssVar( $value ) {
		if ( ! $value ) {
			return;
		}

		$color = isset( $value['color'] ) && !empty( $value['color'] ) ? 'color: ' . $value['color'] . ';' : '';
		$fontFamily = isset( $value['fontFamily'] ) && !empty( $value['fontFamily'] ) ? 'font-family: ' . $value['fontFamily'] . ';' : '';
		$fontSize = isset( $value['fontSize'] ) && !empty( $value['fontSize'] ) ? 'font-size: ' . $value['fontSize'] . ';' : '';
		$lineHeight = isset( $value['lineHeight'] ) && !empty( $value['lineHeight'] ) ? 'line-height: ' . $value['lineHeight'] . ';' : '';
		$fontStyle = isset( $value['fontStyle'] ) && !empty( $value['fontStyle'] ) ? 'font-style: ' . $value['fontStyle'] . ';' : '';
		$fontWeight = isset( $value['fontWeight'] ) && !empty( $value['fontWeight'] ) ? 'font-weight: ' . $value['fontWeight'] . ';' : '';
		$letterSpacing = isset( $value['letterSpacing'] ) && !empty( $value['letterSpacing'] ) ? 'letter-spacing: ' . $value['letterSpacing'] . ';' : '';
		$textDecoration = isset( $value['textDecoration'] ) && !empty( $value['textDecoration'] ) ? 'text-decoration: ' . $value['textDecoration'] . ';' : '';
		$textTransform = isset( $value['textTransform'] ) && !empty( $value['textTransform'] ) ? 'text-transform: ' . $value['textTransform'] . ';' : '';

		$fonts = $color . $fontFamily . $fontSize . $lineHeight . $fontStyle . $fontWeight . $letterSpacing . $textDecoration . $textTransform;

		return $fonts;
	}
}
