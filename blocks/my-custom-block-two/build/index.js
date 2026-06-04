( function ( blocks, blockEditor, element ) {
    blocks.registerBlockType( 'lightshadestudioworks/my-custom-block-two', {
        edit: function () {
            const blockProps = blockEditor.useBlockProps();
            return element.createElement(
                'div',
                blockProps,
                element.createElement('p', null, 'My Custom Block Two (Editor View)')
            );
        },
        save: function () {
            return null;
        },
    } );
} )( window.wp.blocks, window.wp.blockEditor, window.wp.element );
