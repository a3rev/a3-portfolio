/*
 * Inspector Settings
 */
import InspectorGlobalSettings from './components/inspector/global-settings';

import InspectorContainerStyle, {
	cardSpacingAttributes,
} from './components/inspector/container-style';

const { Component } = wp.element;
const { InspectorControls } = wp.blockEditor;

export { cardSpacingAttributes };

/**
 * Inspector controls
 */
export default class Inspector extends Component {
	render() {
		return (
			<InspectorControls>
				<InspectorGlobalSettings { ...this.props } />
				<InspectorContainerStyle { ...this.props } />
			</InspectorControls>
		);
	}
}
