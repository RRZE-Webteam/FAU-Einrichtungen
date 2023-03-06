wp.blocks.registerBlockType( 'my-blocks/full-width-image', {
    title: 'Outside-box Width Image',
    icon: 'format-image',
    category: 'common',
    attributes: {
        imageUrl: {
            type: 'string',
            source: 'attribute',
            selector: 'img',
            attribute: 'src',
        },
    },
    edit: function( props ) {
        function updateImage( value ) {
            props.setAttributes( { imageUrl: value.url } );
        }
        function onFileSelect( e ) {
            const selectedFile = e.target.files[0];
            const reader = new FileReader();
            reader.onload = (e) => {
                const url = e.target.result;
                updateImage( { url: url } );
            };
            reader.readAsDataURL( selectedFile );
        }
        return wp.element.createElement(
            'div',
            { className: 'my-custom-image-div' },
            wp.element.createElement(
                'img',
                { src: props.attributes.imageUrl, className: 'my-custom-image' }
            ),
            wp.element.createElement(
                'input',
                { type: 'file', accept: 'image/*', onChange: onFileSelect }
            )
        );
    },
    save: function( props ) {
        return wp.element.createElement(
            'div',
            { className: 'my-custom-image-div' },
            wp.element.createElement(
                'img',
                { src: props.attributes.imageUrl, className: 'my-custom-image' }
            )
        );
    },
} );
