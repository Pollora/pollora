import { useBlockProps, RichText, MediaUpload, MediaUploadCheck, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes, setAttributes }) {
    const { quote, authorName, authorRole, avatarUrl, avatarId } = attributes;
    const blockProps = useBlockProps();

    const onSelectAvatar = (media) => {
        setAttributes({
            avatarUrl: media.url,
            avatarId: media.id,
        });
    };

    const onRemoveAvatar = () => {
        setAttributes({ avatarUrl: '', avatarId: 0 });
    };

    return (
        <>
            <InspectorControls>
                <PanelBody title={__('Avatar', 'blocks-demo')}>
                    <MediaUploadCheck>
                        <MediaUpload
                            onSelect={onSelectAvatar}
                            allowedTypes={['image']}
                            value={avatarId}
                            render={({ open }) => (
                                <>
                                    {avatarUrl ? (
                                        <>
                                            <img src={avatarUrl} alt="" style={{ width: '100%', borderRadius: '50%', marginBottom: '8px' }} />
                                            <Button isDestructive onClick={onRemoveAvatar} variant="secondary" style={{ width: '100%' }}>
                                                {__('Remove', 'blocks-demo')}
                                            </Button>
                                        </>
                                    ) : (
                                        <Button onClick={open} variant="primary" style={{ width: '100%' }}>
                                            {__('Select Avatar', 'blocks-demo')}
                                        </Button>
                                    )}
                                </>
                            )}
                        />
                    </MediaUploadCheck>
                </PanelBody>
            </InspectorControls>

            <blockquote {...blockProps}>
                <RichText
                    tagName="p"
                    className="wp-block-blocks-demo-testimonial__quote"
                    value={quote}
                    onChange={(val) => setAttributes({ quote: val })}
                    placeholder={__('Write the testimonial quote…', 'blocks-demo')}
                />
                <footer className="wp-block-blocks-demo-testimonial__footer">
                    {avatarUrl && (
                        <img
                            className="wp-block-blocks-demo-testimonial__avatar"
                            src={avatarUrl}
                            alt={authorName}
                        />
                    )}
                    <div className="wp-block-blocks-demo-testimonial__author">
                        <RichText
                            tagName="cite"
                            className="wp-block-blocks-demo-testimonial__name"
                            value={authorName}
                            onChange={(val) => setAttributes({ authorName: val })}
                            placeholder={__('Author name', 'blocks-demo')}
                        />
                        <RichText
                            tagName="span"
                            className="wp-block-blocks-demo-testimonial__role"
                            value={authorRole}
                            onChange={(val) => setAttributes({ authorRole: val })}
                            placeholder={__('Role / Company', 'blocks-demo')}
                        />
                    </div>
                </footer>
            </blockquote>
        </>
    );
}
