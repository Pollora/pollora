import { registerBlockType } from '@wordpress/blocks';
import Edit from './edit';
import metadata from './block.json';
import './editor.css';
import './style.css';

registerBlockType(metadata.name, {
    edit: Edit,
    save: () => null, // Dynamic block — rendered server-side
});
