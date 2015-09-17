(function() {

    tinymce.PluginManager.add('glossaryrteshortcodes', function( editor )
    {
		
		editor.addMenuItem('shortcode_glossary', {
			text: 'Glossar einfügen',
			context: 'tools',
			onclick: function() {
				editor.insertContent('[glossary category=""]');
			}
		});

    });
})();