<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.7
 */
?>


	<form method="get" class="searchform-content" action="<?php echo esc_url(home_url( '/' ))?>">
	    <div class="search-text">
		<label for="suchmaske-content"><?php _e('Geben Sie hier den Suchbegriff ein, um in diesem Webauftritt zu suchen:','fau'); ?></label>
		<span class="searchicon"> </span>
		<input id="suchmaske-content" type="text" value="<?php the_search_query(); ?>" name="s" placeholder="<?php _e('Suchen nach...','fau'); ?>">
		<input type="submit" enterkeyhint="search" value="<?php _e('Finden','fau'); ?>">
	    </div>
	    <?php
	    if (get_theme_mod('search_allowfilter')) {
		echo '<div class="filter">';


		$listtypes = fau_get_searchable_fields();	  
		$query_types = get_query_var('post_type',$listtypes);
		$allowed_types = get_post_types(array('public' => true, 'exclude_from_search' => false));
		if ((is_array($listtypes)) && (!empty($listtypes))) {
		    foreach ($listtypes as $type) {       
			if( in_array( $type, $allowed_types ) ) {
			    $typeinfo = get_post_type_object( $type );
			    $typestr = $typeinfo->labels->name; 	    

			    if ($type == 'attachment') {
				$typestr = __('Dokumente und Bilder', 'fau');
			    }

			    echo '<div class="'.$type.'"><input type="checkbox" name="post_type[]" id="label-'.$type.'" value="'.$type.'"';
			    if (is_array($query_types) && in_array($type, $query_types)) { echo ' checked="checked"'; }
			    echo ">";			
			    echo '<label for="label-'.$type.'">'.$typestr.'</label></div>';
			}
		    }
		}
		echo "</div>\n";
	    }
	    ?>
	</form>
			

