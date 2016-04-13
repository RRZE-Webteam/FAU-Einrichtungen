(function() {
    tinymce.PluginManager.add('faurteshortcodes', function( editor )  {	
	    
	var menuItems = [];
	menuItems.push({
	    type: 'menuitem',
	    text: 'Accordion einfügen',
	    onclick: function() {
	    	editor.insertContent('[collapsibles]<br>[collapse title="Name" color=""]<br>Hier der Text<br>[/collapse]<br>[collapse title="Name" color=""]<br>Hier der Text<br>[/collapse]<br>[/collapsibles]');
	    }
	});
	menuItems.push({
	    type: 'menuitem',
	    text: 'Warnhinweis einfügen',
	    onclick: function() {
	    	editor.insertContent('[attention]Hier der Text[/attention]');
	}
	});
	menuItems.push({
	    type: 'menuitem',
	    text: 'Hinweis einfügen',
	    onclick: function() {
	    	editor.insertContent('[hinweis]Hier der Text[/hinweis]');
	    }
	});
	menuItems.push({
	    type: 'menuitem',
	    text: 'Baustellenhinweis einfügen',
	    onclick: function() {
		editor.insertContent('[baustelle]Hier der Text[/baustelle]');
	    }
	});
	menuItems.push({
	    type: 'menuitem',
	    text: 'Fragezeichen einfügen',
	    onclick: function() {
		editor.insertContent('[question]Hier der Text[/question]');
	    }
	});
	menuItems.push({
	    type: 'menuitem',
	    text: 'Minus-Absatz einfügen',
	    onclick: function() {
		editor.insertContent('[minus]Hier der Text[/minus]');
	    }
	});

	menuItems.push({
	    type: 'menuitem',
	    text: 'Plus-Absatz einfügen',
	    onclick: function() {
		editor.insertContent('[plus]Hier der Text[/plus]');
	    }
	});
	menuItems.push({
	    type: 'menuitem',
	    text: 'Organigramm (Menu) einfügen',
	    onclick: function() {
		editor.insertContent('[organigram menu=""]');
	    }
	});
	menuItems.push({
	    type: 'menuitem',
	    text: 'Assistenten einfügen',
	    onclick: function() {
		editor.insertContent('[assistant id=""]');
	    }
	});


	editor.addMenuItem('InsertShortcodes', {
	    icon: 'code',
	    text: 'Shortcodes',
	    menu: menuItems,
	    context: 'insert',
	});


    });
})();