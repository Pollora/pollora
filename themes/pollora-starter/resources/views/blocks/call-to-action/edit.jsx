import { useBlockProps, RichText, InspectorControls, URLInput } from '@wordpress/block-editor';
import { PanelBody, TextControl, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes, setAttributes }) {
    const { heading, description, buttonText, buttonUrl, alignment } = attributes;
    const blockProps = useBlockProps({ className: `has-text-align-${alignment}` });

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Button Settings', 'pollora-starter')}>
                    <TextControl
                        label={__('Button Text', 'pollora-starter')}
                        value={buttonText}
                        onChange={(val) => setAttributes({ buttonText: val })}
                    />
                    <URLInput
                        label={__('Button URL', 'pollora-starter')}
                        value={buttonUrl}
                        onChange={(val) => setAttributes({ buttonUrl: val })}
                    />
                </PanelBody>
                <PanelBody title={__('Layout', 'pollora-starter')}>
                    <SelectControl
                        label={__('Text Alignment', 'pollora-starter')}
                        value={alignment}
                        options={[
                            { label: __('Left', 'pollora-starter'), value: 'left' },
                            { label: __('Center', 'pollora-starter'), value: 'center' },
                            { label: __('Right', 'pollora-starter'), value: 'right' },
                        ]}
                        onChange={(val) => setAttributes({ alignment: val })}
                    />
                </PanelBody>
            </InspectorControls>

            <div {...blockProps}>
                <RichText
                    tagName="h2"
                    className="wp-block-pollora-starter-call-to-action__heading"
                    value={heading}
                    onChange={(val) => setAttributes({ heading: val })}
                    placeholder={__('Your heading here…', 'pollora-starter')}
                />
                <RichText
                    tagName="p"
                    className="wp-block-pollora-starter-call-to-action__description"
                    value={description}
                    onChange={(val) => setAttributes({ description: val })}
                    placeholder={__('Add a description…', 'pollora-starter')}
                />
                <div className="wp-block-pollora-starter-call-to-action__button-wrapper">
                    <RichText
                        tagName="span"
                        className="wp-block-pollora-starter-call-to-action__button"
                        value={buttonText}
                        onChange={(val) => setAttributes({ buttonText: val })}
                        placeholder={__('Button text', 'pollora-starter')}
                    />
                </div>
            </div>
        </>
    );
}
