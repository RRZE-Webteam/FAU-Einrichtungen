<?php

/* 
 * Search form for content: Errorpages or when someone could try a search
 */

$uri = esc_url($_SERVER['REQUEST_URI']);
$uri = str_replace('/', ' ', $uri);

?>


<div class="search-contenttry">
    <p><?php _e('Vielleicht hilft Ihnen die Suche:','fau'); ?></p>
    <form method="get" action="<?php echo home_url( '/' )?>">
	<div class="search-text">
	    <label for="suchmaske-try"><?php _e('Geben Sie hier den Suchbegriff ein, um in diesem Webauftritt zu suchen:','fau'); ?></label>
	    <span class="searchicon"> </span>
	    <input id="suchmaske-try" type="text" value="<?php echo $uri ?>" name="s" placeholder="<?php _e('Suchen nach...','fau'); ?>">
	    <input type="submit" value="<?php _e('Finden','fau'); ?>">
	</div>
	<?php
	if (get_theme_mod('search_allowfilter')) {
	    echo '<div class="filter">';
	    $query_types = get_query_var('post_type');
	    $listtypes = get_theme_mod('search_post_types');
	     $allowed_types = get_post_types(array('public' => true, 'exclude_from_search' => false));

	    foreach ($listtypes as $type) {                                                
		if( in_array( $type, $allowed_types ) ) {
		    $typeinfo = get_post_type_object( $type );
		    $typestr = $typeinfo->labels->name; 	  
		    if ($type == 'attachment') {
						    $typestr = __('Dokumente und Bilder', 'fau');
						}
		    echo '<div class="nowrap"><input type="checkbox" name="post_type[]" id="label-'.$type.'" value="'.$type.'" checked="checked">';
		    echo '<label for="label-'.$type.'">'.$typestr.'</label></div>';
		}
	    }
	    echo "</div>\n";
	}
	?>

    </form>
</div>