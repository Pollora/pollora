import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

/**
 * Mirrors save.jsx, so the editor shows the markup the page will hold.
 */
export default function Edit() {
    const blockProps = useBlockProps();

    return (
        <div {...blockProps}>
            <p>{__('Hero', 'pollora-starter')}</p>
        </div>
    );
}
