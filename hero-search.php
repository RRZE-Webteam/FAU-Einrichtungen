<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage FAU
 * @since FAU 1.0
 */

global $options;
?>

<section id="hero" class="hero-small">
	<div class="container">
		<div class="row">
			<div class="span8">
			    <?php fau_breadcrumb($options['title_hero_search']); ?>
			</div>
		</div>
		<div class="row">
			<div class="span3">
			    <h1><?php echo $options['title_hero_search']; ?></h1>
			</div>
			<div class="span9">
			    <label class="unsichtbar" for="suchmaske-hero"><?php _e('Geben Sie hier den Suchbegriff ein','fau'); ?></label>
				<form role="search" method="get" class="searchform" action="<?php echo home_url( '/' )?>">
					<input id="suchmaske-hero" type="text" value="<?php the_search_query(); ?>" name="s" placeholder="<?php _e('Suchen nach...','fau'); ?>">
					<input type="submit" value="<?php _e('Finden','fau'); ?>">
					<?php
					if ($options['search_allowfilter']) {
					    echo '<div class="filter">';
					    $query_types = get_query_var('post_type');
					    $listtypes = $options['search_post_types'];
					    foreach ($listtypes as $type) {
						$typeinfo = get_post_type_object( $type );
						$typestr = $typeinfo->labels->name; 	    
						echo '<div class="nowrap"><input type="checkbox" name="post_type[]" id="label-'.$type.'" value="'.$type.'"';
						if (in_array($type, $query_types)) { echo ' checked="checked"'; }
						echo ">";			
						echo '<label for="label-'.$type.'">'.$typestr.'</label></div>';
					    }
					    echo "</div>\n";
					}
					?>
					
				</form>
				
			</div>
		</div>
	</div>
</section>
