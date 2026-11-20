import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

export default function Save() {
    const blockProps = useBlockProps.save();

    return (
        <div {...blockProps}>
            <p>{__('Hero', 'pollora-starter')}</p>
        </div>
    );
}
