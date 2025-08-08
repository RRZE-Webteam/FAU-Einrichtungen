<?php
/**
 * The template partial for displaying the sidebar
 *
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

    global $defaultoptions;

    $sidebarfilled =0;
    $titleup = get_post_meta( $post->ID, 'sidebar_title_above', true );
    $textup = get_post_meta( $post->ID, 'sidebar_text_above', true );
    $titledown = get_post_meta( $post->ID, 'sidebar_title_below', true );
    $textdown = get_post_meta( $post->ID, 'sidebar_text_below', true );
    $page_sidebar       = get_theme_mod('advanced_page_sidebar_wpsidebar');
    $page_sidebar_pos   = get_theme_mod('advanced_page_sidebar_wpsidebar_position');
    
    
    if (get_theme_mod('advanced_page_sidebar_display_in_pagesetting') == false) {
         fau_use_sidebar(false);
    } else {

        if (empty($page_sidebar_pos)) {
            $page_sidebar_pos = 'top';
        }


        if ( $page_sidebar && is_active_sidebar( $defaultoptions['advanced_page_sidebar_wpsidebar_id'] ) ) { 
            $sidebarfilled = true;
        }


        if ($titleup || $titledown || $textup || $textdown) {
            $sidebarfilled =1;
        } else {
            $foundlink = 0;   
            $linkblock1_number = get_theme_mod('advanced_page_sidebar_linkblock1_number');
            if ($linkblock1_number > 0) {	
                for ($i = 1; $i <= $linkblock1_number; $i++) {	
                    $name = 'fauval_linkblock1_link'.$i;
                    $urlname= $name.'_url';
                    $oldurl =  get_post_meta( $post->ID, $urlname, true );
                    $oldid =  get_post_meta( $post->ID, $name, true );
                    if ($oldid || !empty($oldurl)) {
                        $foundlink = 1;    
                    }
                }
            }
            if ($foundlink) {
                $sidebarfilled =2;
            } else {
                $linkblock2_number = get_theme_mod('advanced_page_sidebar_linkblock2_number');
                if ($linkblock2_number > 0) {	
                    for ($i = 1; $i <= $linkblock2_number; $i++) {	
                        $name = 'fauval_linkblock2_link'.$i;
                        $urlname= $name.'_url';
                        $oldurl =  get_post_meta( $post->ID, $urlname, true );
                        $oldid =  get_post_meta( $post->ID, $name, true );
                        if ($oldid || !empty($oldurl)) {
                            $foundlink = 1;    
                        }
                    }
                }

                if ($foundlink) {
                    $sidebarfilled =3;
                } else {
                    $sidebar_personen = get_post_meta( $post->ID, 'sidebar_personen', true );
                    if ($sidebar_personen) {
                        $sidebarfilled =4;	
                    }
                }
            }

        }


        if ($sidebarfilled>0) { 
            fau_use_sidebar(true);
        ?>
        <aside class="portalpage-sidebar" aria-label="<?php echo __('Sidebar','fau');?>">
        <?php
        if (  is_active_sidebar( $defaultoptions['advanced_page_sidebar_wpsidebar_id'] ) && $page_sidebar_pos == 'top' ) { 
            dynamic_sidebar( $defaultoptions['advanced_page_sidebar_wpsidebar_id'] ); 
        }

        get_template_part('template-parts/sidebar', 'events'); 	
        get_template_part('template-parts/sidebar', 'textabove');  

        $order = get_post_meta($post->ID, 'fauval_sidebar_order_personlinks', true );

        if ($order==1) {
            get_template_part('template-parts/sidebar', 'quicklinks');
            get_template_part('template-parts/sidebar', 'personen');
        } else {
            get_template_part('template-parts/sidebar', 'personen');
            get_template_part('template-parts/sidebar', 'quicklinks');
        }

        get_template_part('template-parts/sidebar', 'textbelow'); 

        if (  is_active_sidebar( $defaultoptions['advanced_page_sidebar_wpsidebar_id'] ) && $page_sidebar_pos == 'bottom' ) { 
            dynamic_sidebar( $defaultoptions['advanced_page_sidebar_wpsidebar_id'] ); 
        }	


        ?>
        </aside>
        <?php }	
    } ?>



