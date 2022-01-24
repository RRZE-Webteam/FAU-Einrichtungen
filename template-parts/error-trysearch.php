<?php

/* 
 * Search form for content: Errorpages or when someone could try a search
 */

$uri = esc_url($_SERVER['REQUEST_URI']);
$uri = str_replace('/', ' ', $uri);

?>

 <div class="error-search">
    <div class="search-contenttry">
	<p><?php _e('Vielleicht hilft Ihnen die Suche:','fau'); ?></p>
	<?php get_template_part('template-parts/search', 'form'); ?>
    </div>
</div>

