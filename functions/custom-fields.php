<?php
/*-----------------------------------------------------------------------------------*/
/* Metaboxes and adjustments for generell custom fields 
/*-----------------------------------------------------------------------------------*/

add_action('load-post.php', 'fau_metabox_cf_setup');
add_action('load-post-new.php', 'fau_metabox_cf_setup');

/*-----------------------------------------------------------------------------------*/
/* Meta box setup function.
/*-----------------------------------------------------------------------------------*/
function fau_metabox_cf_setup() {
    
    /* Display Metabox */
    add_action('add_meta_boxes_page', 'fau_add_metabox_page');
    add_action('add_meta_boxes_post', 'fau_add_metabox_post');


    /* Save sidecontent */
    add_action('save_post', 'fau_save_metabox_page_untertitel', 10, 2);

    add_action('save_post', 'fau_save_metabox_page_portalmenu', 10, 2);
    
    add_action('save_post', 'fau_save_metabox_page_portalmenu_oben', 10, 2);

    add_action('save_post', 'fau_save_metabox_page_sidebar', 10, 2);

    if (get_theme_mod('advanced_post_active_subtitle') == true) {
        add_action('save_post', 'fau_save_metabox_post_untertitel', 10, 2);
    }
    
    if (get_theme_mod('advanced_beitragsoptionen') == true) {
        add_action('save_post', 'fau_save_post_teaser', 10, 2);
    }
    
    if (get_theme_mod('advanced_topevent') == true) {
        add_action('save_post', 'fau_save_post_topevent', 10, 2);
    }
   
    // Speichere Seiten-Eigenschaften
    add_action('save_post', 'fau_save_metabox_page_additional_attributes', 10, 2);
}
/*-----------------------------------------------------------------------------------*/
/*  Create one or more meta boxes to be displayed on the post editor screen. 
/*-----------------------------------------------------------------------------------*/

function fau_add_metabox_page() {
    // Einige der Metaboxen sollten wir "bald" zu einem Block in Gutenberg wandeln
    // vgl. https://wordpress.org/gutenberg/handbook/extensibility/meta-box/
	    
    
    add_meta_box(
        'fau_metabox_page_untertitel',			
        esc_html__( 'Untertitel', 'fau' ), 'fau_do_metabox_page_untertitel', 'page','normal','low',
	array(
	    '__block_editor_compatible_meta_box' => true,
	)
    );
    

    add_meta_box(
        'fau_metabox_page_portalmenu',			
        esc_html__( 'Portalmenu unten auf Portalseiten', 'fau' ),		
        'fau_do_metabox_page_portalmenu',		
        'page','side','low'
    );

     add_meta_box(
        'fau_metabox_page_portalmenu_oben',			
        esc_html__( 'Portalmenu oben auf Portalseiten', 'fau' ),		
        'fau_do_metabox_page_portalmenu_oben',		
        'page','side','low'
    );


  

    add_meta_box(
        'fau_metabox_page_sidebar',			
        esc_html__( 'Sidebar', 'fau' ),		
        'fau_do_metabox_page_sidebar',		
        'page','normal','low'
    );

    
     add_meta_box(
        'fau_metabox_page_additional_attributes',			
        esc_html__( 'Seiten-Eigenschaften', 'fau' ),		
        'fau_do_metabox_page_additional_attributes',		
        'page','side','low',
	array(
	    '__block_editor_compatible_meta_box' => true,
	)
    );
    
}

/*-----------------------------------------------------------------------------------*/
/*  Metaboxes fuer Posts
/*-----------------------------------------------------------------------------------*/
function fau_add_metabox_post() {
   
    add_meta_box(
        'fau_metabox_post_teaser', 
        esc_html__('Beitragsoptionen', 'fau'), 
        'fau_do_metabox_post_teaser', 
        'post', 'normal', 'high'
    );
    
    if (get_theme_mod('advanced_post_active_subtitle') == true) {
        add_meta_box(
            'fau_metabox_post_untertitel', 
            esc_html__('Untertitel', 'fau'), 
            'fau_do_metabox_post_untertitel', 
            'post', 'normal', 'high'
        );
    }
    
    if (get_theme_mod('advanced_topevent') == true) {
        add_meta_box(
            'fau_metabox_post_topevent', 
            esc_html__('Top-Event', 'fau'), 
            'fau_do_metabox_post_topevent', 
            'post', 'normal', 'high'
        );
    }
}
/*-----------------------------------------------------------------------------------*/
/*  Display Options for posts
/*-----------------------------------------------------------------------------------*/
function fau_do_metabox_post_teaser($object, $box) {
    global $options;

    wp_nonce_field(basename(__FILE__), 'fau_metabox_post_teaser_nonce');

    if (!current_user_can('edit_post', $object->ID)) {
        return;
    }

    echo "<p>\n";
    echo __('Bitte beachten: Damit ein Artikel auf der Startseite angezeigt werden soll, muss er das folgende Schlagwort erhalten: ', 'fau');
    echo '<b>' . get_theme_mod('start_prefix_tag_newscontent') . '</b> - ' . __('Dies gefolgt von einer Nummer (1-3) für die Reihenfolge.', 'fau');
    
    $slider_catid = get_theme_mod('slider-catid'); 
    if (isset($slider_catid) && $slider_catid > 0) {
        $category = get_category($slider_catid);
        if (isset($category->name)) {
            echo ' ' . __('Damit ein Artikel in der Bühne erscheint, muss er folgender Kategorie angehören: ', 'fau');
            echo '<b>' . $category->name . '</b>';
        }
    }

    echo "</p>\n";

    if (get_theme_mod('advanced_beitragsoptionen') == true) {

        $howto = __('Kurztext für die Bühne und den Newsindex (Startseite und Indexseiten). Wenn leer, wird der Kurztext automatisch aus dem Inhalt abzüglich der erlaubten Zeichen gebildet. ', 'fau');
        $howto .= '<br>' . __('Erlaubte Anzahl an Zeichen:', 'fau');
        $howto .= ' <span class="fauval_anleser_signs">' . get_theme_mod('default_anleser_excerpt_length') . '</span>';


        $abstract = get_post_meta($object->ID, 'abstract', true);
        fau_form_textarea('fauval_anleser', $abstract, __('Anleser', 'fau'), 80, 5, $howto);

        $external_link = get_post_meta($object->ID, 'external_link', true);
        fau_form_url('fauval_external_link', $external_link, __("Externer Link", 'fau'), __('Wenn der Artikel nicht auf der Website liegt, sondern auf eine externe Seite verlinkt werden soll, ist hier eine URL anzugeben.', 'fau'), $placeholder = 'http://', $size = 0);

        $override_thumbdesc = get_post_meta($object->ID, 'fauval_overwrite_thumbdesc', true);
        fau_form_text('fauval_overwrite_thumbdesc', $override_thumbdesc, __('Ersetze Bildbeschreibung', 'fau'), __('Mit diesem optionalen Text kann die Bildunterschrift des verwendeten Beitragsbildes durch einen eigenen Text ersetzt werden, der nur für diesen Beitrag gilt.', 'fau'));

        $sliderimage = get_post_meta($object->ID, 'fauval_slider_image', true);
        fau_form_image('fauval_slider_image', $sliderimage, __('Bühnenbild', 'fau'), __('An dieser Stelle kann optional ein alternatives Bild für die Bühne der Startseite ausgewählt werden, falls das normale Beitragsbild hierzu nicht verwendet werden soll.', 'fau'), 540, 150);
    }
}
/*-----------------------------------------------------------------------------------*/
/* Save the meta box's post metadata.
/*-----------------------------------------------------------------------------------*/
function fau_save_post_teaser($post_id, $post) {
    /* Verify the nonce before proceeding. */
    if (!isset($_POST['fau_metabox_post_teaser_nonce']) || !wp_verify_nonce($_POST['fau_metabox_post_teaser_nonce'], basename(__FILE__))) {
        return;
    }

    $post_type = get_post_type($post_id);

    if ('post' != $post_type || !current_user_can('edit_post', $post_id)) {
        return;
    }

    $newval = isset($_POST['fauval_anleser']) ? wp_filter_nohtml_kses(trim($_POST['fauval_anleser'])) : '';
    $oldval = get_post_meta( $post_id, 'abstract', true );

    if (!empty($newval) && !empty($oldval)) {
        update_post_meta($post_id, 'abstract', $newval);
    } elseif (!empty($newval) && empty($oldval)) {
        add_post_meta($post_id, 'abstract', $newval, true);
    } else {
        delete_post_meta($post_id, 'abstract');
    }

    $newval = isset($_POST['fauval_external_link']) && esc_url(trim($_POST['fauval_external_link'])) ? esc_url(trim($_POST['fauval_external_link'])) : '';
    $oldval = get_post_meta( $post_id, 'external_link', true );

    if (!empty($newval) && !empty($oldval)) {
        update_post_meta($post_id, 'external_link', $newval);
    } elseif (!empty($newval) && empty($oldval)) {
        add_post_meta($post_id, 'external_link', $newval, true);
    } else {
        delete_post_meta($post_id, 'external_link');
    }

    $newval = isset($_POST['fauval_overwrite_thumbdesc']) ? wp_filter_nohtml_kses(trim($_POST['fauval_overwrite_thumbdesc'])) : '';
    $oldval = get_post_meta( $post_id, 'fauval_overwrite_thumbdesc', true );

    if (!empty($newval) && !empty($oldval)) {
        update_post_meta($post_id, 'fauval_overwrite_thumbdesc', $newval);
    } elseif (!empty($newval) && empty($oldval)) {
        add_post_meta($post_id, 'fauval_overwrite_thumbdesc', $newval, true);
    } else {
        delete_post_meta($post_id, 'fauval_overwrite_thumbdesc');
    }

    $newval = isset($_POST['fauval_slider_image']) ? absint($_POST['fauval_slider_image']) : 0;
    $oldval = get_post_meta( $post_id, 'fauval_slider_image', true );

    if (!empty($newval) && !empty($oldval)) {
        update_post_meta($post_id, 'fauval_slider_image', $newval);
    } elseif (!empty($newval) && empty($oldval)) {
        add_post_meta($post_id, 'fauval_slider_image', $newval, true);
    } else {
        delete_post_meta($post_id, 'fauval_slider_image');
    }
	
}
 
/* Display Options for pages */
function fau_do_metabox_post_topevent($object, $box) { 
    global $defaultoptions;
    wp_nonce_field( basename( __FILE__ ), 'fau_metabox_post_topevent_nonce' );

    if (!current_user_can('edit_post', $object->ID)) {
        return;
    }

    $istopevent  = get_post_meta( $object->ID, 'topevent_active', true ); 
    fau_form_onoff('fauval_topevent_active',$istopevent,__('Diesen Artikel als Top-Event anzeigen.','fau'));	


    if (!empty(get_theme_mod('start_topevents_tag'))) {	
       echo '<p>'.__('Bitte beachten: Damit ein Artikel als Top-Event angezeigt wird, muss entweder obige Option gesetzt sein oder der Artikel muss ein gültiges Datum für einen Top-Event angegeben haben, welches noch nicht abgelaufen ist.','fau').'</p>';
    }

    $topevent_title  = get_post_meta( $object->ID, 'topevent_title', true );
    fau_form_text('fauval_topevent_title', $topevent_title, __('Titel','fau'), __('Titel wie er in der Sidebar erscheinen soll. Wenn leer, wird der normale Titel des Beitrags verwendet.','fau'));

    $topevent_desc  = get_post_meta( $object->ID, 'topevent_description', true );
    $help  = __('Kurztext für die Sidebar. Wenn leer, wird der Anleser verwendet.','fau');
    $help  .= ' '. __('Erlaubte Anzahl an Zeichen:','fau');
    $help  .=  ' <span class="fauval_topevent_desc_signs">'.get_theme_mod('default_topevent_excerpt_length').'</span>';    
    fau_form_textarea('fauval_topevent_desc', $topevent_desc, __( "Kurzbeschreibung", 'fau' ),40,5, $help); 	    	



    $topevent_date  = get_post_meta( $object->ID, 'topevent_date', true );
    $topevent_image  = get_post_meta( $object->ID, 'topevent_image', true );	    
    $formateddate = date_i18n( "d-m-Y", strtotime( $topevent_date ) );
    ?>
    <div class="optionseingabe">
        <p>
            <label for="fauval_topevent_date">
                <?php _e( "Datum", 'fau' ); ?>:
            </label>
        </p>
        <input type="date" name="fauval_topevent_date" id="fauval_topevent_date" class="text" value="<?php echo $formateddate; ?>">		
        <br/>
        <div class="howto">
        <?php echo __('Geben Sie hier das Datum des Events ein.','fau');
        echo __('<br>Nutzen Sie das Format: "Tag-Monat-Jahr", wobei Sie Tage und Monate mit führenden Nullen schreiben und das Jahr vierstellig. Beispiel: 01-12-2016 für den ersten Dezember 2016.','fau');?>
        </div>
        <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery("#fauval_topevent_date" ).each(function(){
                this.type="text";
             });
            jQuery("#fauval_topevent_date" ).datepicker();
            jQuery("#fauval_topevent_date" ).datepicker( "option", "dateFormat", 'dd-mm-yy' );
            <?php if (isset($topevent_date)) { ?>
                 jQuery("#fauval_topevent_date" ).datepicker( "setDate", '<?php echo $formateddate ?>' );
            <?php } ?>	
            });
        </script>
    </div>
    <div class="optionseingabe">
        <p>
            <label for="fauval_topevent_image">
                <?php _e( "Symbolbild", 'fau' ); ?>:
            </label>
        </p>
        <?php 

        $hideimage  = get_post_meta( $object->ID, 'topevent_hideimage', true ); 
        if (!isset($hideimage)) {
            $hideimage = get_theme_mod('topevent_hideimage');
        }

        fau_form_onoff('fauval_topevent_hideimage',$hideimage,__('Kein Symbolbild anzeigen.','fau'));	

        echo '<div class="uploader">';

        $image = '';
        $imagehtml = '';

        if (isset($topevent_image) && ($topevent_image>0)) {
            $image = wp_get_attachment_image_src($topevent_image, $defaultoptions['default_rwdimage_typname']);
            if (isset($image)) {
                $imagehtml = '<img class="image_show_topevent_image" src="'.$image[0].'" width="'.get_theme_mod('default_rwdimage_width').'" height="'.get_theme_mod('default_rwdimage_height').'" alt="">';
            }
        }

        echo '<div class="previewimage showimg_topevent_image">';
        if (!empty($imagehtml)) {  
            echo $imagehtml;
        } else {
            _e('Kein Bild ausgewählt.', 'fau');
        }
        echo "</div>\n"; ?>		

        <input type="hidden" name="fauval_topevent_image" id="fauval_topevent_image" value="<?php echo sanitize_key($topevent_image) ; ?>"/>
        <input class="button" name="image_button_topevent_image" id="image_button_topevent_image" value="<?php _e('Bild auswählen', 'fau'); ?>"/>
        <small><a href="#" class="image_remove_topevent_image"><?php _e( "Entfernen", 'fau' );?></a></small>
        <br/>
        <div class="howto">
            <?php echo __('Hier können Sie ein Thumbnail auswählen für den Event. Wenn kein Bild gewählt wird, wird ein Ersatzbild angezeigt.','fau'); ?>	      
        </div>
        <script>
        jQuery(document).ready(function() {
            jQuery('#image_button_topevent_image').click(function()  {
                wp.media.editor.send.attachment = function(props, attachment) {
                    jQuery('#fauval_topevent_image').val(attachment.id);
                    htmlshow = "<img src=\""+attachment.url + "\" width=\"<?php echo get_theme_mod('default_rwdimage_width');?>\" height=\"<?php echo get_theme_mod('default_rwdimage_height');?>\"/>";  					   
                    jQuery('.showimg_topevent_image').html(htmlshow);

                }
                wp.media.editor.open(this);
                return false;
            });
        });
        jQuery(document).ready(function() {
            jQuery('.image_remove_topevent_image').click(function()   {
                jQuery('#fauval_topevent_image').val('');
                jQuery('.showimg_topevent_image').html('<?php _e('Kein Bild ausgewählt.', 'fau'); ?>');
                return false;
            });
        });
       </script>
    </div>
    </div>
    <?php
}
 
 /* Save the meta box's page metadata. */
function fau_save_post_topevent($post_id, $post) {
    /* Verify the nonce before proceeding. */
    if (!isset($_POST['fau_metabox_post_topevent_nonce']) || !wp_verify_nonce($_POST['fau_metabox_post_topevent_nonce'], basename(__FILE__))) {
        return;
    }

    $post_type = get_post_type($post_id);
    
    if ('post' != $post_type || !current_user_can('edit_post', $post_id)) {
        return;
    }

    fau_save_standard('topevent_active', $_POST['fauval_topevent_active'], $post_id, 'post', 'int');
    fau_save_standard('topevent_hideimage', $_POST['fauval_topevent_hideimage'], $post_id, 'post', 'int');
    fau_save_standard('topevent_title', $_POST['fauval_topevent_title'], $post_id, 'post', 'text');
    fau_save_standard('topevent_description', $_POST['fauval_topevent_desc'], $post_id, 'post', 'text');
    fau_save_standard('topevent_date', $_POST['fauval_topevent_date'], $post_id, 'post', 'date');
    fau_save_standard('topevent_image', $_POST['fauval_topevent_image'], $post_id, 'post', 'int');
}

/* Display Options for menuquotes on posts */
function fau_do_metabox_post_untertitel($object, $box) {
    wp_nonce_field(basename(__FILE__), 'fau_metabox_post_untertitel_nonce');

    if (!current_user_can('edit_post', $object->ID)) {
        return;
    }

    $untertitel = get_post_meta($object->ID, 'fauval_untertitel', true);
    fau_form_text('fau_metabox_post_untertitel', $untertitel, __('Untertitel (Inhaltsüberschrift)', 'fau'), __('Dieser Untertitel erscheint im Inhaltsbereich, unterhalb des Balkens mit dem eigentlichen Titel.', 'fau'));
}

/* Save the meta box's post metadata. */
function fau_save_metabox_post_untertitel($post_id, $post) {
    /* Verify the nonce before proceeding. */
    if (!isset($_POST['fau_metabox_post_untertitel_nonce']) || !wp_verify_nonce($_POST['fau_metabox_post_untertitel_nonce'], basename(__FILE__))) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    $newval = isset($_POST['fau_metabox_post_untertitel']) ? sanitize_text_field($_POST['fau_metabox_post_untertitel']) : '';
    fau_save_standard('fauval_untertitel', $newval, $post_id, 'post', 'text');

}

/* Display Options for menuquotes on pages */
function fau_do_metabox_page_untertitel($object, $box) {
    wp_nonce_field(basename(__FILE__), 'fau_metabox_page_untertitel_nonce');

    if (!current_user_can('edit_page', $object->ID)) {
        // Oder sollten wir nach publish_pages  fragen? 
        // oder nach der Rolle? vgl. http://docs.appthemes.com/tutorials/wordpress-check-user-role-function/ 
        return;
    }

    $untertitel = get_post_meta($object->ID, 'headline', true);
    fau_form_text('fau_metabox_page_untertitel', $untertitel, __('Untertitel (Inhaltsüberschrift)', 'fau'), __('Dieser Untertitel erscheint im Inhaltsbereich, unterhalb des Balkens mit dem eigentlichen Titel.', 'fau'));
}

/* Save the meta box's post/page metadata. */
function fau_save_metabox_page_untertitel( $post_id, $post ) {
	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['fau_metabox_page_untertitel_nonce'] ) || !wp_verify_nonce( $_POST['fau_metabox_page_untertitel_nonce'], basename( __FILE__ ) ) ) {
            return $post_id;
        }

	$post_type = get_post_type($post_id);
        
	if ('page' != $post_type || !current_user_can('edit_page', $post_id)) {
            return;
	}

	$newval = isset($_POST['fau_metabox_page_untertitel']) ? sanitize_text_field($_POST['fau_metabox_page_untertitel']) : '';
	fau_save_standard('headline', $newval, $post_id, 'post', 'text');
	
	
}

/* Display Options for portalmenu unten on portal pages */
function fau_do_metabox_page_portalmenu($object, $box) {    
    wp_nonce_field(basename(__FILE__), 'fau_metabox_page_portalmenu_nonce');

    if (!current_user_can('edit_page', $object->ID)) {
        return;
    }

  
    $currentmenu = get_post_meta($object->ID, 'portalmenu-slug', true);
    $currentmenuid = 0;
    if ($currentmenu == sanitize_key($currentmenu)) {
        $currentmenuid = $currentmenu;
    } else {
        $thisterm = get_term_by('name', $currentmenu, 'nav_menu');
        if (!isset($thisterm)) {
            $thisterm = get_term_by('slug', $currentmenu, 'nav_menu');
        }
        if ($thisterm !== false) {
            $currentmenuid = $thisterm->term_id;
        }
    }

    $thislist = array();
    $menuliste = get_terms('nav_menu', array('orderby' => 'name', 'hide_empty' => true));
    foreach ($menuliste as $term) {
        $term_id = $term->term_id;
        $term_name = $term->name;
        $thislist[$term->term_id] = $term->name;
    }
    
    fau_form_select('fau_metabox_page_portalmenu_id', $thislist, $currentmenuid, __('Portalmenü', 'fau').' '.__('unten', 'fau'), __('Bei einer Portalseite wird unter dem Inhalt ein Menu ausgegeben. Bitte wählen Sie hier das Menü aus der Liste. Sollte das Menü noch nicht existieren, kann ein Administrator es anlegen.', 'fau'), 1, __('Kein Portalmenu zeigen', 'fau'));

    $nothumbnails = get_post_meta($object->ID, 'fauval_portalmenu_thumbnailson', true) ? 1 : 0;
    fau_form_onoff('fau_metabox_page_portalmenu_nothumbnails', $nothumbnails, __('Beitragsbilder', 'fau').' '.__('verbergen', 'fau'));

    $nofallbackthumbs = get_post_meta($object->ID, 'fauval_portalmenu_nofallbackthumb', true) ? 1 : 0;
    fau_form_onoff('fau_metabox_page_portalmenu_nofallbackthumb', $nofallbackthumbs, __('Kein Ersatzbild zeigen, wenn Beitragsbild nicht gesetzt ist.', 'fau'));

    $nosub = get_post_meta($object->ID, 'fauval_portalmenu_nosub', true) ? 1 : 0;
    fau_form_onoff('fau_metabox_page_portalmenu_nosub', $nosub, __('Unterpunkte verbergen.', 'fau'));

    $skewed = get_post_meta($object->ID, 'fauval_portalmenu_skewed', true) ? 1 : 0;
    fau_form_onoff('fau_metabox_page_portalmenu_skewed', $skewed, __('Beitragsbilder', 'fau').' '.__('anschrägen', 'fau'));  
    
    $portaltype = get_post_meta($object->ID, 'fauval_portalmenu_type', true);
    fau_form_select('fau_metabox_page_portalmenu_type', array(
        1 => __('Format', 'fau').' 2:1',
        2 => __('Format', 'fau').' 3:2',
        3 => __('Format', 'fau').' 3:4'), $portaltype, __('Bildformat', 'fau'), '', 1);
}

/* Save the meta box's page metadata. */
function fau_save_metabox_page_portalmenu($post_id, $post) {
    /* Verify the nonce before proceeding. */
    if (!isset($_POST['fau_metabox_page_portalmenu_nonce']) || !wp_verify_nonce($_POST['fau_metabox_page_portalmenu_nonce'], basename(__FILE__))) {
        return;
    }

    if ('page' != $_POST['post_type'] || !current_user_can('edit_page', $post_id)) {
        return;
    }

    $newval = isset($_POST['fau_metabox_page_portalmenu_id']) ? trim($_POST['fau_metabox_page_portalmenu_id']) : '';
    $oldval = get_post_meta($post_id, 'portalmenu-slug', true);

    if (!empty($newval) && !empty($oldval)) {
        update_post_meta($post_id, 'portalmenu-slug', $newval);
    } elseif (!empty($newval) && empty($oldval)) {
        add_post_meta($post_id, 'portalmenu-slug', $newval, true);
    } else {
        delete_post_meta($post_id, 'portalmenu-slug');
    }

    $newval = !empty($_POST['fau_metabox_page_portalmenu_nothumbnails']) ? 1 : 0;
    $oldval = get_post_meta($post_id, 'fauval_portalmenu_thumbnailson', true) ? 1 : 0;

    if ($newval && !empty($oldval)) {
        update_post_meta($post_id, 'fauval_portalmenu_thumbnailson', $newval);
    } elseif ($newval && empty($oldval)) {
        add_post_meta($post_id, 'fauval_portalmenu_thumbnailson', $newval, true);
    } else {
        delete_post_meta($post_id, 'fauval_portalmenu_thumbnailson');
    }

    $newval = !empty($_POST['fau_metabox_page_portalmenu_nofallbackthumb']) ? 1 : 0;
    $oldval = get_post_meta($post_id, 'fauval_portalmenu_nofallbackthumb', true) ? 1 : 0;

    if ($newval && !empty($oldval)) {
        update_post_meta($post_id, 'fauval_portalmenu_nofallbackthumb', $newval);
    } elseif ($newval && empty($oldval)) {
        add_post_meta($post_id, 'fauval_portalmenu_nofallbackthumb', $newval, true);
    } else {
        delete_post_meta($post_id, 'fauval_portalmenu_nofallbackthumb');
    }

    $newval = !empty($_POST['fau_metabox_page_portalmenu_nosub']) ? 1 : 0;
    $oldval = get_post_meta($post_id, 'fauval_portalmenu_nosub', true) ? 1 : 0;

    if ($newval && !empty($oldval)) {
        update_post_meta($post_id, 'fauval_portalmenu_nosub', $newval);
    } elseif ($newval && empty($oldval)) {
        add_post_meta($post_id, 'fauval_portalmenu_nosub', $newval, true);
    } else {
        delete_post_meta($post_id, 'fauval_portalmenu_nosub');
    }

    $newval = !empty($_POST['fau_metabox_page_portalmenu_skewed']) ? 1 : 0;
    $oldval = get_post_meta($post_id, 'fauval_portalmenu_skewed', true) ? 1 : 0;

    if ($newval && !empty($oldval)) {
        update_post_meta($post_id, 'fauval_portalmenu_skewed', $newval);
    } elseif ($newval && empty($oldval)) {
        add_post_meta($post_id, 'fauval_portalmenu_skewed', $newval, true);
    } else {
        delete_post_meta($post_id, 'fauval_portalmenu_skewed');
    }

   $newval = isset($_POST['fau_metabox_page_portalmenu_type']) ? absint($_POST['fau_metabox_page_portalmenu_type']) : 0;
    $oldval = get_post_meta($post_id, 'fauval_portalmenu_type', true);

    if ($newval && $oldval) {
        update_post_meta($post_id, 'fauval_portalmenu_type', $newval);
    } elseif ($newval && !$oldval) {
        add_post_meta($post_id, 'fauval_portalmenu_type', $newval, true);
    } else {
        delete_post_meta($post_id, 'fauval_portalmenu_type');
    }
}




/* Display Options for portalmenu oben on portal pages */
function fau_do_metabox_page_portalmenu_oben($object, $box) {    
    wp_nonce_field(basename(__FILE__), 'fau_metabox_page_portalmenu_oben_nonce');

    if (!current_user_can('edit_page', $object->ID)) {
        return;
    }

  
    $currentmenu = get_post_meta($object->ID, 'portalmenu-slug_oben', true);
    $currentmenuid = 0;
    if ($currentmenu == sanitize_key($currentmenu)) {
        $currentmenuid = $currentmenu;
    } else {
        $thisterm = get_term_by('name', $currentmenu, 'nav_menu');
        if (!isset($thisterm)) {
            $thisterm = get_term_by('slug', $currentmenu, 'nav_menu');
        }
        if ($thisterm !== false) {
            $currentmenuid = $thisterm->term_id;
        }
    }

    $thislist = array();
    $menuliste = get_terms('nav_menu', array('orderby' => 'name', 'hide_empty' => true));
    foreach ($menuliste as $term) {
        $term_id = $term->term_id;
        $term_name = $term->name;
        $thislist[$term->term_id] = $term->name;
    }
    
    fau_form_select('fau_metabox_page_portalmenu_id_oben', $thislist, $currentmenuid, __('Portalmenü', 'fau').' '.__('oben', 'fau'), '', 1, __('Kein Portalmenu zeigen', 'fau'));

    $nothumbnails = get_post_meta($object->ID, 'fauval_portalmenu_thumbnailson_oben', true) ? 1 : 0;
    fau_form_onoff('fau_metabox_page_portalmenu_nothumbnails_oben', $nothumbnails, __('Beitragsbilder', 'fau').' '.__('verbergen', 'fau'));

    $nofallbackthumbs = get_post_meta($object->ID, 'fauval_portalmenu_nofallbackthumb_oben', true) ? 1 : 0;
    fau_form_onoff('fau_metabox_page_portalmenu_nofallbackthumb_oben', $nofallbackthumbs, __('Keine Ersatzbild zeigen, wenn Beitragsbild nicht gesetzt ist.', 'fau'));

    $nosub = get_post_meta($object->ID, 'fauval_portalmenu_nosub_oben', true) ? 1 : 0;
    fau_form_onoff('fau_metabox_page_portalmenu_nosub_oben', $nosub, __('Unterpunkte verbergen.', 'fau'));

    $skewed = get_post_meta($object->ID, 'fauval_portalmenu_skewed_oben', true) ? 1 : 0;
    fau_form_onoff('fau_metabox_page_portalmenu_skewed_oben', $skewed, __('Beitragsbilder', 'fau').' '.__('anschrägen', 'fau'));  
    
    $portaltype = get_post_meta($object->ID, 'fauval_portalmenu_type_oben', true);
    fau_form_select('fau_metabox_page_portalmenu_type_oben', array(
        1 => __('Format', 'fau').' 2:1',
        2 => __('Format', 'fau').' 3:2',
        3 => __('Format', 'fau').' 3:4'), $portaltype, __('Bildformat', 'fau'), '', 1);
}
/* Save the meta box's page metadata portalmenu oben. */
function fau_save_metabox_page_portalmenu_oben($post_id, $post) {
    /* Verify the nonce before proceeding. */
    if (!isset($_POST['fau_metabox_page_portalmenu_oben_nonce']) || !wp_verify_nonce($_POST['fau_metabox_page_portalmenu_oben_nonce'], basename(__FILE__))) {
        return;
    }

    if ('page' != $_POST['post_type'] || !current_user_can('edit_page', $post_id)) {
        return;
    }

    $newval = isset($_POST['fau_metabox_page_portalmenu_id_oben']) ? trim($_POST['fau_metabox_page_portalmenu_id_oben']) : '';
    $oldval = get_post_meta($post_id, 'portalmenu-slug_oben', true);

    if (!empty($newval) && !empty($oldval)) {
        update_post_meta($post_id, 'portalmenu-slug_oben', $newval);
    } elseif (!empty($newval) && empty($oldval)) {
        add_post_meta($post_id, 'portalmenu-slug_oben', $newval, true);
    } else {
        delete_post_meta($post_id, 'portalmenu-slug_oben');
    }

    $newval = !empty($_POST['fau_metabox_page_portalmenu_nothumbnails_oben']) ? 1 : 0;
    $oldval = get_post_meta($post_id, 'fauval_portalmenu_thumbnailson_oben', true) ? 1 : 0;

    if ($newval && !empty($oldval)) {
        update_post_meta($post_id, 'fauval_portalmenu_thumbnailson_oben', $newval);
    } elseif ($newval && empty($oldval)) {
        add_post_meta($post_id, 'fauval_portalmenu_thumbnailson_oben', $newval, true);
    } else {
        delete_post_meta($post_id, 'fauval_portalmenu_thumbnailson_oben');
    }

    $newval = !empty($_POST['fau_metabox_page_portalmenu_nofallbackthumb_oben']) ? 1 : 0;
    $oldval = get_post_meta($post_id, 'fauval_portalmenu_nofallbackthumb_oben', true) ? 1 : 0;

    if ($newval && !empty($oldval)) {
        update_post_meta($post_id, 'fauval_portalmenu_nofallbackthumb_oben', $newval);
    } elseif ($newval && empty($oldval)) {
        add_post_meta($post_id, 'fauval_portalmenu_nofallbackthumb_oben', $newval, true);
    } else {
        delete_post_meta($post_id, 'fauval_portalmenu_nofallbackthumb_oben');
    }

    $newval = !empty($_POST['fau_metabox_page_portalmenu_nosub_oben']) ? 1 : 0;
    $oldval = get_post_meta($post_id, 'fauval_portalmenu_nosub_oben', true) ? 1 : 0;

    if ($newval && !empty($oldval)) {
        update_post_meta($post_id, 'fauval_portalmenu_nosub_oben', $newval);
    } elseif ($newval && empty($oldval)) {
        add_post_meta($post_id, 'fauval_portalmenu_nosub_oben', $newval, true);
    } else {
        delete_post_meta($post_id, 'fauval_portalmenu_nosub_oben');
    }

    $newval = !empty($_POST['fau_metabox_page_portalmenu_skewed_oben']) ? 1 : 0;
    $oldval = get_post_meta($post_id, 'fauval_portalmenu_skewed_oben', true) ? 1 : 0;

    if ($newval && !empty($oldval)) {
        update_post_meta($post_id, 'fauval_portalmenu_skewed_oben', $newval);
    } elseif ($newval && empty($oldval)) {
        add_post_meta($post_id, 'fauval_portalmenu_skewed_oben', $newval, true);
    } else {
        delete_post_meta($post_id, 'fauval_portalmenu_skewed_oben');
    }

   $newval = isset($_POST['fau_metabox_page_portalmenu_type_oben']) ? absint($_POST['fau_metabox_page_portalmenu_type_oben']) : 0;
    $oldval = get_post_meta($post_id, 'fauval_portalmenu_type_oben', true);

    if ($newval && $oldval) {
        update_post_meta($post_id, 'fauval_portalmenu_type_oben', $newval);
    } elseif ($newval && !$oldval) {
        add_post_meta($post_id, 'fauval_portalmenu_type_oben', $newval, true);
    } else {
        delete_post_meta($post_id, 'fauval_portalmenu_type_oben');
    }
}

/* 
 * Sidebar der Seiten  
 */

/* Display Options for menuquotes on pages */
function fau_do_metabox_page_sidebar($object, $box) {
    wp_nonce_field(basename(__FILE__), 'fau_metabox_page_sidebar_nonce');
    
    if (!current_user_can('edit_page', $object->ID)) {
        return;
    }
    
    if (get_theme_mod('advanced_page_sidebar_titleabove')) {
        $sidebar_title_above = get_post_meta($object->ID, 'sidebar_title_above', true);
        fau_form_text('sidebar_title_above', $sidebar_title_above, __('Titel oben', 'fau'), __('Titel am Anfang der Sidebar', 'fau'));
    }

    $sidebar_text_above = get_post_meta($object->ID, 'sidebar_text_above', true);
    if (get_theme_mod('advanced_page_sidebar_useeditor_textabove')) {
        fau_form_wpeditor('sidebar_text_above', $sidebar_text_above, __('Textbereich oben', 'fau'), __('Text am Anfang der Sidebar', 'fau'), true);
    } else {
        fau_form_textarea('sidebar_text_above', $sidebar_text_above, __('Textbereich oben', 'fau'), $cols = 50, $rows = 5, __('Text am Anfang der Sidebar', 'fau'));
    }
    if ((get_theme_mod('advanced_page_sidebar_linkblock1_number') > 0) || (get_theme_mod('advanced_page_sidebar_linkblock2_number') > 0)) {
        // Frage nach Reihenfolge Linklisten vs Personen

        $fauval_sidebar_order_personlinks = get_post_meta($object->ID, 'fauval_sidebar_order_personlinks', true);
        if (!isset($fauval_sidebar_order_personlinks)) {
            $fauval_sidebar_order_personlinks = get_theme_mod('advanced_page_sidebar_order_personlinks');
        }
        fau_form_select('fauval_sidebar_order_personlinks', array(0 => __('Zuerst Kontake, dann Linklisten', 'fau'), 1 => __('Zuerst Linklisten, dann Kontakte', 'fau')), $fauval_sidebar_order_personlinks, __('Reihenfolge Kontakte und Linklisten', 'fau'), __('Hier kann die Reihenfolge von Kontakte und Linklisten geändert werden, wie sie auf der Seite präsentiert werden.', 'fau'), 0);
    }

    
    if ( is_plugin_active( 'fau-person/fau-person.php' ) ) {
	
	$listpersonen = FAU_Person\Data::get_contact_list();
	$auswahl = array('-1' => __('Keine (Deaktivieren)', 'fau'));
	    $found = 0;
	    foreach ($listpersonen as $id => $val) {
		$title = $val;
		if ((empty($title))) {
		    $title = get_the_title($id);
		}
		$auswahl[$id] = $title;
		$found = 1;
	    }
	    wp_reset_postdata();
	    if ($found == 1) {
		$sidebar_personen = get_post_meta($object->ID, 'sidebar_personen', true);
		$sidebar_title_personen = get_post_meta($object->ID, 'sidebar_title_personen', true);
		fau_form_text('sidebar_title_personen', $sidebar_title_personen, get_theme_mod('advanced_page_sidebar_personen_title'), __('Titel über Ansprechpartner', 'fau'));
		fau_form_multiselect('sidebar_personen', $auswahl, $sidebar_personen, __('Auswahl Ansprechpartner', 'fau'), __('Wählen Sie die Personen oder Ansprechpartner, die in der Sidebar erscheinen sollen. Es kann mehr als ein Eintrag gewählt werden.', 'fau'), 0);

	    } else {
		echo __('Derzeit sind noch Personen oder Kontakte eingetragen, die man verlinken könnte.', 'fau');
	    }
    }
   
    if (get_theme_mod('advanced_page_sidebar_linkblock1_number') > 0) {
        $block_title = get_post_meta($object->ID, 'fauval_sidebar_title_linkblock1', true);

        fau_form_text('fauval_sidebar_title_linkblock1', $block_title, __('Titel erster Linkblock', 'fau'), __('Titel über die erste Liste von Links, sogenannte Quicklinks', 'fau'));
        for ($i = 1; $i <= get_theme_mod('advanced_page_sidebar_linkblock1_number'); $i++) {
            $name = 'fauval_linkblock1_link' . $i;
            $title = __('Link Nr. ', 'fau') . $i;
            $urlname = $name . '_url';
            $titlename = $name . '_title';

            $oldpageid = get_post_meta($object->ID, $name, true);
            $oldurl = get_post_meta($object->ID, $urlname, true);
            $oldtitle = get_post_meta($object->ID, $titlename, true);
            fau_form_link($name, $oldtitle, $oldurl, $title);
        }
    }

    if (get_theme_mod('advanced_page_sidebar_linkblock2_number') > 0) {

        $block_title = get_post_meta($object->ID, 'fauval_sidebar_title_linkblock2', true);
        // Default erstmal auskommentiert wenn man es leer haben will; irritiert sonst
        // if (isset($block_title) && strlen(trim($block_title))<1) {
        //$block_title = $options['advanced_page_sidebar_linkblock2_title'];	
        //}
        fau_form_text('fauval_sidebar_title_linkblock2', $block_title, __('Titel zweiter Linkblock', 'fau'), __('Titel über die zweite Liste von Links. Weitere Links oder bspw. externe Links.', 'fau'));

        for ($i = 1; $i <= get_theme_mod('advanced_page_sidebar_linkblock2_number'); $i++) {
            $name = 'fauval_linkblock2_link' . $i;
            $title = __('Link Nr. ', 'fau') . $i;
            $urlname = $name . '_url';
            $titlename = $name . '_title';

            $oldpageid = get_post_meta($object->ID, $name, true);
            $oldurl = get_post_meta($object->ID, $urlname, true);
            $oldtitle = get_post_meta($object->ID, $titlename, true);

            fau_form_link($name, $oldtitle, $oldurl, $title);
        }
    }

    if (get_theme_mod('advanced_page_sidebar_titlebelow')) {
        $sidebar_title_below = get_post_meta($object->ID, 'sidebar_title_below', true);
        fau_form_text('sidebar_title_below', $sidebar_title_below, __('Titel unten', 'fau'), __('Titel am Ende der Sidebar', 'fau'));
    }

    $sidebar_text_below = get_post_meta($object->ID, 'sidebar_text_below', true);
    if (get_theme_mod('advanced_page_sidebar_useeditor_textbelow')) {
        fau_form_wpeditor('sidebar_text_below', $sidebar_text_below, __('Textbereich unten', 'fau'), __('Text am Ende der Sidebar', 'fau'), true);
    } else {
        fau_form_textarea('sidebar_text_below', $sidebar_text_below, __('Textbereich unten', 'fau'), $cols = 50, $rows = 5, __('Text am Ende der Sidebar', 'fau'));
    }

    return;
}

/* Save the meta box's page metadata. */
function fau_save_metabox_page_sidebar($post_id, $post) {
    if (!isset($_POST['fau_metabox_page_sidebar_nonce']) || !wp_verify_nonce($_POST['fau_metabox_page_sidebar_nonce'], basename(__FILE__))) {
        return $post_id;
    }

    $post_type = get_post_type($post_id);

    if ('page' != $post_type || !current_user_can('edit_page', $post_id)) {
        return;
    }

    fau_save_standard('sidebar_title_above', $_POST['sidebar_title_above'], $post_id, 'page', 'text');

    if (get_theme_mod('advanced_page_sidebar_useeditor_textabove') == false) {
        fau_save_standard('sidebar_text_above', $_POST['sidebar_text_above'], $post_id, 'page', 'textnohtml');
    } else {
        fau_save_standard('sidebar_text_above', $_POST['sidebar_text_above'], $post_id, 'page', 'wpeditor');
    }

    fau_save_standard('sidebar_title_below', $_POST['sidebar_title_below'], $post_id, 'page', 'text');

    if (get_theme_mod('advanced_page_sidebar_useeditor_textbelow') == false) {
        fau_save_standard('sidebar_text_below', $_POST['sidebar_text_below'], $post_id, 'page', 'textnohtml');
    } else {
        fau_save_standard('sidebar_text_below', $_POST['sidebar_text_below'], $post_id, 'page', 'wpeditor');
    }

    $newval = isset($_POST['fauval_sidebar_order_personlinks']) ? absint($_POST['fauval_sidebar_order_personlinks']) : 0;
    $oldval = get_post_meta($post_id, 'fauval_sidebar_order_personlinks', true);

    if ($newval && $oldval) {
        update_post_meta($post_id, 'fauval_sidebar_order_personlinks', $newval);
    } elseif ($newval && !$oldval) {
        add_post_meta($post_id, 'fauval_sidebar_order_personlinks', $newval, true);
    } else {
        delete_post_meta($post_id, 'fauval_sidebar_order_personlinks');
    }

    $newval = isset($_POST['sidebar_title_personen']) ? sanitize_text_field($_POST['sidebar_title_personen']) : '';
    $oldval = get_post_meta($post_id, 'sidebar_title_personen', true);

    if (!empty($newval) && !empty($oldval)) {
        update_post_meta($post_id, 'sidebar_title_personen', $newval);
    } elseif (!empty($newval) && empty($oldval)) {
        add_post_meta($post_id, 'sidebar_title_personen', $newval, true);
    } else {
        delete_post_meta($post_id, 'sidebar_title_personen');
    }

    $newval = isset($_POST['sidebar_personen']) ? (array) $_POST['sidebar_personen'] : [];
    $oldval = get_post_meta($post_id, 'sidebar_personen', true);
    $remove = 0;
    $found = 0;

    foreach ($newval as $i) {
        if ($i == -1) {
            $remove = 1;
        } elseif ($i > 0) {
            $found = 1;
        }
    }

    if (($remove == 1) || ($found == 0)) {
        delete_post_meta($post_id, 'sidebar_personen');
    } else {
        if (!empty($oldval)) {
            update_post_meta($post_id, 'sidebar_personen', $newval);
        } else {
            add_post_meta($post_id, 'sidebar_personen', $newval, true);
        }
    }

    $newval = isset($_POST['fauval_sidebar_title_linkblock1']) ? sanitize_text_field($_POST['fauval_sidebar_title_linkblock1']) : '';
    $oldval = get_post_meta($post_id, 'fauval_sidebar_title_linkblock1', true);

    if (!empty($newval) && !empty($oldval)) {
        update_post_meta($post_id, 'fauval_sidebar_title_linkblock1', $newval);
    } elseif (!empty($newval) && empty($oldval)) {
        add_post_meta($post_id, 'fauval_sidebar_title_linkblock1', $newval, true);
    } else {
        delete_post_meta($post_id, 'fauval_sidebar_title_linkblock1');
    }

    $newval = isset($_POST['fauval_sidebar_title_linkblock2']) ? sanitize_text_field($_POST['fauval_sidebar_title_linkblock2']) : '';
    $oldval = get_post_meta($post_id, 'fauval_sidebar_title_linkblock2', true);

    if (!empty($newval) && !empty($oldval)) {
        update_post_meta($post_id, 'fauval_sidebar_title_linkblock2', $newval);
    } elseif (!empty($newval) && empty($oldval)) {
        add_post_meta($post_id, 'fauval_sidebar_title_linkblock2', $newval, true);
    } else {
        delete_post_meta($post_id, 'fauval_sidebar_title_linkblock2');
    }

    for ($i = 1; $i <= get_theme_mod('advanced_page_sidebar_linkblock1_number'); $i++) {
        $name = 'fauval_linkblock1_link' . $i;
        $urlname = $name . '_url';
        $titlename = $name . '_title';
        $oldpageid = get_post_meta($post_id, $name, true);
        $oldurl = get_post_meta($post_id, $urlname, true);
        $oldtitle = get_post_meta($post_id, $titlename, true);
        $c = $i - 1;


        $newurl = ( isset($_POST[$urlname]) ? esc_url($_POST[$urlname]) : 0 );
        $newid = ( isset($_POST[$name]) ? sanitize_key($_POST[$name]) : 0 );
        $newtitle = ( isset($_POST[$titlename]) ? sanitize_text_field($_POST[$titlename]) : 0 );

        if (!isset($newid) || ($newid <= 0)) {
            // Versuche aus der URL die ID zu ermitteln
            $relativeurl = fau_make_link_relative($newurl);
            if ($relativeurl != $newurl) {
                // Ist eine interne URL, also mnuss es eine ID geben			
                $newid = url_to_postid($newurl);
            }
        }

        update_post_meta($post_id, $urlname, $newurl);
        update_post_meta($post_id, $titlename, $newtitle);
        update_post_meta($post_id, $name, $newid);
    }


    for ($i = 1; $i <= get_theme_mod('advanced_page_sidebar_linkblock2_number'); $i++) {
        $name = 'fauval_linkblock2_link' . $i;
        $urlname = $name . '_url';
        $titlename = $name . '_title';
        $oldpageid = get_post_meta($post_id, $name, true);
        $oldurl = get_post_meta($post_id, $urlname, true);
        $oldtitle = get_post_meta($post_id, $titlename, true);
        $c = $i - 1;

        $newurl = ( isset($_POST[$urlname]) ? esc_url($_POST[$urlname]) : 0 );
        $newid = ( isset($_POST[$name]) ? sanitize_key($_POST[$name]) : 0 );
        $newtitle = ( isset($_POST[$titlename]) ? sanitize_text_field($_POST[$titlename]) : 0 );

        if (!isset($newid) || ($newid <= 0)) {
            // Versuche aus der URL die ID zu ermitteln
            $relativeurl = fau_make_link_relative($newurl);
            if ($relativeurl != $newurl) {
                // Ist eine interne URL, also mnuss es eine ID geben			
                $newid = url_to_postid($newurl);
            }
        }

        update_post_meta($post_id, $urlname, $newurl);
        update_post_meta($post_id, $titlename, $newtitle);
        update_post_meta($post_id, $name, $newid);
    }
}

/*-----------------------------------------------------------------------------------*/
/*  Metabox fuer optionalen Sprachcode auf Seiten
/*-----------------------------------------------------------------------------------*/
function fau_do_metabox_page_langcode($object, $box) {
    wp_nonce_field(basename(__FILE__), 'fau_metabox_page_langcode_nonce');

    if (!current_user_can('edit_page', $object->ID)) {
        return;
    }

    $prevalue = get_post_meta($object->ID, 'fauval_langcode', true);
    global $default_fau_page_langcodes;
    
    $liste = $default_fau_page_langcodes;
    $sitelang = fau_get_language_main();
    unset($liste[$sitelang]);
    
    
    $labeltext = __('Sprache im Inhaltsbereich deklarieren','fau');
    $howtotext = __('Falls die Sprache dieser Seite von anderen Webseiten des Webauftritts abweicht, geben Sie hier bitte die Sprache an, welche verwendet wird. Wenn die Sprache nicht geändert wird, ändern Sie nichts.</p><p class="hinweis">Achtung: Diese Funktion wird vom Workflow-Plugin nicht berücksichtigt.','fau');
    fau_form_select('fau_metabox_page_langcode', $liste, $prevalue, $labeltext,  $howtotext);
    
    
}

/*-----------------------------------------------------------------------------------*/
/*  Metabox fuer optionalen Sprachcode auf Seiten
/*-----------------------------------------------------------------------------------*/
function fau_do_metabox_page_additional_attributes($object, $box) {
    wp_nonce_field(basename(__FILE__), 'fau_metabox_page_additional_attributes_nonce');

    if (!current_user_can('edit_page', $object->ID)) {
        return;
    }

    $only_subnavi = __('Dies gilt nur für Seiten vom Template "Inhaltsseite mit Navi".','fau');
    
    if (get_theme_mod('advanced_activate_page_langcode') == true) {
	$prevalue = get_post_meta($object->ID, 'fauval_langcode', true);
	global $default_fau_page_langcodes;

	$liste = $default_fau_page_langcodes;
	$sitelang = fau_get_language_main();
	unset($liste[$sitelang]);


	$labeltext = __('Sprache im Inhaltsbereich deklarieren','fau');
	$howtotext = __('Falls die Sprache dieser Seite von anderen Webseiten des Webauftritts abweicht, geben Sie hier bitte die Sprache an, welche verwendet wird. Wenn die Sprache nicht geändert wird, ändern Sie nichts.</p><p class="hinweis">Achtung: Diese Funktion wird vom Workflow-Plugin nicht berücksichtigt.','fau');
	fau_form_select('fau_metabox_page_langcode', $liste, $prevalue, $labeltext,  $howtotext);
	
	$prevalue = get_post_meta($object->ID, 'fauval_pagetitle_langcode', true);
	$labeltext = __('Sprache des Seitentitels umdefinieren','fau');
	$howtotext = __('Falls die Sprache der Seitentitels von der allgemeinen Sprache des Webauftritts abweicht, geben Sie hier bitte die Sprache an, welche verwendet wird. Wenn die Sprache nicht geändert wird, ändern Sie nichts.','fau');
	fau_form_select('fau_metabox_page_title_langcode', $liste, $prevalue, $labeltext,  $howtotext);
    }
    
    echo '<div class="ontemplate_page-subnav">';
     
     
    $ignoresubnavi = get_post_meta($object->ID, 'fauval_hide-in-subnav', true);
    $labeltext = __('Seite verbergen','fau');
    $howtotext = __('Diese Seite wird nicht im Navigationsmenü im Inhaltsbereich gezeigt.','fau').' '.$only_subnavi;
    fau_form_toggle('fau_metabox_page_hide-in-subnav', $ignoresubnavi, $labeltext, $howtotext);
    
    echo '</div>';
    
    $arialabel_subnav = get_post_meta($object->ID, 'fauval_aria-label', true);
    $labeltext = __('ARIA Label','fau');
    $howtotext = __('Wird der Seitentitel auch von einer oder mehreren anderen Seiten verwendet, ist es notwendig, diesen über ein ARIA-Label von den anderen Seiten zu unterscheiden. In diesem Label kann der Titel ergänzt werden um ein Kontext, der die Seite von anderen Seiten mit gleichen Namen unterscheidet.','fau');
    fau_form_text('fau_metabox_page_aria-label', $arialabel_subnav, $labeltext, $howtotext);
    
    if (get_theme_mod('website_type')==-1) {
	$menuebene = get_post_meta($object->ID, 'menu-level', true);
	global $default_fau_page_menuuebenen;
	
	$liste = $default_fau_page_menuuebenen;
	$labeltext = __('Menüebene','fau');
	$howtotext = __('Die Menüebene definiert bei Seiten bis zur welchen Ebene das Menu auf der linken Seite gezeigt wird.','fau').' '.$only_subnavi;
	echo '<div class="ontemplate_page-subnav">';
	fau_form_select('fau_metabox_page_menuebene', $liste, $menuebene, $labeltext,  $howtotext);
	echo '</div>';
    }
    
    
    $thislist = array();
    $categories = get_categories(array('type' => 'imagelink', 'taxonomy' => 'imagelinks_category', 'orderby' => 'name', 'order' => 'ASC', 'hide_empty' => 1));
    foreach ($categories as $category) {
        if (!is_wp_error($category)) {
            if ($category->count > 1) {
                $thislist[$category->cat_ID] = $category->name . ' (' . $category->count . ' ' . __('Bilder', 'fau') . ')';
            } else {
                $thislist[$category->cat_ID] = $category->name . ' (' . $category->count . ' ' . __('Bild', 'fau') . ')';
            }
        }
    }

    if (empty($thislist)) {
        // echo __('Es wurden noch keine Bilder als Logos definiert. Daher kann hier noch nichts ausgewählt werden.', 'fau');
        // Keine Meldung mehr
    } else {
	    echo '<div class="ontemplate_page-portal ontemplate_page-portalindex ontemplate_page-start ontemplate_page-start-sub">';
        $currentcat = get_post_meta($object->ID, 'fauval_imagelink_catid', true);
        fau_form_select(
            'fau_metabox_page_imagelinks_catid',
            $thislist,
            $currentcat,
            __('Bildlinks einblenden', 'fau'),
            __('Wählen Sie hier die Kategorie aus aus der Bildlinks (verlinkte Logos) verwendet werden sollen. Die Bilder aus der gewählten Kategorie werden dann am Ende der Seite angezeigt.', 'fau'),
            1,
            __('Keine Logos zeigen', 'fau'));
        $currentsize = get_post_meta($object->ID, 'fauval_imagelink_size', true);
        $imagesizes = [
            'logo-thumb' => '140×110 Pixel',
            'page-thumb' => '220×110 Pixel',
            'post-thumbnails' => '300×150 Pixel',
            'thumbnail' => '150×150 Pixel',
        ];
        fau_form_select(
            'fau_metabox_page_imagelinks_size',
            $imagesizes,
            $currentsize,
            __('Größe der Bildlinks', 'fau'),
            '',
            1);
	    echo '</div>';
    }
   
    
    
}
/*-----------------------------------------------------------------------------------*/
/* Speichere optionalen Sprachcode auf Seiten
/*-----------------------------------------------------------------------------------*/
function fau_save_metabox_page_additional_attributes( $post_id, $post ) {
    /* Verify the nonce before proceeding. */
    if ( !isset( $_POST['fau_metabox_page_additional_attributes_nonce'] ) || !wp_verify_nonce( $_POST['fau_metabox_page_additional_attributes_nonce'], basename( __FILE__ ) ) ) {
	return $post_id;
    }

    $post_type = get_post_type($post_id);
    if ('page' != $post_type || !current_user_can('edit_page', $post_id)) {
	return;
    }
    if (get_theme_mod('advanced_activate_page_langcode') == true) {
	fau_save_standard('fauval_langcode', $_POST['fau_metabox_page_langcode'], $post_id, 'page', 'text');
	fau_save_standard('fauval_pagetitle_langcode', $_POST['fau_metabox_page_title_langcode'], $post_id, 'page', 'text');
    }

    if (get_theme_mod('website_type')==-1) {
	fau_save_standard('menu-level', $_POST['fau_metabox_page_menuebene'], $post_id, 'page', 'int');  
    }

    $newval = !empty($_POST['fau_metabox_page_hide-in-subnav']) ? 1 : 0;
    fau_save_standard('fauval_hide-in-subnav', $newval, $post_id, '', 'int');
    

    $newval = isset($_POST['fau_metabox_page_imagelinks_catid']) ? absint($_POST['fau_metabox_page_imagelinks_catid']) : 0;
    fau_save_standard('fauval_imagelink_catid', $newval, $post_id, 'post', 'int');

    $newval = isset($_POST['fau_metabox_page_imagelinks_size']) ? esc_attr($_POST['fau_metabox_page_imagelinks_size']) : '';
    fau_save_standard('fauval_imagelink_size', $newval, $post_id, 'post', 'text');

    $newval = isset($_POST['fau_metabox_page_aria-label']) ? sanitize_text_field($_POST['fau_metabox_page_aria-label']) : '';
    fau_save_standard('fauval_aria-label', $newval, $post_id, 'post', 'text');
    

}
/*-----------------------------------------------------------------------------------*/
/* Ersetzt das wpLink-Skript durch ein benutzerdefiniertes Skript.
/*-----------------------------------------------------------------------------------*/

add_action('admin_enqueue_scripts', function () {
    global $defaultoptions;
 
    // Erst deaktivieren wir das Standard-Wordpress-Skript
    wp_deregister_script('wplink');

    // Dann ersetzen wir es durch unser benutzerdefiniertes wpLink-Skript
    wp_enqueue_script('rrze-wplink', $defaultoptions['src-admin-wplinkjs'], array('jquery'), FALSE, TRUE);

    // Lokalisierung des Skripts
    $localized = array(
        'title' => __('Link einfügen/ändern', 'fau'),
        'update' => __('Aktualisieren', 'fau'),
        'save' => __('Link hinzufügen', 'fau'),
        'noTitle' => __('(kein Titel)', 'fau'),
        'noMatchesFound' => __('Keine Ergebnisse gefunden.', 'fau')
    );

    wp_localize_script('rrze-wplink', 'wpLinkL10n', $localized);
    
}, 999);
/*-----------------------------------------------------------------------------------*/
/* EOF
/*-----------------------------------------------------------------------------------*/