/*
 *  Hide or show options for portalmenus and andimagelinks depending on pagetype
 *  
 * @since FAU 2.3.20
 */
jQuery(document).ready(function($){
    if ($("body").hasClass("block-editor-page") == false) {
	if (($('#page_template').val() == 'page-templates/page-start.php')
	 || ($('#page_template').val() == 'page-templates/page-start-sub.php')
	 || ($('#page_template').val() == 'page-templates/page-portalindex.php')
	 || ($('#page_template').val() == 'templates/template-landing-page.php')
	 || ($('#page_template').val() == 'page-templates/page-portal.php'))  {
	    // show the meta box
	    $('#fau_metabox_page_imagelinks').show();
	    $('#fau_metabox_page_portalmenu').show();
	    $('#fau_metabox_page_portalmenu_oben').show();
	    $('#fau_metabox_page_subnavmenu').hide();


	    if (($('#page_template').val() == 'page-templates/page-portal.php')
	     || ($('#page_template').val() == 'page-templates/page-portalindex.php')) {
		$('#fau_metabox_page_untertitel').show();
	    } else {
		$('#fau_metabox_page_untertitel').hide();

	    }

	} else {
	    // hide your meta box
	    $('#fau_metabox_page_imagelinks').hide();
	    $('#fau_metabox_page_portalmenu').hide();
	    $('#fau_metabox_page_portalmenu_oben').hide();
	    $('#fau_metabox_page_untertitel').show();

	    if ($('#page_template').val() == 'page-templates/page-subnav.php') {
		// show the meta box
		$('#fau_metabox_page_subnavmenu').show();

	    } else {
		// hide your meta box
		$('#fau_metabox_page_subnavmenu').hide();
	    }

	}

	$(document).on('change','#page_template', function(){
	    if (($(this).val() == 'page-templates/page-start.php') 
		    || ($(this).val() == 'page-templates/page-start-sub.php')
		    || ($(this).val() == 'page-templates/page-portal.php')
		    || ($(this).val() == 'templates/template-landing-page.php')
		    || ($(this).val() == 'page-templates/page-portalindex.php')) {
		// show the meta box
		$('#fau_metabox_page_imagelinks').show();
		$('#fau_metabox_page_subnavmenu').hide();   
		$('#fau_metabox_page_portalmenu').show();
		$('#fau_metabox_page_portalmenu_oben').show();

		if (($(this).val() == 'page-templates/page-portal.php')
		  || ($(this).val() == 'page-templates/page-portalindex.php')) {
		    $('#fau_metabox_page_untertitel').show();
		} else {
		    $('#fau_metabox_page_untertitel').hide();
		}



	    } else {
		// hide your meta box
		$('#fau_metabox_page_imagelinks').hide();
		$('#fau_metabox_page_portalmenu').hide();
		$('#fau_metabox_page_portalmenu_oben').hide();
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
    

    }

});
