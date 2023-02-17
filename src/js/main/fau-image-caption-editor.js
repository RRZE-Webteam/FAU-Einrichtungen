jQuery(document).ready(function($) {
    wp.media.view.Attachment.Details.prototype.initialize = function() {
        wp.media.view.Attachment.prototype.initialize.apply( this, arguments );
        this.createCaptionEditor();
    };

    wp.media.view.Attachment.Details.prototype.createCaptionEditor = function() {
        var caption = this.model.get('caption');
        this.$('#attachment-details-caption').after('<label for="image_caption">Image Caption</label><div id="image_caption_editor"></div>');
        $('#attachment-details-caption').appendTo($('#image_caption_editor'));
        if (typeof tinyMCE !== 'undefined') {
            tinyMCE.execCommand('mceRemoveEditor', false, 'attachment-details-caption');
            tinyMCE.execCommand('mceAddEditor', false, 'attachment-details-caption');
        }
        else {
            $('#attachment-details-caption').wpEditor({
                mediaButtons: false,
                tinymce: {
                    toolbar1: 'bold,italic,link',
                    toolbar2: '',
                    toolbar3: '',
                    toolbar4: ''
                },
                quicktags: true,
                textarea_name: 'attachments[post_excerpt]',
                textarea_rows: 3,
                teeny: true
            });
        }
    };

    wp.media.view.Attachment.Details.prototype.events['change #attachment-details-caption'] = 'save';
});