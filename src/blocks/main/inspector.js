/*
 * Inspector Settings
 */
import InspectorLayoutSettings from './../global-inspector/layout-settings';
import InspectorStickerSettings from './../global-inspector/sticker-settings';

const { Component } = wp.element;
const { InspectorControls } = wp.blockEditor;

/**
 * Inspector controls
 */
export default class Inspector extends Component {
	render() {
		return (
			<InspectorControls>
				<InspectorLayoutSettings { ...this.props } initialOpen={ true } />
				<InspectorStickerSettings { ...this.props } />
			</InspectorControls>
		);
	}
}
