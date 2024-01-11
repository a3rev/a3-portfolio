/*
 * Inspector Settings
 */
import InspectorGlobalSettings from './components/inspector/global-settings';
import InspectorStickerSettings from './../global-inspector/sticker-settings';
import InspectorContainerStyle from './components/inspector/container-style';

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
				<InspectorContainerStyle { ...this.props } />
				<InspectorStickerSettings { ...this.props } />
			</InspectorControls>
		);
	}
}
