/*
 * Inspector Settings
 */
import InspectorGlobalSettings from './components/inspector/global-settings';
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
				<InspectorGlobalSettings { ...this.props } />
				<InspectorLayoutSettings { ...this.props } />
				<InspectorStickerSettings { ...this.props } />
			</InspectorControls>
		);
	}
}
