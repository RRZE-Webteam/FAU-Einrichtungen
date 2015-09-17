(function() {

    tinymce.PluginManager.add('synonymrteshortcodes', function( editor )
    {
		
		editor.addMenuItem('shortcode_synonym', {
			text: 'Synonym einfügen',
			context: 'tools',
			onclick: function() {
				editor.insertContent('[synonym slug=""]');
			}
		});

    });
})();