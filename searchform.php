<?php
/**
* The template for the search form.
*
* @package WordPress
* @subpackage FAU
* @since FAU 1.0
*/
?>

<form method="get" class="searchform" action="<?php echo fau_esc_url(home_url( '/' ))?>">
    <h2 class="screen-reader-text"><?php echo get_theme_mod('title_hero_search'); ?></h2>
	<div class="search-text">
	    <label for="s"><?php _e('Geben Sie hier den Suchbegriff ein, um in diesem Webauftritt zu suchen:','fau'); ?></label>
	    <span class="searchicon"> </span>
	    <input type="text" value="<?php the_search_query(); ?>" name="s" placeholder="<?php _e('Suchen nach...','fau'); ?>">
	    <input type="submit" id="searchsubmit" value="<?php _e('Finden','fau'); ?>">
	</div>
	<?php 
	
	if (get_theme_mod('search_allowfilter')) {
	    
	    if (is_single() || is_category() || is_tag() || is_tax() ) {
		// Only Posts please :)
		echo '<input type="hidden" name="post_type[]" value="post">'."\n";		
	    } else {
		$listtypes = get_theme_mod('search_post_types');
		$autosearch_types =  get_theme_mod('search_post_types_checked');
		foreach ($listtypes as $type) {
		   if (in_array($type, $autosearch_types)) { 
		    echo '<input type="hidden" name="post_type[]" value="'.$type.'">'."\n";				   
		   }
		}
	    }
	    
	    
	}
	?>
	
</form>