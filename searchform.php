<?php
/**
* The template for the search form.
*
* @package WordPress
* @subpackage FAU
* @since FAU 1.0
*/
?>

<form role="search" method="get" class="searchform" action="<?php echo fau_esc_url(home_url( '/' ))?>">
	<label for="s"><?php _e('Suchen nach...','fau'); ?></label>
	<input type="text" value="<?php the_search_query(); ?>" name="s" placeholder="<?php _e('Suchen nach...','fau'); ?>">
	<input type="submit" id="searchsubmit" value="<?php _e('Finden','fau'); ?>">
</form>