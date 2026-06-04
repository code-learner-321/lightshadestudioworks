( function ( blocks, blockEditor, element ) {
    blocks.registerBlockType( 'lightshadestudioworks/my-custom-block', {
        edit: function () {
            const blockProps = blockEditor.useBlockProps();
            return element.createElement(
                'div',
                blockProps,
                element.createElement('p', null, 'My Custom Block (Editor View)')
            );
        },
        save: function () {
            return null;
        },
    } );
} )( window.wp.blocks, window.wp.blockEditor, window.wp.element );
