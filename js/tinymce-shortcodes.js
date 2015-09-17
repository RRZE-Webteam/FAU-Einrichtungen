(function() {

    tinymce.PluginManager.add('faurteshortcodes', function( editor )
    {	
		editor.addMenuItem('shortcode_organigram', {
			text: 'Organigramm einfügen',
			context: 'tools',
			onclick: function() {
				editor.insertContent('[organigram menu=""]');
			}
		});
		
		editor.addMenuItem('shortcode_assistant', {
			text: 'Assistenten einfügen',
			context: 'tools',
			onclick: function() {
				editor.insertContent('[assistant id=""]');
			}
		});
		
		editor.addMenuItem('shortcode_accordion', {
			text: 'Accordion einfügen',
			context: 'tools',
			onclick: function() {
				editor.insertContent('[collapsibles]<br>[collapse title="Name" color=""]<br>Hier der Text<br>[/collapse]<br>[collapse title="Name" color=""]<br>Hier der Text<br>[/collapse]<br>[/collapsibles]');
			}
		});
                
                
               
	/*	
		editor.addMenuItem('shortcode_person', {
			text: 'Person einfügen',
			context: 'tools',
			onclick: function() {
				editor.insertContent('[person id="" showlink="0" extended="0"]');
			}
		});
		
		editor.addMenuItem('shortcode_persons', {
			text: 'Personengalerie einfügen',
			context: 'tools',
			onclick: function() {
				editor.insertContent('[persons category="" showlink="0" extended="0"]');
			}
		});
*/		
		
		
		
		

    });
})();