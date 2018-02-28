<?php
/**
* The template for the search form in header
*
* @package WordPress
* @subpackage FAU
* @since FAU 1.0
*/
?>
<form id="search-header" role="search" method="get" class="searchform" action="<?php echo fau_esc_url(home_url( '/' ))?>">
    <h2 class="screen-reader-text"><?php echo get_theme_mod('title_hero_search'); ?></h2>
	<label for="headsearchinput"><?php _e('Geben Sie hier den Suchbegriff ein, um in diesem Webauftritt zu suchen:','fau'); ?></label>
	<span class="searchicon"> </span>
	<input id="headsearchinput" type="text" value="<?php the_search_query(); ?>" name="s" placeholder="<?php _e('Suchen nach...','fau'); ?>">
	<?php 
	if (get_theme_mod('search_allowfilter')) {
	    $autosearch_types =  get_theme_mod('search_post_types_checked');
	    $listtypes = fau_get_searchable_fields();	    
	    if (is_array($listtypes)) { 
		foreach ($listtypes as $type) {
		    if (in_array($type, $autosearch_types)) { 
			echo '<input type="hidden" name="post_type[]" value="'.$type.'">'."\n";		
		    }
		}
	    }
	}
	?>
	<input type="submit" id="searchsubmit" value="<?php _e('Finden','fau'); ?>">
</form>

