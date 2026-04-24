import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

export default function Edit() {
    const blockProps = useBlockProps();

    return (
        <div {...blockProps}>
            <p>{__('Hero – Block Editor', 'pollora-starter')}</p>
        </div>
    );
}
