/*
 * Inspector Settings
 */
import InspectorLayoutSettings from './../global-inspector/layout-settings';

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
			</InspectorControls>
		);
	}
}
