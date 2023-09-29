<?php

/* 
 * Search form for content: Errorpages or when someone could try a search
 */

$uri = esc_url($_SERVER['REQUEST_URI']);
$uri = str_replace('/', ' ', $uri);

global $defaultoptions;

$sidebarfilled = false;
 
$error_sidebar_replacesearch = get_theme_mod('advanced_error_sidebar_replacesearch');
if ( $error_sidebar_replacesearch && is_active_sidebar( $defaultoptions['advanced_error_sidebar_replacesearch_id'] ) ) { 
    $sidebarfilled = true;
}
?>

 <div class="error-search">
    <?php if ($sidebarfilled) { 
         dynamic_sidebar( $defaultoptions['advanced_error_sidebar_replacesearch_id'] ); 
    } else { ?>
    <div class="search-contenttry">        
        <p><?php _e('Vielleicht hilft Ihnen die Suche:','fau'); ?></p>
        <?php get_template_part('template-parts/search', 'form'); ?>
    </div>
    <?php } ?>
</div>

