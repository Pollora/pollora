import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function Save({ attributes }) {
    const { quote, authorName, authorRole, avatarUrl } = attributes;
    const blockProps = useBlockProps.save();

    return (
        <blockquote {...blockProps}>
            <RichText.Content
                tagName="p"
                className="wp-block-blocks-demo-testimonial__quote"
                value={quote}
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
                    <RichText.Content
                        tagName="cite"
                        className="wp-block-blocks-demo-testimonial__name"
                        value={authorName}
                    />
                    <RichText.Content
                        tagName="span"
                        className="wp-block-blocks-demo-testimonial__role"
                        value={authorRole}
                    />
                </div>
            </footer>
        </blockquote>
    );
}
