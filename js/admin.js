/*
 * Image Upload for Banner/Logo Link Widget
 */
jQuery(document).ready(function($){
    var custom_uploader;

     $('body').on('click','.upload_image_button',function(e) {
        e.preventDefault();
        var button = $(this);
        var id = button.attr('id').replace('_button', '');
        var idimgid = button.attr('id').replace('url_button', 'id'); 
        var idtitle = button.attr('id').replace('image_url_button', 'title');
	
	if (custom_uploader) {
            custom_uploader.open();
            return;
        }
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: { text:'Choose Image' },
            library: { type: 'image' }, 
            multiple: false
        });
 
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();            
            $('#'+id).val(attachment.url);   
            $('#'+idimgid).val(attachment.id);   
            var pretitle = $('#'+idtitle).val();
            if (!pretitle)
                $('#'+idtitle).val(attachment.title);
           
        });
 
        //Open the uploader dialog
        custom_uploader.open(); 
    });

   
    if (($('#page_template').val() == 'page-templates/page-start.php')
     || ($('#page_template').val() == 'page-templates/page-start-sub.php')
     || ($('#page_template').val() == 'page-templates/page-portalindex.php')
     || ($('#page_template').val() == 'page-templates/page-portal.php')) {
	// show the meta box
	$('#fau_metabox_page_imagelinks').show();
	$('#fau_metabox_page_portalmenu').show();
	$('#fau_metabox_page_subnavmenu').hide();
	

	if (($('#page_template').val() == 'page-templates/page-portal.php')
  	 || ($('#page_template').val() == 'page-templates/page-portalindex.php')) {
	    $('#portalseitenquote').show();
	    $('#fau_metabox_page_untertitel').show();
	} else {
	    $('#portalseitenquote').hide();
	    $('#fau_metabox_page_untertitel').hide();
	    
	}
	
    } else {
	// hide your meta box
	$('#fau_metabox_page_imagelinks').hide();
	$('#fau_metabox_page_portalmenu').hide();
	$('#fau_metabox_page_untertitel').show();
	 
	if ($('#page_template').val() == 'page-templates/page-subnav.php') {
	    // show the meta box
	    $('#fau_metabox_page_subnavmenu').show();

	} else {
	    // hide your meta box
	    $('#fau_metabox_page_subnavmenu').hide();
        }

    }


    $('#page_template').live('change', function(){
	if (($(this).val() == 'page-templates/page-start.php') 
   	        || ($(this).val() == 'page-templates/page-start-sub.php')
		|| ($(this).val() == 'page-templates/page-portal.php')
		|| ($(this).val() == 'page-templates/page-portalindex.php')) {
	    // show the meta box
	    $('#fau_metabox_page_imagelinks').show();
	    $('#fau_metabox_page_subnavmenu').hide();   
	    $('#fau_metabox_page_portalmenu').show();

	    if (($(this).val() == 'page-templates/page-portal.php')
	      || ($(this).val() == 'page-templates/page-portalindex.php')) {
		$('#portalseitenquote').show();
		$('#fau_metabox_page_untertitel').show();
	    } else {
		$('#portalseitenquote').hide();
		$('#fau_metabox_page_untertitel').hide();
	    }

	   
	   
	} else {
	    // hide your meta box
	    $('#fau_metabox_page_imagelinks').hide();
	    $('#fau_metabox_page_portalmenu').hide();
	    $('#fau_metabox_page_untertitel').show();
	    if ($(this).val() == 'page-templates/page-subnav.php') {
		// show the meta box
		$('#fau_metabox_page_subnavmenu').show();

	    } else {
		// hide your meta box
		$('#fau_metabox_page_subnavmenu').hide();

	    }
	}
    });
    
   

   // Limit Main Menu
   if ($('body').hasClass('nav-menus-php')) {
	currenteditimenu = $('#nav-menu-meta-object-id').val();
	titlemain = $("label[for='locations-main-menu']").text();
	// console.log("title: "+ titlemain);
	// console.log("current menu: "+currenteditimenu); 
       
	mainmenunum = $('#select-menu-to-edit option:contains('+titlemain+')').val();
	// console.log("mainmenu: "+mainmenunum); 
       
       if (currenteditimenu == mainmenunum) {
	//  console.log("Working on main menu");
	   $('#add-custom-links').css("display", "none");
	   $('.menu-item-custom .menu-item-bar .menu-item-handle').css("background","red");
	   $('.menu-item-custom .menu-item-bar .menu-item-handle').append("<p><em>Bitte verwenden Sie keine externen Links im Hauptmenü, da dies zu Problemen in der Bedienbarkeit der Website führt.<br> Abhängig von der Konfiguration der Website in den Theme-Options werden externe Links ausgeblendet.</em></p>");
       
	

	    $('#nav-menus-frame').on('change click', function(){
		topmenuel = $('.menu-item-depth-0').length;
		menlength = 0;
		$('.menu-item-depth-0').each(function(){
		    menlength += $(this).find('.menu-item-title').text().length;
		});
		if ((topmenuel>7) && (menlength>60)) {
		    alert("Achtung: Ihr Hauptmenü enthält mehr als 7 Oberpunkte und ist auch zu breit. Bitte reduzieren Sie die Zahl der Oberpunkte, da der Webauftritt ansonsten zu unübersichtlich wird und Teile der Menüpunkte nicht sichtbar sein werden.");
		} else if (topmenuel>7) {
		    alert("Achtung: Ihr Hauptmenü enthält mehr als 7 Oberpunkte. Bitte reduzieren Sie die Zahl der Oberpunkte, da der Webauftritt ansonsten zu unübersichtlich wird.");
		} else if (menlength>60) {
		    alert("Achtung: Die Oberpunkte des Hauptmenüs enthalten zu viele Zeichen. Dadurch kann es zu Beeinträchtigungen bei der Anzeige der Webseite kommen. Bitte verkürzen Sie die Wortlänge der Oberpunkte.");
		}
	    });



	
	}   	   
   }
   
});
