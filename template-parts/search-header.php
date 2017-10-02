<?php
/**
* The template for the search form in header
*
* @package WordPress
* @subpackage FAU
* @since FAU 1.0
*/
global $options;
?>

<form id="search-header" role="search" method="get" class="searchform" action="<?php echo fau_esc_url(home_url( '/' ))?>">
	<label for="s"><?php _e('Suchbegriff eingeben','fau'); ?></label>
	<span class="searchicon"> </span>
	<input type="text" value="<?php the_search_query(); ?>" name="s" placeholder="<?php _e('Suchen nach...','fau'); ?>">
	<?php 
	if ($options['search_allowfilter']) {
	    $autosearch_types =  $options['search_post_types_checked'];

	    $listtypes = $options['search_post_types'];
	    foreach ($listtypes as $type) {
		if (in_array($type, $autosearch_types)) { 
		    echo '<input type="hidden" name="post_type[]" value="'.$type.'">'."\n";		
		}
	    }
	}
	?>
	<input type="submit" id="searchsubmit" value="<?php _e('Finden','fau'); ?>">
</form>


	