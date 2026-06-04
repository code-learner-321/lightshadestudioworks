import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import blockInfo from './block.json';

registerBlockType(blockInfo.name, {
    edit: () => {
        const blockProps = useBlockProps();
        return (
            <div {...blockProps}>
                <p>My Custom Block (Editor View)</p>
            </div>
        );
    },
    save: () => {
        return null;
    },
});